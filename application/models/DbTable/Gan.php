<?php

class Application_Model_DbTable_Gan extends Zend_Db_Table_Abstract
{
    protected $_name = 'gan';
    
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/15
     * 
     */
//    public function add($answer){
//        $answer_id = 0;
//        try{
//            $answer_id = $this->insert($answer);
//        } catch (Zend_Exception $x){
//            die( json_encode( array('status'=> 'error' , 'msg' => $x) ) );
//        }
//        return $answer_id;
//    }
    /*
     * Author : M_AbuAjaj
     * Date   : 16/12/14
     * Update answer with new data (array)
     */
//    public function edit($answer_id, $new_data){
//        $where = $this->getAdapter()->quoteInto('answer_id = ?', $answer_id);
//        try{
//            $this->update($new_data, $where);
//        } catch (Zend_Exception $x){
//            die( json_encode( array('status'=> 'error' , 'msg' => $x) ) );
//        }
//    }
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/15
     */
//    public function getAll($homework_id = 0){
//        $sql = "
//            SELECT *
//            FROM $this->_name ";
//        if( $homework_id ){
//            $sql .= " WHERE homework_id = $homework_id";
//        }
//        return $this->_db->fetchAll($sql);
//    }
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/15
     * Get Gan Info
     */
    public function get( $ganID ){
        $sql = "
            SELECT *
            FROM $this->_name 
            WHERE ganID = $ganID";
        
        return $this->_db->fetchRow($sql);
    }
    
    public function getAll () {
        $sql = "
            SELECT *
            FROM `$this->_name`";
        
        return $this->_db->fetchAll($sql);
    }
       
    public function getGanName($ganID) {
        $sql = "
            SELECT name
            FROM `$this->_name`
            WHERE ganID = $ganID";
        
        return $this->_db->fetchOne($sql);
    }
}
