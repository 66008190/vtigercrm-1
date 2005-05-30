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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/include/utils.php,v 1.188 2005/04/29 05:54:39 rank Exp $
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



/** This function returns the name of the person.
  * It currently returns "first last".  It should not put the space if either name is not available.
  * It should not return errors if either name is not available.
  * If no names are present, it will return ""
  * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
  * All Rights Reserved.
  * Contributor(s): ______________________________________..
  */

  require_once('include/database/PearDatabase.php');
  
function return_name(&$row, $first_column, $last_column)
{
	$first_name = "";
	$last_name = "";
	$full_name = "";

	if(isset($row[$first_column]))
	{
		$first_name = stripslashes($row[$first_column]);
	}

	if(isset($row[$last_column]))
	{
		$last_name = stripslashes($row[$last_column]);
	}

	$full_name = $first_name;

	// If we have a first name and we have a last name
	if($full_name != "" && $last_name != "")
	{
		// append a space, then the last name
		$full_name .= " ".$last_name;
	}
	// If we have no first name, but we have a last name
	else if($last_name != "")
	{
		// append the last name without the space.
		$full_name .= $last_name;
	}

	return $full_name;
}


function get_languages()
{
	global $languages;
	return $languages;
}

function get_language_display($key)
{
	global $languages;
	return $languages[$key];
}

function get_assigned_user_name(&$assigned_user_id)
{
	$user_list = &get_user_array(false,"");
	if(isset($user_list[$assigned_user_id]))
	{
		return $user_list[$assigned_user_id];
	}

	return "";
}

function get_user_array($add_blank=true, $status="Active", $assigned_user="")
{
	global $log;
	static $user_array = null;


	if($user_array == null)
	{
		require_once('include/database/PearDatabase.php');
		$db = new PearDatabase();
		$temp_result = Array();
		// Including deleted users for now.
		if (empty($status)) {
				$query = "SELECT id, user_name from users";
		}
		else {
				$query = "SELECT id, user_name from users WHERE status='$status'";
		}
		if (!empty($assigned_user)) {
			 $query .= " OR id='$assigned_user'";
		}

		$query .= " order by user_name ASC";

		//$log->debug("get_user_array query: $query");
		$result = $db->query($query, true, "Error filling in user array: ");

		if ($add_blank==true){
			// Add in a blank row
			$temp_result[''] = '';
		}

		// Get the id and the name.
		while($row = $db->fetchByAssoc($result))
		{
			$temp_result[$row['id']] = $row['user_name'];
		}

		$user_array = &$temp_result;
	}

	return $user_array;
}

function clean($string, $maxLength)
{
	$string = substr($string, 0, $maxLength);
	return escapeshellcmd($string);
}

/**
 * Copy the specified request variable to the member variable of the specified object.
 * Do no copy if the member variable is already set.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function safe_map($request_var, & $focus, $always_copy = false)
{
	safe_map_named($request_var, $focus, $request_var, $always_copy);
}

/**
 * Copy the specified request variable to the member variable of the specified object.
 * Do no copy if the member variable is already set.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function safe_map_named($request_var, & $focus, $member_var, $always_copy)
{
	global $log;
	if (isset($_REQUEST[$request_var]) && ($always_copy || is_null($focus->$member_var))) {
		$log->debug("safe map named called assigning '{$_REQUEST[$request_var]}' to $member_var");
		$focus->$member_var = $_REQUEST[$request_var];
	}
}

/** This function retrieves an application language file and returns the array of strings included in the $app_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_app_list_strings_language($language)
{
	global $app_list_strings, $default_language, $log, $translation_string_prefix;
	$temp_app_list_strings = $app_list_strings;
	$language_used = $language;

	@include("include/language/$language.lang.php");
	if(!isset($app_list_strings))
	{
		$log->warn("Unable to find the application language file for language: ".$language);
		require("include/language/$default_language.lang.php");
		$language_used = $default_language;
	}

	if(!isset($app_list_strings))
	{
		$log->fatal("Unable to load the application language file for the selected language($language) or the default language($default_language)");
		return null;
	}


	$return_value = $app_list_strings;
	$app_list_strings = $temp_app_list_strings;

	return $return_value;
}

/** This function retrieves an application language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_application_language($language)
{
	global $app_strings, $default_language, $log, $translation_string_prefix;
	$temp_app_strings = $app_strings;
	$language_used = $language;

	@include("include/language/$language.lang.php");
	if(!isset($app_strings))
	{
		$log->warn("Unable to find the application language file for language: ".$language);
		require("include/language/$default_language.lang.php");
		$language_used = $default_language;
	}

	if(!isset($app_strings))
	{
		$log->fatal("Unable to load the application language file for the selected language($language) or the default language($default_language)");
		return null;
	}

	// If we are in debug mode for translating, turn on the prefix now!
	if($translation_string_prefix)
	{
		foreach($app_strings as $entry_key=>$entry_value)
		{
			$app_strings[$entry_key] = $language_used.' '.$entry_value;
		}
	}

	$return_value = $app_strings;
	$app_strings = $temp_app_strings;

	return $return_value;
}

/** This function retrieves a module's language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are in the current module, do not call this function unless you are loading it for the first time */
function return_module_language($language, $module)
{
	global $mod_strings, $default_language, $log, $currentModule, $translation_string_prefix;

	if($currentModule == $module && isset($mod_strings) && $mod_strings != null)
	{
		// We should have already loaded the array.  return the current one.
		//$log->fatal("module strings already loaded for language: ".$language." and module: ".$module);
		return $mod_strings;
	}

	$temp_mod_strings = $mod_strings;
	$language_used = $language;

	@include("modules/$module/language/$language.lang.php");
	if(!isset($mod_strings))
	{
		$log->warn("Unable to find the module language file for language: ".$language." and module: ".$module);
		require("modules/$module/language/$default_language.lang.php");
		$language_used = $default_language;
	}

	if(!isset($mod_strings))
	{
		$log->fatal("Unable to load the module($module) language file for the selected language($language) or the default language($default_language)");
		return null;
	}

	// If we are in debug mode for translating, turn on the prefix now!
	if($translation_string_prefix)
	{
		foreach($mod_strings as $entry_key=>$entry_value)
		{
			$mod_strings[$entry_key] = $language_used.' '.$entry_value;
		}
	}

	$return_value = $mod_strings;
	$mod_strings = $temp_mod_strings;

	return $return_value;
}

/** This function retrieves an application language file and returns the array of strings included in the $mod_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_mod_list_strings_language($language,$module)
{
	global $mod_list_strings, $default_language, $log, $currentModule,$translation_string_prefix;

	$language_used = $language;
	$temp_mod_list_strings = $mod_list_strings;

	if($currentModule == $module && isset($mod_list_strings) && $mod_list_strings != null)
	{
		return $mod_list_strings;
	}

	@include("modules/$module/language/$language.lang.php");

	if(!isset($mod_list_strings))
	{
		$log->fatal("Unable to load the application list language file for the selected language($language) or the default language($default_language)");
		return null;
	}

	$return_value = $mod_list_strings;
	$mod_list_strings = $temp_mod_list_strings;

	return $return_value;
}

/** This function retrieves a theme's language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function return_theme_language($language, $theme)
{
	global $mod_strings, $default_language, $log, $currentModule, $translation_string_prefix;

	$language_used = $language;

	@include("themes/$theme/language/$current_language.lang.php");
	if(!isset($theme_strings))
	{
		$log->warn("Unable to find the theme file for language: ".$language." and theme: ".$theme);
		require("themes/$theme/language/$default_language.lang.php");
		$language_used = $default_language;
	}

	if(!isset($theme_strings))
	{
		$log->fatal("Unable to load the theme($theme) language file for the selected language($language) or the default language($default_language)");
		return null;
	}

	// If we are in debug mode for translating, turn on the prefix now!
	if($translation_string_prefix)
	{
		foreach($theme_strings as $entry_key=>$entry_value)
		{
			$theme_strings[$entry_key] = $language_used.' '.$entry_value;
		}
	}

	return $theme_strings;
}



/** If the session variable is defined and is not equal to "" then return it.  Otherwise, return the default value.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
*/
function return_session_value_or_default($varname, $default)
{
	if(isset($_SESSION[$varname]) && $_SESSION[$varname] != "")
	{
		return $_SESSION[$varname];
	}

	return $default;
}

/**
  * Creates an array of where restrictions.  These are used to construct a where SQL statement on the query
  * It looks for the variable in the $_REQUEST array.  If it is set and is not "" it will create a where clause out of it.
  * @param &$where_clauses - The array to append the clause to
  * @param $variable_name - The name of the variable to look for an add to the where clause if found
  * @param $SQL_name - [Optional] If specified, this is the SQL column name that is used.  If not specified, the $variable_name is used as the SQL_name.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
  */
function append_where_clause(&$where_clauses, $variable_name, $SQL_name = null)
{
	if($SQL_name == null)
	{
		$SQL_name = $variable_name;
	}

	if(isset($_REQUEST[$variable_name]) && $_REQUEST[$variable_name] != "")
	{
		array_push($where_clauses, "$SQL_name like '$_REQUEST[$variable_name]%'");
	}
}

/**
  * Generate the appropriate SQL based on the where clauses.
  * @param $where_clauses - An Array of individual where clauses stored as strings
  * @returns string where_clause - The final SQL where clause to be executed.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
  */
function generate_where_statement($where_clauses)
{
	global $log;
	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");
	return $where;
}

/**
 * A temporary method of generating GUIDs of the correct format for our DB.
 * @return String contianing a GUID in the format: aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
 *
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
*/
function create_guid()
{
    $microTime = microtime();
	list($a_dec, $a_sec) = explode(" ", $microTime);

	$dec_hex = sprintf("%x", $a_dec* 1000000);
	$sec_hex = sprintf("%x", $a_sec);

	ensure_length($dec_hex, 5);
	ensure_length($sec_hex, 6);

	$guid = "";
	$guid .= $dec_hex;
	$guid .= create_guid_section(3);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= $sec_hex;
	$guid .= create_guid_section(6);

	return $guid;

}

function create_guid_section($characters)
{
	$return = "";
	for($i=0; $i<$characters; $i++)
	{
		$return .= sprintf("%x", rand(0,15));
	}
	return $return;
}

function ensure_length(&$string, $length)
{
	$strlen = strlen($string);
	if($strlen < $length)
	{
		$string = str_pad($string,$length,"0");
	}
	else if($strlen > $length)
	{
		$string = substr($string, 0, $length);
	}
}

function microtime_diff($a, $b) {
   list($a_dec, $a_sec) = explode(" ", $a);
   list($b_dec, $b_sec) = explode(" ", $b);
   return $b_sec - $a_sec + $b_dec - $a_dec;
}

/**
 * Check if user id belongs to a system admin.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function is_admin($user) {
	if ($user->is_admin == 'on') return true;
	else return false;
}

/**
 * Return the display name for a theme if it exists.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_theme_display($theme) {
	global $theme_name, $theme_description;
	$temp_theme_name = $theme_name;
	$temp_theme_description = $theme_description;

	if (is_file("./themes/$theme/config.php")) {
		@include("./themes/$theme/config.php");
		$return_theme_value = $theme_name;
	}
	else {
		$return_theme_value = $theme;
	}
	$theme_name = $temp_theme_name;
	$theme_description = $temp_theme_description;

	return $return_theme_value;
}

/**
 * Return an array of directory names.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_themes() {
   if ($dir = @opendir("./themes")) {
		while (($file = readdir($dir)) !== false) {
           if ($file != ".." && $file != "." && $file != "CVS" && $file != "Attic" && $file != "akodarkgem" && $file != "bushtree" && $file != "coolblue" && $file != "Amazon" && $file != "busthree") {
			   if(is_dir("./themes/".$file)) {
				   if(!($file[0] == '.')) {
				   	// set the initial theme name to the filename
				   	$name = $file;

				   	// if there is a configuration class, load that.
				   	if(is_file("./themes/$file/config.php"))
				   	{
				   		require_once("./themes/$file/config.php");
				   		$name = $theme_name;
				   	}

				   	if(is_file("./themes/$file/header.php"))
					{
						$filelist[$file] = $name;
					}
				   }
			   }
		   }
	   }
	   closedir($dir);
   }

   ksort($filelist);
   return $filelist;
}

/**
 * THIS FUNCTION IS DEPRECATED AND SHOULD NOT BE USED; USE get_select_options_with_id()
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.
 * param $option_list - the array of strings to that contains the option list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options (&$option_list, $selected, $advsearch='false') {
	return get_select_options_with_id(&$option_list, $selected, $advsearch);
}

/**
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.   This method expects the option list to have keys and values.  The keys are the ids.  The values are the display strings.
 * param $option_list - the array of strings to that contains the option list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options_with_id (&$option_list, $selected_key, $advsearch='false') {
	return get_select_options_with_id_separate_key($option_list, $option_list, $selected_key, $advsearch);
}


/**
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.   This method expects the option list to have keys and values.  The keys are the ids.  The values are the display strings.
 * param $label_list - the array of strings to that contains the option list
 * param $key_list - the array of strings to that contains the values list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options_with_id_separate_key (&$label_list, &$key_list, $selected_key, $advsearch='false') {
	global $app_strings;
	if($advsearch=='true')
	$select_options = "\n<OPTION value=''>--NA--</OPTION>";
	else
	$select_options = "";

	//for setting null selection values to human readable --None--
	$pattern = "/'0?'></";
	$replacement = "''>".$app_strings['LBL_NONE']."<";
	if (!is_array($selected_key)) $selected_key = array($selected_key);

	//create the type dropdown domain and set the selected value if $opp value already exists
	foreach ($key_list as $option_key=>$option_value) {
		$selected_string = '';
		// the system is evaluating $selected_key == 0 || '' to true.  Be very careful when changing this.  Test all cases.
		// The reported bug was only happening with one of the users in the drop down.  It was being replaced by none.
		if (($option_key != '' && $selected_key == $option_key) || ($selected_key == '' && $option_key == '') || (in_array($option_key, $selected_key)))
		{
			$selected_string = 'selected ';
		}

		$html_value = $option_key;

		$select_options .= "\n<OPTION ".$selected_string."value='$html_value'>$label_list[$option_key]</OPTION>";
	}
	$select_options = preg_replace($pattern, $replacement, $select_options);

	return $select_options;
}


/**
 * Create javascript to clear values of all elements in a form.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_clear_form_js () {
$the_script = <<<EOQ
<script type="text/javascript" language="JavaScript">
<!-- Begin
function clear_form(form) {
	for (j = 0; j < form.elements.length; j++) {
		if (form.elements[j].type == 'text' || form.elements[j].type == 'select-one') {
			form.elements[j].value = '';
		}
	}
}
//  End -->
</script>
EOQ;

return $the_script;
}

/**
 * Create javascript to set the cursor focus to specific field in a form
 * when the screen is rendered.  The field name is currently hardcoded into the
 * the function.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_set_focus_js () {
//TODO Clint 5/20 - Make this function more generic so that it can take in the target form and field names as variables
$the_script = <<<EOQ
<script type="text/javascript" language="JavaScript">
<!-- Begin
function set_focus() {
	if (document.forms.length > 0) {
		for (i = 0; i < document.forms.length; i++) {
			for (j = 0; j < document.forms[i].elements.length; j++) {
				var field = document.forms[i].elements[j];
				if ((field.type == "text" || field.type == "textarea" || field.type == "password") &&
						!field.disabled && (field.name == "first_name" || field.name == "name")) {
					field.focus();
                    if (field.type == "text") {
                        field.select();
                    }
					break;
	    		}
			}
      	}
   	}
}
//  End -->
</script>
EOQ;

return $the_script;
}

/**
 * Very cool algorithm for sorting multi-dimensional arrays.  Found at http://us2.php.net/manual/en/function.array-multisort.php
 * Syntax: $new_array = array_csort($array [, 'col1' [, SORT_FLAG [, SORT_FLAG]]]...);
 * Explanation: $array is the array you want to sort, 'col1' is the name of the column
 * you want to sort, SORT_FLAGS are : SORT_ASC, SORT_DESC, SORT_REGULAR, SORT_NUMERIC, SORT_STRING
 * you can repeat the 'col',FLAG,FLAG, as often you want, the highest prioritiy is given to
 * the first - so the array is sorted by the last given column first, then the one before ...
 * Example: $array = array_csort($array,'town','age',SORT_DESC,'name');
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function array_csort() {
   $args = func_get_args();
   $marray = array_shift($args);
   $i = 0;

   $msortline = "return(array_multisort(";
   foreach ($args as $arg) {
	   $i++;
	   if (is_string($arg)) {
		   foreach ($marray as $row) {
			   $sortarr[$i][] = $row[$arg];
		   }
	   } else {
		   $sortarr[$i] = $arg;
	   }
	   $msortline .= "\$sortarr[".$i."],";
   }
   $msortline .= "\$marray));";

   eval($msortline);
   return $marray;
}

/**
 * Converts localized date format string to jscalendar format
 * Example: $array = array_csort($array,'town','age',SORT_DESC,'name');
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function parse_calendardate($local_format) {
	/* temporarily disabled until international date formats are fixed
	preg_match("/\(?([^-]{1})[^-]*-([^-]{1})[^-]*-([^-]{1})[^-]*\)/", $local_format, $matches);
	if (isset($matches[1]) && isset($matches[2]) && isset($matches[3])) {
		$calendar_format = "%" . $matches[1] . "-%" . $matches[2] . "-%" . $matches[3];
		return str_replace(array("y", "�, "a", "j"), array("Y", "Y", "Y", "d"), $calendar_format);
	}
	else {
		return "%Y-%m-%d";
	} */
	global $current_user;
	if($current_user->date_format == 'dd-mm-yyyy')
	{
		$dt_popup_fmt = "%d-%m-%Y";
	}
	elseif($current_user->date_format == 'mm-dd-yyyy')
	{
		$dt_popup_fmt = "%m-%d-%Y";
	}
	elseif($current_user->date_format == 'yyyy-mm-dd')
	{
		$dt_popup_fmt = "%Y-%m-%d";
	}
	return $dt_popup_fmt;
	//return "%Y-%m-%d";
}

