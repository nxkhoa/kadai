<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->
<!--{strip}-->


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
                <p>下記項目にご選択ください。<br>
                入力後、一番下の「次へ」ボタンをクリックしてください。</p>
                <!--{assign var=key value="payment"}-->
                <font color="#FF0000"><!--{$arrErr[$key]}--></font>
            <hr>
                    ■コンビニ選択<br>
                    <!--{assign var=key2 value="conveni"}-->
                    <!--{if $arrErr[$key2] != ""}-->
                        <font color="#FF0000"><!--{$arrErr[$key2]}--></font><br>
                    <!--{/if}-->

                <!--{assign var=key value="Convenience"}-->
                <!--{foreach from=$arrPaymentInfo.conveni item=data key=id name=cvsloop}-->

                        <input type="radio" id="cvs_id_<!--{$data|h}-->" name="<!--{$key}-->" value="<!--{$data|h}-->" <!--{$data|sfGetChecked:$arrForm[$key].value}--> />
                        <!--{$arrCONVENI[$data]|h}-->
                        <br>
                <!--{/foreach}-->
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
<!--{/strip}-->

