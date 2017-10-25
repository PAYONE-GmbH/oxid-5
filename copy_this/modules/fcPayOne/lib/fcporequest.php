<?php

/**
 * PAYONE OXID Connector is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PAYONE OXID Connector is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PAYONE OXID Connector.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.payone.de
 * @copyright (C) Payone GmbH
 * @version   OXID eShop CEPYD
 */
class fcpoRequest extends oxSuperCfg {

    /**
     * Helper object for dealing with different shop versions
     * @var object
     */
    protected $_oFcpoHelper = null;

    /**
     * Array or request parameters
     *
     * @var array
     */
    protected $_aParameters = array();

    /*
     * Array of valid countries for addresscheck basic
     *
     * @var array
     */
    protected $_aValidCountrys = array(
        'BE',
        'DK',
        'DE',
        'FI',
        'FR',
        'IT',
        'CA',
        'LU',
        'NL',
        'NO',
        'AT',
        'PL',
        'PT',
        'SE',
        'CH',
        'SK',
        'ES',
        'CZ',
        'HU',
        'US',
    );
    protected $_aStateNeededCountries = array(
        'US',
        'CA',
        'CN',
        'JP',
        'MX',
        'BR',
        'AR',
        'ID',
        'TH',
        'IN',
        'AU',
    );

    /*
     * URL of PAYONE Server API
     * 
     * @var string
     */
    protected $_sApiUrl = 'https://api.pay1.de/post-gateway/';

    /*
     * URL of PAYONE Server API
     * 
     * @var string
     */
    protected $_sFrontendApiUrl = 'https://secure.pay1.de/frontend/';
    protected $_aFrontendUnsetParams = array(
        'mid',
        'integrator_name',
        'integrator_version',
        'solution_name',
        'solution_version',
        'ip',
        'errorurl',
        'salutation',
        'pseudocardpan',
    );
    protected $_aFrontendHashParams = array(
        'aid',
        'amount',
        'backurl',
        'clearingtype',
        'currency',
        'customerid',
        'de',
        'encoding',
        'id',
        'mode',
        'no',
        'portalid',
        'pr',
        'reference',
        'request',
        'successurl',
        'targetwindow',
        'va',
        'key'
    );
    
    /**
     * List of RatePay related payment Ids
     * @var array
     */
    protected $_aRatePayPayents = array(
        'fcporp_bill',
    );

    /**
     * Class constructor, sets all required parameters for requests.
     * 
     * @return null
     */
    public function __construct() {
        $oConfig = $this->getConfig();
        $this->_oFcpoHelper = oxNew('fcpohelper');

        $this->addParameter('mid', $oConfig->getConfigParam('sFCPOMerchantID')); //PayOne Merchant ID
        $this->addParameter('portalid', $oConfig->getConfigParam('sFCPOPortalID')); //PayOne Portal ID
        $this->addParameter('key', md5($oConfig->getConfigParam('sFCPOPortalKey'))); //PayOne Portal Key
        if ($oConfig->isUtf()) {
            $this->addParameter('encoding', 'UTF-8'); //Encoding
        }

        $this->addParameter('integrator_name', 'oxid');
        $this->addParameter('integrator_version', $this->_oFcpoHelper->fcpoGetIntegratorVersion());
        $this->addParameter('solution_name', 'fatchip');
        $this->addParameter('solution_version', $this->_oFcpoHelper->fcpoGetModuleVersion());
    }

    /**
     * Loads shop version and formats it in a certain way
     *
     * @return string
     */
    protected function getIntegratorId() {
        return $this->_oFcpoHelper->fcpoGetIntegratorId();
    }

    /**
     * Add parameter to request
     * 
     * @param string $sKey parameter key
     * @param string $sValue parameter value
     * @param bool $blAddAsNullIfEmpty add parameter with value NULL if empty. Default is false
     *
     * @return null
     */
    public function addParameter($sKey, $sValue, $blAddAsNullIfEmpty = false) {
        if ($blAddAsNullIfEmpty === true && empty($sValue)) {
            $sValue = 'NULL';
        }
        $this->_aParameters[$sKey] = $sValue;
    }

    /**
     * Remove parameter from request
     * 
     * @param string $sKey parameter key
     *
     * @return null
     */
    public function removeParameter($sKey) {
        if (array_key_exists($sKey, $this->_aParameters)) {
            unset($this->_aParameters[$sKey]);
        }
    }

    /**
     * Get parameter from request or return false if parameter was not set
     *
     * @param string $sKey parameter key
     * 
     * @return string
     */
    public function getParameter($sKey) {
        if (array_key_exists($sKey, $this->_aParameters)) {
            return $this->_aParameters[$sKey];
        }
        return false;
    }

    /**
     * Get get PAYONE operation mode ( live or test ) for given order
     * 
     * @param object $sOrder order object
     * @param string $sType subtype for the paymentmethod ( Visa, MC, etc. ) Default is ''
     *
     * @return string
     */
    protected function getOperationMode($sPaymentType, $sType = '') {
        $oPayment = oxNew('oxpayment');
        $oPayment->load($sPaymentType);
        return $oPayment->fcpoGetOperationMode($sType);
    }

    protected function _fcpoGetRemoteAddress() {
        $oUtilsServer = $this->_oFcpoHelper->fcpoGetUtilsServer();
        $sIpAddress = $oUtilsServer->getRemoteAddress();

        return $sIpAddress;
    }

    /**
     * Set authorization parameters and return true if payment-method is known or false if payment-method is unknown
     *
     * @param object $oOrder order object
     * @param object $oUser user object
     * @param array $aDynvalue form data
     * @param string $sRefNr payone reference number
     * 
     * @return bool
     */
    protected function setAuthorizationParameters($oOrder, $oUser, $aDynvalue, $sRefNr, $blIsPreauthorization = false) {
        $this->_checkAddress($oOrder, $oUser);

        $oConfig = $this->getConfig();

        $this->addParameter('aid', $oConfig->getConfigParam('sFCPOSubAccountID')); //ID of PayOne Sub-Account
        $this->addParameter('reference', $sRefNr);
        $this->addParameter('amount', number_format($oOrder->oxorder__oxtotalordersum->value, 2, '.', '') * 100); //Total order sum in smallest currency unit
        $this->addParameter('currency', $oOrder->oxorder__oxcurrency->value); //Currency

        $this->_addUserDataParameters($oOrder, $oUser);

        $sIp = $this->_fcpoGetRemoteAddress();
        if ($sIp != '')
            $this->addParameter('ip', $sIp);

        $blIsWalletTypePaymentWithDelAddress = (
                $oOrder->oxorder__oxpaymenttype->value == 'fcpopaydirekt' ||
                $oOrder->fcIsPayPalOrder() === true &&
                $this->getConfig()->getConfigParam('blFCPOPayPalDelAddress') === true
                );

        if ($oOrder->oxorder__oxdellname->value != '') {
            $oDelCountry = oxNew('oxcountry');
            $oDelCountry->load($oOrder->oxorder__oxdelcountryid->value);

            $this->addParameter('shipping_firstname', $oOrder->oxorder__oxdelfname->value);
            $this->addParameter('shipping_lastname', $oOrder->oxorder__oxdellname->value);
            if ($oOrder->oxorder__oxdelcompany->value)
                $this->addParameter('shipping_company', $oOrder->oxorder__oxdelcompany->value);
            $this->addParameter('shipping_street', trim($oOrder->oxorder__oxdelstreet->value . ' ' . $oOrder->oxorder__oxdelstreetnr->value));
            if ($oOrder->oxorder__oxdeladdinfo->value)
                $this->addParameter('shipping_addressaddition', $oOrder->oxorder__oxdeladdinfo->value);
            $this->addParameter('shipping_zip', $oOrder->oxorder__oxdelzip->value);
            $this->addParameter('shipping_city', $oOrder->oxorder__oxdelcity->value);
            $this->addParameter('shipping_country', $oDelCountry->oxcountry__oxisoalpha2->value);
            if ($this->_stateNeeded($oDelCountry->oxcountry__oxisoalpha2->value)) {
                $this->addParameter('shipping_state', $this->_getShortState($oOrder->oxorder__oxdelstateid->value));
            }
        } elseif ($blIsWalletTypePaymentWithDelAddress) {
            $oDelCountry = oxNew('oxcountry');
            $oDelCountry->load($oOrder->oxorder__oxbillcountryid->value);

            $this->addParameter('shipping_firstname', $oOrder->oxorder__oxbillfname->value);
            $this->addParameter('shipping_lastname', $oOrder->oxorder__oxbilllname->value);
            if ($oOrder->oxorder__oxbillcompany->value)
                $this->addParameter('shipping_company', $oOrder->oxorder__oxbillcompany->value);
            $this->addParameter('shipping_street', trim($oOrder->oxorder__oxbillstreet->value . ' ' . $oOrder->oxorder__oxbillstreetnr->value));
            if ($oOrder->oxorder__oxbilladdinfo->value)
                $this->addParameter('shipping_addressaddition', $oOrder->oxorder__oxbilladdinfo->value);
            $this->addParameter('shipping_zip', $oOrder->oxorder__oxbillzip->value);
            $this->addParameter('shipping_city', $oOrder->oxorder__oxbillcity->value);
            $this->addParameter('shipping_country', $oDelCountry->oxcountry__oxisoalpha2->value);
            if ($this->_stateNeeded($oDelCountry->oxcountry__oxisoalpha2->value)) {
                $this->addParameter('shipping_state', $this->_getShortState($oOrder->oxorder__oxbillstateid->value));
            }
        }

        $blPaymentTypeKnown = $this->setPaymentParameters($oOrder, $aDynvalue, $sRefNr);

        if ($oOrder->isDetailedProductInfoNeeded() || ($blIsPreauthorization === false && $this->getConfig()->getConfigParam('blFCPOSendArticlelist') === true)) {
            $this->addProductInfo($oOrder);
        }

        return $blPaymentTypeKnown;
    }

    /**
     * Set payment params for creditcard
     * 
     * @param array $aDynvalue
     * @return boolean
     */
    protected function _setPaymentParamsCC($aDynvalue) {
        $this->addParameter('clearingtype', 'cc'); //Payment method
        $this->addParameter('pseudocardpan', $aDynvalue['fcpo_pseudocardpan']);
        // Override mode for creditcard-type
        $this->addParameter('mode', $aDynvalue['fcpo_ccmode']);

        return true;
    }

    /**
     * Set payment params for debitnote
     * 
     * @param type $aDynvalue
     * @return boolean
     */
    protected function _setPaymentParamsDebitNote($aDynvalue) {
        $oConfig = $this->getConfig();
        $blFCPODebitBICMandatory = $oConfig->getConfigParam('blFCPODebitBICMandatory');

        $this->addParameter('clearingtype', 'elv'); //Payment method
        $this->addParameter('bankcountry', $aDynvalue['fcpo_elv_country']);

        $blBICConfirmed = (
                (
                isset($aDynvalue['fcpo_elv_bic']) &&
                $aDynvalue['fcpo_elv_bic'] != ''
                ) ||
                !$blFCPODebitBICMandatory
                );

        if (isset($aDynvalue['fcpo_elv_iban']) && $aDynvalue['fcpo_elv_iban'] != '' && $blBICConfirmed) {
            $this->addParameter('iban', $aDynvalue['fcpo_elv_iban']);
            if ($blFCPODebitBICMandatory) {
                $this->addParameter('bic', $aDynvalue['fcpo_elv_bic']);
            }
        } elseif (isset($aDynvalue['fcpo_elv_ktonr']) && $aDynvalue['fcpo_elv_ktonr'] != '' && isset($aDynvalue['fcpo_elv_blz']) && $aDynvalue['fcpo_elv_blz'] != '') {
            $this->addParameter('bankaccount', $aDynvalue['fcpo_elv_ktonr']);
            $this->addParameter('bankcode', $aDynvalue['fcpo_elv_blz']);
        }

        $aMandate = $this->_oFcpoHelper->fcpoGetSessionVariable('fcpoMandate');
        if ($aMandate && array_key_exists('mandate_identification', $aMandate) !== false && $aMandate['mandate_status'] == 'pending') {
            $this->addParameter('mandate_identification', $aMandate['mandate_identification']);
        }

        return false;
    }

