<?php


/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: mp.php 2012-05-27 10:58Z EdsonOrdaz $
 */
 
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

function get_achivement($table, $id)
{
	global $db;
	$query = $db->simple_select('achivements_'.$table, '*', $id);
	$achivement = $db->fetch_array($query);
	$read = array(
		"name" => $achivement['name'],
		"image" => $achivement['image'],
		"description" => $achivement['description'],
		"reason" => $achivement['reason']
	);
	return $read;
}

function send_mp_achivement_new($uid, $table, $id)
{
	global $mybb;
	require_once MYBB_ROOT."inc/datahandlers/pm.php";
	$pmhandler = new PMDataHandler();
	$user = get_user($uid);
	switch($table)
	{
		case 'posts':
			$achivement = get_achivement('posts', "apid='".$id."'");
		break;
		
		case 'threads':
			$achivement = get_achivement('threads', "atid='".$id."'");
		break;
		
		case 'reputation':
			$achivement = get_achivement('reputation', "arid='".$id."'");
		break;
		
		case 'timeonline':
			$achivement = get_achivement('timeonline', "toid='".$id."'");
		break;
		
		case 'regdate':
			$achivement = get_achivement('regdate', "rgid='".$id."'");
		break;
		
		case 'custom':
			$achivement = get_achivement('custom', "acid='".$id."'");
		break;
		
		case 'achivement':
			$achivement = get_achivement('achivement', "aaid='".$id."'");
		break;
	}
	$message = $mybb->settings['achivements_bodymp'];
	$message = str_replace('{user}', $user['username'], $message);
	$message = str_replace('{bburl}', $mybb->settings['bburl'], $message);
	$message = str_replace('{bbname}', $mybb->settings['bbname'], $message);
	$message = str_replace('{name}', $achivement['name'], $message);
	if($table == 'custom')
	{
		$message = str_replace('{description}', $achivement['reason'], $message);
	}
	else
	{
		$message = str_replace('{description}', $achivement['description'], $message);
	}
	$message = str_replace('{image}', "[img]".$mybb->settings['bburl']."/".$achivement['image']."[/img]", $message);
	
	$pm = array(
		"subject" => $mybb->settings['achivements_subjectmp'],
		"message" => $message,
		"icon" => -1,
		"fromid" => intval($mybb->settings['achivements_usermp']),
		"toid" => array($user['uid']),
		"do" => '',
		"pmid" => ''
	);	
	$pm['options'] = array(
		"signature" => 1,
		"disablesmilies" => 0,
		"savecopy" => 0,
		"readreceipt" => 0
	);
	$pm['saveasdraft'] = 0;
	$pmhandler->admin_override = 1;
	$pmhandler->set_data($pm);
	if($pmhandler->validate_pm())
	{
		$pmhandler->insert_pm();
	}
}

?>