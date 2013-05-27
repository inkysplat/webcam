<?php

Class IndexController extends Controller{

	public function indexAction(){
		$this->viewParams['refresh'] = true;

		$api = $this->_loadAPI();

		$this->viewParams['lastfm'] = $api['lastfm'];
		$this->viewParams['twitter'] = $api['twitter'];
	}

	public function apiAction($service = ''){

		$data = $this->_loadAPI();

		$this->viewParams['data'] = $data[$service];
		$this->defaultViewType = 'json';
	}

	private function _loadAPI()
	{
		$config = App('Config');
		$cache = Util('Cache');

		$config->ini('api');
		$data = array();

		$lastfm = $config->get('lastfm');
		$cache->setCacheFilename($lastfm['cache_file']);

		if(!($data['lastfm'] = $cache->getCache('lastfm')))
		{
			$file = file_get_contents($lastfm['endpoint']);
			$data['lastfm'] = json_decode($file,true);

			$cache->setCache('lastfm',$data['lastfm'],$lastfm['timetolive']);
			$cache->writeCache();

			$cache->writeRaw($lastfm['cache_file'], $file);
		}

		$twitter = $config->get('twitter');
		$cache->setCacheFilename($twitter['cache_file']);

		if(!($data['twitter'] = $cache->getCache('twitter')))
		{
			$file = file_get_contents($twitter['endpoint']);
			$data['twitter'] = json_decode($file,true);

			$cache->setCache('twitter',$data['twitter'],$twitter['timetolive']);
			$cache->writeCache();

			$cache->writeRaw($twitter['cache_file'],$file);
		}

		return $data;

	}
}