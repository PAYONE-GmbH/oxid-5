[{if $sMasterpassButtonId}]
    [{assign var="iMasterpassButtonIncluded" value=$iMasterpassButtonIncluded+1}]
    [{elseif !$iMasterpassButtonIncluded}]
    [{assign var="iMasterpassButtonIncluded" value="0"}]
    [{else}]
    [{assign var="iMasterpassButtonIncluded" value=$iMasterpassButtonIncluded+1}]
[{/if}]

[{if !$sMasterpassButtonId}]
    [{assign var="sMasterpassButtonId" value='LoginWithMasterpass'}]
[{/if}]

[{$oViewConf->fcpoSetCurrentMasterpassButtonId($sMasterpassButtonId)}]

<div class="[{$sMasterpassButtonClass}]">
    <img class="js-payone-masterpass"
         data-payone-masterpass-shopurl="[{$oViewConf->fcpoGetShopUrl()}]"
         data-payone-masterpass-controller="[{$oViewConf->fcpoGetAjaxControllerUrl()}]"
         src="[{$oViewConf->fcpoGetMasterpassButtonImg()}]">
    <br>
    <a href="https://www.mastercard.com/mc_us/wallet/learnmore/[{$oView->getActiveLangAbbr()}]/[{$oView->getActiveLangAbbr()|upper}]" target="_blank" style="margin-left: 35px;"><u>[{oxmultilang ident="FCPO_MASTERPASS_LEARNMORE"}]</u></a>
</div>
