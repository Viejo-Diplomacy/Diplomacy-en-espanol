<?php


/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: hooks.php 2012-05-27 10:58Z EdsonOrdaz $
 */
 
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("member_profile_end", "profile_achivements");
$plugins->add_hook("postbit", "postbit_achivements");
$plugins->add_hook("usercp_start", "usercp_achivements");
$plugins->add_hook("modcp_start", "modcp_achivements");

function postbit_achivements(&$post)
{
	global $templates, $mybb, $lang;
	$lang->load("achivements", false, true);
	static $static_achievements;
	$post['achivements'] = unserialize($post['achivements']);
	if (!$post['achivements'])
	{
		$achivements = $lang->notachivements;
	}	
	else
	{
		$num_achiviements = 0;
		foreach($post['achivements'] as $column => $logros)
		{
			foreach($logros as $logro)
			{
				if($logro['showpostbit'] == 1)
				{
					++$num_achiviements;
					$achivements .= "<img src=\"".htmlspecialchars_uni($logro['image'])."\" title=\"".htmlspecialchars_uni($logro['name'])."\" /> ";
					if ($num_achiviements >= $mybb->settings['achivements_maxpostbit'])
						break;
					else
						continue;
				}
				if ($num_achiviements >= $mybb->settings['achivements_maxpostbit'])
					break;
				else
					continue;
			}
			if ($num_achiviements >= $mybb->settings['achivements_maxpostbit'])
				break;
			else
				continue;
		}
		if(!$achivements)
		{
			$achivements = $lang->achshide;
		}
	}
	if($mybb->settings['achivements_showachvpostbit'] == 1)
	{
		eval("\$post['achivementspostbit'] = \"".$templates->get("achivements_postbit")."\";");
	}
}

function profile_achivements()
{
	global $mybb, $memprofile, $templates, $lang, $theme, $achivements, $achivementsprofile;
	$lang->load("achivements", false, true);
	$memprofile['achivements'] = unserialize($memprofile['achivements']);
	
	if(!$memprofile['achivements'])
	{
		$lang->achisemptytableprofile = $lang->sprintf($lang->achisemptytableprofile, $memprofile['username']);
		$achivements = $lang->achisemptytableprofile;
	}	
	else
	{
		foreach($memprofile['achivements'] as $column => $logros)
		{
			foreach($logros as $logro)
			{
				if($logro['showprofile'] == 1)
				{
					$achivements .= "<img src=\"".htmlspecialchars_uni($logro['image'])."\" title=\"".htmlspecialchars_uni($logro['name'])."\" /> ";
				}
			}
		}
		if(!$achivements)
		{
			$lang->achishidetableprofile = $lang->sprintf($lang->achishidetableprofile, $memprofile['username']);
			$achivements = $lang->achishidetableprofile;
		}
	}
	$lang->achsmemprofile = $lang->sprintf($lang->achsmemprofile, $memprofile['username']);
	if($mybb->settings['achivements_showachvprofile'] == 1)
	{
		eval("\$achivementsprofile = \"".$templates->get("achivements_profile")."\";");
	}
}

