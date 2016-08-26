<?php
/*
 * Copyright(c) 2012-2013 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Member_Ex.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Util_Ex.php');

/**
 * プラグインの処理クラス
 */
class LC_PgMulpayUtils {

    function actionPrefilterTransform($class_name, &$source, &$objPage, $filename, $objPlugin) {
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE:
            case DEVICE_TYPE_SMARTPHONE:
            case DEVICE_TYPE_PC:
                if (preg_match('/^LC_Page_Mypage.*_Ex$/', $class_name) && strpos($filename, 'navi.tpl') === FALSE) {
                    $objTransform = new SC_Helper_Transform($source);
                    $template_file = 'mypage_navi_add.tpl';
                    switch($objPage->arrPageLayout['device_type_id']){
                        case DEVICE_TYPE_MOBILE:
                            if (strpos($filename, 'index.tpl')) {
                                $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'mobile/';
                                $snip = file_get_contents($template_dir . $template_file);
                                $source = str_replace("退会</a><br>", "退会</a><br>" . $snip, $source);
                                return;
                            }
                            break;
                        case DEVICE_TYPE_SMARTPHONE:
                            $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'sphone/';
                            $objTransform->select('h2.title',NULL, false)->insertAfter(file_get_contents($template_dir . $template_file));
                            break;
                        case DEVICE_TYPE_PC:
                        default:
                            $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'default/';
                            $objTransform->select('h2',NULL, false)->appendChild(file_get_contents($template_dir . $template_file));
                            break;
                    }
                    $source = $objTransform->getHTML();
                }
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                if (strpos($filename, 'mail_templates') !== FALSE) {
                    return;
                }

                if (preg_match('/^LC_Page_Admin.*_Ex$/', $class_name)) {
                    $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'admin/';
                    $objTransform = new SC_Helper_Transform($source);
                    $template_file = 'admin_common_utils_add.tpl';
                    $objTransform->select('head')->appendChild(file_get_contents($template_dir . $template_file));
                    $source = $objTransform->getHTML();
                }

                switch($filename){
                    case 'order/status.tpl':
                        // 対応状況管理画面
                        $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'admin/';
                        $objTransform = new SC_Helper_Transform($source);

                        $template_file = 'order_status_header_add.tpl';
                        $objTransform->select('p.remark')->insertBefore(file_get_contents($template_dir . $template_file));

                        $template_file = 'order_status_list_header_add.tpl';
                        $objTransform->select('table.list > tr > th:last')->appendChild(file_get_contents($template_dir . $template_file));

                        $template_file = 'order_status_list_body_add.tpl';
                        $objTransform->select('table.list > tr', 1)->appendChild(file_get_contents($template_dir . $template_file));

                        $template_file = 'order_status_list_header_col_replace.tpl';
                        $objTransform->select('table.list > col')->removeElement();
                        $objTransform->select('table.list')->appendFirst(file_get_contents($template_dir . $template_file));


                        $source = $objTransform->getHTML();
                        break;
                    case 'order/index.tpl':
                        // 受注一覧画面
                        $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'admin/';
                        $objTransform = new SC_Helper_Transform($source);

                        $template_file = 'order_index_list_btn_add.tpl';
                        $objTransform->select('#form1 > div.btn')->appendChild(file_get_contents($template_dir . $template_file));

                        $template_file = 'order_index_list_header_add.tpl';
                        $objTransform->select('table.list > tr > th:last')->appendChild(file_get_contents($template_dir . $template_file));

                        $template_file = 'order_index_list_body_add.tpl';
                        $objTransform->select('table.list > tr', 1)->appendChild(file_get_contents($template_dir . $template_file));

                        $template_file = 'order_index_list_header_col_replace.tpl';
                        $objTransform->select('table.list > col', 1)->removeElement();
                        $objTransform->select('table.list')->appendFirst(file_get_contents($template_dir . $template_file));


                        $source = $objTransform->getHTML();
                        break;
                    case 'order/edit.tpl':
                        // 受注登録・編集画面
                        $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'admin/';
                        $template_file = 'order_edit_status_add.tpl';
                        $objTransform = new SC_Helper_Transform($source);
                        $objTransform->select('div#order')->appendFirst(file_get_contents($template_dir . $template_file));

                        $template_file = 'order_edit_payment_form_add.tpl';
                        $objTransform->select('div#order')->appendChild(file_get_contents($template_dir . $template_file));

                        $source = $objTransform->getHTML();
                        break;
                    // Fix ticket 52483 - popup in IE start
                    case 'contents/recommend_search.tpl':
                    case 'order/pdf_input.tpl':
                    case 'order/product_select.tpl':
                    case 'order/multiple.tpl':
                        $ua = $_SERVER['HTTP_USER_AGENT'];
                        if (!preg_match('/MSIE/', $ua) || preg_match('/MSIE 7/', $ua)) {
                            $addon = '<?xml version="1.0" encoding="' . CHAR_CODE . '"?>'."\n"
                                    .'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n"
                                    .'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">'."\n"
                                    .'<head>'."\n";
                            $source = $addon.$source;
                        }
                        break;
                    // Fix ticket 52483 - popup in IE end
                    default:
                        break;
                }
                break;
        }

    }

    function actionHook($class_name, $hook_point, &$objPage, $objPlugin) {
        switch ($class_name) {
        case 'LC_Page_Admin_Order_Status_Ex':
            if ($hook_point == 'before') {
                $this->lfDoAdminOrderStatusBefore($objPage);
            }
            break;
        case 'LC_Page_Admin_Order_Ex':
            if ($hook_point == 'after') {
                $this->lfDoAdminOrderAfter($objPage);
            } else if($hook_point == 'before') {
                $this->lfDoAdminOrderBefore($objPage);
            }
            break;
        case 'LC_Page_Admin_Order_Edit_Ex':
            if ($hook_point == 'after') {
                $this->lfDoAdminOrderEditAfter($objPage);
            } else if($hook_point == 'before') {
                $this->lfDoAdminOrderEditBefore($objPage);
            }
            break;
        case 'LC_Page_Admin_Customer_Ex':
            $this->lfDoAdminCustomer($objPage);
            break;
        }
    }

    function lfDoAdminOrderAfter(&$objPage) {
    }

    function lfDoAdminOrderStatusBefore(&$objPage) {
        $mode = $objPage->getMode();
        switch ($mode) {
        case 'plg_pg_mulpay_change_status':
            // パラメーター管理クラス
            $objFormParam = new SC_FormParam_Ex();
            // パラメーター情報の初期化
            $objPage->lfInitParam($objFormParam);
            if (array_search('move', $objFormParam->getKeyList()) === FALSE) {
                $objFormParam->addParam('移動注文番号', 'move', INT_LEN, 'n', array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
            }

            $objFormParam->setParam($_POST);
            // 入力値の変換
            $objFormParam->convParam();
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
                        }

                        if (!$ret) {
                            $arrErr = $objClient->getError();
                            if (!SC_Utils_Ex::isBlank($objPage->plg_pg_mulpay_msg)) {
                                $objPage->plg_pg_mulpay_msg .= '<br />';
                            }
                            $objPage->plg_pg_mulpay_msg .= '注文番号:' .$order_id . 'の決済で下記が発生しました。<br />';
                            if (SC_Utils_Ex::isBlank($arrErr)) {
                                $objPage->plg_pg_mulpay_msg .= '対象の変更は出来ない決済です。';
                            } else {
                                $objPage->plg_pg_mulpay_msg .= implode('<br />', $arrErr);
                            }
                        }
                    }
                    if (SC_Utils_Ex::isBlank($objPage->plg_pg_mulpay_msg)) {
                        $objPage->plg_pg_mulpay_onload = "alert('決済状況変更を実行しました。');" ;
                    } else {
                        $objPage->plg_pg_mulpay_onload = "alert('決済状況変更を実行しましたがエラーがありました。メッセージを確認して下さい。');" ;
                    }
                }
            }
            $_POST['mode'] = 'search';
            $_REQUEST['mode'] = 'search';
            break;
        }
    }


    function lfDoAdminOrderBefore(&$objPage) {
        $mode = $objPage->getMode();
        switch ($mode) {
        case 'plg_pg_mulpay_commit':
        case 'plg_pg_mulpay_cancel':
            if (!SC_Utils_Ex::isBlank($_POST['order_id']) && SC_Utils_Ex::sfIsInt($_POST['order_id'])) {
                $order_id = $_POST['order_id'];
                $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($order_id);
                $objClient = new SC_Mdl_PG_MULPAY_Client_Util_Ex();

                switch ($mode) {
                case 'plg_pg_mulpay_commit':
                    $ret = $objClient->commitOrder($arrOrder);
                    break;
                case 'plg_pg_mulpay_cancel':
                    $ret = $objClient->cancelOrder($arrOrder);
                    break;
                }

                if (!$ret) {
                    $arrErr = $objClient->getError();
                    $objPage->plg_pg_mulpay_error = implode('<br />', $arrErr);
                }
            }
            $_REQUEST['mode'] = 'search';
            $_POST['mode'] = 'search';
            break;
        break;

        case 'plg_pg_mulpay_commit_all':
            if (!SC_Utils_Ex::isBlank($_POST['plg_pg_mulpay_commit_order_id'])) {
                foreach($_POST['plg_pg_mulpay_commit_order_id'] as $order_id) {
                    if (SC_Utils_Ex::sfIsInt($order_id)) {
                        $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($order_id);
                        $objClient = new SC_Mdl_PG_MULPAY_Client_Util_Ex();

                        $ret = $objClient->commitOrder($arrOrder);

                        if (!$ret) {
                            $arrErr = $objClient->getError();
                            if (!SC_Utils_Ex::isBlank($objPage->plg_pg_mulpay_error)) {
                                $objPage->plg_pg_mulpay_error .= '<br />';
                            }
                            $objPage->plg_pg_mulpay_error .= '注文番号:' .$order_id . 'の決済で下記が発生しました。<br />';
                            $objPage->plg_pg_mulpay_error .= implode('<br />', $arrErr);
                        }
                    }
                }
                sleep(2);
            }
            $_POST['mode'] = 'search';
            $_REQUEST['mode'] = 'search';
            break;
        case 'plg_pg_mulpay_cancel_all':
            if (!SC_Utils_Ex::isBlank($_POST['plg_pg_mulpay_cancel_order_id'])) {
                foreach($_POST['plg_pg_mulpay_cancel_order_id'] as $order_id) {
                    if (SC_Utils_Ex::sfIsInt($order_id)) {
                        $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($order_id);
                        $objClient = new SC_Mdl_PG_MULPAY_Client_Util_Ex();

                        $ret = $objClient->cancelOrder($arrOrder);

                        if (!$ret) {
                            $arrErr = $objClient->getError();
                            if (!SC_Utils_Ex::isBlank($objPage->plg_pg_mulpay_error)) {
                                $objPage->plg_pg_mulpay_error .= '<br />';
                            }
                            $objPage->plg_pg_mulpay_error .= implode('<br />', $arrErr);
                        }
                    }
                }
                sleep(2);
            }
            $_POST['mode'] = 'search';
            $_REQUEST['mode'] = 'search';
            break;
        }
        if (!SC_Utils_Ex::isBlank($objPage->plg_pg_mulpay_error)) {
            $objPage->tpl_onload .= "window.alert('決済処理でエラーが生じました。エラー内容を確認して下さい。');";
        }
    }

    function lfDoAdminCustomer(&$objPage) {
        // 本体側のチェックに任せています。
        if ($objPage->getMode() == 'delete' && SC_Utils_Ex::isBlank($objPage->arrErr)) {
            $customer_id = $_REQUEST['edit_customer_id'];
            $arrCustomer = SC_Helper_Customer_Ex::sfGetCustomerDataFromId($customer_id, 'del_flg = 1');
            $objClient = new SC_Mdl_PG_MULPAY_Client_Member_Ex();
            $ret = $objClient->deleteCard($arrCustomer);
        }
    }

    function lfDoAdminOrderEditAfter(&$objPage) {
        // データ読み込み
        if (!SC_Utils_Ex::isBlank($objPage->arrForm['order_id']['value'])) {
            $order_id = $objPage->arrForm['order_id']['value'];
            $objPage->arrPaymentData = SC_Util_PG_MULPAY_Ex::getOrderPayData($order_id);
            if ($objPage->arrPaymentData[MDL_PG_MULPAY_ORDER_COL_PAYID]) {
                $objPage->plg_pg_mulpay_payid = $objPage->arrPaymentData[MDL_PG_MULPAY_ORDER_COL_PAYID];
                $arrPayNames = SC_Util_PG_MULPAY_Ex::getPaymentTypeNames();
                $objPage->plg_pg_mulpay_pay_name = $arrPayNames[$objPage->plg_pg_mulpay_payid];
                $objPage->plg_pg_mulpay_pay_status = $objPage->arrPaymentData['pay_status'];
                $objPage->plg_pg_mulpay_action_status = $objPage->arrPaymentData['action_status'];
            } else {
                $arrPgPayments = SC_Util_PG_MULPAY_Ex::getMulpayPayments();
                $arrPayment = $objPage->arrPayment;
                foreach($arrPayment as $key => $payment) {
                    foreach ($arrPgPayments as $pg_payment) {
                        if ($pg_payment['payment_id'] == $key) {
                            unset($objPage->arrPayment[$key]);
                            break;
                        }
                    }
                }                
            }
        } else {
            $arrPgPayments = SC_Util_PG_MULPAY_Ex::getMulpayPayments();
            $arrPayment = $objPage->arrPayment;
            foreach($arrPayment as $key => $payment) {
                foreach ($arrPgPayments as $pg_payment) {
                    if ($pg_payment['payment_id'] == $key) {
                        unset($objPage->arrPayment[$key]);
                        break;
                    }
                }
            }
        }

        $objPage->arrConvenience = SC_Util_PG_MULPAY_Ex::getConveni();
        if (!SC_Utils_Ex::isBlank($objPage->arrForm['payment_id']['value'])) {
            $payment_id = $objPage->arrForm['payment_id']['value'];
            $arrPaymentInfo = SC_Util_PG_MULPAY_Ex::getPaymentTypeConfig($payment_id);
            $objPage->arrPaymentInfo = $arrPaymentInfo;
        } else {
            foreach ($objPage->arrPayment as $payment_id => $name) {
                $arrPayConfig = SC_Util_PG_MULPAY_Ex::getPaymentTypeConfig($payment_id);
                if (!SC_Utils_Ex::isBlank($arrPayConfig[MDL_PG_MULPAY_PAYMENT_COL_PAYID])) {
                    unset($objPage->arrPayment[$payment_id]);
                }
            }
        }
    }

    function lfDoAdminOrderEditBefore(&$objPage) {
        $mode = $objPage->getMode();
        switch ($mode) {
        case 'plg_pg_mulpay_cancel_continuance':
        case 'plg_pg_mulpay_commit':
        case 'plg_pg_mulpay_change':
        case 'plg_pg_mulpay_cancel':
        case 'plg_pg_mulpay_reauth':
        case 'plg_pg_mulpay_get_status':
            if (!SC_Utils_Ex::isBlank($_POST['order_id']) && SC_Utils_Ex::sfIsInt($_POST['order_id'])) {
                $order_id = $_POST['order_id'];
                $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($order_id);
                $objClient = new SC_Mdl_PG_MULPAY_Client_Util_Ex();

                switch ($mode) {
                case 'plg_pg_mulpay_commit':
                    $ret = $objClient->commitOrder($arrOrder);
                    break;
                case 'plg_pg_mulpay_change':
                    $ret = $objClient->changeOrder($arrOrder);
                    break;
                case 'plg_pg_mulpay_cancel':
                    $ret = $objClient->cancelOrder($arrOrder);
                    break;
                case 'plg_pg_mulpay_cancel_continuance':
                    $ret = $objClient->cancelContinuance($arrOrder);
                    break;
                case 'plg_pg_mulpay_reauth':
                    $ret = $objClient->reauthOrder($arrOrder);
                    break;
                case 'plg_pg_mulpay_get_status':
                    $ret = $objClient->getOrderInfo($arrOrder);
                    break;
                }

                if (!$ret) {
                    $arrErr = $objClient->getError();
                    $objPage->plg_pg_mulpay_error = implode('<br />', $arrErr);
                }
            }
            $_GET['mode'] = 'recalculate';
            break;
        }
    }

    function lfCheckErrorPaymentInput(&$objFormParam, $pay_id) {
        $arrRet =  $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrRet);
        $objErr->arrErr = $objFormParam->checkError();
        $objErr->doFunc(array('お問合せ先電話番号', 'ReceiptsDisp12_1', 'ReceiptsDisp12_2', 'ReceiptsDisp12_3'), array('TEL_CHECK'));
        $objErr->doFunc(array('表示電話番号', 'ServiceTel_1', 'ServiceTel_2', 'ServiceTel_3'), array('TEL_CHECK'));

        if (!SC_Utils_Ex::isBlank($arrRet['PaymentTermDay']) && $arrRet['PaymentTermDay'] > PAYMENT_TERM_DAY_MAX) {
            $objErr->arrErr['PaymentTermDay'] = '※ 支払い期限は最大' . PAYMENT_TERM_DAY_MAX . '日まで設定可能です。';
        }
        if (!SC_Utils_Ex::isBlank($arrRet['PaymentTermSec']) && $arrRet['PaymentTermSec'] > PAYMENT_TERM_SEC_MAX) {
            $objErr->arrErr['PaymentTermDay'] = '※ 支払い期限は最大' . PAYMENT_TERM_SEC_MAX . '秒まで設定可能です。';
        }

        return $objErr->arrErr;
    }

}
