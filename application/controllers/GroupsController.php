<?php

/**
 * This controller handle the frontend website
 *
 * @author M_AbuAjaj
 */
class GroupsController extends Zend_Controller_Action
{
    
    function init()
    {
        
        if( !Zend_Auth::getInstance()->hasIdentity() )
        {
            $this->_redirect('/');
        }
        
        $user = Zend_Auth::getInstance()->getStorage()->read();
        
        #GROUPS
        $group_DB = new Application_Model_DbTable_Group();
        $groups   = $group_DB->getAll( $user->gan_id );
        
        #VIEWS
        $this->view->groups = $groups;
        
        
        #Layout
        $this->_helper->layout->setLayout('layout');
        $this->config = Zend_Registry::get('config');
        #SEO:
        $this->view->title = $this->view->lang->_('SITE_TITLE');
        $this->view->sitedesc = $this->view->lang->_('SITE_DESC');
        $this->view->sitekeywords = $this->view->lang->_('SITE_KEYWORDS');
        #MSG
        $this->msger = $this->_helper->getHelper('FlashMessenger');
        $this->view->flashmsgs = $this->msger->getMessages();
        $this->lang = Zend_Registry::get('lang');
    }
    
    /*
     * Author : M_AbuAjaj
     * Date   : 27/01/2015
     */
    public function indexAction(){
        
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 27/01/2015
     */
    public function groupAction(){
        $group_DB = new Application_Model_DbTable_Group();
        
        $group_id  = $this->_request->getParam('g');
        $target_id = $this->_request->getParam('t');
        $level_id  = $this->_request->getParam('l');
        $game_id   = $this->_request->getParam('gm');
        
        //Return After Planining
        if( $group_id && $target_id && $level_id ){
            $group = $group_DB->get( $group_id );
            $this->view->group     = $group;
            $this->view->group_id  = $group_id;
            $this->view->target_id = $target_id;
            $this->view->level_id  = $level_id;
            $this->view->game_id   = $game_id;
        }
        else if( $group_id ){
            $group = $group_DB->get( $group_id );
            $this->view->group_id = $group_id;
            $this->view->group    = $group;
        }
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 29/01/2015
     */
    public function progressAction(){
        $group_id = $this->_request->getParam('g');
        $this->view->group_id = $group_id;
    }
    
}