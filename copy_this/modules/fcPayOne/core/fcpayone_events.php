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
 * PHP version 5
 * 
 * @copyright 2003 - 2016 Payone GmbH
 * @version   OXID eShop CE
 * @link      http://www.payone.de
 */

/**
 * Eventhandler for module activation and deactivation.
 */
class fcpayone_events
{

    /**
     * Database object
     * 
     * @var object
     */
    protected static $_oFcpoHelper = null;

    /**
     * Paymnts that were once used but now deprecated and marked for removal
     * 
     * @var array
     */
    public static $_aRemovedPaymentMethods = array(
        'fcpoyapital',
        'fcpocommerzfinanz',
        'fcpoklarna_installment',
        'fcpocreditcard_iframe',
        'fcpobillsafe',
        'fcpomasterpass',
        'fcpoonlineueberweisung',
    );

    public static $sQueryTableFcporefnr = "
        CREATE TABLE fcporefnr (
          FCPO_REFNR int(11) NOT NULL AUTO_INCREMENT,
          FCPO_TXID varchar(32) NOT NULL DEFAULT '',
          FCPO_REFPREFIX varchar(32) NOT NULL DEFAULT '',
          PRIMARY KEY (FCPO_REFNR, FCPO_REFPREFIX)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcporequestlog = "
        CREATE TABLE fcporequestlog (
          OXID int(11) NOT NULL AUTO_INCREMENT,
          FCPO_TIMESTAMP timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          FCPO_REFNR int(11) NOT NULL DEFAULT '0',
          FCPO_REQUESTTYPE varchar(32) NOT NULL DEFAULT '',
          FCPO_RESPONSESTATUS varchar(32) NOT NULL DEFAULT '',
          FCPO_REQUEST text NOT NULL,
          FCPO_RESPONSE text NOT NULL,
          FCPO_PORTALID varchar(32) NOT NULL DEFAULT '',
          FCPO_AID varchar(32) NOT NULL DEFAULT '',
          PRIMARY KEY (OXID)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpotransactionstatus = "
        CREATE TABLE fcpotransactionstatus (
          OXID int(11) NOT NULL AUTO_INCREMENT,
          FCPO_TIMESTAMP timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          FCPO_ORDERNR int(11) DEFAULT '0',
          FCPO_KEY varchar(32) NOT NULL DEFAULT '',
          FCPO_TXACTION varchar(32) NOT NULL DEFAULT '',
          FCPO_PORTALID int(11) NOT NULL DEFAULT '0',
          FCPO_AID int(11) NOT NULL DEFAULT '0',
          FCPO_CLEARINGTYPE varchar(32) NOT NULL DEFAULT '',
          FCPO_TXTIME timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          FCPO_CURRENCY varchar(32) NOT NULL DEFAULT '',
          FCPO_USERID int(11) NOT NULL DEFAULT '0',
          FCPO_ACCESSNAME varchar(32) NOT NULL DEFAULT '',
          FCPO_ACCESSCODE varchar(32) NOT NULL DEFAULT '',
          FCPO_PARAM varchar(255) NOT NULL DEFAULT '',
          FCPO_MODE varchar(8) NOT NULL DEFAULT '',
          FCPO_PRICE double NOT NULL DEFAULT '0',
          FCPO_TXID int(11) NOT NULL DEFAULT '0',
          FCPO_REFERENCE int(11) NOT NULL DEFAULT '0',
          FCPO_SEQUENCENUMBER int(11) NOT NULL DEFAULT '0',
          FCPO_COMPANY varchar(255) NOT NULL DEFAULT '',
          FCPO_FIRSTNAME varchar(255) NOT NULL DEFAULT '',
          FCPO_LASTNAME varchar(255) NOT NULL DEFAULT '',
          FCPO_STREET varchar(255) NOT NULL DEFAULT '',
          FCPO_ZIP varchar(16) NOT NULL DEFAULT '',
          FCPO_CITY varchar(255) NOT NULL DEFAULT '',
          FCPO_EMAIL varchar(255) NOT NULL DEFAULT '',
          FCPO_COUNTRY varchar(8) NOT NULL DEFAULT '',
          FCPO_SHIPPING_COMPANY varchar(255) NOT NULL DEFAULT '',
          FCPO_SHIPPING_FIRSTNAME varchar(255) NOT NULL DEFAULT '',
          FCPO_SHIPPING_LASTNAME varchar(255) NOT NULL DEFAULT '',
          FCPO_SHIPPING_STREET varchar(255) NOT NULL DEFAULT '',
          FCPO_SHIPPING_ZIP varchar(16) NOT NULL DEFAULT '',
          FCPO_SHIPPING_CITY varchar(255) NOT NULL DEFAULT '',
          FCPO_SHIPPING_COUNTRY varchar(8) NOT NULL DEFAULT '',
          FCPO_BANKCOUNTRY varchar(8) NOT NULL DEFAULT '',
          FCPO_BANKACCOUNT varchar(32) NOT NULL DEFAULT '',
          FCPO_BANKCODE varchar(32) NOT NULL DEFAULT '',
          FCPO_BANKACCOUNTHOLDER varchar(255) NOT NULL DEFAULT '',
          FCPO_CARDEXPIREDATE varchar(8) NOT NULL DEFAULT '',
          FCPO_CARDTYPE varchar(8) NOT NULL DEFAULT '',
          FCPO_CARDPAN varchar(32) NOT NULL DEFAULT '',
          FCPO_CUSTOMERID int(11) NOT NULL DEFAULT '0',
          FCPO_BALANCE double NOT NULL DEFAULT '0',
          FCPO_RECEIVABLE double NOT NULL DEFAULT '0',
          PRIMARY KEY (OXID)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpopayment2country = "
        CREATE TABLE IF NOT EXISTS fcpopayment2country (
          OXID char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          FCPO_PAYMENTID char(8) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
          FCPO_COUNTRYID char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
          FCPO_TYPE char(8) NOT NULL DEFAULT '',
          PRIMARY KEY (`OXID`),
          KEY `FCPO_PAYMENTID` (`FCPO_PAYMENTID`),
          KEY `FCPO_COUNTRYID` (`FCPO_COUNTRYID`),
          KEY `FCPO_TYPE` (`FCPO_TYPE`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpoStatusForwarding = "
        CREATE TABLE fcpostatusforwarding(
                OXID INT(11) NOT NULL AUTO_INCREMENT ,
                FCPO_PAYONESTATUS VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
                FCPO_URL VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
                FCPO_TIMEOUT DOUBLE NOT NULL DEFAULT '0' ,
                PRIMARY KEY (`OXID`)
        );";
    public static $sQueryTableFcpoStatusMapping = "
        CREATE TABLE fcpostatusmapping(
                OXID INT(11) NOT NULL AUTO_INCREMENT ,
                FCPO_PAYMENTID CHAR(32) CHARSET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '' ,
                FCPO_PAYONESTATUS VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
                FCPO_FOLDER VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
                PRIMARY KEY (`OXID`)
        );";
    public static $sQueryTableFcpoErrorMapping = "
        CREATE TABLE fcpoerrormapping(
                OXID INT(11) NOT NULL AUTO_INCREMENT ,
                FCPO_ERROR_CODE VARCHAR(32) CHARSET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '' ,
                FCPO_LANG_ID VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
                FCPO_MAPPED_MESSAGE TEXT CHARSET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
                FCPO_ERROR_TYPE VARCHAR(32) CHARSET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '' ,
                PRIMARY KEY (`OXID`),
                KEY `FCPO_ERROR_TYPE` (`FCPO_ERROR_TYPE`)
        );";
    public static $sQueryTableFcpoklarnastoreids = "
        CREATE TABLE fcpoklarnastoreids (
          OXID int(11) NOT NULL AUTO_INCREMENT,
          FCPO_STOREID varchar(32) NOT NULL DEFAULT '',
          PRIMARY KEY (OXID)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpoPdfMandates = "
        CREATE TABLE fcpopdfmandates (
          OXORDERID char(32) COLLATE latin1_general_ci NOT NULL,
          FCPO_FILENAME varchar(32) NOT NULL DEFAULT '',
          PRIMARY KEY (OXORDERID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpoklarnacampaigns = "
        CREATE TABLE fcpoklarnacampaigns (
          OXID int(11) NOT NULL AUTO_INCREMENT,
          FCPO_CAMPAIGN_CODE varchar(32) NOT NULL DEFAULT '',
          FCPO_CAMPAIGN_TITLE varchar(128) NOT NULL DEFAULT '',
          PRIMARY KEY (OXID)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpopaypalexpresslogos = "
        CREATE TABLE fcpopayoneexpresslogos (
            OXID int(11) NOT NULL AUTO_INCREMENT,
            FCPO_ACTIVE TINYINT( 1 ) NOT NULL DEFAULT '0',
            FCPO_LANGID INT( 11 ) NOT NULL ,
            FCPO_LOGO VARCHAR( 255 ) NOT NULL ,
            FCPO_DEFAULT TINYINT( 1 ) NOT NULL DEFAULT '0',
            PRIMARY KEY (OXID)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
    public static $sQueryTableFcpocheckedaddresses = "
        CREATE TABLE fcpocheckedaddresses (
          fcpo_address_hash CHAR(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
          fcpo_checkdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (fcpo_address_hash)
        ) ENGINE=INNODB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";

    public static $sQueryTableFcpoShadowBasket = "
        CREATE TABLE IF NOT EXISTS `fcposhadowbasket` (
          `FCPOSESSIONID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
          `OXORDERID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT '',
          `FCPOBASKET` mediumtext NOT NULL,
          `FCPOCREATED` datetime NOT NULL,
          `FCPOCHECKED` datetime DEFAULT NULL,
          PRIMARY KEY (`FCPOSESSIONID`),
          KEY `OXORDERID` (`OXORDERID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    public static $sQueryTableRatePay = "
        CREATE TABLE `fcporatepay` (
          `OXID` char(32) COLLATE latin1_general_ci NOT NULL,
          `OXPAYMENTID` char(32) COLLATE latin1_general_ci NOT NULL,
          `shopid` int(11) NOT NULL,
          `merchant_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `merchant_status` int(11) DEFAULT NULL,
          `shop_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `currency` char(32) NOT NULL,
          `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `activation_status_elv` int(11) DEFAULT NULL,
          `activation_status_installment` int(11) DEFAULT NULL,
          `activation_status_invoice` int(11) DEFAULT NULL,
          `activation_status_prepayment` int(11) DEFAULT NULL,
          `amount_min_longrun` double DEFAULT NULL,
          `b2b_pq_full` tinyint(1) DEFAULT NULL,
          `b2b_pq_light` tinyint(1) DEFAULT NULL,
          `b2b_elv` tinyint(1) DEFAULT NULL,
          `b2b_installment` tinyint(1) DEFAULT NULL,
          `b2b_invoice` tinyint(1) DEFAULT NULL,
          `b2b_prepayment` tinyint(1) DEFAULT NULL,
          `country_code_billing` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `country_code_delivery` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `delivery_address_pq_full` tinyint(1) DEFAULT NULL,
          `delivery_address_pq_light` tinyint(1) DEFAULT NULL,
          `delivery_address_elv` tinyint(1) DEFAULT NULL,
          `delivery_address_installment` tinyint(1) DEFAULT NULL,
          `delivery_address_invoice` tinyint(1) DEFAULT NULL,
          `delivery_address_prepayment` tinyint(1) DEFAULT NULL,
          `device_fingerprint_snippet_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `eligibility_device_fingerprint` tinyint(1) DEFAULT NULL,
          `eligibility_ratepay_elv` tinyint(1) DEFAULT NULL,
          `eligibility_ratepay_installment` tinyint(1) DEFAULT NULL,
          `eligibility_ratepay_invoice` tinyint(1) DEFAULT NULL,
          `eligibility_ratepay_pq_full` tinyint(1) DEFAULT NULL,
          `eligibility_ratepay_pq_light` tinyint(1) DEFAULT NULL,
          `eligibility_ratepay_prepayment` tinyint(1) DEFAULT NULL,
          `interest_rate_merchant_towards_bank` double DEFAULT NULL,
          `interestrate_default` double DEFAULT NULL,
          `interestrate_max` double DEFAULT NULL,
          `interestrate_min` double DEFAULT NULL,
          `min_difference_dueday` int(11) DEFAULT NULL,
          `month_allowed` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `month_longrun` int(11) DEFAULT NULL,
          `month_number_max` int(11) DEFAULT NULL,
          `month_number_min` int(11) DEFAULT NULL,
          `payment_amount` double DEFAULT NULL,
          `payment_firstday` int(11) DEFAULT NULL,
          `payment_lastrate` double DEFAULT NULL,
          `rate_min_longrun` double DEFAULT NULL,
          `rate_min_normal` double DEFAULT NULL,
          `service_charge` double DEFAULT NULL,
          `tx_limit_elv_max` double DEFAULT NULL,
          `tx_limit_elv_min` double DEFAULT NULL,
          `tx_limit_installment_max` double DEFAULT NULL,
          `tx_limit_installment_min` double DEFAULT NULL,
          `tx_limit_invoice_max` double DEFAULT NULL,
          `tx_limit_invoice_min` double DEFAULT NULL,
          `tx_limit_prepayment_max` double DEFAULT NULL,
          `txLimitPrepaymentMin` double DEFAULT NULL,
          `valid_payment_firstdays` int(11) DEFAULT NULL,
          PRIMARY KEY (OXID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    ";

    public static $sQueryTableFcpoUserFlags = "
        CREATE TABLE IF NOT EXISTS `fcpouserflags` (
          `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `FCPOCODE` int(11) NOT NULL,
          `FCPOEFFECT` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
          `FCPOFLAGDURATION` int(11) NOT NULL,
          `FCPONAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `FCPODESC` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`OXID`),
          UNIQUE KEY `FCPOCODE` (`FCPOCODE`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    ";

    public static $sQueryTableFcpoUser2Flag = "
        CREATE TABLE IF NOT EXISTS `fcpouser2flag` (
          `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `OXUSERID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `FCPOUSERFLAGID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `FCPODISPLAYMESSAGE` text COLLATE utf8_unicode_ci NOT NULL,
          `FCPOTIMESTAMP` datetime NOT NULL,
          PRIMARY KEY (`OXID`),
          KEY `OXUSERID` (`OXUSERID`,`FCPOUSERFLAGID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;    
    ";
    
    public static $sQueryAlterOxorderTxid = "ALTER TABLE oxorder ADD COLUMN FCPOTXID VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderRefNr = "ALTER TABLE oxorder ADD COLUMN FCPOREFNR INT(11) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderAuthMode = "ALTER TABLE oxorder ADD COLUMN FCPOAUTHMODE VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderMode = "ALTER TABLE oxorder ADD COLUMN FCPOMODE VARCHAR(8) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxpaymentsAuthMode = "ALTER TABLE oxpayments ADD COLUMN FCPOAUTHMODE VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing1 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKACCOUNTHOLDER VARCHAR(64) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing2 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKACCOUNT VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing3 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKCODE VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing4 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKNAME VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing5 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKBIC VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing6 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKIBAN VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing7 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_LEGALNOTE VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing8 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_DUEDATE VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing9 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_REFERENCE VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing10 = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_INSTRUCTIONNOTE VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxuserPersonalId = "ALTER TABLE oxuser ADD COLUMN FCPOPERSONALID VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxuserCurrentMalus = "ALTER TABLE oxuser ADD COLUMN FCPOCURRMALUS INT(11) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxuserRealBoni = "ALTER TABLE oxuser ADD COLUMN FCPOREALBONI INT(11) DEFAULT NULL;";
    public static $sQueryAlterFcporefnr = "ALTER TABLE fcporefnr ADD COLUMN FCPO_REFPREFIX VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY(FCPO_REFNR, FCPO_REFPREFIX);";
    public static $sQueryChangeFcporequestlog = "ALTER TABLE fcporequestlog CHANGE FCPO_REFNR FCPO_REFNR VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterCampaign1 = "ALTER TABLE fcpoklarnacampaigns ADD FCPO_CAMPAIGN_LANGUAGE VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';";
    public static $sQueryAlterCampaign2 = "ALTER TABLE fcpoklarnacampaigns ADD FCPO_CAMPAIGN_CURRENCY VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';";
    public static $sQueryAlterOxuser = "ALTER TABLE oxuser ADD COLUMN FCPOBONICHECKDATE DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL;";
    public static $sQueryAlterOxpaymentsLiveMode = "ALTER TABLE oxpayments ADD COLUMN FCPOLIVEMODE TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxpaymentsIsPayone = "ALTER TABLE oxpayments ADD COLUMN FCPOISPAYONE TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderarticlesCapturedAmount = "ALTER TABLE oxorderarticles ADD COLUMN FCPOCAPTUREDAMOUNT INT(11) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderarticlesDebitedAmount = "ALTER TABLE oxorderarticles ADD COLUMN FCPODEBITEDAMOUNT INT(11) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderarticlesCapturedPrice = "ALTER TABLE oxorderarticles ADD COLUMN FCPOCAPTUREDPRICE DOUBLE DEFAULT 0 NOT NULL;";
    public static $sQueryAlterOxorderarticlesCapturedPriceCompleted = "ALTER TABLE oxorderarticles ADD COLUMN FCPOCAPTUREDPRICECOMPLETED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderarticlesDebitedPrice = "ALTER TABLE oxorderarticles ADD COLUMN FCPODEBITEDPRICE DOUBLE DEFAULT 0 NOT NULL;";
    public static $sQueryAlterOxorderDelcostDebited = "ALTER TABLE oxorder ADD COLUMN FCPODELCOSTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderPaycostDebited = "ALTER TABLE oxorder ADD COLUMN FCPOPAYCOSTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderWrapcostDebited = "ALTER TABLE oxorder ADD COLUMN FCPOWRAPCOSTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderGiftcardcostDebited = "ALTER TABLE oxorder ADD COLUMN FCPOGIFTCARDCOSTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderVoucherdiscountDebited = "ALTER TABLE oxorder ADD COLUMN FCPOVOUCHERDISCOUNTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderDiscountDebited = "ALTER TABLE oxorder ADD COLUMN FCPODISCOUNTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderNotChecked = "ALTER TABLE oxorder ADD COLUMN FCPOORDERNOTCHECKED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderWorkOrderId = "ALTER TABLE oxorder ADD COLUMN FCPOWORKORDERID VARCHAR(16) DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderClearingReference = "ALTER TABLE oxorder ADD COLUMN FCPOCLEARINGREFERENCE VARCHAR(32) DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderProfileIdent = "ALTER TABLE oxorder ADD COLUMN FCPOPROFILEIDENT VARCHAR(32) DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderIban = "ALTER TABLE oxorder ADD COLUMN FCPOIBAN VARCHAR(32) DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderBic = "ALTER TABLE oxorder ADD COLUMN FCPOBIC VARCHAR(32) DEFAULT '' NOT NULL;";
    public static $sQueryChangeToVarchar1 = "ALTER TABLE fcpotransactionstatus CHANGE FCPO_USERID FCPO_USERID VARCHAR(32) DEFAULT '0' NOT NULL;";
    public static $sQueryChangeToVarchar2 = "ALTER TABLE fcpotransactionstatus CHANGE FCPO_TXID FCPO_TXID VARCHAR(32) DEFAULT '0' NOT NULL;";
    public static $sQueryChangeRefNrToVarchar = "ALTER TABLE oxorder CHANGE FCPOREFNR FCPOREFNR VARCHAR( 32 ) NOT NULL DEFAULT '0'";
    public static $aPaymentMethods = array(
        'fcpoinvoice' => 'Rechnung',
        'fcpopayadvance' => 'Vorauskasse',
        'fcpodebitnote' => 'Bankeinzug/Lastschrift',
        'fcpocashondel' => 'Nachnahme',
        'fcpocreditcard' => 'Kreditkarte',
        'fcpoonlineueberweisung' => 'Online-Ueberweisung',
        'fcpopaypal' => 'PayPal',
        'fcpopaypal_express' => 'PayPal Express',
        'fcpoklarna' => 'Klarna Rechnung',
        'fcpoklarna_invoice' => 'Klarna Pay Later',
        'fcpoklarna_installments' => 'Klarna Slice It',
        'fcpoklarna_directdebit' => 'Klarna Pay Now',
        'fcpobarzahlen' => 'Barzahlen',
        'fcpopaydirekt' => 'Paydirekt',
        'fcpopo_bill' => 'Unzer Rechnungskauf',
        'fcpopo_debitnote' => 'Unzer Lastschrift',
        'fcpopo_installment' => 'Unzer Ratenkauf',
        'fcporp_bill' => 'Ratepay Rechnungskauf',
        'fcpoamazonpay' => 'AmazonPay',
        'fcpo_secinvoice' => 'Gesicherter Rechnungskauf',
        'fcpopaydirekt_express' => 'Paydirekt Express',
        'fcpo_sofort' => 'Sofortüberweisung',
        'fcpo_giropay' => 'Giropay',
        'fcpo_eps' => 'eps - Onlineüberweisung',
        'fcpo_pf_finance' => 'PostFinance E-Finance',
        'fcpo_pf_card' => 'PostFinance Card',
        'fcpo_ideal' => 'iDeal',
        'fcpo_p24' => 'P24',
        'fcpo_bancontact' => 'Bancontact',
        'fcpo_trustly' => 'Trustly',
        'fcpo_wechatpay'=> 'WeChatPay',
        'fcpo_alipay' => 'Alipay',
    );

    /**
     * Execute action on activate event.
     * 
     * @return void
     */
    public static function onActivate()
    {
        $sMessage = "";
        self::$_oFcpoHelper = oxNew('fcpohelper');
        self::addDatabaseStructure();
        $sMessage .= "Datenbankstruktur angepasst...<br>";
        self::addPayonePayments();
        $sMessage .= "Payone-Zahlarten hinzugef&uuml;gt...<br>";
        self::removeDeprecated();
        $sMessage .= "Veraltete Eintr&auml;ge entfernt...<br>";
        self::regenerateViews();
        $sMessage .= "Datenbank-Views erneuert...<br>";
        self::setDefaultConfigValues();
        self::clearTmp();
        $sMessage .= "Tmp geleert...<br>";
        $sMessage .= "Installation erfolgreich!<br>";
        self::$_oFcpoHelper->fcpoGetUtilsView()->addErrorToDisplay($sMessage, false, true);
    }

    /**
     * Execute action on deactivate event.
     * 
     * @return void
     */
    public static function onDeactivate()
    {
        self::$_oFcpoHelper = oxNew('fcpohelper');
        self::deactivatePaymethods();
        $sMessage = "Payone-Zahlarten deaktiviert!<br>";
        self::clearTmp();
        $sMessage .= "Tmp geleert...<br>";
        self::$_oFcpoHelper->fcpoGetUtilsView()->addErrorToDisplay($sMessage, false, true);
    }

    /**
     * Regenerates database view-tables.
     * 
     * @return void
     */
    public static function regenerateViews()
    {
        $oShop = oxNew('oxShop');
        $oShop->generateViews();
    }

    /**
     * Clear tmp dir and smarty cache.
     * 
     * @return void
     */
    public static function clearTmp()
    {
        $sTmpDir = getShopBasePath() . "/tmp/";
        $sSmartyDir = $sTmpDir . "smarty/";

        foreach (glob($sTmpDir . "*.txt") as $sFileName) {
            unlink($sFileName);
        }
        foreach (glob($sSmartyDir . "*.php") as $sFileName) {
            unlink($sFileName);
        }
    }

    /**
     * Adding payone payments.
     * 
     * @return void
     */
    public static function addPayonePayments()
    {
        $oDb = oxDb::getDb();

        foreach (self::$aPaymentMethods as $sPaymentOxid => $sPaymentName) {
            //INSERT PAYMENT METHOD
            self::insertRowIfNotExists('oxpayments', array('OXID' => $sPaymentOxid), "INSERT INTO oxpayments(OXID,OXACTIVE,OXDESC,OXADDSUM,OXADDSUMTYPE,OXFROMBONI,OXFROMAMOUNT,OXTOAMOUNT,OXVALDESC,OXCHECKED,OXDESC_1,OXVALDESC_1,OXDESC_2,OXVALDESC_2,OXDESC_3,OXVALDESC_3,OXLONGDESC,OXLONGDESC_1,OXLONGDESC_2,OXLONGDESC_3,OXSORT,FCPOISPAYONE,FCPOAUTHMODE,FCPOLIVEMODE) VALUES ('{$sPaymentOxid}', 0, '{$sPaymentName}', 0, 'abs', 0, 0, 1000000, '', 0, '{$sPaymentName}', '', '', '', '', '', '', '', '', '', 0, 1, 'preauthorization', 0);");

            //INSERT PAYMENT METHOD CONFIGURATION
            $blInserted = self::insertRowIfNotExists('oxobject2group', array('OXSHOPID' => $sShopId, 'OXOBJECTID' => $sPaymentOxid), "INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidadmin');");
            if ($blInserted === true) {
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidcustomer');");
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxiddealer');");
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidforeigncustomer');");
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidgoodcust');");
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidmiddlecust');");
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidnewcustomer');");
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidnewsletter');");
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidnotyetordered');");
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidpowershopper');");
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidpricea');");
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidpriceb');");
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidpricec');");
                $oDb->Execute("INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidsmallcust');");
            }

            self::insertRowIfNotExists('oxobject2payment', array('OXPAYMENTID' => $sPaymentOxid, 'OXTYPE' => 'oxdelset'), "INSERT INTO oxobject2payment(OXID,OXPAYMENTID,OXOBJECTID,OXTYPE) values (MD5(CONCAT(NOW(),RAND())), '{$sPaymentOxid}', 'oxidstandard', 'oxdelset');");
        }
    }

    /**
     * Removing depreacted stuff.
     * 
     * @return void
     */
    public static function removeDeprecated()
    {
        foreach (self::$_aRemovedPaymentMethods as $sRemovedPaymentMethod) {
            self::dropRowIfExists("oxpayments", array('OXID' => $sRemovedPaymentMethod), "DELETE FROM oxpayments WHERE OXID='" . $sRemovedPaymentMethod . "'");
            self::dropRowIfExists("oxobject2group", array('OXOBJECTID' => $sRemovedPaymentMethod), "DELETE FROM oxobject2group WHERE oxobjectid='" . $sRemovedPaymentMethod . "'");
            self::dropRowIfExists("oxobject2payment", array('OXPAYMENTID' => $sRemovedPaymentMethod), "DELETE FROM oxobject2payment WHERE oxpaymentid='" . $sRemovedPaymentMethod . "'");
        }
    }

    /**
     * Creating database structure changes.
     * 
     * @return void
     */
    public static function addDatabaseStructure()
    {
        //CREATE NEW TABLES
        self::addTableIfNotExists('fcporefnr', self::$sQueryTableFcporefnr);
        self::addTableIfNotExists('fcporequestlog', self::$sQueryTableFcporequestlog);
        self::addTableIfNotExists('fcpotransactionstatus', self::$sQueryTableFcpotransactionstatus);
        self::addTableIfNotExists('fcpopayment2country', self::$sQueryTableFcpopayment2country);
        self::addTableIfNotExists('fcpocheckedaddresses', self::$sQueryTableFcpocheckedaddresses);
        self::addTableIfNotExists('fcposhadowbasket', self::$sQueryTableFcpoShadowBasket);
        self::addTableIfNotExists('fcpostatusforwarding', self::$sQueryTableFcpoStatusForwarding);
        self::addTableIfNotExists('fcpostatusmapping', self::$sQueryTableFcpoStatusMapping);
        self::addTableIfNotExists('fcpoerrormapping', self::$sQueryTableFcpoErrorMapping);
        self::addTableIfNotExists('fcpoklarnastoreids', self::$sQueryTableFcpoklarnastoreids);
        self::addTableIfNotExists('fcpoklarnacampaigns', self::$sQueryTableFcpoklarnacampaigns);
        self::addTableIfNotExists('fcpopdfmandates', self::$sQueryTableFcpoPdfMandates);
        self::addTableIfNotExists('fcpopayoneexpresslogos', self::$sQueryTableFcpopaypalexpresslogos);
        self::addTableIfNotExists('fcporatepay', self::$sQueryTableRatePay);
        self::addTableIfNotExists('fcpouserflags', self::$sQueryTableFcpoUserFlags);
        self::addTableIfNotExists('fcpouser2flag', self::$sQueryTableFcpoUser2Flag);

        //ADD COLUMNS TO EXISTING TABLES
        self::addColumnIfNotExists('oxorder', 'FCPOTXID', self::$sQueryAlterOxorderTxid);
        self::addColumnIfNotExists('oxorder', 'FCPOREFNR', self::$sQueryAlterOxorderRefNr);
        self::addColumnIfNotExists('oxorder', 'FCPOAUTHMODE', self::$sQueryAlterOxorderAuthMode);
        self::addColumnIfNotExists('oxorder', 'FCPOMODE', self::$sQueryAlterOxorderMode);

        self::addColumnIfNotExists('oxorder', 'FCPODELCOSTDEBITED', self::$sQueryAlterOxorderDelcostDebited);
        self::addColumnIfNotExists('oxorder', 'FCPOPAYCOSTDEBITED', self::$sQueryAlterOxorderPaycostDebited);
        self::addColumnIfNotExists('oxorder', 'FCPOWRAPCOSTDEBITED', self::$sQueryAlterOxorderWrapcostDebited);
        self::addColumnIfNotExists('oxorder', 'FCPOGIFTCARDCOSTDEBITED', self::$sQueryAlterOxorderGiftcardcostDebited);
        self::addColumnIfNotExists('oxorder', 'FCPOVOUCHERDISCOUNTDEBITED', self::$sQueryAlterOxorderVoucherdiscountDebited);
        self::addColumnIfNotExists('oxorder', 'FCPODISCOUNTDEBITED', self::$sQueryAlterOxorderDiscountDebited);
        self::addColumnIfNotExists('oxorder', 'FCPOORDERNOTCHECKED', self::$sQueryAlterOxorderNotChecked);
        self::addColumnIfNotExists('oxorder', 'FCPOWORKORDERID', self::$sQueryAlterOxorderWorkOrderId);
        self::addColumnIfNotExists('oxorder', 'FCPOCLEARINGREFERENCE', self::$sQueryAlterOxorderClearingReference);
        self::addColumnIfNotExists('oxorder', 'FCPOPROFILEIDENT', self::$sQueryAlterOxorderProfileIdent);
        self::addColumnIfNotExists('oxorder', 'FCPOIBAN', self::$sQueryAlterOxorderIban);
        self::addColumnIfNotExists('oxorder', 'FCPOBIC', self::$sQueryAlterOxorderBic);

        self::addColumnIfNotExists('oxorderarticles', 'FCPOCAPTUREDAMOUNT', self::$sQueryAlterOxorderarticlesCapturedAmount);
        self::addColumnIfNotExists('oxorderarticles', 'FCPODEBITEDAMOUNT', self::$sQueryAlterOxorderarticlesDebitedAmount);
        self::addColumnIfNotExists('oxorderarticles', 'FCPOCAPTUREDPRICE', self::$sQueryAlterOxorderarticlesCapturedPrice);
        self::addColumnIfNotExists('oxorderarticles', 'FCPOCAPTUREDPRICECOMPLETED', self::$sQueryAlterOxorderarticlesCapturedPriceCompleted);
        self::addColumnIfNotExists('oxorderarticles', 'FCPODEBITEDPRICE', self::$sQueryAlterOxorderarticlesDebitedPrice);

        self::addColumnIfNotExists('oxpayments', 'FCPOISPAYONE', self::$sQueryAlterOxpaymentsIsPayone);
        self::addColumnIfNotExists('oxpayments', 'FCPOAUTHMODE', self::$sQueryAlterOxpaymentsAuthMode);
        self::addColumnIfNotExists('oxpayments', 'FCPOLIVEMODE', self::$sQueryAlterOxpaymentsLiveMode);

        self::addColumnIfNotExists('oxuser', 'FCPOBONICHECKDATE', self::$sQueryAlterOxuser);
        self::addColumnIfNotExists('oxuser', 'FCPOPERSONALID', self::$sQueryAlterOxuserPersonalId);
        self::addColumnIfNotExists('oxuser', 'FCPOCURRMALUS', self::$sQueryAlterOxuserCurrentMalus);
        self::addColumnIfNotExists('oxuser', 'FCPOREALBONI', self::$sQueryAlterOxuserRealBoni);

        self::addColumnIfNotExists('fcporefnr', 'FCPO_REFPREFIX', self::$sQueryAlterFcporefnr);

        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKACCOUNTHOLDER', self::$sQueryAlterTxStatusClearing1);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKACCOUNT', self::$sQueryAlterTxStatusClearing2);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKCODE', self::$sQueryAlterTxStatusClearing3);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKNAME', self::$sQueryAlterTxStatusClearing4);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKBIC', self::$sQueryAlterTxStatusClearing5);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKIBAN', self::$sQueryAlterTxStatusClearing6);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_LEGALNOTE', self::$sQueryAlterTxStatusClearing7);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_DUEDATE', self::$sQueryAlterTxStatusClearing8);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_REFERENCE', self::$sQueryAlterTxStatusClearing9);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_INSTRUCTIONNOTE', self::$sQueryAlterTxStatusClearing10);

        self::addColumnIfNotExists('fcpoklarnacampaigns', 'FCPO_CAMPAIGN_LANGUAGE', self::$sQueryAlterCampaign1);
        self::addColumnIfNotExists('fcpoklarnacampaigns', 'FCPO_CAMPAIGN_CURRENCY', self::$sQueryAlterCampaign2);

        self::changeColumnTypeIfWrong('fcpotransactionstatus', 'FCPO_USERID', 'varchar(32)', self::$sQueryChangeToVarchar1);
        self::changeColumnTypeIfWrong('fcpotransactionstatus', 'FCPO_TXID', 'varchar(32)', self::$sQueryChangeToVarchar2);
        self::changeColumnTypeIfWrong('fcporequestlog', 'FCPO_REFNR', 'varchar(32)', self::$sQueryChangeFcporequestlog);
        self::changeColumnTypeIfWrong('oxorder', 'FCPOREFNR', 'varchar(32)', self::$sQueryChangeRefNrToVarchar);

        self::dropIndexIfExists('fcporefnr', 'FCPO_REFNR');

        //ADD PAYPAL EXPRESS LOGOS
        self::insertRowIfNotExists('fcpopayoneexpresslogos', array('OXID' => '1'), "INSERT INTO fcpopayoneexpresslogos (OXID, FCPO_ACTIVE, FCPO_LANGID, FCPO_LOGO, FCPO_DEFAULT) VALUES(1, 1, 0, 'btn_xpressCheckout_de.gif', 1);");
        self::insertRowIfNotExists('fcpopayoneexpresslogos', array('OXID' => '2'), "INSERT INTO fcpopayoneexpresslogos (OXID, FCPO_ACTIVE, FCPO_LANGID, FCPO_LOGO, FCPO_DEFAULT) VALUES(2, 1, 1, 'btn_xpressCheckout_en.gif', 0);");
        // add available user flags
        self::insertRowIfNotExists('fcpouserflags', array('OXID' => 'fcporatepayrejected'), "INSERT INTO fcpouserflags (OXID, FCPOCODE, FCPOEFFECT, FCPOFLAGDURATION, FCPONAME, FCPODESC) VALUES ('fcporatepayrejected', 307, 'RPR', 24, 'Ratepay Rejected', 'CUSTOM');");
    }

    /**
     * Add a database table.
     * 
     * @param string $sTableName table to add
     * @param string $sQuery     sql-query to add table
     * 
     * @return boolean true or false
     */
    public static function addTableIfNotExists($sTableName, $sQuery)
    {
        if (oxDb::getDb()->Execute("SHOW TABLES LIKE '{$sTableName}'")->EOF) {
            oxDb::getDb()->Execute($sQuery);
            // echo 'Tabelle '.$sTableName.' hinzugef&uuml;gt.<br>';
            return true;
        }
        return false;
    }

    /**
     * Add a column to a database table.
     * 
     * @param string $sTableName  table name
     * @param string $sColumnName column name
     * @param string $sQuery      sql-query to add column to table
     * 
     * @return boolean true or false
     */
    public static function addColumnIfNotExists($sTableName, $sColumnName, $sQuery)
    {
        if (oxDb::getDb()->Execute("SHOW COLUMNS FROM {$sTableName} LIKE '{$sColumnName}'")->EOF) {
            oxDb::getDb()->Execute($sQuery);
            // echo 'In Tabelle '.$sTableName.' Spalte '.$sColumnName.' hinzugef&uuml;gt.<br>';
            return true;
        }
        return false;
    }

    /**
     * Insert a database row to an existing table.
     * 
     * @param string $sTableName database table name
     * @param array  $aKeyValue  keys of rows to add for existance check
     * @param string $sQuery     sql-query to insert data
     * 
     * @return boolean true or false
     */
    public static function insertRowIfNotExists($sTableName, $aKeyValue, $sQuery)
    {
        $oDb = oxDb::getDb();

        $sWhere = '';
        foreach ($aKeyValue as $key => $value) {
            $sWhere .= " AND $key = '$value'";
        }

        $sCheckQuery = "SELECT * FROM {$sTableName} WHERE 1" . $sWhere;
        $mResult = $oDb->getOne($sCheckQuery);

        if ($mResult !== false) return false;
        $oDb->execute($sQuery);

        return true;
    }

    /**
     * Check and change database table structure.
     * 
     * @param string $sTableName    database table name
     * @param string $sColumnName   database column name
     * @param string $sExpectedType column structure type for comparison
     * @param string $sQuery        sql-query to execute
     * 
     * @return boolean true or false
     */
    public static function changeColumnTypeIfWrong($sTableName, $sColumnName, $sExpectedType, $sQuery)
    {
        if (oxDb::getDb()->Execute("SHOW COLUMNS FROM {$sTableName} WHERE FIELD = '{$sColumnName}' AND TYPE = '{$sExpectedType}'")->EOF) {
            oxDb::getDb()->Execute($sQuery);
            // echo 'In Tabelle '.$sTableName.' Spalte '.$sColumnName.' auf Typ '.$sExpectedType.' umgestellt.<br>';
            return true;
        }
        return false;
    }

    /**
     * Delete a database index.
     * 
     * @param string $sTable database table name
     * @param string $sIndex database index name
     * 
     * @return boolean true or false
     */
    public static function dropIndexIfExists($sTable, $sIndex)
    {
        if (!oxDb::getDb()->Execute("SHOW KEYS FROM {$sTable} WHERE Key_name = '{$sIndex}'")->EOF) {
            oxDb::getDb()->Execute("ALTER TABLE {$sTable} DROP INDEX {$sIndex}");
            // echo "In Tabelle {$sTable} den Index {$sIndex} entfernt.<br>";
            return true;
        }
        return false;
    }

    /**
     * Drop a table entry.
     * 
     * @param string $sTableName database table name
     * @param array  $aKeyValue  array of keys to drop
     * @param string $sQuery     sql-query to execute
     * 
     * @return boolean
     */
    public static function dropRowIfExists($sTableName, $aKeyValue, $sQuery)
    {
        $oDb = oxDb::getDb();

        $sWhere = '';
        foreach ($aKeyValue as $key => $value) {
            $sWhere .= " AND $key = '$value'";
        }

        $sCheckQuery = "SELECT * FROM {$sTableName} WHERE 1" . $sWhere;
        $mResult = $oDb->getOne($sCheckQuery);

        if ($mResult === false) return false;
        $oDb->execute($sQuery);

        return true;
    }

    /**
     * Get the OXID eShop version.
     * 
     * @return string versionnumber
     */
    public static function getCurrentVersion()
    {
        return self::$_oFcpoHelper->fcpoGetConfig()->getActiveShop()->oxshops__oxversion->value;
    }

    /**
     * Checks if OXID eShop is below given version.
     * 
     * @param string $sMaxVersion maximum allowed version
     * 
     * @return boolean true or false
     */
    public static function isUnderVersion($sMaxVersion)
    {
        $sCurrVersion = self::getCurrentVersion();
        $blReturn = (version_compare($sCurrVersion, $sMaxVersion, '<')) ? true : false;
        
        return $blReturn;
    }

    /**
     * Checks if OXID eShop is above given version.
     * 
     * @param string  $sMinVersion      minimum allowed version
     * @param boolean $blEqualOrGreater define if equal is allowed
     * 
     * @return boolean true or false
     */  
    public static function isOverVersion($sMinVersion, $blEqualOrGreater = false)
    {
        $sCompareOperator = ($blEqualOrGreater) ? '>=' : '>';
        $sCurrVersion = self::getCurrentVersion();
        
        $blReturn = (version_compare($sCurrVersion, $sMinVersion, $sCompareOperator)) ? true : false;        
        
        return $blReturn;
    }

    /**
     * Checks if OXID eShop is between two given versions.
     * 
     * @param string $sMinVersion minimum allowed version number
     * @param string $sMaxVersion maximum allowed version number
     * 
     * @return boolean true or false
     */
    public static function isBetweenVersions($sMinVersion, $sMaxVersion)
    {
        if (!isOverVersion($sMinVersion, true)) {
            return false;
        }
        if (!isUnderVersion($sMaxVersion)) {
            return false;
        }
        return true;
    }

    /**
     * Copies a sourcefile to a destination.
     * 
     * @param string $sSource      path to source-file
     * @param string $sDestination path to destination-file
     * 
     * @return boolean true or false
     */
    public static function copyFile($sSource, $sDestination)
    {
        if (file_exists($sSource) === true) {
            self::deleteFileIfExists($sDestination);
            if (copy($sSource, $sDestination)) {
                echo 'Datei ' . $sDestination . ' in Theme kopiert.<br>';
            } else {
                echo '<span style="color:red;">ERROR:</span> Kopieren fehlgeschlagen. Bitte kopieren Sie die Datei manuell von "' . $sSource . '" nach "' . $sDestination . '"<br>';
            }
        }
    }
    
    /**
     * Checks if given file exists and deletes if it exists.
     * 
     * @param string $sFile path to file
     * 
     * @return void
     */
    public static function deleteFileIfExists($sFile)
    {
        if (file_exists($sFile)) {
            unlink($sFile);
        }
    }

    /**
     * Deactivates payone paymethods on module deactivation.
     * 
     * @return void
     */
    public static function deactivatePaymethods()
    {
        $sPaymenthodIds = "'" . implode("','", array_keys(self::$aPaymentMethods)) . "'";
        $sQ = "update oxpayments set oxactive = 0 where oxid in ($sPaymenthodIds)";
        oxDB::getDB()->Execute($sQ);
    }

    /**
     * Sets default config values on activation.
     *
     * @return void
     */
    public static function setDefaultConfigValues()
    {
        $oConfig = self::$_oFcpoHelper->fcpoGetConfig();
        $blIsUpdate = self::isUpdate();
        $blHashMethodSet = (bool) $oConfig->getConfigParam('sFCPOHashMethod');


        if (!$blHashMethodSet && $blIsUpdate) {
            $oConfig->saveShopConfVar('str', 'sFCPOHashMethod', 'md5');
        } elseif (!$blHashMethodSet) {
            $oConfig->saveShopConfVar('str', 'sFCPOHashMethod', 'sha2-384');
        }

        if (!$oConfig->getConfigParam('sFCPOAddresscheck')) {
            $oConfig->saveShopConfVar('str', 'sFCPOAddresscheck', 'NO');
        }
    }
    /**
     * If there is an existing merchant id we assume, that current activation
     * is an update
     *
     * @param void
     * @return bool
     */
    public static function isUpdate()
    {
        $oConfig = self::$_oFcpoHelper->fcpoGetConfig();
        return (bool) ($oConfig->getConfigParam('sFCPOMerchantID'));
    }
}