function set_default_config(&$defaults)
{

	foreach ($defaults as $name=>$value)
	{
		if ( ! isset($GLOBALS[$name]) )
		{
			$GLOBALS[$name] = $value;
		}
	}
}

$toHtml = array(
        '"' => '&quot;',
        '<' => '&lt;',
        '>' => '&gt;',
        '& ' => '&amp; ',
        "'" =>  '&#039;',
);

function to_html($string, $encode=true){
        global $toHtml;
        if($encode && is_string($string)){//$string = htmlentities($string, ENT_QUOTES);
        $string = str_replace(array_keys($toHtml), array_values($toHtml), $string);
        }
        return $string;
}

function from_html($string, $encode=true){
        global $toHtml;
        //if($encode && is_string($string))$string = html_entity_decode($string, ENT_QUOTES);
        if($encode && is_string($string)){
                $string = str_replace(array_values($toHtml), array_keys($toHtml), $string);
        }
        return $string;
}


function get_group_options()
{
	global $adb;
	$sql = "select name from groups";
	$result = $adb->query($sql);
	return $result;
}


function get_assigned_user_or_group_name($id,$module)
{
	global $adb;

	//it might so happen that an entity is assigned to a group but at that time the group has no members. even in this case, the query should return a valid value and not just blank

  if($module == 'Leads')
  {
	 //$sql = "select (case when (user_name is null) then  (users2group.groupname) else (user_name) end) as name from leads left join users on users.id= assigned_user_id left join users2group on users2group.groupname=leads.assigned_user_id where leads.id='" .$id ."'";
    //$sql = "select (case when (user_name is null) then  (groups.name) else (user_name) end) as name from leads left join users on users.id= assigned_user_id left join groups on groups.name=leads.assigned_user_id where leads.id='" .$id ."'";

   $sql="select (case when (user_name is null) then  (leadgrouprelation.groupname) else (user_name) end) as name from leads left join users on users.id= assigned_user_id left join leadgrouprelation on leadgrouprelation.leadid=leads.id where leads.deleted=0 and leads.id='". $id ."'";
   
  }
  else if($module == 'Tasks')
  {
       $sql="select (case when (user_name is null) then  (taskgrouprelation.groupname) else (user_name) end) as name from tasks left join users on users.id= assigned_user_id left join taskgrouprelation on taskgrouprelation.taskid=tasks.id where tasks.deleted=0 and tasks.id='". $id ."'";

       //$sql = "select (case when (user_name is null) then  (groups.name) else (user_name) end) as name from tasks left join users on users.id= assigned_user_id left join groups on groups.name=tasks.assigned_user_id where tasks.id='" .$id ."'";

  }
  else if($module == 'Calls')
  {
       $sql="select (case when (user_name is null) then  (callgrouprelation.groupname) else (user_name) end) as name from calls left join users on users.id= assigned_user_id left join callgrouprelation on callgrouprelation.callid=calls.id where calls.deleted=0 and calls.id='". $id ."'";

       //     $sql = "select (case when (user_name is null) then  (groups.name) else (user_name) end) as name from calls left join users on users.id= assigned_user_id left join groups on groups.name=calls.assigned_user_id where calls.id='" .$id ."'";

  }

	$result = $adb->query($sql);
	$tempval = $adb->fetch_row($result);
	return $tempval[0];
}

function getTabname($tabid)
{
        global $adb;
	$sql = "select tablabel from tab where tabid='".$tabid."'";
	$result = $adb->query($sql);
	$tabname=  $adb->query_result($result,0,"tablabel");
	return $tabname;

}

function getTabid($module)
{
        global $adb;
	$sql = "select tabid from tab where name='".$module."'";
	$result = $adb->query($sql);
	$tabid=  $adb->query_result($result,0,"tabid");
	return $tabid;

}

function getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype)
{
	global $adb;
	global $theme;
	global $mod_strings;
	global $app_strings;
	global $current_user;
	$value = $col_fields[$fieldname];
	$custfld = '';

	if($generatedtype == 2)
		$mod_strings[$fieldlabel] = $fieldlabel;

	if($uitype == 5 || $uitype == 6 || $uitype ==23)
	{
		if($value=='')
                {
                        $disp_value=getNewDisplayDate();
                }
                else
                {
                        $disp_value = getDisplayDate($value);
                }

		$custfld .= '<td width="20%" class="dataLabel">';

		if($uitype == 6 || $uitype == 23)
			$custfld .= '<font color="red">*</font>';

		$custfld .= $mod_strings[$fieldlabel].':</td>';
		$date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
		$custfld .= '<td width="30%"><input name="'.$fieldname.'" id="jscal_field_'.$fieldname.'" type="text" size="11" maxlength="10" value="'.$disp_value.'"> <img src="themes/'.$theme.'/images/calendar.gif" id="jscal_trigger_'.$fieldname.'">';
		if($uitype == 6)
                {
			if($col_fields['time_start']!='')
                        {
                                $curr_time = $col_fields['time_start'];
                        }
                        else
                        {
                                $curr_time = date('H:i');
                        }
                        $custfld .= '&nbsp; <input name="time_start" size="5" maxlength="5" type="text" value="'.$curr_time.'">';
                }
		if($uitype == 5 || $uitype == 23)
			$custfld .= '<br><font size=1><em old="(yyyy-mm-dd)">('.$current_user->date_format.')</em></font></td>';
		else
			$custfld .= '<br><font size=1><em old="(yyyy-mm-dd 24:00)">('.$current_user->date_format.' '.$app_strings['YEAR_MONTH_DATE'].')</em></font></td>';
		$custfld .= '<script type="text/javascript">';
		$custfld .= 'Calendar.setup ({';
				$custfld .= 'inputField : "jscal_field_'.$fieldname.'", ifFormat : "'.$date_format.'", showsTime : false, button : "jscal_trigger_'.$fieldname.'", singleClick : true, step : 1';
				$custfld .= '});';
		$custfld .= '</script>';
	}
	elseif($uitype == 15 || $uitype == 16)
	{
		$custfld .= '<td width="20%" class="dataLabel">';

		if($uitype == 16)
			$custfld .= '<font color="red">*</font>';

		$custfld .= $mod_strings[$fieldlabel].':</td>';
		//$pick_query="select * from ".$fieldname." order by sortorderid";
		$pick_query="select * from ".$fieldname;
		$pickListResult = $adb->query($pick_query);
		$noofpickrows = $adb->num_rows($pickListResult);
		$custfld .= '<td width="30%"><select name="'.$fieldname.'">';
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue=$adb->query_result($pickListResult,$j,strtolower($fieldname));

			if($value == $pickListValue)
			{
				$chk_val = "selected";	
			}
			else
			{	
				$chk_val = '';
			}

			$custfld .= '<OPTION value="'.$pickListValue.'" '.$chk_val.'>'.$pickListValue.'</OPTION>';
		}
		$custfld .= '</td>';
	}
	elseif($uitype == 19)
	{
		$custfld .= '<td width="20%" class="dataLabel" valign="top">'.$mod_strings[$fieldlabel].':</td>';
        	$custfld .= '<td colspan=3><textarea name="'.$fieldname.'" cols="118" rows="30">'.$value.'</textarea></td>';
	}
	elseif($uitype == 21)
	{
		$custfld .= '<td width="20%" class="dataLabel" valign="top">'.$mod_strings[$fieldlabel].':</td>';
        	$custfld .= '<td><textarea name="'.$fieldname.'" cols="30" rows="2">'.$value.'</textarea></td>';
	}
	elseif($uitype == 22)
	{
		$custfld .= '<td width="20%" class="dataLabel" valign="top"><font color="red">*</font> '.$mod_strings[$fieldlabel].':</td>';
        	$custfld .= '<td><textarea name="'.$fieldname.'" cols="30" rows="2">'.$value.'</textarea></td>';
	}
	elseif($uitype == 52 || $uitype == 77)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		global $current_user;
		if($value != '')
		{
			$assigned_user_id = $value;	
		}
		else
		{
			$assigned_user_id = $current_user->id;
		}
		if($uitype == 52)
		{
			$combo_lbl_name = 'assigned_user_id';
		}
		elseif($uitype == 77)
		{
			$combo_lbl_name = 'assigned_user_id1';
		}

		$users_combo = get_select_options_with_id(get_user_array(FALSE, "Active", $assigned_user_id), $assigned_user_id);
                $custfld .= '<td width="30%"><select name="'.$combo_lbl_name.'">'.$users_combo.'</select></td>';
	}
	elseif($uitype == 53)     
	{  
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
          
          $result = get_group_options();
          $nameArray = $adb->fetch_array($result);
	  	
          
          global $current_user;
	  if($value != '' && $value != 0)
	  {
		  $assigned_user_id = $value;
		  $user_checked = "checked";
		  $team_checked = '';
		  $user_style='display:block';
		  $team_style='display:none';			
	  }
	  else
	  {
		  if($value=='0')
		  {
			  $record = $col_fields["record_id"];
			  $module = $col_fields["record_module"];

			  $selected_groupname = getGroupName($record, $module);
			  $user_checked = '';
		          $team_checked = 'checked';
			  $user_style='display:none';
		  	  $team_style='display:block';
		  }
		  else	
		  {				
			  $assigned_user_id = $current_user->id;
			  $user_checked = "checked";
		          $team_checked = '';
			  $user_style='display:block';
		  	  $team_style='display:none';
		  }	
	  }
         
          
          $users_combo = get_select_options_with_id(get_user_array(FALSE, "Active", $assigned_user_id), $assigned_user_id);
          
          $GROUP_SELECT_OPTION = '<td width=30%><input type="radio"
          name="assigntype" value="U" '.$user_checked.'
          onclick="toggleAssignType(this.value)">'.$app_strings['LBL_USER'].'<input
          type="radio" name="assigntype" value="T"'.$team_checked.'
          onclick="toggleAssignType(this.value)">'.$app_strings['LBL_TEAM'].'<br><span
          id="assign_user" style="'.$user_style.'"><select name="assigned_user_id">';
          
          $GROUP_SELECT_OPTION .= $users_combo;
          
          $GROUP_SELECT_OPTION .= '</select></span>';
          
          $GROUP_SELECT_OPTION .='<span id="assign_team" style="'.$team_style.'"><select name="assigned_group_name">';
          
          
          do
          {
            $groupname=$nameArray["name"];
	    $selected = '';	
	    if($groupname == $selected_groupname)
	    {
		$selected = "selected";
            }	
            $GROUP_SELECT_OPTION .= '<option value="';
            $GROUP_SELECT_OPTION .=  $groupname;
            $GROUP_SELECT_OPTION .=  '" '.$selected.'>';
            $GROUP_SELECT_OPTION .= $nameArray["name"];
            $GROUP_SELECT_OPTION .= '</option>';
          }while($nameArray = $adb->fetch_array($result));
//          $GROUP_SELECT_OPTION .='<option value=none>'.$app_strings['LBL_NONE_NO_LINE'].'</option>';
          $GROUP_SELECT_OPTION .= ' </select></td>';
          
          $custfld .= $GROUP_SELECT_OPTION;
          
          
        }
	elseif($uitype == 51 || $uitype == 50)
	{

                if(isset($_REQUEST['account_id']) && $_REQUEST['account_id'] != '')
                        $value = $_REQUEST['account_id'];	

		if($value != '')
		{		
			$account_name = getAccountName($value);	
		}
		$custfld .= '<td width="20%" class="dataLabel">';
		if($uitype==50)
			$custfld .= '<font color="red">*</font>';
		$custfld .= $mod_strings[$fieldlabel].':</td>';


		$custfld .= '<td width="30%" valign="top"  class="dataField"><input readonly name="account_name" type="text" value="'.$account_name.'"><input name="account_id" type="hidden" value="'.$value.'">&nbsp;<input title="Change" accessKey="Change" type="button" class="button" value="'.$app_strings['LBL_CHANGE_BUTTON_LABEL'].'" name="btn1" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");\'></td>';	
	}
	elseif($uitype == 54)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$pick_query="select * from groups";
		$pickListResult = $adb->query($pick_query);
		$noofpickrows = $adb->num_rows($pickListResult);
		$custfld .= '<td width="30%"><select name="'.$fieldname.'">';
		$custfld .= '<OPTION value="selectagroup" selected>'.$app_strings['LBL_SELECT_GROUP'].'</OPTION>';
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue=$adb->query_result($pickListResult,$j,"name");

			if($value == $pickListValue)
			{
				$chk_val = "selected";	
			}
			else
			{	
				$chk_val = '';	
			}

			$custfld .= '<OPTION value="'.$pickListValue.'" '.$chk_val.'>'.$pickListValue.'</OPTION>';
		}
		$custfld .= '</td>';
		
	}
	elseif($uitype == 55)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';

		$pick_query="select * from salutationtype order by sortorderid";
		$pickListResult = $adb->query($pick_query);
		$noofpickrows = $adb->num_rows($pickListResult);
		$salt_value = $col_fields["salutationtype"];
		$custfld .= '<td width="30%"><select name="salutationtype">';
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue=$adb->query_result($pickListResult,$j,"salutationtype");
			
			if($salt_value == $pickListValue)
			{
				$chk_val = "selected";	
			}
			else
			{	
				$chk_val = '';	
			}

			$custfld .= '<OPTION value="'.$pickListValue.'" '.$chk_val.'>'.$pickListValue.'</OPTION>';
		}
		$custfld .= '</select><input name="'.$fieldname.'" type="text" size="25" maxlength="'.$maxlength.'" value="'.$value.'"></td>';
	}
	elseif($uitype == 59)
	{
                if(isset($_REQUEST['parent_id']) & $_REQUEST['parent_id'] != '')
                        $value = $_REQUEST['parent_id'];

		if($value != '')
		{		
			$product_name = getProductName($value);	
		}
               $custfld .= '<td width="20%" class="dataLabel">';
               $custfld .= $mod_strings[$fieldlabel].':</td>';
	       $custfld .= '<td width="30%"><input name="product_id" type="hidden" value="'.$value.'"><input name="product_name" readonly type="text" value="'.$product_name.'"> <input title="Change [Alt+G]" accessKey="G" type="button" class="button" value="'.$app_strings['LBL_CHANGE_BUTTON_LABEL'].'" name="button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\'></td>';		

	}
	elseif($uitype == 63)
        {
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                if($value=='')
                $value=1;
                $custfld .= '<td width="30%"><input name="'.$fieldname.'" type="text" size="2" maxlength="'.$maxlength.'" value="'.$value.'">&nbsp;';
                $pick_query="select * from duration_minutes order by sortorderid";
                $pickListResult = $adb->query($pick_query);
                $noofpickrows = $adb->num_rows($pickListResult);
                $salt_value = $col_fields["duration_minutes"];
                $custfld .= '<select name="duration_minutes">';
                for($j = 0; $j < $noofpickrows; $j++)
                {
                        $pickListValue=$adb->query_result($pickListResult,$j,"duration_minutes");

                        if($salt_value == $pickListValue)
                        {
                                $chk_val = "selected";
                        }
                        else
                        {
                                $chk_val = '';
                        }

                        $custfld .= '<OPTION value="'.$pickListValue.'" '.$chk_val.'>'.$pickListValue.'</OPTION>';
                }
                $custfld .= '</select>';
                $custfld .= $app_strings['LBL_HOUR_AND_MINUTE'].'</td>';
        }
	elseif($uitype == 64)
        {
                $custfld .= '<td width="20%" class="dataLabel">';
                $custfld .= $mod_strings[$fieldlabel].':</td>';
                $date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
                $custfld .= '<td width="30%"><input name="'.$fieldname.'" id="jscal_field" type="text" size="11" readonly maxlength="10" value="'.$value.'"> <img src="themes/'.$theme.'/images/calendar.gif" id="jscal_trigger">&nbsp;<input name="duetime" size="5" maxlength="5" readonly type="text" value=""> <input name="duedate_flag" type="checkbox" language="javascript" onclick="set_values(this.form)" checked>'.$mod_strings["LBL_NONE"].'<br><font size="1"><em>'.$mod_strings["DATE_FORMAT"].'</em></font></td>';
                $custfld .= '<script type="text/javascript">';
                $custfld .= 'Calendar.setup ({';
                                $custfld .= 'inputField : "jscal_field", ifFormat : "'.$date_format.'", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1';
                                $custfld .= '});';
                $custfld .= '</script>';
        }
	elseif($uitype == 56)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		if($value == 1)
		{
			$custfld .='<td width="30%"><input name="'.$fieldname.'" type="checkbox"  checked></td>';
		}
		else
		{
			$custfld .='<td width="30%"><input name="'.$fieldname.'" type="checkbox"></td>';
		}
	}
	elseif($uitype == 57)
	{
		//if(isset($_REQUEST['contact_id']) && $_REQUEST['contact_id'] != '')
		//	$value = $_REQUEST['contact_id'];

		if($value != '')
               {
                       $contact_name = getContactName($value);
               }
	       elseif(isset($_REQUEST['contact_id']) && $_REQUEST['contact_id'] != '')
	       {
			$value = $value = $_REQUEST['contact_id'];
			$contact_name = getContactName($value);
	       }		 	
		$custfld .= '<td width="20%" valign="center" class="dataLabel">'.$mod_strings[$fieldlabel].'</td>';
        	$custfld .= '<td width="30%"><input name="contact_name" readonly type="text" value="'.$contact_name.'"><input name="contact_id" type="hidden" value="'.$value.'">&nbsp;<input title="Change" accessKey="" type="button" class="button" value="'.$app_strings['LBL_CHANGE_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\'></td>';	
	}
        elseif($uitype == 61)
        {
                global $current_user;
                if($value != '')
                {
                        $assigned_user_id = $value;
                }
                else
                {
                        $assigned_user_id = $current_user->id;
                }
                if($value!='')
                        $filename=' [ '.$value. ' ]';
		$custfld .= '<td width="20%" valign="top" class="dataLabel">'.$mod_strings[$fieldlabel].'</td>';
                $custfld .='<td colspan="3"><input name="'.$fieldname.'" type="file" size="60" value="'.$value.
'"/><input type="hidden" name="filename" value=""/><input type="hidden" name="id" value=""/>'.$filename.'</td>';
        }
        elseif($uitype == 62)
        {
                if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '')
                        $value = $_REQUEST['parent_id'];

		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads")
			{
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");
				$parent_name = $first_name.' '.$last_name;
				$lead_selected = "selected";

			}
			elseif($parent_module == "Accounts")
			{
				$sql = "select * from  account where accountid=".$value;
				$result = $adb->query($sql);
				$parent_name = $adb->query_result($result,0,"accountname");
				$account_selected = "selected";

			}
			elseif($parent_module == "Potentials")
			{
				$sql = "select * from  potential where potentialid=".$value;
				$result = $adb->query($sql);
				$parent_name = $adb->query_result($result,0,"potentialname");
				$contact_selected = "selected";

			}
			elseif($parent_module == "Products")
			{
				$sql = "select * from  products where productid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"productname");
				$product_selected = "selected";

			}
		}
		$custfld .= '<td width="20%" class="dataLabel"><select name="parent_type" onChange=\'document.EditView.parent_name.value=""; document.EditView.parent_id.value=""\'>';
                $custfld .= '<OPTION value="Leads" '.$lead_selected.'>'.$app_strings['COMBO_LEADS'].'</OPTION>';
                $custfld .= '<OPTION value="Accounts" '.$account_selected.'>'.$app_strings['COMBO_ACCOUNTS'].'</OPTION>';
                $custfld .= '<OPTION value="Potentials" '.$contact_selected.'>'.$app_strings['COMBO_POTENTIALS'].'</OPTION>';
                $custfld .= '<OPTION value="Products" '.$product_selected.'>'.$app_strings['COMBO_PRODUCTS'].'</OPTION></select></td>';

	        $custfld .= '<td width="30%"><input name="parent_id" type="hidden" value="'.$value.'"><input name="parent_name" readonly type="text" value="'.$parent_name.'"> <input title="Change [Alt+G]" accessKey="G" type="button" class="button" value="'.$app_strings['LBL_CHANGE_BUTTON_LABEL'].'" name="button" LANGUAGE=javascript onclick=\'return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\'></td>';

        }
        elseif($uitype == 66)
        {
                if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '')
                        $value = $_REQUEST['parent_id'];

		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads")
			{
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");
				$parent_name = $first_name.' '.$last_name;
				$lead_selected = "selected";

			}
			elseif($parent_module == "Accounts")
			{
				$sql = "select * from  account where accountid=".$value;
				$result = $adb->query($sql);
				$parent_name = $adb->query_result($result,0,"accountname");
				$account_selected = "selected";

			}
			elseif($parent_module == "Potentials")
			{
				$sql = "select * from  potential where potentialid=".$value;
				$result = $adb->query($sql);
				$parent_name = $adb->query_result($result,0,"potentialname");
				$contact_selected = "selected";

			}
		
		}
		$custfld .= '<td width="20%" class="dataLabel"><select name="parent_type" onChange=\'document.EditView.parent_name.value=""; document.EditView.parent_id.value=""\'>';
                $custfld .= '<OPTION value="Leads" '.$lead_selected.'>'.$app_strings['COMBO_LEADS'].'</OPTION>';
                $custfld .= '<OPTION value="Accounts" '.$account_selected.'>'.$app_strings['COMBO_ACCOUNTS'].'</OPTION>';
                $custfld .= '<OPTION value="Potentials" '.$contact_selected.'>'.$app_strings['COMBO_POTENTIALS'].'</OPTION>';

	        $custfld .= '<td width="30%"><input name="parent_id" type="hidden" value="'.$value.'"><input name="parent_name" readonly type="text" value="'.$parent_name.'"> <input title="Change [Alt+G]" accessKey="G" type="button" class="button" value="'.$app_strings['LBL_CHANGE_BUTTON_LABEL'].'" name="button" LANGUAGE=javascript onclick=\'return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\'></td>';

        }
        elseif($uitype == 67)
        {
                if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '')
                        $value = $_REQUEST['parent_id'];

		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads")
			{
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");
				$parent_name = $first_name.' '.$last_name;
				$lead_selected = "selected";

			}
			elseif($parent_module == "Contacts")
			{
				$sql = "select * from  contactdetails where contactid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");
				$parent_name = $first_name.' '.$last_name;
				$contact_selected = "selected";

			}
		}
		$custfld .= '<td width="20%" class="dataLabel"><select name="parent_type" onChange=\'document.EditView.parent_name.value=""; document.EditView.parent_id.value=""\'>';
                $custfld .= '<OPTION value="Leads" '.$lead_selected.'>'.$app_strings['COMBO_LEADS'].'</OPTION>';
                $custfld .= '<OPTION value="Contacts" '.$contact_selected.'>'.$app_strings['COMBO_CONTACTS'].'</OPTION>';

	        $custfld .= '<td width="30%"><input name="parent_id" type="hidden" value="'.$value.'"><input name="parent_name" readonly type="text" value="'.$parent_name.'"> <input title="Change [Alt+G]" accessKey="G" type="button" class="button" value="'.$app_strings['LBL_CHANGE_BUTTON_LABEL'].'" name="button" LANGUAGE=javascript onclick=\'return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\'></td>';

        }

	elseif($uitype == 65)
	{
	
		$custfld .= '<td width="20%" class="dataLabel"><select name="parent_type" onChange=\'document.EditView.parent_name.value=""; document.EditView.parent_id.value=""\'>
                <OPTION value="Leads">'.$app_strings['COMBO_LEADS'].'</OPTION>
                <OPTION value="Accounts">'.$app_strings['COMBO_ACCOUNTS'].'</OPTION>
                <OPTION value="Potentials">'.$app_strings['COMBO_POTENTIALS'].'</OPTION>
                <OPTION value="Products">'.$app_strings['COMBO_PRODUCTS'].'</OPTION></select></td>';

		$custfld .= '<td width="30%"><input name="parent_id" type="hidden" value=""><input name="parent_name" readonly type="text" value=""> <input title="Change [Alt+G]" accessKey="G" type="button" class="button" value="'.$app_strings['LBL_CHANGE_BUTTON_LABEL'].'" name="button" LANGUAGE=javascript onclick=\'return window.open("index.php?module="+ document.EditView.parent_type.value + "&action=Popup&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\'></td>';	
	}
	elseif($uitype == 71 || $uitype == 72)
	{
		$custfld .= '<td width="20%" class="dataLabel">';

		if($uitype == 72)
		{
			$custfld .= '<font color="red">*</font>';
		}

		$disp_currency = getDisplayCurrency();

		$custfld .= $mod_strings[$fieldlabel].': ('.$disp_currency.')</td>';

		$custfld .= '<td width="30%"><input name="'.$fieldname.'" type="text" size="25" maxlength="'.$maxlength.'" value="'.$value.'"></td>';
	}
	elseif($uitype == 75)
	{

		if($value != '')
               {
                       $vendor_name = getVendorName($value);
               }
	       elseif(isset($_REQUEST['vendor_id']) && $_REQUEST['vendor_id'] != '')
	       {
			$value = $_REQUEST['vendor_id'];
			$vendor_name = getVendorName($value);
	       }		 	
		$custfld .= '<td width="20%" valign="center" class="dataLabel">'.$mod_strings[$fieldlabel].'</td>';
        	$custfld .= '<td width="30%"><input name="vendor_name" readonly type="text" value="'.$vendor_name.'"><input name="vendor_id" type="hidden" value="'.$value.'">&nbsp;<input title="Change" accessKey="" type="button" class="button" value="'.$app_strings['LBL_CHANGE_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Products&action=VendorPopup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\'></td>';	
	}
	elseif($uitype == 76)
	{

		if($value != '')
               {
                       $potential_name = getPotentialName($value);
               }
	       elseif(isset($_REQUEST['potential_id']) && $_REQUEST['potential_id'] != '')
	       {
			$value = $_REQUEST['potental_id'];
			$potential_name = getPotentialName($value);
	       }		 	
		$custfld .= '<td width="20%" valign="center" class="dataLabel">'.$mod_strings[$fieldlabel].'</td>';
        	$custfld .= '<td width="30%"><input name="potential_name" readonly type="text" value="'.$potential_name.'"><input name="potential_id" type="hidden" value="'.$value.'">&nbsp;<input title="Change" accessKey="" type="button" class="button" value="'.$app_strings['LBL_CHANGE_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Potentials&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\'></td>';	
	}
	elseif($uitype == 78)
	{

		if($value != '')
               {
                       $quote_name = getQuoteName($value);
               }
	       elseif(isset($_REQUEST['quote_id']) && $_REQUEST['quote_id'] != '')
	       {
			$value = $_REQUEST['quote_id'];
			$potential_name = getQuoteName($value);
	       }		 	
		$custfld .= '<td width="20%" valign="center" class="dataLabel">'.$mod_strings[$fieldlabel].'</td>';
        	$custfld .= '<td width="30%"><input name="quote_name" readonly type="text" value="'.$quote_name.'"><input name="quote_id" type="hidden" value="'.$value.'">&nbsp;<input title="Change" accessKey="" type="button" class="button" value="'.$app_strings['LBL_CHANGE_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\'></td>';	
	}
	elseif($uitype == 79)
	{

		if($value != '')
               {
                       $purchaseorder_name = getPoName($value);
               }
	       elseif(isset($_REQUEST['purchaseorder_id']) && $_REQUEST['purchaseorder_id'] != '')
	       {
			$value = $_REQUEST['purchaseorder_id'];
			$purchaseorder_name = getPoName($value);
	       }		 	
		$custfld .= '<td width="20%" valign="center" class="dataLabel">'.$mod_strings[$fieldlabel].'</td>';
        	$custfld .= '<td width="30%"><input name="purchaseorder_name" readonly type="text" value="'.$purchaseorder_name.'"><input name="purchaseorder_id" type="hidden" value="'.$value.'">&nbsp;<input title="Change" accessKey="" type="button" class="button" value="'.$app_strings['LBL_CHANGE_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Orders&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\'></td>';	
	}
	elseif($uitype == 80)
	{

		if($value != '')
               {
                       $salesorder_name = getSoName($value);
               }
	       elseif(isset($_REQUEST['salesorder_id']) && $_REQUEST['salesorder_id'] != '')
	       {
			$value = $_REQUEST['salesorder_id'];
			$salesorder_name = getSoName($value);
	       }		 	
		$custfld .= '<td width="20%" valign="center" class="dataLabel">'.$mod_strings[$fieldlabel].'</td>';
        	$custfld .= '<td width="30%"><input name="salesorder_name" readonly type="text" value="'.$salesorder_name.'"><input name="salesorder_id" type="hidden" value="'.$value.'">&nbsp;<input title="Change" accessKey="" type="button" class="button" value="'.$app_strings['LBL_CHANGE_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Orders&action=SalesOrderPopup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");\'></td>';	
	}
	elseif($uitype == 30)
	{
		$rem_days = 0;
		$rem_hrs = 0;
		$rem_min = 0;
		if($value!='')
		$SET_REM = "CHECKED";
		$rem_days = floor($col_fields[$fieldname]/(24*60));
		$rem_hrs = floor(($col_fields[$fieldname]-$rem_days*24*60)/60);
		$rem_min = ($col_fields[$fieldname]-$rem_days*24*60)%60;

                $custfld .= '<td width="20%" class="dataLabel" valign="top">'.$mod_strings[$fieldlabel].':</td>';
                $custfld .= '<td valign="top" colspan=3>&nbsp;<input type="radio" name="set_reminder" value="Yes" '.$SET_REM.'>&nbsp;'.$mod_strings['LBL_YES'].'&nbsp;<input type="radio" name="set_reminder" value="No">&nbsp;'.$mod_strings['LBL_NO'].'&nbsp;';
		$day_options = getReminderSelectOption(0,31,'remdays',$rem_days);
		$hr_options = getReminderSelectOption(0,23,'remhrs',$rem_hrs);
		$min_options = getReminderSelectOption(1,59,'remmin',$rem_min);
		$custfld .= '&nbsp;&nbsp;'.$day_options.' &nbsp;'.$mod_strings['LBL_DAYS'].'&nbsp;&nbsp;'.$hr_options.'&nbsp;'.$mod_strings['LBL_HOURS'].'&nbsp;&nbsp;'.$min_options.'&nbsp;'.$mod_strings['LBL_MINUTES'].'&nbsp;&nbsp;'.$mod_strings['LBL_BEFORE_EVENT'].'</td>';
		$SET_REM = '';
	}
	else
	{
		$custfld .= '<td width="20%" class="dataLabel">';

		if($uitype == 2)
			$custfld .= '<font color="red">*</font>';

		$custfld .= $mod_strings[$fieldlabel].':</td>';

		$custfld .= '<td width="30%"><input name="'.$fieldname.'" type="text" size="25" maxlength="'.$maxlength.'" value="'.$value.'"></td>';
	}

	return $custfld;
}

function getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype)
{
	global $adb;
	global $mod_strings;
	global $app_strings;
	global $current_user;
	$custfld = '';
	$value ='';

	if($generatedtype == 2)
		$mod_strings[$fieldlabel] = $fieldlabel;

        if($col_fields[$fieldname]=='--None--')
                $col_fields[$fieldname]='';
	
	if($uitype == 13)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$custfld .= '<td width="30%" valign="top" class="dataField"><a href="mailto:'.$col_fields[$fieldname].'">'.$col_fields[$fieldname].'</a></td>';
	}
	elseif($uitype == 17)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$custfld .= '<td width="30%" valign="top" class="dataField"><a href="http://'.$col_fields[$fieldname].'" target="_blank">'.$col_fields[$fieldname].'</a></td>';
	}
        elseif($uitype == 19 || $uitype == 21 || $uitype == 22)
        {
                $col_fields[$fieldname]=nl2br($col_fields[$fieldname]);
                $custfld .= '<td width="20%" class="dataLabel" valign="top">'.$mod_strings[$fieldlabel].':</td>';
                $custfld .= '<td valign="top" class="dataField">'.$col_fields[$fieldname].'</td>';
        }
	elseif($uitype == 51 || $uitype == 50)
	{
		$account_id = $col_fields[$fieldname];
		if($account_id != '')
		{
			$account_name = getAccountName($account_id);
		}
		//Account Name View	
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Accounts&action=DetailView&record='.$account_id.'">'.$account_name.'</a></td>';
		

	}
	elseif($uitype == 52 || $uitype == 77)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$user_id = $col_fields[$fieldname];
		$user_name = getUserName($user_id);
		if(is_admin($current_user))
		{
			$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Users&action=DetailView&record='.$user_id.'">'.$user_name.'</a></td>';
		}
		else
		{
			$custfld .= '<td width="30%" valign="top" class="dataField">'.$user_name.'</td>';
		}
	}
	elseif($uitype == 53)
	{
		$user_id = $col_fields[$fieldname];
		if($user_id != 0)
		{
			$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].$app_strings['LBL_USER'].' :</td>';
			$user_name = getUserName($user_id);
			if(is_admin($current_user))
			{
				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Users&action=DetailView&record='.$user_id.'">'.$user_name.'</a></td>';
			}
			else
			{
				$custfld .= '<td width="30%" valign="top" class="dataField">'.$user_name.'</td>';
			}
		}
		elseif($user_id == 0)
		{
			$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].$app_strings['LBL_GROUP'].' :</td>';
			$id = $col_fields["record_id"];	
			$module = $col_fields["record_module"];
			$groupname = getGroupName($id, $module);
			if(is_admin($current_user))
                        {
				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Users&action=UserInfoUtil&groupname='.$groupname.'">'.$groupname.'</a></td>';
			}
			else
			{
				$custfld .= '<td width="30%" valign="top" class="dataField">'.$groupname.'</td>';
			}			
		}
		
	}
	elseif($uitype == 55)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $value = $col_fields[$fieldname];
                $sal_value = $col_fields["salutationtype"];
                if($sal_value == '--None--')
                {
                        $sal_value='';
                }
                $custfld .= '<td width="30%" valign="top" class="dataField">'.$sal_value.' '.$value.'</td>';
        }
	elseif($uitype == 56)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$value = $col_fields[$fieldname];
		if($value == 1)
		{
			$display_val = 'yes';
		}
		else
		{
			$display_val = '';
		}
		$custfld .= '<td width="30%" valign="top" class="dataField">'.$display_val.'</td>';
	}
	elseif($uitype == 57)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $contact_id = $col_fields[$fieldname];
                if($contact_id != '')
                {
                        $contact_name = getContactName($contact_id);
                }

                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Contacts&action=DetailView&record='.$contact_id.'">'.$contact_name.'</a></td>';
        }
	elseif($uitype == 59)
	{
		$product_id = $col_fields[$fieldname];
		if($product_id != '')
		{
			$product_name = getProductName($product_id);
		}
		//Account Name View	
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Products&action=DetailView&record='.$product_id.'">'.$product_name.'</a></td>';
		
	}
        elseif($uitype == 61)
        {
                global $adb;

                $attachmentid=$adb->query_result($adb->query("select * from seattachmentsrel where crmid = ".$col_fields['record_id']),0,'attachmentsid');
		if($col_fields[$fieldname] == '' && $attachmentid != '')
		{
				$attachquery = "select * from attachments where attachmentsid=".$attachmentid;
        		        $col_fields[$fieldname] = $adb->query_result($adb->query($attachquery),0,'name');
		}
                $custfldval = '<a href = "index.php?module=uploads&action=downloadfile&return_module='.$col_fields['record_module'].'&fileid='.$attachmentid.'&filename='.$col_fields[$fieldname].'">'.$col_fields[$fieldname].'</a>';

                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $custfld .= '<td width="30%" valign="top" class="dataField">'.$custfldval.'</td>';
        }
	elseif($uitype == 62)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_LEAD_NAME'].':</td>';
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$first_name.' '.$last_name.'</a></td>';
			}
			elseif($parent_module == "Accounts")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_ACCOUNT_NAME'].':</td>';
				$sql = "select * from  account where accountid=".$value;
				$result = $adb->query($sql);
				$account_name = $adb->query_result($result,0,"accountname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a></td>';
			}
			elseif($parent_module == "Potentials")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_POTENTIAL_NAME'].':</td>';
				$sql = "select * from  potential where potentialid=".$value;
				$result = $adb->query($sql);
				$potentialname = $adb->query_result($result,0,"potentialname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$potentialname.'</a></td>';
			}
			elseif($parent_module == "Products")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_PRODUCT_NAME'].':</td>';
				$sql = "select * from  products where productid=".$value;
				$result = $adb->query($sql);
				$productname= $adb->query_result($result,0,"productname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$productname.'</a></td>';
			}
		}
		else
		{
			$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
			$custfld .= '<td width="30%" valign="top" class="dataField">'.$value.'</td>';
		}


	}
	elseif($uitype == 66)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_LEAD_NAME'].':</td>';
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$first_name.' '.$last_name.'</a></td>';
			}
			elseif($parent_module == "Accounts")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_ACCOUNT_NAME'].':</td>';
				$sql = "select * from  account where accountid=".$value;
				$result = $adb->query($sql);
				$account_name = $adb->query_result($result,0,"accountname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a></td>';
			}
			elseif($parent_module == "Potentials")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_POTENTIAL_NAME'].':</td>';
				$sql = "select * from  potential where potentialid=".$value;
				$result = $adb->query($sql);
				$potentialname = $adb->query_result($result,0,"potentialname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$potentialname.'</a></td>';
			}
		}
		else
		{
			$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
			$custfld .= '<td width="30%" valign="top" class="dataField">'.$value.'</td>';
		}
	}
	elseif($uitype == 67)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_LEAD_NAME'].':</td>';
				$sql = "select * from leaddetails where leadid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
				$last_name = $adb->query_result($result,0,"lastname");

				$custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$first_name.' '.$last_name.'</a></td>';
			}
			elseif($parent_module == "Contacts")
			{
				$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['LBL_CONTACT_NAME'].':</td>';
				$sql = "select * from  contactdetails where contactid=".$value;
				$result = $adb->query($sql);
				$first_name = $adb->query_result($result,0,"firstname");
                                $last_name = $adb->query_result($result,0,"lastname");

                                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$first_name.' '.$last_name.'</a></td>';
			}
		}
		else
		{
			$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
			$custfld .= '<td width="30%" valign="top" class="dataField">'.$value.'</td>';
		}
	}

	elseif($uitype==63)
        {
	   $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';	
           $custfld .= '<td width="30%" valign="top" class="dataField">'.$col_fields[$fieldname].'h&nbsp; '.$col_fields['duration_minutes'].'m</td>';
        }
	elseif($uitype == 6)
        {
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
	
          	if($col_fields[$fieldname]=='0')
                $col_fields[$fieldname]='';
		if($col_fields['time_start']!='')
                {
                       $start_time = $col_fields['time_start'];
                }
	
          	$custfld .= '<td width="30%" valign="top" class="dataField">'.getDisplayDate($col_fields[$fieldname]).'&nbsp;'.$start_time.'</td>';
	}
	elseif($uitype == 5 || $uitype == 23 || $uitype == 70)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$cur_date_val = $col_fields[$fieldname];
		$display_val = getDisplayDate($cur_date_val);
		$custfld .= '<td width="30%" valign="top" class="dataField">'.$display_val.'</td>';	
	}
	elseif($uitype == 71 || $uitype == 72)
	{
		$custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
		$display_val = '';
		if($col_fields[$fieldname] != '' && $col_fields[$fieldname] != 0)
		{	
			$curr_symbol = getCurrencySymbol();
			$display_val = $curr_symbol.' '.$col_fields[$fieldname];
		}
		$custfld .= '<td width="30%" valign="top" class="dataField">'.$display_val.'</td>';	
	}
	elseif($uitype == 75)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $vendor_id = $col_fields[$fieldname];
                if($vendor_id != '')
                {
                        $vendor_name = getVendorName($vendor_id);
                }

                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Products&action=VendorDetailView&record='.$vendor_id.'">'.$vendor_name.'</a></td>';
        }
	elseif($uitype == 76)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $potential_id = $col_fields[$fieldname];
                if($potential_id != '')
                {
                        $potential_name = getPotentialName($potential_id);
                }

                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Potentials&action=DetailView&record='.$potential_id.'">'.$potential_name.'</a></td>';
        }
	elseif($uitype == 78)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $quote_id = $col_fields[$fieldname];
                if($quote_id != '')
                {
                        $quote_name = getQuoteName($quote_id);
                }

                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Quotes&action=DetailView&record='.$quote_id.'">'.$quote_name.'</a></td>';
        }
	elseif($uitype == 79)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $purchaseorder_id = $col_fields[$fieldname];
                if($purchaseorder_id != '')
                {
                        $purchaseorder_name = getPoName($purchaseorder_id);
                }

                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Orders&action=DetailView&record='.$purchaseorder_id.'">'.$purchaseorder_name.'</a></td>';
        }
	elseif($uitype == 80)
        {
                $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
                $salesorder_id = $col_fields[$fieldname];
                if($salesorder_id != '')
                {
                        $salesorder_name = getSoName($salesorder_id);
                }

                $custfld .= '<td width="30%" valign="top" class="dataField"><a href="index.php?module=Orders&action=SalesOrderDetailView&record='.$salesorder_id.'">'.$salesorder_name.'</a></td>';
        }
	elseif($uitype == 30)
	{
		$rem_days = 0;
		$rem_hrs = 0;
		$rem_min = 0;
		$reminder_str ="";
		$rem_days = floor($col_fields[$fieldname]/(24*60));
		$rem_hrs = floor(($col_fields[$fieldname]-$rem_days*24*60)/60);
		$rem_min = ($col_fields[$fieldname]-$rem_days*24*60)%60;
                 
                $custfld .= '<td width="20%" class="dataLabel" valign="top">'.$mod_strings[$fieldlabel].':</td>';
		if($col_fields[$fieldname])
                {
                        $reminder_str= $rem_days.'&nbsp;'.$mod_strings['LBL_DAYS'].'&nbsp;'.$rem_hrs.'&nbsp;'.$mod_strings['LBL_HOURS'].'&nbsp;'.$rem_min.'&nbsp;'.$mod_strings['LBL_MINUTES'].'&nbsp;&nbsp;'.$mod_strings['LBL_BEFORE_EVENT'];
                }
                $custfld .= '<td valign="top" colspan=3 class="datafield">&nbsp;'.$reminder_str.'</td>';
	}
	else
	{
	  $custfld .= '<td width="20%" class="dataLabel">'.$mod_strings[$fieldlabel].':</td>';
	
          if($col_fields[$fieldname]=='0')
                $col_fields[$fieldname]='';
	
          $custfld .= '<td width="30%" valign="top" class="dataField">'.$col_fields[$fieldname].'</td>';
	}
	return $custfld;	
}

