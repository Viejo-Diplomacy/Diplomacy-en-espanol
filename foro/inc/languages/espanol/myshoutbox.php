<?php

/**
 * MyShoutBox for MyBB 1.4.x (MYBB_ROOT/inc/plugins/shoutbox.php)
 * Copyright © 2009 Pirata Nervo, All Rights Reserved!
 *
 * Website: http://www.consoleworld.net
 * License: 
 * "This plugin is offered "as is" with no guarantees.
 * You may redistribute it provided the code and credits 
 * remain intact with no changes. This is not distributed
 * under GPL, so you may NOT re-use the code in any other
 * module, plugin, or program.
 * 
 * Free for non-commercial purposes!"
 *
 * This plugin is based off Asad Niazi's spicefuse shoutbox plugin.
 * Spicefuse Shoutbox website: www.spicefuse.com
 *
 *
 * File description: MyShoutbox main file
 */

if(!defined('IN_MYBB'))
	die('This file cannot be accessed directly.');

//$plugins->add_hook("index_end", "myshoutbox_index");
$plugins->add_hook("global_end", "myshoutbox_init");
$plugins->add_hook("xmlhttp", "myshoutbox_load");
$plugins->add_hook("pre_output_page", "myshoutbox_output_control");

$plugins->add_hook('admin_load', 'myshoutbox_admin');
$plugins->add_hook('admin_tools_menu', 'myshoutbox_admin_tools_menu');
$plugins->add_hook('admin_tools_action_handler', 'myshoutbox_admin_tools_action_handler');
$plugins->add_hook('admin_tools_permissions', 'myshoutbox_admin_permissions');

// reported shouts notice
$plugins->add_hook('admin_home_menu', 'myshoutbox_admin_home_menu');

function myshoutbox_info()
{
	return array(
		'name'			=> 'MyShoutbox',
		'description'	=> 'A powerful AJAX shoutbox for MyBB.',
		'website'		=> 'http://consoleworld.net/',
		'author'		=> 'Pirata Nervo',
		'authorsite'	=> 'http://consoleworld.net/',
		'version'		=> '1.3',
		'guid'			=> 'c7e5e6c1a57f0639ea52d7813b23579f',
		'compatibility' => '14*',
	);
}

