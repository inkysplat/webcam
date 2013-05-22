<?php

$args = array('lastfm', 'twitter');

include_once('api.php');
$twitter = json_decode($calls['twitter'],true);
$lastfm = json_decode($calls['lastfm'], true);


echo "<!-- ".print_r($lastfm,true)."-->";

$refresh_page = true;
