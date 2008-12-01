<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once('vtlib/Vtiger/Module.php');
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Event.php');
include_once('vtlib/Vtiger/Zip.php');

/**
 * Provides API to package vtiger CRM module and associated files.
 * @package vtlib
 */
class Vtiger_PackageExport {
	var $_export_tmpdir = 'test/vtlib';
	var $_export_modulexml_filename = null;
	var $_export_modulexml_file = null;

	/**
	 * Constructor
	 */
	function Vtiger_PackageExport() {
		if(is_dir($this->_export_tmpdir) === FALSE) {
			mkdir($this->_export_tmpdir);
		}
	}

	/** Output Handlers */

	/** @access private */
	function openNode($node,$delimiter="\n") {
		$this->__write("<$node>$delimiter");
	}
	/** @access private */
	function closeNode($node,$delimiter="\n") {
		$this->__write("</$node>$delimiter");
	}
	/** @access private */
	function outputNode($value, $node='') {
		if($node != '') $this->openNode($node,'');
		$this->__write($value);
		if($node != '') $this->closeNode($node);
	}
	/** @access private */
	function __write($value) {
		fwrite($this->_export_modulexml_file, $value);
	}

	/**
	 * Set the module.xml file path for this export and 
	 * return its temporary path.
	 * @access private
	 */
	function __getManifestFilePath() {
		if(empty($this->_export_modulexml_filename)) {
			// Set the module xml filename to be written for exporting.
			$this->_export_modulexml_filename = "manifest-".time().".xml";
		}
		return "$this->_export_tmpdir/$this->_export_modulexml_filename";
	}

	/**
	 * Initialize Export
	 * @access private
	 */
	function __initExport($module) {
		// We will be including the file, so do a security check.
		Vtiger_Utils::checkFileAccess("modules/$module/$module.php");
		$this->_export_modulexml_file = fopen($this->__getManifestFilePath(), 'w');
		$this->__write("<?xml version='1.0'?>\n");
	}

	/**
	 * Post export work.
	 * @access private
	 */
	function __finishExport() {
		if(!empty($this->_export_modulexml_file)) {
			fclose($this->_export_modulexml_file);
			$this->_export_modulexml_file = null;
		}
	}

    /**
	 * Clean up the temporary files created.
	 * @access private
     */
	function __cleanupExport() {
		if(!empty($this->_export_modulexml_filename)) {
			unlink($this->__getManifestFilePath());
		}
	}

	/**
	 * Export Module as a zip file.
	 * @param Vtiger_Module Instance of module
	 * @param Path Output directory path
	 * @param String Zipfilename to use
	 * @param Boolean True for sending the output as download
	 */
	function export($moduleInstance, $todir='', $zipfilename='', $directDownload=false) {

		$module = $moduleInstance->name;

		$this->__initExport($module);

		// Call module export function
		$this->export_Module($moduleInstance);

		$this->__finishExport();		

		// Export as Zip
		if($zipfilename == '') $zipfilename = "$module-" . date('YmdHis') . ".zip";
		$zipfilename = "$this->_export_tmpdir/$zipfilename";

		$zip = new Vtiger_Zip($zipfilename);
		// Add manifest file
		$zip->addFile($this->__getManifestFilePath(), "manifest.xml");		
		// Copy module directory
		$zip->copyDirectoryFromDisk("modules/$module");
		// Copy templates directory of the module (if any)
		if(is_dir("Smarty/templates/modules/$module"))
			$zip->copyDirectoryFromDisk("Smarty/templates/modules/$module", "templates");

		$zip->save();

		if($directDownload) {
			$zip->forceDownload($zipfilename);
			unlink($zipfilename);
		}
		$this->__cleanupExport();
	}

	/**
	 * Export vtiger dependencies
	 * @access private
	 */
	function export_Dependencies() {
		global $vtiger_current_version;
		$this->openNode('dependencies');
		$this->outputNode($vtiger_current_version, 'vtiger_version');
		$this->closeNode('dependencies');
	}

