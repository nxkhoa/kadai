<!--{*
/**
 * @copyright 2012 GMO Payment Gateway, Inc. All Rights Reserved.
 * @link http://www.gmo-pg.com/
 * Updated: 2012/07/30
 */
*}-->

<form name="form1" id="form1" method="POST" action="?" >
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="pay_id" value="<!--{$arrForm.pay_id|h}-->" />
<input type="hidden" name="pay_status" value="<!--{$arrForm.pay_status|h}-->" />
<input type="hidden" name="search_pageno" value="<!--{$tpl_pageno}-->" >
<input type="hidden" name="order_id" value="" />
<div id="order" class="contents-main">
    <h2>決済種類 抽出条件</h2>
        <div class="btn">
        <!--{foreach key=key item=item from=$arrPaymentType}-->
            <!--{if $key != $SelectedPayId}-->
            <a
                class="btn-normal"
                style="padding-right: 1em; margin-bottom: 1em;"
                    href="javascript:;"
                    onclick="document.form1.search_pageno.value='1'; eccube.setModeAndSubmit('search','pay_id','<!--{$key}-->' ); return false;"
            ><!--{$item}--></a>
            <!--{else}-->
            <a
                class="btn-normal"
                style="padding-right: 1em;"
            ><!--{$item}-->(選択中)</a>
            <!--{/if}-->
        <!--{/foreach}-->
        </div>

    <h2>決済状況 抽出条件</h2>
        <div class="btn">
        <!--{foreach key=key item=item from=$arrPaymentStatus}-->
            <!--{if $key !== $SelectedPayStatus}-->
            <a
                class="btn-normal"
                style="padding-right: 1em; margin-bottom: 1em;"
                    href="javascript:;"
                    onclick="document.form1.search_pageno.value='1'; eccube.setModeAndSubmit('search','pay_status','<!--{$key}-->' ); return false;"
            ><!--{$item}--></a>
            <!--{else}-->
            <a
                class="btn-normal"
                style="padding-right: 1em;"
            ><!--{$item}-->(選択中)</a>
            <!--{/if}-->
        <!--{/foreach}-->
        </div>

    <!--{if $tpl_linemax > 0}-->
    <h2>対応状況変更</h2>
        <div class="btn">
            <select name="change_status">
                <option value="" selected="selected" style="<!--{$Errormes|sfGetErrorColor}-->" >選択してください</option>
                <!--{foreach key=key item=item from=$arrORDERSTATUS}-->
                <!--{if $key ne $SelectedStatus}-->
                <option value="<!--{$key}-->" ><!--{$item}--></option>
                <!--{/if}-->
                <!--{/foreach}-->
                <option value="delete">削除</option>
            </select>
            <a class="btn-normal" href="javascript:;" onclick="fnSelectCheckSubmit(); return false;"><span>移動</span></a>
        </div>
        <span class="attention">※ <!--{$arrORDERSTATUS[$smarty.const.ORDER_CANCEL]}-->もしくは、削除に変更時には、在庫数を手動で戻してください。</span>
