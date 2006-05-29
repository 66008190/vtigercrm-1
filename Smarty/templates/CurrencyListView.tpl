{*<!--

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

-->*}
{literal}
<script>
	function deleteCurrency(currid)
	{
        	show("status");
	        var ajaxObj = new Ajax(ajaxCurrencyDeleteResponse);
        	var urlstring = "action=SettingsAjax&file=CurrencyDeleteStep1&return_action=CurrencyListView&return_module=Settings&module=Settings&parenttab=Settings&id="+currid;
	        ajaxObj.process("index.php?",urlstring);
	}

	function ajaxCurrencyDeleteResponse(response)
	{
        	hide("status");
	        document.getElementById("currencydiv").innerHTML= response.responseText;
	}
	
	function transferCurrency(del_currencyid)
	{
                show("status");
                hide("CurrencyDeleteLay");
                var ajaxObj = new Ajax(ajaxCurrencySaveResponse);
                var trans_currencyid=document.getElementById('transfer_currency_id').options[document.getElementById('transfer_currency_id').options.selectedIndex].value;
                var urlstring ="module=Settings&action=SettingsAjax&file=CurrencyDelete&ajax=true&delete_currency_id="+del_currencyid+"&transfer_currency_id="+trans_currencyid;
                ajaxObj.process("index.php?",urlstring);

	}

	function ajaxCurrencySaveResponse(response)
	{
        	hide("status");
	        document.getElementById("CurrencyListViewContents").innerHTML= response.responseText;
	}


</script>

{/literal}
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
        {include file='SettingsMenu.tpl'}
<td width="75%" valign="top">
<form action="index.php">
<input type="hidden" name="module" value="Settings">
<input type="hidden" name="action" value="CurrencyEditView">
<input type="hidden" name="parenttab" value="{$PARENTTAB}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
		<td class="showPanelBg" valign="top" width="90%"  style="padding-left:20px; "><br />
			<span class="lvtHeaderText">{$MOD.LBL_MODULE_NAME} &gt;{$MOD.LBL_CONFIGURATION} &gt; {$MOD.LBL_CURRENCY_CONFIG}</span>
			<hr noshade="noshade" size="1" />
		</td>
		<td width="10%" class="showPanelBg">&nbsp;</td>
	</tr>
	<tr>
		<td width="90%" style="padding-left:20px;" valign="top">
			<input type="submit" name="new" value="{$MOD.LBL_NEW_CURRENCY}" class="classBtn" /><br /><br />
			<div id="CurrencyListViewContents">
				{include file="CurrencyListViewEntries.tpl"}
			</div>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>
</form>
</td>
</tr>
</table>
<div id="currencydiv" style="display:block;position:absolute;left:350px;top:200px;"></div>
{include file='SettingsSubMenu.tpl'}
