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
   * override voucher handling for bonusbox coupons, retrieve voucherseries by ID and
   * create adhoc voucher
   * @param type $sVoucherId 
   */
  public function addVoucher($sVoucherId)
  {
    $bonusboxHandler = mo_bonusbox__main::getInstance();
    if(!$bonusboxHandler->isActive())
    {
      return parent::addVoucher($sVoucherId);
    }
    
    //fetch available Bonusbox bagdes
    try
    {
      $response = $bonusboxHandler->getInterface()->getCoupons();
    }
    catch(Exception $e)
    {
      return parent::addVoucher($sVoucherId);
    }
    
    //generate voucherSeries ID
    $voucherSeriesId = $bonusboxHandler->getHelper()->getVoucherSeriesIdByGetCouponResponse($response);
    
    $voucherSeries = oxNew('oxVoucherSerie');
    if(!$voucherSeries->load($voucherSeriesId))
    {
      return parent::addVoucher($sVoucherId);
    }

    //generate new voucher and add
    $voucherSeries->mo_bonusbox__generateVoucher($sVoucherId);
    return parent::addVoucher($sVoucherId);
  }
  
  /**
   * iterate through simple (!) vouchers (stdClass) and check for bonusbox-status
   * @return stdClass 
   */
  public function mo_bonusbox__getBonusboxVoucher()
  {
    foreach($this->getVouchers() as $voucher)
    {
      if($voucher->mo_bonusbox__is_bonus_voucher)
      {
        return $voucher;
      }
    }
    return null;
  }
}