<?php 
$url = 'http://www.webdiplo.com/foro/index.php?stats=xml';
$googleseo = 0;
$error['url_notaccess'] = '<pre>ERROR: Feed address is not accessible!</pre>';
$error['url_invalid'] = '<pre>An error occurred! This could be because of one of the following reasons:
- The target link is wrong
- The target Forum is closed
- ProStats plugin is inactive
- ProStats XML feed setting is off<pre>';
$parent_template = '<div>{$record->uname2}{$lasttopics}</div> ';
$lasttopics_template = '
		<link rel="stylesheet" href="css/global.css" type="text/css" />
		<link rel="stylesheet" href="css/gamepanel.css" type="text/css" />
		<link rel="stylesheet" href="css/home.css" type="text/css" />
		<link href="http://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet" type="text/css" />
<div class="gamePanelHome hometable homeMessages homeNotice" style="text-align:left; margin-bottom:4px; background:#fff">
	<div class="titleBar homeForumSubject homeForumTopBorder" style="font-style:normal; overflow:hidden; font-decotarion:none; color:#b07500; font-family: Kaushan Script, normal; font-size:12px"><a href="{$threadlink}" target="_blank" style="text-decotarion:none;color:#b07500;" >{$record->longsubject}</a></div>
	<div class="" style="font-color:#fff; margin-left:8px; font-family: Georgia, Arial, sans-serif; ">{$record->datetime}, por <em><strong>{$record->luname2}</strong</em></div><br />
</div>
';
$remote_xml = my_fetch_remote_file($url);
if(!$remote_xml){
	die($error['url_notaccess']);
}
@$xml = simplexml_load_string($remote_xml);
if(@!$xml->record)
{
	die($error['url_invalid']);
}
$output = '';
$i = 0;
foreach ($xml->record as $record)
{
	if (intval($googleseo) == 1)
	{
		$threadlink = $xml->bburl.'/Thread-'.space2dash($record->longsubject).'?action=lastpost';
		$forumlink = $xml->bburl.'/Forum-'.space2dash($record->ffullname);
		$firstposter_profilelink = $xml->bburl.'/User-'.$record->uname;
		$lastposter_profilelink = $xml->bburl.'/User-'.$record->luname;
	}
	else if (intval($xml->seo) == 1)
	{
		$threadlink = $xml->bburl.'/thread-'.$record->tid.'-lastpost.html';
		$forumlink = $xml->bburl.'/forum-'.$record->fid.'.html';
		$firstposter_profilelink = $xml->bburl.'/user-'.$record->fuid.'.html';
		$lastposter_profilelink = $xml->bburl.'/user-'.$record->luid.'.html';
	}
	else
	{
		$threadlink = $xml->bburl.'/showthread.php?tid='.$record->tid.'&action=lastpost';
		$forumlink = $xml->bburl.'/forumdisplay.php?fid='.$record->fid;
		$firstposter_profilelink = $xml->bburl.'/member.php?action=profile&uid='.$record->fuid;
		$lastposter_profilelink = $xml->bburl.'/member.php?action=profile&uid='.$record->luid;
	}	
	eval("\$lasttopics .= \"".addslashes($lasttopics_template)."\";");	
	++$i;
}
$record = '';
eval("\$output .= \"".addslashes($parent_template)."\";");
echo $output;
function my_fetch_remote_file($url)
{
	if(function_exists("curl_init"))
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
 	else if(function_exists("fsockopen"))
	{
		$url = @parse_url($url);
		if(!$url['host'])
		{
			return false;
		}
		if(!$url['port'])
		{
			$url['port'] = 80;
		}
		if(!$url['path'])
		{
			$url['path'] = "/";
		}
		if($url['query'])
		{
			$url['path'] .= "?{$url['query']}";
		}
		$fp = @fsockopen($url['host'], $url['port'], $error_no, $error, 10);
		@stream_set_timeout($fp, 10);
		if(!$fp)
		{
			return false;
		}
		$headers = array();
		$headers[] = "GET {$url['path']} HTTP/1.0";
		$headers[] = "Host: {$url['host']}";
		$headers[] = "Connection: Close";
		$headers[] = "\r\n";		
		$headers = implode("\r\n", $headers);	
		if(!@fwrite($fp, $headers))
		{
			return false;
		}
		while(!feof($fp))
		{
			$data .= fgets($fp, 12800);
		}
		fclose($fp);
		$data = explode("\r\n\r\n", $data, 2);
		return $data[1];
	}
}
function space2dash($string='')
{
	return str_replace(' ', '-', $string);
}
?>