    /**
     * Set payment params paypal
     * 
     * @param object $oOrder
     * @param string $sRefNr
     * @return boolean
     */
    protected function _setPaymentParamsPayPal($oOrder, $sRefNr) {
        $this->addParameter('clearingtype', 'wlt'); //Payment method
        $this->addParameter('wallettype', 'PPE');
        $this->addParameter('narrative_text', 'Ihre Bestellung Nr. ' . $sRefNr . ' bei ' . $this->_oFcpoHelper->fcpoGetShopName());

        if ($oOrder->oxorder__oxpaymenttype->value == 'fcpopaypal_express') {
            $this->addParameter('workorderid', $this->_oFcpoHelper->fcpoGetSessionVariable('fcpoWorkorderId'));
        }

        return true;
    }

    /**
     * Set payment params for klarna
     * 
     * @param void
     * @return boolean
     */
    protected function _setPaymentParamsKlarna() {
        $this->addParameter('clearingtype', 'fnc'); //Payment method
        $this->addParameter('financingtype', 'KLS');
        $sCampaign = $this->_oFcpoHelper->fcpoGetSessionVariable('fcpo_klarna_campaign');
        if ($sCampaign) {
            $this->addParameter('add_paydata[klsid]', $sCampaign);
            $this->_oFcpoHelper->fcpoDeleteSessionVariable('fcpo_klarna_campaign');
        }
    }

    /**
     * Set payment parameters and return true if payment-method is known or false if payment-method is unknown
     *
     * @param object $oOrder order object
     * @param array $aDynvalue form data
     * @param string $sRefNr payone reference number
     * 
     * @return bool
     */
    protected function setPaymentParameters($oOrder, $aDynvalue, $sRefNr) {
        $blAddRedirectUrls = false;

        switch ($oOrder->oxorder__oxpaymenttype->value) {
            case 'fcpocreditcard':
                $blAddRedirectUrls = $this->_setPaymentParamsCC($aDynvalue);
                break;
            case 'fcpocreditcard_iframe':
                $this->addParameter('clearingtype', 'cc'); //Payment method
                $blAddRedirectUrls = true;
                break;
            case 'fcpocashondel':
                $this->addParameter('clearingtype', 'cod'); //Payment method
                $this->addParameter('shippingprovider', 'DHL');
                break;
            case 'fcpodebitnote':
                $blAddRedirectUrls = $this->_setPaymentParamsDebitNote($aDynvalue);
                break;
            case 'fcpopayadvance':
                $this->addParameter('clearingtype', 'vor'); //Payment method
                break;
            case 'fcpoinvoice':
                $this->addParameter('clearingtype', 'rec'); //Payment method
                break;
            case 'fcpoonlineueberweisung':
                $this->addParametersOnlineTransaction($oOrder, $aDynvalue);
                $blAddRedirectUrls = true;
                break;
            case 'fcpopaypal':
            case 'fcpopaypal_express':
                $blAddRedirectUrls = $this->_setPaymentParamsPayPal($oOrder, $sRefNr);
                break;
            case 'fcpobillsafe':
                $this->addParameter('clearingtype', 'fnc'); //Payment method
                $this->addParameter('financingtype', 'BSV');
                $blAddRedirectUrls = true;
                break;
            case 'fcpoklarna':
                $this->addParameter('clearingtype', 'fnc'); //Payment method
                $this->addParameter('financingtype', 'KLV');
                break;
                break;
            case 'fcpobarzahlen':
                $this->addParameter('clearingtype', 'csh'); //Payment method
                $this->addParameter('cashtype', 'BZN');
                $this->addParameter('api_version', '3.10');
                break;
            case 'fcpopaydirekt':
                $this->addParameter('clearingtype', 'wlt'); //Payment method
                $this->addParameter('wallettype', 'PDT');
                if (strlen($sRefNr) <= 37) {// 37 is the max in this parameter for paydirekt - otherwise the request will fail
                    $this->addParameter('narrative_text', $sRefNr);
                }
                $blAddRedirectUrls = true;
                break;
            case 'fcpopo_bill':
            case 'fcpopo_debitnote':
            case 'fcpopo_installment':
                $blAddRedirectUrls = $this->_fcpoAddPayolutionParameters($oOrder);
                break;
            case 'fcporp_bill':
                $blAddRedirectUrls = $this->_fcpoAddRatePayParameters($oOrder);
                break;
            default:
                return false;
        }

        if ($blAddRedirectUrls === true) {
            $this->_addRedirectUrls('payment', $sRefNr);
        }
        return true;
    }

    protected function _addRedirectUrls($sAbortClass, $sRefNr = false, $blIsPayPalExpress = false) {
        $oConfig = $this->getConfig();
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $sShopURL = $oConfig->getCurrentShopUrl();

        $sRToken = '';
        if ($this->_oFcpoHelper->fcpoGetIntShopVersion() >= 4310) {
            $sRToken = '&rtoken=' . $oSession->getRemoteAccessToken();
        }

        $sSid = $oSession->sid(true);
        if ($sSid != '') {
            $sSid = '&' . $sSid;
        }

        $sAddParams = '';

        if ($sRefNr) {
            $sAddParams .= '&refnr=' . $sRefNr;
        }

        if ($blIsPayPalExpress === true) {
            $sAddParams .= '&fnc=fcpoHandlePayPalExpress';
        } else {
            $sAddParams .= '&fnc=execute';
        }

        if ($this->_oFcpoHelper->fcpoGetRequestParameter('sDeliveryAddressMD5')) {
            $sAddParams .= '&sDeliveryAddressMD5=' . $this->_oFcpoHelper->fcpoGetRequestParameter('sDeliveryAddressMD5');
        }

        $blDownloadableProductsAgreement = $this->_oFcpoHelper->fcpoGetRequestParameter('oxdownloadableproductsagreement');
        if ($blDownloadableProductsAgreement) {
            $sAddParams .= '&fcdpa=1'; // rewrite for oxdownloadableproductsagreement-param because of length-restriction
        }

        $blServiceProductsAgreement = $this->_oFcpoHelper->fcpoGetRequestParameter('oxserviceproductsagreement');
        if ($blServiceProductsAgreement) {
            $sAddParams .= '&fcspa=1'; // rewrite for oxserviceproductsagreement-param because of length-restriction
        }

        $sSuccessUrl = $sShopURL . 'index.php?cl=order&fcposuccess=1&ord_agb=1&stoken=' . $this->_oFcpoHelper->fcpoGetRequestParameter('stoken') . $sSid . $sAddParams . $sRToken;
        $sErrorUrl = $sShopURL . 'index.php?type=error&cl=' . $sAbortClass . $sRToken;
        $sBackUrl = $sShopURL . 'index.php?type=cancel&cl=' . $sAbortClass . $sRToken;

        $this->addParameter('successurl', $sSuccessUrl);
        $this->addParameter('errorurl', $sErrorUrl);
        $this->addParameter('backurl', $sBackUrl);
    }

    /**
     * Set payment parameters for the payment method "Online ?berweisung"
     * and return true if payment-method is known or false if payment-method is unknown
     *
     * @param object $oOrder order object
     * @param array $aDynvalue form data
     * 
     * @return null
     */
    protected function addParametersOnlineTransaction($oOrder, $aDynvalue) {
        $this->addParameter('clearingtype', 'sb'); //Payment method
        $this->addParameter('onlinebanktransfertype', $aDynvalue['fcpo_sotype']);
        // Override mode for Sofort-?berweisung type
        $this->addParameter('mode', $this->getOperationMode($oOrder->oxorder__oxpaymenttype->value, $aDynvalue['fcpo_sotype']));
        switch ($aDynvalue['fcpo_sotype']) {
            case 'PNT':
                $oBillCountry = oxNew('oxcountry');
                $oBillCountry->load($oOrder->oxorder__oxbillcountryid->value);
                $this->addParameter('bankcountry', $oBillCountry->oxcountry__oxisoalpha2->value);
                if (isset($aDynvalue['fcpo_ou_ktonr']) && $aDynvalue['fcpo_ou_ktonr'] != '' && isset($aDynvalue['fcpo_ou_blz']) && $aDynvalue['fcpo_ou_blz'] != '') {
                    $this->addParameter('bankaccount', $aDynvalue['fcpo_ou_ktonr']);
                    $this->addParameter('bankcode', $aDynvalue['fcpo_ou_blz']);
                } elseif (isset($aDynvalue['fcpo_ou_iban']) && $aDynvalue['fcpo_ou_iban'] != '' && isset($aDynvalue['fcpo_ou_bic']) && $aDynvalue['fcpo_ou_bic'] != '') {
                    $this->addParameter('iban', $aDynvalue['fcpo_ou_iban']);
                    $this->addParameter('bic', $aDynvalue['fcpo_ou_bic']);
                }
                break;
            case 'GPY':
                $this->addParameter('bankcountry', 'DE');
                $this->addParameter('iban', $aDynvalue['fcpo_ou_iban']);
                $this->addParameter('bic', $aDynvalue['fcpo_ou_bic']);
                break;
            case 'EPS':
                $this->addParameter('bankcountry', 'AT');
                $this->addParameter('bankgrouptype', $aDynvalue['fcpo_so_bankgrouptype_eps']);
                break;
            case 'PFF':
                $this->addParameter('bankcountry', 'CH');
                break;
            case 'PFC':
                $this->addParameter('bankcountry', 'CH');
                break;
            case 'IDL':
                $this->addParameter('bankcountry', 'NL');
                $this->addParameter('bankgrouptype', $aDynvalue['fcpo_so_bankgrouptype_idl']);
                break;
            case 'P24':
                $this->addParameter('bankcountry', 'PL');
            default:
                break;
        }
    }

