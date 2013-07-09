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

?>
	<li class="formlisttitle">Modificar orden en que se muestran las unidades:</li>
	<li class="formlistfield">
		Tipo de unidad:<select name="userForm[unitOrder]">
			<option value='Mixed'<?php if($User->unitOrder=='Mixed') print "selected"; ?>>Todas</option>
			<option value='FA'   <?php if($User->unitOrder=='FA')    print "selected"; ?>>Flotas -> Ejércitos</option>
			<option value='AF'   <?php if($User->unitOrder=='AF')    print "selected"; ?>>Ejércitos -> Flotas</option>
		</select> - 
		Ordenar por: <select name="userForm[sortOrder]">
			<option value='BuildOrder'<?php if($User->sortOrder=='BuildOrder') print "selected"; ?>>Antigüedad</option>
			<option value='TerrName'  <?php if($User->sortOrder=='TerrName')   print "selected"; ?>>Nombre del territorio</option>
			<option value='NorthSouth'<?php if($User->sortOrder=='NorthSouth') print "selected"; ?>>Norte -> Sur</option>
			<option value='EastWest'  <?php if($User->sortOrder=='EastWest')   print "selected"; ?>>Este -> Oeste</option>
		</select>
	</li>
	<li class="formlistdesc">
		Cómo se ordenan las unidades en el espacio para enviar las órdenes.
	</li>
	
	<li class="formlisttitle">Mostrar nombre del pa&iacute;s:</li>
	<li class="formlistfield">
		<strong>En el chat global:</strong>
		<input type="radio" name="userForm[showCountryNames]" value="Yes" <?php if($User->showCountryNames=='Yes') print "checked"; ?>>Si
		<input type="radio" name="userForm[showCountryNames]" value="No"  <?php if($User->showCountryNames=='No')  print "checked"; ?>>No
	</li>
	<li class="formlistfield">
		<strong>En el mapa:</strong>
		<input type="radio" name="userForm[showCountryNamesMap]" value="Yes" <?php if($User->showCountryNamesMap=='Yes') print "checked"; ?>>Si
		<input type="radio" name="userForm[showCountryNamesMap]" value="No"  <?php if($User->showCountryNamesMap=='No')  print "checked"; ?>>No
	</li>
	<li class="formlistdesc">
		La primera opción muestra un pequeño cartel con el nombre del país que posee las unidades o los territorios.<br>
		La segunda elimina los colores del chat y en su lugar coloca el nombre del país que ha enviado el mensaje.
	</li>

	<li class="formlisttitle">Ajustar color para la deficiencia de visi&oacute;n:</li>
	<li class="formlistfield">
		<select name="userForm[colorCorrect]">
			<option value='Off'        <?php if($User->colorCorrect=='Off')         print "selected"; ?>>Ninguno</option>
			<option value='Protanope'  <?php if($User->colorCorrect=='Protanope')   print "selected"; ?>>Protanopia</option>
			<option value='Deuteranope'<?php if($User->colorCorrect=='Deuteranope') print "selected"; ?>>Deuteranopia</option>
			<option value='Tritanope'  <?php if($User->colorCorrect=='Tritanope')   print "selected"; ?>>Tritanopia</option>
		</select>
	</li>
	<li class="formlistdesc">
		Mejora los mapas para las personas con problemas de daltonismo. 
	</li>

<?php
/*
 * This is done in PHP because Eclipse complains about HTML syntax errors otherwise
 * because the starting <form><ul> is elsewhere
 */
print '</ul>

<div class="hr"></div>

<input type="submit" class="form-submit notice" value="Actualizar">
</form>';

?>