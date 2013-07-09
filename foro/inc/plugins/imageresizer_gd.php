<?php

/**
 * Plugin name: Image Resizer & Optimizer with GD
 * Based on Cipher's Image Resizer plugin <ferry@cipher.demon.nl>
 *
 * @package imageresizer_gd
 * @version 1.1.1
 * @author Cipher <ferry@cipher.demon.nl>
 * @author MT Jordan <mtjo62@gmail.com>
 * @copyright 2007 <ferry@cipher.demon.nl>
 * @copyright 2010 openSource Partners
 * @license LGPL
 * @revision $Id: imageresizer_gd.php,v 1.4 2010/15/11 08:13:44 mtjo $
 */

$plugins->add_hook( 'parse_message', 'imageresizer_gd_message' );
$plugins->add_hook( 'pre_output_page', 'imageresizer_gd_page' );

function imageresizer_gd_info()
{
    return array(
        'name'          => 'Image Resizer & Optimizer with GD',
        'description'   => 'Resizes and optimizes an image if the width or filesize is larger than a maximum width given through the settings panel.',
        'website'       => 'http://mods.mybboard.net/',
        'author'        => 'MT Jordan',
        'version'       => '1.1.1',
        'guid'          => '3d1f80eabbdc15967e189c3b222ca68a',
        'compatibility' => '16*'
    );
}

