<?php

 use Zend\Form\Form;

class Application_Form_AddStudent extends Zend_Form {
    
    protected $_studentName = null;
    public function setStudentname($studentName){
        $this->_studentName = $studentName;
    }
    protected $_studentLastName = null;
    public function setStudentlastname($studentLastName){
        $this->_studentLastName = $studentLastName;
    }
    protected $_gender = null;
    public function setGender($gender){
        $this->_gender = $gender;
    }
    protected $_birthDate = null;
    public function setBirthdate($birthDate){
        $this->_birthDate = $birthDate;
    }
    protected $_fatherName = null;
    public function setFathername($fatherName){
        $this->_fatherName = $fatherName;
    }
    protected $_fatherPhone = null;
    public function setFatherphone($fatherPhone){
        $this->_fatherPhone = $fatherPhone;
    }
    protected $_motherName = null;
    public function setMothername($motherName){
        $this->_motherName = $motherName;
    }
    protected $_motherPhone = null;
    public function setMotherphone($motherPhone){
        $this->_motherPhone = $motherPhone;
    }
    protected $_idNumber = null;
    public function setIdnumber($idNumber){
        $this->_idNumber = $idNumber;
    }
 public function init(){
    $lang = Zend_Registry::get('lang');
    $this->setMethod('post');
    $this->setName('add_form');        
    if($this->_studentName != NULL){
        $this->setAction($this->_getUrl('managestudents', 'update')); 
    } else {
        $this->setAction($this->_getUrl('managestudents', 'save')); 
    }
    $this->setAttrib('lang', $lang); 
    $this->setAttrib('enctype', 'application/x-www-form-urlencoded');
    $this->setDecorators(array(
        array('ViewScript', array('viewScript' => 'managestudents/add.phtml'),'Form')
    ));

    $studentName = $this->createElement('text', 'studentName', array('class' => 'form-element', 'placeholder' => $lang->_('STUDENTNAME')));
    $studentName->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));
    if($this->_studentName != NULL){
        $studentName->setValue($this->_studentName);
    }
    
    $studentLastName = $this->createElement('text', 'studentLastName', array('class' => 'form-element', 'placeholder' => $lang->_('STUDENTLASTNAME')));
    if($this->_studentLastName != NULL){
        $studentLastName->setValue($this->_studentLastName);
    }
    $gender = $this->createElement('select', 'gender', array('class' => 'form-element', 'label' => $lang->_('GENDER')));
    $gender->addMultiOptions(array(
        'Male' => $lang->_('MALE'),
        'Female' => $lang->_('FEMALE'),
    ));
    $gender->setRequired(true)
             ->addErrorMessage($lang->_('REQUIRED_FIELD'));
    if($this->_gender != NULL){
        $gender->setValue($this->_gender);
    }
    
    $birthDate = $this->createElement('text', 'birthDate', array('class' => 'form-element', 'placeholder' => $lang->_('BIRTHDATE')));
    $birthDate->addValidator('Date',  TRUE  )
              ->addErrorMessage($lang->_('INVALID_DATE'));
    $birthDate->setRequired(true)->addErrorMessage($lang->_('REQUIRED_FIELD'));
    
    if($this->_birthDate != NULL){
        $birthDate->setValue($this->_birthDate);
    }
    $fatherName = $this->createElement('text', 'fatherName', array('class' => 'form-element', 'placeholder' => $lang->_('FATHERNAME')));
    if($this->_fatherName != NULL){
        $fatherName->setValue($this->_fatherName);
    }
    $fatherPhone = $this->createElement('text', 'fatherPhone', array('class' => 'form-element', 'placeholder' => $lang->_('FATHERPHONE')));
    if($this->_fatherPhone != NULL){
        $fatherPhone->setValue($this->_fatherPhone);
    }
    $motherName = $this->createElement('text', 'motherName', array('class' => 'form-element', 'placeholder' => $lang->_('MOTHERNAME')));
    if($this->_motherName != NULL){
        $motherName->setValue($this->_motherName);
    }
    $motherPhone = $this->createElement('text', 'motherPhone', array('class' => 'form-element', 'placeholder' => $lang->_('MOTHERPHONE')));
    if($this->_motherPhone != NULL){
        $motherPhone->setValue($this->_motherPhone);
    }
    $idNumber = $this->createElement('text', 'idNumber', array('class' => 'form-element', 'placeholder' => $lang->_('STUDENTIDNUMBER')));
    if($this->_idNumber != NULL){
        $idNumber->setValue($this->_idNumber);
    }
    $submit = $this->createElement('submit', 'submit', array('class' => 'btn btn-finish', 'label' => $lang->_('FINISH')));

    $this->addElements( array(
        $studentName,
        $studentLastName,
        $gender,
        $birthDate,
        $motherName,
        $motherPhone,
        $fatherName,
        $fatherPhone,
        $idNumber,
        $submit)
    );

    parent::init();
  }
  
private function _getUrl ($controller, $action) {
    $url = new Zend_View_Helper_Url();
    return $url->url(array('controller'=> $controller,'action'=>$action),'default');
}
}
