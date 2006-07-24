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

require_once('include/logging.php');
require_once('modules/Accounts/Account.php');
require_once('include/database/PearDatabase.php');
global $adb;

$local_log =& LoggerManager::getLogger('AccountsAjax');

$ajaxaction = $_REQUEST["ajxaction"];
if($ajaxaction == "DETAILVIEW")
{
     $crmid = $_REQUEST["recordid"];
     $tablename = $_REQUEST["tableName"];
     $fieldname = $_REQUEST["fldName"];
     $fieldvalue = $_REQUEST["fieldValue"];
     if($crmid != "")
	 {
	     $acntObj = new Account();
	     $acntObj->retrieve_entity_info($crmid,"Accounts");
	     $acntObj->column_fields[$fieldname] = $fieldvalue;
	     if($fieldname == 'annual_revenue')//annual revenue converted to dollar value while saving
	     {
		     $acntObj->column_fields[$fieldname] = getConvertedPrice($fieldvalue);
	     }	     
	     $acntObj->id = $crmid;
  	     $acntObj->mode = "edit";
       	     $acntObj->save("Accounts");
             if($acntObj->id != "")
	     {
			echo ":#:SUCCESS";
	     }else
	     {
			echo ":#:FAILURE";
	     }   
	}else
	{
         echo ":#:FAILURE";
    }
}
?>
