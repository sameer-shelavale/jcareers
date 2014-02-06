<?php
/*
:::::::::::::::::::::::::::::::::::::::::::::::::
::                                             ::
::         CAPTCHA Validation projects         ::
::                                             ::
::             2006 10. 01. 09.56.             ::
::                                             ::
::                                             ::
:: Use set_time_limit ( ) function for longer  ::
::   execution, the execution time at this     ::
::     confuguration less than 3 secunds       ::
::                                             ::
:::::::::::::::::::::::::::::::::::::::::::::::::
*/
set_time_limit ( 0 );
/*
:::::::::::::::::::::::::::::::::::::::::::::::::
::                                             ::
::          Include required classes           ::
::                                             ::
:::::::::::::::::::::::::::::::::::::::::::::::::
*/
include "Captcha.class.php";
include "GifMerge.class.php";
/*
:::::::::::::::::::::::::::::::::::::::::::::::::
::                                             ::
::   And turn the http header into image/gif   ::
::                                             ::
:::::::::::::::::::::::::::::::::::::::::::::::::
*/
header ( 'Content-type: image/gif' );
/*
:::::::::::::::::::::::::::::::::::::::::::::::::
::                                             ::
::   Construct the CAPTCHA animation frames    ::
::                                             ::
:::::::::::::::::::::::::::::::::::::::::::::::::
+
+
+  Constructor parameters:
+  - CAPTCHA validation unique string ( which read from a file from a specified path e.g.: 'captcha/{unique time}.txt'  )
+  - Font file for CAPTCHA image
+  - Given CAPTCHA image color by hexadecimal value
+
+
*/
$captcha = new Captcha ( fread ( fopen ( "captcha/".$_SERVER['QUERY_STRING'].".txt", "r"), filesize ( "captcha/".$_SERVER['QUERY_STRING'].".txt" ) ), "fonts/verdana.ttf", "666600" );
for ( $i = 0; $i < 35; $i++ )
{
	$j = -160;
	$frames[$i] = $captcha->Frame ( $j + ( $i * 16 ), 16 );
	$framed[$i] = 10; /* Delay between CAPTCHA animation frames */
}
/*
:::::::::::::::::::::::::::::::::::::::::::::::::
::                                             ::
::  And build & show frames into animated GIF  ::
::                                             ::
:::::::::::::::::::::::::::::::::::::::::::::::::
*/
$anim = new GifMerge( $frames, 255, 255, 255, 0, $framed, 0, 0, 'C_MEMORY' );
echo $anim->getAnimation ( );
?>
