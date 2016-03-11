<?php

/**
 * This controller handles the management of groups and students in group
 *
 */
class ManagestudentsController extends Zend_Controller_Action
{
    
    function init()
    {
        
        if( !Zend_Auth::getInstance()->hasIdentity() )
        {
            $this->_redirect('/');
        }
        
        $user = Zend_Auth::getInstance()->getStorage()->read();
        
        #GROUPS
        $student_DB = new Application_Model_DbTable_Student();
        $students = $student_DB->getAllInGan ($user->ganID);
        #VIEWS
        $this->view->students = $students;
        
        
        #Layout
        $this->_helper->layout->setLayout('layout');
        $this->config = Zend_Registry::get('config');
        #SEO:
        $this->view->title = $this->view->lang->_('SITE_TITLE');
        $this->view->sitedesc = $this->view->lang->_('SITE_DESC');
        $this->view->sitekeywords = $this->view->lang->_('SITE_KEYWORDS');
        #MSG
        $this->msger = $this->_helper->getHelper('FlashMessenger');
        $this->view->flashmsgs = $this->msger->getMessages();
        $this->lang = Zend_Registry::get('lang');
    }
    
    
    public function indexAction(){

    }
   
    public function addAction(){
        $form = new Application_Form_AddStudent();        
        $this->view->form = $form;       
    }
    
    public function saveAction () {
        $user = Zend_Auth::getInstance()->getStorage()->read();

        $request = $this->getRequest();
        $student_data = $request->getPost();
        
        $studentName = trim($student_data['studentName']);
        $gender = trim($student_data['gender']);
        $birthDate = trim($student_data['birthDate']);

        if ( !strlen($studentName) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_STUDENTNAME').'</div>');
            $this->_redirect('/managestudents/add');
        }
        if ( !strlen($gender) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GENDER').'</div>');
            $this->_redirect('/managestudents/add');
        }
        if ( !strlen($birthDate) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_BIRTHDATE').'</div>');
            $this->_redirect('/managestudents/add');
        }
        
        $student_DB = new Application_Model_DbTable_Student();
        $new_student = array(
            'ganID'      => $user->ganID,
            'name'       => $student_data['studentName'],
            'gender'     => $student_data['gender'],
            'birthDate'  => $student_data['birthDate']
        );
        try{
            $student_id = $student_DB->insert( $new_student );
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $student_data['birthDate']) ) );
        }
        $this->_redirect("/managestudents");
    }
    
    public function deletestudentAction () {
        $studentID = $this->_request->getParam('s');
        $student_DB = new Application_Model_DbTable_Student();
        
        $student_DB->delete("studentID = $studentID");
        
        $this->_redirect("/managestudents");
    }
    
    public function deleteAction () {
        $user = Zend_Auth::getInstance()->getStorage()->read();
        
        $student_DB = new Application_Model_DbTable_Student();
        $students = $student_DB->getAllInGan ($user->ganID);
        #VIEWS
        $this->view->students = $students;
    }
}