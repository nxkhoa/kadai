<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Base_Ex.php');

/**
 * 決済モジュール 決済処理: au
 */
class SC_Mdl_PG_MULPAY_Client_Au extends SC_Mdl_PG_MULPAY_Client_Base_Ex {

    /**
     * コンストラクタ
     *
     * @return void
     */
    function __construct() {
    }

    function doAuReturn($arrOrder, $arrParam, $arrPaymentInfo) {

        if (SC_Utils_Ex::isBlank($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYDATA])) {
            $msg = 'auかんたん決済遷移エラー:決済データが受注情報に見つかりませんでした.';
            $objMdl->printLog($msg);
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "例外エラー<br />auかんたん決済遷移エラー<br />この手続きは無効となりました。<br />決済データが受注情報に見つかりませんでした。");
            SC_Response_Ex::actionExit();
        } else {
            $arrPayData = unserialize($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYDATA]);
        }

        if (!SC_Utils_Ex::isBlank($arrParam['ErrCode'])) {
            $this->setError($this->createErrCode($arrParam));
        }

        if ($arrParam['Status'] == 'PAYFAIL') {
            $msg = 'auかんたん決済決済に失敗しました。';
            $this->setError($msg);
        }

        if (SC_Utils_Ex::isBlank($arrParam['PayInfoNo'])) {
            $msg = 'auかんたん決済決済がキャンセルされました。';
            $this->setError($msg);
        }

        $this->setResults($arrParam);

        $arrParam = $this->getResults();

        $arrParam['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_RECV_NOTICE;

        // if (SC_Utils_Ex::isBlank($this->getError())) {
        //     if (!SC_Utils_Ex::isBlank($arrPaymentInfo['JobCd'])) {
        //         $arrParam['pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrPaymentInfo['JobCd']);
        //     } else {
        //         $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_AUTH;
        //     }
        // }else {
        //     $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;
        // }

        
        if (!SC_Utils_Ex::isBlank($this->getError())) {
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;
        }


        SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, $arrParam);

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        // 成功時のみ表示用データの構築
        $this->setOrderPaymentViewData($arrOrder, $arrParam, $arrPaymentInfo);

        return true;
    }



    function doPaymentRequest($arrOrder, $arrParam, $arrPaymentInfo) {

        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'EntryTranAu.idPass';

        $arrSendKey = array(
            'ShopID',
            'ShopPass',
            'OrderID',
            'JobCd',
            'Amount',
        );

        $arrParam['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_ENTRY_REQUEST;
        $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_UNSETTLED;
        $arrParam['success_pay_status'] = '';
        $arrParam['fail_pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;

        $ret = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
        if (!$ret) {
            return $ret;
        }

        $server_url = $arrMdlSetting['server_url'] . 'ExecTranAu.idPass';
        $arrSendKey = array(
            'ShopID',
            'ShopPass',
            'AccessID',
            'AccessPass',
            'OrderID',
            'SiteID',
            'SitePass',
//            'MemberID',
//            'MemberName',
//            'CreateMember',
            'ClientField1',
            'ClientField2',
            'ClientField3',
            'Commodity',
            'RetURL',
            'PaymentTermSec',
            'ServiceName',
            'ServiceTel',
            'ClientFieldFlag'
        );
        if ($arrOrder['customer_id'] > 0) {
            $arrSendKey[] = 'MemberID';
            $arrSendKey[] = 'MemberName';
            $arrSendKey[] = 'CreateMember';
        }

        $arrParam['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_EXEC_REQUEST;
        $arrParam['pay_status'] = '';
        $arrParam['success_pay_status'] = '';
        // if (!SC_Utils_Ex::isBlank($arrPaymentInfo['JobCd'])) {
        //     $arrParam['success_pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrPaymentInfo['JobCd']);
        // } else {
        //     $arrParam['success_pay_status'] = MDL_PG_MULPAY_PAY_STATUS_AUTH;
        // }
        $arrParam['fail_pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;

        $ret = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
        return $ret;
    }

    function getTargetPoint() {
        return '';
    }

    function getSendParam($arrData) {

    }

}
