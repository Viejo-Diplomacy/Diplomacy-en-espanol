<?php
/*
    Copyright (C) 2004-2010 Kestas J. Kuliukas
	This file is part of webDiplomacy.
    webDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    webDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('IN_CODE') or die('This script can not be run by itself.');
/**
 * @package Base
 * @subpackage Forms
 */

?>
	<li class="formlisttitle">Direcci&oacute;n de correo</li>
	<li class="formlistfield"><input type="text" name="userForm[email]" size="50" value="<?php
		if ( isset($_REQUEST['userForm']['email'] ) )
		{
			print $_REQUEST['userForm']['email'];
		}
		elseif( isset($User->email) )
		{
			print $User->email;
		}
		?>" <?php if ( isset($_REQUEST['emailToken']) ) print 'readonly '; ?> /></li>
	<li class="formlistdesc">T&uacute; direcci&oacute;n de correo. <strong>No</strong> se usar&aacute; para enviar spam.</li>

	<li class="formlisttitle">Ocultar correo electr&oacute;nico:</li>
	<li class="formlistfield">
		<input type="radio" name="userForm[hideEmail]" value="Yes" <?php if($User->hideEmail=='Yes') print "checked"; ?>>Si
		<input type="radio" name="userForm[hideEmail]" value="No" <?php if($User->hideEmail=='No') print "checked"; ?>>No
	</li>
          </li>
      <li class="formlistdesc">
     
   </li>
	<li class="formlistdesc">
		Selecciona si quieres que otros usuar&iacute;os puedan ver tu direcci&oacute;n de
		correo electr&oacute;nico. Si optas por mostrar tu correo electr&oacute;nico se
        integrar&aacute; en una imagen para evitar que los robots de spam puedan verlo.
	</li>
    
    	<!--Opción para enviar mails-->
	<li class="formlisttitle"> Notificaciones por email:</li>
   <li class="formlistfield">
   	<input type="radio" name="userForm[sendEmail]" value="Yes" <?php if($User->sendEmail=='Yes') print "checked"; ?>>Si
      <input type="radio" name="userForm[sendEmail]" value="No" <?php if($User->sendEmail=='No') print "checked"; ?>>No
   </li>
   <li class="formlistdesc">
     &iquest;Deseas recibir notificaciones por email (Por defecto, NO)? <br />
     Recibir&aacute;s un correo cada vez que se pausa o cambia el turno en una partida. 
     
   </li>
    
<!--Link forum options-->
	<li class="formlisttitle"> Enlaza tu cuenta con el foro</li>
   <li class="formlistfield">
		<div id="linkaccountform"></div>
   </li>
   <script type="text/javascript">
		
		jQuery.ajax({
		type: "POST",
		url: "linkforumaccount.php",
		data: {guid: "<?php print $User->id; ?>", email : "<?php print $User->email; ?>", username : "<?php print $User->username; ?>"}
		}).done(function(data) {jQuery('#linkaccountform').html(data);});
   
   </script>
   <li class="formlistdesc">
     Puedes enlazar tu cuenta con el foro desde aqui.
     
   </li>	<li class="formlisttitle">Contrase&ntilde;a</li>
	<li class="formlistfield">
		<input type="password" name="userForm[password]" maxlength=30>
	</li>
	<li class="formlistdesc">
		Tu contrase&ntilde;a en webdiplo.
	</li>

	<li class="formlisttitle">Repite la contrase&ntilde;a:</li>
	<li class="formlistfield">
		<input type="password" name="userForm[passwordcheck]" maxlength=30>
	</li>
	<li class="formlistdesc">
		Repite tu contrase&ntilde;a para comprobar que coincide.
	</li>

	<li class="formlisttitle">P&aacute;gina de Inicio:</li>
	<li class="formlistfield">
		<input type="text" size=50 name="userForm[homepage]" value="<?php print $User->homepage; ?>" maxlength=150>
	</li>
	<li class="formlistdesc">
		<?php if ( !$User->type['User'] ) print '<strong>(Optional)</strong>: '; ?>
		T&oacute; blog, p&aacute;gina personal &oacute; web favorita.
	</li>

	<li class="formlisttitle">Comentario:</li>
	<li class="formlistfield">
		<TEXTAREA NAME="userForm[comment]" ROWS="3" COLS="50"><?php
			print str_replace('<br />', "\n", $User->comment);
		?></textarea>
	</li>
	<li class="formlistdesc">
		<?php if ( !$User->type['User'] ) print '<strong>(Optional)</strong>: '; ?>
		Un comentario  &oacute; frase que desees introducir en tu perfil.
	</li>
	
<?php