function getSalesEntityType($crmid)
{
	global $adb;
	$sql = "select * from crmentity where crmid=".$crmid;
        $result = $adb->query($sql);
	$parent_module = $adb->query_result($result,0,"setype");
	return $parent_module;
}

function getAccountName($account_id)
{
	global $adb;
	$sql = "select accountname from account where accountid=".$account_id;
        $result = $adb->query($sql);
	$accountname = $adb->query_result($result,0,"accountname");
	return $accountname;
}
function getproductname($product_id)
{
	global $adb;
	$sql = "select productname from products where productid=".$product_id;
        $result = $adb->query($sql);
	$productname = $adb->query_result($result,0,"productname");
	return $productname;
}
function getPotentialName($potential_id)
{
	global $adb;
	$sql = "select potentialname from potential where potentialid=".$potential_id;
        $result = $adb->query($sql);
	$potentialname = $adb->query_result($result,0,"potentialname");
	return $potentialname;
}

function getContactName($contact_id)
{
        global $adb;
        $sql = "select * from contactdetails where contactid=".$contact_id;
        $result = $adb->query($sql);
        $firstname = $adb->query_result($result,0,"firstname");
        $lastname = $adb->query_result($result,0,"lastname");
        $contact_name = $firstname.' '.$lastname;
        return $contact_name;
}

function getVendorName($vendor_id)
{
        global $adb;
        $sql = "select * from vendor where vendorid=".$vendor_id;
        $result = $adb->query($sql);
        $vendor_name = $adb->query_result($result,0,"name");
        return $vendor_name;
}

function getQuoteName($quote_id)
{
        global $adb;
        $sql = "select * from quotes where quoteid=".$quote_id;
        $result = $adb->query($sql);
        $quote_name = $adb->query_result($result,0,"subject");
        return $quote_name;
}

function getPoName($po_id)
{
        global $adb;
        $sql = "select * from purchaseorder where purchaseorderid=".$po_id;
        $result = $adb->query($sql);
        $po_name = $adb->query_result($result,0,"subject");
        return $po_name;
}

function getSoName($so_id)
{
        global $adb;
        $sql = "select * from salesorder where salesorderid=".$so_id;
        $result = $adb->query($sql);
        $so_name = $adb->query_result($result,0,"subject");
        return $so_name;
}
function getGroupName($id, $module)
{
	global $adb;
	if($module == 'Leads')
	{
		$sql = "select * from leadgrouprelation where leadid=".$id;
	}
        elseif($module == 'HelpDesk')
        {
                $sql = "select * from ticketgrouprelation where ticketid=".$id;
        }
	elseif($module = 'Calls')
	{
		$sql = "select * from activitygrouprelation where activityid=".$id;
	}
	elseif($module = 'Tasks')
	{
		$sql = "select * from taskgrouprelation where taskid=".$id;
	}
	$result = $adb->query($sql);
	$groupname = $adb->query_result($result,0,"groupname");
	return $groupname;
}


function getColumnFields($module)
{
	global $adb;
	$column_fld = Array();
        $tabid = getTabid($module);
	$sql = "select * from field where tabid=".$tabid;
        $result = $adb->query($sql);
        $noofrows = $adb->num_rows($result);
	for($i=0; $i<$noofrows; $i++)
	{
		$fieldname = $adb->query_result($result,$i,"fieldname");
		$column_fld[$fieldname] = ''; 
	}
	return $column_fld;	
}

