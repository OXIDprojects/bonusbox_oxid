[{include file="headitem.tpl" title="MO_BONUSBOX__SETUP_TITLE"|oxmultilangassign}]

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post">
  <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
  <input type="hidden" name="fnc" value="save">
  [{$oViewConf->getHiddenSid()}]

  <table cellspacing="0" cellpadding="0" border="0" style="width:100%;height:100%;">
    
    <tr>
      <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_BONUSBOX__LIVE_MODE" }]</td>
      <td valign="top" class="edittext">
        <input type="hidden" name="mo_bonusbox__config[is_live_mode]" value="0" />
        <input type="checkbox" 
               class="editinput" 
               name="mo_bonusbox__config[is_live_mode]" 
               value="1" 
               [{if $mo_bonusbox__config.is_live_mode}]checked="checked"[{/if}] />
               [{ oxinputhelp ident="MO_BONUSBOX__LIVE_MODE_HELP" }]
      </td>
    </tr>
    
    <tr>
      <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_BONUSBOX__LIVE_KEY_PUBLIC" }]</td>
      <td valign="top" class="edittext">
        <input type="text" class="editinput" name="mo_bonusbox__config[live_key_public]" value="[{$mo_bonusbox__config.live_key_public}]"/>
        [{ oxinputhelp ident="MO_BONUSBOX__LIVE_KEY_PUBLIC_HELP" }]
      </td>
    </tr>
    
    <tr>
      <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_BONUSBOX__LIVE_KEY_SECRET" }]</td>
      <td valign="top" class="edittext">
        <input type="text" class="editinput" name="mo_bonusbox__config[live_key_secret]" value="[{$mo_bonusbox__config.live_key_secret}]"/>
        [{ oxinputhelp ident="MO_BONUSBOX__LIVE_KEY_SECRET_HELP" }]
      </td>
    </tr>
    
    <tr>
      <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_BONUSBOX__TEST_KEY_PUBLIC" }]</td>
      <td valign="top" class="edittext">
        <input type="text" class="editinput" name="mo_bonusbox__config[test_key_public]" value="[{$mo_bonusbox__config.test_key_public}]"/>
        [{ oxinputhelp ident="MO_BONUSBOX__TEST_KEY_PUBLIC_HELP" }]
      </td>
    </tr>
    
    <tr>
      <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_BONUSBOX__TEST_KEY_SECRET" }]</td>
      <td valign="top" class="edittext">
        <input type="text" class="editinput" name="mo_bonusbox__config[test_key_secret]" value="[{$mo_bonusbox__config.test_key_secret}]"/>
        [{ oxinputhelp ident="MO_BONUSBOX__TEST_KEY_SECRET_HELP" }]
      </td>
    </tr>
    
    <tr>
      <td></td>
      <td><input type="submit" /></td>
    </tr>
    
    
    <tr>
      <td colspan="2" valign="top" class="edittext" width="250" nowrap="">
        <hr />
        <strong>[{ oxmultilang ident="MO_BONUSBOX__BADGE_INFO_TITLE" }]<strong>
      </td>
    </tr>
    
    <tr>
    <table>
      [{foreach from=$mo_bonusbox__badges item="badgeInfo"}]
      <tr>
        <td valign="top" class="edittext" width="250" nowrap="">
          [{ oxmultilang ident="MO_BONUSBOX__BADGE_TITLE" }] [{$badgeInfo.title}]
        </td>
        <td valign="top" class="edittext">
          [{ oxmultilang ident="MO_BONUSBOX__BADGE_BENEFIT" }] [{$badgeInfo.benefit}]
        </td>
        [{if ($oView->mo_bonusbox__getAssignedVoucherseries($badgeInfo)) }]
          <td valign="top" class="edittext" style="background-color: green;">
            [{ oxmultilang ident="MO_BONUSBOX__COUPONSERIES_ACTIVE" }]
          </td>
        [{else}]
          <td valign="top" class="edittext" style="background-color: red;">
            [{ oxmultilang ident="MO_BONUSBOX__COUPONSERIES_INACTIVE" }]
          </td>
        [{/if}]
          <td valign="top" class="edittext">
            <a href="[{$oViewConf->getSelfLink()}]cl=voucherserie&oxid=[{$oView->mo_bonusbox__getVoucherSeriesId($badgeInfo.id)}]" target="basefrm" onclick="_homeExpAct('nav-1-2','nav-1-2-5');">
              [{ oxmultilang ident="MO_BONUSBOX__COUPONSERIES_EDIT" }]
            </a>
          </td>
      </tr>
    [{/foreach}]
    </table>
    </tr>    

  </table>

  <input type="hidden" name="fnc" value="save">
</form>

<script type="text/javascript">
  <!--
  function _homeExpAct(mnid,sbid){
    top.navigation.adminnav._navExtExpAct(mnid,sbid);
  }
  //-->
</script>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
