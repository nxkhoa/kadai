<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';
require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Util_Ex.php');

/**
 * 決済モジュール 結果受信クラス
 *
 */
class LC_Page_Mdl_PG_MULPAY_Recv extends LC_Page_Ex {

    var $objMdl;
    var $arrSetting;

    function LC_Page_Mdl_PG_MULPAY_Recv() {
    }

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        GC_Utils_Ex::gfPrintLog(print_r(file_get_contents('php://input'),true));
        $this->skip_load_page_layout = true;
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
//        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        $this->lfSetPostLog($_REQUEST, $this->objMdl);
        $objFormParam = new SC_FormParam();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_REQUEST);
        $objFormParam->convParam();

        $arrErr = $this->lfCheckError($objFormParam);
        if (SC_Utils_Ex::isBlank($arrErr)) {
            $order_id = $this->lfGetOrderId($objFormParam->getValue('OrderID'));
            if (SC_Utils_Ex::isBlank($order_id)) {
                $this->lfDoNoOrder($objFormParam->getHashArray());
                $this->lfSendResponse(false);
                SC_Response_Ex::actionExit();
            }

            if (!defined('MDL_PG_MULPAY_RECEIVE_WAIT_TIME')) {
                $sleep = 2;
            } else {
                $sleep = MDL_PG_MULPAY_RECEIVE_WAIT_TIME;
            }
            $status = $objFormParam->getValue('Status');
            if (/*strcmp($status, 'REQSUCCESS') == 0 ||*/ strcmp($status, 'AUTH') == 0 || strcmp($status, 'CHECK') == 0|| strcmp($status, 'CAPTURE') == 0) {
                sleep(MDL_PG_MULPAY_RECEIVE_WAIT_TIME);
            }

            $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($order_id);
            if (SC_Utils_Ex::isBlank($arrOrder)) {
                $this->lfDoNoOrder($objFormParam->getHashArray());
                $this->lfSendResponse(false);
                SC_Response_Ex::actionExit();
            }

            if (!$this->lfCheckTermRecv($objFormParam, $arrOrder)) {
                $this->lfSendResponse(true);
                SC_Response_Ex::actionExit();
            }

            $res = $this->lfDoReceive($objFormParam->getHashArray(), $arrOrder);

            $this->lfSendResponse($res);
            SC_Response_Ex::actionExit();
        } else {
            $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
            $objMdl->printLog('param_error:' . print_r($arrErr,true));
            $this->lfDoNoOrder($objFormParam->getHashArray());
            $this->lfSendResponse(false);
            SC_Response_Ex::actionExit();
        }
    }

    function lfDoReceive(&$arrParam, &$arrOrder) {

        if ($arrParam['AccessID'] != $arrOrder['AccessID']) {
            $this->lfDoUnMatchAccessID($arrParam, $arrOrder);
        }

        switch ($arrParam['PayType']) {
        case MULPAY_PAYTYPE_MOBILESUICA:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_MOBILESUICA) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                $res = $this->lfDoRecvMobileSuica($arrParam, $arrOrder);
            }
            break;
        case MULPAY_PAYTYPE_MOBILEEDY:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_MOBILEEDY) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                $res = $this->lfDoRecvMobileEdy($arrParam, $arrOrder);
            }
            break;
        case MULPAY_PAYTYPE_CVS:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_CVS) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                $res = $this->lfDoRecvCvs($arrParam, $arrOrder);
            }
            break;
        case MULPAY_PAYTYPE_PAYEASY:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_PAYEASY
                && $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_ATM) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                $res = $this->lfDoRecvPayEasy($arrParam, $arrOrder);
            }
            break;
        case MULPAY_PAYTYPE_PAYPAL:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_PAYPAL) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                $res = $this->lfDoRecvPayPal($arrParam, $arrOrder);
            }
            break;
        case MULPAY_PAYTYPE_IDNET:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_IDNET) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                $res = $this->lfDoRecvIDnet($arrParam, $arrOrder);
            }
            break;
        case MULPAY_PAYTYPE_WEBMONEY:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_WEBMONEY) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                $res = $this->lfDoRecvWebMoney($arrParam, $arrOrder);
            }
            break;
        case MULPAY_PAYTYPE_AU:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_AU) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                $res = $this->lfDoRecvAu($arrParam, $arrOrder);
            }
            break;
        case MULPAY_PAYTYPE_AUCONTINUANCE:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_AUCONTINUANCE) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                $res = $this->lfDoRecvAuContinuance($arrParam, $arrOrder);
            }
            break;
        case MULPAY_PAYTYPE_DOCOMO:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_DOCOMO) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                $res = $this->lfDoRecvDocomo($arrParam, $arrOrder);
            }
            break;
        case MULPAY_PAYTYPE_DOCOMOCONTINUANCE:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_DOCOMOCONTINUANCE) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                $res = $this->lfDoRecvDocomoContinuance($arrParam, $arrOrder);
            }
            break;
        case MULPAY_PAYTYPE_SB:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_SB) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                 $res = $this->lfDoRecvSb($arrParam, $arrOrder);
            }
            break;
        case MULPAY_PAYTYPE_CREDIT:
        default:
            if ($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_CREDIT
                && $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_REGIST_CREDIT
                && $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_CREDIT_CHECK
                && $arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYID] != MDL_PG_MULPAY_PAYID_CREDIT_SAUTH
                 ) {
                $this->lfDoUnMatchPayType($arrParam, $arrOrder);
                $res = false;
            } else {
                $res = $this->lfDoRecvCredit($arrParam, $arrOrder);
            }
            break;
        }
        if ($res) {
            unset($arrParam['ShopPass']);
            unset($arrParam['AccessPass']);
            SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, $arrParam);
        } else {
            // ログのみ
            SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, $arrParam, true);
        }
        return $res;
    }

    function lfDoRecvDefault(&$arrParam, &$arrOrder) {
        $order_status = null;
        $sqlval = array();
        switch ($arrParam['Status']) {
        case 'UNPROCESSED':
//            $order_status = ORDER_PAY_WAIT;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_UNSETTLED;
            break;
        case 'REQSUCCESS':
            $order_status = ORDER_PAY_WAIT;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_REQUEST_SUCCESS;
            break;
        case 'PAYSUCCESS':
            $order_status = ORDER_PRE_END;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_PAY_SUCCESS;
            break;
        case 'PAYFAIL':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;
            break;
        case 'EXPIRED':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_EXPIRE;
            break;
        default:
            return false;
        }
        if (!SC_Utils_Ex::isBlank($order_status)) {
            $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
            $objMdl->printLog('update order status:' .  $order_status);

            $objPurchase = new SC_Helper_Purchase_Ex();
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
            $objQuery->commit();
        }
        return true;
    }

    function lfDoRecvMobileSuica(&$arrParam, &$arrOrder) {
        $this->lfDoRecvDefault($arrParam, $arrOrder);
        return true;
    }

    function lfDoRecvMobileEdy(&$arrParam, &$arrOrder) {
        $this->lfDoRecvDefault($arrParam, $arrOrder);
        return true;
    }

    function lfDoRecvCvs(&$arrParam, &$arrOrder) {
        $this->lfDoRecvDefault($arrParam, $arrOrder);
        return true;
    }

    function lfDoRecvPayEasy(&$arrParam, &$arrOrder) {
        $this->lfDoRecvDefault($arrParam, $arrOrder);
        return true;
    }

    function lfDoRecvWebMoney(&$arrParam, &$arrOrder) {
        $this->lfDoRecvDefault($arrParam, $arrOrder);
        return true;
    }

    function lfDoRecvPayPal(&$arrParam, &$arrOrder) {
        $order_status = null;
        $sqlval = array();
        switch ($arrParam['Status']) {
        case 'UNPROCESSED':
            if ($arrParam['JobCd'] == 'CAPTURE') {
                $order_status = ORDER_PAY_WAIT;
            } else {
                $order_status = ORDER_CANCEL;
            }
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_UNSETTLED;
            break;
        case 'REQSUCCESS':
            if ($arrParam['JobCd'] == 'CAPTURE') {
                $order_status = ORDER_PAY_WAIT;
            } else {
                $order_status = ORDER_CANCEL;
            }
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_REQUEST_SUCCESS;
            break;
        case 'PAYSUCCESS':
            $order_status = ORDER_PRE_END;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_PAY_SUCCESS;
            break;
        case 'CAPTURE':
            $order_status = ORDER_PRE_END;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_CAPTURE;
            break;
        case 'PAYFAIL':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;
            break;
        case 'CANCEL':
            $order_status = ORDER_CANCEL;
            $arrParam['action_status'] = MDL_PG_MULPAY_ACTION_STATUS_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_CANCEL;
            break;
        case 'EXPIRED':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_EXPIRE;
            break;
        default:
            return false;
        }
        if (!SC_Utils_Ex::isBlank($order_status)) {
            $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
            $objMdl->printLog('update order status:' .  $order_status);
            $objPurchase = new SC_Helper_Purchase_Ex();
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
            $objQuery->commit();
            if ($order_status == ORDER_PRE_END) {
                $objPurchase->sendOrderMail($arrOrder['order_id']);
                $objMdl->printLog('send order mail:' . $arrOrder['order_id']);
            }
        }
        return true;
    }

    function lfDoRecvIDnet(&$arrParam, &$arrOrder) {
        $order_status = null;
        $sqlval = array();
        switch ($arrParam['Status']) {
        case 'UNPROCESSED':
            $order_status = ORDER_CANCEL;
            break;
        case 'REQSUCCESS':
            if ($arrParam['JobCd'] == 'CANCEL') {
                $order_status = ORDER_CANCEL;
            } else {
                $order_status = ORDER_PAY_WAIT;
            }
            break;
        case 'PAYSUCCESS':
            $order_status = ORDER_PRE_END;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_PAY_SUCCESS;
            break;
        case 'CAPTURE':
            $order_status = ORDER_PRE_END;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_CAPTURE;
            break;
        case 'AUTH':
            $order_status = ORDER_NEW;
            //$sqlval['payment_date'] = 'CURRENT_TIMESTAMP';
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_AUTH;
            break;
        case 'SALES':
            $order_status = null;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_SALES;
            break;
        case 'CANCEL':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_CANCEL;
            break;
        case 'PAYFAIL':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;
            break;
        case 'EXPIRED':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_EXPIRE;
            break;
        default:
            return false;
        }

        if (!SC_Utils_Ex::isBlank($arrParam['Amount'])) {
            $sqlval['payment_total'] = (int)trim($arrParam['Amount']) + (int)trim($arrParam['Tax']);
        }

        if (!SC_Utils_Ex::isBlank($order_status)) {
            $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
            $objMdl->printLog('update order status:' .  $order_status);

            $objPurchase = new SC_Helper_Purchase_Ex();
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
            $objQuery->commit();
        }
        return true;
    }

    function lfDoRecvSb(&$arrParam, &$arrOrder) {
        $order_status = null;
        $sqlval = array();
        switch ($arrParam['Status']) {
        case 'UNPROCESSED':
        case 'REQSUCCESS':
            switch ($arrParam['JobCd']) {
            case 'AUTH':
            case 'CAPTURE':
            case 'SALES':
                $order_status = ORDER_PENDING;
                break;
            case 'CANCEL':
                $order_status = ORDER_CANCEL;
                break;
            }
            break;
        case 'AUTH':
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_AUTH;
            $order_status = ORDER_NEW;
            break;
        case 'SALES':
            $order_status = null;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_SALES;
            break;
        case 'CAPTURE':
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_CAPTURE;
            $order_status = ORDER_NEW;
            break;
        case 'CANCEL':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_CANCEL;
            break;
        case 'PAYFAIL':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;
            break;
        case 'EXPIRED':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_EXPIRE;
            break;
        default:
            return false;
            break;
        }
        if (!SC_Utils_Ex::isBlank($arrParam['Amount'])) {
            $sqlval['payment_total'] = (int)trim($arrParam['Amount']) + (int)trim($arrParam['Tax']);
        }

        if (!SC_Utils_Ex::isBlank($order_status)) {
            $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
            $objMdl->printLog('update order status:' .  $order_status);
            $objPurchase = new SC_Helper_Purchase_Ex();
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
            $objQuery->commit();
            if ($order_status == ORDER_NEW) {
                $objPurchase->sendOrderMail($arrOrder['order_id']);
                $objMdl->printLog('send order mail:' . $arrOrder['order_id']);
            }
        }
        return true;
    }

    function lfDoRecvDocomoContinuance(&$arrParam, &$arrOrder) {
        $order_status = null;
        $sqlval = array();
        switch ($arrParam['Status']) {
        case 'UNPROCESSED':
        case 'REQSUCCESS':
        case 'AUTHPROCESS':
        case 'CERT_DONE':
            $order_status = ORDER_PAY_WAIT;
            break;
        case 'REGISTER':
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_PAY_SUCCESS;
            $arrPluginConfig = SC_Util_PG_MULPAY_Ex::getPluginConfig('PgCarrierSubs');
            if ($arrPluginConfig['regist_status']) {
                $order_status = $arrPluginConfig['regist_status'];
            } else {
                $order_status = ORDER_PRE_END;
            }
            break;
        case 'CANCEL':
        case 'RETURN':
        case 'PAYFAIL':
        case 'END':
        case 'RUN-END':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_PAYFAIL;
            break;
        default:
            return false;
        }

        if (!SC_Utils_Ex::isBlank($arrParam['Amount'])) {
            $sqlval['payment_total'] = (int)trim($arrParam['Amount']) + (int)trim($arrParam['Tax']);
        }

        if (!SC_Utils_Ex::isBlank($order_status)) {
            $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
            $objMdl->printLog('update order status:' .  $order_status);
            $objPurchase = new SC_Helper_Purchase_Ex();
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
            $objQuery->commit();
            if ($order_status == ORDER_PRE_END || $order_status == ORDER_NEW) {
                $objPurchase->sendOrderMail($arrOrder['order_id']);
                $objMdl->printLog('send order mail:' . $arrOrder['order_id']);
            }
        }
        return true;
    }

    function lfDoRecvAuContinuance(&$arrParam, &$arrOrder) {
        $order_status = null;
        $sqlval = array();
        switch ($arrParam['Status']) {
        case 'UNPROCESSED':
        case 'REQSUCCESS':
        case 'AUTHPROCESS':
        case 'CERT_DONE':
            $order_status = ORDER_PAY_WAIT;
            break;
        case 'REGISTER':
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_PAY_SUCCESS;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_PAY_SUCCESS;
            $arrPluginConfig = SC_Util_PG_MULPAY_Ex::getPluginConfig('PgCarrierSubs');
            if ($arrPluginConfig['regist_status']) {
                $order_status = $arrPluginConfig['regist_status'];
            } else {
                $order_status = ORDER_PRE_END;
            }
            break;
        case 'CANCEL':
        case 'RETURN':
        case 'PAYFAIL':
        case 'END':
        case 'RUN-END':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_PAYFAIL;
            break;
        default:
            return false;
        }

        if (!SC_Utils_Ex::isBlank($arrParam['Amount'])) {
            $sqlval['payment_total'] = (int)trim($arrParam['Amount']) + (int)trim($arrParam['Tax']);
        }

        if (!SC_Utils_Ex::isBlank($order_status)) {
            $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
            $objMdl->printLog('update order status:' .  $order_status);
            $objPurchase = new SC_Helper_Purchase_Ex();
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
            $objQuery->commit();
            if ($order_status == ORDER_PRE_END || $order_status == ORDER_NEW) {
                $objPurchase->sendOrderMail($arrOrder['order_id']);
                $objMdl->printLog('send order mail:' . $arrOrder['order_id']);
            }
        }
        return true;
    }


    function lfDoRecvAu(&$arrParam, &$arrOrder) {
        $order_status = null;
        $sqlval = array();
        switch ($arrParam['Status']) {
        case 'UNPROCESSED':
        case 'REQSUCCESS':
            switch ($arrParam['JobCd']) {
            case 'AUTH':
            case 'CAPTURE':
                $order_status = ORDER_PAY_WAIT;
                $order_status = null;
                break;
            case 'SALES':
            case 'RETURN':
                $order_status = null;
                break;
            case 'CANCEL':
                $order_status = ORDER_CANCEL;
                break;
            }
            break;
        case 'SALES':
            $order_status = null;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_SALES;
            break;
        case 'RETURN':
            $order_status = null;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_RETURN;
            break;
        case 'CANCEL':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_CANCEL;
            break;
        case 'PAYSUCCESS':
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_PAY_SUCCESS;
            $order_status = ORDER_PRE_END;
            break;
        case 'AUTH':
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_AUTH;
            $order_status = ORDER_NEW;
//            $sqlval['payment_date'] = 'CURRENT_TIMESTAMP';
            break;
        case 'CAPTURE':
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_CAPTURE;
            $order_status = ORDER_PRE_END;
            break;
        case 'SALES':
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_SALES;
//            $order_status = ORDER_PRE_END;
            break;
        case 'PAYFAIL':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;
            break;
        case 'EXPIRED':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_EXPIRE;
            break;
        default:
            return false;
        }

        if (!SC_Utils_Ex::isBlank($arrParam['Amount'])) {
            $sqlval['payment_total'] = (int)trim($arrParam['Amount']) + (int)trim($arrParam['Tax']);
        }

        if (!SC_Utils_Ex::isBlank($order_status)) {
            $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
            $objMdl->printLog('update order status:' .  $order_status);
            $objPurchase = new SC_Helper_Purchase_Ex();
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
            $objQuery->commit();
            if ($order_status == ORDER_PRE_END || $order_status == ORDER_NEW) {
                $objPurchase->sendOrderMail($arrOrder['order_id']);
                $objMdl->printLog('send order mail:' . $arrOrder['order_id']);
            }
        }
        return true;
    }

    function lfDoRecvCredit(&$arrParam, &$arrOrder) {
        $order_status = null;
        $sqlval = array();
        switch ($arrParam['Status']) {
        case 'UNPROCESSED':
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_UNSETTLED;
            break;
        case 'AUTHENTICATED':
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_UNSETTLED;
            break;
        case 'CHECK':
            $order_status = ORDER_NEW;
            $arrParam['pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrParam['Status']);
            break;
        case 'CAPTURE':
            $order_status = ORDER_NEW;
            $sqlval['payment_date'] = 'CURRENT_TIMESTAMP';
            $arrParam['pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrParam['Status']);
            break;
        case 'AUTH':
        case 'SAUTH':
            $order_status = ORDER_NEW;
            $arrParam['pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrParam['Status']);
            break;
        case 'SALES':
        case 'VOID':
        case 'RETURN':
        case 'RETURNX':
        case 'RETURNX':
            $order_status = null;
            $arrParam['pay_status'] = constant('MDL_PG_MULPAY_PAY_STATUS_' . $arrParam['Status']);
            break;
        default:
            return false;
        }

        if (!SC_Utils_Ex::isBlank($arrParam['Amount']) && $arrParam['Amount'] != '0') {
            $sqlval['payment_total'] = (int)trim($arrParam['Amount']) + (int)trim($arrParam['Tax']);
        }

        if (!SC_Utils_Ex::isBlank($order_status)) {
            $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
            if ($arrOrder['no_update_status_flg'] != 1) {
                $objMdl->printLog('update order status:' .  $order_status . ' order_id=' . $arrOrder['order_id']);
                $objPurchase = new SC_Helper_Purchase_Ex();
                $objQuery =& SC_Query_Ex::getSingletonInstance();
                $objQuery->begin();
                $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
                $objQuery->commit();
            } else {
                $objMdl->printLog('no update order status order_id=' . $arrOrder['order_id']);
                $arrParam['no_update_status_flg'] = '0';
            }
        }
        return true;
    }


    function lfDoRecvDocomo(&$arrParam, &$arrOrder) {
        $order_status = null;
        $sqlval = array();
        switch ($arrParam['Status']) {
        default:
        case 'UNPROCESSED':
        case 'REQSUCCESS':
            $order_status = null;
            switch ($arrParam['JobCd']) {
            case 'AUTH':
            case 'CAPTURE':
                $order_status = ORDER_PAY_WAIT;
                break;
            case 'SALES':
            case 'RETURN':
                $order_status = null;
                break;
            case 'CANCEL':
                $order_status = ORDER_CANCEL;
                break;
            }
            break;
        case 'AUTH':
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_AUTH;
            $order_status = ORDER_NEW;
            break;
        case 'CAPTURE':
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_CAPTURE;
            $order_status = ORDER_PRE_END;
            break;
        case 'SALES':
            $order_status = null;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_SALES;
            break;
        case 'CANCEL':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_CANCEL;
            break;
        case 'PAYFAIL':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_FAIL;
            break;
        case 'EXPIRED':
            $order_status = ORDER_CANCEL;
            $arrParam['pay_status'] = MDL_PG_MULPAY_PAY_STATUS_EXPIRE;
            break;
            return false;
        }
        if (!SC_Utils_Ex::isBlank($arrParam['Amount'])) {
            $sqlval['payment_total'] = (int)trim($arrParam['Amount']) + (int)trim($arrParam['Tax']);
        }

        if (!SC_Utils_Ex::isBlank($order_status)) {
            $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
            $objMdl->printLog('update order status:' .  $order_status);
            $objPurchase = new SC_Helper_Purchase_Ex();
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
            $objQuery->commit();
            if ($order_status == ORDER_PRE_END || $order_status == ORDER_NEW) {
                $objPurchase->sendOrderMail($arrOrder['order_id']);
                $objMdl->printLog('send order mail:' . $arrOrder['order_id']);
            }
        }
        return true;
    }


    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
     * POST アクセスの妥当性を検証する.無効化
     *
     */
    function doValidToken() {
    }

    function lfGetOrderId($param_OrderID) {
        list($order_id, $dummy) = explode('-', $param_OrderID);
        if (SC_Utils_Ex::isBlank($order_id) && !SC_Utils::sfIsInt($order_id)) {
            return;
        } else {
            return $order_id;
        }
    }

    /**
     * POST ログは全て残す事が推奨されている。
     *
     */
    function lfSetPostLog($arrPost) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $objMdl->printLog('******* receiveer data start *******');
//        foreach($arrPost as $key => $val) {
//            $objMdl->printLog("\t" . $key . " => " . $val);
//        }
        $objMdl->printLog(print_r($arrPost,true));
        $objMdl->printLog('******* receiveer data end *******');
    }

    /**
     * レスポンスを返す。
     *
     * @param boolean
     * @param mode
     * @return void
     */
    function lfSendResponse($result) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $objMdl->printLog('response:' . ($result ? 'true' : 'false'));
        if($result) {
            echo '0';
        } else {
            echo '1';
        }
    }

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam("ShopID", "ShopID", 13, 'a', array("MAX_LENGTH_CHECK", "ALNUM_CHECK", "EXIST_CHECK"));
        $objFormParam->addParam("ShopPass", "ShopPass", 10, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK", "EXIST_CHECK"));
        $objFormParam->addParam("AccessID", "AccessID", 32, 'a', array("MAX_LENGTH_CHECK", "ALNUM_CHECK"));
        $objFormParam->addParam("AccessPass", "AccessPass", 32, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("OrderID", "OrderID", 27, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK", "EXIST_CHECK"));
        $objFormParam->addParam("Status", "Status", STEXT_LEN, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("JobCd", "JobCd", STEXT_LEN, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("Amount", "Amount", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("Tax", "Tax", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("Currency", "Currency", 3, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("Forward", "Forward", 7, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("Method", "Method", 1, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("PayTimes", "PayTimes", 2, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("TranID", "TranID", 28, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("Approve", "Approve", 7, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("TranDate", "TranDate", 14, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("ErrCode", "ErrCode", STEXT_LEN, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("ErrInfo", "ErrInfo", STEXT_LEN, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("PayType", "PayType", 3, 'n', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("CvsCode", "CvsCode", 5, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("CvsConfNo", "CvsConfNo", 20, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("CvsReceiptNo", "CvsReceiptNo", 32, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("EdyReceiptNo", "EdyReceiptNo", 16, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("EdyOrderNo", "EdyOrderNo", 40, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("SuicaReceiptNo", "SuicaReceiptNo", 9, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("SuicaOrderNo", "SuicaOrderNo", 40, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("CustID", "CustID", 11, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("BkCode", "BkCode", 5, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("ConfNo", "ConfNo", 20, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("PaymentTerm", "PaymentTerm", 14, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("EncryptReceiptNo", "EncryptReceiptNo", 128, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("FinishDate", "FinishDate", 14, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("ReceiptDate", "ReceiptDate", 14, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("WebMoneyManagementNo", "WebMoneyManagementNo", 16, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("WebMoneySettleCode", "WebMoneySettleCode", 25, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("AuPayInfoNo", "AuPayInfoNo", 16, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("AuPayMethod", "AuPayMethod", 2, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("AuCancelAmount", "AuCancelAmount", 7, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("AuCancelTax", "AuCancelTax", 7, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("DocomoSettlementCode", "DocomoSettlementCode", 12, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"));
        $objFormParam->addParam("DocomoCancelAmount", "DocomoCancelAmount", 6, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("DocomoCancelTax", "DocomoCancelTax", 6, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));

    }

    /**
     * 入力内容のチェックを行なう.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return array 入力チェック結果の配列
     */
    function lfCheckError(&$objFormParam) {
        $objErr = new SC_CheckError_Ex($objFormParam->getHashArray());
        $objErr->arrErr = $objFormParam->checkError();

        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        if ($objFormParam->getValue('ShopID') != $arrMdlSetting['ShopID']) {
            $objErr->arrErr['ShopID'] = '※ShopIDが一致しません。';
        }

        return $objErr->arrErr;
    }

    /**
     * 間隔が空いた通知のチェック
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param array $arrOrder 受注情報
     * @return boolean
     */
    function lfCheckTermRecv(&$objFormParam, &$arrOrder) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        if ($objFormParam->getValue('Status') != ''
                && $objFormParam->getValue('TranDate') != ''
                && defined('MDL_PG_MULPAY_RECEIVE_CHECK_TIME')) {
            $trandate = $objFormParam->getValue('TranDate');
            $y = substr($trandate, 0, 4);
            $m = substr($trandate, 4, 2);
            $d = substr($trandate, 6, 2);
            $h = substr($trandate, 8, 2);
            $i = substr($trandate, 10, 2);
            $s = substr($trandate, 12, 2);
            $trantime = mktime($h, $i, $s, $m, $d, $y);
            $now = mktime();
            if ($now - $trantime > MDL_PG_MULPAY_RECEIVE_CHECK_TIME) {
                $objMdl->printLog('CHECK TIME Over TranDate=' . $trandate . '(' . $trantime . ':' . $now . ')');

                $objClient = new SC_Mdl_PG_MULPAY_Client_Util_Ex();
                $ret = $objClient->getOrderInfo($arrOrder);
                if (!$ret) {
                    $objMdl->printLog('Fail: check status request');
                    return false;
                }
                $arrResult = $objClient->getResults();
                if ($arrResult['Status'] != $objFormParam->getValue('Status')) {
                    $objMdl->printLog('Pass request: Term check and status no match');
                    return false;
                }
            }
        }
        return true;
    }


    function lfSendMail($tplpath, $subject, $arrParam, $arrOrder = array()) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $objMdl->printLog('param_error:' . $subject . ' Param:' . print_r($arrParam,true));

        if (!SC_Utils_Ex::isBlank($arrParam['ErrCode'])
            and !SC_Utils_Ex::isBlank($arrParam['ErrInfo'])) {
            return;
        }
        if ($arrParam['Status'] == 'PAYFAIL') {
            return;
        }
        switch ($arrParam['PayType']) {
        case MULPAY_PAYTYPE_MOBILESUICA:
            $arrParam['pay_type'] = MDL_PG_MULPAY_PAYNAME_MOBILESUICA;
            break;
        case MULPAY_PAYTYPE_MOBILEEDY:
            $arrParam['pay_type'] = MDL_PG_MULPAY_PAYNAME_MOBILEEDY;
            break;
        case MULPAY_PAYTYPE_CVS:
            $arrParam['pay_type'] = MDL_PG_MULPAY_PAYNAME_CVS;
            break;
        case MULPAY_PAYTYPE_PAYEASY:
            $arrParam['pay_type'] = MDL_PG_MULPAY_PAYNAME_PAYEASY;
            break;
        case MULPAY_PAYTYPE_PAYPAL:
            $arrParam['pay_type'] = MDL_PG_MULPAY_PAYNAME_PAYPAL;
            break;
        case MULPAY_PAYTYPE_IDNET:
            $arrParam['pay_type'] = MDL_PG_MULPAY_PAYNAME_IDNET;
            break;
        case MULPAY_PAYTYPE_WEBMONEY:
            $arrParam['pay_type'] = MDL_PG_MULPAY_PAYNAME_WEBMONEY;
            break;
        case MULPAY_PAYTYPE_AU:
            $arrParam['pay_type'] = MDL_PG_MULPAY_PAYNAME_AU;
            break;
        case MULPAY_PAYTYPE_DOCOMO:
            $arrParam['pay_type'] = MDL_PG_MULPAY_PAYNAME_DOCOMO;
            break;
        case MULPAY_PAYTYPE_CREDIT:
            $arrParam['pay_type'] = MDL_PG_MULPAY_PAYNAME_CREDIT;
            break;
        default:
            $arrParam['pay_type'] = '不明な決済(PayType)';
            break;
        }
        $arrParam['order_id'] = $this->lfGetOrderId($arrParam['OrderID']);

        $objPage = new LC_Page_Ex();
        $objPage->arrParam = $arrParam;
        $objPage->arrOrder = $arrOrder;
        $objMailView = new SC_SiteView_Ex();
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        $objMailView->assignobj($objPage);
        $body = $objMailView->fetch($tplpath);
        // メール送信処理
        $objSendMail = new SC_SendMail_Ex();
        $to = $arrInfo['email02'];
        $from = $arrInfo['email03'];
        $error = $arrInfo['email04'];
        $objSendMail->setItem($to, $subject, $body, $from, $arrInfo['shop_name'], $from, $error, $error);
        $objSendMail->sendMail();
    }

    function lfDoNoOrder($arrParam) {
        $tplpath = MDL_PG_MULPAY_TEMPLATE_PATH . 'mail_template/recv_no_order.tpl';
        $subject = MDL_PG_MULPAY_MODULE_NAME . ' 不一致データ検出';

        $this->lfSendMail($tplpath, $subject, $arrParam);
    }

    function lfDoUnMatchAccessID(&$arrParam, &$arrOrder) {
        $tplpath = MDL_PG_MULPAY_TEMPLATE_PATH . 'mail_template/recv_unmatch_accessid.tpl';
        $subject = MDL_PG_MULPAY_MODULE_NAME . ' 取引ID不一致データ検出';
        $this->lfSendMail($tplpath, $subject, $arrParam, $arrOrder);
    }

    function lfDoUnMatchPayType(&$arrParam, $arrOrder) {
        $tplpath = MDL_PG_MULPAY_TEMPLATE_PATH . 'mail_template/recv_unmatch_paytype.tpl';
        $subject = MDL_PG_MULPAY_MODULE_NAME . ' 支払い方法不一致データ検出';
        $this->lfSendMail($tplpath, $subject, $arrParam, $arrOrder);
    }

}


