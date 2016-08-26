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
 * 決済モジュール モジュール設定画面クラス
 */
class LC_Page_Mdl_PG_MULPAY_Config extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * コンストラクタ
     *
     * @return void
     */
    function LC_Page_Mdl_PG_MULPAY_Config() {
    }

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $objMdl =& SC_Mdl_PG_MULPAY::getInstance();
        $this->tpl_mainpage = MDL_PG_MULPAY_TEMPLATE_PATH. 'admin/config.tpl';
        $this->tpl_subtitle = $objMdl->getName();
        $this->arrPayments = SC_Util_PG_MULPAY_Ex::getPaymentTypeNames();
        $this->arrConnectServerType = array( '1' => 'テスト環境', '2' => '本番環境' , '3' => '入力指定');
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
        $objMdl =& SC_Mdl_PG_MULPAY::getInstance();
        $objMdl->install();
        $objMdl->updateFile();
        switch($this->getMode()) {
        case 'register':
            list($this->arrForm, $this->arrErr, $this->tpl_onload) = $this->registerMode($_POST);
            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                $this->lfRegistPage($this->arrForm['is_tpl_init']['value']);
                $plugin_id = $this->lfRegistPlugins();
                $this->lfRegistBloc($this->arrForm['enable_payment_type']['value'], $plugin_id, $this->arrForm['is_tpl_init']['value']);
            }
            break;
        default:
            list($this->arrForm, $this->tpl_onload) = $this->defaultMode();
            break;
        }
        $this->tpl_is_module_regist = $this->lfIsRegistPaymentModule();
        $this->setTemplate($this->tpl_mainpage);
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
     * 初回表示処理
     *
     */
    function defaultMode() {
        $objMdl =& SC_Mdl_PG_MULPAY::getInstance();
        $subData = $objMdl->getUserSettings();
        $tpl_onload = $this->getBodyOnload($subData);
        $objForm = $this->initParam($subData);
        return array($objForm->getFormParamList(), $tpl_onload);
    }

    /**
     * 登録ボタン押下時の処理
     *
     */
    function registerMode(&$arrParam) {
        // 認証情報
        $objSess = new SC_Session_Ex();

        $objMdl =& SC_Mdl_PG_MULPAY::getInstance();
        $tpl_onload = $this->getBodyOnload($arrParam);
        $objForm = $this->initParam($arrParam);
        if ($arrErr = $this->checkError($objForm)) {
            return array($objForm->getFormParamList(), $arrErr, $tpl_onload);
        }
        $arrForm = $objForm->getHashArray();

        $objMdl->updateFile();
        $arrFailedFile = $objMdl->getFailedCopyFile();
        if (count($arrFailedFile) > 0) {
            foreach($arrFailedFile as $file) {
                $alert = $file . 'に書込権限を与えてください。';
                $tpl_onload .= 'alert("' . $alert . '");';
            }
            return array($objForm->getFormParamList(), $arrErr, $tpl_onload);
        }
        $arrForm['enable_security_code'] = '1';
        // 接続先切替機能
        switch ($arrForm['connect_server_type']) {
            case '1':
                $arrForm['server_url'] = MDL_PG_MULPAY_SERVER_URL_TEST;
                $arrForm['kanri_server_url'] = MDL_PG_MULPAY_KANRI_URL_TEST;
                break;
            case '2':
                $arrForm['server_url'] = MDL_PG_MULPAY_SERVER_URL_PROD;
                $arrForm['kanri_server_url'] = MDL_PG_MULPAY_KANRI_URL_PROD;
                break;
            case '3':
                break;
            default:
        }
        $objMdl->registerUserSettings($arrForm);

        // del_flgを削除にしておく
        $objQuery =& SC_Query::getSingletonInstance();
        $objQuery->begin();
        $arrUpdVal = array('del_flg' => 1);
        $where = 'module_code = ?';
        $arrWhereVal = array(MDL_PG_MULPAY_CODE);
        $objQuery->update('dtb_payment', $arrUpdVal, $where, $arrWhereVal);

        foreach ($arrForm['enable_payment_type'] as $payment_type_id) {
            $arrData = array();
            $arrData['payment_method'] = $this->arrPayments[ $payment_type_id ];
            $arrData[MDL_PG_MULPAY_PAYMENT_COL_PAYID] = $payment_type_id;
            $arrData['fix'] = 3;
            $arrData['creator_id'] = $objSess->member_id;
            $arrData['update_date'] = 'CURRENT_TIMESTAMP';
            $arrData['module_path'] = MDL_PG_MULPAY_PATH . 'payment.php';
            $arrData['module_code'] = $objMdl->getCode(true);
            $arrData['del_flg'] = '0';

            $arrPayment = $this->getPaymentDB($payment_type_id);

            // 更新データがあれば更新する。
            if (count($arrPayment) > 0){
                // データが存在していればUPDATE、無ければINSERT
                $where = "module_code = ? AND " . MDL_PG_MULPAY_PAYMENT_COL_PAYID . " = ?";
                $arrWhereVal = array($objMdl->getCode(true), (string)$payment_type_id);
                $arrDefault = $this->lfGetDefaultPaymentConfig($payment_type_id);
                $arrData['upper_rule_max'] = $arrDefault['upper_rule_max'];
                $objQuery->update('dtb_payment', $arrData, $where, $arrWhereVal);
            } else {
                // ランクの最大値を取得する
                $max_rank = $objQuery->max('rank', 'dtb_payment');
                $arrData["create_date"] = "CURRENT_TIMESTAMP";
                $arrData["rank"] = $max_rank + 1;
                $arrData['payment_id'] = $objQuery->nextVal('dtb_payment_payment_id');
                $arrData = array_merge($this->lfGetDefaultPaymentConfig($payment_type_id), $arrData);
                $objQuery->insert("dtb_payment", $arrData);
            }
        }

        $objQuery->commit();

        $tpl_onload .= 'alert("登録完了しました。\n基本情報＞支払方法設定より詳細設定をしてください。");window.close();';
        return array($objForm->getFormParamList(), $arrErr, $tpl_onload);
    }

    /**
     * フォームパラメータ初期化
     *
     * @param array $arrData
     * @return object SC_FormParam
     */
    function initParam($arrData = array()) {
        $objForm = new SC_FormParam_Ex();

        if (SC_Utils_Ex::isBlank($arrData['connect_server_type']) && !SC_Utils_Ex::isBlank($arrData['server_url'])) {
            $arrData['connect_server_type'] = '3';
        }

        $objForm->addParam('接続先', 'connect_server_type', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK'), isset($arrData['connect_server_type']) ? $arrData['connect_server_type'] : "");
        $objForm->addParam('接続先サーバーURL', 'server_url', URL_LEN, 'a', array('EXIST_CHECK', 'URL_CHECK'), isset($arrData['server_url']) ? $arrData['server_url'] : "");
        $objForm->addParam('管理画面サーバーURL', 'kanri_server_url', URL_LEN, 'a', array('EXIST_CHECK', 'URL_CHECK'), isset($arrData['kanri_server_url']) ? $arrData['kanri_server_url'] : "");

        $objForm->addParam('サイトID', 'site_id', STEXT_LEN, 'a', array('EXIST_CHECK', 'ALNUM_CHECK'), isset($arrData['site_id']) ? $arrData['site_id'] : "");
        $objForm->addParam('サイトパスワード', 'site_pass', STEXT_LEN, 'a', array('EXIST_CHECK', 'ALNUM_CHECK'), isset($arrData['site_pass']) ? $arrData['site_pass'] : "");
        $objForm->addParam('ショップID', 'ShopID', STEXT_LEN, 'a', array('EXIST_CHECK', 'ALNUM_CHECK'), isset($arrData['shop_id']) ? $arrData['shop_id'] : "");
        $objForm->addParam('ショップパスワード', 'ShopPass', STEXT_LEN, 'a', array('EXIST_CHECK', 'ALNUM_CHECK'), isset($arrData['shop_pass']) ? $arrData['shop_pass'] : "");

        $objForm->addParam('決済方法', 'enable_payment_type', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK'), isset($arrData['enable_payment_type']) ? $arrData['enable_payment_type'] : "");

        $objForm->addParam('テンプレート初期化', 'is_tpl_init', INT_LEN, 'n', array('NUM_CHECK'),"");

        $objForm->setParam($arrData);
        $objForm->convParam();
        return $objForm;
    }

    /**
     * 入力パラメータの検証
     *
     * @param SC_FormParam $objForm
     * @return array|null
     */
    function checkError(&$objForm) {
        $arrErr = null;
        $arrErr = $objForm->checkError();
        if ($objForm->getValue('connect_server_type') != '3') {
            unset($arrErr['server_url']);
            unset($arrErr['kanri_server_url']);
        }
        if (extension_loaded('xml') === false) {
            $arrErr['err'] .= '※ xml拡張モジュールがロード出来ません。PHPの動作環境がEC-CUBEのシステム環境要件を満たしているか確認して下さい。';
        }
        if (extension_loaded('curl') === false) {
            $arrErr['err'] .= '※ curl拡張モジュールがロード出来ません。PHPの動作環境がEC-CUBEのシステム環境要件を満たしているか確認して下さい。';
        }
        if (extension_loaded('mbstring') === false) {
            $arrErr['err'] .= '※ mbstring拡張モジュールがロード出来ません。PHPの動作環境がEC-CUBEのシステム環境要件を満たしているか確認して下さい。';
        }
        return $arrErr;
    }

    // DBからデータを取得する
    function getPaymentDB($type){
        $objMdl =& SC_Mdl_PG_MULPAY::getInstance();
        $objQuery =& SC_Query::getSingletonInstance();
        $arrVal = array($objMdl->getCode(true), (string)$type);
        $arrRet = $objQuery->select("module_id", "dtb_payment", "module_code = ? AND " . MDL_PG_MULPAY_PAYMENT_COL_PAYID . " = ?", $arrVal);
        return $arrRet;
    }

    // onload設定
    function getBodyOnload($arrData) {
        $tpl_onload = '';
        return $tpl_onload;
    }

    function lfIsRegistPaymentModule() {
        $objMdl =& SC_Mdl_PG_MULPAY::getInstance();
        $module_code = $objMdl->getCode(true);
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        if($objQuery->count('dtb_payment', 'module_code = ?', array($module_code))) {
            return true;
        }else{
            return false;
        }
    }

    function lfRegistPlugins() {
        $plugin_code = 'PgMulpayCore';
        $is_enable = true;
        $plugin_id = SC_Util_PG_MULPAY_Ex::installPluginFromPluginInfo($plugin_code, $is_enable, '0');

        $plugin_code = 'PgMulpayUtils';
        $is_enable = true;
        $plugin_id = SC_Util_PG_MULPAY_Ex::installPluginFromPluginInfo($plugin_code, $is_enable, '1');

        $plugin_code = 'Pg2Click';
        $is_enable = false;
        $plugin_id = SC_Util_PG_MULPAY_Ex::installPluginFromPluginInfo($plugin_code, $is_enable, '2');

        SC_Utils_Ex::clearCompliedTemplate();
        return $plugin_id;
    }

    function lfRegistPage($is_force) {
        $arrPageId = array();
        // 決済画面をデザインテンプレートに足す
        $page_name = '商品購入/決済画面';
        $url = 'shopping/load_payment_module.php';
        $filename = 'shopping/load_payment_module';

        $tpl_data = file_get_contents(MDL_PG_MULPAY_TEMPLATE_PATH . 'default/load_payment_module.tpl');
        $device_type_id = DEVICE_TYPE_PC;
        $page_id = SC_Util_PG_MULPAY_Ex::setPageData($tpl_data, $page_name, $url, $filename, $device_type_id, $is_force);
        $arrPageId[ $filename ][ $device_type_id ] = $page_id;

        $tpl_data = file_get_contents(MDL_PG_MULPAY_TEMPLATE_PATH . 'sphone/load_payment_module.tpl');
        $device_type_id = DEVICE_TYPE_SMARTPHONE;
        $page_id = SC_Util_PG_MULPAY_Ex::setPageData($tpl_data, $page_name, $url, $filename, $device_type_id, $is_force);
        $arrPageId[ $filename ][ $device_type_id ] = $page_id;

        $tpl_data = file_get_contents(MDL_PG_MULPAY_TEMPLATE_PATH . 'mobile/load_payment_module.tpl', $is_force);
        $device_type_id = DEVICE_TYPE_MOBILE;
        $page_id = SC_Util_PG_MULPAY_Ex::setPageData($tpl_data, $page_name, $url, $filename, $device_type_id, $is_force);
        $arrPageId[ $filename ][ $device_type_id ] = $page_id;

        $objMdl =& SC_Mdl_PG_MULPAY::getInstance();
        $objMdl->registerSubData($arrPageId, 'page_setting');
    }

    function lfGetDefaultPaymentConfig($payment_type_id) {
        $arrData = array();
        $arrData['charge'] = '0';
        $arrData['rule_max'] = '1';

        switch ($payment_type_id) {
            case MDL_PG_MULPAY_PAYID_CREDIT:
            case MDL_PG_MULPAY_PAYID_REGIST_CREDIT:
            case MDL_PG_MULPAY_PAYID_CREDIT_CHECK:
            case MDL_PG_MULPAY_PAYID_CREDIT_SAUTH:                
                $arrData['upper_rule'] = CREDIT_RULE_MAX;
                break;
            case MDL_PG_MULPAY_PAYID_CVS:
                $arrData['upper_rule'] = CONVENI_RULE_MAX;
                break;
            case MDL_PG_MULPAY_PAYID_PAYEASY:
            case MDL_PG_MULPAY_PAYID_ATM:
                $arrData['upper_rule'] = PAYEASY_RULE_MAX;
                break;
            case MDL_PG_MULPAY_PAYID_MOBILEEDY:
                $arrData['upper_rule'] = EDY_RULE_MAX;
                break;
            case MDL_PG_MULPAY_PAYID_MOBILESUICA:
                $arrData['upper_rule'] = SUICA_RULE_MAX;
                break;
            case MDL_PG_MULPAY_PAYID_PAYPAL:
                $arrData['upper_rule'] = PAYPAL_RULE_MAX;
                break;
            case MDL_PG_MULPAY_PAYID_IDNET:
                $arrData['upper_rule'] = NETID_RULE_MAX;
                break;
            case MDL_PG_MULPAY_PAYID_WEBMONEY:
                $arrData['upper_rule'] = WEBMONEY_RULE_MAX;
                break;
            case MDL_PG_MULPAY_PAYID_AU:
            case MDL_PG_MULPAY_PAYID_AUCONTINUANCE:
                $arrData['upper_rule'] = AU_RULE_MAX;
                break;
            case MDL_PG_MULPAY_PAYID_DOCOMO:
            case MDL_PG_MULPAY_PAYID_DOCOMOCONTINUANCE:
                $arrData['upper_rule'] = DOCOMO_RULE_MAX;
                break;
            case MDL_PG_MULPAY_PAYID_SB:
                $arrData['upper_rule'] = SB_RULE_MAX;

        }
        $arrData['upper_rule_max'] = $arrData['upper_rule'];
        return $arrData;
    }

    function lfRegistBloc($arrPaymentTypeId, $plugin_id, $is_force) {
        $arrBlocId = array();
        foreach ($arrPaymentTypeId as $payment_type_id) {
            $filename = "";
            switch ($payment_type_id) {
            case MDL_PG_MULPAY_PAYID_CREDIT:
                $filename ="pg_mulpay_credit";
                break;
            case MDL_PG_MULPAY_PAYID_REGIST_CREDIT:
                $filename ="pg_mulpay_regist_credit";
                break;
            case MDL_PG_MULPAY_PAYID_CVS:
                $filename ="pg_mulpay_cvs";
                break;
            case MDL_PG_MULPAY_PAYID_PAYEASY:
                $filename ="pg_mulpay_payeasy";
                break;
            case MDL_PG_MULPAY_PAYID_ATM:
                $filename ="pg_mulpay_atm";
                break;
            case MDL_PG_MULPAY_PAYID_MOBILEEDY:
                $filename ="pg_mulpay_mobileedy";
                break;
            case MDL_PG_MULPAY_PAYID_MOBILESUICA:
                $filename ="pg_mulpay_mobilesuica";
                break;
            case MDL_PG_MULPAY_PAYID_PAYPAL:
                $filename ="pg_mulpay_paypal";
                break;
            case MDL_PG_MULPAY_PAYID_IDNET:
                $filename ="pg_mulpay_idnet";
                break;
            case MDL_PG_MULPAY_PAYID_WEBMONEY:
                $filename ="pg_mulpay_webmoney";
                break;
            case MDL_PG_MULPAY_PAYID_AU:
                $filename ="pg_mulpay_au";
                break;
            case MDL_PG_MULPAY_PAYID_AUCONTINUANCE:
                $filename = "pg_mulpay_aucontinuance";
                break;
            case MDL_PG_MULPAY_PAYID_DOCOMO:
                $filename = 'pg_mulpay_docomo';
                break;
            case MDL_PG_MULPAY_PAYID_DOCOMOCONTINUANCE:
                $filename = 'pg_mulpay_docomocontinuance';
                break;
            case MDL_PG_MULPAY_PAYID_SB:
                $filename = 'pg_mulpay_sb';
                break;
            case MDL_PG_MULPAY_PAYID_REGIST_CREDIT:
            case MDL_PG_MULPAY_PAYID_CREDIT_CHECK:
            case MDL_PG_MULPAY_PAYID_CREDIT_SAUTH:
            default:
                break;
            }
            if ($filename != "") {
                $bloc_name = $this->arrPayments[$payment_type_id] . "入力フォーム";
                if (file_exists(MDL_PG_MULPAY_TEMPLATE_PATH . 'default/bloc/' . $filename . '.tpl')) {
                    $bloc_data = file_get_contents(MDL_PG_MULPAY_TEMPLATE_PATH . 'default/bloc/' . $filename . '.tpl');
                    $device_type_id = DEVICE_TYPE_PC;
                    $bloc_id = SC_Util_PG_MULPAY_Ex::setBlocData($plugin_id, $bloc_data, $device_type_id, $bloc_name, $filename, "", $is_force);
                    $arrBlocId[ $filename ][ $device_type_id ] = $bloc_id;
                }
                if (file_exists(MDL_PG_MULPAY_TEMPLATE_PATH . 'sphone/bloc/' . $filename . '.tpl')) {
                    $bloc_data = file_get_contents(MDL_PG_MULPAY_TEMPLATE_PATH . 'sphone/bloc/' . $filename . '.tpl');
                    $device_type_id = DEVICE_TYPE_SMARTPHONE;
                    $bloc_id = SC_Util_PG_MULPAY_Ex::setBlocData($plugin_id, $bloc_data, $device_type_id, $bloc_name, $filename, "", $is_force);
                    $arrBlocId[ $filename ][ $device_type_id ] = $bloc_id;
                }
                if (file_exists(MDL_PG_MULPAY_TEMPLATE_PATH . 'mobile/bloc/' . $filename . '.tpl')) {
                    $bloc_data = file_get_contents(MDL_PG_MULPAY_TEMPLATE_PATH . 'mobile/bloc/' . $filename . '.tpl');
                    $device_type_id = DEVICE_TYPE_MOBILE;
                    $bloc_id = SC_Util_PG_MULPAY_Ex::setBlocData($plugin_id, $bloc_data, $device_type_id, $bloc_name, $filename, "", $is_force);
                    $arrBlocId[ $filename ][ $device_type_id ] = $bloc_id;
                }
            }
        }
        $objMdl =& SC_Mdl_PG_MULPAY::getInstance();
        $objMdl->registerSubData($arrBlocId, 'bloc_setting');
    }

}
