<?php
/**
* 2015 Jorge Vargas
*
* NOTICE OF LICENSE
*
* This source file is subject to the End User License Agreement (EULA)
*
* See attachmente file LICENSE
*
* @author    Jorge Vargas <jorgevargaslarrota@hotmail.com>
* @copyright 2012-2015 Jorge Vargas
* @license   End User License Agreement (EULA)
* @package   freshdesk
* @version   1.0
*/

class ContactController extends ContactControllerCore
{

    public function initContent()
    {
        parent::initContent();

        $this->assignOrderList();

        $email = Tools::safeOutput(
            Tools::getValue(
                'from',
                ((isset($this->context->cookie) && isset($this->context->cookie->email)
                    && Validate::isEmail($this->context->cookie->email)) ? $this->context->cookie->email : '')
            )
        );
        $this->context->smarty->assign(array(
            'errors' => $this->errors,
            'email' => $email,
            'fileupload' => Configuration::get('PS_CUSTOMER_SERVICE_FILE_UPLOAD')
        ));

        if (($id_customer_thread = (int)Tools::getValue('id_customer_thread')) && $token = Tools::getValue('token')) {
            $customerThread = Db::getInstance()->getRow(
                'SELECT cm.* 
                FROM '._DB_PREFIX_.'customer_thread cm
                WHERE cm.id_customer_thread = '.(int)$id_customer_thread.' 
                AND cm.id_shop = '.(int)$this->context->shop->id.' 
                AND token = \''.pSQL($token).'\''
            );
            $this->context->smarty->assign('customerThread', $customerThread);
        }
        
        $this->context->smarty->assign(array(
            'contacts' => Contact::getContacts($this->context->language->id),
            'message' => html_entity_decode(Tools::getValue('message'))
        ));

        $customer_name = '';
        if (isset($this->context->customer) && $this->context->customer->isLogged()) {
            $customer_name = "{$this->context->customer->firstname} {$this->context->customer->lastname}";
        }

        $this->context->smarty->assign(array(
            'customerName' => pSQL(Tools::getValue('customerName', $customer_name))
        ));

        $this->setTemplate($this->getTemplate('contact-form.tpl'));
    }

    public function getTemplate($template)
    {
        $this->module = Module::getInstanceByName('tickets');
        if ($this->module->active) {
            if (Tools::file_exists_cache(_PS_THEME_DIR_.'modules/'.$this->module->name.'/'.$template)) {
                return _PS_THEME_DIR_.'modules/'.$this->module->name.'/'.$template;
            } elseif (Tools::file_exists_cache(
                _PS_THEME_DIR_.'modules/'.$this->module->name.'/views/templates/front/'.$template
            )) {
                return _PS_THEME_DIR_.'modules/'.$this->module->name.'/views/templates/front/'.$template;
            } elseif (Tools::file_exists_cache(
                _PS_MODULE_DIR_.$this->module->name.'/views/templates/front/'.$template
            )) {
                return _PS_MODULE_DIR_.$this->module->name.'/views/templates/front/'.$template;
            }
            return false;
        }
        else
            return _PS_THEME_DIR_.($template);
    }
}
