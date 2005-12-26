<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/EditView.php,v 1.21 2005/03/24 16:18:38 samk Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Contacts/Forms.php');
require_once('include/CustomFieldUtil.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('include/FormValidationUtil.php');

global $log;
global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$focus = new Contact();
$smarty = new vtigerCRM_Smarty;

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) 
{
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit';
    $focus->retrieve_entity_info($_REQUEST['record'],"Contacts");
    $log->info("Entity info successfully retrieved for EditView.");
    $focus->firstname=$focus->column_fields['firstname'];
    $focus->lastname=$focus->column_fields['lastname'];

}
if(isset($_REQUEST['account_id']) && $_REQUEST['account_id']!='' && $_REQUEST['record']=='')
{
        require_once('modules/Accounts/Account.php');
        $focus->column_fields['account_id'] = $_REQUEST['account_id'];
        $acct_focus = new Account();
        $acct_focus->retrieve_entity_info($_REQUEST['account_id'],"Accounts");
        $focus->column_fields['fax']=$acct_focus->column_fields['fax'];
        $focus->column_fields['otherphone']=$acct_focus->column_fields['phone'];
        $focus->column_fields['mailingcity']=$acct_focus->column_fields['bill_city'];
        $focus->column_fields['othercity']=$acct_focus->column_fields['ship_city'];
        $focus->column_fields['mailingstreet']=$acct_focus->column_fields['bill_street'];
        $focus->column_fields['otherstreet']=$acct_focus->column_fields['ship_street'];
        $focus->column_fields['mailingstate']=$acct_focus->column_fields['bill_state'];
        $focus->column_fields['otherstate']=$acct_focus->column_fields['ship_state'];
        $focus->column_fields['mailingzip']=$acct_focus->column_fields['bill_code'];
        $focus->column_fields['otherzip']=$acct_focus->column_fields['ship_code'];
        $focus->column_fields['mailingcountry']=$acct_focus->column_fields['bill_country'];
        $focus->column_fields['othercountry']=$acct_focus->column_fields['ship_country'];
        $log->debug("Accountid Id from the request is ".$_REQUEST['account_id']);

}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	$focus->id = "";
	$focus->mode = "";
}

$disp_view = getView($focus->mode);
$smarty->assign("BLOCKS",getBlocks("Contacts",$disp_view,$mode,$focus->column_fields));
//echo '<pre>';print_r(getBlocks("Contacts",$disp_view,$mode,$focus->column_fields));echo '</pre>';
$smarty->assign("OP_MODE",$disp_view);

//needed when creating a new contact with a default account value passed in
if (isset($_REQUEST['account_name']) && is_null($focus->account_name)) {
	$focus->account_name = $_REQUEST['account_name'];
	

}
if (isset($_REQUEST['account_id']) && is_null($focus->account_id)) {
	$focus->account_id = $_REQUEST['account_id'];
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
//retreiving the combo values array
$comboFieldNames = Array('leadsource'=>'lead_source_dom'
                      ,'salutationtype'=>'salutation_dom');
$comboFieldArray = getComboArray($comboFieldNames);

require_once($theme_path.'layout_utils.php');

$log->info("Contact detail view");

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("NAME",$focus->lastname." ".$focus->firstname);
$smarty->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
if(isset($cust_fld))
{
        $smarty->assign("CUSTOMFIELD", $cust_fld);
}
$smarty->assign("ID", $focus->id);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD","Contact");

if($focus->mode == 'edit')
{
        $smarty->assign("MODE", $focus->mode);
}

if(isset($_REQUEST['activity_mode']) && $_REQUEST['activity_mode'] !='')
        $smarty->assign("ACTIVITYMODE",$_REQUEST['activity_mode']);

// Unimplemented until jscalendar language files are fixed
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

if (isset($_REQUEST['return_module']))
$smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action']))
$smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id']))
$smarty->assign("RETURN_ID", $_REQUEST['return_id']);
if (isset($_REQUEST['return_viewname']))
$smarty->assign("RETURN_VIEWNAME", $_REQUEST['return_viewname']);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);


$contact_tables = Array('contactdetails','crmentity','contactsubdetails','contactscf','contactaddress','customerdetails'); 

 $tabid = getTabid("Contacts");
 $validationData = getDBValidationData($contact_tables,$tabid);
 $fieldName = '';
 $fieldLabel = '';
 $fldDataType = '';

 $rows = count($validationData);
 foreach($validationData as $fldName => $fldLabel_array)
 {
   if($fieldName == '')
   {
     $fieldName="'".$fldName."'";
   }
   else
   {
     $fieldName .= ",'".$fldName ."'";
   }
   foreach($fldLabel_array as $fldLabel => $datatype)
   {
	if($fieldLabel == '')
	{
			
     		$fieldLabel = "'".$fldLabel ."'";
	}		
      else
       {
      $fieldLabel .= ",'".$fldLabel ."'";
        }
 	if($fldDataType == '')
         {
      		$fldDataType = "'".$datatype ."'";
    	}
	 else
        {
       		$fldDataType .= ",'".$datatype ."'";
     	}
   }
 }

$smarty->assign("VALIDATION_DATA_FIELDNAME",$fieldName);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$fldDataType);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$fieldLabel);

$smarty->display("salesEditView.tpl");


?>
