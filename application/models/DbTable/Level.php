<?php

class Application_Model_DbTable_Level extends Zend_Db_Table_Abstract
{
    protected $_name = 'level';
    
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/15
     * Get All Levels
     */
    public function getAll( $target_id = 0 ){
        $sql = "
            SELECT *
            FROM $this->_name ";
        if( $target_id ){
           $sql .= "WHERE target_id = $target_id "; 
        }
        $sql .= "ORDER BY level_id ASC";
        
        return $this->_db->fetchAll($sql);
    }
   
}