    /**
     * Add product information for module invoicing
     *
     * @param object $oOrder order object
     * 
     * @return null
     */
    public function addProductInfo($oOrder, $aPositions = false, $blDebit = false) {
        $dAmount = 0;

        $aOrderArticleListe = $oOrder->getOrderArticles();
        $i = 1;

        foreach ($aOrderArticleListe->getArray() as $oOrderarticle) {
            if ($aPositions === false || array_key_exists($oOrderarticle->getId(), $aPositions) !== false) {
                if ($aPositions !== false && array_key_exists($oOrderarticle->getId(), $aPositions) !== false) {
                    $dItemAmount = $aPositions[$oOrderarticle->getId()]['amount'];
                } else {
                    $dItemAmount = $oOrderarticle->oxorderarticles__oxamount->value;
                }
                $this->addParameter('id[' . $i . ']', $oOrderarticle->oxorderarticles__oxartnum->value);
                $this->addParameter('pr[' . $i . ']', number_format($oOrderarticle->oxorderarticles__oxbprice->value, 2, '.', '') * 100);
                $dAmount += $oOrderarticle->oxorderarticles__oxbprice->value * $dItemAmount;
                $this->addParameter('it[' . $i . ']', 'goods');
                $this->addParameter('no[' . $i . ']', $dItemAmount);
                $this->addParameter('de[' . $i . ']', $oOrderarticle->oxorderarticles__oxtitle->value);
                $this->addParameter('va[' . $i . ']', number_format($oOrderarticle->oxorderarticles__oxvat->value * 100, 0, '.', ''));
                $i++;
            }
        }

        $sQuery = "SELECT IF(SUM(fcpocapturedamount) = 0, 1, 0) AS b FROM oxorderarticles WHERE oxorderid = '{$oOrder->getId()}' GROUP BY oxorderid";
        $blFirstCapture = (bool) oxDb::getDb()->GetOne($sQuery);

        if ($aPositions === false || $blFirstCapture === true || $blDebit === true) {
            $oLang = $this->_oFcpoHelper->fcpoGetLang();
            if ($oOrder->oxorder__oxdelcost->value != 0 && ($aPositions === false || ($blDebit === false || array_key_exists('oxdelcost', $aPositions) !== false))) {
                $sDelDesc = '';
                if ($oOrder->oxorder__oxdelcost->value > 0) {
                    $sDelDesc .= $oLang->translateString('FCPO_SURCHARGE', null, false);
                } else {
                    $sDelDesc .= $oLang->translateString('FCPO_DEDUCTION', null, false);
                }
                $sDelDesc .= ' ' . str_replace(':', '', $oLang->translateString('FCPO_SHIPPINGCOST', null, false));
                $this->addParameter('id[' . $i . ']', 'delivery');
                $this->addParameter('pr[' . $i . ']', number_format($oOrder->oxorder__oxdelcost->value, 2, '.', '') * 100);
                $dAmount += $oOrder->oxorder__oxdelcost->value;
                $this->addParameter('it[' . $i . ']', 'shipment');
                $this->addParameter('no[' . $i . ']', 1);
                $this->addParameter('de[' . $i . ']', $sDelDesc);
                $this->addParameter('va[' . $i . ']', number_format($oOrder->oxorder__oxdelvat->value * 100, 0, '.', ''));
                $i++;
            }
            if ($oOrder->oxorder__oxpaycost->value != 0 && ($aPositions === false || ($blDebit === false || array_key_exists('oxpaycost', $aPositions) !== false))) {
                $sPayDesc = '';
                if ($oOrder->oxorder__oxpaycost->value > 0) {
                    $sPayDesc .= $oLang->translateString('FCPO_SURCHARGE', null, false);
                } else {
                    $sPayDesc .= $oLang->translateString('FCPO_DEDUCTION', null, false);
                }
                $sPayDesc .= ' ' . str_replace(':', '', $oLang->translateString('FCPO_PAYMENTTYPE', null, false));
                $this->addParameter('id[' . $i . ']', 'payment');
                $this->addParameter('pr[' . $i . ']', number_format($oOrder->oxorder__oxpaycost->value, 2, '.', '') * 100);
                $dAmount += $oOrder->oxorder__oxpaycost->value;
                $this->addParameter('it[' . $i . ']', 'handling');
                $this->addParameter('no[' . $i . ']', 1);
                $this->addParameter('de[' . $i . ']', $sPayDesc);
                $this->addParameter('va[' . $i . ']', number_format($oOrder->oxorder__oxpayvat->value * 100, 0, '.', ''));
                $i++;
            }
            if ($oOrder->oxorder__oxwrapcost->value != 0 && ($aPositions === false || ($blDebit === false || array_key_exists('oxwrapcost', $aPositions) !== false))) {
                $this->addParameter('id[' . $i . ']', 'wrapping');
                $this->addParameter('pr[' . $i . ']', number_format($oOrder->oxorder__oxwrapcost->value, 2, '.', '') * 100);
                $dAmount += $oOrder->oxorder__oxwrapcost->value;
                $this->addParameter('it[' . $i . ']', 'goods');
                $this->addParameter('no[' . $i . ']', 1);
                $this->addParameter('de[' . $i . ']', $oLang->translateString('FCPO_WRAPPING', null, false));
                $this->addParameter('va[' . $i . ']', number_format($oOrder->oxorder__oxwrapvat->value * 100, 0, '.', ''));
                $i++;
            }
            if ($oOrder->oxorder__oxgiftcardcost->value != 0 && ($aPositions === false || ($blDebit === false || array_key_exists('oxgiftcardcost', $aPositions) !== false))) {
                $this->addParameter('id[' . $i . ']', 'giftcard');
                $this->addParameter('pr[' . $i . ']', number_format($oOrder->oxorder__oxgiftcardcost->value, 2, '.', '') * 100);
                $dAmount += $oOrder->oxorder__oxgiftcardcost->value;
                $this->addParameter('it[' . $i . ']', 'goods');
                $this->addParameter('no[' . $i . ']', 1);
                $this->addParameter('de[' . $i . ']', $oLang->translateString('FCPO_GIFTCARD', null, false));
                $this->addParameter('va[' . $i . ']', number_format($oOrder->oxorder__oxgiftcardvat->value * 100, 0, '.', ''));
                $i++;
            }
            if ($oOrder->oxorder__oxvoucherdiscount->value != 0 && ($aPositions === false || ($blDebit === false || array_key_exists('oxvoucherdiscount', $aPositions) !== false))) {
                $this->addParameter('id[' . $i . ']', 'voucher');
                $this->addParameter('pr[' . $i . ']', $oOrder->oxorder__oxvoucherdiscount->value * -100);
                $dAmount += ($oOrder->oxorder__oxvoucherdiscount->value * -1);
                $this->addParameter('it[' . $i . ']', 'voucher');
                $this->addParameter('no[' . $i . ']', 1);
                $this->addParameter('de[' . $i . ']', $oLang->translateString('FCPO_VOUCHER', null, false));
                $this->addParameter('va[' . $i . ']', number_format($oOrder->oxorder__oxartvat1->value * 100, 0, '.', ''));
                $i++;
            }
            if ($oOrder->oxorder__oxdiscount->value != 0 && ($aPositions === false || ($blDebit === false || array_key_exists('oxdiscount', $aPositions) !== false))) {
                $this->addParameter('id[' . $i . ']', 'discount');
                $this->addParameter('pr[' . $i . ']', round($oOrder->oxorder__oxdiscount->value, 2) * -100);
                $dAmount += (round($oOrder->oxorder__oxdiscount->value, 2) * -1);
                $this->addParameter('it[' . $i . ']', 'voucher');
                $this->addParameter('no[' . $i . ']', 1);
                $this->addParameter('de[' . $i . ']', $oLang->translateString('FCPO_DISCOUNT', null, false));
                $this->addParameter('va[' . $i . ']', number_format($oOrder->oxorder__oxartvat1->value * 100, 0, '.', ''));
            }
        }
        return $dAmount;
    }

    /**
     * Send request to PAYONE Server-API with request-type "authorization" or "preauthorization"
     *
     * @param object $oOrder order object
     * @param object $oUser user object
     * @param array $aDynvalue form data
     * @param string $sRefNr payone reference number
     * 
     * @return array
     */
    public function sendRequestAuthorization($sType, $oOrder, $oUser, $aDynvalue, $sRefNr) {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $this->addParameter('request', $sType); //Request method
        $this->addParameter('mode', $this->getOperationMode($oOrder->oxorder__oxpaymenttype->value)); //PayOne Portal Operation Mode (live or test)

        $blIsPreAuth = $sType == 'preauthorization' ? true : false;

        $blPayMethodIsKnown = $this->setAuthorizationParameters($oOrder, $oUser, $aDynvalue, $sRefNr, $blIsPreAuth);
        if ($blPayMethodIsKnown === true) {
            if (fcPayOnePayment::fcIsPayOneFrontendApiPaymentType($oOrder->oxorder__oxpaymenttype->value)) {
                return $this->_handleFrontendApiCall();
            }
            return $this->send();
        } else {
            return false;
        }
    }

    protected function _getFrontendHash($aHashParams) {
        $oConfig = $this->getConfig();
        ksort($aHashParams, SORT_STRING);
        unset($aHashParams['key']);
        $aHashParams['key'] = $oConfig->getConfigParam('sFCPOPortalKey');

        $sHashString = '';
        foreach ($aHashParams as $sKey => $sValue) {
            $sHashString .= $sValue;
        }
        return md5($sHashString);
    }

    protected function _getFrontendApiUrl() {
        $this->_aParameters['targetwindow'] = 'parent';

        $aHashParams = array();
        foreach ($this->_aParameters as $sKey => $sValue) {
            if (array_search($sKey, $this->_aFrontendUnsetParams) !== false) {
                unset($this->_aParameters[$sKey]);
            } elseif (array_search($sKey, $this->_aFrontendHashParams) !== false || stripos($sKey, '[') !== false) {
                $aHashParams[$sKey] = $sValue;
            }
        }
        $this->_aParameters['hash'] = $this->_getFrontendHash($aHashParams);


        $sUrlParams = '?';
        foreach ($this->_aParameters as $sKey => $sValue) {
            $sUrlParams .= $sKey . '=' . urlencode($sValue) . '&';
        }
        $sUrlParams = rtrim($sUrlParams, '&');
        $sFrontendApiUrl = $this->_sFrontendApiUrl . $sUrlParams;

        $this->_logRequest('NONE - Frontend API Call', 'Frontend API');
        return $sFrontendApiUrl;
    }

    protected function _handleFrontendApiCall() {
        $sFrontendApiUrl = $this->_getFrontendApiUrl();

        $aStatus = array(
            'status' => 'REDIRECT',
            'txid' => '',
            'redirecturl' => $sFrontendApiUrl,
        );
        return $aStatus;
    }

    /**
     * Template getter for checking which kind of field should be shown
     * 
     * @param void
     * @return bool
     */
    public function fcpoIsB2B($oUser) {
        $oConfig = $this->getConfig();
        $blB2BModeActive = $oConfig->getConfigParam('blFCPOPayolutionB2BMode');

        if ($blB2BModeActive) {
            $blCompany = ($oUser->oxuser__oxcompany->value) ? true : false;
            $blReturn = $blCompany;
            // check if we already have ustid, then showing is not needed
            if ($blCompany) {
                $blReturn = ($oUser->oxuser__oxustid->value) ? false : true;
            }
        } else {
            $blReturn = false;
        }

        return $blReturn;
    }

    /**
     * Method adds all bunch of ratepay-params
     * 
     * @param oxOrder $oOrder
     * @return false => no redirect params 
     */
    protected function _fcpoAddRatePayParameters($oOrder) {
        // needed objects and data
        $oConfig = $this->getConfig();
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $oRatePay = oxNew('fcporatepay');
        $sPaymentId = $oOrder->oxorder__oxpaymenttype->value;
        $oUser = $oOrder->getOrderUser();
        $sProfileId = $this->_oFcpoHelper->fcpoGetSessionVariable('ratepayprofileid');
        $aProfileData = $oRatePay->fcpoGetProfileData($sProfileId);
        $sRatePayShopId = $aProfileData['shopid'];
        $sDeviceFingerprint = md5($oUser->oxuser__oxfname->value.$oUser->oxuser__oxlname->value.  microtime());

        $sFinancignType = $this->_fcpoGetFinancingTypeByPaymentId($sPaymentId);
        $oCur = $oConfig->getActShopCurrencyObject();
        $sCountry = '';
        $oCountry = oxNew('oxcountry');
        if ($oCountry->load($oUser->oxuser__oxcountryid->value)) {
            $sCountry = $oCountry->oxcountry__oxisoalpha2->value;
        }
        $oCountry = oxNew('oxcountry');
        $sShippingCountry = '';
        if ($oCountry->load($oOrder->oxuser__oxdelcountryid->value)) {
            $sShippingCountry = $oCountry->oxcountry__oxisoalpha2->value;
        }
        if (!$sShippingCountry) {
            $sShippingCountry = $sCountry;
        }
        
        $sShippingFirstName = ($oOrder->oxorder__oxdelfname->value) ? $oOrder->oxorder__oxdelfname->value : $oUser->oxuser__oxfname->value;
        $sShippingLastName = ($oOrder->oxorder__oxdellname->value) ? $oOrder->oxorder__oxdellname->value : $oUser->oxuser__oxlname->value;
        $sShippingStreet = ($oOrder->oxorder__oxdelstreet->value) ? $oOrder->oxorder__oxdelstreet->value." ".$oOrder->oxorder__oxdelstreetnr->value : $oUser->oxuser__oxstreet->value.' '.$oUser->oxuser__oxstreetnr->value;
        $sShippingZip = ($oOrder->oxorder__oxdelzip->value) ? $oOrder->oxorder__oxdelzip->value : $oUser->oxuser__oxzip->value;
        $sShippingCity = ($oOrder->oxorder__oxdelcity->value) ? $oOrder->oxorder__oxdelcity->value : $oUser->oxuser__oxcity->value;

        if ($oConfig->isUtf()) {
            $this->addParameter('encoding', 'UTF-8');
        } else {
            $this->addParameter('encoding', 'ISO-8859-1');
        }

        // set common params
        $this->addParameter('clearingtype', 'fnc');
        $this->addParameter('currency', $oCur->name);
        $this->addParameter('financingtype', $sFinancignType);

        // set ratepay params
        $this->addParameter('add_paydata[shop_id]', $sRatePayShopId);
        $this->addParameter('add_paydata[device_token]', $sDeviceFingerprint);
        $this->addParameter('add_paydata[customer_allow_credit_inquiry]', 'yes');
        $this->addParameter('add_paydata[vat_id]', 'DE265567757');
        $this->addParameter('customer_is_present', 'yes');
        $this->addParameter('api_version', '3.10');
        $this->addParameter('param', 'session-1');
        $this->addParameter('shop_id', $sRatePayShopId);
        $this->addParameter('data', $sRatePayShopId);
        $this->addParameter('email', $oUser->oxuser__oxusername->value);
        $this->addParameter('firstname', $oUser->oxuser__oxfname->value);
        $this->addParameter('lastname', $oUser->oxuser__oxlname->value);
        $this->addParameter('street', $oUser->oxuser__oxstreet->value.' '.$oUser->oxuser__oxstreetnr->value);
        $this->addParameter('zip', $oUser->oxuser__oxzip->value);
        $this->addParameter('city', $oUser->oxuser__oxcity->value);
        $this->addParameter('company', $oUser->oxuser__oxcompany->value);
        $this->addParameter('shipping_country', strtoupper($sCountry));
        $this->addParameter('shipping_firstname', $sShippingFirstName);
        $this->addParameter('shipping_lastname', $sShippingLastName);
        $this->addParameter('shipping_street', $sShippingStreet);
        $this->addParameter('shipping_zip', $sShippingZip);
        $this->addParameter('shipping_city', $sShippingCity);
        $this->addParameter('shipping_country', strtoupper($sShippingCountry));

        // basket handling
        $oBasket = $oSession->getBasket();
        $iIndex = 1;
        foreach ($oBasket->getContents() as $oBasketItem) {
            $oArticle = $oBasketItem->getArticle();
            $sArticleIdent = ($oArticle->oxarticles__oxean->value) ? $oArticle->oxarticles__oxean->value : $oArticle->oxarticles__oxartnum->value;
                
            $this->addParameter('it[' . (string) $iIndex . ']', 'goods');
            $this->addParameter('id[' . (string) $iIndex . ']', $sArticleIdent);
            $this->addParameter('pr[' . (string) $iIndex . ']', $this->_fcpoGetCentPrice($oBasketItem));
            $this->addParameter('no[' . (string) $iIndex . ']', $oBasketItem->getAmount());
            $this->addParameter('de[' . (string) $iIndex . ']', $oBasketItem->getTitle());
            $this->addParameter('va[' . (string) $iIndex . ']', $this->_fcpoGetCentPrice($oBasketItem->getPrice()->getVat()));
            $iIndex++;
        }

        $sDeliveryCosts = $oBasket->getCosts('oxdelivery')->getBruttoPrice();
        $dDelveryCosts = (double) str_replace(',', '.', $sDeliveryCosts);
        $this->addParameter('it[' . (string) $iIndex . ']', 'shipment');
        $this->addParameter('id[' . (string) $iIndex . ']', 'Standard Versand');
        $this->addParameter('pr[' . (string) $iIndex . ']', $this->_fcpoGetCentPrice($dDelveryCosts));
        $this->addParameter('no[' . (string) $iIndex . ']', '1');
        $this->addParameter('de[' . (string) $iIndex . ']', 'Standard Versand');

        return false;
    }

