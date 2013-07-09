<?php

/**
 * MyBB 1.6
 * Copyright 2012 Edson Ordaz, All Rights Reserved
 *
 * Email: nicedo_eeos@hotmail.com
 * WebSite: http://www.mybb-es.com
 *
 * $Id: achivements.php 2012-05-27Z Edson Ordaz $
 *
 * UPDATED: Version 2.4
 * 
 * + Pone todos los logros (de postbit y de perfil) como
 *   visibles para todo el foro (tomando en cuenta que los del 
 *   postbit solo mostrara los que el administrador tiene permitido.
 * + Se agrega nueva configuracion para dar logros a usuarios inactivos.
 * + Se dan logros por logros
 */

function task_achivements($task)
{
	global $db, $mybb;
	if($mybb->settings['achivements_enable'] == 1)
	{
		require_once MYBB_ROOT."inc/plugins/achivements/include/mp.php";
		$cells = array('posts', 'threads', 'reputation', 'timeonline', 'regdate', 'achivement');
		$posts_id = array();
		$threads_id = array();
		$reputation_id = array();
		$timeonline_id = array();
		$regdate_id = array();
		$achivement_id = array();
		foreach ($cells as $type)
		{
			$query = $db->simple_select('achivements_'.$type);
			while ($achivement = $db->fetch_array($query))
			{
				switch ($type)
				{
					case 'posts':
						$posts_id[$achivement['apid']] = $achivement;
					break;
					
					case 'threads':
						$threads_id[$achivement['atid']] = $achivement;
					break;
					
					case 'reputation':
						$reputation_id[$achivement['arid']] = $achivement;
					break;
					
					case 'timeonline':
						$timeonline_id[$achivement['toid']] = $achivement;
					break;
					
					case 'regdate':
						$regdate_id[$achivement['rgid']] = $achivement;
					break;
					
					case 'achivement':
						$achivement_id[$achivement['aaid']] = $achivement;
					break;
				}
			}
			$db->free_result($query);
		}
		$users = array();
		$taskusersoffline = '';
		if($mybb->settings['achivements_taskuseroffline'] == 0)
		{
			$taskusersoffline = "lastactive >= '{$task['lastrun']}'";
		}
		$query = $db->simple_select("users", "*", $taskusersoffline);
		while($user = $db->fetch_array($query))
		{
			$users[$user['uid']] = $user;
		}
		$countachivements = 0;
		$countusers = 0;
		foreach ($users as $uid => $user)
		{
			$sendmp = unserialize($mybb->settings['achivements_sendmp']);
			$logros_obtenidos = unserialize($user['achivements']);
			
			foreach($posts_id as $apid => $achivement)
			{
				if (!isset($logros_obtenidos['apid'][$apid]) || empty($logros_obtenidos['apid'][$apid]))
				{
					if($achivement['posts'] <= intval($user['postnum']))
					{
						++$countachivements;
						$logros_obtenidos['apid'][$apid] = array('apid' => intval($achivement['apid']), 'name' => $db->escape_string($achivement['name']), 'image' => $db->escape_string($achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if($sendmp['posts'] == 1)
						{
							send_mp_achivement_new($uid, 'posts', $achivement['apid']);
						}
					}
				}
			}
			foreach($threads_id as $atid => $achivement)
			{
				if (!isset($logros_obtenidos['atid'][$atid]) || empty($logros_obtenidos['atid'][$atid]))
				{
					if ($achivement['threads'] <= intval($user['threads']))
					{
						++$countachivements;
						$logros_obtenidos['atid'][$atid] = array('atid' => intval($achivement['atid']), 'name' => $db->escape_string($achivement['name']), 'image' => $db->escape_string($achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if($sendmp['threads'] == 1)
						{
							send_mp_achivement_new($uid, 'threads', $achivement['atid']);
						}
					}
				}
			}
			foreach($reputation_id as $arid => $achivement)
			{
				if (!isset($logros_obtenidos['arid'][$arid]) || empty($logros_obtenidos['arid'][$arid]))
				{
					if ($achivement['reputation'] <= intval($user['reputation']))
					{
						++$countachivements;
						$logros_obtenidos['arid'][$arid] = array('arid' => intval($achivement['arid']), 'name' => $db->escape_string($achivement['name']), 'image' => $db->escape_string($achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if($sendmp['reputation'] == 1)
						{
							send_mp_achivement_new($uid, 'reputation', $achivement['arid']);
						}
					}
				}
			}
			foreach($timeonline_id as $toid => $achivement)
			{
				if (!isset($logros_obtenidos['toid'][$toid]) || empty($logros_obtenidos['toid'][$toid]))
				{
					switch($achivement['timeonlinetype'])
					{
						case "hours":
							$timeonline = $achivement['timeonline']*60*60;
							break;
						case "days":
							$timeonline = $achivement['timeonline']*60*60*24;
							break;
						case "weeks":
							$timeonline = $achivement['timeonline']*60*60*24*7;
						case "months":
							$timeonline = $achivement['timeonline']*60*60*24*30;
							break;
						case "years":
							$timeonline = $achivement['timeonline']*60*60*24*365;
							break;
					}
					if($timeonline <= intval($user['timeonline']))
					{
						++$countachivements;
						$logros_obtenidos['toid'][$toid] = array('toid' => intval($achivement['toid']), 'name' => $db->escape_string($achivement['name']), 'image' => $db->escape_string($achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if($sendmp['timeonline'] == 1)
						{
							send_mp_achivement_new($uid, 'timeonline', $achivement['toid']);
						}
					}
				}
			}
			foreach($regdate_id as $rgid => $achivement)
			{
				if (!isset($logros_obtenidos['rgid'][$rgid]) || empty($logros_obtenidos['rgid'][$rgid]))
				{
					switch($achivement['regdatetype'])
					{
						case "hours":
							$regdate = $achivement['regdate']*60*60;
							break;
						case "days":
							$regdate = $achivement['regdate']*60*60*24;
							break;
						case "weeks":
							$regdate = $achivement['regdate']*60*60*24*7;
						case "months":
							$regdate = $achivement['regdate']*60*60*24*30;
							break;
						case "years":
							$regdate = $achivement['regdate']*60*60*24*365;
							break;
					}
					$rdate = TIME_NOW - $regdate;
					if(intval($user['regdate']) <= $rdate)
					{
						++$countachivements;
						$logros_obtenidos['rgid'][$rgid] = array('rgid' => intval($achivement['rgid']), 'name' => $db->escape_string($achivement['name']), 'image' => $db->escape_string($achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if($sendmp['regdate'] == 1)
						{
							send_mp_achivement_new($uid, 'regdate', $achivement['rgid']);
						}
					}
				}
			}
			foreach($achivement_id as $aaid => $achivement)
			{
				$achs = unserialize($achivement['achivements']);
				$error = 0;
				if(!empty($achs))
				{
					foreach($achs as $column => $ach)
					{
						foreach($ach as $id => $logro)
						{
							if(intval($logro[$column]) != $logros_obtenidos[$column][$id][$column])
							{
								++$error;
							}
						}
					}
				}
				else
				{
					if(!isset($logros_obtenidos['aaid'][$aaid]) || empty($logros_obtenidos['aaid'][$aaid]))
					{
						++$countachivements;
						$logros_obtenidos['aaid'][$aaid] = array('aaid' => intval($achivement['aaid']), 'name' => $db->escape_string($achivement['name']), 'image' => $db->escape_string($achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if($sendmp['achivements'] == 1)
						{
							send_mp_achivement_new($uid, 'achivement', $achivement['aaid']);
						}
					}
				}
				if($error == 0)
				{
					if(!isset($logros_obtenidos['aaid'][$aaid]) || empty($logros_obtenidos['aaid'][$aaid]))
					{
						++$countachivements;
						$logros_obtenidos['aaid'][$aaid] = array('aaid' => intval($achivement['aaid']), 'name' => $db->escape_string($achivement['name']), 'image' => $db->escape_string($achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if($sendmp['achivements'] == 1)
						{
							send_mp_achivement_new($uid, 'achivement', $achivement['aaid']);
						}
					}
				}
			}
			
			$logros_obtenidos = serialize($logros_obtenidos);
			$db->update_query('users', array('achivements' => $logros_obtenidos), 'uid=\''.$uid.'\'');
			++$countusers;
		}
		$tasklog = "Se han repartido logros a {$countusers} usuarios. se dieron {$countachivements} medallas en total.";
	}else{
		$tasklog = "Reparto de medallas esta desactivado.";
	}
	add_task_log($task, $tasklog);
}
?>