<?php

Class IndexController extends Controller{

	public function __construct($deps)
	{
		parent::__construct($deps);

		$this->_api = new ApiModel(array('db'=>$deps['db']));
	}

	public function indexAction(){
		$this->viewParams['refresh'] = true;
		$this->viewParams['lastfm'] = $this->_api->getLastfmTrack();
		$this->viewParams['twitter'] = $this->_api->getTwitterTweet();

	}

	public function apiAction($service = ''){

		$data = $this->_loadAPI();

		if($service != '')
		{
			if(substr($service,-5) == '.json')
			{
				$service = substr($service,0,-5);
			}

			$this->viewParams['data'] = $data[$service];
		}else{
			$this->viewParams['data'] = $data;
		}

		$this->defaultViewType = 'json';
	}

}