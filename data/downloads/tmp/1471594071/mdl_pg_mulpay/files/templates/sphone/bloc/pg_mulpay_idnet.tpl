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
        <!--{if !$tpl_is_select_page_call}-->
            <div class="information end">
                <p>iD決済処理中です。そのまま、お待ち下さい。</p>
                <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
            </div>
            <div class="bubbleBox hot">
                <div class="bubble_announce clearfix">
                    <img src="<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->loading.gif" />
                </div>
            </div>
        <!--{else}-->
            <div class="information end">
                <p>iD決済と注文処理が完了しました。</p>
                <span class="attention">iD決済画面に自動で画面が切り替わります。</span>
                <input type="hidden" name="AccessID" value="<!--{$AccessID}-->" />
            </div>
            <div class="btn_area">
                <ul class="btn_btm">

                        <li>
                            <a rel="external" href="javascript:void(fnCheckSubmit('next'));" class="btn"/>次へ</a>
                        </li>
                </ul>
            </div>
        <!--{/if}-->
        </section>

        <section id="payment_form_body" style="<!--{if $tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information end">
                <!--{assign var=key1 value="payment"}-->
                <!--{assign var=key2 value="MailAddress"}-->
                <!--{if $arrErr[$key1] != ""}-->
                <p>iD決済エラー<br />大変お手数ですがエラーの内容を確認して、再度お手続きを進めて下さい。</p>
                <p class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></p>
                <!--{else}-->
                <p>下記項目をご入力ください。<br />
                入力後、一番下の「次へ」ボタンをクリックしてください。</p>
                <p class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></p>
                <!--{/if}-->
            </div>

                <h3 class="subtitle"><!--{$tpl_title|h}-->入力</h3>
                <dl class="form_entry">
                    <dt>
                        決済端末メールアドレス<span class="attention">※</span>
                    </dt>
                    <dt>
                    <!--{assign var=key1 value="MailAddress"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="128" class="box240" />

                    <p class="mini"><span class="attention">※iD決済を行うモバイル端末のメールアドレスを入力して下さい。</span></p>

                    </dt>
                </dl>


            <div class="btn_area">
                <ul class="btn_btm">

                        <li>
                            <a rel="external" href="javascript:void(fnCheckSubmit('next'));" class="btn"/>次へ</a>
                        </li>

                        <li>
                            <a rel="external" href="javascript:void(fnCheckSubmit('return'));" class="btn_back"/>戻る</a>
                        </li>
                </ul>
            </div>
       </section><!--{* /payment_form_body *}-->


