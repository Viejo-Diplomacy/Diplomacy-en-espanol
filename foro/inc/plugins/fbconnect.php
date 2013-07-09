<?php
/**
 * In the name of Allah, The Gracious, The Merciful
 * Author: Nayar(njoolfo0@gmail.com)
 * Plugin site: http://mybbmodding.net
 * A big thanks to Ali Razavi for helping me in this project. Without him, this wouldn't have reached this far.
 * Ali Razavi's Website: www.alilg.com
 */
 
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("global_start", "fbconnect_start");

function fbconnect_info()
{
	require_once MYBB_ROOT."fbcore/fbconnectplugin.php";
    return fbcore_fbconnect_info();
}
function fbconnect_install()
{
	require_once MYBB_ROOT."fbcore/fbconnectplugin.php";
    return fbcore_fbconnect_install();
}
function fbconnect_uninstall()
{
	require_once MYBB_ROOT."fbcore/fbconnectplugin.php";
    return fbcore_fbconnect_uninstall();
}
function fbconnect_is_installed()
{
	require_once MYBB_ROOT."fbcore/fbconnectplugin.php";
    return fbcore_fbconnect_is_installed();
}
function fbconnect_activate()
{
	require_once MYBB_ROOT."fbcore/fbconnectplugin.php";
    return fbcore_fbconnect_activate();
}
function fbconnect_deactivate()
{
	require_once MYBB_ROOT."fbcore/fbconnectplugin.php";
    return fbcore_fbconnect_deactivate();
}

function fbconnect_start()
{
	global $fbconnect,$mybb;
	if ($mybb->settings['fbcore2'] && $mybb->settings['fbcore3'])
	{
		if($mybb->user['uid'])
		{
			if(!$mybb->user['fbuid'])
			{
				$perms = $mybb->settings['fbconnect8'];
				if($perms)
				{
					$perms = ','.$perms.'';
				}
				//$lol = '<a href="https://graph.facebook.com/oauth/authorize?client_id='.$mybb->settings['fbcore2'].'&redirect_uri='.$mybb->settings['bburl'].'/fbconnect.php&type=user_agent&display=page&scope=email'.$perms.'">Link Account with Facebook</a>';
				$lol = '<a href="'.$mybb->settings['bburl'].'/fbcore/fbconnect.php">Link Account with Facebook</a>';
				$lol = str_replace('"', '\"', $lol);
				eval("\$fbconnect = \"".$lol."\";");
			}
		}
		else
		{
			$perms = $mybb->settings['fbconnect8'];
			if($perms)
			{
				$perms = ','.$perms.'';
			}
			//$lol = '<a href="https://graph.facebook.com/oauth/authorize?client_id='.$mybb->settings['fbcore2'].'&redirect_uri='.$mybb->settings['bburl'].'/fbconnect.php&type=user_agent&display=page&scope=email'.$perms.'"><img src="./fbcore/fbconnect.png" border="0" /></a>';
			$lol = '<a href="'.$mybb->settings['bburl'].'/fbcore/fbconnect.php"><img src="./fbcore/fbconnect.png" border="0" /></a>';
			$lol = str_replace('"', '\"', $lol);
			eval("\$fbconnect = \"".$lol."\";");
		}
	}
}
?>