<?php

/**
 * Description of Application_View_Helper_ShowTaxonomy
 *
 * @author M_AbuAjaj
 */
require_once 'Zend/View/Helper/Abstract.php';
class Application_View_Helper_Baseurl extends Zend_View_Helper_Abstract{
  
  public function baseurl()
  {
      $config = Zend_Registry::get('config');
      return 'http://'.$config->balagan->url.'/';
  }
  
}

?>
