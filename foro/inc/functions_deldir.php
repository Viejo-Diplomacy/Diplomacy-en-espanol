<?php
/**
 * MyBB WYSIWYG Editor 1.0
 * Copyright 2010 FrinkLabs, All Rights Reserved
 *
 * Website: http://frinklabs.xe.cx/
 * License: http://frinklabs.xe.cx/wysiwyg/license
 */

/**
 * Deletes directory and it's content
 *
 * @param string The path to the directory
 */
function delete_dir($dir) 
{
    if(!$handle = @opendir($dir))
    {	
	    return;
	}
	
    while (false !== ($obj = readdir($handle))) 
	{
        if($obj=='.' || $obj=='..')
        {		
		    continue;
	    }
		
        if(!@unlink($dir.'/'.$obj))
        {		
		    delete_dir($dir.'/'.$obj, true);
		}
    }

    closedir($handle);
	
    @rmdir($dir);  
}

?>