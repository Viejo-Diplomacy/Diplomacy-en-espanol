<?php
/*
RSS Feed Poster
by: vbgamer45
http://www.mybbhacks.com
Copyright 2010  MyBBHacks.com

############################################
License Information:

Links to http://www.mybbhacks.com must remain unless
branding free option is purchased.
#############################################
*/

$feedcount = 0;
$maxitemcount = 0;
$tag = '';
$tag_attrs = '';
$insideitem = false;
$depth = array();



function verify_rss_url($url)
{
	global $txt, $depth;

	// Rss Data storage
	$finalrss = '';
	$failed = true;

		$fp2 = @fopen($url, "r");
		if ($fp2)
		{
			$failed = false;
			$contents = '';
			while (!feof($fp2))
			{
			  $contents .= fread($fp2, 8192);
			}
	
			fclose($fp2);
	
			$finalrss = $contents;
		}
	



		if($failed == true)
		{
			$url_array = parse_url($url);
	
			$fp = @fsockopen($url_array['host'], 80, $errno, $errstr, 30);
			if (!$fp)
			{
	
			}
			else
			{
				$failed = false;
	
			   $out = "GET " . $url_array['path'] . (@$url_array['query'] != '' ? '?' . $url_array['query'] : '') . "  HTTP/1.1\r\n";
			   $out .= "Host: " . $url_array['host'] . "\r\n";
			   $out .= "Connection: Close\r\n\r\n";
	
			   fwrite($fp, $out);
	
			   $rssdata = '';
			   
		   $header = '';
		   // Remove stupid headers.
		   	do 
			{
				$header .= fgets ($fp, 128 );

		 	 } while ( strpos($header, "\r\n\r\n" ) === false );
	
			   while (!feof($fp))
			   {
			       $rssdata .= fgets($fp, 128);
			   }
			   fclose($fp);
	
			   @$finalrss = @$rssdata;
	
			}
		}
	

	// Use cURL

		if($failed == true)
		{
			if(function_exists("curl_init"))
			{
				$failed = false;
				// Last but not least try cUrl
				$ch = curl_init();
	
				// set URL and other appropriate options
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
				// grab URL, and return output
				$output = curl_exec($ch);
	
				// close curl resource, and free up system resources
				curl_close($ch);
				return $output;
			}
		}




	// XML Parser functions to verify the XML Feed
	if($failed == false)
	{
		$depth = array();

		$xml_parser = xml_parser_create();
		xml_set_element_handler($xml_parser, "startElement2", "endElement2");


		   if (!xml_parse($xml_parser, $finalrss)) {
		      fatal_error(sprintf($txt['feedposter_err_xmlerror'],
		                   xml_error_string(xml_get_error_code($xml_parser)),
		                   xml_get_current_line_number($xml_parser)), false);
		   }

		xml_parser_free($xml_parser);
	}
	else
	{
		// We were not able to download the feed :(
		
	}


}


function startElement2($parser, $name, $attrs)
{
   global $depth;
   $depth[$parser]++;
}

function endElement2($parser, $name)
{
   global $depth;
   $depth[$parser]--;
}

