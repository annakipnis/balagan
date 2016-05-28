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
        
        $this->view->userRole = $_SESSION['Default']['role'];
        
        if (isset($_SESSION['Default']['field'])) {
            $fieldID = $_SESSION['Default']['field'];
            $fields_DB = new Application_Model_DbTable_Field ();
            $this->view->fieldName = $fields_DB->getFieldName($fieldID);
        }
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
            $targets   = $target_DB->getAll($_SESSION['Default']['field']);
            $_targets  = array();
            foreach ($targets as $t) { 
                $t['subgoals'] = $target_DB->getAllSubGoals( $t['goalID'] );
                $_targets[] = $t; 
            }

            $this->view->group_id = $group_id;
            $this->view->targets  = $_targets;
            
            $groups_DB = new Application_Model_DbTable_Group();
            $this->view->groupName = $groups_DB->getName($group_id);
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
            $games = $game_DB->getAll($_SESSION['Default']['field'], $target_id);
            if ($games) {
                $this->view->games  = $games;
            }
            $this->view->group_id = $group_id;
            
            $groups_DB = new Application_Model_DbTable_Group();
            $this->view->groupName = $groups_DB->getName($group_id);
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
                'date'       => date('Y-m-d H:i:s'),
                'fieldID'    => $_SESSION['Default']['field']
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
            
            $groups_DB = new Application_Model_DbTable_Group();
            $this->view->groupName = $groups_DB->getName($group_id);
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
        
        if(isset($_FILES["photo"]["error"])){
            if($_FILES["photo"]["error"] > 0){
                if($_FILES["photo"]["error"] != 4){ 
                    $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$_FILES["photo"]["error"].'</div>');
                    $this->_redirect("/planing/addgame");
                } //else 4 = No file was uploaded, than do nothing. (add with default icon)
            } else{
                $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
                $filename = $_FILES["photo"]["name"];
                $filetype = $_FILES["photo"]["type"];
                $filesize = $_FILES["photo"]["size"];
                // Verify file extension
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if(!array_key_exists($ext, $allowed)) {
                    $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('FILE_WRONG_FORMAT').'</div>');
                    $this->_redirect("/planing/addgame");
                }
                // Verify file size - 5MB maximum
                $maxsize = 5 * 1024 * 1024;
                if($filesize > $maxsize) {
                    $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('FILE_SIZE_LIMIT').'</div>');
                    $this->_redirect("/admin/addgame/g/".$goalID);
                }
                // Verify MYME type of the file
                if(in_array($filetype, $allowed)){
                    // Check whether file exists before uploading it
                    if(!file_exists($this->config->paths->upload->games . $_FILES["photo"]["name"])){
                        move_uploaded_file($_FILES["photo"]["tmp_name"], $this->config->paths->upload->games . $_FILES["photo"]["name"]);
                    } 
                } else{
                    $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('FILE_ERROR').'</div>');
                    $this->_redirect("/planing/addgame");
                }
            }
        } else{
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('FILE_ERROR').'</div>');
            $this->_redirect("/planing/addgame");
        }
        
        $gameName = trim($game_data['gameName']);

        if ( !strlen($gameName) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GAMENAME').'</div>');
            $this->_redirect('/planing/addgame');
        }
        
        $games_DB = new Application_Model_DbTable_Game();
        $new_game = array(
            'goalID'    => $target_id,
            'name'      => $game_data['gameName'],
            'fieldID'   => $_SESSION['Default']['field'],
            'icon'  => $_FILES["photo"]["name"],
        );
        try{
            $game_id = $games_DB->insert( $new_game );
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
        }
        
        $groups_DB = new Application_Model_DbTable_Group();
        $this->view->groupName = $groups_DB->getName($group_id);
        
        $this->_redirect("/planing/games/g/".$group_id."/t/".$target_id);
    }
    
    public function  plannedactivitiesAction () {
        $user = Zend_Auth::getInstance()->getStorage()->read();
        $group_DB = new Application_Model_DbTable_Group();
        if (!isset ($_SESSION['Default']['field'])) {
            $this->view->field_error = true;
        } else {
            $groups   = $group_DB->getAll( $user->ganID, $_SESSION['Default']['field']);

            $plans_DB = new Application_Model_DbTable_Planing ();
            $games_DB = new Application_Model_DbTable_Game ();
            $_groups  = array();
            foreach($groups as $g) {
                $last_plan = $plans_DB->getLastPlan($g['groupID'], $_SESSION['Default']['field']);
                $gameID = $last_plan['game_id'];
                $goalName = $games_DB->getGoalName ($gameID);
                if ($last_plan) {
                    $g['plan'] = $last_plan['game_name'];
                    $g['goal'] = $goalName;
                    $_groups[] = $g;
                }
            }            
            #VIEWS
            $this->view->groups = $_groups;
        }
    }
    
    public function doneactivitiesAction () {
        $user = Zend_Auth::getInstance()->getStorage()->read();
        $group_DB = new Application_Model_DbTable_Group();
        if (!isset ($_SESSION['Default']['field'])) {
            $this->view->field_error = true;
        } else {
            $groups   = $group_DB->getAll( $user->ganID, $_SESSION['Default']['field']);      
            #VIEWS
            $this->view->groups = $groups;
        }
    }
}
