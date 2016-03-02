<?php

 use Zend\Form\Form;

class Application_Form_AddUser extends Zend_Form {
    
 public function init(){
    $lang = Zend_Registry::get('lang');
    $this->setMethod('post');
    $this->setName('adduser_form');        
    $this->setAction($this->_getUrl('admin', 'saveuser')); 
    $this->setAttrib('lang', $lang); 
    $this->setAttrib('enctype', 'application/x-www-form-urlencoded');
    $this->setDecorators(array(
        array('ViewScript', array('viewScript' => 'admin/adduser.phtml'),'Form')
    ));

    $email = $this->createElement('text', 'email', array('class' => 'form-element', 'placeholder' => $lang->_('EMAIL')));
    $email->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));

    $password = $this->createElement('password', 'password', array('class' => 'form-element', 'placeholder' => $lang->_('PASSWORD')));
    $password->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));
    
    $isAdmin = $this->createElement('checkbox', 'isAdmin', array('label' => $lang->_('IS_ADMIN')));
    $isAdmin->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));

    $submit = $this->createElement('submit', 'submit', array('class' => 'btn btn-finish', 'label' => $lang->_('FINISH')));

    $this->addElements( array(
        $email,
        $password,
        $isAdmin,
        $submit)
    );

    parent::init();
  }
  
private function _getUrl ($controller, $action) {
    $url = new Zend_View_Helper_Url();
    return $url->url(array('controller'=> $controller,'action'=>$action),'default');
}
}
