<!--{*
/*
 * Copyright(c) 2013 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Update: 2013/04/04
 */
*}-->
<section id="mypagecolumn">

    <h2 class="title"><!--{$tpl_title|h}--></h2>
    <!--{include file=$tpl_navi}-->
    
    <h3 class="title_mypage">現在登録されているカード情報</h3>

        <form name="form1" id="form1" method="post" action="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/mypage/change_card.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="delete" />

        <!--{if !$arrData}-->
            <p>登録されているカード情報はありません。</p>
        <!--{else}-->
            <!--{if $arrErr}-->
            <div class="information">
            <!--{assign var=key value="CardSeq"}-->
                <p class="attention"><!--{$arrErr[$key]}--></p>
            <!--{assign var=key value="error"}-->
                <p class="attention"><!--{$arrErr[$key]}--></p>
            </div>
            <!--{/if}-->
            <!--{if $tpl_is_success}-->
            <div class="information">
                <p class="attention">正常に更新されました。</p>
            </div>
            <!--{/if}-->
            
            <dl class="form_entry">
                <dt>選択<span class="attention">※</span></dt>
                <dd>
                    登録カード番号選択
                </dd>
                <!--{assign var=key1 value="CardSeq"}-->
                <!--{foreach from=$arrData item=data}-->
                <!--{if $data.DeleteFlag != '1'}-->
                <dt>
                    <input type="radio" name="CardSeq" id="CardSeq" value="<!--{$data.CardSeq|h}-->" <!--{if $arrForm[$key1].value==$data.CardSeq}-->checked="checked"<!--{/if}--> <!--{if $tpl_plg_target_seq==$data.CardSeq}-->checked="checked"<!--{/if}--> class="data-role-none" />
                        <label for="CardSeq"><!--{$data.CardSeq|h}--></label>
                </dt>
                <dd>
                    カード番号: <!--{$data.CardNo|h}-->&nbsp;&nbsp; 有効期限: <!--{$data.Expire|substr:0:2|h}-->年<!--{$data.Expire|substr:2:2|h}-->月
                    <!--{if $data.HolderName != ''}-->&nbsp;&nbsp;カード名義：<!--{$data.HolderName}--><!--{/if}-->
                </dd>
                <!--{/if}-->
                <!--{/foreach}-->
            </dl>

            <div class="btn_area">
                <p><input type="submit" class="btn data-role-none" value="選択した情報を削除" /></p>
            </div>
        <!--{/if}-->
        </form>

        <h3 class="title_mypage">カード情報を新規登録</h3>

        <form name="form2" id="form2" method="post" action="<!--{$smarty.const.HTTPS_URL|sfTrimURL}-->/mypage/change_card.php">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="regist" />
            <div class="information">
                <p>下記項目にご入力ください。「<span class="attention">※</span>」印は入力必須項目です。<br />
                    入力後、一番下の「登録する」ボタンをクリックしてください。</p>
                <!--{assign var=key value="error2"}-->
                <p class="attention"><!--{$arrErr[$key]}--></p>
            </div>

            <dl class="form_entry">
                <dt>カード番号<span class="attention">※</span></dt>
                <dd>
                    <!--{assign var=key1 value="CardNo"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="16" class="box120" />
                </dd>
                <dt>カード有効期限<span class="attention">※</span></dt>
                <dd>
                    <!--{assign var=key1 value="Expire_month"}-->
                    <!--{assign var=key2 value="Expire_year"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                    <select name="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" class="boxShort data-role-none">
                    <option value="">&minus;&minus;</option>
                    <!--{html_options options=$arrMonth selected=$arrForm[$key1].value}-->
                    </select>月
                    &nbsp;/&nbsp;
                    20<select name="<!--{$key2}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" class="boxShort data-role-none">
                    <option value="">&minus;&minus;</option>
                    <!--{html_options options=$arrYear selected=$arrForm[$key2].value}-->
                    </select>年
                </dd>
                <dt>カード名義<span class="attention">※</span></dt>
                <dd>
                    <!--{assign var=key1 value="card_name1"}-->
                    <!--{assign var=key2 value="card_name2"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                    名:<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="box120" />
                    &nbsp;
                    姓:<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="box120" />
                    <p class="mini"><span class="attention">カードに記載の名前をご記入下さい。ご本人名義のカードをご使用ください。</span>半角英文字入力（例：TARO YAMADA）</p>
                </dd>
            </dl>

            <div class="btn_area">
                <p>
                    <input type="submit" value="カード情報を登録" class="btn data-role-none" />
                </p>
            </div>
        </form>
</section>

