 <link rel="stylesheet" type="text/css" href="[{$oViewConf->fcpoGetModuleCssPath('fcpoamazon.css')}]">

[{if $sAmazonButtonId}]
    [{assign var="iAmzButtonIncluded" value=$iAmzButtonIncluded+1}]
[{elseif !$iAmzButtonIncluded}]
    [{assign var="iAmzButtonIncluded" value="0"}]
[{else}]
    [{assign var="iAmzButtonIncluded" value=$iAmzButtonIncluded+1}]
[{/if}]

[{if !$sAmazonButtonId}]
    [{assign var="sAmazonButtonId" value='LoginWithAmazon'}]
[{/if}]

[{$oViewConf->fcpoSetCurrentAmazonButtonId($sAmazonButtonId)}]

<div id="[{$sAmazonButtonId}][{$iAmzButtonIncluded}]" class="[{$sAmazonButtonClass}]"></div>
<script>
    // initialize client
    if (typeof window.onAmazonLoginReady !== 'function') {
        window.onAmazonLoginReady = function() {
            amazon.Login.setClientId('[{$oViewConf->fcpoGetAmazonPayClientId()}]');
            [{if !$oViewConf->fcpoAmazonLoginSessionActive()}]
                amazon.Login.logout();
            [{/if}]
        };
    }

    // initialize button array
    if (typeof window.onAmazonPaymentsReadyArray === 'undefined') {
        window.onAmazonPaymentsReadyArray = [];
    }

    // iterate through filled array with buttons
    if (typeof window.onAmazonPaymentsReady !== 'function') {
        window.onAmazonPaymentsReady = function () {
            window.onAmazonPaymentsReadyArray.forEach(function (callback) {
                callback();
            });
        };
    }

    // fill array with amazon pay button
    window.onAmazonPaymentsReadyArray.push(function () {
        var authRequest, loginOptions;
        OffAmazonPayments.Button('[{$sAmazonButtonId}][{$iAmzButtonIncluded}]', '[{$oViewConf->fcpoGetAmazonPaySellerId()}]', {
            type: '[{$oViewConf->fcpoGetAmazonPayButtonType()}]',
            color: '[{$oViewConf->fcpoGetAmazonPayButtonColor()}]',
            language: 'none',
            size: 'medium',
            authorization: function () {
                loginOptions = {
                    scope: 'payments:billing_address payments:shipping_address payments:widget profile',
                    popup: [{$oViewConf->fcpoGetAmzPopup()}]
                };
                authRequest = amazon.Login.authorize(loginOptions, '[{$oViewConf->fcpoGetAmazonRedirectUrl()}]');
            }
        });
    });

</script>
[{if $oViewConf->fcpoGetAllowIncludeAmazonWidgetUrl()}]
    <script async="async" src='[{$oViewConf->fcpoGetAmazonWidgetsUrl()}]'></script>
[{/if}]
