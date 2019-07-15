[{oxstyle include=$oViewConf->fcpoGetModuleCssPath('fcpayone.css')}]
[{$smarty.block.parent}]
<div class="checkout-shipping-infotext">
    [{if $oViewConf->fcpoShouldDisplayCreditratingInfo()}]
        [{assign var="sFcpoApprovalText" value=$oViewConf->fcpoGetApprovalText()}]
        [{if !empty(sFcpoApprovalText)}]
            <div class="fcpoCreditratingApprovalText">
                [{$sFcpoApprovalText}]
            </div>
        [{/if}]
    [{/if}]
</div>