    /**
     * Item price in smallest available unit
     * 
     * @param oxBasketItem/double $mValue
     * @return int
     */
    protected function _fcpoGetCentPrice($mValue) {
        $oConfig = $this->getConfig();
        $dBruttoPrice = 0.00;
        if ($mValue instanceof oxBasketItem) {
            $oPrice = $mValue->getPrice();
            $dBruttoPricePosSum = $oPrice->getBruttoPrice();
            $dAmount = $mValue->getAmount();
            $dBruttoPrice = round($dBruttoPricePosSum/$dAmount, 2);
        } else if (is_float($mValue)) {
            $dBruttoPrice = $mValue;
        }
        if (isset($dBruttoPrice)) {
            $oCur = $oConfig->getActShopCurrencyObject();
            $dFactor = (double) pow(10, $oCur->decimal);
            
            $dReturnPrice = $dBruttoPrice * $dFactor;
        }

        return $dReturnPrice;
    }

    /**
     * Adds needed parameters for payolution
     * 
     * @param oxOrder $oOrder
     * @return bool
     */
    protected function _fcpoAddPayolutionParameters($oOrder) {
        $oConfig = $this->getConfig();
        $sPaymentId = $oOrder->oxorder__oxpaymenttype->value;
        $oUser = $oOrder->getOrderUser();
        $sWorkorderId = $this->_oFcpoHelper->fcpoGetSessionVariable('payolution_workorderid');
        $aBankData = $this->_oFcpoHelper->fcpoGetSessionVariable('payolution_bankdata');
        $sInstallmentDuration = $this->_oFcpoHelper->fcpoGetSessionVariable('payolution_installment_duration');
        $sFieldNameAddition = str_replace("fcpopo_", "", $sPaymentId);

        $this->addParameter('clearingtype', 'fnc');
        $sPaymentType = $this->_fcpoGetPayolutionPaymentTypeById($sPaymentId);
        $sFinancignType = $this->_fcpoGetFinancingTypeByPaymentId($sPaymentId);
        $this->addParameter('financingtype', $sFinancignType);
        $this->addParameter('add_paydata[payment_type]', $sPaymentType);
        $this->addParameter('api_version', '3.10');
        $this->addParameter('mode', $this->getOperationMode($oOrder->oxorder__oxpaymenttype->value));

        $this->_fcpoAddPayolutionUserData($oUser, $sPaymentId);

        if ($sWorkorderId !== null) {
            $this->addParameter('workorderid', $sWorkorderId);
        }

        $blValidBankData = (
                isset($aBankData) &&
                is_array($aBankData) &&
                count($aBankData) == 3 &&
                $aBankData['fcpo_payolution_' . $sFieldNameAddition . '_accountholder'] &&
                $aBankData['fcpo_payolution_' . $sFieldNameAddition . '_iban'] &&
                $aBankData['fcpo_payolution_' . $sFieldNameAddition . '_bic']
                );

        if ($blValidBankData) {
            $this->addParameter('iban', $aBankData['fcpo_payolution_' . $sFieldNameAddition . '_iban']);
            $this->addParameter('bic', $aBankData['fcpo_payolution_' . $sFieldNameAddition . '_bic']);
        }

        if ($oConfig->isUtf()) {
            $this->addParameter('encoding', 'UTF-8');
        } else {
            $this->addParameter('encoding', 'ISO-8859-1');
        }

        $sIp = $this->_fcpoGetRemoteAddress();
        if ($sIp != '')
            $this->addParameter('ip', $sIp);

        $this->addParameter('language', $this->_oFcpoHelper->fcpoGetLang()->getLanguageAbbr());

        if ($sInstallmentDuration) {
            $this->addParameter('add_paydata[installment_duration]', $sInstallmentDuration);
        }

        $this->_oFcpoHelper->fcpoDeleteSessionVariable('payolution_workorderid');
        $this->_oFcpoHelper->fcpoDeleteSessionVariable('payolution_bankdata');
        $this->_oFcpoHelper->fcpoDeleteSessionVariable('payolution_installment_duration');

        return false;
    }

    /**
     * Performs a refund_anouncement call
     * 
     * @param oxOrder $oOrder
     * @return array
     */
    public function sendRequestPayolutionRefundAnnouncement($oOrder) {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $sTxid = $oOrder->oxorder__fcpotxid->value;
        $sWorkorderId = $oOrder->oxorder__fcpoworkorderid->value;
        $sPaymentId = $oOrder->oxorder__oxpaymenttype->value;

        $this->addParameter('request', 'genericpayment'); //Request method
        $this->addParameter('mode', $this->getOperationMode($sPaymentId)); //PayOne Portal Operation Mode (live or test)
        $this->addParameter('aid', $oConfig->getConfigParam('sFCPOSubAccountID')); //ID of PayOne Sub-Account
        $this->addParameter('clearingtype', 'fnc');
        $oCurr = $oConfig->getActShopCurrencyObject();
        $this->addParameter('currency', $oCurr->name);
        $this->addParameter('add_paydata[action]', 'refund_announcement');
        $this->addParameter('api_version', '3.10');
        $this->addParameter('txid', $sTxid);
        $this->addParameter('workorderid', $sWorkorderId);

        return $this->send();
    }

    /**
     * 
     * @param string $sPaymentId
     * @param oxUser $oUser
     * @param array $aBankData
     * @param string $sAction
     * @param string $sWorkorderId
     * @return array
     */
    public function sendRequestPayolutionInstallment($sPaymentId, $oUser, $aBankData = null, $sAction = 'calculation', $sWorkorderId = null, $sDuration = null) {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $oSession = $this->_oFcpoHelper->fcpoGetSession();

        $sRequestMethod = 'genericpayment';
        $sRequestMethod = ($sAction == 'preauthorization') ? 'preauthorization' : 'genericpayment';
        $this->addParameter('request', $sRequestMethod); //Request method
        $this->addParameter('mode', $this->getOperationMode($sPaymentId)); //PayOne Portal Operation Mode (live or test)
        $this->addParameter('aid', $oConfig->getConfigParam('sFCPOSubAccountID')); //ID of PayOne Sub-Account

        $this->addParameter('clearingtype', 'fnc');

        $oBasket = $oSession->getBasket();
        $oPrice = $oBasket->getPrice();
        $this->addParameter('amount', number_format($oPrice->getBruttoPrice(), 2, '.', '') * 100);

        $oCurr = $oConfig->getActShopCurrencyObject();
        $this->addParameter('currency', $oCurr->name);
        $sFinancingType = $this->_fcpoGetFinancingTypeByPaymentId($sPaymentId);
        $this->_fcpoAddPayolutionUserData($oUser, $sPaymentId);
        $this->addParameter('financingtype', $sFinancingType);
        $this->addParameter('add_paydata[action]', $sAction);
        $this->addParameter('api_version', '3.10');

        if ($sWorkorderId !== null) {
            $this->addParameter('workorderid', $sWorkorderId);
        }

        if ($oConfig->isUtf()) {
            $this->addParameter('encoding', 'UTF-8');
        } else {
            $this->addParameter('encoding', 'ISO-8859-1');
        }

        $sIp = $this->_fcpoGetRemoteAddress();
        if ($sIp != '')
            $this->addParameter('ip', $sIp);

        $this->addParameter('language', $this->_oFcpoHelper->fcpoGetLang()->getLanguageAbbr());

        $blValidBankData = (
                isset($aBankData) &&
                is_array($aBankData) &&
                count($aBankData) == 3 &&
                $aBankData['fcpo_payolution_installment_accountholder'] &&
                $aBankData['fcpo_payolution_installment_iban'] &&
                $aBankData['fcpo_payolution_installment_bic']
                );

        if ($blValidBankData) {
            $this->addParameter('iban', $aBankData['fcpo_payolution_installment_iban']);
            $this->addParameter('bic', $aBankData['fcpo_payolution_installment_bic']);
        }

        return $this->send();
    }

    /**
     * Sends a payolution precheck request to 
     * 
     * @param string $sType
     * @param object $oUser
     * @param string $sWorkorderId
     * @return array
     */
    public function sendRequestPayolutionPreCheck($sPaymentId, $oUser, $aBankData, $sWorkorderId = null) {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $sTelephoneNumber = $oUser->oxuser__oxfon->value;

        $this->addParameter('request', 'genericpayment'); //Request method
        $this->addParameter('mode', $this->getOperationMode($sPaymentId)); //PayOne Portal Operation Mode (live or test)
        $this->addParameter('aid', $oConfig->getConfigParam('sFCPOSubAccountID')); //ID of PayOne Sub-Account

        $this->addParameter('clearingtype', 'fnc');

        $oBasket = $oSession->getBasket();
        $oPrice = $oBasket->getPrice();
        $this->addParameter('amount', number_format($oPrice->getBruttoPrice(), 2, '.', '') * 100);

        $oCurr = $oConfig->getActShopCurrencyObject();
        $this->addParameter('currency', $oCurr->name);

        $sPaymentType = $this->_fcpoGetPayolutionPaymentTypeById($sPaymentId);
        $sFinancignType = $this->_fcpoGetFinancingTypeByPaymentId($sPaymentId);
        $this->_fcpoAddPayolutionUserData($oUser, $sPaymentId);

        $this->addParameter('financingtype', $sFinancignType);
        $this->addParameter('add_paydata[action]', 'pre_check');
        $this->addParameter('add_paydata[payment_type]', $sPaymentType);
        $this->addParameter('api_version', '3.10');

        if ($sWorkorderId !== null) {
            $this->addParameter('workorderid', $sWorkorderId);
        }

        if ($sTelephoneNumber) {
            $this->addParameter('telephonenumber', $sTelephoneNumber);
        }

        if ($oConfig->isUtf()) {
            $this->addParameter('encoding', 'UTF-8');
        } else {
            $this->addParameter('encoding', 'ISO-8859-1');
        }

        $sIp = $this->_fcpoGetRemoteAddress();
        if ($sIp != '')
            $this->addParameter('ip', $sIp);

        $this->addParameter('language', $this->_oFcpoHelper->fcpoGetLang()->getLanguageAbbr());



        $blValidBankData = (
                isset($aBankData) &&
                is_array($aBankData) &&
                count($aBankData) == 3 &&
                $aBankData['fcpo_payolution_accountholder'] &&
                $aBankData['fcpo_payolution_iban'] &&
                $aBankData['fcpo_payolution_bic']
                );

        if ($blValidBankData) {
            $this->addParameter('iban', $aBankData['fcpo_payolution_iban']);
            $this->addParameter('bic', $aBankData['fcpo_payolution_bic']);
        }

        return $this->send();
    }

