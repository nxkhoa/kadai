<!--{*
 * Copyright(c) 2013 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2013/01/10
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
                <p>ソフトバンクまとめて支払いの準備が完了しました。</p>
                <span class="attention">ソフトバンクまとめて支払い画面へ遷移します。</span>
                <input type="hidden" name="AccessID" value="<!--{$AccessID|h}-->" />
                <input type="hidden" name="Token" value="<!--{$Token|h}-->" />
                <!--{else}-->
                <p>ソフトバンクまとめて支払い処理中です。そのまま、お待ち下さい。</p>
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
                <p>ソフトバンクまとめて支払い決済エラー<br />
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

