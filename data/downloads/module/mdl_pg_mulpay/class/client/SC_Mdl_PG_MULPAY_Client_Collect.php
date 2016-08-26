<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Base_Ex.php');

/**
 * 決済モジュール 決済処理: 代引き決済
 */
class SC_Mdl_PG_MULPAY_Client_Collect extends SC_Mdl_PG_MULPAY_Client_Base_Ex {

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

        $arrParam['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_ENTRY_SUCCESS;
        $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_REQUEST_SUCCESS;

        SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, $arrParam);
        return true;
    }

    function getTargetPoint() {
        return '';
    }

    function getSendParam($arrData) {

    }

}
