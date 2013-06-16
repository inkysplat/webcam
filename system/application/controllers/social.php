<?php

Class SocialController extends Controller
{
	public function __construct($deps)
	{
		parent::__construct($deps);
	}

	public function fetchAction($service = ''){

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

		$this->defaultViewType = 'json';
	}

	public function messagesAction()
	{
		$this->defaultViewType = 'json';

		$this->viewParams['ajax']['lastfm'] = $this->_model->getLastfmTrack();
		$this->viewParams['ajax']['twitter'] = $this->_model->getTwitterTweet();

		if($this->_model->getGithubCommitMessage() != '')
		{
			$this->viewParams['ajax']['github']  = $this->_model->getGithubCommitMessage().' (';
			$this->viewParams['ajax']['github'] .= $this->_model->getGithubCommitter('username').')';
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

