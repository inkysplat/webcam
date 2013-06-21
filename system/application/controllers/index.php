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

	}
}