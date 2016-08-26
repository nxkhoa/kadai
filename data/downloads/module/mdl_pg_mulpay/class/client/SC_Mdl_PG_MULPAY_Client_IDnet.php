<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Base_Ex.php');

/**
 * 決済モジュール 決済処理: iD
 */
class SC_Mdl_PG_MULPAY_Client_IDnet extends SC_Mdl_PG_MULPAY_Client_Base_Ex {

    /**
     * コンストラクタ
     *
     * @return void
     */
    function __construct() {
    }

    function getIdStartPageUrl() {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'NetidStart.idPass';
        return $server_url;
    }

    function doPaymentRequest($arrOrder, $arrParam, $arrPaymentInfo) {

        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'EntryTranNetid.idPass';

        $arrSendKey = array(
            'ShopID',
            'ShopPass',
            'OrderID',
            'JobCd',
            'Amount',
            'RetURL',
        );

        $arrParam['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_ENTRY_REQUEST;
        $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_UNSETTLED;
        $arrParam['success_pay_status'] = '';
        $arrParam['fail_pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;

        $ret = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
        if (!$ret) {
            return $ret;
        }

        $server_url = $arrMdlSetting['server_url'] . 'ExecTranNetid.idPass';
        $arrSendKey = array(
            'ShopID',
            'ShopPass',
            'AccessID',
            'AccessPass',
            'OrderID',
            'CustomerName',
            'PaymentTermDay',
            'MailAddress',
            'ShopMailAddress',
            'ItemName',
            'ClientField1',
            'ClientField2',
            'ClientField3',
            'ClientFieldFlag'
        );

        $arrParam['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_EXEC_REQUEST;
        $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_UNSETTLED;
        $arrParam['success_pay_status'] = MDL_PG_MULPAY_PAY_STATUS_REQUEST_SUCCESS;
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
