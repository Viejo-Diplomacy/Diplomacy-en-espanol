<?php


/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: achivements.php 2012-04-12 10:58Z EdsonOrdaz $
 */
 
define("IN_MYBB", 1);
define('THIS_SCRIPT', 'achivements.php');
define("KILL_GLOBALS", 1);

require_once "./global.php";
$lang->load("achivements");
add_breadcrumb($lang->achivements);

if($mybb->user['uid'] == 0)
{
	error_no_permission();
}
$ids = array('posts' => 'apid', 'threads' => 'atid', 'reputation' => 'arid', 'timeonline' => 'toid', 'regdate' => 'rgid', 'custom' => 'acid');
$tables = array('posts', 'threads', 'reputation', 'timeonline', 'regdate', 'custom');
foreach ($tables as $table)
{
	$query = $db->simple_select("achivements_".$table, "COUNT(".$ids[$table].") AS ".$ids[$table]."s", '', array('limit' => 1));
	$count = $db->fetch_field($query, $ids[$table]."s");
	if(empty($count))
	{
		switch($table)
		{
			case 'posts':
				$lang->achiviementstableempty = $lang->sprintf($lang->achiviementstableemptydate,$lang->achivementsbyposts);
				eval("\$posts = \"".$templates->get("achivements_empty")."\";");
			break;
			
			case 'threads':
				$lang->achiviementstableempty = $lang->sprintf($lang->achiviementstableemptydate,$lang->achivementsbythreads);
				eval("\$threads = \"".$templates->get("achivements_empty")."\";");
			break;
			
			case 'reputation':
				$lang->achiviementstableempty = $lang->sprintf($lang->achiviementstableemptydate,$lang->achivementsbyreputation);
				eval("\$reputation = \"".$templates->get("achivements_empty")."\";");
			break;
			
			case 'timeonline':
				$lang->achiviementstableempty = $lang->sprintf($lang->achiviementstableemptydate,$lang->achivementsbytimeonline);
				eval("\$timeonline = \"".$templates->get("achivements_empty")."\";");
			break;
			
			case 'regdate':
				$lang->achiviementstableempty = $lang->sprintf($lang->achiviementstableemptydate,$lang->achivementsbyregdate);
				eval("\$regdate = \"".$templates->get("achivements_empty")."\";");
			break;
		}
	}
	else
	{
		$query = $db->simple_select("achivements_".$table, '*', '');
		while($achivements = $db->fetch_array($query))
		{
			$color = alt_trow();
			switch($table)
			{
				case 'posts':
					$value = $achivements['posts'];
					eval("\$posts .= \"".$templates->get("achivements_list")."\";");
					unset($value);
				break;
				
				case 'threads':
					$value = $achivements['threads'];
					eval("\$threads .= \"".$templates->get("achivements_list")."\";");
					unset($value);
				break;
				
				case 'reputation':
					$value = $achivements['reputation'];
					eval("\$reputation .= \"".$templates->get("achivements_list")."\";");
					unset($value);
				break;
				
				case 'timeonline':
					switch($achivements['timeonlinetype'])
					{
						case "hours":
							if($achivements['timeonline'] > 1){
								$online .= " ".$lang->hours;
							}else{
								$online .= " ".$lang->hour;
							}
						break;
						
						case "days":
							if($achivements['timeonline'] > 1){
								$online .= " ".$lang->days;
							}else{
								$online .= " ".$lang->day;
							}
						break;
						
						case "weeks":
							if($achivements['timeonline'] > 1){
								$online .= " ".$lang->weeks;
							}else{
								$online .= " ".$lang->week;
							}
						break;
						
						case "months":
							if($achivements['timeonline'] > 1){
								$online .= " ".$lang->months;
							}else{
								$online .= " ".$lang->month;
							}
						break;
						
						case "years":
							if($achivements['timeonline'] > 1){
								$online .= " ".$lang->years;
							}else{
								$online .= " ".$lang->year;
							}
						break;
					}
					$value = $achivements['timeonline'].$online;
					eval("\$timeonline .= \"".$templates->get("achivements_list")."\";");
					unset($value);
					unset($online);
				break;
				
				case 'regdate':
					switch($achivements['regdatetype'])
					{
						case "hours":
							if($achivements['regdate'] > 1){
								$online .= " ".$lang->hours;
							}else{
								$online .= " ".$lang->hour;
							}
						break;
						
						case "days":
							if($achivements['regdate'] > 1){
								$online .= " ".$lang->days;
							}else{
								$online .= " ".$lang->day;
							}
						break;
						
						case "weeks":
							if($achivements['regdate'] > 1){
								$online .= " ".$lang->weeks;
							}else{
								$online .= " ".$lang->week;
							}
						break;
						
						case "months":
							if($achivements['regdate'] > 1){
								$online .= " ".$lang->months;
							}else{
								$online .= " ".$lang->month;
							}
						break;
						
						case "years":
							if($achivements['regdate'] > 1){
								$online .= " ".$lang->years;
							}else{
								$online .= " ".$lang->year;
							}
						break;
					}
					$value = $achivements['regdate'].$online;
					eval("\$regdate .= \"".$templates->get("achivements_list")."\";");
					unset($value);
					unset($online);
				break;
			}
		}
	}
}
eval("\$page = \"".$templates->get("achivements")."\";");
output_page($page);
?>