if( $User->type['User'] ) {
	// If the user is registered show the list of muted users/countries:

// Patch to block Users	
	$BlockedUsers = array();
	foreach($User->getBlockUsers() as $blockUserID) {
		$BlockedUsers[] = new User($blockUserID);
	}
	if( count($BlockedUsers) > 0 ) {
		print '<li class="formlisttitle">Usuar&iacute;os bloqueados:</li>';
		print '<li class="formlistdesc">Estos son los usuar&iacute;os que has bloqueado. No se pueden unir a tus partidas ni t&uacute; a las suyas.</li>';
		print '<li class="formlistfield"><ul>';
		foreach ($BlockedUsers as $BlockedUser) {
			print '<li>'.$BlockedUser->profile_link().' '.libHTML::blocked("profile.php?userID=".$BlockedUser->id.'&toggleBlock=on&rand='.rand(0,99999).'#block').'</li>';
		}
		print '</ul></li>';
	}
	
// End Patch
	$MutedUsers = array();
	foreach($User->getMuteUsers() as $muteUserID) {
		$MutedUsers[] = new User($muteUserID);
	}
	if( count($MutedUsers) > 0 ) {
		print '<li class="formlisttitle">Jugadores silenciados:</li>';
		print '<li class="formlistdesc">Estos jugadores no pueden enviarte mensajes.</li>';
		print '<li class="formlistfield"><ul>';
		foreach ($MutedUsers as $MutedUser) {
			print '<li>'.$MutedUser->profile_link().' '.libHTML::muted("profile.php?userID=".$MutedUser->id.'&toggleMute=on&rand='.rand(0,99999).'#mute').'</li>';
		}
		print '</ul></li>';
	}

	$MutedGames = array();
	foreach($User->getMuteCountries() as $muteGamePair) {
		list($gameID, $muteCountryID) = $muteGamePair;
		if( !isset($MutedGames[$gameID])) $MutedGames[$gameID] = array();
		$MutedGames[$gameID][] = $muteCountryID;
	}
	if( count($MutedGames) > 0 ) {
		print '<li class="formlisttitle">Paises silenciados:</li>';
		print '<li class="formlistdesc">Los paises que has silenciado, no pueden enviarte mensajes.</li>';
		print '<li class="formlistfield"><ul>';
		$LoadedVariants = array();
		foreach ($MutedGames as $gameID=>$mutedCountries) {
			list($variantID) = $DB->sql_row("SELECT variantID FROM wD_Games WHERE id=".$gameID);
			if( !isset($LoadedVariants[$variantID]))
				$LoadedVariants[$variantID] = libVariant::loadFromVariantID($variantID);
			$Game = $LoadedVariants[$variantID]->Game($gameID);
			print '<li>'.$Game->name.'<ul>';
			foreach($mutedCountries as $mutedCountryID) {
				print '<li>'.$Game->Members->ByCountryID[$mutedCountryID]->country.' '.
				libHTML::muted("board.php?gameID=".$Game->id."&msgCountryID=".$mutedCountryID."&toggleMute=".$mutedCountryID."&rand=".rand(0,99999).'#chatboxanchor').'</li>';
			}
			print '</ul></li>';
		}
		print '</ul></li>';
	}
	
	// $tablMutedThreads = $DB-&gt;sql_tabl(
		// &quot;SELECT mt.muteThreadID, f.subject, f.replies, fu.username &quot;.
		// &quot;FROM wD_MuteThread mt &quot;.
		// &quot;INNER JOIN wD_ForumMessages f ON f.id = mt.muteThreadID &quot;.
		// &quot;INNER JOIN wD_Users fu ON fu.id = f.fromUserID &quot;.
		// &quot;WHERE mt.userID = &quot;.$User-&gt;id);
	// $mutedThreads = array();
	// while( $mutedThread = $DB-&gt;tabl_hash($tablMutedThreads))
		// $mutedThreads[] = $mutedThread;
	// unset($tablMutedThreads);
	
	// if( count($mutedThreads) &gt; 0 ) {
		// print '&lt;li class=&quot;formlisttitle&quot;&gt;&lt;a name=&quot;threadmutes&quot;&gt;&lt;/a&gt;Muted threads:&lt;/li&gt;';
		// print '&lt;li class=&quot;formlistdesc&quot;&gt;The threads which you muted.&lt;/li&gt;';
		
		// $unmuteThreadID=0;
		// if( isset($_GET['unmuteThreadID']) ) {
			
			// $unmuteThreadID = (int)$_GET['unmuteThreadID'];
			// $User-&gt;toggleThreadMute($unmuteThreadID);
			
			// print '&lt;li class=&quot;formlistfield&quot;&gt;&lt;strong&gt;Thread &lt;a class=&quot;light&quot; href=&quot;forum.php?threadID='.$unmuteThreadID.'#'.$unmuteThreadID.
				// '&quot;&gt;#'.$unmuteThreadID.'&lt;/a&gt; unmuted.&lt;/strong&gt;&lt;/li&gt;';
		// }
		
		// print '&lt;li class=&quot;formlistfield&quot;&gt;&lt;ul&gt;';
		
		// foreach ($mutedThreads as $mutedThread) {
			// if( $unmuteThreadID == $mutedThread['muteThreadID']) continue;
			// print '&lt;li&gt;'.
				// '&lt;a class=&quot;light&quot; href=&quot;forum.php?threadID='.$mutedThread['muteThreadID'].'#'.$mutedThread['muteThreadID'].'&quot;&gt;'.
				// $mutedThread['subject'].'&lt;/a&gt; '.
				// libHTML::muted('usercp.php?unmuteThreadID='.$mutedThread['muteThreadID'].'#threadmutes').'&lt;br /&gt;'.
				// $mutedThread['username'].' ('.$mutedThread['replies'].' replies)&lt;br /&gt;'.
				// '&lt;/li&gt;';
		// }
		// print '&lt;/ul&gt;&lt;/li&gt;';
	// }
}

/*
 * This is done in PHP because Eclipse complains about HTML syntax errors otherwise
 * because the starting <form><ul> is elsewhere
 */
print '</ul>

<div class="hr"></div>

<input type="submit" class="form-submit notice" value="Actualizar">
</form>';


?>