<?php
/**
 *
 * @copyright 2012 GMO Payment Gateway, Inc. All Rights Reserved.
 * @link http://www.gmo-pg.com/
 *
 */

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * プラグインの設定画面クラス
 *
 * @package Pg2Click
 * @author GMO Payment Gateway, Inc.
 * @version $Id$
 */
class LC_Page_Plugin_Pg2Click_Config extends LC_Page_Admin_Ex {

    var $arrForm = array();

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR ."Pg2Click/config.tpl";
        $this->tpl_subtitle = "PGマルチペイメントサービス ２クリック決済プラグイン";
    }

    /**
     * プロセス.
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
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        $arrForm = array();
        switch ($this->getMode()) {
        case 'register':
            $arrForm = $objFormParam->getHashArray();
            $this->arrErr = $objFormParam->checkError();
            // エラーなしの場合にはデータを送信
            if (count($this->arrErr) == 0) {
                $this->arrErr = $this->registData($arrForm);
                if (count($this->arrErr) == 0) {
                    SC_Utils_Ex::clearCompliedTemplate();
                    $this->tpl_onload = "alert('設定が完了しました。');";
                }
            }
            break;
        default:
            $arrForm = $this->loadData();
            $this->tpl_is_init = true;
            break;
        }
        $this->arrForm = $arrForm;
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
     * パラメーター情報の初期化
     *
     * @param object $objFormParam SC_FormParamインスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam('ライセンスキー', 'LicenseKey', STEXT_LEN, 'a', array('EXIST_CHECK','MAX_LENGTH_CHECK','SPTAB_CHECK'));
    }


    function loadData() {
        $arrRet = array();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = "plugin_code = 'Pg2Click'";
        $arrData = $objQuery->getRow('*', 'dtb_plugin', $where);
        if (!SC_Utils_Ex::isBlank($arrData['free_field1'])) {
            $arrRet = unserialize($arrData['free_field1']);
        }
        return $arrRet;
    }

    function registData($arrData) {
        if (!SC_Utils_Ex::isBlank($arrData['LicenseKey'])
            && defined('MDL_PG_MULPAY')
            && MDL_PG_MULPAY == true
            && defined('MDL_PG_MULPAY_2CLICK_KEY')
            && MDL_PG_MULPAY_2CLICK_KEY === sha1($arrData['LicenseKey'])
            ) {
            $arrData['2click_enable'] = true;
        } else {
            return array('LicenseKey' => '※ 有効に出来ませんでした。<br />');
        }

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // UPDATEする値を作成する。
        $sqlval = array();
        $sqlval['free_field1'] = serialize($arrData);
        $sqlval['free_field2'] = '';
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = 'Pg2Click'";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where);
        $objQuery->commit();
    }
}

