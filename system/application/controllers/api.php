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
			$payload = $request->post('payload');

			$config->ini('api');
			$gh = $config->get('github');

			$cache = Util('Cache');
			$cache->writeRaw($gh['cache_file'],$payload);

			$this->_model->touchApiCache(md5($payload),'github');

			$cache->setCacheFilename($gh['cache_file']);
			$cache->setCache('github', json_decode($payload,true));
			$cache->writeCache();

			file_put_contents('/tmp/github.log',print_r(json_decode($payload,true),true));

		}
	}
}