function getUserName($userid)
{
	global $adb;
	if($userid != '')
	{
		$sql = "select user_name from users where id=".$userid;
		$result = $adb->query($sql);
		$user_name = $adb->query_result($result,0,"user_name");
	}
	return $user_name;	
}

function getUserEmail($userid)
{
        global $adb;
        if($userid != '')
        {
                $sql = "select email1 from users where id=".$userid;
                $result = $adb->query($sql);
                $email = $adb->query_result($result,0,"email1");
        }
        return $email;
}		
//outlook security
function getUserId_Ol($username)
{
	global $adb;
	$sql = "select id from users where user_name='".$username."'";
	$result = $adb->query($sql);
	$num_rows = $adb->num_rows($result);
	if($num_rows > 0)
	{
		$user_id = $adb->query_result($result,0,"id");
    }
    else
    {
	    $user_id = 0;
    }    	
	return $user_id;
}	
//outlook security
function getNavigationValues($start, $noofrows, $list_max_entries_per_page)
{
	$navigation_array = Array();	

	require_once('config.php');
	//Setting the start to end counter
	$starttoendvaluecounter = $list_max_entries_per_page - 1;
	//Setting the ending value
	if($noofrows > $list_max_entries_per_page)
	{
		$end = $start + $starttoendvaluecounter;
		if($end > $noofrows)
		{
			$end = $noofrows;
		}
		$startvalue = 1;
		$remainder = $noofrows % $list_max_entries_per_page;
		if($remainder > 0)
		{
			$endval = $noofrows - $remainder + 1;
		}
		elseif($remainder == 0)
		{
			$endval = $noofrows - $starttoendvaluecounter;
		}
	}
	else
	{
		$end = $noofrows;
	}


	//Setting the next and previous value
	if(isset($start) && $start != '')
	{
		$tempnextstartvalue = $start + $list_max_entries_per_page;
		if($tempnextstartvalue <= $noofrows)
		{

			$nextstartvalue = $tempnextstartvalue;
		}
		$tempprevvalue = $_REQUEST['start'] - $list_max_entries_per_page;
		if($tempprevvalue  > 0)
		{
			$prevstartvalue = $tempprevvalue;
		}
	}
	else
	{
		if($noofrows > $list_max_entries_per_page)
		{
			$nextstartvalue = $list_max_entries_per_page + 1;
		}
	}

	$navigation_array['start'] = $start;
	$navigation_array['end'] = $endval;
	$navigation_array['prev'] = $prevstartvalue;
	$navigation_array['next'] = $nextstartvalue;
	$navigation_array['end_val'] = $end;
	return $navigation_array;

} 		

function getURLstring($focus)
{
	$qry = "";
	foreach($focus->column_fields as $fldname=>$val)
	{
		if(isset($_REQUEST[$fldname]) && $_REQUEST[$fldname] != '')
		{
			if($qry == '')
			$qry = "&".$fldname."=".$_REQUEST[$fldname];
			else
			$qry .="&".$fldname."=".$_REQUEST[$fldname];
		}
	}
	if(isset($_REQUEST['current_user_only']) && $_REQUEST['current_user_only'] !='')
	{
		$qry .="&current_user_only=".$_REQUEST['current_user_only'];
	}
	if(isset($_REQUEST['advanced']) && $_REQUEST['advanced'] =='true')
	{
		$qry .="&advanced=true";
	}

	if($qry !='')
	{
		$qry .="&query=true";
	}
	return $qry;

}
function getListViewHeader($focus, $module,$sort_qry='',$sorder='',$order_by='',$relatedlist='',$oCv)
{
	global $adb;
	global $theme;
	global $app_strings;
	global $mod_strings;
	$arrow='';
	$qry = getURLstring($focus);
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$list_header = '<tr class="moduleListTitle" height=20>';
	$list_header .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
	if($relatedlist == '')
	{
		$list_header .='<td WIDTH="1" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><input type="checkbox" name="selectall" onClick=toggleSelect(this.checked,"selected_id")></td>';
		$list_header .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';
	}

	//Get the tabid of the module
	//require_once('modules/Users/UserInfoUtil.php')
	$tabid = getTabid($module);
	global $profile_id;
        if($profile_id == '')
        {
                global $current_user;
                $profile_id = fetchUserProfileId($current_user->id);
        }
	//added for customview 27/5
	if($oCv)
        {
                if(isset($oCv->list_fields))
                {
                        $focus->list_fields = $oCv->list_fields;
                }
        }

	//modified for customview 27/5 - $app_strings change to $mod_strings
	foreach($focus->list_fields as $name=>$tableinfo)
	{
		//$fieldname = $focus->list_fields_name[$name];  //commented for customview 27/5
		//added for customview 27/5
		if($oCv)
                {
                        if(isset($oCv->list_fields_name))
                        {
                                $fieldname = $oCv->list_fields_name[$name];
                        }else
                        {
                                $fieldname = $focus->list_fields_name[$name];
                        }
                }

		//Getting the Entries from Profile2 field table
		$query = "select profile2field.* from field inner join profile2field on field.fieldid=profile2field.fieldid where profile2field.tabid=".$tabid." and profile2field.profileid=".$profile_id." and field.fieldname='".$fieldname."'";
		$result = $adb->query($query);

		//Getting the Entries from def_org_field table
		$query1 = "select def_org_field.* from field inner join def_org_field on field.fieldid=def_org_field.fieldid where def_org_field.tabid=".$tabid." and field.fieldname='".$fieldname."'";
		$result_def = $adb->query($query1);


		if($adb->query_result($result,0,"visible") == 0 && $adb->query_result($result_def,0,"visible") == 0)
		{

			if(isset($focus->sortby_fields) && $focus->sortby_fields !='')
			{
				foreach($focus->list_fields[$name] as $tab=>$col)
				{
					if(in_array($col,$focus->sortby_fields))
					{
						if($order_by == $col)
                                        	{
                                                	if($sorder == 'ASC')
                                                	{
                                                        	$sorder = "DESC";
	                                                        $arrow = "<img src ='".$image_path."arrow_down.gif' border='0'>";
        	 	                                 }
                        	                        else
                                	                {
                                        	                $sorder = 'ASC';
                                                	        $arrow = "<img src ='".$image_path."arrow_up.gif' border='0'>";
                                                	}
                                        	}
						if($relatedlist !='')
                                                {
                                                        $name = $mod_strings[$name];
                                                }
                                                else
                                                {
                                        		$name = "<a href='index.php?module=".$module."&action=index".$sort_qry."&order_by=".$col."&sorder=".$sorder."' class='listFormHeaderLinks'>".$mod_strings[$name]."&nbsp;".$arrow."</a>";
                                        		$arrow = '';
						}
					}
					else
						$name = $mod_strings[$name];
				}
			}
			//Added condition to hide the close column in Related Lists
			if($name == 'Close' && $relatedlist != '')
			{
				$list_header .= '';
			}
			else
			{
				$list_header .= '<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$name.'</td>';
				$list_header .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';
			}
		}
	}
	$list_header .='<td class="moduleListTitle" style="padding:0px 3px 0px 3px;">'.$app_strings['LBL_EDIT'].' | '.$app_strings['LBL_DELETE'].'</td>';
	$list_header .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';
	$list_header .= '</tr>';
	return $list_header;

}

function getSearchListViewHeader($focus, $module,$sort_qry='',$sorder='',$order_by='')
{
	global $adb;
	global $theme;
	global $app_strings;
        global $mod_strings;
        $arrow='';

	//$theme = $focus->current_theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";		
	$list_header = '<tr class="moduleListTitle" height=20>';
	$list_header .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
	foreach($focus->search_fields as $name=>$tableinfo)
	{
		$fieldname = $focus->search_fields_name[$name];
		global $profile_id;
		$tabid = getTabid($module);
		$query = "select profile2field.* from field inner join profile2field on field.fieldid=profile2field.fieldid where profile2field.tabid=".$tabid." and profile2field.profileid=".$profile_id." and field.fieldname='".$fieldname."'";
		$result = $adb->query($query);

		//Getting the Entries from def_org_field table
		$query1 = "select def_org_field.* from field inner join def_org_field on field.fieldid=def_org_field.fieldid where def_org_field.tabid=".$tabid." and field.fieldname='".$fieldname."'";
		$result_def = $adb->query($query1);

		if($adb->query_result($result,0,"visible") == 0 && $adb->query_result($result_def,0,"visible") == 0)
		{
			if(isset($focus->sortby_fields) && $focus->sortby_fields !='')
                        {
                                foreach($focus->search_fields[$name] as $tab=>$col)
                                {
                                        if(in_array($col,$focus->sortby_fields))
                                        {
                                                if($order_by == $col)
                                                {
                                                        if($sorder == 'ASC')
                                                        {
                                                                $sorder = "DESC";
                                                                $arrow = "<img src ='".$image_path."arrow_down.gif' border='0'>";
                                                         }
                                                        else
                                                        {
                                                                $sorder = 'ASC';
                                                                $arrow = "<img src ='".$image_path."arrow_up.gif' border='0'>";
                                                        }
                                                }
                                                $name = "<a href='index.php?module=".$module."&action=Popup".$sort_qry."&order_by=".$col."&sorder=".$sorder."' class='listFormHeaderLinks'>".$app_strings[$name]."&nbsp;".$arrow."</a>";
                                                $arrow = '';
                                        }
                                        else
                                                $name = $app_strings[$name];
                                }
                        }
			$list_header .= '<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$name.'</td>';
			$list_header .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';
		}
	}	
	$list_header .= '</tr>';
	return $list_header;

}

function getRelatedToEntity($module,$list_result,$rset)
{

	global $adb;
	$seid = $adb->query_result($list_result,$rset,"relatedto");

	if(isset($seid) && $seid != '')
	{
		$parent_module = $parent_module = getSalesEntityType($seid);
		if($parent_module == 'Accounts')
		{
			$parent_query = "SELECT accountname FROM account WHERE accountid=".$seid;
			$parent_result = $adb->query($parent_query);
			$parent_name = $adb->query_result($parent_result,0,"accountname");
		}
		if($parent_module == 'Leads')
		{
			$parent_query = "SELECT firstname,lastname FROM leaddetails WHERE leadid=".$seid;
			$parent_result = $adb->query($parent_query);
			$parent_name = $adb->query_result($parent_result,0,"firstname") ." " .$adb->query_result($parent_result,0,"lastname");
		}
		if($parent_module == 'Potentials')
		{
			$parent_query = "SELECT potentialname FROM potential WHERE potentialid=".$seid;
			$parent_result = $adb->query($parent_query);
			$parent_name = $adb->query_result($parent_result,0,"potentialname");
		}
		if($parent_module == 'Products')
		{
			$parent_query = "SELECT productname FROM products WHERE productid=".$seid;
			$parent_result = $adb->query($parent_query);
			$parent_name = $adb->query_result($parent_result,0,"productname");
		}

		$parent_value = "<a href='index.php?module=".$parent_module."&action=DetailView&record=".$seid."'>".$parent_name."</a>"; 
	}
	else
	{
		$parent_value = '';
	}
	return $parent_value;

}

function getRelatedTo($module,$list_result,$rset)
{

        global $adb;
        $activity_id = $adb->query_result($list_result,$rset,"activityid");

        $evt_query="select seactivityrel.crmid,crmentity.setype from seactivityrel, crmentity where seactivityrel.activityid='".$activity_id."' and seactivityrel.crmid = crmentity.crmid";

        $evt_result = $adb->query($evt_query);
        $parent_module = $adb->query_result($evt_result,0,'setype');
        $parent_id = $adb->query_result($evt_result,0,'crmid');
        if($parent_module == 'Accounts')
        {
                $parent_query = "SELECT accountname FROM account WHERE accountid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"accountname");
        }
        if($parent_module == 'Leads')
        {
                $parent_query = "SELECT firstname,lastname FROM leaddetails WHERE leadid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"firstname") ." " .$adb->query_result($parent_result,0,"lastname");
        }
        if($parent_module == 'Potentials')
        {
                $parent_query = "SELECT potentialname FROM potential WHERE potentialid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"potentialname");
        }
        if($parent_module == 'Products')
        {
                $parent_query = "SELECT productname FROM products WHERE productid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"productname");
        }
	if($parent_module == 'Contacts' && $module == 'Emails')
        {
                $parent_query = "SELECT firstname,lastname FROM contactdetails WHERE contactid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"firstname") ." " .$adb->query_result($parent_result,0,"lastname");
        }

        $parent_value = "<a href='index.php?module=".$parent_module."&action=DetailView&record=".$parent_id."'>".$parent_name."</a>";
        return $parent_value;


}

