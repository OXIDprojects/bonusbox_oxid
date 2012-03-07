<?php 
/**
 * $Id: $
 */

function mo_bonusbox__autoload($class)
{
  $classes = array(
      'mo_bonusbox__client' => '/classes/mo_bonusbox__client.php',
      'mo_bonusbox__feedback_handler' => '/classes/mo_bonusbox__feedback_handler.php',
      'mo_bonusbox__interface' => '/classes/mo_bonusbox__interface.php',
      'mo_bonusbox__logger' => '/classes/mo_bonusbox__logger.php',
      'mo_bonusbox__main' => '/classes/mo_bonusbox__main.php',
      'mo_bonusbox__param_builder' => '/classes/mo_bonusbox__param_builder.php',
      );
  
  $cn = strtolower($class);
  if (isset($classes[$cn]))
  {
    require dirname(__FILE__) . $classes[$cn];
  }
}

spl_autoload_register('mo_bonusbox__autoload');