    /**
     * Adds userdata by offering a user object
     * 
     * @param object $oUser
     * @param string $sPaymentId
     * @return void
     */
    protected function _fcpoAddPayolutionUserData($oUser, $sPaymentId) {
        $this->addParameter('email', $oUser->oxuser__oxusername->value);
        $this->addParameter('firstname', $oUser->oxuser__oxfname->value);
        $this->addParameter('lastname', $oUser->oxuser__oxlname->value);
        $this->addParameter('street', $oUser->oxuser__oxstreet->value . " " . $oUser->oxuser__oxstreetnr->value); // and number
        $this->addParameter('zip', $oUser->oxuser__oxzip->value);
        $this->addParameter('city', $oUser->oxuser__oxcity->value);
        $blAddCompanyData = $this->_fcpoCheckAddCompanyData($oUser, $sPaymentId);
        if ($blAddCompanyData) {
            $this->addParameter('company', $oUser->oxuser__oxcompany->value);
            $this->addParameter('add_paydata[company_uid]', $oUser->oxuser__oxustid->value);
            $this->addParameter('add_paydata[b2b]', 'yes');
        }

        if ($oUser->oxuser__oxbirthdate->value != '0000-00-00') {
            $sBirthday = str_replace('-', '', $oUser->oxuser__oxbirthdate->value);
            $this->addParameter('birthday', $sBirthday);
        }

        $sCountry = '';
        $oCountry = oxNew('oxcountry');
        if ($oCountry->load($oUser->oxuser__oxcountryid->value)) {
            $sCountry = $oCountry->oxcountry__oxisoalpha2->value;
        }
        $this->addParameter('country', strtoupper($sCountry));
    }

    /**
     * Returns if company data should be added to call deepending on settings and payment type
     * 
     * @param oxUser $oUser
     * @param string $sPaymentId
     * @return bool
     */
    protected function _fcpoCheckAddCompanyData($oUser, $sPaymentId) {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $blB2BModeActive = $oConfig->getConfigParam('blFCPOPayolutionB2BMode');
        $blValidPaymentForCompanyData = in_array($sPaymentId, array('fcpopo_bill'));
        $blReturn = ($blB2BModeActive && $oUser->oxuser__oxcompany->value && $blValidPaymentForCompanyData);

        return $blReturn;
    }

    /**
     * Method returns matching financing type for a given payment id
     * 
     * @param string $sPaymentId
     * @return string
     */
    protected function _fcpoGetFinancingTypeByPaymentId($sPaymentId) {
        $aMap = array(
            'fcpopo_bill' => 'PYV',
            'fcpopo_debitnote' => 'PYD',
            'fcpopo_installment' => 'PYS',
            'fcporp_bill' => 'RPV',
        );

        $blPaymentIdMatch = isset($aMap[$sPaymentId]);

        $sReturn = '';
        if ($blPaymentIdMatch) {
            $sReturn = $aMap[$sPaymentId];
        }

        return $sReturn;
    }

    /**
     * Returns matching payolution payment type for given paymentid
     * 
     * @param string $sPaymentId
     * @return string
     */
    protected function _fcpoGetPayolutionPaymentTypeById($sPaymentId) {
        $aPayolutionPaymentMap = array(
            'fcpopo_bill' => 'Payolution-Invoicing',
            'fcpopo_debitnote' => 'Payolution-Debit',
            'fcpopo_installment' => 'Payolution-Installment',
        );

        if (isset($aPayolutionPaymentMap[$sPaymentId])) {
            $sPaymentType = $aPayolutionPaymentMap[$sPaymentId];
        } else {
            $sPaymentType = '';
        }

        return $sPaymentType;
    }

    /**
     * Send profile request to PAYONE Server-API with request-type "genericpayment"
     * 
     * @return array
     */
    public function sendRequestRatePayProfile($aRatePayData, $sWorkorderId = false) {
        $sPaymentId = $aRatePayData['OXPAYMENTID'];
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        /**
         * @todo: create method that fetches all saved params from profile
         * $aRatePayParams = $this->_fcpoGetRatePayParams($sRatePayShopId);
         */
        $sFinancingType = $this->_fcpoGetFinancingTypeByPaymentId($sPaymentId);

        $this->addParameter('request', 'genericpayment'); //Request method
        $this->addParameter('mode', $this->getOperationMode('fcpopaypal_express')); //PayOne Portal Operation Mode (live or test)
        $this->addParameter('aid', $oConfig->getConfigParam('sFCPOSubAccountID')); //ID of PayOne Sub-Account

        $this->addParameter('clearingtype', 'fnc');
        $this->addParameter('financingtype', $sFinancingType);

        if ($sWorkorderId !== false) {
            $this->addParameter('workorderid', $sWorkorderId);
        }
        $this->addParameter('add_paydata[action]', 'profile');
        $this->addParameter('add_paydata[shop_id]', $aRatePayData['shopid']);
        $this->addParameter('currency', $aRatePayData['currency']);

        return $this->send();
    }

    /**
     * Send request to PAYONE Server-API with request-type "genericpayment"
     * 
     * @return array
     */
    public function sendRequestGenericPayment($sWorkorderId = false) {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $oSession = $this->_oFcpoHelper->fcpoGetSession();

        $this->addParameter('request', 'genericpayment'); //Request method
        $this->addParameter('mode', $this->getOperationMode('fcpopaypal_express')); //PayOne Portal Operation Mode (live or test)
        $this->addParameter('aid', $oConfig->getConfigParam('sFCPOSubAccountID')); //ID of PayOne Sub-Account

        $this->addParameter('clearingtype', 'wlt');
        $this->addParameter('wallettype', 'PPE');

        $oBasket = $oSession->getBasket();
        $oPrice = $oBasket->getPrice();
        $this->addParameter('amount', number_format($oPrice->getBruttoPrice(), 2, '.', '') * 100);

        $oCurr = $oConfig->getActShopCurrencyObject();
        $this->addParameter('currency', $oCurr->name);

        $this->addParameter('narrative_text', 'Test');

        if ($sWorkorderId !== false) {
            $this->addParameter('workorderid', $sWorkorderId);
            $this->addParameter('add_paydata[action]', 'getexpresscheckoutdetails');
        } else {
            $this->addParameter('add_paydata[action]', 'setexpresscheckout');
        }

        $this->_addRedirectUrls('basket', false, true);

        return $this->send();
    }

    /**
     * Send request to PAYONE Server-API with request-type "capture"
     *
     * @param object $oOrder order object
     * @param double $dAmount capture amount
     * 
     * @return array
     */
    public function sendRequestCapture($oOrder, $dAmount, $blSettleAccount = true, $aPositions = false) {
        $this->addParameter('request', 'capture'); //Request method
        $sMode = $oOrder->oxorder__fcpomode->value;
        if ($sMode == '') {
            $sMode = $this->getOperationMode($oOrder->oxorder__oxpaymenttype->value);
        }
        $this->addParameter('mode', $sMode); //PayOne Portal Operation Mode (live or test)

        $this->addParameter('language', $this->_oFcpoHelper->fcpoGetLang()->getLanguageAbbr());
        $this->addParameter('txid', $oOrder->oxorder__fcpotxid->value); //PayOne Transaction ID
        $this->addParameter('sequencenumber', $oOrder->getSequenceNumber());
        $this->addParameter('amount', number_format($dAmount, 2, '.', '') * 100); //Total order sum in smallest currency unit
        $this->addParameter('currency', $oOrder->oxorder__oxcurrency->value); //Currency

        if ($oOrder->allowAccountSettlement() === true && $blSettleAccount === false) {
            $sSettleAccount = 'no';
        } else {
            $sSettleAccount = 'auto';
        }

        $this->addParameter('settleaccount', $sSettleAccount);

        if ($this->_oFcpoHelper->fcpoGetRequestParameter('capture_completeorder') == '1') {
            $this->addParameter('capturemode', 'completed');
        }

        // Bedingung $amount == $oOrder->oxorder__oxorder__oxtotalordersum->value nur solange wie Artikelliste nicht f?r Multi-Capture m?glich
        if ($oOrder->isDetailedProductInfoNeeded() || ($this->getConfig()->getConfigParam('blFCPOSendArticlelist') === true && $dAmount == $oOrder->oxorder__oxtotalordersum->value)) {
            $dAmount = $this->addProductInfo($oOrder, $aPositions);
            if ($aPositions !== false) {
                //partial-amount
                $this->addParameter('amount', number_format($dAmount, 2, '.', '') * 100); //Total order sum in smallest currency unit
            }
        }

        $this->_fcpoAddCaptureRatePayParams($oOrder);

        $aResponse = $this->send();

        if ($aPositions && $aResponse && array_key_exists('status', $aResponse) !== false && $aResponse['status'] == 'APPROVED') {
            foreach ($aPositions as $sOrderArtId => $aPos) {
                $sQuery = "UPDATE oxorderarticles SET fcpocapturedamount = fcpocapturedamount + {$aPos['amount']} WHERE oxid = '{$sOrderArtId}'";
                oxDb::getDb()->Execute($sQuery);
            }
        }

        return $aResponse;
    }
    
    /**
     * Adds RatePay specific parameters
     * 
     * @param type $oOrder
     * @return void
     */
    protected function _fcpoAddCaptureRatePayParams($oOrder) {
        $sPaymentId = $oOrder->oxorder__oxpaymenttype->value;
        if (in_array($sPaymentId, $this->_aRatePayPayents)) {
            $this->addParameter('add_paydata[shop_id]', $oOrder->oxorder__fcpoprofileid->value);
        }
    }

    /**
     * Send request to PAYONE Server-API with request-type "debit"
     *
     * @param object $oOrder order object
     * @param double $dAmount capture amount
     * @param string $sBankCountry ISO2 of the country of the bank. Default is false
     * @param string $sBankAccount bank account number. Default is false
     * @param string $sBankCode bank code. Default is false
     * @param string $sBankaccountholder bank account holder. Default is false
     * 
     * @return array
     */
    public function sendRequestDebit($oOrder, $dAmount, $sBankCountry = false, $sBankAccount = false, $sBankCode = '', $sBankaccountholder = '', $aPositions = false) {
        $this->addParameter('request', 'debit'); //Request method
        $sMode = $oOrder->oxorder__fcpomode->value;
        if ($sMode == '') {
            $sMode = $this->getOperationMode($oOrder->oxorder__oxpaymenttype->value);
        }
        $this->addParameter('mode', $sMode); //PayOne Portal Operation Mode (live or test)

        $this->addParameter('txid', $oOrder->oxorder__fcpotxid->value); //PayOne Transaction ID
        $this->addParameter('sequencenumber', $oOrder->getSequenceNumber());
        $this->addParameter('amount', number_format($dAmount, 2, '.', '') * 100); //Total order sum in smallest currency unit
        $this->addParameter('currency', $oOrder->oxorder__oxcurrency->value); //Currency

        $this->addParameter('transactiontype', 'GT');

        if ($sBankAccount !== false && $sBankCountry !== false) {
            $this->addParameter('bankcountry', $sBankCountry);
            $this->addParameter('bankaccount', $sBankAccount);
            $this->addParameter('bankcode', $sBankCode);
            $this->addParameter('bankaccountholder', $sBankaccountholder);
        }

        // Bedingung $amount == $oOrder->oxorder__oxorder__oxtotalordersum->value nur solange wie Artikelliste nicht f?r Multi-Capture m?glich
        if ($oOrder->isDetailedProductInfoNeeded()) {
            $dAmount = $this->addProductInfo($oOrder, $aPositions, true);
            // amount for credit entry has to be negative
            $dAmount = (double) $dAmount * -1;
            if ($aPositions !== false) {
                //partial-amount
                $this->addParameter('amount', number_format($dAmount, 2, '.', '') * 100); //Total order sum in smallest currency unit
            }
        }

        $aResponse = $this->send();

        if ($aPositions && $aResponse && array_key_exists('status', $aResponse) !== false && $aResponse['status'] == 'APPROVED') {
            foreach ($aPositions as $sOrderArtId => $aPos) {
                switch ($sOrderArtId) {
                    case 'oxdelcost':
                        $sQuery = "UPDATE oxorder SET fcpodelcostdebited = 1 WHERE oxid = '{$oOrder->getId()}'";
                        break;
                    case 'oxpaycost':
                        $sQuery = "UPDATE oxorder SET fcpopaycostdebited = 1 WHERE oxid = '{$oOrder->getId()}'";
                        break;
                    case 'oxwrapcost':
                        $sQuery = "UPDATE oxorder SET fcpowrapcostdebited = 1 WHERE oxid = '{$oOrder->getId()}'";
                        break;
                    case 'oxgiftcardcost':
                        $sQuery = "UPDATE oxorder SET fcpogiftcardcostdebited = 1 WHERE oxid = '{$oOrder->getId()}'";
                        break;
                    case 'oxvoucherdiscount':
                        $sQuery = "UPDATE oxorder SET fcpovoucherdiscountdebited = 1 WHERE oxid = '{$oOrder->getId()}'";
                        break;
                    case 'oxdiscount':
                        $sQuery = "UPDATE oxorder SET fcpodiscountdebited = 1 WHERE oxid = '{$oOrder->getId()}'";
                        break;
                    default:
                        $sQuery = "UPDATE oxorderarticles SET fcpodebitedamount = fcpodebitedamount + {$aPos['amount']} WHERE oxid = '{$sOrderArtId}'";
                        break;
                }
                oxDb::getDb()->Execute($sQuery);
            }
        }

        return $aResponse;
    }

