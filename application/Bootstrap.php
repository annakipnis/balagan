<?php

//use Zend\Acl;
//use Zend\Acl\Role\GenericRole as Role;
//use Zend\Acl\Resource\GenericResource as Resource;

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initDoctype()
    {
        date_default_timezone_set('Asia/Jerusalem');

        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
        
        $view->addHelperPath('../application/views/helpers/', 'Application_View_Helper');
        
        
        $lang_sess = new Zend_Session_Namespace('lang');
        
        $lang = 'he';
        if ( isset($lang_sess->lang) ) {
           $lang = $lang_sess->lang;
        } 
        if ( isset($_GET['lang']) ) {
            $lang_sess->lang = $_GET['lang'];
            $lang = $lang_sess->lang;
        } 
      
        $translate = new Zend_Translate(
                array(
                    'adapter' => 'csv',
                    'content' => '../lang/'. $lang.'.csv',
                    'locale'  =>  $lang
                ));
        
        
        $view->lang = $translate;
        Zend_Registry::set('lang', $translate);
        
        /**Permissions**/
        $acl = new Zend_Acl();
        $acl->addRole(new Zend_Acl_Role('user'))
            ->addRole(new Zend_Acl_Role('admin'), 'user');
                
        $acl->addResource(new Zend_Acl_Resource('groups'));
        $acl->addResource(new Zend_Acl_Resource('students'));
        $acl->addResource(new Zend_Acl_Resource('fields'));
        $acl->addResource(new Zend_Acl_Resource('planning'));
        $acl->addResource(new Zend_Acl_Resource('documentation'));
        $acl->addResource(new Zend_Acl_Resource('managegroups'));
        $acl->addResource(new Zend_Acl_Resource('managestudents'));
        
        $acl->addResource(new Zend_Acl_Resource('manage fields'));
        $acl->addResource(new Zend_Acl_Resource('manage goals and games'));
        $acl->addResource(new Zend_Acl_Resource('manage gans and users'));

        $acl->allow('user', 'groups', array('read','edit'));
        $acl->allow('user', 'students', array('read','edit'));
        $acl->allow('user', 'fields', array('read','edit'));
        $acl->allow('user', 'planning', array('read','edit'));
        $acl->allow('user', 'documentation', array('read','edit'));
        $acl->allow('user', 'managegroups', array('read','edit'));
        $acl->allow('user', 'managestudents', array('read','edit'));
        $acl->allow('admin');

        $view->acl = $acl;
        Zend_Registry::set('acl', $acl);
    }

    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions(),true);
        Zend_Registry::set('config', $config);
        
        $view = $this->getResource('view');
        $view->version = $config->balagan->version;
        
        # adding Zend logger
        $writer_fb = new Zend_Log_Writer_Firebug();
        //  $writer_fs = new Zend_Log_Writer_Stream('../logs/'.date('Y-m-d').'-nj.log');
        $logger = new Zend_Log($writer_fb);
        
        Zend_Registry::set('logger', $logger);
        
    }
   
    protected function _initHooks()
    {
       
        # Send Mail To Nearby Youth Center With Seeker Data
#         topxiteHooksRegistry::addHook('onYoungCenterAllow', 'Application_Model_SendMail', 'youngCenterSendMail', $params = array());
         
    }

}

