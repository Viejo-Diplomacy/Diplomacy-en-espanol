<?php
/*
    Copyright (C) 2013 Oliver Auth
	This file is part of vDiplomacy.
    vDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    vDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('IN_CODE') or die('This script can not be run by itself.');

require_once('lib/countryswitch.php');

$User->clearNotification('CountrySwitch');

if ( isset($_REQUEST['CancelSwitch']) )
	libSwitch::CancelSwitch((int)$_REQUEST['CancelSwitch']);

if ( isset($_REQUEST['RejectSwitch']) )
	libSwitch::RejectSwitch((int)$_REQUEST['RejectSwitch']);

if ( isset($_REQUEST['ClaimBackSwitch']) )
	libSwitch::ClaimBackSwitch((int)$_REQUEST['ClaimBackSwitch']);

if ( isset($_REQUEST['ReturnSwitch']) )
	libSwitch::ReturnSwitch((int)$_REQUEST['ReturnSwitch']);

if ( isset($_REQUEST['AcceptSwitch']) )
	libSwitch::AcceptSwitch((int)$_REQUEST['AcceptSwitch']);

if ( isset($_REQUEST['newSwitch']) )
	$error = libSwitch::NewSwitch($_REQUEST['newSwitch']);
?>

	<a name="Switch"></a>
	<form method="post"><ul class="formlist">
	<li class="formlisttitle">Países intercambiados con otro jugador:</li>
	<li class="formlistfield"><?php print libSwitch::allSwitchesHTML($User->id);?></li>
	<li class="formlistdesc">Todos los disponibles.</li>

	<?php if (isset($error) && ($error != '')) {?>
		<li class="formlisttitle">ERROR:</li>
		<li class="formlistfield"><?php print $error;?></li>
		<br>
	<?php }?>
	<li class="formlisttitle">Nuevo intercambio de países:</li>
	<li class="formlistfield">
	<TABLE> <THEAD><TH>Partida / ID</TH><TH>Enviar a jugador ID</TH><TH> </TH></THEAD><TR>
	<TD><select name="newSwitch[gameID]">
		<?php
		$sql='SELECT m.gameID, g.name FROM wD_Members m
				INNER JOIN wD_Games g ON (g.id = m.gameID)
				WHERE g.id NOT IN (SELECT gameID FROM wD_CountrySwitch WHERE ((fromID='.$User->id.' OR toID='.$User->id.') AND status IN ("Send","Active"))) AND 
				g.phase!="Pre-game" AND m.status="Playing" AND m.userID='.$User->id;
		$tabl = $DB->sql_tabl($sql);
		while(list($gameID, $gameName) = $DB->tabl_row($tabl))
			print '<option value="'.$gameID.'">'.$gameName.'</option>';
		?>
	</select></TD>
	<TD><input type="text" name="newSwitch[toID]" size="5"><br></TD>
	<TD><input type="submit" class="form-submit notice" value="Aceptar"></TD></TR></TABLE>
	<li class="formlistdesc">Selecciona la partida que deseas intercambiar temporalmente con otro usuar&iacute;o. Él deberá confirmar el intercambio, pero puedes solicitar la devoluci&oacute;n de tu juego en cualquier momento.<br>
	Nota: La ID de los jugadores se puede ver en su perfil o en el enlace que lleve a él (es el número en que acaba).</li>
	
	<li class="formlisttitle">¿C&oacute;mo funciona?</li>
	<li class="formlistfield">Te permite transpasar tu pa&iacute;s en una determinada partida a otro jugador, el cual recibe un solucitud, si acepta se har&aacute; cargo de tu partida. La posici&oacute;n podr&aacute; ser devuelta en cualquier momento al jugador original por cualquiera de los dos. Si la partida termina ser&aacute; automaticamente devuelta al jugador original.</li>
	<li class="formlistfield">Esta herramienta ha sido creada para encontrar un sustituto en partidas que otros jugadores no pueden continuar.</li>
</ul>

