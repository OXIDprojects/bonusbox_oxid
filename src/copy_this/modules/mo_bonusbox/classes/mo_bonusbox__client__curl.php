<?php
/**
 * $Id: $
 */
/**
 * handle communication with Bonusbox via curl
 */
class mo_bonusbox__client__curl extends mo_bonusbox__client
{
  const HEADER_ACCEPT = 'Accept: application/json,application/vnd.api;ver=1';
  const HEADER_CONTENT_TYPE = 'Content-Type: application/json';
  
  /**
   * call Bonusbox API with given parameters
   * 
   * @param array $params
   * @return string 
   */
  public function callService($params)
  {
    $this->logger->logExecution($params, true);
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $params['url']);
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $params['request_method']);
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(self::HEADER_ACCEPT, self::HEADER_CONTENT_TYPE));
    
    if(!empty($params['post_data']))
    {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $params['post_data']);
    }
    
    curl_setopt($ch, CURLOPT_USERPWD, $params['user_pwd']);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $result = $this->curlExec($ch);

    curl_close($ch);
    
    $this->logger->logExecution($result);
    return $result;
  }
  
  /**
   * execute curl operation
   *
   * @param type $ch
   * @return type 
   */
  protected function curlExec($ch) 
  {
    return curl_exec($ch);
  }
}