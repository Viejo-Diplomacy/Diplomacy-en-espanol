<?php


/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: install.php 2012-05-27 10:58Z EdsonOrdaz $
 */
 
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

function delete_images_unlink()
{
	global $db;
	$ids_images = array('posts', 'threads', 'reputation', 'timeonline', 'regdate', 'custom', 'achivement');
	$delete_images = array();
	foreach($ids_images as $tables)
	{
		$query = $db->simple_select('achivements_'.$tables);
		while($achivement_img = $db->fetch_array($query))
		{
			$delete_images[] = @unlink(MYBB_ROOT.$achivement_img['image']);
		}
	}
}

function create_task()
{
	$file = fopen(MYBB_ROOT."inc/tasks/achivements.php", 'w');
	fwrite($file, task());
	fclose($file);
}

$tables[] = array('name' => 'achivements_threads', 'insert' => "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."achivements_threads` (
  `atid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `threads` int(10) NOT NULL DEFAULT 0,
  `image` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`atid`)
) ENGINE=MyISAM");

$tables[] = array('name' => 'achivements_posts', 'insert' => "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."achivements_posts` (
  `apid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `posts` int(10) NOT NULL DEFAULT 0,
  `image` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`apid`)
) ENGINE=MyISAM");

$tables[] = array('name' => 'achivements_reputation', 'insert' => "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."achivements_reputation` (
  `arid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `reputation` int(10) NOT NULL DEFAULT 0,
  `image` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`arid`)
) ENGINE=MyISAM");

$tables[] = array('name' => 'achivements_timeonline', 'insert' => "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."achivements_timeonline` (
  `toid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `timeonline` int(10) NOT NULL DEFAULT 0,
  `timeonlinetype` varchar(120) NOT NULL DEFAULT '',
  `image` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`toid`)
) ENGINE=MyISAM");

$tables[] = array('name' => 'achivements_regdate', 'insert' => "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."achivements_regdate` (
  `rgid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `regdate` int(10) NOT NULL DEFAULT 0,
  `regdatetype` varchar(120) NOT NULL DEFAULT '',
  `image` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`rgid`)
) ENGINE=MyISAM");

$tables[] = array('name' => 'achivements_custom', 'insert' => "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."achivements_custom` (
  `acid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `reason` TEXT NOT NULL,
  `image` varchar(250) NOT NULL DEFAULT '',
  `modcp` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`acid`)
) ENGINE=MyISAM");

$tables[] = array('name' => 'achivements_customlog', 'insert' => "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."achivements_customlog` (
  `lid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) NOT NULL,
  `dateline` bigint(30) NOT NULL,
  `log` TEXT NOT NULL,
  `ipaddress` varchar(50) NOT NULL,
  PRIMARY KEY (`lid`)
) ENGINE=MyISAM");

