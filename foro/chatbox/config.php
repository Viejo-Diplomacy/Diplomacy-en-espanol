<?php
# CHANGUONDYU 10/2009 #

date_default_timezone_set('America/Mexico_City'); // change your location for true time
#read this page to set true timezone http://www.php.net/manual/en/timezones.php

############ SETTINGS ########
/*
false = NO
true = YES
*/ 

// Security
$config['check_domain_reffer'] = false; // Check reffer url
$config['check_chatbox_key'] = false; // check ChatboxKey

$config['forumlink'] = 'domain1.net/forum,domain2.com'; //Forum url
$config['chatboxkey'] = 'your_chatbox_key'; // ChatboxKey

$config['password_tools'] = '123456'; // Password for tools.php
$config['managegroup'] = "3,4,6"; // Managed Group 3=Smod, 4=Admin

// POST
$config['checkflood'] = true; // Check Flood, spam
$config['strip_slash'] = true; // Strip Slash ?
$config['max_message_len'] = 255; // Max message character
$config['remove_badword'] = true; // Check bad word ?

// Message
$config['autorefresh'] = 10; // Auto refresh time (in second)
$config['maxmessage'] = 30; // Number of message show on chatbox
$config['archive_messageperpage'] = 50; // Number of message per page on Archive popup
$config['removelink'] = false; // Remove link
$config['linkmask'] = true; // Mask link [link]

$config['use_me'] = true; // Use /me command ?
$command['me'] = '/me';

// Time Setting
$config['showtime'] = true; // Show/Hide Time
$config['timeformat'] = "h:i A"; // Time format
$config['dateformat'] = "d-m"; // date format


############ PHRASE ###############
$phrase['prune'] = "Ha limpiado el chat";
$phrase['archive'] = "Archivo";
$phrase['today'] = "Hoy";
$phrase['yesterday'] = "Ayer";
$phrase['linkmask'] = "[Link]";
$phrase['linkremoved'] = "<i>[Link ha sido removido]</i>";
$phrase['bannotice'] = "Has sido baneado del Chat";
$phrase['notice'] = "<b>Noticia(s)</b>: ";

$phrase['banned'] = "Baneado el miembro";
$phrase['unbanned'] = "Desbaneado el miembro";
$phrase['banned_name'] = "Baneado";
$phrase['unbanned_name'] = "Desbaneado";

$phrase['load'] = "<i>Cargando...</i> ";
$phrase['accessdenied'] = "<b>Acceso Denegado (ChatboxKey incorrecta o URL)</b>";
$phrase['pruneusernotice'] = "Ha borrado todos los mensajes de:";
$phrase['nomessagefound'] = '<b>No hay mensajes de este usuario</b>';
$phrase['checkflood'] = '<b>Intentas Hacer Flood ?</b>';
$phrase['reason'] = 'Razon';

######## Command ####
$command['prune'] = '/borrar';
$command['ban'] = '/banear';
$command['notice'] = '/noticia';
$command['unban'] = '/desbanear';

######## File name ########
$fcbfile['message'] = 'fcb_message.txt';
$fcbfile['notice'] = 'fcb_notice.txt';
$fcbfile['smilie'] = 'fcb_smilies.txt';
$fcbfile['badword'] = 'fcb_badword.txt';

// datastore file
$fcbfile['ds_smilie'] = 'ds_smilies.txt';
$fcbfile['ds_banned'] = 'ds_banned.txt';
$fcbfile['ds_lastshout'] = 'ds_lastshout.txt';
$fcbfile['ds_notice'] = 'ds_notice.txt';

############# NOT SETTINGS - Don't change ##########################
$config['cbforumlink'] = explode(',' , $config['forumlink']);
$config['cbforumlink'] = $config['cbforumlink'][0];
?>