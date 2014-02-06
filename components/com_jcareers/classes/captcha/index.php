<?php
/*
:::::::::::::::::::::::::::::::::::::::::::::::::
::                                             ::
::         CAPTCHA Validation projects         ::
::                                             ::
::             2006 10. 01. 09.56.             ::
::                                             ::
::                                             ::
::                                             ::
::                                             ::
:::::::::::::::::::::::::::::::::::::::::::::::::
*/
if ( isset ( $_POST['go'] ) )
{
	if ( $_POST['validate'] == trim ( fread ( fopen ( "captcha/".$_POST['id'].".txt", "r"), filesize ( "captcha/".$_POST['id'].".txt" ) ) ) )
	{
		$message = "Given value is equal with CAPTCHA string.";
	}
	else
	{
		$message = "Given value IS NOT equal with CAPTCHA string.";
	}
	unlink ( "captcha/".$_POST['id'].".txt" );
}
/*
:::::::::::::::::::::::::::::::::::::::::::::::::
::                                             ::
::      Create an unique validation string     ::
::                                             ::
:::::::::::::::::::::::::::::::::::::::::::::::::
*/
$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
$string = "";
for ( $i = 0; $i < 5; $i++ )
	$string .= $chars{rand ( 0, strlen ( $chars ) )};
$id = time ( );
fwrite ( fopen ( "captcha/$id.txt", "w" ), $string );
/*
:::::::::::::::::::::::::::::::::::::::::::::::::
::                                             ::
::              Checker HTML code              ::
::                                             ::
:::::::::::::::::::::::::::::::::::::::::::::::::
*/
?>
<html>
	<head>
		<title>CAPTCHA Test</title>
		<style type="text/css">
			body {
				align=center;
				text-align:center;
			}
		</style>
	</head>
	<form method=post action=index.php>
		<table align=center>
			<tr>
				<td colspan=2 align=center><big><b><?php echo $message; ?></b></big></td>
			</tr>
			<tr>
				<td colspan=2 align=center><img src="captcha.php?<?php echo $id; ?>" alt=""></td>
			</tr>
			<tr>
				<td colspan=2 align=center>
					<input type=text name="validate" size=35>
					<input type=hidden name="id" value="<?php echo $id; ?>">
				</td>
			</tr>
			<tr>
				<td style="width:45%;" align=right><input type=submit name="go" value="Test" size=35></td>
				<td style="width:55%;" align=left><input onclick="window.location.href='index.php'" type=submit name="redraw" value="Redraw" size=35></td>
			</tr>
		</table>
	</form>
</html>
