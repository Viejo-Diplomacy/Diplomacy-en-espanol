<?php
/*
	Copyright (C) 2010 Oliver Auth / Kestas J. Kuliukas

	This file is part of the Classic variant for webDiplomacy

	The Classic variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License 
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Classic variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	This is a new install-file with improved xy-coordinates for unit placement.
	You need the new maps in resources too.
	
	This is Version: 1.1.1
	
	Changelog:
	1.0: initial release
	1.1: new order of terittories so they keep their original ID
	1.1.1: fixed a owner-issue (Ruhr -> Germany)
	
*/

defined('IN_CODE') or die('This script can not be run by itself.');

require_once("variants/install.php");

InstallTerritory::$Territories=array();
$countries=$this->countries;
$territoryRawData=array(
	array('Clyde'                       ,'Coast','No' ,309 ,337,157,120,'England'),
	array('Edinburgh'                   ,'Coast','Yes',340 ,350,175,109,'England'),
	array('Liverpool'                   ,'Coast','Yes',315 ,405,166,140,'England'),
	array('Yorkshire'                   ,'Coast','No' ,355 ,440,184,150,'England'),
	array('Wales'                       ,'Coast','No' ,305 ,480,154,179,'England'),
	array('London'                      ,'Coast','Yes',358 ,485,176,185,'England'),
	array('Portugal'                    ,'Coast','Yes',100 ,718,65 ,292,'Neutral'),
	array('Spain'                       ,'Coast','Yes',205 ,760,110,307,'Neutral'),
	array('North Africa'                ,'Coast','No' ,210 ,923,130,380,'Neutral'),
	array('Tunis'                       ,'Coast','Yes',420 ,970,204,384,'Neutral'),
	array('Naples'                      ,'Coast','Yes',570 ,870,298,357,'Italy'  ),
	array('Rome'                        ,'Coast','Yes',522 ,800,272,330,'Italy'  ),
	array('Tuscany'                     ,'Coast','No' ,485 ,755,256,312,'Italy'  ),
	array('Piedmont'                    ,'Coast','No' ,441 ,694,228,276,'Italy'  ),
	array('Venice'                      ,'Coast','Yes',490 ,710,258,292,'Italy'  ),
	array('Apulia'                      ,'Coast','No' ,575 ,813,295,335,'Italy'  ),
	array('Greece'                      ,'Coast','Yes',676 ,851,355,361,'Neutral'),
	array('Albania'                     ,'Coast','No' ,640 ,820,343,341,'Neutral'),
	array('Serbia'                      ,'Land' ,'Yes',668 ,766,357,324,'Neutral'),
	array('Bulgaria'                    ,'Coast','Yes',730 ,776,394,318,'Neutral'),
	array('Rumania'                     ,'Coast','Yes',765 ,717,412,272,'Neutral'),
	array('Constantinople'              ,'Coast','Yes',821 ,840,433,345,'Turkey' ),
	array('Smyrna'                      ,'Coast','Yes',899 ,890,480,363,'Turkey' ),
	array('Ankara'                      ,'Coast','Yes',970 ,820,485,320,'Turkey' ),
	array('Armenia'                     ,'Coast','No' ,1130,835,572,323,'Turkey' ),
	array('Syria'                       ,'Coast','No' ,1073,940,559,370,'Turkey' ),
	array('Sevastopol'                  ,'Coast','Yes',920 ,595,485,248,'Russia' ),
	array('Ukraine'                     ,'Land' ,'No' ,779 ,579,418,220,'Russia' ),
	array('Warsaw'                      ,'Land' ,'Yes',680 ,540,358,209,'Russia' ),
	array('Livonia'                     ,'Coast','No' ,719 ,441,374,168,'Russia' ),
	array('Moscow'                      ,'Land' ,'Yes',880 ,440,490,160,'Russia' ),
	array('St. Petersburg'              ,'Coast','Yes',837 ,309,445,97 ,'Russia' ),
	array('Finland'                     ,'Coast','No' ,710 ,263,369,109,'Russia' ),
	array('Sweden'                      ,'Coast','Yes',587 ,285,307,103,'Neutral'),
	array('Norway'                      ,'Coast','Yes',518 ,270,265,113,'Neutral'),
	array('Denmark'                     ,'Coast','Yes',501 ,435,264,150,'Neutral'),
	array('Kiel'                        ,'Coast','Yes',477 ,504,253,193,'Germany'),
	array('Berlin'                      ,'Coast','Yes',559 ,493,287,189,'Germany'),
	array('Prussia'                     ,'Coast','No' ,618 ,482,319,202,'Germany'),
	array('Silesia'                     ,'Land' ,'No' ,589 ,535,315,222,'Germany'),
	array('Munich'                      ,'Land' ,'Yes',489 ,596,252,229,'Germany'),
	array('Ruhr'                        ,'Land' ,'No' ,450 ,550,227,221,'Germany'),
	array('Holland'                     ,'Coast','Yes',433 ,510,234,185,'Neutral'),
	array('Belgium'                     ,'Coast','Yes',410 ,550,215,205,'Neutral'),
	array('Picardy'                     ,'Coast','No' ,363 ,559,182,213,'France' ),
	array('Brest'                       ,'Coast','Yes',298 ,595,153,240,'France' ),
	array('Paris'                       ,'Land' ,'Yes',336 ,620,176,233,'France' ),
	array('Burgundy'                    ,'Land' ,'No' ,393 ,629,204,243,'France' ),
	array('Marseilles'                  ,'Coast','Yes',390 ,689,199,275,'France' ),
	array('Gascony'                     ,'Coast','No' ,301 ,676,154,265,'France' ),
	array('Barents Sea'                 ,'Sea'  ,'No' ,826 ,37 ,427,15 ,'Neutral'),
	array('Norwegian Sea'               ,'Sea'  ,'No' ,437 ,149,240,31 ,'Neutral'),
	array('North Sea'                   ,'Sea'  ,'No' ,414 ,378,216,125,'Neutral'),
	array('Skagerrack'                  ,'Sea'  ,'No' ,518 ,363,284,146,'Neutral'),
	array('Heligoland Bight'            ,'Sea'  ,'No' ,455 ,439,241,159,'Neutral'),
	array('Baltic Sea'                  ,'Sea'  ,'No' ,621 ,431,338,177,'Neutral'),
	array('Gulf of Bothnia'             ,'Sea'  ,'No' ,653 ,330,345,141,'Neutral'),
	array('North Atlantic Ocean'        ,'Sea'  ,'No' ,145 ,250,78 ,92 ,'Neutral'),
	array('Irish Sea'                   ,'Sea'  ,'No' ,208 ,478,118,171,'Neutral'),
	array('English Channel'             ,'Sea'  ,'No' ,275 ,526,145,205,'Neutral'),
	array('Mid-Atlantic Ocean'          ,'Sea'  ,'No' ,106 ,587,60 ,251,'Neutral'),
	array('Western Mediterranean'       ,'Sea'  ,'No' ,327 ,852,186,350,'Neutral'),
	array('Gulf of Lyons'               ,'Sea'  ,'No' ,366 ,774,208,322,'Neutral'),
	array('Tyrrhenian Sea'              ,'Sea'  ,'No' ,480 ,847,248,343,'Neutral'),
	array('Ionian Sea'                  ,'Sea'  ,'No' ,610 ,952,325,379,'Neutral'),
	array('Adriatic Sea'                ,'Sea'  ,'No' ,554 ,761,316,329,'Neutral'),
	array('Aegean Sea'                  ,'Sea'  ,'No' ,755 ,930,395,363,'Neutral'),
	array('Eastern Mediterranean'       ,'Sea'  ,'No' ,860 ,967,465,383,'Neutral'),
	array('Black Sea'                   ,'Sea'  ,'No' ,915 ,735,457,300,'Neutral'),
	array('Tyrolia'                     ,'Land' ,'No' ,542 ,632,282,252,'Austria'),
	array('Bohemia'                     ,'Land' ,'No' ,567 ,586,298,227,'Austria'),
	array('Vienna'                      ,'Land' ,'Yes',602 ,637,314,246,'Austria'),
	array('Trieste'                     ,'Coast','Yes',579 ,710,308,288,'Austria'),
	array('Budapest'                    ,'Land' ,'Yes',661 ,678,350,260,'Austria'),
	array('Galicia'                     ,'Land' ,'No' ,710 ,610,378,240,'Austria'),
	array('Spain (North Coast)'         ,'Coast','No' ,193 ,685,90 ,273,'Neutral'),
	array('Spain (South Coast)'         ,'Coast','No' ,191 ,832,106,345,'Neutral'),
	array('St. Petersburg (North Coast)','Coast','No' ,828 , 90,448,59 ,'Russia' ),
	array('St. Petersburg (South Coast)','Coast','No' ,760 ,335,397,130,'Russia' ),
	array('Bulgaria (North Coast)'      ,'Coast','No' ,785 ,762,424,304,'Neutral'),
	array('Bulgaria (South Coast)'      ,'Coast','No' ,749 ,815,397,332,'Neutral')
);

