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
 

class fcpayone_support extends fcpayone_adminview {
    
    /**
     * Current class template name.
     * 
     * @var string
     */
    protected $_sThisTemplate = 'fcpayone_support.tpl';

    /**
     * Returns current view identifier
     *
     * @return string
     */
    public function getViewId() {
        return 'dyn_fcpayone';
    }

    /**
     * Return admin template seperator sign by shop-version
     *
     * @return string
     */
    public function fcGetAdminSeperator() {
        $iVersion = $this->_oFcpoHelper->fcpoGetIntShopVersion();
        if($iVersion < 4300) {
            return '?';
        } else {
            return '&';
        }
    }
    
}