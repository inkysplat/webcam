<?php  
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL, $_GET["uri"]); 
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0"); 
	curl_setopt($ch, CURLOPT_HEADER, 'accept-language: en-us');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  echo curl_exec($ch); 
  curl_close($ch);      