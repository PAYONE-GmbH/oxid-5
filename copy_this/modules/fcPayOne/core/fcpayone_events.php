<?php

/** 
 * PAYONE OXID Connector is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PAYONE OXID Connector is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PAYONE OXID Connector.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.payone.de
 * @copyright (C) Payone GmbH
 * @version   OXID eShop CE
 */
 
class fcpayone_events {
    
    /**
     * Database object
     * @var object
     */
    protected static $_oFcpoHelper = null;
    
    /**
     * Paymnts that were once used but now deprecated and marked for removal
     * @var array
     */
    public static $_aRemovedPaymentMethods = array(
        'fcpoyapital',
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
    
    public static $sQueryAlterOxorderTxid                   = "ALTER TABLE oxorder ADD COLUMN FCPOTXID VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderRefNr                  = "ALTER TABLE oxorder ADD COLUMN FCPOREFNR INT(11) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderAuthMode               = "ALTER TABLE oxorder ADD COLUMN FCPOAUTHMODE VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterOxorderMode                   = "ALTER TABLE oxorder ADD COLUMN FCPOMODE VARCHAR(8) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";

    public static $sQueryAlterOxpaymentsAuthMode            = "ALTER TABLE oxpayments ADD COLUMN FCPOAUTHMODE VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    
    public static $sQueryAlterTxStatusClearing1             = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKACCOUNTHOLDER VARCHAR(64) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing2             = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKACCOUNT VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing3             = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKCODE VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing4             = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKNAME VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing5             = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKBIC VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing6             = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_BANKIBAN VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing7             = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_LEGALNOTE VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing8             = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_DUEDATE VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing9             = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_REFERENCE VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterTxStatusClearing10            = "ALTER TABLE fcpotransactionstatus ADD COLUMN FCPO_CLEARING_INSTRUCTIONNOTE VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    
    public static $sQueryAlterOxuserPersonalId              = "ALTER TABLE oxuser ADD COLUMN FCPOPERSONALID VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    public static $sQueryAlterFcporefnr                     = "ALTER TABLE fcporefnr ADD COLUMN FCPO_REFPREFIX VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY(FCPO_REFNR, FCPO_REFPREFIX);";

    public static $sQueryChangeFcporequestlog               = "ALTER TABLE fcporequestlog CHANGE FCPO_REFNR FCPO_REFNR VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' NOT NULL;";
    
    public static $sQueryAlterCampaign1                     = "ALTER TABLE fcpoklarnacampaigns ADD FCPO_CAMPAIGN_LANGUAGE VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';";
    public static $sQueryAlterCampaign2                     = "ALTER TABLE fcpoklarnacampaigns ADD FCPO_CAMPAIGN_CURRENCY VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';";

    public static $sQueryAlterOxuser                        = "ALTER TABLE oxuser ADD COLUMN FCPOBONICHECKDATE DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL;";

    public static $sQueryAlterOxpaymentsLiveMode            = "ALTER TABLE oxpayments ADD COLUMN FCPOLIVEMODE TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxpaymentsIsPayone            = "ALTER TABLE oxpayments ADD COLUMN FCPOISPAYONE TINYINT(1) DEFAULT '0' NOT NULL;";

    public static $sQueryAlterOxorderarticlesCapturedAmount = "ALTER TABLE oxorderarticles ADD COLUMN FCPOCAPTUREDAMOUNT INT(11) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderarticlesDebitedAmount  = "ALTER TABLE oxorderarticles ADD COLUMN FCPODEBITEDAMOUNT INT(11) DEFAULT '0' NOT NULL;";

    public static $sQueryAlterOxorderDelcostDebited         = "ALTER TABLE oxorder ADD COLUMN FCPODELCOSTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderPaycostDebited         = "ALTER TABLE oxorder ADD COLUMN FCPOPAYCOSTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderWrapcostDebited        = "ALTER TABLE oxorder ADD COLUMN FCPOWRAPCOSTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderGiftcardcostDebited    = "ALTER TABLE oxorder ADD COLUMN FCPOGIFTCARDCOSTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderVoucherdiscountDebited = "ALTER TABLE oxorder ADD COLUMN FCPOVOUCHERDISCOUNTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderDiscountDebited        = "ALTER TABLE oxorder ADD COLUMN FCPODISCOUNTDEBITED TINYINT(1) DEFAULT '0' NOT NULL;";
    public static $sQueryAlterOxorderNotChecked             = "ALTER TABLE oxorder ADD COLUMN FCPOORDERNOTCHECKED TINYINT(1) DEFAULT '0' NOT NULL;";

    public static $sQueryChangeToVarchar1                   = "ALTER TABLE fcpotransactionstatus CHANGE FCPO_USERID FCPO_USERID VARCHAR(32) DEFAULT '0' NOT NULL;";
    public static $sQueryChangeToVarchar2                   = "ALTER TABLE fcpotransactionstatus CHANGE FCPO_TXID FCPO_TXID VARCHAR(32) DEFAULT '0' NOT NULL;";

    public static $sQueryChangeRefNrToVarchar               = "ALTER TABLE oxorder CHANGE FCPOREFNR FCPOREFNR VARCHAR( 32 ) NOT NULL DEFAULT '0'";
    
    public static $aPaymentMethods = array(
        'fcpoinvoice' => 'Rechnung',
        'fcpopayadvance' => 'Vorauskasse',
        'fcpodebitnote' => 'Bankeinzug/Lastschrift',
        'fcpocashondel' => 'Nachnahme',
        'fcpocreditcard' => 'Kreditkarte Channel Client-API',
        'fcpocreditcard_iframe' => 'Kreditkarte Channel Frontend',
        'fcpoonlineueberweisung' => 'Online-Ueberweisung',
        'fcpopaypal' => 'PayPal',
        'fcpopaypal_express' => 'PayPal Express',
        'fcpocommerzfinanz' => 'Commerz Finanz',
        'fcpobillsafe' => 'BillSAFE',
        'fcpoklarna' => 'Klarna Rechnung',
        'fcpoklarna_installment' => 'Klarna Ratenkauf',
        'fcpobarzahlen' => 'Barzahlen',
        'fcpopaydirekt' => 'Paydirekt',        
    );
        

    
    
    /**
     * Execute action on activate event
     */
    public static function onActivate() {
        $sMessage = "";
        self::$_oFcpoHelper = oxNew('fcpohelper');
        $oFcpoHelper = oxNew('fcpohelper');
        self::addDatabaseStructure();
        $sMessage .= "Datenbankstruktur angepasst...<br>";
        self::addPayonePayments();
        $sMessage .= "Payone-Zahlarten hinzugef&uuml;gt...<br>";
        self::removeDeprecated();
        $sMessage .= "Veraltete Eintr&auml;ge entfernt...<br>";
        self::regenerateViews();
        $sMessage .= "Datenbank-Views erneuert...<br>";
        self::clearTmp();
        $sMessage .= "Tmp geleert...<br>";
        $sMessage .= "Installation erfolgreich!<br>";
        
        self::$_oFcpoHelper->fcpoGetUtilsView()->addErrorToDisplay($sMessage, false, true);
    }
    
    
    /**
     * Execute action on deactivate event
     */
    public static function onDeactivate() {
        // currently there is no action planned
    }
    
    
    public static function regenerateViews() {
        $oShop = oxNew('oxShop');
        $oShop->generateViews();
    }
    
    
    public static function clearTmp() {
        $sTmpDir    = getShopBasePath()."/tmp/";
        $sSmartyDir = $sTmpDir."smarty/";
        
        foreach (glob($sTmpDir."*.txt") as $sFileName) {
            unlink($sFileName);
        }
        foreach (glob($sSmartyDir."*.php") as $sFileName) {
            unlink($sFileName);
        }
    }


    /**
     * Adding payone payments
     * 
     * @param void
     * @return void
     */
    public static function addPayonePayments() {
        $oDb = oxDb::getDb();
        
        foreach (self::$aPaymentMethods as $sPaymentOxid => $sPaymentName) {
            //INSERT PAYMENT METHOD
            if($sPaymentOxid == 'fcpocommerzfinanz') {
                self::insertRowIfNotExists('oxpayments', array('OXID' => $sPaymentOxid), "INSERT INTO oxpayments(OXID,OXACTIVE,OXDESC,OXADDSUM,OXADDSUMTYPE,OXFROMBONI,OXFROMAMOUNT,OXTOAMOUNT,OXVALDESC,OXCHECKED,OXDESC_1,OXVALDESC_1,OXDESC_2,OXVALDESC_2,OXDESC_3,OXVALDESC_3,OXLONGDESC,OXLONGDESC_1,OXLONGDESC_2,OXLONGDESC_3,OXSORT,FCPOISPAYONE,FCPOAUTHMODE,FCPOLIVEMODE) VALUES ('{$sPaymentOxid}', 1, '{$sPaymentName}', 0, 'abs', 0, 100, 5000, '', 0, '{$sPaymentName}', '', '', '', '', '', '', '', '', '', 0, 1, 'preauthorization', 0);");
            } else {
                self::insertRowIfNotExists('oxpayments', array('OXID' => $sPaymentOxid), "INSERT INTO oxpayments(OXID,OXACTIVE,OXDESC,OXADDSUM,OXADDSUMTYPE,OXFROMBONI,OXFROMAMOUNT,OXTOAMOUNT,OXVALDESC,OXCHECKED,OXDESC_1,OXVALDESC_1,OXDESC_2,OXVALDESC_2,OXDESC_3,OXVALDESC_3,OXLONGDESC,OXLONGDESC_1,OXLONGDESC_2,OXLONGDESC_3,OXSORT,FCPOISPAYONE,FCPOAUTHMODE,FCPOLIVEMODE) VALUES ('{$sPaymentOxid}', 1, '{$sPaymentName}', 0, 'abs', 0, 0, 1000000, '', 0, '{$sPaymentName}', '', '', '', '', '', '', '', '', '', 0, 1, 'preauthorization', 0);");
            }

            //INSERT PAYMENT METHOD CONFIGURATION
            $blInserted = self::insertRowIfNotExists('oxobject2group', array('OXSHOPID' => $sShopId, 'OXOBJECTID' => $sPaymentOxid), "INSERT INTO oxobject2group(OXID,OXSHOPID,OXOBJECTID,OXGROUPSID) values (MD5(CONCAT(NOW(), RAND())), '{$sShopId}', '{$sPaymentOxid}', 'oxidadmin');");
            if($blInserted === true) {
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
     * Removing depreacted stuff
     * 
     * @param void
     * @return void
     */
    public static function removeDeprecated() {
        foreach (self::$_aRemovedPaymentMethods as $sRemovedPaymentMethod) {
            self::dropRowIfExists("oxpayments", array('OXID' => $sRemovedPaymentMethod), "DELETE FROM oxpayments WHERE OXID='".$sRemovedPaymentMethod."'");
            self::dropRowIfExists("oxobject2group", array('OXOBJECTID' => $sRemovedPaymentMethod), "DELETE FROM oxobject2group WHERE oxobjectid='".$sRemovedPaymentMethod."'");
            self::dropRowIfExists("oxobject2payment", array('OXPAYMENTID' => $sRemovedPaymentMethod), "DELETE FROM oxobject2payment WHERE oxpaymentid='".$sRemovedPaymentMethod."'");
        }
    }


    /**
     * Creating database structure changes
     * 
     * @param void
     * @return void
     */
    public static function addDatabaseStructure() {
        //CREATE NEW TABLES
        self::addTableIfNotExists('fcporefnr',              self::$sQueryTableFcporefnr);
        self::addTableIfNotExists('fcporequestlog',         self::$sQueryTableFcporequestlog);
        self::addTableIfNotExists('fcpotransactionstatus',  self::$sQueryTableFcpotransactionstatus);
        self::addTableIfNotExists('fcpopayment2country',    self::$sQueryTableFcpopayment2country);
        self::addTableIfNotExists('fcpocheckedaddresses',   self::$sQueryTableFcpocheckedaddresses);
        self::addTableIfNotExists('fcpostatusforwarding',   self::$sQueryTableFcpoStatusForwarding);
        self::addTableIfNotExists('fcpostatusmapping',      self::$sQueryTableFcpoStatusMapping);
        self::addTableIfNotExists('fcpoklarnastoreids',     self::$sQueryTableFcpoklarnastoreids);
        self::addTableIfNotExists('fcpoklarnacampaigns',    self::$sQueryTableFcpoklarnacampaigns);
        self::addTableIfNotExists('fcpopdfmandates',        self::$sQueryTableFcpoPdfMandates);
        self::addTableIfNotExists('fcpopayoneexpresslogos', self::$sQueryTableFcpopaypalexpresslogos);

        //ADD COLUMNS TO EXISTING TABLES
        self::addColumnIfNotExists('oxorder',               'FCPOTXID',                         self::$sQueryAlterOxorderTxid);
        self::addColumnIfNotExists('oxorder',               'FCPOREFNR',                        self::$sQueryAlterOxorderRefNr);
        self::addColumnIfNotExists('oxorder',               'FCPOAUTHMODE',                     self::$sQueryAlterOxorderAuthMode);
        self::addColumnIfNotExists('oxorder',               'FCPOMODE',                         self::$sQueryAlterOxorderMode);

        self::addColumnIfNotExists('oxorder',               'FCPODELCOSTDEBITED',               self::$sQueryAlterOxorderDelcostDebited);
        self::addColumnIfNotExists('oxorder',               'FCPOPAYCOSTDEBITED',               self::$sQueryAlterOxorderPaycostDebited);
        self::addColumnIfNotExists('oxorder',               'FCPOWRAPCOSTDEBITED',              self::$sQueryAlterOxorderWrapcostDebited);
        self::addColumnIfNotExists('oxorder',               'FCPOGIFTCARDCOSTDEBITED',          self::$sQueryAlterOxorderGiftcardcostDebited);
        self::addColumnIfNotExists('oxorder',               'FCPOVOUCHERDISCOUNTDEBITED',       self::$sQueryAlterOxorderVoucherdiscountDebited);
        self::addColumnIfNotExists('oxorder',               'FCPODISCOUNTDEBITED',              self::$sQueryAlterOxorderDiscountDebited);
        self::addColumnIfNotExists('oxorder',               'FCPOORDERNOTCHECKED',              self::$sQueryAlterOxorderNotChecked);

        self::addColumnIfNotExists('oxorderarticles',       'FCPOCAPTUREDAMOUNT',               self::$sQueryAlterOxorderarticlesCapturedAmount);
        self::addColumnIfNotExists('oxorderarticles',       'FCPODEBITEDAMOUNT',                self::$sQueryAlterOxorderarticlesDebitedAmount);

        self::addColumnIfNotExists('oxpayments',            'FCPOISPAYONE',                     self::$sQueryAlterOxpaymentsIsPayone);
        self::addColumnIfNotExists('oxpayments',            'FCPOAUTHMODE',                     self::$sQueryAlterOxpaymentsAuthMode);
        self::addColumnIfNotExists('oxpayments',            'FCPOLIVEMODE',                     self::$sQueryAlterOxpaymentsLiveMode);

        self::addColumnIfNotExists('oxuser',                'FCPOBONICHECKDATE',                self::$sQueryAlterOxuser);
        self::addColumnIfNotExists('oxuser',                'FCPOPERSONALID',                   self::$sQueryAlterOxuserPersonalId);
        self::addColumnIfNotExists('fcporefnr',             'FCPO_REFPREFIX',                   self::$sQueryAlterFcporefnr);

        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKACCOUNTHOLDER',  self::$sQueryAlterTxStatusClearing1);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKACCOUNT',        self::$sQueryAlterTxStatusClearing2);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKCODE',           self::$sQueryAlterTxStatusClearing3);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKNAME',           self::$sQueryAlterTxStatusClearing4);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKBIC',            self::$sQueryAlterTxStatusClearing5);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_BANKIBAN',           self::$sQueryAlterTxStatusClearing6);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_LEGALNOTE',          self::$sQueryAlterTxStatusClearing7);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_DUEDATE',            self::$sQueryAlterTxStatusClearing8);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_REFERENCE',          self::$sQueryAlterTxStatusClearing9);
        self::addColumnIfNotExists('fcpotransactionstatus', 'FCPO_CLEARING_INSTRUCTIONNOTE',    self::$sQueryAlterTxStatusClearing10);

        self::addColumnIfNotExists('fcpoklarnacampaigns',   'FCPO_CAMPAIGN_LANGUAGE',           self::$sQueryAlterCampaign1);
        self::addColumnIfNotExists('fcpoklarnacampaigns',   'FCPO_CAMPAIGN_CURRENCY',           self::$sQueryAlterCampaign2);

        self::changeColumnTypeIfWrong('fcpotransactionstatus',  'FCPO_USERID',  'varchar(32)',  self::$sQueryChangeToVarchar1);
        self::changeColumnTypeIfWrong('fcpotransactionstatus',  'FCPO_TXID',    'varchar(32)',  self::$sQueryChangeToVarchar2);
        self::changeColumnTypeIfWrong('fcporequestlog',         'FCPO_REFNR',   'varchar(32)',  self::$sQueryChangeFcporequestlog);
        self::changeColumnTypeIfWrong('oxorder',                'FCPOREFNR',    'varchar(32)',  self::$sQueryChangeRefNrToVarchar);

        self::dropIndexIfExists('fcporefnr', 'FCPO_REFNR');

        //ADD PAYPAL EXPRESS LOGOS
        self::insertRowIfNotExists('fcpopayoneexpresslogos', array('OXID' => '1'), "INSERT INTO fcpopayoneexpresslogos (OXID, FCPO_ACTIVE, FCPO_LANGID, FCPO_LOGO, FCPO_DEFAULT) VALUES(1, 1, 0, 'btn_xpressCheckout_de.gif', 1);");
        self::insertRowIfNotExists('fcpopayoneexpresslogos', array('OXID' => '2'), "INSERT INTO fcpopayoneexpresslogos (OXID, FCPO_ACTIVE, FCPO_LANGID, FCPO_LOGO, FCPO_DEFAULT) VALUES(2, 1, 1, 'btn_xpressCheckout_en.gif', 0);");
    }
    
    
    
