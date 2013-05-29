<?php
if(isset($format)):
	switch($format):
		case 'javascript':
			$i = 0;
			echo "\nvar images = new Array();";
			foreach($images as $image)
			{
				echo "\nimages[".$i++."] = '".DIR_SEP.'webcam'.DIR_SEP.$date_path.DIR_SEP.$image."'";
			}
		break;
		case 'json':
			echo json_encode($images);
			break;
	endswitch;
endif;