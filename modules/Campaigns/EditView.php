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
require_once('include/database/PearDatabase.php');
require_once('Smarty_setup.php');
require_once('include/utils/utils.php');
require_once('modules/Campaigns/Campaigns.php');
require_once('include/FormValidationUtil.php');

global $app_strings,$mod_strings,$currentModule,$theme;

$focus = new Campaigns();
$smarty = new vtigerCRM_Smarty();
//added to fix the issue4600
$searchurl = getBasic_Advance_SearchURL();
$smarty->assign("SEARCH", $searchurl);
//4600 ends


if(isset($_REQUEST['record']) && $_REQUEST['record'] !='') 
{
	$focus->id = $_REQUEST['record'];
	$focus->mode = 'edit'; 	
	$focus->retrieve_entity_info($_REQUEST['record'],"Campaigns");
	$focus->name=$focus->column_fields['campaignname'];		
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	$focus->id = "";
    	$focus->mode = ''; 	
} 

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$disp_view = getView($focus->mode);
if($disp_view == 'edit_view')
	$smarty->assign("BLOCKS",getBlocks($currentModule,$disp_view,$mode,$focus->column_fields));
else
{
	$smarty->assign("BASBLOCKS",getBlocks($currentModule,$disp_view,$mode,$focus->column_fields,'BAS'));
}
	
$smarty->assign("OP_MODE",$disp_view);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Campaign');


$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);

if (isset($focus->name)) 
$smarty->assign("NAME", $focus->name);
else 
$smarty->assign("NAME", "");

if(isset($cust_fld))
{
        $smarty->assign("CUSTOMFIELD", $cust_fld);
}
$smarty->assign("ID", $focus->id);
if($focus->mode == 'edit')
{
        $smarty->assign("MODE", $focus->mode);
        $smarty->assign("OLDSMOWNERID", $focus->column_fields['assigned_user_id']);
	$smarty->assign("UPDATEINFO",updateInfo($focus->id));
}

if(isset($_REQUEST['return_module'])) 
$smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) 
$smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) 
$smarty->assign("RETURN_ID", $_REQUEST['return_id']);
if(isset($_REQUEST['product_id'])) 
$smarty->assign("PRODUCTID", $_REQUEST['product_id']);
if (isset($_REQUEST['return_viewname'])) 
$smarty->assign("RETURN_VIEWNAME", $_REQUEST['return_viewname']);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

$tabid = getTabid("Campaigns");
$validationData = getDBValidationData($focus->tab_name,$tabid);
$data = split_validationdataArray($validationData);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);
$smarty->assign("DUPLICATE", $_REQUEST['isDuplicate']);
// Module Sequence Numbering
if($focus->mode != 'edit') {
		$autostr = getTranslatedString('MSG_AUTO_GEN_ON_SAVE');
		$inv_no = $adb->pquery("SELECT prefix, cur_id from vtiger_modentity_num where semodule = ? and active=1",array($module));
        $invstr = $adb->query_result($inv_no,0,'prefix');
        $invno = $adb->query_result($inv_no,0,'cur_id');
        if($focus->checkModuleSeqNumber('vtiger_campaign', 'campaign_no', $invstr.$invno))
                echo '<br><font color="#FF0000"><b>Duplicate Campaign Number - Click <a href="index.php?module=Settings&action=CustomModEntityNo&parenttab=Settings">here</a> to Configure the Campaign Number</b></font>'.$num_rows;
        else
                $smarty->assign("inv_no",$autostr);
}
// END

if($focus->mode == 'edit')
	$smarty->display("salesEditView.tpl");
else
	$smarty->display("CreateView.tpl");

?>
