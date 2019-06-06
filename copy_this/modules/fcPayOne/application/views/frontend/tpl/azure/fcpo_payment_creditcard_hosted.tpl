[{if $oView->hasPaymentMethodAvailableSubTypes('cc')}]
    [{assign var="dynvalue" value=$oView->getDynValue()}]
    <dl>
        <dt>
            <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
            <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}]</b> [{$oView->fcpoGetFormattedPaymentCosts($paymentmethod)}]</label>
        </dt>
        <dd id="fcpoCreditcardSpinner" style="display: block;">
            <img src="[{$oViewConf->fcpoGetModuleImgUrl()}]ajax-loader.gif">
        </dd>
        <dd id="fcpoCreditcard" style="display:none;" class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
            <script type="text/javascript" src="[{$oViewConf->fcpoGetHostedPayoneJs()}]"></script>
            <input type="hidden" name="dynvalue[fcpo_kknumber]" value="">
            <input type="hidden" name="fcpo_cc_type" value="hosted">
            [{foreach from=$aFcPoCCPaymentMetaData item="oFcPoCCPaymentMetaData"}]
                <input type="hidden" name="[{$oFcPoCCPaymentMetaData->sHashName}]" value="[{$oFcPoCCPaymentMetaData->sHashValue}]">
                <input type="hidden" name="[{$oFcPoCCPaymentMetaData->sOperationModeName}]" value="[{$oFcPoCCPaymentMetaData->sOperationModeValue}]">
            [{/foreach}]
            <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
            <ul class="form">
                <li>
                    <label for="cardtypeInput">[{oxmultilang ident="FCPO_CREDITCARD"}]</label>
                    <select id="cardtype" name="dynvalue[fcpo_kktype]">
                        [{foreach from=$aFcPoCCPaymentMetaData item="oFcPoCCPaymentMetaData"}]
                            <option value="[{$oFcPoCCPaymentMetaData->sPaymentTag}]" [{if $oFcPoCCPaymentMetaData->blSelected}]selected[{/if}]>[{$oFcPoCCPaymentMetaData->sPaymentName}]</option>
                        [{/foreach}]
                    </select>
                </li>
                <li>
                    <label for="cardpanInput">[{oxmultilang ident="FCPO_NUMBER"}]</label>
                    <span class="inputIframe" id="cardpan"></span>
                </li>
                [{if $oView->fcpoUseCVC()}]
                    <li>
                        <label for="cvcInput">[{oxmultilang ident="FCPO_CARD_SECURITY_CODE"}]</label>
                        <span id="containercardcvc2" class="inputIframe"></span>
                    </li>
                [{/if}]
                <li>
                    <label for="expireInput">[{oxmultilang ident="FCPO_VALID_UNTIL"}]</label>
                    <span id="expireInput" class="inputIframe">
                        <span id="cardexpiremonth"></span>
                        <span id="cardexpireyear"></span>
                    </span>
                </li>
                <li>
                    <label for="firstname">[{oxmultilang ident="FCPO_FIRSTNAME"}]</label>
                    <input autocomplete="off" id="firstname" type="text" name="firstname" value="">
                </li>
                <li>
                    <label for="lastname">[{oxmultilang ident="FCPO_LASTNAME"}]</label>
                    <input autocomplete="off" id="lastname" type="text" name="lastname" value="">
                </li>
                <li>
                    <div id="errorOutput"></div>
                </li>
            </ul>
            [{oxid_include_dynamic file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath('fcpo_payment_creditcard_script.tpl')}]
            [{block name="checkout_payment_longdesc"}]
                [{if $paymentmethod->oxpayments__oxlongdesc->value}]
                    <div class="desc">
                        [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                    </div>
                [{/if}]
            [{/block}]
        </dd>
    </dl>
[{/if}]