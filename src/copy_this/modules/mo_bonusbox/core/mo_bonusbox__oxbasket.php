<?php

/**
 * $Id: $
 */

/**
 * @extend oxbasket 
 * add Bonusbox functionality to oxbasket
 */
class mo_bonusbox__oxbasket extends mo_bonusbox__oxbasket_parent
{
  /**
   * @extend addVoucher
   * override voucher handling for bonusbox coupons
   * @param type $sVoucherId 
   */
  public function addVoucher($sVoucherId)
  {
    $bonusboxHandler = mo_bonusbox__main::getInstance();
    if(!$bonusboxHandler->isActive)
    {
      return parent::addVoucher($sVoucherId);
    }
    
    $response = $bonusboxHandler->getInterface()->getCoupons();
    
    if(!$voucherSeriesId = $bonusboxHandler->getHelper()->getVoucherSeriesIdByGetCouponResponse($response))
    {
      return parent::addVoucher($sVoucherId);
    }
    
  }
}