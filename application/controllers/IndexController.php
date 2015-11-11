<?php

/**
 * This controller handle the frontend website
 *
 * @author M_AbuAjaj
 */
class IndexController extends Zend_Controller_Action
{
    
    function init()
    {
        
        if( Zend_Auth::getInstance()->hasIdentity() )
        {
            $this->_redirect('/groups');
        }
        
        #Layout
        $this->_helper->layout->setLayout('layout');
        
        $this->config = Zend_Registry::get('config');
        
        #SEO:
        $this->view->title = $this->view->lang->_('SITE_TITLE');
        $this->view->sitedesc = $this->view->lang->_('SITE_DESC');
        $this->view->sitekeywords = $this->view->lang->_('SITE_KEYWORDS');
        
        $this->msger = $this->_helper->getHelper('FlashMessenger');
        $this->view->flashmsgs = $this->msger->getMessages();
        $this->lang = Zend_Registry::get('lang');
    }
    
    /*
     * Author : M_AbuAjaj
     * Date   : 27/01/2015
     */
    public function indexAction(){
        $form = new Application_Form_Login();
        $this->view->form = $form;
    }
    
}