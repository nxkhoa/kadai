<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Base_Ex.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Member_Ex.php');

/**
 * 決済モジュール 決済処理: 各種取引処理
 */
class SC_Mdl_PG_MULPAY_Client_Util extends SC_Mdl_PG_MULPAY_Client_Base_Ex {

    /**
     * コンストラクタ
     *
     * @return void
     */
    function __construct() {
    }

    function saveOrderCard($arrOrder, $arrParam = array()) {
        $arrCustomer = SC_Helper_Customer_Ex::sfGetCustomerData($arrOrder['customer_id']);
        $objClientMember = new SC_Mdl_PG_MULPAY_Client_Member_Ex();
        $ret = $objClientMember->getMember($arrCustomer);
        if(!$ret) {
            $objClientMember->saveMember($arrCustomer);
        }

        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'TradedCard.idPass';

        $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($arrOrder['order_id']);


        $arrSendKey = array(
            'ShopID',
            'ShopPass',
            'OrderID',
            'SiteID',
            'SitePass',
            'MemberID',
            'DefaultFlag',
            'HolderName',
        );

        $arrParam['DefaultFlag'] = '1'; // 最終登録したカードをデフォルトに

        $arrSendData = $this->getSendData($arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        return true;
    }

    function getOrderInfo($arrOrder) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_REGIST_CREDIT
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT_CHECK
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT_SAUTH) {

            $server_url = $arrMdlSetting['server_url'] . 'SearchTrade.idPass';
            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'OrderID',
            );
        } else {
            $server_url = $arrMdlSetting['server_url'] . 'SearchTradeMulti.idPass';
            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'OrderID',
                'PayType',
            );

        }

        $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($arrOrder['order_id']);

        $arrParam = array();
        if (SC_Utils_Ex::isBlank($arrOrder['OrderID'])) {
            $msg = '決済履歴がありません。';
            $this->setError($msg);
            return false;
        }
        if (array_search('PayType', $arrSendKey) !== FALSE && SC_Utils_Ex::isBlank($arrOrder['PayType'])) {
            $arrOrder['PayType'] = SC_Util_PG_MULPAY_Ex::getPayTypeFromPayId($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID]);
        }

        $arrSendData = $this->getSendData($arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        $arrResults = $this->getResults();
        $arrResults['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_EXEC_SUCCESS;
        if (defined('MDL_PG_MULPAY_PAY_STATUS_' . $arrResults['Status'])) {
            $arrResults['pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrResults['Status']);
        } else if (!SC_Utils_Ex::isBlank($arrResults['Status'])) {
            $arrResults['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_EXCEPT;
        }

        SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, $arrResults);
        return true;
    }

    function changeOrder($arrOrder) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();
        $arrPaymentInfo = SC_Util_PG_MULPAY_Ex::getPaymentTypeConfig($arrOrder['payment_id']);

        $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($arrOrder['order_id']);


        if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_IDNET) {
            $term = '90';

            $server_url = $arrMdlSetting['server_url'] . 'ChangeTranNetid.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'OrderID',
                'Amount',
            );
        } else if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_REGIST_CREDIT
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT_CHECK
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT_SAUTH) {

            $term = '180';

            $server_url = $arrMdlSetting['server_url'] . 'ChangeTran.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'JobCd',
                'Amount',
            );

        } else {
            $msg = '決済金額変更エラー：金額変更処理に対応していない決済です。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }


        $arrParam = array();
        if (SC_Utils_Ex::isBlank($arrOrder['TranDate'])) {
            return false;
        }
        sscanf($arrOrder['TranDate'], '%04d%02d%02d%02d%02d%02d', $year, $month, $day, $hour, $min, $sec);

        if (strtotime('+' . $term . ' days', mktime($hour, $min, $sec, $month, $day, $year)) < time()) {
            $msg = '決済処理エラー：金額変更期限を越えています。(決済日から' . $term . '日以内)';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }

        if (array_search('JobCd', $arrSendKey) !== FALSE) {
            $arrParam['JobCd'] = $arrPaymentInfo['JobCd'];
        }

        SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, array('no_update_status_flg' => '1'));

        $arrSendData = $this->getSendData($arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }

        $arrResults = $this->getResults();

        $arrResults['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_EXEC_SUCCESS;

        if (!SC_Utils_Ex::isBlank($arrPaymentInfo['JobCd'])) {
            $arrResults['pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrPaymentInfo['JobCd']);
        } else {
            $arrResults['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_AUTH;
        }

        $arrResults['JobCd'] = $arrPaymentInfo['JobCd'];
        if (SC_Utils_Ex::isBlank($arrResults['Amount'])) {
            $arrResults['Amount'] = $arrOrder['payment_total'];
        }
        SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, $arrResults);
        return true;
    }

    function commitOrder($arrOrder) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();
        $arrPaymentInfo = SC_Util_PG_MULPAY_Ex::getPaymentTypeConfig($arrOrder['payment_id']);

        $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($arrOrder['order_id']);

        $target_term_days = '90';

        if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_IDNET) {

            $server_url = $arrMdlSetting['server_url'] . 'SalesTranNetid.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'OrderID',
                'Amount',
            );

        } else if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_AU) {

            $server_url = $arrMdlSetting['server_url'] . 'AuSales.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'OrderID',
                'Amount',
            );
        } else if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_DOCOMO) {
            $server_url = $arrMdlSetting['server_url'] . 'DocomoSales.idPass';
            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'OrderID',
                'Amount',
            );
        } else if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_SB) {
            $target_term_days = '60';
            $server_url = $arrMdlSetting['server_url'] . 'SbSales.idPass';
            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'OrderID',
                'Amount',
            );
        } else if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_REGIST_CREDIT) {

            $server_url = $arrMdlSetting['server_url'] . 'AlterTran.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'JobCd',
                'Amount',
            );
        } else {
            $msg = '決済確定エラー：確定処理に対応していない決済です。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }

        $arrParam = array();
        if (SC_Utils_Ex::isBlank($arrOrder['TranDate'])) {
            return false;
        }
        sscanf($arrOrder['TranDate'], '%04d%02d%02d%02d%02d%02d', $year, $month, $day, $hour, $min, $sec);

        if (strtotime('+' . $target_term_days . ' days', mktime($hour, $min, $sec, $month, $day, $year)) > time()) {
            if (array_search('JobCd', $arrSendKey) !== FALSE) {
                $arrParam['JobCd'] = 'SALES';
            }
        } else {
            $msg = '決済確定前エラー：確定期限を越えています。(決済日から' . $target_term_days . '日以内)';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }

        $arrSendData = $this->getSendData($arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }

        $arrResults = $this->getResults();

        $arrResults['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_EXEC_SUCCESS;
        $arrResults['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_COMMIT;

        SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, $arrResults);

        return true;
    }

    function reauthOrder($arrOrder, $arrParam) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'AlterTran.idPass';

        $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($arrOrder['order_id']);
        $arrPaymentInfo = SC_Util_PG_MULPAY_Ex::getPaymentTypeConfig($arrOrder['payment_id']);

        if ($arrOrder['pay_status'] != MDL_PG_MULPAY_PAY_STATUS_VOID && $arrOrder['pay_status'] != MDL_PG_MULPAY_PAY_STATUS_RETURN && $arrOrder['pay_status'] != MDL_PG_MULPAY_PAY_STATUS_RETURNX) {
            $msg = '決済エラー：取り消されていない注文は再オーソリ出来ません。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }

        if (!($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_REGIST_CREDIT
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT_CHECK
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT_SAUTH)) {
            $msg = '決済エラー：再オーソリはクレジットカード決済のみ対応しています。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }

        $arrSendKey = array(
            'ShopID',
            'ShopPass',
            'AccessID',
            'AccessPass',
            'JobCd',
            'Amount',
            'Method',
            'PayTimes'
        );

        $arrParam['JobCd'] = $arrPaymentInfo['JobCd'];
        $arrParam['Method'] = $arrOrder['Method'];
        $arrParam['PayTimes'] = $arrOrder['PayTimes'];
        SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, array('no_update_status_flg' => '1'));

        $arrSendData = $this->getSendData($arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }

        $arrResults = $this->getResults();

        $arrResults['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_EXEC_SUCCESS;
        if (!SC_Utils_Ex::isBlank($arrPaymentInfo['JobCd'])) {
            $arrResults['pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrPaymentInfo['JobCd']);
        } else {
            $arrResults['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_AUTH;
        }

        $arrResults['JobCd'] = $arrPaymentInfo['JobCd'];
        if (SC_Utils_Ex::isBlank($arrResults['Amount'])) {
            $arrResults['Amount'] = $arrOrder['payment_total'];
        }

        SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, $arrResults);

        return true;
    }

    // 継続課金解約
    function cancelContinuance($arrOrder) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($arrOrder['order_id']);
        if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_AUCONTINUANCE) {
            $server_url = $arrMdlSetting['server_url'] . 'AuContinuanceCancel.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'OrderID',
            );
        } elseif ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_DOCOMOCONTINUANCE) {
            $server_url = $arrMdlSetting['server_url'] . 'DocomoContinuanceShopEnd.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'OrderID',
                'Amount',
                'LastMonthFreeFlag',
            );
        } else {
            $msg = '決済キャンセル・解約エラー：解約処理に対応していない決済です。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }

        $arrSendData = $this->getSendData($arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }

        $arrResults = $this->getResults();

        $arrResults['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_EXEC_SUCCESS;
        if (array_search('Status', $arrSendKey) === FALSE && SC_Utils_Ex::isBlank($arrParam['Status'])) {
            $arrParam['Status'] = 'CANCEL';
        }
        $arrResults['pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrParam['Status']);

        SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, $arrResults);
        return true;
    }

    // 返品
    function cancelOrder($arrOrder) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($arrOrder['order_id']);

        if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_IDNET) {
            $term = '180';

            $server_url = $arrMdlSetting['server_url'] . 'CancelTranNetid.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'OrderID',
                'Amount',
            );
        } else if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_PAYPAL) {
            $term = '50';

            $server_url = $arrMdlSetting['server_url'] . 'CancelTranPaypal.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'OrderID',
                'Amount',
            );

        } else if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_AU) {
            $term = '60';
            if ($arrOrder['Status'] == 'AUTH') {            
                $term = '60'; // 90日
            } else if ($arrOrder['Status'] != 'AUTH') {
                sscanf($arrOrder['TranDate'], '%04d%02d%02d%02d%02d%02d', $year, $month, $day, $hour, $min, $sec);
                $target = mktime($hour, $min, $sec, $month, $day, $year);
                if (date('Ym') != date('Ym', $target)) {
                    $limit_time =  mktime(0,0,0, $month + 3, 1, $year); // 翌々月末日 (３ヶ月先の１日未満）
                    if ($limit_time < mktime()) {
                        $msg = '決済キャンセルエラー：auかんたん決済返品期限切れ。返品期限は売上確定月の翌々月末日までです。';
                        $objMdl->printLog($msg);
                        $this->setError($msg);
                        return false;
                    }
                }
            }

            $server_url = $arrMdlSetting['server_url'] . 'AuCancelReturn.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'OrderID',
                'CancelAmount',
            );

            if ($arrOrder['PayMethod'] == '03') {
                $msg = '決済キャンセルエラー：auかんたん決済(WebMoney)は返品処理に対応していません。';
                $objMdl->printLog($msg);
                $this->setError($msg);
                return false;
            }
        } else if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_SB) {
            $term = '60';
            if ($arrOrder['Status'] == 'SALSE') {
                sscanf($arrOrder['TranDate'], '%04d%02d%02d%02d%02d%02d', $year, $month, $day, $hour, $min, $sec);
                if ($day > 0 && $day <= 10) {
                    $target = mktime($hour, $min, $sec, $month, 13, $year);
                } else if ($day > 10 && $day <= 20) {
                    $target = mktime($hour, $min, $sec, $month, 23, $year);
                } else {
                    $target = mktime($hour, $min, $sec, $month + 1, 2, $year);
                }
                if ($target < mktime()) {
                    $msg = '決済キャンセルエラー：ソフトバンクケータイ支払いキャンセル期限切れ。キャンセル期限はサービス仕様をご確認下さい。';
                    $objMdl->printLog($msg);
                    $this->setError($msg);
                    return false;
                }
            }

            $server_url = $arrMdlSetting['server_url'] . 'SbCancel.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'OrderID',
                'CancelAmount',
            );

        } else if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_DOCOMO) {
            $term = '180';

            $server_url = $arrMdlSetting['server_url'] . 'DocomoCancelReturn.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'OrderID',
                'CancelAmount',
            );

            // 決済翌日12時以降出ないと返品は不可
            // 当日の扱いは20:00まで
            if (SC_Utils_Ex::isBlank($arrOrder['TranDate'])) {
                return false;
            }
            sscanf($arrOrder['TranDate'], '%04d%02d%02d%02d%02d%02d', $year, $month, $day, $hour, $min, $sec);
            $target = mktime($hour, $min, $sec, $month, $day, $year);
            if (date('H') >= 12) { // 12時以降？
                $to = strtotime('-1 days', strtotime(date('Y/m/d 20:00:00')));
            } else {
                $to = strtotime('-2 days', strtotime(date('Y/m/d 20:00:00')));
            }
            if ($target > $to) {
                $msg = '決済キャンセルエラー： ドコモケータイ払いキャンセルは翌日の12:00以降から可能です。当日の扱いは20:00までの取引です。';
                $objMdl->printLog($msg);
                $this->setError($msg);
                return false;
            }

            $cancel_limit = mktime(20,0,0,$month + 3, 0, $year);
            if ( mktime() > $cancel_limit) {
                 $msg = '決済キャンセルエラー：ドコモケータイ払い取消期限切れ。ドコモケータイ払いのキャンセルは取引日の翌々月末20:00までです。';
                 $objMdl->printLog($msg);
                 $this->setError($msg);
                 return false;
            }

        } else if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_REGIST_CREDIT
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT_CHECK
            || $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT_SAUTH) {

            $term = '180';

            $server_url = $arrMdlSetting['server_url'] . 'AlterTran.idPass';

            $arrSendKey = array(
                'ShopID',
                'ShopPass',
                'AccessID',
                'AccessPass',
                'JobCd',
            );
        } else {
            $msg = '決済キャンセル・返品エラー：キャンセル・返品処理に対応していない決済です。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }

        $arrParam = array();
        if (SC_Utils_Ex::isBlank($arrOrder['TranDate'])) {
            return false;
        }
        sscanf($arrOrder['TranDate'], '%04d%02d%02d%02d%02d%02d', $year, $month, $day, $hour, $min, $sec);
        if ( date('Ymd') == sprintf('%04d%02d%02d', $year, $month, $day)) {
            if (array_search('JobCd', $arrSendKey) !== FALSE) {
                $arrParam['JobCd'] = 'VOID';
            }
        } else if (date('Ym') == sprintf('%04d%02d', $year, $month)) {
            if (array_search('JobCd', $arrSendKey) !== FALSE) {
                $arrParam['JobCd'] = 'RETURN';
            }
        } else {
            if(strtotime('+' . $term . ' days', mktime($hour, $min, $sec, $month, $day, $year)) > time()) {
                if (array_search('JobCd', $arrSendKey) !== FALSE) {
                    $arrParam['JobCd'] = 'RETURNX';
                    if ($arrOrder['Status'] == 'AUTH') {
                        $arrParam['JobCd'] = 'RETURN';
                    }
                }
            } else {
                $msg = '決済変更エラー：取消期限を越えています。(決済日から' . $term . '日以内)';
                $objMdl->printLog($msg);
                $this->setError($msg);
                return false;
            }
        }
        if ($arrParam['JobCd'] == 'RETURNX' && $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYSTATUS] == MDL_PG_MULPAY_PAY_STATUS_AUTH) {
            $arrParam['JobCd'] = 'RETURN';
        }

        $arrSendData = $this->getSendData($arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }

        $arrResults = $this->getResults();

        if (array_search('JobCd', $arrSendKey) !== FALSE) {
           $arrResults['JobCd'] = $arrParam['JobCd'];
        }

        $arrResults['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_EXEC_SUCCESS;
        if (array_search('JobCd', $arrSendKey) === FALSE && SC_Utils_Ex::isBlank($arrParam['JobCd'])) {
            $arrParam['JobCd'] = 'CANCEL';
        }
        $arrResults['pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrParam['JobCd']);

        SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, $arrResults);
        return true;
    }

}
