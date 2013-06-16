<?php

Class SocialModel extends Model
{

	private $data = array();

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


		$api = array('lastfm','twitter','github','instagram','blog');

		foreach($api as $name)
		{
			//get config
			$a = $config->get($name);
			//set cache file
			$cache->setCacheFilename($a['cache_file']);

			$this->data[$name] = $cache->getCache($name);

			//empty data
			if(!$this->data[$name])
			{
				if(isset($a['endpoint']) && !empty($a['endpoint']))
				{
					//call the end point
					$this->data[$name] = $this->_callEndPoint($name,$a['endpoint']);

					//write to the cache file
					$cache->setCache($name,$this->data[$name],$a['timetolive']);
					$cache->writeCache();

					$encoded = json_encode($this->data[$name]);
					//save a raw version of the file also
					$cache->writeRaw($a['cache_file'], $encoded);

					$message = $this->getMessage($name);

					//update the timestamp
					$this->touchCache(md5($encoded), $message, $name);
				}
			}
		}
	}

	public function getMessage($service)
	{
		switch($service)
		{
			case 'twitter':
				return $this->getTwitterTweet();
				break;
			case 'lastfm':
				return $this->getLastfmTrack();
				break;
			case 'github':
				return $this->getGithubCommitMessage().' ('.$this->getGithubCommitter('username').')';
				break;
			case 'instagram':
				return $this->getInstagramLatestImage();
				break;
			case 'blog':
				return $this->getBlogTitle();
				break;
			default:
				return 'service-not-configured';
				break;
		}
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
	 * Returns all the API Data sofar
	 * @return array
	 */
	public function getAllApiData()
	{
		return $this->data;
	}

	public function getBlogTitle()
	{
		if(isset($this->data['blog']))
		{
			if(isset($this->data['blog']['channel']['item'][0]['title']))
			{
				return utf8_encode($this->data['blog']['channel']['item'][0]['title']);
			}
		}
	}

	public function getBlogUrl()
	{
		if(isset($this->data['blog']))
		{
			if(isset($this->data['blog']['channel']['item'][0]['link']))
			{
				return $this->data['blog']['channel']['item'][0]['link'];
			}
		}
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

	public function getLastfmTrackImage()
	{
		$show = '';
		if(isset($this->data['lastfm']))
		{
			if(isset($this->data['lastfm']['recenttracks']['track']['image']))
			{
				$images = $this->data['lastfm']['recenttracks']['track']['image'];
				foreach($images as $image)
				{
					if($image['size'] == 'extralarge' && $image['#text'] != '')
					{
						$show = $image['#text'];
					}else{
						if($show == '' && $image['size'] == 'large' && $image['#text'] != '')
						{
							$show = $image['#text'];
						}
					}
				}
			}
		}

		if($show == '')
		{
			$show = 'http://userserve-ak.last.fm/serve/300x300/80529101.png';
		}

		return $show;
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

	public function getGithubCommitter($field = '')
	{
		if(isset($this->data['github']))
		{
			if(isset($this->data['github']['commits'][0]['author']))
			{
				$author = $this->data['github']['commits'][0]['author'];

				if(isset($author[$field]))
				{
					return $author[$field];
				}

				return $author;
			}
		}
	}

	public function getGithubCommitMessage()
	{
		if(isset($this->data['github']))
		{
			if(isset($this->data['github']['commits'][0]['message']))
			{
				return $this->data['github']['commits'][0]['message'];
			}
		}
	}

	public function getGithubCommitRepo()
	{
		if(isset($this->data['github']))
		{
			if(isset($this->data['github']['repository']['name']))
			{
				return $this->data['github']['repository']['name'];
			}
		}
	}

	public function getInstagramLatestImage($image_size = 'low_resolution')
	{
		if(isset($this->data['instagram']))
		{
			if(isset($this->data['instagram']['data'][0]['images'][$image_size]['url']))
			{
				return $this->data['instagram']['data'][0]['images'][$image_size]['url'];
			}
		}
	}

	public function getInstagramLatestCaption()
	{
		if(isset($this->data['instagram']))
		{
			if(isset($this->data['instagram']['data'][0]['caption']['text']))
			{
				return $this->data['instagram']['data'][0]['caption']['text'];
			}
		}
	}


	/**
	 * Save a new time stamp and hash of the cache file to database
	 * 
	 * @param  string $hash [description]
	 * @param  string $name [description]
	 * @return void
	 */
	public function touchCache($hash, $message, $name)
	{
		$this->_db->update('social_current', 
				array(
					'hash'=>$hash,
					'message' => $message,
					'datetime'=>date('Y-m-d H:i:s')), 
				array('api_name' => $name));
	}

	/**
	 * Get the API endpoint and JSON decode
	 * 
	 * @param  string $url [description]
	 * @return  array      [description]
	 */
	private function _callEndPoint($service, $url)
	{
		switch($service)
		{
			case 'twitter':
				$config = App('Config');
				$config->ini('api');
				$settings = $config->get('twitter');

				$params = array(
					'consumer_key'=>$settings['consumer_key'],
					'consumer_secret'=>$settings['consumer_secret'],
					'oauth_access_token_secret'=>$settings['access_secret'],
					'oauth_access_token'=>$settings['access_token']);

				$twitter = new Twitter($params);

				$feed = $twitter->setGetfield($settings['get_fields'])
		             ->buildOauth($url, 'GET')
		             ->performRequest();

		        return json_decode(trim($feed),true);

			break;
			default:
				$file = file_get_contents($url);
				return json_decode($file,true);
			break;
		}		
	}
}