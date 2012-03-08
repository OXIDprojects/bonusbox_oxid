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
    
    $badges = $bonusboxHandler->getInterface()->getBadges(true);
    
    if(!empty($badges['error']))
    {
      $this->addAdminError($badges['error']);
      return array();
    }
    
    return $badges;
  }
  
  /**
   * add errors to display
   *
   * @param type $result 
   */
  protected function addAdminError($error)
  {
    oxUtilsView::getInstance()->addErrorToDisplay($error);
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