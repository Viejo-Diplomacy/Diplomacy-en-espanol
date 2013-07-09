<?php

/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: hooks.php 2012-04-29 10:58Z EdsonOrdaz $
 */

// Disallow direct access to this file for security reasons

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("postbit", "postbit_extension_rank");
$plugins->add_hook("member_profile_end", "profile_extension_rank");

function postbit_extension_rank(&$post)
{
	global $db, $templates, $lang;
	extensions_lang('ranks');
	$query = $db->simple_select("ranks", '*', "rid='".$post['rank']."'");
	$rank = $db->fetch_array($query);
	eval("\$post['rankpostbit'] = \"".$templates->get("ranks_postbit")."\";");
}

function profile_extension_rank()
{
	global $mybb, $templates, $rankprofile, $theme, $memprofile, $db, $lang;
	extensions_lang('ranks');
	$lang->rankby = $lang->sprintf($lang->rankby, $memprofile['username']);
	$query = $db->simple_select("ranks", '*', "rid='".$memprofile['rank']."'");
	$rank = $db->fetch_array($query);
	eval("\$rankprofile = \"".$templates->get("ranks_profile")."\";");
}

?>