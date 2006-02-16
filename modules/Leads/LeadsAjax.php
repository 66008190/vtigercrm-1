<?php
require_once('include/logging.php');
require_once('modules/Leads/Lead.php');
require_once('include/database/PearDatabase.php');
global $adb;

$local_log =& LoggerManager::getLogger('LeadsAjax');

$ajaxaction = $_REQUEST["ajxaction"];

if($ajaxaction == "DETAILVIEW")
{
     $crmid = $_REQUEST["recordid"];
     $tablename = $_REQUEST["tableName"];
     $fieldname = $_REQUEST["fldName"];
     //$columname = $_REQUEST["clmnName"];
     $fieldvalue = $_REQUEST["fieldValue"];
     
     if($crmid != "")
	{
          $leadObj = new Lead();
	     $leadObj->retrieve_entity_info($crmid,"Leads");
	     $leadObj->column_fields[$fieldname] = $fieldvalue;
	     $leadObj->id = $crmid;
  		$leadObj->mode = "edit";
       	$leadObj->save("Leads");
          if($leadObj->id != "")
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