//parameter added for customview $oCv 27/5
function getListViewEntries($focus, $module,$list_result,$navigation_array,$relatedlist='',$returnset='',$edit_action='EditView',$del_action='Delete',$oCv='')
{
	global $adb;
	global $app_strings;
	$noofrows = $adb->num_rows($list_result);
	$list_header = '<script>
			function confirmdelete(url)
			{
				if(confirm("Are you sure?"))
				{
					document.location.href=url;
				}
			}
		</script>';
	global $theme;
	$evt_status;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	//getting the fieldtable entries from database
	$tabid = getTabid($module);
	
	//added for customview 27/5
	if($oCv)
        {
                if(isset($oCv->list_fields))
                {
                        $focus->list_fields = $oCv->list_fields;
                }
        }

	for ($i=$navigation_array['start']; $i<=$navigation_array['end_val']; $i++)
	{
		if (($i%2)==0)
			$list_header .= '<tr height=20 class=evenListRow>';
		else
			$list_header .= '<tr height=20 class=oddListRow>';

		//Getting the entityid
		$entity_id = $adb->query_result($list_result,$i-1,"crmid");
		$owner_id = $adb->query_result($list_result,$i-1,"smownerid");

		if($relatedlist == '')
		{
			$list_header .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
			$list_header .= '<td valign=TOP style="padding:0px 3px 0px 3px;"><INPUT type=checkbox NAME="selected_id" value= '.$entity_id.' onClick=toggleSelectAll(this.name,"selectall")></td>';
		}
		$list_header .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
		foreach($focus->list_fields as $name=>$tableinfo)
		{
			$fieldname = $focus->list_fields_name[$name];
			
			//added for customview 27/5
			if($oCv)
                        {
                                if(isset($oCv->list_fields_name))
                                {
                                        $fieldname = $oCv->list_fields_name[$name];
                                }
                        }

			global $profile_id;
			$query = "select profile2field.* from field inner join profile2field on field.fieldid=profile2field.fieldid where profile2field.tabid=".$tabid." and profile2field.profileid=".$profile_id." and field.fieldname='".$fieldname."'";
			$result = $adb->query($query);


			//Getting the Entries from def_org_field table
			$query1 = "select def_org_field.* from field inner join def_org_field on field.fieldid=def_org_field.fieldid where def_org_field.tabid=".$tabid." and field.fieldname='".$fieldname."'";
			$result_def = $adb->query($query1);

			if($adb->query_result($result,0,"visible") == 0 && $adb->query_result($result_def,0,"visible") == 0)
			{
				if($fieldname == '')
				{
					$table_name = '';
					$column_name = '';
					foreach($tableinfo as $tablename=>$colname)
					{
						$table_name=$tablename;
						$column_name = $colname;
					}
					$value = $adb->query_result($list_result,$i-1,$colname);
				}
				else
				{


					if(($module == 'Activities' || $module == 'Tasks' || $module == 'Meetings' || $module == 'Emails' || $module == 'HelpDesk') && (($name=='Related to') || ($name=='Contact Name') || ($name=='Close')))
					{
						$status = $adb->query_result($list_result,$i-1,"status");
						if($status == '')
                                                $status = $adb->query_result($list_result,$i-1,"eventstatus");
						if ($name=='Related to')
							$value=getRelatedTo($module,$list_result,$i-1);
						if($name=='Contact Name')
						{
							$first_name = $adb->query_result($list_result,$i-1,"firstname");
							$last_name = $adb->query_result($list_result,$i-1,"lastname");
							$contact_id = $adb->query_result($list_result,$i-1,"contactid");
							$contact_name = "";
							$value="";
							if($first_name != 'NULL')
								$contact_name .= $first_name;
							if($last_name != 'NULL')
								$contact_name .= " ".$last_name;
							if(($contact_name != "") && ($contact_id !='NULL'))
								$value =  "<a href='index.php?module=Contacts&action=DetailView&record=".$contact_id."'>".$contact_name."</a>";
						}
						if ($name == 'Close')
						{
							if($status =='Deferred' || $status == 'Completed' || $status == 'Held' || $status == '')
							{
								$value="";
							}
							else
							{
								$activityid = $adb->query_result($list_result,$i-1,"activityid");
								$activitytype = $adb->query_result($list_result,$i-1,"activitytype");
                                                                if($activitytype=='Task')
                                                                $evt_status='&status=Completed';
                                                                else
                                                                $evt_status='&eventstatus=Held';
                                                                $value = "<a href='index.php?return_module=Activities&return_action=index&return_id=".$activityid."&action=Save&module=Activities&record=".$activityid."&change_status=true".$evt_status."'>X</a>";
							}
						}
					}
					elseif(($module == 'Faq' || $module == 'Notes') && $name=='Related to')
					{
						$value=getRelatedToEntity($module,$list_result,$i-1);
					}
					elseif($name=='Account Name')
					{
						//modified for customview 27/5
						if($module == 'Accounts')
                                                {
                                                	$account_id = $adb->query_result($list_result,$i-1,"crmid");
                                                	$account_name = getAccountName($account_id);
                                                	$value = '<a href="index.php?module=Accounts&action=DetailView&record='.$account_id.'">'.$account_name.'</a>';
                                                }else
                                                {
                                                	$account_id = $adb->query_result($list_result,$i-1,"accountid");
                                                	$account_name = getAccountName($account_id);
                                                	$value = '<a href="index.php?module=Accounts&action=DetailView&record='.$account_id.'">'.$account_name.'</a>';
                                                }
					}
					elseif(($module == 'PriceBook' || $module == 'Quotes' || 'Orders') && $name == 'Product Name')
					{
						$product_id = $adb->query_result($list_result,$i-1,"productid");
						$product_name = getProductName($product_id);
						$value = '<a href="index.php?module=Products&action=DetailView&record='.$product_id.'">'.$product_name.'</a>';
					}
					elseif($module == 'Quotes' && $name == 'Potential Name')
					{
						$potential_id = $adb->query_result($list_result,$i-1,"potentialid");
						$potential_name = getPotentialName($potential_id);
						$value = '<a href="index.php?module=Potentials&action=DetailView&record='.$potential_id.'">'.$potential_name.'</a>';
					}
					elseif($owner_id == 0 && $name == 'Assigned To')
                                       {
                                               $value = getGroupName($entity_id, $module);
                                       }
					else
					{

						$query = "select * from field where tabid=".$tabid." and fieldname='".$fieldname."'";
						$field_result = $adb->query($query);
						$list_result_count = $i-1;

						$value = getValue($field_result,$list_result,$fieldname,$focus,$module,$entity_id,$list_result_count,"list","",$returnset);
					}
				}
				//Added condition to hide the close symbol in Related Lists
//				if($relatedlist != '' && $value == "<a href='index.php?return_module=Activities&return_action=index&return_id=".$activityid."&action=Save&module=Activities&record=".$activityid."&change_status=true&status=Completed'>X</a>")
				if($name == 'Close' && $relatedlist != '')
				{
					$list_header .= '';
				}
				else
				{
					$list_header .= '<td height="21" style="padding:0px 3px 0px 3px;">'.$value.'</td>';
					$list_header .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
				}
				if($fieldname=='filename')
				{
					$filename = $adb->query_result($list_result,$list_result_count,$fieldname);
				}
			}

		}

		if($returnset=='')
			$returnset = '&return_module='.$module.'&return_action=index';

		if($module == 'Activities')
		{
			$actvity_type = $adb->query_result($list_result,$list_result_count,'activitytype');
			if($actvity_type == 'Task')
				$returnset .= '&activity_mode=Task';
			else
				$returnset .= '&activity_mode=Events';
		}
		$list_header .= '<td style="padding:0px 3px 0px 3px;">';
		$mod_dir=getModuleDirName($module);
		if(isPermitted($module,1,$entity_id) == 'yes')
		{
			
			
			$list_header .='<a href="index.php?action='.$edit_action.'&module='.$mod_dir.'&record='.$entity_id.$returnset.'&filename='.$filename.'">'.$app_strings['LNK_EDIT'].'</a>&nbsp;|&nbsp;';
		}
		if(isPermitted($module,2,$entity_id) == 'yes')
		{
			$del_param = 'index.php?action='.$del_action.'&module='.$mod_dir.'&record='.$entity_id.$returnset;
			$list_header .= '<a href="javascript:confirmdelete(\''.$del_param.'\')">'.$app_strings['LNK_DELETE'].'</a>';
		}
		$list_header .= '<td>';
		$list_header .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
		$list_header .= '</tr>';
	}
	return $list_header;
}


function getSearchListViewEntries($focus, $module,$list_result,$navigation_array)
{
	global $adb;
	$noofrows = $adb->num_rows($list_result);
	$list_header = '';
	global $theme;	
	//$theme = $focus->current_theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	//getting the fieldtable entries from database
	$tabid = getTabid($module);

	for ($i=$navigation_array['start']; $i<=$navigation_array['end_val']; $i++)
	{
		if (($i%2)==0)
			$list_header .= '<tr height=20 class=evenListRow>';
		else
			$list_header .= '<tr height=20 class=oddListRow>';

		//Getting the entityid
		$entity_id = $adb->query_result($list_result,$i-1,"crmid");

		$list_header .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
		foreach($focus->search_fields as $name=>$tableinfo)
		{
			$fieldname = $focus->search_fields_name[$name];
			global $profile_id;
			$query = "select profile2field.* from field inner join profile2field on field.fieldid=profile2field.fieldid where profile2field.tabid=".$tabid." and profile2field.profileid=".$profile_id." and field.fieldname='".$fieldname."'";
			$result = $adb->query($query);
	
			//Getting the Entries from def_org_field table
			$query1 = "select def_org_field.* from field inner join def_org_field on field.fieldid=def_org_field.fieldid where def_org_field.tabid=".$tabid." and field.fieldname='".$fieldname."'";
			$result_def = $adb->query($query1);


			if($adb->query_result($result,0,"visible") == 0 && $adb->query_result($result_def,0,"visible") == 0)
			{

				if($fieldname == '')
				{
					$table_name = '';
					$column_name = '';
					foreach($tableinfo as $tablename=>$colname)
					{
						$table_name=$tablename;
						$column_name = $colname;
					}
					$value = $adb->query_result($list_result,$i-1,$colname); 
				}
				else
				{
					if(($module == 'Calls' || $module == 'Tasks' || $module == 'Meetings' || $module == 'Emails') && (($name=='Related to') || ($name=='Contact Name')))
					{
						if ($name=='Related to')
							$value=getRelatedTo($module,$list_result,$i-1);
						if($name=='Contact Name')
						{
							$first_name = $adb->query_result($list_result,$i-1,"firstname");
							$last_name = $adb->query_result($list_result,$i-1,"lastname");
							$contact_id = $adb->query_result($list_result,$i-1,"contactid");
							$contact_name = "";
							$value="";
							if($first_name != 'NULL')
								$contact_name .= $first_name;
							if($last_name != 'NULL')
								$contact_name .= " ".$last_name;
							if(($contact_name != "") && ($contact_id !='NULL'))
								$value =  "<a href='index.php?module=Contacts&action=DetailView&record=".$contact_id."'>".$contact_name."</a>";
						}
					}
					elseif(($module == 'Faq' || $module == 'Notes') && $name=='Related to')
					{
						$value=getRelatedToEntity($module,$list_result,$i-1);
					}
					elseif($name=='Account Name' && $module == 'Potentials')
                                        {
                                                $account_id = $adb->query_result($list_result,$i-1,"accountid");
                                                $account_name = getAccountName($account_id);
                                                $value = $account_name;
                                        }
					else
					{
						$query = "select * from field where tabid=".$tabid." and fieldname='".$fieldname."'";
						$field_result = $adb->query($query);
						$list_result_count = $i-1;

						$value = getValue($field_result,$list_result,$fieldname,$focus,$module,$entity_id,$list_result_count,"search",$focus->popup_type);
					}

				}
			$list_header .= '<td height="21" style="padding:0px 3px 0px 3px;">'.$value.'</td>';
			$list_header .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
			}
		}	
		$list_header .= '</tr>';
	}
	return $list_header;
}

function getValue($field_result, $list_result,$fieldname,$focus,$module,$entity_id,$list_result_count,$mode,$popuptype,$returnset='')
{
	global $adb;
	$uitype = $adb->query_result($field_result,0,"uitype");
	
	$colname = $adb->query_result($field_result,0,"columnname");
	$temp_val = $adb->query_result($list_result,$list_result_count,$colname);
		
	if($uitype == 52 || $uitype == 53 || $uitype == 77)
	{
                $user_name = getUserName($temp_val);
		$value = $user_name;
	}
	elseif($uitype == 5 || $uitype == 6 || $uitype == 23 || $uitype == 70)
	{
		if($temp_val != '')
		{
			$value = getDisplayDate($temp_val);  
		}
		else
		{
			$value = $temp_val;
		}
		
	}
	elseif($uitype == 71 || $uitype == 72)
	{
		if($temp_val != '' && $temp_val != 0)
		{
			$symbol = getCurrencySymbol();
			$value = $symbol.' '.$temp_val;  
		}
		else
		{
			$value = '';
		}
		
	}
	elseif($uitype == 17)
	{
		$value = '<a href="http://'.$temp_val.'" target="_blank">'.$temp_val.'</a>';
	}
	elseif($uitype == 13)
        {
                $value = '<a href="mailto:'.$temp_val.'">'.$temp_val.'</a>';
        }
	elseif($uitype == 56)
	{
		if($temp_val == 1)
		{
			$value = 'yes';
		}
		else
		{
			$value = '';
		}
	}	
	elseif($uitype == 57)
	{
		global $adb;
		if($temp_val != '')
                {
			$sql="select * from contactdetails where contactid=".$temp_val;		
			$result=$adb->query($sql);
			$firstname=$adb->query_result($result,0,"firstname");
			$lastname=$adb->query_result($result,0,"lastname");
			$name=$lastname.' '.$firstname;

			$value= '<a href=index.php?module=Contacts&action=DetailView&record='.$temp_val.'>'.$name.'</a>';
		}
		else
			$value='';
	}
        elseif($uitype == 61)
        {
                global $adb;

		$attachmentid=$adb->query_result($adb->query("select * from seattachmentsrel where crmid = ".$entity_id),0,'attachmentsid');
		$value = '<a href = "index.php?module=uploads&action=downloadfile&return_module='.$module.'&fileid='.$attachmentid.'&filename='.$temp_val.'">'.$temp_val.'</a>';

        }
	elseif($uitype == 62)
	{
		global $adb;

		$parentid = $adb->query_result($list_result,$list_result_count,"parent_id");
		$parenttype = $adb->query_result($list_result,$list_result_count,"parent_type");

		if($parenttype == "Leads")	
		{
			$tablename = "leaddetails";	$fieldname = "lastname";	$idname="leadid";	
		}
		if($parenttype == "Accounts")	
		{
			$tablename = "account";		$fieldname = "accountname";     $idname="accountid";
		}
		if($parenttype == "Products")	
		{
			$tablename = "products";	$fieldname = "productname";     $idname="productid";
		}
		if($parenttype == "HelpDesk")	
		{
			$tablename = "troubletickets";	$fieldname = "title";        	$idname="crmid";
		}
		if($parenttype == "Products")	
		{
			$tablename = "products";	$fieldname = "productname";     $idname="productid";
		}

		if($parentid != '')
                {
			$sql="select * from ".$tablename." where ".$idname." = ".$parentid;
			//echo '<br> query : .. '.$sql;
			$fieldvalue=$adb->query_result($adb->query($sql),0,$fieldname);
			//echo '<br><br> val : '.$fieldvalue;

			$value='<a href=index.php?module='.$parenttype.'&action=DetailView&record='.$parentid.'>'.$fieldvalue.'</a>';
		}
		else
			$value='';
	}
	elseif($uitype == 66)
	{
		global $adb;

		$parentid = $adb->query_result($list_result,$list_result_count,"parent_id");
		$parenttype = $adb->query_result($list_result,$list_result_count,"parent_type");

		if($parenttype == "Leads")	
		{
			$tablename = "leaddetails";	$fieldname = "lastname";	$idname="leadid";	
		}
		if($parenttype == "Accounts")	
		{
			$tablename = "account";		$fieldname = "accountname";     $idname="accountid";
		}
		if($parenttype == "HelpDesk")	
		{
			$tablename = "troubletickets";	$fieldname = "title";        	$idname="crmid";
		}
		if($parentid != '')
                {
			$sql="select * from ".$tablename." where ".$idname." = ".$parentid;
			//echo '<br> query : .. '.$sql;
			$fieldvalue=$adb->query_result($adb->query($sql),0,$fieldname);
			//echo '<br><br> val : '.$fieldvalue;

			$value='<a href=index.php?module='.$parenttype.'&action=DetailView&record='.$parentid.'>'.$fieldvalue.'</a>';
		}
		else
			$value='';
	}
	elseif($uitype == 67)
	{
		global $adb;

		$parentid = $adb->query_result($list_result,$list_result_count,"parent_id");
		$parenttype = $adb->query_result($list_result,$list_result_count,"parent_type");

		if($parenttype == "Leads")	
		{
			$tablename = "leaddetails";	$fieldname = "lastname";	$idname="leadid";	
		}
		if($parenttype == "Contacts")	
		{
			$tablename = "contactdetails";		$fieldname = "contactname";     $idname="contactid";
		}
		if($parentid != '')
                {
			$sql="select * from ".$tablename." where ".$idname." = ".$parentid;
			//echo '<br> query : .. '.$sql;
			$fieldvalue=$adb->query_result($adb->query($sql),0,$fieldname);
			//echo '<br><br> val : '.$fieldvalue;

			$value='<a href=index.php?module='.$parenttype.'&action=DetailView&record='.$parentid.'>'.$fieldvalue.'</a>';
		}
		else
			$value='';
	}
	elseif($uitype == 78)
        {

		global $adb;
		if($temp_val != '')
                {
			
                        $quote_name = getQuoteName($temp_val);
			$value= '<a href=index.php?module=Quotes&action=DetailView&record='.$temp_val.'>'.$quote_name.'</a>';
		}
		else
			$value='';
        }
	elseif($uitype == 79)
        {

		global $adb;
		if($temp_val != '')
                {
			
                        $purchaseorder_name = getPoName($temp_val);
			$value= '<a href=index.php?module=Orders&action=DetailView&record='.$temp_val.'>'.$purchaseorder_name.'</a>';
		}
		else
			$value='';
        }
	elseif($uitype == 80)
        {

		global $adb;
		if($temp_val != '')
                {
			
                        $salesorder_name = getSoName($temp_val);
			$value= '<a href=index.php?module=Orders&action=SalesOrderDetailView&record='.$temp_val.'>'.$salesorder_name.'</a>';
		}
		else
			$value='';
        }
	elseif($uitype == 75)
        {

		global $adb;
		if($temp_val != '')
                {
			
                        $vendor_name = getVendorName($temp_val);
			$value= '<a href=index.php?module=Products&action=VendorDetailView&record='.$temp_val.'>'.$vendor_name.'</a>';
		}
		else
			$value='';
        }
	else
	{
	
		if($fieldname == $focus->list_link_field)
		{
			if($mode == "search")
			{
				if($popuptype == "specific")
				{
					// Added for get the first name of contact in Popup window
                                        if($colname == "lastname" && $module == 'Contacts')
					{
                                               $firstname=$adb->query_result($list_result,$list_result_count,'firstname');
                                        	$temp_val =$firstname.' '.$temp_val;
					}
			
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_specific("'.$entity_id.'", "'.$temp_val.'"); window.close()\'>'.$temp_val.'</a>';
				}
				elseif($popuptype == "detailview")
                                {
                                        if($colname == "lastname" && $module == 'Contacts')
                                               $firstname=$adb->query_result($list_result,$list_result_count,'firstname');
                                        $temp_val =$firstname.' '.$temp_val;

					$focus->record_id = $_REQUEST['recordid'];
                                        $value = '<a href="a" LANGUAGE=javascript onclick=\'add_data_to_relatedlist("'.$entity_id.'","'.$focus->record_id.'"); window.close()\'>'.$temp_val.'</a>';
                                }
				elseif($popuptype == "formname_specific")
				{
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_formname_specific("'.$_REQUEST['form'].'", "'.$entity_id.'", "'.$temp_val.'"); window.close()\'>'.$temp_val.'</a>';
				}
				elseif($popuptype == "inventory_prod")
				{
					$unitprice=$adb->query_result($list_result,$list_result_count,'unit_price');
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_inventory("'.$entity_id.'", "'.$temp_val.'", "'.$unitprice.'"); window.close()\'>'.$temp_val.'</a>';
				}
				elseif($popuptype == "inventory_pb")
				{

					$prod_id = $_REQUEST['productid'];
					$flname =  $_REQUEST['fldname'];
					$listprice=getListPrice($prod_id,$entity_id);	
					
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_inventory_pb("'.$listprice.'", "'.$flname.'"); window.close()\'>'.$temp_val.'</a>';
				}
				else
				{
					if($colname == "lastname")
                                                $firstname=$adb->query_result($list_result,$list_result_count,'firstname');
                                        $temp_val =$firstname.' '.$temp_val;
	
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return("'.$entity_id.'", "'.$temp_val.'"); window.close()\'>'.$temp_val.'</a>';
				}
			}
			else
			{
				if(($module == "Leads" && $colname == "lastname") || ($module == "Contacts" && $colname == "lastname"))
				{
			                if($colname == "lastname")
			                        $firstname=$adb->query_result($list_result,$list_result_count,'firstname');
			                $temp_val =$firstname.' '.$temp_val;
					$value = '<a href="index.php?action=DetailView&module='.$module.'&record='.$entity_id.'">'.$temp_val.'</a>';
				}
				elseif($module == "Activities")
                                {
                                        $actvity_type = $adb->query_result($list_result,$list_result_count,'activitytype');
                                        if($actvity_type == "Task")
                                        {
                                               $value = '<a href="index.php?action=DetailView&module='.$module.'&record='.$entity_id.'&activity_mode=Task">'.$temp_val.'</a>';
                                        }
                                        else
                                        {
                                                $value = '<a href="index.php?action=DetailView&module='.$module.'&record='.$entity_id.'&activity_mode=Events">'.$temp_val.'</a>';
                                        }
                                }
				elseif($module == "Vendor")
				{
						
                                        $value = '<a href="index.php?action=VendorDetailView&module=Products&record='.$entity_id.'">'.$temp_val.'</a>';
				}
				elseif($module == "PriceBook")
				{
						
                                        $value = '<a href="index.php?action=PriceBookDetailView&module=Products&record='.$entity_id.'">'.$temp_val.'</a>';
				}
				elseif($module == "SalesOrder")
				{
						
                                        $value = '<a href="index.php?action=SalesOrderDetailView&module=Orders&record='.$entity_id.'">'.$temp_val.'</a>';
				}
                                else
                                {
                                        $value = '<a href="index.php?action=DetailView&module='.$module.'&record='.$entity_id.'">'.$temp_val.'</a>';
                                }
			}
		}
		else
		{
			$value = $temp_val;
		}
	}
//	$value .= $returnset;
	return $value; 
}


