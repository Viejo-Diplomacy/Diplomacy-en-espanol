<?php
/**
 * MyBB 1.6
 * Copyright 2012 MyBB-Es Team, All Rights Reserved
 *
 * Website: http://www.mybb-es.com.com
 *
 * $Id: class_ranks.php 2012-04-15 10:58Z EdsonOrdaz $
 */
 
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

class class_extension_ranks extends DataHandler 
{
	public $tabs;
	
	public function tabs($location='home', $options=array())
	{
		global $page, $lang;
		//die(var_dump($location))
		$tabs["home"] = array(
			'title' => $lang->ranks,
			'link' => "index.php?module=achivements-ranks",
			'description' => $lang->ranks_tab_des
		);
		$tabs["new"] = array(
			'title' => $lang->newrank,
			'link' => "index.php?module=achivements-ranks&action=new",
			'description' => $lang->newrank_tab_des
		);
		if($location == "edit")
		{
			$tabs["edit"] = array(
				'title' => $lang->editrank,
				'link' => "index.php?module=achivements-ranks&action=edit&rid=$options[rid]",
				'description' => $lang->editrank_tab_des
			);
		}
		$page->output_nav_tabs($tabs, $location);
	}

	public function validate_rank()
	{
		global $lang, $db;
		$rank = &$this->data;
		if(empty($rank['name']))
		{
			$this->set_error($lang->notname);
		}
		if(empty($rank['description']))
		{
			$this->set_error($lang->notdescription);
		}
		if(empty($rank['level']))
		{
			$this->set_error($lang->notlevel);
		}
		if(empty($rank['image']) && $rank['edit'] == false)
		{
			$this->set_error($lang->notimage);
		}
		$query = $db->simple_select("ranks", "*", "level='{$rank['level']}'");
		$level = $db->fetch_array($query);
		if($level['rid'] && $rank['edit'] == false)
		{
			$lang->repeatlevel = $lang->sprintf($lang->repeatlevel, $level['name'], $rank['level']);
			$this->set_error($lang->repeatlevel);
			return false;
		}
		if($rank['edit'] == true && $level['rid'] != $rank['rid'] && !is_null($level['rid']))
		{
			$lang->repeatlevel = $lang->sprintf($lang->repeatlevel, $level['name'], $rank['level']);
			$this->set_error($lang->repeatlevel);
			return false;
		}
		$db->free_result($query);
		if(count($this->get_errors()) < 1)
		{
			$this->validate_image();
		}
		$this->set_validated(true);
		if(count($this->get_errors()) > 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function validate_image()
	{
		global $lang;
		$rank = &$this->data;
		if(!$imagen['name'] || !$imagen['tmp_name'])
		{
			$imagen = $rank['image'];
		}
		if($rank['imageactual'] && $imagen['error'] > 0)
		{
			$rank['image'] = $rank['imageactual'];
			$this->set_data($rank);
			return false;
		}
		if(!is_uploaded_file($imagen['tmp_name']))
		{
			$this->set_error($lang->notcopyimage);
			return false;
		}
		$ext = get_extension(my_strtolower($imagen['name']));
		if(!preg_match("#^(gif|jpg|jpeg|jpe|bmp|png)$#i", $ext)) 
		{
			$this->set_error($lang->extnotvalidateimg);
			return false;
		}
		$path = MYBB_ROOT."inc/plugins/achivements/extensions/ranks/upload";
		$filename = "rank_".date('d_m_y_g_i_s').'.'.$ext; 
		$moved = @move_uploaded_file($imagen['tmp_name'], $path."/".$filename);
		if(!$moved)
		{
			$this->set_error($lang->notcopyimage);
			return false;
		}
		@my_chmod($path."/".$filename, '0644');
		if($imagen['error'])
		{
			@unlink($path."/".$filename);		
			$this->set_error($lang->notloadingimage);
			return false;
		}
		switch(my_strtolower($imagen['type']))
		{
			case "image/gif":
				$img_type =  1;
				break;
			case "image/jpeg":
			case "image/x-jpg":
			case "image/x-jpeg":
			case "image/pjpeg":
			case "image/jpg":
				$img_type = 2;
				break;
			case "image/png":
			case "image/x-png":
				$img_type = 3;
				break;
			default:
				$img_type = 0;
		}
		if($img_type == 0)
		{
			@unlink($path."/".$filename);
			$this->set_error($lang->extnotvalidateimg);
			return false;
		}
		@unlink(MYBB_ROOT.$rank['imageactual']);
		$rank['image'] = "inc/plugins/achivements/extensions/ranks/upload/".$filename;
		$this->set_data($rank);
		return true;
	}
	
	public function insert_rank()
	{
		global $lang, $db;
		if(!$this->get_validated())
		{
			die($lang->not_validate);
		}
		if(count($this->get_errors()) > 0)
		{
			die($lang->infonotvalid);
		}
		$rank = &$this->data;
		$insert = array(
			"name" => $rank['name'],
			"description" => $rank['description'],
			"apid" => $rank['apid'],
			"atid" => $rank['atid'],
			"arid" => $rank['arid'],
			"toid" => $rank['toid'],
			"rgid" => $rank['rgid'],
			"image" => $rank['image'],
			"level" => $rank['level']
		);
		if($rank['edit'] == true)
		{
			$db->update_query("ranks", $insert, "rid=".$rank['rid']);
		}
		else
		{
			$rid = $db->insert_id();
			$insert['rid'] = $rid;
			$db->insert_query("ranks", $insert);
		}
	}
	
	
}

?>