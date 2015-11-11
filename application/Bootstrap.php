<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initDoctype()
    {
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
    }

    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions());
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

