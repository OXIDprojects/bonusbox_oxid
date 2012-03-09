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
    if(!$bonusboxHandler->isActive())
    {
      return parent::addVoucher($sVoucherId);
    }
    
    try
    {
      $response = $bonusboxHandler->getInterface()->getCoupons();
    }
    catch(Exception $e)
    {
      return parent::addVoucher($sVoucherId);
    }
    
    if(!$voucherSeriesId = $bonusboxHandler->getHelper()->getVoucherSeriesIdByGetCouponResponse($response))
    {
      return parent::addVoucher($sVoucherId);
    }

    $voucherSeries = oxNew('oxVoucherSerie');
    if(!$voucherSeries->load($voucherSeriesId))
    {
      return parent::addVoucher($sVoucherId);
    }

    //generate new voucher and add
    $voucherSeries->mo_bonusbox__generateVoucher($sVoucherId);
    return parent::addVoucher($sVoucherId);
  }
}