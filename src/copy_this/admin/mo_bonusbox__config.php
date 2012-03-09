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
  
  /**
   * check whether bonusboxvoucherseries already exists
   * @return type bolean 
   */
  public function mo_bonusbox__getAssignedVoucherseries($aBadgeInfo)
  {
    $bonusboxHandler = mo_bonusbox__main::getInstance();
    $sMo_BB_BadgeId = $bonusboxHandler->getHelper()->getVoucherSeriesIdByBadgeId($aBadgeInfo['id']);

    $voucherseries = oxNew('oxvoucherserie');
    if ($voucherseries->load($sMo_BB_BadgeId))
    {
      // sync data
      return true;
    }
    else
    {
      return $this->mo_bonusbox__generateVoucherseries($aBadgeInfo);
    }
  }

  /**
   * creeate bonusboxvoucherseries
   * @return type boolean 
   */
  protected function mo_bonusbox__generateVoucherseries($aBadge)
  {
    $oVoucherseries = oxNew('oxvoucherserie');

    $bonusboxHandler = mo_bonusbox__main::getInstance();

    $oVoucherseries->setId($bonusboxHandler->getHelper()->getVoucherSeriesIdByBadgeId($aBadge['id']));
    $oVoucherseries->oxvoucherseries__oxvoucherserieid = new oxField($oVoucherseries->getId());
    $oVoucherseries->oxvoucherseries__oxserienr = new oxField($bonusboxHandler->getHelper()->getVoucherSeriesTitleByBadgeTitle($aBadge['title']));
    $oVoucherseries->oxvoucherseries__oxseriedescription = new oxField($aBadge['benefit']);
    return $oVoucherseries->save();
  }
}