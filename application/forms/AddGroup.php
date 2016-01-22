<?php

 use Zend\Form\Form;

class Application_Form_AddGroup extends Zend_Form {
    
 public function init(){
    $lang = Zend_Registry::get('lang');
    $this->setMethod('post');
    $this->setName('addgroup_form');        
    $this->setAction($this->_getUrl('managegroups', 'save')); 
    $this->setAttrib('lang', $lang); 
    $this->setAttrib('enctype', 'application/x-www-form-urlencoded');
    $this->setDecorators(array(
        array('ViewScript', array('viewScript' => 'managegroups/add.phtml'),'Form')
    ));

    $groupName = $this->createElement('text', 'groupName', array('class' => 'form-element', 'placeholder' => $lang->_('GROUPNAME')));
    $groupName->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));

    $color = $this->createElement('select', 'color', array('class' => 'form-element','label' => $lang->_('GROUPCOLOR')));
    $color->addMultiOptions(array(
        'yellow' => $lang->_('YELLOW'),
        'red' => $lang->_('RED'),
        'purple' => $lang->_('PURPLE'),
        'green' => $lang->_('GREEN'),
        'blue' => $lang->_('BLUE')
    ));
    $color->setRequired(true)
             ->addErrorMessage($lang->_('REQUIRED_FIELD'));

    $submit = $this->createElement('submit', 'submit', array('class' => 'btn btn-finish', 'label' => $lang->_('FINISH')));

    $this->addElements( array(
        $groupName,
        $color,
        $submit)
    );

    parent::init();
  }
  
private function _getUrl ($controller, $action) {
    $url = new Zend_View_Helper_Url();
    return $url->url(array('controller'=> $controller,'action'=>$action),'default');
}
}
