<!--{*
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */
*}-->
<div id="mypagecolumn">

    <h2 class="title"><!--{$tpl_title|h}--></h2>
    <!--{include file=$tpl_navi}-->
    <div id="mycontents_area">
        <h3>現在登録されているカード情報</h3>
        <form name="form1" id="form1" method="post" action="?">
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

            <table summary="クレジットカード選択">
                <colgroup width="10%"></colgroup>
                <colgroup width="5%"></colgroup>
                <colgroup width="85%"></colgroup>
                <tr>
                    <th class="alignC">選択<span class="attention">※</span></th>
                    <th colspan="2" class="alignC">登録カード番号選択</th>
                </tr>

                <!--{assign var=key1 value="CardSeq"}-->
                <!--{foreach from=$arrData item=data}-->
                <!--{if $data.DeleteFlag != '1'}-->
                <tr>
                    <th class="alignC">
                        <input type="radio" name="CardSeq" value="<!--{$data.CardSeq|h}-->" <!--{if $arrForm[$key1].value==$data.CardSeq}-->checked="checked"<!--{/if}--> <!--{if $tpl_plg_target_seq==$data.CardSeq}-->checked="checked"<!--{/if}-->/>
                    </th>
                    <td class="alignC">
                        <!--{$data.CardSeq|h}-->
                    </td>
                    <td>
                    カード番号: <!--{$data.CardNo|h}-->&nbsp;&nbsp; 有効期限: <!--{$data.Expire|substr:0:2|h}-->年<!--{$data.Expire|substr:2:2|h}-->月
                    <!--{if $data.HolderName != ''}-->&nbsp;&nbsp;カード名義：<!--{$data.HolderName}--><!--{/if}-->
                    </td>
                </tr>
                <!--{/if}-->
                <!--{/foreach}-->
            </table>
        <!--{/if}-->
        <div class="btn_area">
            <ul>
                <li>
                    <input type="submit" value="選択した情報を削除" />
                </li>
            </ul>
        </div>
        </form>

        <h3>カード情報を新規登録</h3>
        <form name="form2" id="form2" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="regist" />
        <div class="information">
            <p>下記項目にご入力ください。「<span class="attention">※</span>」印は入力必須項目です。<br />
                入力後、一番下の「登録する」ボタンをクリックしてください。</p>
            <!--{assign var=key value="error2"}-->
            <p class="attention"><!--{$arrErr[$key]}--></p>
        </div>

            <table summary="クレジットカード番号入力">
                <colgroup width="20%"></colgroup>
                <colgroup width="80%"></colgroup>
                <tr>
                    <th colspan="2" class="alignC"><!--{$tpl_title|h}-->番号入力</th>
                </tr>
                <tr>
                    <th class="alignR">
                        カード番号<span class="attention">※</span>
                    </th>
                    <td>
                    <!--{assign var=key1 value="CardNo"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="16" class="box120" />
                    </td>
                </tr>
                <tr>
                    <th class="alignR">
                        カード有効期限<span class="attention">※</span>
                    </th>
                    <td>
                    <!--{assign var=key1 value="Expire_month"}-->
                    <!--{assign var=key2 value="Expire_year"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                    <select name="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
                    <option value="">&minus;&minus;</option>
                    <!--{html_options options=$arrMonth selected=$arrForm[$key1].value}-->
                    </select>月
                    &nbsp;/&nbsp;
                    20<select name="<!--{$key2}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->">
                    <option value="">&minus;&minus;</option>
                    <!--{html_options options=$arrYear selected=$arrForm[$key2].value}-->
                    </select>年
                    </td>
                </tr>
                <tr>
                    <th class="alignR">
                        カード名義<span class="attention">※</span>
                    </th>
                    <td>
                        <!--{assign var=key1 value="card_name1"}-->
                        <!--{assign var=key2 value="card_name2"}-->
                        <span class="attention"><!--{$arrErr[$key1]}--></span>
                        <span class="attention"><!--{$arrErr[$key2]}--></span>
                        名:<input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->" size="20" class="box120" />
                        &nbsp;
                        姓:<input type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2].value|h}-->" maxlength="<!--{$arrForm[$key2].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key2]|sfGetErrorColor}-->" size="20" class="box120" />
                        <p class="mini"><span class="attention">カードに記載の名前をご記入下さい。ご本人名義のカードをご使用ください。</span>半角英文字入力（例：TARO YAMADA）</p>
                    </td>
                </tr>
            </table>

        <div class="btn_area">
            <ul>
                <li>
                    <input type="submit" value="カード情報を登録" />
                </li>
            </ul>
        </div>
        </form>
    </div>
</div>
