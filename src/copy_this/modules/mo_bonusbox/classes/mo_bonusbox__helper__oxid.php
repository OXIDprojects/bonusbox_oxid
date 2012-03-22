<?php

/**
 * $Id: $
 */

/**
 * helper methods 
 */
class mo_bonusbox__helper__oxid extends mo_bonusbox__helper
{
  /**
   * check for badge information in response and translate to voucherSeriesId
   * @param type $response
   * @return mixed
   */
  public function getVoucherSeriesIdByGetCouponResponse($response)
  {
    if(empty($response['user']['badge']['id']))
    {
      return false;
    }
    
    return $this->getVoucherSeriesIdByBadgeId($response['user']['badge']['id']);
  }
  
  public function getVoucherSeriesIdByBadgeId($badgeId)
  {
    return 'mo_bonusbox__' . $badgeId;
  }
  
  public function getVoucherSeriesTitleByBadgeTitle($badgeTitle)
  {
    return 'Bonusbox: ' . $badgeTitle;
  }
}