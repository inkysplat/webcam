<?php

Class IndexController extends Controller{

	public function __construct($deps)
	{
		parent::__construct($deps);
	}

	public function indexAction(){
		$this->viewParams['refresh'] = true;

		$api = $this->_loadAPI();

		if(isset($api['lastfm']))
		{
			if(isset($api['lastfm']['recenttracks']['track']['artist']))
			{
				$artist = $api['lastfm']['recenttracks']['track']['artist']['#text'];
			}else{
				if(isset($api['lastfm']['recenttracks']['track'][0]['artist']))
				{
					$artist = $api['lastfm']['recenttracks']['track'][0]['artist']['#text'];
				}
			}

			if(isset($api['lastfm']['recenttracks']['track']['name']))
			{
				$track = $api['lastfm']['recenttracks']['track']['name'];
			}else{
				if(isset($api['lastfm']['recenttracks']['track'][0]['name']))
				{
					$track = $api['lastfm']['recenttracks']['track'][0]['name'];
				}
			}

			$this->viewParams['lastfm'] = $artist.' - '.$track;
		}

		if(isset($api['twitter']))
		{
			if(isset($api['twitter'][0]['text']))
			{
				$this->viewParams['twitter'] = $api['twitter'][0]['text'];
			}
		}

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

	private function _loadAPI()
	{
		$config = App('Config');
		$cache = Util('Cache');

		$config->ini('api');
		$data = array();

		$api = array('lastfm','twitter');

		foreach($api as $name)
		{
			//get config
			$a = $config->get($name);
			//set cache file
			$cache->setCacheFilename($a['cache_file']);

			//empty data
			if(!($data[$name] = $cache->getCache($name)))
			{
				$file = file_get_contents($a['endpoint']);
				$data[$name] = json_decode($file,true);

				$cache->setCache($name,$data[$name],$a['timetolive']);
				$cache->writeCache();

				$cache->writeRaw($a['cache_file'], $file);

				$this->_db->update('api_cache', 
					array(
						'hash'=>md5($file),
						'datetime'=>date('Y-m-d H:i:s')), 
					array('api_name' => $name));
			}
		}

		return $data;
	}
}