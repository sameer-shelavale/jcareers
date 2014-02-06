<?php
/*
:::::::::::::::::::::::::::::::::::::::::::::::::
::                                             ::
::         CAPTCHA Validation projects         ::
::                                             ::
::             2006 10. 09. 03.40.             ::
::                                             ::
::                                             ::
::                                             ::
::                                             ::
:::::::::::::::::::::::::::::::::::::::::::::::::
*/
define ( VERSION, '1.00' );
/*
:::::::::::::::::::::::::::::::::::::::::::::::::
::                                             ::
::      C H A P T A  N O I S E  C L A S S      ::
::                                             ::
:::::::::::::::::::::::::::::::::::::::::::::::::
*/
class Captcha {
	/*
	:::::::::::::::::::::::::::::::::::::::::::::
	::                                         ::
	::           V A R I A B L E S             ::
	::                                         ::
	:::::::::::::::::::::::::::::::::::::::::::::
	*/
	var $image;
	var $frame;
	/*
	:::::::::::::::::::::::::::::::::::::::::::::
	::                                         ::
	::M A I N  C L A S S  C O N S T R U C T O R::
	::                                         ::
	:::::::::::::::::::::::::::::::::::::::::::::
	*/
	function Captcha ( $text, $font, $color )
	{
		$C              = HexDec ( $color );
		$R              = floor ( $C / pow ( 256, 2 ) );
		$G              = floor ( ( $C % pow ( 256, 2 ) ) / pow ( 256, 1 ) );
		$B              = floor ( ( ( $C % pow ( 256, 2 ) ) % pow ( 256, 1 ) ) / pow ( 256, 0 ) );
		$fsize          = 32;
		$bound          = array ( );
		$bound          = imageTTFBbox ( $fsize, 0, $font, $text );
		$this->image    = imageCreateTrueColor ( $bound[4] + 5, abs($bound[5]) + 15 );
		imageFill       ( $this->image, 0, 0, ImageColorAllocate ( $this->image, 255, 255, 255 ) );
		imagettftext    ( $this->image, $fsize, 0, 2, abs($bound[5]) + 5, ImageColorAllocate ( $this->image, $R, $G, $B ), $font, $text );
	}
	/*
	:::::::::::::::::::::::::::::::::::::::::::::
	::                                         ::
	::                N O I S E                ::
	::                                         ::
	:::::::::::::::::::::::::::::::::::::::::::::
	*/
	function Noise( $intGray, $intColor )
	{
		$intWidth  = imageSX( $this->frame );
		$intHeight = imageSY( $this->frame );
		for ( $i = 0; $i < 768; $i++ )
		{
			if ( $i < 255 )
			{
				$arrLUT[ $i ] = 0;
			}
			else if ( $i < 512 )
			{
				$arrLUT[ $i ] = $i - 256;
			}
			else
			{
				$arrLUT[ $i ] = 255;
			}
		}
		$intGray2 = $intGray / 2;
		$intColor2 = $intColor / 2;
		for ( $y = 0; $y < $intHeight; $y++ )
		{
			for ( $x = 0; $x < $intWidth; $x++ )
			{
				$rgbCol	 = imageColorAt( $this->frame, $x, $y );
				$red     = ( $rgbCol >> 16 ) & 0xFF;
				$green   = ( $rgbCol >> 8 ) & 0xFF;
				$blue    = $rgbCol & 0xFF;
				$rndGray = rand( 0, $intGray ) - $intGray2;
				$red   += 255 + $rndGray + mt_rand( 0, $intColor ) - $intColor2;
				$green += 255 + $rndGray + mt_rand( 0, $intColor ) - $intColor2;
				$blue  += 255 + $rndGray + mt_rand( 0, $intColor ) - $intColor2;
				imageSetPixel( $this->frame, $x, $y, ( $arrLUT[ $red ] << 16 ) | ( $arrLUT[ $green ] << 8 ) | $arrLUT[ $blue ] );
			}
		}
	}
	/*
	:::::::::::::::::::::::::::::::::::::::::::::
	::                                         ::
	::        C A P T C H A  F R A M E         ::
	::                                         ::
	:::::::::::::::::::::::::::::::::::::::::::::
	*/
	function Frame ( )
	{
		$this->frame = imageCreateTrueColor ( imageSX ( $this->image ), imageSY ( $this->image ) );
		if ( imageCopy ( $this->frame, $this->image, 0, 0, 0, 0, imageSX ( $this->image ), imageSY ( $this->image ) ) )
		{
			Captcha::Noise ( 200, 0 );
			ob_start ( ); imageGif ( $this->frame ); imageDestroy ( $this->frame ); $frame = ob_get_contents ( ); ob_end_clean ( );
		}
		return $frame;
	}
	/*
	:::::::::::::::::::::::::::::::::::::::::::::
	::                                         ::
	::              __END_CLASS__              ::
	::                                         ::
	:::::::::::::::::::::::::::::::::::::::::::::
	*/
}
?>