    public static function addTableIfNotExists($sTableName, $sQuery) {
        if(oxDb::getDb()->Execute("SHOW TABLES LIKE '{$sTableName}'")->EOF) {
            oxDb::getDb()->Execute($sQuery);
            #echo 'Tabelle '.$sTableName.' hinzugef&uuml;gt.<br>';
            return true;
        }
        return false;
    }
    

    public static function addColumnIfNotExists($sTableName, $sColumnName, $sQuery) {
        if(oxDb::getDb()->Execute("SHOW COLUMNS FROM {$sTableName} LIKE '{$sColumnName}'")->EOF) {
            oxDb::getDb()->Execute($sQuery);
            #echo 'In Tabelle '.$sTableName.' Spalte '.$sColumnName.' hinzugef&uuml;gt.<br>';
            return true;
        }
        return false;
    }
    

    public static function insertRowIfNotExists($sTableName, $aKeyValue, $sQuery) {
        $sWhere = '';
        foreach ($aKeyValue as $key => $value) {
            $sWhere .= " AND $key = '$value'";
        }
        if(oxDb::getDb()->Execute("SELECT * FROM {$sTableName} WHERE 1".$sWhere)->EOF) {
            oxDb::getDb()->Execute($sQuery);
            #echo 'In Tabelle '.$sTableName.' neuen Eintrag erstellt.<br>';
            return true;
        }
        return false;
    }
    

