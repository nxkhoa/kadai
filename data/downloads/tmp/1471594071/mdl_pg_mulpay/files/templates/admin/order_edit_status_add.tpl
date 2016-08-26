<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->
<!--{if $plg_pg_mulpay_payid}-->
<script type="text/javascript">
<!--
    function fnPlgPgMulpayConfirm(mode, anchor, anchor_name) {
        if(window.confirm('決済操作を行います。\n受注データを編集していない場合は先に保存して下さい。\nよろしいですか？')) {
            fnModeSubmit(mode, anchor, anchor_name);
        }
    }

//-->
</script>

    <h2><!--{$smarty.const.MDL_PG_MULPAY_SERVICE_NAME}-->決済情報</h2>
    <table class="form" id="plg_pg_mulpay_form">
        <tr>
            <th>決済種別</th>
            <td><!--{$plg_pg_mulpay_pay_name|h}-->(<!--{$plg_pg_mulpay_payid}-->)</td>
        </tr>
        <tr>
            <th>取引状態</th>
            <td>
                <!--{if $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_UNSETTLED}-->
                未決済
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_REQUEST_SUCCESS}-->
                決済要求成功
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_PAY_SUCCESS}-->
                支払い完了
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_EXPIRE}-->
                期限切れ
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_FAIL}-->
                決済失敗
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_CANCEL}-->
                キャンセル
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_AUTH}-->
                仮売上済み
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_COMMIT}-->
                実売上済み
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_SALES}-->
                実売上済み
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_CAPTURE}-->
                即時売上済み
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_VOID}-->
                取消済み
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_RETURN}-->
                返品済み
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_RETURNX}-->
                月跨ぎ返品済み
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_SAUTH}-->
                簡易オーソリ済み
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_CHECK}-->
                有効性チェック済み
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_EXCEPT}-->
                例外エラー
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_REGISTER}-->
                継続課金登録
                <!--{elseif $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_CERT_DONE}-->
                決済認可完了
                <!--{else}-->
                不明な状態
                <!--{/if}-->
            </td>
        </tr>
<!--{if $smarty.const.MDL_PG_MULPAY_DEBUG}-->
        <tr>
            <th>最終操作状態<br />(MDL_PG_MULPAY_DEBUG表示)</th>
            <td>
                <!--{if $plg_pg_mulpay_action_status == MDL_PG_MULPAY_ACTION_STATUS_UNSETTLED}-->
                操作記録無し
                <!--{elseif $plg_pg_mulpay_action_status == MDL_PG_MULPAY_ACTION_STATUS_ENTRY_REQUEST}-->
                取引登録要求
                <!--{elseif $plg_pg_mulpay_action_status == MDL_PG_MULPAY_ACTION_STATUS_ENTRY_SUCCESS}-->
                取引登録要求成功
                <!--{elseif $plg_pg_mulpay_action_status == MDL_PG_MULPAY_ACTION_STATUS_EXEC_REQUEST}-->
                決済要求
                <!--{elseif $plg_pg_mulpay_action_status == MDL_PG_MULPAY_ACTION_STATUS_EXEC_SUCCESS}-->
                決済要求成功
                <!--{elseif $plg_pg_mulpay_action_status == MDL_PG_MULPAY_ACTION_STATUS_EXEC_FAIL}-->
                決済要求失敗
                <!--{elseif $plg_pg_mulpay_action_status == MDL_PG_MULPAY_ACTION_STATUS_WAIT_NOTICE}-->
                通知待ち
                <!--{elseif $plg_pg_mulpay_action_status == MDL_PG_MULPAY_ACTION_STATUS_RECV_NOTICE}-->
                通知受信済み
                <!--{elseif $plg_pg_mulpay_action_status == MDL_PG_MULPAY_ACTION_STATUS_EXPIRE}-->
                期限切れ
                <!--{elseif $plg_pg_mulpay_action_status == MDL_PG_MULPAY_ACTION_STATUS_CANCEL}-->
                キャンセル
                <!--{else}-->
                不明な操作
                <!--{/if}-->
            </td>
        </tr>
