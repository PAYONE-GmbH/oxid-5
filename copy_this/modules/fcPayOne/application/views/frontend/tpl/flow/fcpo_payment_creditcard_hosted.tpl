[{if $oView->hasPaymentMethodAvailableSubTypes('cc')}]
    [{assign var="dynvalue" value=$oView->getDynValue()}]
    <div class="well well-sm">
        <dl id="fcpoCreditcard" style="display:none;">
            <dt>
                <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
                <label for="payment_[{$sPaymentID}]">
                    <b>
                        [{$paymentmethod->oxpayments__oxdesc->value}]
                        [{if $paymentmethod->getPrice()}]
                        [{assign var="oPaymentPrice" value=$paymentmethod->getPrice()}]
                        [{if $oViewConf->isFunctionalityEnabled('blShowVATForPayCharge')}]
                        ([{oxprice price=$oPaymentPrice->getNettoPrice() currency=$currency}]
                        [{if $oPaymentPrice->getVatValue() > 0}]
                        [{oxmultilang ident="PLUS_VAT"}] [{oxprice price=$oPaymentPrice->getVatValue() currency=$currency}]
                        [{/if}])
                        [{else}]
                        ([{oxprice price=$oPaymentPrice->getBruttoPrice() currency=$currency}])
                        [{/if}]
                        [{/if}]
                    </b>
                </label>
            </dt>
            </dt>
            <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
                <script type="text/javascript" src="[{$oViewConf->fcpoGetHostedPayoneJs()}]"></script>
                <input type="hidden" name="dynvalue[fcpo_kknumber]" value="">
                <input type="hidden" name="fcpo_cc_type" value="hosted">
                <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
                [{foreach from=$aFcPoCCPaymentMetaData item="oFcPoCCPaymentMetaData"}]
                    <input type="hidden" name="[{$oFcPoCCPaymentMetaData->sHashName}]" value="[{$oFcPoCCPaymentMetaData->sHashValue}]">
                    <input type="hidden" name="[{$oFcPoCCPaymentMetaData->sOperationModeName}]" value="[{$oFcPoCCPaymentMetaData->sOperationModeValue}]">
                [{/foreach}]
                <div class="form-group">
                    <label for="cardtype" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_CREDITCARD"}]</label>
                    <div class="col-lg-9">
                        <select id="cardtype" name="dynvalue[fcpo_kktype]"  class="form-control selectpicker" required="required">
                            [{foreach from=$aFcPoCCPaymentMetaData item="oFcPoCCPaymentMetaData"}]
                                <option value="[{$oFcPoCCPaymentMetaData->sPaymentTag}]" [{if $oFcPoCCPaymentMetaData->blSelected}]selected[{/if}]>[{$oFcPoCCPaymentMetaData->sPaymentName}]</option>
                            [{/foreach}]
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cardpanInput" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_NUMBER"}]</label>
                    <div class="col-lg-9">
                        <span class="inputIframe" id="cardpan"></span>
                    </div>
                </div>

                [{if $oView->fcpoUseCVC()}]
                    <div class="form-group">
                        <label for="cvcInput" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_CARD_SECURITY_CODE"}]</label>
                        <div class="col-lg-9">
                            <span id="cardcvc2" class="inputIframe"></span>
                        </div>
                    </div>
                [{/if}]
                <div class="form-group">
                    <label for="expireInput" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_VALID_UNTIL"}]</label>
                    <div class="col-lg-9">
                        <span id="expireInput" class="inputIframe">
                            <span id="cardexpiremonth"></span>
                            <span id="cardexpireyear"></span>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstname" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_FIRSTNAME"}]</label>
                    <div class="col-lg-9">
                        <input class="form-control" autocomplete="off" id="firstname" type="text" name="firstname" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="req control-label col-lg-3">[{oxmultilang ident="FCPO_LASTNAME"}]</label>
                    <div class="col-lg-9">
                        <input class="form-control" autocomplete="off" id="lastname" type="text" name="lastname" value="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-9 col-lg-push-3">
                        <div id="errorOutput" class="alert-danger"></div>
                    </div>
                </div>
                [{oxid_include_dynamic file=$oViewConf->fcpoGetAbsModuleTemplateFrontendPath('fcpo_payment_creditcard_script.tpl')}]
                [{block name="checkout_payment_longdesc"}]
					[{if $paymentmethod->oxpayments__oxlongdesc->value|trim }]
						<div class="row">
							<div class="col-xs-12 col-lg-9 col-lg-offset-3">
								<div class="alert alert-info">
			                        [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
				                </div>
							</div>
						</div>
                    [{/if}]
                [{/block}]
            </dd>
        </dl>
    </div>
[{/if}]
