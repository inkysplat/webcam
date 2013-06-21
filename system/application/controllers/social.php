<?php

Class SocialController extends Controller
{
	public function __construct($deps)
	{
		$this->defaultViewType = 'json';
		
		parent::__construct($deps);
	}

	public function fetchAction($service = ''){

		$this->defaultViewType = 'json';

		if($service != '')
		{
			if(substr($service,-5) == '.json')
			{
				$service = substr($service,0,-5);
			}

			$this->viewParams['data'] = $this->_model->getApiData($service);
		}else{
			$this->viewParams['data'] = $this->_model->getAllApiData();
		}
		
	}

	public function messagesAction()
	{
		$this->defaultViewType = 'json';

		$services = array('lastfm','twitter','github','blog','instagram');

		foreach($services as $service)
		{
			$this->viewParams['ajax'][$service]['msg'] = $this->_model->getMessage($service);
			switch($service){
				case 'instagram':
					$this->viewParams['ajax'][$service]['caption'] = $this->_model->getInstagramLatestCaption();
					break;
				case 'lastfm':
					$this->viewParams['ajax'][$service]['img'] = $this->_model->getLastfmTrackImage();
					break;
				case 'blog':
					$this->viewParams['ajax'][$service]['url'] = $this->_model->getBlogUrl();
					break;
			}
		}
	}

	public function githubServiceHookAction()
	{
		$this->defaultViewType = 'plain';

		$request = Util('Request');
		$config = App('Config');

		if($request->post('payload'))
		{
			$payload = stripslashes($request->post('payload'));

			$config->ini('api');
			$gh = $config->get('github');

			$cache = Util('Cache');
			$cache->writeRaw($gh['cache_file'],$payload);

			$decoded = json_decode($payload,true);

			$cache->setCacheFilename($gh['cache_file']);
			$cache->setCache('github', $decoded);
			$cache->writeCache();

			$message = $decoded['commits'][0]['message'].' ('.
				$decoded['commits'][0]['author']['username'].')';

			$this->_model->touchCache(md5($payload), $message, 'github');
		}
	}
}

