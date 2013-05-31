<?php

Class ApiController extends Controller
{
	public function __construct($deps)
	{
		parent::__construct($deps);

		$this->_api = new ApiModel(array('db'=>$deps['db']));
	}

	public function githubServiceHookAction()
	{
		$request = Util('Request');
		$config = App('Config');

		if($request->post('payload'))
		{
			$payload = $request->post('payload');

			$config->ini('api');
			$gh = $config->get('github');

			$cache = Util('Cache');

			$cache->setCacheFilename($gh['cache_file']);

			$cache->setCache('github',json_decode($payload));
			$cache->writeCache();

			$cache->writeRaw($gh['cache_file'],$payload);

			$this->_api->touchApiCache(md5($payload),'github');

		}
	}
}




