<?php

class Application_Model_DbTable_Game extends Zend_Db_Table_Abstract
{
    protected $_name = 'games';
    
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/15
     * Get All Games (By target + level)
     */
    public function getAll( $fieldID, $goalID = 0){
        $sql = "
            SELECT *
            FROM $this->_name 
            WHERE active = '1'";
        if( $goalID ){
           $sql .= "AND goalID = $goalID "; 
        }
        $sql .= "AND fieldID = $fieldID
                ORDER BY name ASC";
        
        return $this->_db->fetchAll($sql);
    }
    
    public function getRandomGame($goalID){
        $sql = "
            SELECT *
            FROM $this->_name 
            WHERE active = '1'
            AND goalID = $goalID
            ORDER BY RAND() LIMIT 0,1";
        
        return $this->_db->fetchRow($sql);
    }
        
    public function getAllByLevel($goalLevel, $fieldID){
        $sql = "
           SELECT gm.*
            FROM $this->_name as gm
            LEFT JOIN `goals` as g
            ON (gm.goalID = g.goalID)
            WHERE g.level = $goalLevel
            ANd g.fieldID = $fieldID";

        return $this->_db->fetchAll($sql);
    }
    
    public function countGames ($goalID) {
        $sql = "
           SELECT COUNT(*)
            FROM $this->_name
            WHERE goalID = $goalID";

        return $this->_db->fetchAll($sql);
    }
    
    public function getGamesNotPlayed ($groupID, $goalID) {
        $sql = "
            SELECT *
            FROM `$this->_name`
            WHERE goalID = $goalID AND gameID NOT IN ( 
                SELECT g.gameID
                FROM `records` as r
                JOIN `$this->_name` as g
                ON(r.gameID = g.gameID)
                WHERE r.groupID = $groupID AND g.goalID = $goalID
                )";
        
        return $this->_db->fetchAll($sql);
    }
    
    public function getGoal ($gameID){
        $sql = "
            SELECT goalID
            FROM `$this->_name`
            WHERE gameID = $gameID";
        
        return $this->_db->fetchOne($sql);
    }
    
    public function getGameName($gameID) {
        $sql = "
            SELECT name
            FROM `$this->_name`
            WHERE gameID = $gameID";
        
        return $this->_db->fetchOne($sql);
    }
   
    public function getGoalName ($gameID) {
        $sql = "
            SELECT g.name
            FROM `$this->_name` as gm
            LEFT JOIN `goals` as g
            ON (gm.goalID = g.goalID)
            WHERE gameID = $gameID";
        
        return $this->_db->fetchOne($sql);
    }
}
