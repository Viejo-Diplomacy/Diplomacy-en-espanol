<?php

/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: forumrequiements.php 2012-04-12 10:58Z EdsonOrdaz $
 */

// Disallow direct access to this file for security reasons

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("forumdisplay_end", "extension_forumrequirements");
$plugins->add_hook("archive_start", "extension_forumrequirements_archive");
$plugins->add_hook("showthread_end", "extension_forumrequirements_threads");
$plugins->add_hook("newreply_end", "extension_forumrequirements_threads");
$plugins->add_hook("newthread_end", "extension_forumrequirements_threads");

function extension_forumrequiements_info()
{
	global $lang;
	extensions_lang('forumrequiements');
	return array(
		'name' => $lang->forumrequiements,
		'description' => $lang->plugdes,
		'version' => '1.0',
		'website' => 'http://www.mybb-es.com/',
		'author' => 'Edson Ordaz',
		'authorsite' => 'http://www.mybb-es.com/',
		'achivements' => '*'
	);
}

function extension_forumrequiements_activate()
{
	global $lang, $db, $cache;
	
	extensions_lang('forumrequiements');
	
	if(!$db->field_exists("requireachivements", "forums"))  
	{
		$db->add_column("forums", "requireachivements", "TEXT NOT NULL"); 
		$cache->update_forums();
	}
	$group = extensions_add_settinggroup('forumsrequirements', $lang->forumrequiements, $lang->plugdes, 1);
	extensions_add_settings('forumrequirements_enable', $lang->enable, $lang->enabledes, 'yesno', 0, 1, intval($group));
	extensions_add_settings('forumrequirements_message', $lang->message, $lang->messagedes, 'textarea', $lang->valuemessage, 2, intval($group));
	rebuild_settings();
}

function extension_forumrequiements_deactivate()
{
	global $db, $cache;
	if($db->field_exists("requireachivements", "forums"))  
	{
		$db->drop_column("forums", "requireachivements");
		$cache->update_forums();
	}
	extensions_remove_settinggroup('forumsrequirements');
	extensions_remove_settings('forumrequirements_%');
	rebuild_settings();
}

function extension_forumrequiements_admin()
{
	global $lang, $cache, $mybb, $page, $db;
	extensions_lang('forumrequiements');
	$forum_cache = cache_forums();
	
	if($mybb->input['action'] == "edit")
	{
		$fid = intval($mybb->input['fid']);
		$forum = $forum_cache[$fid];
		
		if(!$forum['fid'])
		{
			flash_message($lang->noforumexists, 'error');
			admin_redirect("index.php?module=achivements-forumrequiements");
		}
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
				'requireachivements' => @serialize($dats)
			);
			$db->update_query("forums", $update, "fid='".intval($forum['fid'])."'");
			$cache->update_forums();
			flash_message($lang->success_update, 'success');
			admin_redirect("index.php?module=achivements-forumrequiements");
		}
		
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
		
		$achivements = @unserialize($forum['requireachivements']);
		
		$form = new Form("index.php?module=achivements-forumrequiements&action=edit", "post");
		$form_container = new FormContainer($forum['name']);
		echo $form->generate_hidden_field("fid", $forum['fid']);
		$form_container->output_row($lang->achivementsrequestposts."<em>*</em>", $lang->achivementsrequestpostsdes, $form->generate_select_box("posts", $posts, $achivements['posts'], array('id' => 'posts')), 'posts');
		$form_container->output_row($lang->achivementsrequestthreads."<em>*</em>", $lang->achivementsrequestthreadsdes, $form->generate_select_box("threads", $threads, $achivements['threads'], array('id' => 'threads')), 'threads');
		$form_container->output_row($lang->achivementsrequestreputations."<em>*</em>", $lang->achivementsrequestreputationsdes, $form->generate_select_box("reputation", $reputations, $achivements['reputation'], array('id' => 'reputation')), 'reputation');
		$form_container->output_row($lang->achivementsrequesttimeonline."<em>*</em>", $lang->achivementsrequesttimeonlinedes, $form->generate_select_box("timeonline", $timeonline, $achivements['timeonline'], array('id' => 'timeonline')), 'timeonline');
		$form_container->output_row($lang->achivementsrequestregdate."<em>*</em>", $lang->achivementsrequestregdatedes, $form->generate_select_box("regdate", $regdate, $achivements['regdate'], array('id' => 'regdate')), 'regdate');
		$form_container->output_row($lang->achivementsrequestcustom."<em>*</em>", $lang->achivementsrequestcustomdes, $form->generate_select_box("custom", $custom, $achivements['custom'], array('id' => 'custom')), 'custom');
		$form_container->end();

		$buttons[] = $form->generate_submit_button($lang->save, array('name' => 'save'));
		$form->output_submit_wrapper($buttons);
		$form->end();
		$page->output_footer();
	}
	
	$table = new Table;
	$table->construct_header($lang->forums);
	$table->construct_header($lang->posts, array('class' => 'align_center', 'width' => '10%'));
	$table->construct_header($lang->threads, array('class' => 'align_center', 'width' => '10%'));
	$table->construct_header($lang->reputation, array('class' => 'align_center', 'width' => '10%'));
	$table->construct_header($lang->timeonline, array('class' => 'align_center', 'width' => '15%'));
	$table->construct_header($lang->regdate, array('class' => 'align_center', 'width' => '20%'));
	$table->construct_header($lang->custom, array('class' => 'align_center', 'width' => '10%'));
	$table->construct_row();
	
	foreach($forum_cache as $forum)
	{
		if($forum['type'] != "c")
		{
			$achivements = @unserialize($forum['requireachivements']);
				
			$posts = get_achivement_id('posts', 'apid', $achivements['posts']);
			$threads = get_achivement_id('threads', 'atid', $achivements['threads']);
			$reputation = get_achivement_id('reputation', 'arid', $achivements['reputation']);
			$timeonline = get_achivement_id('timeonline', 'toid', $achivements['timeonline']);
			$regdate = get_achivement_id('regdate', 'rgid', $achivements['regdate']);
			$custom = get_achivement_id('custom', 'acid', $achivements['custom']);
			
			if(!$posts['name'])
				$posts['name'] = $lang->none;
			if(!$threads['name'])
				$threads['name'] = $lang->none;
			if(!$reputation['name'])
				$reputation['name'] = $lang->none;
			if(!$timeonline['name'])
				$timeonline['name'] = $lang->none;
			if(!$regdate['name'])
				$regdate['name'] = $lang->none;
			if(!$custom['name'])
				$custom['name'] = $lang->none;
				
			$table->construct_cell("<a href=\"index.php?module=achivements-forumrequiements&action=edit&fid={$forum['fid']}\" /><strong>".$forum['name']."</strong></a>");
			$table->construct_cell($posts['name'], array('class' => 'align_center'));
			$table->construct_cell($threads['name'], array('class' => 'align_center'));
			$table->construct_cell($reputation['name'], array('class' => 'align_center'));
			$table->construct_cell($timeonline['name'], array('class' => 'align_center'));
			$table->construct_cell($regdate['name'], array('class' => 'align_center'));
			$table->construct_cell($custom['name'], array('class' => 'align_center'));
			unset($posts);
			unset($threads);
			unset($reputation);
			unset($timeonline);
			unset($regdate);
			unset($custom);
		}
		$table->construct_row();
	}
	$table->output($lang->forums);
	$page->output_footer();
}

