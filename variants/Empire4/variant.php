<?php
/*
	Copyright (C) 2010 Carey Jensen / Oliver Auth
This file is part of the Fall of the American Empire IV variant for webDiplomacy
	The Fall of the American Empire IV variant for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either version
	3 of the License, or (at your option) any later version.
	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
>>>>>>> 4d9b181c65f726a9b242cf8e4ffd9c256e924d30
	---

	Changelog:
	1.0:     initial release by Carey Jensen
	1.5:     new webdip v.98 code by Oliver Auth
	1.5.1:   small adjustments for the new variant.php code
	1.6:     new graphics and coordinates for both maps
	1.6.1:   small tweaks for a better readability of the country colors
	1.6.2:   new country-colors (same as original webdip)
	1.6.2.1: border error fixed
	1.6.2.2: spelling error fixed
	1.6.2.3: border error fixed
	1.6.2.4: small graphical error fixed
	1.6.2.5: small graphical error fixed
	1.6.2.6: border error fixed
	1.7:     more border errors fixed
	1.7.1:   up the memory limit for the largemap
	1.7.2:   borderfix
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Empire4Variant extends WDVariant {
	public $id         = 20;
	public $mapID      = 20;
	public $name       = 'Empire4';
	public $fullName   = 'Ca&iacute;da del Imperio Americano IV';
	public $description= 'El pa&iacute;s est&aacute; en ruinas y en un estado de anarqu&iacute;a. T&uacute; juegas con uno de los 10 pa&iacute;ses nuevos o antiguos, en un intento de conquistar Am&eacute;rica del Norte';
	public $author     = 'Vincent Mous';
	public $adapter    = 'Carey Jensen / Oliver Auth';
	public $version    = '1.7';
	public $codeVersion= '1.7.3';
	public $homepage   = 'http://www.variantbank.org/results/rules/e/empire4.htm';

	public $countries=array('British-Columbia', 'California', 'Florida', 'Heartland', 'Mexico', 'New-York', 'Peru', 'Quebec', 'Texas', 'Cuba');

	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']            = 'Empire4';
		$this->variantClasses['adjudicatorPreGame'] = 'Empire4';
	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 34;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1999);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1999);
		};';
	}
}

?>