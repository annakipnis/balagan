<?php

/**
 * Description of LoginController
 *
 * @author M_AbuAjaj
 */
class LoginController extends Zend_Controller_Action
{
    function init()
    {
//        $this->admin_is_on = false;
        
        if( Zend_Auth::getInstance()->hasIdentity() )
        {
//            $userInfo = Zend_Auth::getInstance()->getStorage()->read();
//            if ( $userInfo->role == Application_Model_DbTable_Users::$ADMIN ) {
//                $this->admin_is_on = true;
//            }       
             
            Zend_Auth::getInstance()->clearIdentity();
        }
        $this->config = Zend_Registry::get('config');
        $this->lang = Zend_Registry::get('lang');
        $this->msger = $this->_helper->getHelper('FlashMessenger');
        $this->view->flashmsgs = $this->msger->getMessages();
    }
    
    /*
     * Author : M_AbuAjaj
     * Date   : 22/02/15 
     */
    public function indexAction(){
        
    }
    
    
    public function grantAction(){
        
        if( !$this->admin_is_on ) {
           die('ACCESS DENIED!');
        }
        
        $request = $this->getRequest();
        $user_data = $request->getPost();

        //$authAdapter = $this->getAuthAdapter();

        # get the username and password from the form
        $username = trim($user_data['username']);
      
        $password =  'boss';
   
        if (!strlen($username)){
           echo  $this->view->lang->_('MISSING USERNAME');die;
        }
      
        # pass to the adapter the submitted username and password
        $authAdapter = $this->getAuthAdapter();
        $authAdapter->setIdentity($username)
                    ->setCredential($password);

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);

         # is the user a valid one?
        if( $this->admin_is_on )
        {
            # all info about this user from the login table
            # ommit only the password, we don't need that
           $user = new Application_Model_DbTable_Users();
         
            $userInfo = $user->getUserInfo($username);

            # the default storage is a session with namespace Zend_Auth
            $authStorage = $auth->getStorage();
            $authStorage->write($userInfo);
            
            
            if ( $userInfo->role == Application_Model_DbTable_Users::$EMPLOYER ) {
              $jobs = $user->getEmpJobs($userInfo->user_id);
      //        print_r ($jobs);die;
              $user_jobs = new Zend_Session_Namespace('jobs');
              foreach ($jobs as $id) {   
                  $user_jobs->jobs[] = $id->job_id;
              }
              
              $this->_redirect('/empconsole/'); 
            }
                  
                        
            if ( $userInfo->role == Application_Model_DbTable_Users::$SEEKER )
                 $this->_redirect('/sekconsole/'); 
            
            if ( $userInfo->role == Application_Model_DbTable_Users::$ADMIN )
                 $this->_redirect('/addmin/');
              
            
            die ('Invalid user account!');
        }
        else
        {

 echo Zend_Json::encode(array('status' => false, 'msg' => $this->view->lang->_('WRONG LOGIN') ));die;
        }

    }

    
    /**
     * This action check the user login 
     * 
     */
    public function checkAction(){
        $request = $this->getRequest();
        $user_data = $request->getPost();
        
        //$authAdapter = $this->getAuthAdapter();

        # get the username and password from the form
        $username = trim($user_data['username']);
        $password = trim($user_data['password']);
        
        $remember_me = isset($user_data['rememberme']) && $user_data['rememberme'];

        if ( !strlen($username) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('MISSING_USERNAME').'</div>');
            $this->_redirect('/');
        }
        if ( !strlen($password) ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('MISSING_PASSWORD').'</div>');
            $this->_redirect('/');
        }
        # pass to the adapter the submitted username and password
        $authAdapter = $this->getAuthAdapter();
        $authAdapter->setIdentity($username)
                    ->setCredential($password);
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        
        $user_DB = new Application_Model_DbTable_Gan();
        $user_Info = $user_DB->getGanInfo( $username );
        
        if( !$user_Info ){
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('NOT_VERIFIED').'</div>');
            $this->_redirect('/');
        }
        
        # is the user a valid one?
        if($result->isValid())
        {
            # all info about this user from the login table
            # ommit only the password, we don't need that
            $userInfo = $authAdapter->getResultRowObject(null, 'password');
            
            # the default storage is a session with namespace Zend_Auth
            $authStorage = $auth->getStorage();
            $authStorage->write($userInfo);
            // print_r ($userInfo);die;
            // Throw signin event
//            topxiteHooksRegistry::dispatchEvent('onSignIn', $userInfo); 
            # remeber me
            
            // $seconds  = 6048000; // 70 days
            // if ($remember_me) {
            //  Zend_Session::RememberMe($seconds);
            //}
            // else {
            //   Zend_Session::ForgetMe();
            //}
            
//            topxiteHooksRegistry::dispatchEvent('lastLogin', $userInfo); 
            
            //$user = new Application_Model_DbTable_Users();
            //$user->setLastLogin($userInfo->user_id);
            $this->_redirect('/groups');
        }
        else
        {
            $this->msger->addMessage('<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$this->lang->_('WRONG_LOGIN').'</div>');
            $this->_redirect('/');
        }
    }

    /**
     * Gets the adapter for authentication against a database table
     *
     * @return object
     */
    protected function getAuthAdapter()
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        
        $config = Zend_Registry::get('config');
        $salt = $config->topxite->enc->salt;
        
        
        $authAdapter->setTableName('gan')
                    ->setIdentityColumn('email')
                    ->setCredentialColumn('password');
//                    ->setCredentialTreatment('MD5( CONCAT(?,"'.$salt.'") ) AND status');
           
        return $authAdapter;
    }
   
}
?>
