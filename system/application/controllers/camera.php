<?php

Class CameraController extends Controller
{
	public function __construct($deps)
	{
		parent::__construct($deps);
	}

	public function indexAction(){

	}

	public function latestAction()
	{
		//this query is the definative way to get the latest
		$sql = "SELECT * FROM webcam_images WHERE datetime = (".
					"SELECT MAX(datetime) AS datetime FROM webcam_images".
				") AND uploaded=1 AND archived=1 ORDER BY datetime";

		$rs = $this->_db->fetchAll($sql);

		$rs[0]['url'] = $this->_setUrlPath($rs[0]);

		$this->defaultViewType = 'json';
		$this->viewParams['latest'] = $rs;
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

			$archive 	= '';
			$url 		= '';

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

				$site_url = 'http://'.$_SERVER['HTTP_HOST'].'/';				
				$url = str_replace(PUBLIC_PATH,$site_url,$archive);
			}

			$insert = array(
				'filename' 	=> basename($archive),
				'path' 		=> $archive,
				'url'		=> $url,
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
		$this->defaultViewType = 'javascript';

		$request = Util('Request');

		$date = date('Y-m-d');

		if(isset($request->params['date']) && $request->params['date'])
		{
			$dt = new DateTime($request->params['date']);
			$date = $dt->format('Y-m-d');
		}

		$sql = "SELECT * FROM webcam_images ".
				"WHERE DATE(datetime) = ? AND uploaded=1 AND archived=1 ".
				"ORDER BY datetime DESC ";
		$bind = array($date);

		if(isset($request->params['limit']) && $request->params['limit'] > 0)
		{
			$sql .= " LIMIT ".(int)$request->params['limit'];
		}

		$images = $this->_db->fetchAll($sql,$bind);

		if(!empty($images))
		{
			$format = 'javascript';
			if(isset($request->params['format']) && $request->params['format'])
			{
				$format = $request->params['format'];
			}

			foreach($images as &$img)
			{
				$img['url'] = $this->_setUrlPath($img);
			}

			switch($format)
			{
				case 'json':
					$this->defaultViewType = 'json';
					$this->viewParams['format'] = 'json';
					$this->viewParams['images'] = $images;
					$this->viewParams['full'] = false;
					if(isset($request->params['full']) && $request->params['full'])
					{
						$this->viewParams['full'] = true;
					}
				break;
				case 'javascript':					

					$this->defaultViewType = 'javascript';
					$this->viewParams['format'] = 'javascript';
					$this->viewParams['images'] =  $images;
				break;
			}
		}

	}

	private function _setUrlPath($url)
	{
		if($url['url'] == null || $url['url'] == '')
		{
			$site_url = 'http://'.$_SERVER['HTTP_HOST'].'/';

			if(defined('PUBLIC_PATH'))
			{
				$url['url'] = str_replace(PUBLIC_PATH, $site_url,$url['path']);
			}

			if(strpos('/home/webcam/public_html',$url['url']) !== false)
			{
				$url['url'] = str_replace('/home/webcam/public_html',$site_url, $url['url']);
			}
		}

		return $url['url'];
	}

	public function rawlistAction()
	{

		$this->defaultViewType = 'javascript';

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