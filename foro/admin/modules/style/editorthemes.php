<?php
/**
 * MyBB WYSIWYG Editor 1.0
 * Copyright 2010 FrinkLabs, All Rights Reserved
 *
 * Website: http://frinklabs.xe.cx/
 * License: http://frinklabs.xe.cx/wysiwyg/license
 */

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

function add_slashes($var)
{
	$var = str_replace('"', '\"', $var);
				
	return $var;
}

require_once MYBB_ADMIN_DIR."inc/functions_themes.php";

$lang->load("style_themes");
$lang->load('style_editorthemes');

$page->extra_header .= "
<script type=\"text/javascript\">
//<![CDATA[
var save_changes_lang_string = '{$lang->save_changes_js}';
var delete_lang_string = '{$lang->delete}';
var file_lang_string = '{$lang->file}';
var globally_lang_string = '{$lang->globally}';
var specific_actions_lang_string = '{$lang->specific_actions}';
var specific_actions_desc_lang_string = '{$lang->specific_actions_desc}';
var delete_confirm_lang_string = '{$lang->delete_confirm_js}';
//]]>
</script>";

if($mybb->input['action'] == "xmlhttp_stylesheet" && $mybb->request_method == "post")
{	
	// Does the theme not exist?
	if(!file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/theme.php"))
	{
		flash_message($lang->error_invalid_theme, 'error');
		admin_redirect("index.php?module=style/editorthemes");
	}
	
	// Does the stylesheet not exist?
	if(!file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/".$mybb->input['file'].""))
	{
		flash_message($lang->error_invalid_stylesheet, 'error');
		admin_redirect("index.php?module=style-editorthemes&amp;action=edit&amp;theme=".$mybb->input['theme']);
	}
	
	include MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/theme.php";
	
	ob_start();

       include MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/".$mybb->input['file'];
       $stylesheet = ob_get_contents();
	   
    ob_end_clean(); 
	
	$css_array = css_to_array($stylesheet);
	$selector_list = get_selectors_as_options($css_array, $mybb->input['selector']);
	$editable_selector = $css_array[$mybb->input['selector']];
	$properties = parse_css_properties($editable_selector['values']);
	
	$form = new Form("index.php?module=style-editorthemes&amp;action=stylesheet_properties", "post", "selector_form", 0, "", true);
	echo $form->generate_hidden_field("tid", $mybb->input['tid'], array('id' => "tid"))."\n";
	echo $form->generate_hidden_field("file", htmlspecialchars_uni($mybb->input['file']), array('id' => "file"))."\n";
	echo $form->generate_hidden_field("selector", htmlspecialchars_uni($mybb->input['selector']), array('id' => 'hidden_selector'))."\n";
	
	$table = new Table;	
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[background]', $properties['background'], array('id' => 'css_bits[background]', 'style' => 'width: 260px;'))."</div><div><strong>Background</strong></div>", array('style' => 'width: 20%;'));
	$table->construct_cell("<strong>Extra CSS Attributes:</strong><br /><div style=\"align: center;\">".$form->generate_text_area('css_bits[extra]', $properties['extra'], array('id' => 'css_bits[extra]', 'style' => 'width: 98%;', 'rows' => '19'))."</div>", array('rowspan' => 8));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[color]', $properties['color'], array('id' => 'css_bits[color]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->color}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[width]', $properties['width'], array('id' => 'css_bits[width]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->width}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[font_family]', $properties['font-family'], array('id' => 'css_bits[font_family]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->font_family}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[font_size]', $properties['font-size'], array('id' => 'css_bits[font_size]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->font_size}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[font_style]', $properties['font-style'], array('id' => 'css_bits[font_style]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->font_style}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[font_weight]', $properties['font-weight'], array('id' => 'css_bits[font_weight]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->font_weight}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[text_decoration]', $properties['text-decoration'], array('id' => 'css_bits[text_decoration]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->text_decoration}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	
	$table->output(htmlspecialchars_uni($editable_selector['class_name'])."<span id=\"saved\" style=\"color: #FEE0C6;\"></span>");
	exit;
}

$page->add_breadcrumb_item("Editor Themes", "index.php?module=style-editorthemes");

if($mybb->input['action'] == "add" || $mybb->input['action'] == "import" || $mybb->input['action'] == "assign" || !$mybb->input['action'])
{
	$sub_tabs['themes'] = array(
		'title' => $lang->editorthemes,
		'link' => "index.php?module=style-editorthemes",
		'description' => $lang->editorthemes_desc
	);

	$sub_tabs['create_theme'] = array(
		'title' => $lang->create_new_editortheme,
		'link' => "index.php?module=style-editorthemes&amp;action=add",
		'description' => $lang->create_new_editortheme_desc
	);

	$sub_tabs['import_theme'] = array(
		'title' =>$lang->import_a_editortheme,
		'link' => "index.php?module=style-editorthemes&amp;action=import",
		'description' => $lang->import_a_editortheme_desc
	);
	
	$sub_tabs['assign_themes'] = array(
		'title' => $lang->assign_editorthemes,
		'link' => "index.php?module=style-editorthemes&amp;action=assign",
		'description' => $lang->assign_editorthemes_desc
	);
}

if($mybb->input['action'] == "edit" || $mybb->input['action'] == "export")
{
	$sub_tabs['edit_stylesheets'] = array(
		'title' => $lang->edit_stylesheets,
		'link' => "index.php?module=style-editorthemes&amp;action=edit&amp;theme={$mybb->input['theme']}",
		'description' => $lang->edit_stylesheets_desc
	);
	
	$sub_tabs['export_theme'] = array(
		'title' => $lang->export_theme,
		'link' => "index.php?module=style-editorthemes&amp;action=export&amp;theme={$mybb->input['theme']}",
		'description' => $lang->export_theme_desc2
	);
}

if($mybb->input['action'] == "edit_stylesheet")
{
    $sub_tabs['simple_mode'] = array(
		'title' => $lang->edit_stylesheet_simple_mode,
		'link' => "index.php?module=style-editorthemes&action=edit_stylesheet&file=".$mybb->input['file']."&theme=".$mybb->input['theme']."&mode=simple",
		'description' => $lang->edit_stylesheet_simple_mode_desc
	);
	
	$sub_tabs['advanced_mode'] = array(
		'title' => $lang->edit_stylesheet_advanced_mode,
		'link' => "index.php?module=style-editorthemes&action=edit_stylesheet&file=".$mybb->input['file']."&theme=".$mybb->input['theme']."&mode=advanced",
		'description' => $lang->edit_stylesheet_advanced_mode_desc
	);
}

if($mybb->input['action'] == "demo")
{
    $sub_tabs['theme_demonstration'] = array(
		'title' => $lang->theme_demo,
		'link' => "index.php?module=style-editorthemes",
		'description' => $lang->theme_demo_desc
	);
}

$plugins->run_hooks("admin_style_themes_begin");

