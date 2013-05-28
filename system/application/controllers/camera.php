<?php

Class CameraController extends Controller
{
	public function __construct($deps)
	{
		parent::__construct($deps);
	}

	public function indexAction(){

	}

	public function postAction()
	{
		$this->defaultViewType = 'plain';

		if($_FILES && !empty($_FILES))
		{
			$temporary 		= $_FILES['filedata']['tmp_name'];
			$destination 	= PUBLIC_PATH.'webcam.jpg';

			move_uploaded_file($temporary, $destination);

			$success 	= false;
			$archived 	= false;

			if(file_exists($destination) && filesize($destination) > 0)
			{
				$success = true;

				$archive = PUBLIC_PATH.'webcam'.DIR_SEP;
				$archive .= date('Y').DIR_SEP.date('m').DIR_SEP.date('d');
				$archive .= DIR_SEP.'video0-'.date('Ymd-Hi').'.jpg';

				$archive_path = dirname($archive);
				if(!is_dir($archive_path))
				{
					mkdir($archive_path,0755,TRUE);
				}

				if(copy($destination, $archive))
				{
					$archived = true;
				}
			}

			$insert = array(
				'filename' 	=> basename($archive),
				'path' 		=> $archive,
				'hash' 		=> md5(readfile($destination)),
				'size' 		=> $_FILES['filedata']['size'],
				'mime_type' => $_FILES['filedata']['type'],
				'uploaded' 	=> $success?'1':'0',
				'archived'	=> $archived?'1':'0'
				);

			$this->_db->insert('webcam_images',$insert);

			$this->viewParams['success'] = $success;
		}
	}

	public function listAction()
	{

		$this->defaultViewType = 'plain';

		$date = date('Y-m-d');

		$request = Util('Request');

		if(isset($request->params['date']) && $request->params['date'])
		{
			$dt = new DateTime($request->params['date']);
			$date = $dt->format('Y-m-d');
		}

		$date_path = implode(DIR_SEP,explode('-',$date));
		$image_path = PUBLIC_PATH.DIR_SEP.'webcam'.DIR_SEP.$date_path;

		if(!is_dir($image_path))
		{
			return false;
		}

		$files = scandir($image_path);
		if($files && is_array($files))
		{
			$images = array();
			foreach($files as $file)
			{
				if($file == '.' || $file == '..')
					continue;

				if(count($images) > 500)
					continue;

				if(substr($file,-4,4) == '.jpg')
					$images[] = $file;
			}

			if(!empty($images))
			{
				$format = 'javascript';
				if(isset($request->params['format']) && $request->params['format'])
				{
					$format = $request->params['format'];
				}

				switch($format)
				{
					case 'json':
						$paths = array();
						foreach($images as $image)
						{
							$paths[] = DIR_SEP.'webcam'.DIR_SEP.$date_path.DIR_SEP.$image;
						}

						$this->defaultViewType = 'json';
						$this->viewParams['format'] = 'json';
						$this->viewParams['images'] = $paths;
					break;
					case 'javascript':
					default:
						$this->defaultViewType = 'javascript';
						$this->viewParams['format'] = 'javascript';
						$this->viewParams['images'] =  $images;
						$this->viewParams['date_path'] = $date_path;
					break;
				}
			}
		}
	}
}