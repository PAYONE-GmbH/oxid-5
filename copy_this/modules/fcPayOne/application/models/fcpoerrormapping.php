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

/**
 * Error mapping model
 *
 * @author andre
 */
class fcpoerrormapping extends oxBase {

    /**
     * Helper object for dealing with different shop versions
     * @var object
     */
    protected $_oFcpoHelper = null;

    /**
     * Centralized Database instance
     * @var object
     */
    protected $_oFcpoDb = null;

    /**
     * Init needed data
     */
    public function __construct() {
        parent::__construct();
        $this->_oFcpoHelper = oxNew('fcpohelper');
        $this->_oFcpoDb = oxDb::getDb();
    }

    /**
     * Requests database for existing mappings and returns an array of mapping objects
     * 
     * @param void
     * @return array
     */
    public function fcpoGetExistingMappings($sType='general') {
        $aMappings = array();
        
        $sWhere = $this->_fcpoGetMappingWhere($sType);
        
        $oDb = $this->_oFcpoHelper->fcpoGetDb(true);

        $sQuery = "SELECT oxid, fcpo_error_code, fcpo_lang_id, fcpo_mapped_message FROM fcpoerrormapping {$sWhere} ORDER BY oxid ASC";
        $oResult = $oDb->Execute($sQuery);
        if ($oResult != false && $oResult->recordCount() > 0) {
            while (!$oResult->EOF) {
                // collect data
                $sOxid = $oResult->fields['oxid'];
                $sErrorCode = $oResult->fields['fcpo_error_code'];
                $sLangId = $oResult->fields['fcpo_lang_id'];
                $sMappedMessage = $oResult->fields['fcpo_mapped_message'];
                
                // create object
                $oMapping = new stdClass();
                $oMapping->sOxid = $sOxid;
                $oMapping->sErrorCode = $sErrorCode;
                $oMapping->sLangId = $sLangId;
                $oMapping->sMappedMessage = $sMappedMessage;
                $aMappings[] = $oMapping;
                $oResult->moveNext();
            }
        }

        return $aMappings;
    }

    /**
     * Extracts all error codes from xml file adn returns them as array
     * 
     * @param void
     * @return mixed
     */
    public function fcpoGetAvailableErrorCodes($sType='general') {
        $mReturn = $sErrorXmlPath = false;
        if ($sType == 'general') {
            $sErrorXmlPath = getShopBasePath() . "/modules/fcPayOne/payoneerrors.xml";
            $sErrorXmlPath = str_replace('//', '/', $sErrorXmlPath);
        }
        elseif ($sType == 'iframe') {
            $sErrorXmlPath = getShopBasePath() . "/modules/fcPayOne/iframeerrors.xml";
            $sErrorXmlPath = str_replace('//', '/', $sErrorXmlPath);
        }
        
        if (file_exists($sErrorXmlPath)) {
            try {
                $oXml = simplexml_load_file($sErrorXmlPath);
                $aReturn = $this->_fcpoParseXml($oXml);
                $mReturn = (is_array($aReturn)) ? $aReturn : false;
            } catch (oxException $ex) {
                $mReturn = false;
                throw $ex;
            }
        }
        
        return $mReturn;
    }

    /**
     * Updates current set of mappings into database
     * 
     * @param array $aMappings
     * @return void
     */
    public function fcpoUpdateMappings($aMappings, $sType) {
        $oDb = $this->_oFcpoHelper->fcpoGetDb();
        // iterate through mappings
        foreach ($aMappings as $sMappingId => $aData) {
            $sQuery = $this->_fcpoGetQuery($sMappingId, $aData, $sType);
            $oDb->Execute($sQuery);
        }
    }
    
    /**
     * Fetches mapped error message by error code
     * 
     * @param string $sErrorCode
     * @return string
     */
    public function fcpoFetchMappedErrorMessage($sErrorCode) {
        $oUBase = $this->_oFcpoHelper->getFactoryObject('oxUBase');
        $oLang = $this->_oFcpoHelper->fcpoGetLang();
        $sAbbr = $oUBase->getActiveLangAbbr();
        $aLanguages = $oLang->getLanguageArray(null, true, true);
        $sLangId = false;
        foreach ($aLanguages as $oLanguage) {
            if ($oLanguage->abbr == $sAbbr) {
                $sLangId = $oLanguage->id;
            }
        }

        $sMappedMessage = '';
        if ($sLangId !== false) {
            $sQuery = $this->_fcpoGetSearchQuery($sErrorCode, $sLangId);
            $sMappedMessage = $this->_oFcpoDb->GetOne($sQuery);
        }

        return $sMappedMessage;
    }
    
    /**
     * Returns where part of requesting error mappings from errormapping table
     * 
     * @param string $sType
     * @return string
     */
    protected function _fcpoGetMappingWhere($sType) {
        $aValidTypes = array(
            'general',
            'iframe',
        );
        
        $blValid = in_array($sType, $aValidTypes);
        $sWhere = '';
        if ($blValid) {
            $sWhere = "WHERE fcpo_error_type=".$this->_oFcpoDb->quote($sType);
        }
        
        return $sWhere;
    }
    
