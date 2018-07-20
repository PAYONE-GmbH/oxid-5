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
 * @version   OXID eShop CE
 */
class fcPayOneViewConf extends fcPayOneViewConf_parent {

    /**
     * Name of the module folder
     * @var string
     */
    protected $_sModuleFolder = "fcPayOne";

    /**
     * Helper object for dealing with different shop versions
     * @var object
     */
    protected $_oFcpoHelper = null;

    /**
     * Hosted creditcard js url
     * 
     * @var string
     */
    protected $_sFcPoHostedJsUrl = 'https://secure.pay1.de/client-api/js/v1/payone_hosted_min.js';

    /**
     * List of handled themes
     * @var array
     */
    protected $_aSupportedThemes = array(
        'flow' => 'flow',
        'azure' => 'azure',
        'mobile' => 'mobile',
    );

    /**
     * Counts the amount of widgets have been included by call
     * @var int
     */
    protected $_iAmzWidgetIncludeCounter = 0;

    /**
     * Determines the source of a button include
     * @var string|null
     */
    protected $_sCurrentAmazonButtonId = null;

    /**
     * Determines the source of a button include
     * @var string|null
     */
    protected $_sCurrentMasterpassButtonId = null;

    /**
     * Initializing needed things
     */
    public function __construct() {
        parent::__construct();
        $this->_oFcpoHelper = oxNew('fcpohelper');
        $this->_iAmzWidgetIncludeCounter = 0;
    }

    /**
     * Returns the path to module
     * 
     * @param void
     * @return string
     */
    public function fcpoGetModulePath() {
        return $this->getModulePath($this->_sModuleFolder);
    }

    /**
     * Returns the url to module
     * 
     * @param void
     * @return string
     */
    public function fcpoGetModuleUrl() {
        return $this->getModuleUrl($this->_sModuleFolder);
    }

    /**
     * Returns url to module img folder (admin)
     * 
     * @param void
     * @return string
     */
    public function fcpoGetAdminModuleImgUrl() {
        $sModuleUrl = $this->fcpoGetModuleUrl();
        $sModuleAdminImgUrl = $sModuleUrl . 'out/admin/img/';

        return $sModuleAdminImgUrl;
    }

    /**
     * Returns url to module img folder
     *
     * @param void
     * @return string
     */
    public function fcpoGetModuleImgUrl() {
        $sModuleUrl = $this->fcpoGetModuleUrl();
        $sModuleImgUrl = $sModuleUrl . 'out/img/';

        return $sModuleImgUrl;
    }

    /**
     * Returns the path to javascripts of module
     * 
     * @param string $sFile
     * @return string
     */
    public function fcpoGetAbsModuleJsPath($sFile = "") {
        $sModulePath = $this->fcpoGetModulePath();
        $sModuleJsPath = $sModulePath . 'out/src/js/';
        if ($sFile) {
            $sModuleJsPath = $sModuleJsPath . $sFile;
        }

        return $sModuleJsPath;
    }

    /**
     * Returns the path to javascripts of module
     * 
     * @param string $sFile
     * @return string
     */
    public function fcpoGetModuleJsPath($sFile = "") {
        $sModuleUrl = $this->fcpoGetModuleUrl();
        $sModuleJsUrl = $sModuleUrl . 'out/src/js/';
        if ($sFile) {
            $sModuleJsUrl = $sModuleJsUrl . $sFile;
        }

        return $sModuleJsUrl;
    }

    /**
     * Returns integer of shop version
     * 
     * @param void
     * @return string
     */
    public function fcpoGetIntShopVersion() {
        return $this->_oFcpoHelper->fcpoGetIntShopVersion();
    }

    /**
     * Returns the path to javascripts of module
     * 
     * @param string $sFile
     * @return string
     */
    public function fcpoGetModuleCssPath($sFile = "") {
        $sModuleUrl = $this->fcpoGetModuleUrl();
        $sModuleUrl = $sModuleUrl . 'out/src/css/';
        if ($sFile) {
            $sModuleUrl = $sModuleUrl . $sFile;
        }

        return $sModuleUrl;
    }

    /**
     * Returns the path to javascripts of module
     * 
     * @param string $sFile
     * @return string
     */
    public function fcpoGetAbsModuleTemplateFrontendPath($sFile = "") {
        $sModulePath = $this->fcpoGetModulePath();
        $sModulePath = $sModulePath . 'application/views/frontend/tpl/';
        if ($sFile) {
            $sModulePath = $sModulePath . $sFile;
        }

        return $sModulePath;
    }

