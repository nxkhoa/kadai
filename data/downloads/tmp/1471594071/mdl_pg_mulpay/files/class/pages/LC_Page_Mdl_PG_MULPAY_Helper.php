<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

// {{{ requires
require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

/**
 * 決済モジュール 決済画面クラス
 */
class LC_Page_Mdl_PG_MULPAY_Helper extends LC_Page_Ex {
    var $type;
    var $objMdl;
    var $arrSetting;

    /**
     * コンストラクタ
     *
     * @return void
     */
    function LC_Page_Mdl_PG_MULPAY_Helper() {
        $this->objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $this->arrSetting = $this->objMdl->getUserSettings();
    }

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->httpCacheControl('nocache');
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
        $objSiteSess = new SC_SiteSession_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objCartSess = new SC_CartSession_Ex(); // 多重処理チェック

        if (!SC_Utils_Ex::isBlank($_SESSION['order_id'])) {
            $order_id = $_SESSION['order_id'];
        } else if (!SC_Utils_Ex::isBlank($_REQUEST['order_id'])
                     && SC_Utils_Ex::sfIsInt($_REQUEST['order_id'])
                     && $this->lfIsValidToken($_REQUEST['order_id'], $_REQUEST[TRANSACTION_ID_NAME])) {
            $order_id = $_REQUEST['order_id'];
            $_SESSION['order_id'] = $order_id;
        } else {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "例外エラー<br />注文情報の取得が出来ませんでした。<br />この手続きは無効となりました。");
        }

        $arrOrder = $objPurchase->getOrder($order_id);
        $this->tpl_title = $arrOrder['payment_method'];
        $objFormParam = new SC_FormParam();
        // 受注情報が決済処理中となっているか確認
        if ($arrOrder['status'] != ORDER_PENDING) {
            switch ($arrOrder['status']) {
            case ORDER_NEW:
            case ORDER_PRE_END:
                SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                SC_Response_Ex::actionExit();
                break;
            case ORDER_PAY_WAIT:
                // リンク型遷移での戻りは各ヘルパーに処理させる場合があるため、リダイレクトしない。
                if ($this->getMode() != 'pgreturn') {
                    SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                    SC_Response_Ex::actionExit();
                }
                break;
            default:
                if ($this->getMode() != 'pgreturn' && !SC_Utils_Ex::isBlank($arrOrder['status'])) {
                    SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                        "例外エラー<br />注文情報が無効です。<br />この手続きは無効となりました。");
                    SC_Response_Ex::actionExit();
                }
                break;
            }
        }

        // 決済手段毎のページヘルパークラスを読み込み
        $arrPaymentInfo = SC_Util_PG_MULPAY_Ex::getPaymentTypeConfig($arrOrder['payment_id']);
        if (SC_Utils_Ex::isBlank($arrPaymentInfo[MDL_PG_MULPAY_CODE . '_payment_code'])) {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "例外エラー<br />注文情報の決済方法と決済モジュールの設定が一致していません。<br />この手続きは無効となりました。<br />管理者に連絡をして下さい。");
            SC_Response_Ex::actionExit();
        }

        $helper_name = 'LC_PageHelper_Mdl_PG_MULPAY_' . $arrPaymentInfo[MDL_PG_MULPAY_CODE . '_payment_code'] . '_Ex';

        if (!file_exists(MDL_PG_MULPAY_PAGE_HELPEREX_PATH . $helper_name . '.php')) {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "例外エラー<br />決済モジュールのページヘルパーが読み込めません。<br />この手続きは無効となりました。<br />管理者に連絡をして下さい。");
            SC_Response_Ex::actionExit();
        }

        $this->lfSetToken($arrOrder, $arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID]);

        require_once(MDL_PG_MULPAY_PAGE_HELPEREX_PATH . $helper_name . '.php');

        $objPageHelper = new $helper_name;
        $objPageHelper->initParam($objFormParam, $arrPaymentInfo, $arrOrder);

        $objFormParam->setParam($arrPaymentInfo);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        $this->tpl_url = "?";
        if(SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE && $this->getMode() == '') {
            $_POST['mode'] = 'next';
            $_REQUEST['mode'] = 'next';
        }else if(SC_Display_Ex::detectDevice() === DEVICE_TYPE_SMARTPHONE) {
            $this->tpl_url = SHOPPING_MODULE_URLPATH;
        }

        $this->arrPaymentInfo = $arrPaymentInfo;

        $objPageHelper->modeAction($this->getMode(), $objFormParam, $arrOrder, $this);

        $this->tpl_form_bloc_path = $objPageHelper->getFormBloc();

        $this->arrForm = $objFormParam->getFormParamList();
/*
        if (defined('MDL_PG_MULPAY_DEBUG') && MDL_PG_MULPAY_DEBUG) {
            $this->objMdl->printLog(print_r($this,true));
        }
*/
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
     * トークンチェックしない.
     */
    function doValidToken() {
        // nothing.
    }

    function lfSetToken(&$arrOrder, $pay_id) {
        $objPurchase = new SC_Helper_Purchase_Ex();
        $sqlval[MDL_PG_MULPAY_ORDER_COL_TRANSID] = SC_Helper_Session_Ex::getToken();
        $sqlval[MDL_PG_MULPAY_ORDER_COL_PAYID] = $pay_id;
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], null, null, null, $sqlval);
        $objQuery->commit();
    }

    /**
     * 外部ページからの遷移の際に受注情報内のTRANSACTION IDとのCSFRチェックを行う。
     *
     * @param integer $order_id 受注ID
     * @param text $transactionid TRANSACTION ID
     * @return void
     */
    function lfIsValidToken($order_id, $transactionid) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        if($objQuery->get(MDL_PG_MULPAY_ORDER_COL_TRANSID, 'dtb_order', 'order_id = ?', array($order_id)) == $transactionid) {
            return true;
        }
        return false;
    }

}
