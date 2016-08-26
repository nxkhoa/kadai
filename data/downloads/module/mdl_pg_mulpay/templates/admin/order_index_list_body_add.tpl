<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->
            <!--{assign var=plg_col_payid value=$smarty.const.MDL_PG_MULPAY_ORDER_COL_PAYID}-->
            <!--{assign var=plg_col_paystatus value=$smarty.const.MDL_PG_MULPAY_ORDER_COL_PAYSTATUS}-->

            <td class="center">

            <!--{if $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_CREDIT || $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_REGIST_CREDIT || $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_IDNET || $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_AU || $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_DOCOMO || $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_SB}-->

                <!--{if $arrResults[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_AUTH}-->
                <input type="checkbox" name="plg_pg_mulpay_commit_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="plg_pg_mulpay_commit_order_id_<!--{$arrResults[cnt].order_id}-->"/><label for="plg_pg_mulpay_commit_order_id_<!--{$arrResults[cnt].order_id}-->">一括売上</label><br>
                <a href="./" onclick="eccube.setModeAndSubmit('plg_pg_mulpay_commit', 'order_id', <!--{$arrResults[cnt].order_id}-->); return false;"><span class="icon_class">個別売上</span></a>
                <!--{else}-->
                    &minus;
                <!--{/if}-->
            <!--{else}-->
                    &minus;
            <!--{/if}-->

            </td>

            <td class="center">

            <!--{if $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_CREDIT || $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_REGIST_CREDIT || $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_IDNET || $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_PAYPAL || $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_AU || $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_DOCOMO || $arrResults[cnt][$plg_col_payid] == MDL_PG_MULPAY_PAYID_SB}-->

                <!--{if $arrResults[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_AUTH || $arrResults[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_AUTH || $arrResults[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_COMMIT || $arrResults[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_SALES || $arrResults[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_CAPTURE}-->
                <input type="checkbox" name="plg_pg_mulpay_cancel_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="plg_pg_mulpay_cancel_id_<!--{$arrResults[cnt].order_id}-->"/><label for="plg_pg_mulpay_cancel_<!--{$arrResults[cnt].order_id}-->">一括取消</label><br>
                <a href="./" onclick="eccube.setModeAndSubmit('plg_pg_mulpay_cancel', 'order_id', <!--{$arrResults[cnt].order_id}-->); return false;"><span class="icon_class">個別取消</span></a>
                <!--{else}-->
                    &minus;
                <!--{/if}-->
            <!--{else}-->
                    &minus;
            <!--{/if}-->
            </td>