    /**
     * Returns hosted js url
     * 
     * @return string
     */
    public function fcpoGetHostedPayoneJs() {
        return $this->_sFcPoHostedJsUrl;
    }
    
    /**
     * Returns Iframe mappings
     * 
     * @param void
     * @return array
     */
    public function fcpoGetIframeMappings() {
        $oErrorMapping = $this->_oFcpoHelper->getFactoryObject('fcpoerrormapping');
        $aExistingErrorMappings = $oErrorMapping->fcpoGetExistingMappings('iframe');

        return $aExistingErrorMappings;
    }
    
    /**
     * Returns abbroviation by given id
     * 
     * @param string $sLangId
     * @return string
     */
    public function fcpoGetLangAbbrById($sLangId) {
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        return $oLang->getLanguageAbbr($sLangId);
    }

    /**
     * Returns if a complete set of salutations is available
     *
     * @param void
     * @return bool
     */
    public function fcpoUserHasSalutation() {
        $oSession = $this->_oFcpoHelper->fcpoGetSession();
        $oBasket = $oSession->getBasket();
        $oUser = $oBasket->getBasketUser();
        $oAddress = $oUser->getSelectedAddress();
        $sSalutation = $oUser->oxuser__oxsal->value;
        $sSalutationDelAddress = $oAddress->oxaddress__oxsal->value;

        $blHasSalutation = (
            $sSalutation &&
            $sSalutationDelAddress
        );

        return $blHasSalutation;
    }

    /**
     * Returns if amazonpay is active and though button can be displayed
     *
     * @param void
     * @return bool
     */
    public function fcpoCanDisplayAmazonPayButton() {
        $oPayment = $this->_oFcpoHelper->getFactoryObject('oxpayment');
        $oPayment->load('fcpoamazonpay');
        $blIsActive = (bool) $oPayment->oxpayments__oxactive->value;

        return $blIsActive;
    }


    /**
     * Returns amazon widgets url depending if mode is live or test
     */
    public function fcpoGetAmazonWidgetsUrl() {
        $oPayment = $this->_oFcpoHelper->getFactoryObject('oxpayment');
        $oPayment->load('fcpoamazonpay');
        $blIsLive = $oPayment->oxpayments__fcpolivemode->value;

        $sAmazonWidgetsUrl = 'https://static-eu.payments-amazon.com/OffAmazonPayments/eur/sandbox/lpa/js/Widgets.js';
        if ($blIsLive) {
            $sAmazonWidgetsUrl = 'https://static-eu.payments-amazon.com/OffAmazonPayments/eur/lpa/js/Widgets.js';
        }

        return $sAmazonWidgetsUrl;
    }

    /**
     * Returns amazon client id
     *
     * @return string
     */
    public function fcpoGetAmazonPayClientId() {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sClientId = $oConfig->getConfigParam('sFCPOAmazonPayClientId');

        return (string)$sClientId;
    }

    /**
     * Returns amazon seller id
     *
     * @return string
     */
    public function fcpoGetAmazonPaySellerId() {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sSellerId = $oConfig->getConfigParam('sFCPOAmazonPaySellerId');

        return (string)$sSellerId;
    }

    /**
     * Method returns previously saved reference id
     *
     * @param void
     * @return mixed
     */
    public function fcpoGetAmazonPayReferenceId() {
        $sAmazonReferenceId = $this->_oFcpoHelper->fcpoGetSessionVariable('fcpoAmazonReferenceId');

        return $sAmazonReferenceId;
    }

    /**
     * Returns config value for button type
     *
     * @param void
     * @return string
     */
    public function fcpoGetAmazonPayButtonType() {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sValue = $oConfig->getConfigParam('sFCPOAmazonButtonType');

        return (string)$sValue;
    }

    /**
     * Returns config value for button color
     *
     * @param void
     * @return string
     */
    public function fcpoGetAmazonPayButtonColor() {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sValue = $oConfig->getConfigParam('sFCPOAmazonButtonColor');

        return (string)$sValue;
    }