    public static function changeColumnTypeIfWrong($sTableName, $sColumnName, $sExpectedType, $sQuery) {
        if(oxDb::getDb()->Execute("SHOW COLUMNS FROM {$sTableName} WHERE FIELD = '{$sColumnName}' AND TYPE = '{$sExpectedType}'")->EOF) {
            oxDb::getDb()->Execute($sQuery);
            #echo 'In Tabelle '.$sTableName.' Spalte '.$sColumnName.' auf Typ '.$sExpectedType.' umgestellt.<br>';
            return true;
        }
        return false;
    }
    

    public static function dropIndexIfExists($sTable, $sIndex) {
        if(!oxDb::getDb()->Execute("SHOW KEYS FROM {$sTable} WHERE Key_name = '{$sIndex}'")->EOF) {
            oxDb::getDb()->Execute("ALTER TABLE {$sTable} DROP INDEX {$sIndex}");
            #echo "In Tabelle {$sTable} den Index {$sIndex} entfernt.<br>";
            return true;
        }
        return false;
    }
    
    
    public static function dropRowIfExists($sTableName, $aKeyValue, $sQuery) {
        $blReturn   = false;
        $sWhere     = '';
        
        foreach ($aKeyValue as $key => $value) {
            $sWhere .= " AND $key = '$value'";
        }
        if(oxDb::getDb()->Execute("SELECT * FROM {$sTableName} WHERE 1".$sWhere)->EOF) {
            oxDb::getDb()->Execute($sQuery);
            $blReturn = true;
        }
        
        return $blReturn;
    }
    

