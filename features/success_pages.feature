Feature: Collecting user data
  In order to get product recommendations for customers from bonusbox
  shop owners will need to share user data
  
  Scenario: Sending cart information
    Given a bonusbox shop with api key "private_api_key"
    a user has the following products in his cart:
    | sku | quantity | code     | price | vat_rate |
    | 123 |        1 | item     |  1000 |     1900 |
    | 124 |        2 | item     |  2000 |     1900 |
    | 225 |        1 | shipping |   499 |     1900 |
    When the checkout was successful
    Then the oxid plugin sends the cart like this curl call:
    """
    curl -H "Accept: application/json,application/vnd.api;ver=1" \
         -X POST https://api.bonusbox.me/success_pages \
         -H "Content-Type: application/json" \
         -u private_api_key: \
         -d '{"items" : [{"sku" : 123, "quantity" : 1, "code" : "item", "price" : 1000, "vat_rate" : 1900 },{"sku" : 124, "quantity" : 2, "code" : "item", "price" : 2000, "vat_rate" : 1900 },{"sku" : 225, "quantity" : 1, "code" : "shipping", "price" : 499, "vat_rate" : 1900 },]'
    ""
"