<br /><br />

    <h2>決済状況変更</h2>

        <div class="btn">
            <select name="plg_pg_mulpay_change_status">
                <option value="" selected="selected">選択してください</option>
                <option value="commit">一括売上</option>
                <option value="cancel">一括取消</option>
                <option value="reauth">一括再オーソリ</option>
            </select>
            <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpaySelectCheckSubmit(); return false;"><span>実行</span></a>
        </div>

        <!--{if $plg_pg_mulpay_msg != ""}-->
        <span class="attention"><!--{$plg_pg_mulpay_msg}--></span><br /><br />
        <!--{/if}-->

        <p class="remark">
            <!--{$tpl_linemax}-->件が該当しました。
            <!--{$tpl_strnavi}-->
        </p>

        <table class="list center">
            <col width="5%" />
            <col width="10%" />
            <col width="10%" />

            <col width="10%" />
            <col width="7%" />
            <col width="10%" />
            <col width="16%" />
            <col width="10%" />
            <col width="11%" />
            <col width="11%" />
            <tr>
                <th><label for="move_check">選択</label> <input type="checkbox" name="move_check" id="move_check" onclick="eccube.checkAllBox(this, 'input[name=\'move[]\']')" /></th>
                <th>決済状況</th>
                <th>支払方法</th>

                <th>対応状況</th>
                <th>注文番号</th>
                <th>受注日</th>
                <th>お名前</th>
                <th>購入金額（円）</th>
                <th>入金日</th>
                <th>発送日</th>
            </tr>
            <!--{section name=cnt loop=$arrResults}-->
            <!--{assign var=status value="`$arrResults[cnt].status`"}-->
            <!--{assign var=pay_status value="`$arrResults[cnt].memo04`"}-->
            <tr style="background:<!--{$arrORDERSTATUS_COLOR[$status]}-->;">
                <td><input type="checkbox" name="move[]" value="<!--{$arrResults[cnt].order_id}-->" ></td>
                <td>
                <!--{$arrPaymentStatus[$pay_status]|h}-->
                </td>
                <td><!--{$arrResults[cnt].payment_method|h}--></td>

                <td><!--{$arrORDERSTATUS[$status]}--></td>
                <td><a href="#" onclick="fnOpenWindow('./disp.php?order_id=<!--{$arrResults[cnt].order_id}-->','order_disp','800','900'); return false;" ><!--{$arrResults[cnt].order_id}--></a></td>
                <td><!--{$arrResults[cnt].create_date|sfDispDBDate:false}--></td>
                <td><!--{$arrResults[cnt].order_name01|h}--><!--{$arrResults[cnt].order_name02|h}--></td>
                <!--{assign var=payment_id value=`$arrResults[cnt].payment_id`}-->
                <td class="right"><!--{$arrResults[cnt].total|number_format}--></td>
                <td><!--{if $arrResults[cnt].payment_date != ""}--><!--{$arrResults[cnt].payment_date|sfDispDBDate:false}--><!--{else}-->未入金<!--{/if}--></td>
                <td><!--{if $arrResults[cnt].status eq 5}--><!--{$arrResults[cnt].commit_date|sfDispDBDate:false}--><!--{else}-->未発送<!--{/if}--></td>
            </tr>
            <!--{/section}-->
        </table>
        <input type="hidden" name="move[]" value="" />

        <p><!--{$tpl_strnavi}--></p>

    <!--{elseif $arrResults != "" & $tpl_linemax == 0}-->
        <div class="message">
            該当するデータはありません。
        </div>
    <!--{/if}-->

    <!--{* 登録テーブルここまで *}-->
</div>
</form>

<script type="text/javascript">
<!--
function fnPlgPgMulpaySelectCheckSubmit(){

    var selectflag = 0;
    var fm = document.form1;

    if(fm.plg_pg_mulpay_change_status.options[document.form1.plg_pg_mulpay_change_status.selectedIndex].value == ""){
    selectflag = 1;
    }

    if(selectflag == 1){
        alert('セレクトボックスが選択されていません');
        return false;
    }
    var i;
    var checkflag = 0;
    var max = fm["move[]"].length;

    if(max) {
        for (i=0;i<max;i++){
            if(fm["move[]"][i].checked == true){
                checkflag = 1;
            }
        }
    } else {
        if(fm["move[]"].checked == true) {
            checkflag = 1;
        }
    }

    if(checkflag == 0){
        alert('チェックボックスが選択されていません');
        return false;
    }

    if(selectflag == 0 && checkflag == 1){
    document.form1.mode.value = 'plg_pg_mulpay_change_status';
    document.form1.submit();
    }
}

function fnSelectCheckSubmit(){

    var selectflag = 0;
    var fm = document.form1;

    if(fm.change_status.options[document.form1.change_status.selectedIndex].value == ""){
    selectflag = 1;
    }

    if(selectflag == 1){
        alert('セレクトボックスが選択されていません');
        return false;
    }
    var i;
    var checkflag = 0;
    var max = fm["move[]"].length;

    if(max) {
        for (i=0;i<max;i++){
            if(fm["move[]"][i].checked == true){
                checkflag = 1;
            }
        }
    } else {
        if(fm["move[]"].checked == true) {
            checkflag = 1;
        }
    }

    if(checkflag == 0){
        alert('チェックボックスが選択されていません');
        return false;
    }

    if(selectflag == 0 && checkflag == 1){
    document.form1.mode.value = 'update';
    document.form1.submit();
    }
}
//-->
</script>
