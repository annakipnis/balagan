<?php

/**
 * Description of User Register With Email And Password..
 *
 * @author M_AbiAjaj
 * Date : 11/10/13
 */
class  Application_Form_Register extends Zend_Form{
 
    public function init(){
        $config = Zend_Registry::get('config');
        $lang = Zend_Registry::get('lang');
        $this->setMethod('post');
        $this->setName('free_user_form');
        $this->setAction('/register/new');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('lang', $lang);

        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'register/register.phtml'),'Form')
            ));

        $name = $this->createElement('text', 'name', array('class' => 'form-control', 'placeholder'=> $lang->_('NAME'))  );
        $name->setRequired(true)
             ->addErrorMessage($lang->_('REGISTER_NAME_ERROR_MSG'));

        $email =  $this->createElement('text', 'email', array('data-content'=>$lang->_('REGISTER_EMAIL_HINT'),'class' => 'form-control', 'placeholder'=> $lang->_('EMAIL') ,'hint' => $lang->_('EMAIL_HINT')));
        $email->addValidator('EmailAddress')
              ->setRequired(true)
              ->addErrorMessage($lang->_('REGISTER_EMAIL_ERROR_MSG'));
        $password =  $this->createElement('password', 'password', array('data-content'=>$lang->_('REGISTER_PASSWORD_HINT'),'class' => 'form-control', 'placeholder' => $lang->_('PASSWORD')));
        $password->addValidator('alnum')
                 ->addValidator('stringLength', false, array(6, 20))
                 ->setRequired(true)
                 ->addErrorMessage($lang->_('REGISTER_PASSWORD_ERROR_MSG'));
        $repassword =  $this->createElement('password', 'repassword', array('class' => 'form-control', 'placeholder' => $lang->_('REPASSWORD')));
        $repassword ->addValidator(new Zend_Validate_Identical('password'))
                    ->setRequired(true)
                    ->addErrorMessage($lang->_('REGISTER_REPASSWORD_ERROR_MSG'));
        
        $terms =  $this->createElement('checkbox', 'terms_agree', array('label' => $lang->_('TERMS_AGREE_LABEL')));
        $terms->setUncheckedValue(null);
        $terms->setDecorators(array(
            'Description',
            'Errors',
            array('ViewHelper'),
            array('Label',  array('class' => 'radio-inline','placement' => 'APPEND','escape' => false))
        )); 
        $terms->setRequired(true)->addErrorMessage($lang->_('TERMS_ERROR_MSG'));
        
        $submit =  $this->createElement('submit', 'submit', array('label' => $lang->_('REGISTER'), 'class' => 'btn btn-orange btn-block') );
        
        $this->addElements( array($name, $email, $password, $repassword, $terms, $submit) );
   


   parent::init();
  }
  
}

?>
