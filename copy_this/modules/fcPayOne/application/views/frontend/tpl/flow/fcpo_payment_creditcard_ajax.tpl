[{if $oView->hasPaymentMethodAvailableSubTypes('cc')}]
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
            <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
                <input type="hidden" name="fcpo_cc_type" value="ajax">
                [{foreach from=$aFcPoCCPaymentMetaData item="oFcPoCCPaymentMetaData"}]
                    <input type="hidden" name="[{$oFcPoCCPaymentMetaData->sHashName}]" value="[{$oFcPoCCPaymentMetaData->sHashValue}]">
                    <input type="hidden" name="[{$oFcPoCCPaymentMetaData->sOperationModeName}]" value="[{$oFcPoCCPaymentMetaData->sOperationModeValue}]">
                [{/foreach}]
                <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
                <div class="form-group" id="fcpo_cc_error">
                    <div class="col-lg-9">
                        <span class="help-block">
                            <ul role="alert" class="list-unstyled text-danger">
                                <li>[{oxmultilang ident="FCPO_ERROR"}]<div id="fcpo_cc_error_content"></div></li>
                            </ul>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_CREDITCARD"}]</label>
                    <div class="col-lg-9">
                        <select name="dynvalue[fcpo_kktype]" [{if $oView->getMaestroUK()}]onchange="fcCheckType(this); return false;"[{/if}] class="form-control selectpicker" required="required">
                            [{foreach from=$aFcPoCCPaymentMetaData item="oFcPoCCPaymentMetaData"}]
                                <option value="[{$oFcPoCCPaymentMetaData->sPaymentTag}]" [{if $oFcPoCCPaymentMetaData->blSelected}]selected[{/if}]>[{$oFcPoCCPaymentMetaData->sPaymentName}]</option>
                            [{/foreach}]
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_NUMBER"}]</label>
                    <div class="col-lg-9">
                        <input autocomplete="off" type="text" class="form-control js-oxValidate js-oxValidate_notEmpty payment_text" size="20" maxlength="64" name="dynvalue[fcpo_kknumber]" value="[{$dynvalue.fcpo_kknumber}]" required="required">
                        <div id="fcpo_cc_number_invalid" class="fcpo_check_error">
                            <span class="help-block">
                                <ul role="alert" class="list-unstyled text-danger">
                                    <li>[{oxmultilang ident="FCPO_CC_NUMBER_INVALID"}]</li>
                                </ul>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_BANK_ACCOUNT_HOLDER_2"}]</label>
                    <div class="col-lg-9">
                        <input autocomplete="off" type="text" class="form-control js-oxValidate js-oxValidate_notEmpty" size="20" maxlength="64" name="dynvalue[fcpo_kkname]" value="[{if $dynvalue.fcpo_kkname}][{$dynvalue.fcpo_kkname}][{else}][{$oxcmp_user->oxuser__oxfname->value}] [{$oxcmp_user->oxuser__oxlname->value}][{/if}]" required="required">
                        <span class="help-block">[{oxmultilang ident="FCPO_IF_DEFERENT_FROM_BILLING_ADDRESS"}]</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="req control-label col-xs-12 col-lg-3">[{oxmultilang ident="FCPO_VALID_UNTIL"}]</label>
                    <div class="col-xs-6 col-lg-2">
                        <select name="dynvalue[fcpo_kkmonth]" class="form-control selectpicker" required="required">
                            <option [{if $dynvalue.fcpo_kkmonth == "01"}]selected[{/if}]>01</option>
                            <option [{if $dynvalue.fcpo_kkmonth == "02"}]selected[{/if}]>02</option>
                            <option [{if $dynvalue.fcpo_kkmonth == "03"}]selected[{/if}]>03</option>
                            <option [{if $dynvalue.fcpo_kkmonth == "04"}]selected[{/if}]>04</option>
                            <option [{if $dynvalue.fcpo_kkmonth == "05"}]selected[{/if}]>05</option>
                            <option [{if $dynvalue.fcpo_kkmonth == "06"}]selected[{/if}]>06</option>
                            <option [{if $dynvalue.fcpo_kkmonth == "07"}]selected[{/if}]>07</option>
                            <option [{if $dynvalue.fcpo_kkmonth == "08"}]selected[{/if}]>08</option>
                            <option [{if $dynvalue.fcpo_kkmonth == "09"}]selected[{/if}]>09</option>
                            <option [{if $dynvalue.fcpo_kkmonth == "10"}]selected[{/if}]>10</option>
                            <option [{if $dynvalue.fcpo_kkmonth == "11"}]selected[{/if}]>11</option>
                            <option [{if $dynvalue.fcpo_kkmonth == "12"}]selected[{/if}]>12</option>
                        </select>
                    </div>
                    <div class="col-xs-6 col-lg-2">
                        <select name="dynvalue[fcpo_kkyear]"class="form-control selectpicker">
                            [{foreach from=$oView->getCreditYears() item=year}]
                                <option [{if $dynvalue.fcpo_kkyear == $year}]selected[{/if}]>[{$year}]</option>
                            [{/foreach}]
                        </select>
                    </div>
                    <div class="col-sm-3"></div>
                    <div class="col-lg-9">
                        <div id="fcpo_cc_date_invalid" class="fcpo_check_error">
                            <span class="help-block">
                                <ul role="alert" class="list-unstyled text-danger">
                                    <li>[{oxmultilang ident="FCPO_CC_DATE_INVALID"}]</li>
                                </ul>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_CARD_SECURITY_CODE"}]</label>
                    <div class="col-lg-9">
                        <input autocomplete="off" type="text" class="form-control js-oxValidate js-oxValidate_notEmpty payment_text" size="20" maxlength="64" name="dynvalue[fcpo_kkpruef]" value="[{$dynvalue.fcpo_kkpruef}]" required="required">
                        <div id="fcpo_cc_cvc2_invalid" class="fcpo_check_error">
                            <span class="help-block">
                                <ul role="alert" class="list-unstyled text-danger">
                                    <li>[{oxmultilang ident="FCPO_CC_CVC2_INVALID"}]</li>
                                </ul>
                            </span>
                        </div>
                        <span class="help-block">[{oxmultilang ident="FCPO_CARD_SECURITY_CODE_DESCRIPTION"}]</span>
                    </div>
                </div>
                [{if $oView->getMaestroUK()}]
                    <div class="form-group" id="fcpo_kkcsn_row" style="display: none;">
                        <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_CARDSEQUENCENUMBER"}]</label>
                        <div class="col-lg-9">
                            <input autocomplete="off" type="text" class="payment_text" size="20" maxlength="64" name="dynvalue[fcpo_kkcsn]" value="[{$dynvalue.fcpo_kkcsn}]">
                        </div>
                    </div>
                [{/if}]
                <div class="clearfix"></div>
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
