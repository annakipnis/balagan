<?php

class Application_Model_DbTable_Group extends Zend_Db_Table_Abstract
{
    protected $_name = 'group';
    
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/2015
     * Get All Groups of Gan
     */
    public function getAll( $gan_id ){
        $sql = "
            SELECT g.*, GROUP_CONCAT(s.name SEPARATOR ', ') as students
            FROM `$this->_name` as g
            LEFT JOIN `student` as s
            ON(g.group_id = s.group_id)
            WHERE g.gan_id = $gan_id
            GROUP BY g.group_id";
        
        return $this->_db->fetchAll($sql);
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/2015
     * Get Group
     */
    public function get( $group_id ){
        $sql = "
            SELECT g.*, GROUP_CONCAT(s.name SEPARATOR ', ') as students
            FROM `$this->_name` as g
            LEFT JOIN `student` as s
            ON(g.group_id = s.group_id)
            WHERE g.group_id = $group_id";
        
        return $this->_db->fetchRow($sql);
    }
   
}
