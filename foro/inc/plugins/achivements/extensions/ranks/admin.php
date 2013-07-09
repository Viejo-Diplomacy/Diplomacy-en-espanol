<?php

/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: admin.php 2012-04-29 10:58Z EdsonOrdaz $
 */

// Disallow direct access to this file for security reasons

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
function admin_load_rank()
{
	global $lang, $cache, $mybb, $page, $db;
	require_once MYBB_ROOT."inc/plugins/achivements/extensions/ranks/class_ranks.php";
	require_once MYBB_ROOT."inc/plugins/achivements/extensions/ranks/forms.php";
	$rankhandler = new class_extension_ranks;
	if($mybb->input['action'] == "new")
	{
		$rankhandler->tabs('new');
		if($mybb->request_method == "post")
		{
			$rank = array(
				"name" => $db->escape_string($mybb->input['name']),
				"description" => $db->escape_string($mybb->input['description']),
				"apid" => intval($mybb->input['posts']),
				"atid" => intval($mybb->input['threads']),
				"arid" => intval($mybb->input['reputation']),
				"toid" => intval($mybb->input['timeonline']),
				"rgid" => intval($mybb->input['regdate']),
				"image" => $_FILES['image'],
				"level" => intval($mybb->input['level'])
			);
			$rankhandler->set_data($rank);
			if(!$rankhandler->validate_rank())
			{
				$errors = $rankhandler->get_friendly_errors();
			}
			if(!$errors)
			{
				$rankhandler->insert_rank();
				flash_message($lang->successnewrank, 'success');
				admin_redirect("index.php?module=achivements-ranks");
			}
		}
		if($errors)
		{
			$page->output_inline_error($errors);
		}
		form_new_rank();
	}
	elseif($mybb->input['action'] == "edit")
	{
		$rank = get_rank($mybb->input['rid']);
		if(!$rank['rid'])
		{
			flash_message($lang->notexistrankedit, 'error');
			admin_redirect("index.php?module=achivements-ranks");
		}
		if($mybb->request_method == "post")
		{
			$rank = array(
				"rid" => intval($mybb->input['rid']),
				"name" => $db->escape_string($mybb->input['name']),
				"description" => $db->escape_string($mybb->input['description']),
				"apid" => intval($mybb->input['posts']),
				"atid" => intval($mybb->input['threads']),
				"arid" => intval($mybb->input['reputation']),
				"toid" => intval($mybb->input['timeonline']),
				"rgid" => intval($mybb->input['regdate']),
				"image" => $_FILES['image'],
				"imageactual" => $mybb->input['imageactual'],
				"level" => intval($mybb->input['level']),
				"edit" => true
			);
			$rankhandler->set_data($rank);
			if(!$rankhandler->validate_rank())
			{
				$errors = $rankhandler->get_friendly_errors();
			}
			if(!$errors)
			{		
				$rankhandler->insert_rank();
				flash_message($lang->successeditrank, 'success');
				admin_redirect("index.php?module=achivements-ranks");
			}
		}
		if($errors)
		{
			$page->output_inline_error($errors);
		}
		$rankhandler->tabs('edit', array('rid' => $rank['rid']));
		form_edit_rank($rank['rid']);
	}
	elseif($mybb->input['action'] == "delete")
	{
		$rank = get_rank($mybb->input['rid']);
		if(!$rank['rid'])
		{
			flash_message($lang->notexistrankedit, 'error');
			admin_redirect("index.php?module=achivements-ranks");
		}
		@unlink(MYBB_ROOT.$rank['image']);
		$db->query("DELETE FROM ".TABLE_PREFIX."ranks WHERE rid='$rank[rid]'");
		flash_message($lang->deletsuccessrank, 'success');
		admin_redirect("index.php?module=achivements-ranks");
	}
	elseif($mybb->input['order'])
	{
		if($mybb->input['order'] != 'asc' && $mybb->input['order'] != 'desc')
		{
			$mybb->input['order'] = 'asc';
		}
		
		$insertcache = array(
			'order_dir' => $mybb->input['order']
		);
		$cache->update("ranks", $insertcache);
		
		if($mybb->input['order'] == 'asc')
		{
			$ascdesc = $lang->asc;
		}
		elseif($mybb->input['order'] == 'desc')
		{
			$ascdesc = $lang->desc;
		}
		$lang->successorderascdesc = $lang->sprintf($lang->successorderascdesc, $ascdesc);
		flash_message($lang->successorderascdesc, 'success');
		admin_redirect("index.php?module=achivements-ranks");
	}
	else
	{
		$orderdir = $cache->read('ranks');
		if(!$orderdir['order_dir'])
		{
			$insertcache = array(
				'order_dir' => 'asc'
			);
			$cache->update("ranks", $insertcache);
		}
		$rankhandler->tabs();
		home_ranks();
	}
}

?>