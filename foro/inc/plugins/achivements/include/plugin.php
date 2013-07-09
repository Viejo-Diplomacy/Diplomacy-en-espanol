<?php


/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: plugin.php 2012-05-20 10:58Z EdsonOrdaz $
 */
 
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

function achivements_init_info()
{
	global $lang;
	$lang->load('achivements');
	return array(
		"name"			=> "Achivements",
		"description"	=> $lang->achivements_description,
		"website"		=> "http://www.mybb-es.com/",
		"author"		=> "Edson Ordaz",
		"authorsite"	=> "http://www.facebook.com/EdsonOrdaz",
		"version"		=> "2.4",
		"compatibility" => "16*",
		"guid"			=> "2d34a3446ace942c960261094b798a22"
	);
}

function achivements_init_is_installed()
{
	global $db;
	if ($db->table_exists('achivements_threads') && $db->table_exists('achivements_posts') && $db->table_exists('achivements_reputation') && $db->table_exists('achivements_timeonline') && $db->table_exists('achivements_regdate') && $db->table_exists('achivements_custom'))
	{
		$return = true;
	}else{
		$return = false;
	}
	return $return;
}

function achivements_init_install()
{
	global $db, $cache;
	require_once MYBB_ROOT."inc/plugins/achivements/include/install.php";
	$collation = $db->build_create_table_collation();
	foreach($tables as $table)
	{
		if(!$db->table_exists($table['name']))
		{
			$db->query($table['insert'].$collation);
		}
	}
	foreach($fields as $field)
	{
		if(!$db->field_exists($field['column'], $field['table']))  
		{
			$db->add_column($field['table'], $field['column'], $field['insert']); 
		}
	}
	count_threads_update();
	if(!file_exists(MYBB_ROOT."inc/tasks/achivements.php"))
	{
		create_task();
	}else{
		create_task_tools();
	}	
	foreach(settings_insert() as $installsettings)
	{
		$db->insert_query("settings", $installsettings);
	}
	foreach($templates as $template)
	{
		$db->insert_query("templates", $template);
	}
	
	$update_cache = array(
		"active" => ''
	);
	$cache->update("extensions", $update_cache);
	
	rebuildsettings();
	
	require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets('member_profile', '#{\$profilefields}#', '{\$profilefields}<!-- Profile_achivements -->{\$achivementsprofile}<!-- /Profile_achivements -->');
	find_replace_templatesets('usercp_nav_profile', '#{\$changesigop}#', '{\$changesigop}<!-- usercp_achivements --><div><a href="usercp.php?action=achivements" class="usercp_nav_item" style="padding-left: 40px;background: url(inc/plugins/achivements/include/images/achivements_usercp.png) no-repeat left center;">Achivements</a></div><!-- /usercp_achivements -->');
	find_replace_templatesets('modcp_nav', '#{\$lang->mcp_nav_editprofile}</a></td></tr>#', '{\$lang->mcp_nav_editprofile}</a></td></tr><!--modcp_achivements-->');
	find_replace_templatesets('postbit', '#'.preg_quote('{$post[\'user_details\']}').'#', "{\$post['user_details']}<!-- postbit_achivements -->{\$post['achivementspostbit']}<!-- /postbit_achivements -->");
	find_replace_templatesets('postbit_classic', '#'.preg_quote('{$post[\'user_details\']}').'#', "{\$post['user_details']}<!-- postbit_achivements -->{\$post['achivementspostbit']}<!-- /postbit_achivements -->");
	
	change_admin_permission("achivements", true, 1);
	change_admin_permission("achivements", "posts", 0);
	change_admin_permission("achivements", "threads", 0);
	change_admin_permission("achivements", "reputation", 0);
	change_admin_permission("achivements", "timeonline", 0);
	change_admin_permission("achivements", "regdate", 0);
	change_admin_permission("achivements", "custom", 0);
	change_admin_permission("achivements", "achivements", 0);
	change_admin_permission("achivements", "settings", 0);
	change_admin_permission("achivements", "extensions", 0);
	change_admin_permission("achivements", "config", 0);
}

function achivements_init_uninstall()
{
	global $db, $cache;
	
	$extensions_cache = $cache->read("extensions");
	$active_extensions = $extensions_cache['active'];
	if(!empty($active_extensions))
	{
		foreach($active_extensions as $extension_file)
		{
			require_once MYBB_ROOT."inc/plugins/achivements/extensions/".$extension_file.".php";
			if(function_exists("extension_{$extension_file}_deactivate"))
			{
				call_user_func("extension_{$extension_file}_deactivate");
			}
		}
	}
	require_once MYBB_ROOT."inc/plugins/achivements/include/install.php";
	delete_images_unlink();
	foreach($tables as $table)
	{
		if($db->table_exists($table['name']))
		{
			$db->drop_table($table['name']);
		}
	}
	foreach($fields as $field)
	{
		if($db->field_exists($field['column'], $field['table']))  
		{
			$db->drop_column($field['table'], $field['column']);
		}
	}
	if(file_exists(MYBB_ROOT."inc/tasks/achivements.php"))
	{
		@unlink(MYBB_ROOT.'inc/tasks/achivements.php');
	}
	$db->delete_query('tasks', 'file=\'achivements\'');
	$db->delete_query('datacache', 'title=\'extensions\'');
	$db->delete_query("settings","name LIKE 'achivements_%'");
	$db->delete_query("templates","title LIKE 'achivements%'");
	rebuildsettings();
	
	require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets('member_profile', '#\<!--\sProfile_achivements\s--\>(.+)\<!--\s/Profile_achivements\s--\>#is', '', 0);
	find_replace_templatesets('usercp_nav_profile', '#\<!--\susercp_achivements\s--\>(.+)\<!--\s/usercp_achivements\s--\>#is', '', 0);
	find_replace_templatesets('modcp_nav', '#\<!--modcp_achivements-->#is', '', 0);
	find_replace_templatesets('postbit', '#\<!--\spostbit_achivements\s--\>(.+)\<!--\s/postbit_achivements\s--\>#is', '', 0);
	find_replace_templatesets('postbit_classic', '#\<!--\spostbit_achivements\s--\>(.+)\<!--\s/postbit_achivements\s--\>#is', '', 0);
	
	change_admin_permission("achivements", false, -1);
	change_admin_permission("achivements", "posts", -1);
	change_admin_permission("achivements", "threads", -1);
	change_admin_permission("achivements", "reputation", -1);
	change_admin_permission("achivements", "timeonline", -1);
	change_admin_permission("achivements", "regdate", -1);
	change_admin_permission("achivements", "custom", -1);
	change_admin_permission("achivements", "achivements", -1);
	change_admin_permission("achivements", "settings", -1);
	change_admin_permission("achivements", "extensions", -1);
	change_admin_permission("achivements", "config", -1);
}

function get_extensions_unistall()
{
	$directory = @opendir(MYBB_ROOT."inc/plugins/achivements/extensions/");
	if($directory)
	{
		while($file = readdir($directory))
		{
			$ext = get_extension($file);
			if($ext == "php")
			{
				$extensions_list[] = $file;
			}
		}
		@sort($extensions_list);
	}
	@closedir($directory);
	
	return $extensions_list;
}
?>