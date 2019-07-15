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

$oViewConf = oxNew("oxViewConfig");
$sImgUrl = $oViewConf->getModuleUrl('fcpayone', 'payone_icon.png');

$aModule = array(
    'id'            => 'fcpayone',
    'title'         => '<img src="' . $sImgUrl . '" alt="FC"> PAYONE Payment f&uuml;r OXID eShop',
    'description'   => 'Sie suchen nach der optimalen Payment-L&ouml;sung f&uuml;r Ihren Online-Shop?<br><br>
                        PAYONE bietet Unternehmensl&ouml;sungen zur automatisierten und ganzheitlichen Abwicklung aller Zahlungsprozesse im E-Commerce. 
                        Der Payment Service Provider ist ein Unternehmen der Sparkassen-Finanzgruppe und von der Bundesanstalt f&uuml;r Finanzdienstleistungsaufsicht als Zahlungsinstitut zugelassen. 
                        Das Leistungsspektrum umfasst die Akzeptanz und Abwicklung nationaler und internationaler Zahlungsarten sowie alle Zahlungsdienstleistungen. 
                        Standardisierte Schnittstellen und Extensions erlauben eine einfache Integration in bestehende E-Commerce und IT-Systeme bei h&ouml;chsten Sicherheitsstandards.<br><br>
                        Hier finden Sie weitere Informationen zum PAYONE Payment-Modul f&uuml;r OXID eShop: 
                        <a href="https://www.payone.de/plattform-integration/extensions/oxid/" style="color:darkblue;text-decoration: underline;" title="PAYONE Homepage" target="_blank">
                            https://www.payone.de
                        </a>',
    'thumbnail'     => 'picture.gif',
    'version'       => '2.3.0',
    'author'        => 'FATCHIP GmbH',
    'email'         => 'kontakt@fatchip.de',
    'url'           => 'https://wiki.fatchip.de/public/faqpayone',
    'extend'        => array(
        // controllers
        'basket'                            => 'fcPayOne/extend/application/controllers/fcPayOneBasketView',
        'user'                              => 'fcPayOne/extend/application/controllers/fcPayOneUserView',
        'order'                             => 'fcPayOne/extend/application/controllers/fcPayOneOrderView',
        'payment'                           => 'fcPayOne/extend/application/controllers/fcPayOnePaymentView',
        'thankyou'                          => 'fcPayOne/extend/application/controllers/fcPayOneThankyouView',
        // models
        'oxbasket'                          => 'fcPayOne/extend/application/models/fcPayOneBasket',
        'oxbasketitem'                      => 'fcPayOne/extend/application/models/fcPayOneBasketitem',
        'oxorder'                           => 'fcPayOne/extend/application/models/fcPayOneOrder',
        'oxorderarticle'                    => 'fcPayOne/extend/application/models/fcPayOneOrderarticle',
        'oxpayment'                         => 'fcPayOne/extend/application/models/fcPayOnePayment',
        'oxpaymentgateway'                  => 'fcPayOne/extend/application/models/fcPayOnePaymentgateway',
        'oxuser'                            => 'fcPayOne/extend/application/models/fcPayOneUser',
        // core
        'oxviewconfig'                      => 'fcPayOne/extend/core/fcPayOneViewConf',
    ),
    'files'         => array(
        // controllers -> admin
        'fcpayone_adminview'                => 'fcPayOne/application/controllers/admin/fcpayone_adminview.php',
        'fcpayone_admindetails'             => 'fcPayOne/application/controllers/admin/fcpayone_admindetails.php',
        'fcpayone_adminlist'                => 'fcPayOne/application/controllers/admin/fcpayone_adminlist.php',
        'fcPayOne_Main_Ajax'                => 'fcPayOne/application/controllers/admin/fcPayOne_Main_Ajax.php',
        'fcpayone_admin'                    => 'fcPayOne/application/controllers/admin/fcpayone_admin.php',
        'fcpayone_apilog'                   => 'fcPayOne/application/controllers/admin/fcpayone_apilog.php',
        'fcpayone_apilog_list'              => 'fcPayOne/application/controllers/admin/fcpayone_apilog_list.php',
        'fcpayone_apilog_main'              => 'fcPayOne/application/controllers/admin/fcpayone_apilog_main.php',
        'fcpayone_boni'                     => 'fcPayOne/application/controllers/admin/fcpayone_boni.php',
        'fcpayone_boni_list'                => 'fcPayOne/application/controllers/admin/fcpayone_boni_list.php',
        'fcpayone_boni_main'                => 'fcPayOne/application/controllers/admin/fcpayone_boni_main.php',
        'fcpayone_list'                     => 'fcPayOne/application/controllers/admin/fcpayone_list.php',
        'fcpayone_log'                      => 'fcPayOne/application/controllers/admin/fcpayone_log.php',
        'fcpayone_log_list'                 => 'fcPayOne/application/controllers/admin/fcpayone_log_list.php',
        'fcpayone_main'                     => 'fcPayOne/application/controllers/admin/fcpayone_main.php',
        'fcpayone_order'                    => 'fcPayOne/application/controllers/admin/fcpayone_order.php',
        'fcpayone_protocol'                 => 'fcPayOne/application/controllers/admin/fcpayone_protocol.php',
        'fcpayone_status_forwarding'        => 'fcPayOne/application/controllers/admin/fcpayone_status_forwarding.php',
        'fcpayone_status_mapping'           => 'fcPayOne/application/controllers/admin/fcpayone_status_mapping.php',
        'fcpayone_error_mapping'            => 'fcPayOne/application/controllers/admin/fcpayone_error_mapping.php',
        // controllers
        'fcpayone_ajax'                     => 'fcPayOne/application/controllers/fcpayone_ajax.php',
        // models
        'fcpouserflag'                      => 'fcPayOne/application/models/fcpouserflag.php',
        'fcporequestlog'                    => 'fcPayOne/application/models/fcporequestlog.php',
        'fcpotransactionstatus'             => 'fcPayOne/application/models/fcpotransactionstatus.php',
        'fcpomapping'                       => 'fcPayOne/application/models/fcpomapping.php',
        'fcpoerrormapping'                  => 'fcPayOne/application/models/fcpoerrormapping.php',
        'fcpoforwarding'                    => 'fcPayOne/application/models/fcpoforwarding.php',
        'fcpoconfigexport'                  => 'fcPayOne/application/models/fcpoconfigexport.php',
        'fcpoklarna'                        => 'fcPayOne/application/models/fcpoklarna.php',
        'fcpopaypal'                        => 'fcPayOne/application/models/fcpopaypal.php',
        'fcporatepay'                       => 'fcPayOne/application/models/fcporatepay.php',
        // libs
        'fcpohelper'                        => 'fcPayOne/lib/fcpohelper.php',
        'fcporequest'                       => 'fcPayOne/lib/fcporequest.php',
        // core
        'fcpayone_events'                   => 'fcPayOne/core/fcpayone_events.php',
    ),
    'templates' => array(
        // admin
        'fcpayone_popup_main.tpl'           => 'fcPayOne/application/views/admin/tpl/popups/fcpayone_popup_main.tpl',
        'fcpayone.tpl'                      => 'fcPayOne/application/views/admin/tpl/fcpayone.tpl',
        'fcpayone_apilog.tpl'               => 'fcPayOne/application/views/admin/tpl/fcpayone_apilog.tpl',
        'fcpayone_apilog_list.tpl'          => 'fcPayOne/application/views/admin/tpl/fcpayone_apilog_list.tpl',
        'fcpayone_apilog_main.tpl'          => 'fcPayOne/application/views/admin/tpl/fcpayone_apilog_main.tpl',
        'fcpayone_boni.tpl'                 => 'fcPayOne/application/views/admin/tpl/fcpayone_boni.tpl',
        'fcpayone_boni_list.tpl'            => 'fcPayOne/application/views/admin/tpl/fcpayone_boni_list.tpl',
        'fcpayone_boni_main.tpl'            => 'fcPayOne/application/views/admin/tpl/fcpayone_boni_main.tpl',
        'fcpayone_cc_preview.tpl'           => 'fcPayOne/application/views/admin/tpl/fcpayone_cc_preview.tpl',
        'fcpayone_list.tpl'                 => 'fcPayOne/application/views/admin/tpl/fcpayone_list.tpl',
        'fcpayone_log.tpl'                  => 'fcPayOne/application/views/admin/tpl/fcpayone_log.tpl',
        'fcpayone_log_list.tpl'             => 'fcPayOne/application/views/admin/tpl/fcpayone_log_list.tpl',
        'fcpayone_main.tpl'                 => 'fcPayOne/application/views/admin/tpl/fcpayone_main.tpl',
        'fcpayone_order.tpl'                => 'fcPayOne/application/views/admin/tpl/fcpayone_order.tpl',
        'fcpayone_protocol.tpl'             => 'fcPayOne/application/views/admin/tpl/fcpayone_protocol.tpl',
        'fcpayone_status_forwarding.tpl'    => 'fcPayOne/application/views/admin/tpl/fcpayone_status_forwarding.tpl',
        'fcpayone_status_mapping.tpl'       => 'fcPayOne/application/views/admin/tpl/fcpayone_status_mapping.tpl',
        'fcpayone_error_mapping.tpl'        => 'fcPayOne/application/views/admin/tpl/fcpayone_error_mapping.tpl',
    ),
    'events'        => array(
        'onActivate'                        => 'fcpayone_events::onActivate',
        'onDeactivate'                      => 'fcpayone_events::onDeactivate',
    ),
    'blocks'        => array(
        array(
            'template' => 'layout/base.tpl',
            'block' => 'base_js',
            'file' => 'fcpo_base_js_extend'
        ),
        array(
            'template' => 'layout/base.tpl',
            'block' => 'base_style',
            'file' => 'fcpo_base_css_extend'
        ),
        array(
            'template' => 'page/checkout/basket.tpl',
            'block' => 'checkout_basket_main',
            'file' => 'fcpo_basket_override'
        ),
        array(
            'template' => 'widget/minibasket/minibasket.tpl',
            'block' => 'widget_minibasket_total',
            'file' => 'fcpo_minibasket_total_override',
        ),
        array(
            'template' => 'page/checkout/order.tpl',
            'block' => 'checkout_order_address',
            'file' => 'fcpo_order_override'
        ),
        array(
            'template' => 'page/checkout/user.tpl',
            'block' => 'checkout_user_main',
            'file' => 'fcpo_user_override'
        ),
        array(
            'template' => '_formparams.tpl',
            'block' => 'admin_formparams',
            'file' => 'fcpo_admin_formparams',
        ),
        array(
            'template' => 'page/checkout/payment.tpl',
            'block' => 'change_payment',
            'file' => 'fcpo_payment_override',
        ),
        array(
            'template' => 'page/checkout/payment.tpl',
            'block' => 'select_payment',
            'file' => 'fcpo_payment_select_override',
        ),
        array(
            'template' => 'page/checkout/order.tpl',
            'block' => 'order_basket',
            'file' => 'fcpo_order_basket_override',
        ),
        array(
            'template' => 'page/checkout/order.tpl',
            'block' => 'checkout_order_errors',
            'file' => 'fcpo_order_checkout_order_errors'
        ),
        array(
            'template' => 'page/checkout/thankyou.tpl',
            'block' => 'checkout_thankyou_proceed',
            'file' => 'fcpo_thankyou_checkout_thankyou',
        ),
        array(
            'template' => 'email/html/order_cust.tpl',
            'block' => 'email_html_order_cust_paymentinfo',
            'file' => 'fcpo_email_html_order_cust_paymentinfo',
        ),
        array(
            'template' => 'email/plain/order_cust.tpl',
            'block' => 'email_plain_order_cust_paymentinfo',
            'file' => 'fcpo_email_plain_order_cust_paymentinfo',
        ),
        array(
            'template' => 'order_list.tpl',
            'block' => 'admin_order_list_colgroup',
            'file' => 'fcpo_admin_order_list_colgroup',
        ),
        array(
            'template' => 'order_list.tpl',
            'block' => 'admin_order_list_filter',
            'file' => 'fcpo_admin_order_list_filter',
        ),
        array(
            'template' => 'order_list.tpl',
            'block' => 'admin_order_list_sorting',
            'file' => 'fcpo_admin_order_list_sorting',
        ),
        array(
            'template' => 'order_list.tpl',
            'block' => 'admin_order_list_item',
            'file' => 'fcpo_admin_order_list_item',
        ),
        array(
            'template' => 'payment_list.tpl',
            'block' => 'admin_payment_list_filter',
            'file' => 'fcpo_admin_payment_list_filter',
        ),
        array(
            'template' => 'payment_main.tpl',
            'block' => 'admin_payment_main_form',
            'file' => 'fcpo_admin_payment_main_form',
        ),
        array(
            'template' => 'page/checkout/basket.tpl',
            'block' => 'basket_btn_next_top',
            'file' => 'fcpo_basket_btn_next',
        ),
        array(
            'template' => 'page/checkout/basket.tpl',
            'block' => 'basket_btn_next_bottom',
            'file' => 'fcpo_basket_btn_next_bottom',
        ),
        array(
            'template' => 'page/checkout/payment.tpl',
            'block' => 'checkout_payment_errors',
            'file' => 'fcpo_payment_errors',
        ),
        array(
            'template' => 'form/user_checkout_change.tpl',
            'block' => 'user_checkout_change_next_step_top',
            'file' => 'fcpo_checkout_creditrating_approval',
        ),
        array(
            'template' => 'form/login.tpl',
            'block' => 'checkout_options_login_text',
            'file' => 'fcpo_checkout_options_login_text',
        ),

    ),
);

if(class_exists('fcpohelper')) {
    $sShopEdition = fcpohelper::fcpoGetStaticConfig()->getActiveShop()->oxshops__oxedition->value;
    if($sShopEdition == 'EE') {
        $aModule['blocks'][] = array(
                'template' => 'roles_bemain.tpl',
                'block' => 'admin_roles_bemain_form',
                'file' => 'fcpo_admin_roles_bemain_form',
        );
        $aModule['extend'][] = array('roles_bemain' => 'fcPayOne/extend/application/controllers/admin/fcPayOneRolesBeMain');
    }
}