function usercp_achivements()
{
	global $mybb,$db,$templates,$theme,$lang,$headerinclude,$header,$footer,$usercpnav;
	if($mybb->input['action'] != "achivements" && $mybb->input['action'] != "do_achivements")	
	{ 
		return; 
	}
	if($mybb->input['action'] == "do_achivements" && $mybb->request_method == "post")
	{
		verify_post_check($mybb->input['my_post_key']);
		$mybb->user['achivements'] = unserialize($mybb->user['achivements']);
		$tables = array('apid', 'atid', 'arid', 'toid', 'rgid', 'acid');
		if($mybb->input['profile'])
		{
			foreach($tables as $table)
			{
				$count = count($mybb->user['achivements'][$table]);
				for($i = 1; $i <= $count; $i++)
				{
					$mybb->user['achivements'][$table][$i]['showprofile'] = 0;
				}
			}
			if($mybb->input['showachivement'])
			{
				foreach($mybb->input['showachivement'] as $achs)
				{
					$newlogro = unserialize($achs);
					if($mybb->user['achivements'][$newlogro['table']][$newlogro['id']][$newlogro['table']] == $newlogro['id'])
					{
						$mybb->user['achivements'][$newlogro['table']][$newlogro['id']]['showprofile'] = 1;
					}
				}
			}
		}
		if($mybb->input['postbit'])
		{
			foreach($tables as $table)
			{
				$count = count($mybb->user['achivements'][$table]);
				for($i = 1; $i <= $count; $i++)
				{
					$mybb->user['achivements'][$table][$i]['showpostbit'] = 0;
				}
			}
			if($mybb->input['showachivement'])
			{
				$count = 0;
				foreach($mybb->input['showachivement'] as $achs)
				{
					$newlogro = unserialize($achs);
					if($mybb->user['achivements'][$newlogro['table']][$newlogro['id']][$newlogro['table']] == $newlogro['id'])
					{
						$mybb->user['achivements'][$newlogro['table']][$newlogro['id']]['showpostbit'] = 1;
						++$count;
					}
					if($count >= $mybb->settings['achivements_maxpostbit'])
						break;
					else
						continue;
				}
			}
		}
		$logros_obtenidos = serialize($mybb->user['achivements']);
		$db->update_query('users', array('achivements' => $logros_obtenidos), 'uid=\''.$mybb->user['uid'].'\'');
		redirect("usercp.php", $lang->redirect_profileupdated);
	}
	if($errors)
	{
		$errors = inline_error($errors);
	}
	$lang->load("achivements", false, true);
	add_breadcrumb($lang->nav_usercp, "usercp.php");
	add_breadcrumb($lang->achivements, "usercp.php");
	
	static $static_achievements;
	$mybb->user['achivements'] = unserialize($mybb->user['achivements']);
	
	if(!$mybb->user['achivements'])
	{
		$achivements = "<span class='smalltext'>{$lang->achisemptytableprofileusercp}</span>";
		$currentachivements = "<span class='smalltext'>{$lang->achisemptytableprofileusercp}</span>";
		$currentachivementspostbit = "<span class='smalltext'>{$lang->achisemptytableprofileusercp}</span>";
	}	
	else
	{
		$intshow = 0;
		$intshowpostbit = 0;
		$currentachivements = "<small>{$lang->achivementsshowprofilecurrent}</small><br />";
		$lang->achivementsshowpostbitcurrent = $lang->sprintf($lang->achivementsshowpostbitcurrent, $mybb->settings['achivements_maxpostbit']);
		$currentachivementspostbit = "<small>{$lang->achivementsshowpostbitcurrent}</small><br />";
		
		
		$countachs = 1;
		$postbit = 0;
		foreach($mybb->user['achivements'] as $column => $logros)
		{
			foreach($logros as $logro)
			{
				if($logro['showprofile'] == 1)
				{
					$intshow = 1;
					$currentachivements .= "<img src=\"".htmlspecialchars_uni($logro['image'])."\" title=\"".htmlspecialchars_uni($logro['name'])."\" /> ";
				}
				if($logro['showpostbit'] == 1 && $postbit == 0)
				{
					$intshowpostbit = 1;
					$currentachivementspostbit .= "<img src=\"".htmlspecialchars_uni($logro['image'])."\" title=\"".htmlspecialchars_uni($logro['name'])."\" /> ";
					if($countachs >= intval($mybb->settings['achivements_maxpostbit']))
					{
						$postbit = 1;
					}else{
						$postbit = 0;
					}
					++$countachs;
				}
			}
		}
		
		if($intshowpostbit == 0)
		{
			$currentachivementspostbit = "<small>{$lang->notachshowpostbit}</small>";
		}
		if($intshow == 0)
		{
			$currentachivements = "<small>{$lang->notachshowprofile}</small>";
		}
		foreach($mybb->user['achivements'] as $column => $logros)
		{
			foreach($logros as $logro)
			{
				$id = '';
				if($logro['apid'])
				{
					$id = array('table' => 'apid', 'id' => $logro['apid']);
				}
				elseif($logro['atid'])
				{
					$id = array('table' => 'atid', 'id' => $logro['atid']);
				}
				elseif($logro['arid'])
				{
					$id = array('table' => 'arid', 'id' => $logro['arid']);
				}
				elseif($logro['toid'])
				{
					$id = array('table' => 'toid', 'id' => $logro['toid']);
				}
				elseif($logro['rgid'])
				{
					$id = array('table' => 'rgid', 'id' => $logro['rgid']);
				}
				elseif($logro['acid'])
				{
					$id = array('table' => 'acid', 'id' => $logro['acid']);
				}
				$id = @serialize($id);
				$id = htmlspecialchars($id);
				$achivement .= "<img src=\"".htmlspecialchars_uni($logro['image'])."\" title=\"".htmlspecialchars_uni($logro['name'])."\" /> ";
				eval("\$achivements .= \"".$templates->get("achivements_usercp_all")."\";");
				unset($achivement);
			}
		}
	}
	eval("\$achivementsusercp = \"".$templates->get("achivements_usercp")."\";");
	output_page($achivementsusercp);
}

