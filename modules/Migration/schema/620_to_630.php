<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

if (!defined('VTIGER_UPGRADE'))
die('Invalid entry point');
chdir(dirname(__FILE__) . '/../../../');


if(defined('VTIGER_UPGRADE')) {
    
global $adb;

$query = 'SELECT DISTINCT profileid FROM vtiger_profile2utility';
$result = $adb->pquery($query, array());

$profileId = $adb->query_result($result,0,'profileid');
Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_profile2utility(profileid,tabid,activityid,permission) VALUES ('.$profileId.',40,5,0)',array());
Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_profile2utility(profileid,tabid,activityid,permission) VALUES ('.$profileId.',40,6,0)',array());
Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_profile2utility(profileid,tabid,activityid,permission) VALUES ('.$profileId.',40,10,0)',array());
Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_profile2utility(profileid,tabid,activityid,permission) VALUES ('.$profileId.',19,5,0)',array());
Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_profile2utility(profileid,tabid,activityid,permission) VALUES ('.$profileId.',19,6,0)',array());
Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_profile2utility(profileid,tabid,activityid,permission) VALUES ('.$profileId.',19,10,0)',array());

for($i=1; $i< $adb->num_rows($result); $i++){

$profileId = $adb->query_result($result,$i,'profileid');

Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_profile2utility(profileid,tabid,activityid,permission) VALUES ('.$profileId.',40,5,1)',array());
Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_profile2utility(profileid,tabid,activityid,permission) VALUES ('.$profileId.',40,6,1)',array());
Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_profile2utility(profileid,tabid,activityid,permission) VALUES ('.$profileId.',40,10,0)',array());
Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_profile2utility(profileid,tabid,activityid,permission) VALUES ('.$profileId.',19,5,1)',array());
Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_profile2utility(profileid,tabid,activityid,permission) VALUES ('.$profileId.',19,6,1)',array());
Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_profile2utility(profileid,tabid,activityid,permission) VALUES ('.$profileId.',19,10,0)',array());

}     
   
}
?>