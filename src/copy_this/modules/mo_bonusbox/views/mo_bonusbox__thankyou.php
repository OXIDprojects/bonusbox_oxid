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
      
    if(!mo_bonusbox__main::getInstance()->isActive())
    {
      return $return;
    }
    
    //check for Bonusbox-vouchers
    $basket = $this->getBasket();
    if($basket->mo_bonusbox__hasBonusboxVoucher())
    {
      //consume/delete coupon
      try 
      {
      
      }
      catch(Exception $e)
      {
        
      }
    }
    
    //transmit basket-items
    try
    {
      $result = mo_bonusbox__main::getInstance()->getInterface()->createSuccessPages($basket);
      $this->_aViewData['mo_bonusbox__iframe_url'] = $result['url'];
    }
    catch (Exception $e)
    {
      $this->_aViewData['mo_bonusbox__iframe_url'] = '';
    }
    
    return $return;
  }
}