<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->
            <!--{assign var=plg_col_payid value=$smarty.const.MDL_PG_MULPAY_ORDER_COL_PAYID}-->
            <!--{assign var=plg_col_paystatus value=$smarty.const.MDL_PG_MULPAY_ORDER_COL_PAYSTATUS}-->

            <td class="center">
            <!--{if $arrStatus[cnt][$plg_col_payid] == ""}-->
                &minus;
            <!--{else}-->
                <!--{if $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_UNSETTLED}-->
                未決済
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_REQUEST_SUCCESS}-->
                決済要求成功
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_PAY_SUCCESS}-->
                支払い完了
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_EXPIRE}-->
                期限切れ
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_FAIL}-->
                決済失敗
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_CANCEL}-->
                キャンセル
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_AUTH}-->
                仮売上済み
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_COMMIT}-->
                実売上済み
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_SALES}-->
                実売上済み
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_CAPTURE}-->
                即時売上済み
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_VOID}-->
                取消済み
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_RETURN}-->
                返品済み
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_RETURNX}-->
                月跨ぎ返品済み
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_SAUTH}-->
                簡易オーソリ済み
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_CHECK}-->
                有効性チェック済み
                <!--{elseif $arrStatus[cnt][$plg_col_paystatus] == MDL_PG_MULPAY_PAY_STATUS_EXCEPT}-->
                例外エラー
                <!--{else}-->
                不明な状態
                <!--{/if}-->
            <!--{/if}-->
            </td>
