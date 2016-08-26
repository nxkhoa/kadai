<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Credit_Ex.php');

/**
 * 決済モジュール 決済処理: 登録クレジットカード
 */
class SC_Mdl_PG_MULPAY_Client_RegistCredit extends SC_Mdl_PG_MULPAY_Client_Credit_Ex {

    /**
     * コンストラクタ
     *
     * @return void
     */
    function __construct() {
    }

    function doPaymentRequest($arrOrder, $arrParam, $arrPaymentInfo) {

        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $arrConfig = $objMdl->getSubData();
        if ($arrConfig['subs']['subs_after_payment'] == '1' && $arrOrder[MDL_PG_MULPAY_ORDER_COL_SPFLG] == 'subscription') {
            $arrPaymentInfo['JobCd'] = 'CHECK';
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
            'Method',
        );

        $arrParam['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_ENTRY_REQUEST;
        $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_UNSETTLED;
        $arrParam['success_pay_status'] = '';
        $arrParam['fail_pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;

        $ret = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
        if (!$ret) {
            return $ret;
        }

        $server_url = $arrMdlSetting['server_url'] . 'ExecTran.idPass';
        $arrSendKey = array(
            'AccessID',
            'AccessPass',
            'OrderID',
            'Method',
            'PayTimes',
            'SiteID',
            'SitePass',
            'MemberID',
            'CardSeq',
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