function imageresizer_gd_activate()
{
    global $mybb, $db;

    //DELETE ALL SETTINGS - make sure there are no settings from a previous activation
    $db->query( "DELETE FROM " . TABLE_PREFIX . "settings WHERE name IN('image_resizer_active','image_resizer_resizeroveride','image_resizer_maxwidth','image_resizer_resizewidth','image_resizer_maxsize','image_resizer_animwidth','image_resizer_animsize','image_resizer_showwarning','image_resizer_borderstyle','image_resizer_bordersize','image_resizer_bordercolor','image_resizer_warning','image_resizer_warningcolor','image_resizer_warningbackground','image_resizer_warningposition')" );
    $db->query( "DELETE FROM " . TABLE_PREFIX . "settinggroups WHERE name='imageresizer_gd'" );

    // Add settings
    $settings_group = array(
        'gid'          => 'NULL',
        'name'         => 'imageresizer_gd',
        'title'        => 'Image Resizer & Optimizer with GD',
        'description'  => 'Settings for Image Resizer & Optimizer with GD plugin.',
        'disporder'    => '20',
        'isdefault'    => '0'
    );
    $db->insert_query('settinggroups', $settings_group);
    $gid = $db->insert_id();

    $setting = array(
        'sid'          => 'NULL',
        'name'         => 'image_resizer_active',
        'title'        => 'Active',
        'description'  => 'This gives you the possibility to deactivate the plugin without losing the settings.',
        'optionscode'  => 'yesno',
        'value'        => '0',
        'disporder'    => '1',
        'gid'          => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_resizeroveride',
        'title'       => 'Overide dynamic resizing and optimization',
        'description' => 'This setting overides dynamic GD processing and statically resizes images. Set this to "Yes" if your Host has disabled the fopen wrapper and does not allow .htaccess overide.',
        'optionscode' => 'yesno',
        'value'       => '0',
        'disporder'   => '2',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_maxwidth',
        'title'       => 'Maximum width',
        'description' => 'Maximum width of images in posts.',
        'optionscode' => 'text',
        'value'       => '500',
        'disporder'   => '3',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_resizewidth',
        'title'       => 'Resize width',
        'description' => 'Resized width of images in posts that exceed maximum width.',
        'optionscode' => 'text',
        'value'       => '500',
        'disporder'   => '4',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_maxsize',
        'title'       => 'Maximum filesize',
        'description' => 'Maximum filesize allowed before an image is optimized regardless of width.',
        'optionscode' => 'text',
        'value'       => '30000',
        'disporder'   => '5',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_animwidth',
        'title'       => 'Maximum width for animated GIF',
        'description' => 'Maximum width allowed for an animated GIF.',
        'optionscode' => 'text',
        'value'       => '500',
        'disporder'   => '6',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_animsize',
        'title'       => 'Maximum filesize for animated GIF',
        'description' => 'Maximum filesize allowed for an animated GIF before it is flattened regardless of width.',
        'optionscode' => 'text',
        'value'       => '30000',
        'disporder'   => '7',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_showwarning',
        'title'       => 'Show warning',
        'description' => 'Should users be alerted to view full size image by a message link.',
        'optionscode' => 'yesno',
        'value'       => '1',
        'disporder'   => '8',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_borderstyle',
        'title'       => 'Warning border style',
        'description' => 'Warning border style.',
        'optionscode' => "select\ndashed=Dashed\ndotted=Dotted\ngroove=Groove\ninset=Inset\noutset=Outset\nridge=Ridge\nsolid=Solid",
        'value'       => 'dotted',
        'disporder'   => '9',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_bordersize',
        'title'       => 'Warning border size',
        'description' => 'Warning border size be in px. Leave blank or 0 for no border.',
        'optionscode' => 'text',
        'value'       => '1',
        'disporder'   => '10',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_bordercolor',
        'title'       => 'Warning border color',
        'description' => 'Warning border color.',
        'optionscode' => 'text',
        'value'       => '#ff0000',
        'disporder'   => '11',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_warning',
        'title'       => 'Warning message',
        'description' => 'Enter the of the warning text message.',
        'optionscode' => 'text',
        'value'       => 'Image exceeds set limits. Click to view full size image',
        'disporder'   => '12',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_warningcolor',
        'title'       => 'Warning text color',
        'description' => 'Warning message text color.',
        'optionscode' => 'text',
        'value'       => '#ffffff',
        'disporder'   => '13',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

    $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_warningbackground',
        'title'       => 'Warning background',
        'description' => 'Warning message background color.',
        'optionscode' => 'text',
        'value'       => '#ff0000',
        'disporder'   => '14',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

     $setting = array(
        'sid'         => 'NULL',
        'name'        => 'image_resizer_warningposition',
        'title'       => 'Warning message position',
        'description' => 'Vertical position of the warning message.',
        'optionscode' => "select\n0=Top\n1=Bottom",
        'value'       => '1',
        'disporder'   => '15',
        'gid'         => intval( $gid )
    );
    $db->insert_query( 'settings', $setting );

}

function imageresizer_gd_deactivate()
{
    global $db;

    //DELETE ALL SETTINGS
    $db->query( "DELETE FROM " . TABLE_PREFIX . "settings WHERE name IN('image_resizer_active','image_resizer_resizeroveride','image_resizer_maxwidth','image_resizer_resizewidth','image_resizer_maxsize','image_resizer_animwidth','image_resizer_animsize','image_resizer_showwarning','image_resizer_borderstyle','image_resizer_bordersize','image_resizer_bordercolor','image_resizer_warning','image_resizer_warningcolor','image_resizer_warningbackground','image_resizer_warningposition')" );
    $db->query( "DELETE FROM " . TABLE_PREFIX . "settinggroups WHERE name='imageresizer_gd'" );
}

function imageresizer_gd_message( $message )
{
    global $mybb;

    if ( $mybb->settings['image_resizer_active'] == '1' )
    {
        $pattern = '#<img(.*?)/>#';
        $replace = '<img class="postimage"$1/>';
        $message = preg_replace( $pattern, $replace, $message );
    }

    return $message;
}

function imageresizer_gd_page( $page )
{
    global $mybb;

    if ( $mybb->settings['image_resizer_active'] == '1' )
    {
        $page = str_replace( '</body>', imageresizer_gd_javascript().'</body>', $page );
    }

    return $page;
}

function imageresizer_gd_javascript()
{
    global $mybb;

    $maxwidth = $mybb->settings['image_resizer_maxwidth'];
    $maxsize = $mybb->settings['image_resizer_maxsize'];
    $animwidth = $mybb->settings['image_resizer_animwidth'];
    $animsize = $mybb->settings['image_resizer_animsize'];
    $resizewidth = $mybb->settings['image_resizer_resizewidth'];
    $borderstyle = $mybb->settings['image_resizer_borderstyle'];
    $bordersize = ( is_numeric( str_ireplace( 'px', '', $mybb->settings['image_resizer_bordersize'] ) ) ) ? ( int ) str_ireplace( 'px', '', $mybb->settings['image_resizer_bordersize'] ) : 1;
    $bordercolor = $mybb->settings['image_resizer_bordercolor'];
    $setwarning = ( int ) $mybb->settings['image_resizer_showwarning'];
    $warning = $mybb->settings['image_resizer_warning'];
    $warningcolor = $mybb->settings['image_resizer_warningcolor'];
    $warningbackground = $mybb->settings['image_resizer_warningbackground'];
    $warningposition = $mybb->settings['image_resizer_warningposition'];
    $resizerEngineURL = $mybb->settings['bburl'] . '/inc/plugins/resizer_class/resizer_class.php';
    $resizeroveride = (int) $mybb->settings['image_resizer_resizeroveride'];

    $jscript = '<script type="text/javascript">' . "\n" .
               '<!--' . "\n" .
               'function resize_images() {' . "\n" .
               'var img = $$( "img.postimage" );' . "\n" .
               'for ( var i = 0; i < img.length; i++ ) {' . "\n" .
               'while ( !img[i].complete ) { break; }' . "\n" .
               'if ( img[i].width > ' . $maxwidth . ' ) {' . "\n" .
               'var imgURL = img[i].getAttribute( "src" );' . "\n";

    if ( $resizeroveride )
    {
        $jscript .= 'var oldWidth = img[i].width;' . "\n" .
                    'var oldHeight = img[i].height;' . "\n" .
                    'img[i].width = ' . $resizewidth . ';' . "\n" .
                    'img[i].height = oldHeight * ( ' . $resizewidth . ' / oldWidth )' . "\n";
    }
    else
    {
         $jscript .= 'img[i].setAttribute( "src", "' . $resizerEngineURL . '?filename="+ imgURL +"&newwidth=' . $resizewidth . '&maxsize=' . $maxsize . '&maxwidth=' . $maxwidth . '&animwidth=' . $animwidth . '&animsize=' . $animsize . '" );' . "\n";
    }

    $jscript .= 'img[i].setAttribute( "alt", imgURL );' . "\n" .
                'img[i].setAttribute( "title", imgURL );' . "\n";

    if ( $setwarning )
    {
        if ( $bordersize )
        {
            $imageborder = $bordersize . 'px ' . $borderstyle . ' ' . $bordercolor;

            if ( $warningposition )
            {
                $warningborder = 'border-bottom:' . $imageborder . ';border-left:' . $imageborder . ';border-right:' . $imageborder . ';';

                $jscript .= 'img[i].style.borderTop = "' . $imageborder . '";' . "\n" .
                            'img[i].style.borderLeft = "' . $imageborder . '";' . "\n" .
                            'img[i].style.borderRight = "' . $imageborder . '";' . "\n" ;
            }
            else
            {
                $warningborder = 'border-top:' . $imageborder . ';border-left:' . $imageborder . ';border-right:' . $imageborder . ';';

                $jscript .= 'img[i].style.borderBottom = "' . $imageborder . '";' . "\n" .
                            'img[i].style.borderLeft = "' . $imageborder . '";' . "\n" .
                            'img[i].style.borderRight = "' . $imageborder . '";' . "\n";
            }
        }

        if ( $warningposition )
            $position = 'img[i].nextSibling';
        else
            $position = 'img[i]';

        $jscript .= 'var parent = img[i].parentNode;' . "\n" .
                    'var warning = document.createElement( "div" );' . "\n" .
                    'var align = parent.getAttribute( "style" );' . "\n" .
                    'var setalign = "";' . "\n" .
                    'if ( align == "text-align: center;" || align == "text-align: right;" ) {' . "\n" .
                    'if ( align == "text-align: center;" ) setalign = "center";' . "\n" .
                    'if ( align == "text-align: right;" ) setalign = "right"; }' . "\n" .
                    'warning.innerHTML = "<div style=\"' . $warningborder . 'background:' . $warningbackground . ';padding:3px 0;text-align:center;width:' . $resizewidth  . 'px;\"><a href=\"" + imgURL + "\" target=\"_blank\" style=\"font-weight:bold;color: ' . $warningcolor . ';\">' . $warning . '</a></div>";' . "\n" .
                    'warning.setAttribute( "align", setalign );' . "\n" .
                    'parent.insertBefore( warning, ' . $position . ' );' . "\n";
    }

    $jscript .= '}}}' . "\n" .
                'Event.observe( window, "load", function() { resize_images(); } );' . "\n" .
                '-->' . "\n" .
                '</script>' . "\n";

    return $jscript;
}

?>