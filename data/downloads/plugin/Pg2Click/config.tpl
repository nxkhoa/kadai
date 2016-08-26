<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 *}-->
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<!--{include file='css/contents.tpl'}-->
<script type="text/javascript">
<!--
self.moveTo(20,20);self.focus();

function win_open(URL){
    var WIN;
    WIN = window.open(URL);
    WIN.focus();
}

//-->
</script>
<style type="text/css">
.info { font-size: 90%; };
</style>



<h1><span class="title"><!--{$tpl_subtitle}--></span></h1>
<h2><!--{$smarty.const.MDL_PG_MULPAY_MODULE_NAME}--></h2>
<span>

<!--{$tpl_subtitle}-->をご利用頂く為には<!--{$smarty.const.MDL_PG_MULPAY_COMPANY_NAME|h}-->とご契約を行っていただく必要があります。<br />
&nbsp;&nbsp;<a href="#" onClick="win_open('<!--{$smarty.const.MDL_PG_MULPAY_INFO_URI}-->')" > ＞＞<!--{$smarty.const.MDL_PG_MULPAY_SERVICE_NAME}-->について</a>

</span>

<h2>ライセンス設定</h2>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|escape}-->">
  <input type="hidden" name="mode" value="" />
  <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
  <table class="form">
    <colgroup width="20%">
    <colgroup width="80%">
    <!--{if $arrErr.err != ""}-->
    <tr>
      <td colspan="2"><!--{$arrErr.err}--></td>
    </tr>
    <!--{/if}-->

    <!--{assign var=key value="LicenseKey"}-->
    <tr id="<!--{$key}-->">
      <th>ラインセンスキー<span class="attention"> *</span></th>
      <td>
        <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
        <input type="password" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key]|h}-->" class="box40" />
        <br />
      </td>
    </tr>

  </table>
  <div class="btn-area">
    <ul>
      <li>
        <a class="btn-action" href="javascript:;" onclick="document.form1.mode.value='register';document.body.style.cursor = 'wait';document.form1.submit();return false;"><span class="btn-next">この内容で登録する</span></a>
      </li>
    </ul>
  </div>

</form>

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
