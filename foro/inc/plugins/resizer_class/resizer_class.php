<?php

/**
 * Plugin name: Image Resizer & Optimizer with GD
 * Based on Cipher's Image Resizer plugin <ferry@cipher.demon.nl>
 *
 * @package imageresizer
 * @version 1.1.1
 * @author MT Jordan <mtjo62@gmail.com>
 * @copyright 2010 openSource Partners
 * @license LGPL
 * @revision $Id: resizer_class.php,v 1.1 2008/09/08 08:13:44 mtjo $
 */

class resizeImage
{
   /**
     * Binary safe string
     *
     * @access public
     * @var    str
     */
   var $imgStr;

   /**
     * Constructor
     *
     * @access public
     * @param  str $src
     * @param  int $maxWidth
     * @param  int $maxSize
     * @param  int $animSize
     * @return void
     */
   function resizeImage( $src, $maxWidth, $newWidth, $maxSize, $animWidth, $animSize )
   {
       $imgInfo = @getimagesize( $src );

       if ( !$imgInfo ) exit;

       $this->imgStr = file_get_contents( $src );
       $imgType      = 'process_' . $imgInfo[2];

       $this->$imgType( $src, $imgInfo, $maxWidth, $this->setWidth( $src, $imgInfo, $maxWidth, $newWidth, $animWidth ), $maxSize, $animWidth, $animSize );
    }

    /**
     * Calculate the new images dimensions
     *
     * @access public
     * @param  str   $src
     * @param  array $imgInfo
     * @param  int   $maxWidth
     * @param  int   $newWidth
     * @param  int   $animWidth
     * @return array
     */
    function setWidth( $src, $imgInfo, $maxWidth, $newWidth, $animWidth )
    {
        $setWidth = array();

        if ( $this->gifAnim( $src ) && $imgInfo[0] > $animWidth )
        {
            $percentage = $animWidth / $imgInfo[0];

            $setWidth[] = $animWidth;
            $setWidth[] = $imgInfo[1] * $percentage;
        }
        elseif ( !$this->gifAnim( $src ) && $imgInfo[0] > $maxWidth )
        {
            $percentage = $newWidth / $imgInfo[0];

            $setWidth[] = $newWidth;
            $setWidth[] = $imgInfo[1] * $percentage;
        }
        else
        {
            $setWidth[] = $imgInfo[0];
            $setWidth[] = $imgInfo[1];
        }

        return $setWidth;
    }

    /**
     * Process GIF image
     *
     * @access public
     * @param  str   $src
     * @param  array $imgInfo
     * @param  int   $newWidth
     * @param  int   $maxSize
     * @param  int   $animSize
     * @return void
     */
    function process_1( $src, $imgInfo, $maxWidth, $newWidth, $maxSize, $animWidth, $animSize )
    {
        header( 'Content-type: image/gif' );

        //if animated GIF, test for width > $animWidth and filesize > $animSize, else return w/o processing
        if ( $this->gifAnim( $src ) )
        {
            if ( $imgInfo[0] < $animWidth && strlen( $this->imgStr ) < $animSize )
            {
                $fp = fopen( $src, 'rb' );
                fpassthru( $fp );
                exit;
            }
        }

        //if image < $maxWidth and < $maxSize, return w/o processing
        if ( !$this->gifAnim( $src ) && $imgInfo[0] < $maxWidth && strlen( $this->imgStr ) < $maxSize )
        {
            $fp = fopen( $src, 'rb' );
            fpassthru( $fp );
            exit;
        }

        $thumb   = imagecreate( $newWidth[0], $newWidth[1] );
        $source  = imagecreatefromgif( $src );

        $isTrans = $this->isImgTransparent( $source, $imgInfo );
        $rgb     = $this->randomRGB( $source );

        if ( $isTrans )
            imagefill( $thumb, 0, 0, imagecolorallocate( $thumb, $rgb['r'], $rgb['g'], $rgb['b'] ) );

        imagecopyresampled( $thumb, $source, 0, 0, 0, 0,  $newWidth[0], $newWidth[1], $imgInfo[0], $imgInfo[1] );

        if ( $isTrans )
            $this->setImgTransparent( $thumb );

        imagegif( $thumb );
        imagedestroy( $thumb );
    }

    /**
     * Process JPG image
     *
     * @access public
     * @param  str   $src
     * @param  array $imgInfo
     * @param  int   $maxWidth
     * @param  int   $newWidth
     * @param  int   $maxSize
     * @param  int   $animSize
     * @return void
     */
    function process_2( $src, $imgInfo, $maxWidth, $newWidth, $maxSize, $animWidth=null, $animSize=null )
    {
        header( 'Content-type: image/jpeg' );

        //if image < $maxWidth and < $maxSize, return w/o processing
        if ( $imgInfo[0] < $maxWidth && strlen( $this->imgStr ) < $maxSize )
        {
            $fp = fopen( $src, 'rb' );
            fpassthru( $fp );
            exit;
        }

        $thumb  = imagecreatetruecolor( $newWidth[0], $newWidth[1] );
        $source = imagecreatefromjpeg( $src );

        imagecopyresized( $thumb, $source, 0, 0, 0, 0,  $newWidth[0], $newWidth[1], $imgInfo[0], $imgInfo[1] );

        imagejpeg( $thumb );
        imagedestroy( $thumb );
    }

