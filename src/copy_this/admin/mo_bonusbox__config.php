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
  
  /**
   * retrieve badges
   * @return type array 
   */
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
   * check whether bonusboxvoucherseries already exists and create if not
   * @return type boolean 
   */
  public function mo_bonusbox__getAssignedVoucherseries($aBadgeInfo)
  {
    $mo_bonusbox__badgeId = $this->mo_bonusbox__getVoucherSeriesId($aBadgeInfo['id']);

    $oVoucherseries = oxNew('oxvoucherserie');
    
    if (!$oVoucherseries->load($mo_bonusbox__badgeId))
    {
      return !$this->mo_bonusbox__generateVoucherseries($aBadgeInfo);
    }
      
    $sDefTimeStamp = oxUtilsDate::getInstance()->formatDBDate( '-' );
    if(strtotime( $oVoucherseries->oxvoucherseries__oxenddate->value ) > time() || !$oVoucherseries->oxvoucherseries__oxenddate->value || $oVoucherseries->oxvoucherseries__oxenddate->value == $sDefTimeStamp)
      {  
        return true;
      }
      
    return false;   
  }

  /**
   * create bonusboxvoucherseries
   * @return type boolean 
   */
  protected function mo_bonusbox__generateVoucherseries($aBadge)
  {
    $oVoucherseries = oxNew('oxvoucherserie');

    $oVoucherseries->setId($this->mo_bonusbox__getVoucherSeriesId($aBadge['id']));
    $oVoucherseries->oxvoucherseries__oxvoucherserieid = new oxField($oVoucherseries->getId());
    $oVoucherseries->oxvoucherseries__oxserienr = new oxField($this->mo_bonusbox__getVoucherSeriesTitle($aBadge['title']));
    $oVoucherseries->oxvoucherseries__oxseriedescription = new oxField($aBadge['benefit']);
    $oVoucherseries->oxvoucherseries__oxenddate = new oxField("2000-01-01 00:00:00");
    $oVoucherseries->oxvoucherseries__oxallowuseanother = new oxField(1);
    return $oVoucherseries->save();
  }
  
  /**
   * build VoucherSeriesID
   * @return type string
   */
  public function mo_bonusbox__getVoucherSeriesId($sBadgeId)
  {
    $bonusboxHandler = mo_bonusbox__main::getInstance();
    return $bonusboxHandler->getHelper()->getVoucherSeriesIdByBadgeId($sBadgeId);
  }
  
  /**
   * build VoucherSeriesTitle
   * @return type string
   */
  public function mo_bonusbox__getVoucherSeriesTitle($sBadgeTitle)
  {
    $bonusboxHandler = mo_bonusbox__main::getInstance();
    return $bonusboxHandler->getHelper()->getVoucherSeriesTitleByBadgeTitle($sBadgeTitle);
  }
}