<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2013/03/29
 *}-->
<script type="text/javascript">//<![CDATA[
var send = true;

function fnCheckSubmit(mode) {
    $('#payment_form_body').slideToggle();
    $('#payment_form_loading').slideToggle();

    if(send) {
        send = false;
        fnModeSubmit(mode,'','');
        return false;
    } else {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    }
}

function fnAutoLoadSubmit() {

    var mode = 'next';
    send = false;
    setTimeout("fnModeSubmit('next','','')", 1000);
}
$(function() {
    <!--{$tpl_payment_onload}-->
});
//]]>
</script>

        <div id="payment_form_loading" style="<!--{if !$tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information">
                <!--{if $tpl_is_select_page_call}-->
                <p>PayPal決済と注文処理が完了しました。</p>
                <span class="attention">PayPalのログイン画面へ遷移します。</span>
                <input type="hidden" name="ShopID" value="<!--{$ShopID}-->" />
                <input type="hidden" name="AccessID" value="<!--{$AccessID}-->" />
                <!--{else}-->
                <p>PayPal決済処理中です。そのまま、お待ち下さい。</p>
                <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
                <!--{/if}-->
            </div>
            <table summary="">
                <tr>
                <td class="alignC">
                    <img src="<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->loading.gif" />
                </td>
                </tr>
            </table>
        </div>

        <div id="payment_form_body" style="<!--{if $tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information">
                <p>PayPal決済エラー<br />
                大変お手数ですがエラーの内容を確認して、再度お手続きを進めて下さい。</p>
                <!--{assign var=key1 value="payment"}-->
                <p class="attention"><!--{$arrErr[$key1]}--></p>
            </div>
            <div class="btn_area">
                <ul>
                    <li>
                        <input type="image" onclick="return fnCheckSubmit('return');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back" id="back"/>
                    </li>
                </ul>
            </div>

       </div><!--{* /payment_form_body *}-->

