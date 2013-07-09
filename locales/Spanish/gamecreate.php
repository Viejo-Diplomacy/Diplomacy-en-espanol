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
<div class="content-bare content-board-header content-title-header">
<div class="pageTitle barAlt1">
	Crear una nueva partida
</div>
<div class="pageDescription barAlt2">
Comenza una nueva partida, tú decides el nombre, cuánto duran los turnos, y mucho más.
</div>
</div>
<div class="content content-follow-on">
<form method="post">
<ul class="formlist">

	<li class="formlisttitle">
		Nombre:
	</li>
	<li class="formlistfield">
		<input type="text" name="newGame[name]" value="" size="30" onkeypress="if (event.keyCode==13) this.blur(); return event.keyCode!=13">
	</li>
	<li class="formlistdesc">
		El nombre de tu partida
	</li>

	<li class="formlisttitle">
		Duración de la fase de Diplomacia (Diplomacy): (5 minutos - 7 días)
	</li>
	<li class="formlistfield">
		<select id="phaseMinutes" name="newGame[phaseMinutes]" onchange="{document.getElementById('wait').selectedIndex = this.selectedIndex;document.getElementById('wait2').selectedIndex = this.selectedIndex;}">
		<?php
			$phaseList = array(5, /*10,*/ 15, /*20,*/ 30,
				60, /*120,*/ /*240,*/ 360, /*480,*/ /*600,*/ 720, /*840, 960, 1080, 1200, 1320,*/
				1440, /*1440+60,*/ 2160, 2880, 2880+60*12, 4320, 5760, 7200, 8640, 10080, /*14400*/);

			foreach ($phaseList as $i) {
				$opt = libTime::timeLengthText($i*60);

				print '<option value="'.$i.'"'.($i==1440 ? ' selected' : '').'>'.$opt.'</option>';
			}
		?>
		<!--<option value="0">Custom</option>-->
		</select>
		<span id="phaseHoursText" style="display:none">
			 - Phase length: <input type="text" id="phaseHours" name="newGame[phaseHours]" value="24" size="4" style="text-align:right;"
			 onkeypress="if (event.keyCode==13) this.blur(); return event.keyCode!=13"
			 onChange="
			  this.value = parseInt(this.value);
			  if (this.value == 'NaN' ) this.value = 24;
			  if (this.value < 1 ) this.value = 1;
			  document.getElementById('phaseMinutes').selectedIndex = 28;
			  document.getElementById('phaseMinutes').options[28].value = this.value * 60;
			  document.getElementById('wait').selectedIndex = 17;" > hours.
		</span>

	</li>
	<li class="formlistdesc">
		El número máximo de horas permitidas para que los jugadores puedan discutir las acciones en cada fase. Más tiempo de duración de la fase significa más tiempo para tomar decisiones y hacer negociaciones, pero hace que el juego sea más lento. Y menos 
tiempo de duración de fase hace que el juego sea más rápido, pero requiere que los jugadores se conecten a la partida con más frecuencia. <br/> 
	<strong>Por defecto:</strong> 24 horas/1 día
	</li>
