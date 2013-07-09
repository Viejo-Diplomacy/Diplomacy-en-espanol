<?php

/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: hello_world.php 2012-04-12 10:58Z EdsonOrdaz $
 */

// Disallow direct access to this file for security reasons

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
$plugins->add_hook("pre_output_page", "hello_world_extensions");
$plugins->add_hook("postbit", "hello_world_postbit_extensions");

function extension_hello_world_info()
{
	/**
	 * Array of information about the extension.
	 * name: The name of the extensin
	 * description: Description of what the extension does
	 * website: The website the extension is maintained at (Optional)
	 * author: The name of the author of the extension
	 * authorsite: The URL to the website of the author (Optional)
	 * version: The version number of the extension
	 */
	global $lang;
	
	/*
	 * extensions_lang($name_extension);
	 * load language extension
	 */
	 
	extensions_lang('hello_world');
	
	return array(
		'name' => $lang->hello_world,
		'description' => $lang->hello_world_extension_description,
		'version' => '1.0',
		'website' => 'http://www.mybb-es.com/',
		'author' => 'Edson Ordaz',
		'authorsite' => 'http://www.mybb-es.com/',
		'achivements' => '*'
	);
}


/**
 * ADDITIONAL EXTENSION INSTALL/UNINSTALL ROUTINES
 *
 * function extension_hello_world_activate()
 * {
 * }
 *
 * function extension_hello_world_activate()
 * {
 * }
 */

function hello_world_extensions($page)
{
	global $lang;
	//load language
	extensions_lang('hello_world');
	
	$page = str_replace("<div id=\"content\">", "<div id=\"content\"><p>{$lang->hello_global}</p>", $page);
	return $page;
}

function hello_world_postbit_extensions(&$post)
{
	global $lang;
	//load language
	extensions_lang('hello_world');
	
	$post['message'] = "<strong>{$lang->hello_postbit}</strong><br /><br />{$post['message']}";
}
?>