    public static function getCurrentVersion() {
        return versionToInt(self::$_oFcpoHelper->fcpoGetConfig()->getActiveShop()->oxshops__oxversion->value);
    }
    

    public static function versionToInt($sVersion) {
        $iVersion = (int)str_replace('.', '', $sVersion);
        while ($iVersion < 1000) {
            $iVersion = $iVersion*10;
        }
        return $iVersion;
    }
    

    public static function isUnderVersion($sMaxVersion) {
        $iMaxVersion    = self::versionToInt($sMaxVersion);
        $iCurrVersion   = self::getCurrentVersion();
        if($iCurrVersion < $iMaxVersion) {
            return true;
        }
        return false;
    }
    

    public static function isOverVersion($sMinVersion, $blEqualOrGreater = false) {
        $iMinVersion    = self::versionToInt($sMinVersion);
        $iCurrVersion   = self::getCurrentVersion();
        if($blEqualOrGreater === false) {
            if($iCurrVersion > $iMinVersion) {
                return true;
            }
        } else {
            if($iCurrVersion >= $iMinVersion) {
                return true;
            }        
        }
        return false;
    }
    

    public static function isBetweenVersions($sMinVersion, $sMaxVersion) {
        if(!isOverVersion($sMinVersion, true)) {
            return false;
        }
        if(!isUnderVersion($sMaxVersion)) {
            return false;
        }
        return true;
    }
    

    public static function copyFile($sSource, $sDestination) {
        if(file_exists($sSource) === true) {
            if(file_exists($sDestination)) {
                if(md5_file($sSource) != md5_file($sDestination)) {
                    unlink($sDestination);
                } else {
                    return;
                }
            }
            if(copy($sSource, $sDestination)) {
            echo 'Datei '.$sDestination.' in Theme kopiert.<br>';
            } else {
                echo '<span style="color:red;">ERROR:</span> Kopieren fehlgeschlagen. Bitte kopieren Sie die Datei manuell von "'.$sSource.'" nach "'.$sDestination.'"<br>';
            }
        }
    }
}