<!--- COMIENZO MODIFICACION TIEMPO FASES / BEGIN PHASE TIME MODIFICATION --->
	<ul class="formlist">
	<li class="formlisttitle">
		Personalizar fases de Retiradas y Ajustes militares
	</li>
	 <small>(Por defecto, igual que la fase de Diplomacia)</small>
	<table><tbody><tr>
	<td align="left" width="0%">
	<li class="formlistfield">
		<select id="wait" name="newGame[phase2Minutes]">
		<?php
			//$phaseList = array(5, 10, 15, 20, 30,
			//	60, 120, 240, 360, 480, 600, 720, 840, 960, 1080, 1200, 1320,
			//	1440, 2160, 2880, 4320, 5760, 7200, 8640, 10080, 14400, 1440+60, 2880+60*2);

			foreach ($phaseList as $i) {
				$opt = libTime::timeLengthText($i*60);

				print '<option value="'.$i.'"'.($i==1440 ? ' selected' : '').'>'.$opt.'</option>';
			}
		?>
		</select>
	</li>
	</td>
	<td align="left" width="100%">
	<li class="formlistdesc">
		<strong>Retiradas</strong> (Retreats) <br/><hr>
		Aquí puedes establecer la duración para la fase de Ajustes, por si quieres hacerla más breve que la normal de Diplomacia.  
	</li>
	</td>
	</tr>
	
	<li class="formlisttitle">
		<!--Duración de la fase Ajustes militares (Builds): (5 minutos - 7 días)-->
	</li>
	<tr>
	<td align="left" width="0%">
	<li class="formlistfield">
		<select id="wait2" name="newGame[phase3Minutes]" >
		<?php
			//$phaseList = array(5, 10, 15, 20, 30,
			//	60, 120, 240, 360, 480, 600, 720, 840, 960, 1080, 1200, 1320,
			//	1440, 2160, 2880, 4320, 5760, 7200, 8640, 10080, 14400, 1440+60, 2880+60*2);

			foreach ($phaseList as $i) {
				$opt = libTime::timeLengthText($i*60);

				print '<option value="'.$i.'"'.($i==1440 ? ' selected' : '').'>'.$opt.'</option>';
			}
		?>
		</select>
	</li>
	</td>
	<td align="left" width="100%">
	<li class="formlistdesc">
		<strong>Ajustes militares</strong> (Builds) <br/><hr>
		Aquí puedes establecer la duración para la fase de Ajustes, por si quieres hacerla más breve que la normal de Diplomacia.  
	</li>
	</td>
	</tbody></table>
	<!--- FIN MODIFICACION TIEMPO FASES / END PHASE TIME MODIFICATION --->


	<li class="formlisttitle">
		Apuesta: (2<?php print libHTML::points(); ?> -
			<?php print $User->points.libHTML::points(); ?>)
	</li>
	<li class="formlistfield">
		<input type="text" name="newGame[bet]" size="7" value="<?php print $formPoints ?>" 
			onkeypress="if (event.keyCode==13) this.blur(); return event.keyCode!=13"
			onChange="
				this.value = parseInt(this.value);
				if (this.value == 'NaN' ) this.value = <?php print $defaultPoints; ?>;
				if (this.value < 2) this.value = 2;
				if (this.value > <?php print $User->points; ?>) this.value = <?php print $User->points; ?>;"
			/>
	</li>
	<li class="formlistdesc">
		La apuesta requerida para unirse a esta partida. Esta es la cantidad de puntos que todos los jugadores,
		incluido tú deben de poner para unirse a la partida. (<a href="points.php" class="light">Leer más</a>). 
		Nota: las variantes de menos de 7 jugadores, pueden tener limitada la apuesta máxima.<br />
		<?php
			if (isset(Config::$limitBet))
			{
				print 'Hay algunas restricciones sobre cuántos '.libHTML::points().' están permitidos en función del número de jugadores de tu partida.<br />';
				$first=true;
				foreach (Config::$limitBet as $limit=>$bet)
				{
					if ($first)
					{
						print '('.$limit.' jugadores permite una apuesta máxima de '.$bet.libHTML::points().',';
						$first = false;
					}
					else
						print $limit.' jugadores: '.$bet.libHTML::points().', ';
				}
				print 'variantes con más jugadores no tienen límite.)';
				print '<br />';
			}
		?>
		<br />

		<strong>Por defecto:</strong> <?php print $defaultPoints.libHTML::points(); ?>
	</li>
	
