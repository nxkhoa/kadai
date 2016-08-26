<?php
/**
 * プラグイン の情報クラス.
 *
 * @package Pg2Click
 * @author GMO Payment Gateway, Inc.
 * @version $Id: $
 */
class plugin_info {
    /** プラグインコード(必須)：プラグインを識別する為キーで、他のプラグインと重複しない一意な値である必要がありま. */
    static $PLUGIN_CODE       = "Pg2Click";
    /** プラグイン名(必須)：EC-CUBE上で表示されるプラグイン名. */
    static $PLUGIN_NAME       = "PGマルチペイメントサービス 2クリック決済プラグイン";
    /** クラス名(必須)：プラグインのクラス（拡張子は含まない） */
    static $CLASS_NAME        = "Pg2Click";
    /** プラグインバージョン(必須)：プラグインのバージョン. */
    static $PLUGIN_VERSION    = "1.1";
    /** 対応バージョン(必須)：対応するEC-CUBEバージョン. */
    static $COMPLIANT_VERSION = "2.12.0";
    /** 作者(必須)：プラグイン作者. */
    static $AUTHOR            = "GMOペイメントゲートウェイ株式会社";
    /** 説明(必須)：プラグインの説明. */
    static $DESCRIPTION       = "PGマルチペイメントサービス決済モジュール用の２クリック決済プラグインです。";
    /** プラグインURL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $PLUGIN_SITE_URL   = "http://www.gmo-pg.com/";
    /** プラグイン作者URL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $AUTHOR_SITE_URL   = "http://www.gmo-pg.com/";
    /** フックポイント：フックポイントとコールバック関数を定義します */
    static $HOOK_POINTS       = array(
        array("LC_Page_Products_Detail_action_before", 'hookActionBefore'),
        array("LC_Page_Products_List_action_before", 'hookActionBefore'),
        array("LC_Page_Cart_action_before", 'hookActionBefore'),
        array("LC_Page_Shopping_action_before", 'hookActionBefore'),
        array("LC_Page_Shopping_Payment_action_before", 'hookActionBefore'),
        array("LC_Page_Shopping_Deliv_action_before", 'hookActionBefore'),
        array("LC_Page_Shopping_Confirm_action_before", 'hookActionBefore'),
        array("LC_Page_Shopping_Confirm_action_after", 'hookActionAfter'),
        array("prefilterTransform", 'prefilterTransform'));
}
