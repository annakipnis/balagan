<?php

/**
 * This controller handles the management of groups and students in group
 *
 */
class ManagegroupsController extends Zend_Controller_Action
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
        if (isset ($_SESSION['Default']['field'])) {
            $groups   = $group_DB->getAll($user->ganID, $_SESSION['Default']['field']);
            if ($groups) {
                $this->view->groups = $groups;
            }
        } else {
            $this->view->error = true;
        }
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
        
        $this->view->userRole = $_SESSION['Default']['role'];
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
            $this->_redirect('/managegroups/add');
        }
        
        $group_DB = new Application_Model_DbTable_Group();
        $new_group = array(
            'name'       => $group_data['groupName'],
            'color'     => $group_data['color'],
            'ganID'     => $user->ganID,
            'fieldID'   => $_SESSION['Default']['field']
        );
        try{
            $group_id = $group_DB->insert( $new_group );
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
        }
        $this->_redirect("/managegroups");
    }
    
        public function editAction(){
            
        }
        
        public function groupAction () {
        $groupID = $this->_request->getParam('g');
        
        $student_DB = new Application_Model_DbTable_StudentsInField();
        $students = $student_DB->getAll ($groupID);

        $this->view->students = $students;
        $this->view->groupID = $groupID;
    }
    
    public function addtogroupAction () {
        $user = Zend_Auth::getInstance()->getStorage()->read();
        $ganID = $user->ganID;

        $groupID = $this->_request->getParam('g');
        
        $student_DB = new Application_Model_DbTable_StudentsInField();
        $students = $student_DB->getAllGroupless($ganID, $_SESSION['Default']['field']);

        $this->view->students = $students;
        $this->view->groupID = $groupID;
    }
    
    public function addstudentAction () {
        $studentID = $this->_request->getParam('s');
        $groupID = $this->_request->getParam('g');
        
        $students_DB = new Application_Model_DbTable_Student ();
        $id = $students_DB->getID ($studentID, $_SESSION['Default']['field']);
        $student_DB = new Application_Model_DbTable_StudentsInField();
        $data = array ( 'groupID' => $groupID );
        $student_DB->update($data, "id = $id");
        
        $this->_redirect("/managegroups/group/g/".$groupID);

    }
    
    public function deletestudentsAction () {
        $groupID = $this->_request->getParam('g');
        
        $student_DB = new Application_Model_DbTable_StudentsInField();
        $students = $student_DB->getAll ($groupID);

        $this->view->students = $students;
        $this->view->groupID = $groupID;
    }

    public function deletestudentAction () {
        $studentID = $this->_request->getParam('s');
        $groupID = $this->_request->getParam('g');
        
        $students_DB = new Application_Model_DbTable_Student ();
        $id = $students_DB->getID ($studentID, $_SESSION['Default']['field']);
        $student_DB = new Application_Model_DbTable_StudentsInField();
        $data = array ( 'groupID' => NULL );
        $student_DB->update($data, "id = $id");
        
        $this->_redirect("/managegroups");
    }
    
    public function deletegroupAction () {
        $groupID = $this->_request->getParam('g');
        
        $group_DB = new Application_Model_DbTable_Group();
        $student_DB = new Application_Model_DbTable_StudentsInField();
        $students = $student_DB->getAll($groupID);
        
        foreach ($students as $s) {
            $data = array ( 'groupID' => NULL );
            $studentID = $s['id'];
            $student_DB->update($data, "id = $studentID");
        }
        
        $group_DB->delete("groupID = $groupID");
        
        $this->_redirect("/managegroups");
    }
    
    public function importgroupsAction () {
        $fieldID = $this->_request->getParam('f');
        if ($fieldID) {
            $user = Zend_Auth::getInstance()->getStorage()->read();
            $groups_DB = new Application_Model_DbTable_Group();
            $students_DB = new Application_Model_DbTable_StudentsInField();
            $groups = $groups_DB->getAll($user->ganID, $fieldID);
            foreach ($groups as $g) {
                $new_group = array('name'    => $g['name'],
                                   'color'   => $g['color'],
                                   'ganID'   => $user->ganID,
                                   'fieldID' => $_SESSION['Default']['field']);
                try{
                    $group_id = $groups_DB->insert($new_group);
                } catch (Exception $ex) {
                    die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
                }
                $students = $students_DB->getAll($g['groupID']);
                foreach ($students as $s) {
                    $new_student = array('studentID' => $s['studentID'],
                                        'fieldID'    => $_SESSION['Default']['field'],
                                        'groupID'    => $group_id);
                    try{
                        $student_id = $students_DB->insert($new_student);
                    } catch (Exception $ex) {
                        die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
                    }
                }
            }
            $this->_redirect("/managegroups");
        } else {
            $fields_DB = new Application_Model_DbTable_Field();
            $fields   = $fields_DB->getAll();

            $this->view->fields = $fields;
            $this->view->fieldID = $_SESSION['Default']['field'];
        }
    }
}