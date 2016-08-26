<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
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
    setTimeout(fnModeSubmit('next','',''), 1000);
}
$(function() {
    <!--{$tpl_payment_onload}-->
});
//]]>
</script>

        <section id="payment_form_loading" style="<!--{if !$tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information end">
                <!--{if $tpl_is_select_page_call}-->
                <p>pay-easy決済と注文処理が完了しました。</p>
                <span class="attention">金融機関反映のため、20秒程度お待ち下さい。自動で画面が切り替わります。</span>
                <input type="hidden" name="code" value="<!--{$EncryptReceiptNo}-->" />
                <input type="hidden" name="rkbn" value="1" />
                <!--{else}-->
                <p>pay-easy決済処理中です。そのまま、お待ち下さい。</p>
                <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
                <!--{/if}-->
            </div>
            <div class="bubbleBox hot">
                <div class="bubble_announce clearfix">
                    <img src="<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->loading.gif" />
                </div>
            </div>
        </section>

        <section id="payment_form_body" style="<!--{if $tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information end">
                <p>pay-easy決済エラー<br />
                大変お手数ですがエラーの内容を確認して、再度お手続きを進めて下さい。</p>
                <!--{assign var=key1 value="payment"}-->
                <p class="attention"><!--{$arrErr[$key1]}--></p>
            </div>

            <div class="btn_area">
                <ul class="btn_btm">
                        <li>
                            <a rel="external" href="javascript:void(fnCheckSubmit('return'));" class="btn_back"/>戻る</a>
                        </li>
                </ul>
            </div>
       </section><!--{* /payment_form_body *}-->

