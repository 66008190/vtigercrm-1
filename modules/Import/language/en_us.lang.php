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
 * $Header$
 * Description:  Defines the English language pack for the Account module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


$mod_strings = Array(
'LBL_IMPORT_MODULE_NO_DIRECTORY'=>'The directory ',
'LBL_IMPORT_MODULE_NO_DIRECTORY_END'=>' does not exist or is not writable',
'LBL_IMPORT_MODULE_ERROR_NO_UPLOAD'=>'File was not uploaded successfully, try again',
'LBL_IMPORT_MODULE_ERROR_LARGE_FILE'=>'File is too large. Max:',
'LBL_IMPORT_MODULE_ERROR_LARGE_FILE_END'=>'Bytes. Change $upload_maxsize in config.php',
'LBL_MODULE_NAME'=>'Import',
'LBL_TRY_AGAIN'=>'Try Again',
'LBL_ERROR'=>'Error:',
'ERR_MULTIPLE'=>'Multiple columns have been defined with the same field name.',
'ERR_MISSING_REQUIRED_FIELDS'=>'Missing required fields:',
'ERR_SELECT_FULL_NAME'=>'You cannot select Full Name when First Name and Last Name are selected.',
'ERR_SELECT_FILE'=>'Select a file to upload.',
'LBL_SELECT_FILE'=>'Select file:',
'LBL_CUSTOM'=>'Custom',
'LBL_DONT_MAP'=>'-- Do not map this field --',
'LBL_STEP_1_TITLE'=>'Step 1: Select the Source',
'LBL_WHAT_IS'=>'What is the data source?',
'LBL_MICROSOFT_OUTLOOK'=>'Microsoft Outlook',
'LBL_ACT'=>'Act!',
'LBL_SALESFORCE'=>'Salesforce.com',
'LBL_MY_SAVED'=>'My Saved Sources:',
'LBL_PUBLISH'=>'publish',
'LBL_DELETE'=>'delete',
'LBL_PUBLISHED_SOURCES'=>'Published Sources:',
'LBL_UNPUBLISH'=>'un-publish',
'LBL_NEXT'=>'Next >',
'LBL_BACK'=>'< Back',
'LBL_STEP_2_TITLE'=>'Step 2: Upload Export File',
'LBL_HAS_HEADER'=>'Has Header:',

'LBL_NUM_1'=>'1.',
'LBL_NUM_2'=>'2.',
'LBL_NUM_3'=>'3.',
'LBL_NUM_4'=>'4.',
'LBL_NUM_5'=>'5.',
'LBL_NUM_6'=>'6.',
'LBL_NUM_7'=>'7.',
'LBL_NUM_8'=>'8.',
'LBL_NUM_9'=>'9.',
'LBL_NUM_10'=>'10.',
'LBL_NUM_11'=>'11.',
'LBL_NUM_12'=>'12.',
'LBL_NOW_CHOOSE'=>'Now choose that file to import:',
'LBL_IMPORT_OUTLOOK_TITLE'=>'Microsoft Outlook 98 and 2000 can export data in the <b>Comma Separated Values</b> format which can be used to import data into the system. To export your data from Outlook, follow the steps below:',
'LBL_OUTLOOK_NUM_1'=>'Start <b>Outlook</b>',
'LBL_OUTLOOK_NUM_2'=>'Select the <b>File</b> menu, then the <b>Import and Export ...</b> menu option',
'LBL_OUTLOOK_NUM_3'=>'Choose <b>Export to a file</b> and click Next',
'LBL_OUTLOOK_NUM_4'=>'Choose <b>Comma Separated Values (Windows)</b> and click <b>Next</b>.<br>  Note: You may be prompted to install the export component',
'LBL_OUTLOOK_NUM_5'=>'Select the <b>Contacts</b> folder and click <b>Next</b>. You can select different contacts folders if your contacts are stored in multiple folders',
'LBL_OUTLOOK_NUM_6'=>'Choose a filename and click <b>Next</b>',
'LBL_OUTLOOK_NUM_7'=>'Click <b>Finish</b>',
'LBL_IMPORT_ACT_TITLE'=>'Act! can export data in the <b>Comma Separated Values</b> format which can be used to import data into the system. To export your data from Act!, follow the steps below:',
'LBL_ACT_NUM_1'=>'Launch <b>ACT!</b>',
'LBL_ACT_NUM_2'=>'Select the <b>File</b> menu, the <b>Data Exchange</b> menu option, then the <b>Export...</b> menu option',
'LBL_ACT_NUM_3'=>'Select the file type <b>Text-Delimited</b>',
'LBL_ACT_NUM_4'=>'Choose a filename and location for the exported data and click <b>Next</b>',
'LBL_ACT_NUM_5'=>'Select <b>Contacts records only</b>',
'LBL_ACT_NUM_6'=>'Click the <b>Options...</b> button',
'LBL_ACT_NUM_7'=>'Select <b>Comma</b> as the field separator character',
'LBL_ACT_NUM_8'=>'Check the <b>Yes, export field names</b> checkbox and click <b>OK</b>',
'LBL_ACT_NUM_9'=>'Click <b>Next</b>',
'LBL_ACT_NUM_10'=>'Select <b>All Records</b> and then Click <b>Finish</b>',

'LBL_IMPORT_SF_TITLE'=>'Salesforce.com can export data in the <b>Comma Separated Values</b> format which can be used to import data into the system. To export your data from Salesforce.com, follow the steps below:',
'LBL_SF_NUM_1'=>'Open your browser, go to http://www.salesforce.com, and login with your email address and password',
'LBL_SF_NUM_2'=>'Click on the <b>Reports</b> tab on the top menu',
'LBL_SF_NUM_3'=>'To export Accounts:</b> Click on the <b>Active Accounts</b> link<br><b>To export Contacts:</b> Click on the <b>Mailing List</b> link',
'LBL_SF_NUM_4'=>'On <b>Step 1: Select your report type</b>, select <b>Tabular Report</b>click <b>Next</b>',
'LBL_SF_NUM_5'=>'On <b>Step 2: Select the report columns</b>, choose the columns you want to export and click <b>Next</b>',
'LBL_SF_NUM_6'=>'On <b>Step 3: Select the information to summarize</b>, just click <b>Next</b>',
'LBL_SF_NUM_7'=>'On <b>Step 4: Order the report columns</b>, just click <b>Next</b>',
'LBL_SF_NUM_8'=>'On <b>Step 5: Select your report criteria</b>, under <b>Start Date</b>, choose a date far enough in the past to include all your Accounts. You can also export a subset of Accounts using more advanced criteria. When you are done, click <b>Run Report</b>',
'LBL_SF_NUM_9'=>'A report will be generated, and the page should display <b>Report Generation Status: Complete.</b> Now click <b>Export to Excel</b>',
'LBL_SF_NUM_10'=>'On <b>Export Report:</b>, for <b>Export File Format:</b>, choose <b>Comma Delimited .csv</b>. Click <b>Export</b>.',
'LBL_SF_NUM_11'=>'A dialog will pop up for you to save the export file to your computer.',
'LBL_IMPORT_CUSTOM_TITLE'=>'Many applications will allow you to export data into a <b>Comma Delimited text file (.csv)</b>. Generally most applications follow these general steps:',
'LBL_CUSTOM_NUM_1'=>'Launch the application and Open the data file',
'LBL_CUSTOM_NUM_2'=>'Select the <b>Save As...</b> or <b>Export...</b> menu option',
'LBL_CUSTOM_NUM_3'=>'Save the file in a <b>CSV</b> or <b>Comma Separated Values</b> format',

'LBL_STEP_3_TITLE'=>'Step 3: Confirm Fields and Import',

'LBL_SELECT_FIELDS_TO_MAP'=>'In the list below, select the fields in your import file that should be imported into each field in the system. When you are finished, click <b>Import Now</b>:',

'LBL_DATABASE_FIELD'=>'Database Field',
'LBL_HEADER_ROW'=>'Header Row',
'LBL_ROW'=>'Row',
'LBL_SAVE_AS_CUSTOM'=>'Save as Custom Mapping:',
'LBL_CONTACTS_NOTE_1'=>'Either Last Name or Full Name must be mapped.',
'LBL_CONTACTS_NOTE_2'=>'If Full Name is mapped, then First Name and Last Name are ignored.',
'LBL_CONTACTS_NOTE_3'=>'If Full Name is mapped, then the data in Full Name will be split into First Name and Last Name when inserted into the database.',
'LBL_CONTACTS_NOTE_4'=>'Fields ending in Address Street 2 and Address Street 3 are concatenated together with the main Address Street Field when inserted into the database.',
'LBL_ACCOUNTS_NOTE_1'=>'Account Name must be mapped.',
'LBL_ACCOUNTS_NOTE_2'=>'Fields ending in Address Street 2 and Address Street 3 are concatenated together with the main Address Street Field when inserted into the database.',
'LBL_OPPORTUNITIES_NOTE_1'=>'Opportunity Name, Account Name, Date Closed, and Sales Stage are required fields.',
'LBL_IMPORT_NOW'=>'Import Now',
'LBL_'=>'',
'LBL_'=>'',
'LBL_CANNOT_OPEN'=>'Cannot open the imported file for reading',
'LBL_NOT_SAME_NUMBER'=>'There were not the same number of fields per line in your file',
'LBL_NO_LINES'=>'There were no lines in your import file',
'LBL_FILE_ALREADY_BEEN_OR'=>'The import file has already been processed or does not exist',
'LBL_SUCCESS'=>'Success:',
'LBL_SUCCESSFULLY'=>'Succesfully Imported',
'LBL_LAST_IMPORT_UNDONE'=>'Your last import was undone',
'LBL_NO_IMPORT_TO_UNDO'=>'There was no import to undo.',
'LBL_FAIL'=>'Fail:',
'LBL_RECORDS_SKIPPED'=>'records skipped because they were missing one or more required fields',
'LBL_IDS_EXISTED_OR_LONGER'=>'records skipped because the id\'s either existed or where longer than 36 characters',
'LBL_RESULTS'=>'Results',
'LBL_IMPORT_MORE'=>'Import More',
'LBL_FINISHED'=>'Finished',
'LBL_UNDO_LAST_IMPORT'=>'Undo Last Import',



);

