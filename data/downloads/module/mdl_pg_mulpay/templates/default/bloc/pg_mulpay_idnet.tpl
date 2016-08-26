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
        <!--{if !$tpl_is_select_page_call}-->
            <div class="information">
                <p>iD決済処理中です。そのまま、お待ち下さい。</p>
                <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
            </div>
            <table summary="">
                <tr>
                <td class="alignC">
                    <img src="<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->loading.gif" />
                </td>
                </tr>
            </table>
        <!--{else}-->
            <div class="information">
                <p>iD決済と注文処理が完了しました。</p>
                <span class="attention">iD決済画面に自動で画面が切り替わります。</span>
                <input type="hidden" name="AccessID" value="<!--{$AccessID}-->" />
            </div>
            <div class="btn_area">
                <ul>
                    <li>
                        <input type="image" onclick="return fnCheckSubmit('next');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" border="0" name="next" id="next" />
                    </li>
                </ul>
            </div>
        <!--{/if}-->

        </div>

        <div id="payment_form_body" style="<!--{if $tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information">
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
            <table summary="iD決済入力">
                <colgroup width="25%"></colgroup>
                <colgroup width="75%"></colgroup>
                <tr>
                    <th class="alignC" colspan="2">iD決済入力</th>
                </tr>
                <tr>
                    <th class="alignR">
                        決済端末メールアドレス<span class="attention">※</span>
                    </th>
                    <td>
                    <!--{assign var=key1 value="MailAddress"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="128" class="box240" />

                    <p class="mini"><span class="attention">※iD決済を行うモバイル端末のメールアドレスを入力して下さい。</span></p>

                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td>
                        以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
                        <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
                    </td>
                </tr>
            </table>

            <div class="btn_area">
                <ul>
                    <li>
                        <input type="image" onclick="return fnCheckSubmit('return');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back" id="back"/>
                    </li>
                    <li>
                        <input type="image" onclick="return fnCheckSubmit('next');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" border="0" name="next" id="next" />
                    </li>
                </ul>
            </div>

       </div><!--{* /payment_form_body *}-->

