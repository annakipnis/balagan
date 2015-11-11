<?php

/**
 * This controller handle the frontend website
 *
 * @author M_AbuAjaj
 */
class PlaningController extends Zend_Controller_Action
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
     * Date   : 28/01/2015
     */
    public function targetsAction(){
        $group_id = $this->_request->getParam('g');
        if( $group_id ){
            $target_DB = new Application_Model_DbTable_Target();
            $level_DB  = new Application_Model_DbTable_Level();
            
            $targets   = $target_DB->getAll();
            $_targets  = array();
            foreach ($targets as $t) { $t['levels'] = $level_DB->getAll( $t['target_id'] ); $_targets[] = $t; }

            $this->view->group_id = $group_id;
            $this->view->targets  = $_targets;
        }
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 28/01/2015
     */
    public function gamesAction(){
        $group_id  = $this->_request->getParam('g');
        $target_id = $this->_request->getParam('t');
        $level_id  = $this->_request->getParam('l');
        
        if( $group_id && $target_id && $level_id ){
            $game_DB = new Application_Model_DbTable_Game();
            $games = $game_DB->getAll($target_id, $level_id);
            
            $this->view->group_id = $group_id;
            $this->view->games  = $games;
        }
    }
    
}