	/**
	 * Export Module Handler
	 * @access private
	 */
	function export_Module($moduleInstance) {
		global $adb;

		$moduleid = $moduleInstance->id;

		$sqlresult = $adb->query("SELECT * FROM vtiger_parenttabrel WHERE tabid = $moduleid");
		$parenttabid = $adb->query_result($sqlresult, 0, 'parenttabid');
		$menu = Vtiger_Menu::getInstance($parenttabid);
		$parent_name = $menu->label;

		$sqlresult = $adb->query("SELECT * FROM vtiger_tab WHERE tabid = $moduleid");
		$tabname = $adb->query_result($sqlresult, 0, 'name');
		$tablabel= $adb->query_result($sqlresult, 0, 'tablabel');

		$this->openNode('module');
		$this->outputNode(date('Y-m-d H:i:s'),'exporttime');
		$this->outputNode($tabname, 'name');
		$this->outputNode($tablabel, 'label');
		$this->outputNode($parent_name, 'parent');

		// Export dependency information
		$this->export_Dependencies();

		// Export module tables
		$this->export_Tables($moduleInstance);

		// Export module blocks
		$this->export_Blocks($moduleInstance);

		// Export module filters
		$this->export_CustomViews($moduleInstance);

		// Export Sharing Access
		$this->export_SharingAccess($moduleInstance);

		// Export Events
		$this->export_Events($moduleInstance);		

		// Export Actions
		$this->export_Actions($moduleInstance);

		$this->closeNode('module');
	}

	/**
	 * Export module base and related tables
	 * @access private
	 */
	function export_Tables($moduleInstance) {
		$modulename = $moduleInstance->name;

		require_once("modules/$modulename/$modulename.php");

		$focus = new $modulename();

		// Setup required module variables which is need for vtlib API's
		vtlib_setup_modulevars($modulename, $focus);

		$tables = Array ($focus->table_name);
		if(!empty($focus->groupTable)) $tables[] = $focus->groupTable[0];
		if(!empty($focus->customFieldTable)) $tables[] = $focus->customFieldTable[0];

		$this->openNode('tables');

		foreach($tables as $table) {
			$this->openNode('table');
			$this->outputNode($table, 'name');
			$this->outputNode(Vtiger_Utils::CreateTableSql($table), 'sql');
			$this->closeNode('table');
		}
		$this->closeNode('tables');
	}

	/**
	 * Export module blocks with its related fields
	 * @access private
	 */
	function export_Blocks($moduleInstance) {
		global $adb;
		$sqlresult = $adb->pquery("SELECT * FROM vtiger_blocks WHERE tabid = ?", Array($moduleInstance->id));
		$resultrows= $adb->num_rows($sqlresult);

		$this->openNode('blocks');
		for($index = 0; $index < $resultrows; ++$index) {
			$blockid    = $adb->query_result($sqlresult, $index, 'blockid');
			$blocklabel = $adb->query_result($sqlresult, $index, 'blocklabel');
		
			$this->openNode('block');
			$this->outputNode($blocklabel, 'label');
			// Export fields associated with the block
			$this->export_Fields($moduleInstance, $blockid);
			$this->closeNode('block');
		}
		$this->closeNode('blocks');
	}

