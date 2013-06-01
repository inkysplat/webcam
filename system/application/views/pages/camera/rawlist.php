<?php
if(isset($format)):
	switch($format):
		case 'javascript':
			$i = 0;
			echo "\nvar images = new Array();";
			foreach($images as $image)
			{
				$image = str_replace(SITE_URL,'',$image);
				echo "\nimages[".$i++."] = '".SITE_URL.$image."'";
			}
		break;
		case 'jsonp':
		case 'json':
			$i = array();
			foreach($images as $image)
			{
				$image = str_replace(SITE_URL,'',$image);
				$i[] = SITE_URL.$image;
			}
			echo json_encode($i);
			break;
	endswitch;
endif;