<?php
/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: forms.php 2012-04-15 10:58Z EdsonOrdaz $
 */
 
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

function home_ranks()
{
	global $db, $lang, $mybb, $cache;
	$query = $db->simple_select('ranks', 'COUNT(rid) AS rids', '', array('limit' => 1));
	$quantity = $db->fetch_field($query, "rids");
	$pagina = intval($mybb->input['page']);
	$perpage = 10;
	if($pagina > 0)
	{
		$start = ($pagina - 1) * $perpage;
		$pages = $quantity / $perpage;
		$pages = ceil($pages);
		if($pagina > $pages || $pagina <= 0)
		{
			$start = 0;
			$pagina = 1;
		}
	}
	else
	{
		$start = 0;
		$pagina = 1;
	}
	$pageurl = "index.php?module=achivements-ranks";
	$table = new Table;
	$table->construct_header($lang->image, array("width" => "5%","class" => "align_center"));
	$table->construct_header($lang->namedescription);
	$table->construct_header($lang->achivements, array("width" => "20%","class" => "align_center"));
	$table->construct_header($lang->options, array("width" => "10%","class" => "align_center"));
	$table->construct_row();
	$orderdir = $cache->read("ranks");
	$query = $db->simple_select("ranks", '*', '', array('order_by' => 'level', 'order_dir' => $orderdir['order_dir'], 'limit' => $start.", ".$perpage));
	while($rank = $db->fetch_array($query))
	{
		$achivements = achivements_get();
		$lang->confirmdeleterank = $lang->sprintf($lang->confirmdeleterankpoop, $rank['name']);
		
		if(!empty($achivements['apid'][$rank['apid']]['apid']))
		{
			$logros .= "<img src=\"../".$achivements['apid'][$rank['apid']]['image']."\" title=\"".$achivements['apid'][$rank['apid']]['name']."\" /> ";
		}
		if(!empty($achivements['atid'][$rank['atid']]['atid']))
		{
			$logros .= "<img src=\"../".$achivements['atid'][$rank['atid']]['image']."\" title=\"".$achivements['atid'][$rank['atid']]['name']."\"/> ";
		}
		if(!empty($achivements['arid'][$rank['arid']]['arid']))
		{
			$logros .= "<img src=\"../".$achivements['arid'][$rank['arid']]['image']."\" title=\"".$achivements['arid'][$rank['arid']]['name']."\"/> ";
		}
		if(!empty($achivements['toid'][$rank['toid']]['toid']))
		{
			$logros .= "<img src=\"../".$achivements['toid'][$rank['toid']]['image']."\" title=\"".$achivements['toid'][$rank['toid']]['name']."\"/> ";
		}
		if(!empty($achivements['rgid'][$rank['rgid']]['rgid']))
		{
			$logros .= "<img src=\"../".$achivements['rgid'][$rank['rgid']]['image']."\" title=\"".$achivements['rgid'][$rank['rgid']]['name']."\"/> ";
		}
		if(!$logros)
		{
			$logros = $lang->none;
		}
		$table->construct_cell("<img src=\"../$rank[image]\" title=\"$rank[name]\">",array("class" => "align_center"));
		$table->construct_cell("<strong><a href=\"index.php?module=achivements-ranks&action=edit&rid=$rank[rid]\" />$rank[name]</a></strong><br /><small>$rank[description]</small>");
		$table->construct_cell($logros ,array("class" => "align_center"));
		$popup = new PopupMenu("rid_$rank[rid]", $lang->options);
		$popup->add_item($lang->edit, "index.php?module=achivements-ranks&action=edit&rid=$rank[rid]");
		$popup->add_item($lang->delete, "index.php?module=achivements-ranks&action=delete&rid={$rank['rid']}&my_post_key={$mybb->post_code}\" target=\"_self\" onclick=\"return AdminCP.deleteConfirmation(this, '{$lang->confirmdeleterank}')");
		$Popuss = $popup->fetch();
		$table->construct_cell($Popuss, array('class' => 'align_center'));
		$table->construct_row();
		unset($logros);
	}
	if($table->num_rows() == 1)
	{
		$table->construct_cell($lang->emptyranks, array('colspan' => 4, 'class' => 'align_center'));
		$table->construct_row();
	}
	
	if($orderdir['order_dir'] == 'asc')
	{
		$show = "<a href=\"index.php?module=achivements-ranks&order=desc\" />{$lang->showdesctable}</a>";
	}
	else
	{
		$show = "<a href=\"index.php?module=achivements-ranks&order=asc\" />{$lang->showasctable}</a>";
	}
	$table->output("<div style=\"float:right;\">$show</div>".$lang->ranks);
	echo multipage($quantity, (int)$perpage, (int)$pagina, $pageurl);
}

