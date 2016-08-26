<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->

<!--{strip}-->
        <!--{if $tpl_is_td_tran}-->
            <b>本人認証サービス(3-Dセキュア認証)の画面に移動します。</b>
            <hr>
                    本人認証サービス（3-Dセキュア認証）を続けます。<br>
                    「次へ」ボタンをクリックして下さい。<br>
                    <font color="#FF0000">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font>
                    <br>
                    <br>
            <input type="hidden" name="PaReq" value="<!--{$arrTdData.PaReq}-->" />
            <input type="hidden" name="TermUrl" value="<!--{$arrTdData.TermUrl}-->" />
            <input type="hidden" name="MD" value="<!--{$arrTdData.MD}-->" />
            <center><input type="submit" value="次へ"></center>
        <!--{/if}-->

        <!--{if $tpl_is_loading}-->
            <b>決済処理を続けます。</b>
            <hr>
                   決済処理を完了するため<br>
                    「次へ」ボタンをクリックして下さい。<br>
                    <font color="#FF0000">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font>
                    <br>
                    <br>
            <center><input type="submit" value="次へ"></center>
        <!--{/if}-->

        <!--{if !$tpl_is_loading}-->
            <!--{assign var=key1 value="payment"}-->
            <!--{if $arrErr[$key1] != ""}-->
                <b>決済エラー</b><br>
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

            <!--{else}-->
                <p>決済に利用するカードをご選択下さい。<br>
                入力後、一番下の「次へ」ボタンをクリックしてください。</p>
                <!--{assign var=key2 value="CardSeq"}-->
                <font color="#FF0000"><!--{$arrErr[$key2]}--></font>
            <hr>
                    ■クレジットカード選択<br>
                <!--{assign var=key1 value="CardSeq"}-->
                <!--{foreach from=$arrData item=data}-->
                    <input type="radio" name="CardSeq" value="<!--{$data.CardSeq|h}-->" <!--{$data|sfGetChecked:$arrForm[$key].value}--> <!--{$data|sfGetChecked:$tpl_plg_target_seq}--> />
                    カード番号: <!--{$data.CardNo|h}-->&nbsp;有効期限: <!--{$data.Expire|h}--><br>
                    <br>
                <!--{/foreach}-->
                <br>

                    ■支払い方法 <font color="#FF0000">*</font><br>
                        <!--{assign var=key1 value="Method"}-->
                    <!--{if $arrErr[$key1] != ""}-->
                        <font color="#FF0000"><!--{$arrErr[$key1]}--></font><br>
                    <!--{/if}-->
                        <select name="<!--{$key1}-->">
                        <!--{html_options options=$arrPayMethod selected=$arrForm[$key1].value}-->
                        </select>
                    <br>
                    <br>

        以上の内容で間違いなければ、下記「次へ」ボタンをクリックしてください。<br />
        <font color="#FF0000">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</font>

            <center><input type="submit" value="次へ"></center>
            <!--{/if}-->
    </form>


    <form action="?mode=return" method="get">
        <input type="hidden" name="mode" value="return">
        <center><input type="submit" value="戻る"></center>
    </form>
        <!--{/if}-->
<!--{/strip}-->
