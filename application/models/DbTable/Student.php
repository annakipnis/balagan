<?php

class Application_Model_DbTable_Student extends Zend_Db_Table_Abstract
{
    protected $_name = 'students';
    
    /*
     * Author : M_AbuAjaj
     * Date   : 16/12/14
     * insert new Homework
     */
//    public function add( $homework ){
//        try{
//            $homework_id = $this->insert($homework);
//        } catch (Zend_Exception $x){
//            die( json_encode( array('status'=> 'error' , 'msg' => $x) ) );
//        }
//        return $homework_id;
//    }
    /*
     * Author : M_AbuAjaj
     * Date   : 16/12/14
     * update homework
     */
//    public function edit( $homework ){
//        $where = $this->getAdapter()->quoteInto('homework_id = ?', $homework['homework_id']);
//        try{
//            $this->update($homework, $where);
//        } catch (Zend_Exception $x){
//            die( json_encode( array('status'=> 'error' , 'msg' => $x) ) );
//        }
//    }
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/15
     * get all students from a group
     */
//    public function getAll( $groupID = 0 ){
//        $sql = "
//            SELECT *, name as studentName
//            FROM $this->_name ";
//        if( $groupID ){
//           $sql .= "WHERE groupID = $groupID "; 
//        }
//        $sql .= "ORDER BY name ASC";
//        
//        return $this->_db->fetchAll($sql);
//    }
    
//    public function getAllGroupless($ganID){
//        $sql = "
//            SELECT *, name as studentName
//            FROM $this->_name 
//            WHERE groupID IS NULL
//            AND ganID = $ganID
//            ORDER BY name ASC";
//        
//        return $this->_db->fetchAll($sql);
//    }
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/15
     * get all student from a gan
     */
    public function getAllInGan( $ganID ){
        $sql = "
            SELECT *,s.name as studentName
            FROM $this->_name as s
            WHERE s.ganID = $ganID 
            ORDER BY s.name ASC";
        
        return $this->_db->fetchAll($sql);
    }
    
    public function getID($studentID, $fieldID){
        $sql = "
            SELECT id
            FROM $this->_name as s
            LEFT JOIN `studentsinfield` as sf
            ON(s.studentID = sf.studentID)
            WHERE s.studentID = $studentID AND sf.fieldID = $fieldID";
        
        return $this->_db->fetchOne($sql);
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 03/03/15
     * get all games and grades of a student (התקדמות אישית)
     */
//    public function get( $studentID, $fieldID){
//        $sql = "
//            SELECT s.name as student, r.date, gr.name as grade, go.name as goal
//            FROM $this->_name as s
//            LEFT JOIN `records` as r
//            ON(s.studentID = r.studentID)
//            LEFT JOIN `games` as g
//            ON(r.gameID = g.gameID)
//            LEFT JOIN `goals` as go
//            ON(g.goalID = go.goalID)
//            LEFT JOIN `grades` as gr
//            ON(r.gradeID = gr.gradeID)
//            WHERE s.studentID = $studentID
//            AND go.fieldID = $fieldID
//            ORDER BY r.date DESC";
//        
//        return $this->_db->fetchAll($sql);
//    }
    
//    public function getRecords( $studentID, $fieldID){
//        $sql = "
//            SELECT s.name as student, r.date, gr.name as grade, go.name as goal
//            FROM $this->_name as s
//            LEFT JOIN `records` as r
//            ON(s.studentID = r.studentID)
//            LEFT JOIN `games` as g
//            ON(r.gameID = g.gameID)
//            LEFT JOIN `goals` as go
//            ON(g.goalID = go.goalID)
//            LEFT JOIN `grades` as gr
//            ON(r.gradeID = gr.gradeID)
//            WHERE s.studentID = $studentID
//            AND go.fieldID = $fieldID
//            ORDER BY r.date ASC";
//        
//        return $this->_db->fetchAll($sql);
//    }
}
