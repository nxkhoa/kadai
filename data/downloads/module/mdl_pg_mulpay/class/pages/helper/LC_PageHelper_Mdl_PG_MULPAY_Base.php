<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

// {{{ requires
/**
 * 決済モジュール 決済画面ヘルパー：ベース
 */
class LC_PageHelper_MDL_PG_MULPAY_Base {
    /**
     * コンストラクタ
     *
     * @return void
     */
    function __construct() {
    }

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @param array $arrPaymentInfo 決済設定情報
     * @param array $arrOrder 受注情報
     * @return void
     */
    function initParam(&$objFormParam, $arrPaymentInfo, &$arrOrder) {
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
    }

    /**
     * 画面に設定するテンプレート名を返す
     *
     * @return text テンプレートファイル名
     */
    function getTemplate() {
        return "";
    }

    /**
     * 画面に設定するフォーム用ブロック名を返す
     *
     * @return text テンプレートブロック名
     */
    function getFormBloc() {
        return "";
    }


}