function myshoutbox_activate()
{
	global $db;
	require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
	
	//myshoutbox_deactivate();
    
	$shoutbox_group = array(
		"name"		=> "mysb_shoutbox",
		"title"		=> "MyShoutbox",
		"description"	=> "Settings for the MyShoutbox plugin.",
		"disporder"	=> "1",
		"isdefault"	=> "no",
	);
    
	$db->insert_query("settinggroups", $shoutbox_group);
	$gid = $db->insert_id();
	
	$shoutbox_setting_1 = array(
		"name"		=> "mysb_shouts_main",
		"title"		=> "# of Shouts to display",
		"description"	=> "The maximum number of shouts you want to be displayed.",
		"optionscode"	=> "text",
		"value"		=> "30",
		"disporder"	=> "1",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_2 = array(
		"name"		=> "mysb_refresh_interval",
		"title"		=> "Refresh Interval",
		"description"	=> "How many seconds before the shoutbox is reloaded using AJAX transparently. ",
		"optionscode"	=> "text",
		"value"		=> "15",
		"disporder"	=> "2",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_3 = array(
		"name"		=> "mysb_allow_mycode",
		"title"		=> "Allow MyCode?",
		"description"	=> "Allow MyBB code in shouts to format text using [b], [i] etc..?",
		"optionscode"	=> "yesno",
		"value"		=> "yes",
		"disporder"	=> "3",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_4 = array(
		"name"		=> "mysb_allow_smilies",
		"title"		=> "Allow Smilies?",
		"description"	=> "Allow smilies in shouts?",
		"optionscode"	=> "yesno",
		"value"		=> "yes",
		"disporder"	=> "4",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_5 = array(
		"name"		=> "mysb_allow_imgcode",
		"title"		=> "Allow IMGCode?",
		"description"	=> "Allow images in shoutbox? Note: Some can post too big images and mess up your layout.",
		"optionscode"	=> "yesno",
		"value"		=> "no",
		"disporder"	=> "5",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_6 = array(
		"name"		=> "mysb_height",
		"title"		=> "ShoutBox Height",
		"description"	=> "Set the height for shoutbox here.",
		"optionscode"	=> "text",
		"value"		=> "125",
		"disporder"	=> "6",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_7 = array(
		"name"		=> "mysb_datetime",
		"title"		=> "ShoutBox Date/Time",
		"description"	=> "PHP date time format for shoutbox. <a href=\"http://php.net/date\" target=\"_blank\">check here</a> for more info.",
		"optionscode"	=> "text",
		"value"		=> "d-m-H:i",
		"disporder"	=> "7",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_8 = array(
		"name"		=> "mysb_full_ppage",
		"title"		=> "Shouts per page on full view?",
		"description"	=> "The number of shouts you want to be displayed, per page, on the full shoutbox view.",
		"optionscode"	=> "text",
		"value"		=> "50",
		"disporder"	=> "8",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_9 = array(
		"name"		=> "mysb_allow_smods",
		"title"		=> "Allow super moderators to delete?",
		"description"	=> "Allow super mods to delete shouts in the shoutbox?",
		"optionscode"	=> "yesno",
		"value"		=> "yes",
		"disporder"	=> "9",
		"gid"		=> intval($gid),
	);
	
	/*$shoutbox_setting_10 = array(
		"name"		=> "mysb_guestview",
		"title"		=> "Can Guests view the Shoutbox?",
		"description"	=> "Are guests allowed to view shoutbox?",
		"optionscode"	=> "yesno",
		"value"		=> "no",
		"disporder"	=> "10",
		"gid"		=> intval($gid),
	);*/
	
	$shoutbox_setting_10 = array(
		"name"		=> "mysb_allow_html",
		"title"		=> "Allow HTML?",
		"description"	=> "Allow html in shoutbox?",
		"optionscode"	=> "yesno",
		"value"		=> "no",
		"disporder"	=> "10",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_11 = array(
		"name"		=> "mysb_flood_time",
		"title"		=> "Flood Check?",
		"description"	=> "Add a flood check for everyone but the moderators. Enter a time in seconds here. Enter 0 to disable.",
		"optionscode"	=> "text",
		"value"		=> "5",
		"disporder"	=> "11",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_12 = array(
		"name"		=> "mysb_usergroups",
		"title"		=> "Groups allowed to view the shoutbox",
		"description"	=> "The groupd ids of the users allowed to view the shoutbox. (Seperated by a comma. Leave blank to allow all.)",
		"optionscode"	=> "text",
		"value"		=> "",
		"disporder"	=> "12",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_13 = array(
		"name"		=> "mysb_additional_groups",
		"title"		=> "Check additional groups?",
		"description"	=> "Set this to yes if you want additional groups to be checked. (This setting will only take effect if the above one is not blank)",
		"optionscode"	=> "yesno",
		"value"		=> "no",
		"disporder"	=> "13",
		"gid"		=> intval($gid),
	);
		
	$shoutbox_setting_14 = array(
		"name"		=> "mysb_allow_mods",
		"title"		=> "Allow moderators to delete?",
		"description"	=> "Allow moderators to delete shouts in the shoutbox?",
		"optionscode"	=> "yesno",
		"value"		=> "yes",
		"disporder"	=> "14",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_15 = array(
		"name"		=> "mysb_location",
		"title"		=> "Where do you want to display the shoutbox?",
		"description"	=> "Accepted values: \'global_header\', \'global_footer\', \'index_bottom\', \'index_top\'. (without quotes)<br /><small>\'global_header\' = Displays the shoutbox on the header of every page.<br />\'global_footer\' = Displays the shoutbox on the footer of every page.<br />\'index_bottom\' = Displays the shoutbox on the index page below the forums list.<br />\'index_top\' = Displays the shoutbox on the index page above the forums list.",
		"optionscode"	=> "text",
		"value"		=> "index_bottom",
		"disporder"	=> "15",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_16 = array(
		"name"		=> "mysb_display_message",
		"title"		=> "Do you want to show a message to banned users?",
		"description"	=> "Do you want to show a message to banned users? The message can be changed in the language files of MyShoutbox. (it is displayed instead of the shoutbox)",
		"value"		=> 1,
		"optionscode"	=> "yesno",
		"disporder"	=> "16",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_17 = array(
		"name"		=> "mysb_bot_disabled",
		"title"		=> "Is the bot disabled?",
		"description"	=> "If yes, the bot column will not be displayed and the bot will not reply to any shouts.",
		"value"		=> 0,
		"optionscode"	=> "yesno",
		"disporder"	=> "17",
		"gid"		=> intval($gid),
	);
	
	$shoutbox_setting_18 = array(
		"name"		=> "mysb_text_size",
		"title"		=> "Font size",
		"description"	=> "Enter the font size of the shouts. Default is 1.",
		"value"		=> 1,
		"optionscode"	=> "text",
		"disporder"	=> "18",
		"gid"		=> intval($gid),
	);
    
	$db->insert_query("settings", $shoutbox_setting_1);
	$db->insert_query("settings", $shoutbox_setting_2);
	$db->insert_query("settings", $shoutbox_setting_3);
	$db->insert_query("settings", $shoutbox_setting_4);
	$db->insert_query("settings", $shoutbox_setting_5);
	$db->insert_query("settings", $shoutbox_setting_6);
	$db->insert_query("settings", $shoutbox_setting_7);
	$db->insert_query("settings", $shoutbox_setting_8);
	$db->insert_query("settings", $shoutbox_setting_9);
	$db->insert_query("settings", $shoutbox_setting_10);
	$db->insert_query("settings", $shoutbox_setting_11);
	$db->insert_query("settings", $shoutbox_setting_12);
	$db->insert_query("settings", $shoutbox_setting_13);
	$db->insert_query("settings", $shoutbox_setting_14);
	$db->insert_query("settings", $shoutbox_setting_15);
	$db->insert_query("settings", $shoutbox_setting_16);
	$db->insert_query("settings", $shoutbox_setting_17);
	$db->insert_query("settings", $shoutbox_setting_18);
	
	// create table
	$db->query("CREATE TABLE `".TABLE_PREFIX."mysb_shouts` (
	  `id` int(10) NOT NULL auto_increment,
	  `uid` int(10) NOT NULL,
	  `shout_msg` text NOT NULL,
	  `shout_date` int(10) NOT NULL,
	  `shout_ip` varchar(30) NOT NULL,
	  `hidden` varchar(10) NOT NULL,
	  PRIMARY KEY  (`id`)
		) TYPE=MyISAM");
	
	// create messages table
	$db->query("CREATE TABLE `".TABLE_PREFIX."mysb_messages` (
	  `mid` int(10) NOT NULL auto_increment,
	  `touid` int(10) NOT NULL,
	  `fromuid` int(10) NOT NULL,
	  PRIMARY KEY  (`mid`)
		) TYPE=MyISAM");
	
	// create bot table
	$db->query("CREATE TABLE `".TABLE_PREFIX."mysb_bot` (
		`bid` int(10) NOT NULL auto_increment,
		`uid` int(10) NOT NULL,
		`bot_state` varchar(10) NOT NULL,
		`mood` int(10) NOT NULL,
		PRIMARY KEY  (`bid`)
	) TYPE=MyISAM");
	
	// create reports table
	$db->query("CREATE TABLE `".TABLE_PREFIX."mysb_reports` (
	  `rid` int(10) NOT NULL auto_increment,
	  `username` varchar(100) NOT NULL DEFAULT '',
	  `reason` varchar(255) NOT NULL DEFAULT '',
	  `date` bigint(30) NOT NULL DEFAULT 0,
	  `sid` int(10) NOT NULL DEFAULT 0,
	  `marked` tinyint(1) NOT NULL DEFAULT 0,
	  PRIMARY KEY  (`rid`)
		) TYPE=MyISAM");
		
	$db->query("INSERT INTO ".TABLE_PREFIX."mysb_shouts VALUES (NULL, 1, 'Chat Cargado', ".time().", '127.0.0.1', 'no')");
	
	$db->query("INSERT INTO ".TABLE_PREFIX."mysb_bot VALUES (NULL, -2, 'yes', 10)");
	
	$db->write_query("ALTER TABLE `".TABLE_PREFIX."users` ADD `mysb_banned` smallint(1) NOT NULL DEFAULT 0;");
	$db->write_query("ALTER TABLE `".TABLE_PREFIX."users` ADD `mysb_banned_reason` varchar(255) NOT NULL DEFAULT '';");
	
	// load templates
	$mysb_shoutbox_tpl = '
<style type="text/css">

.shoutbox {
	margin: 0;
	padding: 0;
	left: 0;
}


li.shoutbox_normal {
	list-style: none;
	margin: 0;
	position: relative;
	cursor: pointer;
	color: transparent;
	display: inline ;
	border: 1px;
	border-color: #FFFFFF;
}

li.shoutbox_color {
	list-style: none;
	position: relative;
	cursor: pointer;
	color: transparent;
	display: inline ;
	border: 0px;
	float: left;
	margin: 1px;
}

.shoutbox_button_color a {
	width: 9px;
	height: 9px;
	display: block;
	border: 1px solid #FFF;
}

</style>

<table border="0" cellspacing="1" cellpadding="4" class="tborder">
<thead>
<tr>
<td class="thead" colspan="5">
<div class="expcolimage"><img src="{$theme[\'imgdir\']}/collapse.gif" id="shoutbox_img" class="expander" alt="[-]" /></div>
<div><strong>{$lang->mysb_shoutbox}</strong> (<a href="index.php?action=full_shoutbox">{$lang->mysb_fullsbox}</a>)<br /></div>
</td>
</tr>
</thead>

<tbody id="shoutbox_e">
<tr>
 <td class="trow2" width="100%" align="center" colspan="3"><form onSubmit="ShoutBox.sendMessage(); $(\'send_to\').value = \'\'; return false;">{$lang->mysb_uid} <input type="text" id="send_to" size="30" /> - <input type="submit" value="{$lang->mysb_sendnow}" id="sending-status" /> <br /> <small>({$lang->mysb_sendnow_desc})</small></form>{$mysb_message}<div id="shoutbox_sendinfo"></div></td>
</tr>
<tr>
 <td class="trow2" width="12%" align="center"><strong>Bot Info</strong></td>
 <td class="trow2" width="66%" align="center"><form onSubmit="ShoutBox.postShout(); $(\'shout_data\').value = \'\'; return false;">{$lang->mysb_shout} <input type="text" id="shout_data" size="50" /> - <input type="submit" value="{$lang->mysb_shoutnow}" id="shouting-status" /></form></td>
 
 <td class="trow2" width="12%" align="center"><strong>Panel de Usuario</strong></td>
</tr>
<tr>
 <td class="trow1" width="12%" align="center"><font size="2"><strong>Bot Mood</strong>: {$botmood}<br><strong>Bot State</strong>: {$botstate}</font></td>
 <td class="trow1" width="76%"><font size="{$mybb->settings[\'mysb_text_size\']}"><div id="shoutbox_data" style="height: {$mybb->settings[\'mysb_height\']}px; overflow: auto;">{$lang->mysb_loading}</div></font></td>
 <td class="trow1" width="12%" align="center">
 <a style="cursor: pointer;" id="talktobot" onclick="ShoutBox.talkToBot(); return false;">{$lang->mysb_talkbot}</a>
<br />
 <!--<a style="cursor: pointer;" id="smilies" onclick="window.open(\'misc.php?action=smilies&popup=true&editor=clickableEditor\',\'Smilies\',\'scrollbars=yes, menubar=no,width=460,height=360,toolbar=no\');">Smilies</a>-->
<a style="cursor: pointer;" id="shoutbox_smilies">Smilies</a>
		
		<div id="shoutbox_smilies_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000; width: 500px; height: 200px; overflow: auto;">
		<ul class="shoutbox">
			{$smilie_inserter}
		</ul>

		</div>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_smilies");
		</script>
<br />

<a style="cursor: pointer;" id="shoutbox_popup">PopupSB</a>
		
		<div id="shoutbox_popup_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000;">
		<ul class="shoutbox">
			<a style="cursor: pointer;" onclick="window.open(\'shoutbox.php\',\'{$lang->mysb_shoutbox}\',\'scrollbars=yes, menubar=no,width=825,height=449,toolbar=no\');">{$lang->mysb_shoutbox}</a>
			<br />
			<a style="cursor: pointer;" onclick="window.open(\'shoutbox.php?action=full\',\'FullShoutBox\',\'scrollbars=yes, menubar=no,width=660,height=260,toolbar=no\');">Full ShoutBox</a>
		</ul>

		</div>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_popup");
		</script>
<br />

<a style="cursor: pointer;" id="shoutbox_mycode">MyCode</a>
		
		<div id="shoutbox_mycode_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000;">
		<ul class="shoutbox">
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'B\');">Bold</a>
			<br />
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'I\');">Italic</a>
			<br />
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'U\');">Underline</a>
			<br />
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'S\');">Strike</a>
		</ul>

		</div>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_mycode");
		</script>
<br />

<a style="cursor: pointer;" id="shoutbox_colors">Colores</a>
		<span class="shoutbox_button_color">
            <div id="shoutbox_colors_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000;">
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #800000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'800000\');"></a></li>
                    <li class="shoutbox_color" style="background: #8B4513;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'8B4513\');"></a></li>
                    <li class="shoutbox_color" style="background: #006400;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'006400\');"></a></li>
                    <li class="shoutbox_color" style="background: #2F4F4F;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'2F4F4F\');"></a></li>
                    <li class="shoutbox_color" style="background: #000080;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'000080\');"></a></li>
                    <li class="shoutbox_color" style="background: #4B0082;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'4B0082\');"></a></li>
                    <li class="shoutbox_color" style="background: #800080;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'800080\');"></a></li>
                    <li class="shoutbox_color" style="background: #000000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'000000\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FF0000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF0000\');"></a></li>
                    <li class="shoutbox_color" style="background: #DAA520;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'DAA520\');"></a></li>
                    <li class="shoutbox_color" style="background: #6B8E23;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'6B8E23\');"></a></li>
                    <li class="shoutbox_color" style="background: #708090;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'708090\');"></a></li>
                    <li class="shoutbox_color" style="background: #0000CD;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'0000CD\');"></a></li>
                    <li class="shoutbox_color" style="background: #483D8B;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'483D8B\');"></a></li>
                    <li class="shoutbox_color" style="background: #C71585;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'C71585\');"></a></li>
                    <li class="shoutbox_color" style="background: #696969;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'696969\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FF4500;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF4500\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFA500;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFA500\');"></a></li>
                    <li class="shoutbox_color" style="background: #808000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'808000\');"></a></li>
                    <li class="shoutbox_color" style="background: #4682B4;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'4682B4\');"></a></li>
                    <li class="shoutbox_color" style="background: #1E90FF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'1E90FF\');"></a></li>
                    <li class="shoutbox_color" style="background: #9400D3;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'9400D3\');"></a></li>
                    <li class="shoutbox_color" style="background: #FF1493;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF1493\');"></a></li>
                    <li class="shoutbox_color" style="background: #A9A9A9;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'A9A9A9\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FF6347;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF6347\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFD700;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFD700\');"></a></li>
                    <li class="shoutbox_color" style="background: #32CD32;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'32CD32\');"></a></li>
                    <li class="shoutbox_color" style="background: #87CEEB;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'87CEEB\');"></a></li>
                    <li class="shoutbox_color" style="background: #00BFFF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'00BFFF\');"></a></li>
                    <li class="shoutbox_color" style="background: #9370DB;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'9370DB\');"></a></li>
                    <li class="shoutbox_color" style="background: #FF69B4;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF69B4\');"></a></li>
                    <li class="shoutbox_color" style="background: #DCDCDC;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'DCDCDC\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FFDAB9;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFDAB9\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFFFE0;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFFFE0\');"></a></li>
                    <li class="shoutbox_color" style="background: #98FB98;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'98FB98\');"></a></li>
                    <li class="shoutbox_color" style="background: #E0FFFF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'E0FFFF\');"></a></li>
                    <li class="shoutbox_color" style="background: #87CEFA;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'87CEFA\');"></a></li>
                    <li class="shoutbox_color" style="background: #E6E6FA;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'E6E6FA\');"></a></li>
                    <li class="shoutbox_color" style="background: #DDA0DD;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'DDA0DD\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFFFFF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFFFFF\');"></a></li>
                </ul>
            </div>
         </span>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_colors");
		</script>
<br />

</tr>
</tbody>
</table>

<script>
ShoutBox.refreshInterval = {$mybb->settings[\'mysb_refresh_interval\']};
ShoutBox.MaxEntries = {$mybb->settings[\'mysb_shouts_main\']};
ShoutBox.lang = [\'{$lang->mysb_posting}\', \'{$lang->mysb_shoutnow}\', \'{$lang->mysb_loading}\', \'{$lang->mysb_flood_check}\', \'{$lang->mysb_no_perform}\', \'{$lang->mysb_sending}\', \'{$lang->mysb_sendnow}\', \'{$lang->mysb_already_sent}\', \'{$lang->mysb_deleted}\', \'{$lang->mysb_invalid}\', \'{$lang->mysb_self}\', \'{$lang->mysb_report_invalid_sid}\', \'{$lang->mysb_shout_reported}\', \'{$lang->mysb_shout_already_reported}\'];
ShoutBox.bindSmilieInserter("sb_clickable_smilies");
{$extra_js}
Event.observe(window, \'load\', ShoutBox.showShouts); 
</script>

<br />';

$mysb_shoutbox_nobot_tpl = '
<style type="text/css">

.shoutbox {
	margin: 0;
	padding: 0;
	left: 0;
}


li.shoutbox_normal {
	list-style: none;
	margin: 0;
	position: relative;
	cursor: pointer;
	color: transparent;
	display: inline ;
	border: 1px;
	border-color: #FFFFFF;
}

li.shoutbox_color {
	list-style: none;
	position: relative;
	cursor: pointer;
	color: transparent;
	display: inline ;
	border: 0px;
	float: left;
	margin: 1px;
}

.shoutbox_button_color a {
	width: 9px;
	height: 9px;
	display: block;
	border: 1px solid #FFF;
}

</style>

<table border="0" cellspacing="1" cellpadding="4" class="tborder">
<thead>
<tr>
<td class="thead" colspan="2">
<div class="expcolimage"><img src="{$theme[\'imgdir\']}/collapse.gif" id="shoutbox_img" class="expander" alt="[-]" /></div>
<div><strong>{$lang->mysb_shoutbox}</strong> (<a href="index.php?action=full_shoutbox">{$lang->mysb_fullsbox}</a> - <a href="pspshoutbox.php" title="Click here for the Portable Device version">Portable Device version</a>)<br /></div>
</td>
</tr>
</thead>

<tbody id="shoutbox_e">
<tr>
 <td class="trow2" width="100%" align="center" colspan="2"><form onSubmit="ShoutBox.sendMessage(); $(\'send_to\').value = \'\'; return false;">{$lang->mysb_uid} <input type="text" id="send_to" size="30" /> - <input type="submit" value="{$lang->mysb_sendnow}" id="sending-status" /> <br /> <small>({$lang->mysb_sendnow_desc})</small></form>{$mysb_message}<div id="shoutbox_sendinfo"></div></td>
</tr>
<tr>
 <td class="trow2" width="88%" align="center"><form onSubmit="ShoutBox.postShout(); $(\'shout_data\').value = \'\'; return false;">{$lang->mysb_shout} <input type="text" id="shout_data" size="50" /> - <input type="submit" value="{$lang->mysb_shoutnow}" id="shouting-status" /></form></td>
 <td class="trow2" width="12%" align="center"><strong>User Panel</strong></td>
</tr>
<tr>
 <td class="trow1" width="88%"><font size="{$mybb->settings[\'mysb_text_size\']}"><div id="shoutbox_data" style="height: {$mybb->settings[\'mysb_height\']}px; overflow: auto;">{$lang->mysb_loading}</div></font></td>
 <td class="trow1" width="12%" align="center">
 <!--<a style="cursor: pointer;" id="smilies" onclick="window.open(\'misc.php?action=smilies&popup=true&editor=clickableEditor\',\'Smilies\',\'scrollbars=yes, menubar=no,width=460,height=360,toolbar=no\');">Smilies</a>-->
<a style="cursor: pointer;" id="shoutbox_smilies">Smilies</a>
		
		<div id="shoutbox_smilies_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000; width: 500px; height: 200px; overflow: auto;">
		<ul class="shoutbox">
			{$smilie_inserter}
		</ul>

		</div>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_smilies");
		</script>
<br />

<a style="cursor: pointer;" id="shoutbox_popup">PopupSB</a>
		
		<div id="shoutbox_popup_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000;">
		<ul class="shoutbox">
			<a style="cursor: pointer;" onclick="window.open(\'shoutbox.php\',\'{$lang->mysb_shoutbox}\',\'scrollbars=yes, menubar=no,width=825,height=449,toolbar=no\');">{$lang->mysb_shoutbox}</a>
			<br />
			<a style="cursor: pointer;" onclick="window.open(\'shoutbox.php?action=full\',\'FullShoutBox\',\'scrollbars=yes, menubar=no,width=660,height=260,toolbar=no\');">Full ShoutBox</a>
		</ul>

		</div>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_popup");
		</script>
<br />

<a style="cursor: pointer;" id="shoutbox_mycode">MyCode</a>
		
		<div id="shoutbox_mycode_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000;">
		<ul class="shoutbox">
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'B\');">Bold</a>
			<br />
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'I\');">Italic</a>
			<br />
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'U\');">Underline</a>
			<br />
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'S\');">Strike</a>
		</ul>

		</div>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_mycode");
		</script>
<br />

<a style="cursor: pointer;" id="shoutbox_colors">Colors</a>
		<span class="shoutbox_button_color">
            <div id="shoutbox_colors_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000;">
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #800000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'800000\');"></a></li>
                    <li class="shoutbox_color" style="background: #8B4513;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'8B4513\');"></a></li>
                    <li class="shoutbox_color" style="background: #006400;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'006400\');"></a></li>
                    <li class="shoutbox_color" style="background: #2F4F4F;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'2F4F4F\');"></a></li>
                    <li class="shoutbox_color" style="background: #000080;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'000080\');"></a></li>
                    <li class="shoutbox_color" style="background: #4B0082;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'4B0082\');"></a></li>
                    <li class="shoutbox_color" style="background: #800080;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'800080\');"></a></li>
                    <li class="shoutbox_color" style="background: #000000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'000000\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FF0000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF0000\');"></a></li>
                    <li class="shoutbox_color" style="background: #DAA520;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'DAA520\');"></a></li>
                    <li class="shoutbox_color" style="background: #6B8E23;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'6B8E23\');"></a></li>
                    <li class="shoutbox_color" style="background: #708090;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'708090\');"></a></li>
                    <li class="shoutbox_color" style="background: #0000CD;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'0000CD\');"></a></li>
                    <li class="shoutbox_color" style="background: #483D8B;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'483D8B\');"></a></li>
                    <li class="shoutbox_color" style="background: #C71585;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'C71585\');"></a></li>
                    <li class="shoutbox_color" style="background: #696969;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'696969\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FF4500;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF4500\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFA500;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFA500\');"></a></li>
                    <li class="shoutbox_color" style="background: #808000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'808000\');"></a></li>
                    <li class="shoutbox_color" style="background: #4682B4;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'4682B4\');"></a></li>
                    <li class="shoutbox_color" style="background: #1E90FF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'1E90FF\');"></a></li>
                    <li class="shoutbox_color" style="background: #9400D3;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'9400D3\');"></a></li>
                    <li class="shoutbox_color" style="background: #FF1493;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF1493\');"></a></li>
                    <li class="shoutbox_color" style="background: #A9A9A9;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'A9A9A9\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FF6347;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF6347\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFD700;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFD700\');"></a></li>
                    <li class="shoutbox_color" style="background: #32CD32;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'32CD32\');"></a></li>
                    <li class="shoutbox_color" style="background: #87CEEB;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'87CEEB\');"></a></li>
                    <li class="shoutbox_color" style="background: #00BFFF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'00BFFF\');"></a></li>
                    <li class="shoutbox_color" style="background: #9370DB;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'9370DB\');"></a></li>
                    <li class="shoutbox_color" style="background: #FF69B4;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF69B4\');"></a></li>
                    <li class="shoutbox_color" style="background: #DCDCDC;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'DCDCDC\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FFDAB9;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFDAB9\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFFFE0;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFFFE0\');"></a></li>
                    <li class="shoutbox_color" style="background: #98FB98;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'98FB98\');"></a></li>
                    <li class="shoutbox_color" style="background: #E0FFFF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'E0FFFF\');"></a></li>
                    <li class="shoutbox_color" style="background: #87CEFA;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'87CEFA\');"></a></li>
                    <li class="shoutbox_color" style="background: #E6E6FA;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'E6E6FA\');"></a></li>
                    <li class="shoutbox_color" style="background: #DDA0DD;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'DDA0DD\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFFFFF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFFFFF\');"></a></li>
                </ul>
            </div>
         </span>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_colors");
		</script>
<br />

</tr>
</tbody>
</table>

<script>
ShoutBox.refreshInterval = {$mybb->settings[\'mysb_refresh_interval\']};
ShoutBox.MaxEntries = {$mybb->settings[\'mysb_shouts_main\']};
ShoutBox.lang = [\'{$lang->mysb_posting}\', \'{$lang->mysb_shoutnow}\', \'{$lang->mysb_loading}\', \'{$lang->mysb_flood_check}\', \'{$lang->mysb_no_perform}\', \'{$lang->mysb_sending}\', \'{$lang->mysb_sendnow}\', \'{$lang->mysb_already_sent}\', \'{$lang->mysb_deleted}\', \'{$lang->mysb_invalid}\', \'{$lang->mysb_self}\', \'{$lang->mysb_report_invalid_sid}\', \'{$lang->mysb_shout_reported}\', \'{$lang->mysb_shout_already_reported}\'];
ShoutBox.bindSmilieInserter("sb_clickable_smilies");
{$extra_js}
Event.observe(window, \'load\', ShoutBox.showShouts); 
</script>

<br />';

	$mysb_boxfull_tpl = '<html>
<head>
<title>Full Shoutbox</title>
{$headerinclude}
</head>
<body>
{$header}

<table border="0" cellspacing="1" cellpadding="4" class="tborder">
<thead>
<tr>
<td class="thead" colspan="5">
<div><strong>{$lang->mysb_shoutbox}</strong><br /></div>
</td>
</tr>
</thead>

<tr>
 {$mysb_shoutbox_data}
</tr>
</table>

<br />

<center>$multipage</center>

{$footer}
</body>
</html>
';

	$mysb_smilies_tpl = '
<div style="margin:auto; width: 500px; margin-top: 20px;">
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="" width="500">
<tr>
<td class="">
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2" id="sb_clickable_smilies">
{$sb_smilies}
</table>
</td>
</tr>
</table>
</div>';

	$mysb_popup_shoutbox_tpl = '
	<html>
<head>
<title>{$lang->mysb_shoutbox}</title>
{$headerinclude}
</head>
<body>

<style type="text/css">

.shoutbox {
	margin: 0;
	padding: 0;
	left: 0;
}


li.shoutbox_normal {
	list-style: none;
	margin: 0;
	position: relative;
	cursor: pointer;
	color: transparent;
	display: inline ;
	border: 1px;
	border-color: #FFFFFF;
}

li.shoutbox_color {
	list-style: none;
	position: relative;
	cursor: pointer;
	color: transparent;
	display: inline ;
	border: 0px;
	float: left;
	margin: 1px;
}

.shoutbox_button_color a {
	width: 9px;
	height: 9px;
	display: block;
	border: 1px solid #FFF;
}

</style>

<table border="0" cellspacing="1" cellpadding="4" class="tborder">
<thead>
<tr>
<td class="thead" colspan="5">
<div class="expcolimage"><img src="{$theme[\'imgdir\']}/collapse.gif" id="shoutbox_img" class="expander" alt="[-]" /></div>
<div><strong>{$lang->mysb_shoutbox}</strong> (<a href="index.php?action=full_shoutbox">{$lang->mysb_fullsbox}</a> - <a href="pspshoutbox.php" title="Click here for the Portable Device version">Portable Device version</a>)<br /></div>
</td>
</tr>
</thead>

<tbody id="shoutbox_e">
<tr>
 <td class="trow2" width="100%" align="center" colspan="3"><form onSubmit="ShoutBox.sendMessage(); $(\'send_to\').value = \'\'; return false;">{$lang->mysb_uid} <input type="text" id="send_to" size="30" /> - <input type="submit" value="{$lang->mysb_sendnow}" id="sending-status" /> <br /> <small>({$lang->mysb_sendnow_desc})</small></form>{$mysb_message}<div id="shoutbox_sendinfo"></div></td>
</tr>
<tr>
 <td class="trow2" width="12%" align="center"><strong>Bot Info</strong></td>
 <td class="trow2" width="66%" align="center"><form onSubmit="ShoutBox.postShout(); $(\'shout_data\').value = \'\'; return false;">{$lang->mysb_shout} <input type="text" id="shout_data" size="50" /> - <input type="submit" value="{$lang->mysb_shoutnow}" id="shouting-status" /></form></td>
 <td class="trow2" width="12%" align="center"><strong>User Panel</strong></td>
</tr>
<tr>
 <td class="trow1" width="12%" align="center"><font size="2"><strong>Bot Mood</strong>: {$botmood}<br><strong>Bot State</strong>: {$botstate}</font></td>
 <td class="trow1" width="76%"><font size="{$mybb->settings[\'mysb_text_size\']}"><div align="left" id="shoutbox_data" style="height: {$mybb->settings[\'mysb_height\']}px; overflow: auto;">{$lang->mysb_loading}</div></font></td>
 <td class="trow1" width="12%" align="center">
 <a style="cursor: pointer;" id="talktobot" onclick="ShoutBox.talkToBot(); return false;">{$lang->mysb_talkbot}</a>
<br />
 <!--<a style="cursor: pointer;" id="smilies" onclick="window.open(\'misc.php?action=smilies&popup=true&editor=clickableEditor\',\'Smilies\',\'scrollbars=yes, menubar=no,width=460,height=360,toolbar=no\');">Smilies</a>-->
<a style="cursor: pointer;" id="shoutbox_smilies">Smilies</a>
		
		<div id="shoutbox_smilies_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000; width: 500px; height: 200px; overflow: auto;">
		<ul class="shoutbox">
			{$smilie_inserter}
		</ul>

		</div>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_smilies");
		</script>
<br />

<a style="cursor: pointer;" id="shoutbox_popup">PopupSB</a>
		
		<div id="shoutbox_popup_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000;">
		<ul class="shoutbox">
			<a style="cursor: pointer;" onclick="window.open(\'shoutbox.php\',\'{$lang->mysb_shoutbox}\',\'scrollbars=yes, menubar=no,width=825,height=449,toolbar=no\');">{$lang->mysb_shoutbox}</a>
			<br />
			<a style="cursor: pointer;" onclick="window.open(\'shoutbox.php?action=full\',\'FullShoutBox\',\'scrollbars=yes, menubar=no,width=660,height=260,toolbar=no\');">Full ShoutBox</a>
		</ul>

		</div>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_popup");
		</script>
<br />

<a style="cursor: pointer;" id="shoutbox_mycode">MyCode</a>
		
		<div id="shoutbox_mycode_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000;">
		<ul class="shoutbox">
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'B\');">Bold</a>
			<br />
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'I\');">Italic</a>
			<br />
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'U\');">Underline</a>
			<br />
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'S\');">Strike</a>
		</ul>

		</div>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_mycode");
		</script>
<br />

<a style="cursor: pointer;" id="shoutbox_colors">Colors</a>
		<span class="shoutbox_button_color">
            <div id="shoutbox_colors_popup" class="popup_menu" style="display: none; padding: 5px; background: #F2F2F2; color: #000000; border: 1px solid #000000;">
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #800000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'800000\');"></a></li>
                    <li class="shoutbox_color" style="background: #8B4513;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'8B4513\');"></a></li>
                    <li class="shoutbox_color" style="background: #006400;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'006400\');"></a></li>
                    <li class="shoutbox_color" style="background: #2F4F4F;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'2F4F4F\');"></a></li>
                    <li class="shoutbox_color" style="background: #000080;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'000080\');"></a></li>
                    <li class="shoutbox_color" style="background: #4B0082;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'4B0082\');"></a></li>
                    <li class="shoutbox_color" style="background: #800080;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'800080\');"></a></li>
                    <li class="shoutbox_color" style="background: #000000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'000000\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FF0000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF0000\');"></a></li>
                    <li class="shoutbox_color" style="background: #DAA520;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'DAA520\');"></a></li>
                    <li class="shoutbox_color" style="background: #6B8E23;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'6B8E23\');"></a></li>
                    <li class="shoutbox_color" style="background: #708090;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'708090\');"></a></li>
                    <li class="shoutbox_color" style="background: #0000CD;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'0000CD\');"></a></li>
                    <li class="shoutbox_color" style="background: #483D8B;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'483D8B\');"></a></li>
                    <li class="shoutbox_color" style="background: #C71585;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'C71585\');"></a></li>
                    <li class="shoutbox_color" style="background: #696969;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'696969\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FF4500;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF4500\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFA500;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFA500\');"></a></li>
                    <li class="shoutbox_color" style="background: #808000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'808000\');"></a></li>
                    <li class="shoutbox_color" style="background: #4682B4;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'4682B4\');"></a></li>
                    <li class="shoutbox_color" style="background: #1E90FF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'1E90FF\');"></a></li>
                    <li class="shoutbox_color" style="background: #9400D3;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'9400D3\');"></a></li>
                    <li class="shoutbox_color" style="background: #FF1493;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF1493\');"></a></li>
                    <li class="shoutbox_color" style="background: #A9A9A9;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'A9A9A9\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FF6347;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF6347\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFD700;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFD700\');"></a></li>
                    <li class="shoutbox_color" style="background: #32CD32;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'32CD32\');"></a></li>
                    <li class="shoutbox_color" style="background: #87CEEB;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'87CEEB\');"></a></li>
                    <li class="shoutbox_color" style="background: #00BFFF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'00BFFF\');"></a></li>
                    <li class="shoutbox_color" style="background: #9370DB;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'9370DB\');"></a></li>
                    <li class="shoutbox_color" style="background: #FF69B4;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF69B4\');"></a></li>
                    <li class="shoutbox_color" style="background: #DCDCDC;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'DCDCDC\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FFDAB9;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFDAB9\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFFFE0;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFFFE0\');"></a></li>
                    <li class="shoutbox_color" style="background: #98FB98;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'98FB98\');"></a></li>
                    <li class="shoutbox_color" style="background: #E0FFFF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'E0FFFF\');"></a></li>
                    <li class="shoutbox_color" style="background: #87CEFA;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'87CEFA\');"></a></li>
                    <li class="shoutbox_color" style="background: #E6E6FA;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'E6E6FA\');"></a></li>
                    <li class="shoutbox_color" style="background: #DDA0DD;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'DDA0DD\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFFFFF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFFFFF\');"></a></li>
                </ul>
            </div>
         </span>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_colors");
		</script>
<br />

</tr>
</tbody>
</table>

<script>
ShoutBox.refreshInterval = {$mybb->settings[\'mysb_refresh_interval\']};
ShoutBox.MaxEntries = {$mybb->settings[\'mysb_shouts_main\']};
ShoutBox.lang = [\'{$lang->mysb_posting}\', \'{$lang->mysb_shoutnow}\', \'{$lang->mysb_loading}\', \'{$lang->mysb_flood_check}\', \'{$lang->mysb_no_perform}\', \'{$lang->mysb_sending}\', \'{$lang->mysb_sendnow}\', \'{$lang->mysb_already_sent}\', \'{$lang->mysb_deleted}\', \'{$lang->mysb_invalid}\', \'{$lang->mysb_self}\', \'{$lang->mysb_report_invalid_sid}\', \'{$lang->mysb_shout_reported}\', \'{$lang->mysb_shout_already_reported}\'];
ShoutBox.bindSmilieInserter("sb_clickable_smilies");
{$extra_js}
Event.observe(window, \'load\', ShoutBox.showShouts); 
</script>

</body>
</html>';

$mysb_popup_shoutbox_nobot_tpl = '
	<html>
<head>
<title>{$lang->mysb_shoutbox}</title>
{$headerinclude}
</head>
<body>

<style type="text/css">

.shoutbox {
	margin: 0;
	padding: 0;
	left: 0;
}


li.shoutbox_normal {
	list-style: none;
	margin: 0;
	position: relative;
	cursor: pointer;
	color: transparent;
	display: inline ;
	border: 1px;
	border-color: #FFFFFF;
}

li.shoutbox_color {
	list-style: none;
	position: relative;
	cursor: pointer;
	color: transparent;
	display: inline ;
	border: 0px;
	float: left;
	margin: 1px;
}

.shoutbox_button_color a {
	width: 9px;
	height: 9px;
	display: block;
	border: 1px solid #FFF;
}

</style>

<table border="0" cellspacing="1" cellpadding="4" class="tborder">
<thead>
<tr>
<td class="thead" colspan="5">
<div class="expcolimage"><img src="{$theme[\'imgdir\']}/collapse.gif" id="shoutbox_img" class="expander" alt="[-]" /></div>
<div><strong>{$lang->mysb_shoutbox}</strong> (<a href="index.php?action=full_shoutbox">{$lang->mysb_fullsbox}</a> - <a href="pspshoutbox.php" title="Click here for the Portable Device version">Portable Device version</a>)<br /></div>
</td>
</tr>
</thead>

<tbody id="shoutbox_e">
<tr>
 <td class="trow2" width="100%" align="center" colspan="2"><form onSubmit="ShoutBox.sendMessage(); $(\'send_to\').value = \'\'; return false;">{$lang->mysb_uid} <input type="text" id="send_to" size="30" /> - <input type="submit" value="{$lang->mysb_sendnow}" id="sending-status" /> <br /> <small>({$lang->mysb_sendnow_desc})</small></form>{$mysb_message}<div id="shoutbox_sendinfo"></div></td>
</tr>
<tr>
 <td class="trow2" width="88%" align="center"><form onSubmit="ShoutBox.postShout(); $(\'shout_data\').value = \'\'; return false;">{$lang->mysb_shout} <input type="text" id="shout_data" size="50" /> - <input type="submit" value="{$lang->mysb_shoutnow}" id="shouting-status" /></form></td>
 <td class="trow2" width="22%" align="center"><strong>User Panel</strong></td>
</tr>
<tr>
 <td class="trow1" width="88%"><font size="{$mybb->settings[\'mysb_text_size\']}"><div align="left" id="shoutbox_data" style="height: {$mybb->settings[\'mysb_height\']}px; overflow: auto;">{$lang->mysb_loading}</div></font></td>
 <td class="trow1" width="12%" align="center">
<a style="cursor: pointer;" id="shoutbox_smilies">Smilies</a>
		
		<div id="shoutbox_smilies_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000; width: 500px; height: 200px; overflow: auto;">
		<ul class="shoutbox">
			{$smilie_inserter}
		</ul>

		</div>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_smilies");
		</script>
<br />

<a style="cursor: pointer;" id="shoutbox_popup">PopupSB</a>
		
		<div id="shoutbox_popup_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000;">
		<ul class="shoutbox">
			<a style="cursor: pointer;" onclick="window.open(\'shoutbox.php\',\'{$lang->mysb_shoutbox}\',\'scrollbars=yes, menubar=no,width=825,height=449,toolbar=no\');">{$lang->mysb_shoutbox}</a>
			<br />
			<a style="cursor: pointer;" onclick="window.open(\'shoutbox.php?action=full\',\'FullShoutBox\',\'scrollbars=yes, menubar=no,width=660,height=260,toolbar=no\');">Full ShoutBox</a>
		</ul>

		</div>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_popup");
		</script>
<br />

<a style="cursor: pointer;" id="shoutbox_mycode">MyCode</a>
		
		<div id="shoutbox_mycode_popup" class="popup_menu" style="display: none; padding: 15px; background: #F2F2F2; color: #000000; border: 1px solid #000000;">
		<ul class="shoutbox">
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'B\');">Bold</a>
			<br />
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'I\');">Italic</a>
			<br />
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'U\');">Underline</a>
			<br />
			<a style="cursor: pointer;" onclick="ShoutBox.mycodeAdd(\'S\');">Strike</a>
		</ul>

		</div>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_mycode");
		</script>
<br />

<a style="cursor: pointer;" id="shoutbox_colors">Colors</a>
		<span class="shoutbox_button_color">
            <div id="shoutbox_colors_popup" class="popup_menu" style="display: none; padding: 5px; background: #F2F2F2; color: #000000; border: 1px solid #000000;">
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #800000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'800000\');"></a></li>
                    <li class="shoutbox_color" style="background: #8B4513;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'8B4513\');"></a></li>
                    <li class="shoutbox_color" style="background: #006400;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'006400\');"></a></li>
                    <li class="shoutbox_color" style="background: #2F4F4F;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'2F4F4F\');"></a></li>
                    <li class="shoutbox_color" style="background: #000080;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'000080\');"></a></li>
                    <li class="shoutbox_color" style="background: #4B0082;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'4B0082\');"></a></li>
                    <li class="shoutbox_color" style="background: #800080;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'800080\');"></a></li>
                    <li class="shoutbox_color" style="background: #000000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'000000\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FF0000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF0000\');"></a></li>
                    <li class="shoutbox_color" style="background: #DAA520;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'DAA520\');"></a></li>
                    <li class="shoutbox_color" style="background: #6B8E23;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'6B8E23\');"></a></li>
                    <li class="shoutbox_color" style="background: #708090;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'708090\');"></a></li>
                    <li class="shoutbox_color" style="background: #0000CD;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'0000CD\');"></a></li>
                    <li class="shoutbox_color" style="background: #483D8B;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'483D8B\');"></a></li>
                    <li class="shoutbox_color" style="background: #C71585;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'C71585\');"></a></li>
                    <li class="shoutbox_color" style="background: #696969;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'696969\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FF4500;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF4500\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFA500;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFA500\');"></a></li>
                    <li class="shoutbox_color" style="background: #808000;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'808000\');"></a></li>
                    <li class="shoutbox_color" style="background: #4682B4;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'4682B4\');"></a></li>
                    <li class="shoutbox_color" style="background: #1E90FF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'1E90FF\');"></a></li>
                    <li class="shoutbox_color" style="background: #9400D3;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'9400D3\');"></a></li>
                    <li class="shoutbox_color" style="background: #FF1493;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF1493\');"></a></li>
                    <li class="shoutbox_color" style="background: #A9A9A9;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'A9A9A9\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FF6347;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF6347\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFD700;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFD700\');"></a></li>
                    <li class="shoutbox_color" style="background: #32CD32;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'32CD32\');"></a></li>
                    <li class="shoutbox_color" style="background: #87CEEB;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'87CEEB\');"></a></li>
                    <li class="shoutbox_color" style="background: #00BFFF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'00BFFF\');"></a></li>
                    <li class="shoutbox_color" style="background: #9370DB;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'9370DB\');"></a></li>
                    <li class="shoutbox_color" style="background: #FF69B4;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FF69B4\');"></a></li>
                    <li class="shoutbox_color" style="background: #DCDCDC;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'DCDCDC\');"></a></li>
                </ul>
                
                <ul class="shoutbox">
                    <li class="shoutbox_color" style="background: #FFDAB9;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFDAB9\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFFFE0;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFFFE0\');"></a></li>
                    <li class="shoutbox_color" style="background: #98FB98;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'98FB98\');"></a></li>
                    <li class="shoutbox_color" style="background: #E0FFFF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'E0FFFF\');"></a></li>
                    <li class="shoutbox_color" style="background: #87CEFA;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'87CEFA\');"></a></li>
                    <li class="shoutbox_color" style="background: #E6E6FA;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'E6E6FA\');"></a></li>
                    <li class="shoutbox_color" style="background: #DDA0DD;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'DDA0DD\');"></a></li>
                    <li class="shoutbox_color" style="background: #FFFFFF;"><a style="cursor: pointer;" onclick="ShoutBox.colorAdd(\'FFFFFF\');"></a></li>
                </ul>
            </div>
         </span>
		<script type="text/javascript">
			new ShoutboxPopupMenu("shoutbox_colors");
		</script>
<br />

</tr>
</tbody>
</table>

<script>
ShoutBox.refreshInterval = {$mybb->settings[\'mysb_refresh_interval\']};
ShoutBox.MaxEntries = {$mybb->settings[\'mysb_shouts_main\']};
ShoutBox.lang = [\'{$lang->mysb_posting}\', \'{$lang->mysb_shoutnow}\', \'{$lang->mysb_loading}\', \'{$lang->mysb_flood_check}\', \'{$lang->mysb_no_perform}\', \'{$lang->mysb_sending}\', \'{$lang->mysb_sendnow}\', \'{$lang->mysb_already_sent}\', \'{$lang->mysb_deleted}\', \'{$lang->mysb_invalid}\', \'{$lang->mysb_self}\', \'{$lang->mysb_report_invalid_sid}\', \'{$lang->mysb_shout_reported}\', \'{$lang->mysb_shout_already_reported}\'];
ShoutBox.bindSmilieInserter("sb_clickable_smilies");
{$extra_js}
Event.observe(window, \'load\', ShoutBox.showShouts); 
</script>

</body>
</html>';

	$mysb_popup_boxfull_tpl = '
	<html>
<head>
<title>Full {$lang->mysb_shoutbox}</title>
{$headerinclude}
</head>
<body>

<table border="0" cellspacing="1" cellpadding="4" class="tborder">
<thead>
<tr>
<td class="thead" colspan="5">
<div><strong>{$lang->mysb_shoutbox}</strong><br /></div>
</td>
</tr>
</thead>

<tr>
 <div align="left">{$mysb_shoutbox_data}</div>
</tr>
</table>

<br />

<center>$multipage</center>

</body>

</html>';

	$mysb_portable_tpl = '
	<html>
<head>
<title>{$lang->mysb_shoutbox}</title>
{$headerinclude}
<!--<SCRIPT>var timeID = setTimeout("document.forms[0].submit()", 30000)</SCRIPT>-->
</head>
<body>

<form id="0" action="pspshoutbox.php"></form>

<table border="0" cellspacing="1" cellpadding="4" class="tborder">
<thead>
<tr>
<td class="thead" colspan="5">
<form id="1" action="pspshoutbox.php?action=shout" method="post">{$lang->mysb_shout} <input type="hidden" name="postcode" value="{$mybb->post_code}" /> <input type="text" name="shout_data" size="50" /> - <input type="submit" value="{$lang->mysb_shoutnow}" id="shouting-status" /></form>
</td>
</tr>
<tr>
<td class="thead" colspan="5">
<div><strong>{$lang->mysb_shoutbox}</strong> - <!--<a href="pspshoutbox.php">Refresh</a> --><small>(<a href="pspshoutbox.php?action=refresh">Refresh</a>)</small> <br /></div>
</td>
</tr>
</thead>

<tr>
 {$mysb_shoutbox_data}
</tr>
</table>

</body>

</html>';

	
	// insert templates
	$db->insert_query('templates', array('title' => 'mysb_shoutbox', 'sid' => '-1', 'template' => $db->escape_string($mysb_shoutbox_tpl), 'version' => '1408', 'status' => '', 'dateline' => TIME_NOW));
	$db->insert_query('templates', array('title' => 'mysb_shoutbox_bot_disabled', 'sid' => '-1', 'template' => $db->escape_string($mysb_shoutbox_nobot_tpl), 'version' => '1408', 'status' => '', 'dateline' => TIME_NOW));
	$db->insert_query('templates', array('title' => 'mysb_shoutbox_full', 'sid' => '-1', 'template' => $db->escape_string($mysb_boxfull_tpl), 'version' => '1408', 'status' => '', 'dateline' => TIME_NOW));
	$db->insert_query('templates', array('title' => 'mysb_smilieinsert', 'sid' => '-1', 'template' => $db->escape_string($mysb_smilies_tpl), 'version' => '1408', 'status' => '', 'dateline' => TIME_NOW));
	$db->insert_query('templates', array('title' => 'mysb_shoutbox_popup', 'sid' => '-1', 'template' => $db->escape_string($mysb_popup_shoutbox_tpl), 'version' => '1408', 'status' => '', 'dateline' => TIME_NOW));
	$db->insert_query('templates', array('title' => 'mysb_shoutbox_popup_bot_disabled', 'sid' => '-1', 'template' => $db->escape_string($mysb_popup_shoutbox_nobot_tpl), 'version' => '1408', 'status' => '', 'dateline' => TIME_NOW));
	$db->insert_query('templates', array('title' => 'mysb_shoutbox_popup_full', 'sid' => '-1', 'template' => $db->escape_string($mysb_popup_boxfull_tpl), 'version' => '1408', 'status' => '', 'dateline' => TIME_NOW));
	$db->insert_query('templates', array('title' => 'mysb_shoutbox_psp', 'sid' => '-1', 'template' => $db->escape_string($mysb_portable_tpl), 'version' => '1408', 'status' => '', 'dateline' => TIME_NOW));
	
	
	/*$db->query("INSERT INTO `".TABLE_PREFIX."templates` VALUES (NULL, 'mysb_shoutbox', '".$db->escape_string($mysb_shoutbox_tpl)."', '-1', '148', '', '".time()."', 0)");																								
	$db->query("INSERT INTO `".TABLE_PREFIX."templates` VALUES (NULL, 'mysb_shoutbox_bot_disabled', '".$db->escape_string($mysb_shoutbox_nobot_tpl)."', '-1', '148', '', '".time()."', 0)");
	$db->query("INSERT INTO `".TABLE_PREFIX."templates` VALUES (NULL, 'mysb_shoutbox_full', '".$db->escape_string($mysb_boxfull_tpl)."', '-1', '148', '', '".time()."', 0)");
	$db->query("INSERT INTO `".TABLE_PREFIX."templates` VALUES (NULL, 'mysb_smilieinsert', '".$db->escape_string($mysb_smilies_tpl)."', '-1', '148', '', '".time()."', 0)");
	$db->query("INSERT INTO `".TABLE_PREFIX."templates` VALUES (NULL, 'mysb_shoutbox_popup', '".$db->escape_string($mysb_popup_shoutbox_tpl)."', '-1', '148', '', '".time()."', 0)");
	$db->query("INSERT INTO `".TABLE_PREFIX."templates` VALUES (NULL, 'mysb_shoutbox_popup_bot_disabled', '".$db->escape_string($mysb_popup_shoutbox_nobot_tpl)."', '-1', '148', '', '".time()."', 0)");
	$db->query("INSERT INTO `".TABLE_PREFIX."templates` VALUES (NULL, 'mysb_shoutbox_popup_full', '".$db->escape_string($mysb_popup_boxfull_tpl)."', '-1', '148', '', '".time()."', 0)");
	$db->query("INSERT INTO `".TABLE_PREFIX."templates` VALUES (NULL, 'mysb_shoutbox_psp', '".$db->escape_string($mysb_portable_tpl)."', '-1', '148', '', '".time()."', 0)");*/

	// Version 1.1 doesn't require template edits anymore :D
	/*find_replace_templatesets("index", '#{\$forums}(\r?)\n#', "{\$forums}\n<mysb_shoutbox>\n");
	find_replace_templatesets('headerinclude', '#{\$newpmmsg}#', '{\$newpmmsg}'."\n".'<script type="text/javascript" src="jscripts/myshoutbox.js?ver=121"></script>');*/
	
	// rebuild settings...
	rebuild_settings();
	
	/*global $plugins_cache, $cache, $active_plugins, $plugins_cache;
	$plugins_cache['active'] = $active_plugins;
	$cache->update("plugins", $plugins_cache);*/

}

function myshoutbox_deactivate()
{
	global $db;
	//require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
	
	$db->write_query("DROP TABLE ".TABLE_PREFIX."mysb_shouts");
	$db->write_query("DROP TABLE ".TABLE_PREFIX."mysb_messages");
	$db->write_query("DROP TABLE ".TABLE_PREFIX."mysb_bot");
	$db->write_query("DROP TABLE ".TABLE_PREFIX."mysb_reports");
	$db->write_query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name = 'mysb_shoutbox'");
	$db->write_query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('mysb_shouts_main','mysb_refresh_interval','mysb_allow_mycode',
							'mysb_allow_smilies','mysb_allow_imgcode','mysb_height','mysb_datetime','mysb_full_ppage','mysb_allow_smods',
							'mysb_allow_html','mysb_flood_time','mysb_usergroups','mysb_additional_groups','mysb_allow_mods','mysb_location','mysb_display_message','mysb_bot_disabled')");

	$db->write_query("DELETE FROM ".TABLE_PREFIX."templates WHERE title IN('mysb_shoutbox','mysb_shoutbox_full','mysb_smilieinsert','mysb_shoutbox_popup','mysb_shoutbox_popup_full','mysb_shoutbox_psp','mysb_shoutbox_popup_bot_disabled','mysb_shoutbox_bot_disabled') AND sid='-1'");
	
	$db->write_query("ALTER TABLE `".TABLE_PREFIX."users` DROP `mysb_banned`;");
	$db->write_query("ALTER TABLE `".TABLE_PREFIX."users` DROP `mysb_banned_reason`;");
	
	// Version 1.1 doesn't required template edits anymore :D
	/*
	find_replace_templatesets("index", '#<mysb_shoutbox>(\r?)\n#', "", 0);
	find_replace_templatesets('headerinclude', '#' . preg_quote('<script type="text/javascript" src="jscripts/myshoutbox.js?ver=121"></script>') . '#', '', 0);*/
}

function myshoutbox_init()
{
	global $mybb, $templates, $mysb_shoutbox, $lang, $theme, $db, $mysb_message, $smilie_inserter, $headerinclude;
	
	$lang->load("myshoutbox");
	
	$canview = myshoutbox_can_view();
	
	if (!$canview && $canview !== 2) { // 2 means we have been banned
		return;
	}
	elseif ($canview === 2) { // 2 means we have been banned
	
		if ($mybb->settings['mysb_display_message'] != 1) {
			return;
		}
		else {
			// display banned from shoutbox message
			
			$error = "<table border=\"0\" cellspacing=\"{$theme['borderwidth']}\" cellpadding=\"{$theme['tablespace']}\" class=\"tborder\">
<tr>
<td class=\"thead\"><strong>".$lang->mysb_shoutbox."</strong></td>
</tr>
<tr>
<td class=\"trow1\">".$lang->sprintf($lang->mysb_error_ban, htmlspecialchars_uni($mybb->user['mysb_banned_reason']))."</td>
</tr>
</table><br />";
			
			if ($mybb->settings['mysb_location'] == 'global_header')
			{
				myshoutbox_display('global_header', 1, $error);
			}
			elseif ($mybb->settings['mysb_location'] == 'global_footer')
			{
				myshoutbox_display('global_footer', 1, $error);
			}
			elseif ($mybb->settings['mysb_location'] == 'index_bottom')
			{
				myshoutbox_display('index_bottom', 1, $error);
			}
			elseif ($mybb->settings['mysb_location'] == 'index_top')
			{
				myshoutbox_display('index_top', 1, $error);
			}
			
			return;
		}
	}

	if ($mybb->input['action'] == 'full_shoutbox') {
		return myshoutbox_show_full();
	}
	
	// add our report button javascript code
	/*eval("\$headerinclude .= \"".addslashes('<script type="text/javascript">
function mysb_reportShout()
{
	var reason = window.prompt("Enter a reason:");
	return reason;
}
</script>')."\";");*/
	
	eval("\$headerinclude .= \"".addslashes('<script type="text/javascript" src="jscripts/myshoutbox.js?ver=121"></script>')."\";"); // we have a new headerinclude (myshoutbox.js must be included)
	
	if ($mybb->settings['mysb_location'] == 'global_header')
	{
		myshoutbox_display('global_header');
	}
	elseif ($mybb->settings['mysb_location'] == 'global_footer')
	{
		myshoutbox_display('global_footer');
	}
	elseif ($mybb->settings['mysb_location'] == 'index_bottom')
	{
		myshoutbox_display('index_bottom');
	}
	elseif ($mybb->settings['mysb_location'] == 'index_top')
	{
		myshoutbox_display('index_top');
	}
}

function myshoutbox_psp_show()
{
	global $db, $mybb, $templates, $lang, $footer, $headerinclude, $header, $charset;

	if (!myshoutbox_can_view()) {
		return;
	}
	
	// Send our headers.
	header("Content-type: text/html; charset={$charset}");
	
	// Make navigation
	add_breadcrumb($lang->mysb_shoutbox, "pspshoutbox.php");
	$per_page = intval($mybb->settings['mysb_full_ppage']);

	// pagination
	$query = $db->simple_select("mysb_shouts", "COUNT(*) as shouts_count");
	$shouts_count = $db->fetch_field($query, 'shouts_count');
	
	$page = intval($mybb->input['page']);
	$pages = ceil($shouts_count / $per_page);

	if ($page > $pages) {
		$page = 1;
	}

	if ($page) {
		$start = ($page-1) * $per_page;
	} else {
		$start = 0;
		$page = 1;
	}
	
	// multi-page
	if ($shouts_count > $per_page) {
		$multipage = multipage($shouts_count, $per_page, $page, "pspshoutbox.php?action=full");		
	}
	
	// get data
	require_once MYBB_ROOT.'inc/class_parser.php';
	$parser = new postParser;
	
	$query = $db->query("SELECT s.*, u.username, u.usergroup, u.displaygroup FROM ".TABLE_PREFIX."mysb_shouts s 
							LEFT JOIN ".TABLE_PREFIX."users u ON (u.uid = s.uid) 
						 ORDER by s.id DESC LIMIT {$start}, {$per_page}");
	
	while ($row = $db->fetch_array($query))
	{
		$parser_options = array(
				'allow_mycode' => $mybb->settings['mysb_allow_mycode'],
				'allow_smilies' => $mybb->settings['mysb_allow_smilies'],
				'allow_imgcode' => $mybb->settings['mysb_allow_imgcode'],
				'allow_html' => $mybb->settings['mysb_allow_html'],
				'me_username' => $row['username']
			);		
			
		$message = $parser->parse_message($row['shout_msg'], $parser_options);
		
		if(intval(stripos($message, "botmood")) == 1 && $message{0} == '/')
			continue;
		
		if(intval(stripos($message, "pvt")) == 1 && $message{0} == '/')
		{
			sscanf($message, "/pvt %d \"", $userID);
			$message = ereg_replace("/pvt ".$userID." ", "", $message);
			if ($mybb->user['uid'] == intval($userID) || $mybb->user['uid'] == $row['uid'])
			{
				$query2 = $db->simple_select("users", "username", "uid='".intval($userID)."'");
				$userName = $db->fetch_field($query2, 'username');
				
				$message = "<span style=\"background-color: #BBBBBB;\"> Private Shout to $userName: ".$message."</span>";
		
				$row['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);

				$extra = ($mybb->usergroup['cancp'] == 1 ? "title='{$row[shout_ip]}'" : "");

				if ($row['uid'] != "-1") $username = '<a href="./member.php?action=profile&uid='.$row['uid'].'" {$extra}>'.$row['username'].'</a>';
				else $username = format_name($lang->mysb_botname, 2, "2");
				$date_time = my_date($mybb->settings['mysb_datetime'], $row['shout_date']);
				$class = alt_trow();
				
				// place post code in url as it's the only way to protect against CSRF (hopefuly no one will post the link to delete the shout lol)
				
				// disabled
				/*if (myshoutbox_can_delete()) {
					$delete = "(<a onClick='return confirm(\"{$lang->mysb_delconfirm}\");' href='pspshoutbox.php?action=delete_shout&amp;id={$row[id]}'>{$lang->mysb_delete}</a>) ";
					if ($row['hidden'] == "yes"){
						$recover = "(<a onClick='return confirm(\"{$lang->mysb_recconfirm}\");' href='pspshoutbox.php?action=recover_shout&amp;id={$row[id]}'>{$lang->mysb_recover}</a>) ";
						$remove = "(<a onClick='return confirm(\"{$lang->mysb_remconfirm}\");' href='pspshoutbox.php?action=remove_shout&amp;id={$row[id]}'>{$lang->mysb_remove}</a>) ";
					}
				}*/
				
				if (myshoutbox_can_delete() && $row['hidden'] == "yes") { 
					$mysb_shoutbox_data .= "<tr id='shout-{$row[id]}'><td align=\"left\" class='{$class}'>&raquo; <strong><span style=\"color: #FF0000\";>DELETED</span></strong> &raquo; {$remove}{$recover}<font face=\"arial\">{$username}</font> - {$date_time} -- {$message}</td></tr>";
				}
				elseif ($row['hidden'] == "no")
				{
					$mysb_shoutbox_data .= "<tr id='shout-{$row[id]}'><td align=\"left\" class='{$class}'>&raquo; {$delete}<font face=\"arial\">{$username}</font> - {$date_time} -- {$message}</td></tr>";
				}
			}
		}		
		else {
			$row['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);

			$extra = ($mybb->usergroup['cancp'] == 1 ? "title='{$row[shout_ip]}'" : "");

			if ($row['uid'] != "-1") $username = '<a href="./member.php?action=profile&uid='.$row['uid'].'" {$extra}>'.$row['username'].'</a>';
			else $username = format_name($lang->mysb_botname, 2, "2");
			$date_time = my_date($mybb->settings['mysb_datetime'], $row['shout_date']);
			$class = alt_trow();
		
			// disabled
			/*if (myshoutbox_can_delete()) {
				$delete = "(<a onClick='return confirm(\"{$lang->mysb_delconfirm}\");' href='pspshoutbox.php?action=delete_shout&amp;id={$row[id]}'>{$lang->mysb_delete}</a>) ";
				if ($row['hidden'] == "yes"){
					$recover = "(<a onClick='return confirm(\"{$lang->mysb_recconfirm}\");' href='pspshoutbox.php?action=recover_shout&amp;id={$row[id]}'>{$lang->mysb_recover}</a>) ";
					$remove = "(<a onClick='return confirm(\"{$lang->mysb_remconfirm}\");' href='pspshoutbox.php?action=remove_shout&amp;id={$row[id]}'>{$lang->mysb_remove}</a>) ";
				}
			}*/
			
			if (myshoutbox_can_delete() && $row['hidden'] == "yes") { 
				$mysb_shoutbox_data .= "<tr id='shout-{$row[id]}'><td align=\"left\" class='{$class}'>&raquo; <strong><span style=\"color: #FF0000\";>DELETED</span></strong> &raquo; {$remove}{$recover}<font face=\"arial\">{$username}</font> - {$date_time} -- {$message}</td></tr>";
			}
			elseif ($row['hidden'] == "no")
			{
				$mysb_shoutbox_data .= "<tr id='shout-{$row[id]}'><td align=\"left\" class='{$class}'>&raquo; {$delete}<font face=\"arial\">{$username}</font> - {$date_time} -- {$message}</td></tr>";
			}
		}
	}
	
	
	eval("\$shoutbox = \"".$templates->get("mysb_shoutbox_psp")."\";");
	
	$db->query("SELECT * FROM ".TABLE_PREFIX."mysb_shouts ORDER by id DESC LIMIT 10");
	
	output_page($shoutbox);
	exit;
}

function myshoutbox_popup_show_full()
{
	global $db, $mybb, $templates, $lang, $footer, $headerinclude, $header, $charset;

	if (!myshoutbox_can_view()) {
		return;
	}
	
	// Send our headers.
	header("Content-type: text/html; charset={$charset}");
	
	// Make navigation
	add_breadcrumb($lang->mysb_shoutbox, "shoutbox.php?action=full");
	$per_page = intval($mybb->settings['mysb_full_ppage']);

	// pagination
	$query = $db->simple_select("mysb_shouts", "COUNT(*) as shouts_count");
	$shouts_count = $db->fetch_field($query, 'shouts_count');
	
	$page = intval($mybb->input['page']);
	$pages = ceil($shouts_count / $per_page);

	if ($page > $pages) {
		$page = 1;
	}

	if ($page) {
		$start = ($page-1) * $per_page;
	} else {
		$start = 0;
		$page = 1;
	}
	
	// multi-page
	if ($shouts_count > $per_page) {
		$multipage = multipage($shouts_count, $per_page, $page, "shoutbox.php?action=full");		
	}
	
	// get data
	require_once MYBB_ROOT.'inc/class_parser.php';
	$parser = new postParser;
	
	$query = $db->query("SELECT s.*, u.username, u.usergroup, u.displaygroup FROM ".TABLE_PREFIX."mysb_shouts s 
							LEFT JOIN ".TABLE_PREFIX."users u ON (u.uid = s.uid) 
						 ORDER by s.id DESC LIMIT {$start}, {$per_page}");
	
	while ($row = $db->fetch_array($query))
	{
		
		$parser_options = array(
				'allow_mycode' => $mybb->settings['mysb_allow_mycode'],
				'allow_smilies' => $mybb->settings['mysb_allow_smilies'],
				'allow_imgcode' => $mybb->settings['mysb_allow_imgcode'],
				'allow_html' => $mybb->settings['mysb_allow_html'],
				'me_username' => $row['username']
			);		
			
		$message = $parser->parse_message($row['shout_msg'], $parser_options);
		
		if(intval(stripos($message, "botmood")) == 1 && $message{0} == '/')
			continue;
		elseif(intval(stripos($message, "pvt")) == 1 && $message{0} == '/') // display a private shout
		{
			sscanf($message, "/pvt %d \"", $userID);
			$message = ereg_replace("/pvt ".$userID." ", "", $message);
			if ($mybb->user['uid'] == intval($userID) || $mybb->user['uid'] == $row['uid'])
			{
				$query2 = $db->simple_select("users", "username", "uid='".intval($userID)."'");
				$userName = $db->fetch_field($query2, 'username');
				
				$message = "<span style=\"background-color: #BBBBBB;\"> Private Shout to $userName: ".$message."</span>";
		
				$row['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);

				$extra = ($mybb->usergroup['cancp'] == 1 ? "title='{$row[shout_ip]}'" : "");

				if ($row['uid'] != "-1") $username = '<a href="./member.php?action=profile&uid='.$row['uid'].'" {$extra}>'.$row['username'].'</a>';
				else $username = format_name($lang->mysb_botname, 2, "2");
				$date_time = my_date($mybb->settings['mysb_datetime'], $row['shout_date']);
				$class = alt_trow();
	
				/*if (myshoutbox_can_delete()) {
					$delete = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.deleteShout({$row[id]}, 1,\"{$lang->mysb_delconfirm}\");'>{$lang->mysb_delete}</a>) ";
					if ($row['hidden'] == "yes"){
						$recover = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.recoverShout({$row[id]}, 1, \"{$lang->mysb_recconfirm}\");'>{$lang->mysb_recover}</a>) ";
						$remove = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.removeShout({$row[id]}, 1, \"{$lang->mysb_remconfirm}\");'>{$lang->mysb_remove}</a>) ";
					}
				}
				else {*/
					$delete = '&nbsp;';
					$recover = '&nbsp;';
					$remove = '&nbsp;';
				//}
	
				$mysb_shoutbox_data .= "<tr id='shout-{$row[id]}'><td class='{$class}'>&raquo; {$delete}{$recover}{$report}{$username} - {$date_time} -- {$message}</td></tr>";
			}
		}		
		else { // display a normal shout
			$row['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);

			$extra = ($mybb->usergroup['cancp'] == 1 ? "title='{$row[shout_ip]}'" : "");

			if ($row['uid'] != "-1") $username = '<a href="./member.php?action=profile&uid='.$row['uid'].'" {$extra}>'.$row['username'].'</a>';
			else $username = format_name($lang->mysb_botname, 2, "2");
			$date_time = my_date($mybb->settings['mysb_datetime'], $row['shout_date']);
			$class = alt_trow();
		
			/*if (myshoutbox_can_delete()) {
				$delete = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.deleteShout({$row[id]}, 1,\"{$lang->mysb_delconfirm}\");'>{$lang->mysb_delete}</a>) ";
				if ($row['hidden'] == "yes"){
					$recover = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.recoverShout({$row[id]}, 1, \"{$lang->mysb_recconfirm}\");'>{$lang->mysb_recover}</a>) ";
					$remove = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.removeShout({$row[id]}, 1, \"{$lang->mysb_remconfirm}\");'>{$lang->mysb_remove}</a>) ";
				}
			}
			else {*/
				$delete = '&nbsp;';
				$recover = '&nbsp;';
				$remove = '&nbsp;';
			//}
		
			$mysb_shoutbox_data .= "<tr id='shout-{$row[id]}'><td class='{$class}'>&raquo; {$delete}{$recover}{$report}{$username} - {$date_time} -- {$message}</td></tr>";
		}
	}
	
	
	eval("\$shoutbox_full = \"".$templates->get("mysb_shoutbox_popup_full")."\";");
	
	$db->query("SELECT * FROM ".TABLE_PREFIX."mysb_shouts ORDER by id DESC LIMIT 10");
	
	output_page($shoutbox_full);
	exit;
}

function myshoutbox_show_full()
{
	global $db, $mybb, $templates, $lang, $footer, $headerinclude, $header, $charset;

	if (!myshoutbox_can_view()) {
		return;
	}
	
	// Send our headers.
	header("Content-type: text/html; charset={$charset}");
	
	// Make navigation
	add_breadcrumb($lang->mysb_shoutbox, "index.php?action=full_shoutbox");
	$per_page = intval($mybb->settings['mysb_full_ppage']);

	// pagination
	$query = $db->simple_select("mysb_shouts", "COUNT(*) as shouts_count");
	$shouts_count = $db->fetch_field($query, 'shouts_count');
	
	$page = intval($mybb->input['page']);
	$pages = ceil($shouts_count / $per_page);

	if ($page > $pages) {
		$page = 1;
	}

	if ($page) {
		$start = ($page-1) * $per_page;
	} else {
		$start = 0;
		$page = 1;
	}
	
	// multi-page
	if ($shouts_count > $per_page) {
		$multipage = multipage($shouts_count, $per_page, $page, "index.php?action=full_shoutbox");		
	}
	
	// get data
	require_once MYBB_ROOT.'inc/class_parser.php';
	$parser = new postParser;
	
	$query = $db->query("SELECT s.*, u.username, u.usergroup, u.displaygroup FROM ".TABLE_PREFIX."mysb_shouts s 
							LEFT JOIN ".TABLE_PREFIX."users u ON (u.uid = s.uid) 
						 ORDER by s.id DESC LIMIT {$start}, {$per_page}");
	
	while ($row = $db->fetch_array($query))
	{
		$parser_options = array(
				'allow_mycode' => $mybb->settings['mysb_allow_mycode'],
				'allow_smilies' => $mybb->settings['mysb_allow_smilies'],
				'allow_imgcode' => $mybb->settings['mysb_allow_imgcode'],
				'allow_html' => $mybb->settings['mysb_allow_html'],
				'me_username' => $row['username']
			);		
			
		$message = $parser->parse_message($row['shout_msg'], $parser_options);
		
		if(intval(stripos($message, "botmood")) == 1 && $message{0} == '/')
			continue;
		elseif(intval(stripos($message, "pvt")) == 1 && $message{0} == '/')
		{
			sscanf($message, "/pvt %d \"", $userID);
			$message = ereg_replace("/pvt ".$userID." ", "", $message);
			if ($mybb->user['uid'] == intval($userID) || $mybb->user['uid'] == $row['uid'])
			{
				$query2 = $db->simple_select("users", "username", "uid='".intval($userID)."'");
				$userName = $db->fetch_field($query2, 'username');
				
				$message = "<span style=\"background-color: #BBBBBB;\"> Private Shout to $userName: ".$message."</span>";
		
				$row['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);

				$extra = ($mybb->usergroup['cancp'] == 1 ? "title='{$row[shout_ip]}'" : "");

				if ($row['uid'] != "-1") $username = '<a href="./member.php?action=profile&uid='.$row['uid'].'" {$extra}>'.$row['username'].'</a>';
				else $username = format_name($lang->mysb_botname, 2, "2");
				$date_time = my_date($mybb->settings['mysb_datetime'], $row['shout_date']);
				$class = alt_trow();
	
				/*if (myshoutbox_can_delete()) {
					$delete = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.deleteShout({$row[id]}, 1,\"{$lang->mysb_delconfirm}\");'>{$lang->mysb_delete}</a>) ";
					if ($row['hidden'] == "yes"){
						$recover = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.recoverShout({$row[id]}, 1, \"{$lang->mysb_recconfirm}\");'>{$lang->mysb_recover}</a>) ";
						$remove = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.removeShout({$row[id]}, 1, \"{$lang->mysb_remconfirm}\");'>{$lang->mysb_remove}</a>) ";
					}
				}
				else {*/
					$delete = '&nbsp;';
					$recover = '&nbsp;';
					$remove = '&nbsp;';
				//}
	
				$mysb_shoutbox_data .= "<tr id='shout-{$row[id]}'><td class='{$class}'>&raquo; {$delete}{$recover}{$report}{$username} - {$date_time} -- {$message}</td></tr>";
			}
		}		
		else {
			$row['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);

			$extra = ($mybb->usergroup['cancp'] == 1 ? "title='{$row[shout_ip]}'" : "");

			if ($row['uid'] != "-1") $username = '<a href="./member.php?action=profile&uid='.$row['uid'].'" {$extra}>'.$row['username'].'</a>';
			else $username = format_name($lang->mysb_botname, 2, "2");
			$date_time = my_date($mybb->settings['mysb_datetime'], $row['shout_date']);
			$class = alt_trow();
		
			/*if (myshoutbox_can_delete()) {
				$delete = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.deleteShout({$row[id]}, 1,\"{$lang->mysb_delconfirm}\");'>{$lang->mysb_delete}</a>) ";
				if ($row['hidden'] == "yes"){
					$recover = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.recoverShout({$row[id]}, 1, \"{$lang->mysb_recconfirm}\");'>{$lang->mysb_recover}</a>) ";
					$remove = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.removeShout({$row[id]}, 1, \"{$lang->mysb_remconfirm}\");'>{$lang->mysb_remove}</a>) ";
				}
			}
			else {*/
				$delete = '&nbsp;';
				$recover = '&nbsp;';
				$remove = '&nbsp;';
			//}
		
			$mysb_shoutbox_data .= "<tr id='shout-{$row[id]}'><td class='{$class}'>&raquo; {$delete}{$recover}{$report}{$username} - {$date_time} -- {$message}</td></tr>";
		}
	}
	
	
	eval("\$shoutbox_full = \"".$templates->get("mysb_shoutbox_full")."\";");
	
	$db->query("SELECT * FROM ".TABLE_PREFIX."mysb_shouts ORDER by id DESC LIMIT 10");
	
	output_page($shoutbox_full);
	exit;
}

// function copied (from functions.php - MyBB 1.4.4) and modified to match my needs
/**
 * Build the javascript clickable smilie inserter
 *
 * @return string The clickable smilies list
 */
function myshoutbox_build_clickable_smilies()
{
	global $cache, $smiliecache, $theme, $templates, $lang, $mybb, $smiliecount;
	
	// new
	$bak_smiliecache = $smiliecache;
	$bak_smiliecount = $smiliecount;
	$bak_settings['smilieinsertercols'] = $mybb->settings['smilieinsertercols'];
	$bak_settings['smilieinsertertot'] = $mybb->settings['smilieinsertertot'];
	// new
	$mybb->settings['smilieinsertercols'] = 6;

	if($mybb->settings['smilieinserter'] != 0 && $mybb->settings['smilieinsertercols'] && $mybb->settings['smilieinsertertot'])
	{
		if(!$smiliecount)
		{
			$smilie_cache = $cache->read("smilies");
			$smiliecount = count($smilie_cache);
		}

		if(!$smiliecache)
		{
			if(!is_array($smilie_cache))
			{
				$smilie_cache = $cache->read("smilies");
			}
			foreach($smilie_cache as $smilie)
			{
				if($smilie['showclickable'] != 0)
				{
					$smiliecache[$smilie['find']] = $smilie['image'];
				}
			}
		}

		unset($smilie);

		if(is_array($smiliecache))
		{
			reset($smiliecache);
			
			// new
			$mybb->settings['smilieinsertertot'] = $smiliecount;

			$sb_smilies = "";
			$counter = 0;
			$i = 0;

			foreach($smiliecache as $find => $image)
			{
				if($i < $mybb->settings['smilieinsertertot'])
				{
					if($counter == 0)
					{
						$sb_smilies .=  "<tr>\n";
					}
	
					$find = htmlspecialchars_uni($find);
					$sb_smilies .= "<td style=\"text-align: center\"><img src=\"{$image}\" border=\"0\" class=\"sb_smilie\" alt=\"{$find}\" /></td>\n";
					++$i;
					++$counter;
	
					if($counter == $mybb->settings['smilieinsertercols'])
					{
						$counter = 0;
						$sb_smilies .= "</tr>\n";
					}
				}
			}

			if($counter != 0)
			{
				$colspan = $mybb->settings['smilieinsertercols'] - $counter;
				$sb_smilies .= "<td colspan=\"{$colspan}\">&nbsp;</td>\n</tr>\n";
			}

			eval("\$clickable_smilies = \"".$templates->get("mysb_smilieinsert")."\";");
		}
		else
		{
			$clickable_smilies = "";
		}
	}
	else
	{
		$clickable_smilies = "";
	}
	
	// new
	$smiliecache = $bak_smiliecache;
	$smiliecount = $bak_smiliecount;
	$mybb->settings['smilieinsertercols'] = $bak_settings['smilieinsertercols'];
	$mybb->settings['smilieinsertertot'] = $bak_settings['smilieinsertertot'];

	return $clickable_smilies;
}

/**
 * Add shoutbox template before output 
 */
function myshoutbox_output_control($page_data)
{
	global $mybb, $templates, $mysb_shoutbox, $lang, $theme, $db, $mysb_message, $smilie_inserter;
	
	if (myshoutbox_can_view()) {
	
		$query = $db->simple_select("mysb_bot", "mood", "bid='1'");
		$botmood = intval($db->fetch_field($query, 'mood'));
		
		if ($botmood >= 20 && $botmood <= 30)
			$botmood = "<img src=\"".$mybb->settings['bburl']."/inc/plugins/bot/images/very%20happy.gif\" alt=\"Very Happy\" title=\"Very Happy\">";
		elseif ($botmood >= 10 && $botmood < 20)
			$botmood = "<img src=\"".$mybb->settings['bburl']."/inc/plugins/bot/images/happy.gif\" alt=\"Happy\" title=\"Happy\">";
		elseif ($botmood < 10 && $botmood > -10)
			$botmood = "<img src=\"".$mybb->settings['bburl']."/inc/plugins/bot/images/serious.gif\" alt=\"Serious\" title=\"Serious\">";
		elseif ($botmood <= -10 && $botmood > -20)
			$botmood = "<img src=\"".$mybb->settings['bburl']."/inc/plugins/bot/images/mad.gif\" alt=\"Mad\" title=\"Mad\">";
		elseif ($botmood <= -20 && $botmood >= -30)
			$botmood = "<img src=\"".$mybb->settings['bburl']."/inc/plugins/bot/images/furious.gif\" alt=\"Furious\" title=\"Furious\">";
		
		$query = $db->simple_select("mysb_bot", "bot_state", "bid='1'");
		$botstate = $db->fetch_field($query, 'bot_state');
		
		if (strcmp($botstate, "yes") == 0)
			$botstate = "On";
		elseif (strcmp($botstate, "no") == 0)
			$botstate = "Off";

		// no shout button for guests
		if ($mybb->user['usergroup'] == 1)
			$extra_js = "ShoutBox.disableShout();";
		else
			$extra_js = "";
		
		//$lang->mysb_refreshtitle = $lang->sprintf($lang->mysb_refreshtitle, intval($mybb->settings[mysb_refresh_interval]));
		
		$smilie_inserter = myshoutbox_build_clickable_smilies(); // smilie inserter
		
		//eval("\$mysb_shoutbox = \"".$templates->get("mysb_shoutbox")."\";");
		
		if ($mybb->settings['mysb_bot_disabled'] == 0)
			eval("\$mysb_shoutbox = \"".$templates->get("mysb_shoutbox")."\";");
		else
			eval("\$mysb_shoutbox = \"".$templates->get("mysb_shoutbox_bot_disabled")."\";");
	}
	

	return str_replace('<mysb_shoutbox>', $mysb_shoutbox, $page_data); // still allow the shoutbox to be placed anywhere the admin wants
}

// Output the shoutbox, no template edits :D
function myshoutbox_display($location, $is_error=0, $error_msg='')
{
	global $mybb, $templates, $mysb_shoutbox, $lang, $theme, $db, $mysb_message, $smilie_inserter;
	
	if ($is_error == 1 && $error_msg != '')
	{
		global $templates;
	
		switch($location)
		{
			case 'global_header':
			
				global $header;
				
				$header .= $error_msg; // shoutbox is on header
			break;
			
			case 'global_footer':
			
				global $footer;
				
				$footer .= $error_msg; // shoutbox is on footer
			break;
			
			case 'index_top':
				if(!$templates->cache['index'])
					$templates->cache('index');
		
				$templates->cache['index'] = str_replace('{$forums}',$error_msg.'{$forums}',$templates->cache['index']);
			break;
			
			case 'index_bottom':
				if(!$templates->cache['index'])
					$templates->cache('index');
		
				$templates->cache['index'] = str_replace('{$forums}','{$forums}'.$error_msg,$templates->cache['index']);
			break;
		}
		
		return;
	}
	
	if (myshoutbox_can_view()) {
	
		$query = $db->simple_select("mysb_bot", "mood", "bid='1'");
		$botmood = intval($db->fetch_field($query, 'mood'));
		
		if ($botmood >= 20 && $botmood <= 30)
			$botmood = "<img src=\"".$mybb->settings['bburl']."/inc/plugins/bot/images/very%20happy.gif\" alt=\"Very Happy\" title=\"Very Happy\">";
		elseif ($botmood >= 10 && $botmood < 20)
			$botmood = "<img src=\"".$mybb->settings['bburl']."/inc/plugins/bot/images/happy.gif\" alt=\"Happy\" title=\"Happy\">";
		elseif ($botmood < 10 && $botmood > -10)
			$botmood = "<img src=\"".$mybb->settings['bburl']."/inc/plugins/bot/images/serious.gif\" alt=\"Serious\" title=\"Serious\">";
		elseif ($botmood <= -10 && $botmood > -20)
			$botmood = "<img src=\"".$mybb->settings['bburl']."/inc/plugins/bot/images/mad.gif\" alt=\"Mad\" title=\"Mad\">";
		elseif ($botmood <= -20 && $botmood >= -30)
			$botmood = "<img src=\"".$mybb->settings['bburl']."/inc/plugins/bot/images/furious.gif\" alt=\"Furious\" title=\"Furious\">";
		
		$query = $db->simple_select("mysb_bot", "bot_state", "bid='1'");
		$botstate = $db->fetch_field($query, 'bot_state');
		
		if (strcmp($botstate, "yes") == 0)
			$botstate = "On";
		elseif (strcmp($botstate, "no") == 0)
			$botstate = "Off";

		// no shout button for guests
		if ($mybb->user['usergroup'] == 1)
			$extra_js = "ShoutBox.disableShout();";
		else
			$extra_js = "";
		
		//$lang->mysb_refreshtitle = $lang->sprintf($lang->mysb_refreshtitle, intval($mybb->settings[mysb_refresh_interval]));
		
		$smilie_inserter = myshoutbox_build_clickable_smilies(); // smilie inserter
		
		if ($mybb->settings['mysb_bot_disabled'] == 0)
			eval("\$mysb_shoutbox = \"".$templates->get("mysb_shoutbox")."\";");
		else
			eval("\$mysb_shoutbox = \"".$templates->get("mysb_shoutbox_bot_disabled")."\";");
	}
	
	global $templates;
	
	switch($location)
	{
		case 'global_header':
		
			global $header;
			
			$header .= $mysb_shoutbox; // shoutbox is on header
		break;
		
		case 'global_footer':
		
			global $footer;
			
			$footer .= $mysb_shoutbox; // shoutbox is on footer
		break;
		
		case 'index_top':
			if(!$templates->cache['index'])
				$templates->cache('index');
	
			$templates->cache['index'] = str_replace('{$forums}',$mysb_shoutbox.'{$forums}',$templates->cache['index']);
		break;
		
		case 'index_bottom':
			if(!$templates->cache['index'])
				$templates->cache('index');
	
			$templates->cache['index'] = str_replace('{$forums}','{$forums}'.$mysb_shoutbox,$templates->cache['index']);
		break;
	}
	
	return;
}

function myshoutbox_load()
{
	global $mybb, $lang, $charset;

	$lang->load("myshoutbox");
	
	// Send our headers.
	header("Content-type: text/html; charset={$charset}");
	
	switch ($mybb->input['action'])
	{
		case 'show_shouts':
			myshoutbox_show_shouts(intval($mybb->input['last_id']));
		break;
			
		case 'add_shout':
			myshoutbox_add_shout();
		break;
		
		case 'delete_shout':
			myshoutbox_delete_shout(intval($mybb->input['id']));
		break;
			
		case 'remove_shout':
			myshoutbox_remove_shout(intval($mybb->input['id']));
		break;
		
		case 'recover_shout':
			myshoutbox_recover_shout(intval($mybb->input['id']));
		break;
		
		case 'send_message':
			myshoutbox_send_message(intval($mybb->input['send_to']));
		break;

		case 'delete_message':
			myshoutbox_delete_message(intval($mybb->input['mid']));
		break;
		
		case 'report_shout':
			myshoutbox_report_shout($mybb->input['reason'], intval($mybb->input['sid']));
		break;
	}
}

function myshoutbox_show_shouts($last_id = 0)
{
	global $db, $mybb, $parser, $charset, $lang;
	
	require_once MYBB_ROOT.'inc/class_parser.php';
	$parser = new postParser;

	$query = $db->query("SELECT s.*, u.username, u.usergroup, u.displaygroup FROM ".TABLE_PREFIX."mysb_shouts s 
							LEFT JOIN ".TABLE_PREFIX."users u ON (u.uid = s.uid) 
						WHERE s.id>{$last_id} ORDER by s.id DESC LIMIT {$mybb->settings[mysb_shouts_main]}");
	
	// fetch results 
	$messages = "";
	$entries = 0;
	while ($row = $db->fetch_array($query))
	{
		$report = "(<a id=\"report_".$row['id']."\" href=\"#shoutbox\" onclick=\"javascript: return ShoutBox.promptReason(".$row['id'].");\" style=\"cursor: pointer;\">{$lang->mysb_report_button}</a>) ";
		
		$parser_options = array(
				'allow_mycode' => $mybb->settings['mysb_allow_mycode'],
				'allow_smilies' => $mybb->settings['mysb_allow_smilies'],
				'allow_imgcode' => $mybb->settings['mysb_allow_imgcode'],
				'allow_html' => $mybb->settings['mysb_allow_html'],
				'me_username' => $row['username']
			);		
			
		$message = $parser->parse_message($row['shout_msg'], $parser_options);
		
		if(intval(stripos($message, "botmood")) == 1 && $message{0} == '/')
			continue;
		elseif(intval(stripos($message, "pvt")) == 1 && $message{0} == '/')
		{
			sscanf($message, "/pvt %d \"", $userID);
			$message = ereg_replace("/pvt ".$userID." ", "", $message);
			if ($mybb->user['uid'] == intval($userID) || $mybb->user['uid'] == $row['uid'])
			{
				$query2 = $db->simple_select("users", "username", "uid='".intval($userID)."'");
				$userName = $db->fetch_field($query2, 'username');
				
				$message = "<span style=\"background-color: #BBBBBB;\"> Private Shout to $userName: ".$message."</span>";
			
				$row['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
				$extra = ($mybb->usergroup['cancp'] == 1 ? "title='{$row[shout_ip]}'" : "");
		
				if ($row['uid'] != "-1") $username = ''.$row['username'].'';
				else $username = format_name($lang->mysb_botname, 2, "2");
				$date_time = my_date($mybb->settings['mysb_datetime'], $row['shout_date']);

				if (myshoutbox_can_delete()) {
					$delete = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.deleteShout({$row[id]}, 1,\"{$lang->mysb_delconfirm}\");'>{$lang->mysb_delete}</a>) ";
					if ($row['hidden'] == "yes"){
						$recover = "(<a onClick='return confirm(\"{$lang->mysb_recconfirm}\");' href='#' onclick='javascript: return ShoutBox.recoverShout({$row[id]}, 1);'>{$lang->mysb_recover}</a>) ";
						$remove = "(<a onClick='return confirm(\"{$lang->mysb_remconfirm}\");' href='#' onclick='javascript: return ShoutBox.removeShout({$row[id]}, 1);'>{$lang->mysb_remove}</a>) ";
					}
				}
				else {
					$delete = '&nbsp;';
					$recover = '&nbsp;';
					$remove = '&nbsp;';
				}
		
				if (myshoutbox_can_delete() && $row['hidden'] == "yes") { 
					$messages .= "&raquo; <strong><span style=\"color: #FF0000\";>DELETED</span></strong> &raquo; {$remove}{$recover}{$report}<a href='#' onClick=\"javascript: ShoutBox.pvtAdd(".$row['uid']."); return false;\" {$extra}>{$username}</a> - {$date_time} -- {$message}<br>\r\n";
				}
				elseif ($row['hidden'] == "no") $messages .= "&raquo; {$delete}{$recover}{$report}<span style=\"\";><font face=\"arial\"><a href='#' onClick=\"javascript: ShoutBox.pvtAdd(".$row['uid']."); return false;\" {$extra}>{$username}</a></span></font> - {$date_time} -- {$message}<br>\r\n";
		
				$entries++;
		
				if ($entries == 1) {
					$maxid = $row['id'];
				}
			}
		}
		else {
			$row['username'] = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
		
			$extra = ($mybb->usergroup['cancp'] == 1 ? "title='{$row[shout_ip]}'" : "");
		
			if ($row['uid'] != "-1") $username = ''.$row['username'].'';
			else $username = format_name($lang->mysb_botname, 2, "2");
			$date_time = my_date($mybb->settings['mysb_datetime'], $row['shout_date']);

			if (myshoutbox_can_delete()) {
				$delete = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.deleteShout({$row[id]}, 1,\"{$lang->mysb_delconfirm}\");'>{$lang->mysb_delete}</a>) ";
				if ($row['hidden'] == "yes"){
					$recover = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.recoverShout({$row[id]}, 1, \"{$lang->mysb_recconfirm}\");'>{$lang->mysb_recover}</a>) ";
					$remove = "(<a href='#shoutbox' onclick='javascript: return ShoutBox.removeShout({$row[id]}, 1, \"{$lang->mysb_remconfirm}\");'>{$lang->mysb_remove}</a>) ";
				}
			}
			else {
				$delete = '&nbsp;';
				$recover = '&nbsp;';
				$remove = '&nbsp;';
			}
		
			if (myshoutbox_can_delete() && $row['hidden'] == "yes") { 
				$messages .= "&raquo; <strong><span style=\"color: #FF0000\";>DELETED</span></strong> &raquo; {$remove}{$recover}{$report}<a href='#' onClick=\"javascript: ShoutBox.pvtAdd(".$row['uid']."); return false;\" {$extra}>{$username}</a> - {$date_time} -- {$message}<br>\r\n";
			}
			elseif ($row['hidden'] == "no") $messages .= "&raquo; {$delete}{$recover}{$report}<span style=\"\";><font face=\"arial\"><a href='#' onClick=\"javascript: ShoutBox.pvtAdd(".$row['uid']."); return false;\" {$extra}>{$username}</a></span></font> - {$date_time} -- {$message}<br>\r\n";
		
			$entries++;
		
			if ($entries == 1) {
				$maxid = $row['id'];
			}
		}
	}
	
	if (!$maxid) {
		$maxid = $last_id;
	}
	
	$myquery = $db->simple_select('mysb_messages', 'fromuid, mid', 'touid='.intval($mybb->user['uid']));
	$chat_messages = '&nbsp;';
	while ($msg = $db->fetch_array($myquery))
	{
		$chat_messages .= "<span style=\"color: #FF0000;\"><strong>".$lang->sprintf($lang->mysb_send_message, $db->fetch_field($db->simple_select('users', 'username', 'uid='.intval($msg['fromuid'])), 'username'))."</strong></span> <small>(<a onClick='return confirm(\"{$lang->mysb_message_delconfirm}\");' href='javascript: ShoutBox.deleteMessage(".intval($msg['mid']).", 2);'>{$lang->mysb_message_delete}</a>)</small><br />";
	}
	
	if (!$entries) // in case there are no shouts to display
	{
		$entries = 1;
		$maxid = 1;
		$messages = 
		$messages .= "&raquo; {$lang->mysb_no_shouts_display}<br>\r\n";
	}
	
	
	echo "{$maxid}^--^{$entries}^--^{$messages}^--^{$chat_messages}";
	exit;
}

function myshoutbox_send_message($uid)
{
	global $db, $mybb;
	
	if ($mybb->user['uid'] < 0)
		return false; // guests can't send messages
	
	$uid = intval($uid);
	
	// cannot send message to yourself
	if ($mybb->user['uid'] == $uid)
	{
		echo "send_self";
		exit;
	}
	
	// cannot send message to guests
	if ($uid <= 0)
	{
		echo "send_invalid";
		exit;
	}
	
	if ($db->fetch_field($db->simple_select('mysb_messages', 'mid', 'touid='.intval($uid).' AND fromuid='.intval($mybb->user['uid'])),'mid'))
	{
		echo "already_sent";
		exit;
	}
	
	// cannot send message to invalid uid
	if (!$db->fetch_field($db->simple_select('users', 'username', 'uid='.intval($uid)),'username'))
	{
		echo "send_invalid";
		exit;
	}
	
	$message = array(
			'touid' => intval($uid),
			'fromuid' => intval($mybb->user['uid'])
	);
		
	$db->insert_query('mysb_messages', $message);
	
	exit;
}

function myshoutbox_report_shout($reason, $sid)
{
	global $db, $mybb;
	
	$sid = intval($sid); // shout id
	
	if ($mybb->user['uid'] <= 0)
		return false; // guests can't report shouts
	
	// cannot report an invalid shout
	// get shout id
	if (!($shout_id = $db->fetch_field($db->simple_select('mysb_shouts', 'id', 'id=\''.intval($sid).'\''),'id')))
	{
		echo "invalid_shout";
		exit;
	}

	if (($uid = $db->fetch_field($db->simple_select('mysb_reports', 'rid', 'sid='.intval($sid).' AND username=\''.$db->escape_string($mybb->user['username']).'\''),'rid')))
	{
		echo "already_reported";
		exit;
	}
	
	$report = array(
			'username' => $db->escape_string($mybb->user['username']),
			'reason' => $db->escape_string($reason),
			'date' => time(),
			'sid' => $sid
	);
		
	$db->insert_query('mysb_reports', $report);
	
	echo 'shout_reported';
	exit;
}

function myshoutbox_delete_message($mid)
{
	global $db, $mybb;
	
	$mid = intval($mid);
	
	$db->query("DELETE FROM ".TABLE_PREFIX."mysb_messages WHERE mid = ".intval($mid));
	
	echo "deleted";
	
	exit;
}

function myshoutbox_add_bot_shout($message)
{
	global $db, $mybb;
	
	$shout_data = array(
			'uid' => '-1',
			'shout_msg' => $db->escape_string(str_replace('^--^', '-', $message)),
			'shout_date' => time(),
			'shout_ip' => get_ip(),
			'hidden' => "no"
		);
		
	$db->insert_query('mysb_shouts', $shout_data);
	
	exit;
}

function myshoutbox_add_custom_shout($message)
{
	global $db, $mybb;
	
	$shout_data = array(
			'uid' => $mybb->user['uid'],
			'shout_msg' => $db->escape_string(str_replace('^--^', '-', $message)),
			'shout_date' => time(),
			'shout_ip' => get_ip(),
			'hidden' => "no"
		);
		
	$db->insert_query('mysb_shouts', $shout_data);
	
	exit;
}

function myshoutbox_psp_add_shout()
{
	global $db, $mybb, $bot_state;
	
	// guests not allowed!
	if ($mybb->user['usergroup'] == 1 || $mybb->user['uid'] < 1 || !myshoutbox_can_view()) {
		die("failed!");
	}
	
	if(intval(stripos($mybb->input['shout_data'], "botmood")) == 1 && $mybb->input['shout_data']{0} == '/' && is_moderator()) {
	
		$bquery = $db->simple_select("mysb_bot", "mood", "bid='1'");
		$bmood = intval($db->fetch_field($query, 'mood'));
		
		sscanf($mybb->input['shout_data'], "/botmood %d", $moodInt);
		
		$db->update_query("mysb_bot", array('mood' => intval($moodInt)), "bid='1'", 1);
	}
	
	$shout_data = array(
			'uid' => $mybb->user['uid'],
			'shout_msg' => $db->escape_string(str_replace('^--^', '-', $mybb->input['shout_data'])),
			'shout_date' => time(),
			'shout_ip' => get_ip(),
			'hidden' => "no"
		);
		
	if ($db->insert_query('mysb_shouts', $shout_data)) {
		redirect("pspshoutbox.php", "Success! Redirecting..", "Success!");
	} else {
		redirect("pspshoutbox.php", "Failed! Redirecting..", "Failed!");
	}
	
	// the bot is VERY simple and was coded last year, while I was still learning PHP! So don't expect it to be that good.
	// Since then I've been improing the code to make it faster and trust me, it's much faster than before :P
	// Note: I used some functions like strcmp and strnatcasecmp because I was used to code in C and I didn't know at that time that I could do the same using a different code
	if(intval(stripos($mybb->input['shout_data'], "mysb_bot")) == 1 && $mybb->input['shout_data']{0} == '@' && $mybb->settings['mysb_bot_disabled'] == 0) // check if the user talked to the bot, if yes then do what's needed
	{
		require_once MYBB_ROOT."inc/plugins/bot/bot_words.php";
		require_once MYBB_ROOT."inc/plugins/bot/user_words.php";
		require_once MYBB_ROOT."inc/plugins/bot/functions_bot.php";
		
		$query = $db->simple_select("mysb_bot", "mood", "bid='1'");
		$mood = intval($db->fetch_field($query, 'mood'));
		
		if ($mood >= 20 && $mood <= 30)
			$mood = "Very Happy";
		elseif ($mood >= 10 && $mood < 20)
			$mood = "Happy";
		elseif ($mood < 10 && $mood > -10)
			$mood = "Serious";
		elseif ($mood <= -10 && $mood > -20)
			$mood = "Mad";
		elseif ($mood <= -20 && $mood >= -30)
			$mood = "Furious";
		
		$query = $db->simple_select("mysb_bot", "bot_state", "bid='1'");
		$bot_state = $db->fetch_field($query, 'bot_state');
		if ($bot_state == "yes")
		{
			if (preg_match("/mysb_bot turn off/i", $mybb->input['shout_data']) && is_moderator())
			{
				$message = "[b]Turning Off[/b]... Bye Master ".$mybb->user['username'];
				$db->update_query("mysb_bot", array($where.'bot_state' => "no"), "bid='1'", 1);
				myshoutbox_add_bot_shout($message);
				exit;
			}
			elseif (preg_match("/mysb_bot turn on/i", $mybb->input['shout_data']) && is_moderator())
			{
				$message = "I am already On.";
				myshoutbox_add_bot_shout($message);
				exit;
			}
			
			$canBuild = 1;
			
			if (strcmp($mood, "Furious") == 0)
			{
				$rN = mt_rand(0,1);
				
				if (!$rN)
					$canBuild = 0;
			}
			
			if ($canBuild == 1)
			{
				$nwords = countUserWords($mybb->input['shout_data']);
				if ($nwords > 0)
				{
					$words = checkUserWords($mybb->input['shout_data']);
					$message = "";
					$type = 0;
					$wordfound = 0;
			
					for ($i=0;$i<=$nwords-1;$i++)
					{	
						$row = 0;
						for ($type=0;$type<=10;$type++)
						{
							while(strlen($UserWord[$type][$row]) != 0)
							{
								if (strnatcasecmp($words[$i], $UserWord[$type][$row]) == 0)
								{
									$message .= buildResponse($words[$i], $type);
									if ($type == 7 || $type == 2 || $type == 3)
										$wordfound = 1;
									break;
								}
		
								if (strlen($UserWord[$type][$row]) == 0)
									break;
								else
									$row++;
							}
							if ($wordfound == 1)
								break;
							$row = 0;
						}
					}
				
					if (strlen($message) == 0)
						myshoutbox_add_bot_shout("Damn I forgot what I was going to say.");
				
					$query = $db->simple_select("mysb_bot", "mood", "bid='1'");
					$mood2 = intval($db->fetch_field($query, 'mood'));
				
					if ($mood2 >= 20 && $mood2 <= 30) 
						$mood2 = "Very Happy";
					elseif ($mood2 >= 10 && $mood2 < 20)
						$mood2 = "Happy";
					elseif ($mood2 < 10 && $mood2 > -10)
						$mood2 = "Serious";
					elseif ($mood2 <= -10 && $mood2 > -20)
						$mood2 = "Mad";
					elseif ($mood2 <= -20 && $mood2 >= -30)
						$mood2 = "Furious";
				
					if (strnatcasecmp($mood, $mood2) != 0)
						myshoutbox_add_bot_shout($message."[b] - Mood changed to ".$mood2."[/b]!");
					else
						myshoutbox_add_bot_shout($message);
				}
				else 
				{
					myshoutbox_add_bot_shout("I am sorry ".$mybb->user['username']." but I do not understand.");
				}
			}
			else {
				$response = $BotWord[5][mt_rand(0,count($BotWord[5])-1)];
				myshoutbox_add_bot_shout($response);
			}
		}
		elseif ($bot_state == "no")
		{
			if (preg_match("/mysb_bot turn on/i", $mybb->input['shout_data']) && is_moderator())
			{
				$message = "[b]Turning On[/b]... Hello Master ".$mybb->user['username'];
				$db->update_query("mysb_bot", array('bot_state' => "yes"), "bid='1'", 1);
				myshoutbox_add_bot_shout($message);
			}
		}
	}

	exit;
}

function myshoutbox_add_shout()
{
	global $db, $mybb, $bot_state;
	
	// guests not allowed!
	if ($mybb->user['usergroup'] == 1 || $mybb->user['uid'] < 1 || !myshoutbox_can_view()) {
		die("failed!");
	}
	
	// purge database?
	$postData = trim($mybb->input['shout_data']);
	if ($mybb->usergroup['cancp'] == 1 && substr($postData, 0, 7) == '/delete') {
		
		preg_match('/\/delete\s{1,}(all|older than|newer than)($|\s{1,}([0-9]+\-[0-9]+\-[0-9]+)|\s{1,}[0-9]+)/i', $postData, $match);
		
		// we have date?
		if (stristr($match[2], '-'))
			$date = explode('-', $match[2]);
		
		// purge?
		if ($match[1] == 'all') {
			$db->delete_query('mysb_shouts');
			$db->insert_query('mysb_shouts', array('uid' => intval($mybb->user['uid']), 'shout_msg' => 'First shout', 'shout_date' => time(), 'shout_ip' => get_ip()));
		}
		elseif (strtolower($match[1]) == 'older than') 
		{
			
			if ($date) {
				$timeStamp = mktime(23, 59, 59, $date[0], ($date[1]-1), $date[2]);
				$db->delete_query('mysb_shouts', 'shout_date <= ' . $timeStamp);
			}
			else // delete based on id
				$db->delete_query('mysb_shouts', 'id < ' . intval($match[2]));
		}
		elseif (strtolower($match[1]) == 'newer than') 
		{
			if ($date) {

				$timeStamp = mktime(1, 1, 1, $date[0], ($date[1]+1), $date[2]);
				$db->delete_query('mysb_shouts', 'shout_date >= ' . $timeStamp);
			}
			else // delete based on id
				$db->delete_query('mysb_shouts', 'id > ' . intval($match[2]));
		}
		
		die("deleted");
	}
	
	// flood check
	if (intval($mybb->settings['mysb_flood_time']) && !is_moderator()) {
		$lastShout = $db->fetch_field($db->simple_select('mysb_shouts', 'MAX(shout_date) as lastShout', 'uid = '.intval($mybb->user['uid'])), 'lastShout');
		$interval = time() - $lastShout;
		
		if ($interval <= $mybb->settings['mysb_flood_time'])
			die("flood|" . ($mybb->settings['mysb_flood_time'] - $interval));
	}
	
	if(intval(stripos($mybb->input['shout_data'], "botmood")) == 1 && $mybb->input['shout_data']{0} == '/' && is_moderator()) {
		$bquery = $db->simple_select("mysb_bot", "mood", "bid='1'");
		$bmood = intval($db->fetch_field($query, 'mood'));
		
		sscanf($mybb->input['shout_data'], "/botmood %d", $moodInt);
		$db->update_query("mysb_bot", array('mood' => intval($moodInt)), "bid='1'", 1);
	}
	
	$shout_data = array(
			'uid' => $mybb->user['uid'],
			'shout_msg' => $db->escape_string(str_replace('^--^', '-', $mybb->input['shout_data'])),
			'shout_date' => time(),
			'shout_ip' => get_ip(),
			'hidden' => "no"
		);
		
	if ($db->insert_query('mysb_shouts', $shout_data)) {
		echo "success!!";
	} else {
		echo "failed!";
	}
	
	if(intval(stripos($mybb->input['shout_data'], "mysb_bot")) == 1 && $mybb->input['shout_data']{0} == '@' && $mybb->settings['mysb_bot_disabled'] == 0) // check if the user talked to the bot, if yes then do what's needed
	{
		require_once MYBB_ROOT."inc/plugins/bot/bot_words.php";
		require_once MYBB_ROOT."inc/plugins/bot/user_words.php";
		require_once MYBB_ROOT."inc/plugins/bot/functions_bot.php";
		
		$query = $db->simple_select("mysb_bot", "mood", "bid='1'");
		$mood = intval($db->fetch_field($query, 'mood'));
		
		if ($mood >= 20 && $mood <= 30)
			$mood = "Very Happy";
		elseif ($mood >= 10 && $mood < 20)
			$mood = "Happy";
		elseif ($mood < 10 && $mood > -10)
			$mood = "Serious";
		elseif ($mood <= -10 && $mood > -20)
			$mood = "Mad";
		elseif ($mood <= -20 && $mood >= -30)
			$mood = "Furious";
		
		$query = $db->simple_select("mysb_bot", "bot_state", "bid='1'");
		$bot_state = $db->fetch_field($query, 'bot_state');
		if ($bot_state == "yes")
		{
			if (preg_match("/mysb_bot turn off/i", $mybb->input['shout_data']) && is_moderator())
			{
				$message = "[b]Turning Off[/b]... Bye Master ".$mybb->user['username'];
				$db->update_query("mysb_bot", array($where.'bot_state' => "no"), "bid='1'", 1);
				myshoutbox_add_bot_shout($message);
				exit;
			}
			elseif (preg_match("/mysb_bot turn on/i", $mybb->input['shout_data']) && is_moderator())
			{
				$message = "I am already On.";
				myshoutbox_add_bot_shout($message);
				exit;
			}
			
			$canBuild = 1;
			
			if (strcmp($mood, "Furious") == 0)
			{
				$rN = mt_rand(0,1);
				
				if ($rN == 0)
					$canBuild = 0;
			}
			
			if ($canBuild == 1)
			{
				$nwords = countUserWords($mybb->input['shout_data']);
				if ($nwords > 0)
				{
					$words = checkUserWords($mybb->input['shout_data']);
					$message = "";
					$type = 0;
					$wordfound = 0;
			
					for ($i=0;$i<=$nwords-1;$i++)
					{	
						$row = 0;
						for ($type=0;$type<=10;$type++)
						{
							while(strlen($UserWord[$type][$row]) != 0)
							{
								if (strnatcasecmp($words[$i], $UserWord[$type][$row]) == 0)
								{
									$message .= buildResponse($words[$i], $type);
									if ($type == 7 || $type == 2 || $type == 3)
										$wordfound = 1;
									break;
								}
		
								if (strlen($UserWord[$type][$row]) == 0)
									break;
								else
									$row++;
							}
							if ($wordfound == 1)
								break;
							$row = 0;
						}
					}
				
					if (strlen($message) == 0)
						myshoutbox_add_bot_shout("Damn I forgot what I was going to say.");
				
					$query = $db->simple_select("mysb_bot", "mood", "bid='1'");
					$mood2 = intval($db->fetch_field($query, 'mood'));
				
					if ($mood2 >= 20 && $mood2 <= 30)
						$mood2 = "Very Happy";
					elseif ($mood2 >= 10 && $mood2 < 20)
						$mood2 = "Happy";
					elseif ($mood2 < 10 && $mood2 > -10)
						$mood2 = "Serious";
					elseif ($mood2 <= -10 && $mood2 > -20)
						$mood2 = "Mad";
					elseif ($mood2 <= -20 && $mood2 >= -30)
						$mood2 = "Furious";
				
					if (strnatcasecmp($mood, $mood2) != 0)
						myshoutbox_add_bot_shout($message."[b] - Mood changed to ".$mood2."[/b]!");
					else
						myshoutbox_add_bot_shout($message);
				}
				else 
				{
					myshoutbox_add_bot_shout("I am sorry ".$mybb->user['username']." but I do not understand.");
				}
			}
			else {
				$response = $BotWord[5][mt_rand(0,count($BotWord[5]))];
				myshoutbox_add_bot_shout($response);
			}
		}
		elseif ($bot_state == "no")
		{
			if (preg_match("/mysb_bot turn on/i", $mybb->input['shout_data']) && is_moderator())
			{
				$message = "[b]Turning On[/b]... Hello Master ".$mybb->user['username'];
				$db->update_query("mysb_bot", array('bot_state' => "yes"), "bid='1'", 1);
				myshoutbox_add_bot_shout($message);
			}
		}
	}

	exit;
}

function myshoutbox_delete_shout($shout_id)
{
	global $db;
	
	$shout_id = intval($shout_id);
	
	if (myshoutbox_can_delete()) {
		$db->update_query("mysb_shouts", array('hidden' => "yes"), "id='".$shout_id."'", 1);
		echo "success!";
	}
	else
		echo "failed!";
	
	exit;
}

function myshoutbox_remove_shout($shout_id)
{
	global $db;
	
	$shout_id = intval($shout_id);
	
	if (myshoutbox_can_delete()) {
		$db->query("DELETE FROM ".TABLE_PREFIX."mysb_shouts WHERE id = {$shout_id}");
		echo "success!";
	}
	else
		echo "failed!";
	
	exit;
}

function myshoutbox_recover_shout($shout_id)
{
	global $db;
	
	$shout_id = intval($shout_id);
	
	if (myshoutbox_can_delete()) {
		$db->update_query("mysb_shouts", array('hidden' => "no"), "id='".$shout_id."'", 1);
		echo "success!";
	}
	else
		echo "failed!";
	
	exit;
}

function myshoutbox_can_delete()
{
	global $mybb;

	if ($mybb->usergroup['cancp'] == 1 || ($mybb->settings['mysb_allow_smods'] == 1 && $mybb->usergroup['issupermod'] == 1) || ($mybb->settings['mysb_allow_mods'] == 1 && $mybb->usergroup['canmodcp'] == 1))
		return true;
	
	return false;
}

function myshoutbox_can_view()
{
	global $mybb;
	
	if ($mybb->usergroup['canview'] == 0) // can view the board?
		return false;	
		
	// verify if is banned user before checking usergroups
	if (intval($mybb->user['mysb_banned']) == 1) // banned from the shoutbox
		return 2; // 2 means we have been banned
	
	if (empty($mybb->settings['mysb_usergroups']))
		return true;

	// can guests view the shoutbox?
	/*if ($mybb->settings['mysb_guestview'] == 0 && $mybb->user['usergroup'] == 1)
		return false;*/
		
	$groups = explode(",", $mybb->settings['mysb_usergroups']);
	$add_groups = "";
	
	if ($mybb->settings['mysb_additional_groups'] == 1 && $mybb->user['additionalgroups'])
		$add_groups = explode(",", $mybb->user['additionalgroups']);
	
	if (!in_array($mybb->user['usergroup'], $groups)) { // is the user allowed to view the shoutbox?
		// didn't find gid (primary) in allowed list, check additonal groups if setting is set to yes and if any were found
		
		if ($add_groups) {
			if (count(array_intersect($add_groups, $groups)) == 0)
				return false;
		}
		else 
			return false;
	}

	return true;

}

function myshoutbox_admin_home_menu(&$sub_menu)
{
	global $lang, $db;
	
	$lang->load('myshoutbox');

	$reports = $db->fetch_field($db->simple_select("mysb_reports", "COUNT(rid) as reports", "marked='0'"), "reports");
	
	//$reports = $db->fetch_field($db->simple_select("plaza_mydownloads_downloads", "COUNT(did) as comments", "hidden=0"), "comments");
	
	$sub_menu[] = array('id' => 'myshoutbox', 'title' => $lang->sprintf($lang->myshoutbox_unread_reports, $reports), 'link' => 'index.php?module=tools/myshoutbox&action=reports');
}

function myshoutbox_admin_tools_menu(&$sub_menu)
{
	global $lang;
	
	$lang->load('myshoutbox');
	$sub_menu[] = array('id' => 'myshoutbox', 'title' => $lang->myshoutbox_index, 'link' => 'index.php?module=tools/myshoutbox');
}

function myshoutbox_admin_tools_action_handler(&$actions)
{
	$actions['myshoutbox'] = array('active' => 'myshoutbox', 'file' => 'myshoutbox');
}

function myshoutbox_admin_permissions(&$admin_permissions)
{
  	global $db, $mybb, $lang;
  
	$lang->load("mysb_shoutbox", false, true);
	$admin_permissions['mysb_shoutbox'] = $lang->mysb_shoutbox_canmanage;
	
}

function myshoutbox_admin()
{
	global $db, $lang, $mybb, $page, $run_module, $action_file, $mybbadmin, $plugins;
	
	$lang->load("myshoutbox", false, true);
	
	if($run_module == 'tools' && $action_file == 'myshoutbox')
	{	
		if ($mybb->input['action'] == 'ban')
		{
			if ($mybb->request_method == "post")
			{
				if(!isset($mybb->input['my_post_key']) || $mybb->post_code != $mybb->input['my_post_key'] || !$mybb->input['username'])
				{
					$mybb->request_method = "get";
					flash_message($lang->myshoutbox_error, 'error');
					admin_redirect("index.php?module=tools/myshoutbox");
				}

				$db->update_query('users', array('mysb_banned' => 1, 'mysb_banned_reason' => $db->escape_string($mybb->input['reason'])), 'username=\''.$db->escape_string($mybb->input['username']).'\'', 1);
				
				$lang->myshoutbox_log_banned = $lang->sprintf($lang->myshoutbox_log_banned, $mybb->input['username']);
				log_admin_action($lang->myshoutbox_log_banned);
				
				flash_message($lang->myshoutbox_user_banned, 'success');
				admin_redirect("index.php?module=tools/myshoutbox");
			}
		}
		elseif ($mybb->input['action'] == 'unban')
		{
			if ($mybb->request_method == "post")
			{
				if(!isset($mybb->input['my_post_key']) || $mybb->post_code != $mybb->input['my_post_key'] || !$mybb->input['username'])
				{
					$mybb->request_method = "get";
					flash_message($lang->myshoutbox_error, 'error');
					admin_redirect("index.php?module=tools/myshoutbox");
				}
				
				$db->update_query('users', array('mysb_banned' => 0, 'mysb_banned_reason' => ''), 'username=\''.$db->escape_string($mybb->input['username']).'\'', 1);
				
				$lang->myshoutbox_log_unbanned = $lang->sprintf($lang->myshoutbox_log_unbanned, $mybb->input['username']);
				log_admin_action($lang->myshoutbox_log_unbanned);
				
				flash_message($lang->myshoutbox_user_unbanned, 'success');
				admin_redirect("index.php?module=tools/myshoutbox");
			}
		}
		elseif ($mybb->input['action'] == 'delete_report')
		{
			if ($mybb->request_method == "post")
			{
				if(!isset($mybb->input['my_post_key']) || $mybb->post_code != $mybb->input['my_post_key'] || !$mybb->input['rid'])
				{
					$mybb->request_method = "get";
					flash_message($lang->myshoutbox_error, 'error');
					admin_redirect("index.php?module=tools/myshoutbox");
				}
				
				// don't check if the report id exists, just try to delete it
				$db->delete_query('mysb_reports', 'rid='.intval($mybb->input['rid']), 1);
				
				$lang->myshoutbox_log_deleted_report = $lang->sprintf($lang->myshoutbox_log_deleted_report);
				log_admin_action($lang->myshoutbox_log_deleted_report);
				
				flash_message($lang->myshoutbox_report_deleted, 'success');
				admin_redirect("index.php?module=tools/myshoutbox&amp;action=reports");
			}
		}
		elseif ($mybb->input['action'] == 'mark_report')
		{
			if ($mybb->request_method == "post")
			{
				if(!isset($mybb->input['my_post_key']) || $mybb->post_code != $mybb->input['my_post_key'] || !$mybb->input['rid'])
				{
					$mybb->request_method = "get";
					flash_message($lang->myshoutbox_error, 'error');
					admin_redirect("index.php?module=tools/myshoutbox");
				}
				
				// don't check if the report id exists, just try to mark it as read
				$db->update_query('mysb_reports', array('marked' => 1), 'rid=\''.intval($mybb->input['rid']).'\'', 1);
				
				log_admin_action($lang->myshoutbox_log_marked_report);
				
				flash_message($lang->myshoutbox_report_marked, 'success');
				admin_redirect("index.php?module=tools/myshoutbox&amp;action=reports");
			}
		}
		elseif ($mybb->input['action'] == 'reports')
		{
			$page->add_breadcrumb_item($lang->myshoutbox_reported_shouts, 'index.php?module=tools/myshoutbox');
		
			$page->output_header($lang->myshoutbox_reported_shouts);
			
			$sub_tabs['myshoutbox'] = array(
				'title'			=> $lang->myshoutbox_home,
				'link'			=> 'index.php?module=tools/myshoutbox',
				'description'	=> $lang->myshoutbox_description
			);
			
			$sub_tabs['myshoutbox_reports'] = array(
				'title'			=> $lang->myshoutbox_reported_shouts." (".intval($db->fetch_field($db->simple_select("mysb_reports", "COUNT(rid) as reports", "marked='0'"), "reports")).")",
				'link'			=> 'index.php?module=tools/myshoutbox&amp;action=reports',
				'description'	=> $lang->myshoutbox_myshoutbox_reported_shouts_description
			);
			
			$page->output_nav_tabs($sub_tabs, 'myshoutbox_reports');

			// table
			$table = new Table;
			$table->construct_header($lang->myshoutbox_sid, array('width' => '10%'));
			$table->construct_header($lang->myshoutbox_username);
			$table->construct_header($lang->myshoutbox_reported_by);
			$table->construct_header($lang->myshoutbox_reason);
			$table->construct_header($lang->myshoutbox_date, array('width' => '15%'));
			$table->construct_header($lang->myshoutbox_view);
			$table->construct_header($lang->myshoutbox_delete);
			$table->construct_header($lang->myshoutbox_mark);
			
			// pagination
			$per_page = 15;
			if($mybb->input['page'] && intval($mybb->input['page']) > 1)
			{
				$mybb->input['page'] = intval($mybb->input['page']);
				$start = ($mybb->input['page']*$per_page)-$per_page;
			}
			else
			{
				$mybb->input['page'] = 1;
				$start = 0;
			}
			
			$query = $db->simple_select("mysb_reports", "COUNT(rid) as reports");
			$total_rows = $db->fetch_field($query, "reports");
		
			echo "<br />".draw_admin_pagination($mybb->input['page'], $per_page, $total_rows, "index.php?module=tools/myshoutbox&amp;action=reports&amp;page={page}");
			
			$query = $db->query("
				SELECT s.*, r.*
				FROM ".TABLE_PREFIX."mysb_reports r
				LEFT JOIN ".TABLE_PREFIX."mysb_shouts s ON (s.id=r.sid)
				ORDER BY r.date DESC LIMIT {$start}, {$per_page}
			");
			while($r = $db->fetch_array($query)) {
				
				if ($r['marked'] == 0)
				{
					$styles = 'background-color: #FFD7D7';
				}
				else
					$styles = '';
				
				$table->construct_cell(htmlspecialchars_uni($r['sid']), array('width' => '10%', 'style' => $styles));
				$table->construct_cell("<a href=\"".$mybb->settings['bburl']."/member.php?action=profile&amp;uid=".intval($r['uid'])."\">".htmlspecialchars_uni($db->fetch_field($db->simple_select('users', 'username', 'uid='.intval($r['uid']), array('limit' => 1)), 'username'))."</a>", array('style' => $styles));
				$table->construct_cell(htmlspecialchars_uni($r['username']), array('style' => $styles));
				$table->construct_cell(htmlspecialchars_uni($r['reason']), array('style' => $styles));
				
				$html_data = " <input type=\"submit\" class=\"submit_button\" value=\"{$lang->myshoutbox_view}\" onclick=\"alert('".myshoutbox_jsspecialchars(htmlspecialchars_uni($r['shout_msg']))."')\" />";
				
				$table->construct_cell(my_date($mybb->settings['dateformat'], $r['date'], '', false).", ".my_date($mybb->settings['timeformat'], $r['date']), array('width' => '15%', 'style' => $styles));
				
				$table->construct_cell($html_data, array('width' => '10%', 'style' => $styles));
				
				$form = new Form("index.php?module=tools/myshoutbox&amp;action=delete_report", "post", 'myshoutbox" onsubmit="return confirm(\''.myshoutbox_jsspecialchars($lang->myshoutbox_delete_report_confirm).'\');', 0, "", true);
				$html_data = $form->construct_return;
				$html_data .= $form->generate_hidden_field("rid", $r['rid']);
				$html_data .= "<input type=\"submit\" class=\"submit_button\" value=\"{$lang->myshoutbox_delete}\" />";
				$html_data .= $form->end();
				
				$table->construct_cell($html_data, array('width' => '10%', 'style' => $styles));
				
				$form = new Form("index.php?module=tools/myshoutbox&amp;action=mark_report", "post", 'myshoutbox" onsubmit="return confirm(\''.myshoutbox_jsspecialchars($lang->myshoutbox_mark_report_confirm).'\');', 0, "", true);
				$html_data = $form->construct_return;
				$html_data .= $form->generate_hidden_field("rid", $r['rid']);
				$html_data .= "<input type=\"submit\" class=\"submit_button\" value=\"{$lang->myshoutbox_mark}\" />";
				$html_data .= $form->end();
				
				$table->construct_cell($html_data, array('width' => '10%', 'style' => $styles));
				
				$table->construct_row();
				$found = true;
			}
			
			if (!$found)
			{
				$table->construct_cell($lang->myshoutbox_no_shouts_reported, array('colspan' => 8));
				$table->construct_row();
			}
			
			$table->output($lang->myshoutbox_reported_shouts);
			
			$page->output_footer();
		
			exit;
		}
		
		// no action
		$page->add_breadcrumb_item($lang->myshoutbox_home, 'index.php?module=tools/myshoutbox');
		
		$page->output_header($lang->myshoutbox_home);
		
		$sub_tabs['myshoutbox'] = array(
			'title'			=> $lang->myshoutbox_home,
			'link'			=> 'index.php?module=tools/myshoutbox',
			'description'	=> $lang->myshoutbox_description
		);
		
		$sub_tabs['myshoutbox_reports'] = array(
			'title'			=> $lang->myshoutbox_reported_shouts." (".intval($db->fetch_field($db->simple_select("mysb_reports", "COUNT(rid) as reports", "marked='0'"), "reports")).")",
			'link'			=> 'index.php?module=tools/myshoutbox&amp;action=reports',
			'description'	=> $lang->myshoutbox_myshoutbox_reported_shouts_description
		);
		
		$page->output_nav_tabs($sub_tabs, 'myshoutbox');
		
		$tabs = array(
			'banuser' => $lang->myshoutbox_quick_ban,
			'unbanuser' => $lang->myshoutbox_quick_unban
		);
		
		$page->output_tab_control($tabs);
		
		// quick ban user form
		echo "<div id=\"tab_banuser\">\n";
		$form = new Form("index.php?module=tools/myshoutbox&amp;action=ban", "post", "myshoutbox");
		
		$form_container = new FormContainer($lang->myshoutbox_ban_user);
		$form_container->output_row($lang->myshoutbox_ban_username, htmlspecialchars_uni($lang->myshoutbox_ban_username_desc), $form->generate_text_box('username', htmlspecialchars_uni($mybb->input['username']), array('id' => 'username')), 'username');
		$form_container->output_row($lang->myshoutbox_ban_reason, htmlspecialchars_uni($lang->myshoutbox_ban_reason_desc), $form->generate_text_box('reason', htmlspecialchars_uni($mybb->input['reason']), array('id' => 'reason')), 'reason');
		
		$form_container->end();
		
		$buttons = "";
		$buttons[] = $form->generate_submit_button($lang->myshoutbox_submit);
		$buttons[] = $form->generate_reset_button($lang->myshoutbox_reset);
		$form->output_submit_wrapper($buttons);
		$form->end();
		
		echo "</div>\n";
		
		// quick unban user form
		echo "<div id=\"tab_unbanuser\">\n";
		$form = new Form("index.php?module=tools/myshoutbox&amp;action=unban", "post", "myshoutbox");
		
		$form_container = new FormContainer($lang->myshoutbox_unban_user);
		$form_container->output_row($lang->myshoutbox_unban_username, htmlspecialchars_uni($lang->myshoutbox_unban_username_desc), $form->generate_text_box('username', htmlspecialchars_uni($mybb->input['username']), array('id' => 'username')), 'username');
		
		$form_container->end();
	
		$buttons = "";
		$buttons[] = $form->generate_submit_button($lang->myshoutbox_submit);
		$buttons[] = $form->generate_reset_button($lang->myshoutbox_reset);
		$form->output_submit_wrapper($buttons);
		$form->end();
		
		echo "</div>\n";
		
		// pagination
		$per_page = 15;
		if($mybb->input['page'] && intval($mybb->input['page']) > 1)
		{
			$mybb->input['page'] = intval($mybb->input['page']);
			$start = ($mybb->input['page']*$per_page)-$per_page;
		}
		else
		{
			$mybb->input['page'] = 1;
			$start = 0;
		}
		
		$query = $db->simple_select("users", "COUNT(uid) as users", 'mysb_banned=1');
		$total_rows = $db->fetch_field($query, "users");
	
		echo "<br />".draw_admin_pagination($mybb->input['page'], $per_page, $total_rows, "index.php?module=tools/myshoutbox&amp;page={page}");
		
		// table
		$table = new Table;
		$table->construct_header("<div style=\"width: 30%;\">".$lang->myshoutbox_username."</div>");
		$table->construct_header("<div style=\"width: 70%;\">".$lang->myshoutbox_reason."</div>");
		
		$query = $db->simple_select('users', 'uid,username,mysb_banned_reason', 'mysb_banned=1', array('order_by' => 'uid', 'order_dir' => 'ASC', 'limit' => "{$start}, {$per_page}"));
		while($r = $db->fetch_array($query)) {
			$table->construct_cell(htmlspecialchars_uni($r['username']));
			$table->construct_cell(htmlspecialchars_uni($r['mysb_banned_reason']));
			$table->construct_row();
			$found = true;
		}
		
		if (!$found)
		{
			$table->construct_cell($lang->myshoutbox_no_users_banned, array('colspan' => 2));
			$table->construct_row();
		}
		
		$table->output($lang->myshoutbox_banned_users);
		
		$page->output_footer();
		
		exit;
	}
}

/**
 * Somewhat like htmlspecialchars_uni but for JavaScript strings
 * 
 * @param string: The string to be parsed
 * @return string: Javascript compatible string
 */
function myshoutbox_jsspecialchars($str)
{
	// Converts & -> &amp; allowing Unicode
	// Parses out HTML comments as the XHTML validator doesn't seem to like them
	$string = preg_replace(array("#\<\!--.*?--\>#", "#&(?!\#[0-9]+;)#"), array('','&amp;'), $str);
	return strtr($string, array("\n" => '\n', "\r" => '\r', '\\' => '\\\\', '"' => '\x22', "'" => '\x27', '<' => '&lt;', '>' => '&gt;'));
}

?>
