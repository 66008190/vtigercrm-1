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

	
require_once('Smarty_setup.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;
global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;


global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


$smarty = new vtigerCRM_Smarty;

$sql="select * from organizationdetails";
$result = $adb->query($sql);
$organization_name = $adb->query_result($result,0,'organizationame');
$organization_address= $adb->query_result($result,0,'address');
$organization_city = $adb->query_result($result,0,'city');
$organization_state = $adb->query_result($result,0,'state');
$organization_code = $adb->query_result($result,0,'code');
$organization_country = $adb->query_result($result,0,'country');
$organization_phone = $adb->query_result($result,0,'phone');
$organization_fax = $adb->query_result($result,0,'fax');
$organization_website = $adb->query_result($result,0,'website');
$organization_logo = $adb->query_result($result,0,'logo');
$organization_logoname = $adb->query_result($result,0,'logoname');

if (isset($organization_name))
	$smarty->assign("ORGANIZATIONNAME",$organization_name);
if (isset($organization_address))
	$smarty->assign("ORGANIZATIONADDRESS",$organization_address);
if (isset($organization_city))
	$smarty->assign("ORGANIZATIONCITY",$organization_city);
if (isset($organization_state))
	$smarty->assign("ORGANIZATIONSTATE",$organization_state);
if (isset($organization_code))
	$smarty->assign("ORGANIZATIONCODE",$organization_code);
if (isset($organization_country))
    $smarty->assign("ORGANIZATIONCOUNTRY",$organization_country);
if (isset($organization_phone))
	$smarty->assign("ORGANIZATIONPHONE",$organization_phone);
if (isset($organization_fax))
	$smarty->assign("ORGANIZATIONFAX",$organization_fax);
if (isset($organization_website))
	$smarty->assign("ORGANIZATIONWEBSITE",$organization_website);
if (isset($organization_logo))
	$smarty->assign("ORGANIZATIONLOGO",$organization_logo);

$path = "test/logo";
$dir_handle = @opendir($path) or die("Unable to open directory $path");

while ($file = readdir($dir_handle))
{
        $filetyp =str_replace(".",'',strtolower(substr($file, -4)));
   if($organization_logoname==$file)
   {    
        if ($filetyp == 'jpeg' OR $filetyp == 'jpg' OR $filetyp == 'png')
        {
		if($file!="." && $file!="..")
		{
			
 		     $organization_logopath= $path;
		     $logo_name=$file;
		}
			
        }
   }	
}


if (isset($organization_logopath))
	$smarty->assign("ORGANIZATIONLOGOPATH",$path);
if (isset($organization_logoname))
	$smarty->assign("ORGANIZATIONLOGONAME",$logo_name);
closedir($dir_handle);

$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->display('Settings/CompanyInfo.tpl');
?>
