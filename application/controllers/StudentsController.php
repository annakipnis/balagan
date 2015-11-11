<?php

/**
 * This controller handle the teacher website
 *
 * @author M_AbuAjaj
 */
class StudentsController extends Zend_Controller_Action
{
    
    function init()
    {
        if( !Zend_Auth::getInstance()->hasIdentity() )
        {
            $this->_redirect('/');
        }
        $this->user = Zend_Auth::getInstance()->getStorage()->read();
        
        #Layout
        $this->_helper->layout->setLayout('layout');
        $this->config = Zend_Registry::get('config');
        $this->msger  = $this->_helper->getHelper('FlashMessenger');
        $this->view->flashmsgs = $this->msger->getMessages();
        $this->lang   = Zend_Registry::get('lang');
        date_default_timezone_set('Asia/Tel_Aviv');
    }
    
    /*
     * Author : M_AbuAjaj
     * Date   : 06/11/2014
     */
    public function indexAction(){
        #Get All Students
        $student_DB = new Application_Model_DbTable_Student();
        $students   = $student_DB->getAllInGan( $this->user->gan_id );
        $this->view->students = $students;
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 03/03/2015
     * get Student
     */
    public function getAction(){
        if( $this->getRequest()->isGet() ){
            $student_id = $this->_request->getParam('s');
            $student_DB = new Application_Model_DbTable_Student();
            $student = $student_DB->get($student_id);
            $results = array();
            foreach ($student as $row) {
                if( $row['grade'] && $row['target'] && $row['create_date'] ){
                    $results[] = $row;
                }
            }
            
            if( $student && isset($student[0]) && isset($student[0]['student']) ) {
                $this->view->student_name = $student[0]['student'];
            }
            
            $this->view->student = $results;
        }
    }
    
}