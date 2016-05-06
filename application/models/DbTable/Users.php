<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{
    protected $_name = 'users';
    
    public function get( $userID ){
        $sql = "
            SELECT *
            FROM $this->_name 
            WHERE userID = $userID";
        
        return $this->_db->fetchRow($sql);
    }
    
    public function getUserByGan( $ganID ){
        $sql = "
            SELECT *
            FROM $this->_name as u
            LEFT JOIN `gan` as g
            ON (u.ganID = g.ganID)
            WHERE g.ganID = $ganID";
        
        return $this->_db->fetchAll($sql);
    }

    public function getUserInfo($username){
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        return $this->_db->fetchRow('SELECT SQL_CACHE * FROM '.$this->_name.' WHERE `email` = "'.$username.'"');
    }

    public function isExist($username){
        return $this->_db->fetchRow('SELECT SQL_CACHE email FROM '.$this->_name.' WHERE `email` = "'.$username.'"');
    }
    
    public function isAdmin( $username ){
        $sql = "
            SELECT *
            FROM $this->_name 
            WHERE email = '$username' AND isAdmin = 1";
        return $this->_db->fetchRow($sql);
    }
    
    public function getRole ($username) {
        $sql = "
            SELECT r.name as 'roleName'
            FROM $this->_name as u
            LEFT JOIN `roles` as r
            ON (u.roleID = r.roleID)
            WHERE email = '$username'";
        return $this->_db->fetchOne($sql);
    }
   
}
