<!--{*
/*
 * Copyright(c) 2013 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Update: 2013/04/04
 */
*}-->
<!--{strip}-->
<br>
■現在登録されているカード情報<br>
<form name="form1" id="form1" method="post" action="./change_card.php">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="delete" />
<!--{if !$arrData}-->
登録されているカード情報はありません。<br>
<!--{else}-->
    <!--{if $arrErr}-->
        <font color="#FF0000">
            <!--{assign var=key value="CardSeq"}-->
                <!--{$arrErr[$key]}-->
            <!--{assign var=key value="error"}-->
                <!--{$arrErr[$key]}-->
            <br>
        </font>
    <!--{/if}-->
    <!--{if $tpl_is_success}-->
        <font color="#FF0000">
                正常に更新されました。<br>
        </font>
    <!--{/if}-->
    <br>
                <!--{assign var=key1 value="CardSeq"}-->
                <!--{foreach from=$arrData item=data}-->
                <!--{if $data.DeleteFlag != '1'}-->
                選択:
                    <input type="radio" name="CardSeq" id="CardSeq" value="<!--{$data.CardSeq|h}-->" <!--{if $arrForm[$key1].value==$data.CardSeq}-->checked="checked"<!--{/if}--> <!--{if $tpl_plg_target_seq==$data.CardSeq}-->checked="checked"<!--{/if}--> />
<br>

                    カード番号:<br><!--{$data.CardNo|h}--><br>
                    有効期限: <!--{$data.Expire|substr:0:2|h}-->年<!--{$data.Expire|substr:2:2|h}-->月<br>
                    <!--{if $data.HolderName != ''}-->カード名義：<!--{$data.HolderName}--><!--{/if}--><br>
                    <br>
                <!--{/if}-->
                <!--{/foreach}-->
    <br>
    <input type="submit" value="選択した情報を削除">
<!--{/if}-->
</form>
<hr>
■カード情報を新規登録<br>
<form name="form2" id="form2" method="post" action="./change_card.php">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="regist" />
下記項目にご入力ください。全ての項目が入力必須です。<br>
入力後、一番下の「登録する」ボタンをクリックしてください。<br>
<!--{assign var=key value="error2"}-->
<font color="#FF0000"><!--{$arrErr[$key]}--></font>
<br>
カード番号：<br>
                    <!--{assign var=key1 value="CardNo"}-->
                    <font color="#FF0000"><!--{$arrErr[$key1]}--></font>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="16"  />
<br>
カード有効期限：<br>
                    <!--{assign var=key1 value="Expire_month"}-->
                    <!--{assign var=key2 value="Expire_year"}-->
                    <font color="#FF0000"><!--{$arrErr[$key1]}--></font>
                    <font color="#FF0000"><!--{$arrErr[$key2]}--></font>
                    <select name="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
                    <option value="">--</option>
                    <!--{html_options options=$arrMonth selected=$arrForm[$key1].value}-->
                    </select>月
                    &nbsp;/&nbsp;
                    20<select name="<!--{$key2}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->">
                    <option value="">-</option>
                    <!--{html_options options=$arrYear selected=$arrForm[$key2].value}-->
                    </select>年
<br>
                    <!--{assign var=key1 value="card_name1"}-->
                    <!--{assign var=key2 value="card_name2"}-->
                    <font color="#FF0000"><!--{$arrErr[$key1]}--></font>
                    <font color="#FF0000"><!--{$arrErr[$key2]}--></font>
                    カード名義 名:<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" />
                    <br>
                    カード名義 姓:<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" />
                    <br>
                    <font color="#FF0000">カードに記載の名前をご記入下さい。ご本人名義のカードをご使用ください。</font>半角英文字入力（例：TARO YAMADA）<br>
<br>
<input type="submit" value="カード情報を登録"/>
</form>
<!--{/strip}-->
