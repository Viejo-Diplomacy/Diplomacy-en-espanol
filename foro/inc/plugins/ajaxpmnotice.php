<?php

/*
Ajax PM Notification Plugin for MyBB
Copyright (C) 2010 Sebastian Wunderlich

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

if(!defined('IN_MYBB'))
{
	die();
}

$plugins->add_hook('pre_output_page','ajaxpmnotice');
$plugins->add_hook('ajaxpmnotice_start','ajaxpmnotice_pm');

function ajaxpmnotice_info()
{
	return array
	(
		'name'=>'Ajax PM Notification',
		'description'=>'Checks automatic for new PM in background.',
		'website'=>'http://mods.mybboard.net/view/ajax-pm-notification',
		'author'=>'Sebastian Wunderlich',
		'version'=>'1.8.1',
		'guid'=>'7668a144af9090222cee3e7c11e9c502',
		'compatibility'=>'14*,16*',
		'codename'=>'ajaxpmnotice'
	);
}

function ajaxpmnotice_activate()
{
	global $db;
	$info=ajaxpmnotice_info();
	$setting_group_array=array
	(
		'name'=>$info['codename'],
		'title'=>$info['name'],
		'description'=>'Here you can edit '.$info['name'].' settings.',
		'disporder'=>1,
		'isdefault'=>0
	);
	$db->insert_query('settinggroups',$setting_group_array);
	$group=$db->insert_id();
	$settings=array
	(
		'ajaxpmnotice_refresh'=>array
		(
			'Refresh interval',
			'Set the refresh interval (in milliseconds).',
			'text',
			20000
		)
	);
	$i=1;
	foreach($settings as $name=>$sinfo)
	{
		$insert_array=array
		(
			'name'=>$name,
			'title'=>$db->escape_string($sinfo[0]),
			'description'=>$db->escape_string($sinfo[1]),
			'optionscode'=>$db->escape_string($sinfo[2]),
			'value'=>$db->escape_string($sinfo[3]),
			'gid'=>$group,
			'disporder'=>$i,
			'isdefault'=>0
		);
		$db->insert_query('settings',$insert_array);
		$i++;
	}
	rebuild_settings();
}

function ajaxpmnotice_deactivate()
{
	global $db;
	$info=ajaxpmnotice_info();
	$result=$db->simple_select('settinggroups','gid','name="'.$info['codename'].'"',array('limit'=>1));
	$group=$db->fetch_array($result);
	if(!empty($group['gid']))
	{
		$db->delete_query('settinggroups','gid="'.$group['gid'].'"');
		$db->delete_query('settings','gid="'.$group['gid'].'"');
		rebuild_settings();
	}
}

function ajaxpmnotice($page)
{
	global $mybb;
	if($mybb->user['pmnotice']>0&&$mybb->settings['enablepms']!=0&&$mybb->usergroup['canusepms']!=0&&$mybb->usergroup['canview']!=0)
	{
		$page=str_replace('</head>','<script type="text/javascript">
<!--
function ajaxpmnotice()
{
	new Ajax.Request
	(
		\''.$mybb->settings['bburl'].'/pm.php\',
		{
			method:\'get\',onComplete:function(request)
			{
				$(\'ajaxpmnotice\').innerHTML=request.responseText;
			}
		}
	);
}
ajaxpmnotice();
setInterval("ajaxpmnotice()",'.$mybb->settings['ajaxpmnotice_refresh'].');
// -->
</script>
</head>',$page);
		$page=preg_replace('#<div class="pm_alert" id="pm_notice">(.*)</div>(.*)<br />#Usi','<noscript><div class="pm_alert" id="pm_notice">$1</div><br /></noscript>',$page);
		$page=str_replace('</body>','<div id="ajaxpmnotice"></div></body>',$page);
		return $page;
	}
}

function ajaxpmnotice_pm()
{
	global $mybb,$lang;
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
	header('Content-Type: text/html; charset='.$lang->settings['charset']);
	if($mybb->user['pms_unread']==1)
	{
		define('AJAXPMNOTICE',substr($lang->newpm_notice_one,0,strpos($lang->newpm_notice_one,'</strong>')+9));
	}
	if($mybb->user['pms_unread']>1)
	{
		define('AJAXPMNOTICE',$lang->sprintf(substr($lang->newpm_notice_multiple,0,strpos($lang->newpm_notice_multiple,'</strong>')+9),$mybb->user['pms_unread']));
	}
}

?>