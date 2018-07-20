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
  
class Unit_fcPayOne_Extend_Application_Models_fcPayOneUserTest extends OxidTestCase {

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     * @throws exception
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
     * @throws exception
     * @return mixed Method return.
     */
    public function invokeSetAttribute(&$object, $propertyName, $value) {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }


    /**
     * Testing fcpoSetMasterpassUser for case user already exists
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test_fcpoSetMasterpassUser_ExistingUser() {
        $aMockResponse = array('add_paydata[email]'=>'someemail@somewhere.com');

        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoValidateMasterpassUserDataByResponse',
            '_fcpoUserExists',
            '_fcpoCreateMasterpassUserByResponse',
            'getId',
            '_fcpoGetUserOxidByEmail',
            '_fcpoAddDeliveryAddress',
            '_fcpoLogMeIn',
            'load',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidateMasterpassUserDataByResponse')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoUserExists')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCreateMasterpassUserByResponse')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('someId'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetUserOxidByEmail')
            ->will($this->returnValue('someId'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoAddDeliveryAddress')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoLogMeIn')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('load')
            ->will($this->returnValue(null));

        $oHelper =
            $this
                ->getMockBuilder('fcpohelper')
                ->disableOriginalConstructor()
                ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetSessionVariable')
            ->will($this->returnValue('someWorkorderId'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->fcpoSetMasterpassUser($aMockResponse));
    }

    /**
     * Testing _fcpoLogMeIn for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoLogMeIn_Coverage() {
        $oTestObject = $this->getMock('fcPayOneUser', array('getId'));
        $oTestObject
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('someId'));

        $oHelper =
            $this
                ->getMockBuilder('fcpohelper')
                ->disableOriginalConstructor()
                ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoSetSessionVariable')
            ->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoLogMeIn());
    }

    /**
     * Testing fcpoSetMasterpassUser for case of new user
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test_fcpoSetMasterpassUser_NewUser() {
        $aMockResponse = array('add_paydata[email]'=>'someemail@somewhere.com');

        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoValidateMasterpassUserDataByResponse',
            '_fcpoUserExists',
            '_fcpoCreateMasterpassUserByResponse',
            'getId',
            '_fcpoGetUserOxidByEmail',
            '_fcpoAddDeliveryAddress',
            '_fcpoLogMeIn',
            'load',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoValidateMasterpassUserDataByResponse')
            ->will($this->returnValue(true));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoUserExists')
            ->will($this->returnValue(false));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCreateMasterpassUserByResponse')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('someId'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetUserOxidByEmail')
            ->will($this->returnValue('someId'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoAddDeliveryAddress')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoLogMeIn')
            ->will($this->returnValue(null));
        $oTestObject
            ->expects($this->any())
            ->method('load')
            ->will($this->returnValue(null));

        $oHelper =
            $this
                ->getMockBuilder('fcpohelper')
                ->disableOriginalConstructor()
                ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetSessionVariable')
            ->will($this->returnValue('someWorkorderId'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->fcpoSetMasterpassUser($aMockResponse));
    }

    /**
     * Testing _fcpoValidateMasterpassUserDataByResponse for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoValidateMasterpassUserDataByResponse_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');

        $aMockResponse = array(
            'add_paydata[email]'=>'someValue',
            'add_paydata[firstname]'=>'someValue',
            'add_paydata[lastname]'=>'someValue',
            'add_paydata[country]'=>'someValue',
            'add_paydata[zip]'=>'someValue',
            'add_paydata[city]'=>'someValue',
            'add_paydata[street]'=>'someValue',
        );

        $oMockBasket = $this->getMock('oxBasket', array('setPayment'));
        $oMockBasket
            ->expects($this->any())
            ->method('setPayment')
            ->will($this->returnValue(null));


        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession
            ->expects($this->any())
            ->method('getBasket')
            ->will($this->returnValue($oMockBasket));

        $oHelper =
            $this
                ->getMockBuilder('fcpohelper')
                ->disableOriginalConstructor()
                ->getMock();
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetSession')
            ->will($this->returnValue($oMockSession));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(
            true,
            $oTestObject->_fcpoValidateMasterpassUserDataByResponse($aMockResponse)
        );
    }

    /**
     * Testing _fcpoCreateMasterpassUserByResponse for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoCreateMasterpassUserByResponse_Coverage() {
        $aMockSplittedStreet = array(
            'street'=>'someStreet',
            'streetnr'=>'someStreetNr',
        );

        $sMockCountryId = 'someCountryId';

        $aMockResponse = array(
            'add_paydata[email]'=>'someValue',
            'add_paydata[firstname]'=>'someValue',
            'add_paydata[lastname]'=>'someValue',
            'add_paydata[country]'=>'someValue',
            'add_paydata[zip]'=>'someValue',
            'add_paydata[city]'=>'someValue',
            'add_paydata[street]'=>'someValue',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoGetCountryIdByIso2',
            '_fcpoSplitStreetAndStreetNr',
            'save',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoGetCountryIdByIso2')
            ->will($this->returnValue($sMockCountryId));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoSplitStreetAndStreetNr')
            ->will($this->returnValue($aMockSplittedStreet));
        $oTestObject
            ->expects($this->any())
            ->method('save')
            ->will($this->returnValue(null));

        $this->assertEquals(null, $oTestObject->_fcpoCreateMasterpassUserByResponse($aMockResponse));
    }

    /**
     * Testing fcpoSetAmazonOrderReferenceDetailsResponse for coverage
     *
     * @param void
     * @return void
     */
    public function test_fcpoSetAmazonOrderReferenceDetailsResponse_Coverage() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoAmazonEmailEncode',
            '_fcpoAddOrUpdateAmazonUser',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoAmazonEmailEncode')
            ->will($this->returnValue('someEncodedEmail'));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoAddOrUpdateAmazonUser')
            ->will($this->returnValue(null));

