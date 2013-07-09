<?php
/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: task.php 2012-04-15 10:58Z EdsonOrdaz $
 */
 
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

function create_task()
{
	$file = fopen(MYBB_ROOT."inc/tasks/ranks.php", 'w');
	fwrite($file, task());
	fclose($file);
}

function create_task_tools()
{
	global $db, $lang;
	$lang->load('ranks');
	$new_task_ranks = array(
		"title" => "Ranks",
		"description" => $lang->ranks_plug_description,
		"file" => "ranks",
		"minute" => '0',
		"hour" => '0',
		"day" => '*',
		"month" => '*',
		"weekday" => '*',
		"nextrun" => TIME_NOW + (1*24*60*60),
		"enabled" => '1',
		"logging" => '1'
	);
	$db->insert_query("tasks", $new_task_ranks);
}

function task()
{	
	$task = <<<TASK_RANKS
	<?php
/**
 * MyBB 1.6
 * Copyright 2012 Edson Ordaz, All Rights Reserved
 *
 * Email: nicedo_eeos@hotmail.com
 * WebSite: http://www.mybb-es.com
 *
 * \$Id: ranks.php 2012-05-16Z Edson Ordaz \$
 */
 
function task_ranks(\$task)
{
	global \$db, \$mybb, \$lang;
	require_once MYBB_ROOT."inc/plugins/achivements.php";
	\$ranks = array();
	\$query = \$db->simple_select("ranks", '*', '', array('order_by' => 'level', 'order_dir' => 'desc'));
	while(\$rank = \$db->fetch_array(\$query))
	{
		\$ranks[\$rank['rid']] = \$rank;
	}
	\$db->free_result(\$query);
	
	\$users = array();
	\$taskusersoffline = '';
	if(\$mybb->settings['achivements_taskuseroffline'] == 0)
	{
		\$taskusersoffline = "lastactive >= '{\$task['lastrun']}'";
	}
	\$query = \$db->simple_select("users", "*", \$taskusersoffline);
	while(\$user = \$db->fetch_array(\$query))
	{
		\$users[\$user['uid']] = \$user;
	}
	\$countusers = 0;
	\$achivements = achivements_get();
	foreach(\$users as \$uid => \$user)
	{
		\$logros = @unserialize(\$user['achivements']);
		\$error = 0;
		\$rid = 0;
		foreach(\$ranks as \$rank)
		{
			if(\$logros['apid'][\$rank['apid']]['apid'] != \$achivements['apid'][\$rank['apid']]['apid'])
			{
				++\$error;
			}
			if(\$logros['atid'][\$rank['atid']]['atid'] != \$achivements['atid'][\$rank['atid']]['atid'])
			{
				++\$error;
			}
			if(\$logros['arid'][\$rank['arid']]['arid'] != \$achivements['arid'][\$rank['arid']]['arid'])
			{
				++\$error;
			}
			if(\$logros['toid'][\$rank['toid']]['toid'] != \$achivements['toid'][\$rank['toid']]['toid'])
			{
				++\$error;
			}
			if(\$logros['rgid'][\$rank['rgid']]['rgid'] != \$achivements['rgid'][\$rank['rgid']]['rgid'])
			{
				++\$error;
			}
			if(\$error == 0)
			{
				\$rid = intval(\$rank['rid']);
				break;
			}
			elseif(\$error > 0)
			{
				\$error = 0;
				continue;
			}
		}
		if(\$error == 0)
		{
			\$db->update_query('users', array('rank' => \$rid), 'uid=\''.\$uid.'\'');
			++\$countusers;
		}
	}
	\$lang->tasklogranks = \$lang->sprintf('have been changed to {1} users range', \$countusers);
	add_task_log(\$task, \$lang->tasklogranks);
}
?>
TASK_RANKS;
	create_task_tools();
	return $task;
}

?>