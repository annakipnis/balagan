<?php

 use Zend\Form\Form;

class Application_Form_AddGoal extends Zend_Form {
    
 public function init(){
    $lang = Zend_Registry::get('lang');
    $this->setMethod('post');
    $this->setName('addgoal_form');        
    $this->setAction($this->_getUrl('admin', 'savegoal')); 
    $this->setAttrib('lang', $lang); 
    $this->setAttrib('enctype', 'application/x-www-form-urlencoded');
    $this->setDecorators(array(
        array('ViewScript', array('viewScript' => 'admin/addgoal.phtml'),'Form')
    ));

    $goalName = $this->createElement('text', 'goalName', array('class' => 'form-element', 'placeholder' => $lang->_('GOAL_NAME')));
    $goalName->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));

    $goalLevel = $this->createElement('text', 'goalLevel', array('class' => 'form-element', 'placeholder' => $lang->_('GOAL_LEVEL')));
    $goalLevel->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));

    $submit = $this->createElement('submit', 'submit', array('class' => 'btn btn-finish', 'label' => $lang->_('FINISH')));

    $this->addElements( array(
        $goalName,
        $goalLevel,
        $submit)
    );

    parent::init();
  }
  
private function _getUrl ($controller, $action) {
    $url = new Zend_View_Helper_Url();
    return $url->url(array('controller'=> $controller,'action'=>$action),'default');
}
}
