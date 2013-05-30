<?php

Abstract Class Model
{
	public function __construct($deps = array())
	{
		if(is_array($deps))
		{
			foreach($deps as $key=>$obj)
			{
				$key = '_'.$key;
				$this->$key = $obj;
			}
		}
	}
}