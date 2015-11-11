<?php

class Application_Model_DbTable_Student extends Zend_Db_Table_Abstract
{
    protected $_name = 'student';
    
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
     * 
     */
    public function getAll( $group_id = 0 ){
        $sql = "
            SELECT *
            FROM $this->_name ";
        if( $group_id ){
           $sql .= "WHERE group_id = $group_id "; 
        }
        $sql .= "ORDER BY name ASC";
        
        return $this->_db->fetchAll($sql);
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/15
     * 
     */
    public function getAllInGan( $gan_id ){
        $sql = "
            SELECT *
            FROM $this->_name 
            WHERE gan_id = $gan_id 
            ORDER BY name ASC";
        
        return $this->_db->fetchAll($sql);
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 03/03/15
     */
    public function get( $student_id ){
        $sql = "
            SELECT s.name as student, d.create_date, gr.name as grade, tr.name as target
            FROM $this->_name as s
            LEFT JOIN `documentation` as d
            ON(s.student_id = d.student_id)
            LEFT JOIN `target` as tr
            ON(d.target_id = tr.target_id)
            LEFT JOIN `grade` as gr
            ON(d.grade_id = gr.grade_id)
            WHERE s.student_id = $student_id";
        
        return $this->_db->fetchAll($sql);
    }
}