$tables[] = array('name' => 'achivements_achivement', 'insert' => "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."achivements_achivement` (
  `aaid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` TEXT NOT NULL,
  `image` varchar(250) NOT NULL DEFAULT '',
  `achivements` TEXT NOT NULL,
  PRIMARY KEY (`aaid`)
) ENGINE=MyISAM");

$templates[] = array(
	"title"		=> 'achivements',
	"template"	=> $db->escape_string('<html>
<head>
<title>{$mybb->settings[\'bbname\']} - {$lang->achivements}</title>
{$headerinclude}
</head>
<body>
{$header}
<table width="100%" border="0" align="center">
<tr>
<td valign="top">
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="4">
<strong>{$lang->achivementsbyposts}</span></td>
</tr>
<tr>
<td class="tcat" width="10%" align="center"><strong>{$lang->image}</strong></td>
<td class="tcat" width="10%" align="center"><strong>{$lang->posts}</strong></td>
<td class="tcat" width="20%"><strong>{$lang->name}</strong></td>
<td class="tcat"><strong>{$lang->description}</strong></td>
</tr>
{$posts}
</table>
<br />
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="4">
<strong>{$lang->achivementsbythreads}</span></td>
</tr>
<tr>
<td class="tcat" width="10%" align="center"><strong>{$lang->image}</strong></td>
<td class="tcat" width="10%" align="center"><strong>{$lang->threads}</strong></td>
<td class="tcat" width="20%"><strong>{$lang->name}</strong></td>
<td class="tcat"><strong>{$lang->description}</strong></td>
</tr>
{$threads}
</table>
<br />
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="4">
<strong>{$lang->achivementsbyreputation}</span></td>
</tr>
<tr>
<td class="tcat" width="10%" align="center"><strong>{$lang->image}</strong></td>
<td class="tcat" width="10%" align="center"><strong>{$lang->reputation}</strong></td>
<td class="tcat" width="20%"><strong>{$lang->name}</strong></td>
<td class="tcat"><strong>{$lang->description}</strong></td>
</tr>
{$reputation}
</table>
<br />
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="4">
<strong>{$lang->achivementsbytimeonline}</span></td>
</tr>
<tr>
<td class="tcat" width="10%" align="center"><strong>{$lang->image}</strong></td>
<td class="tcat" width="15%" align="center"><strong>{$lang->timeonline}</strong></td>
<td class="tcat" width="20%"><strong>{$lang->name}</strong></td>
<td class="tcat"><strong>{$lang->description}</strong></td>
</tr>
{$timeonline}
</table>
<br />
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="4">
<strong>{$lang->achivementsbyregdate}</span></td>
</tr>
<tr>
<td class="tcat" width="10%" align="center"><strong>{$lang->image}</strong></td>
<td class="tcat" width="15%" align="center"><strong>{$lang->regdate}</strong></td>
<td class="tcat" width="20%"><strong>{$lang->name}</strong></td>
<td class="tcat"><strong>{$lang->description}</strong></td>
</tr>
{$regdate}
</table>
</td>
</tr>
</table>
{$footer}
</body>
</html>'),
	"sid"		=> -1,
	"version"	=> 1604,
	"dateline"	=> TIME_NOW,
);

$templates[] = array(
	"title"		=> 'achivements_empty',
	"template"	=> $db->escape_string('<tr><td class="trow1" colspan="4" align="center">{$lang->achiviementstableempty}</td></tr>'),
	"sid"		=> -1,
	"version"	=> 1604,
	"dateline"	=> TIME_NOW,
);

$templates[] = array(
	"title"		=> 'achivements_list',
	"template"	=> $db->escape_string('<tr>
<td class="{$color}" width="10%" align="center"><img src="{$achivements[\'image\']}" /></td>
<td class="{$color}" width="10%" align="center">{$value}</td>
<td class="{$color}" width="20%">{$achivements[\'name\']}</td>
<td class="{$color}">{$achivements[\'description\']}</td>
</tr>'),
	"sid"		=> -1,
	"version"	=> 1604,
	"dateline"	=> TIME_NOW,
);

$templates[] = array(
	"title"		=> 'achivements_profile',
	"template"	=> $db->escape_string('<br />
<table id="achivements" border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead"><strong>{$lang->achsmemprofile}</strong></td>
</tr>
<tr>
<td class="trow1">
{$achivements}
</td>
</tr>
</table>'),
	"sid"		=> -1,
	"version"	=> 1604,
	"dateline"	=> TIME_NOW,
);

$templates[] = array(
	"title"		=> 'achivements_postbit',
	"template"	=> $db->escape_string('{$lang->achivements}: {$achivements}'),
	"sid"		=> -1,
	"version"	=> 1604,
	"dateline"	=> TIME_NOW,
);

//add templates v2.1
$templates[] = array(
	"title"		=> 'achivements_usercp',
	"template"	=> $db->escape_string('<html>
<head>
<title>{$mybb->settings[\'bbname\']} - {$lang->achivements}</title>
{$headerinclude}

<script languaje="javascript">
function select_all_achivements(){
   for (i=0;i<document.achivements_form.elements.length;i++)
      if(document.achivements_form.elements[i].type == "checkbox")
         document.achivements_form.elements[i].checked=1
}
function unselect_all_achivements(){
   for (i=0;i<document.achivements_form.elements.length;i++)
      if(document.achivements_form.elements[i].type == "checkbox")
         document.achivements_form.elements[i].checked=0
} 
</script>
</head>
<body>
{$header}
<form method="post" name="achivements_form" enctype="multipart/form-data" action="usercp.php">
<input type="hidden" name="my_post_key" value="{$mybb->post_code}" />
<table width="100%" border="0" align="center">
<tr>
{$usercpnav}
<td valign="top">
{$errors}
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead"><strong>{$lang->achivements}</strong></td>
</tr>
<tr>
<td class="tcat"><strong>{$lang->achivementscurrentprofile}</strong></td>
</tr>
<tr>
<td class="trow1">{$currentachivements}</td>
</tr>
<tr>
<td class="tcat"><strong>{$lang->achivementscurrentpostbit}</strong></td>
</tr>
<tr>
<td class="trow1">{$currentachivementspostbit}</td>
</tr>
<tr>
<td class="tcat"><strong>{$lang->myachivements}</strong></td>
</tr>
<tr>
<td class="trow2">
<a href="javascript:select_all_achivements()"><small>{$lang->markall}</small></a> |
<a href="javascript:unselect_all_achivements()"><small>{$lang->marknone}</small></a><br />
{$achivements}</td>
</tr>
</table>
<br />
<div align="center">
<input type="hidden" name="action" value="do_achivements" />
<input type="submit" class="button" name="profile" value="{$lang->showinprofile}" />
<input type="submit" class="button" name="postbit" value="{$lang->showinpostbit}" />
</div>
</td>
</tr>
</table>
</form>
{$footer}
</body>
</html>'),
	"sid"		=> -1,
	"version"	=> 1604,
	"dateline"	=> TIME_NOW,
);

$templates[] = array(
	"title"		=> 'achivements_usercp_all',
	"template"	=> $db->escape_string('<label for="{$logro[\'name\']}"><input type="checkbox" name="showachivement[]" value="{$id}" /> {$achivement}</label>'),
	"sid"		=> -1,
	"version"	=> 1604,
	"dateline"	=> TIME_NOW,
);

$templates[] = array(
	"title"		=> 'achivements_modcp',
	"template"	=> $db->escape_string('<html>
<head>
<title>{$mybb->settings[\'bbname\']} - {$lang->achivements}</title>
{$headerinclude}
</head>
<body>
{$header}
<table width="100%" border="0" align="center">
<tr>
{$modcp_nav}
<td valign="top">
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="4"><strong>{$lang->custom_modules}</strong></td>
</tr>
<tr>
<td class="tcat" width="10%" align="center"><span class="smalltext"><strong>{$lang->image}</strong></span>
<td class="tcat"><span class="smalltext"><strong>{$lang->namedescription}</strong></span>
<td class="tcat" width="20%" align="center" colspan="2"><span class="smalltext"><strong>{$lang->option}</strong></span>
</td>
<tr>
{$custom_achivements}
</tr>
</table>
</td>
</tr>
</table>
{$footer}
</body>
</html>'),
	"sid"		=> -1,
	"version"	=> 1604,
	"dateline"	=> TIME_NOW,
);

$templates[] = array(
	"title"		=> 'achivements_modcp_give',
	"template"	=> $db->escape_string('<html>
<head>
<title>{$mybb->settings[\'bbname\']} - {$lang->giveachivements}</title>
{$headerinclude}
</head>
<body>
{$header}
<table width="100%" border="0" align="center">
<tr>
{$modcp_nav}
<td valign="top">
<form action="modcp.php?action=achivements" method="post">
<input type="hidden" name="my_post_key" value="{$mybb->post_code}" />
<input type="hidden" name="mod" value="{$mybb->input[\'mod\']}" />
<input type="hidden" name="acid" value="{$mybb->input[\'acid\']}" />
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="2"><strong>{$lang->giveuserform}</strong></td>
</tr>
<tr>
<td class="trow1"><strong>{$lang->user}</strong>:</td>
<td class="trow2"><input type="text" class="textbox" id="username" name="username" size="40" maxlength="85"  tabindex="1" /></td>
</table>
<br />
<div style="text-align:center"><input type="submit" class="button" name="submit" value="{$lang->giveachivements}"/></div>
</form>
</td>
</tr>
</table>
{$footer}
<script type="text/javascript" src="jscripts/autocomplete.js?ver=1400"></script>
<script type="text/javascript">
<!--
	if(use_xmlhttprequest == "1")
	{
		new autoComplete("username", "xmlhttp.php?action=get_users", {valueSpan: "username"});
	}
// -->
</script>
</body>
</html>'),
	"sid"		=> -1,
	"version"	=> 1604,
	"dateline"	=> TIME_NOW,
);

$templates[] = array(
	"title"		=> 'achivements_modcp_quit',
	"template"	=> $db->escape_string('<html>
<head>
<title>{$mybb->settings[\'bbname\']} - {$lang->quitachivement}</title>
{$headerinclude}
</head>
<body>
{$header}
<table width="100%" border="0" align="center">
<tr>
{$modcp_nav}
<td valign="top">
<form action="modcp.php?action=achivements" method="post">
<input type="hidden" name="my_post_key" value="{$mybb->post_code}" />
<input type="hidden" name="mod" value="{$mybb->input[\'mod\']}" />
<input type="hidden" name="acid" value="{$mybb->input[\'acid\']}" />
<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder">
<tr>
<td class="thead" colspan="2"><strong>{$lang->quitcustom}</strong></td>
</tr>
<tr>
<td class="trow1"><strong>{$lang->user}</strong>:</td>
<td class="trow2"><input type="text" class="textbox" id="username" name="username" size="40" maxlength="85"  tabindex="1" /></td>
</table>
<br />
<div style="text-align:center"><input type="submit" class="button" name="submit" value="{$lang->quitachivement}"/></div>
</form>
</td>
</tr>
</table>
{$footer}
<script type="text/javascript" src="jscripts/autocomplete.js?ver=1400"></script>
<script type="text/javascript">
<!--
	if(use_xmlhttprequest == "1")
	{
		new autoComplete("username", "xmlhttp.php?action=get_users", {valueSpan: "username"});
	}
// -->
</script>
</body>
</html>'),
	"sid"		=> -1,
	"version"	=> 1604,
	"dateline"	=> TIME_NOW,
);

$templates[] = array(
	"title"		=> 'achivements_modcp_nav',
	"template"	=> $db->escape_string('<tr>
<td class="trow1 smalltext">
<a href="modcp.php?action=achivements" style="display: block;padding: 1px 0 1px 23px;background: url(inc/plugins/achivements/include/images/modcp.png)  no-repeat left center;">{$lang->custom_modules}</a></td>
</tr>'),
	"sid"		=> -1,
	"version"	=> 1604,
	"dateline"	=> TIME_NOW,
);

$templates[] = array(
	"title"		=> 'achivements_modcp_list',
	"template"	=> $db->escape_string('<tr>
<td class="{$trow}" align="center"><img src="{$custom[\'image\']}" title="{$custom[\'name\']}" /></td>
<td class="{$trow}"><strong>{$custom[\'name\']}</strong><br /><span class="smalltext">{$custom[\'reason\']}</span></td>
<td class="{$trow}" width="10%" align="center"><a href="modcp.php?action=achivements&mod=give&acid={$custom[\'acid\']}" />{$lang->give}</a></td>
<td class="{$trow}" width="10%" align="center"><a href="modcp.php?action=achivements&mod=quit&acid={$custom[\'acid\']}" />{$lang->quit}</a></td>
</tr>'),
	"sid"		=> -1,
	"version"	=> 1604,
	"dateline"	=> TIME_NOW,
);

$fields[] = array('table' => 'users', 'column' => 'achivements', 'insert' => 'TEXT NOT NULL;');
$fields[] = array('table' => 'users', 'column' => 'threads', 'insert' => 'int(10) unsigned NOT NULL default \'0\'');

function settings_insert()
{
	global $lang;
	$lang->load('achivements');
	$settings_achivements[] = array(
			"name"			=> "achivements_enable",
			"title"			=> $lang->enable,
			"description"	=> $lang->enable_des,
			"optionscode"	=> "yesno",
			"value"			=> "1",
			"disporder"		=> 1,
			"gid"			=> 0
	);
	$settings_achivements[] = array(
			"name"			=> "achivements_showachvprofile",
			"title"			=> $lang->showachvprofile,
			"description"	=> $lang->showachvprofiledes,
			"optionscode"	=> "yesno",
			"value"			=> "1",
			"disporder"		=> 2,
			"gid"			=> 0
	);
	$settings_achivements[] = array(
			"name"			=> "achivements_showachvpostbit",
			"title"			=> $lang->showachvpostbit,
			"description"	=> $lang->showachvpostbitdes,
			"optionscode"	=> "yesno",
			"value"			=> "1",
			"disporder"		=> 3,
			"gid"			=> 0
	);
	$settings_achivements[] = array(
			"name"			=> "achivements_sendmp",
			"title"			=> $lang->sendmpachivements,
			"description"	=> $lang->sendmpachivements_des,
			"optionscode"	=> "textarea",
			"value"			=> "",
			"disporder"		=> 4,
			"gid"			=> 0
	);
	$settings_achivements[] = array(
			"name"			=> "achivements_subjectmp",
			"title"			=> $lang->titlemp,
			"description"	=> $lang->titlempdes,
			"optionscode"	=> "textarea",
			"value"			=> $lang->subjectvalue,
			"disporder"		=> 5,
			"gid"			=> 0
	);
	$settings_achivements[] = array(
			"name"			=> "achivements_bodymp",
			"title"			=> $lang->bodymp,
			"description"	=> $lang->bodympdes,
			"optionscode"	=> "textarea",
			"value"			=> $lang->bodyvalue,
			"disporder"		=> 6,
			"gid"			=> 0
	);
	$settings_achivements[] = array(
			"name"			=> "achivements_usermp",
			"title"			=> $lang->user,
			"description"	=> $lang->usersendmp,
			"optionscode"	=> "text",
			"value"			=> "1",
			"disporder"		=> 7,
			"gid"			=> 0
	);
	$settings_achivements[] = array(
			"name"			=> "achivements_rebuild",
			"title"			=> $lang->rebuild,
			"description"	=> $lang->rebuild,
			"optionscode"	=> "yesno",
			"value"			=> "0",
			"disporder"		=> 8,
			"gid"			=> 0
	);
	$settings_achivements[] = array(
			"name"			=> "achivements_maxpostbit",
			"title"			=> $lang->maxpostbit,
			"description"	=> $lang->maxpostbitdes,
			"optionscode"	=> "text",
			"value"			=> "15",
			"disporder"		=> 9,
			"gid"			=> 0
	);
	$settings_achivements[] = array(
			"name"			=> "achivements_taskuseroffline",
			"title"			=> $lang->taskoffline,
			"description"	=> $lang->taskofflinedes,
			"optionscode"	=> "yesno",
			"value"			=> "1",
			"disporder"		=> 10,
			"gid"			=> 0
	);
	$settings_achivements[] = array(
			"name"			=> "achivements_modcp",
			"title"			=> $lang->canmodcpachs,
			"description"	=> $lang->canmodcpachsdes,
			"optionscode"	=> "yesno",
			"value"			=> "1",
			"disporder"		=> 11,
			"gid"			=> 0
	);
	return $settings_achivements;
}

function count_threads_update()
{
	global $db;
	$query = $db->simple_select("users", "uid");
	while($user = $db->fetch_array($query))
	{
		$users[$user['uid']] = $user;
	}
	foreach($users as $user)
	{
		$query = $db->simple_select("threads", "COUNT(tid) AS threads", "uid = '".$user['uid']."'");
		$threads_count = intval($db->fetch_field($query, "threads"));
		$db->update_query("users", array("threads" => $threads_count), "uid = '".$user['uid']."'");
	}
}

function create_task_tools()
{
	global $db, $lang;
	$lang->load('achivements');
	$new_task_achivements = array(
		"title" => "Achivements",
		"description" => $lang->desctaks,
		"file" => "achivements",
		"minute" => '0',
		"hour" => '0',
		"day" => '*',
		"month" => '*',
		"weekday" => '*',
		"nextrun" => TIME_NOW + (1*24*60*60),
		"enabled" => '1',
		"logging" => '1'
	);
	$db->insert_query("tasks", $new_task_achivements);
}

function task()
{	
	$task = <<<TASK_ACHIVEMENTS
<?php

/**
 * MyBB 1.6
 * Copyright 2012 Edson Ordaz, All Rights Reserved
 *
 * Email: nicedo_eeos@hotmail.com
 * WebSite: http://www.mybb-es.com
 *
 * \$Id: achivements.php 2012-05-27Z Edson Ordaz \$
 *
 * UPDATED: Version 2.4
 * 
 * + Pone todos los logros (de postbit y de perfil) como
 *   visibles para todo el foro (tomando en cuenta que los del 
 *   postbit solo mostrara los que el administrador tiene permitido.
 * + Se agrega nueva configuracion para dar logros a usuarios inactivos.
 * + Se dan logros por logros
 */

function task_achivements(\$task)
{
	global \$db, \$mybb;
	if(\$mybb->settings['achivements_enable'] == 1)
	{
		require_once MYBB_ROOT."inc/plugins/achivements/include/mp.php";
		\$cells = array('posts', 'threads', 'reputation', 'timeonline', 'regdate', 'achivement');
		\$posts_id = array();
		\$threads_id = array();
		\$reputation_id = array();
		\$timeonline_id = array();
		\$regdate_id = array();
		\$achivement_id = array();
		foreach (\$cells as \$type)
		{
			\$query = \$db->simple_select('achivements_'.\$type);
			while (\$achivement = \$db->fetch_array(\$query))
			{
				switch (\$type)
				{
					case 'posts':
						\$posts_id[\$achivement['apid']] = \$achivement;
					break;
					
					case 'threads':
						\$threads_id[\$achivement['atid']] = \$achivement;
					break;
					
					case 'reputation':
						\$reputation_id[\$achivement['arid']] = \$achivement;
					break;
					
					case 'timeonline':
						\$timeonline_id[\$achivement['toid']] = \$achivement;
					break;
					
					case 'regdate':
						\$regdate_id[\$achivement['rgid']] = \$achivement;
					break;
					
					case 'achivement':
						\$achivement_id[\$achivement['aaid']] = \$achivement;
					break;
				}
			}
			\$db->free_result(\$query);
		}
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
		\$countachivements = 0;
		\$countusers = 0;
		foreach (\$users as \$uid => \$user)
		{
			\$sendmp = unserialize(\$mybb->settings['achivements_sendmp']);
			\$logros_obtenidos = unserialize(\$user['achivements']);
			
			foreach(\$posts_id as \$apid => \$achivement)
			{
				if (!isset(\$logros_obtenidos['apid'][\$apid]) || empty(\$logros_obtenidos['apid'][\$apid]))
				{
					if(\$achivement['posts'] <= intval(\$user['postnum']))
					{
						++\$countachivements;
						\$logros_obtenidos['apid'][\$apid] = array('apid' => intval(\$achivement['apid']), 'name' => \$db->escape_string(\$achivement['name']), 'image' => \$db->escape_string(\$achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if(\$sendmp['posts'] == 1)
						{
							send_mp_achivement_new(\$uid, 'posts', \$achivement['apid']);
						}
					}
				}
			}
			foreach(\$threads_id as \$atid => \$achivement)
			{
				if (!isset(\$logros_obtenidos['atid'][\$atid]) || empty(\$logros_obtenidos['atid'][\$atid]))
				{
					if (\$achivement['threads'] <= intval(\$user['threads']))
					{
						++\$countachivements;
						\$logros_obtenidos['atid'][\$atid] = array('atid' => intval(\$achivement['atid']), 'name' => \$db->escape_string(\$achivement['name']), 'image' => \$db->escape_string(\$achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if(\$sendmp['threads'] == 1)
						{
							send_mp_achivement_new(\$uid, 'threads', \$achivement['atid']);
						}
					}
				}
			}
			foreach(\$reputation_id as \$arid => \$achivement)
			{
				if (!isset(\$logros_obtenidos['arid'][\$arid]) || empty(\$logros_obtenidos['arid'][\$arid]))
				{
					if (\$achivement['reputation'] <= intval(\$user['reputation']))
					{
						++\$countachivements;
						\$logros_obtenidos['arid'][\$arid] = array('arid' => intval(\$achivement['arid']), 'name' => \$db->escape_string(\$achivement['name']), 'image' => \$db->escape_string(\$achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if(\$sendmp['reputation'] == 1)
						{
							send_mp_achivement_new(\$uid, 'reputation', \$achivement['arid']);
						}
					}
				}
			}
			foreach(\$timeonline_id as \$toid => \$achivement)
			{
				if (!isset(\$logros_obtenidos['toid'][\$toid]) || empty(\$logros_obtenidos['toid'][\$toid]))
				{
					switch(\$achivement['timeonlinetype'])
					{
						case "hours":
							\$timeonline = \$achivement['timeonline']*60*60;
							break;
						case "days":
							\$timeonline = \$achivement['timeonline']*60*60*24;
							break;
						case "weeks":
							\$timeonline = \$achivement['timeonline']*60*60*24*7;
						case "months":
							\$timeonline = \$achivement['timeonline']*60*60*24*30;
							break;
						case "years":
							\$timeonline = \$achivement['timeonline']*60*60*24*365;
							break;
					}
					if(\$timeonline <= intval(\$user['timeonline']))
					{
						++\$countachivements;
						\$logros_obtenidos['toid'][\$toid] = array('toid' => intval(\$achivement['toid']), 'name' => \$db->escape_string(\$achivement['name']), 'image' => \$db->escape_string(\$achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if(\$sendmp['timeonline'] == 1)
						{
							send_mp_achivement_new(\$uid, 'timeonline', \$achivement['toid']);
						}
					}
				}
			}
			foreach(\$regdate_id as \$rgid => \$achivement)
			{
				if (!isset(\$logros_obtenidos['rgid'][\$rgid]) || empty(\$logros_obtenidos['rgid'][\$rgid]))
				{
					switch(\$achivement['regdatetype'])
					{
						case "hours":
							\$regdate = \$achivement['regdate']*60*60;
							break;
						case "days":
							\$regdate = \$achivement['regdate']*60*60*24;
							break;
						case "weeks":
							\$regdate = \$achivement['regdate']*60*60*24*7;
						case "months":
							\$regdate = \$achivement['regdate']*60*60*24*30;
							break;
						case "years":
							\$regdate = \$achivement['regdate']*60*60*24*365;
							break;
					}
					\$rdate = TIME_NOW - \$regdate;
					if(intval(\$user['regdate']) <= \$rdate)
					{
						++\$countachivements;
						\$logros_obtenidos['rgid'][\$rgid] = array('rgid' => intval(\$achivement['rgid']), 'name' => \$db->escape_string(\$achivement['name']), 'image' => \$db->escape_string(\$achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if(\$sendmp['regdate'] == 1)
						{
							send_mp_achivement_new(\$uid, 'regdate', \$achivement['rgid']);
						}
					}
				}
			}
			foreach(\$achivement_id as \$aaid => \$achivement)
			{
				\$achs = unserialize(\$achivement['achivements']);
				\$error = 0;
				if(!empty(\$achs))
				{
					foreach(\$achs as \$column => \$ach)
					{
						foreach(\$ach as \$id => \$logro)
						{
							if(intval(\$logro[\$column]) != \$logros_obtenidos[\$column][\$id][\$column])
							{
								++\$error;
							}
						}
					}
				}
				else
				{
					if(!isset(\$logros_obtenidos['aaid'][\$aaid]) || empty(\$logros_obtenidos['aaid'][\$aaid]))
					{
						++\$countachivements;
						\$logros_obtenidos['aaid'][\$aaid] = array('aaid' => intval(\$achivement['aaid']), 'name' => \$db->escape_string(\$achivement['name']), 'image' => \$db->escape_string(\$achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if(\$sendmp['achivements'] == 1)
						{
							send_mp_achivement_new(\$uid, 'achivement', \$achivement['aaid']);
						}
					}
				}
				if(\$error == 0)
				{
					if(!isset(\$logros_obtenidos['aaid'][\$aaid]) || empty(\$logros_obtenidos['aaid'][\$aaid]))
					{
						++\$countachivements;
						\$logros_obtenidos['aaid'][\$aaid] = array('aaid' => intval(\$achivement['aaid']), 'name' => \$db->escape_string(\$achivement['name']), 'image' => \$db->escape_string(\$achivement['image']), 'showprofile' => 1, 'showpostbit' => 1);
						if(\$sendmp['achivements'] == 1)
						{
							send_mp_achivement_new(\$uid, 'achivement', \$achivement['aaid']);
						}
					}
				}
			}
			
			\$logros_obtenidos = serialize(\$logros_obtenidos);
			\$db->update_query('users', array('achivements' => \$logros_obtenidos), 'uid=\''.\$uid.'\'');
			++\$countusers;
		}
		\$tasklog = "Se han repartido logros a {\$countusers} usuarios. se dieron {\$countachivements} medallas en total.";
	}else{
		\$tasklog = "Reparto de medallas esta desactivado.";
	}
	add_task_log(\$task, \$tasklog);
}
?>
TASK_ACHIVEMENTS;
	create_task_tools();
	return $task;
}

?>