function modcp_achivements()
{
	global $templates, $modcp_nav, $lang, $mybb, $db;
	global $headerinclude, $header, $errors, $theme, $cache, $footer;
	$lang->load("achivements", false, true);
	$lang->load("admin/achivements", false, true);
	
	if($mybb->settings['achivements_modcp'] == 1)
	{
		eval("\$nav_achivements = \"".$templates->get("achivements_modcp_nav")."\";");
		$modcp_nav = str_replace('<!--modcp_achivements-->', $nav_achivements, $modcp_nav);
	}
	
	if($mybb->input['action'] == "achivements" && $mybb->usergroup['canmodcp'] == 1 && $mybb->settings['achivements_modcp'] == 1)
	{
		add_breadcrumb($lang->nav_modcp, "modcp.php");
		add_breadcrumb($lang->achivements, 'modcp.php?action=achivements');
		if($mybb->input['mod'] == "give")
		{
			$query = $db->simple_select('achivements_custom', '*', "acid='".intval($mybb->input['acid'])."'");
			$custom = $db->fetch_array($query);
			if(!$custom['acid'])
			{
				error_no_permission();
			}
			if($custom['modcp'] == 0)
			{
				error_no_permission();
			}
			if($mybb->request_method == "post")
			{
				$query = $db->simple_select("users", '*', "username='".$db->escape_string($mybb->input['username'])."'");
				$user = $db->fetch_array($query);
				if(!$user['uid'])
				{
					error_no_permission();
				}
				$achivements = unserialize($user['achivements']);
				$acid = intval($custom['acid']);
				
				if(!empty($achivements['acid'][$acid]['acid']))
				{
					$lang->repeatcustom = $lang->sprintf($lang->repeatcustom, $user['username'], $custom['name']);
					redirect("modcp.php?action=achivements", $lang->repeatcustom);
				}
				
				$achivements['acid'][$acid] = array('acid' => $acid, 'name' => $db->escape_string($custom['name']), 'image' => $db->escape_string($custom['image']), 'showprofile' => 1, 'showpostbit' => 0);
				
				$updateuser = array(
					'achivements' => serialize($achivements)
				);
				
				$lang->successachcustom = $lang->sprintf($lang->successachcustom, $custom['name'], $user['username']);
				$db->update_query("users", $updateuser,"uid=".$user['uid']);
				
				$add = array(
					'user' => $mybb->user['uid'],
					'give' => $user['uid'],
					'dateline' => TIME_NOW,
					'log' => 'give',
					'acid' => $custom['acid'],
					'ipaddress' => get_ip()
				);
				$add = serialize($add);
				add_log_custom_modcp($add);
				
				require_once MYBB_ROOT."inc/plugins/achivements/include/mp.php";
				$sendmp = unserialize($mybb->settings['achivements_sendmp']);
				if($sendmp['custom'] == 1)
				{
					send_mp_achivement_new($user['uid'], 'custom', $custom['acid']);
				}
				
				redirect("modcp.php?action=achivements", $lang->successachcustom);
				exit;
			}
			$lang->giveuserform = $lang->sprintf($lang->giveuserform, $custom['name']);
			eval("\$give = \"".$templates->get("achivements_modcp_give")."\";");
			output_page($give);
			exit;
		}
		elseif($mybb->input['mod'] == "quit")
		{
			$query = $db->simple_select('achivements_custom', '*', "acid='".intval($mybb->input['acid'])."'");
			$custom = $db->fetch_array($query);
			if(!$custom['acid'])
			{
				error_no_permission();
			}
			if($custom['modcp'] == 0)
			{
				error_no_permission();
			}
			if($mybb->request_method == "post")
			{
				$query = $db->simple_select("users", '*', "username='".$db->escape_string($mybb->input['username'])."'");
				$user = $db->fetch_array($query);
				if(!$user['uid'])
				{
					error_no_permission();
				}
				$achivements = unserialize($user['achivements']);
				$acid = intval($custom['acid']);
				
				if(empty($achivements['acid'][$acid]['acid']))
				{
					$lang->notcustomuser = $lang->sprintf($lang->notcustomuser, $user['username'], $custom['name']);
					redirect("modcp.php?action=achivements", $lang->notcustomuser);
				}
				
				unset($achivements['acid'][$acid]);
				if(empty($achivements['acid']))
				{
					unset($achivements['acid']);
				}
		
				$updateuser = array(
					'achivements' => serialize($achivements)
				);
				$db->update_query("users", $updateuser,"uid=".$user['uid']);
				
				$add = array(
					'user' => $mybb->user['uid'],
					'revoke' => $user['uid'],
					'dateline' => TIME_NOW,
					'log' => 'revoke',
					'acid' => $custom['acid'],
					'ipaddress' => get_ip()
				);
				$add = serialize($add);
				add_log_custom_modcp($add);
				
				$lang->successachivementcustomdelete = $lang->sprintf($lang->successachivementcustomdelete, $user['username'], $custom['name']);
				redirect("modcp.php?action=achivements", $lang->successachivementcustomdelete);
				exit;
			}
			$lang->quitcustom = $lang->sprintf($lang->quitcustom, $custom['name']);
			eval("\$quit = \"".$templates->get("achivements_modcp_quit")."\";");
			output_page($quit);
			exit;
		}
		
		$query = $db->simple_select('achivements_custom', '*', 'modcp="1"', array('order_by' => 'acid', 'order_dir' => 'DESC'));
		while($custom = $db->fetch_array($query))
		{
			$trow = alt_trow();
			eval("\$custom_achivements .= \"".$templates->get("achivements_modcp_list")."\";");
		}
		if(empty($custom_achivements))
		{
			$custom_achivements = "<tr><td class=\"trow1\" align=\"center\" colspan=\"10\">{$lang->emptycustom_modules}</td></tr>";
		}
		eval("\$modcpachivements = \"".$templates->get("achivements_modcp")."\";");
		output_page($modcpachivements);
	}
}

