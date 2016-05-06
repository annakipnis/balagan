<?php

 use Zend\Form\Form;

class Application_Form_AddGameToGoal extends Zend_Form {
    
    protected $_gamename = null;
    public function setGamename($gamename){
        $this->_gamename = $gamename;
    }
    
 public function init(){
    $lang = Zend_Registry::get('lang');
    $this->setMethod('post');
    $this->setName('addgame_form');        
    if($this->_gamename != NULL) {
        $this->setAction($this->_getUrl('admin', 'updategame')); 
    } else {
        $this->setAction($this->_getUrl('admin', 'savegame')); 
    }
    $this->setAttrib('lang', $lang); 
    $this->setAttrib('enctype', 'application/x-www-form-urlencoded');
    $this->setDecorators(array(
        array('ViewScript', array('viewScript' => 'admin/addgame.phtml'),'Form')
    ));

    $gameName = $this->createElement('text', 'gameName', array('class' => 'form-element', 'placeholder' => $lang->_('GAMENAME')));
    $gameName->setRequired(true)
          ->addErrorMessage($lang->_('REQUIRED_FIELD'));

    if($this->_gamename != NULL){
        $gameName->setValue($this->_gamename);
    }

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
