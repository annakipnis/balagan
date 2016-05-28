<?php

 use Zend\Form\Form;

class Application_Form_AddGame extends Zend_Form {
    
 public function init(){
    $lang = Zend_Registry::get('lang');
    $this->setMethod('post');
    $this->setName('addgame_form');   
    $this->setAction($this->_getUrl('planing', 'addgame')); 
    $this->setAttrib('lang', $lang); 
    $this->setAttrib('enctype', 'multipart/form-data');
    $this->setDecorators(array(
        array('ViewScript', array('viewScript' => 'planing/newgame.phtml'),'Form')
    ));

    $gameName = $this->createElement('text', 'gameName', array('class' => 'form-element', 'placeholder' => $lang->_('GAMENAME')));
    $gameName->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));

    
    $submit = $this->createElement('submit', 'submit', array('class' => 'btn btn-finish', 'label' => $lang->_('FINISH')));

    $this->addElements( array(
        $gameName,
        $submit)
    );

    parent::init();
  }
  
private function _getUrl ($controller, $action) {
    $url = new Zend_View_Helper_Url();
    return $url->url(array('controller'=> $controller,'action'=>$action),'default');
}
}
