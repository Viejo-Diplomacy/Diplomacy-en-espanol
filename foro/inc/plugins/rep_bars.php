<?php
/*********************************************************************************************
+ Reputation Bars v0.3 : A Plugin for MyBB 1.4 and 1.6
+ Free to Use
+ Free to Edit
+ But Not Allowed to distribute
-----
Update 0.2: Fix an small bug. Also added Reputation page link to the Image Bars.
Update 0.3: Updated to be use for MyBB 1.6.5
**********************************************************************************************
*/
if(!defined("IN_MYBB")){
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
function rep_bars_info(){
	return array(		"name"			=> "Reputation Bars",		"description"	=> "Change Reputation number to Bars.",		"website"		=> "http://yaldaram.com",		"author"		=> "Yaldaram",		"authorsite"	=> "http://yaldaram.com",		"version"		=> "0.2",		"compatibility" => "14*,16*", "guid" => "ee481a454ca5e0f54624425f2062bd1d"	);
}
function rep_bars_activate(){
	global $db, $mybb;
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	$template = array(
		"title"		=> "rep_bars",
		"template"	=> '<span title="{$post[\\\'username\\\']} has {$rep_points} points."><a href="reputation.php?uid={$post[\\\'uid\\\']}">{$rep_bars}</a></span>',
		"sid"		=> -1
	);
	$db->insert_query("templates", $template);
}
function rep_bars_deactivate(){
	global $db, $mybb;
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='rep_bars'");
    rebuild_settings();
}
$plugins->add_hook("postbit", "rep_bars");
function rep_bars(&$post){
	global $mybb, $templates;
	if ($post['uid'] != "0"){
		if ($post['reputation'] < "0"){
			$rep_bars = '<img src="images/rep_bars/rep_neg.png">';
		}
		else if ($post['reputation'] == "0"){
			$rep_bars = '<img src="images/rep_bars/rep_neu.png">';
		}
		else if ($post['reputation'] > "0" && $post['reputation'] <= "10"){
			$rep_bars = '<img src="images/rep_bars/rep_pos.png">';
		}
		else if ($post['reputation'] >= "11" && $post['reputation'] <= "20"){
			$rep_bars = '<img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png">';
		}
		else if ($post['reputation'] >= "21" && $post['reputation'] <= "30"){
			$rep_bars = '<img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png">';
		}
		else if ($post['reputation'] >= "31" && $post['reputation'] <= "40"){
			$rep_bars = '<img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png">';
		}
		else if ($post['reputation'] >= "41" && $post['reputation'] <= "50"){
			$rep_bars = '<img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png">';
		}
		else if ($post['reputation'] >= "51" && $post['reputation'] <= "60"){
			$rep_bars = '<img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png">';
		}
		else if ($post['reputation'] >= "61" && $post['reputation'] <= "70"){
			$rep_bars = '<img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png">';
		}
		else if ($post['reputation'] >= "71" && $post['reputation'] <= "80"){
			$rep_bars = '<img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png">';
		}
		else if ($post['reputation'] >= "81" && $post['reputation'] <= "90"){
			$rep_bars = '<img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png">';
		}
		else if ($post['reputation'] > "90"){
			$rep_bars = '<img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png"><img src="images/rep_bars/rep_pos.png">';
		}
		$rep_points = $post['reputation'];
		eval("\$post['rep_bars'] = \"".$templates->get("rep_bars")."\";");
		$post['user_details'] = str_replace($post['userreputation'], $post['rep_bars'], $post['user_details']);
		return $post;
	}
}
?>