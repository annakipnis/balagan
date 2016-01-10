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
            //$level_DB  = new Application_Model_DbTable_Level();
            
            $targets   = $target_DB->getAll();
            $_targets  = array();
            foreach ($targets as $t) { 
                $t['subgoals'] = $target_DB->getAllSubGoals( $t['goalID'] );
                $_targets[] = $t; 
            }

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
        
        if( $group_id && $target_id ){
            $game_DB = new Application_Model_DbTable_Game();
            $games = $game_DB->getAll($target_id);
            
            $this->view->group_id = $group_id;
            $this->view->games  = $games;
        }
    }
    
    public function planAction() {
        
        $group_id  = $this->_request->getParam('g');
        $target_id = $this->_request->getParam('t');
        $game_id = $this->_request->getParam('gm');
        
        if( $group_id && $target_id && $game_id ){
            $plan_DB = new Application_Model_DbTable_Planing();
            //date_default_timezone_set('Asia/Jerusalem');

            $new_plan = array(
                'groupID'    => $group_id,
                'gameID'     => $game_id,
                'date'       => date('Y-m-d H:i:s')
            );
            try{
                $plan_id = $plan_DB->insert( $new_plan );
                $plans = $plan_DB->getAll($plan_id);

                $this->view->plan_id = $plan_id;
                $this->view->plans  = $plans;
                
                $this->_redirect('/groups/group/g/'.$group_id.'/t/'.$target_id.'/gm/'.$game_id);
            } catch (Exception $ex) {
                die( json_encode( array('status'=> 'danger', 'msg' => $this->lang->_('FAILED_DOC')) ) );
            }
        }
    }
    
    public function newgameAction () {
        $form = new Application_Form_AddGame();        
        $this->view->form = $form;
    }
    
    public function addgameAction () {
        $group_id  = $this->_request->getParam('g');
        $target_id = $this->_request->getParam('t');
        
        $request = $this->getRequest();
        $game_data = $request->getPost();
        
        $gameName = trim($game_data['gameName']);

        if ( !strlen($gameName) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GAMENAME').'</div>');
            $this->_redirect('/planing/addgame');
        }
        
        $games_DB = new Application_Model_DbTable_Game();
        $new_game = array(
            'goalID'    => $target_id,
            'name'      => $game_data['gameName'],
        );
        try{
            $game_id = $games_DB->insert( $new_game );
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $group_id) ) );
        }
        $this->_redirect("/planing/games/g/".$group_id."/t/".$target_id);
    }
    
    public function  plannedactivitiesAction () {
        $user = Zend_Auth::getInstance()->getStorage()->read();
        $group_DB = new Application_Model_DbTable_Group();
        $groups   = $group_DB->getAll( $user->ganID );
        
        $plans_DB = new Application_Model_DbTable_Planing ();
        $_groups  = array();
        foreach($groups as $g) {
            $g['plan']= $plans_DB->getLastPlan($g['groupID'])[0]['game_name'];
            $_groups[] = $g;
        }
          
            
        #VIEWS
        $this->view->groups = $_groups;
    }
    
}