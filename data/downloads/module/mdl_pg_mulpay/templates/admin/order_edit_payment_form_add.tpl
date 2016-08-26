<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->
<!--{if $arrForm.status.value != $smarty.const.ORDER_PENDING}-->
<!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_CREDIT || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_REGIST_CREDIT || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_CREDIT_CHECK || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_CREDIT_SAUTH || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_CVS || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_PAYEASY || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_ATM || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_MOBILESUICA || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_IDNET || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_WEBMONEY || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_COLLECT || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_AU || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_SB}-->

<script type="text/javascript">//<![CDATA[
var def_payment_id = $('select[name="payment_id"]').val();

$(function() {
    $('select[name=payment_id]').attr('onchange','');
    $('select[name=payment_id]').unbind();
    $('select[name=payment_id]').change(
        function() {
            $('select[name=payment_id]').val(def_payment_id);
            alert('お支払い方法の変更は無効になります。');
        }
    );
});


//]]>
</script>


<!--{/if}-->
<!--{/if}-->

