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
global $adb;

$profilename = $_REQUEST['profileName'];
$parentProfileId= $_REQUEST['parentProfileId'];
$description= $_REQUEST['profileName'];
//Creating Profile
createProfile($profilename,$parentProfileId,$description);

$loc = "Location: index.php?action=ListProfiles&module=Users";
header($loc);
?>
