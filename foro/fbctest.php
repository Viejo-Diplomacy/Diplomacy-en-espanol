<?php
/**
 * In the name of Allah, The Gracious, The Merciful
 * This is just a test file to see whether Facebook Connect would work on your host
 */

define('FACEBOOK_APP_ID', 'your application id');
define('FACEBOOK_SECRET', 'your application secret');

$cookie = get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);
		if ($cookie)
		{
			$facebook_data = fbconnect_login_form();
			echo $facebook_data;
			
			$fbuser = json_decode(@file_get_contents('https://graph.facebook.com/me?access_token=' .$cookie['access_token']));
			echo "Your Facebook UID is {$cookie['uid']}";
			echo "<br />Your Name is {$fbuser->name}";
			echo "<br />Your Email is {$fbuser->email}";
			if ($cookie['uid'])
			{	
				echo "<br />Test 1: Pass!";
			}
			else
			{
				echo "<br />Test 1: Fail!";
			}			
			if ($fbuser->email)
			{	
				echo "<br />Test 2: Pass!";
			}
			else
			{
				echo "<br />Test 2: Fail!";
			}
		}
		else
		{
			$facebook_data = fbconnect_login_form();
			echo $facebook_data;
		}

function get_facebook_cookie($app_id, $application_secret)
{
	$args = array();
	parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
	ksort($args);
	$payload = '';
	foreach ($args as $key => $value)
	{
		if ($key != 'sig')
		{
			$payload .= $key . '=' . $value;
		}
	}
	
	if (md5($payload . $application_secret) != $args['sig'])
	{
		return null;
	}
	return $args;
}

function fbconnect_login_form()
{
	return 'Sign in to start the test: <fb:login-button perms="email"></fb:login-button>
		 <div id="fb-root"></div>
    <script src="http://connect.facebook.net/en_US/all.js"></script>
    <script>
      FB.init({appId: '.FACEBOOK_APP_ID.', status: true,
               cookie: true, xfbml: true});
      FB.Event.subscribe(\'auth.login\', function(response) {
        window.location.reload();
      });
	  
    </script>';
}
?>