<!--{*
 * Copyright(c) 2013 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2014/01/08
 *}-->
<!--{strip}-->
        <!--{if !$tpl_is_loading}-->
            <b>決済処理を続けます。</b>
            <hr>
                <!--{if $tpl_is_select_page_call}-->
                <b>ソフトバンクまとめて支払いの画面に移動します。</b>
                    「次へ」ボタンをクリックして下さい。<br>
                <font color="#FF0000">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font>
                <input type="hidden" name="AccessID" value="<!--{$AccessID|h}-->" />
                <input type="hidden" name="Token" value="<!--{$Token|h}-->" />
                <center><input type="submit" value="次へ"></center>
                <!--{else}-->
                   決済処理を完了するため<br>
                    「次へ」ボタンをクリックして下さい。<br>
                    <font color="#FF0000">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font>
                    <br>
                    <br>
                <center><input type="submit" value="次へ"></center>
                <!--{/if}-->
        <!--{/if}-->

        <!--{if $tpl_is_loading}-->
                <b>ソフトバンクまとめて支払いエラー</b><br>
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
