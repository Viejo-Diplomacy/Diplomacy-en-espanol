<?php
/**
 * Advanced WYSIWYG 1.2
 * Copyright 2011 Codicious, All Rights Reserved
 *
 * Website: http://codicious.com/
 */
 
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("pre_output_page", "wysiwyg_load_editor");
$plugins->add_hook("pre_output_page", "wysiwyg_load_editor_quickreply");
$plugins->add_hook('admin_style_action_handler','wysiwyg_admin_action');
$plugins->add_hook('admin_style_menu','wysiwyg_admin_menu');
$plugins->add_hook("admin_config_settings_change", "wysiwyg_settings");
$plugins->add_hook("admin_config_settings_start", "wysiwyg_settings");

/**
 * Returns plugin info
 *
 * @return array Plugin Info
 */
function wysiwyg_info()
{
    global $lang;

    $lang->load('style_editorthemes');
	
	return array(
		"name"			=> $lang->mybb_wysiwyg,
		"description"	=> "<img src=\"../inc/plugins/wysiwyg/icon.png\" style=\"margin-right: 5px;\" align=\"left\">{$lang->mybb_wysiwyg_desc}",
		"website"		=> "http://marketplace.codicious.com/i/wysiwyg",
		"author"		=> "Codicious",
		"authorsite"	=> "http://codicious.com/",
		"version"		=> "1.2.1",
		"guid" 			=> "2890631a830db212b8704f60c94c899c",
		"compatibility" => "16*"
	);
}

/**
 * Installs Plugin
 */
function wysiwyg_install() 
{
    global $db;

	include_once MYBB_ROOT."inc/adminfunctions_templates.php";
	include_once MYBB_ROOT."inc/class_xml.php";	
	 
	$db->write_query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name IN('wysiwygeditor')");

	$db->write_query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN(
		'enablewysiwygeditor',
		'showwysiwygonnewpost',
		'showwysiwygateditsignature',
		'showwysiwyginquickreply',
		'showwysiwygonaddevent',
		'showwysiwygonpm'
	)");	
	
	$db->write_query("ALTER TABLE `".TABLE_PREFIX."themes` ADD `wysiwyg_theme` varchar(100) NOT NULL DEFAULT ''");
	
	$query = $db->query("SELECT disporder FROM ".TABLE_PREFIX."settinggroups ORDER BY disporder DESC LIMIT 1");
    $disporder = $db->fetch_field($query, "disporder");
	$disporder++;
   
    $insertarray = array(
	    'name' => 'wysiwygeditor',
	    'title' => 'WYSIWYG Editor',
		'description' => 'Here you can change settings of the WYSIWYG editor plugin.',
		'isdefault' => '0',
	    'disporder' => $disporder
	);
	
    $gid = $db->insert_query("settinggroups", $insertarray);  
	
	$insertarray = array(
		'name' => 'enablewysiwygeditor',
		'title' => 'Enable WYSIWYG Editor',
		'description' => 'If you enable the WYSIWYG editor, it will replace the old MyCode editor. The MyCode editor have to be enabled to use this feature.',
		'optionscode' => 'onoff',
		'value' => '1',
		'disporder' => '1',
		'gid' => $gid
	);
	
    $db->insert_query("settings", $insertarray);
	
	$insertarray = array(
		'name' => 'showwysiwygonnewpost',
		'title' => 'Show editor on New Post/New Thread Page?',
		'description' => '',
		'optionscode' => 'yesno',
		'value' => '1',
		'disporder' => '2',
		'gid' => $gid
	);
	
    $db->insert_query("settings", $insertarray);
	
	$insertarray = array(
		'name' => 'showwysiwyginquickreply',
		'title' => 'Show editor in quick reply?',
		'description' => '',
		'optionscode' => 'yesno',
		'value' => '1',
		'disporder' => '3',
		'gid' => $gid
	);
	
    $db->insert_query("settings", $insertarray);
	
	$insertarray = array(
		'name' => 'showwysiwygateditsignature',
		'title' => 'Show editor on edit signature page?',
		'description' => '',
		'optionscode' => 'yesno',
		'value' => '1',
		'disporder' => '4',
		'gid' => $gid
	);
	
    $db->insert_query("settings", $insertarray);
	
	$insertarray = array(
		'name' => 'showwysiwygonpm',
		'title' => 'Show editor on private message page?',
		'description' => '',
		'optionscode' => 'yesno',
		'value' => '1',
		'disporder' => '5',
		'gid' => $gid
	);
	
    $db->insert_query("settings", $insertarray);
	
	$insertarray = array(
		'name' => 'showwysiwygonaddevent',
		'title' => 'Show editor on the "Add Event" page?',
		'description' => '',
		'optionscode' => 'yesno',
		'value' => '1',
		'disporder' => '6',
		'gid' => $gid
	);
	
    $db->insert_query("settings", $insertarray);
	
	rebuild_settings();
	
    $contents = @file_get_contents(MYBB_ROOT.'inc/plugins/wysiwyg/templates.xml');

    $parser = new XMLParser($contents);
    $tree = $parser->get_tree();

	foreach ($tree['templates']['template'] AS $template) 
	{
	    $insert_array = array(
		  'title' => $template['title']['value'],
		  'template' => $db->escape_string($template['template']['value']),
		  'sid' => '-1',
		  'version' => '',
		  'dateline' => TIME_NOW
	     );
	
	    $db->insert_query("templates", $insert_array);
	}
}