    /**
     * Returns if address widget should be displayed readonly
     *
     * @param void
     * @return bool
     */
    public function fcpoGetAmazonPayAddressWidgetIsReadOnly() {
        $blAmazonPayAddressWidgetLocked =
            (bool) $this->_oFcpoHelper->fcpoGetSessionVariable('fcpoAmazonPayAddressWidgetLocked');
        return $blAmazonPayAddressWidgetLocked;
    }

    /**
     * Returns url that will be send to amazon for redirect after login
     *
     * @param void
     * @return string
     */
    public function fcpoGetAmazonRedirectUrl() {
        $oConfig = $this->_oFcpoHelper->fcpoGetConfig();
        $sShopUrl = $oConfig->getSslShopUrl();
        // force protocol to be 100% ssl
        if (strpos($sShopUrl, 'http://') !== false) {
            $sShopUrl = str_replace('http://', 'https://', $sShopUrl);
        }
        $sRedirectUrl = $sShopUrl."index.php?cl=user&fnc=fcpoamazonloginreturn";

        return $sRedirectUrl;
    }

    /**
     * Method returns if there is an active amazon session
     *
     * @param void
     * @return bool
     */
    public function fcpoAmazonLoginSessionActive() {
        $sAmazonLoginAccessToken =
            $this->_oFcpoHelper->fcpoGetSessionVariable('sAmazonLoginAccessToken');

        $blLoggedIn = ($sAmazonLoginAccessToken) ? true : false;

        return $blLoggedIn;
    }

    /**
     * Method returns active theme path by checking current theme and its parent
     * If theme is not assignable, 'azure' will be the fallback
     *
     * @param void
     * @return string
     */
    public function fcpoGetActiveThemePath() {
        $sReturn = 'azure';
        $oTheme = $this->_oFcpoHelper->getFactoryObject('oxTheme');

        $sCurrentActiveId = $oTheme->getActiveThemeId();
        $oTheme->load($sCurrentActiveId);
        $aThemeIds = array_keys($this->_aSupportedThemes);
        $sCurrentParentId = $oTheme->getInfo('parentTheme');

        // we're more interested on the parent then on child theme
        if ($sCurrentParentId) {
            $sCurrentActiveId = $sCurrentParentId;
        }

        if (in_array($sCurrentActiveId, $aThemeIds)) {
            $sReturn = $this->_aSupportedThemes[$sCurrentActiveId];
        }

        return $sReturn;
    }

    /**
     * Makes this Email unique to be able to handle amazon users different from standard users
     * Currently the email address simply gets a prefix
     *
     * @param $sEmail
     * @return string
     */
    public function fcpoAmazonEmailEncode($sEmail) {
        $sAmazonEmail = "fcpoamz_".$sEmail;

        return $sAmazonEmail;
    }

    /**
     * Returns the origin email of an amazon encoded email
     *
     * @param $sEmail
     * @return string
     */
    public function fcpoAmazonEmailDecode($sEmail) {
        $sOriginEmail = $sEmail;
        if (strpos($sEmail, 'fcpoamz_') !== false) {
            $sOriginEmail = str_replace('fcpoamz_', '', $sEmail);
        }

        return $sOriginEmail;
    }

    /**
     * Returns if amazon runs in async mode
     *
     * @param void
     * @return bool
     */
    public function fcpoIsAmazonAsyncMode() {
        $oConfig = $this->getConfig();
        $sFCPOAmazonMode = $oConfig->getConfigParam('sFCPOAmazonMode');
        $blReturn = false;
        if ($sFCPOAmazonMode == 'alwaysasync') {
            $blReturn = true;
        }

        return $blReturn;
    }

    /**
     * Checks if popup method should be used. Depends on setting and/or
     * ssl state
     *
     * @param void
     * @return string
     */
    public function fcpoGetAmzPopup() {
        $oConfig = $this->getConfig();
        $sFCPOAmazonLoginMode = $oConfig->getConfigParam('sFCPOAmazonLoginMode');
        switch ($sFCPOAmazonLoginMode) {
            case 'popup':
                $sReturn = 'true';
                break;
            case 'redirect':
                $sReturn = 'false';
                break;
            default:
                $sReturn = 'false';
                if ($this->isSsl()) {
                    $sReturn = 'true';
                }
        }

        return $sReturn;
    }

    /**
     * Returns current widget count
     *
     * @return int
     */
    public function fcpoGetCurrentAmzWidgetCount() {
        return $this->_iAmzWidgetIncludeCounter;
    }

