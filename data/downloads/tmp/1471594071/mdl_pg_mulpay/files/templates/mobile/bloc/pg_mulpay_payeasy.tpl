<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->
<!--{strip}-->
        <!--{if !$tpl_is_loading}-->
                <!--{if $tpl_is_select_page_call}-->
                <b>pay-easy決済と注文処理が完了しました。</b>
                <hr>
                   金融機関反映のため、20秒程度お待ち下さい。<br>
                    その後「次へ」ボタンをクリックで金融機関選択画面に移動します。<br>
                    <font color="#FF0000">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font>
                    <br>
                    <br>
                    <input type="hidden" name="code" value="<!--{$EncryptReceiptNo}-->" />
                    <input type="hidden" name="rkbn" value="1" />
                    <center><input type="submit" value="次へ"></center>
                <!--{else}-->
                    <b>決済処理を続けます。</b>
                    <hr>
                   決済処理を完了するため<br>
                    「次へ」ボタンをクリックして下さい。<br>
                    <font color="#FF0000">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font>
                    <br>
                    <br>
                    <center><input type="submit" value="次へ"></center>

                <!--{/if}-->
        <!--{/if}-->

        <!--{if $tpl_is_loading}-->
                <b>pay-easy決済エラー</b><br>
                大変お手数ですがエラーの内容を確認して、再度お手続きを進めて下さい。<br>

                <!--{assign var=key1 value="payment"}-->
                <font color="#FF0000">
                <!--{$arrErr[$key1]}-->
                </font>
    </form>

    <form action="?mode=return" method="get">
        <input type="hidden" name="mode" value="return">
        <center><input type="submit" value="戻る"></center>
    </form>
        <!--{/if}-->

<!--{/strip}-->

