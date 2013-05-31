<?php

Class ApiController extends Controller
{
	public function __construct($deps)
	{
		parent::__construct($deps);

		//$this->_api = new ApiModel(array('db'=>$deps['db']));
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

			$this->_model->touchApiCache(md5($payload),'github');

			$decoded = json_decode($payload,true);

			$cache->setCacheFilename($gh['cache_file']);
			$cache->setCache('github', $decoded);
			$cache->writeCache();
		}
	}
}





