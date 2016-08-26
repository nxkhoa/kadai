<!--{*
 * Copyright(c) 2012-2013 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2013/06/19
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
    var mode = 'load';
    send = false;
    fnModeSubmit('load','','');
}
$(function() {
    <!--{$tpl_payment_onload}-->
});

//]]>
</script>
        <!--{if $tpl_is_td_tran}-->
        <section id="payment_form_td_tran" style="<!--{if !$tpl_is_td_tran}-->display:none;<!--{/if}-->">
            <div class="information">
                <p>本人認証サービス(3-Dセキュア認証)の画面に移動します。</p>
            </div>
            <div class="bubbleBox hot">
                <div class="bubble_announce clearfix">
                    本人認証サービス（3-Dセキュア認証）を続けます。<br />
                    自動で移動しない場合は「次へ」ボタンをクリックして下さい。<br />
                </div>
                <div class="information end">
                    <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
                </div>
            </div>
            <div class="bubbleBox hot">
                <div class="bubble_announce clearfix">
                    <img src="<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->loading.gif" />
                </div>
            </div>
            <div class="btn_area">
                <ul class="btn_btm">

                        <li>
                        <!--{if $tpl_btn_next}-->
                            <a rel="external" href="javascript:void(fnCheckSubmit('next'));" class="btn"/>次へ</a>
                        <!--{/if}-->
                        </li>

                        <!--{if !$tpl_btn_next}-->
                        <li>
                            <a rel="external" href="javascript:void(fnCheckSubmit('return'));" class="btn_back"/>戻る</a>
                        </li>
                        <!--{/if}-->
                </ul>
            </div>
        </section>
        <input type="hidden" name="PaReq" value="<!--{$arrTdData.PaReq}-->" />
        <input type="hidden" name="TermUrl" value="<!--{$arrTdData.TermUrl}-->" />
        <input type="hidden" name="MD" value="<!--{$arrTdData.MD}-->" />

        <!--{/if}-->

        <!--{if !$tpl_is_td_tran}-->
         <section id="payment_form_loading" style="<!--{if !$tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information end">
               <p>決済処理中です。しばらくお待ち下さい。</p>
            </div>
            <div class="bubbleBox hot">
                <div class="bubble_announce clearfix">
                    <img src="<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->loading.gif" />
                </div>
            </div>
        </section>
        <!--{/if}-->

        <!--{assign var=key1 value="payment"}-->
        <!--{if $arrErr[$key1] != ""}-->
        <section id="payment_form_body" style="<!--{if $tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information end">
                <p>大変お手数ですがエラーの内容を確認して、再度お手続きを進めて下さい。</p>
                <!--{assign var=key1 value="payment"}-->
                <!--{assign var=key2 value="CardSeq"}-->
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

       <!--{else}-->

        <section id="payment_form_body" style="<!--{if $tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information end">
                <p>決済に利用するカードをご選択ください。「<span class="attention">※</span>」印は入力必須項目です。<br />
                入力後、一番下の「次へ」ボタンをクリックしてください。</p>
                <!--{assign var=key2 value="CardSeq"}-->
                <p class="attention"><!--{$arrErr[$key1]}--></p>
                <p class="attention"><!--{$arrErr[$key2]}--></p>
            </div>

                <h3 class="subtitle">登録カードの選択</h3>
                <ul class="form_entry">

                <!--{assign var=key1 value="CardSeq"}-->
                <!--{foreach from=$arrData item=data}-->
                    <li>
                        <input type="radio" id="CardSeq_<!--{$data.CardSeq|h}-->" class="radio_btn data-role-none"  name="CardSeq" value="<!--{$data.CardSeq|h}-->" <!--{if $arrForm[$key1].value==$data.CardSeq}-->checked="checked"<!--{/if}--> <!--{if $tpl_plg_target_seq==$data.CardSeq}-->checked="checked"<!--{/if}--> />
                        <label for="CardSeq_<!--{$data.CardSeq|h}-->">カード番号: <!--{$data.CardNo|h}--><br />有効期限: <!--{$data.Expire|h}--></label>
                    </li>
                <!--{/foreach}-->
                </ul>
                <h3 class="subtitle">お支払い方法</h3>
                <dl class="form_entry">
                    <dt>
                        支払い方法<span class="attention">※</span>
                    </dt>
                    <dd>
                        <!--{assign var=key1 value="Method"}-->
                        <span class="attention"><!--{$arrErr[$key1]}--></span>
                        <select name="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->"  class="boxShort data-role-none">
                        <!--{html_options options=$arrPayMethod selected=$arrForm[$key1].value|default:''}-->
                        </select>
                    </dd>
                </dl>

            <table>
                <tr>
                    <td>
                        以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
                        <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
                    </td>
                </tr>
            </table>

            <div class="btn_area">
                <ul class="btn_btm">

                        <li>
                        <!--{if $tpl_btn_next}-->
                            <a rel="external" href="javascript:void(fnCheckSubmit('next'));" class="btn"/>次へ</a>
                        <!--{else}-->
                            <a rel="external" href="javascript:void(fnCheckSubmit('next'));" class="btn"/>ご注文完了ページへ</a>
                        <!--{/if}-->
                        </li>

                        <!--{if !$tpl_btn_next}-->
                        <li>
                            <a rel="external" href="javascript:void(fnCheckSubmit('return'));" class="btn_back"/>戻る</a>
                        </li>
                        <!--{/if}-->
                </ul>
            </div>
       </section><!--{* /payment_form_body *}-->
        <!--{/if}-->

