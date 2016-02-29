<?php

/**
 * This controller handle the frontend website
 *
 * @author M_AbuAjaj
 */
class DocumentationController extends Zend_Controller_Action
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
    public function studentsAction(){
        $group_id  = $this->_request->getParam('g');
        $target_id = $this->_request->getParam('t');
        $game_id   = $this->_request->getParam('gm');
        
        $is_recommend = $this->_request->getParam('recommend');
        
        //Return After Planining
        if( $group_id && $target_id ){
            $student_DB = new Application_Model_DbTable_Student();
            $grade_DB   = new Application_Model_DbTable_Grade();
            $students   = $student_DB->getAll($group_id);

            $grades = $grade_DB->getAll( $target_id );
            $_students = array();
            foreach ($students as $s) { 
                $s['grades'] = $grades; 
                $_students[] = $s; 
                
            }

            $this->view->students  = $_students;
            $this->view->group_id  = $group_id;
            $this->view->target_id = $target_id;
            $this->view->game_id   = $game_id;
        } 
        else {
            $plans_DB = new Application_Model_DbTable_Planing();
            if ($plans_DB->getLastPlan ($group_id)) {
                $last_plan = $plans_DB->getLastPlan ($group_id) [0];
                $group_id = $last_plan ['groupID'];
                $target_id = $last_plan ['goal_id'];
                $game_id = $last_plan ['game_id'];


                $student_DB = new Application_Model_DbTable_Student();
                $grade_DB   = new Application_Model_DbTable_Grade();
                $students   = $student_DB->getAll($group_id);

                $grades = $grade_DB->getAll( $target_id );
                $_students = array();
                foreach ($students as $s) { 
                    $s['grades'] = $grades; 
                    $_students[] = $s; 

                }

                $this->view->students  = $_students;
                $this->view->group_id  = $group_id;
                $this->view->target_id = $target_id;
                $this->view->game_id   = $game_id;
            } else {
                $this->view->group_id  = $group_id;
            }
        }
        
        if ($is_recommend) {
            $this->recommend ();
        }
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/2015
     */
    public function savegradeAction(){
        if( $this->getRequest()->isPost() ){
            $_params = $this->_request->getParams();
            if( $_params['studentID'] && $_params['gradeID'] && $_params['gameID'] && $_params['groupID']){
                $doc_DB = new Application_Model_DbTable_Records();
                $new_doc = array(
                    'studentID'  => $_params['studentID'],
                    'gameID'     => $_params['gameID'],
                    'gradeID'    => $_params['gradeID'],
                    'date'       => date('Y-m-d H:i:s'),
                    'groupID'    => $_params['groupID']

                );
                try{
                    $doc_id = $doc_DB->insert( $new_doc );
                } catch (Exception $ex) {
                    die( json_encode( array('status'=> 'danger', 'msg' => $this->lang->_('FAILED_DOC')) ) );
                }
                die( json_encode( array('status'=> 'success', 'msg' => $this->lang->_('SUCCESS_DOC')) ) );
            }
            die( json_encode( array('status'=> 'danger', 'msg' => $this->lang->_('FAILED_DOC')) ) );
        }
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 18/03/2015
     */
    public function savenotesAction(){
        if( $this->getRequest()->isPost() ){
            $_params = $this->_request->getParams();
            if( $_params['group_id'] && $_params['notes'] ){
                $doc_DB = new Application_Model_DbTable_Records();
                $new_doc = array(
                    'group_id'    => $_params['group_id'],
                    'notes'       => $_params['notes'],
                    'create_date' => time()
                );
                
                try{
                    $doc_id = $doc_DB->insert( $new_doc );
                } catch (Exception $ex) {
                    die( json_encode( array('status'=> 'danger', 'msg' => $this->lang->_('FAILED_DOC')) ) );
                }
                die( json_encode( array('status'=> 'success', 'msg' => $this->lang->_('SUCCESS_DOC')) ) );
            }
            die( json_encode( array('status'=> 'danger', 'msg' => $this->lang->_('FAILED_DOC')) ) );
        }
    }
    
    /*
     * 
     */
    
    public function recommend (){
        $group_id  = $this->_request->getParam('g');

        $plans_DB = new Application_Model_DbTable_Planing();
        if ($plans_DB->getLastPlan ($group_id)) {
            $last_plan = $plans_DB->getLastPlan ($group_id) [0];
            $target_id = $last_plan ['goal_id'];
            $game_id = $last_plan ['game_id'];
            $related_plan_id = $last_plan ['planID'];

            $targets_DB =  new Application_Model_DbTable_Target();
            $target_level = $targets_DB->getGoalLevel($target_id);
            settype($target_level, "integer");

            $records_DB = new Application_Model_DbTable_Records ();
            $records = $records_DB->getAll($group_id, $game_id);

            $count_1 = 0;
            $count_4 = 0;
            foreach ($records as $r) {
                $grade = $r ['grade_value'];
                settype($grade, "integer");
                if ($grade == 1){
                    $count_1++;
                }
                else if ($grade == 4){
                    $count_4++;
                }
            }

            $num_of_students = count($records);
            $num_of_levels = count($targets_DB->getLevels());

            //current target
            $recommended_target = $target_id; 
            $recommendation = $this->lang->_('RECOMMEND_CURRENT_LEVEL');

            if ($count_1 >= $num_of_students / 2) {
                //return to a game from prev goal
                if ($target_level > 1) {
                    $target_level = $target_level - 1;
                    $recommended_target = $targets_DB->getAllByLevel($target_level);
                    $recommendation = $this->lang->_('RECOMMEND_PREV_LEVEL');
                }
            }
            else if ($count_4 >= $num_of_students - 1) {
                $unlearned_targets = $targets_DB->getUnlearnedInLevel ($target_level, $group_id);
                //next target in current level 
                if (count($unlearned_targets) > 0) {
                    $recommended_target = $unlearned_targets [0]['goalID'];
                    $recommendation = $this->lang->_('RECOMMEND_NEXT_LEVEL');
                }
                //if learned all targets in current level, move to next level
                else if ($target_level < $num_of_levels) {
                    $target_level = $target_level + 1;
                    $recommended_target = $targets_DB->getAllByLevel($target_level)['goalID'];
                    $recommendation = $this->lang->_('RECOMMEND_NEXT_LEVEL');
                }
            }

            $games_DB = new Application_Model_DbTable_Game ();

            settype($recommended_target, "integer");
            $unplayed_games = $games_DB->getGamesNotPlayed($group_id, $recommended_target);        
            //check if all games were played
            if (count($unplayed_games) == 0) {
                $recommended_game = $games_DB->getRandomGame($recommended_target);
            }
            else {
                $recommended_game = $unplayed_games [0];
            }

            $this->view->recommended_target = $recommended_target;
            $this->view->recommendation = $recommendation;
            $this->view->recommended_game = $recommended_game['name'];

            //insert to DB as last plan
            $plan_DB = new Application_Model_DbTable_Planing();

            $new_plan = array(
                'groupID'        => $group_id,
                'gameID'         => $recommended_game['gameID'],
                'date'           => date('Y-m-d H:i:s'),
                'recommendation' => $recommendation,
                'relatedPlanID'  => $related_plan_id
            );
            try{
                $plan_id = $plan_DB->insert( $new_plan );
            } catch (Exception $ex) {
                die( json_encode( array('status'=> 'danger', 'msg' => $this->lang->_('FAILED_DOC')) ) );
            }    
        }
    }
    
}
