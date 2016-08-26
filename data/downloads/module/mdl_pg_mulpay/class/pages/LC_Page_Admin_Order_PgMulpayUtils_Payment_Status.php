<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

// {{{ requires
require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(CLASS_EX_REALDIR . "page_extends/admin/LC_Page_Admin_Ex.php");
require_once(MDL_PG_MULPAY_CLASSEX_PATH . "util_extends/SC_Util_PG_MULPAY_Ex.php");

/**
 *
 */
class LC_Page_Admin_Order_PgMulpayUtils_Payment_Status extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = MDL_PG_MULPAY_TEMPLATE_PATH . 'admin/order_payment_status.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'status';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = '決済状況管理';

        $masterData = new SC_DB_MasterData_Ex();
        $this->arrORDERSTATUS = $masterData->getMasterData('mtb_order_status');
        $this->arrORDERSTATUS_COLOR = $masterData->getMasterData('mtb_order_status_color');


        //$this->arrPaymentType = SC_Util_PG_MULPAY_Ex::getPaymentTypeNames();

        $arrData = SC_Util_PG_MULPAY_Ex::getMulpayPayments();
        foreach ($arrData as $payment) {
            $this->arrPaymentType[ $payment[MDL_PG_MULPAY_PAYMENT_COL_PAYID] ] = $payment['payment_method'];
        }

        $this->arrPaymentStatus = SC_Util_PG_MULPAY_Ex::getPaymentStatus();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {

        $objDb = new SC_Helper_DB_Ex();

        // パラメーター管理クラス
        $objFormParam = new SC_FormParam_Ex();
        // パラメーター情報の初期化
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        // 入力値の変換
        $objFormParam->convParam();

        $this->arrForm = $objFormParam->getHashArray();

        //支払方法の取得
        $this->arrPayment = $objDb->sfGetIDValueList('dtb_payment', 'payment_id', 'payment_method');
        switch ($this->getMode()) {
            case 'update':
                switch ($objFormParam->getValue('change_status')) {
                    case '':
                        break;
                        // 削除
                    case 'delete':
                        $this->lfDelete($objFormParam->getValue('move'));
                        break;
                        // 更新
                    default:
                        $this->lfStatusMove($objFormParam->getValue('change_status'), $objFormParam->getValue('move'));
                        break;
                }

                // 対応状況
                $pay_id = !is_null($_POST['pay_id']) ? $objFormParam->getValue('pay_id') : '';
                $pay_status = !is_null($_POST['pay_status']) ? $objFormParam->getValue('pay_status') : '';
                break;

            case 'plg_pg_mulpay_change_status':
                $arrErr = $objFormParam->checkError();
                if (SC_Utils_Ex::isBlank($arrErr)) {
                    $arrOrderId = $objFormParam->getValue('move');

                    if (isset($arrOrderId) && is_array($arrOrderId)) {
                        foreach ($arrOrderId as $order_id) {
                            if (!SC_Utils_Ex::sfIsInt($order_id)) {
                                continue;
                            }
                            $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($order_id);
                            $objClient = new SC_Mdl_PG_MULPAY_Client_Util_Ex();

                            if ($_POST['plg_pg_mulpay_change_status'] == 'commit') {
                                $ret = $objClient->commitOrder($arrOrder);
                            } else if ($_POST['plg_pg_mulpay_change_status'] == 'cancel') {
                                $ret = $objClient->cancelOrder($arrOrder);
                            } else if ($_POST['plg_pg_mulpay_change_status'] == 'reauth') {
                                $ret = $objClient->reauthOrder($arrOrder);
                            }

                            if (!$ret) {
                                $arrErr = $objClient->getError();
                                if (!SC_Utils_Ex::isBlank($objPage->plg_pg_mulpay_msg)) {
                                    $this->plg_pg_mulpay_msg .= '<br />';
                                }
                                $this->plg_pg_mulpay_msg .= '注文番号:' .$order_id . 'の決済で下記が発生しました。<br />';
                                if (SC_Utils_Ex::isBlank($arrErr)) {
                                    $this->plg_pg_mulpay_msg .= '対象の変更は出来ない決済です。';
                                } else {
                                    $this->plg_pg_mulpay_msg .= implode('<br />', $arrErr);
                                }
                            }
                        }
                        sleep(2);
                        if (SC_Utils_Ex::isBlank($this->plg_pg_mulpay_msg)) {
                            $this->plg_pg_mulpay_onload = "alert('決済状況変更を実行しました。');" ;
                        } else {
                            $this->plg_pg_mulpay_onload = "alert('決済状況変更を実行しましたがエラーがありました。メッセージを確認して下さい。');" ;
                        }
                    }
                }
            case 'search':
                // 対応状況
                $pay_id = !is_null($_POST['pay_id']) ? $objFormParam->getValue('pay_id') : '';
                $pay_status = !is_null($_POST['pay_status']) ? $objFormParam->getValue('pay_status') : '';
                break;

            default:
                // 対応状況
                $pay_id = '';
                $pay_status = '';
                break;
        }

        // 対応状況
        $this->SelectedPayId = $pay_id;
        $this->SelectedPayStatus = $pay_status;
        //検索結果の表示
        $this->lfDisp($pay_id, $pay_status, $objFormParam->getValue('search_pageno'));

    }

    /**
     *  パラメーター情報の初期化
     *  @param SC_FormParam
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam('注文番号', 'order_id', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('選択決済種類', 'pay_id', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('選択決済状況', 'pay_status', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('変更後対応状況', 'change_status', STEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));

        $objFormParam->addParam('ページ番号', 'search_pageno', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('選択注文番号', 'move', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
    }

    /**
     *  入力内容のチェック
     *  @param SC_FormParam
     */
    function lfCheckError(&$objFormParam) {
        // 入力データを渡す。
        $arrRet = $objFormParam->getHashArray();
        $arrErr = $objFormParam->checkError();
        if (is_null($objFormParam->getValue('search_pageno'))) {
            $objFormParam->setValue('search_pageno', 1);
        }

    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    // 対応状況一覧の表示
    function lfDisp($pay_id, $pay_status, $pageno) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $select ='*';
        $from = 'dtb_order';
        $where = 'del_flg = 0 AND ' . MDL_PG_MULPAY_ORDER_COL_PAYID . ' IS NOT NULL';
        $arrWhereVal = array();

        if (!SC_Utils_Ex::isBlank($pay_id)) {
            $where .= " AND " . MDL_PG_MULPAY_ORDER_COL_PAYID . " = ?";
            $arrWhereVal[] = $pay_id;
        }
        if (!SC_Utils_Ex::isBlank($pay_status)) {
            $where .= " AND " . MDL_PG_MULPAY_ORDER_COL_PAYSTATUS . " = ?";
            $arrWhereVal[] = $pay_status;
        }
        $order = 'order_id DESC';

        $linemax = $objQuery->count($from, $where, $arrWhereVal);
        $this->tpl_linemax = $linemax;

        // ページ送りの処理
        $page_max = ORDER_STATUS_MAX;

        // ページ送りの取得
        $objNavi = new SC_PageNavi_Ex($pageno, $linemax, $page_max, 'fnNaviSearchOnlyPage', NAVI_PMAX);
        $this->tpl_strnavi = $objNavi->strnavi;      // 表示文字列
        $startno = $objNavi->start_row;

        $this->tpl_pageno = $pageno;

        // 取得範囲の指定(開始行番号、行数のセット)
        $objQuery->setLimitOffset($page_max, $startno);

        //表示順序
        $objQuery->setOrder($order);

        //検索結果の取得
        $this->arrResults = $objQuery->select($select, $from, $where, $arrWhereVal);
    }

    /**
     * 対応状況の更新
     */
    function lfStatusMove($statusId, $arrOrderId) {
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        if (!isset($arrOrderId) || !is_array($arrOrderId)) {
            return false;
        }
        $masterData = new SC_DB_MasterData_Ex();
        $arrORDERSTATUS = $masterData->getMasterData('mtb_order_status');

        $objQuery->begin();

        foreach ($arrOrderId as $orderId) {
            $objPurchase->sfUpdateOrderStatus($orderId, $statusId);
        }

        $objQuery->commit();

        $this->tpl_onload = "window.alert('選択項目を" . $arrORDERSTATUS[$statusId] . "へ移動しました。');";
        return true;
    }

    /**
     * 受注テーブルの論理削除
     */
    function lfDelete($arrOrderId) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        if (!isset($arrOrderId) || !is_array($arrOrderId)) {
            return false;
        }

        $arrUpdate = array(
            'del_flg'      => 1,
            'update_date'  => 'CURRENT_TIMESTAMP',
        );

        $objQuery->begin();

        foreach ($arrOrderId as $orderId) {
            $objQuery->update('dtb_order', $arrUpdate, 'order_id = ?', array($orderId));
        }

        $objQuery->commit();

        $this->tpl_onload = "window.alert('選択項目を削除しました。');";
        return true;
    }


}
