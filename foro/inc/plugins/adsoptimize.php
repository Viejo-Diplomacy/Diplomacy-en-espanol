<?php
/*
// In the name of Allah,the Gracious,the Mercifull
// Author: Nayar(njoolfo0@gmail.com)
// Plugin site: http://nayarweb.co.cc/forum/thread-62.html
// Please read the license before using this plugin.
// License: 
*/
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

function adsoptimize_info()
{
	return array(
		"name"			=> "Adsense Optimizer",
		"description"	=> "Optimize where to display ads",
		"website"		=> "http://nayarweb.co.cc/forum/thread-62.html",
		"author"		=> "Nayar",
		"authorsite"	=> "http://www.nayarweb.co.cc",
		"version"		=> "1.0.1",
		"compatibility" => "1*",
		"guid" 			=> "",
	);
}
function adsoptimize_activate()
{
	include MYBB_ROOT."/inc/adminfunctions_templates.php";
		find_replace_templatesets("index", "#".preg_quote('{$forums}')."#i", '<!-- google_ad_section_start -->{$forums}<!-- google_ad_section_end -->');
		find_replace_templatesets("forumdisplay", "#".preg_quote('{$threadslist}')."#i", '<!-- google_ad_section_start -->{$threadslist}<!-- google_ad_section_end -->');
		//find_replace_templatesets("showthread", "#".preg_quote('{$posts}')."#i", '<!-- google_ad_section_start -->{$posts}<!-- google_ad_section_end -->');
		find_replace_templatesets("postbit", "#".preg_quote('{$post[\'message\']}')."#i", '<!-- google_ad_section_start -->{$post[\'message\']}<!-- google_ad_section_end -->');
		find_replace_templatesets("portal", "#".preg_quote('{$announcements}')."#i", '<!-- google_ad_section_start -->{$announcements}<!-- google_ad_section_end -->');
		
			
}
function adsoptimize_deactivate()
{
	include MYBB_ROOT."/inc/adminfunctions_templates.php";
		find_replace_templatesets("index", "#".preg_quote('<!-- google_ad_section_start -->{$forums}<!-- google_ad_section_end -->')."#i", '{$forums}',0);
		//find_replace_templatesets("showthread", "#".preg_quote('<!-- google_ad_section_start -->{$posts}<!-- google_ad_section_end -->')."#i", '{$posts}',0);
		find_replace_templatesets("forumdisplay", "#".preg_quote('<!-- google_ad_section_start -->{$threadslist}<!-- google_ad_section_end -->')."#i", '{$threadslist}',0);
		find_replace_templatesets("postbit", "#".preg_quote('<!-- google_ad_section_start -->{$post[\'message\']}<!-- google_ad_section_end -->')."#i", '{$post[\'message\']}',0);
		find_replace_templatesets("portal", "#".preg_quote('<!-- google_ad_section_start -->{$announcements}<!-- google_ad_section_end -->')."#i", '{$announcements}',0);
}
?>
