[{$smarty.block.parent}]

[{assign var='sAmazonButtonId' value=$_prefix|cat:"LoginWithAmazonMiniBasket"}]
[{assign var='sMasterpassButtonId' value=$_prefix|cat:"LoginWithMasterpassMiniBasket"}]

[{if $oViewConf->fcpoCanDisplayAmazonPayButton()}]
    <p class="functions clear text-right">
        [{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]
        [{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpayone_amazon_paybutton.tpl'}]
        [{include
        file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)
        sAmazonButtonId=$sAmazonButtonId
        sAmazonButtonClass="payone_basket_amazon_btn_flow_minibasket"
        }]
    </p>
[{/if}]

[{if $oViewConf->fcpoCanDisplayMasterpassButton()}]
<p class="functions clear text-right">
    [{assign var="sFcPoMasterpassTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()|cat:'/fcpayone_masterpass_button.tpl'}]
    [{include
        file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoMasterpassTemplatePath)
        sMasterpassButtonId=$sMasterpassButtonId
        sMasterpassButtonClass="payone_basket_masterpass_btn_flow_minibasket"
    }]
</p>
[{/if}]
