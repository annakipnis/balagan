<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Application_Model_DbTable_Comments extends Zend_Db_Table_Abstract
{
    protected $_name = 'comments';

    public function getLast($groupID, $fieldID){
        $sql = "
            SELECT *
            FROM `$this->_name` 
            WHERE groupID = $groupID AND fieldID = $fieldID
            ORDER BY date DESC";
        
        return $this->_db->fetchRow($sql);
    }
}

