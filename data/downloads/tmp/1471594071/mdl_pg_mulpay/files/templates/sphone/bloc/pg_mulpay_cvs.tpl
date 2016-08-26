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

//]]>
</script>

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

        <section id="payment_form_body" style="<!--{if $tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information end">
                <p>下記項目をご選択ください。<br />
                入力後、一番下の「次へ」ボタンをクリックしてください。</p>
                <!--{assign var=key1 value="payment"}-->
                <!--{assign var=key2 value="conveni"}-->
                <p class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></p>
            </div>

                <h3 class="subtitle">お支払いをするコンビニの種類</h3>
                <ul class="form_entry">

                <!--{assign var=key value="Convenience"}-->
                <!--{foreach from=$arrPaymentInfo.conveni item=data key=id name=cvsloop}-->
                    <li>
                        <input class="radio_btn data-role-none" type="radio" id="cvs_id_<!--{$data|h}-->" name="<!--{$key}-->" value="<!--{$data|h}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" <!--{$data|sfGetChecked:$arrForm[$key].value}--> />
                        <label for="cvs_id_<!--{$data|h}-->"><!--{$arrCONVENI[$data]|h}--></label>
                    </li>
                <!--{/foreach}-->
                </ul>


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

