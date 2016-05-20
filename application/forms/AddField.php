<?php

 use Zend\Form\Form;

class Application_Form_AddField extends Zend_Form {
    
    protected $_fieldname = null;
    public function setFieldname($fieldname){
        $this->_fieldname = $fieldname;
    }
    
    public function init(){
       $lang = Zend_Registry::get('lang');
       $this->setMethod('post');
       $this->setName('addfield_form');        
       if($this->_fieldname != NULL){
           $this->setAction($this->_getUrl('admin', 'updatefield')); 
       } else {
           $this->setAction($this->_getUrl('admin', 'savefield')); 
       }
       $this->setAttrib('lang', $lang); 
       $this->setAttrib('enctype', 'application/x-www-form-urlencoded');
       $this->setDecorators(array(
           array('ViewScript', array('viewScript' => 'admin/addfield.phtml'),'Form')
       ));

       $fieldName = $this->createElement('text', 'fieldName', array('class' => 'form-element', 'placeholder' => $lang->_('FIELD_NAME')));
       $fieldName->setRequired(true)
             ->addErrorMessage($lang->_('REQUIRED_FIELD'));

       if($this->_fieldname != NULL){
           $fieldName->setValue($this->_fieldname);
       }

       $config = Zend_Registry::get('config');
       $image = new Zend_Form_Element_File('image');
       $image->setLabel($lang->_('CHOOSE_FILE'))
             ->setDestination($config->paths->upload)
             ->setRequired(true);
       $image->addValidator('Count', false, 1); // ensure only 1 file
       $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

       $submit = $this->createElement('submit', 'submit', array('class' => 'btn btn-finish', 'label' => $lang->_('FINISH')));

       $this->addElements( array(
           $fieldName,
           $image,
           $submit)
       );

       parent::init();
     }
  
    private function _getUrl ($controller, $action) {
        $url = new Zend_View_Helper_Url();
        return $url->url(array('controller'=> $controller,'action'=>$action),'default');
    }
}
