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
        <link href="/css/font-awesome.min.css" rel="stylesheet">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link href="/js/vendor/fancybox/jquery.fancybox.css" rel="stylesheet">
        <link rel="stylesheet" href="/css/style.css">
        <script>
            var localClient = <?= $_SERVER['REMOTE_ADDR'] == '82.152.190.66'?'true':'false'?>;
            var staticStream = <?= isset($_GET['static'])?'true':'false';?>;
        </script>
    </head>
    <body>

