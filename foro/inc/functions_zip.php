<?php
/**
 * MyBB WYSIWYG Editor 1.0
 * Copyright 2010 FrinkLabs, All Rights Reserved
 *
 * Website: http://frinklabs.xe.cx/
 * License: http://frinklabs.xe.cx/wysiwyg/license
 */

/**
 * Extracts zip archives
 *
 * @param string The path to the zip archive
 * @param string The path to the directory in which it should extract the archive to
 * @return boolean
 */
function extract_zip($archive, $target)
{
    // Does the archive exist?
	if (file_exists($archive) && ($zip = zip_open($archive)))
	{
		while($zip_entry = zip_read($zip))
		{
			$file_name = zip_entry_name($zip_entry);
			$file_size = zip_entry_filesize($zip_entry);
			$comp_meth = zip_entry_compressionmethod($zip_entry);

			if (zip_entry_open($zip, $zip_entry, 'rb'))
			{
				$buffer = zip_entry_read($zip_entry, $file_size);

				if (preg_match('/\/$/', $file_name) && ($comp_meth == 'stored'))
				{
					if (!is_dir($target . $file_name))
						@mkdir($target . $file_name, 0777);
				}
				else
				{
					$fp = fopen($target . $file_name, 'wb');
					fwrite($fp, $buffer);
					fclose($fp);
				}

				zip_entry_close($zip_entry);
			}
		}

		zip_close($zip);
	}
	else
	{
		return false;
    }
}
?>