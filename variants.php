<?php
/*
Copyright (C) 2004-2011 Oliver Auth

This file is part of vDiplomacy.

webDiplomacy is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

webDiplomacy is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANT<?php
/*
Copyright (C) 2004-2011 Oliver Auth

This file is part of vDiplomacy.

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

/**
* @package Base
* @subpackage Static
*/

require_once('header.php');

libHTML::starthtml();

if(!(isset($_REQUEST['variantID'])))
{
	print '<script type="text/javascript" src="contrib/tablekit/tablekit.js"></script>';
	print libHTML::pageTitle('Variantes','Una lista de las variantes disponibles en este servidor, con la información y las reglas específicas para cada una de ellas.');
	$variantsOn=array();
	$variantsOff=array();

$variants = glob('variants/*');
foreach($variants as $variantDir) {
	// $variantDir = '/home/webdiplo/public_html/'.$variantDir;
	if( is_dir($variantDir) && file_exists($variantDir.'/variant.php') )
	{
      $variantDir=substr($variantDir,9);
      if( in_array($variantDir, Config::$variants) )
         $variantsOn[] = $variantDir;
      else
         $variantsOff[] = $variantDir;
   }
}
	
	if( count($variantsOff) )
		print '<a name="top"></a><h4>Variantes activas:</h4>';
	
	print '<style type="text/css">
			.sortcol { cursor: pointer;
				padding-right: 20px;
				background-repeat: no-repeat;
				background-position: right center; }
			.sortasc {
				background-color: #DDFFAC;
				background-image: url(contrib/tablekit/up.gif); }
			.sortdesc {
				background-color: #B9DDFF;
				background-image: url(contrib/tablekit/down.gif); }
			.nosort { cursor: default;} 
		</style>';
		
	print '<TABLE class="sortable">
				<THEAD>
					<TH style="border: 1px solid #000" class="sortfirstasc">Nombre</TH>
					<TH style="border: 1px solid #000">Jugadores</TH>
					<TH style="border: 1px solid #000">Terminadas</TH>
					<TH style="border: 1px solid #000">Media Turnos</TH>
					<TH style="border: 1px solid #000">Ratio*</TH>
					<TH style="border: 1px solid #000">Actividad**</TH>
				</THEAD>
				<TFOOT>
					<tr style="border: 1px solid #666"><td colspan=6><b>**Ratio</b> = ("jugadores" x "partidas jugadas") - <b>**Actividad</b> = Número de partidas abiertas</td></tr>
				</TFOOT>';
			
	foreach( $variantsOn as $variantName )
	{
		$Variant = libVariant::loadFromVariantName($variantName);
		list($players)=$DB->sql_row(
			'SELECT COUNT(*) FROM wD_Members m
				INNER JOIN wD_Games g ON (g.id = m.gameID) 
			WHERE g.variantID='.$Variant->id.' AND g.phase = "Finished"');
		list($turns,$games) = $DB->sql_row('SELECT SUM(turn), COUNT(*) FROM wD_Games WHERE variantID='.$Variant->id.' AND phase = "Finished"');
		list($hot) = $DB->sql_row('SELECT COUNT(*) FROM wD_Games WHERE variantID='.$Variant->id.' AND phase != "Finished" AND phase != "Pre-game"');
		print '<TR><TD style="border: 1px solid #666">'.$Variant->link().'</TD>';
		print '<TD style="border: 1px solid #666">'.($games==0?count($Variant->countries):round($players/$games,2)) .' jugadores</TD>';
		print '<TD style="border: 1px solid #666">'.$games.' partida'.($games!=1?'s':'').'</TD>';
		print '<TD style="border: 1px solid #666">'.($games==0?'0.00':number_format($turns/$games,2)).' turnos</TD>';
		print '<TD style="border: 1px solid #666">'.$players.'</TD>';
		print '<TD style="border: 1px solid #666">'.$hot.'</TD></TR>';
	}
	print '</TABLE>';

	if( count($variantsOff) )
	{
		print '<h4>Variantes desactivadas</h4>';
		print '<p>Implementadas pero no activadas en este servidor.</p>';
		print '<ul>';
		foreach( $variantsOff as $variantName )
		{
			$Variant = libVariant::loadFromVariantName($variantName);
			print '<li>' . $Variant->name . '</a> (' . count($Variant->countries) . ' jugadores)</li>';
		}
		print '</ul>';
	}

	print '<div class="hr"></div>';
}
else
{
	$id=intval($_REQUEST['variantID']);
	if (!(isset(Config::$variants[$id])))
		foreach (array_reverse(Config::$variants,true) as $id => $name);
	$Variant = libVariant::loadFromVariantID($id);
	print libHTML::pageTitle($Variant->fullName . ' (' . count($Variant->countries) . ' jugadores)',$Variant->description);
	print '<div style="text-align:center"><span id="Image_'. $Variant->name . '"> <a href="';
		if (file_exists(libVariant::cacheDir($Variant->name).'/sampleMapLarge.png'))
			print libVariant::cacheDir($Variant->name).'/sampleMapLarge.png';
		else
			print 'map.php?variantID=' . $Variant->id. '&largemap';	
	print '" target="_blank"> <img src="';
	if (file_exists(libVariant::cacheDir($Variant->name).'/sampleMap.png'))
		print libVariant::cacheDir($Variant->name).'/sampleMap.png';
	else
		print 'map.php?variantID=' . $Variant->id;
	print '" alt="Open large map" title="Mapa de la variante '. $Variant->name .' " /></a></span> </div><br />';

				
	
	
	print '<table>
		<td style="text-align:left">Buscar partidas: 		
			<form style="display: inline" action="gamelistings.php" method="POST">
				<input type="hidden" name="gamelistType" value="New" />
				<input type="hidden" name="searchOff" value="true" />
				<input type="hidden" name="search[chooseVariant]" value="'.$Variant->id.'" />
				<input type="submit" value="Nuevas" /></form>							
			<form style="display: inline" action="gamelistings.php" method="POST">
				<input type="hidden" name="gamelistType" value="Open" />
				<input type="hidden" name="searchOff" value="true" />
				<input type="hidden" name="search[chooseVariant]" value="'.$Variant->id.'" />
				<input type="submit" value="Abiertas"/></form>				
			<form style="display: inline" action="gamelistings.php" method="POST">
				<input type="hidden" name="gamelistType" value="Active" />
				<input type="hidden" name="searchOff" value="true" />
				<input type="hidden" name="search[chooseVariant]" value="'.$Variant->id.'" />
				<input type="submit" value="Activas" /></form>
			<form style="display: inline" action="gamelistings.php" method="POST">
				<input type="hidden" name="gamelistType" value="Finished" />
				<input type="hidden" name="searchOff" value="true" />
				<input type="hidden" name="search[chooseVariant]" value="'.$Variant->id.'" />
				<input type="submit" value="Terminadas" /></form>
		</td> <td style="text-align:right">
			<form style="display: inline" action="stats.php" method="GET">
				<input type="hidden" name="variantID" value="'.$Variant->id.'" />
				<input type="submit" value="Estadísticas" /></form>			
			<form style="display: inline" action="edit.php" method="GET">
				<input type="hidden" name="variantID" value="'.$Variant->id.'" />
				<input type="submit" value="Info del mapa" /></form>			
			<form style="display: inline" action="files.php" method="GET">
				<input type="hidden" name="variantID" value="'.$Variant->id.'" />
				<input type="submit" value="Ver código" /></form>
		</td>
	</table>';
			
	print '<br><div style="color:white"><strong>Parámetros de la variante';
	if ((isset($Variant->version)) || (isset($Variant->CodeVersion)))
	{
		print ' (';
		if (isset($Variant->version))
			print 'Versión: '. $Variant->version.(isset($Variant->codeVersion)?' / ':'');
		if (isset($Variant->codeVersion))
			print 'Código: ' . $Variant->codeVersion;
		print ')';
	}
	print ':</strong>';
	
	print '<ul>';
	if (isset($Variant->homepage))
		print '<li><a href="'. $Variant->homepage .'">Página de la variante</a></li>';
	if (isset($Variant->author))
		print '<li> Creada por '. $Variant->author .'</li>';
	if (isset($Variant->adapter))
		print '<li> Adaptada por '. $Variant->adapter .'</li>';

	list($turns,$games) = $DB->sql_row('SELECT SUM(turn), COUNT(*) FROM wD_Games WHERE variantID='.$Variant->id.' AND phase = "Finished"');
	print '<li> Partidas terminadas: '. $games .' partida'.($games!=1?'s':'').'</li>';
	print '<li> Duración media: '. ($games==0?'0.00':number_format($turns/$games,2)) .' turns</li>';

	print '<li> Centros para ganar: ' . $Variant->supplyCenterTarget . ' (de '.$Variant->supplyCenterCount.')</li>';

	$count=array('Sea'=>0,'Land'=>0,'Coast'=>0,'All'=>0);
	$tabl = $DB->sql_tabl(
		'SELECT TYPE,count(TYPE) FROM wD_Territories t
			WHERE EXISTS (SELECT * FROM wD_Borders b WHERE b.fromTerrID = t.id && b.mapID = t.mapID) 
			&& t.mapID ='.$Variant->mapID.' && t.name NOT LIKE "% Coast)%" 
		GROUP BY TYPE');
	while(list($type,$counter) = $DB->tabl_row($tabl))
	{
		$count[$type]=$counter;
		$count['All']+=$counter;
	}	
	print '<li> Territorios: '.$count['All'].' (Tierra='.$count['Land'].'; Costa='.$count['Coast'].'; Mar='.$count['Sea'].')</li>';

	if (!file_exists('variants/'. $Variant->name .'/rules.html'))
		print '<li>Se aplican las reglas estándar de Diplomacy</li>';
	print '</ul>';

	if (file_exists('variants/'. $Variant->name .'/rules.html'))
	{
		print '<p><strong>Información/Reglas especiales:</strong></p>';
		print '<div>'.file_get_contents('variants/'. $Variant->name .'/rules.html').'</div>';
	}
}

print '</div>';
libHTML::footer();

?>