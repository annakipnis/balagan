<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Application_Model_DbTable_Planing extends Zend_Db_Table_Abstract
{
    protected $_name = 'plans';

    public function getAll($planID){
        $sql = "
            SELECT p.*, g.name as game_name
            FROM `$this->_name` as p
            LEFT JOIN `games` as g
            ON(p.gameID = g.gameID)
            WHERE p.planID = $planID";
        
        return $this->_db->fetchAll($sql);
    }
    
     public function getLastPlan ($group_id) {
         $sql = "
            SELECT p.*, g.gameID as game_id, g.goalID as goal_id, g.name as game_name
            FROM `$this->_name` as p
            LEFT JOIN `games` as g
            ON(p.gameID = g.gameID)
            WHERE p.groupID = $group_id
            ORDER BY p.date DESC";
        
        return $this->_db->fetchAll($sql);
     }
     
     //just recommendations
     public function getByGroup ($group_id) {
         $sql = "
            SELECT p.*, g.name as game_name
            FROM `$this->_name` as p
            LEFT JOIN `games` as g
            ON(p.gameID = g.gameID)
            WHERE p.groupID = $group_id 
            AND p.relatedPlanID IS NOT NULL 
            ORDER BY p.date ASC";
        
        return $this->_db->fetchAll($sql);
     }
}

