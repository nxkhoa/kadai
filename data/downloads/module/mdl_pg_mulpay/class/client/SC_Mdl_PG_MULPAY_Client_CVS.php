<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Base_Ex.php');

/**
 * 決済モジュール 決済処理: コンビニ決済
 */
class SC_Mdl_PG_MULPAY_Client_CVS extends SC_Mdl_PG_MULPAY_Client_Base_Ex {

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

        $server_url = $arrMdlSetting['server_url'] . 'EntryTranCvs.idPass';

        $arrSendKey = array(
            'ShopID',
            'ShopPass',
            'OrderID',
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

        $server_url = $arrMdlSetting['server_url'] . 'ExecTranCvs.idPass';
        $arrSendKey = array(
            'AccessID',
            'AccessPass',
            'OrderID',
            'Convenience',
            'PayTimes',
            'CustomerName',
            'CustomerKana',
            'TelNo',
            'PaymentTermDay',
            'MailAddress',
            'ShopMailAddress',
            'ReserveNo',
            'MemberNo',
            'RegisterDisp1',
            'RegisterDisp2',
            'RegisterDisp3',
            'RegisterDisp4',
            'RegisterDisp5',
            'RegisterDisp6',
            'RegisterDisp7',
            'RegisterDisp8',
            'ReceiptsDisp1',
            'ReceiptsDisp2',
            'ReceiptsDisp3',
            'ReceiptsDisp4',
            'ReceiptsDisp5',
            'ReceiptsDisp6',
            'ReceiptsDisp7',
            'ReceiptsDisp8',
            'ReceiptsDisp9',
            'ReceiptsDisp10',
            'ReceiptsDisp11',
            'ReceiptsDisp12',
            'ReceiptsDisp13',
            'ClientField1',
            'ClientField2',
            'ClientField3',
            'ClientFieldFlag'
        );

        $arrParam['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_EXEC_REQUEST;
        $arrParam['pay_status'] = '';
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
