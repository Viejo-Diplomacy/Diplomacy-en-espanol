<?php
/**
 * In the name of Allah, The Gracious, The Merciful
 * Author: Nayar(njoolfo0@gmail.com)
 * Plugin site: http://mybbmodding.net
 */
 
if(!defined("IN_MYBB"))
{
    die("Direct initialization of this file is not allowed.<br /><br />
    Please make sure IN_MYBB is defined.");
}

function fbcore_fbcore_info()
{
	return array(
		"name"			=> "FBCore",
		"description"	=> "Implements Facebook Javascript SDK",
		"website"		=> "http://mybbmodding.net/forums/showthread.php?tid=168",
		"author"		=> "Nayar",
		"authorsite"	=> "http://mybbmodding.net",
		"version"		=> "0.0.2",
		"guid" 			=> "",
		"compatibility" => "16*",
	);
}
function fbcore_fbcore_activate()
{
	global $db;
	$fbcore_group = array(
		"gid" => "NULL",
		"name" => "fbcore",
		"title" => "FBCore",
		"description" => "Basic Facebook Setup",
		"disporder" => "15",
		"isdefault" => "no",
	);
	$db->insert_query("settinggroups", $fbcore_group);
	$gid = $db->insert_id();
	/*$fbcore_setting_1 = array(
		"sid"			=> "NULL",
		"name"			=> "fbcore1",
		"title"			=> "Enable/Disable FBCore",
		"description"	=> "",
		"optionscode"	=> "onoff",
		"value"			=> '1',
		"disporder"		=> '1',
		"gid"			=> intval($gid),
	);*/
		$fbcore_setting_2 = array(
		"sid"			=> "NULL",
		"name"			=> "fbcore2",
		"title"			=> "FACEBOOK_APP_ID",
		"description"	=> "",
		"optionscode"	=> "text",
		"value"			=> '',
		"disporder"		=> '2',
		"gid"			=> intval($gid),
	);
	$fbcore_setting_3 = array(
		"sid"			=> "NULL",
		"name"			=> "fbcore3",
		"title"			=> "FACEBOOK_SECRET",
		"description"	=> "",
		"optionscode"	=> "text",
		"value"			=> '',
		"disporder"		=> '3',
		"gid"			=> intval($gid),
	);

	/*$fbcore_setting_X = array(
		"sid"			=> "NULL",
		"name"			=> "fbcoreX",
		"title"			=> "",
		"description"	=> "",
		"optionscode"	=> "text",
		"value"			=> '',
		"disporder"		=> 'X',
		"gid"			=> intval($gid),
	);*/
	
	// Delete fbcore1
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbcore1'");
	
	//$db->insert_query("settings", $fbcore_setting_1);
	$db->insert_query("settings", $fbcore_setting_2);
	$db->insert_query("settings", $fbcore_setting_3);
	//$db->insert_query("settings", $fbcore_setting_X);
	rebuildsettings();
	
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("footer", "#".preg_quote('<!-- FACEBOOK CORE -->{$fbcore}<!-- /FACEBOOK CORE -->')."#i", '',0);
}
function fbcore_fbcore_deactivate()
{
	global $db;

	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='fbcore'");
	//$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbcore1'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbcore2'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbcore3'");
	//$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='fbcoreX'");
	rebuildsettings();
}

?>