function extension_forumrequirements_threads()
{
	global $forum, $mybb;
	
	$achivements_forum = @unserialize($forum['requireachivements']);
	$achivements_user = @unserialize($mybb->user['achivements']);
	
	if($achivements_forum['posts'] == 0)
		$achivements_forum['posts'] = null;
	if($achivements_forum['threads'] == 0)
		$achivements_forum['threads'] = null;
	if($achivements_forum['reputation'] == 0)
		$achivements_forum['reputation'] = null;
	if($achivements_forum['timeonline'] == 0)
		$achivements_forum['timeonline'] = null;
	if($achivements_forum['regdate'] == 0)
		$achivements_forum['regdate'] = null;
	if($achivements_forum['custom'] == 0)
		$achivements_forum['custom'] = null;
	
	$posts = $achivements_user['apid'][$achivements_forum['posts']]['apid'];
	$threads = $achivements_user['atid'][$achivements_forum['threads']]['atid'];
	$reputation = $achivements_user['arid'][$achivements_forum['reputation']]['arid'];
	$timeonline = $achivements_user['toid'][$achivements_forum['timeonline']]['toid'];
	$regdate = $achivements_user['rgid'][$achivements_forum['regdate']]['rgid'];
	$custom = $achivements_user['acid'][$achivements_forum['custom']]['acid'];
	
	if($mybb->settings['forumrequirements_enable'] == 1)
	{
		if($achivements_forum['posts'] != $posts || $achivements_forum['threads'] != $threads || $achivements_forum['reputation'] != $reputation || $achivements_forum['timeonline'] != $timeonline || $achivements_forum['regdate'] != $regdate || $achivements_forum['custom'] != $custom)
		{
			$mybb->settings['forumrequirements_message'] = str_replace('{achivements}', error_achivements_view($achivements_forum), $mybb->settings['forumrequirements_message']);
			error($mybb->settings['forumrequirements_message'], $mybb->settings['bbname']);
		}
	}
}

