<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->
<!--{strip}-->

        <!--{if !$tpl_is_loading}-->
            <!--{if !$tpl_is_select_page_call}-->
                <b>決済処理を続けます。</b>
                <hr>
                       決済処理を完了するため<br>
                        「次へ」ボタンをクリックして下さい。<br>
                        <font color="#FF0000">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font>
                        <br>
                        <br>
                <center><input type="submit" value="次へ"></center>
            <!--{else}-->
                <b>決済処理を続けます。</b>
                <hr>
                    <b>注文処理が完了しました。<br>iDネット決済の画面に移動します。</b>
                        「次へ」ボタンをクリックして下さい。<br>
                    <font color="#FF0000">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font>
                        <br>
                        <br>
                    <input type="hidden" name="AccessID" value="<!--{$AccessID}-->" />
                <center><input type="submit" value="次へ"></center>
            <!--{/if}-->
        <!--{/if}-->

        <!--{if $tpl_is_loading}-->
                <!--{assign var=key1 value="payment"}-->
                <!--{assign var=key2 value="MailAddress"}-->
                <!--{if $arrErr[$key1] != ""}-->
                    iD決済エラー<br />大変お手数ですがエラーの内容を確認して、再度お手続きを進めて下さい。<br>
                    <font color="#FF0000">
                    <!--{$arrErr[$key1]}-->
                    </font>
                <!--{else}-->
                    下記項目をご入力ください。<br>
                    入力後、一番下の「次へ」ボタンをクリックしてください。<br>
                    <!--{assign var=key value="payment"}-->
                    <font color="#FF0000"><!--{$arrErr[$key]}--></font><br>
                <!--{/if}-->
                    <br>
                    ■決済端末メールアドレス<br>
                    <!--{assign var=key1 value="MailAddress"}-->
                    <!--{if $arrErr[$key1] != ""}-->
                        <font color="#FF0000"><!--{$arrErr[$key1]}--></font><br>
                    <!--{/if}-->
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" istyle="3"  size="128" />
                    <br>
                    <font color="#FF0000">※iD決済を行うモバイル端末のメールアドレスを入力して下さい。</font>
                    <br>
                    <br>

        以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
        <font color="#FF0000">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font>

        <center><input type="submit" value="次へ"></center>

    </form>

    <form action="?mode=return" method="get">
        <input type="hidden" name="mode" value="return">
        <center><input type="submit" value="戻る"></center>
    </form>

        <!--{/if}-->

<!--{/strip}-->

