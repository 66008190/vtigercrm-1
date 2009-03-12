<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *******************************************************************************/
 
class CustomerPortal {
 	
 	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
 					
		require_once('include/utils/utils.php');			
		global $adb;
 		
 		if($eventType == 'module.postinstall') {			
			$portalmodules = array("Accounts","Contacts","HelpDesk","Invoice","Quotes","Documents","Faq","Services","Products");
			$i=0;
			foreach($portalmodules as $modules) {
				++$i;
				$tabid = getTabid($modules);	
				$adb->query("INSERT INTO vtiger_customerportal_tabs (tabid,visible,sequence) VALUES ($tabid,1,$i)");
			}
			for($j = 0; $j< count($portalmodules); $j++) {
			 	$tabid = getTabid($portalmodules[$j]);	
				$adb->query("INSERT INTO vtiger_customerportal_prefs(tabid,prefkey,prefvalue) VALUES ($tabid,'showrelatedinfo',1)");
			}
			
			$adb->query("INSERT INTO vtiger_customerportal_prefs(tabid,prefkey,prefvalue) VALUES (0,'userid',1)");
			
			// Mark the module as Standard module
			$adb->pquery('UPDATE vtiger_tab SET customized=0 WHERE name=?', array($moduleName));
			
		} else if($eventType == 'module.disabled') {
		// TODO Handle actions when this module is disabled.
		} else if($eventType == 'module.enabled') {
		// TODO Handle actions when this module is enabled.
		} else if($eventType == 'module.preuninstall') {
		// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
		// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
		// TODO Handle actions after this module is updated.
		}
 	}
}
?>