<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

// {{{ requires
require_once(MDL_PG_MULPAY_PAGE_HELPEREX_PATH . 'LC_PageHelper_Mdl_PG_MULPAY_Base_Ex.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Collect_Ex.php');

/**
 * 決済モジュール 決済画面ヘルパー：代引き決済
 */
class LC_PageHelper_Mdl_PG_MULPAY_Collect extends LC_PageHelper_Mdl_PG_MULPAY_Base_Ex {

    /**
     * コンストラクタ
     *
     * @return void
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param array $arrSetting モジュール設定情報
     * @param array $arrOrder 受注情報
     * @return void
     */
    function initParam(&$objFormParam, &$arrPaymentInfo, &$arrOrder) {
    }

    /**
     * 入力内容のチェックを行なう.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return array 入力チェック結果の配列
     */
    function checkError(&$objFormParam) {
    }


    /**
     * 画面モード毎のアクションを行う
     *
     * @param text $mode Mode値
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param array $arrOrder 受注情報
     * @param LC_Page $objPage 呼出元ページオブジェクト
     * @return void
     */
    function modeAction($mode, &$objFormParam, &$arrOrder, &$objPage) {
        $objPurchase = new SC_Helper_Purchase_Ex();
        // 決済実行
        $objClient = new SC_Mdl_PG_MULPAY_Client_Collect_Ex();
        $result = $objClient->doPaymentRequest($arrOrder, $objFormParam->getHashArray(), $objPage->arrPaymentInfo);

        $order_status = ORDER_NEW;
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
        $objQuery->commit();
        $objPurchase->sendOrderMail($arrOrder['order_id']);
        SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
        $objPage->actionExit();
    }

    /**
     * 画面に設定するテンプレート名を返す
     *
     * @return text テンプレートファイル名
     */
    function getFormBloc() {
        return;
    }

}
