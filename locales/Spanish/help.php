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

print libHTML::pageTitle('Ayuda webDiplo.com y Enlaces','Enlaces a páginas con más información acerca de webDiplo.com.');
?>
<ul class="formlist">

<li><a href="intro.php">Introducción a webdiplo.com</a></li>
<li class="formlistdesc">Una introducción de como jugar a webDiplo.com, con detalles de movimientos, tipos de unidades,
y reglas de webDiplo.com.</li>

<li><a href="faq.php">FAQ</a></li>
<li class="formlistdesc">WebDiplo.com FAQ.</li>

<li><a href="rules.php">Reglas</a></li>
<li class="formlistdesc">Reglas de webDiplo.com.</li>

<li><a href="halloffame.php">salón de la fama</a></li>
<li class="formlistdesc">Los profesionales de webdiplo.com, los top 100!</li>

<li><a href="points.php">Puntos webDiplo.com</a></li>
<li class="formlistdesc">Que son los puntos, como ganarlos y como entrar en el salón de la fama.</li>

<li><a href="profile.php">Buscar un usuario</a></li>
<li class="formlistdesc">Buscar una cuenta de usuario registrado en webdiplo.com, si conoces el numero de ID, usuario, o e-mail.</li>

<li><a href="variants.php">Información de Variantes</a></li>
<li class="formlistdesc">Una lista de las variantes disponibles en webdiplo.com, con creditos e información.</li>

<li><a href="credits.php">Creditos</a></li>
<li class="formlistdesc">Los creditos.</li>

<p>Si no encontraste la información ó ayuda que buscabas. puedes escribir un mensaje en el <a href="/foro" class="light">Foro</a>