function UpdateRSSFeedBots($task)
{
	global $db, $context, $feedcount, $maxitemcount, $insideitem, $tag, $tag_attrs;

	// First get all the enabled bots
	$context['feeds'] = array();
	$request = $db->write_query("
			SELECT
				ID_FEED, fid, feedurl, title, postername, updatetime, enabled, html,
				uid, locked, articlelink, topicprefix, numbertoimport, importevery, markasread 
			FROM ".TABLE_PREFIX."feedbot
			WHERE enabled = 1");

	while ($row = $db->fetch_array($request))
	{
		$context['feeds'][] = $row;
	}

	

	require_once MYBB_ROOT."inc/datahandlers/post.php";
	

	// Check if a field expired
	foreach ($context['feeds'] as $key => $feed)
	{

		$current_time = time();


		// If the feedbot time to next import has expired
		//add_task_log($task, "Check " . ($current_time + (60 * $feed['importevery'])) . " :" . $feed['updatetime']);
		
		if ($current_time > $feed['updatetime'])
		{

			$feeddata = GetRSSData($feed['feedurl']);



			if ($feeddata != false)
			{

				// Process the XML
					$xml_parser = xml_parser_create();
					$context['feeditems'] = array();
					$feedcount = 0;
					$maxitemcount = $feed['numbertoimport'];
					$tag = '';
					$tag_attrs = '';
					$insideitem = false;
					$context['feeditems'][0] = array();
					$context['feeditems'][0][] = array();
					$context['feeditems'][0]['title'] = '';
					$context['feeditems'][0]['description'] = '';
					$context['feeditems'][0]['link'] = '';



					xml_set_element_handler($xml_parser, "startElement1", "endElement1");
					xml_set_character_data_handler($xml_parser, "characterData1");

					if (!xml_parse($xml_parser, $feeddata))
					 {
						// Error reading xml data

					     xml_parser_free($xml_parser);
					 }
					else
					{
					   	// Data must be valid lets extra some information from it
					   	// RSS Feeds are a list of items that might contain title, description, and link


					   	// Free the xml parser memory
						xml_parser_free($xml_parser);

						// Loop though all the items
						$myfeedcount = 0;

						for ($i = 0; $i < ($feedcount); $i++)
						{
							

							if ($myfeedcount >= $maxitemcount)
							{
								continue;
							}
							
							//add_task_log($task, "NotSkip: $myfeedcount : $maxitemcount : $feedcount  T:" . $context['feeditems'][$i]['title']);

							
							// Check feed Log
							// Generate the hash for the log
							if(!isset($context['feeditems'][$i]['title']) || !isset($context['feeditems'][$i]['description']))
								continue;
								
							if(empty($context['feeditems'][$i]['title']) && empty($context['feeditems'][$i]['description']))
								continue;	
								
							

							$itemhash = md5($context['feeditems'][$i]['title'] . $context['feeditems'][$i]['description']);
							$request = $db->write_query("
							SELECT
								feedtime
							FROM ".TABLE_PREFIX."feedbot_log
							WHERE feedhash = '$itemhash'");


							// If no has has found that means no duplicate entry
							if ($db->num_rows($request) == 0)
							{

								// Create the Post
								$msg_title = ($feed['html'] ? $context['feeditems'][$i]['title'] : strip_tags($context['feeditems'][$i]['title']));

								$msg_body =  ($feed['html'] ? $context['feeditems'][$i]['description'] . "\n\n" . $context['feeditems'][$i]['link']  : strip_tags($context['feeditems'][$i]['description'] .  "\n\n" . $context['feeditems'][$i]['link']));
							
									
								$posthandler = new PostDataHandler("insert");
								$posthandler->action = "thread";
								
								if (strlen($msg_title) > 120)
									$msg_title = substr($msg_title,0,115);
								
								$msg_title = trim($msg_title);
							

								$new_thread = array(
									"fid" => $feed['fid'],
									"subject" => $feed['topicprefix'] . $msg_title,
									"icon" => '',
									"uid" => $feed['uid'],
									"username" => $feed['postername'],
									"message" => '[b]' . $msg_title . "[/b]\n\n" . $msg_body,
									"ipaddress" => '127.0.0.1',
									"posthash" => ''
								);
								
								$new_thread['modoptions']  = array('closethread' => $feed['locked']);
								
								$posthandler->set_data($new_thread);
								$valid_thread = $posthandler->validate_thread();
								
								if(!$valid_thread)
								{
									$post_errors = $posthandler->get_friendly_errors();
								}
								else 
									$thread_info = $posthandler->insert_thread();
									
									
								$tid = (int) $thread_info['tid'];
								$pid = (int)  $thread_info['pid'];
								
								if ($feed['markasread'])
								{
									// Mark thread as read
									require_once MYBB_ROOT."inc/functions_indicators.php";
									mark_thread_read($tid, $feed['fid']);
								}

								// Add Feed Log
								$fid = $feed['ID_FEED'];
								$ftime = time();

								$db->write_query("
								INSERT INTO ".TABLE_PREFIX."feedbot_log
									(ID_FEED, feedhash, feedtime, tid, pid)
								VALUES
									($fid,'$itemhash',$ftime,$tid,$pid)");
								
								$myfeedcount++;

							}
						}

					 } // End valid XML check



			}  // End get feed data
			
			
			
			// Set the RSS Feed Update time
			$updatetime = time() +  (60 * $feed['importevery']);
			
			
			$db->write_query("
			UPDATE ".TABLE_PREFIX."feedbot 
			SET 
				updatetime = '$updatetime'
		
			WHERE ID_FEED = " . $feed['ID_FEED']);

		} // End expire check


	} // End for each feed

}

function GetRSSData($url)
{
	$url_array = parse_url($url);
	

		$fp2 = @fopen($url, "r");
		if ($fp2)
		{
			$contents = '';
			while (!feof($fp2))
			{
			  $contents .= fread($fp2, 8192);
			}
	
			fclose($fp2);
	
			return $contents;
		}



		$fp = fsockopen($url_array['host'], 80, $errno, $errstr, 30);
		if (!$fp)
		{
	
		}
		else
		{
	
	
		   $out = "GET " . $url_array['path'] . (@$url_array['query'] != '' ? '?' . $url_array['query'] : '') . "  HTTP/1.1\r\n";
		   $out .= "Host: " . $url_array['host'] . "\r\n";
		   $out .= "Connection: Close\r\n\r\n";
	
		   fwrite($fp, $out);
	
		   $rssdata = '';
		   
		   $header = '';
		   // Remove stupid headers.
		   	do 
			{
				$header .= fgets ($fp, 128 );

		 	 } while ( strpos($header, "\r\n\r\n" ) === false );
	
		   while (!feof($fp))
		   {
		       $rssdata .= fgets($fp, 128);
		   }
		   fclose($fp);

		   $finalrss = $rssdata;
	
		   return  $finalrss;
		}
	


		if(function_exists("curl_init"))
		{
			// Last but not least try cUrl
			$ch = curl_init();
	
			// set URL and other appropriate options
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
			// grab URL, and return output
			$output = curl_exec($ch);
	
			// close curl resource, and free up system resources
			curl_close($ch);
			
			return $output;
		}
	


	// Failure return false
	return false;

}

function startElement1($parser, $name, $attrs)
 {
	global $insideitem, $tag, $tag_attrs;
	if ($insideitem)
	{
		$tag = $name;
		$tag_attrs =  $attrs;
	}
	elseif ($name == "ITEM"  || $name == "ENTRY")
	{
		$insideitem = true;
	}
}

function endElement1($parser, $name)
{
	global $insideitem, $tag, $feedcount, $context, $tag_attrs;

	if ($name == "ITEM" || $name == "ENTRY")
	{
		$feedcount++;
		$context['feeditems'][$feedcount] = array();
		$context['feeditems'][$feedcount][] = array();
		$context['feeditems'][$feedcount]['title'] = '';
		$context['feeditems'][$feedcount]['description'] = '';
		$context['feeditems'][$feedcount]['link'] = '';
		$tag_attrs = '';
		$insideitem = false;
	}
}

function characterData1($parser, $data)
 {
	global $insideitem, $tag,  $feedcount, $context, $maxitemcount, $tag_attrs;

	if ($insideitem )
 	{
		switch ($tag)
		{
			case "TITLE":
				$context['feeditems'][$feedcount]['title'] .= $data;
			break;

			case "DESCRIPTION":
				$context['feeditems'][$feedcount]['description'] .= $data;

			break;

			case "LINK":
				$context['feeditems'][$feedcount]['link'] .= $data;
			break;
			
			case "SUMMARY":
			$context['feeditems'][$feedcount]['description'] .= $data;

			break;
			case "CONTENT":
			$context['feeditems'][$feedcount]['description'] .= $data;

			break;

			case "LINK":
				$data = trim($data);
				$context['feeditems'][$feedcount]['link'] .= $data;
				IF (empty($data) && isset($tag_attrs['HREF']))
					$context['feeditems'][$feedcount]['link'] .= $tag_attrs['HREF'];
				
				
			break;
			
		}
	}
}



function task_rssfeedposter($task)
{
	global $lang;
	
	$lang->load('rssfeedposter');
	
	UpdateRSSFeedBots($task);
	
	add_task_log($task, $lang->rssfeedposter_taskran);
}
?>