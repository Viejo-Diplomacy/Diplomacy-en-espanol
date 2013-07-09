<?php

/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: threadbump.php 2012-04-12 10:58Z EdsonOrdaz $
 */

// Disallow direct access to this file for security reasons

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("showthread_end", "extension_bump_start");

function extension_threadbump_info()
{
	global $lang;
	extensions_lang('threadbump');
	return array(
		'name' => $lang->threadbump,
		'description' => $lang->threadbumpplugdes,
		'version' => '1.0',
		'website' => 'http://www.mybb-es.com/',
		'author' => 'Edson Ordaz',
		'authorsite' => 'http://www.mybb-es.com/',
		'achivements' => '*'
	);
}

function extension_threadbump_activate()
{
	global $lang, $db, $cache;
	
	extensions_lang('threadbump');
	
	if(!$db->field_exists("nextbump", "users"))  
	{
		$db->add_column("users", "nextbump", "bigint(30) NOT NULL default '0'"); 
	}
	$update = array(
		'achivements' => ''
	);
	$cache->update("threadbump" , $update);
	$group = extensions_add_settinggroup('threadbump', $lang->threadbump, $lang->threadbumpplugdes, 1);
	extensions_add_settings('threadbump_enable', $lang->enable, $lang->enabledes, 'yesno', 0, 1, intval($group));
	extensions_add_settings('threadbump_message', $lang->message, $lang->messagedes, 'textarea', $lang->valuemessage, 2, intval($group));
	extensions_add_settings('threadbump_nottime', $lang->messagetime, $lang->messagetimedes, 'textarea', $lang->notbumpthread_time, 2, intval($group));
	rebuild_settings();
}

function extension_threadbump_deactivate()
{
	global $db;
	if($db->field_exists("nextbump", "users"))  
	{
		$db->drop_column("users", "nextbump");
	}
	$db->delete_query("datacache", "title='threadbump'");
	extensions_remove_settinggroup('threadbump');
	extensions_remove_settings('threadbump_%');
	rebuild_settings();
}

function extension_threadbump_admin()
{
	global $lang, $cache, $mybb, $page, $db;
	extensions_lang('threadbump');
	
	if($mybb->request_method == "post")
	{
		$dats = array(
			'posts' => intval($mybb->input['posts']),
			'threads' => intval($mybb->input['threads']),
			'reputation' => intval($mybb->input['reputation']),
			'timeonline' => intval($mybb->input['timeonline']),
			'regdate' => intval($mybb->input['regdate']),
			'custom' => intval($mybb->input['custom']),
		);
		$update = array(
			'achivements' => @serialize($dats)
		);
		$cache->update("threadbump", $update);
		flash_message($lang->success_update, 'success');
		admin_redirect("index.php?module=achivements-threadbump");
	}
	$cacheachivements = $cache->read("threadbump");
	$achivements = @unserialize($cacheachivements['achivements']);
	
	$posts = array();
	$threads = array();
	$reputations = array();
	$timeonline = array();
	$regdate = array();
	$custom = array();
		
	$get_posts = get_achivements_posts();
	$get_threads = get_achivements_threads();
	$get_reputations = get_achivements_reputation();
	$get_timeonline = get_achivements_timeonline();
	$get_regdate = get_achivements_regdate();
	$get_custom = get_achivements_custom();
		
	$posts[0] = $lang->none;
	$threads[0] = $lang->none;
	$reputations[0] = $lang->none;
	$timeonline[0] = $lang->none;
	$regdate[0] = $lang->none;
	$custom[0] = $lang->none;
		
	foreach($get_posts as $post)
	{
		$posts[$post['apid']] = $post['name'];
	}
		
	foreach($get_threads as $thread)
	{
		$threads[$thread['atid']] = $thread['name'];
	}
		
	foreach($get_reputations as $reputation)
	{
		$reputations[$reputation['arid']] = $reputation['name'];
	}
		
	foreach($get_timeonline as $time)
	{
		$timeonline[$time['toid']] = $time['name'];
	}
		
	foreach($get_regdate as $reg_date)
	{
		$regdate[$reg_date['rgid']] = $reg_date['name'];
	}
		
	foreach($get_custom as $customs)
	{
		$custom[$customs['acid']] = $customs['name'];
	}
		
	$form = new Form("index.php?module=achivements-threadbump", "post");
	$form_container = new FormContainer($lang->threadbump);
	$form_container->output_row($lang->achivementsrequestposts."<em>*</em>", $lang->achivementsrequestpostsdes, $form->generate_select_box("posts", $posts, $achivements['posts'], array('id' => 'posts')), 'posts');
	$form_container->output_row($lang->achivementsrequestthreads."<em>*</em>", $lang->achivementsrequestthreadsdes, $form->generate_select_box("threads", $threads, $achivements['threads'], array('id' => 'threads')), 'threads');
	$form_container->output_row($lang->achivementsrequestreputations."<em>*</em>", $lang->achivementsrequestreputationsdes, $form->generate_select_box("reputation", $reputations, $achivements['reputation'], array('id' => 'reputation')), 'reputation');
	$form_container->output_row($lang->achivementsrequesttimeonline."<em>*</em>", $lang->achivementsrequesttimeonlinedes, $form->generate_select_box("timeonline", $timeonline, $achivements['timeonline'], array('id' => 'timeonline')), 'timeonline');
	$form_container->output_row($lang->achivementsrequestregdate."<em>*</em>", $lang->achivementsrequestregdatedes, $form->generate_select_box("regdate", $regdate, $achivements['regdate'], array('id' => 'regdate')), 'regdate');
	$form_container->end();

	$buttons[] = $form->generate_submit_button($lang->save, array('name' => 'save'));
	$form->output_submit_wrapper($buttons);
	$form->end();
	$page->output_footer();
}

