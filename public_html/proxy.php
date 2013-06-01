<?php

try{

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $_GET["uri"]); 
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0"); 
	curl_setopt($ch, CURLOPT_HEADER, 'accept-language: en-us');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$result = curl_exec($ch); 
	curl_close($ch);

}catch(Exception $e)
{
	if(!($file = file_get_contents($_GET['uri'])))
	{
		throw new Exception("Failed fetching URL::".$_GET['uri']);
	}
}

if(isset($file))
{
	ob_end_clean();
	die($file);
}