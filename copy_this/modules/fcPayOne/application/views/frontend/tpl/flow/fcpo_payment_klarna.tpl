<div class="well well-sm">
    <dl>
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
            <input type="hidden" name="fcpo_mode_[{$sPaymentID}]" value="[{$paymentmethod->fcpoGetOperationMode()}]">
            [{assign var="blDisplayCampaignMissing" value=false}]
            [{if $blDisplayCampaignMissing == false}]
                [{if $oView->fcpoKlarnaInfoNeeded()}]
                    <div class="form-group" id="fcpo_elv_error_blocked">
                        <div class="col-lg-9">
                            [{oxmultilang ident="FCPO_KLV_INFO_NEEDED"}]
                            <br>
                        </div>
                    </div>
                [{/if}]
                [{if $sPaymentID == "fcpoklarna"}]
                    [{assign var="blKlv" value=true}]
                [{else}]
                    [{assign var="blKlv" value=false}]
                [{/if}]
                [{if $oView->fcpoKlarnaIsTelephoneNumberNeeded()}]
                    <div class="form-group">
                        <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_KLV_TELEPHONENUMBER"}]</label>
                        <div class="col-lg-9">
                            <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" [{if $blKlv}]name="dynvalue[fcpo_klv_fon]" value="[{$dynvalue.fcpo_klv_fon}]"[{else}]name="dynvalue[fcpo_kls_fon]" value="[{$dynvalue.fcpo_kls_fon}]"[{/if}]>
                            <div [{if $blKlv}]id="fcpo_klv_fon_invalid"[{else}]id="fcpo_kls_fon_invalid"[{/if}] class="fcpo_check_error">
                                <span class="help-block">
                                    <ul role="alert" class="list-unstyled text-danger">
                                        <li>[{oxmultilang ident="FCPO_KLV_TELEPHONENUMBER_INVALID"}]</li>
                                    </ul>
                                </span>
                            </div>
                        </div>
                    </div>
                [{/if}]
                [{if $oView->fcpoKlarnaIsBirthdayNeeded()}]
                    <div class="form-group">
                        <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_KLV_BIRTHDAY"}]</label>
                        <div class="col-lg-3">
                            <input class="form-control" placeholder="DD" autocomplete="off" type="text" size="3" maxlength="2"[{if $blKlv}]name="dynvalue[fcpo_klv_birthday][day]" value="[{$dynvalue.fcpo_klv_birthday.day}]"[{else}]name="dynvalue[fcpo_kls_birthday][day]" value="[{$dynvalue.fcpo_kls_birthday.day}]"[{/if}] >
                        </div>
                        <div class="col-lg-3">
                            <input class="form-control" placeholder="MM" autocomplete="off" type="text" size="3" maxlength="2" [{if $blKlv}]name="dynvalue[fcpo_klv_birthday][month]" value="[{$dynvalue.fcpo_klv_birthday.month}]"[{else}]name="dynvalue[fcpo_kls_birthday][month]" value="[{$dynvalue.fcpo_kls_birthday.month}]"[{/if}]>
                        </div>
                        <div class="col-lg-3">
                            <input class="form-control" placeholder="YYYY" autocomplete="off" type="text" size="8" maxlength="4" [{if $blKlv}]name="dynvalue[fcpo_klv_birthday][year]" value="[{$dynvalue.fcpo_klv_birthday.year}]"[{else}]name="dynvalue[fcpo_kls_birthday][year]" value="[{$dynvalue.fcpo_kls_birthday.year}]"[{/if}]>
                        </div>
                        <div class="col-lg-9">
                            <div [{if $blKlv}]id="fcpo_klv_birthday_invalid"[{else}]id="fcpo_kls_birthday_invalid"[{/if}] class="fcpo_check_error">
                                <span class="help-block">
                                    <ul role="alert" class="list-unstyled text-danger">
                                        <li>[{oxmultilang ident="FCPO_KLV_BIRTHDAY_INVALID"}]</li>
                                    </ul>
                                </span>
                            </div>
                        </div>
                    </div>
                [{/if}]
                [{if $oView->fcpoKlarnaIsAddressAdditionNeeded()}]
                    <div class="form-group">
                        <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_KLV_ADDINFO"}]</label>
                        <div class="col-lg-9">
                            <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" [{if $blKlv}]name="dynvalue[fcpo_klv_addinfo]" value="[{$dynvalue.fcpo_klv_addinfo}]"[{else}]name="dynvalue[fcpo_kls_addinfo]" value="[{$dynvalue.fcpo_kls_addinfo}]"[{/if}]>
                            <div [{if $blKlv}]id="fcpo_klv_addinfo_invalid"[{else}]id="fcpo_kls_addinfo_invalid"[{/if}] class="fcpo_check_error">
                                <span class="help-block">
                                    <ul role="alert" class="list-unstyled text-danger">
                                        <li>[{oxmultilang ident="FCPO_KLV_ADDINFO_INVALID"}]</li>
                                    </ul>
                                </span>
                            </div>
                        </div>
                    </div>
                [{/if}]
                [{if $oView->fcpoKlarnaIsDelAddressAdditionNeeded()}]
                    <div class="form-group">
                        <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_KLV_ADDINFO_DEL"}]</label>
                        <div class="col-lg-9">
                            <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" [{if $blKlv}]name="dynvalue[fcpo_klv_del_addinfo]" value="[{$dynvalue.fcpo_klv_del_addinfo}]"[{else}]name="dynvalue[fcpo_kls_del_addinfo]" value="[{$dynvalue.fcpo_kls_del_addinfo}]"[{/if}]>
                            <div [{if $blKlv}]id="fcpo_klv_del_addinfo_invalid"[{else}]id="fcpo_kls_del_addinfo_invalid"[{/if}] class="fcpo_check_error">
                                <span class="help-block">
                                    <ul role="alert" class="list-unstyled text-danger">
                                        <li>[{oxmultilang ident="FCPO_KLV_ADDINFO_INVALID"}]</li>
                                    </ul>
                                </span>
                            </div>
                        </div>
                    </div>
                [{/if}]
                [{if $oView->fcpoKlarnaIsGenderNeeded()}]
                    <div class="form-group">
                        <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_KLV_SAL"}]</label>
                        <div class="col-lg-2">
                            [{if $blKlv}]
                                [{include file="form/fieldset/salutation.tpl" name="dynvalue[fcpo_klv_sal]" value=$dynvalue.fcpo_klv_sal class="form-control selectpicker show-tick"}]
                            [{else}]
                                [{include file="form/fieldset/salutation.tpl" name="dynvalue[fcpo_kls_sal]" value=$dynvalue.fcpo_kls_sal class="form-control selectpicker show-tick"}]
                            [{/if}]
                        </div>
                    </div>
                [{/if}]
                [{if $oView->fcpoKlarnaIsPersonalIdNeeded()}]
                    <div class="form-group">
                        <label class="req control-label col-lg-3">[{oxmultilang ident="FCPO_KLV_PERSONALID"}]</label>
                        <div class="col-lg-9">
                            <input class="form-control" autocomplete="off" type="text" size="20" maxlength="64" [{if $blKlv}]name="dynvalue[fcpo_klv_personalid]" value="[{$dynvalue.fcpo_klv_personalid}]"[{else}]name="dynvalue[fcpo_kls_personalid]" value="[{$dynvalue.fcpo_kls_personalid}]"[{/if}]>
                            <div [{if $blKlv}]id="fcpo_klv_personalid_invalid"[{else}]id="fcpo_kls_personalid_invalid"[{/if}] class="fcpo_check_error">
                                <span class="help-block">
                                    <ul role="alert" class="list-unstyled text-danger">
                                        <li>[{oxmultilang ident="FCPO_KLV_PERSONALID_INVALID"}]</li>
                                    </ul>
                                </span>
                            </div>
                        </div>
                    </div>
                [{/if}]
                <div class="form-group">
                    <div class="col-lg-1 col-lg-offset-2">
                        [{if $blKlv}]
                            <input type="hidden" name="dynvalue[fcpo_klv_confirm]" value="false">
                            <input class="form-control checkbox" type="checkbox" name="dynvalue[fcpo_klv_confirm]" value="true" [{if $dynvalue.fcpo_klv_confirm}]checked[{/if}]>
                        [{else}]
                            <input type="hidden" name="dynvalue[fcpo_kls_confirm]" value="false">
                            <input class="form-control checkbox" type="checkbox" name="dynvalue[fcpo_kls_confirm]" value="true" [{if $dynvalue.fcpo_kls_confirm}]checked[{/if}]>
                        [{/if}]
                    </div>
                    <div class="col-lg-9">
                        [{$oView->fcpoGetConfirmationText()}]
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-9">
                        <div [{if $blKlv}]id="fcpo_klv_confirmation_missing"[{else}]id="fcpo_kls_confirmation_missing"[{/if}] class="fcpo_check_error">
                            <span class="help-block">
                                <ul role="alert" class="list-unstyled text-danger">
                                    <li>[{oxmultilang ident="FCPO_KLV_CONFIRMATION_MISSING"}]</li>
                                </ul>
                            </span>
                        </div>
                    </div>
                </div>
            [{else}]
                <div class="form-group">
                    <div class="col-lg-9">
                        [{oxmultilang ident="FCPO_KLS_NO_CAMPAIGN"}]
                        <input type="hidden" name="fcpo_klarna_no_campaign" value="true">
                    </div>
                </div>
            [{/if}]

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
