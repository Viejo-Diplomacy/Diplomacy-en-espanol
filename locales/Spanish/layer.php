<?php
/*
    Copyright (C) 2004-2013 Kestas J. Kuliukas

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

defined('IN_CODE') or die('This script can not be run by itself.');

// The Locale class, extended with Italian specific modifications
class Locale_Spanish extends Locale_Abstract {
	
	function initialize() {
		// Affects the way dates are output
		setlocale(LC_ALL, 'es_ES.UTF-8', 'es_ES.ISO8859-1','es_ES.ISO8859-15', 'es_ES');
		
		// Load up the serialized translation lookup table
		$this->textLookup = unserialize(file_get_contents('locales/Spanish/lookup.php.txt'));
	}
	
	public function text($text, array $args=array()) {
	
		if( $text == "%s days")
		{
			if( $args[0] == 1)
				$text = "%s d&iacute;a";
		}
		elseif( $text == "%s d&iacute;as, %s horas" )
		{
			if( $args[0] == 1 && $args[1] == 1 )
				$text = "%s d&iacute;a, %s hora";
			elseif( $args[0] == 1 )
				$text = "%s d&iacute;a, %s horas";
			elseif( $args[1] == 1 )
				$text = "%s d&iacute;as, %s hora";
		}
		elseif( $text == 'Total (finished): <strong>%s</strong>' )
		{
			if( $args[0] == 1 )
				$text = 'Total (terminado (singular)): <strong>%s</strong>';
		}
		elseif( $text == 'Ganadas: <strong>%s</strong>' )
		{
			if( $args[0] == 1 )
				$text = 'Ganada (singular): <strong>%s</strong>';
		}
	
		return parent::text($text, $args);
	}
	
	function includeJS($jsInclude) {
		// Load up the Spanish JS layer instead of the default English one:
		if( $jsInclude == '../locales/spanish/layer.js' )
			return '../locales/Spanish/layer.js';
		
		return $jsInclude;
	}
	
	// Unlike the default translation layer don't worry about whether we're in debug mode; just log all failed lookups
	protected function failedLookup($text) {
		
		$this->failedLookups[$text] = $text;
	}
	
	function onFinish() {
		// Load up the Spanish text lookup hash table on startup
		libHTML::$footerIncludes[]='../locales/Spanish/lookup.js';
		
		$this->logFailedLookups();
	}
	
	function includePHP($phpInclude) {
		// Any PHP includes which have locales/English in them need to point to locales/Spanish,
		// because this locale has translated all files within locales/English. (e.g. faq.php)
		return str_replace('locales/English','locales/Spanish',$phpInclude);
	}
	
	function staticFile($resource) {
		
		// If loading up the classic map name overlays include instead the Italian names:
		if( $resource == 'variants/Classic/resources/smallmapNames.png' )
			return 'locales/Spanish/SmallMapNames.png';
		else if ( $resource == 'variants/Classic/resources/mapNames.png')
			return 'locales/Spanish/LargeMapNames.png';
		else
			return $resource;
	}
}

// Load up the Italian Locale as the default
$Locale = new Locale_Spanish();
