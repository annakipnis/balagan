<?php

class Application_Model_DbTable_StudentsInField extends Zend_Db_Table_Abstract
{
    protected $_name = 'studentsinfield';
    
   
    public function getAll( $groupID = 0 ){
        $sql = "
            SELECT *, name as studentName
            FROM $this->_name as sf
            LEFT JOIN `students` as s
            ON (sf.studentID = s.studentID)";
        if( $groupID ){
           $sql .= "WHERE sf.groupID = $groupID "; 
        }
        $sql .= "ORDER BY name ASC";
        
        return $this->_db->fetchAll($sql);
    }
    
    public function getAllGroupless($ganID, $fieldID){
        $sql = "
            SELECT *, name as studentName
            FROM $this->_name as sf
            LEFT JOIN `students` as s
            ON (sf.studentID = s.studentID)
            WHERE sf.groupID IS NULL
            AND s.ganID = $ganID
            AND sf.fieldID = $fieldID
            OR sf.fieldID IS NULL
            ORDER BY name ASC";
        
        return $this->_db->fetchAll($sql);
    }

    /* all students in gan */
    public function getAllInGan($ganID, $fieldID){
        $sql = "
            SELECT *,s.name as studentName
            FROM $this->_name as sf
            LEFT JOIN `students` as s
            ON (sf.studentID = s.studentID)
            WHERE s.ganID = $ganID 
            AND sf.fieldID = $fieldID
            ORDER BY s.name ASC";
        
        return $this->_db->fetchAll($sql);
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 03/03/15
     * get all games and grades of a student (התקדמות אישית)
     */
    public function get( $studentID, $fieldID){
        $sql = "
            SELECT s.name as student, r.date, gr.name as grade, go.name as goal
            FROM $this->_name as sf
            LEFT JOIN `records` as r
            ON(sf.id = r.studentinfieldID)
            LEFT JOIN `games` as g
            ON(r.gameID = g.gameID)
            LEFT JOIN `goals` as go
            ON(g.goalID = go.goalID)
            LEFT JOIN `grades` as gr
            ON(r.gradeID = gr.gradeID)
            LEFT JOIN `students` as s
            ON(sf.studentID = s.studentID)
            WHERE s.studentID = $studentID
            AND go.fieldID = $fieldID
            ORDER BY r.date DESC";
        
        return $this->_db->fetchAll($sql);
    }
    
    public function getRecords( $studentID, $fieldID){
        $sql = "
            SELECT s.name as student, r.date, gr.name as grade, go.name as goal
            FROM $this->_name as sf
            LEFT JOIN `records` as r
            ON(sf.id = r.studentinfieldID)
            LEFT JOIN `games` as g
            ON(r.gameID = g.gameID)
            LEFT JOIN `goals` as go
            ON(g.goalID = go.goalID)
            LEFT JOIN `grades` as gr
            ON(r.gradeID = gr.gradeID)
            LEFT JOIN `students` as s
            ON(sf.studentID = s.studentID)
            WHERE s.studentID = $studentID
            AND go.fieldID = $fieldID
            ORDER BY r.date ASC";
        
        return $this->_db->fetchAll($sql);
    }
}
