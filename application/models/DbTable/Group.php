<?php

class Application_Model_DbTable_Group extends Zend_Db_Table_Abstract
{
    protected $_name = 'groups';
    
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/2015
     * Get All Groups of Gan
     */
    public function getAll( $ganID ){
        $sql = "
            SELECT g.*, GROUP_CONCAT(s.name SEPARATOR ', ') as students
            FROM `$this->_name` as g
            LEFT JOIN `students` as s
            ON(g.groupID = s.groupID)
            WHERE g.ganID = $ganID
            GROUP BY g.groupID";
        
        return $this->_db->fetchAll($sql);
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/2015
     * Get Group
     */
    public function get( $groupID ){
        $sql = "
            SELECT g.*, GROUP_CONCAT(s.name SEPARATOR ', ') as students
            FROM `$this->_name` as g
            LEFT JOIN `students` as s
            ON(g.groupID = s.groupID)
            WHERE g.groupID = $groupID";
        
        return $this->_db->fetchRow($sql);
    }
   
}
