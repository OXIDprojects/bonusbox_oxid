<?php

/**
 * $Id: $
 */
class mo_bonusbox__oxvoucherserie extends mo_bonusbox__oxvoucherserie_parent
{
  /**
   * generate adhoc voucher
   * @param type $couponCode
   * @return type 
   */
  public function mo_bonusbox__generateVoucher($couponCode)
  {
    $voucher = oxNew("oxvoucher");
    $id = $this->mo_bonusbox__generateVoucherIdByCouponCode($couponCode);
    
    if($voucher->load($id))
    {
      return $voucher;
    }
    
    $voucher->setId($id);
    $voucher->oxvouchers__oxvoucherserieid = new oxField($this->getId());
    $voucher->oxvouchers__oxvouchernr = new oxField($couponCode);
    $voucher->save();
    
    return $voucher;
  }
  
  /**
   * generate ID - has to be dynamic (e.g. with session-id) if DELETE coupon should fail for any reasons
   * @param type $couponCode
   * @return type 
   */
  protected function mo_bonusbox__generateVoucherIdByCouponCode($couponCode)
  {
    return md5($this->getSession()->getId() . $this->getId() . $couponCode);
  }
}