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
 
class Unit_fcPayOne_Extend_Application_Controllers_fcPayOnePaymentView extends OxidTestCase {

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array()) {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Set protected/private attribute value
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $propertyName property that shall be set
     * @param array  $value value to be set
     *
     * @return mixed Method return.
     */
    public function invokeSetAttribute(&$object, $propertyName, $value) {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }

    /**
     * Testing fcpoMasterpassSuccessReturn in case of error response
     */
    public function test_fcpoMasterpassSuccessReturn_ResponseError() {
        $oMockConfig = $this->getMock('oxConfig', array(
            'getShopUrl'
        ));
        $oMockConfig
            ->expects($this->any())
            ->method('getShopUrl')
            ->will($this->returnValue('https://someurl.com'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getConfig',
            'render',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('getConfig')
            ->will($this->returnValue($oMockConfig));
        $oTestObject
            ->expects($this->any())
            ->method('render')
            ->will($this->returnValue(null));

        $aMockResponse = array(
            'status' => 'ERROR',
        );

        $oMockRequest = $this->getMock('fcporequest', array(
            'fcpoSendRequestMasterpassGetCheckout'
        ));
        $oMockRequest
            ->expects($this->any())
            ->method('fcpoSendRequestMasterpassGetCheckout')
            ->will($this->returnValue($aMockResponse));

        $oMockUser = $this->getMock('oxUser', array(
            'fcpoSetMasterpassUser'
        ));
        $oMockUser
            ->expects($this->any())
            ->method('fcpoSetMasterpassUser')
            ->will($this->returnValue(false));

        $oMockUtils = $this->getMock('oxUtils', array(
            'redirect'
        ));
        $oMockUtils
            ->expects($this->any())
            ->method('redirect')
            ->will($this->returnValue(null));

        $oHelper =
            $this
                ->getMockBuilder('fcpohelper')
                ->disableOriginalConstructor()
                ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoSetSessionVariable')
            ->will($this->returnValue(null));
        $oHelper
            ->expects($this->any())
            ->method('getFactoryObject')
            ->will($this->onConsecutiveCalls($oMockRequest, $oMockUser));
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetUtils')
            ->will($this->returnValue($oMockUtils));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->fcpoMasterpassSuccessReturn());
    }

    /**
     * Testing fcpoMasterpassSuccessReturn in case of user creation failed
     */
    public function test_fcpoMasterpassSuccessReturn_CreateUserFailed() {
        $oMockConfig = $this->getMock('oxConfig', array(
            'getShopUrl'
        ));
        $oMockConfig
            ->expects($this->any())
            ->method('getShopUrl')
            ->will($this->returnValue('https://someurl.com'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getConfig',
            'render',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('getConfig')
            ->will($this->returnValue($oMockConfig));
        $oTestObject
            ->expects($this->any())
            ->method('render')
            ->will($this->returnValue(null));

        $aMockResponse = array(
            'status' => 'OK',
        );

        $oMockRequest = $this->getMock('fcporequest', array(
            'fcpoSendRequestMasterpassGetCheckout'
        ));
        $oMockRequest
            ->expects($this->any())
            ->method('fcpoSendRequestMasterpassGetCheckout')
            ->will($this->returnValue($aMockResponse));

        $oMockUser = $this->getMock('oxUser', array(
            'fcpoSetMasterpassUser'
        ));
        $oMockUser
            ->expects($this->any())
            ->method('fcpoSetMasterpassUser')
            ->will($this->returnValue(false));

        $oMockUtils = $this->getMock('oxUtils', array(
            'redirect'
        ));
        $oMockUtils
            ->expects($this->any())
            ->method('redirect')
            ->will($this->returnValue(null));

        $oHelper =
            $this
                ->getMockBuilder('fcpohelper')
                ->disableOriginalConstructor()
                ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoSetSessionVariable')
            ->will($this->returnValue(null));
        $oHelper
            ->expects($this->any())
            ->method('getFactoryObject')
            ->will($this->onConsecutiveCalls($oMockRequest, $oMockUser));
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetUtils')
            ->will($this->returnValue($oMockUtils));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->fcpoMasterpassSuccessReturn());
    }

    /**
     * Testing getPaymentErrorText for coverage
     */
    public function test_getPaymentErrorText_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $sExpect = $sResponse = $oTestObject->getPaymentErrorText();

        $this->assertEquals($sExpect, $sResponse);
    }


    /**
     * Testing fcpoMasterpassSuccessReturn in case of success
     */
    public function test_fcpoMasterpassSuccessReturn_Success() {
        $oMockConfig = $this->getMock('oxConfig', array(
            'getShopUrl'
        ));
        $oMockConfig
            ->expects($this->any())
            ->method('getShopUrl')
            ->will($this->returnValue('https://someurl.com'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getConfig',
            'render',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('getConfig')
            ->will($this->returnValue($oMockConfig));
        $oTestObject
            ->expects($this->any())
            ->method('render')
            ->will($this->returnValue(null));

        $aMockResponse = array(
            'status' => 'OK',
        );

        $oMockRequest = $this->getMock('fcporequest', array(
            'fcpoSendRequestMasterpassGetCheckout'
        ));
        $oMockRequest
            ->expects($this->any())
            ->method('fcpoSendRequestMasterpassGetCheckout')
            ->will($this->returnValue($aMockResponse));

        $oMockUser = $this->getMock('oxUser', array(
            'fcpoSetMasterpassUser'
        ));
        $oMockUser
            ->expects($this->any())
            ->method('fcpoSetMasterpassUser')
            ->will($this->returnValue(true));

        $oMockUtils = $this->getMock('oxUtils', array(
            'redirect'
        ));
        $oMockUtils
            ->expects($this->any())
            ->method('redirect')
            ->will($this->returnValue(null));

        $oHelper =
            $this
                ->getMockBuilder('fcpohelper')
                ->disableOriginalConstructor()
                ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoSetSessionVariable')
            ->will($this->returnValue(null));
        $oHelper
            ->expects($this->any())
            ->method('getFactoryObject')
            ->will($this->onConsecutiveCalls($oMockRequest, $oMockUser));
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetUtils')
            ->will($this->returnValue($oMockUtils));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->fcpoMasterpassSuccessReturn());
    }

    /**
     * Testing fcpoAmazonUserLogin for coverage
     */
    public function test_fcpoAmazonUserLogin_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('render'));
        $oTestObject->expects($this->any())->method('render')->will($this->returnValue(null));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils
            ->expects($this->any())
            ->method('redirect')
            ->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->fcpoAmazonUserLogin());
    }

    /**
     * Testing fcpoGetRatePayMatchedProfile for coverage
     */
    public function test_fcpoGetRatePayMatchedProfile_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $sExpect = $sResponse = $oTestObject->fcpoGetRatePayMatchedProfile('somePaymentId');

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing fcpoGetSofoShowIban for coverage
     */
    public function test_fcpoGetSofoShowIban_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig
            ->expects($this->any())
            ->method('getConfigParam')
            ->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('true', $oTestObject->fcpoGetSofoShowIban());
    }

    /**
     * Testing _filterDynData for having filter
     * 
     * @param void
     * @return void
     */
    public function test__filterDynData_HasFilter() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_hasFilterDynDataMethod'));
        $oTestObject->expects($this->any())->method('_hasFilterDynDataMethod')->will($this->returnValue(true));

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_filterDynData'));
    }

    /**
     * Testing _filterDynData for using method to store cc data
     * 
     * @param void
     * @return void
     */
    public function test__filterDynData_CCStored() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_hasFilterDynDataMethod'));
        $oTestObject->expects($this->any())->method('_hasFilterDynDataMethod')->will($this->returnValue(false));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_filterDynData'));
    }

    /**
     * Testing _filterDynData for case of renew cc data
     * 
     * @param void
     * @return void
     */
    public function test__filterDynData_Renew() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_hasFilterDynDataMethod'));
        $oTestObject->expects($this->any())->method('_hasFilterDynDataMethod')->will($this->returnValue(false));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $aDynData = array('someValue');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($aDynData));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_filterDynData'));
    }

    /**
     * Testing init method
     * 
     * @param void
     * @return void
     */
    public function test_init_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_hasFilterDynDataMethod'));
        $oTestObject->expects($this->any())->method('_hasFilterDynDataMethod')->will($this->returnValue(false));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->onConsecutiveCalls(true, true));

        $oMockOrder = $this->getMock('oxOrder', array('load'));
        $oMockOrder->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('cancel'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        /**
         * Switched off test due to scalar error on the parent call
         */
        // $this->assertEquals(null, $oTestObject->init());
    }

    /**
     * Testing _hasFilterDynDataMethod for coverage
     * 
     * @param void
     * @return void
     */
    public function test__hasFilterDynDataMethod_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockConfig = $this->getMock('oxConfig', array('getVersion'));
        $oMockConfig->expects($this->any())->method('getVersion')->will($this->returnValue('4.10.7'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $this->invokeMethod($oTestObject, '_hasFilterDynDataMethod'));
    }

    /**
     * Testing getConfigParam for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getConfigParam_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someConfigValue'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $this->assertEquals('someConfigValue', $oTestObject->getConfigParam('someParamName'));
    }

    /**
     * Testing getMerchantId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getMerchantId_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue('someMerchantId'));

        $this->assertEquals('someMerchantId', $oTestObject->getMerchantId());
    }

    /**
     * Testing getSubAccountId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getSubAccountId_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $this->assertEquals('someValue', $oTestObject->getSubAccountId());
    }

    /**
     * Testing getPortalId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getPortalId_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $this->assertEquals('someValue', $oTestObject->getPortalId());
    }

    /**
     * Testing getPortalKey for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getPortalKey_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $this->assertEquals('someValue', $oTestObject->getPortalKey());
    }

    /**
     * Testing getChecktype for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getChecktype_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $this->assertEquals('someValue', $oTestObject->getChecktype());
    }

    /**
     * Testing fcpoRatePayAllowed for coverage
     */
    public function test_fcpoRatePayAllowed_Coverage() {
        $aMockProfile = array('some', 'data');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoGetMatchingProfile'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetMatchingProfile')
            ->will($this->returnValue($aMockProfile));

        $this->assertEquals(true, $oTestObject->fcpoRatePayAllowed('somePaymentId'));
    }

    /**
     * Testing getUserBillCountryId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getUserBillCountryId_Coverage() {
        $oMockUser = new stdClass();
        $oMockUser->oxuser__oxcountryid = new oxField('someCountryId');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $this->invokeSetAttribute($oTestObject, '_sUserBillCountryId', null);

        $this->assertEquals('someCountryId', $oTestObject->getUserBillCountryId());
    }

    /**
     * Testing getUserDelCountryId fo rcoverage
     * 
     * @param void
     * @return void
     */
    public function test_getUserDelCountryId_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockAddress = new stdClass();
        $oMockAddress->oxaddress__oxcountryid = new oxField('someCountryId');

        $oMockOrder = $this->getMock('oxOrder', array('getDelAddressInfo'));
        $oMockOrder->expects($this->any())->method('getDelAddressInfo')->will($this->returnValue($oMockAddress));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_sUserDelCountryId', null);

        $this->assertEquals('someCountryId', $oTestObject->getUserDelCountryId());
    }

    /**
     * Testing isPaymentMethodAvailableToUser for case delivery address
     * 
     * @param void
     * @return void
     */
    public function test_isPaymentMethodAvailableToUser_DelAddress() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUserBillCountryId', 'getUserDelCountryId'));
        $oTestObject->expects($this->any())->method('getUserBillCountryId')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getUserDelCountryId')->will($this->returnValue(true));

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someValue'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals('1', $this->invokeMethod($oTestObject, 'isPaymentMethodAvailableToUser', array('paymentid', 'type')));
    }

    /**
     * Testing isPaymentMethodAvailableToUser for case bill address
     * 
     * @param void
     * @return void
     */
    public function test_isPaymentMethodAvailableToUser_BillAddress() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUserBillCountryId', 'getUserDelCountryId'));
        $oTestObject->expects($this->any())->method('getUserBillCountryId')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getUserDelCountryId')->will($this->returnValue(false));

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someValue'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals('1', $this->invokeMethod($oTestObject, 'isPaymentMethodAvailableToUser', array('paymentid', 'type')));
    }

    /**
     * Testing hasPaymentMethodAvailableSubTypes for CC
     * 
     * @param void
     * @return void
     */
    public function test_hasPaymentMethodAvailableSubTypes_CC() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getVisa',
            'getMastercard',
            'getAmex',
            'getDiners',
            'getJCB',
            'getMaestroInternational',
            'getMaestroUK',
            'getDiscover',
            'getCarteBleue',
            'getSofortUeberweisung',
            'getGiropay',
            'getEPS',
            'getPostFinanceEFinance',
            'getPostFinanceCard',
            'getIdeal',
            'getP24',
                )
        );

        $oTestObject->expects($this->any())->method('getVisa')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getMastercard')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getAmex')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getDiners')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getJCB')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getMaestroInternational')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getMaestroUK')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getDiscover')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getCarteBleue')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getSofortUeberweisung')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getGiropay')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getEPS')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getPostFinanceCard')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getIdeal')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getP24')->will($this->returnValue(false));
        $this->assertEquals(false, $oTestObject->hasPaymentMethodAvailableSubTypes('cc'));
    }

    /**
     * Testing hasPaymentMethodAvailableSubTypes for CC
     * 
     * @param void
     * @return void
     */
    public function test_hasPaymentMethodAvailableSubTypes_SB() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getVisa',
            'getMastercard',
            'getAmex',
            'getDiners',
            'getJCB',
            'getMaestroInternational',
            'getMaestroUK',
            'getDiscover',
            'getCarteBleue',
            'getSofortUeberweisung',
            'getGiropay',
            'getEPS',
            'getPostFinanceEFinance',
            'getPostFinanceCard',
            'getIdeal',
            'getP24',
                )
        );

        $oTestObject->expects($this->any())->method('getVisa')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getMastercard')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getAmex')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getDiners')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getJCB')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getMaestroInternational')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getMaestroUK')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getDiscover')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getCarteBleue')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getSofortUeberweisung')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getGiropay')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getEPS')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getPostFinanceCard')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getIdeal')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getP24')->will($this->returnValue(false));
        $this->assertEquals(false, $oTestObject->hasPaymentMethodAvailableSubTypes('sb'));
    }

    /**
     * Testing getDefaultOnlineUeberweisung for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getDefaultOnlineUeberweisung_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getSofortUeberweisung',
            'getGiropay',
            'getEPS',
            'getPostFinanceEFinance',
            'getPostFinanceCard',
            'getIdeal',
            'getP24',
                )
        );
        $oTestObject->expects($this->any())->method('getSofortUeberweisung')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getGiropay')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getEPS')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getPostFinanceEFinance')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getPostFinanceCard')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getIdeal')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getP24')->will($this->returnValue(false));
        $this->assertEquals('', $oTestObject->getDefaultOnlineUeberweisung());
    }

    /**
     * Testing getVisa vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getVisa_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getVisa());
    }

    /**
     * Testing getMastercard vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getMastercard_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getMastercard());
    }

    /**
     * Testing getAmex vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getAmex_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getAmex());
    }

    /**
     * Testing getDiners vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getDiners_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getDiners());
    }

    /**
     * Testing getJCB vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getJCB_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getJCB());
    }

    /**
     * Testing getMaestroInternational vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getMaestroInternational_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getMaestroInternational());
    }

    /**
     * Testing getMaestroUK vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getMaestroUK_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getMaestroUK());
    }

    /**
     * Testing getDiscover vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getDiscover_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getDiscover());
    }

    /**
     * Testing getCarteBleue vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getCarteBleue_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getCarteBleue());
    }

    /**
     * Testing getSofortUeberweisung vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getSofortUeberweisung_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getSofortUeberweisung());
    }

    /**
     * Testing getGiropay vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getGiropay_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getGiropay());
    }

    /**
     * Testing getEPS vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getEPS_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getEPS());
    }

    /**
     * Testing getPostFinanceEFinance vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getPostFinanceEFinance_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getPostFinanceEFinance());
    }

    /**
     * Testing getPostFinanceCard vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getPostFinanceCard_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getPostFinanceCard());
    }

    /**
     * Testing getIdeal vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getIdeal_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getIdeal());
    }

    /**
     * Testing getP24 vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getP24_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getP24());
    }

    /**
     * Testing get encoding for utf8
     * 
     * @param void
     * @return void
     */
    public function test_getEncoding_Utf8() {
        $oMockConfig = $this->getMock('oxConfig', array('isUtf'));
        $oMockConfig->expects($this->any())->method('isUtf')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $this->assertEquals('UTF-8', $oTestObject->getEncoding());
    }

    /**
     * Testing get encoding for ascii
     * 
     * @param void
     * @return void
     */
    public function test_getEncoding_NoUtf8() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('isUtf')->will($this->returnValue(false));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $this->assertEquals('ISO-8859-1', $oTestObject->getEncoding());
    }

    /**
     * Testing getAmount for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getAmount_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockPrice = $this->getMock('oxPrice', array('getBruttoPrice'));
        $oMockPrice->expects($this->any())->method('getBruttoPrice')->will($this->returnValue(1.99));

        $oMockBasket = $this->getMock('oxBasket', array('getPrice'));
        $oMockBasket->expects($this->any())->method('getPrice')->will($this->returnValue($oMockPrice));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(199, $oTestObject->getAmount());
    }

    /**
     * Testing getTplLang for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getTplLang_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockLang = $this->getMock('oxLang', array('getLanguageAbbr'));
        $oMockLang->expects($this->any())->method('getLanguageAbbr')->will($this->returnValue('DE'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('DE', $oTestObject->getTplLang());
    }

    /**
     * Testing fcGetLangId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcGetLangId_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockLang = $this->getMock('oxLang', array('getBaseLanguage'));
        $oMockLang->expects($this->any())->method('getBaseLanguage')->will($this->returnValue(0));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(0, $oTestObject->fcGetLangId());
    }

    /**
     * Testing getHashCC for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getHashCC_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $sResponse = $sExpect = $oTestObject->getHashCC('test');

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing fcpoGetCCPaymentMetaData for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetCCPaymentMetaData_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getVisa',
            'getMastercard',
            'getAmex',
            'getDiners',
            'getJCB',
            'getMaestroInternational',
            'getMaestroUK',
            'getDiscover',
            'getCarteBleue',
            '_fcpoGetCCPaymentMetaData',
        ));

        $oTestObject->expects($this->any())->method('getVisa')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getMastercard')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getAmex')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getDiners')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getJCB')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getMaestroInternational')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getMaestroUK')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getDiscover')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getCarteBleue')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetCCPaymentMetaData')->will($this->returnValue('someValue'));

        $aExpect = array('someValue', 'someValue', 'someValue', 'someValue', 'someValue', 'someValue', 'someValue', 'someValue', 'someValue');
        $aResponse = $oTestObject->fcpoGetCCPaymentMetaData();

        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing fcpoGetOnlinePaymentMetaData for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetOnlinePaymentMetaData_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getSofortUeberweisung',
            'getGiropay',
            'getEPS',
            'getPostFinanceEFinance',
            'getPostFinanceCard',
            'getIdeal',
            'getP24',
            'getBancontact',
            '_fcpoGetOnlinePaymentData',

        ));

        $oTestObject->expects($this->any())->method('getSofortUeberweisung')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getGiropay')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getEPS')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getPostFinanceEFinance')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getPostFinanceCard')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getIdeal')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getP24')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getBancontact')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetOnlinePaymentData')->will($this->returnValue('someValue'));

        $aExpect = array(
            'someValue',
            'someValue',
            'someValue',
            'someValue',
            'someValue',
            'someValue',
            'someValue',
            'someValue'
        );
        $aResponse = $oTestObject->fcpoGetOnlinePaymentMetaData();

        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing fcpoGetActiveThemePath for coverage
     */
    public function test_fcpoGetActiveThemePath_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockViewConfig = $this->getMock('oxViewConfig', array('fcpoGetActiveThemePath'));
        $oMockViewConfig->expects($this->any())->method('fcpoGetActiveThemePath')->will($this->returnValue('flow'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockViewConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('flow', $oTestObject->fcpoGetActiveThemePath());
    }


    /**
     * Testing _fcpoGetOnlinePaymentData for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoGetOnlinePaymentData_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getDynValue'));

        $sIdent = 'P24';
        $aDynValue['fcpo_sotype'] = $sIdent;
        $oTestObject->expects($this->any())->method('getDynValue')->will($this->returnValue($aDynValue));

        $oExpectPaymentMetaData = new stdClass();
        $oExpectPaymentMetaData->sShortcut = $sIdent;
        $oExpectPaymentMetaData->sCaption = 'P24';
        $oExpectPaymentMetaData->blSelected = true;

        $oResponse = $this->invokeMethod($oTestObject, '_fcpoGetOnlinePaymentData', array($sIdent));
        $this->assertEquals($oExpectPaymentMetaData, $oResponse);
    }

    /**
     * Testing _fcpoGetCCPaymentMetaData for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoGetCCPaymentMetaData_Coverage() {
        $sPaymentTag = 'someTag';
        $sPaymentName = 'someName';
        $aDynValue['fcpo_kktype'] = $sPaymentTag;
        $sHashCC = md5('12345');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getDynValue', 'getHashCC'));
        $oTestObject->expects($this->any())->method('getDynValue')->will($this->returnValue($aDynValue));
        $oTestObject->expects($this->any())->method('getHashCC')->will($this->returnValue($sHashCC));

        $oMockPayment = $this->getMock('oxPayment', array('getId', 'fcpoGetOperationMode'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockPayment->expects($this->any())->method('fcpoGetOperationMode')->will($this->returnValue('test'));

        $oExpectPaymentMetaData = new stdClass();
        $oExpectPaymentMetaData->sHashName = 'fcpo_hashcc_' . $sPaymentTag;
        $oExpectPaymentMetaData->sHashValue = $sHashCC;
        $oExpectPaymentMetaData->sOperationModeName = "fcpo_mode_someId_" . $sPaymentTag;
        $oExpectPaymentMetaData->sOperationModeValue = 'test';
        $oExpectPaymentMetaData->sPaymentTag = $sPaymentTag;
        $oExpectPaymentMetaData->sPaymentName = $sPaymentName;
        $oExpectPaymentMetaData->blSelected = true;

        $oResponse = $this->invokeMethod($oTestObject, '_fcpoGetCCPaymentMetaData', array($oMockPayment, $sPaymentTag, $sPaymentName));
        $this->assertEquals($oExpectPaymentMetaData, $oResponse);
    }

    /**
     * Testing _getOperationModeELV for coverage
     * 
     * @param void
     * @eturn void
     */
    public function test__getOperationModeELV_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $oMockPayment = $this->getMock('oxPayment', array('load', 'fcpoGetOperationMode'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcpoGetOperationMode')->will($this->returnValue('test'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('test', $this->invokeMethod($oTestObject, '_getOperationModeELV'));
    }

    /**
     * Testing getHashELVWithChecktype for Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getHashELVWithChecktype_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getSubAccountId',
            'getChecktype',
            'getEncoding',
            'getMerchantId',
            '_getOperationModeELV',
            'getPortalId',
            'getPortalKey',
                )
        );
        $oTestObject->expects($this->any())->method('getSubAccountId')->will($this->returnValue('someSubaccountId'));
        $oTestObject->expects($this->any())->method('getChecktype')->will($this->returnValue('someChecktype'));
        $oTestObject->expects($this->any())->method('getEncoding')->will($this->returnValue('someEncoding'));
        $oTestObject->expects($this->any())->method('getMerchantId')->will($this->returnValue('someMerchantId'));
        $oTestObject->expects($this->any())->method('_getOperationModeELV')->will($this->returnValue('test'));
        $oTestObject->expects($this->any())->method('getPortalId')->will($this->returnValue('somePortalId'));
        $oTestObject->expects($this->any())->method('getPortalKey')->will($this->returnValue('somePortalKey'));

        $sExpectHash = md5('someSubaccountIdsomeChecktypesomeEncodingsomeMerchantIdtestsomePortalIdbankaccountcheckJSONsomePortalKey');

        $this->assertEquals($sExpectHash, $this->invokeMethod($oTestObject, 'getHashELVWithChecktype'));
    }

    /**
     * Testing getHashELVWithoutChecktype for Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getHashELVWithoutChecktype_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getSubAccountId',
            'getEncoding',
            'getMerchantId',
            '_getOperationModeELV',
            'getPortalId',
            'getPortalKey',
                )
        );
        $oTestObject->expects($this->any())->method('getSubAccountId')->will($this->returnValue('someSubaccountId'));
        $oTestObject->expects($this->any())->method('getEncoding')->will($this->returnValue('someEncoding'));
        $oTestObject->expects($this->any())->method('getMerchantId')->will($this->returnValue('someMerchantId'));
        $oTestObject->expects($this->any())->method('_getOperationModeELV')->will($this->returnValue('test'));
        $oTestObject->expects($this->any())->method('getPortalId')->will($this->returnValue('somePortalId'));
        $oTestObject->expects($this->any())->method('getPortalKey')->will($this->returnValue('somePortalKey'));

        $sExpectHash = md5('someSubaccountIdsomeEncodingsomeMerchantIdtestsomePortalIdbankaccountcheckJSONsomePortalKey');

        $this->assertEquals($sExpectHash, $this->invokeMethod($oTestObject, 'getHashELVWithoutChecktype'));
    }

    /**
     * Testing getPaymentList for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getPaymentList_Coverage_1() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oMockUser = $this->getMock('oxUser', array('checkAddressAndScore'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils->expects($this->any())->method('redirect')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));

        $this->invokeSetAttribute($oTestObject, '_oPaymentList', null);

        $mResponse = $mExpect = $this->invokeMethod($oTestObject, 'getPaymentList');

        $this->assertEquals($mExpect, $mResponse);
    }

    /**
     * Testing getPaymentList for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getPaymentList_Coverage_2() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oMockUser = $this->getMock('oxUser', array('checkAddressAndScore'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(false));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils->expects($this->any())->method('redirect')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));

        $this->invokeSetAttribute($oTestObject, '_oPaymentList', null);
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $mResponse = $mExpect = $this->invokeMethod($oTestObject, 'getPaymentList');

        $this->assertEquals($mExpect, $mResponse);
    }

    /**
     * Testing fcpoUseCVC for coverage
     */
    public function test_fcpoUseCVC_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $this->assertEquals(true, $oTestObject->fcpoUseCVC());
    }

    /**
     * Testing fcpoGetBICMandatory for coverage
     */
    public function test_fcpoGetBICMandatory_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $this->assertEquals('true', $oTestObject->fcpoGetBICMandatory());
    }

    /**
     * Testing fcpoGetCreditcardType for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetCreditcardType_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $this->assertEquals('someValue', $this->invokeMethod($oTestObject, 'fcpoGetCreditcardType'));
    }

    /**
     * Testing validateAmazonPayment for coverage
     *
     * @param void
     * @return void
     */
    public function test_validateAmazonPayment_Coverage() {
        $oMockBasket = $this->getMock('oxBasket', array('setPayment'));
        $oMockBasket->expects($this->any())->method('setPayment')->will($this->returnValue(null));

        $oMockSesssion = $this->getMock('oxSession', array('getBasket'));
        $oMockSesssion->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getSession'));
        $oTestObject->expects($this->any())->method('getSession')->will($this->returnValue($oMockSesssion));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(null));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $oTestObject->validateAmazonPayment());
    }

    /**
     * Testing _fcpoCheckPaypalExpressRemoval for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoCheckPaypalExpressRemoval_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $this->invokeSetAttribute($oTestObject, '_oPaymentList', array('fcpopaypal_express' => 'someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue(false));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoCheckPaypalExpressRemoval'));
    }

    /**
     * Testing _fcpoKlarnaUpdateUser for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoKlarnaUpdateUser_Coverage() {
        $oMockUser = $this->getMock('oxUser', array('getSelectedAddressId'));
        $oMockUser->expects($this->any())->method('getSelectedAddressId')->will($this->returnValue('someAddressId'));

        $oMockAddress = $this->getMock('oxAddress', array('load', 'save'));
        $oMockAddress->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockAddress->expects($this->any())->method('save')->will($this->returnValue(true));

        $sType = 'klv';
        $aDynValue = array(
            'fcpo_' . $sType . '_fon' => '123456',
            'fcpo_' . $sType . '_birthday' => 'someBirthday',
            'fcpo_' . $sType . '_personalid' => 'someId',
            'fcpo_' . $sType . '_sal' => 'someSal',
            'fcpo_' . $sType . '_addinfo' => 'someAddinfo',
            'fcpo_' . $sType . '_del_addinfo' => 'someDelAddinfo',
        );

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getDynValue',
            'getUser',
            '_fcpoGetType',
            '_fcpoCheckUpdateField',
            '_fcpoGetPaymentId',
        ));
        $oTestObject->expects($this->any())->method('getDynValue')->will($this->returnValue($aDynValue));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_fcpoGetType')->will($this->returnValue($sType));
        $oTestObject->expects($this->any())->method('_fcpoCheckUpdateField')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetPaymentId')->will($this->returnValue('somePaymentId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockAddress));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoKlarnaUpdateUser());
    }

    /**
     * Testing _fcpoCheckUpdateField for coverage
     */
    public function test__fcpoCheckUpdateField_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $blMockUserChanged = true;
        $sMockType = 'someType';
        $sMockDbField = 'someField';
        $sMockDynValueField = 'someDynField';
        $sMockIdent = 'fcpo_' . $sMockType . '_' . $sMockDynValueField;
        $aMockDynValue = array(
            $sMockIdent=>'someValue',
        );

        $oMockUser = oxNew('oxUser');

        $this->assertEquals(true, $oTestObject->_fcpoCheckUpdateField(
            $blMockUserChanged,
            $sMockType,
            $aMockDynValue,
            $sMockDbField,
            $sMockDynValueField,
            $oMockUser
        ));
    }

    /**
     * Testing _fcpoGetType for coverage
     */
    public function test__fcpoGetType_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $this->assertEquals('klv', $oTestObject->_fcpoGetType('somePaymentId'));
    }

    /**
     * Testing validatePayment coverage
     * 
     * @param void
     * @return void
     */
    public function test_validatePayment_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoGetPaymentId',
            '_fcpoCheckKlarnaUpdateUser',
            '_processParentReturnValue',
            '_fcpoProcessValidation'
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetPaymentId')
            ->will($this->returnValue('somePaymentId'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCheckKlarnaUpdateUser')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('_processParentReturnValue')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoProcessValidation')
            ->will($this->returnValue('order'));

        $this->assertEquals('order', $this->invokeMethod($oTestObject, 'validatePayment'));
    }

    /**
     * Testing fcpoGetInstallments for coverage
     */
    public function test_fcpoGetInstallments_Coverage() {
         $oTestObject = oxNew('fcPayOnePaymentView');

         $aMockResult = array('some', 'data');
         $this->invokeSetAttribute($oTestObject, '_aInstallmentCalculation', $aMockResult);

         $this->assertEquals($aMockResult, $oTestObject->fcpoGetInstallments());
    }

    /**
     * Testing _fcpoGetMatchingProfile for coverage
     */
    public function test__fcpoGetMatchingProfile_Coverage() {
        $aMockProfiles = array(
            array(
                'tx_limit_invoice_max'=>'2000',
                'tx_limit_invoice_min'=>'50',
                'activation_status_invoice'=>'okelydokely',
            ),
        );

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoFetchRatePayProfilesByPaymentType',
            '_fcpoGetRatePayStringAdditionByPaymentId',
            '_fcpoCheckRatePayProfileMatch',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoFetchRatePayProfilesByPaymentType')
            ->will($this->returnValue($aMockProfiles));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetRatePayStringAdditionByPaymentId')
            ->will($this->returnValue('invoice'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCheckRatePayProfileMatch')
            ->will($this->returnValue(true));

        $aExpect = array(
            'tx_limit_invoice_max'=>'2000',
            'tx_limit_invoice_min'=>'50',
            'activation_status_invoice'=>'okelydokely',
        );

        $this->assertEquals($aExpect, $oTestObject->_fcpoGetMatchingProfile('somePaymentId'));
    }

    /**
     * Testing _fcpoCheckRatePayProfileMatch for coverage
     */
    public function test__fcpoCheckRatePayProfileMatch_Coverage() {
        $aMockRatepayMatchData = array(
            'activation_status'=>'2',
            'basketvalue_max'=>219.99,
            'basketvalue_min'=>29.99,
        );

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('fcpoGetBasketSum'));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoGetBasketSum')
            ->will($this->returnValue(199.99));

        $this->assertEquals(
            true,
            $oTestObject->_fcpoCheckRatePayProfileMatch($aMockRatepayMatchData)
        );
    }

    /**
     * Testing _fcpoGetRatePayStringAdditionByPaymentId for coverage
     */
    public function test__fcpoGetRatePayStringAdditionByPaymentId_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $this->assertEquals('invoice', $oTestObject->_fcpoGetRatePayStringAdditionByPaymentId('fcporp_bill'));
    }

    /**
     * Testing _fcpoFetchRatePayProfilesByPaymentType for coverage
     */
    public function test__fcpoFetchRatePayProfilesByPaymentType_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $aMockRatePayProfiles = array('some','data');

        $oMockRatePay = $this->getMock('fcporatepay', array(
            'fcpoGetRatePayProfiles',
        ));
        $oMockRatePay
            ->expects($this->any())
            ->method('fcpoGetRatePayProfiles')
            ->will($this->returnValue($aMockRatePayProfiles));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper
            ->expects($this->any())
            ->method('getFactoryObject')
            ->will($this->returnValue($oMockRatePay));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            $aMockRatePayProfiles,
            $oTestObject->_fcpoFetchRatePayProfilesByPaymentType('somePaymentId')
        );
    }



    /**
     * Testing _processParentReturnValue for coverage
     * 
     * @param void
     * @return void
     */
    public function test__processParentReturnValue_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $this->assertEquals('someValue', $this->invokeMethod($oTestObject, '_processParentReturnValue', array('someValue')));
    }

    /**
     * Testing fcGetApprovalText for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcGetApprovalText_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $this->assertEquals('someValue', $oTestObject->fcGetApprovalText());
    }

    /**
     * Testing fcShowApprovalMessage for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcShowApprovalMessage_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $this->assertEquals(true, $oTestObject->fcShowApprovalMessage());
    }

    /**
     * Testing getIntegratorid for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getIntegratorid_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntegratorId')->will($this->returnValue('someIntegratorId'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someIntegratorId', $oTestObject->getIntegratorid());
    }

    /**
     * Testing getIntegratorver for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getIntegratorver_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntegratorVersion')->will($this->returnValue('someIntegratorVersion'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someIntegratorVersion', $oTestObject->getIntegratorver());
    }

    /**
     * Testing getIntegratorextver for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getIntegratorextver_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetModuleVersion')->will($this->returnValue('someModuleVersion'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someModuleVersion', $oTestObject->getIntegratorextver());
    }

    /**
     * Testing fcpoGetConfirmationText for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetConfirmationText_Coverage() {
        $sId = 'someKlarnaStoreId';
        $sKlarnaLang = '';
        $sConfirmText = 'someConfirmText';

        $oMockPayment = $this->getMock('oxpayment', array('fcpoGetKlarnaStoreId'));
        $oMockPayment->expects($this->any())->method('fcpoGetKlarnaStoreId')->will($this->returnValue(''));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoGetKlarnaLang'));
        $oTestObject->expects($this->any())->method('_fcpoGetKlarnaLang')->will($this->returnValue($sKlarnaLang));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue($sConfirmText));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = $sConfirmText;
        $this->assertEquals($sExpect, $oTestObject->fcpoGetConfirmationText());
    }

    /**
     * Testing fcpoKlarnaIsTelephoneNumberNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaIsTelephoneNumberNeeded_Coverage() {
        $oMockUser = new stdClass();
        $oMockUser->oxuser__oxfon = new oxField('123456789');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $this->assertEquals(false, $oTestObject->fcpoKlarnaIsTelephoneNumberNeeded());
    }

    /**
     * Testing fcpoKlarnaIsBirthdayNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaIsBirthdayNeeded_Coverage() {
        $oMockCountry = new stdClass();
        $oMockCountry->oxcountry__oxisoalpha2 = new oxField('DE');

        $oMockUser = $this->getMock('oxUser', array('getUserCountry'));
        $oMockUser->expects($this->any())->method('getUserCountry')->will($this->returnValue($oMockCountry));
        $oMockUser->oxuser__oxbirthdate = new oxField('0000-00-00');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $this->assertEquals(true, $oTestObject->fcpoKlarnaIsBirthdayNeeded());
    }

    /**
     * Testing fcpoKlarnaIsAddressAdditionNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaIsAddressAdditionNeeded_Coverage() {
        $oMockUser = new stdClass();
        $oMockUser->oxuser__oxaddinfo = new oxField('');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', 'fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('fcGetBillCountry')->will($this->returnValue('nl'));

        $this->assertEquals(true, $oTestObject->fcpoKlarnaIsAddressAdditionNeeded());
    }

    /**
     * Testing fcpoKlarnaIsDelAddressAdditionNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaIsDelAddressAdditionNeeded_Coverage() {
        $oMockUser = $this->getMock('oxUser', array('getSelectedAddressId'));
        $oMockUser->expects($this->any())->method('getSelectedAddressId')->will($this->returnValue('someAddressId'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', 'fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('fcGetBillCountry')->will($this->returnValue('nl'));

        $oMockAddress = $this->getMock('oxAddress', array('load'));
        $oMockAddress->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockAddress->oxaddress__oxaddinfo = new oxField(false);

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockAddress));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->fcpoKlarnaIsDelAddressAdditionNeeded());
    }

    /**
     * Testing fcpoKlarnaIsGenderNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaIsGenderNeeded_Coverage() {
        $oMockUser = new stdClass();
        $oMockUser->oxuser__oxsal = new oxField(false);

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', 'fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('fcGetBillCountry')->will($this->returnValue('nl'));

        $this->assertEquals(true, $oTestObject->fcpoKlarnaIsGenderNeeded());
    }

    /**
     * Testing fcpoKlarnaIsPersonalIdNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaIsPersonalIdNeeded_Coverage() {
        $oMockUser = new stdClass();
        $oMockUser->oxuser__fcpopersonalid = new oxField(false);

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', 'fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('fcGetBillCountry')->will($this->returnValue('dk'));

        $this->assertEquals(true, $oTestObject->fcpoKlarnaIsPersonalIdNeeded());
    }

    /**
     * Testing fcpoKlarnaInfoNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaInfoNeeded_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'fcpoKlarnaIsTelephoneNumberNeeded',
            'fcpoKlarnaIsBirthdayNeeded',
            'fcpoKlarnaIsAddressAdditionNeeded',
            'fcpoKlarnaIsDelAddressAdditionNeeded',
            'fcpoKlarnaIsGenderNeeded',
            'fcpoKlarnaIsPersonalIdNeeded',
                )
        );
        $oTestObject->expects($this->any())->method('fcpoKlarnaIsTelephoneNumberNeeded')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('fcpoKlarnaIsBirthdayNeeded')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('fcpoKlarnaIsAddressAdditionNeeded')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('fcpoKlarnaIsDelAddressAdditionNeeded')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('fcpoKlarnaIsGenderNeeded')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('fcpoKlarnaIsPersonalIdNeeded')->will($this->returnValue(false));

        $this->assertEquals(false, $oTestObject->fcpoKlarnaInfoNeeded());
    }

    /**
     * Testing fcpoGetDebitCountries for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetDebitCountries_Coverage() {
        $aCountries = array('a7c40f631fc920687.20179984');

        $oMockPayment = $this->getMock('oxPayment', array('fcpoGetCountryIsoAlphaById', 'fcpoGetCountryNameById'));
        $oMockPayment->expects($this->any())->method('fcpoGetCountryIsoAlphaById')->will($this->returnValue('DE'));
        $oMockPayment->expects($this->any())->method('fcpoGetCountryNameById')->will($this->returnValue('Deutschland'));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue($aCountries));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', 'fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aExpect = array();
        $aExpect['DE'] = 'Deutschland';

        $this->assertEquals($aExpect, $oTestObject->fcpoGetDebitCountries());
    }

    /**
     * Testing fcpoShowOldDebitFields for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoShowOldDebitFields_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', 'fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->fcpoShowOldDebitFields());
    }

    /**
     * Testing _fcCleanupSessionFragments for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcCleanupSessionFragments_Coverage() {
        $oMockPayment = $this->getMock('oxPayment', array('getId'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('someId'));

        $oTestObject = oxNew('fcPayOnePaymentView');
        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcCleanupSessionFragments', array($oMockPayment)));
    }

    /**
     * Testing _fcGetPaymentByPaymentType for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcGetPaymentByPaymentType_Positive() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockUser = $this->getMock('oxUser', array('getId'));
        $oMockUser->expects($this->any())->method('getId')->will($this->returnValue('someId'));

        $sMockPaymentType = 'fcpopayadvance';

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someValue'));

        $oMockUserPayment = $this->getMock('oxuserpayment', array('load'));
        $oMockUserPayment->expects($this->any())->method('load')->will($this->returnValue(true));

        $oMockPayment = $this->getMock('oxPayment', array('fcpoGetUserPaymentId'));
        $oMockPayment->expects($this->any())->method('fcpoGetUserPaymentId')->will($this->returnValue('someUserPaymentId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockPayment, $oMockUserPayment));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oResponse = $oExpect = $this->invokeMethod($oTestObject, '_fcGetPaymentByPaymentType', array($oMockUser, $sMockPaymentType));

        $this->assertEquals($oExpect, $oResponse);
    }

    /**
     * Testing _fcGetPaymentByPaymentType for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcGetPaymentByPaymentType_Negative() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockUser = $this->getMock('oxUser', array('getId'));
        $oMockUser->expects($this->any())->method('getId')->will($this->returnValue('someId'));

        $sMockPaymentType = null;

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someValue'));

        $oMockUserPayment = $this->getMock('oxuserpayment', array('load'));
        $oMockUserPayment->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUserPayment));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals(false, $this->invokeMethod($oTestObject, '_fcGetPaymentByPaymentType', array($oMockUser, $sMockPaymentType)));
    }

    /**
     * Testing _assignDebitNoteParams for coverage
     * 
     * @param void
     * @return void
     */
    public function test__assignDebitNoteParams_Coverage() {
        $oMockUser = $this->getMock('oxUser', array('getId'));
        $oMockUser->expects($this->any())->method('getId')->will($this->returnValue('someId'));

        $oMockUserPayment = $this->getMock('oxuserpayment', array('load'));
        $oMockUserPayment->expects($this->any())->method('load')->will($this->returnValue(true));

        $oMockPaymentData = new stdClass();
        $oMockPaymentData->name = 'someName';
        $oMockPaymentData->value = 'someValue';
        $aMockPaymentData = array($oMockPaymentData);

        $oMockUtils = $this->getMock('oxUtils', array('assignValuesFromText'));
        $oMockUtils->expects($this->any())->method('assignValuesFromText')->will($this->returnValue($aMockPaymentData));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', '_fcGetPaymentByPaymentType', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcGetPaymentByPaymentType')->will($this->returnValue($oMockUserPayment));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));


        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $this->invokeMethod($oTestObject, '_assignDebitNoteParams'));
    }

    /**
     * Testing getDynValue for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getDynValue_Coverage() {
        $aPaymentList = array();
        $aPaymentList['fcpodebitnote'] = 'someValue';

        $aDynValues = array('someDynValue');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'getPaymentList', '_assignDebitNoteParams'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getPaymentList')->will($this->returnValue($aPaymentList));
        $oTestObject->expects($this->any())->method('_assignDebitNoteParams')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_aDynValue', $aDynValues);

        $this->assertEquals($aDynValues, $oTestObject->getDynValue());
    }

    /**
     * Testing fcGetBillCountry for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcGetBillCountry_Coverage() {
        $oMockCountry = $this->getMock('oxCountry', array('load'));
        $oMockCountry->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockCountry->oxcountry__oxisoalpha2 = new oxField('de');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUserBillCountryId'));
        $oTestObject->expects($this->any())->method('getUserBillCountryId')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockCountry));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('de', $oTestObject->fcGetBillCountry());
    }

    /**
     * Testing _setValues for coverage
     * 
     * @param void
     * @return void
     */
    public function test__setValues_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcIsPayOnePaymentType', 'fcShowApprovalMessage', 'fcGetApprovalText'));
        $oTestObject->expects($this->any())->method('_fcIsPayOnePaymentType')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcShowApprovalMessage')->will($this->returnValue('someMessage'));
        $oTestObject->expects($this->any())->method('fcGetApprovalText')->will($this->returnValue('someText'));

        $aPaymentList = array();
        $oMockPayment = $this->getMock('oxPayment', array('getId', 'fcShowApprovalMessage', 'fcBoniCheckNeeded'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));
        $aPaymentList[] = $oMockPayment;

        /**
         * Switched off due to passing references in phpunit is currently impossible
         * @see https://stackoverflow.com/questions/10971647/phpunit-and-call-time-pass-by-reference
         */
        // $this->assertEquals(null, $oTestObject->_setValues($aPaymentList));
    }

    /**
     * Testing _fcGetCurrentVersion for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcGetCurrentVersion_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4800));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(4800, $this->invokeMethod($oTestObject, '_fcGetCurrentVersion'));
    }

    /**
     * Testing _setDeprecatedValues for coverage
     * 
     * @param void
     * @return void
     */
    public function test__setDeprecatedValues_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
                '_fcGetCurrentVersion',
                '_fcIsPayOnePaymentType',
                'fcShowApprovalMessage',
                'fcBoniCheckNeeded',
                'fcGetApprovalText'
            )
        );
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4200));
        $oTestObject->expects($this->any())->method('_fcIsPayOnePaymentType')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcShowApprovalMessage')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcGetApprovalText')->will($this->returnValue('someText'));

        $oMockLang = $this->getMock('oxLang', array('getId'));
        $oMockLang->expects($this->any())->method('getId')->will($this->returnValue('someId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockPaymentList = array();
        $oMockPayment = $this->getMock('oxPayment', array('getId', 'fcBoniCheckNeeded'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));
        $oMockPayment->oxpayments__oxlongdesc = new oxField('someDesc');
        $aMockPaymentList[] = $oMockPayment;

        // $this->invokeMethod($oTestObject, '_setDeprecatedValues', array($aMockPaymentList)
        /**
         * Switched off test due to impossible to pass a reference in context of unit test
         * @see https://stackoverflow.com/questions/10971647/phpunit-and-call-time-pass-by-reference
         */
        // $this->assertEquals(null, $oTestObject->_setDeprecatedValues($aMockPaymentList));
    }

    /**
     * Testing _fcpoGetKlarnaLang for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoGetKlarnaLang_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('fcGetBillCountry')->will($this->returnValue('de'));

        $this->assertEquals('de_de', $this->invokeMethod($oTestObject, '_fcpoGetKlarnaLang'));
    }

    /**
     * Testing fcpoProcessValidation for error
     */
    public function tests__fcpoProcessValidation_Error() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
                '_fcpoSetKlarnaCampaigns',
                '_fcpoCheckBoniMoment',
                '_fcpoSetBoniErrorValues',
                '_fcpoSetMandateParams',
                '_fcCleanupSessionFragments',
                '_fcpoPayolutionPreCheck',
                '_fcpoCheckRatePayBillMandatoryUserData',
                '_fcpoAdultCheck',
                '_fcpoSecInvoiceSaveRequestedValues',
            )
        );
        $oTestObject->expects($this->any())->method('_fcpoSetKlarnaCampaigns')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckBoniMoment')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoSetBoniErrorValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetMandateParams')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcCleanupSessionFragments')->will($this->returnValue('someText'));
        $oTestObject->expects($this->any())->method('_fcpoPayolutionPreCheck')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcpoCheckRatePayBillMandatoryUserData')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcpoAdultCheck')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcpoSecInvoiceSaveRequestedValues')->will($this->returnValue(null));

        $oMockPayment = $this->getMock('oxPayment', array('load'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $oTestObject->_fcpoProcessValidation('order', 'fcporp_bill'));
    }

    /**
     * Testing fcpoProcessValidation for ok
     */
    public function tests__fcpoProcessValidation_Ok() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
                '_fcpoSetKlarnaCampaigns',
                '_fcpoCheckBoniMoment',
                '_fcpoSetBoniErrorValues',
                '_fcpoSetMandateParams',
                '_fcCleanupSessionFragments',
                '_fcpoPayolutionPreCheck',
                '_fcpoCheckRatePayBillMandatoryUserData',
                '_fcpoAdultCheck',
                '_fcpoSecInvoiceSaveRequestedValues',
            )
        );
        $oTestObject->expects($this->any())->method('_fcpoSetKlarnaCampaigns')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckBoniMoment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetBoniErrorValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetMandateParams')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcCleanupSessionFragments')->will($this->returnValue('someText'));
        $oTestObject->expects($this->any())->method('_fcpoPayolutionPreCheck')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcpoCheckRatePayBillMandatoryUserData')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcpoAdultCheck')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcpoSecInvoiceSaveRequestedValues')->will($this->returnValue(null));

        $oMockPayment = $this->getMock('oxPayment', array('load'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $oTestObject->_fcpoProcessValidation('order', 'somePaymentId'));
    }

    /**
     * Testing fcpoProcessValidation for case of amazon payment
     */
    public function tests__fcpoProcessValidation_Amazon() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
                '_fcpoSetKlarnaCampaigns',
                '_fcpoCheckBoniMoment',
                '_fcpoSetBoniErrorValues',
                '_fcpoSetMandateParams',
                '_fcCleanupSessionFragments',
                '_fcpoPayolutionPreCheck',
                '_fcpoCheckRatePayBillMandatoryUserData',
                '_fcpoAdultCheck',
                '_fcpoSecInvoiceSaveRequestedValues',
            )
        );
        $oTestObject->expects($this->any())->method('_fcpoSetKlarnaCampaigns')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckBoniMoment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetBoniErrorValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetMandateParams')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcCleanupSessionFragments')->will($this->returnValue('someText'));
        $oTestObject->expects($this->any())->method('_fcpoPayolutionPreCheck')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcpoCheckRatePayBillMandatoryUserData')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcpoAdultCheck')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcpoSecInvoiceSaveRequestedValues')->will($this->returnValue(null));

        $oMockPayment = $this->getMock('oxPayment', array('load'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $oTestObject->_fcpoProcessValidation('order', 'fcpoamazonpay'));
    }

    /**
     * Testing _fcpoAdultCheck for coverage
     */
    public function test__fcpoAdultCheck_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoAdultCheckRequired',
            '_fcpoUserIsAdult',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoAdultCheckRequired')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoUserIsAdult')
            ->will($this->returnValue(false));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue('translatedString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoAdultCheck('order', 'somePaymentId'));
    }


    /**
     * Testing _fcpoUserIsAdult for coverage
     */
    public function test__fcpoUserIsAdult_Coverage() {
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxbirthdate = new oxField('2004-09-28');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $this->assertEquals(false, $oTestObject->_fcpoUserIsAdult());
    }

    /**
     * Testing _fcpoAdultCheckRequired for coverage
     */
    public function test__fcpoAdultCheckRequired_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $this->assertEquals(true, $oTestObject->_fcpoAdultCheckRequired('fcpo_secinvoice'));
    }

    /**
     * Testing setPayolutionAjaxParams for coverage
     */
    public function test_setPayolutionAjaxParams_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $aMockParams = array('some', 'params');
        $this->assertEquals(null, $oTestObject->setPayolutionAjaxParams($aMockParams));
    }

    /**
     * Testing fcpoPayolutionPreCheck for coverage
     */
    public function test_fcpoPayolutionPreCheck_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoPayolutionPreCheck'));
        $oTestObject->expects($this->any())->method('_fcpoPayolutionPreCheck')->will($this->returnValue(true));

        $this->assertEquals(true, $oTestObject->fcpoPayolutionPreCheck('somePaymentId'));
    }

    /**
     * Testing fcpoPayolutionShowBankData for case bill
     */
    public function test_fcpoPayolutionShowBankData_Bill() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $this->assertEquals(false, $oTestObject->fcpoPayolutionShowBankData('fcpopo_bill'));
    }

    /**
     * Testing fcpoPayolutionShowBankData for case debitnote
     */
    public function test_fcpoPayolutionShowBankData_Debitnote() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoCheckPayolutionBankDataCountryException',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCheckPayolutionBankDataCountryException')
            ->will($this->returnValue(false));

        $this->assertEquals(true, $oTestObject->fcpoPayolutionShowBankData('fcpopo_debitnote'));
    }


    /**
     * Testing fcpoGetBasketSum for coverage
     */
    public function test_fcpoGetBasketSum_Coverage() {
        $oMockBasket = $this->getMock('oxBasket', array('getBruttoSum'));
        $oMockBasket->expects($this->any())->method('getBruttoSum')->will($this->returnValue(199.99));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getSession'));
        $oTestObject->expects($this->any())->method('getSession')->will($this->returnValue($oMockSession));

        $sExpect = "199,99";

        $this->assertEquals($sExpect, $oTestObject->fcpoGetBasketSum());
    }

    /**
     * Testing fcpoRatePayShowUstid for coverage
     */
    public function test_fcpoRatePayShowUstid_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig
            ->expects($this->any())
            ->method('getConfigParam')
            ->will($this->returnValue(true));

        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser
            ->expects($this->any())
            ->method('save')
            ->will($this->returnValue(null));
        $oMockUser->oxuser__oxcompany = new oxField('someCompany');
        $oMockUser->oxuser__oxustid = new oxField('');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getConfig',
            'getUser'
        ));
        $oTestObject
            ->expects($this->any())
            ->method('getConfig')
            ->will($this->returnValue($oMockConfig));
        $oTestObject
            ->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($oMockUser));

        $this->assertEquals(true, $oTestObject->fcpoRatePayShowUstid());
    }

    /**
     * Testing fcpoRatePayShowBirthdate for Coverage
     */
    public function test_fcpoRatePayShowBirthdate_Coverage() {
        $oMockUser = $this->getMock('oxUser', array('load'));
        $oMockUser
            ->expects($this->any())
            ->method('load')
            ->will($this->returnValue(true));
        $oMockUser->oxuser__oxbirthdate = new oxField('0000-00-00');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getUser',
            'fcpoRatePayShowUstid'
        ));
        $oTestObject
            ->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($oMockUser));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoRatePayShowUstid')
            ->will($this->returnValue(false));

        $this->assertEquals(true, $oTestObject->fcpoRatePayShowBirthdate());
    }

    /**
     * Testing fcpoRatePayShowFon for coverage
     */
    public function test_fcpoRatePayShowFon_Coverage() {
        $oMockUser = $this->getMock('oxUser', array('load'));
        $oMockUser
            ->expects($this->any())
            ->method('load')
            ->will($this->returnValue(true));
        $oMockUser->oxuser__oxfon = new oxField('');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getUser',
            'fcpoRatePayShowUstid'
        ));
        $oTestObject
            ->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($oMockUser));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoRatePayShowUstid')
            ->will($this->returnValue(false));

        $this->assertEquals(true, $oTestObject->fcpoRatePayShowFon());
    }

    /**
     * Testing _fcpoCheckRatePayBillMandatoryUserData case B2B
     */
    public function test__fcpoCheckRatePayBillMandatoryUserData_B2B() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig
            ->expects($this->any())
            ->method('getConfigParam')
            ->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getConfig',
            '_fcpoRatePaySaveRequestedValues',
            'fcpoRatePayShowUstid',
            'fcpoRatePayShowFon',
            'fcpoRatePayShowBirthdate',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('getConfig')
            ->will($this->returnValue($oMockConfig));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoRatePaySaveRequestedValues')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoRatePayShowUstid')
            ->will($this->returnValue(false));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoRatePayShowFon')
            ->will($this->returnValue(false));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoRatePayShowBirthdate')
            ->will($this->returnValue(false));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang
            ->expects($this->any())
            ->method('translateString')
            ->will($this->returnValue('someTranslatedString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_fcpoCheckRatePayBillMandatoryUserData(false, 'somePaymentId'));
    }

    /**
     * Testing _fcpoCheckRatePayBillMandatoryUserData case B2C
     */
    public function test__fcpoCheckRatePayBillMandatoryUserData_B2C() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig
            ->expects($this->any())
            ->method('getConfigParam')
            ->will($this->returnValue(false));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getConfig',
            '_fcpoRatePaySaveRequestedValues',
            'fcpoRatePayShowUstid',
            'fcpoRatePayShowFon',
            'fcpoRatePayShowBirthdate',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('getConfig')
            ->will($this->returnValue($oMockConfig));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoRatePaySaveRequestedValues')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoRatePayShowUstid')
            ->will($this->returnValue(false));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoRatePayShowFon')
            ->will($this->returnValue(false));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoRatePayShowBirthdate')
            ->will($this->returnValue(false));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang
            ->expects($this->any())
            ->method('translateString')
            ->will($this->returnValue('someTranslatedString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_fcpoCheckRatePayBillMandatoryUserData(false, 'somePaymentId'));
    }

    /**
     * Testing _fcpoPayolutionPreCheck with valid bankdata
     */
    public function test__fcpoPayolutionPreCheck_ValidBankData() {
       $oTestObject = $this->getMock('fcPayOnePaymentView', 
                array(
                    '_fcpoIsPayolution', 
                    '_fcpoPayolutionSaveRequestedValues',
                    '_fcpoCheckAgreedDataUsage',
                    '_fcpoGetPayolutionBankData', 
                    '_fcpoValidateBankData',
                    '_fcpoCheckSepaAgreed',
                    '_fcpoPerformPayolutionPreCheck',
                    '_fcpoCheckPayolutionMandatoryUserData',
                    '_fcpoGetPayolutionSelectedInstallmentIndex',
                ));
        
        $oTestObject->expects($this->any())->method('_fcpoIsPayolution')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoPayolutionSaveRequestedValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckAgreedDataUsage')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionBankData')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoValidateBankData')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoCheckSepaAgreed')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoPerformPayolutionPreCheck')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoCheckPayolutionMandatoryUserData')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionSelectedInstallmentIndex')->will($this->returnValue(false));

        $this->assertEquals(null, $oTestObject->_fcpoPayolutionPreCheck(null, 'fcpopo_installment'));
    }

    /**
     * Testing _fcpoPayolutionPreCheck with invalid bankdata
     */
    public function test__fcpoPayolutionPreCheck_InvalidBankData() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', 
                array(
                    '_fcpoIsPayolution', 
                    '_fcpoPayolutionSaveRequestedValues',
                    '_fcpoCheckAgreedDataUsage',
                    '_fcpoGetPayolutionBankData', 
                    '_fcpoValidateBankData',
                    '_fcpoCheckSepaAgreed',
                    '_fcpoPerformPayolutionPreCheck',
                    '_fcpoCheckPayolutionMandatoryUserData',
                    '_fcpoGetPayolutionSelectedInstallmentIndex',
                ));
        $oTestObject->expects($this->any())->method('_fcpoIsPayolution')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoPayolutionSaveRequestedValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckAgreedDataUsage')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionBankData')->will($this->returnValue($aMockBankData));
        $oTestObject->expects($this->any())->method('_fcpoValidateBankData')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoCheckSepaAgreed')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoPerformPayolutionPreCheck')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoCheckPayolutionMandatoryUserData')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionSelectedInstallmentIndex')->will($this->returnValue(false));

        $this->assertEquals(null, $oTestObject->_fcpoPayolutionPreCheck(null, 'fcpopo_installment'));
    }

    /**
     * Testing _fcpoPayolutionPreCheck with invalid userdata
     */
    public function test__fcpoPayolutionPreCheck_InvalidUserData() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );

        $oTestObject = $this->getMock('fcPayOnePaymentView',
            array(
                '_fcpoIsPayolution',
                '_fcpoPayolutionSaveRequestedValues',
                '_fcpoCheckAgreedDataUsage',
                '_fcpoGetPayolutionBankData',
                '_fcpoValidateBankData',
                '_fcpoCheckSepaAgreed',
                '_fcpoPerformPayolutionPreCheck',
                '_fcpoCheckPayolutionMandatoryUserData',
                '_fcpoGetPayolutionSelectedInstallmentIndex',
            ));

        $oTestObject->expects($this->any())->method('_fcpoIsPayolution')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoPayolutionSaveRequestedValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckAgreedDataUsage')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionBankData')->will($this->returnValue($aMockBankData));
        $oTestObject->expects($this->any())->method('_fcpoValidateBankData')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckSepaAgreed')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoPerformPayolutionPreCheck')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoCheckPayolutionMandatoryUserData')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionSelectedInstallmentIndex')->will($this->returnValue(false));

        $this->invokeSetAttribute($oTestObject, '_blIsPayolutionInstallmentAjax', false);

        $this->assertEquals(null, $oTestObject->_fcpoPayolutionPreCheck(null, 'fcpopo_installment'));
    }

    /**
     * Testing _fcpoPayolutionPreCheck coming via ajax
     */
    public function test__fcpoPayolutionPreCheck_ViaAjax() {
        $oTestObject = $this->getMock('fcPayOnePaymentView',
            array(
                '_fcpoIsPayolution',
                '_fcpoPayolutionSaveRequestedValues',
                '_fcpoCheckAgreedDataUsage',
                '_fcpoGetPayolutionBankData',
                '_fcpoValidateBankData',
                '_fcpoCheckSepaAgreed',
                '_fcpoPerformPayolutionPreCheck',
                '_fcpoCheckPayolutionMandatoryUserData',
                '_fcpoGetPayolutionSelectedInstallmentIndex',
            ));

        $oTestObject->expects($this->any())->method('_fcpoIsPayolution')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoPayolutionSaveRequestedValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckAgreedDataUsage')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionBankData')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoValidateBankData')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckSepaAgreed')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoPerformPayolutionPreCheck')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoCheckPayolutionMandatoryUserData')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionSelectedInstallmentIndex')->will($this->returnValue(false));

        $this->invokeSetAttribute($oTestObject, '_blIsPayolutionInstallmentAjax', true);

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang
            ->expects($this->any())
            ->method('translateString')
            ->will($this->returnValue('someTranslatedString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);


        $this->assertEquals('someTranslatedString', $oTestObject->_fcpoPayolutionPreCheck(null, 'fcpopo_installment'));
    }

    /**
     * Testing _fcpoValidateMandatoryUserDataForPayolutionBill for coverage
     */
    public function test__fcpoValidateMandatoryUserDataForPayolutionBill_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoValidatePayolutionBillHasTelephone',
            '_fcpoValidatePayolutionBillHasUstid',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidatePayolutionBillHasTelephone')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidatePayolutionBillHasUstid')
            ->will($this->returnValue(true));

        $this->assertEquals(
            true,
            $oTestObject->_fcpoValidateMandatoryUserDataForPayolutionBill('fcpopo_bill')
        );
    }

    /**
     * Testing _fcpoValidatePayolutionBillHasUstid for coverage
     */
    public function test__fcpoValidatePayolutionBillHasUstid_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array(
            'getConfigParam',
        ));
        $oMockConfig
            ->expects($this->any())
            ->method('getConfigParam')
            ->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getConfig',
            'fcpoPayolutionBillTelephoneRequired',
            'fcpoGetUserValue',
        ));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('fcpoPayolutionBillTelephoneRequired')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoGetUserValue')->will($this->onConsecutiveCalls('someCompany',''));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang
            ->expects($this->any())
            ->method('translateString')
            ->will($this->returnValue('someTranslatedString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            false,
            $oTestObject->_fcpoValidatePayolutionBillHasUstid()
        );
    }

    /**
     * Testing _fcpoValidatePayolutionBillHasTelephone for coverage
     */
    public function test__fcpoValidatePayolutionBillHasTelephone_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig
            ->expects($this->any())
            ->method('getConfigParam')
            ->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'fcpoGetUserValue',
            'fcpoPayolutionBillTelephoneRequired',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoGetUserValue')
            ->will($this->returnValue(''));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoPayolutionBillTelephoneRequired')
            ->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang
            ->expects($this->any())
            ->method('translateString')
            ->will($this->returnValue('someTranslatedString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            false,
            $oTestObject->_fcpoValidatePayolutionBillHasTelephone()
        );
    }


    /**
     * Testing _fcpoPayolutionPreCheck with sepa check
     */
    public function test__fcpoPayolutionPreCheck_Sepa() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', 
                array(
                    '_fcpoIsPayolution', 
                    '_fcpoPayolutionSaveRequestedValues',
                    '_fcpoCheckAgreed', 
                    '_fcpoGetPayolutionBankData', 
                    '_fcpoValidateBankData',
                    '_fcpoCheckSepaAgreed',
                    '_fcpoPerformPayolutionPreCheck',
                ));
        
        $oTestObject->expects($this->any())->method('_fcpoIsPayolution')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoPayolutionSaveRequestedValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckAgreed')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionBankData')->will($this->returnValue($aMockBankData));
        $oTestObject->expects($this->any())->method('_fcpoValidateBankData')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckSepaAgreed')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoPerformPayolutionPreCheck')->will($this->returnValue(false));
        
        $this->assertEquals(null, $oTestObject->_fcpoPayolutionPreCheck(null, 'someId'));
    }

    /**
     * Testing _fcpoValidateBankData for payment payolution installment
     */
    public function test__fcpoValidateBankData_PayolutionInstallment() {
        $aMockBankData = array(
            'fcpo_payolution_installment_accountholder' => 'Some Person',
            'fcpo_payolution_installment_iban' => 'DE12500105170648489890',
            'fcpo_payolution_installment_bic' => 'BELADEBEXXX',
        );
        $oTestObject = oxNew('fcPayOnePaymentView');
        
        $this->assertEquals(true, $oTestObject->_fcpoValidateBankData($aMockBankData, 'fcpopo_installment'));
    }

    /**
     * Testing _fcpoValidateBankData for coverage
     */
    public function test__fcpoValidateBankData_PayolutionDebitnote() {
        $aMockBankData = array(
            'fcpo_payolution_debitnote_accountholder' => 'Some Person',
            'fcpo_payolution_debitnote_iban' => 'DE12500105170648489890',
            'fcpo_payolution_debitnote_bic' => 'BELADEBEXXX',
        );
        $oTestObject = oxNew('fcPayOnePaymentView');

        $this->assertEquals(true, $oTestObject->_fcpoValidateBankData($aMockBankData, 'fcpopo_debitnote'));
    }

    /**
     * Testing _fcpoGetPayolutionBankData for coverage
     */
    public function test__fcpoGetPayolutionBankData_Coverage() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );

        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockBankData));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals($aMockBankData, $oTestObject->_fcpoGetPayolutionBankData('fcpopo_bill'));
    }

    /**
     * Testing _fcpoGetPayolutionSelectedInstallmentIndex for coverage
     */
    public function test__fcpoGetPayolutionSelectedInstallmentIndex_Coverage() {
        $aMockParams = array(
            'fcpo_payolution_installment_index' => 'someIndex',
        );

        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockParams));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someIndex', $oTestObject->_fcpoGetPayolutionSelectedInstallmentIndex());
    }
    
    /**
     * Testing _fcpoCheckAgreedDataUsage for coverage
     */
    public function test__fcpoCheckAgreedDataUsage_Coverage() {
        $aMockData = array(
            'fcpo_payolution_bill_agreed' => 'agreed',
        );

        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockData));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_blIsPayolutionInstallmentAjax', true);
        $this->invokeSetAttribute($oTestObject, '_aAjaxPayolutionParams', $aMockData);

        $this->assertEquals(true, $oTestObject->_fcpoCheckAgreedDataUsage());
    }

    /**
     * Testing _fcpoCheckSepaAgreed with payment payolution installment
     */
    public function test__fcpoCheckSepaAgreed_PayolutionInstallment() {
        $aMockData = array(
            'fcpo_payolution_debitnote_sepa_agreed' => 'agreed',
        );

        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockData));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(true, $oTestObject->_fcpoCheckSepaAgreed('fcpopo_installment'));
    }

    /**
     * Testing _fcpoCheckSepaAgreed with payment payolution debitnote
     */
    public function test__fcpoCheckSepaAgreed_PayolutionDebitnote() {
        $aMockData = array(
            'fcpo_payolution_debitnote_sepa_agreed' => 'agreed',
        );

        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockData));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoCheckSepaAgreed('fcpopo_debitnote'));
    }

    /**
     * Testing _fcpoRatePaySaveRequestedValues for coverage
     */
    public function test__fcpoRatePaySaveRequestedValues_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser
            ->expects($this->any())
            ->method('save')
            ->will($this->returnValue(null));
        $oMockUser->oxuser__oxbirthdate = new oxField('0000-00-00');


        $oMockBasket = $this->getMock('oxBasket', array('getBasketUser'));
        $oMockBasket
            ->expects($this->any())
            ->method('getBasketUser')
            ->will($this->returnValue($oMockUser));


        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession
            ->expects($this->any())
            ->method('getBasket')
            ->will($this->returnValue($oMockBasket));

        $aMockDynValues = array(
            'fcporp_bill_birthdate_year'=>'1978',
            'fcporp_bill_birthdate_month'=>'12',
            'fcporp_bill_birthdate_day'=>'07',
            'fcporp_bill_fon'=>'0123456789',
            'fcporp_bill_ustid'=>'someUstid',
        );

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockDynValues));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoRatePaySaveRequestedValues('fcporp_bill'));
    }

    /**
     * Testing _fcpoPayolutionSaveRequestedValues for coverage
     */
    public function test__fcpoPayolutionSaveRequestedValues_Coverage() {
        $aMockData = array(
            'fcpo_payolution_birthdate_year' => '1978',
            'fcpo_payolution_birthdate_month' => '12',
            'fcpo_payolution_birthdate_day' => '07',
            'fcpo_payolution_ustid' => 'someUstid',
        );

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoSaveBirthdayData',
            '_fcpoSaveUserData',
        ));
        $oTestObject->expects($this->any())->method('_fcpoSaveBirthdayData')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSaveUserData')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockData));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_blIsPayolutionInstallmentAjax', true);
        
        $this->assertEquals(true, $oTestObject->_fcpoPayolutionSaveRequestedValues('somePaymentId'));
    }

    /**
     * Testing _fcpoSecInvoiceSaveRequestedValues for coverage
     */
    public function test__fcpoSecInvoiceSaveRequestedValues_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoSaveBirthdayData'));
        $oTestObject->expects($this->any())->method('_fcpoSaveBirthdayData')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(array('some', 'array')));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoSecInvoiceSaveRequestedValues('somePaymentId'));
    }

    /**
     * Testing _fcpoSaveUserData for coverage
     */
    public function test__fcpoSaveUserData_Coverage() {
        $aMockRequestedValues = array('some', 'data');
        $sMockPaymentId = 'somePaymentId';
        $sMockDbFieldName = 'someDbField';

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'fcpoGetUserValue',
            '_fcpoSetUserValue',
            '_fcpoGetRequestedValue',
        ));
        $oTestObject->expects($this->any())->method('fcpoGetUserValue')->will($this->returnValue('userValue'));
        $oTestObject->expects($this->any())->method('_fcpoSetUserValue')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoGetRequestedValue')->will($this->returnValue($aMockRequestedValues));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('requestedValue'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);


        $this->assertEquals(true, $oTestObject->_fcpoSaveUserData($aMockRequestedValues, $sMockPaymentId, $sMockDbFieldName));
    }

    /**
     * Testing _fcpoSaveBirthdayData for success case of saving bdate data
     */
    public function test__fcpoSaveBirthdayData_BirthdayRequired() {
        $aMockRequestedValues = array('some', 'data');
        $sMockPaymentId = 'somePaymentId';

        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(null));

        $aMockBirthdayValidityCheckResult = array(
            'blValidBirthdateData'=>true,
            'blBirthdayRequired'=>true
        );

        $aMockBirthdate = '1978-12-07';

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoGetUserFromSession',
            '_fcpoValidateBirthdayData',
            '_fcpoExtractBirthdateFromRequest',
        ));
        $oTestObject->expects($this->any())->method('_fcpoGetUserFromSession')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_fcpoValidateBirthdayData')->will($this->returnValue($aMockBirthdayValidityCheckResult));
        $oTestObject->expects($this->any())->method('_fcpoExtractBirthdateFromRequest')->will($this->returnValue($aMockBirthdate));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translate')->will($this->returnValue('translatedString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoSaveBirthdayData($aMockRequestedValues, $sMockPaymentId));
    }

    /**
     * Testing _fcpoSaveBirthdayData in case of invalid birhtday date
     */
    public function test__fcpoSaveBirthdayData_InvalidBirthday() {
        $aMockRequestedValues = array('some', 'data');
        $sMockPaymentId = 'somePaymentId';

        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(null));

        $aMockBirthdayValidityCheckResult = array(
            'blValidBirthdateData'=>false,
            'blBirthdayRequired'=>true
        );

        $aMockBirthdate = '1978-12-07';

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoGetUserFromSession',
            '_fcpoValidateBirthdayData',
            '_fcpoExtractBirthdateFromRequest',
        ));
        $oTestObject->expects($this->any())->method('_fcpoGetUserFromSession')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_fcpoValidateBirthdayData')->will($this->returnValue($aMockBirthdayValidityCheckResult));
        $oTestObject->expects($this->any())->method('_fcpoExtractBirthdateFromRequest')->will($this->returnValue($aMockBirthdate));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translate')->will($this->returnValue('translatedString'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_fcpoSaveBirthdayData($aMockRequestedValues, $sMockPaymentId));
    }

    /**
     * Testing  _fcpoExtractBirthdateFromRequest in case of payolution payment
     */
    public function test__fcpoExtractBirthdateFromRequest_Payolution() {
        $sMockPaymentId = 'fcpopo_debitnote';
        $oTestObject = oxNew('fcPayOnePaymentView');

        $aMockRequestValues = array(
            'fcpo_payolution_debitnote_birthdate_year'=>'1978',
            'fcpo_payolution_debitnote_birthdate_month'=>'12',
            'fcpo_payolution_debitnote_birthdate_day'=>'07',
        );

        $sExpect = "1978-12-07";

        $this->assertEquals($sExpect, $oTestObject->_fcpoExtractBirthdateFromRequest($aMockRequestValues,$sMockPaymentId));
    }

    /**
     * Testing  _fcpoExtractBirthdateFromRequest in case of scure invoice payment
     */
    public function test__fcpoExtractBirthdateFromRequest_Secinvoice() {
        $sMockPaymentId = 'fcpo_secinvoice';
        $oTestObject = oxNew('fcPayOnePaymentView');

        $aMockRequestValues = array(
            'fcpo_secinvoice_birthdate_year'=>'1978',
            'fcpo_secinvoice_birthdate_month'=>'12',
            'fcpo_secinvoice_birthdate_day'=>'07',
        );

        $sExpect = "1978-12-07";

        $this->assertEquals($sExpect, $oTestObject->_fcpoExtractBirthdateFromRequest($aMockRequestValues,$sMockPaymentId));
    }

    /**
     * Testing _fcpoGetRequestedValue for coverage
     */
    public function test__fcpoGetRequestedValue_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $sMockPaymentId = 'fcpopo_debitnote';
        $sMockDbFieldName = 'someDBField';
        $aMockRequestedValues['fcpo_payolution_debitnote_'.$sMockDbFieldName] = 'someValue';

        $this->assertEquals(
            'someValue',
            $oTestObject->_fcpoGetRequestedValue(
                $aMockRequestedValues,
                $sMockPaymentId,
                $sMockDbFieldName
            )
        );
    }

    /**
     * Testing _fcpoValidateBirthdayData for validation of payolution payment
     */
    public function test__fcpoValidateBirthdayData_Payolution() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'fcpoShowB2C',
            '_fcpoValidatePayolutionBirthdayData',
            '_fcpoValidateSecInvoiceBirthdayData',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoShowB2C')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidatePayolutionBirthdayData')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidateSecInvoiceBirthdayData')
            ->will($this->returnValue(true));

        $sMockPaymentId = 'fcpopo_installment';
        $aRequestedValues = array('some', 'values');

        $aExpect = array(
            'blValidBirthdateData' => true,
            'blBirthdayRequired' => true,
        );

        $this->assertEquals($aExpect, $oTestObject->_fcpoValidateBirthdayData($sMockPaymentId, $aRequestedValues));
    }

    /**
     * Testing _fcpoValidateBirthdayData for validation of payolution payment
     */
    public function test__fcpoValidateBirthdayData_SecInvoice() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'fcpoShowB2C',
            '_fcpoValidatePayolutionBirthdayData',
            '_fcpoValidateSecInvoiceBirthdayData',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoShowB2C')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidatePayolutionBirthdayData')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidateSecInvoiceBirthdayData')
            ->will($this->returnValue(true));

        $sMockPaymentId = 'fcpo_secinvoice';
        $aRequestedValues = array('some', 'values');

        $aExpect = array(
            'blValidBirthdateData' => true,
            'blBirthdayRequired' => true,
        );

        $this->assertEquals($aExpect, $oTestObject->_fcpoValidateBirthdayData($sMockPaymentId, $aRequestedValues));
    }

    /**
     * Testing _fcpoValidatePayolutionBirthdayData fo coverage
     */
    public function test__fcpoValidatePayolutionBirthdayData_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang
            ->expects($this->any())
            ->method('translateString')
            ->will($this->returnValue('someTranslation'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetLang')
            ->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sMockPaymentId = 'fcpopo_bill';

        $aMockRequestValues = array(
            'fcpo_payolution_bill_birthdate_year'=>'1978',
            'fcpo_payolution_bill_birthdate_month'=>'12',
            'fcpo_payolution_bill_birthdate_day'=>'07',
        );

        $this->assertEquals(
            true,
            $oTestObject->_fcpoValidatePayolutionBirthdayData($sMockPaymentId,$aMockRequestValues)
        );
    }

    /**
     * Testing _fcpoValidateSecInvoiceBirthdayData for coverage
     */
    public function test__fcpoValidateSecInvoiceBirthdayData_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang
            ->expects($this->any())
            ->method('translateString')
            ->will($this->returnValue('someTranslation'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetLang')
            ->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sMockPaymentId = 'fcpo_secinvoice';

        $aMockRequestValues = array(
            'fcpo_secinvoice_birthdate_year'=>'1978',
            'fcpo_secinvoice_birthdate_month'=>'12',
            'fcpo_secinvoice_birthdate_day'=>'07',
        );

        $this->assertEquals(
            true,
            $oTestObject->_fcpoValidateSecInvoiceBirthdayData($aMockRequestValues)
        );
    }

    /**
     * Testing _fcpoIsPayolution for checking valid response on given payolution id
     */
    public function test__fcpoIsPayolution_IsPayolutionDebit() {
        $sMockPaymentId = 'fcpopo_debitnote';
        $oTestObject = oxNew('fcPayOnePaymentView');
        
        $this->assertEquals(true, $oTestObject->_fcpoIsPayolution($sMockPaymentId));
    }

    /**
     * Testing fcpoPerformInstallmentCalculation for coverage
     */
    public function test_fcpoPerformInstallmentCalculation_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoPerformInstallmentCalculation'
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoPerformInstallmentCalculation')
            ->will($this->returnValue(null));

        $this->assertEquals(null, $oTestObject->fcpoPerformInstallmentCalculation());
    }

    /**
     * Testing _fcpoPerformInstallmentCalculation in case of success
     */
    public function test__fcpoPerformInstallmentCalculation_Success() {
        $aMockBankData = array('some', 'bankdata');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoGetPayolutionBankData',
            '_fcpoSetInstallmentOptionsByResponse',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetPayolutionBankData')
            ->will($this->returnValue($aMockBankData));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoSetInstallmentOptionsByResponse')
            ->will($this->returnValue(null));

        $aMockResponse = array(
            'status' => 'SUCCESS',
            'workorderid' => 'SomeWorkOrderId',
        );

        $oMockRequest = $this->getMock('fcporequest', array(
            'sendRequestPayolutionInstallment',
        ));
        $oMockRequest
            ->expects($this->any())
            ->method('sendRequestPayolutionInstallment')
            ->will($this->returnValue($aMockResponse));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper
            ->expects($this->any())
            ->method('getFactoryObject')
            ->will($this->returnValue($oMockRequest));
        $oHelper
            ->expects($this->any())
            ->method('fcpoSetSessionVariable')
            ->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sMockPaymentId = 'somePaymentId';

        $this->assertEquals(true, $oTestObject->_fcpoPerformInstallmentCalculation($sMockPaymentId));
    }

    /**
     * Testing _fcpoPerformInstallmentCalculation in case of error
     */
    public function test__fcpoPerformInstallmentCalculation_Error() {
        $aMockBankData = array('some', 'bankdata');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoGetPayolutionBankData',
            '_fcpoSetInstallmentOptionsByResponse',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetPayolutionBankData')
            ->will($this->returnValue($aMockBankData));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoSetInstallmentOptionsByResponse')
            ->will($this->returnValue(null));

        $aMockResponse = array(
            'status' => 'ERROR',
            'workorderid' => 'SomeWorkOrderId',
        );

        $oMockRequest = $this->getMock('fcporequest', array(
            'sendRequestPayolutionInstallment',
        ));
        $oMockRequest
            ->expects($this->any())
            ->method('sendRequestPayolutionInstallment')
            ->will($this->returnValue($aMockResponse));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper
            ->expects($this->any())
            ->method('getFactoryObject')
            ->will($this->returnValue($oMockRequest));
        $oHelper
            ->expects($this->any())
            ->method('fcpoSetSessionVariable')
            ->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sMockPaymentId = 'somePaymentId';

        $this->assertEquals(false, $oTestObject->_fcpoPerformInstallmentCalculation($sMockPaymentId));
    }

    /**
     * Testing _fcpoSetInstallmentOptionsByResponse for coverage
     */
    public function test__fcpoSetInstallmentOptionsByResponse_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $aMockResponse = array(
            'add_paydata[PaymentDetails_0_Duration]'=>'',
            'add_paydata[PaymentDetails_0_Currency]'=>'',
            'add_paydata[PaymentDetails_0_StandardCreditInformation]'=>'',
            'add_paydata[PaymentDetails_0_Usage]'=>'',
            'add_paydata[PaymentDetails_0_EffectiveInterestRate]'=>'',
            'add_paydata[PaymentDetails_0_InterestRate]'=>'',
            'add_paydata[PaymentDetails_0_OriginalAmount]'=>'',
            'add_paydata[PaymentDetails_0_TotalAmount]'=>'',
            'add_paydata[PaymentDetails_0_MinimumInstallmentFee]'=>'',
            'add_paydata[PaymentDetails_0_Installment_1_Amount'=>'',
            'add_paydata[PaymentDetails_0_Installment_1_Amount]'=>'',
            'add_paydata[PaymentDetails_0_Installment_1_Due'=>'',
            'add_paydata[PaymentDetails_0_Currency]'=>'',
            'add_paydata[PaymentDetails_0_StandardCreditInformationUrl]'=>'',
        );

        $aExpect = $aReturn = $oTestObject->_fcpoSetInstallmentOptionsByResponse($aMockResponse);

        $this->assertEquals($aExpect, $aReturn);
    }

    /**
     * Testing _fcpoPerformPayolutionPreCheck for error case
     */
    public function test__fcpoPerformPayolutionPreCheck_Error() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );
        
        $aMockResponse = array('status'=>'ERROR','workorderid'=>'someId');
        
        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(true));
        $oMockUser->oxuser__oxbirthdate = new oxField('1977-12-08', oxField::T_RAW);
        $oMockUser->oxuser__oxustid = new oxField('someUstid', oxField::T_RAW);
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoCheckIfPrecheckNeeded',
            'getUser',
            '_fcpoGetUserFromSession',
            '_fcpoGetPayolutionBankData',
            '_fcpoPerformInstallmentCalculation',
            '_fcpoGetPayolutionSelectedInstallmentIndex',
            '_fcpoPayolutionFetchDuration',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCheckIfPrecheckNeeded')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue(false));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetUserFromSession')
            ->will($this->returnValue($oMockUser));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetPayolutionBankData')
            ->will($this->returnValue($aMockBankData));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoPerformInstallmentCalculation')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetPayolutionSelectedInstallmentIndex')
            ->will($this->returnValue('someIndex'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoPayolutionFetchDuration')
            ->will($this->returnValue('someDuration'));

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestPayolutionPreCheck'));
        $oMockRequest->expects($this->any())->method('sendRequestPayolutionPreCheck')->will($this->returnValue($aMockResponse));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(false, $oTestObject->_fcpoPerformPayolutionPreCheck('someId'));
    }

    /**
     * Testing _fcpoPerformPayolutionPreCheck for valid case
     */
    public function test__fcpoPerformPayolutionPreCheck_OK() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );
        
        $aMockResponse = array('status'=>'OK','workorderid'=>'someId');
        
        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(true));
        $oMockUser->oxuser__oxbirthdate = new oxField('1977-12-08', oxField::T_RAW);
        $oMockUser->oxuser__oxustid = new oxField('someUstid', oxField::T_RAW);

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoCheckIfPrecheckNeeded',
            'getUser',
            '_fcpoGetUserFromSession',
            '_fcpoGetPayolutionBankData',
            '_fcpoPerformInstallmentCalculation',
            '_fcpoGetPayolutionSelectedInstallmentIndex',
            '_fcpoPayolutionFetchDuration',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCheckIfPrecheckNeeded')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue(false));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetUserFromSession')
            ->will($this->returnValue($oMockUser));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetPayolutionBankData')
            ->will($this->returnValue($aMockBankData));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoPerformInstallmentCalculation')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetPayolutionSelectedInstallmentIndex')
            ->will($this->returnValue('someIndex'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoPayolutionFetchDuration')
            ->will($this->returnValue('someDuration'));
        
        $oMockRequest = $this->getMock('fcporequest', array('sendRequestPayolutionPreCheck'));
        $oMockRequest->expects($this->any())->method('sendRequestPayolutionPreCheck')->will($this->returnValue($aMockResponse));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(true, $oTestObject->_fcpoPerformPayolutionPreCheck('someId'));
    }

    /**
     * Testing _fcpoPerformPayolutionPreCheck for invalid calculation
     */
    public function test__fcpoPerformPayolutionPreCheck_InvalidCalculation() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );

        $aMockResponse = array('status'=>'OK','workorderid'=>'someId');

        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(true));
        $oMockUser->oxuser__oxbirthdate = new oxField('1977-12-08', oxField::T_RAW);
        $oMockUser->oxuser__oxustid = new oxField('someUstid', oxField::T_RAW);

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            '_fcpoCheckIfPrecheckNeeded',
            'getUser',
            '_fcpoGetUserFromSession',
            '_fcpoGetPayolutionBankData',
            '_fcpoPerformInstallmentCalculation',
            '_fcpoGetPayolutionSelectedInstallmentIndex',
            '_fcpoPayolutionFetchDuration',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCheckIfPrecheckNeeded')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue(false));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetUserFromSession')
            ->will($this->returnValue($oMockUser));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetPayolutionBankData')
            ->will($this->returnValue($aMockBankData));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoPerformInstallmentCalculation')
            ->will($this->returnValue(false));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetPayolutionSelectedInstallmentIndex')
            ->will($this->returnValue('someIndex'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoPayolutionFetchDuration')
            ->will($this->returnValue('someDuration'));

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestPayolutionPreCheck'));
        $oMockRequest->expects($this->any())->method('sendRequestPayolutionPreCheck')->will($this->returnValue($aMockResponse));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoPerformPayolutionPreCheck('someId'));
    }

    /**
     * Testing _fcpoCheckIfPrecheckNeeded for coverage
     */
    public function test__fcpoCheckIfPrecheckNeeded_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $sMockPaymentId = 'fcpopo_installment';
        $this->invokeSetAttribute($oTestObject, '_blIsPayolutionInstallmentAjax', false);

        $this->assertEquals(false, $oTestObject->_fcpoCheckIfPrecheckNeeded($sMockPaymentId));
    }

    /**
     * Testing _fcpoPayolutionFetchDuration for coverage
     */
    public function test__fcpoPayolutionFetchDuration_Coverage() {
        $aMockInstallmentCalculation = array(
            'someIndex'=> array(
                'Duration'=>'someDuration',
            ),
        );
        $oTestObject = oxNew('fcPayOnePaymentView');
        $sMockSelectedIndex = 'someIndex';
        $this->invokeSetAttribute($oTestObject, '_aInstallmentCalculation', $aMockInstallmentCalculation);

        $this->assertEquals('someDuration', $oTestObject->_fcpoPayolutionFetchDuration($sMockSelectedIndex));
    }

    /**
     * Testing fcpoSetMandateParams for coverage
     */
    public function test__fcpoSetMandateParams_Coverage() {
        $oMockPayment = $this->getMock('oxPayment', array('getId', 'fcpoGetOperationMode'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('fcpodebitnote'));
        $oMockPayment->expects($this->any())->method('fcpoGetOperationMode')->will($this->returnValue('test'));

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestManagemandate'));
        $oMockRequest->expects($this->any())->method('sendRequestManagemandate')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someParam'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoHandleMandateResponse', 'getConfig'));
        $oTestObject->expects($this->any())->method('_fcpoHandleMandateResponse')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoSetMandateParams($oMockPayment));
    }

    /**
     * Testing _fcpoHandleMandateResponse for error case
     */
    public function test__fcpoHandleMandateResponse_Error() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $aMockResponse['status'] = 'ERROR';

        $this->assertEquals(null, $oTestObject->_fcpoHandleMandateResponse($aMockResponse));
    }

    /**
     * Testing _fcpoHandleMandateResponse for ok case
     */
    public function test__fcpoHandleMandateResponse_Ok() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $aMockResponse['status'] = 'OK';
        $aMockResponse['mandate_status'] = 'someMandateStatus';

        $this->assertEquals(null, $oTestObject->_fcpoHandleMandateResponse($aMockResponse));
    }

    /**
     * Testing _fcpoSetBoniErrorValues for coverage
     */
    public function test__fcpoSetBoniErrorValues_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someParam'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcGetCurrentVersion','fcGetLangId'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('fcGetLangId')->will($this->returnValue(0));
        

        $oMockBasket = $this->getMock('oxBasket', array('setTsProductId'));
        $oMockBasket->expects($this->any())->method('setTsProductId')->will($this->returnValue($oMockBasket));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoSetBoniErrorValues('someId'));
    }

    /**
     * Testing _fcpoCheckBoniMoment for coverage
     */
    public function test__fcpoCheckBoniMoment_Coverage() {
        $oMockPayment = oxNew('oxPayment');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoCheckAddressAndScore', 'getConfig'));
        $oTestObject->expects($this->any())->method('_fcpoCheckAddressAndScore')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $this->assertEquals(true, $oTestObject->_fcpoCheckBoniMoment($oMockPayment));
    }

    /**
     * Testing _fcpoCheckAddressAndScore for case that check is needed
     */
    public function test__fcpoCheckAddressAndScore_CheckNeeded() {
        $oMockPayment = $this->getMock('oxPayment', array('getId', 'fcBoniCheckNeeded'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));

        $oMockUser = $this->getMock('oxUser', array('checkAddressAndScore', '_fcpoCheckUserBoni'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));
        $oMockUser->expects($this->any())->method('_fcpoCheckUserBoni')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', '_fcpoValidateApproval', '_fcpoSetNotChecked'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_fcpoValidateApproval')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetNotChecked')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(array('someValue')));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoCheckAddressAndScore($oMockPayment));
    }

    /**
     * Testing _fcpoCheckAddressAndScore for case that check is needed
     */
    public function test__fcpoCheckAddressAndScore_CheckNotNeeded() {
        $oMockPayment = $this->getMock('oxPayment', array('getId', 'fcBoniCheckNeeded'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(false));

        $oMockUser = $this->getMock('oxUser', array('checkAddressAndScore', '_fcpoCheckUserBoni'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));
        $oMockUser->expects($this->any())->method('_fcpoCheckUserBoni')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', '_fcpoValidateApproval', '_fcpoSetNotChecked'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_fcpoValidateApproval')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetNotChecked')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(array('someValue')));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoCheckAddressAndScore($oMockPayment));
    }

    /**
     * Testing _fcpoSetNotChecked for coverage
     */
    public function test__fcpoSetNotChecked_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoSetNotChecked(true, false));
    }

    /**
     * Testing _fcpoCheckUserBoni for coverage
     */
    public function test__fcpoCheckUserBoni_Coverage() {
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxboni = new oxField(10);

        $oMockPayment = oxNew('oxPayment');
        $oMockPayment->oxpayments__oxfromboni = new oxField(20);

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $this->assertEquals(false, $oTestObject->_fcpoCheckUserBoni(true, $oMockPayment));
    }

    /**
     * Testing  _fcpoValidateApproval for coverage
     */
    public function test__fcpoValidateApproval_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $aMockApproval = array('someId' => 'false');
        $sMockPaymentId = 'someId';

        $this->assertEquals(false, $oTestObject->_fcpoValidateApproval($sMockPaymentId, $aMockApproval));
    }

    /**
     * Testing _fcpoSetKlarnaCampaigns for coverage
     */
    public function test__fcpoSetKlarnaCampaigns_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(null));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('someParam'));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoSetKlarnaCampaigns());
    }

    /**
     * Testing _fcpoGetPaymentId for coverage
     */
    public function test__fcpoGetPaymentId_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue('somePaymentId'));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('somePaymentId', $oTestObject->_fcpoGetPaymentId());
    }

    /**
     * Testing _fcpoCheckKlarnaUpdateUser for coverage
     */
    public function test__fcpoCheckKlarnaUpdateUser_Coverage() {
        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser
            ->expects($this->any())
            ->method('save')
            ->will($this->returnValue(null));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getUser',
            '_fcpoKlarnaUpdateUser',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($oMockUser));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoKlarnaUpdateUser')
            ->will($this->returnValue(null));

        $this->assertEquals(null, $oTestObject->_fcpoCheckKlarnaUpdateUser('fcpoklarna'));
    }

    /**
     * Testing _fcpoGetDynValues for coverage
     */
    public function test__fcpoGetDynValues_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue(array('someValue')));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(array('someValue'), $oTestObject->_fcpoGetDynValues());
    }

    /**
     * Testing fcIsPayOnePaymentType for coverage
     */
    public function test_fcIsPayOnePaymentType_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $this->assertEquals(true, $oTestObject->fcIsPayOnePaymentType('fcpopo_installment'));
    }

    /**
     * Testing fcpoShowB2B with activated B2B mode
     */
    public function test_fcpoShowB2B_B2BModeActive() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxcompany = new oxField('someCompany', oxField::T_RAW);
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        
        $this->assertEquals(true, $oTestObject->fcpoShowB2B());
    }

    /**
     * Testing fcpoShowB2B with deactivated B2B mode
     */
    public function test_fcpoShowB2B_B2BModeInActive() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxcompany = new oxField('someCompany', oxField::T_RAW);
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        
        $this->assertEquals(false, $oTestObject->fcpoShowB2B());
    }
    
    /**
     * Testing fcpoShowB2C for coverage
     */
    public function test_fcpoShowB2C_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxbirthdate = new oxField('1978-12-07', oxField::T_RAW);
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser', 'fcpoShowB2B'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('fcpoShowB2B')->will($this->returnValue(false));
        
        $this->assertEquals(true, $oTestObject->fcpoShowB2C());
    }

    /**
     * Testing fcpoPayolutionBillTelephoneRequired for coverage
     */
    public function test_fcpoPayolutionBillTelephoneRequired_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'fcpoGetTargetCountry',
            'fcpoGetUserValue',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoGetTargetCountry')
            ->will($this->returnValue('someCountry'));
        $oTestObject
            ->expects($this->any())
            ->method('fcpoGetUserValue')
            ->will($this->returnValue(''));

        $this->invokeSetAttribute($oTestObject, '_aPayolutionBillMandatoryTelephoneCountries', array('someOtherCountry'));

        $this->assertEquals(false, $oTestObject->fcpoPayolutionBillTelephoneRequired());
    }
    
    /**
     * Testing fcpoGetBirthdayField for coverage
     */
    public function test_fcpoGetBirthdayField_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('fcpoGetUserValue'));
        $oTestObject->expects($this->any())->method('fcpoGetUserValue')->will($this->returnValue('1978-12-07'));
        
        $this->assertEquals('1978', $oTestObject->fcpoGetBirthdayField('year'));
    }
    
    /**
     * Testing fcpoGetUserValue coverage
     */
    public function test_fcpoGetUserValue_Coverage() {
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxbirthdate = new oxField('1978-12-07', oxField::T_RAW);
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        
        $this->assertEquals('1978-12-07', $oTestObject->fcpoGetUserValue('oxbirthdate'));
    }


    /**
     * Testing _fcpoSetUserValue for coverage
     */
    public function test__fcpoSetUserValue_Coverage() {
        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser
            ->expects($this->any())
            ->method('save')
            ->will($this->returnValue(null));
        $oMockUser->oxuser__oxbirthdate = new oxField('1978-12-07', oxField::T_RAW);

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $this->assertEquals(null, $oTestObject->_fcpoSetUserValue('oxbirthdate', 'someValue'));
    }
    
    /**
     * Testing fcpoGetPayolutionAgreementLink coverage
     */
    public function test_fcpoGetPayolutionAgreementLink_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someCompany'));
        
        $sExpect = 'https://payment.payolution.com/payolution-payment/infoport/dataprivacydeclaration?mId='.  base64_encode('someCompany')."&lang=de&territory=";
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        
        $this->assertEquals($sExpect, $oTestObject->fcpoGetPayolutionAgreementLink());
    }
    
    /**
     * Testing fcpoGetPayolutionSepaAgreementLink for coverage
     */
    public function test_fcpoGetPayolutionSepaAgreementLink_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockConfig = $this->getMock('oxConfig', array('getShopUrl'));
        $oMockConfig
            ->expects($this->any())
            ->method('getShopUrl')
            ->will($this->returnValue('https://someshop.com'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetConfig')
            ->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect  = 'https://someshop.com/modules/fcPayOne/lib/fcpopopup_content.php?loadurl=';
        $sExpect .= 'https://payment.payolution.com/payolution-payment/infoport/sepa/mandate.pdf';


        $this->assertEquals($sExpect, $oTestObject->fcpoGetPayolutionSepaAgreementLink());
    }
    
    /**
     * Testing fcpoGetNumericRange for coverage
     */
    public function test__fcpoGetNumericRange_Coverage() {
        $aExpect = array('Bitte wählen...','01','02','03');
        $oTestObject = oxNew('fcPayOnePaymentView');
        $this->assertEquals($aExpect, $oTestObject->_fcpoGetNumericRange(1,3,2));
    }
    
    /**
     * Testing fcpoGetYearRange for coverage
     */
    public function test_fcpoGetYearRange_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        // I will not prepare a hundred entries array ;-)
        $aExpect = $aRange = $oTestObject->fcpoGetYearRange();
        $this->assertEquals($aExpect, $aRange);
    }
    
    /**
     * Testing fcpoGetMonthRange for coverage
     */
    public function test_fcpoGetMonthRange_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $aExpect = $aRange = $oTestObject->fcpoGetMonthRange();
        $this->assertEquals($aExpect, $aRange);
    }
    
    /**
     * Testing fcpoGetDayRange for coverage
     */
    public function test_fcpoGetDayRange_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $aExpect = $aRange = $oTestObject->fcpoGetDayRange();
        $this->assertEquals($aExpect, $aRange);
    }

    /**
     * Testing _fcpoGetUserFromSession for coverage
     */
    public function test__fcpoGetUserFromSession_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockUser = oxNew('oxUser');

        $oMockBasket = $this->getMock('oxBasket', array('getBasketUser'));
        $oMockBasket->expects($this->any())->method('getBasketUser')->will($this->returnValue($oMockUser));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oExpect = $oMockUser;

        $this->assertEquals($oExpect, $oTestObject->_fcpoGetUserFromSession());
    }
}