<?php
if( count(Config::$variants)==1 )
{
	foreach(Config::$variants as $variantID=>$variantName) ;

	$defaultVariantName=$variantName;

	print '<input type="hidden" name="newGame[variantID]" value="'.$variantID.'" />';
}
else
{
?>
	<li class="formlisttitle">Variantes/reglas:</li>
	<li class="formlistfield">
	
	<script type="text/javascript">
	function setExtOptions(i){
		document.getElementById('countryID').options.length=0;
		switch(i)
		{
			<?php
			$checkboxes=array();
			$first='';
			foreach(Config::$variants as $variantID=>$variantName)
			{
				$Variant = libVariant::loadFromVariantName($variantName);
				if (!(isset($Variant->disabled))) {
				$checkboxes[$variantName] = '<option value="'.$variantID.'"'.(($first=='')?' selected':'').'>'.$variantName.'</option>';
				if($first=='') {
					$first='"'.$variantID.'"';
					$defaultName=$variantName;
				}
				
				print "case \"".$variantID."\":\n";
				print 'document.getElementById(\'desc\').innerHTML = "<a class=\'light\' href=\'variants.php?variantID='.$variantID.'\'>'.$Variant->fullName.'</a><hr style=\'color: #aaa\'>'.$Variant->description.'";'."\n";		
				print "document.getElementById('countryID').options[0]=new Option ('Random','0');";
				for ($i=1; $i<=count($Variant->countries); $i++)
					print "document.getElementById('countryID').options[".$i."]=new Option ('".$Variant->countries[($i -1)]."', '".$i."');";
				print "break;\n";	
				}	
			}	
			ksort($checkboxes);	
			?>	
		}
	}
	</script>
	
	<table><tr>
		<td	align="left" width="0%">
			<select name="newGame[variantID]" onChange="setExtOptions(this.value)">
			<?php print implode($checkboxes); ?>
			</select> </td>
		<td align="left" width="100%">
			<div id="desc" style="border-left: 1px solid #aaa; padding: 5px;"></div></td>
	</tr></table>
	</li>
	<li class="formlistdesc">
		Selecciona el tipo variante que te gustaría jugar, haz click sobre el nombre de la variante para
		obtener más información.<br /><br />

		
		<strong>Por defecto:</strong> <?php print $defaultName;?>
	</li>
<?php
}
?>
	<li class="formlisttitle">Asignación de países:</li>
	<li class="formlistfield">
		<select id="countryID" name="newGame[countryID]">
		</select>
	</li>

	<li class="formlistdesc">
		Distribución al azar de cada país, o los jugadores pueden elegir su país .<br /><br />
		<strong>Por Defecto:</strong> Aleatorio (Random).
	</li>
	
	<script type="text/javascript">
	setExtOptions(<?php print $first;?>);
	</script>
	
	<li class="formlisttitle">Tipo de apuesta:</li>
	<li class="formlistfield">
		<input type="radio" name="newGame[potType]" value="Winner-takes-all" checked > Todo o Nada<br />
		<input type="radio" name="newGame[potType]" value="Points-per-supply-center"> Puntos por centro (PPC)
	</li>
	<li class="formlistdesc">
		Una configuración experta: las ganancias deben dividirse segun los centros de abastecimiento que tenga cada
		jugador, o el ganador se lleva todos los puntos (<a href="points.php#ppscwta" class="light">Leer más</a>).<br /><br />

		<strong>Por defecto:</strong> Todo o Nada
	</li>

	<li class="formlisttitle">
		Jugadores anónimos:
	</li>
	<li class="formlistfield">
		<input type="radio" name="newGame[anon]" value="Yes" checked>Sí
		<input type="radio" name="newGame[anon]" value="No">No
	</li>
	<li class="formlistdesc">
		Si lo habilitas no sabrás ni tú ni los demas jugadores contra quién estáis jugando hasta que termine la partida.<br /><br />

		<strong>Por defecto:</strong> Sí, los jugadores serán anónimos
	</li>
	
	<li class="formlisttitle">
		Deshabilitar mensajes en la partida:
	</li>
	<li class="formlistfield">
		<input type="radio" name="newGame[pressType]" value="Regular" checked>Permitir todos
		<input type="radio" name="newGame[pressType]" value="PublicPressOnly">Sólo mensajes globales, sin privados
		<input type="radio" name="newGame[pressType]" value="NoPress">Sin mensajes Gunboat)
	</li>
	<li class="formlistdesc">
		
		<br /><br /><strong>Por defecto:</strong> Permitir todos
	</li>
	