    protected function _stateNeeded($sIso2Country) {
        if (array_search($sIso2Country, $this->_aStateNeededCountries) !== false) {
            return true;
        }
        return false;
    }

    /**
     * Add address parameters by delivery address object
     *
     * @param object $oAddress delivery address object
     * 
     * @return null
     */
    protected function addAddressParamsByAddress($oAddress) {
        $oCountry = oxNew('oxcountry');
        $oCountry->load($oAddress->oxaddress__oxcountryid->value);

        $this->addParameter('firstname', $oAddress->oxaddress__oxfname->value);
        $this->addParameter('lastname', $oAddress->oxaddress__oxlname->value);

        if ($oAddress->oxaddress__oxcompany->value != '') {
            $this->addParameter('company', $oAddress->oxaddress__oxcompany->value);
        }
        $this->addParameter('street', trim($oAddress->oxaddress__oxstreet->value . ' ' . $oAddress->oxaddress__oxstreetnr->value));
        $this->addParameter('zip', $oAddress->oxaddress__oxzip->value);
        $this->addParameter('city', $oAddress->oxaddress__oxcity->value);
        $this->addParameter('country', $oCountry->oxcountry__oxisoalpha2->value);
        if ($this->_stateNeeded($oCountry->oxcountry__oxisoalpha2->value)) {
            $this->addParameter('state', $this->_getShortState($oAddress->oxaddress__oxstateid->value));
        }

        if ($oAddress->oxaddress__oxfon->value != '') {
            $this->addParameter('telephonenumber', $oAddress->oxaddress__oxfon->value);
        }
    }

    protected function _getShortState($sStateId) {
        if ($this->_oFcpoHelper->fcpoGetIntShopVersion() >= 4800) {
            $oDb = oxDb::getDb();
            $sQuery = "SELECT OXISOALPHA2 FROM oxstates WHERE oxid = " . $oDb->quote($sStateId) . " LIMIT 1";
            $sStateId = $oDb->GetOne($sQuery);
        }
        return $sStateId;
    }

    /**
     * Add address parameters by user object
     *
     * @param object $oUser user object
     * 
     * @return null
     */
    protected function addAddressParamsByUser($oUser) {
        $oCountry = oxNew('oxcountry');
        $oCountry->load($oUser->oxuser__oxcountryid->value);

        $this->addParameter('firstname', $oUser->oxuser__oxfname->value);
        $this->addParameter('lastname', $oUser->oxuser__oxlname->value);

        if ($oUser->oxuser__oxcompany->value != '') {
            $this->addParameter('company', $oUser->oxuser__oxcompany->value);
        }
        $this->addParameter('street', trim($oUser->oxuser__oxstreet->value . ' ' . $oUser->oxuser__oxstreetnr->value));
        $this->addParameter('zip', $oUser->oxuser__oxzip->value);
        $this->addParameter('city', $oUser->oxuser__oxcity->value);
        $this->addParameter('country', $oCountry->oxcountry__oxisoalpha2->value);
        if ($this->_stateNeeded($oCountry->oxcountry__oxisoalpha2->value)) {
            $this->addParameter('state', $this->_getShortState($oUser->oxuser__oxstateid->value));
        }

        if ($oUser->oxuser__oxfon->value != '') {
            $this->addParameter('telephonenumber', $oUser->oxuser__oxfon->value);
        }
    }

    /**
     * Get ISO2 country code by given country ID
     *
     * @param string $sCountryId country ID
     * 
     * @return string
     */
    protected function getCountryIso2($sCountryId) {
        $oCountry = oxNew('oxcountry');
        $oCountry->load($sCountryId);
        return $oCountry->oxcountry__oxisoalpha2->value;
    }

    /**
     * This is the wrapper for address checks that has been called from the admin
     *
     * @param $oUser
     * @param bool $blCheckDeliveryAddress
     * @return mixed
     */
    public function sendRequestAddresscheck($oUser, $blCheckDeliveryAddress = false) {
        $mReturn = $this->sendStandardRequestAddresscheck($oUser, $blCheckDeliveryAddress);
        if(is_array($mReturn) && isset($mReturn['personstatus'])) {
            $this->setPayoneMalus($oUser, $mReturn);
        }
        return $mReturn;
    }


    /**
     * Method sets malus depending on addresscheck
     *
     * @param $oUser
     * @param $aResponse
     * @return void
     */
    public function setPayoneMalus($oUser, $aResponse) {
        if(isset($aResponse['personstatus']) && is_object($oUser)) {
            $oConfig = $this->getConfig();
            $iNewMalus = $oConfig->getConfigParam('sFCPOMalus'.strtoupper($aResponse['personstatus']));
            if($iNewMalus !== null) {// null comes if personstatus is unkown
                $iOldMalus = (int)$oUser->oxuser__fcpocurrmalus->value;

                //realboni field is used to keep track of the "real" boni, since this calculation cuts of the boni at 0
                //otherwise the customer could gain boni through this
                $iOldBoni = $oUser->oxuser__fcporealboni->value;
                if($iOldBoni === null) {// real boni not yet calculated
                    $iOldBoni = (int)$oUser->oxuser__oxboni->value;
                }

                $oUser->oxuser__fcpocurrmalus->value = (int)$iNewMalus;

                $iNewBoni = $iOldBoni + $iOldMalus - (int)$iNewMalus;
                $oUser->oxuser__fcporealboni->value = $iNewBoni;

                if($iNewBoni < 0) {
                    $iNewBoni = 0;
                }
                $oUser->oxuser__oxboni->value = (int)$iNewBoni;
                $oUser->save();

                // setting it somehow is not saved, so save it this way
                $sQuery = "UPDATE oxuser SET oxboni = '{$iNewBoni}' WHERE oxid = '{$oUser->getId()}'";
                oxDb::getDb()->Execute($sQuery);
            }
        }
    }


    /**
     * Send request to PAYONE Server-API with request-type "addresscheck"
     * Returns array of the response if the address was checked
     * OR
     * Return true if address-check was skipped because the address has been checked before
     *
     * @param object $oUser user object
     * @param bool $blCheckDeliveryAddress check delivery address? Default is false
     * 
     * @return array
     */
    public function sendStandardRequestAddresscheck($oUser, $blCheckDeliveryAddress = false) {
        $oConfig = $this->getConfig();
        $this->addParameter('request', 'addresscheck');
        $this->addParameter('mode', $oConfig->getConfigParam('sFCPOBoniOpMode')); //Operationmode live or test
        $this->addParameter('aid', $oConfig->getConfigParam('sFCPOSubAccountID')); //ID of PayOne Sub-Account
        $sAddresschecktype = $this->_fcpoGetAddressCheckType();
        $this->addParameter('addresschecktype', $sAddresschecktype);

        if ($sAddresschecktype == 'PE' && $this->getCountryIso2($oUser->oxuser__oxcountryid->value) != 'DE') {
            //AddressCheck Person nur in Deutschland
            //Erfolgreichen Check simulieren
            return array('fcWrongCountry' => true);
        } elseif ($sAddresschecktype == 'BA' && array_search($this->getCountryIso2($oUser->oxuser__oxcountryid->value), $this->_aValidCountrys) === false) {
            //AddressCheck Basic nur in bestimmten L?ndern
            //Erfolgreichen Check simulieren
            return array('fcWrongCountry' => true);
        } else {
            $oAddress = oxNew('oxaddress');
            if ($blCheckDeliveryAddress === true) {
                $sDeliveryAddressId = $oUser->getSelectedAddressId();
                if ($sDeliveryAddressId) {
                    $oAddress->load($sDeliveryAddressId);
                } else {
                    return false;
                }
                $this->addAddressParamsByAddress($oAddress);
            } else {
                $this->addAddressParamsByUser($oUser);
            }

            $this->addParameter('language', $this->_oFcpoHelper->fcpoGetLang()->getLanguageAbbr());

            if ($this->_wasAddressCheckedBefore() === false) {
                $aResponse = $this->send();

                if ($this->_fcpoCheckAddressCanBeSaved($aResponse)) {
                    $this->_saveCheckedAddress($aResponse);
                }

                return $aResponse;
            }
            return true;
        }
    }

    /**
     * Parses response and set fallback if conditions match
     *
     * @param $aResponse
     * @return array
     */
    protected function _fcpoCheckUseFallbackBoniversum($aResponse) {
        $oConfig = $this->getConfig();
        $sScore = $aResponse['score'];
        $sAddresscheckType = $this->_fcpoGetAddressCheckType();

        $blUseFallBack = (
            $sScore == 'U' &&
            in_array($sAddresscheckType, array('BB', 'PB'))
        );

        if ($blUseFallBack) {
            $sFCPOBoniversumFallback = $oConfig->getConfigParam('sFCPOBoniversumFallback');
            $aResponse['score'] = $sFCPOBoniversumFallback;
            if ($sFCPOBoniversumFallback == 'R' && $aResponse['status'] == 'VALID') {
                $aResponse['status'] = 'ERROR';
            }
        }

        return $aResponse;
    }

    /**
     * Check, correct and return addresschecktype
     *
     */
    protected function _fcpoGetAddressCheckType() {
        $oConfig = $this->getConfig();
        $sBoniCheckType = $oConfig->getConfigParam('sFCPOBonicheck');
        $sAddressCheckType = $oConfig->getConfigParam('sFCPOAddresscheck');

        if ($sBoniCheckType == 'CE') {
            $sAddressCheckType = 'PB';
        }

        return $sAddressCheckType;
    }

    /**
     * Method checks if current address can be saved after call for address check
     *
     * @param $aResponse
     * @return bool
     */
    protected function _fcpoCheckAddressCanBeSaved($aResponse) {
        $blReturn = (
            $aResponse['status'] == 'VALID' &&
            $this->_fcpoNotBlockingPersonstatus($aResponse)
        );

        return $blReturn;
    }

    /**
     * Method checks if personstatus and settings block saving former addresschecks
     *
     * @param $aResponse
     * @return bool
     */
    protected function _fcpoNotBlockingPersonstatus($aResponse) {
        $oConfig = $this->getConfig();
        $sFCPOAddresscheck = $oConfig->getConfigParam('sFCPOAddresscheck');
        $sResponsePersonstatus = $aResponse['personstatus'];

        $aBlockingPersonStatus = array();
        $aPersonStatusToCheck = array('PPF', 'UKN', 'PUG', 'PNZ', 'PNP');

        foreach ($aPersonStatusToCheck as $sPersonstatusToCheck) {
            $blBlocking = $oConfig->getConfigParam('blFCPOAddCheck' . $sPersonstatusToCheck);
            if ($blBlocking) {
                $aBlockingPersonStatus[] = $sPersonstatusToCheck;
            }
        }

        $blReturn = true;
        if ($sFCPOAddresscheck == 'PE') {
            $blReturn = (
                !in_array($sResponsePersonstatus, $aBlockingPersonStatus)
            );
        }

        return $blReturn;
    }

