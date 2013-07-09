<?php

defined('IN_CODE') or die('This script can not be run by itself.');

/**
 * @package Base
 * @subpackage Static
 */
?>

<div style="color:white">
<p class="intro">
Dado que Diplomacy es un juego de comunicación, confianza (y desconfianza
y como normalmente las partidas duran un buen tiempo, es muy importante
para los jugadores que se juegue lo mejor posible y no abandonar a la mitad.</p>

<p class="intro">

El grado de <b>Fiabilidad</b> es un cálculo sencillo que representa con qué
fiabilidad envías tus órdenes y cuánto de fiable eres por jugar tus partidas hasta el final.
</p>

<div class="hr" ></div>

<p class="intro">
Tu rango depende de 2 factores importantes. Cuántos turnos has perdido al no
enviar órdenes en comparación con el número total de turnos que hayas jugado, 
y cuántas partidas has abandonado antes de terminarse.<br>
<b>Ejemplo</b>: Si un jugador falla en el 5% de sus partidas, su Fiablidad sería de 90, en un 15% sería de 70, etc.
</p>

<p class="intro">
A este grado le restamos un 10% por cada partida que hayas abandonado antes de terminar.
La penalización por las partidas "Abandonadas" puede parecer demasiado severa, pero muchas
partidas se arruinan si un jugador no juega hasta el final, y la mayoría de las veces otros jugadores 
obtienen beneficios o ventajas que no se han merecido.
<br>Pero puedes recuperar la Fiabilidad perdida reemplazando <b>partidas abiertas</b> en la que algún jugador se haya ido.
</p>

<p class="intro">
<style>
div.fraction-inline { display: inline-block; position: relative; vertical-align: middle; }
.fraction-inline > span { display: block; padding: 0; }
.fraction-inline span.divider{ position: absolute; top: 0em; display: none;	letter-spacing: -0.1em;	 }
.fraction-inline span.denominator{ border-top: thin solid black; text-align:center;}
</style>
El cálculo exacto es:
<div class="intro">
	100 &minus; (100 *
	<div class="fraction-inline">
		<span class="numerator">2 * TurnosSaltados (NMR)</span>
		<span class="divider">________________</span>
		<span class="denominator">FasesTotales</span>
	</div>
	) &minus; 10 * AbandonosDefinitivos
</div><br>
<span class="intro">

<?php
	require_once(l_r('lib/reliability.php'));		 

	if ( isset($_REQUEST['userID']) && intval($_REQUEST['userID'])>0 )
		$UserProfile = new User((int)$_REQUEST['userID']);
	else
		$UserProfile = $User;

	$mm = $UserProfile->missedMoves;
	$pp = $UserProfile->phasesPlayed;
	$cd = $UserProfile->gamesLeft - $UserProfile->leftBalanced;
	
	if (libReliability::getReliability($UserProfile) < 0)
	{
		print 'Durante los 20 primeros turnos los jugadores cuentan como "Nuevos" y no tienen grado de Fiabilidad.';
	}
	else
	{
		print 'El cálculo para '.($UserProfile == $User ? 'tu' : $UserProfile->username.'s').' Fiabilidad es:
					100 &minus; (100 *
					<div class="fraction-inline">
						<span class="numerator">2 * <b>'.$mm.'</b></span>
						<span class="divider">________________</span>
						<span class="denominator"><b>'.$pp.'</b></span>
					</div>
					) &minus; 10 * <b>'.$cd.'</b>
					= 100 &minus; '.($pp == 0 ? '0' : round(200 * $mm / $pp)).
					' &minus; '.(10 * $cd).' = <b>'.libReliability::getReliability($UserProfile).'</b>';
	}
?>
</span>
</p>

<p class="intro">
Las partidas <b>Live</b> (rápidas) <u>no</u> afectan a tu Fiabilidad.
</p>

<p class="intro">
Cuando alguien crea una partida puede seleccionar un mínimo de Fiabilidad para poder unirse a ellas,
y si tu grado es demasiado bajo no podrás incorporarte a todas las partidas que quieras.<br>
Por cada 10% en el grado de Fiabilidad podrás unirte a 1 partida. Si tu Fiabilidad es <b>91% o más</b> podrás unirte a todas las que quieras.
</p>

<?php
	if (abs(libReliability::getReliability($UserProfile)) < 100)
	{
		print '<p class="intro">
			Cómo mejorar '.($UserProfile == $User ? 'la' : $UserProfile->username.'s').' Fiabilidad (en tu caso):<ul>';
		
		if ($cd > 0)
		{
			print '<li class="intro"> Reemplaza a alguien en las "partidas Abiertas" que alguien ha abandonado. Las encuentras si hay en la pestaña "Abiertas" de la sección de partidas. Cada país en Desorden Civil (DC) que reemplaces te dará un 10% de Fiabilidad. Cada <b>'.$cd.'</b> partida'.(($cd > 1) ? 's' : '').' '.($UserProfile == $User ? 'tu' : $UserProfile->username.'s').' Fiabilidad será <b>'.round($pp == 0 ? '0' : (100 - 200 * $mm / $pp)).'</b>.</li>';
		}
		
		print '<li class="intro"> Juega más turnos sin dejar pasar las órdenes.';
		
		if ((200 * $mm / $pp) > 10)
		{
			print 'Con <b>'.$mm.'</b> órdenes no enviadas y <b>'.$pp.'</b> turnos jugados '.($UserProfile == $User ? 'tú' : $UserProfile->username).' necesitas jugar <b>'.round((100 - floor((200 * $mm / $pp) / 10 ) *10) * $pp / 200).'</b> ´turnosmás para ganar <b>'.(100 - floor((200 * $mm / $pp) / 10 ) * 10).'+</b> de Fiabilidad.</li>';
		}
				
		print '</ul></p>';
	}
?>

<p class="intro">
En cada partida tu Fiabilidad aparece como un grado después de tu nombre.
Los grados son:<br>
98+, 90+, 80+, 60+, 40+, 10+, 0 y Nuevo
</p>

<div class="hr" ></div>
<!--<p class="intro">
<b>Why should I continue a game if my country can't win?</b><br>
If you can't win a game or are on a losing position you might choose to hurt the country that sealed 
your failure as much as possible by making your defeat as hard as possible. Talk to stronger players 
on the board, they might help you, just because you have a common enemy.
</p>-->
</div>