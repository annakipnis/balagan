<?php

/**
 * This controller handle the frontend website
 *
 * 
 */
class AdminController extends Zend_Controller_Action
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
        
        $this->view->userRole = $_SESSION['Default']['role'];
    }
    

    public function indexAction(){

    }
    
    public function ganAction(){
        $gan_DB = new Application_Model_DbTable_Gan();
        $gans = $gan_DB->getAll();
        $this->view->gans = $gans;
    }
    
    public function editganAction(){
        $ganID = $this->_request->getParam('g');
        $gan_DB = new Application_Model_DbTable_Gan();
        $ganName = $gan_DB->get($ganID)['name'];
        $user_DB = new Application_Model_DbTable_Users();
        $users = $user_DB->getUserByGan($ganID);
        $this->view->users = $users;
        $this->view->ganID = $ganID;
        $this->view->ganName = $ganName;
    }
    
    public function adduserAction() {
        $ganID = $this->_request->getParam('g');
        $form = new Application_Form_AddUser();        
        $this->view->form = $form; 
    }
    
    public function saveuserAction() {
        $ganID = $this->_request->getParam('g');
        $request = $this->getRequest();
        $user_data = $request->getPost();
                
        $users_DB = new Application_Model_DbTable_Users();
        $email = trim($user_data['email']);
        $password = trim($user_data['password']);

        if ( !strlen($email) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_EMAIL').'</div>');
            $this->_redirect('/admin/adduser/g/'.$ganID);
        }
        if ( !strlen($password) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_PASSWORD').'</div>');
            $this->_redirect('/admin/adduser/g/'.$ganID);
        } else if (strlen($password) < 4) {
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('PASSWORD_TOO_SHORT').'</div>');
            $this->_redirect('/admin/adduser/g/'.$ganID);
        }
        if ($users_DB->isExist($email)){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('EMAIL_EXISTS').'</div>');
            $this->_redirect('/admin/adduser/g/'.$ganID);
        }
        $validator = new Zend_Validate_EmailAddress();
        if (! $validator->isValid($email)) {
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('INVALID_EMAIL').'</div>');
            $this->_redirect('/admin/adduser/g/'.$ganID);
        } 
        $roles_DB = new Application_Model_DbTable_Roles ();
        $roleID = $roles_DB->getRoleID ($user_data['isAdmin'] ? "admin" : "user");
        $hashed_password = crypt($user_data['password']); //the salt is automatically generated
        
        $new_user = array(
            'email'       => $user_data['email'],
            'password'    => $hashed_password,
            'ganID'       => intval($ganID),
            'roleID'      => $roleID
        );
        try{
            $user_id = $users_DB->insert( $new_user );
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
        }
        $this->_redirect("/admin/editgan/g/".$ganID);
    }
    public function deleteuserAction() {
        $userID = $this->_request->getParam('u');
        $ganID = $this->_request->getParam('g');
        
        $users_DB = new Application_Model_DbTable_Users();
        $users_DB->delete("userID = $userID");
        
        $this->_redirect("/admin/editgan/g/".$ganID);
    }
    
    public function addganAction() {
        $form = new Application_Form_AddGan();        
        $this->view->form = $form; 
    }
    
    public function saveganAction() {
        $request = $this->getRequest();
        $gan_data = $request->getPost();
        $gan_DB = new Application_Model_DbTable_Gan();
                
        $ganName = trim($gan_data['ganName']);

        if ( !strlen($ganName) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GAN_NAME').'</div>');
            $this->_redirect('/admin/addgan');
        }
        
        $new_gan = array(
            'name'       => $gan_data['ganName'],
            'createDate' => date('Y-m-d H:i:s'),
        );
        try{
            $gan_id = $gan_DB->insert( $new_gan );
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
        }
        $this->_redirect("/admin/gan");
    }
    
    public function deleteganAction(){
        $ganID = $this->_request->getParam('g');
        
        $gan_DB = new Application_Model_DbTable_Gan();
        $gan_DB->delete("ganID = $ganID");
        
        $this->_redirect("/admin/gan");
    }
    
    public function goalsAction() {
        $fieldID = $this->_request->getParam('f');
        if ($fieldID) {
            $_SESSION['Default']['field'] = $fieldID;
        }
        $goals_DB = new Application_Model_DbTable_Target();
        $this->view->goals = $goals_DB->getAll($_SESSION['Default']['field']);
    }
    
    public function subgoalsAction() {
        $goalID = $this->_request->getParam('g');
        $goals_DB = new Application_Model_DbTable_Target();
        $subgoals = $goals_DB->getAllSubGoals($goalID);
        
        $_subgoals  = array();
        foreach($subgoals as $s) {
                $s['subgoals']= ($goals_DB->getAllSubGoals($s['goalID']));
                $_subgoals[] = $s;
        }
        
        $this->view->subgoals = $_subgoals;
        $this->view->goalParent = $goalID;
    }
    
    public function gamesAction(){
        $goalID = $this->_request->getParam('g');
        $games_DB = new Application_Model_DbTable_Game();
        $this->view->games = $games_DB->getAll($_SESSION['Default']['field'], $goalID);
        $this->view->goalID = $goalID;
    }
    
    public function deletegoalAction(){
        $goalID = $this->_request->getParam('g');
        $goals_DB = new Application_Model_DbTable_Target();
        $goals_DB->delete("goalID = $goalID");
        
        $this->_redirect("/admin/goals/");
    }
    
    public function deletesubgoalAction(){
        $goalID = $this->_request->getParam('g');
        $goals_DB = new Application_Model_DbTable_Target();
        $goalID_parent = $goals_DB->getGoalParent($goalID);
        $goals_DB->delete("goalID = $goalID");
        
        $this->_redirect("/admin/subgoals/g/".$goalID_parent);
    }
    
    public function deletegameAction(){
        $gameID = $this->_request->getParam('g');
        $games_DB = new Application_Model_DbTable_Game();
        $goalID = $games_DB->getGoal($gameID);
        $data = array ('active' => FALSE);
        $games_DB->update($data, "gameID = $gameID");
        
        $this->_redirect("/admin/games/g/".$goalID);
    }
    
    public function addfieldAction () {
        $form = new Application_Form_AddField();        
        $this->view->form = $form; 
    }
    
    public function editfieldAction () {
        $fieldID = $this->_request->getParam('f');
        $fields_DB = new Application_Model_DbTable_Field();
        $fieldName = $fields_DB->getFieldName($fieldID);

        $form = new Application_Form_AddField (array('fieldname' => $fieldName));        
        $this->view->form = $form; 
    }
    
    public function savefieldAction () {
        $request = $this->getRequest();
        $field_data = $request->getPost();
        $files = $request->getFiles();
        if ($files) {
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.'!!!!!!!!'.'</div>');
        }
        $field_DB = new Application_Model_DbTable_Field();
                
        $fieldName = trim($field_data['fieldName']);

        if (!strlen($fieldName) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_FIELD_NAME').'</div>');
            $this->_redirect('/admin/addfield');
        } else if (count($field_DB->isExists($field_data['fieldName'])) > 0) {
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('FIELD_NAME_EXISTS').'</div>');
            $this->_redirect('/admin/addfield');
        }
        
        $new_field = array(
            'name'  => $field_data['fieldName'],
        );
        try{
            $field_id = $field_DB->insert( $new_field );
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
        }
        $this->_redirect("/admin/fields");
    }
    public function updatefieldAction () {
        $fieldID = $this->_request->getParam('f');
        $request = $this->getRequest();
        $field_data = $request->getPost();
        $field_DB = new Application_Model_DbTable_Field();
                
        $fieldName = trim($field_data['fieldName']);

        if (!strlen($fieldName) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_FIELD_NAME').'</div>');
            $this->_redirect('/admin/editfield/f/'.$fieldID);
        }
        
        $updated_field = array('name'  => $field_data['fieldName']);
        try {
            $where['fieldID = ?']  = $fieldID;            
            $field_DB->update($updated_field, $where);
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
        }
        $this->_redirect("/admin/fields");
    }
    
    public function deletefieldAction(){
        $fieldID = $this->_request->getParam('f');
        $field_DB = new Application_Model_DbTable_Field();
        $field_DB->delete("fieldID = $fieldID");
        
        $this->_redirect("/admin/fields");
    }
    
    public function addgoalAction() {
        $form = new Application_Form_AddGoal();        
        $this->view->form = $form; 
        
        $goalID_parent = $this->_request->getParam('g');
        if ($goalID_parent) {
            $this->view->goalID_parent = $goalID_parent;
        }
    }
    
    public function savegoalAction() {
        $request = $this->getRequest();
        $goal_data = $request->getPost();
        $goal_DB = new Application_Model_DbTable_Target();
        $goalID_parent = $this->_request->getParam('g');
        $grade_DB = new Application_Model_DbTable_Grade();
                
        $goalName = trim($goal_data['goalName']);
        if (!strlen($goalName) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GOAL_NAME').'</div>');
            $this->_redirect('/admin/addgoal/g/'.$goalID_parent);
        } else if (count($goal_DB->isExists($goal_data['goalName'])) > 0) {
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('GOAL_NAME_EXISTS').'</div>');
            $this->_redirect('/admin/addgoal/g/'.$goalID_parent);
        }
            
        $goalLevel = trim($goal_data['goalLevel']);
        if ($goalID_parent) {
            if (!strlen($goalLevel) ){
                $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GOAL_LEVEL').'</div>');
                $this->_redirect('/admin/addgoal/g/'.$goalID_parent);
            } else if (!is_numeric($goal_data['goalLevel'])) {
                $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GOAL_LEVEL_INT').'</div>');
                $this->_redirect('/admin/addgoal/g/'.$goalID_parent);
            }
            $grade1 = trim($goal_data['grade1']);
            $grade2 = trim($goal_data['grade2']);
            $grade3 = trim($goal_data['grade3']);
            $grade4 = trim($goal_data['grade4']);

            if (!strlen($grade1)||!strlen($grade2)||!strlen($grade3)||!strlen($grade4) ){
                $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GRADE_NAME').'</div>');
                $this->_redirect('/admin/addgoal/g/'.$goalID_parent);
            }
        } else {
            $goalLevel = 0;
        }
        
        $new_goal = array(
            'goalID_parent' => $goalID_parent,
            'name'  => $goal_data['goalName'],
            'icon' => 'counting',
            'level' => $goalLevel,
            'fieldID' => $_SESSION['Default']['field']
        );
                

        try{
            $goal_id = $goal_DB->insert($new_goal);
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
        }
        if ($goalID_parent) {
            $new_grade1 = array(
                'goalID' => $goal_id,
                'name'  => $goal_data['grade1'],
                'value' => 1
            );
            $new_grade2 = array(
                'goalID' => $goal_id,
                'name'  => $goal_data['grade2'],
                'value' => 2
            );
            $new_grade3 = array(
                'goalID' => $goal_id,
                'name'  => $goal_data['grade3'],
                'value' => 3
            );
            $new_grade4 = array(
                'goalID' => $goal_id,
                'name'  => $goal_data['grade4'],
                'value' => 4
            );
            try{
                $grade_id1 = $grade_DB->insert($new_grade1);
                $grade_id2 = $grade_DB->insert($new_grade2);
                $grade_id3 = $grade_DB->insert($new_grade3);
                $grade_id4 = $grade_DB->insert($new_grade4);
            } catch (Exception $ex) {
                die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
            }
        }
        if ($goalID_parent){
            $this->_redirect("/admin/subgoals/g/".$goalID_parent);

        } else {
            $this->_redirect("/admin/goals");
        }
    }
    
    public function addgameAction() {
        $form = new Application_Form_AddGameToGoal();        
        $this->view->form = $form; 
    }
    
    public function savegameAction() {
        $goalID = $this->_request->getParam('g');
        
        $request = $this->getRequest();
        $game_data = $request->getPost();
        
        $gameName = trim($game_data['gameName']);

        if ( !strlen($gameName) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GAMENAME').'</div>');
            $this->_redirect('/admin/addgame/g/'.$goalID);
        }
        
        $games_DB = new Application_Model_DbTable_Game();
        $new_game = array(
            'goalID'    => $goalID,
            'name'      => $game_data['gameName'],
            'fieldID'   => $_SESSION['Default']['field'],
        );
        try{
            $game_id = $games_DB->insert( $new_game );
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
        }
        $this->_redirect("/admin/games/g/".$goalID);
    }
    public function updategameAction() {
        $gameID = $this->_request->getParam('gm');
        $goalID = $this->_request->getParam('g');

        $request = $this->getRequest();
        $game_data = $request->getPost();
        
        $gameName = trim($game_data['gameName']);

        if ( !strlen($gameName) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GAMENAME').'</div>');
            $this->_redirect('/admin/editgame/g/'.$goalID.'/gm/'.$gameID);
        }
        
        $games_DB = new Application_Model_DbTable_Game();
        $updated_game = array('name' => $game_data['gameName']);
        try{
            $games_DB->update($updated_game, "gameID = $gameID");
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
        }
        $this->_redirect("/admin/games/g/".$goalID);
    }
    
    public function editgameAction() {
        $gameID = $this->_request->getParam('gm');
        $game_DB = new Application_Model_DbTable_Game();
        $gameName = $game_DB->getGameName($gameID);
        $form = new Application_Form_AddGameToGoal(array('gamename' => $gameName));        
        $this->view->form = $form;
    }
    
    public function fieldsAction () {
        $fields_DB = new Application_Model_DbTable_Field ();
        $fields = $fields_DB->getAll();
        $this->view->fields = $fields;
    }
   
     public function editgoalAction() {
        $goalID = $this->_request->getParam('g');
        $goalID_parent = $this->_request->getParam('gp');
        
        $goal_DB = new Application_Model_DbTable_Target();
        $goalName = $goal_DB->getGoalName($goalID);
        if ($goalID_parent) {
            $this->view->goalID_parent = $goalID_parent;
            $grades = $goal_DB->getGoalAndGrades($goalID);
            $form = new Application_Form_AddGoal(array('goalname' => $goalName,
                                                   'goallevel'=> $grades[0]['level'],
                                                   'grade1'   => $grades[0]['gradeName'],
                                                   'grade2'   => $grades[1]['gradeName'],
                                                   'grade3'   => $grades[2]['gradeName'],
                                                   'grade4'   => $grades[3]['gradeName']));        
            $this->view->form = $form;
        } else {
            $form = new Application_Form_AddGoal(array('goalname' => $goalName));        
            $this->view->form = $form;
        }
     }
    
    public function updategoalAction() {
        $request = $this->getRequest();
        $goal_data = $request->getPost();
        $goal_DB = new Application_Model_DbTable_Target();
        $goalID_parent = $this->_request->getParam('gp');
        $goalID = $this->_request->getParam('g');
        $grade_DB = new Application_Model_DbTable_Grade();
                
        $goalName = trim($goal_data['goalName']);
        
        if (!strlen($goalName) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GOAL_NAME').'</div>');
            $this->_redirect('/admin/editgoal/g/'.$goalID.'/gp/'.$goalID_parent);
        }
        
        $goalLevel = trim($goal_data['goalLevel']);
        if ($goalID_parent) {
            if (!strlen($goalLevel) ){
                $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GOAL_LEVEL').'</div>');
                $this->_redirect('/admin/editgoal/g/'.$goalID.'/gp/'.$goalID_parent);
            } else if (!is_numeric($goal_data['goalLevel'])) {
                $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GOAL_LEVEL_INT').'</div>');
                $this->_redirect('/admin/editgoal/g/'.$goalID.'/gp/'.$goalID_parent);
            }
            $grade1 = trim($goal_data['grade1']);
            $grade2 = trim($goal_data['grade2']);
            $grade3 = trim($goal_data['grade3']);
            $grade4 = trim($goal_data['grade4']);

            if (!strlen($grade1)||!strlen($grade2)||!strlen($grade3)||!strlen($grade4) ){
                $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('REQUIRED_GRADE_NAME').'</div>');
                $this->_redirect('/admin/editgoal/g/'.$goalID.'/gp/'.$goalID_parent);
            }
            
            $updated_goal = array(
                'goalID_parent' => $goalID_parent,
                'name'  => $goal_data['goalName'],
                'icon' => 'counting',
                'level' => $goalLevel,
                'fieldID' => $_SESSION['Default']['field']);
        } else {
            $goalLevel = 0;
            $updated_goal = array('name'  => $goal_data['goalName']);
        }
        
        try{
            $where_goal = $goal_DB->getAdapter()->quoteInto('goalID = ?', $goalID);
            $goal_DB->update($updated_goal, $where_goal);
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
        }
        if ($goalID_parent) {
            $new_grade1 = array(
                'goalID' => $goalID,
                'name'  => $goal_data['grade1'],
                'value' => 1
            );
            $new_grade2 = array(
                'goalID' => $goalID,
                'name'  => $goal_data['grade2'],
                'value' => 2
            );
            $new_grade3 = array(
                'goalID' => $goalID,
                'name'  => $goal_data['grade3'],
                'value' => 3
            );
            $new_grade4 = array(
                'goalID' => $goalID,
                'name'  => $goal_data['grade4'],
                'value' => 4
            );
            try{
                $where_grade1['goalID = ?'] = $goalID;
                $where_grade1['value = ?']  = 1;
                $grade_DB->update($new_grade1, $where_grade1);
                $where_grade2['goalID = ?'] = $goalID;
                $where_grade2['value = ?']  = 2;
                $grade_DB->update($new_grade2, $where_grade2);
                $where_grade3['goalID = ?'] = $goalID;
                $where_grade3['value = ?']  = 3;
                $grade_DB->update($new_grade3, $where_grade3);
                $where_grade4['goalID = ?'] = $goalID;
                $where_grade4['value = ?']  = 4;
                $grade_DB->update($new_grade4, $where_grade4);

            } catch (Exception $ex) {
                die( json_encode( array('status'=> 'danger', 'msg' => $ex->getMessage()) ) );
            }
        }
        if ($goalID_parent){
            $this->_redirect("/admin/subgoals/g/".$goalID_parent);

        } else {
            $this->_redirect("/admin/goals");
        }
    }
}