</ul>

<div class="hr"></div>

<div id="AdvancedSettingsButton">
<ul class="formlist">
	<li class="formlisttitle">
		<a href="#" onclick="$('AdvancedSettings').show(); $('AdvancedSettingsButton').hide(); return false;">
		Abrir configuración avanzada
		</a>
	</li>
	<li class="formlistdesc">
		La configuración avanzada permite una personalización completa del juego para los jugadores más
		expertos, lo que permite diferentes opciones del mapa, reglas alternativas y opciones de sincronización
		no estándar.<br /><br />

		La configuración por defecto está bien para <strong>nuevos jugadores</strong>.
	</li>
</ul>
</div>

<div id="AdvancedSettings" style="<?php print libHTML::$hideStyle; ?>">

<h3>Configuración avanzada</h3>

	<li class="formlisttitle">
		Tiempo para unirse a la partida: (5 minutos - 7 días)
	</li>
	<li class="formlistfield">
		<select id="wait" name="newGame[joinPeriod]">
		<?php
			foreach ($phaseList as $i) {
				$opt = libTime::timeLengthText($i*60);
				print '<option value="'.$i.'"'.($i==1440*7 ? ' selected' : '').'>'.$opt.'</option>';
			}
		?>
		</select>
	</li>
	<li class="formlistdesc">
		El tiempo de espera para que los jugadores se unan a la partida.

		<br /><br /><strong>Por defecto:</strong> 7 días
	</li>
	
	<li class="formlisttitle">
		Requisitos para unirse a la partida:
	</li>
	<script type="text/javascript">
		function changeMinPhases(i){
			if (i > 0) {
				document.getElementById('minPhases').options[0].value = '20';
				document.getElementById('minPhases').options[0].text  = '20+';
			} else {
				document.getElementById('minPhases').options[0].value = '0';
				document.getElementById('minPhases').options[0].text  = 'none';
			}
		}
	</script>
	<li class="formlistfield">
		<b>Fiabilidad mínima: </b><select name="newGame[minRating]" onChange="changeMinPhases(this.value)">
			<option value=0 selected>ninguno</option>
			<?php
				foreach (libReliability::$grades as $limit=>$grade)
					if ($limit > 0)
						print '<option value='.$limit.'>'.$grade.'</option>';
			?>
			</select> / 
		<b>Fases minimas: </b><select id="minPhases" name="newGame[minPhases]">
			<option value=0 selected>ninguna</option>
			<option value=50>50+</option>
			<option value=100>100+</option>
			<option value=300>300+</option>
			<option value=600>600+</option>
			</select>
	</li>
	<li class="formlistdesc">
		Puedes establecer algunos requisitos para que los jugadores se puedan unir a tú partida.		
		<ul>
			<li><b>Fiabilidad mínima:</b> La fiabilidad mínima que un jugador debe tener para unirse a tu partida.</li>
			<li><b>Fases minimas:</b> Cuántas fases debe haber jugado para unirse a tu partida.</li>
		</ul>
		Esto podría dar lugar a que no encontraras suficientes personas para unirse a tu partida, sé cuidadoso al elejir las opciones.<br /><br />
		<strong>Por defecto:</strong> Sin restricciones.
	</li>

	<li class="formlisttitle">
		Política NMR (Turnos sin movimientos):
	</li>
	<li class="formlistfield">
		<?php 
			$specialCDturnsTxt = ( Config::$specialCDturnsDefault == 0 ? 'off' : (Config::$specialCDturnsDefault > 99 ? '&infin;' : Config::$specialCDturnsDefault) );
			$specialCDcountTxt = ( Config::$specialCDcountDefault == 0 ? 'off' : (Config::$specialCDcountDefault > 99 ? '&infin;' : Config::$specialCDcountDefault) );
		?>
		
		<input type="hidden" id="specialCDturn"  name="newGame[specialCDturn]"  value="<?php print $specialCDturnsTxt;?>">
		<input type="hidden" id="specialCDcount" name="newGame[specialCDcount]" value="<?php print $specialCDcountTxt;?>">
		
		<select id="NMRpolicy" name="newGame[NMRpolicy]" 
			onChange="
				if (this.value == 'c/c') {
					$('NMRpolicyCustom').show();
					$('NMRpolicyText').hide();
				} else {
					opt = this.value.split('/');
					document.getElementById('specialCDturn').value  = opt[0];
					document.getElementById('specialCDcount').value = opt[1];
					if (opt[0] == 0) opt[0] = 'off'; if (opt[0] > 90) opt[0] = '&infin;'; 
					if (opt[1] == 0) opt[1] = 'off'; if (opt[1] > 90) opt[1] = '&infin;'; 
					document.getElementById('specialCDturnCustom').value  = opt[0];
					document.getElementById('specialCDcountCustom').value = opt[1];
					document.getElementById('NMRpolicyText').innerHTML = ' - Turns: <b>' + opt[0] + '</b> - Delay: <b>' + opt[1] + '</b>';
					$('NMRpolicyCustom').hide();
					$('NMRpolicyText').show();
				}
			">
			<option value="0/0">Ninguna</option>
			<option value="<?php print $specialCDturnsTxt;?>/<?php print $specialCDcountTxt;?>" selected>Por defecto</option>
			<option value="5/2">Comedida</option>
			<option value="99/99">Seria</option>
			<option value="c/c">Personalizada</option>
		</select>
		
		
		<span id="NMRpolicyCustom" style="display:none">
			 - Turnos: </b><input 
							type="text" 
							id="specialCDturnCustom" 
							size="2" 
							value='<?php print $specialCDturnsTxt; ?>'
							onkeypress="if (event.keyCode==13) this.blur(); return event.keyCode!=13"
							onChange="document.getElementById('NMRpolicy').selectedIndex = 4;
								if (this.value == 'off') this.value = 0;
								this.value = parseInt(this.value);
								document.getElementById('specialCDturn').value  = this.value;
								if (this.value > 90) this.value = '&infin;';
								if (this.value == 0) this.value = 'off';"
							>
			 - Retraso: </b><input
							type="text"
							id="specialCDcountCustom"
							value = '<?php print $specialCDcountTxt; ?>'
							onkeypress="if (event.keyCode==13) this.blur(); return event.keyCode!=13"
							onChange="document.getElementById('NMRpolicy').selectedIndex = 4;
								if (this.value == 'off') this.value = 0;
								this.value = parseInt(this.value);
								document.getElementById('specialCDcount').value = this.value;
								if (this.value > 90) this.value = '&infin;';
								if (this.value == 0) this.value = 'off';"
							size="2"
							> 
		</span>
		<span id="NMRpolicyText">
			 - Turnos: <b><?php print $specialCDturnsTxt;?></b> - Retraso: <b><?php print $specialCDcountTxt;?></b>
		</span>
	</li>
	<li class="formlistdesc">
		Esta regla pone a los jugadores dentro de Desorden Civil (DC) si no hay movimientos en su turno (NMR).
		<ul>
		<li><strong>Turnos:</strong> Durante cuántos turnos tendrá efecto esta regla. Atención, un turno puede tener hasta tres fases.
		Ejemplo: Seleccionar "2" podrá colocar en DC al país que no mueva durante las fases de Diplomacia, Retiradas y Ajustes de los dos primeros turnos (normalmente Primavera y Otoño).</li>
		<li><strong>Retraso:</strong> Cuánto tiempo se establece para encontrar un reemplazo del jugador (el tiempo que tenga la fase se alargará tantas veces como se seleccione). 
		Ejemplo: si en una partida de 2 días por fase seleccionaras un "Retraso" de "3", el tiempo para buscar un reemplazo se podría alargar hasta 6 días.
		Seleccionar 0 (cero) mandaría al país a Desorden Civil, pero el turno continuaría con normalidad. Los países con 1 centro o menos no contarán para detener la partida.</li>
		</ul>
		Cualquier cantidad mayor de 90 colocará un valor de &infin;, seleccionar cero lo desactivaría (off).
		<br /><br /><strong>Por defecto:</strong> <?php print $specialCDturnsTxt;?> / <?php print $specialCDcountTxt;?>
	</li>

	<li class="formlisttitle">
		Condiciones alternativas de victoria:
	</li>
	<li class="formlistfield"> 
		<b>Número de centros: </b><input type="text" name="newGame[targetSCs]" size="4" value="0"
			onkeypress="if (event.keyCode==13) this.blur(); return event.keyCode!=13"
			onChange="
				this.value = parseInt(this.value);
				if (this.value == 'NaN' ) this.value = 0;"
		/> (0 = Por defecto)<br>
		<b>Máximo de turnos: </b><input type="text" name="newGame[maxTurns]" size="4" value="0"
			onkeypress="if (event.keyCode==13) this.blur(); return event.keyCode!=13"
			onChange="
				this.value = parseInt(this.value);
				if (this.value == 'NaN' ) this.value = 0;
				if (this.value < 4 && this.value != 0) this.value = 4;
				if (this.value > 200) this.value = 200;"
		/> (4 < Turnos m&aacute;ximos < 200)
	</li>
	<li class="formlistdesc">
		Puedes limitar el número máximo de turnos que durará la partida y/o cuántos centros hay que capturar para que se declare la victoria.
		Por favor, comprueba en la descripción de cada variante la duración media en turnos o el núemro de centros por defecto.<br />
		Cuando se alcanza el máximo de turnos marcado, ganará el jugador que tenga más centros al final de la fase de Diplomacia de ese turno.
		Si 2 o más jugadores tienen el mismo número de centros al final de la partida, se comprobará el turno anterior, o el anterior, etc., hasta que se deshaga el empate.
		Si el número de centros de los jugadores ha sido idéntico durante toda la partida, el ganador se decidirá aleatoriamente.
		<br />Un falor de "0" (por defecto) finalizará la partida como es habitual, cuando un jugador alcance el objetivo de centros.
		<br /><br /><strong>Por defecto:</strong> 0 (no se establece duración / número de centros habitual de la variante)
	</li>

	<?php
		if ($User->id == 7)
			print '
				<li class="formlisttitle">
					Reloj/Contador [Experimental]:
				</li>
				<li class="formlistfield">
					<b>Horas: </b><input type="text" name="newGame[chessTime]" value="0" size="8">
				</li>
				<li class="formlistdesc">
					Si quieres usar un reloj, puedes introducir aquí el tiempo que tiene cada jugador en su reloj.
				</li>
			';
		else
			print '
				<input type="hidden" name="newGame[chessTime]" value="0"
			';
	?>
	
	<li class="formlisttitle">
		<img src="images/icons/lock.png" alt="Private" /> Contraseña para la partida (opcional):
	</li>
	<li class="formlistfield">
		<ul>
			<li>Contraseña: <input type="password" name="newGame[password]" value="" size="30" /></li>
			<li>Confirmar: <input type="password" name="newGame[passwordcheck]" value="" size="30" /></li>
		</ul>
	</li>
	<li class="formlistdesc">
		<strong>Es opcional.</strong> Puede que no haya suficientes jugadores que conozcan tú contraseña para entrar.<br /><br />

		<strong>Por defecto:</strong> Sin contraseña
	</li>
</ul>

</div>

<div class="hr"></div>

<p class="notice">
	<input type="submit" class="form-submit" value="Create">
</p>
</form>