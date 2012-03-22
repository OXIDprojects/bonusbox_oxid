<?php
/**
 * $Id: $
 */

/**
 * main singleton wiring class
 */
class mo_bonusbox__main
{
  /**
   * configuration
   */
  const SHOP_SOFTWARE = 'oxid';
  const CLIENT_INTERFACE = 'curl';
  
  static protected $instance = null;
  
  protected $logger = null;
  protected $paramBuilder = null;
  protected $feedbackHandler = null;
  protected $client = null;
  protected $interface = null;
  protected $helper = null;
  
  /**
   * singleton accessor
   * 
   * @return type 
   */
  static public function getInstance()
  {
    if(is_null(self::$instance))
    {
      self::$instance = new mo_bonusbox__main();
    }
    return self::$instance;
  }
  
  /**
   * mock instance for unit-tests
   * 
   * @param type $mock 
   */
  static public function unitTestSetMockInstance($mock) 
  {
    self::$instance = $mock;
  }
  
  /**
   * remove mock instance
   */
  static public function unitTestRemoveMockInstance() 
  {
    self::$instance = null;
  }
  
  /**
   * constructor
   */
  protected function __construct()
  {
    $this->oxConfig = oxConfig::getInstance();
    $this->bonusboxConfig = $this->oxConfig->getShopConfVar('mo_bonusbox__config');
  }
  
  /**
   * config-getter
   * 
   * @return type 
   */
  public function getBonusboxConfig()
  {
    return $this->bonusboxConfig;
  }
  
  /**
   * logger getter
   * 
   * @return type 
   */
  public function getLogger()
  {
    if(is_null($this->logger))
    {
      $this->logger = new mo_bonusbox__logger();
    }
    return $this->logger;
  }
  
  /**
   * param builder getter
   * 
   * @return type 
   */
  public function getParamBuilder()
  {
    if(is_null($this->paramBuilder))
    {
      $builderClass = 'mo_bonusbox__param_builder__' . self::SHOP_SOFTWARE;
      $this->paramBuilder = new $builderClass($this->oxConfig, $this->getBonusboxConfig(), $this->getLogger(), $this->isLiveMode());
    }
    return $this->paramBuilder;
  }
  
  /**
   * getter method for feedback handler
   * 
   * @return type 
   */
  public function getFeedbackHandler()
  {
    if(is_null($this->feedbackHandler))
    {
      $this->feedbackHandler = new mo_bonusbox__feedback_handler($this->getLogger());
    }
    return $this->feedbackHandler;
  }
  
  /**
   * client getter
   * 
   * @return type 
   */
  public function getClient()
  {
    if(is_null($this->client))
    {
      $clientClass = 'mo_bonusbox__client__' . self::CLIENT_INTERFACE;
      $this->client = new $clientClass($this->getLogger());
    }
    return $this->client;
  }
  
  /**
   * getter for interface
   * 
   * @return type 
   */
  public function getInterface()
  {
    if (is_null($this->interface))
    {
      $this->interface = new mo_bonusbox__interface($this->getClient(), $this->getFeedbackHandler(), $this->getParamBuilder(), $this->getLogger());
    }
    return $this->interface;
  }
  
  /**
   * getter for helper
   * @return type 
   */
  public function getHelper()
  {
    if (is_null($this->helper))
    {
      $helperClass = 'mo_bonusbox__helper__' . self::SHOP_SOFTWARE;
      $this->helper = new $helperClass();
    }
    return $this->helper;
  }

  /**
   * check for live-mode flag
   * 
   * @return type 
   */
  public function isLiveMode()
  {
    $config = $this->getBonusboxConfig();
    return !empty($config['is_live_mode']);
  }
  
  /**
   * validate if necessary data is set
   */
  public function isActive()
  {
    $config = $this->getBonusboxConfig();
    
    if(empty($config))
    {
      return false;
    }
    
    if($this->isLiveMode())
    {
      if(empty($config['live_key_public']) || empty($config['live_key_public']))
      {
        return false;
      }
    }
    else
    {
      if(empty($config['test_key_public']) || empty($config['test_key_public']))
      {
        return false;
      }
    }
    
    return true;
  }
}