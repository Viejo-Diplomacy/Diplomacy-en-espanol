<?php
/* ChangUonDyU - mybbvn.com */

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

define('MOD_ID', 'changfcb');
define('MOD_NAME', 'ChangUonDyU - Extra File Chatbox');
define('MOD_DESC', 'Chatbox using file system');
define('MOD_VER', '3.6.0');

function changfcb_info()
{
	return array(
		"name"			=> MOD_NAME,
		"description"	=> MOD_DESC,
		"website"		=> "http://mybbvn.com",
		"author"		=> "ChangUonDyU",
		"authorsite"	=> "http://mybbvn.com",
		"version"		=> MOD_VER,
	);
}

function changfcb_is_installed()
{
	global $db;
	$query = $db->query("SELECT name FROM ".TABLE_PREFIX."settinggroups WHERE name='".MOD_ID."' LIMIT 1");
	if($db->num_rows($query))
	{
		return true;
	}
	return false;
}

function changfcb_install()
{
	global $db;
	
	### SETTINGGROUP ###
    $settinggroup = array(
		"name" =>			MOD_ID,
		"title" =>			MOD_NAME,
		"description" =>	MOD_DESC,
	);
    $db->insert_query("settinggroups", $settinggroup);
	$gid = intval($db->insert_id());
	
	### SETTINGS ###
    $setting[] = array(
		"name"			=> "changfcb_turn",
		"title"			=> "Enable chatbox ?",
		"optionscode"	=> "yesno",
		"value"			=> 1,
	);
	$setting[] = array(
		"name"			=> "changfcb_key",
		"title"			=> "ChatboxKey (for security)",
		"optionscode"	=> "text",
		"value"			=> "your_chatbox_key",
	);
    $setting[] = array(
		"name"			=> "changfcb_noview",
		"title"			=> "List of UsergroupID cant view Chatbox (Separate by comma)",
		"optionscode"	=> "text",
		"value"			=> "7",
	);
	$setting[] = array(
		"name"			=> "changfcb_noshout",
		"title"			=> "List of UsergroupID cant shout (Separate by comma)",
		"optionscode"	=> "text",
		"value"			=> "1,7",
	);
	$setting[] = array(
		"name" 			=> "changfcb_url",
		"title" 		=> "Chatbox URL",
		"description"	=> "dont add slash / at end",
		"optionscode" 	=> "text",
        "value" 		=> "http://yourhost.com/chatbox",
	);
	$setting[] = array(
		"name" 			=> "changfcb_smfile",
		"title" 		=> "Smilies file URL",
		"description"	=> "",
		"optionscode" 	=> "text",
        "value" 		=> "http://changuondyu.webs.com/fcb_smilies.txt",
	);
	$setting[] = array(
		"name" 			=> "changfcb_height",
		"title" 		=> "Chatbox Height",
		"description"	=> "include px at end",
		"optionscode" 	=> "text",
        "value" 		=> "200px",
	);
	$setting[] = array(
		"name" 			=> "changfcb_smperrow",
		"title" 		=> "Number of smilies per row on Smilies Popup",
		"description"	=> "",
		"optionscode" 	=> "text",
        "value" 		=> "3",
	);
	$setting[] = array(
		"name" 			=> "changfcb_numberrandom",
		"title" 		=> "Number of random smilies",
		"description"	=> "",
		"optionscode" 	=> "text",
        "value" 		=> "20",
	);
	/*
	$setting[] = array(
		"name" 			=> "changfcb_turnb",
		"title" 		=> "Cho phép sử dụng B(in đậm) ?",
		"description"	=> "",
		"optionscode" 	=> "yesno",
        "value" 		=> 1,
	);
	$setting[] = array(
		"name" 			=> "changfcb_turni",
		"title" 		=> "Cho phép sử dụng I (nghiêng) ?",
		"description"	=> "",
		"optionscode" 	=> "yesno",
        "value" 		=> 1,
	);
	$setting[] = array(
		"name" 			=> "changfcb_turnu",
		"title" 		=> "Cho phép sử dụng U (gạch chân) ?",
		"description"	=> "",
		"optionscode" 	=> "yesno",
        "value" 		=> 1,
	);
	$setting[] = array(
		"name" 			=> "changfcb_turnfont",
		"title" 		=> "Cho phép chọn font ?",
		"description"	=> "",
		"optionscode" 	=> "yesno",
        "value" 		=> 1,
	);
	$setting[] = array(
		"name" 			=> "changfcb_turncolor",
		"title" 		=> "Cho phép chọn màu ?",
		"description"	=> "",
		"optionscode" 	=> "yesno",
        "value" 		=> 1,
	);
	*/
	$setting[] = array(
		"name" 			=> "changfcb_fontlist",
		"title" 		=> "Font List",
		"description"	=> "Line by line",
		"optionscode" 	=> "textarea",
        "value" 		=> "Arial
Arial Black
Arial Narrow
Book Antiqua
Century Gothic
Comic Sans MS
Courier New
Fixedsys
Franklin Gothic Medium
Garamond
Georgia
Impact
Lucida Console
Lucida Sans Unicode
Microsoft Sans Serif
Palatino Linotype
System
Tahoma
Times New Roman
Trebuchet MS
Verdana",
	);
	$setting[] = array(
		"name" 			=> "changfcb_colorlist",
		"title" 		=> "Color List",
		"description"	=> "Line by line",
		"optionscode" 	=> "textarea",
        "value" 		=> "Gold
Khaki
Orange
LightPink
Salmon
Tomato
Red
Brown
Maroon
DarkGreen
DarkCyan
LightSeaGreen
LawnGreen
MediumSeaGreen
BlueViolet
Cyan
Blue
DodgerBlue
LightSkyBlue
White
DimGray
DarkGray
Black",
	);

	// INSERT SETTINGS - NO NEED CHANGE
	foreach ($setting AS $st)
	{
		$dorder++;
		$st['disporder'] = $dorder;
		$st['gid'] = $gid;
		$db->insert_query("settings", $st);
	}
	rebuild_settings();
	
	
	### TEMPLATE ###
	$template['changuondyu_chatbox_main'] = <<<FCB
<table class="tborder" cellpadding="\$theme[tablespace]" cellspacing="\$theme[borderwidth]" border="0" width="100%">
<tr><td class="thead">\$lang->fcb_title</td></tr>
<!-- EDITOR -->
<tr><td class="trow2">
<div id="fcb_smilieboxmain" style="display: none;">
	<div align="center">
		<input type="button" class="button" value="\$lang->fcb_moresm" onclick="fcb_showsmilies();" />
		<input type="button" class="button" value="\$lang->fcb_allsm" onclick="smiliepopup();" />
		<input type="button" class="button" value="\$lang->fcb_close" onclick="fcb_hideshowsmiliebox();" />
	</div>
	<div id="fcb_smiliebox" align="center" style="margin-top: 3px; margin-bottom: 3px;"></div>
</div>
<form name="fcb_form" method="post" action="javascript:fcb_postshout();">
<input id="hmess" type="text" size="100" class="textbox" name="hmess" \$fcb_disable />
<div style="margin-top: 2px;">
<input type="submit" class="button" value="\$lang->fcb_shout" \$fcb_disable />
<input style="font-weight: bold;" type="button" name="hbold" value="B" class="button" onclick="fcb_upstyle('b');" \$fcb_disable />
<input style="font-style: italic;" type="button" name="hitalic" value="I" class="button" onclick="fcb_upstyle('i');" \$fcb_disable />
<input style="text-decoration: underline;" type="button" name="hunderline" value="U" class="button" onclick="fcb_upstyle('u');" \$fcb_disable />
<input type="button" value="\$lang->fcb_smilies" onclick="fcb_showsmiliebox();" class="button" \$fcb_disable />
<select onchange="fcb_upstyle('font');" name="hfont" \$fcb_disable>
<option value="">\$lang->fcb_font</option>
\$fcb_fontlist
</select>
<select onchange="fcb_upstyle('color');" name="hcolor" \$fcb_disable>
<option value="">\$lang->fcb_color</option>
\$fcb_colorlist
</select>

<input type="button" value="\$lang->fcb_refresh" class="button" onclick="fcb_refresh();">
<input type="button" value="\$lang->fcb_archive" class="button" onclick="archivepage();">
</div>
</form>
</td></tr>
<!-- END EDITOR -->

<tr><td class="trow1">

<iframe name="fcb_frame" src="\$fcb_setting[changfcb_url]/index.php" frameborder="0" style="width: 100%; height: \$fcb_setting[changfcb_height];"></iframe>

</td>
</tr>
</table>

<script language="JavaScript" type="text/javascript">
var textstyle = document.getElementById('hmess');

if (fcb_getCookie('fcb_b_userid{\$mybb->user['uid']}').length > 0)
{
	document.fcb_form.hbold.value = fcb_getCookie('fcb_b_userid{\$mybb->user['uid']}');
}
if (fcb_getCookie('fcb_i_userid{\$mybb->user['uid']}').length > 0)
{
	document.fcb_form.hitalic.value = fcb_getCookie('fcb_i_userid{\$mybb->user['uid']}');
}
if (fcb_getCookie('fcb_u_userid{\$mybb->user['uid']}').length > 0)
{
	document.fcb_form.hunderline.value = fcb_getCookie('fcb_u_userid{\$mybb->user['uid']}');
}
if (fcb_getCookie('fcb_font_userid{\$mybb->user['uid']}').length > 0)
{
	document.fcb_form.hfont.value = fcb_getCookie('fcb_font_userid{\$mybb->user['uid']}');
}
if (fcb_getCookie('fcb_color_userid{\$mybb->user['uid']}').length > 0)
{
	document.fcb_form.hcolor.value = fcb_getCookie('fcb_color_userid{\$mybb->user['uid']}');
}
fcb_upstyle_cookie();

function fcb_upstyle_cookie()
{
		if (document.fcb_form.hbold.value == 'B*')
		{
			textstyle.style.fontWeight = 'bold';
		}
		else
		{
			textstyle.style.fontWeight = 'normal';
		}

		if (document.fcb_form.hitalic.value == 'I*')
		{
			textstyle.style.fontStyle = 'italic';
		}
		else
		{
			textstyle.style.fontStyle = 'normal';
		}
		

		if (document.fcb_form.hunderline.value == 'U*')
		{
			textstyle.style.textDecoration = 'underline';
		}
		else
		{
			textstyle.style.textDecoration = 'none';
		}
		
	textstyle.style.fontFamily = document.fcb_form.hfont.value;
	textstyle.style.color = document.fcb_form.hcolor.value;
}


function fcb_upstyle(element)
{
	if (element == 'b')
	{
		if (document.fcb_form.hbold.value == 'B')
		{
			document.fcb_form.hbold.value = 'B*';
			textstyle.style.fontWeight = 'bold';
		}
		else
		{
			document.fcb_form.hbold.value = 'B';
			textstyle.style.fontWeight = 'normal';
		}
		
	}
	else if (element == 'i')
	{
		if (document.fcb_form.hitalic.value == 'I')
		{
			document.fcb_form.hitalic.value = 'I*';
			textstyle.style.fontStyle = 'italic';
		}
		else
		{
			document.fcb_form.hitalic.value = 'I';
			textstyle.style.fontStyle = 'normal';
		}
		
	}
	else if (element == 'u')
	{
		if (document.fcb_form.hunderline.value == 'U')
		{
			document.fcb_form.hunderline.value = 'U*';
			textstyle.style.textDecoration = 'underline';
		}
		else
		{
			document.fcb_form.hunderline.value = 'U';
			textstyle.style.textDecoration = 'none';
		}
		
	}
	else if (element == 'font')
	{
			textstyle.style.fontFamily = document.fcb_form.hfont.value;
	}
	else if (element == 'color')
	{
			textstyle.style.color = document.fcb_form.hcolor.value;
	}
	
	fcb_setCookie('fcb_b_userid{\$mybb->user['uid']}', document.fcb_form.hbold.value);
	fcb_setCookie('fcb_i_userid{\$mybb->user['uid']}', document.fcb_form.hitalic.value);
	fcb_setCookie('fcb_u_userid{\$mybb->user['uid']}', document.fcb_form.hunderline.value);
	fcb_setCookie('fcb_font_userid{\$mybb->user['uid']}', document.fcb_form.hfont.value);
	fcb_setCookie('fcb_color_userid{\$mybb->user['uid']}', document.fcb_form.hcolor.value);
}

function fcb_setCookie(c_name,value)
{
var exdate=new Date();
exdate.setDate(exdate.getDate()+365);
document.cookie=c_name+ "=" +escape(value)+ ";expires="+exdate.toGMTString() + "path=\$mybb->settings[cookiepath]";
}
function fcb_getCookie(c_name)
{
if (document.cookie.length>0)
  {
  c_start=document.cookie.indexOf(c_name + "=");
  if (c_start!=-1)
    { 
    c_start=c_start + c_name.length+1; 
    c_end=document.cookie.indexOf(";",c_start);
    if (c_end==-1) c_end=document.cookie.length;
    return unescape(document.cookie.substring(c_start,c_end));
    }
else { return ""; }
  }

}

var chatboxkey = '\$fcb_chatboxkey';
var huid = '{\$mybb->user['uid']}';
var hgroupid = '{\$mybb->user['usergroup']}';
var huser = "\$fcb_musername";

function fcb_postshout()
{
		hmess = document.fcb_form.hmess.value;
		hcolor = document.fcb_form.hcolor.value;
		hfont = document.fcb_form.hfont.value;
		hbold = document.fcb_form.hbold.value;
		hitalic = document.fcb_form.hitalic.value;
		hunderline = document.fcb_form.hunderline.value;
		document.fcb_form.hmess.value = '';
		if (hmess == '')
		{
			alert('\$lang->fcb_typemess');
		}
		else
		{
			fcb_frame.location = '\$fcb_setting[changfcb_url]/index.php?do=postshout&key=' + chatboxkey + '&userid=' + huid + '&groupid=' + hgroupid + '&username=' + huser + '&message=' + encodeURIComponent(hmess) + '&color=' + hcolor + '&font=' + hfont + '&bold=' + hbold + '&italic=' + hitalic + '&underline=' + hunderline;
		}
}

function archivepage()
{
	window.open("\$fcb_setting[changfcb_url]/archive.php", "fcbarchive", "location=no,scrollbars=yes,width=640,height=480");
}
function addsmilie(code)
{
	document.fcb_form.hmess.value = document.fcb_form.hmess.value + code;
}
function smiliepopup()
{
	window.open("misc.php?do=fcb_allsmilies", "fcballsmilies", "location=no,scrollbars=yes,width=500,height=500");
}

function smshow(request)
{
  if (request.readyState == 4 && request.status == 200)
	{
		document.getElementById('fcb_smiliebox').innerHTML = request.responseText;
	}
}
function fcb_showsmilies()
{
	document.getElementById('fcb_smiliebox').innerHTML = '\$lang->fcb_wait';
	new Ajax.Request('misc.php?do=fcb_randomsmilies', {method: 'GET', postBody: null, onComplete: function(request) { smshow(request); }});
}
function fcb_showsmiliebox()
{
	if (document.getElementById('fcb_smilieboxmain').style.display == 'none')
	{
		document.getElementById('fcb_smilieboxmain').style.display = 'inline';
		fcb_showsmilies();
	}
	else
	{
		document.getElementById('fcb_smilieboxmain').style.display = 'none';
	}
}
function fcb_hideshowsmiliebox()
{
	document.getElementById('fcb_smilieboxmain').style.display = 'none';
}
function fcb_refresh()
{
	fcb_frame.location = '\$fcb_setting[changfcb_url]/index.php';
}
</script>
FCB;
	$template['changuondyu_chatbox_allsmilie'] = <<<FCB
<html>
<head>
\$headerinclude
<script language="javascript">
function addsmilie(code)
{
	opener.document.fcb_form.hmess.value = opener.document.fcb_form.hmess.value + code;
}
</script>

<title>\$lang->fcb_allsm</title>
</head>

<body>

<table width="100%" border="0" class="tborder" cellpadding="\$theme[tablespace]" cellspacing="\$theme[borderwidth]">
\$smilieicon
</table>

</body>
</html>
FCB;
	
	
	// INSERT TEMPLATE - NO NEED CHANGE
	foreach($template as $title => $tname)
	{
		$tp = array(
			'title'		=> $title,
			'template'	=> $db->escape_string($tname),
			'sid'		=> '-1',
			'version'	=> '1410',
			'dateline'	=> TIME_NOW
		);
		$db->insert_query("templates", $tp);
	}
}


