<?php

/**
 * This controller handle the register
 *
 * @author M_AbuAjaj
 */
class RegisterController extends Zend_Controller_Action
{
    
    function init()
    {
//        if(!Zend_Auth::getInstance()->hasIdentity())
//        {
//            
//        }else{
//            
//        }
        
        #Layout
        $this->_helper->layout->setLayout('layout');
        
        $this->config = Zend_Registry::get('config');
        
        $this->msger = $this->_helper->getHelper('FlashMessenger');
        $this->lang = Zend_Registry::get('lang');
    }
    
    /*
     * Author : M_AbuAjaj
     * Date   : 06/11/2014
     */
    public function indexAction(){
        $register_form = new Application_Form_Register();
        $this->view->register_form = $register_form;
    }
}