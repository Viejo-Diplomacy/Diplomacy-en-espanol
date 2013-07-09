<?php

/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: plugin.php 2012-04-29 10:58Z EdsonOrdaz $
 */

// Disallow direct access to this file for security reasons

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

function information_extension_rank()
{
	global $lang;
	
	extensions_lang('ranks');
	
	return array(
		'name' => 'Ranks',
		'description' => $lang->ranks_plug_description,
		'version' => '1.0',
		'website' => 'http://www.mybb-es.com/',
		'author' => 'Edson Ordaz',
		'authorsite' => 'http://www.mybb-es.com/',
		'achivements' => '*',
		'admin_icon' => 'inc/plugins/achivements/extensions/ranks/ranks.png'
	);
}

function active_extension_rank()
{
	global $db, $lang;
	extensions_lang('ranks');
	$collation = $db->build_create_table_collation();
	if(!$db->table_exists('ranks'))
	{
		$db->query("CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."ranks` (
  `rid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` TEXT NOT NULL,
  `apid` int(10) NOT NULL DEFAULT 0,
  `atid` int(10) NOT NULL DEFAULT 0,
  `arid` int(10) NOT NULL DEFAULT 0,
  `toid` int(10) NOT NULL DEFAULT 0,
  `rgid` int(10) NOT NULL DEFAULT 0,
  `image` varchar(250) NOT NULL DEFAULT '',
  `level` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM{$collation}");
	}
	if(!$db->field_exists("rank", "users"))  
	{
		$db->add_column("users", "rank", "int(10) unsigned NOT NULL default '0'"); 
	}
	
	require_once MYBB_ROOT."inc/plugins/achivements/extensions/ranks/task.php";
	if(!file_exists(MYBB_ROOT."inc/tasks/ranks.php"))
	{
		create_task();
	}else{
		create_task_tools();
	}
	
	extensions_add_template('ranks', '<html>
<head>
<title>{$mybb->settings[\'bbname\']} - {$lang->listranks}</title>
{$headerinclude}
</head>
<body>
{$header}
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="8">
<strong>{$lang->listranks}</span></td>
</tr>
<tr>
<td class="tcat" width="5%" align="center"><strong>{$lang->image}</strong></td>
<td class="tcat"><strong>{$lang->namedes}</strong></td>
<td class="tcat" width="5%" align="center"><strong>{$lang->posts}</strong></td>
<td class="tcat" width="5%" align="center"><strong>{$lang->threads}</strong></td>
<td class="tcat" width="5%" align="center"><strong>{$lang->reputation}</strong></td>
<td class="tcat" width="15%" align="center"><strong>{$lang->timeonline}</strong></td>
<td class="tcat" width="15%" align="center"><strong>{$lang->regdate}</strong></td>
<td class="tcat" width="5%" align="center"><strong>{$lang->level}</strong></td>
</tr>
{$ranks}
</table>
{$footer}
</body>
</html>');
	extensions_add_template('ranks_list', '<tr>
<td class="{$trow}" align="center"><img src="{$rank[\'image\']}" /></td>
<td class="{$trow}"><strong>{$rank[\'name\']}</strong><br /><span class="smalltext">{$rank[\'description\']}</span></td>
<td class="{$trow}" align="center">{$rank[\'posts\']}</td>
<td class="{$trow}" align="center">{$rank[\'threads\']}</td>
<td class="{$trow}" align="center">{$rank[\'reputation\']}</td>
<td class="{$trow}" align="center">{$timeonline}</td>
<td class="{$trow}" align="center">{$regdate}</td>
<td class="{$trow}" align="center">{$rank[\'level\']}</td>
</tr>');
	extensions_add_template('ranks_postbit', '<br />{$lang->rank}: <img src="{$rank[\'image\']}" alt="{$rank[\'name\']}" title="{$rank[\'name\']}" /><br />');
	extensions_add_template('ranks_profile', '<br />
<table id="achivements" border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="2"><strong>{$lang->rankby}</strong></td>
</tr>
<tr>
<td class="trow1" width="10%">
<img src="{$rank[\'image\']}" alt="{$rank[\'name\']}" title="{$rank[\'name\']}" />
</td>
<td class="trow1" valign="top" align="left">
<strong>{$rank[\'name\']}</strong><br /><span class="smalltext">{$rank[\'description\']}</span>
</td>
</tr>
</table>');
	
	require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets('member_profile', '#{\$profilefields}#', '{\$profilefields}<!-- profile_rank -->{\$rankprofile}<!-- /profile_rank -->');
	find_replace_templatesets('postbit', '#'.preg_quote('{$post[\'user_details\']}').'#', "{\$post['user_details']}<!-- postbit_rank -->{\$post['rankpostbit']}<!-- /postbit_rank -->");
	find_replace_templatesets('postbit_classic', '#'.preg_quote('{$post[\'user_details\']}').'#', "{\$post['user_details']}<!-- postbit_rank -->{\$post['rankpostbit']}<!-- /postbit_rank -->");
}

function deactivate_rank_extension()
{
	global $db;
	$query = $db->simple_select('ranks');
	while($ranks = $db->fetch_array($query))
	{
		$delete_images = @unlink(MYBB_ROOT.$ranks['image']);
	}
	if($db->table_exists('ranks'))
	{
		$db->drop_table('ranks');
	}
	if($db->field_exists("rank", "users"))  
	{
		$db->drop_column("users", "rank");
	}
	$db->delete_query("datacache", "title='ranks'");
	extensions_remove_templates("'ranks', 'ranks_list', 'ranks_postbit', 'ranks_profile'");
	
	$db->delete_query('tasks', 'file=\'ranks\'');
	if(file_exists(MYBB_ROOT."inc/tasks/ranks.php"))
	{
		@unlink(MYBB_ROOT.'inc/tasks/ranks.php');
	}
	
	require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets('member_profile', '#\<!--\sprofile_rank\s--\>(.+)\<!--\s/profile_rank\s--\>#is', '', 0);
	find_replace_templatesets('postbit', '#\<!--\spostbit_rank\s--\>(.+)\<!--\s/postbit_rank\s--\>#is', '', 0);
	find_replace_templatesets('postbit_classic', '#\<!--\spostbit_rank\s--\>(.+)\<!--\s/postbit_rank\s--\>#is', '', 0);
}
?>