	/**
	 * Export fields related to a module block
	 * @access private
	 */
	function export_Fields($moduleInstance, $blockid) {
		global $adb;
		
		$fieldresult = $adb->pquery("SELECT * FROM vtiger_field WHERE tabid=? AND block=?", Array($moduleInstance->id, $blockid));
		$fieldcount = $adb->num_rows($fieldresult);

		$entityresult = $adb->pquery("SELECT * FROM vtiger_entityname WHERE tabid=?", Array($moduleInstance->id));
		$entity_fieldname = $adb->query_result($entityresult, 0, 'fieldname');

		$this->openNode('fields');
		for($index = 0; $index < $fieldcount; ++$index) {
			$this->openNode('field');
			$fieldname = $adb->query_result($fieldresult, $index, 'fieldname');
			$uitype = $adb->query_result($fieldresult, $index, 'uitype');
			$fieldid = $adb->query_result($fieldresult, $index, 'fieldid');

			$this->outputNode($fieldname, 'fieldname');	
			$this->outputNode($uitype,    'uitype');
			$this->outputNode($adb->query_result($fieldresult, $index, 'columnname'),'columnname');			
			$this->outputNode($adb->query_result($fieldresult, $index, 'tablename'),     'tablename');
			$this->outputNode($adb->query_result($fieldresult, $index, 'generatedtype'), 'generatedtype');
			$this->outputNode($adb->query_result($fieldresult, $index, 'fieldlabel'),    'fieldlabel');
			$this->outputNode($adb->query_result($fieldresult, $index, 'readonly'),      'readonly');
			$this->outputNode($adb->query_result($fieldresult, $index, 'presence'),      'presence');
			$this->outputNode($adb->query_result($fieldresult, $index, 'selected'),      'selected');
			$this->outputNode($adb->query_result($fieldresult, $index, 'sequence'),      'sequence');
			$this->outputNode($adb->query_result($fieldresult, $index, 'maximumlength'), 'maximumlength');
			$this->outputNode($adb->query_result($fieldresult, $index, 'typeofdata'),    'typeofdata');
			$this->outputNode($adb->query_result($fieldresult, $index, 'quickcreate'),   'quickcreate');
			$this->outputNode($adb->query_result($fieldresult, $index, 'quickcreatesequence'),   'quickcreatesequence');
			$this->outputNode($adb->query_result($fieldresult, $index, 'displaytype'),   'displaytype');
			$this->outputNode($adb->query_result($fieldresult, $index, 'info_type'),     'info_type');

			// Export Entity Identifier Information
			if($fieldname == $entity_fieldname) {
				$this->openNode('entityidentifier');
				$this->outputNode($adb->query_result($entityresult, 0, 'entityidfield'),    'entityidfield');
				$this->outputNode($adb->query_result($entityresult, 0, 'entityidcolumn'), 'entityidcolumn');
				$this->closeNode('entityidentifier');
			}

			// Export picklist values for picklist fields
			if($uitype == '15' || $uitype == '16' || $uitype == '111' || $uitype == '33' || $uitype == '55') {
				$picklistvalues = vtlib_getPicklistValues_AccessibleToAll($fieldname);
				$this->openNode('picklistvalues');
				foreach($picklistvalues as $picklistvalue) {
					$this->outputNode($picklistvalue, 'picklistvalue');
				}
				$this->closeNode('picklistvalues');
			}

			// Export field to module relations
			if($uitype == '10') {
				$relatedmodres = $adb->pquery("SELECT * FROM vtiger_fieldmodulerel WHERE fieldid=?", Array($fieldid));
				$relatedmodcount = $adb->num_rows($relatedmodres);
				if($relatedmodcount) {
					$this->openNode('relatedmodules');
					for($relmodidx = 0; $relmodidx < $relatedmodcount; ++$relmodidx) {
						$this->outputNode($adb->query_result($relatedmodres, $relmodidx, 'relmodule'), 'relatedmodule');
					}
					$this->closeNode('relatedmodules');
				}
			}

			$this->closeNode('field');

		}
		$this->closeNode('fields');
	}

