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
        $level_id  = $this->_request->getParam('l');
        $game_id   = $this->_request->getParam('gm');
        
        //Return After Planining
        if( $group_id && $target_id && $level_id ){
            $student_DB = new Application_Model_DbTable_Student();
            $grade_DB   = new Application_Model_DbTable_Grade();
            $students   = $student_DB->getAll($group_id);

            $grades = $grade_DB->getAll( $target_id, $level_id );
            $_students = array();
            foreach ($students as $s) { $s['grades'] = $grades; $_students[] = $s; }

            $this->view->students  = $_students;
            $this->view->group_id  = $group_id;
            $this->view->target_id = $target_id;
            $this->view->level_id  = $level_id;
            $this->view->game_id   = $game_id;
        }
    }
    /*
     * Author : M_AbuAjaj
     * Date   : 24/02/2015
     */
    public function savegradeAction(){
        if( $this->getRequest()->isPost() ){
            $_params = $this->_request->getParams();
            if( $_params['student_id'] && $_params['grade_id'] && $_params['target_id'] && $_params['level_id'] && $_params['game_id'] ){
                $doc_DB = new Application_Model_DbTable_Documentation();
                $new_doc = array(
                    'student_id'  => $_params['student_id'],
                    'target_id'   => $_params['target_id'],
                    'level_id'    => $_params['level_id'],
                    'game_id'     => $_params['game_id'],
                    'grade_id'    => $_params['grade_id'],
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
     * Author : M_AbuAjaj
     * Date   : 18/03/2015
     */
    public function savenotesAction(){
        if( $this->getRequest()->isPost() ){
            $_params = $this->_request->getParams();
            if( $_params['group_id'] && $_params['notes'] ){
                $doc_DB = new Application_Model_DbTable_Documentation();
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
    
}