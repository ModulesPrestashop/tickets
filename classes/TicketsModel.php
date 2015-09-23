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

class TicketsModel extends ObjectModel
{
    public static function getCustomerThreadByIdCustomer($id_customer)
    {
        $sql = 'SELECT *
            FROM '._DB_PREFIX_.'customer_thread
            WHERE id_customer = '.(int)$id_customer;

        return Db::getInstance()->executeS($sql);
    }

    public static function getIdCustomerThreadByIdAndToken($id_customer_thread, $token)
    {
        $context = Context::getContext();
        return (int)Db::getInstance()->getValue(
            'SELECT cm.id_customer_thread FROM '._DB_PREFIX_.'customer_thread cm
                WHERE cm.id_customer_thread = '.(int)$id_customer_thread.'
            AND cm.id_shop = '.(int)$context->shop->id.' AND token = \''.pSQL($token).'\''
        );
    }

    public static function getLastMessage($id_customer_thread)
    {
        $context = Context::getContext();
        return Db::getInstance()->getValue(
            'SELECT cm.message FROM '._DB_PREFIX_.'customer_message cm
                LEFT JOIN '._DB_PREFIX_.'customer_thread cc on (cm.id_customer_thread = cc.id_customer_thread)
            WHERE cc.id_customer_thread = '.(int)$id_customer_thread.' AND cc.id_shop = '.(int)$context->shop->id.'
            ORDER BY cm.date_add DESC'
        );
    }
}