function add_log_custom_modcp($log)
{
	global $db, $lang;
	$log = unserialize($log);
	$user = get_user(intval($log['user']));
	$custom = getcustom($log['acid']);
	
	if($log['log'] == 'add')
	{
		$lang->logadd = $lang->sprintf($lang->logadd, $custom['name']);
		$loginsert = $lang->logadd;
	}
	elseif($log['log'] == 'quit')
	{
		$lang->logdelete = $lang->sprintf($lang->logdelete, $custom['name']);
		$loginsert = $lang->logdelete;
	}
	elseif($log['log'] == 'give')
	{
		$give = get_user($log['give']);
		$username = format_name($give['username'], $give['usergroup'], $give['displaygroup']);
		$profilelink = build_profile_link($username, $give['uid'], "_blank");
		$lang->loggive = $lang->sprintf($lang->loggive, $custom['name'], $profilelink);
		$loginsert = $lang->loggive;
	}
	elseif($log['log'] == 'revoke')
	{
		$revoke = get_user($log['revoke']);
		$username = format_name($revoke['username'], $revoke['usergroup'], $revoke['displaygroup']);
		$profilelink = build_profile_link($username, $revoke['uid'], "_blank");
		$lang->logrevoke = $lang->sprintf($lang->logrevoke, $custom['name'], $profilelink);
		$loginsert = $lang->logrevoke;
	}
	elseif($log['log'] == 'truncate')
	{
		$db->query("truncate ".TABLE_PREFIX."achivements_customlog");
		$loginsert = $lang->logtruncate;
	}
	
	$lid = $db->insert_id();
	$updatelog = array( 
		"lid"  => $lid,
		"user" => intval($log['user']),
		"log" => $loginsert,
		"dateline" => intval($log['dateline']),
		"ipaddress" => $log['ipaddress']
	); 
	$db->insert_query("achivements_customlog", $updatelog);
}

function getcustom($acid)
{
	global $db;
	$query = $db->simple_select('achivements_custom', '*', "acid='".intval($acid)."'");
	$custom = $db->fetch_array($query);
	$db->free_result($query);
	return $custom;
}

?>