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
 

$sLangName  = "Deutsch";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(
'charset'                                   => 'ISO-8859-15',
'fcpo_admin_title'                          => 'PAYONE',
'fcpo_main_title'                           => 'Konfiguration',
'fcpo_main_log'                             => 'Transaktionen',
'FCPO_MERCHANT_ID'                          => 'PAYONE Merchant ID',
'FCPO_PORTAL_ID'                            => 'PAYONE Portal ID',
'FCPO_PORTAL_KEY'                           => 'PAYONE Portal Key',
'FCPO_OPERATION_MODE'                       => 'PAYONE Betriebsmodus',
'FCPO_BONI_OPERATION_MODE'                  => 'Betriebsmodus',
'FCPO_SUBACCOUNT_ID'                        => 'PAYONE Sub-Account ID',
'FCPO_BANKACCOUNTCHECK'                     => 'Pr�fung Bankverbindung',
'FCPO_DEACTIVATED'                          => 'Deaktiviert',
'FCPO_ACTIVATED'                            => 'Aktiviert',
'FCPO_ACTIVATEDWITHPOS'                     => 'Aktiviert, mit Pr�fung gegen POS-Sperrliste<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Nur Zahlmethode Lastschrift Deutschland)',
'FCPO_LIVE_MODE'                            => 'Livemodus',
'FCPO_TEST_MODE'                            => 'Testmodus',
'fcpo_order_title'                          => 'PAYONE',
'FCPO_REFNR'                                => 'Referenz-Nummer',
'FCPO_TXID'                                 => 'PAYONE-Vorgangsnummer (txid)',
'fcpo_action_appointed'                     => 'Bestellung',
'fcpo_action_capture'                       => 'Abbuchung',
'fcpo_action_paid'                          => 'Bezahlung',
'fcpo_action_underpaid'                     => 'Unterzahlung',
'fcpo_action_overpaid'                      => '<span style="color: red;">�berzahlung</span>',
'fcpo_action_cancelation'                   => 'R�ckbelastung',
'fcpo_action_refund'                        => 'R�ckerstattung',
'fcpo_action_debit'                         => 'Forderung/Gutschrift',
'fcpo_action_transfer'                      => 'Umbuchung',
'fcpo_action_reminder'                      => 'Status Mahnverfahren',
'fcpo_clearingtype_elv'                     => 'Lastschrift',
'fcpo_clearingtype_cc'                      => 'Kreditkarte',
'fcpo_clearingtype_vor'                     => 'Vorkasse',
'fcpo_clearingtype_rec'                     => 'Rechnung',
'fcpo_clearingtype_cod'                     => 'Nachnahme',
'fcpo_clearingtype_sb'                      => 'Online-�berweisung',
'fcpo_clearingtype_wlt'                     => 'e-Wallet',
'fcpo_clearingtype_fnc'                     => 'Finanzierung',
'fcpo_clearingtype_csh'                     => 'Barzahlen',
'fcpo_clearingtype_fcpobillsafe'            => 'BillSAFE',
'fcpo_clearingtype_fcpocommerzfinanz'       => 'Commerz Finanz',
'fcpo_clearingtype_fcpoklarna'              => 'Klarna Rechnung',
'fcpo_clearingtype_fcpoklarna_install'      => 'Klarna Ratenkauf',
'FCPO_CAPTURE_APPROVED'                     => 'Buchung war erfolgreich',
'FCPO_CAPTURE_ERROR'                        => 'Fehler bei Buchung: ',
'FCPO_DEBIT_APPROVED'                       => 'Gutschrift war erfolgreich',
'FCPO_DEBIT_ERROR'                          => 'Fehler bei Gutschrift: ',
'FCPO_LIST_HEADER_TXTIME'                   => 'Zeitpunkt',
'FCPO_LIST_HEADER_ORDERNR'                  => 'Bestellnummer',
'FCPO_LIST_HEADER_TXID'                     => 'Transaktionsnummer',
'FCPO_LIST_HEADER_CLEARINGTYPE'             => 'Zahlmethode',
'FCPO_LIST_HEADER_EMAIL'                    => 'Kunden-E-Mail',
'FCPO_LIST_HEADER_PRICE'                    => 'Betrag',
'FCPO_LIST_HEADER_TXACTION'                 => 'Status',
'FCPO_EXECUTE'                              => 'Ausl�sen',
'FCPO_CAPTURE'                              => 'Capture ( Abbuchen )',
'FCPO_DEBIT'                                => 'Debit ( Gutschrift )',
'FCPO_ARE_YOU_SURE'                         => 'Sind Sie sicher, dass Sie diese Aktion ausf�hren m�chten?',
'FCPO_DE'                                   => 'Deutschland',
'FCPO_AT'                                   => '�sterreich',
'FCPO_NL'                                   => 'Niederlande',
'FCPO_HEADER_BANKACCOUNT'                   => 'Bankverbindung (optional)',
'FCPO_BANKCOUNTRY'                          => 'Kontoland',
'FCPO_BANKACCOUNT'                          => 'Kontonummer',
'FCPO_BANKCODE'                             => 'Bankleitzahl',
'FCPO_BANKACCOUNTHOLDER'                    => 'Kontoinhaber',
'FCPO_SHOW'                                 => 'anzeigen',
'FCPO_HIDE'                                 => 'verstecken',
'FCPO_PAYMENTTYPE'                          => 'Zahlungsart',
'FCPO_CARDEXPIREDATE'                       => 'Verfallsdatum',
'FCPO_CARDTYPE'                             => 'Kartentyp',
'FCPO_CARDPAN'                              => 'Maskierte Kartenummer',
'FCPO_BALANCE'                              => 'Saldo',
'FCPO_RECEIVABLE'                           => 'Zahlung',
'FC_IS_PAYONE'								=> 'Dies ist eine PAYONE Zahlungsmethode',
'FCPO_HELP_MERCHANTID'                      => 'Ihre PAYONE Merchant-ID (PAYONE Kundennummer) finden Sie auf allen Abrechnungen von PAYONE sowie rechts oben im PAYONE Merchant Interface (PMI).',
'FCPO_HELP_PORTALID'                        => 'Bitte tragen Sie hier die ID des PAYONE Zahlungsportals ein, �ber welches die Zahlungen abgewickelt werden sollen.<br>Die Portal-ID finden Sie unter <a href="http://www.payone.de" target="_blank">http://www.payone.de</a> > H�ndler-Login unter dem Men�punkt Konfiguration > Zahlungsportale<br><br>Alle relevanten Parameter zur Konfiguration erhalten Sie nach Auswahl von [editieren] unter dem Reiter [API-Parameter]',
'FCPO_HELP_PORTALKEY'                       => 'Bitte tragen Sie hier den Key zur Absicherung des Datenaustausches ein. Dieser kann bei der Konfiguration des PAYONE Zahlungsportals von Ihnen frei festgelegt werden.<br>Die Konfiguration finden Sie unter <a href="http://www.payone.de" target="_blank">http://www.payone.de</a> > H�ndler-Login unter dem Men�punkt Konfiguration > Zahlungsportale > [editieren] > Reiter [Erweitert] > Key<br><br>Alle relevanten Parameter zur Konfiguration erhalten Sie nach Auswahl des Reiters [API-Parameter]',
'FCPO_HELP_OPERATIONMODE'                   => 'Hier k�nnen Sie f�r diese Zahlungsart festlegen ob die Zahlungen im Testmodus abgewickelt werden, oder ob diese Live ausgef�hrt werden. Bitte beachten Sie, dass f�r den Testmodus die definierten Testdaten verwendet werden m�ssen.',
'FCPO_HELP_SUBACCOUNTID'                    => 'Bitte tragen Sie hier die ID des Sub-Accounts ein, �ber welchen die Zahlungen abgewickelt und zugeordnet werden sollen.<br>Die ID finden Sie unter <a href="http://www.payone.de" target="_blank">http://www.payone.de</a> > H�ndler-Login unter dem Men�punkt Konfiguration > Accounts<br><br>Alle relevanten Parameter zur Konfiguration erhalten Sie unter <a href="http://www.payone.de" target="_blank">http://www.payone.de</a> > H�ndler-Login unter dem Men�punkt Konfiguration > Zahlungsportale > [editieren] > Reiter [API-Parameter]',
'FCPO_HELP_POSCHECK'                        => 'Hier k�nnen Sie definieren ob eine Pr�fung der Bankverbindung gegen die POS-Sperrdatei durchgef�hrt werden soll. Bitte beachten Sie, dass die das Modul "Protect" beauftragt worden sein muss und die Pr�fung nur f�r die Zahlungsart Lastschrift Deutschland durchgef�hrt wird.',
'fcpo_admin_config'                         => 'Konfiguration',
'fcpo_admin_config_payment'                 => 'Zahlungseinstellungen',
'fcpo_admin_protocol'                       => 'Protokolle / Logs',
'FCPO_NO_TRANSACTION'                       => 'Keine Transaktion ausgew�hlt',
'fcpo_admin_information'                    => 'Information',
'fcpo_admin_common'                         => 'Allgemein',
'fcpo_admin_support'                        => 'Support',
'fcpo_admin_api_logs'                       => 'API Logs',
'FCPO_LIST_HEADER_TIMESTAMP'                => 'Zeit',
'FCPO_LIST_HEADER_REQUEST'                  => 'Request',
'FCPO_LIST_HEADER_RESPONSE'                 => 'Response',
'FCPO_NO_APILOG'                            => 'Kein Log-Eintrag ausgew�hlt',
'FCPO_ACTIVE_CREDITCARD_TYPES'              => 'Aktive Kreditkartenbrands',
'FCPO_CREDITCARDBRANDS_INFOTEXT'            => 'Hier k�nnen Sie die einzelnen Kreditkartenbrands f�r die Zahlart Kreditkarte aktivieren und konfigurieren.<br>Bitte beachten Sie, dass der jeweilige Kreditkartenbrand bei PAYONE beauftragt worden sein muss.<br>Die Einstellung f�r die Zahlungsart Kreditkarte nehmen Sie unter PAYONE -> Konfiguration -> Zahlungsarten vor.',
'FCPO_ACTIVE_ONLINE_UBERWEISUNG_TYPES'      => 'Aktive Online-�berweisungsarten',
'FCPO_ONLINEUBERWEISUNG_INFOTEXT'           => 'Hier k�nnen Sie die einzelnen Online-�berweisungsarten f�r die Zahlart Online-�berweisung aktivieren und konfigurieren.<br>Bitte beachten Sie, dass die jeweilige Online-�berweisungsart bei PAYONE beauftragt worden sein muss.<br>Die Einstellung f�r die Zahlungsart Online-�berweisungsart nehmen Sie unter PAYONE -> Konfiguration -> Zahlungsarten vor.',
'FCPO_CHANNEL'                              => 'Channel',
'FCPO_AUTHORIZATION_METHOD'                 => 'Autorisierungs-Methode',
'FCPO_PREAUTHORIZATION'                     => 'Vorautorisierung',
'FCPO_PREAUTHORIZATION_HELP'                => 'Bei der Auswahl von "Vorautorisierung" wird der zu zahlende Betrag im Zuge der Bestellung reserviert [von PAYONE empfohlen]. Die Abbuchung (Capture) muss in diesem Fall in einem zweiten Schritt bei Auslieferung der Ware initiiert werden.',
'FCPO_AUTHORIZATION'                        => 'Autorisierung',
'FCPO_AUTHORIZATION_HELP'                   => 'Bei der Auswahl von "Autorisierung" wird der zu zahlende Betrag sofort im Zuge der Bestellung eingezogen.',
'dyn_fcpayone'                              => 'PAYONE',
'FCPO_ONLY_PAYONE'                          => 'Nur PAYONE',
'ORDER_LIST_YOUWANTTOSTORNO'                => 'Wollen Sie diese Bestellung wirklich stornieren?\n ACHTUNG: ggf. offene PAYONE Vorg�nge zu dieser Bestellung bitte vor dem Stornieren abschliessen.',
'FCPO_ORDER_LIST_YOUWANTTODELETE'           => 'Wollen Sie diesen Eintrag wirklich l�schen?\n ACHTUNG: ggf. offene PAYONE Vorg�nge zu dieser Bestellung bitte vor dem L�schen abschliessen.',
'fcpo_admin_config_bonicheck'               => 'Protect',
'FCPO_ADDRESSCHECKTYPE'                     => 'Adresspr�fung',
'FCPO_NO_ADDRESSCHECK'                      => 'Keine Adresspr�fung durchf�hren',
'FCPO_BASIC_ADDRESSCHECK'                   => 'AdressCheck Basic',
'FCPO_PERSON_ADDRESSCHECK'                  => 'AdressCheck Person',
'FCPO_HELP_NO_ADDRESSCHECK'                 => 'Deaktivierung der Adresspr�fung',
'FCPO_HELP_BASIC_ADDRESSCHECK'              => 'Pr�fung der Adresse auf Existenz sowie Erg�nzung und Korrektur der Adresse (M�glich f�r Adressen aus Deutschland, �sterreich, Schweiz, Niederlande, Belgien, Luxemburg, Frankreich, Italien, Spanien, Portugal, D�nemark, Schweden, Finnland, NorwegenPolen, Slowakei, Tschechien, Ungarn, USA, Kanada)',
'FCPO_HELP_PERSON_ADDRESSCHECK'             => 'Pr�fung ob die Person unter der angegebenen der Adresse bekannt ist, Pr�fung der Adresse auf Existenz sowie Erg�nzung und Korrektur der Adresse (nur Deutschland)',
'FCPO_CONSUMERSCORETYPE'                    => 'Bonit�tspr�fung',
'FCPO_NO_BONICHECK'                         => 'Keine Bonit�tspr�fung durchf�hren',
'FCPO_HARD_BONICHECK'                       => 'Infoscore (Harte Merkmale)',
'FCPO_ALL_BONICHECK'                        => 'Infoscore (Alle Merkmale)',
'FCPO_ALL_SCORE_BONICHECK'                  => 'Infoscore (Alle Merkmale + Boniscore)',
'FCPO_HELP_NO_BONICHECK'                    => 'Deaktivierung der Bonit�tspr�fung',
'FCPO_HELP_HARD_BONICHECK'                  => 'Pr�fung auf so genannte "harte" Negativmerkmale (z.B. Verbraucherinsolvenzverfahren, Haftbefehl zur Eidesstattliche Versicherung oder Erzwingung Abgabe der Eidesstattlichen Versicherung). Die Bonit�tspr�fung unterst�tzt ausschlie�lich die Pr�fung von K�ufern aus Deutschland.',
'FCPO_HELP_ALL_BONICHECK'                   => 'Pr�fung auf so genannte "harte" Negativmerkmale (z.B. Verbraucherinsolvenzverfahren, Haftbefehl zur Eidesstattliche Versicherung oder Erzwingung Abgabe der Eidesstattlichen Versicherung), "mittlere" Negativmerkmale (z.B. Mahnbescheid, Vollstreckungsbescheid oder Zwangsvollstreckung) und "weiche" Negativmerkmale (z.B. Inkasso-Mahnverfahren eingeleitet, Fortlauf des au�ergerichtlichen Inkasso-Mahnverfahrens nach Teilzahlung, Einstellung des au�ergerichtlichen Inkasso-Mahnverfahrens wegen Aussichtslosigkeit). Die Bonit�tspr�fung unterst�tzt ausschlie�lich die Pr�fung von K�ufern aus Deutschland.',
'FCPO_HELP_ALL_SCORE_BONICHECK'             => 'Pr�fung auf so genannte "harte" Negativmerkmale (z.B. Verbraucherinsolvenzverfahren, Haftbefehl zur Eidesstattliche Versicherung oder Erzwingung Abgabe der Eidesstattlichen Versicherung), "mittlere" Negativmerkmale (z.B. Mahnbescheid, Vollstreckungsbescheid oder Zwangsvollstreckung) und "weiche" Negativmerkmale (z.B. Inkasso-Mahnverfahren eingeleitet, Fortlauf des au�ergerichtlichen Inkasso-Mahnverfahrens nach Teilzahlung, Einstellung des au�ergerichtlichen Inkasso-Mahnverfahrens wegen Aussichtslosigkeit). Die Bonit�tspr�fung unterst�tzt ausschlie�lich die Pr�fung von K�ufern aus Deutschland.<br><br>Der BoniScore ist ein Scorewert und erm�glicht eine h�here Trennsch�rfe bei vorliegenden Negativmerkmalen.',
'FCPO_HELP_BONI_OPERATIONMODE'              => 'Hier k�nnen Sie f�r die Bonit�tspr�fung festlegen ob die �berpr�fungen im Testmodus abgewickelt werden, oder ob diese Live ausgef�hrt werden.',
'FCPO_SEND_ARTICLELIST'                     => 'Artikelliste versenden',
'FCPO_HELP_SEND_ARTICLELIST'                => 'Bei Aktivierung wird in den vorgesehenen Anfragen an das PAYONE System der Warenkorb incl. der Artikeleinzelpreise mit versendet.<br>Diese Option muss aktiviert sein wenn sie das PAYONE Invoicing beauftragt haben.',
'FCPO_CHECK_DEL_ADDRESS'                    => 'Lieferadresse pr�fen',
'FCPO_HELP_CHECK_DEL_ADDRESS'               => 'Zus�tzliche Pr�fung der Lieferadresse durch die Adresspr�fung.',
'FCPO_CORRECT_ADDRESS'                      => 'Korrigierte Adressen �bernehmen',
'FCPO_HELP_CORRECT_ADDRESS'                 => '�bernahme der jeweils durch die Adresspr�fung korrigierten Adresse anstatt der eingegebenen Adresse in Ihren Shop.',
'FCPO_STATUS_WITH_USER_CORRECTION'          => 'User wird wieder zum Benutzerformular geschickt wenn:',
'FCPO_ADDRESSCHECK_PPB'                     => 'Vor- & Nachname bekannt',
'FCPO_ADDRESSCHECK_PHB'                     => 'Nachname bekannt',
'FCPO_ADDRESSCHECK_PAB'                     => 'Vor- & Nachname nicht bekannt',
'FCPO_ADDRESSCHECK_PKI'                     => 'Mehrdeutigkeit bei Name zu Anschrift',
'FCPO_ADDRESSCHECK_PNZ'                     => 'nicht (mehr) zustellbar',
'FCPO_ADDRESSCHECK_PPV'                     => 'Person verstorben',
'FCPO_ADDRESSCHECK_PPF'                     => 'Wenn Adresse postalisch falsch wird Benutzer zum Benutzerformular zur�ck geschickt',
'FCPO_DURABILITY_BONICHECK'                 => 'Lebensdauer Bonit�tspr�fung in Tagen',
'FCPO_HELP_DURABILITY_BONICHECK'            => 'Anzahl in Tagen, nach der eine neue Bonit�tspr�fung durchgef�hrt wird.<br><br>Bitte beachten Sie die Bestimmungen des BDSG und der Vertragsbedingungen bzgl. der Speicherung und der Lebensdauer der Bonit�tspr�fungen. Es wird empfohlen, eine Lebensdauer von 1 Tag zu konfigurieren.',
'FCPO_MODULE_VERSION'                       => 'Version Modul',
'FCPO_STARTLIMIT_BONICHECK'                 => 'Bonit�tspr�fung ab Warenwert',
'FCPO_HELP_STARTLIMIT_BONICHECK'            => 'Bonit�tspr�fung wird nur ausgef�hrt wenn der Warenwert h�her als der hier konfigurierte Wert ist.<br><br>Wenn die Bonit�tspr�fung immer durchgef�hrt werden soll, lassen Sie dieses Feld leer.',
'FCPO_HELP_ASSIGNCOUNTRIES'                 => 'Wenn keine L�nder zugewiesen sind, gilt die Zahlungsart f�r alle L�nder.<br><br>Wenn L�nder zugewiesen sind gelten die Zahlungsarten nur f�r die zugewiesenen L�nder.<br><br>Gepr�ft werden Rechnungsland und Lieferland.',
'FCPO_HELP_ASSIGNCOUNTRIES_2'               => 'Wenn keine L�nder zugewiesen sind, gilt die Zahlungsart f�r alle L�nder.<br><br>Wenn L�nder zugewiesen sind gelten die Zahlungsarten nur f�r die zugewiesenen L�nder.<br><br>Gepr�ft werden Rechnungsland und Lieferland.',
'FCPO_HELP_ASSIGNCOUNTRIES_3'               => 'Wenn keine L�nder zugewiesen sind, gilt die Zahlungsart f�r alle L�nder.<br><br>Wenn L�nder zugewiesen sind gelten die Zahlungsarten nur f�r die zugewiesenen L�nder.<br><br>Gepr�ft werden Rechnungsland und Lieferland.',
'fcpo_receivable_appointed1'                => 'Reservierung',
'fcpo_receivable_appointed2'                => 'Forderung (Autorisierung)',
'fcpo_receivable_capture'                   => 'Forderung (Capture)',
'fcpo_receivable_debit1'                    => 'Forderung (Debit)',
'fcpo_receivable_debit2'                    => 'Gutschrift (Debit/Refund)',
'fcpo_receivable_reminder'                  => 'Mahnungsversand',
'fcpo_receivable_cancelation'               => 'R�cklastschriftgeb�hr',
'fcpo_payment_capture1'                     => 'Einzug',
'fcpo_payment_capture2'                     => 'Auszahlung',
'fcpo_payment_paid1'                        => 'Zahlungseingang',
'fcpo_payment_paid2'                        => 'R�ckbelastung',
'fcpo_payment_underpaid1'                   => 'Unterzahlung',
'fcpo_payment_underpaid2'                   => 'R�ckbelastung',
'fcpo_payment_debit1'                       => 'Einzug',
'fcpo_payment_debit2'                       => 'Auszahlung',
'fcpo_payment_transfer'                     => 'Umbuchung',
'fcpo_payment'                              => 'Zahlung',
'FCPO_MAIN_CONFIG_INFOTEXT'                 => 'Sie k�nnen f�r jede Zahlart einzeln konfigurieren, ob diese im Test- oder Livemodus abgewickelt werden soll. Die Einstellung finden Sie unter PAYONE -> Konfiguration -> Zahlungsarten. Wir empfehlen Ihnen nach der initialen Konfiguration sowie bei Konfigurations�nderungen zun�chst alle Zahlungsprozesse im Testmodus durchzuf�hren.',
'FCPO_BONICHECK_CONFIG_INFOTEXT'            => 'Bitte beachten Sie, dass Sie die nachfolgenden Optionen nur dann nutzen k�nnen, wenn Sie das Modul Protect von PAYONE beauftragt haben. Die Nutzung der Bonit�tspr�fung und der Adresspr�fung zieht variable Kosten pro Vorgang nach sich, die Sie Ihrem Vertrag entnehmen k�nnen.',
'FCPO_BONICHECK_CONFIG_INFOTEXT_SMALL'      => 'Bitte nehmen Sie die Einstellungen f�r die Bonit�tspr�fung mit Bedacht vor. Die Bonit�tspr�fung wird nach Eingabe der Personendaten durchgef�hrt und beeinflusst die Zahlungsarten, die Ihren Kunden im Checkout-Prozess angeboten werden. Die Bonit�tspr�fung sollte lediglich bei Zahlungsarten eingesetzt werden, die ein Zahlungsausfallrisiko f�r Sie nach sich ziehen (z.B. offene Rechnung oder Lastschrift). Sie konfigurieren dies �ber die Einstellung "Bonit�tsindex" in der Konfiguration der jeweiligen Zahlart. Sie sollten in Ihrem Shop au�erdem in geeigneter Weise darauf hinweisen, dass Sie Bonit�tspr�fungen �ber die InfoScore Consumer Data GmbH durchf�hren.',
'FCPO_INFOTEXT_SET_OPERATIONMODE'           => 'Wird individuell eingestellt unter PAYONE->Konfiguration->Zahlungseinstellungen',
'FCPO_DEFAULT_BONI'                         => 'Standard Bonit�ts-Index',
'FCPO_HELP_DEFAULT_BONI'                    => 'Diesen Bonit�ts-Index erh�lt der Kunde wenn er sich registriert.<br>Zweck: Wenn der Kunde noch nicht gepr�ft wurde und die Pr�fung erst ab einem bestimmten Warenwert erfolgt ist dies der Bonit�ts-Index der bis zur ersten tats�chlichen Pr�fung ber�cksichtigt wird.<br><br>Wenn dieses Feld leer bleibt wird der Oxid-Standard gesetzt ( 1000 ).',
'FCPO_SETTLE_ACCOUNT'                       => 'Saldenausgleich durchf�hren',
'FCPO_HELP_SETTLE_ACCOUNT'                  => 'Deaktivieren sie f�r Teileinz�ge die Checkbox "Saldenausgleich durchf�hren". Bei der letzten Teillieferung muss diese Option aktiviert werden, um einen Kontenausgleich durchzuf�hren.',
'FCPO_CAPTURE_AMOUNT_GREATER_NULL'          => 'Der Betrag f�r einen Capture muss gr��er als 0,00 sein!',
'FCPO_PREAUTHORIZED_AMOUNT'                 => 'Vorautorisierter Betrag',
'FCPO_SAVEBANKDATA'                         => 'Speicherung der Bankdaten',
'FCPO_HELP_SAVEBANKDATA'                    => 'Die Bankverbindung wird genau wie im OXID-Standard f�r die Payone Zahlart Lastschrift verschl�sselt gespeichert und steht beim n�chsten Einkauf direkt f�r den Kunden bereit.',
'FCPO_PRESAVE_ORDER'                        => 'Bestellung vor Authorisierung speichern',
'FCPO_REDUCE_STOCK'                         => 'Lagerbestand reduzieren',
'FCPO_HELP_REDUCE_STOCK'                    => 'Diese Einstellung hat nur Effekt wenn "Bestellung vor Authorisierung speichern" aktiviert ist und man w�hrend dem Bestellvorgang zur Bezahlung zu einem externen Bezahldienst ( z.B. Sofort�berweisung, PayPal oder Kreditkarte mit 3D Secure ) weitergeleitet wird. Die Einstellung gibt an ob vor der Umleitung oder erst wenn der Kunde zur�ckkommt vom Bezahldienst der Lagerbestand reduziert wird.',
'FCPO_REDUCE_STOCK_BEFORE'                  => 'vor Authorisierung',
'FCPO_REDUCE_STOCK_AFTER'                   => 'nach Authorisierung',
'FCPO_HELP_PRESAVE_ORDER'                   => 'Die Bestellung wird schon vor der Authorisierung als unvollst�ndige Bestellung abgespeichert. Dadurch steht die Bestellnummer auch Payone zur Verf�gung.',
'FCPO_VOUCHER'                              => 'Gutschein',
'FCPO_DISCOUNT'                             => 'Rabatt',
'FCPO_WRAPPING'                             => "Geschenkverpackung",
'FCPO_GIFTCARD'                             => "Gru�karte",
'FCPO_SURCHARGE'                            => 'Aufschlag',
'FCPO_DEDUCTION'                            => 'Abschlag',
'FCPO_PAYMENTTYPE'                          => "Zahlungsart:",
'FCPO_SHIPPINGCOST'                         => "Versandkosten",
'FCPO_PRODUCT_CAPTURE'                      => "Abbuchen",
'FCPO_PRODUCT_AMOUNT'                       => "Menge",
'FCPO_PRODUCT_PRICE'                        => "Einzelpreis",
'FCPO_PRODUCT_TITLE'                        => "Produkt",
'FCPO_COMPLETE_ORDER'                       => "Bestellung abschlie�en",
'FCPO_CONSUMERSCORE_MOMENT'                 => "Moment der Bonit�tspr�fung",
'FCPO_CONSUMERSCORE_BEFORE'                 => "Vor Zahlartauswahl",
'FCPO_CONSUMERSCORE_AFTER'                  => "Nach Zahlartauswahl",
'FCPO_HELP_CONSUMERSCORE_MOMENT'            => "Hier k�nnen Sie definieren wann der Kunde gepr�ft werden soll. Die Bonit�tspr�fung wird nur durchgef�hrt wenn der Bonit�tsindex der Zahlart einen Wert gr��er 0 entspricht.<br><br>Optionen:<br><br><ul><li>Vor Zahlartauswahl<br>Die Bonit�t des Kunden wird gepr�ft, wenn die notwendigen Adress und Namesinformationen vorliegen. Dies geschieht vor der Auswahl der Zahlart. Diese Pr�fung findet nicht sichtbar f�r den Kunden im Hintergrund statt.<br><br></li><li>Nach Zahlartauswahl<br>Bei Auswahl dieser Option erscheint ein Auswahlfeld in dem Sie ausw�hlen k�nnen f�r welche Zahlarten eine anschlie�ende Bonit�tspr�fung stattfinden soll.</li></ul>",
'sFCPOApprovalText_default'                 => "Hiermit erkl�re ich mich einverstanden, dass eine Bonit�tspr�fung durchgef�hrt wird.",
'sFCPODenialText_default'                   => "Die Bonit�tspr�fung hat ergeben, dass wir Ihnen die gew�hlte Zahlart leider nicht anbieten k�nnen. Bitte w�hlen Sie eine andere Zahlart",
'FCPO_APPROVALTEXT'                         => "Hinweistext Zustimmung Bonit&auml;tspr&uuml;fung",
'FCPO_DENIALTEXT'                           => "Hinweistext Zahlart abgelehnt",
'FCPO_ORDERNOTCHECKED'                      => "Der Kunde hat der Bonit�ts-Pr�fung nicht zugestimmt!",
'fcpo_admin_config_status_forwarding'       => "Transaktionsstatus - Weiterleitung",
'fcpo_admin_config_status_mapping'          => "Transaktionsstatus - Mapping",
'fcpo_admin_config_add'                     => "Hinzuf�gen",
'fcpo_admin_config_delete'                  => "l�schen",
'fcpo_admin_config_delete_confirm'          => "M�chten Sie diesen Eintrag wirklich l&ouml;schen?",
'fcpo_admin_config_paymenttype'             => "Zahlart",
'fcpo_admin_config_status_payone'           => "PAYONE Status",
'fcpo_admin_config_status_shop'             => "Shop - Status",
'fcpo_admin_config_status'                  => "Status",
'fcpo_admin_config_url'                     => "URL",
'fcpo_admin_config_timeout'                 => "Timeout",    
'fcpo_status_appointed'                     => "Zahlungsvorgang initiiert (APPOINTED)",
'fcpo_status_capture'                       => "Buchung (CAPTURE)",
'fcpo_status_paid'                          => "Bezahlt (PAID)",
'fcpo_status_underpaid'                     => "Unterzahlung (UNDERPAID)",
'fcpo_status_cancelation'                   => "R�cklastschrift (CANCELATION)",
'fcpo_status_refund'                        => "R�ckerstattung (REFUND)",
'fcpo_status_debit'                         => "Buchung (DEBIT)",
'fcpo_status_reminder'                      => "Status des Mahnverfahrens (REMINDER)",
'fcpo_status_vauthorization'                => "Buchung auf Abrechnungskonto (VAUTHORIZATION)",
'fcpo_status_vsettlement'                   => "Abrechnung eines Abrechnungskontos (VSETTLEMENT)",
'fcpo_status_transfer'                      => "Umbuchung (TRANSFER)",
'fcpo_status_invoice'                       => "Erzeugung eines Belegs (INVOICE)",
    
'FCPO_KLARNA_STORE_ID_ADMIN'                => "StoreIDs",
'FCPO_KLARNA_ADD_STORE_ID'                  => "weitere StoreID hinzuf�gen",
'FCPO_KLARNA_DELETE_STORE_ID'               => "L�schen",
    
'FCPO_CONFIG_GROUP_CONN'                    => "Verbindungs-Einstellungen",
'FCPO_CONFIG_GROUP_GENERAL'                 => "Allgemein",
'FCPO_CONFIG_GROUP_DEBITNOTE'               => "Lastschrift",
'FCPO_CONFIG_GROUP_CREDITCARD'              => "Kreditkarte",
'FCPO_CONFIG_GROUP_KLARNA'                  => "Klarna StoreIDs",
'FCPO_CONFIG_GROUP_KLARNA_CAMPAIGNS'        => "Klarna Kampagnen",

'FCPO_CONFIG_GROUP_PP_EXPRESS_LOGOS'        => "PayPal",
'FCPO_CONFIG_ADD_PP_EXPRESS_LOGO'           => "Weitere Sprache hinzuf&uuml;gen",
    
'FCPO_KLARNA_CAMPAIGNS'                     => "Kampagnen",
'FCPO_KLARNA_CAMPAIGN_CODE'                 => "Kampagnen-Code",
'FCPO_KLARNA_CAMPAIGN_TITLE'                => "Titel",
'FCPO_KLARNA_ADD_CAMPAIGN'                  => "weitere Kampagne hinzuf&uuml;gen",
'FCPO_KLARNA_DELETE_STORE_ID'               => "L�schen",

'FCPO_CONFIG_DEBIT_BANKDATA'                => "Eingabe der Bankdaten",
'FCPO_CONFIG_DEBIT_MULTISELECT'             => "Liste der unterst�tzten Kontol�nder.<br>F�r Mehrfachmarkierung STRG-Taste gedr�ckt halten.",
'FCPO_CONFIG_DEBIT_GER'                     => "Nur bei Deutschen Konten",
'FCPO_CONFIG_DEBIT_SHOW_OLD_FIELDS'         => "zus�tzlich Kontonummer/Bankleitzahl anzeigen",
    
'FCPO_CONFIG_DEBIT_MANDATE'                 => "Mandatserteilung",
'FCPO_CONFIG_DEBIT_MANDATE_TEXT'            => 'Die Mandatserteilung erfolgt mit dem kostenpflichtigen Request "managemandate".<br>Dieser Request beinhaltet einen bankaccountcheck. Allerdings ist hier keine Abfrage<br>der POS-Sperrliste m�glich.',
'FCPO_CONFIG_DEBIT_MANDATE_ACTIVE'          => "Mandatserteilung aktiv",
'FCPO_CONFIG_DEBIT_MANDATE_DOWNLOAD'        => "Download Mandat als PDF",
'FCPO_CONFIG_DEBIT_MANDATE_DOWNLOAD_TEXT'   => 'Diese Option kann nur ausgew�hlt werden, wenn bei PAYONE das Produkt<br>"SEPA-Mandate als PDF" gebucht wurde.',
'FCPO_CONFIG_DEBIT_MANDATE_DOWNLOAD_ACTIVE' => "Download Mandat als PDF",
    
'FCPO_HELP_REFPREFIX'                       => "Bei Requests an PAYONE muss immer eine eindeutige Referenznummer �bermittelt werden. Diese wird aus einer laufenden Nummer, normalerweise startend bei 1, generiert. Werden mit den gleichen PAYONE Account-Daten mehrere Shops betrieben ( z.B. Live- und Test-System ) kommt es zu Problemen wenn die Referenznummer schonmal verwendet wurde. Mit verschiedenen Pr�fixen auf den verschiedenen Systemen kann dies vermieden werden.",
'FCPO_REFPREFIX'                            => "Referenznummer Pr�fix ( Optional )",
    
'FCPO_MANDATE_PDF'                          => "SEPA Mandat-Pdf",
'FCPO_MANDATE_DOWNLOAD'                     => "Download",
    
'FCPO_EXPORT_CONFIG'                        => "Konfiguration exportieren",
    
'FCPO_ASSIGN_COUNTRIES'                     => "L&auml;nder zuordnen",
'FCPO_COUNTRIES'                            => "L&auml;nder",
'FCPO_LANGUAGE'                             => "Sprache",
'FCPO_CURRENCY'                             => "W&auml;hrung",
    
'FCPO_HELP_KLARNA_CAMPAIGNS'                => "Die Bestellungen werden bei Klarna nur akzeptiert, wenn die Kombination aus Land, Sprache und W&auml;hrung zusammen passt.<br>Sie m&uuml;ssen diese Kombinationen hier auch hinterlegen und der Kunde bekommt dann nur die passenden Kampagnen angezeigt.",
    
'FCPO_PAYPAL_DELADDRESS'                    => 'Rechnungsadresse bei fehlender Lieferadresse als Lieferadresse &uuml;bergeben.',
'FCPO_HELP_PAYPAL_DELADDRESS'               => 'Wird f&uuml;r den PayPal Verk&auml;uferschutz ben&ouml;tigt.',
'FCPO_PAYPAL_LOGOS'                         => 'Hier k&ouml;nnen Sie die verwendeten PayPal Express Logos hinterlegen.',    
'FCPO_PAYPAL_LOGOS_ACTIVE'                  => 'Aktiv',
'FCPO_PAYPAL_LOGOS_LANG'                    => 'Sprache',
'FCPO_PAYPAL_LOGOS_LOGO'                    => 'Logo',
'FCPO_PAYPAL_LOGOS_UPLOAD'                  => 'Hochladen',
'FCPO_PAYPAL_LOGOS_DEFAULT'                 => 'Standard',
'FCPO_PAYPAL_LOGOS_NOT_EXISTING'            => 'Kein Logo vorhanden!',
    
'ORDER_OVERVIEW_FCPO_ELV_BLZ'               => "BLZ",
'ORDER_OVERVIEW_FCPO_ELV_KTONR'             => "Kontonummer",
'ORDER_OVERVIEW_FCPO_ELV_IBAN'              => "IBAN",
'ORDER_OVERVIEW_FCPO_ELV_BIC'               => "BIC",
    
'FCPO_CC_CONFIG'                            => 'Feldkonfiguration',
'FCPO_CC_STANDARD_STYLE'                    => 'Standardstil',
'FCPO_CC_ERRORS'                            => 'Fehlerausgabe',
    
'FCPO_CC_STANDARD_INPUT'                    => 'Eingabe',
'FCPO_CC_STANDARD_SELECTION'                => 'Auswahl',
'FCPO_CC_STANDARD_FIELDS'                   => 'Felder',
'FCPO_CC_STANDARD_IFRAME'                   => 'Iframe',
    
'FCPO_CC_ACTIVE'                            => 'Aktiv',
'FCPO_CC_LANGUAGE'                          => 'Sprache',
'FCPO_CC_SELECT'                            => 'Auswahl',
    
'FCPO_CC_ERRORLANG_DE'                      => 'Deutsch',
'FCPO_CC_ERRORLANG_EN'                      => 'Englisch',
'FCPO_CC_CUSTOM_TEMPLATE'                   => 'Benutzerdefinierte Anpassung hosted-Iframe',
'FCPO_CC_PREVIEW'                           => 'Vorschau',
    
'FCPO_CREDITCARD'                           => 'Karte:',
'FCPO_NUMBER'                               => 'Nummer:',
'FCPO_CARD_SECURITY_CODE'                   => 'Pr�fziffer:',
'FCPO_VALID_UNTIL'                          => 'G�ltig bis:',
'FCPO_FIRSTNAME'                            => 'Vorname:',
'FCPO_LASTNAME'                             => 'Nachname:',
    
'FCPO_PREVIEW_NOTICE'                       => '&Auml;nderungen sind in der Vorschau erst nach dem Speichern sichtbar!',
    
'FCPO_CC_TYPE'                              => "Anfragetyp",
'FCPO_HELP_CC_TYPE'                         => "hosted-Iframe: Die Eingabefelder werden in einem von Payone gelieferten Iframe angezeigt ( PCI DSS 3.0 konform ).<br>AJAX - Die Kreditkarten-Informationen werden mittels AJAX an Payone gesendet.",

'FCPO_CC_HEADER_TYPE'                       => 'Typ',
'FCPO_CC_HEADER_DIGIT_COUNT'                => 'Anzahl<br>Zeichen',
'FCPO_CC_HEADER_DIGIT_MAX'                  => 'Zeichen<br>Max',
'FCPO_CC_HEADER_IFRAME'                     => 'Iframe',
'FCPO_CC_HEADER_WIDTH'                      => 'Breite',
'FCPO_CC_HEADER_HEIGHT'                     => 'H&ouml;he',
'FCPO_CC_HEADER_STYLE'                      => 'Stil',
'FCPO_CC_HEADER_CSS'                        => 'CSS',
    
'FCPO_CC_ROW_CC_Number'                     => 'Kreditkartennummer',
'FCPO_CC_ROW_CC_CVC'                        => 'Kartenpr&uuml;fziffer',
'FCPO_CC_ROW_CC_Month'                      => 'G&uuml;ltigkeitsmonat',
'FCPO_CC_ROW_CC_Year'                       => 'G&uuml;ltigkeitsjahr',
    
'FCPO_CC_TYPE_NUMERIC'                      => 'Numerisch',
'FCPO_CC_TYPE_PASSWORD'                     => 'Passwort',
'FCPO_CC_TYPE_TEXT'                         => 'Text',
    
'FCPO_CC_IFRAME_STANDARD'                   => 'Standard',
'FCPO_CC_IFRAME_CUSTOM'                     => 'Benutzerdefiniert',
    
);

/*
[{ oxmultilang ident="GENERAL_YOUWANTTODELETE" }]
*/
