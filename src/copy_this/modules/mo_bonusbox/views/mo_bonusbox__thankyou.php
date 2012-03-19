<?php

/**
 * $Id: $
 */
/**
 * @extend thankyou
 * add bonusbox functionality to thankyou view 
 */
class mo_bonusbox__thankyou extends mo_bonusbox__thankyou_parent
{
  public function render()
  {
    $return = parent::render();
      
    $bonusboxHandler = mo_bonusbox__main::getInstance();
    if(!$bonusboxHandler->isActive())
    {
      return $return;
    }
    
    //check for Bonusbox-vouchers
    $oxbasket = $this->getBasket();
    
    if($voucher = $oxbasket->mo_bonusbox__getBonusboxVoucher())
    {
      //consume/delete coupon
      try 
      {
        $bonusboxHandler->getInterface()->deleteCoupons($voucher->sVoucherNr);
      }
      catch(Exception $e)
      {
        //nothing to do here
      }
    }
    
    //transmit basket-items
    try
    {
      $result = $bonusboxHandler->getInterface()->createSuccessPages($oxbasket);
      $this->_aViewData['mo_bonusbox__iframe_url'] = $result['url'];
    }
    catch (Exception $e)
    {
      $this->_aViewData['mo_bonusbox__iframe_url'] = '';
    }
    
    return $return;
  }
}