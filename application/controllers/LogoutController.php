<?php

/**
 * Description of LogoutController
 *
 * @author M_AbuAjaj
 */
class LogoutController extends Zend_Controller_Action
{
    function init()
    {
        $this->admin_is_on = false;
        
        if( Zend_Auth::getInstance()->hasIdentity() )
        {
//            $userInfo = Zend_Auth::getInstance()->getStorage()->read();
//            if ( $userInfo->role == Application_Model_DbTable_Users::$ADMIN ) {
//                $this->admin_is_on = true;
//            }       
            
            Zend_Auth::getInstance()->clearIdentity();
        }
    }
    
    /*
     * Author : M_AbuAjaj
     * Date   : 22/02/15 
     */
    public function indexAction(){
        # clear everything - session is cleared also!
        Zend_Auth::getInstance()->clearIdentity();
        
        $this->_redirect('/');
    }
    
}
?>
