<?php

define('IN_MYBB',1);
define('NO_ONLINE',1);
require('./global.php');
$plugins->run_hooks('ajaxpmnotice_start');

if(defined('AJAXPMNOTICE'))
{
	echo '<div style="position: fixed; right: 60px; top: 40px; width: 360px; z-index: 32765;">
<span style="background-color: #ffffff; display: block; height: 1px; margin: 0 5px; opacity: 0.5;"></span>
<span style="background-color: #000000; border-right: 2px solid #ffffff; border-left: 2px solid #ffffff; display: block; height: 1px; margin: 0 3px; opacity: 0.5;"></span>
<span style="background-color: #000000; border-right: 1px solid #ffffff; border-left: 1px solid #ffffff; display: block; height: 1px; margin: 0 2px; opacity: 0.5;"></span>
<span style="background-color: #000000; border-right: 1px solid #ffffff; border-left: 1px solid #ffffff; display: block; height: 2px; margin: 0 1px; opacity: 0.5;"></span>
<div style="background-color: #000000; border-right: 1px solid #ffffff; border-left: 1px solid #ffffff; color: #ffffff; opacity: 0.5; padding: 0 5px; text-align: center;"><img src="images/ajaxpmnotice.png" alt="" style="float: left; margin-top: -30px;" />'.AJAXPMNOTICE.'</div>
<span style="background-color: #000000; border-right: 1px solid #ffffff; border-left: 1px solid #ffffff; display: block; height: 2px; margin: 0 1px; opacity: 0.5;"></span>
<span style="background-color: #000000; border-right: 1px solid #ffffff; border-left: 1px solid #ffffff; display: block; height: 1px; margin: 0 2px; opacity: 0.5;"></span>
<span style="background-color: #000000; border-right: 2px solid #ffffff; border-left: 2px solid #ffffff; display: block; height: 1px; margin: 0 3px; opacity: 0.5;"></span>
<span style="background-color: #ffffff; display: block; height: 1px; margin: 0 5px; opacity: 0.5;"></span>
</div>
<div style="position: fixed; right: 60px; top: 40px; width: 360px; z-index: 32766;">
<span style="background-color: #ffffff; display: block; height: 1px; margin: 0 5px"></span>
<span style="border-right: 2px solid #ffffff; border-left: 2px solid #ffffff; display: block; height: 1px; margin: 0 3px;"></span>
<span style="border-right: 1px solid #ffffff; border-left: 1px solid #ffffff; display: block; height: 1px; margin: 0 2px;"></span>
<span style="border-right: 1px solid #ffffff; border-left: 1px solid #ffffff; display: block; height: 2px; margin: 0 1px;"></span>
<div style="border-right: 1px solid #ffffff; border-left: 1px solid #ffffff; color: #ffffff; padding: 0 5px; text-align: center;"><img src="images/ajaxpmnotice.png" alt="" style="float: left; margin-top: -30px;" /><a href="'.$mybb->settings['bburl'].'/private.php" style="color: #ffffff; text-decoration: none;">'.AJAXPMNOTICE.'</a></div>
<span style="border-right: 1px solid #ffffff; border-left: 1px solid #ffffff; display: block; height: 2px; margin: 0 1px;"></span>
<span style="border-right: 1px solid #ffffff; border-left: 1px solid #ffffff; display: block; height: 1px; margin: 0 2px;"></span>
<span style="border-right: 2px solid #ffffff; border-left: 2px solid #ffffff; display: block; height: 1px; margin: 0 3px;"></span>
<span style="background-color: #ffffff; display: block; height: 1px; margin: 0 5px;"></span>
</div>'; 
}

?>