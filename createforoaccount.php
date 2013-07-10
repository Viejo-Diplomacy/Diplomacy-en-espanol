<?php
function doCreateForumAccount($name,$email,$epass,$guid){
	
	require_once 'foro/inc/class_core.php';
	$mybb = new MyBB;

	require_once 'foro/inc/config.php';
	require_once 'foro/inc/class_timers.php';
	require_once 'foro/inc/functions.php';
	require_once 'foro/admin/inc/functions.php';
	require_once 'foro/inc/class_xml.php';
	require_once 'foro/inc/functions_user.php';
	require_once 'foro/inc/class_language.php';
	require_once 'foro/inc/functions_rebuild.php';

	require_once "foro/inc/db_mysql.php";
	require_once 'foro/inc/settings.php';
	
	$mybb->settings = &$settings;

    $db = new DB_MySQL;
    $db->connect($config['database']);
    $db->select_db('webdiplo_foro');
    
    $now = time();
    $salt = random_str();
    $loginkey = generate_loginkey();
    $saltedpw = md5(md5($salt).md5($epass));

    $sql ="INSERT INTO `webdiplo_foro`.`mybb_users` (`uid`, `username`, `password`, `salt`, `loginkey`, `email`, `postnum`, `avatar`, `avatardimensions`, `avatartype`, `usergroup`, `additionalgroups`, `displaygroup`, `usertitle`, `regdate`, `lastactive`, `lastvisit`, `lastpost`, `website`, `icq`, `aim`, `yahoo`, `msn`, `birthday`, `birthdayprivacy`, `signature`, `allownotices`, `hideemail`, `subscriptionmethod`, `invisible`, `receivepms`, `receivefrombuddy`, `pmnotice`, `pmnotify`, `threadmode`, `showsigs`, `showavatars`, `showquickreply`, `showredirect`, `ppp`, `tpp`, `daysprune`, `dateformat`, `timeformat`, `timezone`, `dst`, `dstcorrection`, `buddylist`, `ignorelist`, `style`, `away`, `awaydate`, `returndate`, `awayreason`, `pmfolders`, `notepad`, `referrer`, `referrals`, `reputation`, `regip`, `lastip`, `longregip`, `longlastip`, `language`, `timeonline`, `showcodebuttons`, `totalpms`, `unreadpms`, `warningpoints`, `moderateposts`, `moderationtime`, `suspendposting`, `suspensiontime`, `suspendsignature`, `suspendsigtime`, `coppauser`, `classicpostbit`, `loginattempts`, `failedlogin`, `usernotes`, `akismetstopped`, `myalerts_settings`, `fbuid`, `achivements`, `threads`,`game_uid`) 
	       VALUES (NULL, '".$name."', '".$saltedpw."', '".$salt."', '".$loginkey."', '".$email."', '0', '', '', '0', '2', '', '0', '', '".$now."', '0', '0', '0', '', '', '', '', '', '', 'all', '', '0', '0', '0', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', '', '', '', '0', '0', '', '', '0', '0', '0', '', '', '', '', '0', '0', '0', '', '', '0', '0', '', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', '', '0', NULL, NULL, '', '0',".$guid.");";
    
	//echo $sql;
	$db->query($sql);
	//get user count
	$sql = "SELECT count(`uid`) FROM `mybb_users` WHERE 1";
	$usercount = $db->query($sql);
	$usercount = $db->fetch_array($usercount);
	
	// get last user id
	$sql = "SELECT max(`uid`) FROM `mybb_users` WHERE 1";
	$uid = $db->query($sql);
	$uid = $db->fetch_array($uid);
	
	// get the Json array from the datacache table
	$sql = 'SELECT `cache` FROM `mybb_datacache` WHERE `title`=\'stats\'';
	$json = $db->query($sql);
	$json = $db->fetch_array($json);
	// update it with new data
	//print $json['cache']."<br>";
	$json = remakeJsonString($json['cache'],$name,$uid['max(`uid`)'].'',$usercount['count(`uid`)']);
	$sql = 'UPDATE `webdiplo_foro`.`mybb_datacache` SET `cache` = \''.$json.'\' WHERE `mybb_datacache`.`title` = \'stats\';';
	//print $json;
	$db->query($sql);
	
}

//a:7:{s:10:"numthreads";s:3:"133";s:20:"numunapprovedthreads";i:0;s:8:"numposts";s:4:"1106";s:18:"numunapprovedposts";i:0;s:8:"numusers";s:2:"46";s:7:"lastuid";s:2:"61";s:12:"lastusername";s:7:"test124";}
function remakeJsonString($input,$username,$uid,$usercount){
	$ar = explode(';',$input);
	//print_r($ar);
	$index = 0;
	$usercount = intval($usercount);
	$usercount++;
	$output = "";
	foreach($ar as $token){
		
		switch($index){
			case 9:$output .= "s:".strlen($usercount).':"'.$usercount.'"';break;
			case 11:$output .= "s:".strlen($uid).':"'.$uid.'"';break;
			case 13:$output .= "s:".strlen($username).':"'.$username.'"';break;
			default : $output .= $token;
		}
		if($index<14){
			$output .= ";";
		}
		$index++;
	}
	return $output;
}
if(isset($_REQUEST['username']) && isset($_REQUEST['email']) && isset($_REQUEST['password']) && isset($_REQUEST['guid'])){
	doCreateForumAccount($_REQUEST['username'],$_REQUEST['email'],$_REQUEST['password'],$_REQUEST['guid']);
	print "Se ha creado una cuenta en el foro. Ya puedes entrar con tu usuario: <i style='color: rgb(224, 163, 94);'>".$_REQUEST['username']."</i> y la contrasse&ntilde;a elegida.";
}else print 'No ha sido posible crear una cuenta en el foro autom&aacute;ticamente, pero puedes hacerlo de manera manual.';
  ?>