function getListQuery($module,$where='')
{
	if($module == "HelpDesk")
	{
		$query = "select crmentity.crmid,troubletickets.title,troubletickets.status,troubletickets.priority,crmentity.smownerid, contactdetails.contactid, troubletickets.contact_id, contactdetails.firstname, contactdetails.lastname, ticketcf.* from troubletickets inner join ticketcf on ticketcf.ticketid = troubletickets.ticketid inner join crmentity on crmentity.crmid=troubletickets.ticketid left join contactdetails on troubletickets.contact_id=contactdetails.contactid left join users on crmentity.smownerid=users.id and troubletickets.ticketid = ticketcf.ticketid where crmentity.deleted=0";
		//$query = "select crmentity.crmid,troubletickets.title,troubletickets.status,troubletickets.priority,crmentity.smownerid, contactdetails.firstname, contactdetails.lastname, ticketcf.* from troubletickets inner join crmentity on crmentity.crmid=troubletickets.ticketid inner join ticketcf on ticketcf.ticketid = troubletickets.ticketid  left join contactdetails on troubletickets.contact_id=contactdetails.contactid left join users on crmentity.smownerid=users.id where crmentity.deleted=0";
	}
	if($module == "Accounts")
	{
		//$query = "select crmentity.crmid, account.accountname,accountbillads.city,account.website,account.phone,crmentity.smownerid, accountscf.*  from account, accountbillads, accountshipads, accountscf  inner join crmentity on crmentity.crmid=account.accountid and account.accountid=accountbillads.accountaddressid and account.accountid = accountscf.accountid and account.accountid=accountshipads.accountaddressid where crmentity.deleted=0";
		$query = "select crmentity.crmid, account.accountname,accountbillads.city,account.website,account.phone,crmentity.smownerid, accountscf.*  from account inner join crmentity on crmentity.crmid=account.accountid inner join accountbillads on account.accountid=accountbillads.accountaddressid inner join accountshipads on account.accountid=accountshipads.accountaddressid inner join accountscf on account.accountid = accountscf.accountid where crmentity.deleted=0";
	}
	if ($module == "Potentials")
	{
		 //$query = "select crmentity.crmid, crmentity.smownerid,account.accountname, potential.*, potentialscf.* from potential , account, potentialscf inner join crmentity on crmentity.crmid=potential.potentialid and potential.accountid = account.accountid and potentialscf.potentialid = potential.potentialid where crmentity.deleted=0 ".$where;
		 $query = "select crmentity.crmid, crmentity.smownerid,account.accountname, potential.accountid,potential.potentialname,potential.sales_stage,potential.amount,potential.currency,potential.closingdate,potential.typeofrevenue, potentialscf.* from potential inner join crmentity on crmentity.crmid=potential.potentialid inner join account on potential.accountid = account.accountid inner join potentialscf on potentialscf.potentialid = potential.potentialid where crmentity.deleted=0 ".$where;

	}
	if($module == "Leads")
	{
		//$query = "select crmentity.crmid, leaddetails.firstname, leaddetails.lastname, leaddetails.company, leadaddress.phone, leadsubdetails.website, leaddetails.email, crmentity.smownerid, leadscf.* from leaddetails, leadaddress, leadsubdetails, leadscf  inner join crmentity on crmentity.crmid=leaddetails.leadid and leaddetails.leadid=leadaddressid and leaddetails.leadid = leadscf.leadid and leadaddress.leadaddressid=leadsubdetails.leadsubscriptionid where crmentity.deleted=0 and leaddetails.converted=0";
		$query = "select crmentity.crmid, leaddetails.firstname, leaddetails.lastname, leaddetails.company, leadaddress.phone, leadsubdetails.website, leaddetails.email, crmentity.smownerid, leadscf.* from leaddetails inner join crmentity on crmentity.crmid=leaddetails.leadid inner join leadsubdetails on leadsubdetails.leadsubscriptionid=leaddetails.leadid inner join leadaddress on leadaddress.leadaddressid=leadsubdetails.leadsubscriptionid inner join leadscf on leaddetails.leadid = leadscf.leadid where crmentity.deleted=0 and leaddetails.converted=0";
	}
	if($module == "Products")
	{
		$query = "select crmentity.crmid, products.*, productcf.* from products inner join crmentity on crmentity.crmid=products.productid left join productcf on products.productid = productcf.productid where crmentity.deleted=0";
	}
        if($module == "Notes")
        {
		$query="select crmentity.crmid, notes.title, notes.contact_id, notes.filename, crmentity.modifiedtime,senotesrel.crmid as relatedto, contactdetails.firstname, contactdetails.lastname from notes inner join crmentity on crmentity.crmid=notes.notesid left join senotesrel on senotesrel.notesid=notes.notesid left join contactdetails on contactdetails.contactid = notes.contact_id where crmentity.deleted=0 group by notes.notesid order by crmentity.modifiedtime ASC";
        }
        if($module == "Calls")
        {
		$query = "select crmentity.crmid, crmentity.smownerid, seactivityrel.activityid, calls.* from calls inner join crmentity on crmentity.crmid = calls.callid left join seactivityrel on seactivityrel.activityid = calls.callid where crmentity.deleted=0";
        }
	if($module == "Contacts")
        {
                $query = "select crmentity.crmid, crmentity.smownerid, contactdetails.*, contactaddress.*, contactsubdetails.*, contactscf.*, account.accountname from contactdetails, contactaddress, contactsubdetails, contactscf inner join crmentity on crmentity.crmid=contactdetails.contactid and contactdetails.contactid=contactaddress.contactaddressid and contactdetails.contactid = contactscf.contactid and contactaddress.contactaddressid=contactsubdetails.contactsubscriptionid left join account on account.accountid = contactdetails.accountid where crmentity.deleted=0";
		//$query = "select crmentity.crmid, crmentity.smownerid, contactdetails.*, contactaddress.*, contactsubdetails.*, contactscf.*, account.accountname from contactdetails, contactaddress, contactsubdetails, contactscf,crmentity,account where crmentity.crmid=contactdetails.contactid and contactdetails.contactid=contactaddress.contactaddressid and contactdetails.contactid = contactscf.contactid and contactaddress.contactaddressid=contactsubdetails.contactsubscriptionid and account.accountid = contactdetails.accountid and crmentity.deleted=0";
        }
	if($module == "Meetings")
        {
		$query = "select crmentity.crmid,crmentity.smownerid, meetings.*, activity.subject, activity.activityid, contactdetails.lastname, contactdetails.firstname, contactdetails.contactid from meetings inner join crmentity on crmentity.crmid=meetings.meetingid inner join activity on activity.activityid= crmentity.crmid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid WHERE crmentity.deleted=0";
        }
	if($module == "Activities")
        {
		//$query = "select crmentity.crmid,crmentity.smownerid,crmentity.setype, activity.*, contactdetails.lastname, contactdetails.firstname, contactdetails.contactid from activity inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid WHERE crmentity.deleted=0 and (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task') ".$where;
		$query = "select crmentity.crmid,crmentity.smownerid,crmentity.setype, activity.*, contactdetails.lastname, contactdetails.firstname, contactdetails.contactid from activity inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid WHERE crmentity.deleted=0 and (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task') ".$where;
        }
	if($module == "Emails")
        {
                $query = "select crmentity.crmid,crmentity.smownerid, emails.emailid, emails.filename, activity.subject, activity.activityid, contactdetails.lastname, contactdetails.firstname, contactdetails.contactid , activity.date_start from emails inner join crmentity on crmentity.crmid=emails.emailid inner join activity on activity.activityid = crmentity.crmid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid WHERE crmentity.deleted=0";
        }
	if($module == "Faq")
	{
		$query = "select crmentity.crmid,faq.question,faq.category,sefaqrel.crmid as relatedto from faq inner join crmentity on crmentity.crmid=faq.id left join sefaqrel on faq.id=sefaqrel.faqid where crmentity.deleted=0";
	}
	if($module == "Vendor")
	{
		$query = "select crmentity.crmid, vendor.* from vendor inner join crmentity on crmentity.crmid=vendor.vendorid where crmentity.deleted=0";
	}
	if($module == "PriceBook")
	{
		$query = "select crmentity.crmid, pricebook.* from pricebook inner join crmentity on crmentity.crmid=pricebook.pricebookid where crmentity.deleted=0";
	}
	if($module == "Quotes")
	{
		//$query="select products.productname,products.unit_price,quotesproductrel.* from quotesproductrel inner join products on products.productid=quotesproductrel.productid where quoteid=".$focus->id;
		$query = "select crmentity.*, quotes.*, quotesbillads.*, quotesshipads.* from quotes inner join crmentity on crmentity.crmid=quotes.quoteid inner join quotesbillads on quotes.quoteid=quotesbillads.quotebilladdressid inner join quotesshipads on quotes.quoteid=quotesshipads.quoteshipaddressid where crmentity.deleted=0";
	}
	if($module == "Orders")
	{
		$query = "select crmentity.*, purchaseorder.*, pobillads.*, poshipads.*, poproductrel.productid from purchaseorder inner join crmentity on crmentity.crmid=purchaseorder.purchaseorderid inner join pobillads on purchaseorder.purchaseorderid=pobillads.pobilladdressid inner join poshipads on purchaseorder.purchaseorderid=poshipads.poshipaddressid  inner join poproductrel on purchaseorder.purchaseorderid=poproductrel.purchaseorderid where crmentity.deleted=0";
	}
	if($module == "SalesOrder")
	{
		$query = "select crmentity.*, salesorder.*, sobillads.*, soshipads.*, soproductrel.productid from salesorder inner join crmentity on crmentity.crmid=salesorder.salesorderid inner join sobillads on salesorder.salesorderid=sobillads.sobilladdressid inner join soshipads on salesorder.salesorderid=soshipads.soshipaddressid  inner join soproductrel on salesorder.salesorderid=soproductrel.salesorderid where crmentity.deleted=0";
	}
	if($module == "Invoice")
	{
		$query = "select crmentity.*, invoice.*, invoicebillads.*, invoiceshipads.*, invoiceproductrel.productid from invoice inner join crmentity on crmentity.crmid=invoice.invoiceid inner join invoicebillads on invoice.invoiceid=invoicebillads.invoicebilladdressid inner join invoiceshipads on invoice.invoiceid=invoiceshipads.invoiceshipaddressid  inner join invoiceproductrel on invoice.invoiceid=invoiceproductrel.invoiceid where crmentity.deleted=0";
	}
	global $others_permission_id;
	global $current_user;	
	if($others_permission_id == 3 && $module != 'Notes' && $module != 'Products' && $module != 'Faq' && $module!= 'Vendor' && $module != 'PriceBook')
	{
		$query .= " and crmentity.smownerid in(".$current_user->id .",0)";
	}
	return $query;
}

function getActionid($action)
{
	$actionid = '';
	if($action == 'Save')
	{
		$actionid= 0;
	}
	else if($action == 'EditView')
	{
		$actionid= 1;
	}
	else if($action == 'Delete')
	{
		$actionid= 2;
	}
	else if($action == 'index')
	{
		$actionid= 3;
	}
	else if($action == 'DetailView')
	{
		$actionid= 4;
	}		
	else if($action == 'Import')
	{
		$actionid= 5;
	}
	else if($action == 'Export')
	{
		$actionid= 6;
	}
	else if($action == 'BusinessCard')
	{
		$actionid= 7;
	}
	else if($action == 'Merge')
	{
		$actionid= 8;
	}
	return $actionid;
}

function getActionname($actionid)
{
	$actionname = '';
	if($actionid == 0)
	{
		$actionname= 'Save';
	}
	else if($actionid == 1)
	{
		$actionname= 'EditView';
	}
	else if($actionid == 2)
	{
		$actionname= 'Delete';
	}
	else if($actionid == 3)
	{
		$actionname= 'index';
	}
	else if($actionid == 4)
	{
		$actionname= 'DetailView';
	}		
	else if($actionid == 5)
	{
		$actionname= 'Import';
	}
	else if($actionid == 6)
	{
		$actionname= 'Export';
	}
	else if($actionid == 7)
	{
		$actionname= 'BusinessCard';
	}
	else if($actionid == 8)
	{
		$actionname= 'Merge';
	}
	return $actionname;
}


function getUserId($record)
{
	global $adb;
        $user_id=$adb->query_result($adb->query("select * from crmentity where crmid = ".$record),0,'smownerid');
	return $user_id;	
}