if($mybb->input['action'] == "demo")
{
	$editor = '<link type="text/css" rel="stylesheet" href="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/ui.css" /><style>.hidden{display:none;}</style><div id="mce_editor_0_parent"><div class="tabMenu"><ul><li id="mce_editor_0_editor_tab" class="activeTabMenu"><a href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\', \'mceWysiwygEditor\', false);"><span>Editor</span></a></li><li id="mce_editor_0_code_tab" class=""><a href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\', \'mceCodeView\', false);"><span>Source</span></a></li></ul></div><div class="subTabMenu"><div class="containerHead"><div class="mceToolbar" id="mce_editor_0_toolBar"><ul><li id="mce_editor_0_fontNameSelect_li"><select onchange="tinyMCE.execInstanceCommand(\'mce_editor_0\',\'FontName\',false,this.options[this.selectedIndex].value);" onfocus="tinyMCE.addSelectAccessibility(event, this, window);" name="mce_editor_0_fontNameSelect" id="mce_editor_0_fontNameSelect" class="fontFormat"><option value="">Fontfamily</option><option value="Arial" style="font-family: Arial,Helvetica,sans-serif;">Arial</option><option value="Chicago" style="font-family: Chicago,Impact,Compacta,sans-serif;">Chicago</option><option value="Comic Sans MS" style="font-family: \'Comic Sans MS\',sans-serif;">Comic Sans MS</option><option value="Courier New" style="font-family: \'Courier New\',Courier,mono;">Courier New</option><option value="Geneva" style="font-family: Geneva,Arial,Helvetica,sans-serif;">Geneva</option><option value="Georgia" style="font-family: Georgia,\'Times New Roman\',Times,serif;">Georgia</option><option value="Helvetica" style="font-family: Helvetica,Verdana,sans-serif;">Helvetica</option><option value="Impact" style="font-family: Impact,Compacta,Chicago,sans-serif;">Impact</option><option value="Lucida Sans" style="font-family: \'Lucida Sans\',Monaco,Geneva,sans-serif;">Lucida Sans</option><option value="Tahoma" style="font-family: Tahoma,Arial,Helvetica,sans-serif;">Tahoma</option><option value="Times New Roman" style="font-family: \'Times New Roman\',Times,Georgia,serif;">Times New Roman</option><option value="Trebuchet MS" style="font-family: \'Trebuchet MS\',Arial,sans-serif;">Trebuchet MS</option><option value="Verdana" style="font-family: Verdana,Helvetica,sans-serif;">Verdana</option></select></li><li id="mce_editor_0_fontSizeSelect_li"><select onchange="tinyMCE.execInstanceCommand(\'mce_editor_0\',\'FontSize\',false,this.options[this.selectedIndex].value);" onfocus="tinyMCE.addSelectAccessibility(event, this, window);" name="mce_editor_0_fontSizeSelect" id="mce_editor_0_fontSizeSelect" class="fontFormat"><option value="0">Fontsize</option><option value="1" style="font-size: 8pt;">8 pt</option><option value="2" style="font-size: 10pt;">10 pt</option><option value="3" style="font-size: 12pt;">12 pt</option><option value="4" style="font-size: 14pt;">14 pt</option><option value="5" style="font-size: 18pt;">18 pt</option><option value="6" style="font-size: 24pt;">24 pt</option><option value="7" style="font-size: 36pt;">36 pt</option></select></li><li><img class="mceSeparator" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/separatorM.png"></li><li id="mce_editor_0_b_li" class=""><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'Bold\',false,\'false\');" id="mce_editor_0_b"><img title="Fett" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/fontStyleBoldM.png"></a></li><li id="mce_editor_0_i_li" class=""><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'Italic\',false,\'false\');" id="mce_editor_0_i"><img title="Kursiv" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/fontStyleItalicM.png"></a></li><li id="mce_editor_0_u_li" class=""><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'Underline\',false,\'false\');" id="mce_editor_0_u"><img title="Unterstreichen" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/fontStyleUnderlineM.png"></a></li><li id="mce_editor_0_s_li" class=""><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'Strikethrough\',false,\'false\');" id="mce_editor_0_s"><img title="Durchstreichen" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/fontStyleStriketroughM.png"></a></li><li><img class="mceSeparator" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/separatorM.png"></li><li id="mce_editor_0_justifyleft_li" class=""><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'JustifyLeft\',false,\'false\');" id="mce_editor_0_justifyleft"><img title="Linksbundig" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/textAlignLeftM.png"></a></li><li id="mce_editor_0_justifycenter_li" class=""><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'JustifyCenter\',false,\'false\');" id="mce_editor_0_justifycenter"><img title="Zentrieren" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/textAlignCenterM.png"></a></li><li id="mce_editor_0_justifyright_li" class=""><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'JustifyRight\',false,\'false\');" id="mce_editor_0_justifyright"><img title="Rechtsbundig" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/textAlignRightM.png"></a></li><li id="mce_editor_0_justifyfull_li"><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'JustifyFull\',false,\'false\');" id="mce_editor_0_justifyfull"><img title="Blocksatz" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/textJustifyM.png"></a></li><li><img class="mceSeparator" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/separatorM.png"></li><li id="mce_editor_0_color_li"><a target="_self" class="" onmousedown="return false;" href="javascript:void(0)" id="mce_editor_0_color"><img style="background-color: transparent;" title="Schriftfarbe" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/fontColorPickerEmptyM.png"></a><div id="mce_editor_0_colorMenu" class="hidden"><div class="mceColors"><ul><li><a title="#000000" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#000000\');" style="background-color: rgb(0, 0, 0); border-color: rgb(0, 0, 0);"></a></li><li><a title="#333333" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#333333\');" style="background-color: rgb(51, 51, 51); border-color: rgb(51, 51, 51);"></a></li><li><a title="#666666" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#666666\');" style="background-color: rgb(102, 102, 102); border-color: rgb(102, 102, 102);"></a></li><li><a title="#999999" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#999999\');" style="background-color: rgb(153, 153, 153); border-color: rgb(153, 153, 153);"></a></li><li><a title="#cccccc" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#cccccc\');" style="background-color: rgb(204, 204, 204); border-color: rgb(204, 204, 204);"></a></li><li><a title="#ffffff" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#ffffff\');" style="background-color: rgb(255, 255, 255); border-color: rgb(255, 255, 255);"></a></li><li><a title="transparent" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'transparent\');" style="background-image: url(&quot;'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/colorPickerEmptyS.png&quot;);"></a></li><li><a title="#000066" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#000066\');" style="background-color: rgb(0, 0, 102); border-color: rgb(0, 0, 102);"></a></li><li><a title="#006666" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#006666\');" style="background-color: rgb(0, 102, 102); border-color: rgb(0, 102, 102);"></a></li><li><a title="#006600" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#006600\');" style="background-color: rgb(0, 102, 0); border-color: rgb(0, 102, 0);"></a></li><li><a title="#666600" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#666600\');" style="background-color: rgb(102, 102, 0); border-color: rgb(102, 102, 0);"></a></li><li><a title="#663300" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#663300\');" style="background-color: rgb(102, 51, 0); border-color: rgb(102, 51, 0);"></a></li><li><a title="#660000" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#660000\');" style="background-color: rgb(102, 0, 0); border-color: rgb(102, 0, 0);"></a></li><li><a title="#660066" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#660066\');" style="background-color: rgb(102, 0, 102); border-color: rgb(102, 0, 102);"></a></li><li><a title="#000099" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#000099\');" style="background-color: rgb(0, 0, 153); border-color: rgb(0, 0, 153);"></a></li><li><a title="#009999" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#009999\');" style="background-color: rgb(0, 153, 153); border-color: rgb(0, 153, 153);"></a></li><li><a title="#009900" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#009900\');" style="background-color: rgb(0, 153, 0); border-color: rgb(0, 153, 0);"></a></li><li><a title="#999900" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#999900\');" style="background-color: rgb(153, 153, 0); border-color: rgb(153, 153, 0);"></a></li><li><a title="#993300" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#993300\');" style="background-color: rgb(153, 51, 0); border-color: rgb(153, 51, 0);"></a></li><li><a title="#990000" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#990000\');" style="background-color: rgb(153, 0, 0); border-color: rgb(153, 0, 0);"></a></li><li><a title="#990099" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#990099\');" style="background-color: rgb(153, 0, 153); border-color: rgb(153, 0, 153);"></a></li><li><a title="#0000ff" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#0000ff\');" style="background-color: rgb(0, 0, 255); border-color: rgb(0, 0, 255);"></a></li><li><a title="#00ffff" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#00ffff\');" style="background-color: rgb(0, 255, 255); border-color: rgb(0, 255, 255);"></a></li><li><a title="#00ff00" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#00ff00\');" style="background-color: rgb(0, 255, 0); border-color: rgb(0, 255, 0);"></a></li><li><a title="#ffff00" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#ffff00\');" style="background-color: rgb(255, 255, 0); border-color: rgb(255, 255, 0);"></a></li><li><a title="#ff6600" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#ff6600\');" style="background-color: rgb(255, 102, 0); border-color: rgb(255, 102, 0);"></a></li><li><a title="#ff0000" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#ff0000\');" style="background-color: rgb(255, 0, 0); border-color: rgb(255, 0, 0);"></a></li><li><a title="#ff00ff" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#ff00ff\');" style="background-color: rgb(255, 0, 255); border-color: rgb(255, 0, 255);"></a></li><li><a title="#9999ff" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#9999ff\');" style="background-color: rgb(153, 153, 255); border-color: rgb(153, 153, 255);"></a></li><li><a title="#99ffff" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#99ffff\');" style="background-color: rgb(153, 255, 255); border-color: rgb(153, 255, 255);"></a></li><li><a title="#99ff99" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#99ff99\');" style="background-color: rgb(153, 255, 153); border-color: rgb(153, 255, 153);"></a></li><li><a title="#ffff99" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#ffff99\');" style="background-color: rgb(255, 255, 153); border-color: rgb(255, 255, 153);"></a></li><li><a title="#ffcc99" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#ffcc99\');" style="background-color: rgb(255, 204, 153); border-color: rgb(255, 204, 153);"></a></li><li><a title="#ff9999" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#ff9999\');" style="background-color: rgb(255, 153, 153); border-color: rgb(255, 153, 153);"></a></li><li><a title="#ff99ff" href="javascript:tinyMCE.execCommand(\'forecolor\', false, \'#ff99ff\');" style="background-color: rgb(255, 153, 255); border-color: rgb(255, 153, 255);"></a></li></ul></div></div></li></ul><ul><li id="mce_editor_0_undo_li" class="mceButtonDisabled"><a target="_self" class="" onmousedown="return false;" href="javascript:void(0);" id="mce_editor_0_undo"><img title="Undo" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/undoM.png"></a></li><li id="mce_editor_0_redo_li" class="mceButtonDisabled"><a target="_self" class="" onmousedown="return false;" href="javascript:void(0);" id="mce_editor_0_redo"><img title="Redo" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/redoM.png"></a></li><li><img class="mceSeparator" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/separatorM.png"></li><li id="mce_editor_0_link_li" class="mceButtonDisabled"><a target="_self" class="" onmousedown="return false;" href="javascript:void(0);" id="mce_editor_0_link"><img title="Link einfugen" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/linkInsertM.png"></a></li><li id="mce_editor_0_unlink_li" class="mceButtonDisabled"><a target="_self" class="" onmousedown="return false;" href="javascript:void(0);" id="mce_editor_0_unlink"><img title="Link entfernen" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/linkRemoveM.png"></a></li><li><img class="mceSeparator" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/separatorM.png"></li><li id="mce_editor_0_img_li"><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'mceImage\',true,\'false\');" id="mce_editor_0_img"><img title="Bild einfugen" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/insertImageM.png"></a></li><li><img class="mceSeparator" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/separatorM.png"></li><li id="mce_editor_0_list_li"><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'InsertUnorderedList\',false,\'false\');" id="mce_editor_0_bullist"><img title="Liste" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/listStyleUnorderedM.png"></a></li><li id="mce_editor_0_numlist_li" class=""><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'InsertOrderedList\',false,\'false\');" id="mce_editor_0_numlist"><img title="Nummerierte Liste" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/listStyleOrderedM.png"></a></li><li><img class="mceSeparator" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/separatorM.png"></li><li id="mce_editor_0_quote_li" class=""><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'mceQuote\',false,\'false\');" id="mce_editor_0_quote"><img title="Zitat einfugen" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/quoteM.png"></a></li><li id="mce_editor_0_code_li"><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'mceCodeTag\',false,\'false\');" id="mce_editor_0_code"><img title="Code einfugen" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/insertCodeM.png"></a></li><li><img class="mceSeparator" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/separatorM.png"></li><li id="mce_editor_0_php_li" class=""><a target="_self" class="" onmousedown="return false;" href="javascript:tinyMCE.execInstanceCommand(\'mce_editor_0\',\'mce_php\',false);" id="mce_editor_0_php"><img title="PHP-Quelltext" src="'.$mybb->settings['bburl'].'/jscripts/wysiwyg_themes/'.$mybb->input['theme'].'/images/insertPhpM.png"></a></li></ul></div></div></div><div class="border" id="mce_editor_0_tabContent"><div class="tabMenuContent container-1"><iframe frameborder="0" id="mce_editor_0" name="mce_editor_0" class="mceEditorIframe" border="0" marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" allowtransparency="true" style="height: 200px;" tabindex="2"></iframe><textarea id="mce_editor_0_codeview" class="editorCodeView" style="height: 200px;"></textarea></div></div><div class="mceResizeBox" id="mce_editor_0_resize_box"></div><div class="border mceResizeIconRow"><div class="container-1 editorFooter"><div onmousedown="tinyMCE.setResizing(event,\'mce_editor_0\',true);" class="mceResizeIcon" id="mce_editor_0_resize"></div></div></div></div>';
   
    if($mybb->input['ajax'] != 1)
	{
        $page->add_breadcrumb_item($lang->theme_demo, "index.php?module=style-editorthemes&amp;action=demo&amp;theme=".$mybb->input['theme']);

	    $page->output_header($lang->theme_demo);
	
	    $page->output_nav_tabs($sub_tabs, 'theme_demonstration');	
	
	    $table = new Table;
		
		$table->construct_cell($editor);
		$table->construct_row();
	
	    $table->output($lang->theme_demo);
	    $page->output_footer();    	
	}
	else
	{
	    echo "<div id=\"ModalContentContainer\"><div class=\"ModalTitle\">{$lang->theme_demo}<a href=\"javascript:;\" id=\"modalClose\" class=\"float_right modalClose\">&nbsp;</a></div><div class=\"ModalContent\">{$editor}</div></div>";
	}
}