/**
 * Checks if plugin is already installed
 *
 * @return boolean Wheter the plugin is already installed or not
 */
function wysiwyg_is_installed() 
{
    global $db;

    $result = $db->query("show columns from ".TABLE_PREFIX."themes like 'wysiwyg_theme'");

    if($db->num_rows($result) > 0) 
	{
	    return true;
	}
	
	return false;
}

/**
 * Uninstalls Plugin
 */
function wysiwyg_uninstall() 
{
    global $db;

	include_once MYBB_ROOT."inc/class_xml.php";
	
	$db->write_query("ALTER TABLE `".TABLE_PREFIX."themes` DROP `wysiwyg_theme`");	 
	 
	$db->write_query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name IN('wysiwygeditor')");

	$db->write_query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN(
		'enablewysiwygeditor',
		'showwysiwygonnewpost',
		'showwysiwygateditsignature',
		'showwysiwyginquickreply',
		'showwysiwygonpm',
		'showwysiwygonaddevent'
	)");
	
    rebuild_settings();
	
	$contents = @file_get_contents(MYBB_ROOT.'inc/plugins/wysiwyg/templates.xml');

    $parser = new XMLParser($contents);
    $tree = $parser->get_tree();

    foreach ($tree['templates']['template'] AS $template) 
	{
	    $db->delete_query("templates", "title = '".$template['title']['value']."'");
	}
}

/**
 * Activates Plugin
 */
function wysiwyg_activate() 
{
	include_once MYBB_ROOT."inc/adminfunctions_templates.php";
	
	find_replace_templatesets("misc_smilies_popup_smilie", "#".preg_quote('onclick="insertSmilie(\'{$smilie[\'insert\']}\');"')."#i", 'onclick="insertSmilie(\'{$smilie[\'insert\']}\', \'{$smilie[\'image\']}\');"');
}

/**
 * Deactivates Plugin
 */
function wysiwyg_deactivate() 
{
	include_once MYBB_ROOT."inc/adminfunctions_templates.php";
	
	find_replace_templatesets("misc_smilies_popup_smilie", "#".preg_quote('onclick="insertSmilie(\'{$smilie[\'insert\']}\', \'{$smilie[\'image\']}\');"')."#i", 'onclick="insertSmilie(\'{$smilie[\'insert\']}\');"', 0);
}

/**
 * Returns the editor theme of the current forum theme
 *
 * @return string The current editor theme
 */
function get_editor_theme()
{
   global $theme, $db;
   
   $query = $db->simple_select("themes", "*", "tid='".$theme['tid']."'");
   $theme = $db->fetch_array($query);
   
   if(!file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$theme['wysiwyg_theme']."/theme.php"))
   {
      $theme['wysiwyg_theme'] = 'default';
   }
   
   return $theme['wysiwyg_theme'];
}

/**
 * Loads the WYSIWYG editor
 *
 * @param string The old page output
 * @return string The new page output
 */