    /**
     * References current button id set in template
     * for determine the last amazon button on current page
     *
     * @param string $sButtonId
     * @return void
     */
    public function fcpoSetCurrentAmazonButtonId($sButtonId) {
        $this->_sCurrentAmazonButtonId = $sButtonId;
    }


    /**
     * Decides if the JS widgets url source should be included
     * Makes sure it will be included after the last amazon button
     *
     * @param void
     * @return bool
     */
    public function fcpoGetAllowIncludeAmazonWidgetUrl() {
        $iCurrentInludeCount = (int) $this->_oFcpoHelper->fcpoGetSessionVariable('iAmzWidgetsIncludeCounter');
        $iCurrentInludeCount++;
        $this->_oFcpoHelper->fcpoSetSessionVariable('iAmzWidgetsIncludeCounter', $iCurrentInludeCount);

        $iExpectedButtonAmount = $this->_fcpoGetExpectedButtonAmount();

        $blReturn = ($iCurrentInludeCount >= $iExpectedButtonAmount) ? true: false;
        if ($blReturn) {
            // reset counter
            $this->_oFcpoHelper->fcpoSetSessionVariable('iAmzWidgetsIncludeCounter', 0);
        }

        return $blReturn;
    }

    /**
     * Template getter for receiving masterpass button url
     *
     * @param void
     * @return string
     */
    public function fcpoGetMasterpassButtonImg() {
        $sUrl = 'https://masterpass.com/dyn/img/btn/global/mp_chk_btn_147x034px.svg';

        return $sUrl;
    }

    /**
     * Returns url of masterpass js library depending on set mode
     *
     * @param void
     * @return string
     */
    public function fcpoGetMasterpassJsLibUrl() {
        $oPayment = $this->_oFcpoHelper->getFactoryObject('oxpayment');
        $oPayment->load('fcpomasterpass');
        $blIsLive = $oPayment->oxpayments__fcpolivemode->value;

        $sUrl = "https://sandbox.masterpass.com/lightbox/Switch/integration/MasterPass.client.js";
        if ($blIsLive) {
            $sUrl = "https://www.masterpass.com/lightbox/Switch/integration/MasterPass.client.js";
        }

        return $sUrl;
    }

    /**
     * Template getter for deciding if masterpass button can be shown
     *
     * @param void
     * @return bool
     */
    public function fcpoCanDisplayMasterpassButton() {
        $oPayment = $this->_oFcpoHelper->getFactoryObject('oxpayment');
        $oPayment->load('fcpomasterpass');
        $blIsActive = (bool) $oPayment->oxpayments__oxactive->value;

        return $blIsActive;
    }

    /**
     * References current button id set in template
     * for determine the last masterpass button on current page
     *
     * @param string $sButtonId
     * @return void
     */
    public function fcpoSetCurrentMasterpassButtonId($sButtonId) {
        $this->_sCurrentMasterpassButtonId = $sButtonId;
    }

    /**
     * Returns the expected amount of amazon buttons on current page
     *
     * @param void
     * @return void
     */
    protected function _fcpoGetExpectedButtonAmount() {
        $blModalMiniBasket = ($this->_sCurrentAmazonButtonId == 'modalLoginWithAmazonMiniBasket');
        $aController2Amount = array(
            'basket' => 3,
            'user'=> 2,
        );

        $sActController = $this->_oFcpoHelper->fcpoGetRequestParameter('cl');

        $iAmountExpectedButtons = (isset($aController2Amount[$sActController])) ? $aController2Amount[$sActController] : 1;
        if ($blModalMiniBasket) {
            $iAmountExpectedButtons++;
        }

        return $iAmountExpectedButtons;
    }

    /**
     * Template getter for returning ajax controller url
     *
     * @param void
     * @return string
     * @todo class has to be moved into controller folder instead of models
     */
    public function fcpoGetAjaxControllerUrl() {
        $oConfig = $this->getConfig();
        $sShopUrl = $oConfig->getShopUrl();
        $sPath = "modules/fcPayOne/application/models/fcpayone_ajax.php";
        $sControllerPath = $sShopUrl.$sPath;

        return $sControllerPath;
    }

    /**
     * Template getter for returning shopurl
     *
     * @param void
     * @return string
     */
    public function fcpoGetShopUrl() {
        $oConfig = $this->getConfig();
        $sShopUrl = $oConfig->getShopUrl();

        return $sShopUrl;
    }
}
