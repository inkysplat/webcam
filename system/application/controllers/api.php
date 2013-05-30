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

		if($request->post('payload'))
		{
			$payload = $request->post('payload');

			$cache = Util('Cache');

			$cache->writeRaw('github.json',$payload);

			$cache->setCacheFilename('github.json');
			$cache->setCache('github',json_decode($payload));
			$cache->writeCache();

		}
	}
}




