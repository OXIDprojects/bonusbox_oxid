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
    $this->_aViewData['mo_bonusbox__badges'] = $this->mo_bonusbox__getBadgeInfo();
    return $this->_sThisTemplate;
  }
  
  protected function mo_bonusbox__getBadgeInfo()
  {
    $bonusboxHandler = mo_bonusbox__main::getInstance();
    
    if(!$bonusboxHandler->isActive())
    {
      return array();
    }
    
    try
    {
      $badges = $bonusboxHandler->getInterface()->getBadges();
    }
    catch(mo_bonusbox__exception__feedback_error $e)
    {
      oxUtilsView::getInstance()->addErrorToDisplay((string)$e);
      return array();
    }
    
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