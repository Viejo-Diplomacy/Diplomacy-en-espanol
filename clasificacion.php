<?php

require_once('header.php');
require_once("lib/rating.php");

libHTML::starthtml();

print libHTML::pageTitle('Clasificaci&oacute;n','Los 100 mejores jugadores en Diplomacy en Espa&ntilde;ol: informaci&oacute;n de puntos y partidas. Puedes reordenar los datos pulsando sobre cada columna.');
print '<script type="text/javascript" src="contrib/js/tablekit.js"></script>';

print '<style type="text/css">
      .sortcol { cursor: pointer;
         padding-right: 20px;
         background-repeat: no-repeat;
         background-position: right center; }
      .sortasc {
         background-color: #DDFFAC;
         background-image: url(contrib/js/up.gif); }
      .sortdesc {
         background-color: #B9DDFF;
         background-image: url(contrib/js/down.gif); }
      .nosort { cursor: default;} 
   </style>';
   
print '<TABLE class="sortable credits" style="border: 0px solid #000">
         <THEAD>
            <!--<TH style="border: 0px solid #000" > </TH>-->
            <TH style="background:#ccc;border: 1px solid #000; width:150px">Nombre     </TH>
            <!--<TH style="border: 1px solid #000">Reg.</TH>-->
            <!--<TH style="border: 1px solid #000">Posici&oacute;n</TH>-->
            <TH style="background:#ccc; border: 1px solid #000; width:25px" class="sortfirstdesc" title="Puntos totales"><center>Pnts </center></TH>
			<TH></TH>
            <TH style="background:#ccc; border: 1px solid #000; width:25px; font-size:11px;"><center>Victorias</center></TH>
            <TH style="background:#ccc; border: 1px solid #000; width:25px; font-size:11px;"><center>Derrotas</center></TH>
            <TH style="background:#ccc; border: 1px solid #000; width:25px; font-size:11px;"><center>Empates</center></TH>
            <TH style="background:#ccc; border: 1px solid #000; width:25px; font-size:11px;"><center>Sobrev.</center></TH>
            <TH style="background:#ccc; border: 1px solid #000; width:25px; font-size:11px;"><center>Aband.</center></TH>
			<TH></TH>
            <TH style="background:#ccc; border: 1px solid #000; width:25px" title="Puntos según el ranking webDiplomacy">wDip</TH>
			<TH style=" border: 0px solid #000; width:25px"><center><span style="font-size:8px; color:white" title="Ver detalle del jugador según el ranking ELO"> [ELO]</span></center></TH>
         </THEAD>
         <TFOOT>
            <!--<tr style="border: 1px solid #666"><td colspan=6><b>**Rating</b> = ("players" x "games played") - <b>**Hot</b> = Number of active games</td></tr>-->
         </TFOOT>';


$tabl = $DB->sql_tabl("SELECT id FROM wD_Users WHERE id > 4 order BY id"); 

while ( list($userID) = $DB->tabl_row($tabl) )
{
     $UserProfile = new User($userID);
   $rankingDetails = $UserProfile->rankingDetails(); 
   $showAnon = ($User->id == $User->id || $User->type['Moderator']);
   if( $UserProfile->type['Moderator'] )
	$donatorMarker = '<img src="images/icons/medal7.png" alt="Mod" title="Moderador" />';
	elseif( $UserProfile->type['LigaParticipa'] )
	$donatorMarker = libHTML::ligaparticipa().'';
	elseif( $UserProfile->type['LigaGanador1'] )
	$donatorMarker = libHTML::ligaganador1().''; 
else
	$donatorMarker = false;
	$rankingDetails = $UserProfile->rankingDetails();


$showAnon = ($UserProfile->id == $User->id || $User->type['Moderator']);


   print '<TR>
         <!--<TD style="border: 0px solid #666" class="left">'.libHTML::loggedOn($UserProfile->id).'</TD>-->
         <TD style="color: #fff;border: 1px solid #e0a35e; width:150px" class="left" >'.libHTML::loggedOn($UserProfile->id).' '.$rankingDetails['rank'].$donatorMarker.' <a href="profile.php?userID='.$UserProfile->id.'"> '.$UserProfile->username.' </a></TD>
         <!--<TD style="border: 1px solid #666">'.gmstrftime("%d %b %y", $UserProfile->timeJoined).'</TD>-->
         <!--<TD style="border: 1px solid #666">'.$rankingDetails['position'].'</TD>-->
         <TD style="color: #fff;border: 1px solid #e0a35e" class=""><center>'.$rankingDetails['worth'].' '.libHTML::points().'</center></TD>
		 <TD></TD>
         <TD style="color: #fff;border: 1px solid #e0a35e" class=""><center>'.(isset($rankingDetails['stats']['Won'])?$rankingDetails['stats']['Won']:'0').'</center></TD>
         <TD style="color: #fff;border: 1px solid #e0a35e"><center>'.(isset($rankingDetails['stats']['Defeated'])?$rankingDetails['stats']['Defeated']:'0').'</center></TD>
         <TD style="color: #fff;border: 1px solid #e0a35e"><center>'.(isset($rankingDetails['stats']['Drawn'])?$rankingDetails['stats']['Drawn']:'0').'</center></TD>
         <TD style="color: #fff;border: 1px solid #e0a35e"><center>'.(isset($rankingDetails['stats']['Survived'])?$rankingDetails['stats']['Survived']:'0').'</center></TD>
         <TD style="color: #fff;border: 1px solid #e0a35e"><center>'.(isset($rankingDetails['stats']['Resigned'])?$rankingDetails['stats']['Resigned']:'0').'</center></TD>
		 <TD></TD>
         <TD style="color: #fff;border: 1px solid #e0a35e"><center>'.$UserProfile->points.' '.libHTML::points().'</center></TD>
		 <TD><center><a href="hof.php?userID='.$UserProfile->id.'" title="Consutlar Ranking-ELO"><img src="images/icons/stats-icon.gif" title="Consultar Ranking ELO"</a></center>
      </TR>';
}
print '</TABLE>';         
//print '<iframe src="xml2html.php" align="center" scrolling="no" width="250" height="500" frameborder="0"></iframe>';
print '</div>';


libHTML::footer();

?>