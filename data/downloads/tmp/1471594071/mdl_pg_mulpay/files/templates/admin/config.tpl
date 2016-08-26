<!--{*
 * Copyright(c) 2013 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2013/04/26
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

function site_win(){
    var server_url = document.form1.kanri_server_url.value;
    if (server_url == '') {
        alert("管理画面サーバURLを設定してください。");
        return;
    }
    var site_id = document.form1.site_id.value;
        if (site_id == '') {
        alert("サイトIDを設定してください。");
        return;
    }

    var WIN;
    WIN = window.open(server_url + 'site/' +site_id + '/index');
    WIN.focus();
}

function shop_win(){
    var server_url = document.form1.kanri_server_url.value;
    if (server_url == '') {
        alert("管理画面サーバURLを設定してください。");
        return;
    }
    var shop_id = document.form1.ShopID.value;
        if (shop_id == '') {
        alert("ショップIDを設定してください。");
        return;
    }

    var WIN;
    WIN = window.open(server_url + 'shop/' + shop_id + '/index');
    WIN.focus();
}

function connect_select() {
    var connect_type = $("input:radio[name='connect_server_type']:checked").val();
    if (connect_type == '3') {
        $('#server_url').show();
        $('#kanri_server_url').show();
    } else {
        $('#server_url').hide();
        $('#kanri_server_url').hide();
    }
}

$(function(){
    connect_select();
    $("input:radio[name='connect_server_type']").change(function() {
        connect_select();
        });
});
//-->
</script>
<style type="text/css">
.info { font-size: 90%; }
#test-area{
    padding: 10px 0px;
    background: #f5f5f5;
    text-align: center;
}
</style>



<h1><span class="title"><!--{$tpl_subtitle}--></span></h1>
<span>
<!--{$smarty.const.MDL_PG_MULPAY_MODULE_NAME}-->をご利用頂く為には<!--{$smarty.const.MDL_PG_MULPAY_COMPANY_NAME|h}-->とご契約を行っていただく必要があります。
</span>
<p style="margin:12px 0px 12px 0px;display:block; height:35px;" align="center">
<a href="http://www.ec-cube.net/rd.php?aid=a5177c923cb929" target="_blank" onmouseover="chgImg('<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->about_pg_on.jpg','pg');" onmouseout="chgImg('<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->about_pg.jpg','pg')">
<img width="304" height="35" border="0" name="pg" alt="PGマルチペイメントサービスについて" src="<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->about_pg.jpg" />
</a>
</p>
<div id="test-area">
<span>
ご希望の方は、以下のURLよりテスト環境を申し込むことで、<br />テスト運用に必要なアカウント情報を取得することが出来ます。
</span>
<p style="margin:12px 0px 0px 0px;display:block;height:35px;" align="center">
<a href="http://www.ec-cube.net/rd.php?aid=a5177c98d5464d" target="_blank" onmouseover="chgImg('<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->test_on.jpg','test');" onmouseout="chgImg('<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->test.jpg','test');">
<img width="304" height="35" border="0" name="test" alt="テストアカウントについて" src="<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->test.jpg" />
</a></p>
</div>