    /**
     * Parses and formats essential information so it can be passed into frontend
     * 
     * @param object $oXml
     * @return array
     */
    protected function _fcpoParseXml($oXml) {
        $oUBase = oxNew('oxUBase');
        $sAbbr = $oUBase->getActiveLangAbbr();
        $sMessageEntry = "error_message_".$sAbbr;
        $aEntries = array();
        
        foreach ($oXml->entry as $oXmlEntry) {
            $sErrorCode = (string)$oXmlEntry->error_code;
            $sErrorMessage = (string)$oXmlEntry->$sMessageEntry;
            if (!$sErrorCode || empty($sErrorCode)) continue;
            
            $oEntry = new stdClass();
            $oEntry->sErrorCode = $sErrorCode;
            $oEntry->sErrorMessage = $sErrorMessage;
            
            $aEntries[] = $oEntry;
        }
        
        return $aEntries;
    }

    /**
     * Converts a simplexml object into array
     * 
     * @param object $oXml
     * @param array $aOut
     */
    protected function _fcpoXml2Array($oXml, $aOut = array()) {
        foreach ((array) $oXml as $iIndex => $node) {
            $aOut[$iIndex] = ( is_object($node) ) ? $this->_fcpoXml2Array($node) : $node;
        }

        return $aOut;
    }

    /**
     * Returns the matching query for updating/adding data
     * 
     * @param string $sMappingId
     * @param array $aData
     * @return string
     */
    protected function _fcpoGetQuery($sMappingId, $aData, $sType) {
        // quote values from outer space
        if (array_key_exists('delete', $aData) !== false) {
            $oDb = $this->_oFcpoHelper->fcpoGetDb();
            $sOxid = $oDb->quote($sMappingId);
            $sQuery = "DELETE FROM fcpoerrormapping WHERE oxid = {$sOxid}";
        } else {
            $sQuery = $this->_fcpoGetUpdateQuery($sMappingId, $aData, $sType);
        }

        return $sQuery;
    }

    /**
     * Returns wether an insert or update query, depending on data
     * 
     * @param string $sMappingId
     * @param array $aData
     * @return string
     */
    protected function _fcpoGetUpdateQuery($sMappingId, $aData, $sType) {
        $blValidNewEntry = $this->_fcpoIsValidNewEntry($sMappingId, $aData['sErrorCode'], $aData['sLangId'], $aData['sMappedMessage']);

        $sOxid = $this->_oFcpoDb->quote($sMappingId);
        $sErrorCode = $this->_oFcpoDb->quote($aData['sErrorCode']);
        $sLangId = $this->_oFcpoDb->quote($aData['sLangId']);
        $sMappedMessage = $this->_oFcpoDb->quote($aData['sMappedMessage']);
        $sType = $this->_oFcpoDb->quote($sType);

        if ($blValidNewEntry) {
            $sQuery = " INSERT INTO fcpoerrormapping (
                            fcpo_error_code,     fcpo_lang_id,  fcpo_mapped_message, fcpo_error_type
                        ) VALUES (
                            {$sErrorCode},    {$sLangId}, {$sMappedMessage}, {$sType}
                        )";
        } else {
            $sQuery = " UPDATE fcpoerrormapping
                        SET
                            fcpo_error_code = {$sErrorCode},
                            fcpo_lang_id = {$sLangId},
                            fcpo_mapped_message = {$sMappedMessage},
                            fcpo_error_type = {$sType}
                        WHERE
                            oxid = {$sOxid}";
        }

        return $sQuery;
    }
    
    /**
     * Returns Query for searching a certain mapping
     * 
     * @param string $sErrorCode
     * @param string $sLangId
     */
    protected function _fcpoGetSearchQuery($sErrorCode, $sLangId) {
        $sErrorCode = $this->_oFcpoDb->quote($sErrorCode);
        $sLangId = $this->_oFcpoDb->quote($sLangId);
        
        $sQuery = "
            SELECT fcpo_mapped_message FROM fcpoerrormapping 
            WHERE 
            fcpo_error_code = {$sErrorCode} AND
            fcpo_lang_id = {$sLangId}
            LIMIT 1
        ";

        return $sQuery;
    }

    /**
     * Checks if current entry is new and complete
     * 
     * @param type $sMappingId
     * @param type $sPaymentId
     * @param type $sPayoneStatus
     * @param type $sFolder
     * @return bool
     */
    protected function _fcpoIsValidNewEntry($sMappingId, $sErrorCode, $sLangId, $sMappedMessage) {
        $blComplete = (!empty($sPayoneStatus) || !empty($sLangId) || !empty($sMappedMessage));
        $blValid = ($sMappingId == 'new' && $blComplete) ? true : false;

        return $blValid;
    }

}
