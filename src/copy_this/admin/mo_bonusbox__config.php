<?php
/**
 * $Id: $
 */
require_once 'shop_config.php';

/**
 * Configure Bonusbox interface
 */
class mo_bonusbox__config extends Shop_Config
{
  /**
   * class template.
   * @var string
   */
  protected $_sThisTemplate = 'mo_bonusbox__config.tpl';
  
  /**
   * @extend render
   * @return string
   */
  public function render()
  {
    $this->_aViewData['mo_bonusbox__config'] = $this->getConfig()->getShopConfVar('mo_bonusbox__config');
    return $this->_sThisTemplate;
  }
  
  public function mo_bonusbox__getBadgeInfo()
  {
    $bonusboxHandler = mo_bonusbox__main::getInstance();
    
    if(!$bonusboxHandler->isActive())
    {
      return array();
    }
    
    $badges = $bonusboxHandler->getInterface()->getBadges(true);
    
    return $badges;
  }
  
  /**
   * @extend save
   * @return void
   */
  public function save()
  {
    $oxConfig = $this->getConfig();
    $bonusboxConfig = $oxConfig->getParameter('mo_bonusbox__config');
    $oxConfig->saveShopConfVar('arr', 'mo_bonusbox__config', $bonusboxConfig);
  }
}