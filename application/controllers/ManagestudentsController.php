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
        $this->view->userRole = $_SESSION['Default']['role'];

        
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
            'lastName'   => $student_data['studentLastName'],
            'gender'     => $student_data['gender'],
            'birthDate'  => $student_data['birthDate'],
            'fatherName' => $student_data['fatherName'],
            'fatherPhone'=> $student_data['fatherPhone'],
            'motherName' => $student_data['motherName'],
            'motherPhone'=> $student_data['motherPhone'],
            'idNumber'   => $student_data['idNumber']
        );
        try{
            $student_id = $student_DB->insert( $new_student );
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
        }
        $fields_DB = new Application_Model_DbTable_Field ();
        $fields = $fields_DB->getAll();
        $studentinfield_DB = new Application_Model_DbTable_StudentsInField();
        foreach ($fields as $f) {
            $new_studentinfield = array('studentID' => $student_id, 'fieldID' => $f['fieldID']);
            try{
                $id = $studentinfield_DB->insert( $new_studentinfield );
            } catch (Exception $ex) {
                die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
            }
        }
        $this->_redirect("/managestudents");
    }
    
    public function updateAction () {
        $studentID = $this->_request->getParam('s');
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
        $updated_student = array(
            'ganID'      => $user->ganID,
            'name'       => $student_data['studentName'],
            'lastName'   => $student_data['studentLastName'],
            'gender'     => $student_data['gender'],
            'birthDate'  => $student_data['birthDate'],
            'fatherName' => $student_data['fatherName'],
            'fatherPhone'=> $student_data['fatherPhone'],
            'motherName' => $student_data['motherName'],
            'motherPhone'=> $student_data['motherPhone'],
            'idNumber'   => $student_data['idNumber']
        );
        try{
            $where = $student_DB->getAdapter()->quoteInto('studentID = ?', $studentID);
            $student_DB->update($updated_student, $where);
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
        }
        $this->_redirect("/managestudents/student/s/".$studentID);
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
    
    public function studentAction () {
        $studentID = $this->_request->getParam('s');
        $student_DB = new Application_Model_DbTable_Student();
        $student = $student_DB->get($studentID);

        $this->view->studentName = $student['name'];
        $this->view->studentLastName = $student['lastName'];
        if ($student['gender'] == 'Male') {
            $this->view->studentGender = $this->lang->_('MALE');
        } else {
            $this->view->studentGender = $this->lang->_('FEMALE');
        }
        $this->view->studentBirthDate = $student['birthDate'];
        $this->view->fatherName = $student['fatherName'];
        $this->view->motherName = $student['motherName'];
        $this->view->fatherPhone = $student['fatherPhone'];
        $this->view->motherPhone = $student['motherPhone'];
        $this->view->idNumber = $student['idNumber'];

        $this->view->studentID = $studentID;
    }
    
    public function editstudentAction () {
        $studentID = $this->_request->getParam('s');
        $student_DB = new Application_Model_DbTable_Student();
        $student = $student_DB->get($studentID);
        
        $form = new Application_Form_AddStudent (array('studentname' => $student['name'],
                                                        'studentlastname' => $student['lastName'],
                                                        'gender' => $student['gender'],
                                                        'birthdate' => $student['birthDate'],
                                                        'fathername' => $student['fatherName'],
                                                        'fatherphone' => $student['fatherPhone'],
                                                        'mothername' => $student['motherName'],
                                                        'motherphone' => $student['motherPhone'],
                                                        'idnumber' => $student['idNumber']));        
            
        $this->view->form = $form; 
    }
    
    public function reportAction () {
        $student_id = $this->_request->getParam('s');
        $_SESSION['Default']['report'] = true;
        
        $student_DB = new Application_Model_DbTable_StudentsInField();
        $student = $student_DB->getRecordsForAllFields($student_id);
        $results = array();
        foreach ($student as $row) {
            if( $row['grade'] && $row['goal'] && $row['date'] && $row['fieldName'] ){
                $results[] = $row;
            }
        }
        if( $student && isset($student[0]) && isset($student[0]['student']) ) {
            $this->view->student_name = $student[0]['student'];
        }
        
        $this->view->records = $results;
    }
}