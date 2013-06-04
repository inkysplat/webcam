<?php

Class VisitorModel extends Model
{

	/**
	 * Constructor
	 * @param array $deps [description]
	 */
	public function __construct($deps)
	{
		parent::__construct($deps);
	}

	public function addVisitor($params)
	{
		$this->_db->insert('visitors', $params);
	}

	public function latestVisitorCount($interval = 0)
	{
		$sql = "SELECT count(v.visitor_id) AS count FROM (SELECT visitor_id, MAX(datetime) AS datetime FROM visitors GROUP BY remote_addr) AS v";

		if($interval > 0){
			$interval .= ' MINUTE';
			$sql .= " WHERE v.datetime > DATE_SUB(NOW(), INTERVAL ".$interval." )";
		}

		return $this->_db->fetchAll($sql);

	}
}