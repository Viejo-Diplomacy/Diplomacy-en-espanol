<?php

defined('IN_CODE') or die('This script can not be run by itself.');

/**

 * @package Base

 * @subpackage Static

 */

?>



<div style="color:white">

<p class="intro">

El sistema utiliza un procedimiento similiar al ranking ELO usado en el ajedrez, y se basa en el siguiente esquema: <br>

</p> 

<p class="">

- Cada partida se divide en varias partidas 1 contra 1.<br>

- Los puntos finales son la suma de todas las subpartidas.<br>

- Cada partida sufrirá un ajuste dependiendo del número de jugadores

(una partida de 34 jugadores no puntúa 16 veces más que un de 2).<br>



</p>



<p class="intro"> 

Cómo puntúa cada sub-partida <br>(se trata de un esquema básico que no tiene por qué estar actualizado en detalle):

<br> 

La puntuación depende de cómo es el resultado real en comparación con el resultado esperable, y cuánto de válido es el resultado real.

</p>



<p class="intro"> 

Resultado esperable Jugador1 (Re1) y jugador2 (Re2):<br>

- Re1 = 1 / ( 1 + ( 10^( (Rating2 - Rating1) / 400) ) )<br>

- Re2 = 1 - Re1<br>

</p>



<p class="intro">  

Ajustes de los resultados para el cálculo:<br>

-Pone los Centros de los jugadores que hayan abandonado o en desorden civil a 0 (cero), y su ratio a 0 también.<br>

- Todo-o-Nada: pone a cero el contador de centros de todos salvo del ganador.<br>

- Empate: ajusta el contador de centros de manera que cada empatado tenga el mismo número.<br>

- Puntos por Centro: No cambia nada de los datos.<br>



<br>

Cálculo: <br/>

Rr1 = Pl1SCs/(Pl1SCs + Pl2SCs) (si ambos tienen 0 centros pone Rr1 = 0) <br>

Rr2 = Pl2SCs/(Pl1SCs + Pl2SCs) (si ambos tienen 0 centros pone Rr1 = 0)<br>

<br>

La puntuación se basa en la diferencia (D1) de ambos resultados:<br>

D1 = Rr1 - Re1<br>

D2 = Rr2 - Re2<br>

<br>

Ajustes para cada sub-partida(matchValue=mV):<br>

- Valora la importancia de las sustituciones. (Si un jugador apuesta solo la mitad de toda la partida su beneficio solo será de la mitad).<br>

- Normaliza el mV con la diferencia de Centros% de ambos jugadores<br>

<br>

Ajustes para cada partida(gameValue=gV):<br>

- El valor de la partida (gV) se establece: ((númeroPaíses -1 ) / númeroPaíses )^3 * (1 - (númeroPaíses / 100)<br>

Esto ortorga un gV muy bajo a partidas con pocos jugadores, y lo aumenta cuantos más jugadores haya en juego.<br>

- gV = 0 para las partidas de Rinascimento y Guerra Civil Española, que tienen sistemas de puntuación diferentes.<br>

- Si el ganador no alcanza el ObjetivoCentros por los ajustes opcionales de partida, se ajusta la importancia de la partida también.<br>

<br>

Factor-K (K) se establece en 40 para obtener cambios enpuntos reales desde el resultado 0.xx.<br>

<br>

Cálculo final para cada sub-partida:<br>

puntosPartidaJugador1 = K * gV * mV * D1<br>

puntosPartidaJugador2 = K * gV * mV * D2<br>

 

Cálculo final para cada partida:<br>

puntosJugador1 = suma ( puntuaciones de cada partida)<br>

</p>



<div class="hr" ></div>



Los jugadores comienzan con una puntuación ELO de 1000 y está bajará o subirá en función de los resultados y los resultados esperables. 

<br>Ej: un jugador que acaba de llegar al servidor y derrota en una partida a jugadores con más ELO (más veteranos) obtendrá mayor aumento que si un jugador veterano (con ELO alto) derrota a jugadores recién llegados y ELO bajo.

</div>