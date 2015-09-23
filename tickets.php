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

if (!defined('_PS_VERSION_')) {
    exit;
}

class Tickets extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'tickets';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Jorge Vargas';
        $this->need_instance = 0;
        $this->controllers = array('support');
        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Tickets');
        $this->description = $this->l(
            'Extend features of customer thread by including ticket history in front-office'
        );
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
            $this->registerHook('displayCustomerAccount')
            && $this->registerHook('displayMyAccountBlock');
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