if($mybb->input['action'] == "add")
{
    require_once MYBB_ROOT."inc/functions_zip.php";

	if($mybb->request_method == "post")
	{
		if(!$mybb->input['name'])
		{
			$errors[] = $lang->error_missing_name;
		}
		
		if(!$mybb->input['tid_'])
		{
			$errors[] = $lang->error_missing_tid;
		}
		
		if(!ereg ("^[a-zA-Z1-9-_]*$",$mybb->input['tid_']))
		{
			$errors[] = $lang->error_invalid_tid;
		}
		
		if(!$mybb->input['description'])
		{
			$errors[] = $lang->error_missing_description;
		}
		
		if(!$mybb->input['author'])
		{
			$errors[] = $lang->error_missing_author;
		}
		
		if(!$mybb->input['website'])
		{
			$errors[] = $lang->error_missing_website;
		}
		
		if(!$mybb->input['version'])
		{
			$errors[] = $lang->error_missing_version;
		}
		
		if(file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['tid_']."/theme.php"))
		{
			$errors[] = $lang->error_theme_already_exists2;
		}
		
		if(!$errors)
		{	
            extract_zip(MYBB_ROOT."jscripts/wysiwyg_themes/default.zip", MYBB_ROOT."jscripts/wysiwyg_themes/");

			$theme_file = fopen(MYBB_ROOT."jscripts/wysiwyg_themes/theme_cache/theme.php", "r");
			
			$theme = '';
			while(!feof($theme_file))
            {
               $theme .= fgets($theme_file);
            } 
			
            fclose($theme_file);
			
			$varnames = array("THEMEFUNCTIONNAME", "THEMETITLE", "THEMEDESCRIPTION", "THEMEIMAGE", "THEMEVERSION", "THEMEAUTHOR", "THEMEWEBSITE");
            $varcontents   = array($mybb->input['tid_'], add_slashes($mybb->input['name']), add_slashes($mybb->input['description']), add_slashes($mybb->input['image']), add_slashes($mybb->input['version']), add_slashes($mybb->input['author']), add_slashes($mybb->input['website']));
			
            $theme = str_replace($varnames, $varcontents, $theme);

			$theme_file = fopen(MYBB_ROOT."jscripts/wysiwyg_themes/theme_cache/theme.php", "w");
			fwrite($theme_file, $theme);
            fclose($theme_file);
			
			rename(MYBB_ROOT."jscripts/wysiwyg_themes/theme_cache/", MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['tid_']."/");
		
			flash_message($lang->success_theme_created, 'success');
			admin_redirect("index.php?module=style-editorthemes&action=edit&theme=".$mybb->input['tid_']);
		}
	}
	
	$page->add_breadcrumb_item($lang->create_new_theme, "index.php?module=style-themes&amp;action=add");
	
	$page->output_header("{$lang->editorthemes} - {$lang->create_new_theme}");
	
	$page->output_nav_tabs($sub_tabs, 'create_theme');
	
	if($errors)
	{
		$page->output_inline_error($errors);
	}
	
	$form = new Form("index.php?module=style-editorthemes&amp;action=add", "post");
	
	$form_container = new FormContainer($lang->create_a_theme);
	$form_container->output_row($lang->field_name, $lang->field_name_desc, $form->generate_text_box('name', $mybb->input['name'], array('id' => 'name')), 'name');
	$form_container->output_row($lang->field_tid, $lang->field_tid_desc, $form->generate_text_box('tid_', $mybb->input['tid_'], array('id' => 'tid')), 'tid_');
	$form_container->output_row($lang->field_description, $lang->field_description_desc, $form->generate_text_box('description', $mybb->input['description'], array('id' => 'description')), 'description');
	$form_container->output_row($lang->field_author, $lang->field_author_desc, $form->generate_text_box('author', $mybb->input['author'], array('id' => 'author')), 'author');
	$form_container->output_row($lang->field_website, $lang->field_website_desc, $form->generate_text_box('website', $mybb->input['website'], array('id' => 'website')), 'website');
	$form_container->output_row($lang->field_version, $lang->field_version_desc, $form->generate_text_box('version', $mybb->input['version'], array('id' => 'version')), 'version');
	$form_container->output_row($lang->field_image,$lang->field_image_desc, $form->generate_text_box('image', $mybb->input['image'], array('id' => 'image')), 'image');
	
	$form_container->end();
	
	$buttons[] = $form->generate_submit_button($lang->create_new_theme);

	$form->output_submit_wrapper($buttons);
	
	$form->end();
	
	$page->output_footer();
}

