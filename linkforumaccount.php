<?php
function startWizardForm($username,$email,$guid){
	
	print "
			<h5>Estas registrado ya en el foro?</h5>
			<input type='radio' class='hasforumaccount' name='hasforumaccount' value='0' checked>No</input>
			<input type='radio' class='hasforumaccount' name='hasforumaccount' value='1'>Si</input><br>
			<input id='proceed1' type='button' value='Siguiente' />
	";
	print '
		
		
		<script type="text/javascript">
		var resp = "0";
		jQuery(\'.hasforumaccount\').click(function(e){
			resp = e.currentTarget.value;
		});
			jQuery(\'#proceed1\').click(function(){
				
				jQuery.ajax({
					type: "POST",
					url: "linkforumaccount.php",
					data: {wizard: "on", wizardstep: "2", guid: "'.$guid.'",hasforumaccount: resp, username : "'.$username.'", email :"'.$email.'"}
					}).done(function(data) {jQuery(\'#linkaccountform\').html(data);});
			});
		</script>';
	
}
function checkExistingAccountForm($guid){
	print "
			
			<form method='POST'>
				<h5>Introduce tu usuario y contrase&ntilde;a del foro:</h5>
				<input id='linkaccountusername' type='text' name='username' />
				<input id='linkaccountpassword' type='password' name='password' />
				<input id='proceed3' type='button' value='Enlazar cuenta' />
				
			</form>
			
	";
	print '
		<script type="text/javascript">
			jQuery(\'#proceed3\').click(function(){
				
				jQuery.ajax({
					type: "POST",
					url: "linkforumaccount.php",
					data: {wizard: "on", wizardstep: "3", guid: "'.$guid.'",username : jQuery(\'#linkaccountusername\').val(),password : jQuery(\'#linkaccountpassword\').val()}
					}).done(function(data) {jQuery(\'#linkaccountform\').html(data);});
			});
		</script>';
}
function setNewAccountPassword($guid,$username,$email){
	print "
			
			<form method='POST'>
				<h5>Introduce una contrase&ntilde;a para tu nueva cuenta en el foro:</h5>
				<input id='newaccountpassword' type='password' name='password' />
				<input id='proceed4' type='button' value='Continuar' />
				
			</form>
			
	";
	print '
		<script type="text/javascript">
			jQuery(\'#proceed4\').click(function(){
				
				jQuery.ajax({
					type: "POST",
					url: "linkforumaccount.php",
					data: {wizard: "on", wizardstep: "4", guid: "'.$guid.'",username : "'.$username.'",password : jQuery(\'#newaccountpassword\').val(),email :"'.$email.'"}
					}).done(function(data) {jQuery(\'#linkaccountform\').html(data);});
			});
		</script>';
}
function doLinkAccountForm($username,$email,$password,$guid){
	print "
			<h5>A forum account is being created... please wait.</h5>
	";
	
	print '
	<script type="text/javascript">
				jQuery.ajax({
					type: "POST",
					url: "createforoaccount.php",
					data: {username: "'.$username.'", email: "'.$email.'", password: "'.$password.'", guid : "'.$guid.'"}
					}).done(function(data) {jQuery(\'#linkaccountform\').html(data);});
			</script>';
}

function getUserForoAccountForm($username,$email,$guid){
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
    
    $sql = "SELECT `username` FROM `mybb_users` WHERE `game_uid`=".$guid;
	
	$result = $db->query($sql);
	$result = $db->fetch_array($result);
	
	if(isset($result['username'])){
		print "<p>Forum account is linked with username : <i style='color: rgb(224, 163, 94);'>".$result['username']."</i></p>";
	}else{
		print "<p>Si no dispones de cuenta en el foro :</p><input type='button' id='startwizard' value='Procede a enlazarla' />";
		print '
		<script type="text/javascript">
			jQuery(\'#startwizard\').click(function(){
				jQuery.ajax({
					type: "POST",
					url: "linkforumaccount.php",
					data: {wizard: "on", wizardstep: "1", guid: "'.$guid.'", username : "'.$username.'", email :"'.$email.'"}
					}).done(function(data) {jQuery(\'#linkaccountform\').html(data);});
			});
		</script>';
	}
}

function LinkExistingForoAccountForm($username,$password,$guid=-1){

	if($username == ""){
		print "<h5 style='color:red'>Please Enter a correct username.</h5>";
		print "<p>No forum account is linked to this web account :</p><input type='button' id='startwizard' value='Proceed with forum linking' />";
		print '
		<script type="text/javascript">
			jQuery(\'#startwizard\').click(function(){
				jQuery.ajax({
					type: "POST",
					url: "linkforumaccount.php",
					data: {wizard: "on", wizardstep: "1", guid: "'.$guid.'"}
					}).done(function(data) {jQuery(\'#linkaccountform\').html(data);});
			});
		</script>';
		return false;
	}
	
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
    
    $sql = 'SELECT `username`,`password`,`salt`,`loginkey`,`game_uid` FROM `mybb_users` WHERE `username`=\''.$username.'\'';
	
	$result = $db->query($sql);
	$result = $db->fetch_array($result);
	
	if(isset($result['username'])){
		$now = time();
		$salt = $result['salt'];
		$saltedpw = md5(md5($salt).md5($password));
		
		if($saltedpw == $result['password']){
			// link forum with guid
			
			$sql = 'UPDATE `webdiplo_foro`.`mybb_users` SET `game_uid` = '.$guid.' WHERE `mybb_users`.`username` = \''.$result['username'].'\';';
			if($db->query($sql)){
			print "<p>Forum account is linked with username : <i style='color: rgb(224, 163, 94);'>".$result['username']."</i></p>";
			
			return true;
			}
		}
		
	}
	print "
			<h5 style='color:red'>The information provided is not correct.</h5>
			";		
		print "<p>No forum account is linked to this web account :</p><input type='button' id='startwizard' value='Proceed with forum linking' />";
		print '
		<script type="text/javascript">
			jQuery(\'#startwizard\').click(function(){
				jQuery.ajax({
					type: "POST",
					url: "linkforumaccount.php",
					data: {wizard: "on", wizardstep: "1", guid: "'.$guid.'"}
					}).done(function(data) {jQuery(\'#linkaccountform\').html(data);});
			});
		</script>';
	
			
	
}


if(isset($_REQUEST['wizard']) && isset($_REQUEST['wizardstep']) && isset($_REQUEST['guid'])){
	
	switch($_REQUEST['wizardstep']){
		
		case "1": startWizardForm($_REQUEST['username'],$_REQUEST['email'],$_REQUEST['guid']);  break;
		case "2": 
			if($_REQUEST['hasforumaccount']=="0"){
				setNewAccountPassword($_REQUEST['guid'],$_REQUEST['username'],$_REQUEST['email']);
			}else{
				checkExistingAccountForm($_REQUEST['guid']);
			}
			break;
		case "3": LinkExistingForoAccountForm($_REQUEST['username'],$_REQUEST['password'],$_REQUEST['guid']); break;
		case "4": 
			doLinkAccountForm($_REQUEST['username'],$_REQUEST['email'],$_REQUEST['password'],$_REQUEST['guid']);
		break;
		
	}
	
}elseif(isset($_REQUEST['guid'])){
	getUserForoAccountForm($_REQUEST['username'],$_REQUEST['email'],$_REQUEST['guid']);
}else{
	
	print "You can not access this page.";
	
}
?>
