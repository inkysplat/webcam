<?php

Class CameraModel extends Model
{

	public function __construct($deps)
	{
		parent::__construct($deps);
	}

	/**
	 * Get a list of the latest image from the database
	 * 
	 * @return array [description]
	 */
	public function getLatestImage()
	{
		//this query is the definative way to get the latest
		$sql = "SELECT * FROM snapshots WHERE datetime = (".
					"SELECT MAX(datetime) AS datetime FROM snapshots".
				") AND uploaded=1 AND archived=1 ORDER BY datetime";

		$rs = $this->_db->fetchAll($sql);

		if(count($rs) == 1 && isset($rs[0]['path']))
		{
			return $rs[0];
		}

		return $rs;
	}

	/**
	 * Get a list of images from the database
	 * 
	 * @param  string  $date  [description]
	 * @param  integer $limit [description]
	 * @return array
	 */
	public function getListOfImages($date, $limit = 0)
	{
		$sql = "SELECT * FROM snapshots ".
				"WHERE DATE(datetime) = ? AND uploaded=1 AND archived=1 ".
				"ORDER BY datetime DESC ";
		$bind = array($date);

		if($limit > 0)
		{
			$sql .= " LIMIT ".(int)$limit;
		}

		$images = $this->_db->fetchAll($sql,$bind);

		if($images)
		{
			return $images;
		}

		return false;

	}

	/**
	 * Turn a date into a path
	 * 
	 * @param  string $date [description]
	 * @return string
	 */
	public function datePath($date)
	{
		return implode(DIR_SEP,explode('-',$date));
	}

	/**
	 * Get a list of file from the filesystem
	 * 
	 * @param  string $date [description]
	 * @return array
	 */
	public function getListOfRawImages($date)
	{
		$date_path = $this->datePath($date);
		$image_path = PUBLIC_PATH.DIR_SEP.'webcam'.DIR_SEP.$date_path;

		if(!is_dir($image_path))
		{
			return false;
		}

		$files = scandir($image_path);
		if($files && is_array($files))
		{
			$images = array();
			foreach($files as $file)
			{
				if($file == '.' || $file == '..')
					continue;

				if(count($images) > 500)
					continue;

				if(substr($file,-4,4) == '.jpg')
					$images[] = $file;
			}
		}

		$paths = array();
		foreach($images as $image)
		{
			$paths[] = DIR_SEP.'webcam'.DIR_SEP.$date_path.DIR_SEP.$image;
		}

		return $paths;
	}

	/**
	 * Insert a new image record into the database
	 * 
	 * @param array $params [description]
	 */
	public function addSnapshot($params)
	{
		$this->_db->insert('snapshots',$params);
	}
}