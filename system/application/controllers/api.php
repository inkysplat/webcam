<?php

Class ApiController extends Controller
{
	public function __construct($deps)
	{
		parent::__construct($deps);
	}

	public function githubServiceHookAction()
	{

		file_put_contents(CACHE_PATH.'/github.json',print_r(array('get'=>$_GET,'post'=>$_POST,'files'=>$_FILES),true));

		$request = Util('Request');

		if($request->post('payload') || isset($request->params['payload']))
		{
			if($request->post('payload') && $request->post('payload') != '')
			{
				$payload = $request->post('payload');
			}

			if(isset($request->params['payload']) || $request->params['payload'] != '')
			{
				$payload = $request->params['payload'];
			}

			$cache = Util('cache');

			$cache->setCacheFilename('github.json');
			$cache->setCache('github',json_decode($payload));
			$cache->writeCache();

			$cache->writeRaw('github.json',$payload);
		}
	}
}


