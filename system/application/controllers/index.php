<?php

Class IndexController extends Controller{

	private $deps;

	public function __construct($deps)
	{
		parent::__construct($deps);

		$this->_api = new SocialModel(array('db'=>$deps['db']));

		$this->deps = $deps;
	}

	public function indexAction(){

		$this->viewParams['twitter'] = $this->_api->getTwitterTweet();

		$this->viewParams['blog']['url'] = $this->_api->getBlogUrl();
		$this->viewParams['blog']['title'] = $this->_api->getBlogTitle();

		$this->viewParams['lastfm']['url'] = $this->_api->getLastfmTrackImage();
		$this->viewParams['lastfm']['caption'] = $this->_api->getLastfmTrack();

		$this->viewParams['instagram']['url'] = $this->_api->getInstagramLatestImage();
		$this->viewParams['instagram']['caption'] = $this->_api->getInstagramLatestCaption();

		$this->viewParams['github'] = '';

		if($this->_api->getGithubCommitMessage() != '')
		{
			$this->viewParams['github']  = $this->_api->getGithubCommitMessage().' (';
			$this->viewParams['github'] .= $this->_api->getGithubCommitter('username').')';
		}

		$sounds = array();
		if(is_dir(PUBLIC_PATH.'/audio'))
		{
			$files = scandir(PUBLIC_PATH.'/audio');
			foreach($files as $file){
				if(substr($file,-4) == '.mp3'){
					$id = trim(str_replace(' ','_',basename($file,'.mp3')));
					$sounds[$id]['mp3'] = '/audio/'.basename($file);					
				}
				if(substr($file,-4) == '.ogg'){
					$id = trim(str_replace(' ','_',basename($file,'.ogg')));
					$sounds[$id]['ogg'] = '/audio/'.basename($file);				
				}
			}
		}

		$this->viewParams['sounds'] = $sounds;

	}
}