function changfcb_uninstall()
{
	global $db;
	
	### Delete settings ###
	$query = $db->query("SELECT gid FROM ".TABLE_PREFIX."settinggroups WHERE name='".MOD_ID."' LIMIT 1");
	while ($sg = $db->fetch_array($query))
	{
		$gid = intval($sg['gid']);
	}
    if ($gid) $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE gid=$gid");
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='".MOD_ID."'");
	rebuild_settings();
	
	### Delete templates ###
	$deletetemplates = array('changuondyu_chatbox_main','changuondyu_chatbox_allsmilie');
	foreach($deletetemplates as $title)
	{
		$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='$title'");
	}
}

function changfcb_activate()
{
	global $db;
	$db->query("UPDATE ".TABLE_PREFIX."settings SET value=1 WHERE name='changfcb_turn'");
	rebuild_settings();
}

function changfcb_deactivate()
{
	global $db;
	$db->query("UPDATE ".TABLE_PREFIX."settings SET value=0 WHERE name='changfcb_turn'");
	rebuild_settings();
}


$plugins->add_hook("misc_start", "changfcb_misc");
function changfcb_misc()
{
	global $db,$mybb,$templates,$theme,$headerinclude,$lang;

	if($mybb->settings['changfcb_turn'])
	{
		$lang->load('changfcb');
		
		$smilieperrow = $mybb->settings['changfcb_smperrow'];
		$randomsmilie = $mybb->settings['changfcb_numberrandom'];

		if ($_REQUEST['do'] == 'fcb_allsmilies')
		{	
			$smfiletg = file($mybb->settings['changfcb_smfile']);
			$count = 0;
			$smilieicon = '<tr>';
			foreach ($smfiletg as $smbit)
			{
				$count++;
				$smbit = explode(" => ", $smbit);
				$smbit[0] = str_replace('"', '\"', $smbit[0]);
				$smbit[0] = str_replace('\\', '\\\\', $smbit[0]);
				$smilieicon .= "<td class='trow1' align='center'><a href='javascript:addsmilie(\"$smbit[0]\")'><img src='$smbit[1]' border='0'></a></td>";
				if ($count % $smilieperrow == 0) { $smilieicon .= '</tr><tr>'; }
			}
			$smilieicon .= '</tr>';
			eval("\$output = \"".$templates->get("changuondyu_chatbox_allsmilie")."\";");
			echo $output;
		}

		if ($_REQUEST['do'] == 'fcb_randomsmilies')
		{
			Header('Cache-Control: no-cache');
			Header('Pragma: no-cache');
			$smfiletg = file($mybb->settings['changfcb_smfile']);
			$sl = sizeof($smfiletg);
			$count = 0;
			$rand_smilies = array_rand($smfiletg, $randomsmilie);
			for ($i = 0; $i < $randomsmilie; $i++)
			{
				$count++;
				$smbit = $smfiletg[$rand_smilies[$i]];
				$smbit = explode(" => ", $smbit);
				$smbit[0] = str_replace('\\', '\\\\', $smbit[0]);
				$smbit[0] = str_replace('"', '\"', $smbit[0]);
				echo "<a href='javascript:addsmilie(\"$smbit[0]\")'><img src='$smbit[1]' border='0'></a> ";
			}
		}
	}
}
	

