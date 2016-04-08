<?php

class Application_Model_DbTable_Field extends Zend_Db_Table_Abstract
{
    protected $_name = 'fields';

    public function getAll(){
        $sql = "
            SELECT *
            FROM `$this->_name`";
        
        return $this->_db->fetchAll($sql);
    }
    
    public function isExists ($fieldName) {
        $sql = "
            SELECT name
            FROM $this->_name 
            WHERE name REGEXP '$fieldName'";
        
        return $this->_db->fetchAll($sql);
    }
}
