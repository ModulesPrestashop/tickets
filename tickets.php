<?php
/**
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class Tickets extends Module
{
	protected $config_form = false;

	public function __construct()
	{
		$this->name = 'tickets';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'jorgevrgs';
		$this->need_instance = 0;
		$this->controllers = array('support');
		/**
		 * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
		 */
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Customer Thread');
		$this->description = $this->l('Extend features of customer thread by including ticket history in front-office');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		$this->ticket_status = array(
			'open' => $this->l('Open'),
			'pending1' => $this->l('Pending internal'),
			'pending2' => $this->l('Awating answer'),
			'closed' => $this->l('Closed')
		);
	}

	/**
	 * Don't forget to create update methods if needed:
	 * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
	 */
	public function install()
	{
		return parent::install() &&
			$this->registerHook('displayCustomerAccount') &&
			$this->registerHook('displayMyAccountBlock');
	}

	public function uninstall()
	{
		return parent::uninstall();
	}

	public function hookDisplayCustomerAccount()
	{
		return $this->display(__FILE__, 'customer-account.tpl');
	}

	public function hookDisplayMyAccountBlock()
	{
		/* Place your code here. */
	}
}
