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
                <p>下記項目にご入力ください。「<font color="#FF0000">*</font>」印は入力必須項目です。<br>
                入力後、一番下の「次へ」ボタンをクリックしてください。</p>
                <!--{assign var=key value="payment"}-->
                <font color="#FF0000"><!--{$arrErr[$key]}--></font>
            <hr>
                    ■カード番号 <font color="#FF0000">*</font><br>
                    <!--{assign var=key1 value="CardNo"}-->
                    <!--{if $arrErr[$key1] != ""}-->
                        <font color="#FF0000"><!--{$arrErr[$key1]}--></font><br>
                    <!--{/if}-->
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" istyle="3" size="16" />
                    <br>
                    <br>
                    ■カード有効期限 <font color="#FF0000">*</font><br>
                    <!--{assign var=key1 value="Expire_month"}-->
                    <!--{assign var=key2 value="Expire_year"}-->
                    <!--{if $arrErr[$key1] != ""}-->
                        <font color="#FF0000"><!--{$arrErr[$key1]}--></font><br>
                    <!--{/if}-->
                    <!--{if $arrErr[$key2] != ""}-->
                        <font color="#FF0000"><!--{$arrErr[$key2]}--></font><br>
                    <!--{/if}-->

                    <select name="<!--{$key1}-->">
                    <option value="">&minus;&minus;</option>
                    <!--{html_options options=$arrMonth selected=$arrForm[$key1].value}-->
                    </select>月
                    &nbsp;/&nbsp;
                    20<select name="<!--{$key2}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->">
                    <option value="">&minus;&minus;</option>
                    <!--{html_options options=$arrYear selected=$arrForm[$key2].value}-->
                    </select>年
                    <br>
                    <br>
                    ■カード名義 <font color="#FF0000">*</font><br>
                        <!--{assign var=key1 value="card_name1"}-->
                        <!--{assign var=key2 value="card_name2"}-->
                    <!--{if $arrErr[$key1] != ""}-->
                        <font color="#FF0000"><!--{$arrErr[$key1]}--></font><br>
                    <!--{/if}-->
                    <!--{if $arrErr[$key2] != ""}-->
                        <font color="#FF0000"><!--{$arrErr[$key2]}--></font><br>
                    <!--{/if}-->

                        名:<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" istyle="3" size="20"  />
                        <br>
                        姓:<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" istyle="3" size="20"  />
                        <br>
                        <font color="#FF0000">カードに記載の名前をご記入下さい。ご本人名義のカードをご使用ください。</font>
                        <br>半角英文字入力（例：TARO YAMADA）
                        <br>
                        <br>
                <!--{if $arrPaymentInfo.use_securitycd == '1'}-->
                    ■セキュリティコード <!--{if $arrPaymentInfo.use_securitycd_option != '1'}--><font color="#FF0000">*</font><!--{/if}--><br>
                        <!--{assign var=key value="SecurityCode"}-->
                    <!--{if $arrErr[$key] != ""}-->
                        <font color="#FF0000"><!--{$arrErr[$key]}--></font><br>
                    <!--{/if}-->
                        <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" istyle="3" size="4" />
                        <br>
                        <font color="#FF0000">※主にカード裏面の署名欄に記載されている末尾３桁～４桁の数字をご記入下さい。</font>
                        <br>半角入力 (例: 123)
                        <br>
                        <br>
                <!--{/if}-->
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
                <!--{if $arrPaymentInfo.enable_customer_regist && $tpl_pg_regist_card_form}-->
                    ■カード情報登録<br>

                        <!--{assign var=key value="register_card"}-->
                    <!--{if $arrErr[$key] != ""}-->
                        <font color="#FF0000"><!--{$arrErr[$key]}--></font><br>
                    <!--{/if}-->
                    <!--{if !$tpl_plg_pg_mulpay_is_subscription}-->
                        <input type="checkbox" name="<!--{$key}-->" value="1" <!--{if $arrForm[$key].value != ""}-->checked<!--{/if}--> >
                        このカードを登録する。
                    <!--{else}-->
                    <input type="hidden" name="<!--{$key}-->" value="1" />
                    <!--{$tpl_plg_pg_mulpay_subscription_name|h}-->では自動でカード登録します。
                    <!--{/if}-->
                        <br>
                        カード情報を登録すると次回より入力無しで購入出来ます。<br />カード情報は当店では保管いたしません。<br />委託する決済代行会社にて安全に保管されます。
                        <br>
                        <br>
                <!--{/if}-->


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

