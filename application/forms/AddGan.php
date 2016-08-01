<?php

 use Zend\Form\Form;

class Application_Form_AddGan extends Zend_Form {
    
    protected $_ganname = null;
    public function setGanname($ganname){
        $this->_ganname = $ganname;
    }
    
 public function init(){
    $lang = Zend_Registry::get('lang');
    $this->setMethod('post');
    $this->setName('addgan_form');        
    if($this->_ganname != NULL){
        $this->setAction($this->_getUrl('admin', 'updateganname')); 
    } else {
        $this->setAction($this->_getUrl('admin', 'savegan')); 
    }
    $this->setAttrib('lang', $lang); 
    $this->setAttrib('enctype', 'application/x-www-form-urlencoded');
    $this->setDecorators(array(
        array('ViewScript', array('viewScript' => 'admin/addgan.phtml'),'Form')
    ));

    $ganName = $this->createElement('text', 'ganName', array('class' => 'form-element', 'placeholder' => $lang->_('GANNAME')));
    $ganName->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));
    
    if($this->_ganname != NULL){
        $ganName->setValue($this->_ganname);
    }
    
    $submit = $this->createElement('submit', 'submit', array('class' => 'btn btn-finish', 'label' => $lang->_('FINISH')));

    $this->addElements( array(
        $ganName,
        $submit)
    );

    parent::init();
  }
  
private function _getUrl ($controller, $action) {
    $url = new Zend_View_Helper_Url();
    return $url->url(array('controller'=> $controller,'action'=>$action),'default');
}
}