function form_new_rank()
{
	global $mybb, $page, $lang, $db;
	
	$achivements = achivements_get();
	$posts = array();
	$threads = array();
	$reputation = array();
	$timeonline = array();
	$regdate = array();
	
	$posts[0] = $lang->none;
	$threads[0] = $lang->none;
	$reputation[0] = $lang->none;
	$timeonline[0] = $lang->none;
	$regdate[0] = $lang->none;
	
	if($achivements)
	{
		foreach($achivements['apid'] as $apid => $achivement)
		{
			$posts[$achivement['apid']] = $achivement['name'];
		}
		foreach($achivements['atid'] as $atid => $achivement)
		{
			$threads[$achivement['atid']] = $achivement['name'];
		}
		foreach($achivements['arid'] as $arid => $achivement)
		{
			$reputation[$achivement['arid']] = $achivement['name'];
		}
		foreach($achivements['toid'] as $toid => $achivement)
		{
			$timeonline[$achivement['toid']] = $achivement['name'];
		}
		foreach($achivements['rgid'] as $rgid => $achivement)
		{
			$regdate[$achivement['rgid']] = $achivement['name'];
		}
	}
	$form = new Form("index.php?module=achivements-ranks&action=new", "post", "save",1);
	$form_container = new FormContainer($lang->newrank);
	$form_container->output_row($lang->nameofrank."<em>*</em>", $lang->nameofrankdes, $form->generate_text_box('name', $mybb->input['name'], array('id' => 'name')), 'name');
	$form_container->output_row($lang->descriptionofrank."<em>*</em>", $lang->descriptionofrankdes, $form->generate_text_area('description', $mybb->input['description'], array('id' => 'description')), 'description');
	$form_container->output_row($lang->posts."<em>*</em>", $lang->newrankpostsdes, $form->generate_select_box("posts", $posts, $mybb->input['posts'], array('id' => 'posts')), 'posts');
	$form_container->output_row($lang->threads."<em>*</em>", $lang->newrankthreadsdes, $form->generate_select_box("threads", $threads, $mybb->input['threads'], array('id' => 'threads')), 'threads');
	$form_container->output_row($lang->reputation."<em>*</em>", $lang->newrankreputationdes, $form->generate_select_box("reputation", $reputation, $mybb->input['reputation'], array('id' => 'reputation')), 'reputation');
	$form_container->output_row($lang->timeonline."<em>*</em>", $lang->newranktimeonlinedes, $form->generate_select_box("timeonline", $timeonline, $mybb->input['timeonline'], array('id' => 'timeonline')), 'timeonline');
	$form_container->output_row($lang->regdate."<em>*</em>", $lang->newrankregdatedes, $form->generate_select_box("regdate", $regdate, $mybb->input['regdate'], array('id' => 'regdate')), 'regdate');
	$form_container->output_row($lang->image,$lang->imagedesnewrank, $form->generate_file_upload_box("image", array('style' => 'width: 310px;')), 'file');
	$form_container->output_row($lang->level."<em>*</em>", $lang->levelnewrank, $form->generate_text_box('level', $mybb->input['level'], array('id' => 'level')), 'level');
	$form_container->end();

	$buttons[] = $form->generate_submit_button($lang->save, array('name' => 'save'));
	$form->output_submit_wrapper($buttons);
	$form->end();
	$page->output_footer();
}

