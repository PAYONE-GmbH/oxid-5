[{if $oViewConf->fcpoAmazonLoginSessionActive()}]
    [{assign var="sFcPoTemplatePath" value=$oView->fcpoGetActiveThemePath()}]
    [{if $oView->getAllSets()}]
        [{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpo_amazonpay_payment.tpl'}]
        [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]
    [{else}]
        [{assign var="sFcPoTemplatePath" value=$sFcPoTemplatePath|cat:'/fcpo_amazonpay_wrong_address.tpl'}]
        [{include file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath($sFcPoTemplatePath)}]
    [{/if}]
[{else}]
    <script type="text/javascript">
        var oFcPayoneData = oFcPayoneData || [];
        oFcPayoneData.inputs =
            {
                fcpo_mid:                       '[{$oView->getMerchantId()}]',
                fcpo_portalid:                  '[{$oView->getPortalId()}]',
                fcpo_encoding:                  '[{$oView->getEncoding()}]',
                fcpo_aid:                       '[{$oView->getSubAccountId()}]',
                fcpo_amount:                    '[{$oView->getAmount()}]',
                fcpo_currency:                  '[{$currency->name}]',
                fcpo_tpllang:                   '[{$oView->getTplLang()}]',
                fcpo_bill_country:              '[{$oView->fcGetBillCountry()}]',
                dynvalue_fcpo_pseudocardpan:    '',
                dynvalue_fcpo_ccmode:           '',
                fcpo_checktype:                 '[{$oView->getChecktype()}]',
                fcpo_hashelvWith:               '[{$oView->getHashELVWithChecktype()}]',
                fcpo_hashelvWithout:            '[{$oView->getHashELVWithoutChecktype()}]',
                fcpo_integratorid:              '[{$oView->getIntegratorid()}]',
                fcpo_integratorver:             '[{$oView->getIntegratorver()}]',
                fcpo_integratorextver:          '[{$oView->getIntegratorextver()}]'
            };
    </script>

    [{oxstyle include=$oViewConf->fcpoGetModuleCssPath('fcpayone.css')}]

    [{$smarty.block.parent}]
[{/if}]