$mod_list_strings = Array(
'contacts_import_fields' => Array(
	"id"=>"Contact ID"
	,"first_name"=>"First Name"
	,"last_name"=>"Last Name"
	,"salutation"=>"Salutation"
	,"lead_source"=>"Lead Source"
	,"birthdate"=>"Lead Source"
	,"do_not_call"=>"Do Not Call"
	,"email_opt_out"=>"Email Opt Out"
	,"primary_address_street_2"=>"Primary Address Street 2"
	,"primary_address_street_3"=>"Primary Address Street 3"
	,"alt_address_street_2"=>"Other Address Street 2"
	,"alt_address_street_3"=>"Other Address Street 3"
	,"full_name"=>"Full Name"
	,"account_name"=>"Account Name"
	,"account_id"=>"Account ID"
	,"title"=>"Title"
	,"department"=>"Department"
	,"birthdate"=>"Birthdate"
	,"do_not_call"=>"Do Not Call"
	,"phone_home"=>"Phone (Home)"
	,"phone_mobile"=>"Phone (Mobile)"
	,"phone_work"=>"Phone (Work)"
	,"phone_other"=>"Phone (Other)"
	,"phone_fax"=>"Fax"
	,"email1"=>"Email"
	,"email2"=>"Email (Other)"
	,"yahoo_id"=>"Yahoo! ID"
	,"assistant"=>"Assistant"
	,"assistant_phone"=>"Assistant Phone"
	,"primary_address_street"=>"Primary Address Street"
	,"primary_address_city"=>"Primary Address City"
	,"primary_address_state"=>"Primary Address State"
	,"primary_address_postalcode"=>"Primary Address Postalcode"
	,"primary_address_country"=>"Primary Address Country"
	,"alt_address_street"=>"Other Address Street"
	,"alt_address_city"=>"Other Address City"
	,"alt_address_state"=>"Other Address State"
	,"alt_address_postalcode"=>"Other Address Postalcode"
	,"alt_address_country"=>"Other Address Country"
	,"description"=>"Description"

	),

'accounts_import_fields' => Array(
	"id"=>"Account ID",
	"name"=>"Account Name",
	"website"=>"Website",
	"industry"=>"Industry",
	"type"=>"Type",
	"ticker_symbol"=>"Ticker Symbol",
	"parent_name"=>"Member of",
	"employees"=>"Employees",
	"ownership"=>"Ownership",
	"phone_office"=>"Phone",
	"phone_fax"=>"Fax",
	"phone_alternate"=>"Other Phone",
	"email1"=>"Email",
	"email2"=>"Other Email",
	"rating"=>"Rating",
	"sic_code"=>"SIC Code",
	"annual_revenue"=>"Annual Revenue",
	"billing_address_street"=>"Billing Address Street",
	"billing_address_street_2"=>"Billing Address Street 2",
	"billing_address_street_3"=>"Billing Address Street 3",
	"billing_address_street_4"=>"Billing Address Street 4",
	"billing_address_city"=>"Billing Address City",
	"billing_address_state"=>"Billing Address State",
	"billing_address_postalcode"=>"Billing Address Postalcode",
	"billing_address_country"=>"Billing Address Country",
	"shipping_address_street"=>"Shipping Address Street",
	"shipping_address_street_2"=>"Shipping Address Street 2",
	"shipping_address_street_3"=>"Shipping Address Street 3",
	"shipping_address_street_4"=>"Shipping Address Street 4",
	"shipping_address_city"=>"Shipping Address City",
	"shipping_address_state"=>"Shipping Address State",
	"shipping_address_postalcode"=>"Shipping Address Postalcode",
	"shipping_address_country"=>"Shipping Address Country",
	"description"=>"Description"
	),

'opportunities_import_fields' => Array(
		"id"=>"Account ID"
                , "name"=>"Opportunity Name"
                , "account_name"=>"Account Name"
                , "opportunity_type"=>"Opportunity Type"
                , "lead_source"=>"Lead Source"
                , "amount"=>"Amount"
                , "date_closed"=>"Date Closed"
                , "next_step"=>"Next Step"
                , "sales_stage"=>"Sales Stage"
                , "probability"=>"Probability"
                , "description"=>"Description"
                )

);

?>