foreach($territoryRawData as $territoryRawRow)
{
	list($name, $type, $supply, $x, $y, $sx, $sy, $country)=$territoryRawRow;
	if( $country=='Neutral' )
		$countryID=0;
	else
		$countryID=$this->countryID($country);
		
	new InstallTerritory($name, $type, $supply, $countryID, $x, $y, $sx, $sy);
}
unset($territoryRawData);

$bordersRawData=array(
	array('Edinburgh'                   ,'Clyde'                       , 'Yes', 'Yes'),
	array('Liverpool'                   ,'Clyde'                       , 'Yes', 'Yes'),
	array('Norwegian Sea'               ,'Clyde'                       , 'Yes', 'No' ),
	array('North Atlantic Ocean'        ,'Clyde'                       , 'Yes', 'No' ),
	array('Clyde'                       ,'Edinburgh'                   , 'Yes', 'Yes'),
	array('Liverpool'                   ,'Edinburgh'                   , 'No' , 'Yes'),
	array('Yorkshire'                   ,'Edinburgh'                   , 'Yes', 'Yes'),
	array('Norwegian Sea'               ,'Edinburgh'                   , 'Yes', 'No' ),
	array('North Sea'                   ,'Edinburgh'                   , 'Yes', 'No' ),
	array('Clyde'                       ,'Liverpool'                   , 'Yes', 'Yes'),
	array('Edinburgh'                   ,'Liverpool'                   , 'No' , 'Yes'),
	array('Yorkshire'                   ,'Liverpool'                   , 'No' , 'Yes'),
	array('Wales'                       ,'Liverpool'                   , 'Yes', 'Yes'),
	array('North Atlantic Ocean'        ,'Liverpool'                   , 'Yes', 'No' ),
	array('Irish Sea'                   ,'Liverpool'                   , 'Yes', 'No' ),
	array('Edinburgh'                   ,'Yorkshire'                   , 'Yes', 'Yes'),
	array('Liverpool'                   ,'Yorkshire'                   , 'No' , 'Yes'),
	array('Wales'                       ,'Yorkshire'                   , 'No' , 'Yes'),
	array('London'                      ,'Yorkshire'                   , 'Yes', 'Yes'),
	array('North Sea'                   ,'Yorkshire'                   , 'Yes', 'No' ),
	array('Liverpool'                   ,'Wales'                       , 'Yes', 'Yes'),
	array('Yorkshire'                   ,'Wales'                       , 'No' , 'Yes'),
	array('London'                      ,'Wales'                       , 'Yes', 'Yes'),
	array('Irish Sea'                   ,'Wales'                       , 'Yes', 'No' ),
	array('English Channel'             ,'Wales'                       , 'Yes', 'No' ),
	array('Yorkshire'                   ,'London'                      , 'Yes', 'Yes'),
	array('Wales'                       ,'London'                      , 'Yes', 'Yes'),
	array('North Sea'                   ,'London'                      , 'Yes', 'No' ),
	array('English Channel'             ,'London'                      , 'Yes', 'No' ),
	array('Spain'                       ,'Portugal'                    , 'No' , 'Yes'),
	array('Mid-Atlantic Ocean'          ,'Portugal'                    , 'Yes', 'No' ),
	array('Spain (North Coast)'         ,'Portugal'                    , 'Yes', 'No' ),
	array('Spain (South Coast)'         ,'Portugal'                    , 'Yes', 'No' ),
	array('Portugal'                    ,'Spain'                       , 'No' , 'Yes'),
	array('Marseilles'                  ,'Spain'                       , 'No' , 'Yes'),
	array('Gascony'                     ,'Spain'                       , 'No' , 'Yes'),
	array('Tunis'                       ,'North Africa'                , 'Yes', 'Yes'),
	array('Mid-Atlantic Ocean'          ,'North Africa'                , 'Yes', 'No' ),
	array('Western Mediterranean'       ,'North Africa'                , 'Yes', 'No' ),
	array('North Africa'                ,'Tunis'                       , 'Yes', 'Yes'),
	array('Western Mediterranean'       ,'Tunis'                       , 'Yes', 'No' ),
	array('Tyrrhenian Sea'              ,'Tunis'                       , 'Yes', 'No' ),
	array('Ionian Sea'                  ,'Tunis'                       , 'Yes', 'No' ),
	array('Rome'                        ,'Naples'                      , 'Yes', 'Yes'),
	array('Apulia'                      ,'Naples'                      , 'Yes', 'Yes'),
	array('Tyrrhenian Sea'              ,'Naples'                      , 'Yes', 'No' ),
	array('Ionian Sea'                  ,'Naples'                      , 'Yes', 'No' ),
	array('Naples'                      ,'Rome'                        , 'Yes', 'Yes'),
	array('Tuscany'                     ,'Rome'                        , 'Yes', 'Yes'),
	array('Venice'                      ,'Rome'                        , 'No' , 'Yes'),
	array('Apulia'                      ,'Rome'                        , 'No' , 'Yes'),
	array('Tyrrhenian Sea'              ,'Rome'                        , 'Yes', 'No' ),
	array('Rome'                        ,'Tuscany'                     , 'Yes', 'Yes'),
	array('Piedmont'                    ,'Tuscany'                     , 'Yes', 'Yes'),
	array('Venice'                      ,'Tuscany'                     , 'No' , 'Yes'),
	array('Gulf of Lyons'               ,'Tuscany'                     , 'Yes', 'No' ),
	array('Tyrrhenian Sea'              ,'Tuscany'                     , 'Yes', 'No' ),
	array('Tuscany'                     ,'Piedmont'                    , 'Yes', 'Yes'),
	array('Venice'                      ,'Piedmont'                    , 'No' , 'Yes'),
	array('Marseilles'                  ,'Piedmont'                    , 'Yes', 'Yes'),
	array('Gulf of Lyons'               ,'Piedmont'                    , 'Yes', 'No' ),
	array('Tyrolia'                     ,'Piedmont'                    , 'No' , 'Yes'),
	array('Rome'                        ,'Venice'                      , 'No' , 'Yes'),
	array('Tuscany'                     ,'Venice'                      , 'No' , 'Yes'),
	array('Piedmont'                    ,'Venice'                      , 'No' , 'Yes'),
	array('Apulia'                      ,'Venice'                      , 'Yes', 'Yes'),
	array('Adriatic Sea'                ,'Venice'                      , 'Yes', 'No' ),
	array('Tyrolia'                     ,'Venice'                      , 'No' , 'Yes'),
	array('Trieste'                     ,'Venice'                      , 'Yes', 'Yes'),
	array('Naples'                      ,'Apulia'                      , 'Yes', 'Yes'),
	array('Rome'                        ,'Apulia'                      , 'No' , 'Yes'),
	array('Venice'                      ,'Apulia'                      , 'Yes', 'Yes'),
	array('Ionian Sea'                  ,'Apulia'                      , 'Yes', 'No' ),
	array('Adriatic Sea'                ,'Apulia'                      , 'Yes', 'No' ),
	array('Albania'                     ,'Greece'                      , 'Yes', 'Yes'),
	array('Serbia'                      ,'Greece'                      , 'No' , 'Yes'),
	array('Bulgaria'                    ,'Greece'                      , 'No' , 'Yes'),
	array('Ionian Sea'                  ,'Greece'                      , 'Yes', 'No' ),
	array('Aegean Sea'                  ,'Greece'                      , 'Yes', 'No' ),
	array('Bulgaria (South Coast)'      ,'Greece'                      , 'Yes', 'No' ),
	array('Greece'                      ,'Albania'                     , 'Yes', 'Yes'),
	array('Serbia'                      ,'Albania'                     , 'No' , 'Yes'),
	array('Ionian Sea'                  ,'Albania'                     , 'Yes', 'No' ),
	array('Adriatic Sea'                ,'Albania'                     , 'Yes', 'No' ),
	array('Trieste'                     ,'Albania'                     , 'Yes', 'Yes'),
	array('Greece'                      ,'Serbia'                      , 'No' , 'Yes'),
	array('Albania'                     ,'Serbia'                      , 'No' , 'Yes'),
	array('Bulgaria'                    ,'Serbia'                      , 'No' , 'Yes'),
	array('Rumania'                     ,'Serbia'                      , 'No' , 'Yes'),
	array('Trieste'                     ,'Serbia'                      , 'No' , 'Yes'),
	array('Budapest'                    ,'Serbia'                      , 'No' , 'Yes'),
	array('Greece'                      ,'Bulgaria'                    , 'No' , 'Yes'),
	array('Serbia'                      ,'Bulgaria'                    , 'No' , 'Yes'),
	array('Rumania'                     ,'Bulgaria'                    , 'No' , 'Yes'),
	array('Constantinople'              ,'Bulgaria'                    , 'No' , 'Yes'),
	array('Serbia'                      ,'Rumania'                     , 'No' , 'Yes'),
	array('Bulgaria'                    ,'Rumania'                     , 'No' , 'Yes'),
	array('Sevastopol'                  ,'Rumania'                     , 'Yes', 'Yes'),
	array('Ukraine'                     ,'Rumania'                     , 'No' , 'Yes'),
	array('Black Sea'                   ,'Rumania'                     , 'Yes', 'No' ),
	array('Budapest'                    ,'Rumania'                     , 'No' , 'Yes'),
	array('Galicia'                     ,'Rumania'                     , 'No' , 'Yes'),
	array('Bulgaria (North Coast)'      ,'Rumania'                     , 'Yes', 'No' ),
	array('Bulgaria'                    ,'Constantinople'              , 'No' , 'Yes'),
	array('Smyrna'                      ,'Constantinople'              , 'Yes', 'Yes'),
	array('Ankara'                      ,'Constantinople'              , 'Yes', 'Yes'),
	array('Aegean Sea'                  ,'Constantinople'              , 'Yes', 'No' ),
	array('Black Sea'                   ,'Constantinople'              , 'Yes', 'No' ),
	array('Bulgaria (North Coast)'      ,'Constantinople'              , 'Yes', 'No' ),
	array('Bulgaria (South Coast)'      ,'Constantinople'              , 'Yes', 'No' ),
	array('Constantinople'              ,'Smyrna'                      , 'Yes', 'Yes'),
	array('Ankara'                      ,'Smyrna'                      , 'No' , 'Yes'),
	array('Armenia'                     ,'Smyrna'                      , 'No' , 'Yes'),
	array('Syria'                       ,'Smyrna'                      , 'Yes', 'Yes'),
	array('Aegean Sea'                  ,'Smyrna'                      , 'Yes', 'No' ),
	array('Eastern Mediterranean'       ,'Smyrna'                      , 'Yes', 'No' ),
	array('Constantinople'              ,'Ankara'                      , 'Yes', 'Yes'),
	array('Smyrna'                      ,'Ankara'                      , 'No' , 'Yes'),
	array('Armenia'                     ,'Ankara'                      , 'Yes', 'Yes'),
	array('Black Sea'                   ,'Ankara'                      , 'Yes', 'No' ),
	array('Smyrna'                      ,'Armenia'                     , 'No' , 'Yes'),
	array('Ankara'                      ,'Armenia'                     , 'Yes', 'Yes'),
	array('Syria'                       ,'Armenia'                     , 'No' , 'Yes'),
	array('Sevastopol'                  ,'Armenia'                     , 'Yes', 'Yes'),
	array('Black Sea'                   ,'Armenia'                     , 'Yes', 'No' ),
	array('Smyrna'                      ,'Syria'                       , 'Yes', 'Yes'),
	array('Armenia'                     ,'Syria'                       , 'No' , 'Yes'),
	array('Eastern Mediterranean'       ,'Syria'                       , 'Yes', 'No' ),
	array('Rumania'                     ,'Sevastopol'                  , 'Yes', 'Yes'),
	array('Armenia'                     ,'Sevastopol'                  , 'Yes', 'Yes'),
	array('Ukraine'                     ,'Sevastopol'                  , 'No' , 'Yes'),
	array('Moscow'                      ,'Sevastopol'                  , 'No' , 'Yes'),
	array('Black Sea'                   ,'Sevastopol'                  , 'Yes', 'No' ),
	array('Rumania'                     ,'Ukraine'                     , 'No' , 'Yes'),
	array('Sevastopol'                  ,'Ukraine'                     , 'No' , 'Yes'),
	array('Warsaw'                      ,'Ukraine'                     , 'No' , 'Yes'),
	array('Moscow'                      ,'Ukraine'                     , 'No' , 'Yes'),
	array('Galicia'                     ,'Ukraine'                     , 'No' , 'Yes'),
	array('Ukraine'                     ,'Warsaw'                      , 'No' , 'Yes'),
	array('Livonia'                     ,'Warsaw'                      , 'No' , 'Yes'),
	array('Moscow'                      ,'Warsaw'                      , 'No' , 'Yes'),
	array('Prussia'                     ,'Warsaw'                      , 'No' , 'Yes'),
	array('Silesia'                     ,'Warsaw'                      , 'No' , 'Yes'),
	array('Galicia'                     ,'Warsaw'                      , 'No' , 'Yes'),
	array('Warsaw'                      ,'Livonia'                     , 'No' , 'Yes'),
	array('Moscow'                      ,'Livonia'                     , 'No' , 'Yes'),
	array('St. Petersburg'              ,'Livonia'                     , 'No' , 'Yes'),
	array('Prussia'                     ,'Livonia'                     , 'Yes', 'Yes'),
	array('Baltic Sea'                  ,'Livonia'                     , 'Yes', 'No' ),
	array('Gulf of Bothnia'             ,'Livonia'                     , 'Yes', 'No' ),
	array('St. Petersburg (South Coast)','Livonia'                     , 'Yes', 'No' ),
	array('Sevastopol'                  ,'Moscow'                      , 'No' , 'Yes'),
	array('Ukraine'                     ,'Moscow'                      , 'No' , 'Yes'),
	array('Warsaw'                      ,'Moscow'                      , 'No' , 'Yes'),
	array('Livonia'                     ,'Moscow'                      , 'No' , 'Yes'),
	array('St. Petersburg'              ,'Moscow'                      , 'No' , 'Yes'),
	array('Livonia'                     ,'St. Petersburg'              , 'No' , 'Yes'),
	array('Moscow'                      ,'St. Petersburg'              , 'No' , 'Yes'),
	array('Finland'                     ,'St. Petersburg'              , 'No' , 'Yes'),
	array('Norway'                      ,'St. Petersburg'              , 'No' , 'Yes'),
	array('St. Petersburg'              ,'Finland'                     , 'No' , 'Yes'),
	array('Sweden'                      ,'Finland'                     , 'Yes', 'Yes'),
	array('Norway'                      ,'Finland'                     , 'No' , 'Yes'),
	array('Gulf of Bothnia'             ,'Finland'                     , 'Yes', 'No' ),
	array('St. Petersburg (South Coast)','Finland'                     , 'Yes', 'No' ),
	array('Finland'                     ,'Sweden'                      , 'Yes', 'Yes'),
	array('Norway'                      ,'Sweden'                      , 'Yes', 'Yes'),
	array('Denmark'                     ,'Sweden'                      , 'Yes', 'Yes'),
	array('Skagerrack'                  ,'Sweden'                      , 'Yes', 'No' ),
	array('Baltic Sea'                  ,'Sweden'                      , 'Yes', 'No' ),
	array('Gulf of Bothnia'             ,'Sweden'                      , 'Yes', 'No' ),
	array('St. Petersburg'              ,'Norway'                      , 'No' , 'Yes'),
	array('Finland'                     ,'Norway'                      , 'No' , 'Yes'),
	array('Sweden'                      ,'Norway'                      , 'Yes', 'Yes'),
	array('Barents Sea'                 ,'Norway'                      , 'Yes', 'No' ),
	array('Norwegian Sea'               ,'Norway'                      , 'Yes', 'No' ),
	array('North Sea'                   ,'Norway'                      , 'Yes', 'No' ),
	array('Skagerrack'                  ,'Norway'                      , 'Yes', 'No' ),
	array('St. Petersburg (North Coast)','Norway'                      , 'Yes', 'No' ),
	array('Sweden'                      ,'Denmark'                     , 'Yes', 'Yes'),
	array('Kiel'                        ,'Denmark'                     , 'Yes', 'Yes'),
	array('North Sea'                   ,'Denmark'                     , 'Yes', 'No' ),
	array('Skagerrack'                  ,'Denmark'                     , 'Yes', 'No' ),
	array('Heligoland Bight'            ,'Denmark'                     , 'Yes', 'No' ),
	array('Baltic Sea'                  ,'Denmark'                     , 'Yes', 'No' ),
	array('Denmark'                     ,'Kiel'                        , 'Yes', 'Yes'),
	array('Berlin'                      ,'Kiel'                        , 'Yes', 'Yes'),
	array('Munich'                      ,'Kiel'                        , 'No' , 'Yes'),
	array('Ruhr'                        ,'Kiel'                        , 'No' , 'Yes'),
	array('Holland'                     ,'Kiel'                        , 'Yes', 'Yes'),
	array('Heligoland Bight'            ,'Kiel'                        , 'Yes', 'No' ),
	array('Baltic Sea'                  ,'Kiel'                        , 'Yes', 'No' ),
	array('Kiel'                        ,'Berlin'                      , 'Yes', 'Yes'),
	array('Prussia'                     ,'Berlin'                      , 'Yes', 'Yes'),
	array('Silesia'                     ,'Berlin'                      , 'No' , 'Yes'),
	array('Munich'                      ,'Berlin'                      , 'No' , 'Yes'),
	array('Baltic Sea'                  ,'Berlin'                      , 'Yes', 'No' ),
	array('Warsaw'                      ,'Prussia'                     , 'No' , 'Yes'),
	array('Livonia'                     ,'Prussia'                     , 'Yes', 'Yes'),
	array('Berlin'                      ,'Prussia'                     , 'Yes', 'Yes'),
	array('Silesia'                     ,'Prussia'                     , 'No' , 'Yes'),
	array('Baltic Sea'                  ,'Prussia'                     , 'Yes', 'No' ),
	array('Warsaw'                      ,'Silesia'                     , 'No' , 'Yes'),
	array('Berlin'                      ,'Silesia'                     , 'No' , 'Yes'),
	array('Prussia'                     ,'Silesia'                     , 'No' , 'Yes'),
	array('Munich'                      ,'Silesia'                     , 'No' , 'Yes'),
	array('Bohemia'                     ,'Silesia'                     , 'No' , 'Yes'),
	array('Galicia'                     ,'Silesia'                     , 'No' , 'Yes'),
	array('Kiel'                        ,'Munich'                      , 'No' , 'Yes'),
	array('Berlin'                      ,'Munich'                      , 'No' , 'Yes'),
	array('Silesia'                     ,'Munich'                      , 'No' , 'Yes'),
	array('Ruhr'                        ,'Munich'                      , 'No' , 'Yes'),
	array('Burgundy'                    ,'Munich'                      , 'No' , 'Yes'),
	array('Tyrolia'                     ,'Munich'                      , 'No' , 'Yes'),
	array('Bohemia'                     ,'Munich'                      , 'No' , 'Yes'),
	array('Kiel'                        ,'Ruhr'                        , 'No' , 'Yes'),
	array('Munich'                      ,'Ruhr'                        , 'No' , 'Yes'),
	array('Holland'                     ,'Ruhr'                        , 'No' , 'Yes'),
	array('Belgium'                     ,'Ruhr'                        , 'No' , 'Yes'),
	array('Burgundy'                    ,'Ruhr'                        , 'No' , 'Yes'),
	array('Kiel'                        ,'Holland'                     , 'Yes', 'Yes'),
	array('Ruhr'                        ,'Holland'                     , 'No' , 'Yes'),
	array('Belgium'                     ,'Holland'                     , 'Yes', 'Yes'),
	array('North Sea'                   ,'Holland'                     , 'Yes', 'No' ),
	array('Heligoland Bight'            ,'Holland'                     , 'Yes', 'No' ),
	array('Ruhr'                        ,'Belgium'                     , 'No' , 'Yes'),
	array('Holland'                     ,'Belgium'                     , 'Yes', 'Yes'),
	array('Picardy'                     ,'Belgium'                     , 'Yes', 'Yes'),
	array('Burgundy'                    ,'Belgium'                     , 'No' , 'Yes'),
	array('North Sea'                   ,'Belgium'                     , 'Yes', 'No' ),
	array('English Channel'             ,'Belgium'                     , 'Yes', 'No' ),
	array('Belgium'                     ,'Picardy'                     , 'Yes', 'Yes'),
	array('Brest'                       ,'Picardy'                     , 'Yes', 'Yes'),
	array('Paris'                       ,'Picardy'                     , 'No' , 'Yes'),
	array('Burgundy'                    ,'Picardy'                     , 'No' , 'Yes'),
	array('English Channel'             ,'Picardy'                     , 'Yes', 'No' ),
	array('Picardy'                     ,'Brest'                       , 'Yes', 'Yes'),
	array('Paris'                       ,'Brest'                       , 'No' , 'Yes'),
	array('Gascony'                     ,'Brest'                       , 'Yes', 'Yes'),
	array('English Channel'             ,'Brest'                       , 'Yes', 'No' ),
	array('Mid-Atlantic Ocean'          ,'Brest'                       , 'Yes', 'No' ),
	array('Picardy'                     ,'Paris'                       , 'No' , 'Yes'),
	array('Brest'                       ,'Paris'                       , 'No' , 'Yes'),
	array('Burgundy'                    ,'Paris'                       , 'No' , 'Yes'),
	array('Gascony'                     ,'Paris'                       , 'No' , 'Yes'),
	array('Munich'                      ,'Burgundy'                    , 'No' , 'Yes'),
	array('Ruhr'                        ,'Burgundy'                    , 'No' , 'Yes'),
	array('Belgium'                     ,'Burgundy'                    , 'No' , 'Yes'),
	array('Picardy'                     ,'Burgundy'                    , 'No' , 'Yes'),
	array('Paris'                       ,'Burgundy'                    , 'No' , 'Yes'),
	array('Marseilles'                  ,'Burgundy'                    , 'No' , 'Yes'),
	array('Gascony'                     ,'Burgundy'                    , 'No' , 'Yes'),
	array('Spain'                       ,'Marseilles'                  , 'No' , 'Yes'),
	array('Piedmont'                    ,'Marseilles'                  , 'Yes', 'Yes'),
	array('Burgundy'                    ,'Marseilles'                  , 'No' , 'Yes'),
	array('Gascony'                     ,'Marseilles'                  , 'No' , 'Yes'),
	array('Gulf of Lyons'               ,'Marseilles'                  , 'Yes', 'No' ),
	array('Spain (South Coast)'         ,'Marseilles'                  , 'Yes', 'No' ),
	array('Spain'                       ,'Gascony'                     , 'No' , 'Yes'),
	array('Brest'                       ,'Gascony'                     , 'Yes', 'Yes'),
	array('Paris'                       ,'Gascony'                     , 'No' , 'Yes'),
	array('Burgundy'                    ,'Gascony'                     , 'No' , 'Yes'),
	array('Marseilles'                  ,'Gascony'                     , 'No' , 'Yes'),
	array('Mid-Atlantic Ocean'          ,'Gascony'                     , 'Yes', 'No' ),
	array('Spain (North Coast)'         ,'Gascony'                     , 'Yes', 'No' ),
	array('Norway'                      ,'Barents Sea'                 , 'Yes', 'No' ),
	array('Norwegian Sea'               ,'Barents Sea'                 , 'Yes', 'No' ),
	array('St. Petersburg (North Coast)','Barents Sea'                 , 'Yes', 'No' ),
	array('Clyde'                       ,'Norwegian Sea'               , 'Yes', 'No' ),
	array('Edinburgh'                   ,'Norwegian Sea'               , 'Yes', 'No' ),
	array('Norway'                      ,'Norwegian Sea'               , 'Yes', 'No' ),
	array('Barents Sea'                 ,'Norwegian Sea'               , 'Yes', 'No' ),
	array('North Sea'                   ,'Norwegian Sea'               , 'Yes', 'No' ),
	array('North Atlantic Ocean'        ,'Norwegian Sea'               , 'Yes', 'No' ),
	array('Edinburgh'                   ,'North Sea'                   , 'Yes', 'No' ),
	array('Yorkshire'                   ,'North Sea'                   , 'Yes', 'No' ),
	array('London'                      ,'North Sea'                   , 'Yes', 'No' ),
	array('Norway'                      ,'North Sea'                   , 'Yes', 'No' ),
	array('Denmark'                     ,'North Sea'                   , 'Yes', 'No' ),
	array('Holland'                     ,'North Sea'                   , 'Yes', 'No' ),
	array('Belgium'                     ,'North Sea'                   , 'Yes', 'No' ),
	array('Norwegian Sea'               ,'North Sea'                   , 'Yes', 'No' ),
	array('Skagerrack'                  ,'North Sea'                   , 'Yes', 'No' ),
	array('Heligoland Bight'            ,'North Sea'                   , 'Yes', 'No' ),
	array('English Channel'             ,'North Sea'                   , 'Yes', 'No' ),
	array('Sweden'                      ,'Skagerrack'                  , 'Yes', 'No' ),
	array('Norway'                      ,'Skagerrack'                  , 'Yes', 'No' ),
	array('Denmark'                     ,'Skagerrack'                  , 'Yes', 'No' ),
	array('North Sea'                   ,'Skagerrack'                  , 'Yes', 'No' ),
	array('Denmark'                     ,'Heligoland Bight'            , 'Yes', 'No' ),
	array('Kiel'                        ,'Heligoland Bight'            , 'Yes', 'No' ),
	array('Holland'                     ,'Heligoland Bight'            , 'Yes', 'No' ),
	array('North Sea'                   ,'Heligoland Bight'            , 'Yes', 'No' ),
	array('Livonia'                     ,'Baltic Sea'                  , 'Yes', 'No' ),
	array('Sweden'                      ,'Baltic Sea'                  , 'Yes', 'No' ),
	array('Denmark'                     ,'Baltic Sea'                  , 'Yes', 'No' ),
	array('Kiel'                        ,'Baltic Sea'                  , 'Yes', 'No' ),
	array('Berlin'                      ,'Baltic Sea'                  , 'Yes', 'No' ),
	array('Prussia'                     ,'Baltic Sea'                  , 'Yes', 'No' ),
	array('Gulf of Bothnia'             ,'Baltic Sea'                  , 'Yes', 'No' ),
	array('Livonia'                     ,'Gulf of Bothnia'             , 'Yes', 'No' ),
	array('Finland'                     ,'Gulf of Bothnia'             , 'Yes', 'No' ),
	array('Sweden'                      ,'Gulf of Bothnia'             , 'Yes', 'No' ),
	array('Baltic Sea'                  ,'Gulf of Bothnia'             , 'Yes', 'No' ),
	array('St. Petersburg (South Coast)','Gulf of Bothnia'             , 'Yes', 'No' ),
	array('Clyde'                       ,'North Atlantic Ocean'        , 'Yes', 'No' ),
	array('Liverpool'                   ,'North Atlantic Ocean'        , 'Yes', 'No' ),
	array('Norwegian Sea'               ,'North Atlantic Ocean'        , 'Yes', 'No' ),
	array('Irish Sea'                   ,'North Atlantic Ocean'        , 'Yes', 'No' ),
	array('Mid-Atlantic Ocean'          ,'North Atlantic Ocean'        , 'Yes', 'No' ),
	array('Liverpool'                   ,'Irish Sea'                   , 'Yes', 'No' ),
	array('Wales'                       ,'Irish Sea'                   , 'Yes', 'No' ),
	array('North Atlantic Ocean'        ,'Irish Sea'                   , 'Yes', 'No' ),
	array('English Channel'             ,'Irish Sea'                   , 'Yes', 'No' ),
	array('Mid-Atlantic Ocean'          ,'Irish Sea'                   , 'Yes', 'No' ),
	array('Wales'                       ,'English Channel'             , 'Yes', 'No' ),
	array('London'                      ,'English Channel'             , 'Yes', 'No' ),
	array('Belgium'                     ,'English Channel'             , 'Yes', 'No' ),
	array('Picardy'                     ,'English Channel'             , 'Yes', 'No' ),
	array('Brest'                       ,'English Channel'             , 'Yes', 'No' ),
	array('North Sea'                   ,'English Channel'             , 'Yes', 'No' ),
	array('Irish Sea'                   ,'English Channel'             , 'Yes', 'No' ),
	array('Mid-Atlantic Ocean'          ,'English Channel'             , 'Yes', 'No' ),
	array('Portugal'                    ,'Mid-Atlantic Ocean'          , 'Yes', 'No' ),
	array('North Africa'                ,'Mid-Atlantic Ocean'          , 'Yes', 'No' ),
	array('Brest'                       ,'Mid-Atlantic Ocean'          , 'Yes', 'No' ),
	array('Gascony'                     ,'Mid-Atlantic Ocean'          , 'Yes', 'No' ),
	array('North Atlantic Ocean'        ,'Mid-Atlantic Ocean'          , 'Yes', 'No' ),
	array('Irish Sea'                   ,'Mid-Atlantic Ocean'          , 'Yes', 'No' ),
	array('English Channel'             ,'Mid-Atlantic Ocean'          , 'Yes', 'No' ),
	array('Western Mediterranean'       ,'Mid-Atlantic Ocean'          , 'Yes', 'No' ),
	array('Spain (North Coast)'         ,'Mid-Atlantic Ocean'          , 'Yes', 'No' ),
	array('Spain (South Coast)'         ,'Mid-Atlantic Ocean'          , 'Yes', 'No' ),
	array('North Africa'                ,'Western Mediterranean'       , 'Yes', 'No' ),
	array('Tunis'                       ,'Western Mediterranean'       , 'Yes', 'No' ),
	array('Mid-Atlantic Ocean'          ,'Western Mediterranean'       , 'Yes', 'No' ),
	array('Gulf of Lyons'               ,'Western Mediterranean'       , 'Yes', 'No' ),
	array('Tyrrhenian Sea'              ,'Western Mediterranean'       , 'Yes', 'No' ),
	array('Spain (South Coast)'         ,'Western Mediterranean'       , 'Yes', 'No' ),
	array('Tuscany'                     ,'Gulf of Lyons'               , 'Yes', 'No' ),
	array('Piedmont'                    ,'Gulf of Lyons'               , 'Yes', 'No' ),
	array('Marseilles'                  ,'Gulf of Lyons'               , 'Yes', 'No' ),
	array('Western Mediterranean'       ,'Gulf of Lyons'               , 'Yes', 'No' ),
	array('Tyrrhenian Sea'              ,'Gulf of Lyons'               , 'Yes', 'No' ),
	array('Spain (South Coast)'         ,'Gulf of Lyons'               , 'Yes', 'No' ),
	array('Tunis'                       ,'Tyrrhenian Sea'              , 'Yes', 'No' ),
	array('Naples'                      ,'Tyrrhenian Sea'              , 'Yes', 'No' ),
	array('Rome'                        ,'Tyrrhenian Sea'              , 'Yes', 'No' ),
	array('Tuscany'                     ,'Tyrrhenian Sea'              , 'Yes', 'No' ),
	array('Western Mediterranean'       ,'Tyrrhenian Sea'              , 'Yes', 'No' ),
	array('Gulf of Lyons'               ,'Tyrrhenian Sea'              , 'Yes', 'No' ),
	array('Ionian Sea'                  ,'Tyrrhenian Sea'              , 'Yes', 'No' ),
	array('Tunis'                       ,'Ionian Sea'                  , 'Yes', 'No' ),
	array('Naples'                      ,'Ionian Sea'                  , 'Yes', 'No' ),
	array('Apulia'                      ,'Ionian Sea'                  , 'Yes', 'No' ),
	array('Greece'                      ,'Ionian Sea'                  , 'Yes', 'No' ),
	array('Albania'                     ,'Ionian Sea'                  , 'Yes', 'No' ),
	array('Tyrrhenian Sea'              ,'Ionian Sea'                  , 'Yes', 'No' ),
	array('Adriatic Sea'                ,'Ionian Sea'                  , 'Yes', 'No' ),
	array('Aegean Sea'                  ,'Ionian Sea'                  , 'Yes', 'No' ),
	array('Eastern Mediterranean'       ,'Ionian Sea'                  , 'Yes', 'No' ),
	array('Venice'                      ,'Adriatic Sea'                , 'Yes', 'No' ),
	array('Apulia'                      ,'Adriatic Sea'                , 'Yes', 'No' ),
	array('Albania'                     ,'Adriatic Sea'                , 'Yes', 'No' ),
	array('Ionian Sea'                  ,'Adriatic Sea'                , 'Yes', 'No' ),
	array('Trieste'                     ,'Adriatic Sea'                , 'Yes', 'No' ),
	array('Greece'                      ,'Aegean Sea'                  , 'Yes', 'No' ),
	array('Constantinople'              ,'Aegean Sea'                  , 'Yes', 'No' ),
	array('Smyrna'                      ,'Aegean Sea'                  , 'Yes', 'No' ),
	array('Ionian Sea'                  ,'Aegean Sea'                  , 'Yes', 'No' ),
	array('Eastern Mediterranean'       ,'Aegean Sea'                  , 'Yes', 'No' ),
	array('Bulgaria (South Coast)'      ,'Aegean Sea'                  , 'Yes', 'No' ),
	array('Smyrna'                      ,'Eastern Mediterranean'       , 'Yes', 'No' ),
	array('Syria'                       ,'Eastern Mediterranean'       , 'Yes', 'No' ),
	array('Ionian Sea'                  ,'Eastern Mediterranean'       , 'Yes', 'No' ),
	array('Aegean Sea'                  ,'Eastern Mediterranean'       , 'Yes', 'No' ),
	array('Rumania'                     ,'Black Sea'                   , 'Yes', 'No' ),
	array('Constantinople'              ,'Black Sea'                   , 'Yes', 'No' ),
	array('Ankara'                      ,'Black Sea'                   , 'Yes', 'No' ),
	array('Armenia'                     ,'Black Sea'                   , 'Yes', 'No' ),
	array('Sevastopol'                  ,'Black Sea'                   , 'Yes', 'No' ),
	array('Bulgaria (North Coast)'      ,'Black Sea'                   , 'Yes', 'No' ),
	array('Piedmont'                    ,'Tyrolia'                     , 'No' , 'Yes'),
	array('Venice'                      ,'Tyrolia'                     , 'No' , 'Yes'),
	array('Munich'                      ,'Tyrolia'                     , 'No' , 'Yes'),
	array('Bohemia'                     ,'Tyrolia'                     , 'No' , 'Yes'),
	array('Vienna'                      ,'Tyrolia'                     , 'No' , 'Yes'),
	array('Trieste'                     ,'Tyrolia'                     , 'No' , 'Yes'),
	array('Silesia'                     ,'Bohemia'                     , 'No' , 'Yes'),
	array('Munich'                      ,'Bohemia'                     , 'No' , 'Yes'),
	array('Tyrolia'                     ,'Bohemia'                     , 'No' , 'Yes'),
	array('Vienna'                      ,'Bohemia'                     , 'No' , 'Yes'),
	array('Galicia'                     ,'Bohemia'                     , 'No' , 'Yes'),
	array('Tyrolia'                     ,'Vienna'                      , 'No' , 'Yes'),
	array('Bohemia'                     ,'Vienna'                      , 'No' , 'Yes'),
	array('Trieste'                     ,'Vienna'                      , 'No' , 'Yes'),
	array('Budapest'                    ,'Vienna'                      , 'No' , 'Yes'),
	array('Galicia'                     ,'Vienna'                      , 'No' , 'Yes'),
	array('Venice'                      ,'Trieste'                     , 'Yes', 'Yes'),
	array('Albania'                     ,'Trieste'                     , 'Yes', 'Yes'),
	array('Serbia'                      ,'Trieste'                     , 'No' , 'Yes'),
	array('Adriatic Sea'                ,'Trieste'                     , 'Yes', 'No' ),
	array('Tyrolia'                     ,'Trieste'                     , 'No' , 'Yes'),
	array('Vienna'                      ,'Trieste'                     , 'No' , 'Yes'),
	array('Budapest'                    ,'Trieste'                     , 'No' , 'Yes'),
	array('Serbia'                      ,'Budapest'                    , 'No' , 'Yes'),
	array('Rumania'                     ,'Budapest'                    , 'No' , 'Yes'),
	array('Vienna'                      ,'Budapest'                    , 'No' , 'Yes'),
	array('Trieste'                     ,'Budapest'                    , 'No' , 'Yes'),
	array('Galicia'                     ,'Budapest'                    , 'No' , 'Yes'),
	array('Rumania'                     ,'Galicia'                     , 'No' , 'Yes'),
	array('Ukraine'                     ,'Galicia'                     , 'No' , 'Yes'),
	array('Warsaw'                      ,'Galicia'                     , 'No' , 'Yes'),
	array('Silesia'                     ,'Galicia'                     , 'No' , 'Yes'),
	array('Bohemia'                     ,'Galicia'                     , 'No' , 'Yes'),
	array('Vienna'                      ,'Galicia'                     , 'No' , 'Yes'),
	array('Budapest'                    ,'Galicia'                     , 'No' , 'Yes'),
	array('Portugal'                    ,'Spain (North Coast)'         , 'Yes', 'No' ),
	array('Gascony'                     ,'Spain (North Coast)'         , 'Yes', 'No' ),
	array('Mid-Atlantic Ocean'          ,'Spain (North Coast)'         , 'Yes', 'No' ),
	array('Portugal'                    ,'Spain (South Coast)'         , 'Yes', 'No' ),
	array('Marseilles'                  ,'Spain (South Coast)'         , 'Yes', 'No' ),
	array('Mid-Atlantic Ocean'          ,'Spain (South Coast)'         , 'Yes', 'No' ),
	array('Western Mediterranean'       ,'Spain (South Coast)'         , 'Yes', 'No' ),
	array('Gulf of Lyons'               ,'Spain (South Coast)'         , 'Yes', 'No' ),
	array('Norway'                      ,'St. Petersburg (North Coast)', 'Yes', 'No' ),
	array('Barents Sea'                 ,'St. Petersburg (North Coast)', 'Yes', 'No' ),
	array('Livonia'                     ,'St. Petersburg (South Coast)', 'Yes', 'No' ),
	array('Finland'                     ,'St. Petersburg (South Coast)', 'Yes', 'No' ),
	array('Gulf of Bothnia'             ,'St. Petersburg (South Coast)', 'Yes', 'No' ),
	array('Rumania'                     ,'Bulgaria (North Coast)'      , 'Yes', 'No' ),
	array('Constantinople'              ,'Bulgaria (North Coast)'      , 'Yes', 'No' ),
	array('Black Sea'                   ,'Bulgaria (North Coast)'      , 'Yes', 'No' ),
	array('Greece'                      ,'Bulgaria (South Coast)'      , 'Yes', 'No' ),
	array('Constantinople'              ,'Bulgaria (South Coast)'      , 'Yes', 'No' ),
	array('Aegean Sea'                  ,'Bulgaria (South Coast)'      , 'Yes', 'No' )
);

foreach($bordersRawData as $borderRawRow)
{
	list($from, $to, $fleets, $armies)=$borderRawRow;
	InstallTerritory::$Territories[$to]  ->addBorder(InstallTerritory::$Territories[$from],$fleets,$armies);
}
unset($bordersRawData);

InstallTerritory::runSQL($this->mapID);
InstallCache::terrJSON($this->territoriesJSONFile(),$this->mapID);

?>