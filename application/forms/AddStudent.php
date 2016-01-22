<?php

 use Zend\Form\Form;

class Application_Form_AddStudent extends Zend_Form {
    
 public function init(){
    $lang = Zend_Registry::get('lang');
    $this->setMethod('post');
    $this->setName('add_form');        
    $this->setAction($this->_getUrl('managestudents', 'save')); 
    $this->setAttrib('lang', $lang); 
    $this->setAttrib('enctype', 'application/x-www-form-urlencoded');
    $this->setDecorators(array(
        array('ViewScript', array('viewScript' => 'managestudents/add.phtml'),'Form')
    ));

    $studentName = $this->createElement('text', 'studentName', array('class' => 'form-element', 'placeholder' => $lang->_('STUDENTNAME')));
    $studentName->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));

    $gender = $this->createElement('select', 'gender', array('class' => 'form-element', 'label' => $lang->_('GENDER')));
    $gender->addMultiOptions(array(
        'Male' => $lang->_('MALE'),
        'Female' => $lang->_('FEMALE'),
    ));
    $gender->setRequired(true)
             ->addErrorMessage($lang->_('REQUIRED_FIELD'));
    
    $birthDate = $this->createElement('text', 'birthDate', array('class' => 'form-element', 'placeholder' => $lang->_('BIRTHDATE')));
    $birthDate->addValidator('Date',  TRUE  )
              ->addErrorMessage($lang->_('INVALID_DATE'));
    $birthDate->setRequired(true)->addErrorMessage($lang->_('REQUIRED_FIELD'));


    $submit = $this->createElement('submit', 'submit', array('class' => 'btn btn-finish', 'label' => $lang->_('FINISH')));

    $this->addElements( array(
        $studentName,
        $gender,
        $birthDate,
        $submit)
    );

    parent::init();
  }
  
private function _getUrl ($controller, $action) {
    $url = new Zend_View_Helper_Url();
    return $url->url(array('controller'=> $controller,'action'=>$action),'default');
}
}
