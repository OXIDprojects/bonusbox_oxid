<?php
/**
 * $Id: $
 */

/**
 * collects necessary data for API-calls
 */
class mo_bonusbox__param_builder 
{
  const API_HOST = 'https://api.bonusbox.me';
  
  const ITEM_CODE__SHIPPING = 'shipping';
  const ITEM_CODE__INSURANCE = 'insurance';
  const ITEM_CODE__ITEM = 'item';
  const ITEM_CODE__SERVICE = 'service';
  
  /**
   * construct
   * @param type $oxConfig
   * @param type $oxSession
   * @param type $bonusboxConfig
   * @param mo_bonusbox__logger $logger 
   */
  public function __construct($oxConfig, $oxSession, $bonusboxConfig, mo_bonusbox__logger $logger, $isLiveMode)
  {
    $this->oxConfig = $oxConfig;
    $this->oxSession = $oxSession;
    $this->bonusboxConfig = $bonusboxConfig;
    $this->logger = $logger;
    $this->isLiveMode = $isLiveMode;
  }
  
  /**
   * build parameters for getAgreedHandlingCharges
   * @return string 
   */
  public function buildGetBadges()
  {
    $params = array();
    $params['user_pwd'] = $this->getAuthParameters(true);
    $params['url'] = self::API_HOST . '/badges';
    $params['request_method'] = 'GET';

    return $this->filterParams($params);
  }
  
  /**
   * build parameters for getAgreedHandlingCharges
   * @return string 
   */
  public function buildGetCoupons()
  {
    $params = array();
    $params['user_pwd'] = $this->getAuthParameters(true);
    $params['url'] = self::API_HOST . '/coupons/' . $this->oxConfig->getParameter('voucherNr');
    $params['request_method'] = 'GET';

    return $this->filterParams($params);
  }
  
  public function buildCreateSuccessPages(oxBasket $oxbasket)
  {
    $params = array();
    $params['user_pwd'] = $this->getAuthParameters(false);
    $params['url'] = self::API_HOST . '/success_pages';
    $params['request_method'] = 'POST';
    
    $postData = array();
    $postData['addresses'] = $this->getUserData();
    $postData['items'] = $this->getItemData($oxbasket);
    $postData['order_number'] = $oxbasket->getOrderId();
    $postData['style_url'] = '';
    $postData['currency'] = $this->getCurrencyParameter();
    
    $params['post_data'] = $postData;
    
    return $this->filterParams($params);
  }


  /**
   * returns auth-parameters for API-calls
   * @return string 
   */
  protected function getAuthParameters($isPublic)
  {
    if($this->isLiveMode)
    {
      $key = $isPublic ? $this->bonusboxConfig['live_key_public'] : $this->bonusboxConfig['live_key_secret'];
    }
    else
    {
      $key = $isPublic ? $this->bonusboxConfig['test_key_public'] : $this->bonusboxConfig['test_key_secret'];
    }
    
    return $key . ':';
  }
  
  /**
   * multiply float to units
   */
  protected function encodeDecimal($decimal)
  {
    return (int)round($decimal * 100);
  }
  
  /**
   * fetch billing & delivery address data
   * @return type 
   */
  protected function getUserData()
  {
    $userData = array();
    $oxUser = $this->oxConfig->getUser();

    if (!$oxUser)
    {
      return $userData;
    }
    
    $billingAddress = array();
    $deliveryAddress = array();
    
    //billaddress
    $billingAddress['code'] = 'billing';
    $billingAddress['city'] = $oxUser->oxuser__oxcity->value;
    $billingAddress['company'] = $oxUser->oxuser__oxcompany->value;
    $billingAddress['country'] = $this->getCountryCode($oxUser->oxuser__oxcountryid->value);
    $billingAddress['email'] = $oxUser->oxuser__oxusername->value;
    $billingAddress['first_name'] = $oxUser->oxuser__oxfname->value;
    $billingAddress['last_name'] = $oxUser->oxuser__oxlname->value;
    $billingAddress['phone'] = $oxUser->oxuser__oxfon->value;
    $billingAddress['street'] = $oxUser->oxuser__oxstreet->value . ' ' . $oxUser->oxuser__oxstreetnr->value;;
    $billingAddress['postcode'] = $oxUser->oxuser__oxzip->value;
    
    if ($oxUser->getSelectedAddressId())
    {
      $oxDeliveryAddress = $oxUser->getSelectedAddress();
      
      //deliveryaddress
      $deliveryAddress['code'] = 'shipping';
      $deliveryAddress['city'] = $oxDeliveryAddress->oxaddress__oxcity->value;
      $deliveryAddress['company'] = $oxDeliveryAddress->oxaddress__oxcompany->value;
      $deliveryAddress['country'] = $this->getCountryCode($oxDeliveryAddress->oxaddress__oxcountryid->value);
      $deliveryAddress['first_name'] = $oxDeliveryAddress->oxaddress__oxfname->value;
      $deliveryAddress['last_name'] = $oxDeliveryAddress->oxaddress__oxlname->value;
      $deliveryAddress['street'] = $oxDeliveryAddress->oxaddress__oxstreet->value . ' ' . $oxDeliveryAddress->oxaddress__oxstreetnr->value;
      $deliveryAddress['postcode'] = $oxDeliveryAddress->oxaddress__oxzip->value;
    }
    else
    {
      $deliveryAddress = $billingAddress;
      $deliveryAddress['code'] = 'shipping';
    }

    return array($billingAddress, $deliveryAddress);
  }
  
