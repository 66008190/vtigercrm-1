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
 * $Header: vtiger_crm/sugarcrm/phprint.php,v 1.2 2004/10/06 09:02:02 jack Exp $
 * Description: Main file and starting point for the application.  Calls the
 * theme header and footer files defined for the user as well as the module as
 * defined by the input parameters.
 ********************************************************************************/

if (substr(phpversion(), 0, 1) == "5") {
	ini_set("zend.ze1_compatibility_mode", "1");
}

if (ini_get("allow_url_fopen") != 1) {
        die("You must have the allow_url_fopen directive in your php.ini file enabled");
}

$config['start_tag'] = "<!-- startscrmprint -->";
$config['stop_tag'] = "<!-- stopscrmprint -->";
$config['default_charset'] = "utf-8";

require_once("config.php");
require_once("include/utils.php");

if (!isset($_GET['action']) || !isset($_GET['module'])) {
	die("Error: invalid print link");
}
$record = (isset($_GET['record'])) ? $_GET['record'] : "";
$url = $site_URL . "/index.php?module={$_GET['module']}&action={$_GET['action']}&record=$record";
$lang = (empty($_GET['lang'])) ? $default_language : $_GET['lang'];
$app_strings = return_application_language($lang);

$fp = @fopen($url . "&PHPSESSID=" . $_GET['jt'], "rb") or die("Error opening $url<br><br>Is your \$site_URL correct in config.php?");

$get = $page_str = FALSE;
while ($data = fgets($fp, 4096)) {
		if (strpos($data, "<meta http-equiv=\"Content-Type") !== FALSE) {
			// Found character set to use
			$charset = $data;
		}
        if (strpos($data, $config['start_tag']) !== FALSE) {
        	// Found start tag, begin collecting data
        	$get = TRUE;
        }
        if ($get) {
        	// Collect data into $page_str to be later processed
        	$page_str .= $data;
        }
        if (strpos($data, $config['stop_tag']) !== FALSE) {
        	// Found stop tag, stop collecting data
        	$get = FALSE;
        }
}

fclose($fp);
?>
<html>
<head>
<?php
if (isset($charset)) {
	echo $charset . "\n";
}
else {
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . $config['default_charset'] . "\">\n";
}
?>
<style type="text/css" media="all">
IMG { display: none; }
</style>
</head>
<body>
<a href="<?php echo $url; ?>"><< <?php echo $app_strings['LBL_BACK']; ?></a><br><br>
<?php
echo $page_str;
?>
<br><br><a href="<?php echo $url; ?>"><< <?php echo $app_strings['LBL_BACK']; ?></a>
</body>
</html>
