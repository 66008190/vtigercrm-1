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
 * $Header$
 * Description:  Deletes an Account record and then redirects the browser to the 
 * defined return URL.
 ********************************************************************************/

require_once('modules/Orders/SalesOrder.php');
global $mod_strings;

require_once('include/logging.php');
$log = LoggerManager::getLogger('salesorder_delete');

$focus = new SalesOrder();

if(!isset($_REQUEST['record']))
	die($mod_strings['ERR_DELETE_RECORD']);

$sql_recentviewed ='delete from tracker where user_id = '.$current_user->id.' and item_id = '.$_REQUEST['record'];
$adb->query($sql_recentviewed);
if($_REQUEST['return_module'] == $_REQUEST['module'])
{
	$focus->mark_deleted($_REQUEST['record']);
}
elseif($_REQUEST['return_module'] == "Quotes")
{
	$relation_query = "UPDATE salesorder set quoteid='' where salesorderid=".$_REQUEST['record'];
	$adb->query($relation_query);
}
elseif($_REQUEST['return_module'] == "Potentials")
{
	$relation_query = "UPDATE salesorder set potentialid='' where salesorderid=".$_REQUEST['record'];
	$adb->query($relation_query);
}
elseif($_REQUEST['return_module'] == "Products")
{
	$relation_query = "DELETE from soproductrel where salesorderid=".$_REQUEST['record']." and productid=".$_REQUEST['return_id'];
	$adb->query($relation_query);
}
	
header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&smodule=SO&record=".$_REQUEST['return_id']);
?>
