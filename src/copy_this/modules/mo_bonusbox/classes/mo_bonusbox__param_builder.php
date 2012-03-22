<?php
/**
 * $Id: $
 */

/**
 * collects necessary data for API-calls
 */
abstract class mo_bonusbox__param_builder 
{
  const API_HOST = 'https://api.bonusbox.me';
  
  const ITEM_CODE__SHIPPING = 'shipping';
  const ITEM_CODE__INSURANCE = 'insurance';
  const ITEM_CODE__ITEM = 'item';
  const ITEM_CODE__SERVICE = 'service';
  
  /**
   * build parameters for getAgreedHandlingCharges
   * @return array 
   */
  abstract public function buildGetBadges();
  
  /**
   * build parameters for getAgreedHandlingCharges
   * @return array 
   */
  abstract public function buildGetCoupons();
  
  /**
   * build parameters for createSuccessPages
   * @param oxBasket $oxbasket
   * @return array
   */
  abstract public function buildCreateSuccessPages($basket);
  
  /**
   * create parameters for deleteCoupons
   * @param oxBasket $oxbasket
   * @return array
   */
  abstract public function buildDeleteCoupons($couponCode);
}