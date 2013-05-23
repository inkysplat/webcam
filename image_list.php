<?php

define('SITE_PATH',realpath(dirname(__FILE__)).'/system/');

require_once(SITE_PATH.'bootStrap.php');

$date = date('Y-m-d');
if(isset($_GET['date']))
{
	$date = date('Y-m-d',strtotime($_GET['date']));
}

$date_path = implode(DIR_SEP,explode('-',$date));
$image_path = realpath(dirname(__FILE__).DIR_SEP.'webcam'.DIR_SEP.$date_path);
$files = scandir($image_path);

if($files && is_array($files))
{
	$images = array();

	foreach($files as $file)
	{
		if($file == '.' || $file == '..')
		{
			continue;
		}

		if(count($images) > 500)
		{
			continue;
		}

		if(substr($file,-4,4) == '.jpg')
		{
			$images[] = $file;
		}

	}

	if(!empty($images))
	{
		ob_end_clean();

		$i = 0;
		if(isset($_GET) && $_GET['format'])
		{
			$paths = array();
			foreach($images as $image)
			{
				$paths[] = DIR_SEP.'webcam'.DIR_SEP.$date_path.DIR_SEP.$image;
			}

			die(json_encode($paths));
		}else{
			header('Content-type: application/javascript;');
			ob_start();
			echo "\nvar images = new Array();";
			foreach($images as $image)
			{
				echo "\nimages[".$i++."] = '".DIR_SEP.'webcam'.DIR_SEP.$date_path.DIR_SEP.$image."'";
			}
			die(ob_get_clean());
		}
	}

}