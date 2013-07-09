<?php

/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: ranks.php 2012-04-29 10:58Z EdsonOrdaz $
 */

// Disallow direct access to this file for security reasons

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

require_once MYBB_ROOT."inc/plugins/achivements/extensions/ranks/hooks.php";

function extension_ranks_info()
{
	require_once MYBB_ROOT."inc/plugins/achivements/extensions/ranks/plugin.php";
	return information_extension_rank();
}

function extension_ranks_activate()
{
	require_once MYBB_ROOT."inc/plugins/achivements/extensions/ranks/plugin.php";
	active_extension_rank();
}

function extension_ranks_deactivate()
{
	require_once MYBB_ROOT."inc/plugins/achivements/extensions/ranks/plugin.php";
	deactivate_rank_extension();
}

function extension_ranks_admin()
{
	require_once MYBB_ROOT."inc/plugins/achivements/extensions/ranks/admin.php";
	admin_load_rank();
}

function get_rank($rid)
{
	global $db;
	$query = $db->simple_select("ranks", '*', "rid='".intval($rid)."'");
	$rank = $db->fetch_array($query);
	$db->free_result($query);
	return $rank;
}

function get_ranks($orderdir='asc')
{
	global $db;
	$ranks = array();
	$query = $db->simple_select("ranks", '*', '', array('order_by' => 'level', 'order_dir' => $orderdir));
	while($rank = $db->fetch_array($query))
	{
		$ranks[$rank['rid']] = $rank;
	}
	$db->free_result($query);
	return $ranks;
}
?>