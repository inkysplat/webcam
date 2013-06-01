<?php
if(isset($format)):
	switch($format):
		case 'javascript':
			$i = 0;
			echo "\nvar images = new Array();";
			foreach($images as $image)
			{
				echo "\nimages[".$i++."] = ".SITE_URL.$image['url'];
			}
		break;
		case 'jsonp':
		case 'json':
			if($full){
				echo json_encode($images);
			}else{
				$i = array();
				foreach($images as $image)
				{
					$i[] = SITE_URL.$image['url'];
				}
				echo json_encode($i);
			}

			break;
			case 'xml':
				echo trim($xml);
			break;
	endswitch;
endif;