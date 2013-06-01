<?php

Class CameraController extends Controller
{
	public $defaultViewType = 'json';

	public function __construct($deps)
	{
		parent::__construct($deps);
		$this->_setParams();
	}

	public function indexAction(){

	}

	public function latestAction()
	{
		$rs = $this->_model->getLatestImage();

		$rs['url'] = $this->_setUrlPath($rs);

		$this->defaultViewType = $this->format;
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

				$url = str_replace(PUBLIC_PATH,SITE_URL,$archive);
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

			$this->_model->addSnapshot($insert);

			$this->viewParams['success'] = $success;
		}
	}

	private function _setParams()
	{
		$request = Util('Request');

		$date = date('Y-m-d');
		if(isset($request->params['date']) && $request->params['date'])
		{
			$dt = new DateTime($request->params['date']);
			$date = $dt->format('Y-m-d');
		}

		$this->date = $date;

		$limit = 0;
		if(isset($request->params['limit']) && is_numeric($request->params['limit']))
		{
			$limit = $request->params['limit'];
		}

		$this->limit = $limit;

		$format = $this->defaultViewType;
		if(isset($request->params['format']) && $request->params['format'])
		{
			$format = $request->params['format'];
		}

		$this->format = $format;
	}

	public function listAction()
	{
		$request = Util('Request');

		$images = $this->_model->getListOfImages($this->date, $this->limit);

		if(!empty($images))
		{
			foreach($images as &$img)
			{
				$img['url'] = $this->_setUrlPath($img);
			}

			$this->defaultViewType = $this->format;
			$this->viewParams['format'] = $this->format;
			$this->viewParams['images'] = $images;
			$this->viewParams['full'] = false;
			if(isset($request->params['full']) && $request->params['full'])
			{
				$this->viewParams['full'] = true;
			}

			if($this->format == 'xml')
			{
				$this->viewParams['xml'] = View::renderXML($images);
			}
		}

	}

	private function _setUrlPath($url)
	{
		if($url['url'] == null || $url['url'] == '')
		{

			if(defined('PUBLIC_PATH'))
			{
				$url['url'] = str_replace(PUBLIC_PATH, '',$url['path']);
			}

			if(strpos('/home/webcam/public_html',$url['url']) !== false)
			{
				$url['url'] = str_replace('/home/webcam/public_html','', $url['url']);
			}
		}

		return $url['url'];
	}

	public function rawlistAction()
	{

		$images = $this->_model->getListOfRawImages($this->date);

		if(!empty($images))
		{
			$this->defaultViewType = $this->format;
			$this->viewParams['format'] = $this->format;
			$this->viewParams['images'] = $images;
		}
	}
}