if($mybb->input['action'] == "import")
{
    require_once MYBB_ROOT."inc/functions_zip.php";

	if($mybb->request_method == "post")
	{
		if(!$_FILES['local_file'])
		{
			$errors[] = $lang->error_missing_url;
		}
		
		$file_name = str_replace(".zip", "", $_FILES['local_file']['name']);
		
		if(file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$file_name."/theme.php"))
		{
			$errors[] = $lang->error_theme_already_exists3;
		}
		
		if(!$errors)
		{	
	        // Find out if there was an uploaded file
			if($_FILES['local_file']['error'] != 4)
			{
				// Find out if there was an error with the uploaded file
				if($_FILES['local_file']['error'] != 0)
				{
					$errors[] = $lang->error_uploadfailed.$lang->error_uploadfailed_detail;
					switch($_FILES['local_file']['error'])
					{
						case 1: // UPLOAD_ERR_INI_SIZE
							$errors[] = $lang->error_uploadfailed_php1;
							break;
						case 2: // UPLOAD_ERR_FORM_SIZE
							$errors[] = $lang->error_uploadfailed_php2;
							break;
						case 3: // UPLOAD_ERR_PARTIAL
							$errors[] = $lang->error_uploadfailed_php3;
							break;
						case 6: // UPLOAD_ERR_NO_TMP_DIR
							$errors[] = $lang->error_uploadfailed_php6;
							break;
						case 7: // UPLOAD_ERR_CANT_WRITE
							$errors[] = $lang->error_uploadfailed_php7;
							break;
						default:
							$errors[] = $lang->sprintf($lang->error_uploadfailed_phpx, $_FILES['local_file']['error']);
							break;
					}
				}
				
				if(!$errors)
				{
					// Was the temporary file found?
					if(!is_uploaded_file($_FILES['local_file']['tmp_name']))
					{
						$errors[] = $lang->error_uploadfailed_lost;
					}
					
					if(!move_uploaded_file($_FILES['local_file']['tmp_name'], MYBB_ROOT."jscripts/wysiwyg_themes/theme_cache.zip"))
                    {
                        $errors[] = $lang->error_uploadfailed_lost;
                    }					
				}
			}
			else
			{
				// UPLOAD_ERR_NO_FILE
				$errors[] = $lang->error_uploadfailed_php4;
			}
			
			if(!$errors)
			{			
                extract_zip(MYBB_ROOT."jscripts/wysiwyg_themes/theme_cache.zip", MYBB_ROOT."jscripts/wysiwyg_themes/");
			
				@unlink($_FILES['local_file']['tmp_name']);
				@unlink(MYBB_ROOT."jscripts/wysiwyg_themes/theme_cache.zip");
				
			    flash_message($lang->success_imported_theme, 'success');
			    admin_redirect("index.php?module=style-editorthemes");
		    }
		}
	}
	
	$page->add_breadcrumb_item($lang->import_a_editortheme, "index.php?module=style-themes&amp;action=import");
	
	$page->output_header("{$lang->editorthemes} - {$lang->import_a_editortheme}");
	
	$page->output_nav_tabs($sub_tabs, 'import_theme');
	
	if($errors)
	{
		$page->output_inline_error($errors);
	}
	
	$form = new Form("index.php?module=style-editorthemes&amp;action=import", "post", "", 1);
	
	$form_container = new FormContainer($lang->import_a_editortheme);
	$form_container->output_row($lang->field_file, $lang->field_file_desc, $form->generate_file_upload_box("local_file", array('style' => 'width: 230px;')), 'local_file');
	
	$form_container->end();
	
	$buttons[] = $form->generate_submit_button($lang->import_theme);

	$form->output_submit_wrapper($buttons);
	
	$form->end();
	
	$page->output_footer();
}

