<?php
/**
 *
 * Author: ArmyZ Rdz
 * Comaptible with: MyBB 1.4
 * Website: http://www.generalzone.net
 * License: GNU/GPL
 * Utilicelo a su propio riesgo
 * Disfrutalo =D
 *
 */
 
if(!defined('IN_MYBB'))
{
	die();
}
$plugins->add_hook("global_start", "imgflotante");
function imgflotante_info(){
	return array(
		"name"			=> "Imagen Flotante Para Invitados",
		"description"	=> "Este plugin muestra una imagen flotante para los invitados",
		"website"		=> "http://www.generalzone.net",
		"author"		=> "ArmyZ Rodriguez",
		"authorsite"	=> "http://www.generalzone.net",
		"version"		=> "1.2.1",
		"compatibility" => "14*,16*",
		"guid"			=> ""
		);
}
function imgflotante_activate(){
  global $db;
  
include MYBB_ROOT."/inc/adminfunctions_templates.php";
find_replace_templatesets("header", "#".preg_quote('{$bannedwarning}')."#i", '{\$bannedwarning}{\$imgflotante}');
 
 $imgflotante_group = array(
		'gid'			=> 'NULL',
		'name'			=> 'imgflotante',
		'title'			=> 'Imagen flotante para invitados',
		'description'	=> 'Settings For The imgflotante Plugin',
		'disporder'		=> "1",
		'isdefault'		=> 'no',
	);
	$db->insert_query('settinggroups', $imgflotante_group);
	$gid = $db->insert_id();
	
	$imgflotante_setting_1 = array(
		'sid'			=> 'NULL',
		'name'			=> 'enabled_imgflotante',
		'title'			=> 'Activado/Desactivado',
		'description'	=> '',
		'optionscode'	=> 'yesno',
		'value'			=> '1',
		'disporder'		=> 1,
		'gid'			=> intval($gid),
	);
	$db->insert_query('settings', $imgflotante_setting_1);
		
	$imgflotante_setting_2 = array(
		'sid'			=> 'NULL',
		'name'			=> 'imgflotante_location',
		'title'			=> 'Arriva o Abajo?',
		'description'	=> 'puedes poner "fromtop" para colocar la imagen arriva o "frombottom" para colocarla abajo',
		'optionscode'	=> 'text',
		'value'			=> 'fromtop',
		'disporder'		=> 2,
		'gid'			=> intval($gid),
	);
	$db->insert_query('settings', $imgflotante_setting_2);
	
		$imgflotante_setting_3 = array(
		'sid'			=> 'NULL',
		'name'			=> 'imgflotante_img',
		'title'			=> 'Ruta de la Imagen',
		'description'	=> 'escribe la ruta nueva de tu imagen que se mostrara a los invitados...',
		'optionscode'	=> 'text',
		'value'			=> 'banner.php',
		'disporder'		=> 3,
		'gid'			=> intval($gid),
	);
	$db->insert_query('settings', $imgflotante_setting_3);
		
			$imgflotante_setting_4 = array(
		'sid'			=> 'NULL',
		'name'			=> 'imgflotante_img_close',
		'title'			=> 'Ruta de la Imagen para cerrar',
		'description'	=> 'escribe la ruta nueva de tu imagen que se mostrara a los invitados donde se cerrara...',
		'optionscode'	=> 'text',
		'value'			=> 'banner.html',
		'disporder'		=> 4,
		'gid'			=> intval($gid),
	);
	$db->insert_query('settings', $imgflotante_setting_4);


	rebuild_settings();
}
function imgflotante_deactivate(){
  global $db;

include MYBB_ROOT."/inc/adminfunctions_templates.php";
find_replace_templatesets("header", "#".preg_quote('{$imgflotante}')."#i", '', 0);

$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN ('imgflotante_location','enabled_imgflotante','imgflotante_img','imgflotante_img_close')");
$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='imgflotante'");
rebuild_settings();

}
function imgflotante()
{
	global $mybb;
	 if ($mybb->settings['enabled_imgflotante'] == 1)
    {
	if($mybb->user['usergroup']==1)
	{
		global $templates,$settings,$imgflotante;
		$imgflotante = '<script type="text/javascript" src="jscripts/imgflotante.js?ver=1400"></script>
		<script type="text/javascript"> 
		var verticalpos = "'.$mybb->settings['imgflotante_location'].'";
		</script> 
		<style type="text/css">#topbar{position:absolute;border: 0px solid white;padding: 5px;background-color: transparent;width: 450px;visibility: hidden;z-index: 400;}</style><div id="topbar"><a href="'.$mybb->settings['bburl'].'/member.php?action=register"><img src="'.$mybb->settings['imgflotante_img'].'"" alt="Registrarte!" border="0" /></a><a href="#" onclick="closebar(); return false"><img src="'.$mybb->settings['imgflotante_img_close'].'" alt="Cerrar" border="0" /></a></div>';
		}
    }
}
?>