    /**
     * Create a unique hash of the valid address
     * 
     * @param array $aResponse response from the address-check request
     * @return string
     */
    protected function _getAddressHash($aResponse = false) {
        $sHash = false;

        $aAddressParameters = array(
            'firstname',
            'lastname',
            'company',
            'street',
            'streetname',
            'streetnumber',
            'zip',
            'city',
            'country',
            'state',
        );

        $sAddress = '';
        foreach ($aAddressParameters as $sParamKey) {
            $sParamValue = $this->getParameter($sParamKey);
            if ($sParamValue) {
                $blCorrectAddressParam = $this->_fcpoCorrectAddressParam($sParamKey, $sParamValue, $aResponse);
                if ($blCorrectAddressParam) {
                    //take the corrected value from the address-check
                    $sParamValue = $aResponse[$sParamKey];
                }
                $sAddress .= $sParamValue;
            }
        }
        $sHash = md5($sAddress);

        return $sHash;
    }

    /**
     * Check response against current addressdata
     *
     * @param $sParamKey
     * @param $sParamValue
     * @param $aResponse
     * @return bool
     */
    protected function _fcpoCorrectAddressParam($sParamKey, $sParamValue, $aResponse) {
        $blCorrectAddressParam = (
            $aResponse !== false &&
            array_key_exists($sParamKey, $aResponse) !== false &&
            $aResponse[$sParamKey] != $sParamValue
        );

        return $blCorrectAddressParam;
    }

    /**
     * Check and return if this exact address has been checked before
     * 
     * @return bool 
     */
    protected function _wasAddressCheckedBefore() {
        $sCheckHash = $this->_getAddressHash();
        $sQuery = "SELECT fcpo_checkdate FROM fcpocheckedaddresses WHERE fcpo_address_hash = '{$sCheckHash}'";
        $sDate = oxDb::getDb()->GetOne($sQuery);
        if ($sDate != false) {
            return true;
        }
        return false;
    }

    /**
     * Save the hash of a concatenated string with all address information to the DB table fcpocheckedaddresses
     * 
     * @param array $aResponse response from the address-check request
     */
    protected function _saveCheckedAddress($aResponse) {
        $sCheckHash = $this->_getAddressHash($aResponse);
        $sQuery = "REPLACE INTO fcpocheckedaddresses ( fcpo_address_hash ) VALUES ( '{$sCheckHash}' )";
        oxDb::getDb()->Execute($sQuery);
    }

    /** Send request to PAYONE Server-API with request-type "consumerscore"
     *
     * @param object $oUser user object
     *
     * @return array;
     */
    public function sendRequestConsumerscore($oUser) {
        // Consumerscore only allowed in germany
        if ($this->getCountryIso2($oUser->oxuser__oxcountryid->value) == 'DE') {
            $oConfig = $this->getConfig();
            $this->addParameter('request', 'consumerscore');
            $this->addParameter('mode', $oConfig->getConfigParam('sFCPOBoniOpMode')); //Operationmode live or test
            $this->addParameter('aid', $oConfig->getConfigParam('sFCPOSubAccountID')); //ID of PayOne Sub-Account

            $this->addParameter('addresschecktype', $oConfig->getConfigParam('sFCPOAddresscheck'));
            $this->addParameter('consumerscoretype', $oConfig->getConfigParam('sFCPOBonicheck'));

            $this->addAddressParamsByUser($oUser);

            if ($oUser->oxuser__oxbirthdate != '0000-00-00' && $oUser->oxuser__oxbirthdate != '') {
                $this->addParameter('birthday', str_ireplace('-', '', $oUser->oxuser__oxbirthdate->value));
            }

            $this->addParameter('language', $this->_oFcpoHelper->fcpoGetLang()->getLanguageAbbr());

            $aResponse = $this->send();
            $aResponse = $this->_fcpoCheckUseFallbackBoniversum($aResponse);

            return $aResponse;
        } else {
            // Ampel Gruen Response simulieren
            $aResponse = array('scorevalue' => 500, 'fcWrongCountry' => true);
            return $aResponse;
        }
    }

    /**
     * Checks available methods for contacting request target and triggers request with found method
     * 
     * @param type $aUrlArray
     * @return array $aResponse
     */
    protected function _getResponseForParsedRequest($aUrlArray) {
        $aResponse = array();

        if (function_exists("curl_init")) {
            // php native curl exists so we gonna use it for requesting
            $aResponse = $this->_getCurlPhpResponse($aUrlArray);
        } else if (file_exists("/usr/local/bin/curl") || file_exists("/usr/bin/curl")) {
            // cli version of curl exists on server
            $sCurlPath = ( file_exists("/usr/local/bin/curl") ) ? "/usr/local/bin/curl" : "/usr/bin/curl";
            $aResponse = $this->_getCurlCliResponse($aUrlArray, $sCurlPath);
        } else {
            // last resort => via sockets
            $aResponse = $this->_getSocketResponse($aUrlArray);
        }

        return $aResponse;
    }

    /**
     * Tries to fetch a response via network socket
     * 
     * @param type $aUrlArray
     * @return array $aResponse
     */
    protected function _getSocketResponse($aUrlArray) {
        $aResponse = array();

        switch ($aUrlArray['scheme']) {
            case 'https':
                $sScheme = 'ssl://';
                $iPort = 443;
                break;
            case 'http':
            default:
                $sScheme = '';
                $iPort = 80;
        }

        $oFsockOpen = fsockopen($sScheme . $aUrlArray['host'], $iPort, $iErrorNumber, $sErrorString, 45);
        if (!$oFsockOpen) {
            $aResponse[] = "errormessage=fsockopen:Failed opening http socket connection: " . $sErrorString . " (" . $iErrorNumber . ")";
        } else {
            $sRequestHeader = "POST " . $aUrlArray['path'] . " HTTP/1.1\r\n";
            $sRequestHeader .= "Host: " . $aUrlArray['host'] . "\r\n";
            $sRequestHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $sRequestHeader .= "Content-Length: " . strlen($aUrlArray['query']) . "\r\n";
            $sRequestHeader .= "Connection: close\r\n\r\n";
            $sRequestHeader .= $aUrlArray['query'];

            fwrite($oFsockOpen, $sRequestHeader);

            $sResponseHeader = "";
            do {
                $sResponseHeader .= fread($oFsockOpen, 1);
            } while (!preg_match("/\\r\\n\\r\\n$/", $sResponseHeader) && !feof($oFsockOpen));

            while (!feof($oFsockOpen)) {
                $aResponse[] = fgets($oFsockOpen, 1024);
            }
            if (count($aResponse) == 0) {
                $aResponse[] = 'connection-type: 3 - ' . $sResponseHeader;
            }
        }

        return $aResponse;
    }

    /**
     * Using installed CLI version of curl by building the command
     * 
     * @param array $aUrlArray
     * @param string $sCurlPath
     * @return array
     */
    protected function _getCurlCliResponse($aUrlArray, $sCurlPath) {
        $aResponse = array();

        $sPostUrl = $aUrlArray['scheme'] . "://" . $aUrlArray['host'] . $aUrlArray['path'];
        $sPostData = $aUrlArray['query'];

        $sCommand = $sCurlPath . " -m 45 -k -d \"" . $sPostData . "\" " . $sPostUrl;
        $iSysOut = -1;
        $sTemp = exec($sCommand, $aResponse, $iSysOut);
        if ($iSysOut != 0) {
            $aResponse[] = "connection-type: 2 - errormessage=curl error(" . $iSysOut . ")";
        }

        return $aResponse;
    }

    /**
     * Using native php curl to perform request
     * 
     * @param type $aUrlArray
     * @return array $aResponse
     */
    protected function _getCurlPhpResponse($aUrlArray) {
        $aResponse = array();

        $oCurl = curl_init($aUrlArray['scheme'] . "://" . $aUrlArray['host'] . $aUrlArray['path']);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $aUrlArray['query']);

