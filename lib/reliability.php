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

class libReliability
{

	public static $grades = array (
		98=>'98+', 90=>'90+', 80=>'80+', 60=>'60+', 40=>'40+', 10=>'10+', 0=>'0', '-100'=>'Nuevo'
	);
	
	/**
	 * Calc a reliability rating.  Reliability rating is 100 minus phases missed / phases played * 200, not to be lower than 0
	 * Examples: If a user misses 5% of their games, rating would be 90, 15% would be 70, etc. 
	 * Certain features of the site (such as creating and joining games) will be restricted if the reliability rating is too low.
	 * @return reliability
	 */
	static public function calcReliability($missedMoves, $phasesPlayed, $gamesLeft, $leftBalanced)
	{
		if ( $phasesPlayed == 0 )
			$reliability = 100;
		else
			$reliability = ceil(100 - $missedMoves / $phasesPlayed * 200 - (10 * ($gamesLeft - $leftBalanced)));

		if ($reliability < 0) $reliability = 0;
		
		if ( $phasesPlayed < 20 ) $reliability = $reliability * -1;
		if ( $phasesPlayed < 20 && $reliability == 0) $reliability = -1;

		return $reliability;
	}

	/**
	 * Get a user's or members reliability rating.	 
	 * @return reliability
	 */
	static public function getReliability($User)
	{
		return self::calcReliability($User->missedMoves, $User->phasesPlayed, $User->gamesLeft, $User->leftBalanced);
	}
	
	/**
	 * Display the Grade to the given reliability
	 */
	static public function Grade($reliability)
	{
		foreach (self::$grades as $limit=>$grade)
			if ($reliability >= $limit)
				return $grade;
	}
	
	/**
	 * Get a user's Grade... 
	 * @return grade as string...
	 */
	static public function getGrade($User)
	{
		$reliability = libReliability::calcReliability($User->missedMoves, $User->phasesPlayed, $User->gamesLeft, $User->leftBalanced);
		return libReliability::Grade($reliability);
	}
		
	/**
	 * Check if the users reliability is high enough to join/create more games
	 * @return true or error message	 
	 */
	static public function isReliable($User)
	{
		global $DB;
		
		// A player can't join new games, as long as he has active CountrySwiches.
		list($openSwitches)=$DB->sql_row('SELECT COUNT(*) FROM wD_CountrySwitch WHERE (status = "Send" OR status = "Active") AND fromID='.$User->id);
		if ($openSwitches > 0)
			return "<p><b>NOTICE:</b></p><p>No puedes crear o unirte a más partidas porque tienes activado el Intercambio de países (CountrySwitch) en este momento.</p>";

		$reliability = self::getReliability($User);
		$maxGames = ceil($reliability / 10);
		list($totalGames) = $DB->sql_row("SELECT COUNT(*) FROM wD_Members m, wD_Games g WHERE m.userID=".$User->id." and m.gameID=g.id and g.phase!='Finished' and m.bet>1");
		
		// This will prevent newbies from joining 10 games and then leaving right away.
		if ( $totalGames > 4 && $User->phasesPlayed < 20 ) 
			return "<p>Te has unido a demasiadas partidas al mismo tiempo para un jugador nuevo.<br>Tómalo con calma y disfruta de la partida o partidas en las que estás ahora antes de incorporarte o crear otras.<br>Necesitas jugar al menos <strong>20 turnos</strong> para poder unirte a más de 4 partidas. Una vez hayas jugado los 20 turnos tu grado de Fiabilidad decidirá cuántas partidas puedes jugar a un mismo tiempo. Puedes unirte a una partida por cada 10% del grado de Fiabilidad. Si tu Fiabilidad es mayor de 90% podrás unirte a todas las partidas que quieras.<br>Las variantes de 2 jugadores no sufren estas restricciones.</p>";
		
		// If the rating is 90 or above, there is no game limit restriction
		if ($maxGames < 10 && $User->phasesPlayed >= 20) { 
			if ( $reliability == 0 )
				return "<p>ATENCIÓN: No se te permite unirte o crear más partidas porque tienes un grado de Fiabilidad de CERO(significa que has perdido más del 50% de tus turnos)</p>
				<p>Puedes mejorar tu fiabilidad dejando de saltar turnos, incluso dejando las órdenes en Aguantar (HOLD) si no te apetece seguir jugando una partida.</p>
				<p>Si ahora mismo no estás en ninguna partida y no puedes unirte por culpa de esta restricción, puedes contactar con los <a href=\"modforum.php\">moderadores</a> explicar las causas de tan poco grado de Fiabilidad.  Los administradores, bajo su discrección, pueden aumentarte manualmente la Fiabilidad para que te puedas unir a 1 partida. Si das las órdenes adecuadamente, tu grado de Fiabilidad aumentará y podrás jugar más partidas. Las variantes de 2 jugadores no se ven afectadas por estas restricciones.</p>";
			elseif ( $totalGames >= $maxGames ) // Can't have more than reliability rating / 10 games up
				return "<p>ATENCIÓN: No te puedes unir o crear más partidas porque parece que has estado dejando pasar bastantes turnos.</p>
				<p>Puedes mejorar tu fiabilidad dejando de saltar turnos, incluso dejando las órdenes en Aguantar (HOLD) si no te apetece seguir jugando una partida.</p>
				<p>Te en cuenta que si aparece como que has 'Abandonado' una partida, tu grado de Fiabilidad seguirá recibiendo penalizaciones hasta que alguien te reemplace.</p>
				<p>Tu actual grado de Fiabilidad de <strong>".$reliability."</strong> no te permite jugar más de <strong>".$maxGames."</strong> partidas en estos momentos.  
				Cada 10% de fiabilidad te permitirá una partida más. Las variantes de 2 jugadores no se ven afectadas por esta restricción. También puedes unirte en partidas 'Abiertas' que necesiten un reemplazo.</p>";
		}
	}
	
	/**
	 * Update a members reliability-stats
	 */
	static function updateReliability($Member, $type, $calc)
	{
		global $DB, $Game;
		
		if ($type == 'leftBalanced' && ($Member->leftBalanced >= $Member->gamesLeft))
			return;
			
		if ( (count($Game->Variant->countries) > 2) && ($Game->phaseMinutes > 30) )
			$DB->sql_put("UPDATE wD_Users SET ".$type." = ".$type." ".$calc." WHERE id=".$Member->userID);		
	}

	/**
	 * Adjust the missed turns of each member and update the phase counter
	 * for games with more then 2 players and not live games...
	 * "Left" users are included (for civil disorder to total phases ratio calculating)
	 */
	static function updateReliabilities($Members)
	{
		foreach($Members->ByStatus['Playing'] as $Member)
		{
			self::updateReliability($Member, 'phasesPlayed', '+ 1');
			if ($Member->orderStatus == '')
				self::updateReliability($Member, 'missedMoves', '+ 1');
		}
		
		foreach($Members->ByStatus['Left'] as $Member)
		{
			self::updateReliability($Member, 'phasesPlayed', '+ 1');
			self::updateReliability($Member, 'missedMoves' , '+ 1');
		}
	}
	
}

?>
