{*
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
* @package   tickets
* @version   1.0
*}

{if !$message.id_employee}
	{assign var="type" value="customer"}
{else}
	{assign var="type" value="employee"}
{/if}	

<div class="message-item{if $initial}-initial-body{/if}">
{if !$initial}
	<div class="message-avatar">
		<div class="avatar-md">
			{if $type == 'customer'}
				<i class="icon-user icon-3x"></i>
			{else}
				{if isset($current_employee->firstname)}<img src="{$message.employee_image}" alt="{$current_employee->firstname|escape:'html':'UTF-8'}" />{/if}
			{/if}
		</div>
	</div>
{/if}
	<div class="message-body">
		{if !$initial}
			<h4 class="message-item-heading">
				<i class="icon-mail-reply text-muted"></i> 
					{if $type == 'customer'}
						{$message.customer_name|escape:'html':'UTF-8'}
					{else}
						{$message.employee_name|escape:'html':'UTF-8'}
					{/if}
			</h4>
		{/if}
		<span class="message-date">&nbsp;<i class="icon-calendar"></i> - {dateFormat date=$message.date_add full=0} - <i class="icon-time"></i> {$message.date_add|substr:11:5}</span>
		{if isset($message.file_name)} <span class="message-product">&nbsp;<i class="icon-link"></i> <a href="{$message.file_name|escape:'html':'UTF-8'}" target="_blank">{l s='Attachment' mod='tickets'}</a></span>{/if}
		{if isset($message.product_name)} <span class="message-attachment">&nbsp;<i class="icon-book"></i> <a href="{$message.product_link|escape:'html':'UTF-8'}" target="_blank">{l s='Product:' mod='tickets'} {$message.product_name|escape:'html':'UTF-8'} </a></span>{/if}
		<p class="message-item-text">{$message.message|escape:'html':'UTF-8'}</p>
	</div>
</div>