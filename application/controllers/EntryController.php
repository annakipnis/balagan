<?php

/**
 * This controller handle the frontend website
 *
 * 
 */
class EntryController extends Zend_Controller_Action
{
    
    function init()
    {
        
        if( !Zend_Auth::getInstance()->hasIdentity() )
        {
            $this->_redirect('/');
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
    

    public function indexAction(){
        unset($_SESSION['Default']['admin']);
        $_SESSION['Default']['entry'] = true;
    }
    
    public function adminAction () {
        unset($_SESSION['Default']['entry']);
        $_SESSION['Default']['admin'] = true;
        $this->_redirect('/admin/gan');
    }
    
    public function userAction () {
        unset($_SESSION['Default']['entry']);
        unset($_SESSION['Default']['admin']);
        $this->_redirect('/fields');
    }
    
}