if($mybb->input['action'] == "edit")
{	
	// Does the theme not exist?
	if(!file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/theme.php"))
	{
		flash_message($lang->error_invalid_theme, 'error');
		admin_redirect("index.php?module=style/editorthemes");
	}
	
	include MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/theme.php";
	
	$editortheme = call_user_func("wysiwygtheme_".$mybb->input['theme']);
	
	if($mybb->request_method == "post")
	{
		if(!$mybb->input['name'])
		{
			$errors[] = $lang->error_missing_name;
		}
		
		if(!ereg ("^[a-zA-Z1-9-_]*$",$mybb->input['tid_']))
		{
			$errors[] = $lang->error_invalid_tid;
		}
		
		if(!$mybb->input['description'])
		{
			$errors[] = $lang->error_missing_description;
		}
		
		if(!$mybb->input['author'])
		{
			$errors[] = $lang->error_missing_author;
		}
		
		if(!$mybb->input['website'])
		{
			$errors[] = $lang->error_missing_website;
		}
		
		if(!$mybb->input['version'])
		{
			$errors[] = $lang->error_missing_version;
		}
		
		if(!$errors)
		{	
			$theme_file = fopen(MYBB_ROOT."jscripts/wysiwyg_themes/default_theme.php", "r");
			
			$theme = '';
			while(!feof($theme_file))
            {
               $theme .= fgets($theme_file);
            } 
			
            fclose($theme_file);
			
			$varnames = array("THEMEFUNCTIONNAME", "THEMETITLE", "THEMEDESCRIPTION", "THEMEIMAGE", "THEMEVERSION", "THEMEAUTHOR", "THEMEWEBSITE");
			$varcontents = array($mybb->input['theme'], add_slashes($mybb->input['name']), add_slashes($mybb->input['description']), add_slashes($mybb->input['image']), add_slashes($mybb->input['version']), add_slashes($mybb->input['author']), add_slashes($mybb->input['website']));
            
			$theme = str_replace($varnames, $varcontents, $theme);

			$theme_file = fopen(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/theme.php", "w");
			fwrite($theme_file, $theme);
            fclose($theme_file);
		
			flash_message($lang->success_theme_properties_updated, 'success');
			admin_redirect("index.php?module=style-editorthemes&action=edit&theme=".$mybb->input['theme']);
		}
	} 
    else 
    {
		$mybb->input['tid_'] = $mybb->input['theme'];
	    $mybb->input = array_merge($mybb->input, $editortheme);	
    }	
	
	$page->add_breadcrumb_item($editortheme['name'], "index.php?module=style-editorthemes&amp;action=edit&amp;theme=".$mybb->input['theme']);

	$page->output_header("{$lang->editorthemes} - {$editortheme['name']}");
	
	$page->output_nav_tabs($sub_tabs, 'edit_stylesheets');	
	
	if($errors)
	{
		$page->output_inline_error($errors);
	}
	
	$table = new Table;
	$table->construct_header($lang->stylesheets);
	$table->construct_header($lang->controls, array("class" => "align_center", "width" => 150));
		
		$table->construct_cell("<strong><a href=\"index.php?module=style-editorthemes&amp;action=edit_stylesheet&amp;file=ui.css&amp;theme={$mybb->input['theme']}\">{$lang->user_interface}</a></strong> (ui.css)");
		$table->construct_cell("<a href=\"index.php?module=style-editorthemes&amp;action=edit_stylesheet&amp;file=ui.css&amp;theme={$mybb->input['theme']}\">{$lang->edit_stylesheet}</a>", array("class" => "align_center", "width" => "25%"));
		$table->construct_row();

		$table->construct_cell("<strong><a href=\"index.php?module=style-editorthemes&amp;action=edit_stylesheet&amp;file=content.css&amp;theme={$mybb->input['theme']}\">{$lang->editor_content}</a></strong> (content.css)");
		$table->construct_cell("<a href=\"index.php?module=style-editorthemes&amp;action=edit_stylesheet&amp;file=content.css&amp;theme={$mybb->input['theme']}\">{$lang->edit_stylesheet}</a>", array("class" => "align_center", "width" => "25%"));
		$table->construct_row();
	
	$table->output("Stylesheets in {$editortheme['name']}");
	
	$form = new Form("index.php?module=style-editorthemes&amp;action=edit&amp;theme={$mybb->input['theme']}", "post");
	
	$form_container = new FormContainer($lang->edit_theme_properties);
	$form_container->output_row($lang->field_name, $lang->field_name_desc, $form->generate_text_box('name', $mybb->input['name'], array('id' => 'name')), 'name');
	$form_container->output_row($lang->field_description, $lang->field_description_desc, $form->generate_text_box('description', $mybb->input['description'], array('id' => 'description')), 'description');
	$form_container->output_row($lang->field_author, $lang->field_author_desc, $form->generate_text_box('author', $mybb->input['author'], array('id' => 'author')), 'author');
	$form_container->output_row($lang->field_website, $lang->field_website_desc, $form->generate_text_box('website', $mybb->input['website'], array('id' => 'website')), 'website');
	$form_container->output_row($lang->field_version, $lang->field_version_desc, $form->generate_text_box('version', $mybb->input['version'], array('id' => 'version')), 'version');
	$form_container->output_row($lang->field_image, $lang->field_image_desc, $form->generate_text_box('image', $mybb->input['image'], array('id' => 'image')), 'image');
	
	$form_container->end();
	
	$buttons[] = $form->generate_submit_button($lang->save_theme_properties);

	$form->output_submit_wrapper($buttons);
	
	$form->end();
	
	$page->output_footer();    
}

if($mybb->input['action'] == "edit_stylesheet" && (!$mybb->input['mode'] || $mybb->input['mode'] == "simple"))
{	
	// Does the theme not exist?
	if(!file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/theme.php"))
	{
		flash_message($lang->error_invalid_theme, 'error');
		admin_redirect("index.php?module=style/editorthemes");
	}
	
	// Does the stylesheet not exist?
	if(!file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/".$mybb->input['file'].""))
	{
		flash_message($lang->error_invalid_stylesheet, 'error');
		admin_redirect("index.php?module=style-editorthemes&amp;action=edit&amp;theme=".$mybb->input['theme']);
	}
	
	include MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/theme.php";
	
	ob_start();

       include MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/".$mybb->input['file'];
       $stylesheet = ob_get_contents();
	   
    ob_end_clean(); 
		
	if($mybb->request_method == "post")
	{

		// Insert the modified CSS
		$new_stylesheet = $stylesheet;
		
		if($mybb->input['serialized'] == 1)
		{
			$mybb->input['css_bits'] = unserialize($mybb->input['css_bits']);
		}

		$css_to_insert = '';
		foreach($mybb->input['css_bits'] as $field => $value)
		{
			if(!trim($value) || !trim($field))
			{
				continue;
			}
			
			if($field == "extra")
			{
				$css_to_insert .= $value."\n";
			}
			else
			{
				$field = str_replace("_", "-", $field);
				$css_to_insert .= "{$field}: {$value};\n";
			}
		}
		
		$new_stylesheet = insert_into_css($css_to_insert, $mybb->input['selector'], $new_stylesheet);

        $stylesheet_file = fopen(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/".$mybb->input['file'], "w");
        fwrite($stylesheet_file, $new_stylesheet);
        fclose($stylesheet_file);
		
		// Update the CSS file list for this theme
		update_theme_stylesheet_list($file);

		if(!$mybb->input['ajax'])
		{			
			flash_message($lang->success_stylesheet_updated, 'success');
				
			if($mybb->input['save_close'])
			{
				admin_redirect("index.php?module=style-editorthemes&action=edit&theme={$mybb->input['theme']}");
			}
			else
			{
				admin_redirect("index.php?module=style-editorthemes&action=edit_stylesheet&theme={$mybb->input['theme']}&file={$mybb->input['file']}");
			}
		}
		else
		{
			echo "1";
			exit;
		}
	}
	
	// Has the file on the file system been modified?
	if(resync_stylesheet($stylesheet))
	{
	    ob_start();

           include MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/".$mybb->input['file'];
           $stylesheet = ob_get_contents();
	   
        ob_end_clean();
	}
	
	$css_array = css_to_array($stylesheet);
	$selector_list = get_selectors_as_options($css_array, $mybb->input['selector']);
	
	// Do we not have any selectors? Send em to the full edit page
	if(!$selector_list)
	{
		flash_message($lang->error_cannot_parse, 'error');
		admin_redirect("index.php?module=style-editorthemes&action=edit_stylesheet&file=".$mybb->input['file']."&theme=".$mybb->input['theme']."&mode=advanced");
		exit;
	}
	
	$editortheme = call_user_func("wysiwygtheme_".$mybb->input['theme']);

	$page->extra_header .= "
	<script type=\"text/javascript\">
	var my_post_key = '".$mybb->post_code."';
	</script>";	
	
	$page->add_breadcrumb_item($editortheme['name'], "index.php?module=style-editorthemes&amp;action=edit&amp;theme=".$mybb->input['theme']);
	$page->add_breadcrumb_item("{$lang->editing} ".$mybb->input['file'], "index.php?module=style-editorthemes&amp;action=edit&amp;theme=".$mybb->input['theme']);

	$page->output_header("{$lang->editorthemes} - {$editortheme['name']}");
	
	$page->output_nav_tabs($sub_tabs, 'simple_mode');	
	
	// Output the selection box
	$form = new Form("index.php", "get", "selector_form");
	echo $form->generate_hidden_field("module", "style/editorthemes")."\n";
	echo $form->generate_hidden_field("action", "edit_stylesheet")."\n";
	echo $form->generate_hidden_field("theme", $mybb->input['theme'])."\n";
	echo $form->generate_hidden_field("file", htmlspecialchars_uni($mybb->input['file']))."\n";	
	
	echo "{$lang->selector}: <select id=\"selector\" name=\"selector\">\n{$selector_list}</select> <span id=\"mini_spinner\">".$form->generate_submit_button($lang->go)."</span><br /><br />\n";

	$form->end();
	
	// Haven't chosen a selector to edit, show the first one from the stylesheet
	if(!$mybb->input['selector'])
	{
		reset($css_array);
		uasort($css_array, "css_selectors_sort_cmp");
		$selector = key($css_array);
		$editable_selector = $css_array[$selector];
	}
	// Show a specific selector
	else
	{
		$editable_selector = $css_array[$mybb->input['selector']];
		$selector = $mybb->input['selector'];
	}
	
	// Get the properties from this item
	$properties = parse_css_properties($editable_selector['values']);
	
	$form = new Form("index.php?module=style-themes&amp;action=edit_stylesheet", "post");
	echo $form->generate_hidden_field("tid", $mybb->input['theme'], array('id' => "tid"))."\n";
	echo $form->generate_hidden_field("file", htmlspecialchars_uni($mybb->input['file']), array('id' => "file"))."\n";
	echo $form->generate_hidden_field("selector", htmlspecialchars_uni($selector), array('id' => 'hidden_selector'))."\n";
	
	echo "<div id=\"stylesheet\">";
	$table = new Table;	
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[background]', $properties['background'], array('id' => 'css_bits[background]', 'style' => 'width: 260px;'))."</div><div><strong>Background</strong></div>", array('style' => 'width: 20%;'));
	$table->construct_cell("<strong>Extra CSS Attributes:</strong><br /><div style=\"align: center;\">".$form->generate_text_area('css_bits[extra]', $properties['extra'], array('id' => 'css_bits[extra]', 'style' => 'width: 98%;', 'rows' => '19'))."</div>", array('rowspan' => 8));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[color]', $properties['color'], array('id' => 'css_bits[color]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->color}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[width]', $properties['width'], array('id' => 'css_bits[width]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->width}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[font_family]', $properties['font-family'], array('id' => 'css_bits[font_family]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->font_family}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[font_size]', $properties['font-size'], array('id' => 'css_bits[font_size]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->font_size}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[font_style]', $properties['font-style'], array('id' => 'css_bits[font_style]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->font_style}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[font_weight]', $properties['font-weight'], array('id' => 'css_bits[font_weight]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->font_weight}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	$table->construct_cell("<div style=\"float: right;\">".$form->generate_text_box('css_bits[text_decoration]', $properties['text-decoration'], array('id' => 'css_bits[text_decoration]', 'style' => 'width: 260px;'))."</div><div><strong>{$lang->text_decoration}</strong></div>", array('style' => 'width: 40%;'));
	$table->construct_row();
	
	$table->output(htmlspecialchars_uni($editable_selector['class_name'])."<span id=\"saved\" style=\"color: #FEE0C6;\"></span>");
	
	echo "</div>";
	
	$buttons[] = $form->generate_reset_button($lang->reset);
	$buttons[] = $form->generate_submit_button($lang->save_changes, array('id' => 'save', 'name' => 'save'));
	$buttons[] = $form->generate_submit_button($lang->save_changes_and_close, array('id' => 'save_close', 'name' => 'save_close'));

	$form->output_submit_wrapper($buttons);
	
	echo '<script type="text/javascript" src="./jscripts/editorthemes.js"></script>';
	echo '<script type="text/javascript">

