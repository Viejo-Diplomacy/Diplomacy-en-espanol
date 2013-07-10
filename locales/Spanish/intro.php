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
header('Content-Type: text/html; charset=ISO-8859-1');
defined('IN_CODE') or die('This script can not be run by itself.');

/**
 * @package Base
 * @subpackage Static
 */

print libHTML::pageTitle('Introducción a webDiplomacy','Una guía rápida y facil para los jugadores recién llegados a Webdiplo.com.');

print '
<p>
Diplomacy en Español es un juego muy fácil de aprender pero imposible de dominar. Las reglas son todas muy intuitivas,
mucha gente aprende solo jugando, pero con este documento aprenderás mucho más facil. Si despues de leer este
documento aún te quedan dudas por favor lee:
 <p style="text-align:center"><a href="/Diplomacia_Espanol.pdf">Reglamento Diplomacy en Español.</a></p>
</p>

<div class="hr"></div>'

?>
<p style="text-align:center"><a href="#Objective">Objetivo</a> - <a href="#Units">Unidades</a> -
	<a href="#Moves">Movimientos</a> - <a href="#Rules">Reglas</a> - <a href="#Play">Jugar</a></p>

<div class="hr"></div>

<a name="Objetive"></a>
<h3>Objetivo</h3>
<p>
El objetivo del juego es ser el primero en coger 18 centros de abastecimiento. por cada nuevo centro
de abastecimiento que ocupas, consigues una nueva unidad, y pierdes una unidad por cada centro de abastecimiento
que pierdas.<br /><br />
Puedes reconocer los centros de abastecimiento por la imagen de abajo.</p>
<p style="text-align:center;">
	<img src="images/intro/supply.png" alt=" " title="Centro de abastecimiento(mapa grande)" />
	<img src="images/intro/supply2.png" alt=" " title="Centro de abastecimiento (mapa pequeno)" />
</p>

<div class="hr"></div>

<a name="Units"></a>
<h3>Unidades</h3>
<ul class="formlist">
	<li class="formlisttitle">Armada <img src="<?php print STATICSRV; ?>contrib/army.png"
		alt=" "  title="Icono de la armada" /></li>
	<li class="formlistdesc">
		Estas unidades solo se pueden moverse por tierra.
	</li>

	<li class="formlisttitle">Flota <img src="<?php print STATICSRV; ?>contrib/fleet.png"
		alt=" " title="Icono de la flota" /></li>
	<li class="formlistdesc">
		Esta unidad solo puede moverse por el mar, y los territorios costeros. Tambien
		puede mover ejercitos por el mar usando los movimientos convoy.
	</li>
</ul>

<div class="hr"></div>
<a name="Moves"></a>
<h3>Movimientos</h3>
<ul class="formlist">
	<li class="formlisttitle">Mantener</li>
	<li class="formlistdesc">
		La unidad va a defender tu territorio si es atacado, por lo demas no hace nada.
		<p style="text-align:center;">
			 <img src="images/intro/hold.png" alt=" " title="Una unidad en Napoles" />
			</p>
	</li>


	<li class="formlisttitle">Mover</li>
	<li class="formlistdesc">
		La unidad trata de moverse en(/ataque) a un territorio adyacente.
		<p style="text-align:center;">
			<img src="images/intro/armytorome.png" alt=" " title="Una unidad en Napoles se desplaza a Roma" />
			</p>
	</li>


	<li class="formlisttitle">Apoyar mantener, apoyar mover</li>
	<li class="formlistdesc">
		El apoyo es lo mas importante en webdiplomacy. Como no hay una unidad más fuerte que otra, necesitas
		para vencer la combinación de multiples unidades para atacar otros territorios.<br />
		<em>(Mueve el raton sobre las batallas mas complejas para tener una información más detallada.)</em>
		<p style="text-align:center;">
			<img src="images/intro/venecitorome.png" alt=" "
				title="En amarillo un apoyo-mover permite al ejército en Venecia conquistar el ejército en Roma" />
			<img src="images/intro/venecitorome3.png" alt=" "
				title="En verde un soporte-mantener de la flota en el mar Tirreno a Roma lo que permite sostener a una Venecia igualmente bien respaldada" />
		</p>
	</li>


	<li class="formlisttitle">Convoy</li>
	<li class="formlistdesc">
		Puedes utilizar las flotas para mover a la unidades de tierra por mar, tambien puedes encadenar varios convoys juntos
		para mover una unidad de tierra en largas distancias en un solo turno.
		.

		<p style="text-align:center;">
			<img src="images/intro/convoy.png" alt=" "
				title="Un ejército de Venecia se traslada a Túnez, transportado por la flota del Mar Adriático y en el mar Jónico" />
		</p>
	</li>
