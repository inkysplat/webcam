<?php

/**
 * Description of Dbsession
 *
 * @class Dbsession
 */
class Dbsession
{

    private $_db = null;

    private $_table = 'sessions';

    /**
     * Default Constructor...
     *
     * @return void
     */
    public function __construct($_db)
    {
        if (!($_db instanceof Dba))
        {
            if (!($_db instanceof PDO))
            {
                throw new Exception(__METHOD__ . "Session handler expecting PDO object");
            }
        }

        if ($this->_db == null)
        {
            $this->_db = $_db;
        }

        return;
    }

    public function open($path, $id)
    {

        $limit = time() - (3600 * 24);
        $sql = <<<SQL
        DELETE FROM {$this->_table} WHERE timestamp < {$limit};
SQL;
        $r = $this->_db->query($sql);

        return $r !== false?true:false;
    }

    public function read($id)
    {
        $id     = $this->_db->quote($id);
        $sql    = "SELECT data FROM {$this->_table} where session_id ={$id}";
        $result = $this->_db->query($sql);
        if ($result)
        {
            $data = $result->fetchColumn();
            return $data;
        }
        return '';

    }

    public function write($id, $data)
    {
        $id   = $this->_db->quote($id);
        $data = $this->_db->quote($data);

        $sql  = <<<SQL
        INSERT INTO {$this->_table} SET session_id ={$id}, data ={$data}
        ON DUPLICATE KEY UPDATE data ={$data}
SQL;
        $r = $this->_db->query($sql);
        return $r !== false?true:false;
    }

    public function destroy($id)
    {
        $id  = $this->_db->quote($id);
        $sql = "DELETE FROM {$this->_table} WHERE session_id ={$id}";
        $r   = $this->_db->query($sql);
        if ($r)
        {
            setcookie(session_name(), "", time() - 3600);
            return true;
        }
        return false;
    }

    public function close()
    {
        return;
    }

    public function gc($lifetime)
    {
        $lifetime = $this->_db->quote($lifetime);
        $sql      = <<<SQL
        DELETE FROM {$this->_table} WHERE timestamp <
            DATE_SUB(NOW(), INTERVAL {$lifetime} SECOND)
SQL;
        $r = $this->_db->query($sql);
        return $r !== false?true:false;
    }

}

