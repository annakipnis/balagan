<?php

class Application_Model_DbTable_Grade extends Zend_Db_Table_Abstract
{
    protected $_name = 'grade';
    
    /*
     * Author : M_AbuAjaj
     * Date   : 13/11/14
     * insert new lesson
     */
//    public function add($lesson){
//        try{
//            $lesson_id = $this->insert($lesson);
//        } catch (Zend_Exception $x){
//            die( json_encode( array('status'=> 'error' , 'msg' => $x) ) );
//        }
//        return $lesson_id;
//    }
    /*
     * Author : M_AbuAjaj
     * Date   : 25/11/14
     * update lesson
     */
//    public function edit($lesson){
//        $where = $this->getAdapter()->quoteInto('lesson_id = ?', $lesson['lesson_id']);
//        try{
//            $this->update($lesson, $where);
//        } catch (Zend_Exception $x){
//            die( json_encode( array('status'=> 'error' , 'msg' => $x) ) );
//        }
//    }
    
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
           $sql .= " WHERE target_id = $target_id AND level_id = $level_id "; 
        }
        $sql .= " ORDER BY grade_id ASC ";
        
        return $this->_db->fetchAll($sql);
    }
   
}
