<?php

class Application_Model_DbTable_Target extends Zend_Db_Table_Abstract
{
    protected $_name = 'goals';
    
    /*
     * Author : M_AbuAjaj
     * Date   : 13/11/14
     * insert new Task
     */
//    public function add($task){
//        $task_id = 0;
//        try{
//            $task_id = $this->insert($task);
//        } catch (Zend_Exception $x){
//            die( json_encode( array('status'=> 'error' , 'msg' => $x) ) );
//        }
//        return $task_id;
//    }
    /*
     * Author : M_AbuAjaj
     * Date   : 16/11/14
     * Update task with new data (array)
     */
//    public function edit($task_id, $new_data){
//        $where = $this->getAdapter()->quoteInto('task_id = ?', $task_id);
//        try{
//            $this->update($new_data, $where);
//        } catch (Zend_Exception $x){
//            die( json_encode( array('status'=> 'error' , 'msg' => $x) ) );
//        }
//    }
    /*
     * Author : M_AbuAjaj
     * Date   : 26/12/14
     * Delete task 
     */
//    public function del($task_id){
//        $where = $this->getAdapter()->quoteInto('task_id = ?', $task_id);
//        try{
//            $this->delete($where);
//        } catch (Zend_Exception $x){
//            die( json_encode( array('status'=> 'error' , 'msg' => $x) ) );
//        }
//    }
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/15
     * Get All Targets
     */
    public function getAll(){
        $sql = "
            SELECT *
            FROM $this->_name 
            WHERE goalID_parent IS NULL
            ORDER BY name ASC";
        return $this->_db->fetchAll($sql);
    }
   
    public function getAllSubGoals ($targetID){
        $sql = "
            SELECT *
            FROM $this->_name 
            WHERE goalID_parent	= $targetID
            ORDER BY goalID ASC";
        return $this->_db->fetchAll($sql);
    }
    
    public function getGoalLevel ($goalID) {
        $sql = "
            SELECT level
            FROM $this->_name 
            WHERE goalID = $goalID";
        return $this->_db->fetchAll($sql);
    }
    
    //רמות
    public function getLevels () {
        $sql = "SELECT *
                FROM $this->_name 
                WHERE goalID_parent IS NOT NULL
                GROUP BY level";
        return $this->_db->fetchAll($sql);
    }
    
    public function getAllByLevel ($level) {
        $sql = "
            SELECT goalID
            FROM $this->_name 
            WHERE level	= $level
            ORDER BY goalID ASC";
        return $this->_db->fetchRow($sql);
    }
    
    //all unlearned goal in level - don't have 
    public function getUnlearnedInLevel ($level, $groupID) {
        $sql = "
            SELECT *
            FROM $this->_name as g
            LEFT JOIN `games` as gm
            ON(g.goalID = gm.goalID)
            LEFT JOIN `records` as r
            ON(r.gameID = gm.gameID)
            WHERE g.level = $level
            AND r.groupID = $groupID
            AND r.gameID IS NULL";
        
        return $this->_db->fetchAll($sql);
    }
}
