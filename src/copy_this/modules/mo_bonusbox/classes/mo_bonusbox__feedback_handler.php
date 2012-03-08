<?php
/**
 * $Id: $
 */

/**
 * handle responses from API-calls
 */
class mo_bonusbox__feedback_handler
{
  /**
   * construct
   * 
   * @param type $oxConfig
   * @param type $oxSession
   * @param mo_bonusbox__logger $logger 
   */
  public function __construct(mo_bonusbox__logger $logger)
  {
    $this->logger = $logger;
  }
  
  /**
   * decode results for processing
   * 
   * @param type $result
   * @return type 
   */
  protected function decodeResult($result, $flatIndexes = array())
  {
    $result = json_decode($result, true);
    
    foreach($flatIndexes as $index)
    {
      $result = $this->flattenResult($result, $index);
    }
    return $result;
  }
  
  /**
   * handle API-call getBadges
   * 
   * @param type $result
   * @return type 
   */
  public function handleGetBadges($result)
  {
    $result = $this->decodeResult($result, array('badge'));
    
    if ($error = $this->getError($result))
    {
      return $error;
    }
    
    return $result['badge'];
  }
  
  /**
   * extract errors from response
   *
   * @param type $result
   * @return type 
   */
  protected function getError($result)
  {
    if(empty($result['error']))
    {
      return false;
    }
    
    return array('error' => 'Bonusbox Error "' . $result['error']['type'] . '": ' . $result['error']['message']);
  }
  
  /**
   * flatten result for easy processing
   * @param type $result
   * @param type $index 
   */
  protected function flattenResult($result, $index)
  {
    if(empty($result[0][$index]))
    {
      return $result;
    }
    
    if(!empty($result[$index]))
    {
      return $result;
    }
    
    foreach($result as $key => $entry)
    {
      $result[$index][] = $entry[$index];
      unset($result[$key]);
    }
    
    return $result;
  }
}