  /**
   * return iso-code for country
   * @param type $oxCountryId
   * @return type 
   */
  protected function getCountryCode($oxCountryId)
  {
    $country = oxNew('oxcountry');
    if ($country->load($oxCountryId))
    {
      return $country->oxcountry__oxisoalpha2->value;
    }
    return '';
  }
  
  /**
   * fetch article-data from basket
   * @return type 
   */
  protected function getItemData(oxBasket $oxbasket)
  {
    $itemData = array();

    foreach ($oxbasket->getContents() as $basketItem)
    {
      $oxArticle = $basketItem->getArticle();
      $unitPrice = $basketItem->getUnitPrice();

      $itemData[] = $this->getListItem(
                      $oxArticle->oxarticles__oxartnum->value,
                      $unitPrice->getNettoPrice(), 
                      $basketItem->getAmount(),
                      $oxArticle->oxarticles__oxtitle->value, 
                      $oxArticle->oxarticles__oxlongdesc->value, 
                      self::ITEM_CODE__ITEM,
                      $basketItem->getPrice()->getVat(),
                      $oxArticle->getLink(),
                      $oxArticle->getPictureUrl()
              );

      if ($wrapping = $basketItem->getWrapping())
      {
        $itemData[] = $this->getWrappingItem(
                        $wrapping, 
                        $oxArticle->oxarticles__oxartnum->value, 
                        $basketItem->getAmount());
      }
    }

    //gift card
    if($giftCardItem = $this->getGiftCardItem($oxbasket))
    {
      $itemData[] = $giftCardItem;
    }

    //delivery charge
    if($deliveryChargeItem = $this->getDeliveryChargeItem($oxbasket))
    {
      $itemData[] = $deliveryChargeItem;
    }

    return $itemData;
  }
  
  /**
   * get params for wrapping option
   * 
   * @param oxwrapping $wrapping
   * @param type $artnum
   * @param type $quantity
   * @return type 
   */
  protected function getWrappingItem(oxwrapping $wrapping, $artnum, $quantity)
  {
    $oxPrice = $wrapping->getWrappingPrice();
    
    return $this->getListItem(
            'wrapping_' . $artnum,
            $oxPrice->getNettoPrice(), 
            $quantity, 
            'Wrapping ' . $artnum, 
            '',
            self::ITEM_CODE__SERVICE, 
            $oxPrice->getVat(),
            '',
            '');
  }
  
  /**
   * get params for giftcard option
   * 
   * @param type $articleData 
   */
  protected function getGiftCardItem(oxbasket $oxbasket)
  {
    if(!$card = $oxbasket->getCard())
    {
      return false;
    }
    
    $price = $card->getWrappingPrice();

    return $this->getListItem(
            'giftcard', 
            $price->getNettoPrice(), 
            1,
            'Giftcard', 
            '',
            self::ITEM_CODE__SERVICE,
            $price->getVat(),
            '',
            ''
            );
  }
  
  /**
   * get delivery charge from basket
   * @param type $articleData 
   */
  protected function getDeliveryChargeItem(oxBasket $oxbasket)
  {
    if((!$charge = $oxbasket->getCosts('oxdelivery')) || !$charge->getNettoPrice())
    {
      return false;
    }
    
    return $this->getListItem(
            'shipment', 
            $charge->getNettoPrice(), 
            1,
            'Shipment', 
            '',
            self::ITEM_CODE__SHIPPING,
            $charge->getVat(),
            '',
            ''
            );
  }
  
  /**
   * get params for article-list item
   * 
   * @param type $ident
   * @param type $title
   * @param type $code
   * @param type $quantity
   * @param type $netPrice
   * @param type $tax_rate
   * @return type 
   */
  protected function getListItem($ident, $netPrice, $quantity, $title, $description, $code, $tax_rate, $link, $image_url)
  {
    $listItem = array();
    
    $listItem['sku'] = $ident;
    $listItem['price'] = $this->encodeDecimal($netPrice);
    $listItem['quantity'] = $quantity;
    $listItem['title'] = $title;
    $listItem['description'] = $description;
    $listItem['code'] = $code;
    $listItem['vat_rate'] = $this->encodeDecimal($tax_rate);
    $listItem['landing_page'] = $link;
    $listItem['image_url'] = $image_url;
    
    return $listItem;
  }
  
  protected function getCurrencyParameter()
  {
    $currency =  $this->oxConfig->getActShopCurrencyObject();
    return $currency->name;
  }
  
  /**
   * encode data if necessary
   * 
   * @param type $params
   * @return type 
   */
  protected function filterParams($params)
  {
    if(!empty($this->bonusboxConfig['encode_utf8']))
    {
      $params = array_map('utf8_encode', $params);
    }
    
    if(!empty($params['post_data']))
    {
      $params['post_data'] = json_encode($params['post_data']);
    }
    
    return $params;
  }
}