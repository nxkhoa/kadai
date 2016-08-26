<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Base_Ex.php');

/**
 * 決済モジュール 決済処理: クレジットカード
 */
class SC_Mdl_PG_MULPAY_Client_Credit extends SC_Mdl_PG_MULPAY_Client_Base_Ex {

    /**
     * コンストラクタ
     *
     * @return void
     */
    function __construct() {
    }

    function doSecureTran($arrOrder, $arrParam, $arrPaymentInfo) {
        $this->setResults($arrParam);
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();

        if (SC_Utils_Ex::isBlank($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYDATA])) {
            $msg = '3Dセキュア認証遷移エラー:決済データが受注情報に見つかりませんでした.';
            $objMdl->printLog($msg);
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "例外エラー<br />3Dセキュア認証遷移エラー<br />この手続きは無効となりました。<br />決済データが受注情報に見つかりませんでした。");
            SC_Response_Ex::actionExit();
        } else {
            $arrPayData = unserialize($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYDATA]);
        }

        if ($arrPayData['MD'] != $arrParam['MD']) {
            $msg = '3Dセキュア認証遷移エラー:取引ID(MD)が一致しませんでした。(' . $arrParam['MD'] . ':' . $arrPayData['MD'] . ')';
            $objMdl->printLog($msg);
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "例外エラー<br />3Dセキュア認証遷移エラー<br />この手続きは無効となりました。<br />取引ID(MD)が一致しませんでした。");
            SC_Response_Ex::actionExit();
        }

        if (SC_Utils_Ex::isBlank($arrParam['PaRes'])) {
            return false;
        }

        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'SecureTran.idPass';

        $arrSendKey = array(
            'PaRes',
            'MD',
        );

        $arrParam['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_RECV_NOTICE;
        if (!SC_Utils_Ex::isBlank($arrPaymentInfo['JobCd'])) {
            $arrParam['success_pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrPaymentInfo['JobCd']);
        } else {
            $arrParam['success_pay_status'] = MDL_PG_MULPAY_PAY_STATUS_AUTH;
        }
        $arrParam['fail_pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;

        $ret = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
        return $ret;
    }

    function doPaymentRequest($arrOrder, $arrParam, $arrPaymentInfo) {

        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $arrConfig = $objMdl->getSubData();
        if ($arrConfig['subs']['subs_after_payment'] == '1' && $arrOrder[MDL_PG_MULPAY_ORDER_COL_SPFLG] == 'subscription') {
            $arrPaymentInfo['JobCd'] = 'CHECK';
        }

        if (!SC_Utils_Ex::isBlank($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYDATA])) {
            $arrPayData = unserialize($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYDATA]);
            if ($arrPayData['AccessID'] && $arrPayData['AccessPass']) {
                $is_pass = true;
                $arrOrder = array_merge($arrOrder, $arrPayData);
            }
        }

        $server_url = $arrMdlSetting['server_url'] . 'EntryTran.idPass';
        $arrSendKey = array(
            'ShopID',
            'ShopPass',
            'OrderID',
            'JobCd',
            'Amount',
            'TdFlag',
            'TdTenantName',
        );

        $arrParam['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_ENTRY_REQUEST;
        $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_UNSETTLED;
        $arrParam['success_pay_status'] = '';
        $arrParam['fail_pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;

        if (!$is_pass) {
            $ret = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
            if (!$ret) {
                return $ret;
            }
        }

        $server_url = $arrMdlSetting['server_url'] . 'ExecTran.idPass';
        $arrSendKey = array(
            'AccessID',
            'AccessPass',
            'OrderID',
            'Method',
            'PayTimes',
            'CardNo',
            'Expire',
            'SecurityCode',
            'ClientField1',
            'ClientField2',
            'ClientField3',
            'ClientFieldFlag'
        );

        if (SC_Display_Ex::detectDevice() !== DEVICE_TYPE_MOBILE
            && $arrPaymentInfo['TdFlag'] == '1') {
            $arrSendKey[] = 'HttpAccept';
            $arrSendKey[] = 'HttpUserAgent';
            $arrSendKey[] = 'DeviceCategory';
        } else {
            $arrPaymentInfo['TdFlag'] == '0';
        }

        $arrParam['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_EXEC_REQUEST;
        $arrParam['pay_status'] = '';
        if (!SC_Utils_Ex::isBlank($arrPaymentInfo['JobCd'])) {
            $arrParam['success_pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrPaymentInfo['JobCd']);
        } else {
            $arrParam['success_pay_status'] = MDL_PG_MULPAY_PAY_STATUS_AUTH;
        }
        $arrParam['fail_pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;

        if ($arrPaymentInfo['TdFlag'] == '1') {
            $arrParam['success_pay_status'] = MDL_PG_MULPAY_PAY_STATUS_UNSETTLED;
        }

        $ret = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
        return $ret;
    }

    function getTargetPoint() {
        return '';
    }

    function getSendParam($arrData) {

    }

}
