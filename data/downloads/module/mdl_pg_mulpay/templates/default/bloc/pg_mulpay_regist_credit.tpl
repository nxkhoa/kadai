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
        <div id="payment_form_td_tran" style="<!--{if !$tpl_is_td_tran}-->display:none;<!--{/if}-->">
            <div class="information">
                <p>本人認証サービス(3-Dセキュア認証)の画面に移動します。</p>
            </div>
            <table summary="">
                <tr>
                <td class="alignC">
                    本人認証サービス（3-Dセキュア認証）を続けます。<br />
                    「次へ」ボタンをクリックして下さい。<br />
                    <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。
</span>
                </td>
                </tr>
            </table>
        </div>
        <input type="hidden" name="PaReq" value="<!--{$arrTdData.PaReq}-->" />
        <input type="hidden" name="TermUrl" value="<!--{$arrTdData.TermUrl}-->" />
        <input type="hidden" name="MD" value="<!--{$arrTdData.MD}-->" />
        <!--{/if}-->
        <div id="payment_form_loading" style="<!--{if !$tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information">
                <p>決済処理中です。しばらくお待ち下さい。</p>
            </div>
            <table summary="">
                <tr>
                <td class="alignC">
                    <img src="<!--{$smarty.const.MDL_PG_MULPAY_MEDIAFILE_URL}-->loading.gif" />
                </td>
                </tr>
            </table>
        </div>
        <!--{assign var=key1 value="payment"}-->
        <!--{if $arrErr[$key1] != ""}-->
        <div id="payment_form_body" style="<!--{if $tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information">
                <p>決済処理でエラーが発生しました。</p>
                <!--{assign var=key1 value="payment"}-->
                <!--{assign var=key2 value="CardSeq"}-->
                <p class="attention"><!--{$arrErr[$key1]}--></p>
            </div>
            <div class="btn_area">
                <ul>
                    <li>
                        <input type="image" onclick="return fnCheckSubmit('return');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back" id="back"/>
                    </li>
                </ul>
            </div>
        </div>
        <!--{else}-->
        <div id="payment_form_body" style="<!--{if $tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information">
                <p>決済に利用するカードをご選択ください。「<span class="attention">※</span>」印は入力必須項目です。<br />
                入力後、一番下の「次へ」ボタンをクリックしてください。</p>
                <!--{assign var=key2 value="CardSeq"}-->
                <p class="attention"><!--{$arrErr[$key1]}--></p>
                <p class="attention"><!--{$arrErr[$key2]}--></p>
            </div>
            <table summary="クレジットカード選択">
                <colgroup width="10%"></colgroup>
                <colgroup width="10%"></colgroup>
                <colgroup width="80%"></colgroup>
                <tr>
                    <th class="alignC">選択<span class="attention">※</span></th>
                    <th colspan="2" class="alignC">登録カード番号選択</th>
                </tr>

                <!--{assign var=key1 value="CardSeq"}-->
                <!--{foreach from=$arrData item=data}-->
                <tr>
                    <th class="alignC">
                        <input type="radio" name="CardSeq" value="<!--{$data.CardSeq|h}-->" <!--{if $arrForm[$key1].value==$data.CardSeq}-->checked="checked"<!--{/if}--> <!--{if $tpl_plg_target_seq==$data.CardSeq}-->checked="checked"<!--{/if}-->/>
                    </th>
                    <td class="alignC">
                        <!--{$data.CardSeq|h}-->
                    </td>
                    <td>
                    カード番号: <!--{$data.CardNo|h}-->&nbsp;&nbsp; 有効期限: <!--{$data.Expire|h}-->
                    </td>
                </tr>
                <!--{/foreach}-->
                <tr>
                    <th class="alignR" colspan="2">
                        支払い方法<span class="attention">※</span>
                    </th>
                    <td>
                        <!--{assign var=key1 value="Method"}-->
                        <span class="attention"><!--{$arrErr[$key1]}--></span>
                        <select name="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
                        <!--{html_options options=$arrPayMethod selected=$arrForm[$key1].value}-->
                        </select>
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
                    <!--{if $tpl_btn_next}-->
                        <input type="image" onclick="return fnCheckSubmit('next');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" border="0" name="next" id="next" />
                    <!--{else}-->
                        <input type="image" onclick="return fnCheckSubmit('next');" onmouseover="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_order_complete_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_order_complete.jpg" alt="ご注文完了ページへ"  name="next" id="next" />
                    <!--{/if}-->
                    </li>
                </ul>
            </div>
            <!--{/if}-->

       </div><!--{* /payment_form_body *}-->

