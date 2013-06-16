<?php

Class IndexController extends Controller{

	public function __construct($deps)
	{
		parent::__construct($deps);

		$this->_api = new SocialModel(array('db'=>$deps['db']));
	}

	public function indexAction(){
		$this->viewParams['lastfm'] = $this->_api->getLastfmTrack();
		$this->viewParams['twitter'] = $this->_api->getTwitterTweet();

		$this->viewParams['instagram']['url'] = $this->_api->getInstagramLatestImage();
		$this->viewParams['instagram']['caption'] = $this->_api->getInstagramLatestCaption();

		if($this->_api->getGithubCommitMessage() != '')
		{
			$this->viewParams['github']  = $this->_api->getGithubCommitMessage().' (';
			$this->viewParams['github'] .= $this->_api->getGithubCommitter('username').')';
		}

	}

}