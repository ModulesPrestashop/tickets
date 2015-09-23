<?php
/**
* 2014 Jorge Vargas
*
* NOTICE OF LICENSE
*
* This source file is subject to the End User License Agreement (EULA)
*
* See attachmente file LICENSE
*
* @author    Jorge Vargas <jorgevargaslarrota@hotmail.com>
* @copyright 2007-2014 Jorge Vargas
* @license   End User License Agreement (EULA)
* @package   freshdesk
* @version   1.0
*/

include_once (_PS_MODULE_DIR_.'tickets/classes/TicketsModel.php');

class TicketsSupportModuleFrontController extends ModuleFrontController
{
	public $ssl = true;
	public $display_column_left = false;
	public $display_column_right = false;
	public $bootstrap = true;
	public $errors;
	public $auth = true;

	public function initContent()
	{
		parent::initContent();

		// Contacts
		$contacts = Contact::getContacts($this->context->language->id);
		$contact_array = array();
		foreach ($contacts as $contact)
			$contact_array[$contact['id_contact']] = array('id_contact' => $contact['id_contact'], 'name' => $contact['name']);
		$contacts = $contact_array;

		// Messages
		if (Tools::getIsset('ticket') && Validate::isUnsignedInt(Tools::getValue('ticket')))
		{
			$id_ticket = (int)Tools::getValue('ticket');
			$thread = new CustomerThread($id_ticket);
			if (Validate::isLoadedObject($thread))
			{
				// Messages
				$messages = CustomerThread::getMessageCustomerThreads($id_ticket);
				foreach ($messages as $key => $mess)
				{	
					if ($mess['id_employee'])
					{
						$employee = new Employee($mess['id_employee']);
						$messages[$key]['employee_image'] = $employee->getImage();
						$current_employee = $employee;
					}
					if (isset($mess['file_name']) && $mess['file_name'] != '')
						$messages[$key]['file_name'] = _THEME_PROD_PIC_DIR_.$mess['file_name'];
					else
						unset($messages[$key]['file_name']);

					if ($mess['id_product'])
					{
						$product = new Product((int)$mess['id_product'], false, $this->context->language->id);
						if (Validate::isLoadedObject($product))
						{
							$messages[$key]['product_name'] = $product->name;
							$messages[$key]['product_link'] = $this->context->link->getProductLink((int)$product->id);
						}
					}
				}
				$first_message = $messages[0];
				if (!$messages[0]['id_employee'])
					unset($messages[0]);

				$contact = '';
				foreach ($contacts as $c)
					if ($c['id_contact'] == $thread->id_contact)
						$contact = $c['name'];

				$this->context->smarty->assign(array(
					'messages' => $messages,
					'customer' => $this->context->customer,
					'first_message' => $first_message,
					'current_employee' => isset($current_employee) ? $current_employee : '',
					'id_customer_thread' => $id_ticket,
					'reply_message' => count($this->errors) ? Tools::nl2br(Tools::stripslashes(Tools::getValue('reply_message'))) : '',
					'contact' => $contact,
					'thread' => $thread
				));
			}
		}

		// Tickets
		$tickets = TicketsModel::getCustomerThreadByIdCustomer((int)$this->context->customer->id);
		$total_tickets = count($tickets);
		if (is_array($tickets) && count($tickets))
		{
			foreach ($tickets as $key => $ticket)
			{
				$message_ct = CustomerThread::getMessageCustomerThreads($ticket['id_customer_thread']);
				$tickets[$key]['subject'] = $message_ct[0]['message'];
				$tickets[$key]['id_order'] = Order::getUniqReferenceOf((int)$ticket['id_order']);
			}
		}
		// Smarty
		$this->context->smarty->assign(array(
			'tickets' => $tickets,
			'tickets_total' => $total_tickets,
			'contacts' => $contacts,
			'statuses' => $this->module->ticket_status
		));

		$this->setTemplate('support.tpl');
	}

	public function postProcess()
	{
		if (!Tools::isSubmit('submitReply'))
			return;

		$this->postValidate();
		if (count($this->errors))
			return;

		$id_customer_thread = Tools::getValue('ticket');
		$email = pSQL(Tools::getValue('email'));
		$message = Tools::getValue('reply_message');

		// Customer Thread
		$ct = new CustomerThread($id_customer_thread);
		$ct->status = 'open';
		$ct->email = $email;
		$ct->update();
		// Customer Message
		$cm = new CustomerMessage();
		$cm->id_customer_thread = $ct->id;
		$cm->message = $message;
		$cm->ip_address = (int)ip2long(Tools::getRemoteAddr());
		$cm->user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (!$cm->add())
			$this->errors[] = $this->l('An error occurred while sending the message.');
	}

	protected function postValidate()
	{
		if (!($id_customer_thread = (int)Tools::getValue('ticket')) || !Validate::isUnsignedInt($id_customer_thread))
			$this->errors[] = $this->l('Invalid customer thread number');

		if (!($email = trim(Tools::getValue('email'))) || !Validate::isEmail($email))
			$this->errors[] = $this->l('Invalid email address.');

		if (!($message = Tools::getValue('reply_message')))
			$this->errors[] = $this->l('The message cannot be blank.');
		else if (!Validate::isCleanHtml($message))
			$this->errors[] = $this->l('Invalid message');
		else if ($message == TicketsModel::getLastMessage($id_customer_thread))
			$this->errors[] = $this->l('Message was alredy sent before');

		if (!($token = pSQL(Tools::getValue('token'))))
			$this->errors[] = $this->l('Token is missing');

		if ($id_customer_thread != TicketsModel::getIdCustomerThreadByIdAndToken($id_customer_thread, $token))
				$this->errors[] = $this->l('Token incorrect for this id customer thread');
	}

	protected function l($string)
	{
		return Translate::getModuleTranslation($this->module, $string, $this->module->name);
	}

	public function setMedia()
	{
		parent::setMedia();
		// Tickets history
		$this->addJqueryPlugin('footable');
		$this->addJqueryPlugin('footable-sort');
		$this->addJqueryPlugin('scrollTo');
		// Ticket details
		$this->addCSS(_PS_MODULE_DIR_.'tickets/views/css/tickets.css', 'all');
	}
}