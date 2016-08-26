<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

// {{{ requires
require_once(MDL_PG_MULPAY_PAGE_HELPEREX_PATH . 'LC_PageHelper_Mdl_PG_MULPAY_Base_Ex.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Au_Ex.php');

/**
 * 決済モジュール 決済画面ヘルパー：auかんたん決済
 */
class LC_PageHelper_Mdl_PG_MULPAY_Au extends LC_PageHelper_Mdl_PG_MULPAY_Base_Ex {

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
        $arrParam = $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrParam);
        $objErr->arrErr = $objFormParam->checkError();
        return $objErr->arrErr;
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

        $objPage->tpl_is_loding = true;
        switch($mode) {
        case 'next':
            $objPage->arrErr = $this->checkError($objFormParam);
            if (SC_Utils_Ex::isBlank($objPage->arrErr)) {
                // 決済実行
                $objClient = new SC_Mdl_PG_MULPAY_Client_Au_Ex();
                $result = $objClient->doPaymentRequest($arrOrder, $objFormParam->getHashArray(), $objPage->arrPaymentInfo);
                if ($result) {
                    $arrResults = $objClient->getResults();

                    $objPage->AccessID = $arrResults['AccessID'];
                    $objPage->Token = $arrResults['Token'];
                    $objPage->tpl_url = $arrResults['StartURL'];

                    $objPage->tpl_is_select_page_call = true;
                    $objPage->tpl_btn_next = true;
                    $objPage->tpl_payment_onload = 'fnAutoLoadSubmit();';
                } else {
                    $arrErr = $objClient->getError();
                    $objPage->arrErr['payment'] = '※ 決済でエラーが発生しました。<br />' . implode('<br />', $arrErr);
                    $objPage->tpl_is_loding = false;
                }
            } else {
                $objPage->tpl_is_loding = false;
            }
        break;
        case 'return':
            $objPurchase->rollbackOrder($_SESSION['order_id'], ORDER_CANCEL, true);
            SC_Response_Ex::sendRedirect(SHOPPING_CONFIRM_URLPATH);
            SC_Response_Ex::actionExit();
        break;
        case 'pgreturn':
            $objClient = new SC_Mdl_PG_MULPAY_Client_Au_Ex();
            $result = $objClient->doAuReturn($arrOrder, $_REQUEST, $objPage->arrPaymentInfo);
            if ($result) {
                $order_status = ORDER_PRE_END;
                $objQuery =& SC_Query_Ex::getSingletonInstance();
                $objQuery->begin();
                $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
                $objQuery->commit();
                SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                $objPage->actionExit();
            } else {
                $arrErr = $objClient->getError();
                $order_status = ORDER_PENDING;
                $objQuery =& SC_Query_Ex::getSingletonInstance();
                $objQuery->begin();
                $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status);
                $objQuery->commit();
                if (!SC_Utils_Ex::isBlank($arrErr)) {
                    $objPage->arrErr['payment'] = '※ 決済でエラーが発生しました。<br />' . implode('<br />', $arrErr);
                }
            }
            $objPage->tpl_is_loding = false;
            break;
        default:
            $objPage->tpl_payment_onload = 'fnAutoLoadSubmit();';
        break;
        }
    }

    /**
     * 画面に設定するテンプレート名を返す
     *
     * @return text テンプレートファイル名
     */
    function getFormBloc() {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrBlocId = $objMdl->getSubData('bloc_setting');
        $device_type_id = SC_Display_Ex::detectDevice();
        $bloc_id =  $arrBlocId['pg_mulpay_au'][ $device_type_id ];
        if ($bloc_id) {
            $objLayout = new SC_Helper_PageLayout_Ex();
            $arrBloc = $objLayout->getBlocs($device_type_id, 'bloc_id = ?', array($bloc_id), true);
            return $arrBloc[0]['tpl_path'];
        }
        return;
    }

}
