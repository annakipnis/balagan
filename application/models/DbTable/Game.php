<?php

class Application_Model_DbTable_Game extends Zend_Db_Table_Abstract
{
    protected $_name = 'game';
    
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/15
     * Get All Games (By target + level)
     */
    public function getAll( $target_id = 0, $level_id = 0 ){
        $sql = "
            SELECT *
            FROM $this->_name ";
        if( $target_id && $level_id ){
           $sql .= "WHERE target_id = $target_id AND level_id = $level_id "; 
        }
        $sql .= "ORDER BY name ASC";
        
        return $this->_db->fetchAll($sql);
    }
   
}
