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
 * $Header:  vtiger_crm/modules/Accounts/SubPanelView.php,v 1.1 2004/08/17 15:02:56 gjk Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");

global $currentModule;

global $theme;
global $focus;
global $action;
global $app_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Accounts');

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

// focus_list is the means of passing data to a SubPanelView.
global $focus_list;

$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module' value='Accounts'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<tr><td>&nbsp;</td>";
if (isset($focus->industry)) $button .= "<input type='hidden' name='industry' value='".urlencode($focus->industry)."'>\n";
if (isset($focus->account_type)) $button .= "<input type='hidden' name='account_type' value='".urlencode($focus->account_type)."'>\n";
if (isset($focus->ownership)) $button .= "<input type='hidden' name='ownership' value='".urlencode($focus->ownership)."'>\n";
if (isset($focus->website)) $button .= "<input type='hidden' name='website' value='".urlencode($focus->website)."'>\n";
if (isset($focus->billing_address_street)) $button .= "<input type='hidden' name='billing_address_street' value='".urlencode($focus->billing_address_street)."'>\n";
if (isset($focus->billing_address_city)) $button .= "<input type='hidden' name='billing_address_city' value='".urlencode($focus->billing_address_city)."'>\n";
if (isset($focus->billing_address_state)) $button .= "<input type='hidden' name='billing_address_state' value='".urlencode($focus->billing_address_state)."'>\n";
if (isset($focus->billing_address_country)) $button .= "<input type='hidden' name='billing_address_country' value='".urlencode($focus->billing_address_country)."'>\n";
if (isset($focus->billing_address_postalcode)) $button .= "<input type='hidden' name='billing_address_postalcode' value='".urlencode($focus->billing_address_postalcode)."'>\n";
$button .= "<input type='hidden' name='parent_id'>\n";
$button .= "<input type='hidden' name='parent_name'>\n";
$button .= "<td><input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_NEW_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView';this.form.parent_id.value='$focus->id';this.form.parent_name.value='".urlencode($focus->name)."'\" type='submit' name='button' value='  ".$app_strings['LBL_NEW_BUTTON_LABEL']."  '></td>\n";
$button .= "<td><input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' type='button' class='button' value=' ".$app_strings['LBL_SELECT_BUTTON_LABEL']." ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Accounts&action=Popup&html=Popup_picker&form=AccountDetailView&form_submit=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";
$button .= "</tr></form></table>\n";

// Stick the form header out there.
echo get_form_header($current_module_strings['LBL_MEMBER_ORG_FORM_TITLE'], $button, false);
$xtpl=new XTemplate ('modules/Accounts/SubPanelViewMemberAccount.html');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=$focus->id");

$oddRow = true;
foreach($focus_list as $account)
{
	$account_fields = array(
		'ID' => $account->id,
		'NAME' => $account->name,
		'CITY' => $account->billing_address_city,
		'STATE' => $account->billing_address_state,
		'PHONE_WORK' => $account->phone_office
	);
	
	$xtpl->assign("ACCOUNT", $account_fields);
	
	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
    }
    $oddRow = !$oddRow;

	$xtpl->parse("main.row");
// Put the rows in.
}

$xtpl->parse("main");
$xtpl->out("main");

// Stick on the form footer
echo get_form_footer();
 
?>