</ul>

<div class="hr"></div>
<a name="Rules"></a>
<h3>Reglas</h3>
<ul class="formlist">
<li class="formlistdesc">
	En Webdiplomacy ningún ejército o flota es mas fuerte que otro, y una unidad que <strong>defiende</strong>
	siempre sera mas fuerte que una unidad que <strong>ataca</strong> en numeros iguales.
	<p style="text-align:center;">
		<img src="images/intro/reglas.png" alt=" "
			title="Un ejército de Nápoles intenta ir a Roma, pero no tiene ningún apoyo, por lo que el ejército defensor no es desalojado" />
		<img src="images/intro/reglas1.png" alt=" "
			title="La flota y el ejército son iguales en su intento de moverse a Apulia, por lo que tampoco tiene éxito" />
	</p>
</li>


<li class="formlistdesc">
	La unica manera de ganar una batalla es <strong>moviendo</strong> una unidad con el apoyo de otra unidad,
	usando la orden <strong>apoyo a mover</strong>.
	<p style="text-align:center;">
		<img src="images/intro/venecitorome.png" alt=" "
				title="En amarillo apoyo-mover permite al ejército de Venecia conquistar el ejército de Roma" />
	</p>
</li>


<li class="formlistdesc">
	Ademas puedes apoyar una unidad con la orden <strong>mantener</strong> unidades.
	<p style="text-align:center;">
		<img src="images/intro/venecitorome3.png" alt=" "
				title="En verde soporte-mantener desde la flota en el mar Tirreno a Roma lo que permite sostener en contra de Venecia igualmente bien respaldada" />
	</p>
</li>


<li class="formlistdesc">
	Si el numero de <strong>apoyo mantener</strong> es mayor que el numero de <strong>apoyo mover</strong>
	el movimiento tendra exito, de lo contrario fallara.
	<p style="text-align:center;">
		<img src="images/intro/reglas3.png" alt=" "
				title="La flota mueve desde Trieste a Venice con dos soporte-mover, y la flota en Venicia tiene solo un soporte-mantener, por lo tanto Trieste es conquistada" />
		<img src="images/intro/reglas4.png" alt=" "
				title="La flota moviendo desde Trieste a Venice con dos soporte-mover, pero la flota en Vencia tiene dos soporte-mantener, por lo tanto Trieste y Venecia están igualadas y el ataque es bloqueado" />
	</p>
</li>

<li class="formlistdesc">
	Ademas; si una unidad esta siendo atacada tiene que defenderse por tenencia y no puede <strong>apoyar</strong> a otra unidad.
	<p style="text-align:center;">
		<img src="images/intro/reglas3.png" alt=" "
				title="Unidades no soportadas son atacadas, todas ellas cuentan con: Trieste 2 - Venecia 1. Trieste mueve" />
		<img src="images/intro/reglas6.png" alt=" "
				title="Un ejército de Munich ataca Tyrolia, evitando que el apoye a Trieste: 1 - Venecia 1, Trieste se mantiene" />
		<img src="images/intro/reglas7.png" alt=" "
				title="Una flota en el mar Tirreno ataca Roma, evitando que el apoye a Venecia: Trieste 1 - 0 Venecia, Trieste mueve" />
	</p>
</li>

</ul>
<div class="hr"></div>
<ul class="formlist">
<li class="formlistdesc">
	<a name="Play"></a>
	Con estas reglas ya sabes todo lo necesario para empezar a jugar, despues de
	<a href="register.php" class="light">registrarte</a> ya puedes
	<a href="gamecreate.php" class="light">crear una partida</a>
	o <a href="gamelistings.php" class="light">unirte a una partida existente</a>.
	<p style="text-align:center;">
		<img src="images/intro/reglas8.png" alt=" "
				title="Debido a que Prusia soporta manteniendo la flota en el Mar Báltico, el movimiento igualmente admitido para el Mar Báltico desde Livonia falla. Esto permite a la flota en el Mar Báltico el éxito de enviar un ejército en convoy de Berlín a Suecia"  />
	</p>
	</li>
</ul>