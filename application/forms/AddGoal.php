<?php

 use Zend\Form\Form;

class Application_Form_AddGoal extends Zend_Form {

    protected $_goalname = null;
    public function setGoalname($goalname){
        $this->_goalname = $goalname;
    }
    protected $_goallevel = null;
    public function setGoallevel($goallevel){
        $this->_goallevel = $goallevel;
    }
    protected $_grade1 = null;
    public function setGrade1($grade1){
        $this->_grade1 = $grade1;
    }
    protected $_grade2 = null;
    public function setGrade2($grade2){
        $this->_grade2 = $grade2;
    }
    protected $_grade3 = null;
    public function setGrade3($grade3){
        $this->_grade3 = $grade3;
    }
    protected $_grade4 = null;
    public function setGrade4($grade4){
        $this->_grade4 = $grade4;
    }
 public function init(){
    $lang = Zend_Registry::get('lang');
    $this->setMethod('post');
    $this->setName('addgoal_form');        
    if($this->_goalname != NULL){
        $this->setAction($this->_getUrl('admin', 'updategoal')); 
    } else {
        $this->setAction($this->_getUrl('admin', 'savegoal')); 
    }
    $this->setAttrib('lang', $lang); 
    $this->setAttrib('enctype', 'application/x-www-form-urlencoded');
    $this->setDecorators(array(
        array('ViewScript', array('viewScript' => 'admin/addgoal.phtml'),'Form')
    ));
    
    $goalName = $this->createElement('text', 'goalName', array('class' => 'form-element', 'placeholder' => $lang->_('GOAL_NAME')));
    $goalName->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));
    
    if($this->_goalname != NULL){
        $goalName->setValue($this->_goalname);
    }

    $goalLevel = $this->createElement('text', 'goalLevel', array('class' => 'form-element', 'placeholder' => $lang->_('GOAL_LEVEL')));
    $goalLevel->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));
    if($this->_goallevel != NULL){
        $goalLevel->setValue($this->_goallevel);
    }
    
    $grade1 = $this->createElement('text', 'grade1', array('class' => 'form-element', 'placeholder' => $lang->_('GRADE1')));
    $grade1->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));
    
    if($this->_grade1 != NULL){
        $grade1->setValue($this->_grade1);
    }
    $grade2 = $this->createElement('text', 'grade2', array('class' => 'form-element', 'placeholder' => $lang->_('GRADE2')));
    $grade2->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));
    
    if($this->_grade2 != NULL){
        $grade2->setValue($this->_grade2);
    }
    
    $grade3 = $this->createElement('text', 'grade3', array('class' => 'form-element', 'placeholder' => $lang->_('GRADE3')));
    $grade3->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));
    
    if($this->_grade3 != NULL){
        $grade3->setValue($this->_grade3);
    }
    
    $grade4 = $this->createElement('text', 'grade4', array('class' => 'form-element', 'placeholder' => $lang->_('GRADE4')));
    $grade4->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));
    
    if($this->_grade4 != NULL){
        $grade4->setValue($this->_grade4);
    }

    $submit = $this->createElement('submit', 'submit', array('class' => 'btn btn-finish', 'label' => $lang->_('FINISH')));

    $this->addElements( array(
        $goalName,
        $goalLevel,
        $grade1,
        $grade2,
        $grade3,
        $grade4,
        $submit)
    );

    parent::init();
  }
  
private function _getUrl ($controller, $action) {
    $url = new Zend_View_Helper_Url();
    return $url->url(array('controller'=> $controller,'action'=>$action),'default');
}
}
