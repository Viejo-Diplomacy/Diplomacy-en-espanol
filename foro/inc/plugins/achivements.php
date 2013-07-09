<?php


if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}


if(defined("IN_ADMINCP"))
{
	require_once MYBB_ROOT."inc/plugins/achivements/include/extensions.php";
}
global $cache;
$extensionslist = $cache->read("extensions");
if(is_array($extensionslist['active']) && !empty($extensionslist['active']))
{
	foreach($extensionslist['active'] as $extension)
	{
		if($extension != "" && file_exists(MYBB_ROOT."inc/plugins/achivements/extensions/".$extension.".php"))
		{
			require_once MYBB_ROOT."inc/plugins/achivements/extensions/".$extension.".php";
		}
	}
}

require_once MYBB_ROOT."inc/plugins/achivements/include/hooks.php";

function achivements_info()
{
	require_once MYBB_ROOT."inc/plugins/achivements/include/plugin.php";
	return achivements_init_info();
}

function achivements_is_installed()
{
	require_once MYBB_ROOT."inc/plugins/achivements/include/plugin.php";
	return achivements_init_is_installed();
}

function achivements_install()
{
	require_once MYBB_ROOT."inc/plugins/achivements/include/plugin.php";
	achivements_init_install();
}

function achivements_uninstall()
{
	require_once MYBB_ROOT."inc/plugins/achivements/include/plugin.php";
	achivements_init_uninstall();
}

function extensions_add_settinggroup($name, $title, $description, $disporder)
{
	global $db;
	$setting_group = array(
		'name' => 'groupsextensions_'.$db->escape_string($name),
		'title' => $db->escape_string($title),
		'description' => $db->escape_string($description),
		'disporder' => $disporder,
		'isdefault' => 0,
		);
	$db->insert_query('settinggroups', $setting_group);
	$group = $db->insert_id();
	return $group;
}

function extensions_add_settings($name, $title, $description, $optioncode, $value, $disporder, $group)
{
	global $db;
	$insert_array = array(
		'name' => $db->escape_string($name),
		'title' => $db->escape_string($title),
		'description' => $db->escape_string($description),
		'optionscode' => $db->escape_string($optioncode),
		'value' => $db->escape_string($value),
		'gid' => $group,
		'disporder' => $disporder,
		'isdefault' => 0,
		);
	$db->insert_query('settings', $insert_array);
}

function extensions_remove_settings($name)
{
	global $db;
	$db->delete_query("settings", "name LIKE '".$name."'");
}

function extensions_remove_templates($templates)
{
	global $db;
	if (!$templates)
	{
		return false;
	}
	return $db->delete_query('templates', "title IN (".$templates.")");
}

function extensions_add_template($name, $template)
{
	global $db;
	
	if (!$name || !$template)
		return false;
	
	$templateinsert = array(
		"title" => $db->escape_string($name),
		"template" => $db->escape_string($template),
		"sid" => intval(-1)
	);

	return $db->insert_query("templates", $templateinsert);
}

function extensions_remove_settinggroup($name)
{
	global $db;
	$db->delete_query("settinggroups", "name='groupsextensions_".$name."'");
}

function extensions_lang($extension)
{
	global $lang;
	if ($extension == '')
		return;
		
	$lang->set_path(MYBB_ROOT."inc/plugins/achivements/extensions/languages");
	$lang->load($extension);
	$lang->set_path(MYBB_ROOT."inc/languages");
}

function get_achivements_posts()
{
	global $db;
	$posts = array();
	$query = $db->simple_select('achivements_posts', '*', '', array('order_by' => 'apid', 'order_dir' => 'ASC'));
	while($post = $db->fetch_array($query))
	{
		$posts[$post['apid']] = $post;
	}
	$db->free_result($query);
	return $posts;
}

function get_achivements_threads()
{
	global $db;
	$threads = array();
	$query = $db->simple_select('achivements_threads', '*', '', array('order_by' => 'atid', 'order_dir' => 'ASC'));
	while($thread = $db->fetch_array($query))
	{
		$threads[$thread['atid']] = $thread;
	}
	$db->free_result($query);
	return $threads;
}

function get_achivements_reputation()
{
	global $db;
	$reputations = array();
	$query = $db->simple_select('achivements_reputation', '*', '', array('order_by' => 'arid', 'order_dir' => 'ASC'));
	while($reputation = $db->fetch_array($query))
	{
		$reputations[$reputation['arid']] = $reputation;
	}
	$db->free_result($query);
	return $reputations;
}

function get_achivements_timeonline()
{
	global $db;
	$timeonline = array();
	$query = $db->simple_select('achivements_timeonline', '*', '', array('order_by' => 'toid', 'order_dir' => 'ASC'));
	while($time = $db->fetch_array($query))
	{
		$timeonline[$time['toid']] = $time;
	}
	$db->free_result($query);
	return $timeonline;
}

function get_achivements_regdate()
{
	global $db;
	$regdate = array();
	$query = $db->simple_select('achivements_regdate', '*', '', array('order_by' => 'rgid', 'order_dir' => 'ASC'));
	while($reg_date = $db->fetch_array($query))
	{
		$regdate[$reg_date['rgid']] = $reg_date;
	}
	$db->free_result($query);
	return $regdate;
}

function get_achivements_custom()
{
	global $db;
	$customs = array();
	$query = $db->simple_select('achivements_custom', '*', '', array('order_by' => 'acid', 'order_dir' => 'ASC'));
	while($custom = $db->fetch_array($query))
	{
		$customs[$custom['acid']] = $custom;
	}
	$db->free_result($query);
	return $customs;
}

function get_achivement_id($table='posts', $idtable='apid', $id)
{
	global $db;
	$query = $db->simple_select("achivements_{$table}", '*', "{$idtable}='{$id}'");
	$result = $db->fetch_array($query);
	return $result;
}

function achivements_get($orderdir='asc')
{
	global $db;
	static $get_achivements;
	$tables_and_ids = array('posts' => 'apid', 'threads' => 'atid', 'reputation' => 'arid', 'timeonline' => 'toid', 'regdate' => 'rgid', 'custom' => 'acid');
	$desc_by = array('posts', 'threads', 'reputation', 'timeonline', 'regdate', 'custom');
			
	foreach ($desc_by as $tablas)
	{
		switch ($tablas)
		{
			case 'posts':
				$order = array('order_by' => 'apid', 'order_dir' => $orderdir);
			break;
			
			case 'threads':
				$order = array('order_by' => 'atid', 'order_dir' => $orderdir);
			break;
					
			case 'reputation':
				$order = array('order_by' => 'arid', 'order_dir' => $orderdir);
			break;
					
			case 'timeonline':
				$order = array('order_by' => 'toid', 'order_dir' => $orderdir);
			break;
					
			case 'regdate':
				$order = array('order_by' => 'rgid', 'order_dir' => $orderdir);
			break;
			
			case 'custom':
				$order = array('order_by' => 'acid', 'order_dir' => $orderdir);
			break;
		}
		$query = $db->simple_select('achivements_'.$tablas, '*', '', $order);
		while($achievement = $db->fetch_array($query))
		{
			$get_achivements[$tables_and_ids[$tablas]][$achievement[$tables_and_ids[$tablas]]] = $achievement;
		}
	}
	return $get_achivements;
}

?>