function insertProfile2field($profileid)
{
	global $adb;
	$adb->database->SetFetchMode(ADODB_FETCH_ASSOC); 
	$fld_result = $adb->query("select * from field where generatedtype=1 and displaytype in (1,2)");
        $num_rows = $adb->num_rows($fld_result);
        for($i=0; $i<$num_rows; $i++)
        {
                 $tab_id = $adb->query_result($fld_result,$i,'tabid');
                 $field_id = $adb->query_result($fld_result,$i,'fieldid');
                 $adb->query("insert into profile2field values (".$profileid.",".$tab_id.",".$field_id.",0,1)");
	}
}

function insert_def_org_field()
{
	global $adb;
	$adb->database->SetFetchMode(ADODB_FETCH_ASSOC); 
	$fld_result = $adb->query("select * from field where generatedtype=1 and displaytype in (1,2)");
        $num_rows = $adb->num_rows($fld_result);
        for($i=0; $i<$num_rows; $i++)
        {
                 $tab_id = $adb->query_result($fld_result,$i,'tabid');
                 $field_id = $adb->query_result($fld_result,$i,'fieldid');
                 $adb->query("insert into def_org_field values (".$tab_id.",".$field_id.",0,1)");
	}
}

function getProfile2FieldList($fld_module, $profileid)
{
	global $adb;
	$tabid = getTabid($fld_module);
	
	$query = "select profile2field.visible,field.* from profile2field inner join field on field.fieldid=profile2field.fieldid where profile2field.profileid=".$profileid." and profile2field.tabid=".$tabid;
	$result = $adb->query($query);
	return $result;
}
function getDefOrgFieldList($fld_module)
{
	global $adb;
	$tabid = getTabid($fld_module);
	
	$query = "select def_org_field.visible,field.* from def_org_field inner join field on field.fieldid=def_org_field.fieldid where def_org_field.tabid=".$tabid;
	$result = $adb->query($query);
	return $result;
}

function getQuickCreate($tabid,$actionid)
{
        $QuickCreateForm= 'true';

        $profile_id = $_SESSION['authenticated_user_profileid'];
        $tab_per_Data = getAllTabsPermission($profile_id);

        $permissionData = $_SESSION['action_permission_set'];

        if($tab_per_Data[$tabid] !=0)
        {
                $QuickCreateForm= 'false';
        }
        if($permissionData[$tabid][1] !=0)
        {
                $QuickCreateForm= 'false';
        }
	return $QuickCreateForm;

}
function ChangeStatus($status,$activityid,$activity_mode='')
 {
        global $adb;
        if ($activity_mode == 'Task')
        {
                $query = "Update activity set status='".$status."' where activityid = ".$activityid;
        }
        elseif ($activity_mode == 'Events')
        {
                $query = "Update activity set eventstatus='".$status."' where activityid = ".$activityid;
        }
        $adb->query($query);
 }

//parameter $viewid added for customview 27/5
function AlphabeticalSearch($module,$action,$fieldname,$query,$type,$popuptype='',$recordid='',$return_module='',$append_url='',$viewid='')
{
	if($type=='advanced')
		$flag='&advanced=true';

	if($popuptype != '')
		$popuptypevalue = "&popuptype=".$popuptype;

        if($recordid != '')
                $returnvalue = '&recordid='.$recordid;
        if($return_module != '')
                $returnvalue .= '&return_module='.$return_module;

	for($var='A',$i =1;$i<=26;$i++,$var++)
		$list .= '<td class="alphaBg"><a href="index.php?module='.$module.'&action='.$action.'&viewname='.$viewid.'&query='.$query.'&'.$fieldname.'='.$var.$flag.$popuptypevalue.$returnvalue.$append_url.'">'.$var.'</a></td>';

	return $list;
}

function getDisplayDate($cur_date_val)
{
	global $current_user;
	$dat_fmt = $current_user->date_format;
	if($dat_fmt == '')
	{
		$dat_fmt = 'dd-mm-yyyy';
	}

		//echo $dat_fmt;
		//echo '<BR>'.$cur_date_val.'<BR>';
		$date_value = explode(' ',$cur_date_val);
		list($y,$m,$d) = split('-',$date_value[0]);
		//echo $y.'----'.$m.'------'.$d;
		if($dat_fmt == 'dd-mm-yyyy')
		{
			//echo '<br> inside 1';
			$display_date = $d.'-'.$m.'-'.$y;
		}
		elseif($dat_fmt == 'mm-dd-yyyy')
		{

			//echo '<br> inside 2';
			$display_date = $m.'-'.$d.'-'.$y;
		}
		elseif($dat_fmt == 'yyyy-mm-dd')
		{

			//echo '<br> inside 3';
			$display_date = $y.'-'.$m.'-'.$d;
		}

		if($date_value[1] != '')
		{
			$display_date = $display_date.' '.$date_value[1];
		}
	return $display_date;
 			
}


function getNewDisplayDate()
{
	global $current_user;
	$dat_fmt = $current_user->date_format;
	if($dat_fmt == '')
        {
                $dat_fmt = 'dd-mm-yyyy';
        }
	//echo $dat_fmt;
	//echo '<BR>';
	$display_date='';
	if($dat_fmt == 'dd-mm-yyyy')
	{
		$display_date = date('d-m-Y');
	}
	elseif($dat_fmt == 'mm-dd-yyyy')
	{
		$display_date = date('m-d-Y');
	}
	elseif($dat_fmt == 'yyyy-mm-dd')
	{
		$display_date = date('Y-m-d');
	}
		
	//echo $display_date;
	return $display_date;
}

function getDBInsertDateValue($value)
{
	global $current_user;
	$dat_fmt = $current_user->date_format;
	if($dat_fmt == '')
        {
                $dat_fmt = 'dd-mm-yyyy';
        }
	//echo $dat_fmt;
	//echo '<BR>';
	$insert_date='';
	if($dat_fmt == 'dd-mm-yyyy')
	{
		list($d,$m,$y) = split('-',$value);
	}
	elseif($dat_fmt == 'mm-dd-yyyy')
	{
		list($m,$d,$y) = split('-',$value);
	}
	elseif($dat_fmt == 'yyyy-mm-dd')
	{
		list($y,$m,$d) = split('-',$value);
	}
		
	//echo $display_date;
	$insert_date=$y.'-'.$m.'-'.$d;
	return $insert_date;
}

function getDisplayCurrency()
{
	global $adb;
	$sql1 = "select * from currency_info";
	$result = $adb->query($sql1);
	$curr_name = $adb->query_result($result,0,"currency_name");
	$curr_symbol = $adb->query_result($result,0,"currency_symbol");
	$disp_curr = $curr_name.' : '.$curr_symbol;
	return $disp_curr;
}	

function getCurrencySymbol()
{
	global $adb;
	$sql1 = "select * from currency_info";
	$result = $adb->query($sql1);
	$curr_symbol = $adb->query_result($result,0,"currency_symbol");
	return $curr_symbol;
}

function getRelatedLists($module,$focus)
{
	global $adb;
	global $profile_id;
	$mod_dir_name=getModuleDirName($module);
	$tab_per_Data = getAllTabsPermission($profile_id);
	$permissionData = $_SESSION['action_permission_set'];
	$inc_file = 'modules/'.$mod_dir_name.'/RenderRelatedListUI.php';
	include($inc_file);
	$cur_tab_id = getTabid($module);

	$sql1 = "select * from relatedlists where tabid=".$cur_tab_id;
	$result = $adb->query($sql1);
	$num_row = $adb->num_rows($result);
	for($i=0; $i<$num_row; $i++)
	{
		$rel_tab_id = $adb->query_result($result,$i,"related_tabid");
		$funtion_name = $adb->query_result($result,$i,"name");
		if($rel_tab_id != 0)
		{
			if($tab_per_Data[$rel_tab_id] == 0)
			{
		        	if($permissionData[$rel_tab_id][3] == 0)
        			{
		                	$focus_list = & $focus->$funtion_name($focus->id);
        			}
			}
		}
		else
		{
			$focus_list = & $focus->$funtion_name($focus->id);
		}
	}

}

function getModuleDirName($module)
{
	if($module == 'Vendor' || $module == 'PriceBook')
	{
		$dir_name = 'Products';	
	}
	elseif($module == 'SalesOrder')
	{
		$dir_name = 'Orders';
	}
	else
	{
		$dir_name = $module;
	}
	return $dir_name;
}

function getReminderSelectOption($start,$end,$fldname,$selvalue='')
{
	global $mod_strings;
	global $app_strings;
	
	$def_sel ="";
	$OPTION_FLD = "<SELECT name=".$fldname.">";
	for($i=$start;$i<=$end;$i++)
	{
		if($i==$selvalue)
		$def_sel = "SELECTED";
		$OPTION_FLD .= "<OPTION VALUE=".$i." ".$def_sel.">".$i."</OPTION>\n";
		$def_sel = "";
	}
	$OPTION_FLD .="</SELECT>";
	return $OPTION_FLD;
}

function getAssociatedProducts($module,$focus)
{
	global $adb;
	$output = '';
	if($module == 'Quotes')
	{
		$query="select products.productname,products.unit_price,quotesproductrel.* from quotesproductrel inner join products on products.productid=quotesproductrel.productid where quoteid=".$focus->id;
	}
	elseif($module == 'Orders')
	{
		$query="select products.productname,products.unit_price,poproductrel.* from poproductrel inner join products on products.productid=poproductrel.productid where purchaseorderid=".$focus->id;
	}
	elseif($module == 'SalesOrder')
	{
		$query="select products.productname,products.unit_price,soproductrel.* from soproductrel inner join products on products.productid=soproductrel.productid where salesorderid=".$focus->id;
	}
	elseif($module == 'Invoice')
	{
		$query="select products.productname,products.unit_price,invoiceproductrel.* from invoiceproductrel inner join products on products.productid=invoiceproductrel.productid where invoiceid=".$focus->id;
	}

	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=1;$i<=$num_rows;$i++)
	{
		$productname=$adb->query_result($result,$i-1,'productname');
		$unitprice=$adb->query_result($result,$i-1,'unit_price');
		$productid=$adb->query_result($result,$i-1,'productid');
		$qty=$adb->query_result($result,$i-1,'quantity');
		$listprice=$adb->query_result($result,$i-1,'listprice');
		$total = $qty*$listprice;

		$product_id_var = 'hdnProductId'.$i;
		$status_var = 'hdnRowStatus'.$i;
		$qty_var = 'txtQty'.$i;
		$list_price_var = 'txtListPrice'.$i;	
		$total_var = 'total'.$i;	

		$output .='<tr id=row'.$i.' class="evenListRow">';
		$output .='<td width="20%" align="left">'.$productname.'</td>';
		$output .= '<td align="left"><input type=text id="'.$qty_var.'" name="'.$qty_var.'" size="5" value="'.$qty.'" onBlur=\'calcTotal('.$i.')\'></td>';
		$output .= '<td class="dataLabel" align="left">'.$unitprice.'</td>';
		$output .='<td class="dataLabel"><input type=text id="'.$list_price_var.'" name="'.$list_price_var.'" size="12" value="'.$listprice.'" onBlur=\'calcTotal('.$i.')\'><input title="Change [Alt+G]" accessKey="G" type="button" class="button" value="Select" name="button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Products&action=PriceBookPopup&html=Popup_picker&form=EditView&popuptype=inventory_pb&fldname='.$list_price_var.'&productid='.$productid.'","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\'></td>';
		$output .='<td class="dataLabel"><div id="'.$total_var.'" align="right">'.$total.'</div></td>';
		$output .='<td class="dataLabel" id="delCol'.$i.'"><a href=\'javascript:delRow('.$i.')\'>Del</a>';
		$output .= '<input type=hidden id="'.$product_id_var.'" name="'.$product_id_var.'" value='.$productid.'>';
		$output .= '<input type=hidden id="'.$status_var.'" name="'.$status_var.'">';
		$output .= '<input type=hidden id="hdnTotal'.$i.'" name="hdnTotal'.$i.'">';
		$output .= '</td></tr>';

	}
	return $output;

}
function getNoOfAssocProducts($module,$focus)
{
	global $adb;
	$output = '';
	if($module == 'Quotes')
	{
		$query="select products.productname,products.unit_price,quotesproductrel.* from quotesproductrel inner join products on products.productid=quotesproductrel.productid where quoteid=".$focus->id;
	}
	elseif($module == 'Orders')
	{
		$query="select products.productname,products.unit_price,poproductrel.* from poproductrel inner join products on products.productid=poproductrel.productid where purchaseorderid=".$focus->id;
	}
	elseif($module == 'SalesOrder')
	{
		$query="select products.productname,products.unit_price,soproductrel.* from soproductrel inner join products on products.productid=soproductrel.productid where salesorderid=".$focus->id;
	}
	elseif($module == 'Invoice')
	{
		$query="select products.productname,products.unit_price,invoiceproductrel.* from invoiceproductrel inner join products on products.productid=invoiceproductrel.productid where invoiceid=".$focus->id;
	}
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	return $num_rows;
}

function getListPrice($productid,$pbid)
{
	global $adb;
	$query = "select listprice from pricebookproductrel where pricebookid=".$pbid." and productid=".$productid;
	$result = $adb->query($query);
	$lp = $adb->query_result($result,0,'listprice');
	return $lp;
}

function getDetailAssociatedProducts($module,$focus)
{
	global $adb;
	$output = '';
	

	//$output .='<table width="100%" border="0" cellspacing="1" cellpadding="0">';		
	$output .= '<tr><td width="15%" class="dataLabel">Product</td><td width="15%" class="dataLabel">Quantity</td><td width="15%" class="dataLabel">Unit Price</td><td width="15%" class="dataLabel">List Price</td><td width="15%" class="dataLabel">Total</td></tr>';

	if($module == 'Quotes')
	{
		$query="select products.productname,products.unit_price,quotesproductrel.* from quotesproductrel inner join products on products.productid=quotesproductrel.productid where quoteid=".$focus->id;
	}
	elseif($module == 'Orders')
	{
		$query="select products.productname,products.unit_price,poproductrel.* from poproductrel inner join products on products.productid=poproductrel.productid where purchaseorderid=".$focus->id;
	}
	elseif($module == 'SalesOrder')
	{
		$query="select products.productname,products.unit_price,soproductrel.* from soproductrel inner join products on products.productid=soproductrel.productid where salesorderid=".$focus->id;
	}
	elseif($module == 'Invoice')
	{
		$query="select products.productname,products.unit_price,invoiceproductrel.* from invoiceproductrel inner join products on products.productid=invoiceproductrel.productid where invoiceid=".$focus->id;
	}
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=1;$i<=$num_rows;$i++)
	{

		if (($i%2)==0)
                        $output .= '<tr height=20 class=dataLabel>';
                else
                        $output .= '<tr height=20 class=dataLabel>';	

		$productname=$adb->query_result($result,$i-1,'productname');
		$unitprice=$adb->query_result($result,$i-1,'unit_price');
		$productid=$adb->query_result($result,$i-1,'productid');
		$qty=$adb->query_result($result,$i-1,'quantity');
		$listprice=$adb->query_result($result,$i-1,'listprice');
		$total = $qty*$listprice;

			
		$output .= '<td width="15%">'.$productname.'</td><td width="15%">'.$qty.'</td><td width="15%" >'.$unitprice.'</td><td width="15%">'.$listprice.'</td><td width="15%">'.$total.'</td></tr>';

		

	}
	
		$output .= '<tr><td width="15%" class="dataLabel" colspan="4">Sub Total:</td><td width="15%" class="dataLabel">'.$focus->column_fields['hdnSubTotal'].'</td></tr>';
		$output .= '<tr><td width="15%" class="dataLabel" colspan="4">Tax:</td><td width="15%" class="dataLabel">'.$focus->column_fields['txtTax'].'</td></tr>';
		$output .= '<tr><td width="15%" class="dataLabel" colspan="4">Total:</td><td width="15%" class="dataLabel">'.$focus->column_fields['hdnGrandTotal'].'</td></tr>';
		//$output .= '</table>';
	return $output;

}
?>
