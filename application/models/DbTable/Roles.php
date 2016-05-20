<?php

class Application_Model_DbTable_Roles extends Zend_Db_Table_Abstract
{
    protected $_name = 'roles';
    
    public function getRoleID ($roleName){
        $sql = "
            SELECT roleID
            FROM $this->_name 
            WHERE name = '$roleName'";
        
        return $this->_db->fetchOne($sql);
    }
}
