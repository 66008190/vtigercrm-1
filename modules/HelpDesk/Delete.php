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
 * $Header: /advent/projects/wesat/vtiger_crm/vtigercrm/modules/HelpDesk/Delete.php,v 1.3 2005/04/25 05:15:09 mickie Exp $
 * Description:  Deletes an Account record and then redirects the browser to the 
 * defined return URL.
 ********************************************************************************/

require_once('modules/HelpDesk/HelpDesk.php');
global $mod_strings;

require_once('include/logging.php');
$log = LoggerManager::getLogger('ticket_delete');

$focus = new HelpDesk();

if(!isset($_REQUEST['record']))
	die($mod_strings['ERR_DELETE_RECORD']);

if($_REQUEST['return_module'] == $_REQUEST['module'])
	$focus->mark_deleted($_REQUEST['record']);

if($_REQUEST['return_module'] == 'Contacts' || $_REQUEST['return_module'] == 'Accounts')
{
	$sql = "update troubletickets set parent_id='' where ticketid=".$_REQUEST['record'];
	$adb->query($sql);
	$se_sql= 'delete from seticketsrel where ticketid='.$_REQUEST['record'];
	$adb->query($se_sql);

}
if($_REQUEST['return_module'] == 'Products')
{
	$sql = "update troubletickets set product_id='' where ticketid=".$_REQUEST['record'];
	$adb->query($sql);
}

//code added for returning back to the current view after delete from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];

header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']."&viewname=".$return_viewname);

?>
