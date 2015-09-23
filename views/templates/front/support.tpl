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

{capture name=path}
	<a href="{$link->getPageLink('my-account', true)|escape:'htmlall':'UTF-8'}" title="{l s='My account' mod='tickets'}" rel="nofollow">
		{l s='My account' mod='tickets'}
	</a>
	<span class="navigation-pipe">
		{$navigationPipe|escape:'htmlall':'UTF-8'}
	</span>
	{if isset($ticket)}
		<a href="{$link->getModuleLink('tickets', 'support', [], true)|escape:'htmlall':'UTF-8'}" title="{l s='Customer support' mod='tickets'}" rel="nofollow">
			{l s='Tickets' mod='tickets'}
		</a>
		<span class="navigation-pipe">
			{$navigationPipe|escape:'htmlall':'UTF-8'}
		</span>
		{l s='Ticket' mod='tickets'} #{$ticket->id_customer_thread|intval}
	{else}
		{l s='Tickets History' mod='tickets'}
	{/if}
{/capture}

{include file="$tpl_dir./errors.tpl"}

<!-- Ticket history section -->
{if isset($tickets)}
<div class="panel">
	<div class="panel-heading">
		<h1>
			<i class="icon-wrench"></i> {l s='Customer Support' mod='tickets'}
		</h1>
		<div class="btn-toolbar" role="toolbar" aria-label="{l s='Customer Support' mod='tickets'}">
			<div class="btn-group pull-right" role="group" aria-label="{l s='Action toolbar' mod='tickets'}">
				<a href="{$link->getPageLink('contact', true)|escape:'htmlall':'UTF-8'}" role="button" type="button" class="btn btn-default" title="{l s='Add a new ticket' mod='tickets'}">
					<i class="icon-plus"></i> {l s='Add a new ticket' mod='tickets'}
				</a>
			</div>
		</div>
	</div>
	<div class="panel-body">
		{l s='Here you can find a ticket list history and details. Click on subject to open details' mod='tickets'}.
		{if isset($tickets) && $tickets|@count}
		<h2><i class="icon-list"></i> {l s='Ticket History' mod='tickets'}</h2>
		<table class="table table-bordered footab">
			<thead>
				<tr>
					<th class="first_item">{l s='Id Ticket' mod='tickets'}</th>
					<th class="item">{l s='Subject' mod='tickets'}</th>
					<th class="item">{l s='Status' mod='tickets'}</th>
					<th class="item">{l s='Id Order' mod='tickets'}</th>
					<th class="last_item">{l s='Updated' mod='tickets'}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$tickets item=item}
				<tr>
					<td class="pointer" onclick="location.href='{$link->getModuleLink('tickets', 'support', [ticket => {$item.id_customer_thread|intval}], true)}'">{$item.id_customer_thread|intval}</td>
					<td class="pointer" onclick="location.href='{$link->getModuleLink('tickets', 'support', [ticket => {$item.id_customer_thread|intval}], true)}'">
						{$item.subject|truncate:32:'...'|escape:'htmlall':'UTF-8'}<br />
						<small>{l s='Created at' mod='tickets'} {$item.date_add|escape:'htmlall':'UTF-8'}</small>
					</td>
					<td class="pointer" onclick="location.href='{$link->getModuleLink('tickets', 'support', [ticket => {$item.id_customer_thread|intval}], true)}'">
						<p class="label {if $item.status == open}label-primary{elseif $item.status == pending1}label-warning{elseif $item.status == closed}label-success{elseif $item.status == pending2}label-danger{else}label-info{/if}">
							{$statuses[$item.status]|escape:'htmlall':'UTF-8'}
						</p>
					</td>
					<td>{$item.id_order|escape:'htmlall':'UTF-8'}</td>
					<td class="pointer" onclick="location.href='{$link->getModuleLink('tickets', 'support', [ticket => {$item.id_customer_thread|intval}], true)}'">{$item.date_upd|escape:'htmlall':'UTF-8'}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
		{else}
		<p>&nbsp;</p>
		<p>
			<strong>{l s='Information' mod='tickets'}:</strong> {l s='We do not have registered any ticket to this account' mod='tickets'}.
		</p>
		{/if}
	</div>
</div>
{/if}

<!-- Message section -->
{if isset($id_customer_thread) && $id_customer_thread > 0}
<div class="bootstrap col-lg-12">
	<div class="panel">
		<div class="panel-heading">
			<i class="icon-comments"></i>
			{l s='Ticket' mod='tickets'}: <span class="badge">#{$id_customer_thread|intval}</span>
		</div>
		<div class="row">
			<div class="message-item-initial media">
				<span class="avatar-lg pull-left"><i class="icon-user icon-3x"></i></span>
				<div class="media-body">
					<div class="row">
						<div class="col-sm-6">
						{if isset($customer->firstname)}
							<h2>
								{$customer->firstname|escape:'htmlall':'UTF-8'} {$customer->lastname|escape:'htmlall':'UTF-8'} <small>({$customer->email|escape:'htmlall':'UTF-8'})</small>
							</h2>
						{/if}
						{if isset($contact) && trim($contact) != ''}
							<span>{l s='To:' mod='tickets'} </span><span class="badge">{$contact|escape:'htmlall':'UTF-8'}</span>
						{/if}
						</div>
						{if isset($customer->firstname)}
							<div class="col-sm-6">
							</div>
						{/if}
					</div>
					<div class="row">
						<div class="col-sm-12">
							{if !$first_message.id_employee}
								{include file="./message.tpl" message=$first_message initial=true}
							{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			{foreach $messages as $message}
				{include file="./message.tpl" message=$message initial=false}
			{/foreach}
		</div>
	</div>
	<div class="panel">
		<form action="{$link->getModuleLink('tickets', 'support', ['ticket' => {$id_customer_thread|intval}], true)|escape:'htmlall':'UTF-8'}" method="post" enctype="multipart/form-data"  class="form-horizontal">
		<h3>{l s='Your answer to' mod='tickets'} {if isset($contact) && $contact}{$contact|escape:'htmlall':'UTF-8'}{else}{l s='Customer Service' mod='tickets'}{/if}</h3>
		<div class="row">
			<div class="media">
				<div class="pull-left">
					<span class="avatar-md"><i class="icon-user icon-3x"></i></span>
				</div>
				<div class="media-body">
					<textarea cols="30" rows="7" name="reply_message">{$reply_message|escape:'htmlall':'UTF-8'}</textarea>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<input type="hidden" name="email" value="{$customer->email|escape:'html':'UTF-8'}" />
			<input type="hidden" name="token" value="{$thread->token|escape:'html':'UTF-8'}" />
			<button class="btn btn-default pull-right" name="submitReply"><i class="icon-mail-reply"></i> {l s='Send' mod='tickets'}</button>
		</div>
		</form>
	</div>
</div>
{/if}

<p>&nbsp;</p>
<div class="clearfix"></div>
<ul class="footer_links">
	<li><a class="btn btn-default button button-small" href="{$link->getPageLink('my-account', true)|escape:'htmlall':'UTF-8'}"><span><i class="icon-chevron-left"></i> {l s='Back to your account' mod='tickets'}</span></a></li>
	<li><a class="btn btn-default button button-small" href="{$base_dir|escape:'htmlall':'UTF-8'}"><span><i class="icon-chevron-left"></i> {l s='Home' mod='tickets'}</span></a></li>
</ul>