    /**
     * Process PNG image
     *
     * @access public
     * @param  str   $src
     * @param  array $imgInfo
     * @param  int   $maxWidth
     * @param  int   $newWidth
     * @param  int   $maxSize
     * @param  int   $animSize
     * @return void
     */
    function process_3( $src, $imgInfo, $maxWidth, $newWidth, $maxSize, $animWidth=null, $animSize=null )
    {
        header( 'Content-type: image/png' );

        //if image < $maxWidth and < $maxSize, return w/o processing
        if ( $imgInfo[0] < $maxWidth && strlen( $this->imgStr ) < $maxSize )
        {
            $fp = fopen( $src, 'rb' );
            fpassthru( $fp );
            exit;
        }

        $thumb   = imagecreatetruecolor( $newWidth[0], $newWidth[1] );
        $source  = imagecreatefrompng( $src );
        $isTrans = $this->isImgTransparent( $source, $imgInfo );
        $rgb     = $this->randomRGB( $source );

        if ( $isTrans )
            imagefill( $thumb, 0, 0, imagecolorallocate( $thumb, $rgb['r'], $rgb['g'], $rgb['b'] ) );

        imagecopyresampled( $thumb, $source, 0, 0, 0, 0,  $newWidth[0], $newWidth[1], $imgInfo[0], $imgInfo[1] );

        if ( $isTrans )
            $this->setImgTransparent( $thumb );

        imagetruecolortopalette ( $thumb, 20, 256 );

        imagepng( $thumb );
        imagedestroy( $thumb );
    }

    /**
     * Determine if GIF/PNG image has transparent index
     *
     * @access public
     * @param  mixed $src
     * @param  array $imgInfo
     * @return bool
     */
    function isImgTransparent( $src, $imgInfo )
    {
        $destID = imagecreatetruecolor( $imgInfo[0], $imgInfo[1] );

        imagepalettecopy( $destID, $src );
        imagecopyresized( $destID, $src, 0, 0, 0, 0, $imgInfo[0], $imgInfo[1], $imgInfo[0], $imgInfo[1] );

        $black = imagecolorat( $destID, 0, 0 );
        $fill  = imagecolorallocate( $destID, 255, 255, 255 );

        imagefill( $destID, 0, 0, $fill );
        imagecopyresized( $destID, $src, 0, 0, 0, 0, $imgInfo[0], $imgInfo[1], $imgInfo[0], $imgInfo[1] );

        $white = imagecolorat( $destID, 0, 0 );

        imagedestroy( $destID );

        if ( $black != $white && $this->testCorners( $src, $imgInfo ) )
            return true;
        else
            return false;
    }

    /**
     * Check four points to determine if image has consistent transparent index
     *
     * @access public
     * @param  mixed $src
     * @param  array $imgInfo
     * @return bool
     */
    function testCorners( $src, $imgInfo )
    {
        $pointA = imagecolorat( $src, 0, 0 );
        $pointB = imagecolorat( $src, $imgInfo[0] - 1, 0 );
        $pointC = imagecolorat( $src, 0, $imgInfo[1] - 1 );
        $pointD = imagecolorat( $src, $imgInfo[0] - 1, $imgInfo[1] - 1 );

        if ( $pointA == $pointB && $pointB == $pointC && $pointC == $pointD )
            return true;

        return false;
    }

    /**
     * Determine RGB value not in current image color palette
     *
     * @access public
     * @param  mixed $src
     * @param  array $imgInfo
     * @return bool
     */
    function randomRGB( $src )
    {
        $total = ( imagecolorstotal( $src ) <= 0 ) ? 256 : imagecolorstotal( $src );
        $red   = ( rand() % 255 );
        $green = ( rand() % 255 );
        $blue  = ( rand() % 255 );

        for ( $i = 1; $i <= $total; $i++ )
        {
            if ( imagecolorexact( $src, $red, $green, $blue ) == -1 )
            {
                return array( 'r' => $red,
                              'g' => $green,
                              'b' => $blue );
            }
        }
    }

    /**
     * Set processed image background transparent
     *
     * @access public
     * @param  mixed $src
     * @return void
     */
    function setImgTransparent( $src )
    {
        imagecolortransparent( $src, imagecolorat( $src, 0, 0 ) );
    }

    /**
     * Determine if GIF image is animated
     *
     * @access public
     * @param  str $src
     * @return bool
     */
    function gifAnim( $src )
    {
        $str_loc = 0;
        $count   = 0;

        //return after we find a 2nd frame
        while ( $count < 2 )
        {
            $where1 = strpos( $this->imgStr, "\x00\x21\xF9\x04", $str_loc );

            if ( $where1 === FALSE )
            {
                break;
            }
            else
            {
                $str_loc = $where1 + 1;
                $where2  = strpos( $this->imgStr, "\x00\x2C", $str_loc );

                if ( $where2 === FALSE )
                {
                    break;
                }
                else
                {
                    if ( $where1 + 8 == $where2 )
                        $count++;

                    $str_loc = $where2 + 1;
                }
            }
        }

        if ( $count > 1 )
            return true;

        else
            return false;
    }
}

//create the image resize object
return new resizeImage( $_GET['filename'], $_GET['maxwidth'], $_GET['newwidth'], $_GET['maxsize'], $_GET['animwidth'], $_GET['animsize'] );

?>