<!--{/if}-->
        <!--{if $plg_pg_mulpay_error}-->
        <tr>
            <th>決済操作エラー</th>
            <td class="attention"><!--{$plg_pg_mulpay_error|h}--></td>
        </tr>
        <!--{/if}-->
        <!--{if $arrPaymentData.OrderID != ""}-->
        <tr>
            <th>決済オーダーID</th>
            <td><!--{$arrPaymentData.OrderID|h}--></td>
        </tr>
        <!--{/if}-->
        <!--{if $arrPaymentData.ErrInfo != ""}-->
        <tr>
            <th>最終エラーコード</th>
            <td><!--{$arrPaymentData.ErrInfo|h}--></td>
        </tr>
        <!--{/if}-->
        <!--{if $arrPaymentData.error_msg != ""}-->
        <tr>
            <th>最終エラーメッセージ</th>
            <td><!--{$arrPaymentData.error_msg|h}--></td>
        </tr>
        <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_CREDIT || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_REGIST_CREDIT}-->
        <!--{if $plg_pg_mulpay_pay_status != MDL_PG_MULPAY_PAY_STATUS_UNSETTLED}-->
        <tr>
            <th>承認番号</th>
            <td><!--{$arrPaymentData.Approve|h}--></td>
        </tr>
        <tr>
            <th>支払い方法</th>
            <td>
            <!--{if $arrPaymentData.Method == '1'}-->一括払い<!--{/if}-->
            <!--{if $arrPaymentData.Method == '2'}-->分割<!--{$arrPaymentData.PayTimes|h}-->回払い<!--{/if}-->
            <!--{if $arrPaymentData.Method == '3'}-->ボーナス一括<!--{/if}-->
            <!--{if $arrPaymentData.Method == '4'}-->ボーナス分割<!--{/if}-->
            <!--{if $arrPaymentData.Method == '5'}-->リボ払い<!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>仕向け先</th>
            <td><!--{$arrPaymentData.Forward|h}--></td>
        </tr>
        <tr>
            <th>トランザクションID</th>
            <td><!--{$arrPaymentData.TranID|h}--></td>
        </tr>
        <tr>
            <th>与信日時</th>
            <td><!--{$arrPaymentData.TranDate|h}--></td>
        </tr>
        <!--{/if}-->
    <!--{/if}-->
    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_CVS}-->
        <tr>
            <th>支払い先コンビニ</th>
            <td><!--{$arrConvenience[$arrPaymentData.Convenience]|h}--></td>
        </tr>
        <tr>
            <th>確認番号</th>
            <td><!--{$arrPaymentData.ConfNo|h}--></td>
        </tr>
        <tr>
            <th>受付番号</th>
            <td><!--{$arrPaymentData.ReceiptNo|h}--></td>
        </tr>
        <tr>
            <th>払込期限</th>
            <td><!--{$arrPaymentData.PaymentTerm|h}--></td>
        </tr>
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_PAYEASY || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_ATM}-->
        <tr>
            <th>お客様番号</th>
            <td><!--{$arrPaymentData.CustID|h}--></td>
        </tr>
        <tr>
            <th>収納機関番号</th>
            <td><!--{$arrPaymentData.BkCode|h}--></td>
        </tr>
        <tr>
            <th>確認番号</th>
            <td><!--{$arrPaymentData.ConfNo|h}--></td>
        </tr>
        <tr>
            <th>払込期限</th>
            <td><!--{$arrPaymentData.PaymentTerm|h}--></td>
        </tr>
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_MOBILEEDY}-->
        <tr>
            <th>受付番号</th>
            <td><!--{$arrPaymentData.ReceiptNo|h}--></td>
        </tr>
        <tr>
            <th>Edy注文番号</th>
            <td><!--{$arrPaymentData.EdyOrderNo|h}--></td>
        </tr>
        <tr>
            <th>払込期限</th>
            <td><!--{$arrPaymentData.PaymentTerm|h}--></td>
        </tr>
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_MOBILESUICA}-->
        <tr>
            <th>Suica注文番号</th>
            <td><!--{$arrPaymentData.SuicaOrderNo|h}--></td>
        </tr>
        <tr>
            <th>受付番号</th>
            <td><!--{$arrPaymentData.ReceiptNo|h}--></td>
        </tr>
        <tr>
            <th>支払期限</th>
            <td><!--{$arrPaymentData.PaymentTerm|h}--></td>
        </tr>
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_PAYPAL}-->
        <tr>
            <th>PayPal状態</th>
            <td>
            <!--{if $arrPaymentData.Status == 'CAPTURE'}-->即時売上
            <!--{elseif $arrPaymentData.Status == 'PAYFAIL'}-->決済失敗
            <!--{elseif $arrPaymentData.Status == 'CANCEL'}-->キャンセル
            <!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>トランザクションID</th>
            <td><!--{$arrPaymentData.TranID|h}--></td>
        </tr>
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_IDNET}-->
        <tr>
            <th>支払期限</th>
            <td><!--{$arrPaymentData.PaymentTerm|h}--></td>
        </tr>
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_WEBMONEY}-->
        <tr>
            <th>WebMoney状態</th>
            <td>
            <!--{if $arrPaymentData.Status == 'PAYSUCCESS'}-->決済完了
            <!--{elseif $arrPaymentData.Status == 'PAYFAIL'}-->決決済失敗
            <!--{elseif $arrPaymentData.Status == 'REQSUCCESS'}-->要求成功
            <!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>支払期限</th>
            <td><!--{$arrPaymentData.PaymentTerm|h}--></td>
        </tr>
        <tr>
            <th>管理番号</th>
            <td><!--{$arrPaymentData.WebMoneyManagementNo|h}--></td>
        </tr>
        <tr>
            <th>決済コード</th>
            <td><!--{$arrPaymentData.WebMoneySettleCode|h}--></td>
        </tr>
        <tr>
            <th>キャンセル状態</th>
            <td><!--{if $arrPaymentData.PayCancel =='1'}-->支払い操作キャンセル
                <!--{else}-->その他(正常)<!--{/if}-->
            </td>
        </tr>
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_AU || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_AUCONTINUANCE}-->
        <tr>
            <th>au取引状態</th>
            <td>
            <!--{if $arrPaymentData.Status == 'PAYSUCCESS'}-->要求登録
            <!--{elseif $arrPaymentData.Status == 'AUTHPROCESS'}-->認証中
            <!--{elseif $arrPaymentData.Status == 'AUTH'}-->仮売上
            <!--{elseif $arrPaymentData.Status == 'CANCEL'}-->キャンセル
            <!--{elseif $arrPaymentData.Status == 'CAPTURE'}-->即時売上
            <!--{elseif $arrPaymentData.Status == 'REGISTER'}-->継続課金登録
            <!--{elseif $arrPaymentData.Status == 'PAYFAIL'}-->決済失敗
            <!--{/if}-->
            </td>
        </tr>
        <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_AU}-->
        <tr>
            <th>決済情報番号</th>
            <td><!--{$arrPaymentData.AuPayInfoNo|h}--></td>
        </tr>
        <!--{else}-->
        <tr>
            <th>継続課金ID</th>
            <td><!--{$arrPaymentData.AuContinuAccountId|h}--></td>
        </tr>
        <!--{/if}-->
        <tr>
            <th>支払い方法</th>
            <td>
            <!--{if $arrPaymentData.AuPayMethod == '01'}-->合算
            <!--{elseif $arrPaymentData.AuPayMethod == '02'}-->クレジットカード
            <!--{elseif $arrPaymentData.AuPayMethod == '03'}-->WebMoney
            <!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>利用金額</th>
            <td>
            <!--{$arrPaymentData.Amount|number_format|h}-->円
            </td>
        </tr>
        <!--{if $arrPaymentData.AuCancelAmount != "" && $arrPaymentData.AuCancelAmount != "0"}-->
        <tr>
            <th>キャンセル</th>
            <td>
            <!--{$arrPaymentData.AuCancelAmount|number_format|h}-->円
            </td>
        </tr>
        <!--{/if}-->
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_SB}-->
        <tr>
            <th>ソフトバンク取引状態</th>
            <td>
            <!--{if $arrPaymentData.Status == 'AUTH'}-->仮売上
            <!--{elseif $arrPaymentData.Status == 'SALES'}-->売上確定
            <!--{elseif $arrPaymentData.Status == 'CAPTURE'}-->即時売上
            <!--{elseif $arrPaymentData.Status == 'CANCEL'}-->キャンセル
            <!--{elseif $arrPaymentData.Status == 'PAYFAIL'}-->決済失敗
            <!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>ソフトバンク処理トラッキングID</th>
            <td><!--{$arrPaymentData.SbTrackingId|h}--></td>
        </tr>
        <!--{if $arrPaymentData.SbCancelAmount != "" && $arrPaymentData.SbCancelAmount != "0"}-->
        <tr>
            <th>キャンセル金額</th>
            <td>
            <!--{$arrPaymentData.SbCancelAmount|number_format|h}-->円
            </td>
        </tr>
        <!--{/if}-->
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_DOCOMO || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_DOCOMOCONTINUANCE}-->
        <tr>
            <th>取引状態</th>
            <td>
            <!--{if $arrPaymentData.Status == 'PAYSUCCESS'}-->要求登録
            <!--{elseif $arrPaymentData.Status == 'REQSUCCESS'}-->要求成功
            <!--{elseif $arrPaymentData.Status == 'AUTH'}-->仮売上
            <!--{elseif $arrPaymentData.Status == 'CANCEL'}-->キャンセル
            <!--{elseif $arrPaymentData.Status == 'CAPTURE'}-->即時売上
            <!--{elseif $arrPaymentData.Status == 'PAYFAIL'}-->決済失敗
            <!--{elseif $arrPaymentData.Status == 'REGISTER'}-->継続課金登録中
            <!--{elseif $arrPaymentData.Status == 'RUN-END'}-->継続課金終了
            <!--{else}-->その他
            <!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>ドコモ決済番号</th>
            <td>
            <!--{$arrPaymentData.DocomoSettlementCode|h}-->
            </td>
        </tr>
    <!--{/if}-->


    <!--{if $arrPaymentData.Amount != ""}-->
        <tr>
            <th>決済金額</th>
            <td>
            <!--{$arrPaymentData.Amount|number_format|h}-->円
            <!--{if $arrPaymentData.Amount != $arrForm.payment_total.value}-->
                &nbsp;<span class="attention">
                ※決済金額とお支払い合計に差異があります。
                </span>
            <!--{/if}-->
            </td>
        </tr>
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_CREDIT || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_REGIST_CREDIT}-->
        <tr>
            <th>決済操作</th>
            <td>
            <!--{if $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_AUTH}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_commit','','');">売上確定(実売上)実行</a>&nbsp;
            <!--{/if}-->
            <!--{if $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_AUTH || $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_COMMIT || $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_SALES || $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_CAPTURE}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_cancel','','');">取消(返品)実行</a>&nbsp;
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_change','','');">決済金額変更</a>&nbsp;
            <!--{/if}-->
            <!--{if $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_VOID || $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_RETURN || $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_RETURNX}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_reauth','','');">再オーソリ実行</a>&nbsp;
            <!--{/if}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_get_status','','');">決済状態確認・反映</a>&nbsp;
            </td>
        </tr>
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_IDNET}-->
        <tr>
            <th>決済操作</th>
            <td>
            <!--{if $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_AUTH}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_commit','','');">売上確定(実売上)実行</a>&nbsp;
            <!--{/if}-->
            <!--{if $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_AUTH || $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_COMMIT || $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_SALES || $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_CAPTURE}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_cancel','','');">取消(返品)実行</a>&nbsp;
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_change','','');">決済金額変更</a>&nbsp;
            <!--{/if}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_get_status','','');">決済状態確認・反映</a>&nbsp;
            </td>
        </tr>
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_AU || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_DOCOMO || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_SB}-->
        <tr>
            <th>決済操作</th>
            <td>
            <!--{if $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_AUTH}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_commit','','');">売上確定(実売上)実行</a>&nbsp;
            <!--{/if}-->
            <!--{if $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_AUTH || $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_COMMIT || $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_SALES || $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_CAPTURE}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_cancel','','');">取消(返品)実行</a>&nbsp;
            <!--{/if}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_get_status','','');">決済状態確認・反映</a>&nbsp;
            </td>
        </tr>
    <!--{/if}-->
    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_AUCONTINUANCE || $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_DOCOMOCONTINUANCE}-->
        <tr>
            <th>決済操作</th>
            <td>
            <!--{if $plg_pg_mulpay_pay_status == MDL_PG_MULPAY_PAY_STATUS_REGISTER || $arrPaymentData.Status == 'REGISTER'}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_cancel_continuance','','');">継続課金解約実行</a>&nbsp;
            <!--{/if}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_get_status','','');">決済状態確認・反映</a>&nbsp;
            </td>
        </tr>
    <!--{/if}-->

    <!--{if $plg_pg_mulpay_payid == MDL_PG_MULPAY_PAYID_PAYPAL}-->
        <tr>
            <th>決済操作</th>
            <td>
            <!--{if $arrPaymentData.Status == 'CAPTURE'}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_cancel','','');">取消(返品)実行</a>&nbsp;
            <!--{/if}-->
                <a class="btn-normal" href="javascript:;" onclick="fnPlgPgMulpayConfirm('plg_pg_mulpay_get_status','','');">決済状態確認・反映</a>&nbsp;
            </td>
        </tr>
    <!--{/if}-->


    <!--{if $arrPaymentData.payment_log != "" && is_array($arrPaymentData.payment_log) && count($arrPaymentData.payment_log) > 0}-->
        <tr>
            <th>決済ログ</th>
            <td>
            <a href="javascript:void();" onclick="$('#plg_pg_mulpay_log').slideToggle();">決済ログ表示・非表示</a>
            <br />
            <table id="plg_pg_mulpay_log" style="display:none;" class="list">
                <tr>
                    <th>時間</th>
                    <th>内容</th>
                </tr>
            <!--{foreach from=$arrPaymentData.payment_log item=data key=key}-->
                <!--{foreach from=$data item=sdata key=skey}-->
                <tr>
                    <td>
                    <!--{$skey|h}-->
                    </td>
                    <td>
                    <!--{foreach from=$sdata item=val key=vkey}-->
                       <!--{if $val != ""}-->
                       <!--{$vkey|h}-->=
                         <!--{if is_array($val)}-->
                            <!--{$val|var_dump|h}-->
                         <!--{else}-->
                            <!--{$val|h}-->
                         <!--{/if}-->,
                       <!--{/if}-->
                    <!--{/foreach}-->
                    </td>
                </tr>
                <!--{/foreach}-->
            <!--{/foreach}-->
            </table>
            </td>
        </tr>
    <!--{/if}-->

    </table>


    <h2>受注詳細</h2>
<!--{/if}-->

