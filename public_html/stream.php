<?php
ini_set('display_errors', 'Off');
if(preg_match('/robot|spider|crawler|curl|^$/i', $_SERVER['HTTP_USER_AGENT']))
{
	die('BOT');
}

date_default_timezone_set('Europe/London');


///////////////////////////////////////////////////////////////////////////////////
/////////////////////// NOTHING GETS EXECUTED BELOW WE WANT IMAGES ONLY ///////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////


staticImage();
die();

/* Usage: <img src="thisfile.php"> */
$server = "82.152.190.66"; // camera server
$port = 8443; // camera server port
$time = date('H');
if(isset($_GET['static']) || in_array($time,array('00','01','02','03','04','05','19','20','21','22','23')))
{
	staticImage();
}

$fp = fsockopen($server, $port, $errno, $errstr, 30);
if( !$fp ){
	staticImage();
}else{
    streamImage();
}

function streamImage()
{
	global $fp;
	$url = "/?action=stream"; // url on camera server
	$urlstring = "GET ".$url." HTTP/1.0\r\n\r\n";
    fwrite( $fp, $urlstring );
    while( $str = trim( fgets( $fp, 4096 ) ) )header( $str );
    fpassthru( $fp );
    fclose( $fp );
}

function staticImage()
{
	header('Content-Type: image/png;');
    header('Content-Length: ' . filesize('webcam.png'));
    ob_clean();
    flush();
    readfile('webcam.png');
    exit;
}
?>
