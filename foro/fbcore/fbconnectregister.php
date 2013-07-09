<?php
/**
 * In the name of Allah, The Gracious, The Merciful
 * Author: Nayar(njoolfo0@gmail.com)
 * Plugin site: http://mybbmodding.net
 * Adapted from works of Ali Razavi (http://www.alilg.com/)
 */

// A certain type of security needs to be implemented here to prevent direct access
 
define('IN_MYBB', 1);
require_once "../global.php";
require_once MYBB_ROOT."inc/datahandlers/user.php";

$fb_access_token = $_POST["fbaccesstoken"];

// Need to implememt  a way to see whether the username is allowed.
if ($mybb->settings['fbconnect4'] == 1)
{
	// Generate a random password
	$password = random_str(8);

	// Initialise birthday variable
	$birthday = "";

	//Fix birthday format
	if(isset($_POST["fbbirthday"]))
	{
		$birthday_exploded = explode("/",$_POST["fbbirthday"]);
		$birthday_exploded['0'] = ltrim($birthday_exploded['0'],'0');
		$birthday = array(
			"day" => $birthday_exploded['1'],
			"month" => $birthday_exploded['0'],
			"year" => $birthday_exploded['2']
		);	
	}
	
	$new_user_data = array(
		"username" => $_POST['username'],
		"password" => $password,
		"password2" => $password,
		"email" => $_POST['email'],
		"email2" => $_POST['email'],
		"usergroup" => $mybb->settings['fbconnect12'],
		"displaygroup" => $mybb->settings['fbconnect12'],
		"profile_fields_editable" => true,
		"birthday" => $birthday,
		"avatar" => "http://graph.facebook.com/{$_POST['fbuid']}/picture?type=large",
		"avatardimensions" => "100|100",
		"avatartype" => "remote",
		);
	
	$newuser = new UserDataHandler;
	$newuser->set_data($new_user_data);
	if($newuser->validate_user())
	{
		$newuser->insert_user();
		$db->query("UPDATE ".TABLE_PREFIX."users SET fbuid = {$_POST['fbuid']} WHERE uid = '{$newuser->uid}'");
	}
	$plugins->run_hooks("fbservices_register_end");	
}

// Return to FBConnect Page to log in User
echo '<script type="text/javascript">
	<!--
	window.location = "./fbconnect.php"
	//-->
	</script>
	</body>';
?>