Event.observe(window, "load", function() {
//<![CDATA[
    new ThemeSelector("./index.php?module=style-editorthemes&action=xmlhttp_stylesheet", "./index.php?module=style-editorthemes&action=edit_stylesheet", $("selector"), $("stylesheet"), "'.htmlspecialchars_uni($mybb->input['file']).'", $("selector_form"), "'.htmlspecialchars_uni($mybb->input['theme']).'");
});
//]]>
</script>';

	$form->end();
	
	$page->output_footer();    
}

if($mybb->input['action'] == "edit_stylesheet" && $mybb->input['mode'] == "advanced")
{
    // Does the theme not exist?
	if(!file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/theme.php"))
	{
		flash_message($lang->error_invalid_theme, 'error');
		admin_redirect("index.php?module=style/editorthemes");
	}
	
	// Does the stylesheet not exist?
	if(!file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/".$mybb->input['file'].""))
	{
		flash_message($lang->error_invalid_stylesheet, 'error');
		admin_redirect("index.php?module=style-editorthemes&amp;action=edit&amp;theme=".$mybb->input['theme']);
	}
	
	include MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/theme.php";
	
	$editortheme = call_user_func("wysiwygtheme_".$mybb->input['theme']);
	
	ob_start();

       include MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/".$mybb->input['file'];
       $stylesheet = ob_get_contents();
	   
    ob_end_clean();

	if($mybb->request_method == "post")
	{
        $stylesheet_file = fopen(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/".$mybb->input['file'], "w");
        fwrite($stylesheet_file, $mybb->input['stylesheet']);
        fclose($stylesheet_file);

		flash_message($lang->success_stylesheet_updated, 'success');
		
		if(!$mybb->input['save_close'])
		{
			admin_redirect("index.php?module=style-editorthemes&action=edit_stylesheet&file={$mybb->input['file']}&theme={$mybb->input['theme']}&mode=advanced");
		}
		else
		{
			admin_redirect("index.php?module=style-editorthemes&action=edit&theme={$mybb->input['theme']}");
		}
	}	
	
	if($admin_options['codepress'] != 0)
	{
		$page->extra_header .= '
	<link type="text/css" href="./jscripts/codepress/languages/codepress-css.css" rel="stylesheet" id="cp-lang-style" />
	<script type="text/javascript" src="./jscripts/codepress/codepress.js"></script>
	<script type="text/javascript">
		CodePress.language = \'css\';
	</script>';
	}
	
	$page->add_breadcrumb_item($editortheme['name'], "index.php?module=style-editorthemes&amp;action=edit&amp;theme=".$mybb->input['theme']);
	$page->add_breadcrumb_item("{$lang->editing} ".htmlspecialchars_uni($mybb->input['file']), "index.php?module=style-editorthemes&amp;action=edit_stylesheet&amp;theme={$mybb->input['theme']}&amp;file=".htmlspecialchars_uni($mybb->input['file'])."&amp;mode=advanced");
	
	$page->output_header("{$lang->editorthemes} - {$lang->edit_stylesheet_advanced_mode}");
	
	$page->output_nav_tabs($sub_tabs, 'advanced_mode');
	
	// Has the file on the file system been modified?
	if(resync_stylesheet($stylesheet))
	{
	    ob_start();

           include MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/".$mybb->input['file'];
           $stylesheet = ob_get_contents();
	   
        ob_end_clean();
	}
	
	$form = new Form("index.php?module=style-editorthemes&amp;action=edit_stylesheet&amp;mode=advanced", "post", "edit_stylesheet");
	echo $form->generate_hidden_field("theme", $mybb->input['theme'])."\n";
	echo $form->generate_hidden_field("file", htmlspecialchars_uni($mybb->input['file']))."\n";
	
	$table = new Table;	
	$table->construct_cell($form->generate_text_area('stylesheet', $stylesheet, array('id' => 'stylesheet', 'style' => 'width: 99%;', 'class' => 'codepress css', 'rows' => '30')));
	$table->construct_row();
	$table->output("{$lang->full_stylesheet_for} ".htmlspecialchars_uni($mybb->input['file']));
	
	$buttons[] = $form->generate_reset_button($lang->reset);
	$buttons[] = $form->generate_submit_button($lang->save_changes, array('id' => 'save', 'name' => 'save'));
	$buttons[] = $form->generate_submit_button($lang->save_changes_and_close, array('id' => 'save_close', 'name' => 'save_close'));

	$form->output_submit_wrapper($buttons);

	$form->end();
	
	if($admin_options['codepress'] != 0)
	{
		echo "<script type=\"text/javascript\">
	Event.observe('edit_stylesheet', 'submit', function()
	{
		if($('stylesheet_cp')) {
			var area = $('stylesheet_cp');
			area.id = 'stylesheet';
			area.value = stylesheet.getCode();
			area.disabled = false;
		}
	});
</script>";
	}
	
	$page->output_footer();
	
}

if($mybb->input['action'] == "delete")
{
    require_once MYBB_ROOT."inc/functions_deldir.php";

    // Does the theme not exist?
	if(!file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/theme.php") OR $mybb->input['theme'] == "default")
	{
		flash_message($lang->error_invalid_theme, 'error');
		admin_redirect("index.php?module=style/editorthemes");
	}
	
	// User clicked no
	if($mybb->input['no'])
	{
		admin_redirect("index.php?module=style-editorthemes");
	}
	
	if($mybb->request_method == "post")
	{
	    delete_dir(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/");
	
        flash_message($lang->success_theme_deleted, 'success');
		admin_redirect("index.php?module=style-editorthemes");
	}		
	else
	{		
		$page->output_confirm_action("index.php?module=style-editorthemes&amp;action=delete&amp;theme={$mybb->input['theme']}", $lang->confirm_theme_deletion);
	}
	
}

if($mybb->input['action'] == "export")
{
    // tutorial coming soon!
	admin_redirect("index.php?module=style/editorthemes");

    // Does the theme not exist?
	if(!file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/theme.php"))
	{
		flash_message($lang->error_invalid_theme, 'error');
		admin_redirect("index.php?module=style/editorthemes");
	}
	
	include(MYBB_ROOT."jscripts/wysiwyg_themes/".$mybb->input['theme']."/theme.php");
	   
	unset($editortheme);
	   
	$editortheme = call_user_func("wysiwygtheme_".$mybb->input['theme']);

	$page->add_breadcrumb_item($editortheme['name'], "index.php?module=style-editorthemes&amp;action=edit&amp;theme={$mybb->input['theme']}");	
	$page->add_breadcrumb_item($lang->export_theme, "index.php?module=style-editorthemes&amp;action=export&amp;theme={$mybb->input['theme']}");		
	
	$page->output_header($lang->export_theme);
	
	$page->output_nav_tabs($sub_tabs, 'export_theme');

	$table = new Table;
	$table->construct_header('First Step');
    
	$table->construct_cell("TUTORIAL COMING SOON", array('style'=>'text-align:center;'));
	$table->construct_row();

	$table->output($lang->how_to_export);
	
	$page->output_footer();	
}

if($mybb->input['action'] == "assign")
{	
	if(!empty($mybb->input['theme']) && is_array($mybb->input['theme']))
	{
		foreach($mybb->input['theme'] as $update_tid => $theme)
		{
			$db->update_query("themes", array('wysiwyg_theme' => $theme), "tid='".intval($update_tid)."'");
		}
			
		flash_message($lang->success_themes_assigned, 'success');
		admin_redirect("index.php?module=style-editorthemes&amp;action=assign");
	}

	$page->add_breadcrumb_item($lang->assign_editorthemes, "index.php?module=style-editorthemes&amp;action=assign_themes");
	
	$page->output_header($lang->assign_editorthemes);
	
	$page->output_nav_tabs($sub_tabs, 'assign_themes');
	
	function get_theme_select_menu($tid, $default)
	{
	    $select = "<select name=\"theme[".$tid."]\" size=\"1\">\n";
		
		$handle = opendir(MYBB_ROOT."jscripts/wysiwyg_themes/");
   
        while($file = readdir($handle))
        {
	        if(file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$file."/theme.php")) 
	        {
		        include_once(MYBB_ROOT."jscripts/wysiwyg_themes/".$file."/theme.php");
	   
	            unset($editortheme);
	   
	            $editortheme = call_user_func("wysiwygtheme_".$file);
			
			    if(empty($default) AND $file == "default")
				{
				   $selected = ' selected';				
				}
			    elseif($default == $file) 
				{
				   $selected = ' selected';
				}
				else
				{
				   $selected = '';
				}
			
		        $select .= "<option value=\"".$file."\"".$selected.">".$editortheme['name']."</option>";
			}
		}
		
		closedir($handle);	
		
	    $select .= "</select>\n";
		
		return $select;
	}


	$form = new Form("index.php?module=style-editorthemes&amp;action=assign", "post");
	
	$form_container = new FormContainer($lang->assign_editorthemes);
	
	$query = $db->simple_select("themes", "*", "tid!='1'");
	while($theme = $db->fetch_array($query))
	{
		$form_container->output_cell("<strong>{$theme['name']}</strong>", array("width" => "30%"));
	    $form_container->output_cell(get_theme_select_menu($theme['tid'], $theme['wysiwyg_theme']));	
	    $form_container->construct_row();	
	}

	$form_container->end();
	
	$buttons[] = $form->generate_submit_button($lang->save);

	$form->output_submit_wrapper($buttons);
	
	$form->end();	

	$page->output_footer();
}

if(!$mybb->input['action'])
{	
	$page->extra_header .= "<script src=\"../jscripts/scriptaculous.js?load=effects,dragdrop,controls\" type=\"text/javascript\"></script>\n";
	$page->extra_header .=  "<script src=\"jscripts/imodal.js\" type=\"text/javascript\"></script>\n";
	$page->extra_header .=  "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/default/imodal.css\" />\n";
	
	$page->output_header($lang->editorthemes);
	
	$page->output_nav_tabs($sub_tabs, 'themes');

	$table = new Table;
	$table->construct_header($lang->preview, array("width" => 100));
	$table->construct_header($lang->description);
	$table->construct_header($lang->controls, array("class" => "align_center", "width" => 150, "colspan"=>"2"));
    
	$handle = opendir(MYBB_ROOT."jscripts/wysiwyg_themes/");
   
    while($file = readdir($handle))
    {
	   if(file_exists(MYBB_ROOT."jscripts/wysiwyg_themes/".$file."/theme.php")) 
	   {
		   include(MYBB_ROOT."jscripts/wysiwyg_themes/".$file."/theme.php");
	   
	       unset($editortheme);
	   
	       $editortheme = call_user_func("wysiwygtheme_".$file);
		   
		   if(!empty($editortheme['image']))
		   {
	          $table->construct_cell("<img src=\"{$mybb->settings['bburl']}/jscripts/wysiwyg_themes/{$file}/{$editortheme['image']}\"/>");
	       }
		   else
		   {
		      $table->construct_cell("<img src=\"{$mybb->settings['bburl']}/images/nopreview.png\" style=\"opacity:0.7;\" />");
		   }
		   $table->construct_cell("<b>{$editortheme['name']}</b> - {$editortheme['description']}<br /><br />
		   <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"\">
		   <tr>
		   <td style=\"width:15%;text-align:right;\">{$lang->author}</td>
		   <td>{$editortheme['author']} - <a href=\"{$editortheme['website']}\" target=\"_blank\">{$lang->visit_website}</a></td>
		   </tr>
		   <tr>
		   <td style=\"width:15%;text-align:right;\">{$lang->version}</td>
		   <td>{$editortheme['version']}</td>
		   </tr>
		   <tr>
		   <td></td>
		   <td><a href=\"index.php?module=style-editorthemes&amp;action=demo&amp;theme={$file}\" onclick=\"modal = new MyModal({type: 'ajax', url: 'index.php?module=style-editorthemes&amp;action=demo&amp;theme={$file}&ajax=1'}); return false;\"><img src=\"".$mybb->settings['bburl']."/admin/styles/default/images/icons/make_default.gif\"> {$lang->try_demo}</a></td>
		   </tr>
		   </table>", array('style'=>'vertical-align: top;'));
	       
		   if($file !== "default")
		   {
		      $table->construct_cell("<a href=\"index.php?module=style-editorthemes&amp;action=delete&amp;theme={$file}&amp;my_post_key={$mybb->post_code}\" onclick=\"return AdminCP.deleteConfirmation(this, '{$lang->confirm_theme_deletion}')\">{$lang->delete}</a>", array('style'=>'width: 150px;text-align:center;'));
	       }
		   else 
		   {
		      $table->construct_cell("", array('style'=>'width: 150px;'));		   
		   }
	
	       $table->construct_cell("<a href=\"index.php?module=style-editorthemes&action=edit&theme={$file}\">{$lang->edit_theme}</a>", array('style'=>'width: 150px;text-align:center;'));
	       $table->construct_row();

	   }
    }
   
    closedir($handle);	

	$table->output($lang->themes);
	
	$page->output_footer();
}

?>
