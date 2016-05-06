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
        
        $this->view->userRole = $_SESSION['Default']['role'];
    }
    
    /*
     * Author : M_AbuAjaj
     * Date   : 27/01/2015
     */
    public function indexAction(){
        $field_id  = $this->_request->getParam('f');
        if ($field_id) {
            $_SESSION['Default']['field'] = $field_id;
            
            /**for groups view**/
            $user = Zend_Auth::getInstance()->getStorage()->read();
            $group_DB = new Application_Model_DbTable_Group();
            $groups = $group_DB->getAll( $user->ganID, $_SESSION['Default']['field']);
            if ($groups) {
                $this->view->groups = $groups;
            }

            /**for pop up**/            
            $plans_DB = new Application_Model_DbTable_Planing ();
            $goals_DB = new Application_Model_DbTable_Target ();
            $_groups  = array();
            foreach($groups as $g) {
                $lastplan = $plans_DB->getLastPlan($g['groupID'], $_SESSION['Default']['field']);
                if ($lastplan) {
                    $g['plan']= $lastplan['game_name'];
                    $g['goal']= $goals_DB->getGoalName($lastplan['goal_id']);
                    $_groups[] = $g;
                }
            }
          
            $this->view->groups_with_plans = $_groups;
        }
    }
    //homepage without popup
    public function groupsAction () {
        $user = Zend_Auth::getInstance()->getStorage()->read();
        $group_DB = new Application_Model_DbTable_Group();
        if (isset ($_SESSION['Default']['field'])) {
            $groups = $group_DB->getAll( $user->ganID, $_SESSION['Default']['field']);
            if ($groups) {
                $this->view->groups = $groups;
            }
        } else {
            $this->view->error = true;
        }
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 27/01/2015
     */
    public function groupAction(){
        $group_DB = new Application_Model_DbTable_Group();
        
        $group_id  = $this->_request->getParam('g');
        $target_id = $this->_request->getParam('t');
        $game_id   = $this->_request->getParam('gm');
        
        //Return After Planining
        if( $group_id && $target_id ){
            $group = $group_DB->get( $group_id );
            $this->view->group     = $group;
            $this->view->group_id  = $group_id;
            $this->view->target_id = $target_id;
            $this->view->game_id   = $game_id;
        }
        else if( $group_id ){
            $group = $group_DB->get( $group_id );
            $this->view->group_id = $group_id;
            $this->view->group    = $group;
        }
        $comments_DB = new Application_Model_DbTable_Comments();
        if (!isset($_SESSION['Default']['field'])) {
            $this->view->comments = "";
            $this->view->field_error = true;
        } else {
            $last_comment = $comments_DB->getLast($group_id, $_SESSION['Default']['field']);
            $this->view->comments = $last_comment['text'];
        }
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 29/01/2015
     */
    public function progressAction(){
        $groupID = $this->_request->getParam('g');
        
        $group_DB = new Application_Model_DbTable_Group();
        $groupName = $group_DB->getName($groupID);
        $this->view->group_name = $groupName;
        
        $plans_DB = new Application_Model_DbTable_Planing();
        if (!isset($_SESSION['Default']['field'])) {
            $this->view->field_error = true;
        } else {
            $plans = $plans_DB->getByGroup ($groupID, $_SESSION['Default']['field']);
            $this->view->plans = $plans;

            $this->view->groupID = $groupID;
        }
    }
    
    public function reportAction() {
        $groupID = $this->_request->getParam('g');
        $_SESSION['Default']['report'] = true;
        
        $group_DB = new Application_Model_DbTable_Group();
        $groupName = $group_DB->getName($groupID);
        $this->view->group_name = $groupName;
        
        $plans_DB = new Application_Model_DbTable_Planing();
        $plans = $plans_DB->getByGroupReverse ($groupID, $_SESSION['Default']['field']);
        $this->view->plans = $plans;
    }
}
