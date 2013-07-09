<?php
/**
 * In the name of Allah, The Gracious, The Merciful
 * Author: Nayar(njoolfo0@gmail.com)
 * Plugin site: http://mybbmodding.net
 */
 
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

//$plugins->add_hook("global_start", "fbcore_start");

function fbcore_info()
{
	require_once MYBB_ROOT."fbcore/fbcoreplugin.php";
    return fbcore_fbcore_info();
}
function fbcore_activate()
{
	require_once MYBB_ROOT."fbcore/fbcoreplugin.php";
    return fbcore_fbcore_activate();
}
function fbcore_deactivate()
{
	require_once MYBB_ROOT."fbcore/fbcoreplugin.php";
    return fbcore_fbcore_deactivate();
}
/*
function fbcore_start()
{
	global $mybb,$fbcore; 
	if ($mybb->settings['fbcore1'] == 1 && $mybb->settings['fbcore2'])
	{
		$fbjavascript = '<div id="fb-root"></div>
		<script src="http://connect.facebook.net/en_US/all.js"></script>
		<script>
		  FB.init({appId: '.$mybb->settings['fbcore2'].', status: true,
				   cookie: true, xfbml: true});
		  FB.Event.subscribe(\'auth.login\', function(response) {
			window.location.reload();
		  });	  
		</script>';
		$fbjavascript = str_replace('"', '\"', $fbjavascript);
		eval("\$fbcore = \"".$fbjavascript."\";");
	}	
}*/
?>