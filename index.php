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

/**
 * @package Base
 */
require_once('header.php');

require_once(l_r('lib/message.php'));

require_once(l_r('objects/game.php'));

require_once(l_r('gamepanel/gamehome.php'));

/*
 * A field
 *
 * add(field, index)
 * compare(field1, field2) -> 1 if aligned, 0 if not
 *
 */
libHTML::starthtml(l_t('Home'));


if( !isset($_SESSION['lastSeenHome']) || $_SESSION['lastSeenHome'] < $User->timeLastSessionEnded )
{
	$_SESSION['lastSeenHome']=$User->timeLastSessionEnded;
}

class libHome
{
	static public function getType($type=false, $limit=15)
	{
		global $DB, $User;

		$notices=array();

		$tabl=$DB->sql_tabl("SELECT *
			FROM wD_Notices WHERE toUserID=".$User->id.($type ? " AND type='".$type."'" : '')."
			ORDER BY timeSent DESC ".($limit?'LIMIT '.$limit:''));
		while($hash=$DB->tabl_hash($tabl))
		{
			$notices[] = new notice($hash);
		}

		return $notices;
	}
	public static function PMs()
	{
		$pms = self::getType('PM', 10);
		$buf = '';
		foreach($pms as $pm)
		{
			$buf .= $pm->html();
		}
		return $buf;
	}
	public static function Game()
	{
		global $User;

		$pms = self::getType('Game');

		if(!count($pms))
		{
			print '<div class="hr"></div>';
			print '<p class="notice">'.l_t('No game notices found.').'</p>';
			return;
		}

		print '<div class="hr"></div>';

		foreach($pms as $pm)
		{
			print $pm->viewedSplitter();

			print $pm->html();
		}
	}
	public static function NoticePMs()
	{
		global $User;

		try
		{
			$message=notice::sendPMs();
		}
		catch(Exception $e)
		{
			$message=$e->getMessage();
		}

		if ( $message )
			print '<p class="notice">'.$message.'</p>';

		$pms = self::getType('PM');

		if(!count($pms))
		{
			print '<div class="hr"></div>';
			print '<p class="notice">'.l_t('No private messages found; you can send them to other people on their profile page.').'</p>';
			return;
		}

		print '<div class="hr"></div>';

		foreach($pms as $pm)
		{
			print $pm->viewedSplitter();

			print $pm->html();
		}
	}
	public static function NoticeGame()
	{
		global $User;

		$pms = self::getType('Game');

		if(!count($pms))
		{
			print '<div class="hr"></div>';
			print '<p class="notice">'.l_t('No game notices found; try browsing the <a href="gamelistings.php">game listings</a>, '.
				'or <a href="gamecreate.php">create your own</a> game.').'</p>';
			return;
		}

		print '<div class="hr"></div>';

		foreach($pms as $pm)
		{
			print $pm->viewedSplitter();

			print $pm->html();
		}
	}
	public static function Notice()
	{
		global $User;

		$pms = self::getType();

		if(!count($pms))
		{
			print '<div class="hr"></div>';
			print '<p class="notice">'.l_t('No notices found.').'</p>';
			return;
		}

		print '<div class="hr"></div>';

		foreach($pms as $pm)
		{
			print $pm->viewedSplitter();

			print $pm->html();
		}
	}
	static function topUsers()
	{
		global $DB;
		$rows=array();
		$tabl = $DB->sql_tabl("SELECT id, username, points FROM wD_Users
						order BY points DESC LIMIT 10");
		$i=1;
		while(list($userID,$username,$points)=$DB->tabl_row($tabl))
		{
	    $UserProfile = new User($userID);
   		$rankingDetails = $UserProfile->rankingDetails();
		
		
		if( $UserProfile->type['Moderator'] )
			$moderadorMarker = '<img src="images/icons/medal7.png" alt="Mod" title="Moderador" />';
		else
			 $moderadorMarker = false;
		if( $UserProfile->type['LigaGanador1'] )
			$donatorMarker = libHTML::ligaganador1().'';
		elseif( $UserProfile->type['LigaParticipa'] )
			$donatorMarker = libHTML::ligaparticipa().'';
		else
			$donatorMarker = false;
		$rows[] = '<span style="font-size:12px">#'.$i.'</span>: <a href="profile.php?userID='.$UserProfile->id.'" style="text-align:center; font-size:12px; text-decoration:none;">'.$UserProfile->username.'</a> ('.$rankingDetails['worth'].libHTML::points().' '.$rankingDetails['rank'].$moderadorMarker.$donatorMarker.')' ;
			$i++;
		}
		return $rows;
	}
	static function statsGlobalGame()
	{
		global $Misc;
		$stats=array(
			'Nuevas'=>$Misc->GamesNew,
			'Abiertas'=>$Misc->GamesOpen,
			'Activas'=>$Misc->GamesActive,
			'Finalizadas'=>$Misc->GamesFinished
		);

		return $stats;
	}
	static function statsGlobalUser()
	{
		global $Misc;
		$stats=array(
			'Logged on'=>$Misc->OnlinePlayers,
			'Playing'=>$Misc->ActivePlayers,
			'Registered'=>$Misc->TotalPlayers
		);

		if( $stats['Logged on'] <= 1 ) unset($stats['Logged on']);
		if( $stats['Playing'] < 25 ) unset($stats['Playing']);

		return $stats;
	}
	static function globalInfo()
	{
		$userStats = self::statsGlobalUser();
		$gameStats = self::statsGlobalGame();
		$topUsers = self::topUsers();

		//$buf='<div class="content" style="text-align:center;"><strong>Users:</strong> ';
		$buf='<strong>'.l_t('Users:').'</strong> ';
		$first=true;
		foreach($userStats as $name => $val)
		{
			if( $first ) $first=false; else $buf .= ' - ';
			$buf .= l_t($name).':<strong>'.$val.'</strong>';
		}

		$buf .= '<br /><strong>'.l_t('Games:').'</strong> ';
		$first=true;
		foreach($gameStats as $name => $val)
		{
			if( $first ) $first=false; else $buf .= ' - ';
			$buf .= l_t($name).':<strong>'.$val.'</strong>';
		}

		//$buf .= '</div>';
		//$buf .= '<br /><h3>Top 10</h3>'.implode('<br />',$topUsers);


		return $buf;
	}
static function globalInfo2()
	{
		$userStats = self::statsGlobalUser();
		$gameStats = self::statsGlobalGame();
		$topUsers = self::topUsers(); 

		//$buf='';
		$buf='';

		//$buf .= '</div>';
		$buf .= '<div class="homeForumMessage" style="margin-right:40px;text-align:left; text-decoration:none; "><br /><h3 style="font-family:Kaushan Script, normal;text-align:center; ">Top 10</h3><span style="font-size:10px; color:#fff;text-align:center;margin-left:50px;">(seg&uacute;n ranking webDip)</span><br /><br />'.implode('<br />',$topUsers);
		$buf .= '<br /><a href="clasificacion.php" title="Ver ranking" style="font-size:10px; color:#fff; text-decoration:none"><center>Aqu&iacute; puedes ver toda la clasificaci&oacute;n y el total de puntos y partidas.<center></a></div>';
		return $buf;
	}

	static public function gameNotifyBlock ()
	{
		global $User, $DB;

		$tabl=$DB->sql_tabl("SELECT g.* FROM wD_Games g
			INNER JOIN wD_Members m ON ( m.userID = ".$User->id." AND m.gameID = g.id )
			WHERE NOT g.phase = 'Finished'
			ORDER BY g.processTime ASC");
		$buf = '';

		$count=0;
		while($game=$DB->tabl_hash($tabl))
		{
			$count++;
			$Variant=libVariant::loadFromVariantID($game['variantID']);
			$Game=$Variant->panelGameHome($game);

			$buf .= '<div class="hr"></div>';
			$buf .= $Game->summary();
		}

		if($count==0)
		{
			$buf .= '<div class="hr"></div>';
			$buf .= '<div><p class="notice">'.l_t('You\'re not joined to any games!').'<br />
				'.l_t('Access the <a href="gamelistings.php?tab=">Games</a> '.
				'link above to find games you can join, or start a '.
				'<a href="gamecreate.php">New game</a> yourself.</a>').'</p></div>';
		}
		elseif ( $count == 1 && $User->points > 5 )
		{
			$buf .= '<div class="hr"></div>';
			$buf .= '<div><p class="notice">'.l_t('You can join as many games as you '.
			'have the points to join.').' </a></p></div>';
		}
		return $buf;
	}

	static function forumNew() {
		// Select by id, prints replies and new threads
		global $DB, $Misc, $User;

		$tabl = $DB->sql_tabl("
			SELECT m.id as postID, t.id as threadID, m.type, m.timeSent, IF(t.replies IS NULL,m.replies,t.replies) as replies,
				IF(t.subject IS NULL,m.subject,t.subject) as subject,
				m.anon,
				u.id as userID, u.username, u.points, IF(s.userID IS NULL,0,1) as online, u.type as userType,
				SUBSTRING(m.message,1,100) as message, m.latestReplySent, t.fromUserID as threadStarterUserID
			FROM wD_ForumMessages m
			INNER JOIN wD_Users u ON ( m.fromUserID = u.id )
			LEFT JOIN wD_Sessions s ON ( m.fromUserID = s.userID )
			LEFT JOIN wD_ForumMessages t ON ( m.toID = t.id AND t.type = 'ThreadStart' AND m.type = 'ThreadReply' )
			ORDER BY m.timeSent DESC
			LIMIT 50");
		$oldThreads=0;
		$threadCount=0;

		$threadIDs = array();
		$threads = array();

		while(list(
				$postID, $threadID, $type, $timeSent, $replies, $subject,
				$anon,
				$userID, $username, $points, $online, $userType, $message, $latestReplySent,$threadStarterUserID
			) = $DB->tabl_row($tabl))
		{
		
			// Anonymize the forum posts on the home-screen too
			if ($anon == 'Yes')
			{
				$username = 'Anon';
				$userID = 0;
				$points = '??';
				$userType = 'User';
			}
			// End anonymizer
			
			$threadCount++;

			if( $threadID )
				$iconMessage=libHTML::forumMessage($threadID, $postID);
			else
				$iconMessage=libHTML::forumMessage($postID, $postID);

			if ( $type == 'ThreadStart' ) $threadID = $postID;

			if( !isset($threads[$threadID]) )
			{
				if(strlen($subject)>30) $subject = substr($subject,0,40).'...';
				$threadIDs[] = $threadID;
				$threads[$threadID] = array('subject'=>$subject, 'replies'=>$replies,
					'posts'=>array(),'threadStarterUserID'=>$threadStarterUserID);
			}

			$message=Message::refilterHTML($message);

			if( strlen($message) >= 50 ) $message = substr($message,0,50).'...';

			$message = '<div class="message-contents threadID'.$threadID.'" fromUserID="'.$userID.'">'.$message.'</div>';

			$threads[$threadID]['posts'][] = array(
				'iconMessage'=>$iconMessage,'userID'=>$userID, 'username'=>$username,
				'message'=>$message,'points'=>$points, 'online'=>$online, 'userType'=>$userType, 'timeSent'=>$timeSent
			);
		}

		$buf = '';
		$threadCount=0;
		foreach($threadIDs as $threadID)
		{
			$data = $threads[$threadID];

			$buf .= '<div class="hr userID'.$threads[$threadID]['threadStarterUserID'].' threadID'.$threadID.'"></div>';

			$buf .= '<div class="homeForumGroup homeForumAlt'.($threadCount%2 + 1).
				' userID'.$threads[$threadID]['threadStarterUserID'].' threadID'.$threadID.'">
				<div class="homeForumSubject homeForumTopBorder">'.libHTML::forumParticipated($threadID).' '.$data['subject'].'</div> ';

			if( count($data['posts']) < $data['replies'])
			{
				$buf .= '<div class="homeForumPost homeForumMessage homeForumPostAlt'.libHTML::alternate().' ">

				...</div>';
			}


			$data['posts'] = array_reverse($data['posts']);
			foreach($data['posts'] as $post)
			{
				$buf .= '<div class="homeForumPost homeForumPostAlt'.libHTML::alternate().' userID'.$post['userID'].'">


					<div class="homeForumPostTime">'.libTime::text($post['timeSent']).' '.$post['iconMessage'].'</div>
					<a href="profile.php?userID='.$post['userID'].'" class="light">'.$post['username'].'</a>
						'.libHTML::loggedOn($post['userID']) . ' ('.$post['points'].libHTML::points().
						User::typeIcon($post['userType']).')

					<div style="clear:both"></div>
					<div class="homeForumMessage">'.$post['message'].'</div>
					</div>';

			}

			$buf .= '<div class="homeForumLink">
					<div class="homeForumReplies">'.l_t('%s replies','<strong>'.$data['replies'].'</strong>').'</div>
					<a href="forum.php?threadID='.$threadID.'#'.$threadID.'">'.l_t('Open').'</a>
					</div>
					</div>';
		}

		if( $buf )
		{
			return $buf;
		}
		else
		{
			return '<div class="homeNoActivity">'.l_t('No forum posts found, why not '.
				'<a href="forum.php?postboxopen=1#postbox" class="light">start one</a>?');
		}
	}


	static function forumBlock()
	{
		$buf = '<div class="homeHeader">'.l_t('Forum').'</div>';

		$forumNew=libHome::forumNew();
		$buf .=  '<table><tr><td>'.implode('</td></tr><tr><td>',$forumNew).'</td></tr></table>';
		return $buf;
	}
}

if( !$User->type['User'] )
{
	print '<div class="content-notice" style="text-align:center">'.libHome::globalInfo().'</div>';
	print libHTML::pageTitle('Bienvenidos a la Comunidad Hispana de Diplomacy','Un juego multijugador basado en turnos de estrategia en espa&ntildeol.');
	//print '<div class="content">';
	?>
	<p style="text-align: center;"><img
	s	src="images/startmap.jpg" alt="The map"
	title="mapa webDiplomacy" /></p>
<p class="Bienvenido"><em> "La suerte no participa en webdiplomacy. La astucia,
inteligencia, honestidad y la traición perfectamente programadas son las herramientas
necesarias para derrotar a tus contrincantes. El negociador más habil ascenderá hacia
la victoria sobre las espaldas de sus enemigos y amigos.<br />
<br />
¿En quien confias?"<br />
	<?php
	print '</div>';
	/*print '<div class="homeInfoList">
		'.libHome::globalInfo()
		.'</div>';*/

	require_once(l_r('locales/Spanish/intro.php'));
	print '</div>';
}
elseif( isset($_REQUEST['notices']) )
{
	$User->clearNotification('PrivateMessage');

	print '<div class="content"><a href="index.php" class="light">&lt; '.l_t('Back').'</a></div>';

	print '<div class="content-bare content-home-header">';
	print '<table class="homeTable"><tr>';

	notice::$noticesPage=true;

	print '<td class="homeNoticesPMs">';
	print '<div class="homeHeader">'.l_t('Private messages').'</a></div>';
	print libHome::NoticePMs();
	print '</td>';

	print '<td class="homeSplit"></td>';

	print '<td class="homeNoticesGame">';
	print '<div class="homeHeader">'.l_t('Game messages').'</a></div>';
	print libHome::NoticeGame();
	print '</td>';

	print '</tr></table>';
	print '</div>';
	print '</div>';
}
else
{	/*Noticas en portada*/
	print '<div class="content-bare content-home-header">';
	//print '<div class="boardHeader" style="color:#fff;"><center>El ganador de la Temporada Primavera-2013 de la LIGA<br /> es <a href"http://webdiplo.com" >gantz</a>. &iexcl;Enhorabuena! [<a href="http://www.webdiplo.com/foro/thread-212-lastpost.html" target="_blank">M&aacute;s info</a>]</div><hr>';
	print '<div class="boardHeader" style="color:#fff;"><center>Revisa todos los cambios y mejoras en el servidor <a href="http://www.webdiplo.com/foro/forumdisplay.php?fid=24" target="_blank">aqu&iacute;</a>.<center></div><hr>';
	print '<div class="boardHeader" style="color:#fff;"><center>Abierta inscripci&oacute;n hasta el 10/07/13 para el<br /><a href="http://www.webdiplo.com/foro/thread-216.html" target="_blank"><b>I Torneo Gunboat Mediterr&aacute;neo Antiguo</b<</a></div><hr>';
	print '</div>';
	
	print '<div class="content-bare content-home-header">';// content-follow-on">';

	print '<table class="homeTable"><tr>';

	print '<td class="homeMessages">';

	print '<div class="homeHeader"><a href="/foro" style="color: #e0a35e; font-family: Kaushan Script, cursive;" target="_blank">Foro</a></div>';
	if( file_exists(libCache::dirName('forum').'/home-forum.php') )
		print file_get_contents(libCache::dirName('forum').'/home-forum.php');
	else
	{
		$buf_home_forum=libHome::forumNew();
		file_put_contents(libCache::dirName('forum').'/home-forum.php', $buf_home_forum);
		print $buf_home_forum;
	}
	
	print libHome::globalInfo2();
	print '</td>';

	print '<td class="homeSplit"></td>';

	print '<td class="homeGameNotices">';

	/*$buf = libHome::PMs();
	if(strlen($buf))
		print '<div class="homeHeader">Private messages</div>'.$buf;
	*/

	print '<div class="homeHeader"><a href="index.php?notices=on" style="color: #e0a35e; font-family: Kaushan Script, cursive;">Noticias</a></div>';
	print libHome::Notice();
	print '</td>';

	print '<td class="homeSplit"></td>';

	print '<td class="homeGamesStats">';
	print '<div class="homeHeader"><a href="gamelistings.php?page=1&gamelistType=My Games" style="color: #e0a35e; font-family: Kaushan Script, cursive;">Mis Partidas</a></div>';
	print libHome::gameNotifyBlock();

	print '</td>
	</tr></table>';

	print '</div>';
	print '</div>';
}

libHTML::$footerIncludes[] = l_j('home.js');
libHTML::$footerScript[] = l_jf('homeGameHighlighter').'();';

$_SESSION['lastSeenHome']=time();

libHTML::footer();

?>