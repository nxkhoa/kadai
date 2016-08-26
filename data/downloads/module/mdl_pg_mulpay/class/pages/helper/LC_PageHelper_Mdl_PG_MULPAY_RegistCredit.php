<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

// {{{ requires
require_once(MDL_PG_MULPAY_PAGE_HELPEREX_PATH . 'LC_PageHelper_Mdl_PG_MULPAY_Base_Ex.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_RegistCredit_Ex.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Member_Ex.php');

/**
 * 決済モジュール 決済画面ヘルパー：登録クレジット決済
 */
class LC_PageHelper_Mdl_PG_MULPAY_RegistCredit extends LC_PageHelper_Mdl_PG_MULPAY_Base_Ex {

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
        $objFormParam->addParam("お支払いカード登録番号", "CardSeq", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK", "EXIST_CHECK"));
        $objFormParam->addParam("お支払い方法", "Method", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"), "");
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
        $arrPayMethod = SC_Util_PG_MULPAY_Ex::getCreditPayMethod();
        $objPage->arrPayMethod = array();
        foreach ($objPage->arrPaymentInfo['credit_pay_methods'] as $pay_method) {
            if(!SC_Utils_Ex::isBlank($arrPayMethod[$pay_method])) {
                $objPage->arrPayMethod[$pay_method] = $arrPayMethod[$pay_method];
            }
        }
        $objPurchase = new SC_Helper_Purchase_Ex();

        switch($mode) {
        case 'next':
            // 2click 処理
            $objSess = new SC_Session_Ex();
            if(SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE
                    && $objSess->GetSession('plg_pg2click_payment_confirm')
                    && !SC_Utils_Ex::isBlank($objSess->GetSession('plg_pg2click_payment_card'))) {
                $objSess->SetSession('plg_pg2click_payment_confirm', false);
                $arrCardData = $objSess->GetSession('plg_pg2click_payment_card');
                $arrParam = array('CardSeq' => $arrCardData['CardSeq'],
                                  'Method' => '1-0',
                                );
                $objFormParam->setParam($arrParam);
            }
            // 2click 処理ここまで
            $objPage->arrErr = $this->checkError($objFormParam);
            if (SC_Utils_Ex::isBlank($objPage->arrErr)) {
                // 決済実行
                $objClient = new SC_Mdl_PG_MULPAY_Client_RegistCredit_Ex();
                $result = $objClient->doPaymentRequest($arrOrder, $objFormParam->getHashArray(), $objPage->arrPaymentInfo);

                if ($result) {
                    $arrResults = $objClient->getResults();
                    if ($arrResults['ACS'] == '1') {
                        $objPage->arrTdData = $arrResults;
                        $objPage->tpl_url = $arrResults['ACSUrl'];
                        $objPage->tpl_is_td_tran = true;
                        $objPage->tpl_is_loding = true;
                        $objPage->tpl_btn_next = true;
                        $objPage->tpl_payment_onload = "send = false; fnModeSubmit('3dtran','','');";
                        $term_url = substr_replace(SHOPPING_MODULE_URLPATH, '', 0, strlen(ROOT_URLPATH));
                        $objPage->arrTdData['TermUrl'] =  SC_Utils_Ex::sfRmDupSlash(HTTPS_URL . $term_url . '?mode=SecureTran&order_id=' . $arrOrder['order_id']);
                        $objFormParam = new SC_FormParam_Ex();
                    } else {
                        $order_status = ORDER_NEW;
                        $objQuery =& SC_Query_Ex::getSingletonInstance();
                        $objQuery->begin();
                        $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
                        $objQuery->commit();
                        $objPurchase->sendOrderMail($arrOrder['order_id']);
                        $arrParam = SC_Util_PG_MULPAY_Ex::getOrderPayData($arrOrder['order_id']);
                        $this->lfRegistCard($arrOrder, $arrParam);
                        SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                        $objPage->actionExit();
                    }
                } else {
                    $arrErr = $objClient->getError();
                    $objPage->arrErr['payment'] = '※ 決済でエラーが発生しました。<br />' . implode('<br />', $arrErr);
                }
            }
//            break;
        case 'load':
            $arrCustomer = SC_Helper_Customer_Ex::sfGetCustomerData($arrOrder['customer_id']);
            $objClientMember = new SC_Mdl_PG_MULPAY_Client_Member_Ex();
            $ret = $objClientMember->searchCard($arrCustomer);
            if(!$ret) {
                $objPage->arrErr['payment'] = '※ 登録カードが見つかりませんでした。';
            }else {
                $objPage->arrData = $objClientMember->arrResults;
                $objPage->tpl_plg_target_seq = null;
                foreach($objPage->arrData as $arrData) {
                    if ($arrData['DefaultFlag'] == '1') {
                        $objPage->tpl_plg_target_seq = $arrData['CardSeq'];
                    }
                }
                if ($objPage->tpl_plg_target_seq === null) {
                    $objPage->tpl_plg_target_seq = $objPage->arrData[0]['CardSeq'];
                }

                $objSess = new SC_Session_Ex();
                // 2クリック決済用処理
                if ($objSess->GetSession('plg_pg2click_payment_confirm')) {
                    $objSess->SetSession('plg_pg2click_payment_confirm', false);
                    $objPage->tpl_payment_onload = "send = false; fnModeSubmit('next','','');";
                    $objPage->tpl_is_loding = true;
                }else {
                    $objPage->tpl_is_loading = false;
                }
            }
        break;
        case 'SecureTran':
            $objClient = new SC_Mdl_PG_MULPAY_Client_Credit_Ex();
            $result = $objClient->doSecureTran($arrOrder, $_REQUEST, $objPage->arrPaymentInfo);
            if ($result) {
                $order_status = ORDER_NEW;
                $objQuery =& SC_Query_Ex::getSingletonInstance();
                $objQuery->begin();
                $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
                $objQuery->commit();
                $objPurchase->sendOrderMail($arrOrder['order_id']);
                $arrParam = SC_Util_PG_MULPAY_Ex::getOrderPayData($arrOrder['order_id']);
                $this->lfRegistCard($arrOrder, $arrParam);
                SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                $objPage->actionExit();
            } else {
                $arrErr = $objClient->getError();
                if (!SC_Utils_Ex::isBlank($arrErr)) {
                    $objPage->arrErr['payment'] = '※ 決済でエラーが発生しました。<br />' . implode('<br />', $arrErr);
                }
            }
        break;
        case 'return':
            $objPurchase->rollbackOrder($_SESSION['order_id'], ORDER_CANCEL, true);
            SC_Response_Ex::sendRedirect(SHOPPING_CONFIRM_URLPATH);
            SC_Response_Ex::actionExit();
        break;
        default:
            $objPage->tpl_payment_onload = 'fnAutoLoadSubmit();';
            $objPage->tpl_is_loding = true;
        break;
        }
    }

    function lfRegistCard($arrOrder, $arrParam = array()) {
        $objClient = new SC_Mdl_PG_MULPAY_Client_Util_Ex();
        $arrCustomer = SC_Helper_Customer_Ex::sfGetCustomerData($arrOrder['customer_id']);

        $objClientMember = new SC_Mdl_PG_MULPAY_Client_Member_Ex();
        $ret = $objClientMember->searchCard($arrCustomer);

        $order_seq_no = $arrParam['CardSeq'];

        if (!$ret) {
            // 取得出来ない場合はスルー
            return;
        }
        foreach ($objClientMember->arrResults as $arrData) {
            if ($arrData['DefaultFlag'] == '1' && $arrData['CardSeq'] == $order_seq_no) {
                // 変更が無い場合はスルー
                return;
            } else if ($arrData['CardSeq'] == $order_seq_no) {
                // 変更がある場合は、情報を一度確保
                $arrCardData = $arrData;
            }
        }
        // 所有者名を複製
        $arrParam['HolderName'] = $arrCardData['HolderName'];
        
        // 既存データを削除
        $objClientMember->deleteCard($arrCustomer, $arrCardData);

        // 注文した新しいデータでカードを保存
        $objClient->saveOrderCard($arrOrder, $arrParam);
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
        $bloc_id =  $arrBlocId['pg_mulpay_regist_credit'][ $device_type_id ];
        if ($bloc_id) {
            $objLayout = new SC_Helper_PageLayout_Ex();
            $arrBloc = $objLayout->getBlocs($device_type_id, 'bloc_id = ?', array($bloc_id), true);
            return $arrBloc[0]['tpl_path'];
        }
        return;
    }

}
