<?php
if(isset($format)):
	switch($format):
		case 'javascript':
			$i = 0;
			echo "\nvar images = new Array();";
			foreach($images as $image)
			{
				echo "\nimages[".$i++."] = ".$image['url'];
			}
		break;
		case 'json':
			if($full){
				echo json_encode($images);
			}else{
				$i = array();
				foreach($images as $image)
				{
					$i[] = $image['url'];
				}
				echo json_encode($i);
			}

			break;
	endswitch;
endif;