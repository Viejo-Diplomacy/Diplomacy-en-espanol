<?php


/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: extensions.php 2012-05-16 10:58Z EdsonOrdaz $
 */
 
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("admin_achivements_menu_extensions", "get_menus_extensions");
$plugins->add_hook("admin_achivements_action_handler", "get_actions_extensions");
$plugins->add_hook("admin_load", "get_load_extensions_admin");

function get_load_extensions_admin()
{
	global $mybb, $db, $page, $lang, $cache;
	$extensions_list = get_extensions_load();
	if(!empty($extensions_list))
	{
		foreach($extensions_list as $extension_file)
		{
			require_once MYBB_ROOT."inc/plugins/achivements/extensions/".$extension_file;
			$codename = str_replace(".php", "", $extension_file);
			$infofunc = "extension_".$codename."_info";
			if(!function_exists($infofunc))
			{
				continue;
			}
			$extension = $infofunc();
			if(function_exists("extension_{$codename}_admin"))
			{
				if($page->active_action == "{$codename}")
				{
					$page->add_breadcrumb_item($extension['name']);
					$page->output_header($extension['name']);
					
					call_user_func("extension_{$codename}_admin");
					
					$page->output_footer();
					exit;
				}
			}
		}
	}
}

function get_actions_extensions(&$actions)
{
	global $mybb, $lang, $cache;
	$extensions_list = get_extensions_load();
	if(!empty($extensions_list))
	{
		foreach($extensions_list as $extension_file)
		{
			require_once MYBB_ROOT."inc/plugins/achivements/extensions/".$extension_file;
			$codename = str_replace(".php", "", $extension_file);
			
			if(function_exists("extension_{$codename}_admin"))
			{
				$actions[$codename] = array('active' => $codename, 'file' => '');
			}
		}
	}
}

function get_menus_extensions(&$sub_menu)
{
	global $mybb, $lang, $cache;
	$extensions_list = get_extensions_load();
	if(!empty($extensions_list))
	{
		foreach($extensions_list as $extension_file)
		{
			require_once MYBB_ROOT."inc/plugins/achivements/extensions/".$extension_file;
			$codename = str_replace(".php", "", $extension_file);
			$infofunc = "extension_".$codename."_info";

			if(function_exists("extension_{$codename}_admin"))
			{
				$infofunc = "extension_".$codename."_info";
				if(!function_exists($infofunc))
				{
					continue;
				}
				$extension = $infofunc();
				if(!empty($extension['admin_icon']))
				{
					$extension['name'] = "<img src='../".$extension['admin_icon']."' /> ".$extension['name'];
				}
				end($sub_menu);
				$key = (key($sub_menu))+10;
				if(!$key)
				{
					$key = '110';
				}
				$sub_menu[$key] = array('id' => $codename, 'title' => $extension['name'], 'link' => "index.php?module=achivements-".$codename);
			}
		}
	}
}

function get_extensions_load()
{
	global $cache;
	$extensions_cache = $cache->read("extensions");
	$active_extensions = $extensions_cache['active'];
	$directory = @opendir(MYBB_ROOT."inc/plugins/achivements/extensions/");
	if($directory)
	{
		while($file = readdir($directory))
		{
			$ext = get_extension($file);
			if($ext == "php")
			{
				$codename = str_replace(".php", "", $file);
				if(@array_key_exists($codename, $active_extensions))
				{
					$extensions_list[] = $file;
				}
			}
		}
		@sort($extensions_list);
	}
	@closedir($directory);
	return $extensions_list;
}

?>