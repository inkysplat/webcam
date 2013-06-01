<<<<<<< Updated upstream:system/application/views/layouts/header.php
<!doctype html> <!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?= $title;?></title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="Adam Nicholls">
        <meta name="contact" content="technical@goramandvincent.com">
        <meta name="viewport" content="width=device-width">
	<?php if(isset($refresh_page) && $refresh_page):?>
	   <meta http-equiv="refresh" content="60">   
	<?php endif;?>
        <link rel="icon" href="<?= SITE_URL;?>/favicon.ico" type="image/x-icon">
        <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" rel="stylesheet">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" href="/css/style.css">
    </head>
    <body>
=======
<!doctype html> 
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Goram + Vincent - Webcam - Powered by Raspberry Pi.</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="Adam Nicholls">
        <meta name="contact" content="technical@goramandvincent.com">
        <meta name="viewport" content="width=device-width">
	<?php if(isset($refresh_page) && $refresh_page):?>
	   <!-- <meta http-equiv="refresh" content="60">    -->
	<?php endif;?>
        <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/assets/css/style.css">	
        <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    </head>
    <body>
>>>>>>> Stashed changes:system/views/layout/header.inc.php
