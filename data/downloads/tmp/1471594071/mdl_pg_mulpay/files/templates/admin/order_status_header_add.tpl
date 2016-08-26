<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->
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
//-->
</script>
    <br />
    <h2><!--{$smarty.const.MDL_PG_MULPAY_SERVICE_NAME}-->決済状況変更</h2>
    <!--{if $tpl_linemax > 0}-->
        <div class="btn">
            <select name="plg_pg_mulpay_change_status">
                <option value="" selected="selected">選択してください</option>
                <option value="commit">一括売上</option>
                <option value="cancel">一括取消</option>
            </select>
            <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpaySelectCheckSubmit(); return false;"><span>実行</span></a>
        </div>
    <!--{/if}-->

        <!--{if $plg_pg_mulpay_msg != ""}-->
        <span class="attention"><!--{$plg_pg_mulpay_msg}--></span><br /><br />
        <!--{/if}-->