        $aMockResponse['add_paydata[email]'] = 'someMail';

        $this->assertEquals(null, $oTestObject->fcpoSetAmazonOrderReferenceDetailsResponse($aMockResponse));
    }

    /**
     * Testing _fcpoAmazonEmailEncode for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAmazonEmailEncode_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');
        $oMockViewConfig = $this->getMock('oxViewConfig', array('fcpoAmazonEmailEncode'));
        $oMockViewConfig->expects($this->any())->method('fcpoAmazonEmailEncode')->will($this->returnValue('someEncodedEmail'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockViewConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = 'someEncodedEmail';
        $this->assertEquals($sExpect, $oTestObject->_fcpoAmazonEmailEncode('someEmail'));
    }

    /**
     * Testing _fcpoAmazonEmailDecode for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAmazonEmailDecode_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');
        $oMockViewConfig = $this->getMock('oxViewConfig', array('fcpoAmazonEmailDecode'));
        $oMockViewConfig->expects($this->any())->method('fcpoAmazonEmailDecode')->will($this->returnValue('someDecodedEmail'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockViewConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = 'someDecodedEmail';
        $this->assertEquals($sExpect, $oTestObject->_fcpoAmazonEmailDecode('someEmail'));
    }

    /**
     * Testing _fcpoAddOrUpdateAmazonUser for case user exists
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAddOrUpdateAmazonUser_UserExists() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoUserExists',
            '_fcpoUpdateAmazonUser',
            '_fcpoAddAmazonUser',
        ));
        $oTestObject->expects($this->any())->method('_fcpoUserExists')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoUpdateAmazonUser')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoAddAmazonUser')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse['add_paydata[email]'] = 'someEmailAddress';

        $this->assertEquals(null, $oTestObject->_fcpoAddOrUpdateAmazonUser($aMockResponse));
    }

    /**
     * Testing _fcpoAddOrUpdateAmazonUser for case user new
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAddOrUpdateAmazonUser_UserNew() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoUserExists',
            '_fcpoUpdateAmazonUser',
            '_fcpoAddAmazonUser',
        ));
        $oTestObject->expects($this->any())->method('_fcpoUserExists')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoUpdateAmazonUser')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoAddAmazonUser')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse = array(
            'add_paydata[billing_street]'=>'',
            'add_paydata[billing_country]'=>'',
            'add_paydata[email]'=>'',
            'add_paydata[billing_zip]'=>'',
            'add_paydata[billing_telephonenumber]'=>'',
            'add_paydata[billing_firstnam'=>'',
            'add_paydata[billing_lastname]'=>'',
            'add_paydata[billing_city]'=>'',
            'add_paydata[shipping_street]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_firstname]'=>'',
            'add_paydata[shipping_lastname]'=>'',
            'add_paydata[shipping_telephonenumber]'=>'',
            'add_paydata[shipping_city]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_zip]'=>'',
        );

        $this->assertEquals(null, $oTestObject->_fcpoAddOrUpdateAmazonUser($aMockResponse));
    }

    /**
     * Testing _fcpoAddAmazonUser for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAddAmazonUser_Coverage() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoAmazonAddDeliveryAddress',
            '_fcpoGetCountryIdByIso2',
            '_fcpoSplitStreetAndStreetNr',
        ));
        $oTestObject->expects($this->any())->method('_fcpoAmazonAddDeliveryAddress')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoGetCountryIdByIso2')->will($this->returnValue('DE'));
        $oTestObject->expects($this->any())->method('_fcpoSplitStreetAndStreetNr')->will($this->returnValue(array('somestreet', '1')));

        $oMockUser = $this->getMock('oxUser', array('save', 'addToGroup', 'getId'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(null));
        $oMockUser->expects($this->any())->method('addToGroup')->will($this->returnValue(null));
        $oMockUser->expects($this->any())->method('getId')->will($this->returnValue('someUserId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUser));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse = array(
            'add_paydata[billing_street]'=>'',
            'add_paydata[billing_country]'=>'',
            'add_paydata[email]'=>'',
            'add_paydata[billing_zip]'=>'',
            'add_paydata[billing_telephonenumber]'=>'',
            'add_paydata[billing_firstnam'=>'',
            'add_paydata[billing_lastname]'=>'',
            'add_paydata[billing_city]'=>'',
            'add_paydata[shipping_street]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_firstname]'=>'',
            'add_paydata[shipping_lastname]'=>'',
            'add_paydata[shipping_telephonenumber]'=>'',
            'add_paydata[shipping_city]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_zip]'=>'',
        );


        $this->assertEquals('someUserId', $oTestObject->_fcpoAddAmazonUser($aMockResponse));
    }

    /**
     * Testing _fcpoAddAmazonUser for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoUpdateAmazonUser_Coverage() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoGetUserOxidByEmail',
            '_fcpoAmazonAddDeliveryAddress',
            '_fcpoGetCountryIdByIso2',
            '_fcpoSplitStreetAndStreetNr',
        ));
        $oTestObject->expects($this->any())->method('_fcpoGetUserOxidByEmail')->will($this->returnValue('someUserId'));
        $oTestObject->expects($this->any())->method('_fcpoAmazonAddDeliveryAddress')->will($this->returnValue(null));
        $oTestObject->expects($this->any())->method('_fcpoGetCountryIdByIso2')->will($this->returnValue('DE'));
        $oTestObject->expects($this->any())->method('_fcpoSplitStreetAndStreetNr')->will($this->returnValue(array('somestreet', '1')));

        $oMockUser = $this->getMock('oxUser', array('save', 'addToGroup', 'getId'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(null));
        $oMockUser->expects($this->any())->method('addToGroup')->will($this->returnValue(null));
        $oMockUser->expects($this->any())->method('getId')->will($this->returnValue('someUserId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUser));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse = array(
            'add_paydata[billing_street]'=>'',
            'add_paydata[billing_country]'=>'',
            'add_paydata[email]'=>'',
            'add_paydata[billing_zip]'=>'',
            'add_paydata[billing_telephonenumber]'=>'',
            'add_paydata[billing_firstnam'=>'',
            'add_paydata[billing_lastname]'=>'',
            'add_paydata[billing_city]'=>'',
            'add_paydata[shipping_street]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_firstname]'=>'',
            'add_paydata[shipping_lastname]'=>'',
            'add_paydata[shipping_telephonenumber]'=>'',
            'add_paydata[shipping_city]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_zip]'=>'',
        );

        $this->assertEquals('someUserId', $oTestObject->_fcpoUpdateAmazonUser($aMockResponse));
    }

    /**
     * Testing _fcpoAmazonAddDeliveryAddress for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAmazonAddDeliveryAddress_NotExists() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoSplitStreetAndStreetNr',
            '_fcpoGetCountryIdByIso2',
            '_fcpoCheckAddressExists',
        ));
        $oTestObject->expects($this->any())->method('_fcpoSplitStreetAndStreetNr')->will($this->returnValue(array('somestreet', '1')));
        $oTestObject->expects($this->any())->method('_fcpoGetCountryIdByIso2')->will($this->returnValue('DE'));
        $oTestObject->expects($this->any())->method('_fcpoCheckAddressExists')->will($this->returnValue(false));

        $oMockAddress = $this->getMock('oxAddress', array(
            'load',
            'setId',
            'save',
            'getEncodedDeliveryAddress',
        ));
        $oMockAddress->expects($this->any())->method('load')->will($this->returnValue(null));
        $oMockAddress->expects($this->any())->method('setId')->will($this->returnValue(null));
        $oMockAddress->expects($this->any())->method('save')->will($this->returnValue(null));
        $oMockAddress->expects($this->any())->method('getEncodedDeliveryAddress')->will($this->returnValue('someEncodedAddress'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockAddress));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse = array(
            'add_paydata[billing_street]'=>'',
            'add_paydata[billing_country]'=>'',
            'add_paydata[email]'=>'',
            'add_paydata[billing_zip]'=>'',
            'add_paydata[billing_telephonenumber]'=>'',
            'add_paydata[billing_firstnam'=>'',
            'add_paydata[billing_lastname]'=>'',
            'add_paydata[billing_city]'=>'',
            'add_paydata[shipping_street]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_firstname]'=>'',
            'add_paydata[shipping_lastname]'=>'',
            'add_paydata[shipping_telephonenumber]'=>'',
            'add_paydata[shipping_city]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_zip]'=>'',
        );

        $this->assertEquals(null, $oTestObject->_fcpoAmazonAddDeliveryAddress($aMockResponse, 'someUserId'));
    }

    /**
     * Testing _fcpoAmazonAddDeliveryAddress for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoAmazonAddDeliveryAddress_Coverage() {
        $oTestObject = $this->getMock('fcPayOneUser', array(
            '_fcpoSplitStreetAndStreetNr',
            '_fcpoGetCountryIdByIso2',
            '_fcpoCheckAddressExists',
        ));
        $oTestObject->expects($this->any())->method('_fcpoSplitStreetAndStreetNr')->will($this->returnValue(array('somestreet', '1')));
        $oTestObject->expects($this->any())->method('_fcpoGetCountryIdByIso2')->will($this->returnValue('DE'));
        $oTestObject->expects($this->any())->method('_fcpoCheckAddressExists')->will($this->returnValue(false));

        $oMockAddress = $this->getMock('oxAddress', array(
            'load',
            'setId',
            'save',
            'getEncodedDeliveryAddress',
        ));
        $oMockAddress->expects($this->any())->method('load')->will($this->returnValue(null));
        $oMockAddress->expects($this->any())->method('setId')->will($this->returnValue(null));
        $oMockAddress->expects($this->any())->method('save')->will($this->returnValue(null));
        $oMockAddress->expects($this->any())->method('getEncodedDeliveryAddress')->will($this->returnValue('someEncodedAddress'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockAddress));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockResponse = array(
            'add_paydata[billing_street]'=>'',
            'add_paydata[billing_country]'=>'',
            'add_paydata[email]'=>'',
            'add_paydata[billing_zip]'=>'',
            'add_paydata[billing_telephonenumber]'=>'',
            'add_paydata[billing_firstnam'=>'',
            'add_paydata[billing_lastname]'=>'',
            'add_paydata[billing_city]'=>'',
            'add_paydata[shipping_street]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_firstname]'=>'',
            'add_paydata[shipping_lastname]'=>'',
            'add_paydata[shipping_telephonenumber]'=>'',
            'add_paydata[shipping_city]'=>'',
            'add_paydata[shipping_country]'=>'',
            'add_paydata[shipping_zip]'=>'',
        );

        $this->assertEquals(null, $oTestObject->_fcpoAmazonAddDeliveryAddress($aMockResponse, 'someUserId'));
    }

    /**
     * Testing _fcpoCheckAddressExists for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoCheckAddressExists_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');
        $oMockAddress = $this->getMock('oxAddress', array('load'));
        $oMockAddress->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockAddress));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoCheckAddressExists('someEncodedAddress'));
    }

    /**
     * Testing _fcpoSplitStreetAndStreetNr for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoSplitStreetAndStreetNr_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');
        $sMockStreetAndStreetNr = "Some Street 123";
        $aExpect = array('street'=>'Some Street', 'streetnr'=>'123');
        $this->assertEquals($aExpect, $oTestObject->_fcpoSplitStreetAndStreetNr($sMockStreetAndStreetNr));
    }

    /**
     * Testing _fcpoGetCountryIdByIso2 for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoGetCountryIdByIso2_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');

        $oMockCountry = $this->getMock('oxCountry', array('getIdByCode'));
        $oMockCountry->expects($this->any())->method('getIdByCode')->will($this->returnValue('someCountryId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockCountry));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someCountryId', $oTestObject->_fcpoGetCountryIdByIso2('someISOCode'));
    }

    /**
     * Testing _fcpoUserExists with option password
     *
     * @param void
     * @return void
     */
    public function test__fcpoUserExists_WithPassword() {
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxpassword = new oxField('somePassword');

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoGetUserOxidByEmail', 'load'));
        $oTestObject->expects($this->any())->method('_fcpoGetUserOxidByEmail')->will($this->returnValue('someUserId'));
        $oTestObject->expects($this->any())->method('load')->will($this->returnValue($oMockUser));

        $this->assertEquals(false, $oTestObject->_fcpoUserExists('someEmailAddress', true));
    }

    /**
     * Testing _fcpoUserExists with option no password
     *
     * @param void
     * @return void
     */
    public function test__fcpoUserExists_NoPassword() {
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxpassword = new oxField('somePassword');

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoGetUserOxidByEmail', 'load'));
        $oTestObject->expects($this->any())->method('_fcpoGetUserOxidByEmail')->will($this->returnValue('someUserId'));
        $oTestObject->expects($this->any())->method('load')->will($this->returnValue($oMockUser));

        $this->assertEquals(true, $oTestObject->_fcpoUserExists('someEmailAddress', false));
    }

    /**
     * Testiong _fcpoGetUserOxidByEmail for coverage
     *
     * @param void
     * @return void
     * @throws exception
     */
    public function test__fcpoGetUserOxidByEmail_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');

        $oMockDb = $this->getMock('oxDb', array('GetOne', 'quote'));
        $oMockDb->expects($this->any())->method('GetOne')->will($this->returnValue('someUserId'));
        $oMockDb->expects($this->any())->method('quote')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetDb')->will($this->returnValue($oMockDb));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someUserId', $oTestObject->_fcpoGetUserOxidByEmail('someEmailAddress'));
    }


    /**
     * Testing fcpoSetBoni with scorevalue
     */
    public function test_fcpoSetBoni_ScoreValue() {
        $aMockResponse = array(
            'scorevalue' => 5000,
            'score' => 'R',
        );

        $oTestObject = oxNew('fcPayOneUser');
        $oTestObject->oxuser__oxboni = new oxField('');
        $oTestObject->oxuser__fcpobonicheckdate = new oxField('');

        $this->assertEquals(null, $oTestObject->fcpoSetBoni($aMockResponse));
    }

    /**
     * Testing fcpoSetBoni with score tag only
     */
    public function test_fcpoSetBoni_Score() {
        $aMockResponse = array(
            'score' => 'R',
        );

        $oTestObject = oxNew('fcPayOneUser');
        $oTestObject->oxuser__oxboni = new oxField('');
        $oTestObject->oxuser__fcpobonicheckdate = new oxField('');

        $this->assertEquals(null, $oTestObject->fcpoSetBoni($aMockResponse));
    }

    /**
     * Testing isNewBonicheckNeeded for coverage
     */
    public function test_isNewBonicheckNeeded_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');
        $oTestObject->oxuser__fcpobonicheckdate = new oxField('2016-05-01');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(7));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->isNewBonicheckNeeded());
    }

    /**
     * Testing isBonicheckNeededForBasket for coverage
     */
    public function test_isBonicheckNeededForBasket_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');

        $oMockPrice = $this->getMock('oxPrice', array('getBruttoPrice'));
        $oMockPrice->expects($this->any())->method('getBruttoPrice')->will($this->returnValue(10));

        $oMockBasket = $this->getMock('oxBasket', array('getPrice'));
        $oMockBasket->expects($this->any())->method('getPrice')->will($this->returnValue($oMockPrice));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(50));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->isBonicheckNeededForBasket());
    }

    /**
     * Testing isBonicheckNeeded for coverage
     */
    public function test_isBonicheckNeeded_Coverage() {
        $oTestObject = $this->getMock('fcPayOneUser', array('getBoni', 'isNewBonicheckNeeded', 'isBonicheckNeededForBasket'));
        $oTestObject->expects($this->any())->method('getBoni')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isNewBonicheckNeeded')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isBonicheckNeededForBasket')->will($this->returnValue(true));

        $this->assertEquals(true, $oTestObject->isBonicheckNeeded());
    }

    /**
     * Testing checkAddressAndScore in expection that the early return will be triggered with true
     */
    public function test_checkAddressAndScore_ExpectEarlyTrue() {
        $oTestObject = $this->getMock('fcPayOneUser', array('isBonicheckNeeded', 'fcpoSetBoni', 'fcpoIsValidAddress'));
        $oTestObject->expects($this->any())->method('isBonicheckNeeded')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoSetBoni')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoIsValidAddress')->will($this->returnValue(true));

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestConsumerscore', 'sendRequestAddresscheck'));
        $oMockRequest->expects($this->any())->method('sendRequestConsumerscore')->will($this->returnValue(true));
        $oMockRequest->expects($this->any())->method('sendRequestAddresscheck')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->onConsecutiveCalls(true, true, '-1', true, true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->checkAddressAndScore(true, true));
    }

    /**
     * Testing checkAddressAndScore covering the rest of code
     */
    public function test_checkAddressAndScore_Coverage() {
        $oTestObject = $this->getMock('fcPayOneUser', array('isBonicheckNeeded', 'fcpoSetBoni', 'fcpoIsValidAddress'));
        $oTestObject->expects($this->any())->method('isBonicheckNeeded')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoSetBoni')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoIsValidAddress')->will($this->returnValue(true));

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestConsumerscore', 'sendRequestAddresscheck'));
        $oMockRequest->expects($this->any())->method('sendRequestConsumerscore')->will($this->returnValue(true));
        $oMockRequest->expects($this->any())->method('sendRequestAddresscheck')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->checkAddressAndScore(true, true));
    }

    /**
     * Testing checkAddressAndScore covering the rest of code
     */
    public function test_checkAddressAndScore_Coverage_2() {
        $oTestObject = $this->getMock('fcPayOneUser', array('isBonicheckNeeded', 'fcpoSetBoni', 'fcpoIsValidAddress'));
        $oTestObject->expects($this->any())->method('isBonicheckNeeded')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoSetBoni')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcpoIsValidAddress')->will($this->returnValue(true));

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestConsumerscore', 'sendRequestAddresscheck'));
        $oMockRequest->expects($this->any())->method('sendRequestConsumerscore')->will($this->returnValue(true));
        $oMockRequest->expects($this->any())->method('sendRequestAddresscheck')->will($this->returnValue(array('someResponse')));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->checkAddressAndScore(true, false));
    }

    /**
     * Testing getBoni from payone overwriting
     */
    public function test_getBoni_DefaultBoni() {
        $oTestObject = oxNew('fcPayOneUser');
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(5000));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(5000, $oTestObject->getBoni());
    }

    /**
     * Testing getBoni from parent call
     */
    public function test_getBoni_Parent() {
        $oTestObject = oxNew('fcPayOneUser');
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(null));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(1000, $oTestObject->getBoni());
    }

    /**
     * Testing fcpoIsValidAddress 
     */
    public function test_fcpoIsValidAddress_EarlyTrue() {
        $aMockResponse = array(
            'fcWrongCountry' => true,
        );

        $oTestObject = oxNew('fcPayOneUser');

        $this->assertEquals(true, $oTestObject->fcpoIsValidAddress($aMockResponse, true));
    }

    /**
     * Testing fcpoIsValidAddress for case that address check fails
     */
    public function test_fcpoIsValidAddress_AddressCheckFails() {
        $aMockResponse = array(
            'status' => 'VALID',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoValidateResponse'));
        $oTestObject->expects($this->any())->method('_fcpoValidateResponse')->will($this->returnValue(false));

        $this->assertEquals(false, $oTestObject->fcpoIsValidAddress($aMockResponse, true));
    }

    /**
     * Testing fcpoIsValidAddress for case that everything is ok
     */
    public function test_fcpoIsValidAddress_EverythingOk() {
        $aMockResponse = array(
            'status' => 'VALID',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
            'fcWrongCountry' => true,
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoValidateResponse'));
        $oTestObject->expects($this->any())->method('_fcpoValidateResponse')->will($this->returnValue(true));

        $this->assertEquals(true, $oTestObject->fcpoIsValidAddress($aMockResponse, true));
    }

    /**
     * Testing _fcpoValidateResponse for case that addresscheck response is valid
     */
    public function test__fcpoValidateResponse_Valid() {
        $aMockResponse = array(
            'status' => 'VALID',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoValidateUserDataByResponse'));
        $oTestObject->expects($this->any())->method('_fcpoValidateUserDataByResponse')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoValidateResponse($aMockResponse, true));
    }

    /**
     * Testing _fcpoValidateResponse for case that addresscheck response is invalid
     */
    public function test__fcpoValidateResponse_Invalid() {
        $aMockResponse = array(
            'status' => 'INVALID',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoValidateUserDataByResponse'));
        $oTestObject->expects($this->any())->method('_fcpoValidateUserDataByResponse')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_fcpoValidateResponse($aMockResponse, true));
    }

    /**
     * Testing _fcpoValidateResponse for case that addresscheck response has error message
     */
    public function test__fcpoValidateResponse_Error() {
        $aMockResponse = array(
            'status' => 'ERROR',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoValidateUserDataByResponse'));
        $oTestObject->expects($this->any())->method('_fcpoValidateUserDataByResponse')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_fcpoValidateResponse($aMockResponse, true));
    }
    
    /**
     * Testing _fcpoValidateResponse for case that addresscheck response has no response
     */
    public function test__fcpoValidateResponse_None() {
        $aMockResponse = array(
            'status' => 'SOMENONEXISTINGVALUE',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('_fcpoValidateUserDataByResponse'));
        $oTestObject->expects($this->any())->method('_fcpoValidateUserDataByResponse')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_fcpoValidateResponse($aMockResponse, true));
    }
    
    /**
     * Testing _fcpoValidateUserDataByResponse for case that addresscheck response has error message
     */
    public function test__fcpoValidateUserDataByResponse_Error() {
        $aMockResponse = array(
            'status' => 'ERROR',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
        );

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOneUser', array('save'));
        $oTestObject->expects($this->any())->method('save')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $oTestObject->_fcpoValidateUserDataByResponse($aMockResponse, true));
    }
    
    /**
     * Testing _fcpoValidateUserDataByResponse for case that addresscheck response has error message
     */
    public function test__fcpoValidateUserDataByResponse_Success() {
        $aMockResponse = array(
            'status' => 'VALID',
            'firstname' => 'someValue',
            'lastname' => 'someValue',
            'streetname' => 'someValue',
            'streetnumber' => 'someValue',
            'zip' => 'someValue',
            'city' => 'someValue',
            'personstatus' => 'someValue',
        );

        $oTestObject = $this->getMock('fcPayOneUser', array('save'));
        $oTestObject->expects($this->any())->method('save')->will($this->returnValue(true));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $oMockUtilsView = $this->getMock('oxUtilsView', array('addErrorToDisplay'));
        $oMockUtilsView->expects($this->any())->method('addErrorToDisplay')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetUtilsView')->will($this->returnValue($oMockUtilsView));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoValidateUserDataByResponse($aMockResponse, true));
    }
    
    

    /**
     * Testing fcpoUnsetGroups for coverage
     */
    public function test_fcpoUnsetGroups_Coverage() {
        $oTestObject = oxNew('fcPayOneUser');
        $this->assertEquals(null, $oTestObject->fcpoUnsetGroups());
    }

}
