<?php
/**
 * $Id: $
 */
/**
 * handle communication with Bonusbox
 */
abstract class mo_bonusbox__client
{
  /**
   * construct
   * 
   * @param mo_bonusbox__logger $logger 
   */
  public function __construct(mo_bonusbox__logger $logger)
  {
    $this->logger = $logger;
  }
  
  /**
   * call Bonusbox API with given parameters
   * 
   * @param array $params
   * @return string 
   */
  abstract public function callService($params);
}