<?php
if(!defined("IN_MYBB"))
{
    die("Direct initialization of this file is not allowed.<br /><br />
         Please make sure IN_MYBB is defined.");
}
function fbcore_fbconnect_info()
{
	return array(
		"name"			=> "FBConnect",
		"description"	=> "Facebook Connect for MyBB",
		"website"		=> "http://mybbmodding.net/forums/showthread.php?tid=180",
		"author"		=> "Nayar",
		"authorsite"	=> "http://nayarweb.com",
		"version"		=> "1.6.1",
		"guid" 			=> "14257ddb5da0887985784735c80f80e6",
		"compatibility" => "16*",
	);
}
function fbcore_fbconnect_install()
{
	global $db;
	$db->query("ALTER TABLE ".TABLE_PREFIX."users ADD `fbuid` bigint(50) NULL");
}
function fbcore_fbconnect_uninstall()
{
	global $db;	
	$db->query("ALTER TABLE ".TABLE_PREFIX."users drop `fbuid`");
}
function fbcore_fbconnect_is_installed()
{
	global $db;
	if($db->field_exists('fbuid', 'users'))
	{
		return true;
	}
	return false;
}
function fbcore_fbconnect_activate()
{
	global $db;
	$fbconnect_group = array(
		"gid" => "NULL",
		"name" => "fbconnect",
		"title" => "FBConnect",
		"description" => "Enables people to login/register with Facebook",
		"disporder" => "15",
		"isdefault" => "no",
	);
	$db->insert_query("settinggroups", $fbconnect_group);
	$gid = $db->insert_id();
	$fbconnect_setting_1 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect1",
		"title"			=> "Enable/Disable FBConnect Globally",
		"description"	=> "",
		"optionscode"	=> "onoff",
		"value"			=> '1',
		"disporder"		=> '1',
		"gid"			=> intval($gid),
	);

	/*$fbconnect_setting_2 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect2",
		"title"			=> "FACEBOOK_APP_ID",
		"description"	=> "",
		"optionscode"	=> "text",
		"value"			=> '',
		"disporder"		=> '2',
		"gid"			=> intval($gid),
	);
	$fbconnect_setting_3 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect3",
		"title"			=> "FACEBOOK_SECRET",
		"description"	=> "",
		"optionscode"	=> "text",
		"value"			=> '',
		"disporder"		=> '3',
		"gid"			=> intval($gid),
	);*/
	$fbconnect_setting_4 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect4",
		"title"			=> "Enable Registration with Facebook",
		"description"	=> "",
		"optionscode"	=> "onoff",
		"value"			=> '1',
		"disporder"		=> '4',
		"gid"			=> intval($gid),
	);
	$fbconnect_setting_5 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect5",
		"title"			=> "Check Facebook Emails and log in User?",
		"description"	=> "It is not recommended to switch this off as they would be able to register a new account",
		"optionscode"	=> "onoff",
		"value"			=> '1',
		"disporder"		=> '5',
		"gid"			=> intval($gid),
	);
	$fbconnect_setting_6 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect6",
		"title"			=> "Automatically link user fb account if email found",
		"description"	=> "setting 5 must be active for this to take effect",
		"optionscode"	=> "onoff",
		"value"			=> '1',
		"disporder"		=> '6',
		"gid"			=> intval($gid),
	);
	$fbconnect_setting_7 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect7",
		"title"			=> "Use cURL method",
		"description"	=> "",
		"optionscode"	=> "onoff",
		"value"			=> '0',
		"disporder"		=> '7',
		"gid"			=> intval($gid),
	);
	$fbconnect_setting_8 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect8",
		"title"			=> "Additional Permissions",
		"description"	=> "Refer to this document for more help: http://developers.facebook.com/docs/authentication/permissions",
		"optionscode"	=> "text",
		"value"			=> 'user_birthday',
		"disporder"		=> '8',
		"gid"			=> intval($gid),
	);
	$fbconnect_setting_9 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect9",
		"title"			=> "Allow choosing of username",
		"description"	=> "",
		"optionscode"	=> "onoff",
		"value"			=> '1',
		"disporder"		=> '9',
		"gid"			=> intval($gid),
	);
	$fbconnect_setting_10 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect10",
		"title"			=> "Fetch avatar for current users who do not have one",
		"description"	=> "",
		"optionscode"	=> "onoff",
		"value"			=> '1',
		"disporder"		=> '10',
		"gid"			=> intval($gid),
	);
	$fbconnect_setting_11 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect11",
		"title"			=> "Fetch birthday for current users if empty(requires user_birthday permission)",
		"description"	=> "",
		"optionscode"	=> "onoff",
		"value"			=> '1',
		"disporder"		=> '11',
		"gid"			=> intval($gid),
	);
	$fbconnect_setting_12 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect12",
		"title"			=> "Which User Group to put users who registered using FBConnect",
		"description"	=> "Default: 2",
		"optionscode"	=> "text",
		"value"			=> '2',
		"disporder"		=> '12',
		"gid"			=> intval($gid),
	);
	$fbconnect_setting_13 = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnect13",
		"title"			=> "Fetch Avatar for new users",
		"description"	=> "",
		"optionscode"	=> "onoff",
		"value"			=> '1',
		"disporder"		=> '13',
		"gid"			=> intval($gid),
	);
	/*$fbconnect_setting_X = array(
		"sid"			=> "NULL",
		"name"			=> "fbconnectX",
		"title"			=> "",
		"description"	=> "",
		"optionscode"	=> "text",
		"value"			=> '',
		"disporder"		=> 'X',
		"gid"			=> intval($gid),
	);*/
	$db->insert_query("settings", $fbconnect_setting_1);
	//$db->insert_query("settings", $fbconnect_setting_2);
	//$db->insert_query("settings", $fbconnect_setting_3);
	$db->insert_query("settings", $fbconnect_setting_4);
	$db->insert_query("settings", $fbconnect_setting_5);
	$db->insert_query("settings", $fbconnect_setting_6);
	$db->insert_query("settings", $fbconnect_setting_7);
	$db->insert_query("settings", $fbconnect_setting_8);
	$db->insert_query("settings", $fbconnect_setting_9);
	$db->insert_query("settings", $fbconnect_setting_10);
	$db->insert_query("settings", $fbconnect_setting_11);
	$db->insert_query("settings", $fbconnect_setting_12);
	$db->insert_query("settings", $fbconnect_setting_13);
	//$db->insert_query("settings", $fbconnect_setting_X);
	rebuildsettings();
	
	//require MYBB_ROOT."/inc/adminfunctions_templates.php";
	//find_replace_templatesets('header', '#{\$welcomeblock}#', '{\$welcomeblock}<!-- FACEBOOK CONNECT -->{$fbconnect}<!-- /FACEBOOK CONNECT -->');
}
function fbcore_fbconnect_deactivate()
{
	global $db;
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='fbconnect'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect1'");
	// $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect2'");
	// $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect3'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect4'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect5'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect6'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect7'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect8'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect9'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect10'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect11'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect12'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnect13'");
	//$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbconnectX'");
	rebuildsettings();
	
	//require MYBB_ROOT."/inc/adminfunctions_templates.php";
	//find_replace_templatesets("header", "#".preg_quote('<!-- FACEBOOK CONNECT -->{$fbconnect}<!-- /FACEBOOK CONNECT -->')."#i", '',0);
}
?>