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
require_once('database/DatabaseConnection.php');

//function to construct the combo field from database
function getComboValues($fieldname, $tableName, $tableColumn, $tabindex, $value)
{
$query = "select * from ".$tableName;
$result = mysql_query($query);
$output = "<select name='".$fieldname."' tabindex='1'>"; 
while($row = mysql_fetch_array($result))
{
	$selected = '';
	if($value != '' && $row[$tableColumn] == $value)
	{
		$selected = 'selected';
	}
	$output .= "<OPTION value='".$row[$tableColumn]."' ".$selected.">".$row[$tableColumn]."</OPTION>";
}
$output .= "</select>";
return $output;		
}

function getTicketList()
{
	$query = "select troubletickets.id,groupname,contact_id,priority,troubletickets.status,parent_id,parent_type,category,troubletickets.title,troubletickets.description,update_log,version_id,troubletickets.date_created,troubletickets.date_modified,troubletickets.assigned_user_id,contacts.first_name,contacts.last_name,users.user_name from troubletickets left join contacts on troubletickets.contact_id=contacts.id  left join users on troubletickets.id=users.id where troubletickets.deleted=0 and troubletickets.status !='Closed'";
	$result = mysql_query($query);
	return $result;

}

?>
