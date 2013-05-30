<?php

Class ApiController extends Controller
{
	public function __construct($deps)
	{
		parent::__construct($deps);
	}

	public function githubServiceHookAction()
	{
		$request = Util('Request');

		if($request->post('payload') || $request->params['payload'])
		{
			if(isset($request->post('payload')) && $request->post('payload') != '')
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