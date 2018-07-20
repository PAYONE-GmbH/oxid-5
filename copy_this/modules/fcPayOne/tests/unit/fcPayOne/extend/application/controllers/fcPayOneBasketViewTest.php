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
class Unit_fcPayOne_Extend_Application_Controllers_fcPayOneBasketView extends OxidTestCase {

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
     * Testing render for coverage
     */
    public function test_Render_Coverage() {
        $oTestObject = $this->getMock('fcPayOneBasketView', array(
            '_fcpoCheckForAmazonLogoff',
        ));
        $oTestObject
            ->expects($this->any())
            ->method('_fcpoCheckForAmazonLogoff')
            ->will($this->returnValue(null));

        $sExpect = $sResult = $oTestObject->render();
        $this->assertEquals($sExpect, $sResult);
    }

    /**
     * Testing fcpoGetBasketErrorMessage for coverage
     */
    public function test_fcpoGetBasketErrorMessage_Coverage() {
        $oTestObject = oxNew('fcPayOneBasketView');

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang
            ->expects($this->any())
            ->method('translateString')
            ->will($this->returnValue('someMessage'));

        $oHelper = $this->getMock('fcpohelper',
            array(
                'fcpoGetRequestParameter',
                'fcpoDeleteSessionVariable',
                'fcpoGetLang'
            )
        );
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetRequestParameter')
            ->will($this->returnValue('someMessage'));
        $oHelper
            ->expects($this->any())
            ->method('fcpoDeleteSessionVariable')
            ->will($this->returnValue(null));
        $oHelper
            ->expects($this->any())
            ->method('fcpoGetLang')
            ->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someMessage', $oTestObject->fcpoGetBasketErrorMessage());
    }

    /**
     * Testing _fcpoCheckForAmazonLogoff for coverage
     *
     * @param void
     * @return void
     */
    public function test__fcpoCheckForAmazonLogoff_Coverage() {
        $oTestObject = oxNew('fcPayOneBasketView');

        $oHelper = $this->getMock('fcpohelper',
            array(
                'fcpoGetRequestParameter',
                'fcpoDeleteSessionVariable'
            )
        );
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('logoff'));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(null));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertNull($oTestObject->_fcpoCheckForAmazonLogoff());
    }

    /**
     * Testing _fcpoIsPayPalExpressActive for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoIsPayPalExpressActive_Coverage() {
        $oTestObject = oxNew('fcPayOneBasketView');

        $oMockBasket = $this->getMock('oxBasket', array('fcpoIsPayPalExpressActive'));
        $oMockBasket->expects($this->any())->method('fcpoIsPayPalExpressActive')->will($this->returnValue(true));

        $oHelper = $this->getMock('fcpohelper', array('getFactoryObject'));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockBasket));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoIsPayPalExpressActive());
    }

    /**
     * Testing fcpoGetPayPalExpressPic for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetPayPalExpressPic_Coverage() {
        $oTestObject = $this->getMock('fcPayOneBasketView', array('_fcpoIsPayPalExpressActive', '_fcpoGetPayPalExpressPic'));
        $oTestObject->expects($this->any())->method('_fcpoIsPayPalExpressActive')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetPayPalExpressPic')->will($this->returnValue('somePic'));
        
        $sResponse = $this->invokeMethod($oTestObject, 'fcpoGetPayPalExpressPic');
        $sExpect = 'somePic';

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing _fcpoGetPayPalExpressPic for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoGetPayPalExpressPic_Coverage() {
        $oMockBasket = $this->getMock('oxBasket', array('fcpoGetPayPalExpressPic'));
        $oMockBasket->expects($this->any())->method('fcpoGetPayPalExpressPic')->will($this->returnValue('somePic.jpg'));

        $oMockConfig = $this->getMock('oxConfig', array('getCurrentShopUrl'));
        $oMockConfig->expects($this->any())->method('getCurrentShopUrl')->will($this->returnValue('http://someurl.com/'));

        $oTestObject = $this->getMock('fcPayOneBasketView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        
        $oHelper = $this->getMock('fcpohelper', array('getFactoryObject', 'fcpoFileExists'));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockBasket));
        $oHelper->expects($this->any())->method('fcpoFileExists')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_sPayPalExpressLogoPath', 'somePath/');
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sResponse = $this->invokeMethod($oTestObject, '_fcpoGetPayPalExpressPic');
        $sExpect = 'http://someurl.com/somePath/somePic.jpg';

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing fcpoUsePayPalExpress for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoUsePayPalExpress_Error() {
        $oTestObject = $this->getMock('fcPayOneBasketView', array('_fcpoIsPayPalExpressActive'));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils->expects($this->any())->method('redirect')->will($this->returnValue(false));


        $aMockOutput['status'] = 'ERROR';
        $oMockRequest = $this->getMock('fcporequest', array('load', 'sendRequestGenericPayment'));
        $oMockRequest->expects($this->any())->method('sendRequestGenericPayment')->will($this->returnValue($aMockOutput));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));

        $this->assertEquals(false, $this->invokeMethod($oTestObject, 'fcpoUsePayPalExpress'));
    }

    /**
     * Testing fcpoUsePayPalExpress for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoUsePayPalExpress_Redirect() {
        $oTestObject = $this->getMock('fcPayOneBasketView', array('_fcpoIsPayPalExpressActive'));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils->expects($this->any())->method('redirect')->will($this->returnValue(false));

        $aMockOutput['status'] = 'REDIRECT';
        $oMockRequest = $this->getMock('fcporequest', array('load', 'sendRequestGenericPayment'));
        $oMockRequest->expects($this->any())->method('sendRequestGenericPayment')->will($this->returnValue($aMockOutput));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $this->invokeMethod($oTestObject, 'fcpoUsePayPalExpress'));
    }

    /**
     * Lil' paypalexpresslogo database helper
     * 
     * @param void
     * @return void
     */
    protected function _fcpoPreparePaypalExpressLogos() {
        $this->_fcpoTruncateTable('fcpopayoneexpresslogos');
        $sQuery = "
            INSERT INTO `fcpopayoneexpresslogos` (`OXID`, `FCPO_ACTIVE`, `FCPO_LANGID`, `FCPO_LOGO`, `FCPO_DEFAULT`) VALUES
            (1, 1, 0, 'fc_andre_sw_02_250px.1.png', 1),
            (2, 1, 1, 'btn_xpressCheckout_en.gif', 0)
        ";

        oxDb::getDb()->Execute($sQuery);
    }

    /**
     * Truncates table
     * 
     * @param void
     * @return void
     */
    protected function _fcpoTruncateTable($sTableName) {
        $sQuery = "TRUNCATE TABLE `{$sTableName}` ";

        oxDb::getDb()->Execute($sQuery);
    }

}
