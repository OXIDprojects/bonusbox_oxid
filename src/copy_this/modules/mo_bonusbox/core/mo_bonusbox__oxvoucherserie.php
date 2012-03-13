<?php

/**
 * $Id: $
 */
class mo_bonusbox__oxvoucherserie extends mo_bonusbox__oxvoucherserie_parent
{
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
  
  protected function mo_bonusbox__generateVoucherIdByCouponCode($couponCode)
  {
    return md5($this->getId() . $couponCode);
  }
}