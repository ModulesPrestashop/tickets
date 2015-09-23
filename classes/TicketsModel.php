<?php
/**
 * 2007-2015 PrestaShop
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2015 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
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
		return (int)Db::getInstance()->getValue('
			SELECT cm.id_customer_thread FROM '._DB_PREFIX_.'customer_thread cm
		WHERE cm.id_customer_thread = '.(int)$id_customer_thread.'
		AND cm.id_shop = '.(int)$context->shop->id.' AND token = \''.pSQL($token).'\'');
	}

	public static function getLastMessage($id_customer_thread)
	{
		$context = Context::getContext();
		return Db::getInstance()->getValue('
			SELECT cm.message FROM '._DB_PREFIX_.'customer_message cm
		LEFT JOIN '._DB_PREFIX_.'customer_thread cc on (cm.id_customer_thread = cc.id_customer_thread)
		WHERE cc.id_customer_thread = '.(int)$id_customer_thread.' AND cc.id_shop = '.(int)$context->shop->id.'
		ORDER BY cm.date_add DESC');
	}
}