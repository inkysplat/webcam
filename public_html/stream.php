<?php
ini_set('display_errors', 'Off');
if(preg_match('/robot|spider|crawler|curl|^$/i', $_SERVER['HTTP_USER_AGENT']))
{
	die('BOT');
}

/* Usage: <img src="thisfile.php"> */
$server = "82.152.190.66"; // camera server
$port = 8443; // camera server port
$time = date('H');
if(isset($_GET['static']) || in_array($time,array('00','01','02','03','04','05')))
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
	header('Content-Type: image/jpeg;');
    header('Content-Length: ' . filesize('webcam.jpg'));
    ob_clean();
    flush();
    readfile('webcam.jpg');
    exit;
}
?>