function form_edit_rank($id)
{
	global $mybb, $page, $lang, $db;
	$rank = get_rank($id);
	$achivements = achivements_get();
	$posts = array();
	$threads = array();
	$reputation = array();
	$timeonline = array();
	$regdate = array();
	
	$posts[0] = $lang->none;
	$threads[0] = $lang->none;
	$reputation[0] = $lang->none;
	$timeonline[0] = $lang->none;
	$regdate[0] = $lang->none;
	
	foreach($achivements['apid'] as $apid => $achivement)
	{
		$posts[$achivement['apid']] = $achivement['name'];
	}
	foreach($achivements['atid'] as $atid => $achivement)
	{
		$threads[$achivement['atid']] = $achivement['name'];
	}
	foreach($achivements['arid'] as $arid => $achivement)
	{
		$reputation[$achivement['arid']] = $achivement['name'];
	}
	foreach($achivements['toid'] as $toid => $achivement)
	{
		$timeonline[$achivement['toid']] = $achivement['name'];
	}
	foreach($achivements['rgid'] as $rgid => $achivement)
	{
		$regdate[$achivement['rgid']] = $achivement['name'];
	}
	$form = new Form("index.php?module=achivements-ranks&action=edit&rid=$rank[rid]", "post", "save",1);
	$form_container = new FormContainer($lang->newrank);
	echo $form->generate_hidden_field("rid", $rank['rid']);
	echo $form->generate_hidden_field("imageactual", $rank['image']);
	$form_container->output_row($lang->nameofrank."<em>*</em>", $lang->nameofrankdes, $form->generate_text_box('name', $rank['name'], array('id' => 'name')), 'name');
	$form_container->output_row($lang->descriptionofrank."<em>*</em>", $lang->descriptionofrankdes, $form->generate_text_area('description', $rank['description'], array('id' => 'description')), 'description');
	$form_container->output_row($lang->posts."<em>*</em>", $lang->newrankpostsdes, $form->generate_select_box("posts", $posts, $rank['apid'], array('id' => 'posts')), 'posts');
	$form_container->output_row($lang->threads."<em>*</em>", $lang->newrankthreadsdes, $form->generate_select_box("threads", $threads, $rank['atid'], array('id' => 'threads')), 'threads');
	$form_container->output_row($lang->reputation."<em>*</em>", $lang->newrankreputationdes, $form->generate_select_box("reputation", $reputation, $rank['arid'], array('id' => 'reputation')), 'reputation');
	$form_container->output_row($lang->timeonline."<em>*</em>", $lang->newranktimeonlinedes, $form->generate_select_box("timeonline", $timeonline, $rank['toid'], array('id' => 'timeonline')), 'timeonline');
	$form_container->output_row($lang->regdate."<em>*</em>", $lang->newrankregdatedes, $form->generate_select_box("regdate", $regdate, $rank['rgid'], array('id' => 'regdate')), 'regdate');
	$form_container->output_row($lang->imageactual,$lang->imageactualdes, "<img src='../".$rank['image']."' />", 'imageactual_des');
	$form_container->output_row($lang->image,$lang->imagedesnewrank, $form->generate_file_upload_box("image", array('style' => 'width: 310px;')), 'file');
	$form_container->output_row($lang->level."<em>*</em>", $lang->levelnewrank, $form->generate_text_box('level', $rank['level'], array('id' => 'level')), 'level');
	$form_container->end();

	$buttons[] = $form->generate_submit_button($lang->save, array('name' => 'save'));
	$form->output_submit_wrapper($buttons);
	$form->end();
	$page->output_footer();
}


?>