$plugins->add_hook("global_end", "changfcb_main");
function changfcb_main()
{
	global $mybb,$templates,$theme,$lang,$changfcb;
	$lang->load('changfcb');
	$fcb_setting = $mybb->settings;
	if($mybb->settings['changfcb_turn'])
	{
		$groupnoshout = explode(",", $mybb->settings['changfcb_noshout']);
		if(in_array($mybb->user['usergroup'], $groupnoshout))
		{
				$fcb_disable = "disabled='disabled'";
		}
		$groupnoview = explode(",", $mybb->settings['changfcb_noview']);
		if(!in_array($mybb->user['usergroup'], $groupnoview))
		{
			$colorlist = preg_replace("#(\r\n|\r|\n)#s","#",$mybb->settings['changfcb_colorlist']);
			$colorlist = explode('#',$colorlist);
			foreach ($colorlist AS $ccolor)
			{
				$fcb_colorlist .= "<option style='background: $ccolor;' value='$ccolor'>$ccolor</option>";
			}

			$fontlist = preg_replace("#(\r\n|\r|\n)#s","#",$mybb->settings['changfcb_fontlist']);
			$fontlist = explode('#',$fontlist);
			foreach ($fontlist AS $cfont)
			{
				$fcb_fontlist .= "<option style='font: $cfont;' value='$cfont'>$cfont</option>";
			}

			$fcb_musername = format_name($mybb->user['username'], $mybb->user['usergroup'], $mybb->user['displaygroup']);
			$fcb_chatboxkey = md5($mybb->user['uid'].$mybb->settings['changfcb_key'].$mybb->user['usergroup'].md5($fcb_musername.$mybb->settings['changfcb_key']));
			$fcb_musername = rawurlencode($fcb_musername);
			eval("\$changfcb .= \"".$templates->get("changuondyu_chatbox_main")."\";");
		}
	}
}
?>
