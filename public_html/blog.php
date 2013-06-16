<?php header('Content-type: application/json');?>
<?= json_encode(simplexml_load_file('http://goramandvincent.com/site/rss'));?>