function extension_forumrequirements()
{
	global $forum_cache, $fid, $mybb;
	$achivements_forum = @unserialize($forum_cache[$fid]['requireachivements']);
	$achivements_user = @unserialize($mybb->user['achivements']);
	
	if($achivements_forum['posts'] == 0)
		$achivements_forum['posts'] = null;
	if($achivements_forum['threads'] == 0)
		$achivements_forum['threads'] = null;
	if($achivements_forum['reputation'] == 0)
		$achivements_forum['reputation'] = null;
	if($achivements_forum['timeonline'] == 0)
		$achivements_forum['timeonline'] = null;
	if($achivements_forum['regdate'] == 0)
		$achivements_forum['regdate'] = null;
	if($achivements_forum['custom'] == 0)
		$achivements_forum['custom'] = null;
	
	$posts = $achivements_user['apid'][$achivements_forum['posts']]['apid'];
	$threads = $achivements_user['atid'][$achivements_forum['threads']]['atid'];
	$reputation = $achivements_user['arid'][$achivements_forum['reputation']]['arid'];
	$timeonline = $achivements_user['toid'][$achivements_forum['timeonline']]['toid'];
	$regdate = $achivements_user['rgid'][$achivements_forum['regdate']]['rgid'];
	$custom = $achivements_user['acid'][$achivements_forum['custom']]['acid'];
	
	if($mybb->settings['forumrequirements_enable'] == 1)
	{
		if($achivements_forum['posts'] != $posts || $achivements_forum['threads'] != $threads || $achivements_forum['reputation'] != $reputation || $achivements_forum['timeonline'] != $timeonline || $achivements_forum['regdate'] != $regdate || $achivements_forum['custom'] != $custom)
		{
			$mybb->settings['forumrequirements_message'] = str_replace('{achivements}', error_achivements_view($achivements_forum), $mybb->settings['forumrequirements_message']);
			error($mybb->settings['forumrequirements_message'], $mybb->settings['bbname']);
		}
	}
}

function extension_forumrequirements_archive()
{
	global $forum, $mybb;
	$achivements_forum = @unserialize($forum['requireachivements']);
	$achivements_user = @unserialize($mybb->user['achivements']);
	
	if($achivements_forum['posts'] == 0)
		$achivements_forum['posts'] = null;
	if($achivements_forum['threads'] == 0)
		$achivements_forum['threads'] = null;
	if($achivements_forum['reputation'] == 0)
		$achivements_forum['reputation'] = null;
	if($achivements_forum['timeonline'] == 0)
		$achivements_forum['timeonline'] = null;
	if($achivements_forum['regdate'] == 0)
		$achivements_forum['regdate'] = null;
	if($achivements_forum['custom'] == 0)
		$achivements_forum['custom'] = null;
	
	$posts = $achivements_user['apid'][$achivements_forum['posts']]['apid'];
	$threads = $achivements_user['atid'][$achivements_forum['threads']]['atid'];
	$reputation = $achivements_user['arid'][$achivements_forum['reputation']]['arid'];
	$timeonline = $achivements_user['toid'][$achivements_forum['timeonline']]['toid'];
	$regdate = $achivements_user['rgid'][$achivements_forum['regdate']]['rgid'];
	$custom = $achivements_user['acid'][$achivements_forum['custom']]['acid'];
	
	if($mybb->settings['forumrequirements_enable'] == 1)
	{
		if($achivements_forum['posts'] != $posts || $achivements_forum['threads'] != $threads || $achivements_forum['reputation'] != $reputation || $achivements_forum['timeonline'] != $timeonline || $achivements_forum['regdate'] != $regdate || $achivements_forum['custom'] != $custom)
		{
			$mybb->settings['forumrequirements_message'] = str_replace('{achivements}', error_achivements_view($achivements_forum), $mybb->settings['forumrequirements_message']);
			archive_error($mybb->settings['forumrequirements_message'], $mybb->settings['bbname']);
		}
	}
}

function error_achivements_view($achivements)
{
	$archive = '';
	if(THIS_SCRIPT != 'forumdisplay.php' && THIS_SCRIPT != 'showthread.php' && THIS_SCRIPT != 'newthread.php' && THIS_SCRIPT != 'newreply.php')
	{
		$archive = "../";
	}
	$list = "<ul>";
	if($achivements['posts'])
	{
		$table = 'posts';
		$column = 'apid';
		$id = $achivements['posts'];
		$post = get_achivement_id($table, $column, $id);
		if($post)
		{
			$list .= "<li><img src=\"{$archive}{$post['image']}\" /> {$post['name']}</li>";
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
			$list .= "<li><img src=\"{$archive}{$thread['image']}\" /> {$thread['name']}</li>";
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
			$list .= "<li><img src=\"{$archive}{$reputation['image']}\" /> {$reputation['name']}</li>";
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
			$list .= "<li><img src=\"{$archive}{$timeonline['image']}\" /> {$timeonline['name']}</li>";
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
			$list .= "<li><img src=\"{$archive}{$regdate['image']}\" /> {$regdate['name']}</li>";
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
			$list .= "<li><img src=\"{$archive}{$custom['image']}\" /> {$custom['name']}</li>";
		}
	}
	$list .= "</ul>";
	return $list;
}

?>