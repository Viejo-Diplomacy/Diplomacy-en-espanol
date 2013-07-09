<?php
/**
 * Force Postbit Layout
 * Copyright 2011 Aries-Belgium
 *
 * $Id$
 */

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook('showthread_start', 'forcepostbit_force');
$plugins->add_hook('postbit', 'forcepostbit_force',1000);

/**
 * Info function for MyBB plugin system
 */
function forcepostbit_info()
{
	$donate_button = 
'<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RQNL345SN45DS" style="float:right;margin-top:-8px;padding:4px;" target="_blank"><img src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_US/i/btn/btn_donate_SM.gif" /></a>';

	return array(
		"name"			=> "Force Postbit Layout",
		"description"	=> $donate_button."Forces all your users to use the default postbit style.",
		"website"		=> "",
		"author"		=> "Aries-Belgium",
		"authorsite"	=> "http://community.mybb.com/user-3840.html",
		"version"		=> "1.0",
		"guid" 			=> "058dbc133fe972b9848e02d6386aa3a0",
		"compatibility" => "14*,16*"
	);
}

/**
 * The activation function for the MyBB plugin system
 */
function forcepostbit_activate()
{
	include MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("usercp_options", "#".preg_quote("id=\"classicpostbit\" value=\"1\" {\$classicpostbitcheck}")."#s", "id=\"classicpostbit\" disabled=\"disabled\"");
}

/**
 * The deactivation function for the plugin system
 */
function forcepostbit_deactivate()
{
	include MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("usercp_options", "#".preg_quote("id=\"classicpostbit\" disabled=\"disabled\"")."#s", "id=\"classicpostbit\" value=\"1\" {\$classicpostbitcheck}", 0);
}

/**
 * Implementation of the showthread_start hook
 *
 * Force to use the default layout style
 */
function forcepostbit_force($post=null)
{
	global $mybb, $db;
	
	include MYBB_ROOT."inc/settings.php";
	$mybb->settings['postlayout'] = $settings['postlayout'];
	$mybb->user['classicpostbit'] = ($settings['postlayout'] == 'classic') ? 1 : 0;
}