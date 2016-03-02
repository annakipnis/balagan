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
        
        $new_user = array(
            'email'       => $user_data['email'],
            'password'    => $user_data['password'],
            'isAdmin'     => $user_data['isAdmin'],
            'ganID'       => intval($ganID)
        );
        try{
            $user_id = $users_DB->insert( $new_user );
        } catch (Exception $ex) {
            die( json_encode( array('status'=> 'danger', 'msg' => 'Error') ) );
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
            die( json_encode( array('status'=> 'danger', 'msg' => 'Error') ) );
        }
        $this->_redirect("/admin/gan");
    }
    
    public function deleteganAction(){
        $ganID = $this->_request->getParam('g');
        
        $gan_DB = new Application_Model_DbTable_Gan();
        $gan_DB->delete("ganID = $ganID");
        
        $this->_redirect("/admin/gan");
    }
}