        $sCertificateFilePath = getShopBasePath() . 'modules/fcPayOne/cacert.pem';
        if (file_exists($sCertificateFilePath) !== false) {
            curl_setopt($oCurl, CURLOPT_CAINFO, $sCertificateFilePath);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, true);  // force SSL certificate check
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2);  // check hostname in SSL certificate
        } else {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        }

        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 45);

        $result = curl_exec($oCurl);
        if (curl_error($oCurl)) {
            $aResponse[] = "connection-type: 1 - errormessage=" . curl_errno($oCurl) . ": " . curl_error($oCurl);
        } else {
            $aResponse = explode("\n", $result);
        }
        curl_close($oCurl);

        return $aResponse;
    }

    /**
     * Send the previously prepared request, log request and response into the database and return the response
     *
     * @return array;
     */
    protected function send($blOnlyGetUrl = false) {
        ksort($this->_aParameters);

        $iErrorNumber = '';
        $sErrorString = '';

        if ($this->getParameter('mid') === false || $this->getParameter('portalid') === false ||
                $this->getParameter('key') === false || $this->getParameter('mode') === false) {
            $aOutput['errormessage'] = "Payone API Setup Data not complete (API-URL, MID, AID, PortalID, Key, Mode)";
            return $aOutput;
        }

        foreach ($this->_aParameters as $sKey => $sValue) {
            if (is_array($sValue)) {
                foreach ($sValue as $i => $val1) {
                    $sRequestUrl .= "&" . $sKey . "[" . $i . "]=" . urlencode($val1);
                }
            } else {
                $sRequestUrl .= "&" . $sKey . "=" . urlencode($sValue);
            }
        }
        $sRequestUrl = $this->_sApiUrl . "?" . substr($sRequestUrl, 1);

        if ($blOnlyGetUrl === true) {
            return $sRequestUrl;
        }

        $aUrlArray = parse_url($sRequestUrl);

        $aResponse = $this->_getResponseForParsedRequest($aUrlArray);

        if (is_array($aResponse)) {
            $aOutput = $this->_getResponseOutput($aResponse);
            $aOutput = $this->_addMappedErrorIfAvailable($aOutput);
        }

        $sResponse = serialize($aOutput);
        $this->_logRequest($sResponse, $aOutput['status']);

        return $aOutput;
    }

    /**
     * Adds mapped error message to response if available
     * 
     * @param array $aInput
     * @return array
     */
    protected function _addMappedErrorIfAvailable($aInput) {
        $aOutput = $aInput;
        if ($aInput['status'] == 'ERROR') {
            $sErrorCode = $aInput['errorcode'];
            $oErrorMapping = oxNew('fcpoerrormapping');
            $sMappedErrorMessage = $oErrorMapping->fcpoFetchMappedErrorMessage($sErrorCode);
            if ($sMappedErrorMessage) {
                $aOutput['origincustomermessage'] = $aInput['customermessage'];
                $aOutput['customermessage'] = $sMappedErrorMessage;
            }
        }

        return $aOutput;
    }

    /**
     * Parses request respond and format it to needed form
     * 
     * @param array $aResponse
     * @return array
     */
    protected function _getResponseOutput($aResponse) {
        $aOutput = array();
        foreach ($aResponse as $iLinenum => $sLine) {
            $iPos = strpos($sLine, "=");
            if ($iPos > 0) {
                $aOutput[substr($sLine, 0, $iPos)] = trim(substr($sLine, $iPos + 1));
            } elseif (strlen($sLine) > 0) {
                $aOutput[$iLinenum] = $sLine;
            }
        }

        return $aOutput;
    }

    protected function _logRequest($sResponse, $sStatus = '') {
        $oConfig = $this->getConfig();
        $oDb = oxDb::getDb();
        $sRequest = serialize($this->_aParameters);
        $sQuery = " INSERT INTO fcporequestlog (
                        FCPO_REFNR, FCPO_REQUESTTYPE, FCPO_RESPONSESTATUS, FCPO_REQUEST, FCPO_RESPONSE, FCPO_PORTALID, FCPO_AID
                    ) VALUES (
                        '{$this->getParameter('reference')}', 
                        '{$this->getParameter('request')}', 
                        '{$sStatus}', 
                        " . $oDb->quote($sRequest) . ", 
                        " . $oDb->quote($sResponse) . ", 
                        '{$oConfig->getConfigParam('sFCPOPortalID')}', 
                        '{$oConfig->getConfigParam('sFCPOSubAccountID')}'
                    )";
        $oDb->Execute($sQuery);
    }

    /**
     * Check if the user has ordered with this address before
     * If yes, send the updateuser request to payone
     *
     * @param object $oOrder order object
     * @param object $oUser user object
     * 
     * @return null
     */
    protected function _checkAddress($oOrder, $oUser) {
        $oDb = oxDb::getDb();

        $sQuery = " SELECT 
                        oxid 
                    FROM 
                        oxorder 
                    WHERE 
                        oxuserid = " . $oDb->quote($oOrder->oxorder__oxuserid->value) . " AND 
                        oxbillstreet = " . $oDb->quote($oOrder->oxorder__oxbillstreet->value) . " AND 
                        oxbillstreetnr = " . $oDb->quote($oOrder->oxorder__oxbillstreetnr->value) . " AND  
                        oxbillzip = " . $oDb->quote($oOrder->oxorder__oxbillzip->value) . " AND
                        oxbillcity = " . $oDb->quote($oOrder->oxorder__oxbillcity->value) . " AND 
                        oxbillcountryid = " . $oDb->quote($oOrder->oxorder__oxbillcountryid->value) . "
                    ORDER BY 
                        oxorderdate DESC 
                    LIMIT 1";
        $sOrderId = $oDb->GetOne($sQuery);

        if (!$sOrderId) {
            $sPayOneUserId = $this->_getPayoneUserIdByCustNr($oUser->oxuser__oxcustnr->value);
            if ($sPayOneUserId) {
				/**
                 * @var self $oPORequest
                 */
                $oPORequest = oxNew('fcporequest');
                $oResponse = $oPORequest->sendRequestUpdateuser($oOrder, $oUser);
            }
        }
    }

    protected function _getPayoneUserIdByCustNr($sCustNr) {
        $sQuery = " SELECT 
                        fcpo_userid 
                    FROM 
                        fcpotransactionstatus 
                    WHERE 
                        fcpo_customerid = '{$sCustNr}' 
                    ORDER BY 
                        fcpo_timestamp DESC 
                    LIMIT 1";
        $sPayOneUserId = oxDb::getDb()->GetOne($sQuery);
        return $sPayOneUserId;
    }

    /**
     * Add the the user information parameters
     *
     * @param object $oOrder order object
     * @param object $oUser user object
     * @param bool $blIsUpdateUser is update user request? Default is false
     * 
     * @return null
     */
    protected function _addUserDataParameters($oOrder, $oUser, $blIsUpdateUser = false) {
        $oCountry = oxNew('oxcountry');
        $oCountry->load($oOrder->oxorder__oxbillcountryid->value);

        if ($blIsUpdateUser === false) {
			/**
             * TODO: check if that if condition is correctly as request updateuser doesn't have a customerid which is invalid, as said by Payone Technical Support
             */
            $this->addParameter('customerid', $oUser->oxuser__oxcustnr->value);
        }
        $this->addParameter('salutation', ($oOrder->oxorder__oxbillsal->value == 'MR' ? 'Herr' : 'Frau'), $blIsUpdateUser);
        $this->addParameter('gender', ($oOrder->oxorder__oxbillsal->value == 'MR' ? 'm' : 'f'), $blIsUpdateUser);
        $this->addParameter('firstname', $oOrder->oxorder__oxbillfname->value, $blIsUpdateUser);
        $this->addParameter('lastname', $oOrder->oxorder__oxbilllname->value, $blIsUpdateUser);
        if ($blIsUpdateUser || $oOrder->oxorder__oxbillcompany->value != '')
            $this->addParameter('company', $oOrder->oxorder__oxbillcompany->value, $blIsUpdateUser);
        $this->addParameter('street', trim($oOrder->oxorder__oxbillstreet->value . ' ' . $oOrder->oxorder__oxbillstreetnr->value), $blIsUpdateUser);
        if ($blIsUpdateUser || $oOrder->oxorder__oxbilladdinfo->value != '')
            $this->addParameter('addressaddition', $oOrder->oxorder__oxbilladdinfo->value, $blIsUpdateUser);
        $this->addParameter('zip', $oOrder->oxorder__oxbillzip->value, $blIsUpdateUser);
        $this->addParameter('city', $oOrder->oxorder__oxbillcity->value, $blIsUpdateUser);
        $this->addParameter('country', $oCountry->oxcountry__oxisoalpha2->value, $blIsUpdateUser);
        if ($this->_stateNeeded($oCountry->oxcountry__oxisoalpha2->value)) {
            $this->addParameter('state', $this->_getShortState($oOrder->oxorder__oxbillstateid->value));
        }
        $this->addParameter('email', $oOrder->oxorder__oxbillemail->value, $blIsUpdateUser);
        if ($blIsUpdateUser || $oOrder->oxorder__oxbillfon->value != '')
            $this->addParameter('telephonenumber', $oOrder->oxorder__oxbillfon->value, $blIsUpdateUser);

        if ((
                in_array($oOrder->oxorder__oxpaymenttype->value, array('fcpoklarna')) &&
                in_array($oCountry->oxcountry__oxisoalpha2->value, array('DE', 'NL', 'AT'))
                ) || ($blIsUpdateUser || ($oUser->oxuser__oxbirthdate != '0000-00-00' && $oUser->oxuser__oxbirthdate != ''))
        ) {
            $this->addParameter('birthday', str_ireplace('-', '', $oUser->oxuser__oxbirthdate->value), $blIsUpdateUser);
        }
        if (in_array($oOrder->oxorder__oxpaymenttype->value, array('fcpoklarna'))) {
            if ($blIsUpdateUser || $oUser->oxuser__fcpopersonalid->value != '')
                $this->addParameter('personalid', $oUser->oxuser__fcpopersonalid->value, $blIsUpdateUser);
        }
        $this->addParameter('language', $this->_oFcpoHelper->fcpoGetLang()->getLanguageAbbr(), $blIsUpdateUser);
        if ($blIsUpdateUser || $oOrder->oxorder__oxbillustid->value != '')
            $this->addParameter('vatid', $oOrder->oxorder__oxbillustid->value, $blIsUpdateUser);
    }

    /**
     * Send request to PAYONE Server-API with request-type "updateuser"
     *
     * @param object $oOrder order object
     * @param object $oUser user object
     * @param string $sPayOneUserId payone user-id for this user
     * 
     * @return array
     */
    public function sendRequestUpdateuser($oOrder, $oUser) {
        $this->addParameter('request', 'updateuser'); //Request method
        $this->addParameter('mode', $this->getOperationMode($oOrder->oxorder__oxpaymenttype->value)); //PayOne Portal Operation Mode (live or test)

        $this->_addUserDataParameters($oOrder, $oUser, true);
        return $this->send();
    }

    /**
     * Send request to PAYONE Server-API with request-type "managemandate"
     *
     * @param string $sMode operation-mode ( live/test )
     * @param array $aDynvalue payment form-data
     * @param object $oUser user object
     * 
     * @return array
     */
    public function sendRequestManagemandate($sMode, $aDynvalue, $oUser) {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();

        $this->addParameter('request', 'managemandate'); //Request method
        $this->addParameter('mode', $sMode); //PayOne Portal Operation Mode (live or test)
        $this->addParameter('aid', $oConfig->getConfigParam('sFCPOSubAccountID')); //ID of PayOne Sub-Account
        $this->addParameter('clearingtype', 'elv');

        $this->addParameter('customerid', $oUser->oxuser__oxcustnr->value);
        $sPayOneUserId = $this->_getPayoneUserIdByCustNr($oUser->oxuser__oxcustnr->value);
        if ($sPayOneUserId) {
            $this->addParameter('userid', $sPayOneUserId);
        }
        $this->addAddressParamsByUser($oUser);
        $this->addParameter('email', $oUser->oxuser__oxusername->value);
        $this->addParameter('language', $this->_oFcpoHelper->fcpoGetLang()->getLanguageAbbr());
        $this->addParameter('bankcountry', $aDynvalue['fcpo_elv_country']);
        if (isset($aDynvalue['fcpo_elv_iban']) && $aDynvalue['fcpo_elv_iban'] != '' && isset($aDynvalue['fcpo_elv_bic']) && $aDynvalue['fcpo_elv_bic'] != '') {
            $this->addParameter('iban', $aDynvalue['fcpo_elv_iban']);
            $this->addParameter('bic', $aDynvalue['fcpo_elv_bic']);
        } elseif (isset($aDynvalue['fcpo_elv_ktonr']) && $aDynvalue['fcpo_elv_ktonr'] != '' && isset($aDynvalue['fcpo_elv_blz']) && $aDynvalue['fcpo_elv_blz'] != '') {
            $this->addParameter('bankaccount', $aDynvalue['fcpo_elv_ktonr']);
            $this->addParameter('bankcode', $aDynvalue['fcpo_elv_blz']);
        }

        $oCur = $oConfig->getActShopCurrencyObject();
        $this->addParameter('currency', $oCur->name);

        $aResponse = $this->send();
        if (is_array($aResponse)) {
            $aResponse['mode'] = $sMode;
        }

        return $aResponse;
    }

    /**
     * Send request to PAYONE Server-API with request-type "getfile"
     *
     * @param string $sOrderId oxid order id
     * @param string $sMandateIdentification payone mandate identification
     * @param string $sMode operation-mode ( live/test )
     * 
     * @return string
     */
    public function sendRequestGetFile($sOrderId, $sMandateIdentification, $sMode) {
        $sReturn = false;
        $sStatus = 'ERROR';
        $sResponse = '';
        $oDb = oxDb::getDb();

        $this->addParameter('request', 'getfile'); //Request method
        $this->addParameter('file_reference', $sMandateIdentification);
        $this->addParameter('file_type', 'SEPA_MANDATE');
        $this->addParameter('file_format', 'PDF');

        $this->addParameter('mode', $sMode);
        if ($sMode == 'test') {
            $this->removeParameter('integrator_name');
            $this->removeParameter('integrator_version');
            $this->removeParameter('solution_name');
            $this->removeParameter('solution_version');
        }

        $sPath = 'modules/fcPayOne/mandates/' . $sMandateIdentification . '.pdf';
        $sDestinationFile = getShopBasePath() . $sPath;

        $aOptions = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($this->_aParameters),
            ),
        );
        $oContext = stream_context_create($aOptions);
        $oContent = file_get_contents($this->_sApiUrl, false, $oContext);
        if ($oContent !== false) {
            file_put_contents($sDestinationFile, $oContent);

            if (file_exists($sDestinationFile)) {
                $sExists = $oDb->GetOne("SELECT oxorderid FROM fcpopdfmandates WHERE oxorderid = " . $oDb->quote($sOrderId) . " LIMIT 1");
                if (!$sExists) {
                    $sQuery = "INSERT INTO fcpopdfmandates VALUES (" . $oDb->quote($sOrderId) . ", " . $oDb->quote(basename($sDestinationFile)) . ")";
                    $oDb->Execute($sQuery);
                }

                $sReturn = $this->getConfig()->getShopUrl() . "modules/fcPayOne/download.php?id=" . $sOrderId;
                $sStatus = 'SUCCESS';

                $aOutput = array(
                    'file' => $sDestinationFile,
                );
                $sResponse = serialize($aOutput);
            }
        }
        $this->_logRequest($sResponse, $sStatus);

        return $sReturn;
    }

    /**
     * Get the next reference number for the upcoming PAYONE transaction
     * 
     * @param object $oOrder order object
     * 
     * @return string
     */
    public function getRefNr($oOrder = false) {
        $oDb = oxDb::getDb();
        $sRawPrefix = (string) $this->getConfig()->getConfigParam('sFCPORefPrefix');
        $sPrefix = $oDb->quote($sRawPrefix);

        if ($oOrder && !empty($oOrder->oxorder__oxordernr->value)) {
            $sRefNr = $oOrder->oxorder__oxordernr->value;
        } else {
            $sQuery = "SELECT MAX(fcpo_refnr) FROM fcporefnr WHERE fcpo_refprefix = {$sPrefix}";
            $iMaxRefNr = $oDb->GetOne($sQuery);
            $sRefNr = (int) $iMaxRefNr + 1;
            $sQuery = "INSERT INTO fcporefnr (fcpo_refnr, fcpo_txid, fcpo_refprefix)  VALUES ('{$sRefNr}', '', {$sPrefix})";

            $oDb->Execute($sQuery);
        }

        return $sRawPrefix . $sRefNr;
    }

}
