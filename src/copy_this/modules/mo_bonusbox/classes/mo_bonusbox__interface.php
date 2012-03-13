<?php
/**
 * $Id: $
 */

/**
 * delegate API communication
 */
class mo_bonusbox__interface
{
  protected $logger;
  protected $client;
  protected $feedbackHandler;
  protected $paramBuilder;
  
  /**
   * constructor
   * 
   * @param mo_bonusbox__client $client
   * @param mo_bonusbox__feedback_handler $feedbackHandler
   * @param mo_bonusbox__param_builder $paramBuilder
   * @param mo_bonusbox__logger $logger 
   */
  public function __construct(
          mo_bonusbox__client $client, 
          mo_bonusbox__feedback_handler $feedbackHandler, 
          mo_bonusbox__param_builder $paramBuilder, 
          mo_bonusbox__logger $logger)
  {
    $this->client = $client;
    $this->feedbackHandler = $feedbackHandler;
    $this->paramBuilder = $paramBuilder;
    $this->logger = $logger;
  }
  
  /**
   * handle service getBadges
   *  
   * @return type 
   */
  public function getBadges()
  {
    //fetch params
    $params = $this->paramBuilder->buildGetBadges();
    
    //call service
    $result = $this->client->callService($params);
    
    //handle feedback
    return $this->feedbackHandler->handleGetBadges($result);
  }
  
  /**
   * handle service getCoupons
   *  
   * @return type 
   */
  public function getCoupons()
  {
    //fetch params
    $params = $this->paramBuilder->buildGetCoupons();
    
    //call service
    $result = $this->client->callService($params);
    
    //handle feedback
    return $this->feedbackHandler->handleGetCoupons($result);
  }
  
  public function createSuccessPages(oxBasket $oxbasket)
  {
    //fetch params
    $params = $this->paramBuilder->buildCreateSuccessPages($oxbasket);

    //call service
    $result = $this->client->callService($params);

    //handle feedback
    return $this->feedbackHandler->handleCreateSuccessPages($result);
  }
}