<h2><!--{$smarty.const.MDL_PG_MULPAY_SERVICE_NAME|h}-->共通設定</h2>
<span>
設定方法については、以下のサイト内の「<a href="http://www.ec-cube.net/products/detail.php?product_id=323" target="_blank">マニュアルダウンロード</a>」をご参照下さい。<br />
<a href="http://www.ec-cube.net/products/detail.php?product_id=323" target="_blank">http://www.ec-cube.net/products/detail.php?product_id=323</a>
</span>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|escape}-->">
  <input type="hidden" name="mode" value="" />
  <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
  <table class="form">
    <colgroup width="20%">
    <colgroup width="80%">
    <!--{if $arrErr.err != ""}-->
    <tr>
      <td colspan="2"><span class="attention"><!--{$arrErr.err}--></span></td>
    </tr>
    <!--{/if}-->

    <!--{assign var=key value="connect_server_type"}-->
    <tr id="<!--{$key}-->">
      <th>接続先<span class="attention"> *</span></th>
      <td>
      <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
      <!--{html_radios name="$key" options=$arrConnectServerType selected=$arrForm[$key].value separator="&nbsp;"}-->
      </td>
    </tr>

    <!--{assign var=key value="server_url"}-->
    <tr id="<!--{$key}-->">
      <th>接続先サーバーURL<span class="attention"> *</span></th>
      <td>
        <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box60" maxlength="<!--{$arrForm[$key].length}-->" />
      </td>
    </tr>

    <!--{assign var=key value="kanri_server_url"}-->
    <tr id="<!--{$key}-->">
      <th>管理画面サーバーURL<span class="attention"> *</span></th>
      <td>
        <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box60" maxlength="<!--{$arrForm[$key].length}-->" />
      </td>
    </tr>

    <!--{assign var=key value="site_id"}-->
    <tr id="<!--{$key}-->">
      <th>サイトID<span class="attention"> *</span></th>
      <td>
        <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box40" maxlength="<!--{$arrForm[$key].length}-->" />
        <br />
        <span class="info">*PGマルチペイメントサービスの管理画面にログインするIDとは異なりますので、ご注意ください。</span>
      </td>
    </tr>

    <!--{assign var=key value="site_pass"}-->
    <tr id="<!--{$key}-->">
      <th>サイトパスワード<span class="attention"> *</span></th>
      <td>
        <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box40" maxlength="<!--{$arrForm[$key].length}-->" />
        <br />
        <span class="info">*PGマルチペイメントサービスの管理画面にログインするパスワードとは異なりますので、ご注意ください。</span>
      </td>
    </tr>

    <!--{assign var=key value="ShopID"}-->
    <tr id="<!--{$key}-->">
      <th>ショップID<span class="attention"> *</span></th>
      <td>
        <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box40" maxlength="<!--{$arrForm[$key].length}-->" />
        <br />
        <span class="info">*PGマルチペイメントサービスの管理画面にログインするIDとは異なりますので、ご注意ください。</span>
      </td>
    </tr>

    <!--{assign var=key value="ShopPass"}-->
    <tr id="<!--{$key}-->">
      <th>ショップパスワード<span class="attention"> *</span></th>
      <td>
        <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value|h}-->" class="box40" maxlength="<!--{$arrForm[$key].length}-->" />
        <br />
        <span class="info">*PGマルチペイメントサービスの管理画面にログインするパスワードとは異なりますので、ご注意ください。</span>
      </td>
    </tr>

    <!--{assign var=key value="enable_payment_type"}-->
    <tr id="<!--{$key}-->">
      <th>有効にする決済方法<span class="attention"> *</span></th>
      <td>
        <!--{if $arrErr[$key]}--><span class="attention"><!--{$arrErr[$key]}--></span><!--{/if}-->
        <!--{html_checkboxes name="$key" options=$arrPayments selected=$arrForm[$key].value separator="<br />"}-->
        <br />
        <span class="info">*決済方法毎の詳細設定は有効後に<a href="javascript:void(win_open('./basis/payment.php'));">支払方法設定画面</a>で行います。</span>
      </td>
    </tr>

    <tr>
      <th>決済用テンプレート初期化</th>
      <td>
        <input type="checkbox" name="is_tpl_init" value="1" id="is_tpl_init" /><label for="is_tpl_init">決済用テンプレートを初期化する。</label>
        <br />
        <span class="attention">*ページやブロックのデザインテンプレートの内容を初期化します。</span><br />
        <span class="info">*決済に関するテンプレートを修正されている場合には取り扱いに注意して下さい。<br />
        初期化時には、初期化前のテンプレートデータを保存ディレクトリ内にバックアップが作成されます。
        </span>
      </td>
    </tr>

    <tr>
      <th>結果通知プログラムURL</th>
      <td>
        <!--{$smarty.const.MDL_PG_MULPAY_SETTLEMENT_URL|h}--><br />
        <span class="info">*ショップ管理画面よりログインして頂き、タブ「ショップの管理」＞タブ「メール/結果通知設定」で結果通知プログラムURLに設定してください。</span>
      </td>
    </tr>
<!--{*
    <tr>
      <th>お問合せ</th>
      <td>
        <a href=""><!--{$smarty.const.MDL_PG_MULPAY_SERVICE_NAME}--> お問合せプラグインより、お問合せ下さい</a><br />
      </td>
    </tr>
*}-->
    <tr>
      <th>サイト管理</th>
      <td>
        <a href="javascript:void(site_win());">＞＞サイト管理画面</a><br />
        <span class="info">*本番環境管理画面はGMOペイメントゲートウェイ株式会社より発行 れた「クライアント証明書」がインストールされたブラウザでアクセスする必要があります。</span>
      </td>
    </tr>

    <tr>
      <th>ショップ管理</th>
      <td>
        <a href="javascript:void(shop_win());">＞＞ショップ管理画面</a><br />
        <span class="info">*本番環境管理画面はGMOペイメントゲートウェイ株式会社より発行 れた「クライアント証明書」がインストールされたブラウザでアクセスする必要があります。</span>
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
