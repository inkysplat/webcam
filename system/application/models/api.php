<?php

Class ApiModel extends Model
{

	private $date = array();

	/**
	 * Constructor
	 * @param array $deps [description]
	 */
	public function __construct($deps)
	{
		parent::__construct($deps);

		$this->_loadAPI();
	}

	/**
	 * Lazy load and update the APIs
	 * 
	 * @access private
	 * @return void
	 */
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
				//call the end point
				$data[$name] = $this->_callEndPoint($a['endpoint']);

				//write to the cache file
				$cache->setCache($name,$data[$name],$a['timetolive']);
				$cache->writeCache();

				//save a raw version of the file also
				$cache->writeRaw($a['cache_file'], json_encode($data[$name]));

				//update the timestamp
				$this->_updateTimestamp(md5($file), $name);
			}
		}

		$this->data = $data;
	}

	/**
	 * Getter method for the API Data
	 * 
	 * @param  string $service [description]
	 * @return array          [description]
	 */
	public function getApiData($service)
	{
		if(isset($this->data[$service]))
		{
			return $this->data[$service];
		}

		return false;
	}

	/**
	 * Format the latest LastFM track
	 * 
	 * @return string [description]
	 */
	public function getLastfmTrack()
	{
		if(isset($this->data['lastfm']))
		{
			if(isset($this->data['lastfm']['recenttracks']['track']['artist']))
			{
				$artist = $this->data['lastfm']['recenttracks']['track']['artist']['#text'];
			}else{
				if(isset($this->data['lastfm']['recenttracks']['track'][0]['artist']))
				{
					$artist = $this->data['lastfm']['recenttracks']['track'][0]['artist']['#text'];
				}
			}

			if(isset($this->data['lastfm']['recenttracks']['track']['name']))
			{
				$track = $this->data['lastfm']['recenttracks']['track']['name'];
			}else{
				if(isset($this->data['lastfm']['recenttracks']['track'][0]['name']))
				{
					$track = $this->data['lastfm']['recenttracks']['track'][0]['name'];
				}
			}

			return $artist.' - '.$track;
		}

		return '';
	}

	/**
	 * Format the latest tweet
	 * @return string [description]
	 */
	public function getTwitterTweet()
	{
		if(isset($this->data['twitter']))
		{
			if(isset($this->data['twitter'][0]['text']))
			{
				return $this->data['twitter'][0]['text'];
			}
		}

		return '';
	}

	/**
	 * Save a new time stamp and hash of the cache file to database
	 * 
	 * @param  string $hash [description]
	 * @param  string $name [description]
	 * @return void
	 */
	private function _updateTimestamp($hash, $name)
	{
		$this->_db->update('api_cache', 
				array(
					'hash'=>$hash,
					'datetime'=>date('Y-m-d H:i:s')), 
				array('api_name' => $name));

	}

	/**
	 * Get the API endpoint and JSON decode
	 * 
	 * @param  string $url [description]
	 * @return  array      [description]
	 */
	private function _callEndPoint($url)
	{
		$file = file_get_contents($url);
		return json_decode($file,true);
	}
}