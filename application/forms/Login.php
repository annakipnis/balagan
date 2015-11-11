<?php

/**
 * Description Of Login
 *
 * @author M_AbuAjaj
 * Date : 06/11/14
 */
class  Application_Form_Login extends Zend_Form{
    
    public function init(){
        $lang = Zend_Registry::get('lang');
        $this->setMethod('post');
        $this->setName('login_form');
        $this->setAction('/login/check');
        $this->setAttrib('lang', $lang);
        $this->setAttrib('enctype', 'application/x-www-form-urlencoded');
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'index/login.phtml'),'Form')
        ));
        
        $username = $this->createElement('text', 'username', array('class' => 'form-control', 'placeholder' => $lang->_('USERNAME')));
        $username->setRequired(true)
              ->addErrorMessage($lang->_('REQUIRED_FIELD'));
        $username->addFilters(array('StringTrim', 'StripTags'));
        $username->addValidator('EmailAddress',  TRUE  )
              ->addErrorMessage($lang->_('INVALID_EMAIL'));
        
        $password = $this->createElement('password', 'password', array('class' => 'form-control', 'placeholder' => $lang->_('PASSWORD')));
        $password->setRequired(true)
                 ->addErrorMessage($lang->_('PASSWORD_ERROR_MSG'));
        
        $submit = $this->createElement('submit', 'submit', array('class' => 'btn btn-next-action btn-block', 'label' => $lang->_('LOGIN')));
        
        $this->addElements( array(
            $username,
            $password,
            $submit)
        );
        
        parent::init();
  }
  
}
