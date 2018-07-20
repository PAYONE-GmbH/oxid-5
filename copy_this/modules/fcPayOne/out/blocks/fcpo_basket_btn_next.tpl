[{$smarty.block.parent}]
[{assign var="sFcPoTemplatePath" value=$oViewConf->fcpoGetActiveThemePath()}]
[{if $oView->fcpoGetPayPalExpressPic()}]
    <form autocomplete="off" action="[{$oViewConf->getSslSelfLink()}]" method="post">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="basket">
        <input type="hidden" name="fnc" value="fcpoUsePayPalExpress">
        <input type="image" src="[{$oView->fcpoGetPayPalExpressPic()}]" style="float: right;margin-right:10px;">
    </form>
[{/if}]
[{if $oViewConf->fcpoCanDisplayAmazonPayButton()}]
    [{assign var="sFcPoTemplatePathAmazon" value=$sFcPoTemplatePath|cat:'/fcpayone_amazon_paybutton.tpl'}]
    [{include
        file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePathAmazon)
        sAmazonButtonId='LoginWithAmazonButtonUp'
        sAmazonButtonClass='payone_basket_amazon_btn_flow'
    }]
[{/if}]

[{if $oViewConf->fcpoCanDisplayMasterpassButton()}]
    [{assign var="sFcPoTemplatePathMasterpass" value=$sFcPoTemplatePath|cat:'/fcpayone_masterpass_button.tpl'}]
    [{include
        file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePathMasterpass)
        sMasterpassButtonId='LoginWithMasterpassTop'
        sMasterpassButtonClass='payone_basket_masterpass_btn_flow'
    }]
[{/if}]
