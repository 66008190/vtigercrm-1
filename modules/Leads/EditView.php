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
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Leads/Forms.php');

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;

$focus = new Lead();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
/*
//needed when creating a new contact with a default account value passed in 
if (isset($_REQUEST['account_name']) && is_null($focus->account_name)) {
	$focus->account_name = $_REQUEST['account_name'];
	if(get_magic_quotes_gpc() == 1)
	{
		$focus->account_name = stripslashes($focus->account_name);
	}

}
if (isset($_REQUEST['account_id']) && is_null($focus->account_id)) {
	$focus->account_id = $_REQUEST['account_id'];
}
*/

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Lead detail view");

$xtpl=new XTemplate ('modules/Leads/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id());
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("HEADER", get_module_title("Leads", "{MOD.LBL_LEAD}  ".$focus->first_name." ".$focus->last_name, true));
if (isset($focus->first_name)) $xtpl->assign("FIRST_NAME", $focus->first_name);
else $xtpl->assign("FIRST_NAME", "");
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("MOBILE", $focus->mobile);
$xtpl->assign("PHONE", $focus->phone);
$xtpl->assign("FAX", $focus->fax);
$xtpl->assign("EMAIL", $focus->email);
$xtpl->assign("YAHOO_ID", $focus->yahoo_id);
$xtpl->assign("COMPANY", $focus->company);
$xtpl->assign("DESIGNATION", $focus->designation);
$xtpl->assign("WEBSITE", $focus->website);
$xtpl->assign("ANNUAL_REVENUE", $focus->annual_revenue);
$xtpl->assign("EMPLOYEES", $focus->employees);

$xtpl->assign("ADDRESS_STREET", $focus->address_street);
$xtpl->assign("ADDRESS_CITY", $focus->address_city);
$xtpl->assign("ADDRESS_STATE", $focus->address_state);
$xtpl->assign("ADDRESS_POSTALCODE", $focus->address_postalcode);
$xtpl->assign("ADDRESS_COUNTRY", $focus->address_country);
$xtpl->assign("DESCRIPTION", $focus->description);

if ($focus->assigned_user_id == '' && (!isset($focus->id) || $focus->id=0)) $focus->assigned_user_id = $current_user->id; 
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(), $focus->assigned_user_id));
$xtpl->assign("LEAD_SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['lead_source_dom'], $focus->lead_source));
$xtpl->assign("SALUTATION_OPTIONS", get_select_options_with_id($app_list_strings['salutation_dom'], $focus->salutation));
$xtpl->assign("INDUSTRY_OPTIONS", get_select_options_with_id($app_list_strings['industry_dom'], $focus->industry));
$xtpl->assign("LEAD_STATUS_OPTIONS", get_select_options_with_id($app_list_strings['lead_status_dom'], $focus->lead_status));
$xtpl->assign("RATING_OPTIONS", get_select_options_with_id($app_list_strings['rating_dom'], $focus->rating));
$xtpl->assign("LICENSE_KEY_OPTIONS", get_select_options_with_id($app_list_strings['license_key_dom'], $focus->license_key));

$xtpl->parse("main");

$xtpl->out("main");

?>
