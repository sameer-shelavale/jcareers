<?php
/*
:::::::::::::::::::::::::::::::::::::::::::::::::
::                                             ::
::         CAPTCHA Validation projects         ::
::                                             ::
::             2006 10. 01. 09.56.             ::
::                                             ::
::                                             ::
:: Try on-line animated CAPTCHA form generator ::
::                                             ::
:: http://gifs.hu/phpclasses/demos/Captcha01/  ::
::                                             ::
:::::::::::::::::::::::::::::::::::::::::::::::::
*/

include "GIFEncoder.class.php";

define ( 'VERSION', '2.00' );
define ( ANIM_FRAMES, 35 );
define ( ANIM_DELAYS, 10 );

Class Captcha {
	/*
	:::::::::::::::::::::::::::::::::::::::::::::
	::                                         ::
	::           V A R I A B L E S             ::
	::                                         ::
	:::::::::::::::::::::::::::::::::::::::::::::
	*/
	var $var01 = 64;
	var $var02 = 90;
	var $var03 = 0x66; // Red
	var $var04 = 0x66; // Green
	var $var05 = 0x00; // Blue
	var $var06 = 130;
	var $var07 = true;
	var $var08 = array ( );
	var $var09 = array ( );
	var $var10 = array ( );
	var $var11 = array ( );
	var $var12 = array ( );
	var $var13;
	var $var14;
	var $var15;
	var $var16;
	var $var17;
	var $var18;
	/*
	:::::::::::::::::::::::::::::::::::::::::::::
	::                                         ::
	::M A I N  C L A S S  C O N S T R U C T O R::
	::                                         ::
	:::::::::::::::::::::::::::::::::::::::::::::
	*/
	function Captcha ( $text, $font, $color ) {

		$var0 = $font;
		$var4 = HexDec ( $color );
		$this->var03 = floor ( $var4 / pow ( 256, 2 ) );
		$this->var04 = floor ( ( $var4 % pow ( 256, 2 ) ) / pow ( 256, 1 ) );
		$this->var05 = floor ( ( ( $var4 % pow ( 256, 2 ) ) % pow ( 256, 1 ) ) / pow ( 256, 0 ) );
		$var1 = array ( );
		$var1 = imageTTFBbox ( 22, 0, $var0, $text );
		$this->var13 = imageCreateTrueColor ( $var1 [ 4 ] + 15, abs( $var1 [ 5 ] ) + 15 );
		$var2 = ImageColorAllocate( $this->var13,    0,    0,    0 );
		$var3 = ImageColorAllocate( $this->var13,  192,  192,  192 );
		imageFill( $this->var13, 0, 0, $var2 );
		imagettftext( $this->var13, 22, 0, 7, abs( $var1 [ 5 ] ) + 6, $var3, $var0, $text);
		$this->var14 = imageSX ( $this->var13 );
		$this->var15 = imageSY ( $this->var13 );
		for ( $y = 0; $y < $this->var15; $y++ ) {
			for ( $x = 0; $x < $this->var14; $x++ ) {
				$p = imageColorsForIndex ( $this->var13, imageColorAt ( $this->var13, $x, $y ) );
				$this->var17 [ $x ] [ $y ] = $p [ 'red' ] + $p [ 'green' ] + $p [ 'blue' ];
			}
		}
		imageDestroy ( $this->var13 );
		$this->var13 = imageCreateTrueColor ( $this->var14, $this->var15 );
		Captcha::funcs01 ( );
		Captcha::funcs02 ( );
		Captcha::funcs03 ( );
	}
	/*
	:::::::::::::::::::::::::::::::::::::::::::::
	::                                         ::
	::           F U N C T I O N  0 1          ::
	::                                         ::
	:::::::::::::::::::::::::::::::::::::::::::::
	*/
	function funcs01 ( ) {
    	for ( $x = 0; $x < $this->var14; $x++ ) {
    		for ( $y = 0; $y < $this->var15; $y++ ) {
				$var0 = 0;
				$var0 += $this->var17 [ $x ] [ $y ];
				$var0 += $this->var17 [ ( $x + 1 ) % $this->var14 ] [ $y ];
				$var0 += $this->var17 [ ( $x + $this->var14 - 1) % $this->var14 ] [ $y ];
				$var0 += $this->var17 [ $x ] [ ( $y + 1 ) % $this->var15 ];
				$var0 += $this->var17 [ $x ] [ ( $y + $this->var15 - 1 ) % $this->var15 ];
				$var0 += $this->var17 [ ( $x + 1 ) % $this->var14 ] [ ( $y + 1 ) % $this->var15 ];
				$var0 += $this->var17 [ ( $x + $this->var14 - 1) % $this->var14 ] [ ( $y + $this->var15 - 1 ) % $this->var15 ];
				$var0 += $this->var17 [ ( $x + $this->var14 - 1) % $this->var14 ] [ ( $y + 1 ) % $this->var15 ];
				$var0 += $this->var17 [ ( $x + 1 ) % $this->var14 ] [ ( $y + $this->var15 - 1) % $this->var15 ];
				$var0 /= 9;
				$var1 [ $x ] [ $y ] = ( ( ( float ) ( $var0 / 3 ) ) * ( ( float ) ( $this->var01 / 255 ) ) );
			}
		}

    	for ( $x = 1; $x < $this->var14 - 1; $x++ ) {
    		for ( $y = 1; $y < $this->var15 - 1; $y++ ) {
				$this->var11 [ $x ] [ $y ] = ( $var1 [ $x + 1 ] [ $y ] - $var1 [ $x - 1 ] [ $y ] );
				$this->var12 [ $x ] [ $y ] = ( $var1 [ $x ] [ $y + 1 ] - $var1 [ $x ] [ $y - 1 ] );
			}
		}
	}
	/*
	:::::::::::::::::::::::::::::::::::::::::::::
	::                                         ::
	::           F U N C T I O N  0 2          ::
	::                                         ::
	:::::::::::::::::::::::::::::::::::::::::::::
	*/
	function funcs02 ( ) {
		for ( $i = 0; $i < ( 255 - $this->var02 ); $i++) {
			$r = ( int ) ( $this->var03 * $i / (255 - $this->var02 ) );
			$g = ( int ) ( $this->var04 * $i / (255 - $this->var02 ) );
			$b = ( int ) ( $this->var05 * $i / (255 - $this->var02 ) );
			$this->var09 [ $i ] = array ( $r, $g, $b );
		}
		for ( $i = ( 255 - $this->var02 );  $i < 256; $i++ ) {
			$r = ( int ) ( $this->var03 + ( 255 - $this->var03 ) * ( $i + $this->var02 - 255 ) / $this->var02 );
			$g = ( int ) ( $this->var04 + ( 255 - $this->var04 ) * ( $i + $this->var02 - 255 ) / $this->var02 );
			$b = ( int ) ( $this->var05 + ( 255 - $this->var05 ) * ( $i + $this->var02 - 255 ) / $this->var02 );
			$this->var09 [ $i ] = array ( $r, $g, $b );
		}
	}
	/*
	:::::::::::::::::::::::::::::::::::::::::::::
	::                                         ::
	::           F U N C T I O N  0 3          ::
	::                                         ::
	:::::::::::::::::::::::::::::::::::::::::::::
	*/
	function funcs03 ( ) {
		$this->var16 = $this->var06;
		for ( $y = 0; $y < $this->var06; $y ++ ) {
			for ($x = 0; $x < $this->var06; $x++ ) {
				$var0 = ( (float) $x ) / $this->var16;
				$var1 = ( (float) $y ) / $this->var16;
				$var2 = ( float ) ( 1 - sqrt ( $var0 * $var0 + $var1 * $var1 ) );
				if ( $var2 < 0 ) $var2 = 0;
				$this->var10 [ $x ] [ $y ] = ( int ) ( $var2 * 0xff );
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
	function Frame ( $Lx, $Ly ) {
		$this->var16 = $this->var06 - 1;
		for ( $y = 0; $y < $this->var15; $y++ ) {
			$yoffset = $y * $this->var14;
			for ( $x = 0; $x < $this->var14; $x++ ) {
				$var0 = ( int ) ( abs ( $this->var11 [ $x ] [ $y ] - $x + $Lx) );
				$var1 = ( int ) ( abs ( $this->var12 [ $x ] [ $y ] - $y + $Ly) );

				if ( $var0 > $this->var16 ) $var0 = $this->var16;
				if ( $var1 > $this->var16 ) $var1 = $this->var16;

				if ( $this->var07 ) {
					$this->var08 [ $x + $yoffset ] = ( $this->var10 [ $var0 ] [ $var1 ] >> 1 ) +
														( $this->var08 [ $x + $yoffset ] >> 1 );
					imageSetPixel ( $this->var13, $x, $y,
									imageColorAllocate ( $this->var13,
										$this->var09 [ $this->var08 [ $x + $yoffset ] ] [ 0 ],
										$this->var09 [ $this->var08 [ $x + $yoffset ] ] [ 1 ],
										$this->var09 [ $this->var08 [ $x + $yoffset ] ] [ 2 ]
									)
					);
				}
				else
				{
					$this->var08 [ $x + $yoffset ] = $this->var10 [ $var0 ] [ $var1 ];
					imageSetPixel ( $this->var13, $x, $y,
									imageColorAllocate ( $this->var13,
										$this->var09 [ $this->var08 [ $x + $yoffset ] ] [ 0 ],
										$this->var09 [ $this->var08 [ $x + $yoffset ] ] [ 1 ],
										$this->var09 [ $this->var08 [ $x + $yoffset ] ] [ 2 ]
									)
					);
				}
			}
		}
		ob_start ( ); imageGif ( $this->var13 ); $var2 = ob_get_contents ( ); ob_end_clean ( ); return $var2;
	}
	/*
	:::::::::::::::::::::::::::::::::::::::::::::
	::                                         ::
	::          A N I M A T E D  O U T         ::
	::                                         ::
	:::::::::::::::::::::::::::::::::::::::::::::
	*/
	function AnimatedOut ( ) {

		for ( $i = 0; $i < ANIM_FRAMES; $i++ ) {
			$j = -160;
			$f_arr [ ] = Captcha::Frame ( $j + ( $i * 16 ), 16 );
			$d_arr [ ] = ANIM_DELAYS;
		}
		$GIF = new GIFEncoder ( $f_arr, $d_arr, 0, 2, -1, -1, -1, "bin" );
		return ( $GIF->GetAnimation ( ) );
	}
}
?>
