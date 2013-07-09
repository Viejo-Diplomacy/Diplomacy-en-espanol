<?php
/*
	Copyright (C) 2013 Diplomacy en Espa&ntilde;ol (www.webdiplomacy.com.es)

	This file is part of the Guerra Civil Española variant for webDiplomacy

	The GuerraCivil variant for webDiplomacy" is free software:
	you can redistribute it and/or modify it under the terms of the GNU Affero
	General Public License as published by the Free Software Foundation, either 
	version 3 of the License, or (at your option) any later version.

	The GuerraCivil variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied 
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.

	---

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class BandoMember 
{
	public function memberCountryName() { return 'Bando'; }
	public function memberNameCountry() { return 'Bando'; }
	public function memberBar()         { return ''; }
	public function isNameHidden()      { return false;	}
	public $userID=4;
	public $online=0;
}

class GuerraCivilVariant_Chatbox extends Chatbox {

	function getMessages ( $msgCountryID, $limit=20 )
	{
		if ($limit == false)
			ini_set('memory_limit',"15M");
		else
			$limit = $limit*5;

		return parent::getMessages($msgCountryID, $limit);

	}

	public function renderMessages($msgCountryID, $messages) {
		global $Member, $User;
		
		if (isset($User->showCountryNames) && $User->showCountryNames == 'Yes')
			return parent::renderMessages($msgCountryID, $messages);

		for($i=0; $i<count($messages); $i++)
			if( $messages[$i]['fromCountryID']!=0)
				if (!isset($Member) || $Member->countryID != $messages[$i]['fromCountryID'])
					$messages[$i]['message'] = '[<strong>'.$this->countryName($messages[$i]['fromCountryID']).'</strong>]<span style="">: '.$messages[$i]['message'];
				else
					$messages[$i]['message'] = '[<strong>T&uacute;</strong>]<span style="">: '.$messages[$i]['message'];				

		return parent::renderMessages($msgCountryID, $messages);
	}
	
	//Comienza chat para bando
	/**
	 * The UserID for the Bando
	 */
	public $bandoID;

	/**
	 *  Add a special Member for the Chatbox-display:
	 */
	public function __construct()
	{
		global $Game;
		$Game->Variant->countries[]='Bando';
		$Game->Members->ByCountryID[count($Game->Variant->countries)]=new BandoMember();
		$this->bandoID = count($Game->Variant->countries);
	}
	
	/**
	 * And remove it when done:
	 */
	public function __destruct()
	{
		global $Game;
		unset ($Game->Members->ByCountryID[count($Game->Variant->countries)]);
		array_pop($Game->Variant->countries);
	}

	/**
	 * If a message is sent to GreyPress forward it to it