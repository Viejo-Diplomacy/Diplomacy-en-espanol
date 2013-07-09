<?php
/**
 * In the name of Allah, The Gracious, The Merciful
 * Author: Nayar(njoolfo0@gmail.com)
 * Plugin site: http://mybbmodding.net
 * Adapted from works of Ali Razavi (http://www.alilg.com/)
 */

// error_reporting(E_ALL);

// Initialise MyBB.
define('IN_MYBB', 1);
require_once "../global.php";

// Fetch Facebook Application ID and Secret
$app_id = $mybb->settings['fbcore2'];
$app_secret = $mybb->settings['fbcore3'];
$my_url = $mybb->settings['bburl']."/fbcore/fbconnect.php";

// Load Language Variables
$lang->load("fbconnect");

// We set the file not to run by default
$allowrun = false;

// Check if FBConnect is ON and 
if ($mybb->settings['boardclosed'] == 0 && $mybb->settings['fbconnect1'] == 1 && $db->field_exists('fbuid', 'users'))
{
	// Check if FBConnect configuration is not blank
	if ($app_id && $app_secret)
	{
		$allowrun = true;
	}
}

if ($allowrun)
{
	$code = $_REQUEST["code"];

    if(empty($code)) 
	{
        $perms = $mybb->settings['fbconnect8'];
		if($perms)
		{
			$perms = ','.$perms.'';
		}
		
		$dialog_url = "http://www.facebook.com/dialog/oauth?client_id=".$app_id."&redirect_uri=".urlencode($my_url)."&scope=email".$perms;
        
		echo("<script> top.location.href='" . $dialog_url . "'</script>");
    }

    $token_url = "https://graph.facebook.com/oauth/access_token?client_id=".$app_id."&redirect_uri=".urlencode($my_url)."&client_secret=".$app_secret."&code=".$code;

    $access_token = file_get_contents($token_url);

    $graph_url = "https://graph.facebook.com/me?".$access_token;

    if($mybb->settings['fbconnect7'] == '0')
	{
		$fbuser = json_decode(file_get_contents($graph_url));
	}
	else
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt ($ch, CURLOPT_URL, $graph_url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);
		$fbuser = json_decode($file_contents);
	}
	
	// Fix birthday format
	/*
	if(isset($fbuser->birthday))
	{
		$birthday = explode("/",$fbuser->birthday);
		$birthday['0'] = ltrim($birthday['0'],'0');
		$fbuser->birthday = "{$birthday['1']}-{$birthday['0']}-{$birthday['2']}";
	}*/
	
	// Check if member is already logged in
	if(!$mybb->user['uid'])
	{
		if ($fbuser->id && $fbuser->email)
		{
			// See if this Facebook UID is already in database
			$facebook_mybb_user = $db->simple_select("users", "*", "fbuid='{$fbuser->id}'");
			$fb_user = $db->fetch_array($facebook_mybb_user);
			
			// Also check email
			if(!$fb_user && $mybb->settings['fbconnect5'] == "1")
			{
				$facebook_mybb_user = $db->simple_select("users", "*", "email='{$fbuser->email}'");
				$fb_user = $db->fetch_array($facebook_mybb_user);
				
				// Link the accounts
				if($mybb->settings['fbconnect6'] == "1")
				{
					$db->query("UPDATE ".TABLE_PREFIX."users SET fbuid = {$fbuser->id} WHERE email = '{$fbuser->email}'");
				}
			}
			
			// If MyBB Facebook User found
			if($fb_user)
			{
				// Put avatar if has none
				if(empty($fb_user['avatar']) && $mybb->settings['fbconnect10'] == '1')
				{
					$db->query("UPDATE ".TABLE_PREFIX."users SET avatar = 'http://graph.facebook.com/{$fbuser->id}/picture?type=small', avatartype = 'remote', avatardimensions = '100|100' WHERE uid = '{$fb_user['uid']}'");
				}
				
				// Put birthday if empty
				if($fb_user['birthday'] == '' && $mybb->settings['fbconnect11'] == '1')
				{
					if(isset($fbuser->birthday))
					{
						$birthday = explode("/",$fbuser->birthday);
						$birthday['0'] = ltrim($birthday['0'],'0');
						$fbuser->birthday = "{$birthday['1']}-{$birthday['0']}-{$birthday['2']}";
					}
					$db->query("UPDATE ".TABLE_PREFIX."users SET birthday = '{$fbuser->birthday}' WHERE uid = '{$fb_user['uid']}'");
				}
	
				// Log in User
				setcookie(''.$mybb->settings['cookieprefix'].'mybbuser', ''.$fb_user['uid'].'_'.$fb_user['loginkey'].'', NULL, $mybb->settings['cookiepath'], $mybb->settings['cookiedomain']);
				
				//Return to index
				$fbconnectheaderinclude = "";
				$fbconnectdata = "{$lang->fbconnectwelcome}<br />";
				$fbconnectjavascript = "<script type=\"text/javascript\">
					<!--
					window.location = \"../index.php\"
					//-->
					</script>";
			}
			else
			{
				$allowreg = false;
				
				if($mybb->settings['fbconnect4'] == 1)
				{
					$allowreg = true;
				}
				
				$plugins->run_hooks("fbconnect_registerform_start");
				
				//Display Registration Form
				if($allowreg)
				{
					if ($mybb->settings['fbconnect9'] == 1)
					{
						$usernameform = "{$lang->fbconnectchoosename}: <br /><input type=\"text\" class=\"textbox\" name=\"username\" id=\"username\" style=\"width: 25%\" value=\"{$fbuser->name}\" /><br />";
					}
					else
					{
						$usernameform = "<input type=\"hidden\" name=\"username\" value=\"{$fbuser->name}\" />";
					}
					
					$fbconnectheaderinclude = "<script type=\"text/javascript\" src=\"jscripts/validator.js\"></script>";
					
					// Access token code needs to be separated.
					$access_token = explode("&", $access_token);
					$access_token = str_replace("access_token=","",$access_token['0']);

					$fbconnectdata = "
					<form action=\"./fbconnectregister.php\" method=\"post\" id=\"registration_form\"><input type=\"text\" style=\"visibility: hidden;\" value=\"\" name=\"regcheck1\" /><input type=\"text\" style=\"visibility: hidden;\" value=\"true\" name=\"regcheck2\" />
					<br />{$regerrors}
					{$usernameform}
					<input type=\"hidden\" name=\"email\" id=\"email\" style=\"width: 100%\"  value=\"{$fbuser->email}\" />
					<input type=\"hidden\" name=\"fbuid\" value=\"{$fbuser->id}\" />
					<input type=\"hidden\" name=\"fbbirthday\" value=\"{$fbuser->birthday}\" />
					<input type=\"hidden\" name=\"fbaccesstoken\" value=\"{$access_token}\" />
					<input type=\"hidden\" name=\"step\" value=\"registration\" />
					<input type=\"hidden\" name=\"action\" value=\"do_register\" />
					<br /><input type=\"submit\" class=\"button\" name=\"regsubmit\" value=\"Register\" />
					<br /><fb:login-button show-faces=\"true\" width=\"400\" max-rows=\"3\"></fb:login-button>
					</td>
					</tr>
					
					<br />
					</form>";
					
					$fbconnectjavascript = "<script type=\"text/javascript\">
					<!--
						regValidator = new FormValidator('registration_form');
						regValidator.register('username', 'notEmpty', {failure_message:'{$lang->js_validator_no_username}'});
						{$validator_extra}
						regValidator.register('username', 'ajax', {url:'xmlhttp.php?action=username_availability', loading_message:'{$lang->js_validator_checking_username}'}); // needs to be last
					// -->
					</script>";
				}
				else
				{
					$fbconnectheaderinclude = "";
					$fbconnectdata = "{$lang->fbconnectregdisabled}";
					$fbconnectjavascript = "";
				}
			}
		}
		else
		{
			// Ask him to log in if no Facebook Cookie Detected
			// $logginbutton = fbconnect_login_form();
			$fbconnectdata = "<b>{$lang->fbconnectloginregister}</b><br /><br />{$logginbutton}";
		}
	}
	else
	{
		if($fbuser->id && $fbuser->email)
		{
			$facebook_mybb_user = $db->simple_select("users", "*", "fbuid='{$fbuser->id}'");
			$fb_user = $db->fetch_array($facebook_mybb_user);
			if(!$fb_user)	
			{			
				//Add his Facebbok UID into database
				$db->query("UPDATE ".TABLE_PREFIX."users SET fbuid = {$fbuser->id} WHERE uid = '{$mybb->user['uid']}'");
				
				$fbconnectheaderinclude = "";
				$fbconnectdata = "{$lang->fbconnectlinkedaccount}<br />";
				$fbconnectjavascript = "<script type=\"text/javascript\">
					<!--
					window.location = \"../index.php\"
					//-->
					</script>";	
			}
			else
			{
				$fbconnectheaderinclude = "";
				$fbconnectdata = "{$lang->fbconnectlinkaccountfail}<br />";
				$fbconnectjavascript = "<script type=\"text/javascript\">
					<!--
					window.location = \"../index.php\"
					//-->
					</script>";	
			}
		}
		else
		{
			// FB user not detected
			// Fetch Login Button
			$fbconnectheaderinclude = "";
			$fbconnectdata = "{$lang->fbconnectlinkaccount}<br/>";
			$fbconnectjavascript = "";
		}
	}
}
else
{
	$fbconnectdata = $lang->fbconnecterror.$lang->fbconnectdisabled.$lang->fbconnectlanguagemismatch.$lang->fbconnectnotconfigured;
}

// Generate facebook connect page
$fbconnectpage = "<!doctype html>
	<html xmlns:fb=\"http://www.facebook.com/2008/fbml\">
	<head>
	<title>{$mybb->settings['bbname']} - FBConnect </title>
	{$headerinclude}
	{$fbconnectheaderinclude}
	</head>
	<body>
	{$header}
	<table border=\"0\" cellspacing=\"1\" cellpadding=\"4\" class=\"tborder\">
	<tr><td class=\"thead\"><strong>{$lang->fbconnecttitle} </strong></td></tr>
	<tr><td class=\"trow1\" align=\"center\">
	{$fbconnectdata}
	</td></tr><tr><td class=\"trow2\" align=\"right\">{$lang->fbconnectcopyright} <a href=\"http://mybbmodding.net\">FBConnect</a></td></tr></table><br />
	{$fbconnectjavascript}
	{$footer}
	</body>
	</html>";
	
// Output facebook connect page
output_page($fbconnectpage);
?>