<?php

/**
 * This controller handles the management of groups and students in group
 *
 */
class ManageGroupsController extends Zend_Controller_Action
{
    
    function init()
    {
        
        if( !Zend_Auth::getInstance()->hasIdentity() )
        {
            $this->_redirect('/');
        }
        
        $user = Zend_Auth::getInstance()->getStorage()->read();
        
        #GROUPS
        $group_DB = new Application_Model_DbTable_Group();
        $groups   = $group_DB->getAll($user->ganID);
        
        #VIEWS
        $this->view->groups = $groups;
        
        
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
        $form = new Application_Form_AddGroup();        
        $this->view->form = $form;       
    }

    public function saveAction () {
        $user = Zend_Auth::getInstance()->getStorage()->read();

        $request = $this->getRequest();
        $group_data = $request->getPost();
        
        $groupName = trim($group_data['groupName']);

        if ( !strlen($groupName) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GROUPNAME').'</div>');
            $this->_redirect('/manageGroups/add');
        }
        
        $group_DB = new Application_Model_DbTable_Group();
        $new_group = array(
            'name'       => $group_data['groupName'],
            'color'     => $group_data['color'],
            'ganID'     => $user->ganID
        );
        try{
            $group_id = $group_DB->insert( $new_group );
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $group_id) ) );
        }
        $this->_redirect("/manageGroups");
    }
    
        public function editAction(){
            
        }
        
        public function groupAction () {
        $groupID = $this->_request->getParam('g');
        
        $student_DB = new Application_Model_DbTable_Student();
        $students = $student_DB->getAll ($groupID);

        $this->view->students = $students;
        $this->view->groupID = $groupID;
    }
    
    public function addtogroupAction () {
        $user = Zend_Auth::getInstance()->getStorage()->read();
        $ganID = $user->ganID;

        $groupID = $this->_request->getParam('g');
        
        $student_DB = new Application_Model_DbTable_Student();
        $students = $student_DB->getAllGroupless($ganID);

        $this->view->students = $students;
        $this->view->groupID = $groupID;
    }
    
    public function addstudentAction () {
        $studentID = $this->_request->getParam('s');
        $groupID = $this->_request->getParam('g');
        
        $student_DB = new Application_Model_DbTable_Student();
        $data = array ( 'groupID' => $groupID );
        $student_DB->update($data, "studentID = $studentID");
        
        $this->_redirect("/manageGroups");

    }
    
    public function deletestudentAction () {
        $studentID = $this->_request->getParam('s');
        $groupID = $this->_request->getParam('g');
        
        $student_DB = new Application_Model_DbTable_Student();
        $data = array ( 'groupID' => NULL );
        $student_DB->update($data, "studentID = $studentID");
        
        $this->_redirect("/manageGroups");
    }
    
    public function deletegroupAction () {
        $groupID = $this->_request->getParam('g');
        
        $group_DB = new Application_Model_DbTable_Group();
        $student_DB = new Application_Model_DbTable_Student();
        $students = $student_DB->getAll($groupID);
        
        foreach ($students as $s) {
            $data = array ( 'groupID' => NULL );
            $studentID = $s['studentID'];
            $student_DB->update($data, "studentID = $studentID");
        }
        
        $group_DB->delete("groupID = $groupID");
        
        $this->_redirect("/manageGroups");
    }
}