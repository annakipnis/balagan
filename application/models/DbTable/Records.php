<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Application_Model_DbTable_Records extends Zend_Db_Table_Abstract
{
    protected $_name = 'records';

    public function getAll($groupID, $gameID){
        $sql = "
            SELECT r.*, g.value as grade_value
            FROM `$this->_name` as r
            LEFT JOIN `grades` as g
            ON(r.gradeID = g.gradeID)
            WHERE r.groupID = $groupID AND r.gameID = $gameID";
        
        return $this->_db->fetchAll($sql);
    }
    
//    public function insertRecord ($studentID ,$gameID ,$gradeID ,$date ,$groupID) {
//        $sql = "
//            INSERT INTO `$this->_name` (studentID, gameID, gradeID, date, groupID)
//            VALUES ($studentID ,$gameID ,$gradeID ,$date ,$groupID)
//            ON DUPLICATE KEY UPDATE gradeID=$gradeID, date=$date";
//        
//        $statment = $this->_db->query($sql);
//        $statment->execute();
//    }
    
    
}

