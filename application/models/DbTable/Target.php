<?php

class Application_Model_DbTable_Target extends Zend_Db_Table_Abstract
{
    protected $_name = 'target';
    
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
            ORDER BY name ASC";
        return $this->_db->fetchAll($sql);
    }
   
}
