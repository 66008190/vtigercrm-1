<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once('include/utils/UserInfoUtil.php');
require_once('include/RelatedListView.php');
require_once('modules/Activities/Activity.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('modules/PriceBooks/PriceBook.php');
require_once('modules/PurchaseOrder/PurchaseOrder.php');
require_once('modules/SalesOrder/SalesOrder.php');
require_once('modules/Quotes/Quote.php');
require_once('modules/Invoice/Invoice.php');

function getHiddenValues($id,$sid="product_id")
{
        $hidden .= '<form border="0" action="index.php" method="post" name="form" id="form">';
        $hidden .= '<input type="hidden" name="module">';
        $hidden .= '<input type="hidden" name="mode">';
        $hidden .= '<input type="hidden" name="'.$sid.'" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="return_module" value="Products">';
        $hidden .= '<input type="hidden" name="return_action" value="DetailView">';
        $hidden .= '<input type="hidden" name="return_id" value="'.$id.'">';
        //$hidden .= '<input type="hidden" name="parent_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="action">';	
	return $hidden;
}

function getPriceBookHiddenValues($id)
{
        $hidden .= '<form border="0" action="index.php" method="post" name="form" id="form">';
        $hidden .= '<input type="hidden" name="module">';
        $hidden .= '<input type="hidden" name="mode">';
        $hidden .= '<input type="hidden" name="pricebook_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="return_module" value="PriceBooks">';
        $hidden .= '<input type="hidden" name="return_action" value="DetailView">';
        $hidden .= '<input type="hidden" name="return_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="parent_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="action">';	
	return $hidden;
}

function renderRelatedTickets($query,$id)
{
	global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
        echo $hidden;
	echo '<input type="hidden" name="parent_id" value="">';
        $focus = new HelpDesk();

	$button = '';

        if(isPermitted("HelpDesk",1,"") == 'yes')
        {
		$button .= '<input title="New TICKET" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\';this.form.return_action.value=\'DetailView\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TICKET'].'">&nbsp;';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Products','HelpDesk',$focus,$query,$button,$returnset);
	//echo '</form>';
}

function renderRelatedActivities($query,$id,$cntid='')
{
	global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
	$hidden .= '<input type="hidden" name="activity_mode">';	
        //to populate contact name
        if($cntid!=0 && $cntid!='')
        $hidden .= '<input type="hidden" name="contact_id" value="'.$cntid.'">';

        echo $hidden;

        $focus = new Activity();

	$button = '';

        if(isPermitted("Activities",1,"") == 'yes')
        {
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';;this.form.activity_mode.value=\'Events\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Products','Activities',$focus,$query,$button,$returnset);
	//echo '</form>';
}

function renderRelatedAttachments($query,$id,$cntid='')
{
        $hidden = getHiddenValues($id);
	$hidden .= '<input type="hidden" name="parent_id" value="'.$id.'">';
        //to populate contact name
        if($cntid!=0 && $cntid!='')
        $hidden .= '<input type="hidden" name="contact_id" value="'.$cntid.'">';

        echo $hidden;

        return getAttachmentsAndNotes('Products',$query,$id);

        //echo '</form>';
}

function renderProductPurchaseOrders($query,$id,$vendid='',$cntid='')
{
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
	if($vendid!=0)
	{
        	$hidden .= '<input type="hidden" name="vendor_id" value="'.$vendid.'">';
	}
	if($cntid!=0 && $cntid!='')
        $hidden .= '<input type="hidden" name="contact_id" value="'.$cntid.'">';

        echo $hidden;

        $focus = new Order();
 
	$button = '';

        if(isPermitted("PurchaseOrder",1,"") == 'yes')
        {
 
		$button .= '<input title="'.$app_strings['LBL_PORDER_BUTTON_TITLE'].'" accessyKey="O" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'PurchaseOrder\';this.form.return_module.value=\'Products\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_PORDER_BUTTON'].'">&nbsp;';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Products','PurchaseOrder',$focus,$query,$button,$returnset);
	//echo '</form>';
}

function renderProductSalesOrders($query,$id,$cntid='',$prtid='')
{
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
	if($prtid!=0 && $prtid!='')
	{
		$parent_module = getSalesEntityType($prtid);
		if($parent_module == "Accounts")
        	$hidden .= '<input type="hidden" name="account_id" value="'.$prtid.'">';
		if($parent_module == "Potentials")
                $hidden .= '<input type="hidden" name="potential_id" value="'.$prtid.'">';
	}
	if($cntid!=0 && $cntid!='')
        $hidden .= '<input type="hidden" name="contact_id" value="'.$cntid.'">';

        echo $hidden;

        $focus = new SalesOrder();
 
	$button = '';
	if(isPermitted("SalesOrder",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_SORDER_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'SalesOrder\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Products','SalesOrder',$focus,$query,$button,$returnset);
	//echo '</form>';
}

function renderRelatedQuotes($query,$id,$cntid='',$prtid='',$sid="product_id")
{
	global $mod_strings;
	global $app_strings;
	
	$hidden = getHiddenValues($id,$sid); 
	if($prtid!=0 && $prtid!='')
	{
		$parent_module = getSalesEntityType($prtid);
		if($parent_module == "Accounts")
        	$hidden .= '<input type="hidden" name="account_id" value="'.$prtid.'">';
		if($parent_module == "Potentials")
        	$hidden .= '<input type="hidden" name="potential_id" value="'.$prtid.'">';
	}
	if($cntid!=0 && $cntid!='')
        $hidden .= '<input type="hidden" name="contact_id" value="'.$cntid.'">';
	echo $hidden;
	
	$focus = new Quote();
	
	$button = '';
	if(isPermitted("Quotes",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_QUOTE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_QUOTE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Quotes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_QUOTE_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Products','Quotes',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedInvoices($query,$id,$cntid='',$prtid='')
{
	global $mod_strings;
	global $app_strings;

	$hidden = getHiddenValues($id);
	if($prtid!=0 && $prtid!='')
	{
		$parent_module = getSalesEntityType($prtid);
		if($parent_module == "Accounts")
        	$hidden .= '<input type="hidden" name="account_id" value="'.$prtid.'">';
	}
        //to populate contact name
	if($cntid!=0 && $cntid!='')
        $hidden .= '<input type="hidden" name="contact_id" value="'.$cntid.'">';

	echo $hidden;
	
	$focus = new Invoice();
	
	$button = '';
	if(isPermitted("Invoice",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_INVOICE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_INVOICE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Invoice\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_INVOICE_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Products','Invoice',$focus,$query,$button,$returnset);
	//echo '</form>';
}

function renderProductRelatedPriceBooks($query,$id)
{
	global $mod_strings;
	global $app_strings;

	$hidden = getHiddenValues($id);                                                                                             echo $hidden;
	
	$focus = new PriceBook();
	$button = '';
	if(isPermitted("PriceBook",3,"") == 'yes' && $focus->get_pricebook_noproduct($id))
        {
		$button .= '<input title="'.$mod_strings['LBL_ADD_PRICEBOOK_BUTTON_TITLE'].'" accessyKey="'.$mod_strings['LBL_ADD_PRICEBOOK_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'AddProductToPriceBooks\';this.form.module.value=\'Products\'" type="submit" name="button" value="'.$mod_strings['LBL_ADD_PRICEBOOK_BUTTON_LABEL'].'">&nbsp;</td>';

		//$button .= '<input title="'.$mod_strings['LBL_SELECT_PRICEBOOK_BUTTON_TITLE'].'" accessKey="'.$mod_strings['LBL_SELECT_PRICEBOOK_BUTTON_KEY'].'" type="button" class="button" value="'.$mod_strings['LBL_SELECT_PRICEBOOK_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Products&action=Popup&return_module=Potentials&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Products','PriceBook',$focus,$query,$button,$returnset);
	echo '</form>';
}

echo get_form_footer();


?>