function wysiwyg_load_editor($page)
{
    global $theme, $cache, $lang, $mybb, $templates;
 
    $lang->load('wysiwyg'); 

    if($mybb->settings['enablewysiwygeditor'] !== "1")
    {
	    return false;
    }
   
    if(THIS_SCRIPT == "misc.php" && $mybb->input['action'] == "smilies")
    {  
    /*
	Didn't work anymore for some reason...
	$page = str_replace("	var editor = eval('opener.' + 'clickableEditor');
	function insertSmilie(code, src)
	{
		if(editor)
		{
			editor.performInsert(code, \"\", true, false);
		}
	}", "
	function insertSmilie(code, src)
	{
		opener.tinyMCE.insertSmiley(src, code, code);
	}", $page);
	*/
	$page = str_replace("function insertSmilie(code)", "function insertSmilie(code, src)", $page);
	$page = str_replace("if(editor)", "if(1==1)", $page);
	$page = str_replace("editor.performInsert(code, \"\", true, false);", "opener.tinyMCE.insertSmiley(src, code, code);", $page);
	}
  
    switch(THIS_SCRIPT)
    {
        case "newreply.php": 
	   
	    if($mybb->settings['showwysiwygonnewpost'] !== "1")
	    {
	       return false;
	    }
	   
	    break;
	   
        case "newthread.php": 
	   
	    if($mybb->settings['showwysiwygonnewpost'] !== "1")
	    {
	        return false;
	    }
	   
	    break;
	   
        case "editpost.php": 
	   
	    if($mybb->settings['showwysiwygonnewpost'] !== "1")
	    {
	        return false;
	    }
	   
	    break;
	   
        case "usercp.php": 

	    if($mybb->input['action'] == "editsig")
	    {
	        if($mybb->settings['showwysiwygonnewpost'] !== "1")
	        {
	            return false;
	        }
	    }
	   
	    break;
	   
        case "private.php": 
	    if($mybb->input['action'] == "send")
	    {
	        if($mybb->settings['showwysiwygonpm'] !== "1")
	        {
	            return false;
	        }
	    } 
		else 
		{
		    return false;
		}
	   
	    break;
		
        case "calendar.php": 

	    if($mybb->input['action'] == "addevent")
	    {
	        if($mybb->settings['showwysiwygonaddevent'] !== "1")
	        {
	            return false;
	        }
	    }
		else
		{
		    return false;
		}
	   
	    break;
	   
	    default: return false;
    }  
   
    $smilie_cache = $cache->read("smilies");
   
    foreach($smilie_cache as $smilie)
    {
        $smilies .= "smilies['".addslashes($smilie['find'])."'] = new Array('".$smilie['image']."', '".$smilie['name']."');";
    }
   
    $editor_theme = get_editor_theme();
   
    eval("\$new = \"".$templates->get("wysiwyg_default")."\";");

    $page = str_replace("<script type=\"text/javascript\" src=\"jscripts/editor.js?ver=1400\"></script>", $new, $page);  
    $page = str_replace("<script type=\"text/javascript\" src=\"jscripts/editor.js?ver=1600\"></script>", $new, $page);  
    $page = str_replace("<script type=\"text/javascript\" src=\"jscripts/editor.js?ver=1603\"></script>", $new, $page);  
	
    $page = str_replace("id=\"signature\"", "id=\"message\"", $page);
    $page = str_replace("javascript:clickableEditor.openGetMoreSmilies('clickableEditor');", "javascript:Smilies.openGetMoreSmilies('clickableEditor');", $page);

    $page = str_replace("clickableEditor.insertAttachment", "Smilies.insertAttachment", $page);
   
    return $page;
}

/**
 * Loads the WYSIWYG editor for quick reply
 *
 * @param string The old output
 * @return string The new output
 */
function wysiwyg_load_editor_quickreply($page)
{

    global $theme, $cache, $lang, $mybb, $templates;

    $lang->load('wysiwyg');   
   
    if(THIS_SCRIPT !== "showthread.php") 
    {
        return false;
    }

    if($mybb->settings['showwysiwyginquickreply'] !== "1")
    {
	    return false;
    }
   
    $smilie_cache = $cache->read("smilies");
   
    foreach($smilie_cache as $smilie)
    {
        $smilies .= "smilies['".addslashes($smilie['find'])."'] = new Array('".$smilie['image']."', '".$smilie['name']."');";
    }
   
    $editor_theme = get_editor_theme();
   
    eval("\$new = \"".$templates->get("wysiwyg_quickreply")."\";");

    $page = str_replace("<script type=\"text/javascript\" src=\"jscripts/thread.js?ver=1400\"></script>", $new, $page);
    $page = str_replace("<script type=\"text/javascript\" src=\"jscripts/thread.js?ver=1600\"></script>", $new, $page);
    $page = str_replace("<script type=\"text/javascript\" src=\"jscripts/thread.js?ver=1603\"></script>", $new, $page);
	
    $page = str_replace("id=\"quick_reply_submit\"", "id=\"quick_reply_submit\" onclick=\"tinyMCE.triggerSave();\"", $page);
    $page = str_replace("Thread.loadMultiQuoted();", "Thread2.loadMultiQuoted();", $page);	
	
    return $page;
}

/**
 * Adds menu
 *
 * @param array The menu array
 */
function wysiwyg_admin_action(&$action)
{
    $action['editorthemes'] = array('active'=>'editorthemes','file'=>'editorthemes.php');
}

/**
 * Adds sub menu
 *
 * @param array The sub menu array
 */
function wysiwyg_admin_menu(&$sub_menu)
{
	global $lang; 
	
	end($sub_menu);
	
	$key = (key($sub_menu)) + 10;

    $lang->load('style_editorthemes'); 
	
	$sub_menu['3'] = array
	(
		'id' => 'editorthemes',
		'title' => $lang->editorthemes,
		'link' => 'index.php?module=style/editorthemes'
	);
}

/**
 * Loads settings lang vars
 */
function wysiwyg_settings()
{
    global $lang;

    $lang->load("config_settings_wysiwyg");
}
?>