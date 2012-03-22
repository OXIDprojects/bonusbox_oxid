<?php 
/**
 * $Id: $
 */

function mo_bonusbox__autoload($class)
{
  $classes = array(
      'mo_bonusbox__client' => '/classes/mo_bonusbox__client.php',
      'mo_bonusbox__client__curl' => '/classes/mo_bonusbox__client__curl.php',
      'mo_bonusbox__feedback_handler' => '/classes/mo_bonusbox__feedback_handler.php',
      'mo_bonusbox__interface' => '/classes/mo_bonusbox__interface.php',
      'mo_bonusbox__logger' => '/classes/mo_bonusbox__logger.php',
      'mo_bonusbox__main' => '/classes/mo_bonusbox__main.php',
      'mo_bonusbox__param_builder' => '/classes/mo_bonusbox__param_builder.php',
      'mo_bonusbox__param_builder__oxid' => '/classes/mo_bonusbox__param_builder__oxid.php',
      'mo_bonusbox__helper' => '/classes/mo_bonusbox__helper.php',
      'mo_bonusbox__helper__oxid' => '/classes/mo_bonusbox__helper__oxid.php',
      'mo_bonusbox__exception__feedback_error' => '/classes/exceptions/mo_bonusbox__exception__feedback_error.php',
      );
  
  $cn = strtolower($class);
  if (isset($classes[$cn]))
  {
    require dirname(__FILE__) . $classes[$cn];
  }
}

spl_autoload_register('mo_bonusbox__autoload');