function extension_bump_start()
{
	global $tid, $cache, $mybb, $newreply, $fid, $db, $lang;
	extensions_lang('admin/threadbump');
	$user = get_user($mybb->user['uid']);
	$thread = get_thread($tid);
	if($mybb->input['bump'] == $tid)
	{
		if($mybb->settings['threadbump_enable'] == 0)
		{
			error_no_permission();
			return false;
		}
		
		if($thread['uid'] != $user['uid'])
		{
			error_no_permission();
			return false;
		}
		
		$cacheachivements = $cache->read("threadbump");
		$achivements = @unserialize($cacheachivements['achivements']);
		$achivements_user = @unserialize($mybb->user['achivements']);
		
		if($achivements['posts'] == 0)
			$achivements['posts'] = null;
		if($achivements['threads'] == 0)
			$achivements['threads'] = null;
		if($achivements['reputation'] == 0)
			$achivements['reputation'] = null;
		if($achivements['timeonline'] == 0)
			$achivements['timeonline'] = null;
		if($achivements['regdate'] == 0)
			$achivements['regdate'] = null;
			
		$posts = $achivements_user['apid'][$achivements['posts']]['apid'];
		$threads = $achivements_user['atid'][$achivements['threads']]['atid'];
		$reputation = $achivements_user['arid'][$achivements['reputation']]['arid'];
		$timeonline = $achivements_user['toid'][$achivements['timeonline']]['toid'];
		$regdate = $achivements_user['rgid'][$achivements['regdate']]['rgid'];
		
		if($achivements['posts'] != $posts || $achivements['threads'] != $threads || $achivements['reputation'] != $reputation || $achivements['timeonline'] != $timeonline || $achivements['regdate'] != $regdate || $achivements['custom'] != $custom)
		{
			$mybb->settings['threadbump_message'] = str_replace('{achivements}', error_achivements_view_threadbump($achivements), $mybb->settings['threadbump_message']);
			error($mybb->settings['threadbump_message']);
			return false;
		}
		
		$newbump = time() + (1*24*60*60);
		
		if($user['nextbump'] > TIME_NOW)
		{
			$date = my_date($mybb->settings['dateformat'], $user['nextbump']);
			$date .= ", ";
			$date .= my_date($mybb->settings['timeformat'], $user['nextbump']);
			$mybb->settings['threadbump_nottime'] = str_replace('{datetime}', $date, $mybb->settings['threadbump_nottime']);
			error($mybb->settings['threadbump_nottime']);
			return false;
		}
		
		$nextbump = array(
			"nextbump" => $newbump
		);
		$db->update_query('threads', array('lastpost' => TIME_NOW), 'tid="'.$tid.'"');
		$db->update_query('users', $nextbump, 'uid="'.$user['uid'].'"');
		redirect("forumdisplay.php?fid={$fid}", $lang->bumpsuccess);
	}
	if($user['uid'] == $thread['uid'] && $mybb->settings['threadbump_enable'] == 1 && $user['nextbump'] < TIME_NOW)
	{
		$newreply .= "<a href='showthread.php?tid={$tid}&amp;bump={$tid}'><img src=\"images/bump.png\"></a>";
	}
}

function error_achivements_view_threadbump($achivements)
{
	$list = "<ul>";
	if($achivements['posts'])
	{
		$table = 'posts';
		$column = 'apid';
		$id = $achivements['posts'];
		$post = get_achivement_id($table, $column, $id);
		if($post)
		{
			$list .= "<li><img src=\"{$post['image']}\" /> {$post['name']}</li>";
		}
}
	if($achivements['threads'])
	{
		$table = 'threads';
		$column = 'atid';
		$id = $achivements['threads'];
		$thread = get_achivement_id($table, $column, $id);
		if($thread)
		{
			$list .= "<li><img src=\"{$thread['image']}\" /> {$thread['name']}</li>";
		}
	}
	if($achivements['reputation'])
	{
		$table = 'reputation';
		$column = 'arid';
		$id = $achivements['reputation'];
		$reputation = get_achivement_id($table, $column, $id);
		if($reputation)
		{
			$list .= "<li><img src=\"{$reputation['image']}\" /> {$reputation['name']}</li>";
		}
	}
	if($achivements['timeonline'])
	{
		$table = 'timeonline';
		$column = 'toid';
		$id = $achivements['timeonline'];
		$timeonline = get_achivement_id($table, $column, $id);
		if($timeonline)
		{
			$list .= "<li><img src=\"{$timeonline['image']}\" /> {$timeonline['name']}</li>";
		}
	}
	if($achivements['regdate'])
	{
		$table = 'regdate';
		$column = 'rgid';
		$id = $achivements['regdate'];
		$regdate = get_achivement_id($table, $column, $id);
		if($regdate)
		{
			$list .= "<li><img src=\"{$regdate['image']}\" /> {$regdate['name']}</li>";
		}
	}
	if($achivements['custom'])
	{
		$table = 'custom';
		$column = 'acid';
		$id = $achivements['custom'];
		$custom = get_achivement_id($table, $column, $id);
		if($custom)
		{
			$list .= "<li><img src=\"{$custom['image']}\" /> {$custom['name']}</li>";
		}
	}
	$list .= "</ul>";
	return $list;
}
?>