	/**
	 * Export Custom views of the module
	 * @access private
	 */
	function export_CustomViews($moduleInstance) {
		global $adb;

		$customviewres = $adb->pquery("SELECT * FROM vtiger_customview WHERE entitytype = ?", Array($moduleInstance->name));
		$customviewcount=$adb->num_rows($customviewres);

		$this->openNode('customviews');
		for($cvindex = 0; $cvindex < $customviewcount; ++$cvindex) {

			$cvid = $adb->query_result($customviewres, $cvindex, 'cvid');

			$cvcolumnres = $adb->query("SELECT * FROM vtiger_cvcolumnlist WHERE cvid=$cvid");
			$cvcolumncount=$adb->num_rows($cvcolumnres);

			$this->openNode('customview');

			$setdefault = $adb->query_result($customviewres, $cvindex, 'setdefault');
			$setdefault = ($setdefault == 1)? 'true' : 'false';

			$setmetrics = $adb->query_result($customviewres, $cvindex, 'setmetrics');
			$setmetrics = ($setmetrics == 1)? 'true' : 'false';

			$this->outputNode($adb->query_result($customviewres, $cvindex, 'viewname'),   'viewname');
			$this->outputNode($setdefault, 'setdefault');
			$this->outputNode($setmetrics, 'setmetrics');

			$this->openNode('fields');
			for($index = 0; $index < $cvcolumncount; ++$index) {
				$cvcolumnindex = $adb->query_result($cvcolumnres, $index, 'columnindex');
				$cvcolumnname = $adb->query_result($cvcolumnres, $index, 'columnname');
				$cvcolumnnames= explode(':', $cvcolumnname);
				$cvfieldname = $cvcolumnnames[2];

				$this->openNode('field');
				$this->outputNode($cvfieldname, 'fieldname');
				$this->outputNode($cvcolumnindex,'columnindex');

				$cvcolumnruleres = $adb->pquery("SELECT * FROM vtiger_cvadvfilter WHERE cvid=? AND columnname=?",
					Array($cvid, $cvcolumnname));
				$cvcolumnrulecount = $adb->num_rows($cvcolumnruleres);

				if($cvcolumnrulecount) {
					$this->openNode('rules');
					for($rindex = 0; $rindex < $cvcolumnrulecount; ++$rindex) {
						$cvcolumnruleindex = $adb->query_result($cvcolumnruleres, $rindex, 'columnindex');
						$cvcolumnrulecomp  = $adb->query_result($cvcolumnruleres, $rindex, 'comparator');
						$cvcolumnrulevalue = $adb->query_result($cvcolumnruleres, $rindex, 'value');
						$cvcolumnrulecomp  = Vtiger_Filter::translateComparator($cvcolumnrulecomp, true);

						$this->openNode('rule');
						$this->outputNode($cvcolumnruleindex, 'columnindex');
						$this->outputNode($cvcolumnrulecomp, 'comparator');
						$this->outputNode($cvcolumnrulevalue, 'value');
						$this->closeNode('rule');

					}
					$this->closeNode('rules');
				}

				$this->closeNode('field');
			}
			$this->closeNode('fields');

			$this->closeNode('customview');
		}
		$this->closeNode('customviews');
	}

	/**
	 * Export Sharing Access of the module
	 * @access private
	 */
	function export_SharingAccess($moduleInstance) {
		global $adb;

		$deforgshare = $adb->pquery("SELECT * FROM vtiger_def_org_share WHERE tabid=?", Array($moduleInstance->id));
		$deforgshareCount = $adb->num_rows($deforgshare);

		$this->openNode('sharingaccess');
		if($deforgshareCount) {
			for($index = 0; $index < $deforgshareCount; ++$index) {
				$permission = $adb->query_result($deforgshare, $index, 'permission');
				$permissiontext = '';
				if($permission == '0') $permissiontext = 'public_readonly';
				if($permission == '1') $permissiontext = 'public_readwrite';
				if($permission == '2') $permissiontext = 'public_readwritedelete';
				if($permission == '3') $permissiontext = 'private';

				$this->outputNode($permissiontext, 'default');
			}
		}
		$this->closeNode('sharingaccess');		
	}

	/**
	 * Export Events of the module
	 * @access private
	 */
	function export_Events($moduleInstance) {
		$events = Vtiger_Event::getAll($moduleInstance);
		if(!$events) return;

		$this->openNode('events');
		foreach($events as $event) {
			$this->openNode('event');
			$this->outputNode('eventname', $event->eventname);
			$this->outputNode('classname', $event->classname);
			$this->outputNode('filename', $event->filename);
			$this->closeNode('event');
		}
		$this->closeNode('events');
	}

	/**
	 * Export actions (tools) associated with module.
	 * TODO: Need to pickup values based on status for all user (profile)
	 * @access private
	 */
	function export_Actions($moduleInstance) {
		$this->openNode('actions');

		$this->openNode('action');
		$this->outputNode('Export', 'name');
		$this->outputNode('enabled', 'status');
		$this->closeNode('action');

		$this->openNode('action');
		$this->outputNode('Import', 'name');
		$this->outputNode('enabled', 'status');
		$this->closeNode('action');

		$this->closeNode('actions');
	}
}
?>