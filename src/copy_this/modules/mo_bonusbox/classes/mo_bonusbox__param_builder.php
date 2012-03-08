<?php
/**
 * $Id: $
 */

/**
 * collects necessary data for API-calls
 */
class mo_bonusbox__param_builder 
{
  const API_HOST = 'https://api.bonusbox.me';
  
  /**
   * construct
   * @param type $oxConfig
   * @param type $oxSession
   * @param type $bonusboxConfig
   * @param mo_bonusbox__logger $logger 
   */
  public function __construct($oxConfig, $oxSession, $bonusboxConfig, mo_bonusbox__logger $logger, $isLiveMode)
  {
    $this->oxConfig = $oxConfig;
    $this->oxSession = $oxSession;
    $this->bonusboxConfig = $bonusboxConfig;
    $this->logger = $logger;
    $this->isLiveMode = $isLiveMode;
    
    $this->oxBasket = $this->oxSession->getBasket();
  }
  
  /**
   * build parameters for getAgreedHandlingCharges
   * @return string 
   */
  public function buildGetBadges()
  {
    $params = array();
    $params['user_pwd'] = $this->getAuthParameters(true);
    $params['url'] = self::API_HOST . '/badges';
    $params['request_method'] = 'GET';

    return $params;
  }
  
  /**
   * returns auth-parameters for API-calls
   * @return string 
   */
  protected function getAuthParameters($isPublic)
  {
    if($this->isLiveMode)
    {
      $key = $isPublic ? $this->bonusboxConfig['live_key_public'] : $this->bonusboxConfig['live_key_secret'];
    }
    else
    {
      $key = $isPublic ? $this->bonusboxConfig['test_key_public'] : $this->bonusboxConfig['test_key_secret'];
    }
    
    return $key . ':';
  }
  
  /**
   * multiply float prices to units
   */
  protected function encodePrice($price)
  {
    return (int)round($price * 100);
  }
}