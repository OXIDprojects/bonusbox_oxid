<?php

/**
 * $Id: $
 */
/**
 * @extend oxvoucher
 * add Bonusbox functionality to voucher 
 */
class mo_bonusbox__oxvoucher extends mo_bonusbox__oxvoucher_parent
{
  /**
   * check whether voucher belongs to bonusbox series
   * @return type 
   */
  public function mo_bonusbox__isBonusboxVoucher()
  {
    return strpos($this->oxvouchers__oxvoucherserieid->value, 'mo_bonusbox__') === 0;
  }
  
  /**
   * @extend unMarkAsReserved
   * delete adhoc-generated voucher 
   */
  public function unMarkAsReserved()
  {
    parent::unMarkAsReserved();
    
    if($this->mo_bonusbox__isBonusboxVoucher())
    {
      $this->delete();
    }
  }
  
  /**
   * add isBonusboxVoucher info to simple Object
   * @extend getSimpleVoucher
   */
  public function getSimpleVoucher()
  {
    $simpleObject = parent::getSimpleVoucher();
    $simpleObject->mo_bonusbox__is_bonus_voucher = $this->mo_bonusbox__isBonusboxVoucher();
    return $simpleObject;
  }
}