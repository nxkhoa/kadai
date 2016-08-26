<?php
/**
 *
 * @copyright 2014 GMO Payment Gateway, Inc. All Rights Reserved.
 * @link http://www.gmo-pg.com/
 *
 */

// モジュール基本設定
define('MDL_PG_MULPAY', true);
define('MDL_PG_MULPAY_CODE', 'mdl_pg_mulpay');
define('MDL_PG_MULPAY_VERSION', '4.3.8');

// 表示名など
define('MDL_PG_MULPAY_SERVICE_NAME', 'PGマルチペイメントサービス');
define('MDL_PG_MULPAY_MODULE_NAME', 'PGマルチペイメントサービス決済モジュール');
define('MDL_PG_MULPAY_COMPANY_NAME', 'GMOペイメントゲートウェイ株式会社');
define('MDL_PG_MULPAY_INFO_URI', 'http://www.gmo-pg.com/');

// モジュールデバッグ設定
// true の場合、顧客の入力内容がログに残りますので運用時は必ずfalseにして下さい。
define('MDL_PG_MULPAY_DEBUG', false);

// 決済結果受信スリープ時間(秒)
define('MDL_PG_MULPAY_RECEIVE_WAIT_TIME', 5);

// 結果通知の間隔が空いた場合のチェックを行う閾値となる時間(秒)
define('MDL_PG_MULPAY_RECEIVE_CHECK_TIME', 900);

// 接続先設定
define('MDL_PG_MULPAY_SERVER_URL_PROD', 'https://p01.mul-pay.jp/payment/');
define('MDL_PG_MULPAY_KANRI_URL_PROD', 'https://k01.mul-pay.jp/payment/');
define('MDL_PG_MULPAY_SERVER_URL_TEST', 'https://pt01.mul-pay.jp/payment/');
define('MDL_PG_MULPAY_KANRI_URL_TEST', 'https://kt01.mul-pay.jp/kanri/');

// モジュールパス設定
define('MDL_PG_MULPAY_PATH', MODULE_REALDIR.'mdl_pg_mulpay/');
define('MDL_PG_MULPAY_CLASS_PATH', MDL_PG_MULPAY_PATH.'class/');
define('MDL_PG_MULPAY_CLASSEX_PATH', MDL_PG_MULPAY_PATH.'class_extends/');
define('MDL_PG_MULPAY_MODULE_PATH', MDL_PG_MULPAY_PATH.'module/');
define('MDL_PG_MULPAY_TEMPLATE_PATH', MDL_PG_MULPAY_PATH.'templates/');
define('MDL_PG_MULPAY_INCLUDEFILE_PATH', MDL_PG_MULPAY_PATH.'inc/include.php');

define('MDL_PG_MULPAY_PAGE_HELPER_PATH', MDL_PG_MULPAY_PATH.'class/pages/helper/');
define('MDL_PG_MULPAY_PAGE_HELPEREX_PATH', MDL_PG_MULPAY_PATH.'class_extends/pages_extends/helper_extends/');

// エラーメッセージファイル
define('MDL_PG_MULPAY_ERROR_CODE_MSG_FILE', MDL_PG_MULPAY_PATH .'inc/pg_mulpay_errors.txt');


// 決済通知先
define('MDL_PG_MULPAY_SETTLEMENT_FILE', 'pg_mulpay_recv.php');
define('MDL_PG_MULPAY_SETTLEMENT_URL', HTTPS_URL . USER_DIR . MDL_PG_MULPAY_SETTLEMENT_FILE);
define('MDL_PG_MULPAY_SETTLEMENT_PATH', USER_REALDIR . MDL_PG_MULPAY_SETTLEMENT_FILE);

// メディアファイル設置先
define('MDL_PG_MULPAY_MEDIAFILE_URL', HTTPS_URL . USER_DIR . MDL_PG_MULPAY_CODE . '/');
define('MDL_PG_MULPAY_MEDIAFILE_PATH', USER_REALDIR . MDL_PG_MULPAY_CODE . '/');

// 受注データ毎の情報保存カラム設定
define('MDL_PG_MULPAY_ORDER_COL_PAYVIEW', 'memo02');
define('MDL_PG_MULPAY_ORDER_COL_PAYID', 'memo03');
define('MDL_PG_MULPAY_ORDER_COL_PAYSTATUS', 'memo04');
define('MDL_PG_MULPAY_ORDER_COL_PAYDATA', 'memo05');
define('MDL_PG_MULPAY_ORDER_COL_SPFLG', 'memo06');
define('MDL_PG_MULPAY_ORDER_COL_SPDATA', 'memo07');
define('MDL_PG_MULPAY_ORDER_COL_TRANSID', 'memo08');
define('MDL_PG_MULPAY_ORDER_COL_PAYLOG', 'memo09');

// 支払い方法毎の情報保存カラム設定
define('MDL_PG_MULPAY_PAYMENT_COL_PAYID', 'memo03');
define('MDL_PG_MULPAY_PAYMENT_COL_CONFIG', 'memo05');

// 決済タイプ (EC-CUBE内の決済種別 識別ID)
define('MDL_PG_MULPAY_PAYID_CREDIT', '10');
define('MDL_PG_MULPAY_PAYID_REGIST_CREDIT', '11');
define('MDL_PG_MULPAY_PAYID_CREDIT_CHECK', '12');
define('MDL_PG_MULPAY_PAYID_CREDIT_SAUTH', '12');
define('MDL_PG_MULPAY_PAYID_CVS', '20');
define('MDL_PG_MULPAY_PAYID_PAYEASY', '30');
define('MDL_PG_MULPAY_PAYID_ATM', '31');
define('MDL_PG_MULPAY_PAYID_MOBILEEDY', '40');
define('MDL_PG_MULPAY_PAYID_MOBILESUICA', '50');
define('MDL_PG_MULPAY_PAYID_PAYPAL', '60');
define('MDL_PG_MULPAY_PAYID_IDNET', '70');
define('MDL_PG_MULPAY_PAYID_WEBMONEY', '80');
define('MDL_PG_MULPAY_PAYID_AU', '90');
define('MDL_PG_MULPAY_PAYID_AUCONTINUANCE', '91');
define('MDL_PG_MULPAY_PAYID_DOCOMO', '92');
define('MDL_PG_MULPAY_PAYID_DOCOMOCONTINUANCE', '93');
define('MDL_PG_MULPAY_PAYID_SB', '95');
define('MDL_PG_MULPAY_PAYID_BANKTRANS', '100');
define('MDL_PG_MULPAY_PAYID_COLLECT', '900');

// 決済タイプ (EC-CUBE内の決済種別 識別コード)
define('MDL_PG_MULPAY_PAYCODE_CREDIT', 'Credit');
define('MDL_PG_MULPAY_PAYCODE_REGIST_CREDIT', 'RegistCredit');
define('MDL_PG_MULPAY_PAYCODE_CREDIT_CHECK', 'CreditCheck');
define('MDL_PG_MULPAY_PAYCODE_CREDIT_SAUTH', 'CreditSAUTH');
define('MDL_PG_MULPAY_PAYCODE_CVS', 'CVS');
define('MDL_PG_MULPAY_PAYCODE_PAYEASY', 'PayEasy');
define('MDL_PG_MULPAY_PAYCODE_ATM', 'ATM');
define('MDL_PG_MULPAY_PAYCODE_MOBILEEDY', 'MobileEdy');
define('MDL_PG_MULPAY_PAYCODE_MOBILESUICA', 'MobileSuica');
define('MDL_PG_MULPAY_PAYCODE_PAYPAL', 'PayPal');
define('MDL_PG_MULPAY_PAYCODE_IDNET', 'IDnet');
define('MDL_PG_MULPAY_PAYCODE_WEBMONEY', 'WebMoney');
define('MDL_PG_MULPAY_PAYCODE_AU', 'Au');
define('MDL_PG_MULPAY_PAYCODE_AUCONTINUANCE', 'AuContinuance');
define('MDL_PG_MULPAY_PAYCODE_DOCOMO', 'Docomo');
define('MDL_PG_MULPAY_PAYCODE_DOCOMOCONTINUANCE', 'DocomoContinuance');
define('MDL_PG_MULPAY_PAYCODE_SB', 'Sb');
define('MDL_PG_MULPAY_PAYCODE_COLLECT', 'Collect');

// PAYTYPE
define('MULPAY_PAYTYPE_CREDIT', '0');
define('MULPAY_PAYTYPE_MOBILESUICA', '1');
define('MULPAY_PAYTYPE_MOBILEEDY', '2');
define('MULPAY_PAYTYPE_CVS', '3');
define('MULPAY_PAYTYPE_PAYEASY', '4');
define('MULPAY_PAYTYPE_PAYPAL', '5');
define('MULPAY_PAYTYPE_IDNET', '6');
define('MULPAY_PAYTYPE_WEBMONEY', '7');
define('MULPAY_PAYTYPE_AU', '8');
define('MULPAY_PAYTYPE_DOCOMO', '9');
define('MULPAY_PAYTYPE_DOCOMOCONTINUANCE', '10');
define('MULPAY_PAYTYPE_SB', '11');
define('MULPAY_PAYTYPE_JIBUN', '12');
define('MULPAY_PAYTYPE_AUCONTINUANCE', '13');


// 決済タイプ毎のデフォルト表示名
define('MDL_PG_MULPAY_PAYNAME_CREDIT', 'クレジットカード決済');
define('MDL_PG_MULPAY_PAYNAME_REGIST_CREDIT', '登録済みクレジットカード決済');
define('MDL_PG_MULPAY_PAYNAME_CREDIT_CHECK', 'クレジットカード有効性確認');
define('MDL_PG_MULPAY_PAYNAME_CREDIT_SAUTH', 'クレジットカード与信確認');
define('MDL_PG_MULPAY_PAYNAME_CVS', 'コンビニ決済');
define('MDL_PG_MULPAY_PAYNAME_PAYEASY', 'Pay-easy決済(ネットバンク)');
define('MDL_PG_MULPAY_PAYNAME_ATM', 'Pay-easy決済(銀行ATM)');
define('MDL_PG_MULPAY_PAYNAME_MOBILEEDY', '楽天Edy決済');
define('MDL_PG_MULPAY_PAYNAME_MOBILESUICA', 'モバイルSuica決済');
define('MDL_PG_MULPAY_PAYNAME_PAYPAL', 'PayPal決済');
define('MDL_PG_MULPAY_PAYNAME_IDNET', 'iD決済');
define('MDL_PG_MULPAY_PAYNAME_WEBMONEY', 'WebMoney決済');
define('MDL_PG_MULPAY_PAYNAME_AU', 'auかんたん決済');
define('MDL_PG_MULPAY_PAYNAME_AUCONTINUANCE', 'auかんたん決済継続課金');
define('MDL_PG_MULPAY_PAYNAME_DOCOMO', 'ドコモケータイ払い');
define('MDL_PG_MULPAY_PAYNAME_DOCOMOCONTINUANCE', 'ドコモ継続課金決済');
define('MDL_PG_MULPAY_PAYNAME_SB', 'ソフトバンクまとめて支払い');
define('MDL_PG_MULPAY_PAYNAME_BANKTRANS', '口座引き落とし');
define('MDL_PG_MULPAY_PAYNAME_COLLECT', '代引決済');

// 入力状態
define('MDL_PG_MULPAY_INPUT_STATUS_VIEW_FORM', 0);      // 入力前（フォーム表示済み）
define('MDL_PG_MULPAY_INPUT_STATUS_ENTRY_FORM', 1);     // 入力受付済み

// 操作状態
define('MDL_PG_MULPAY_ACTION_STATUS_UNSETTLED', 0);        // 操作前
define('MDL_PG_MULPAY_ACTION_STATUS_ENTRY_REQUEST', 1);    // 取引登録要求
define('MDL_PG_MULPAY_ACTION_STATUS_ENTRY_SUCCESS', 2);    // 取引登録成功
define('MDL_PG_MULPAY_ACTION_STATUS_EXEC_REQUEST', 3);     // 決済要求
define('MDL_PG_MULPAY_ACTION_STATUS_EXEC_SUCCESS', 6);     // 決済要求成功
define('MDL_PG_MULPAY_ACTION_STATUS_EXEC_FAIL', 7);        // 決済要求失敗
define('MDL_PG_MULPAY_ACTION_STATUS_WAIT_NOTICE', 4);      // 通知待ち
define('MDL_PG_MULPAY_ACTION_STATUS_RECV_NOTICE', 5);      // 通知受信(済)
define('MDL_PG_MULPAY_ACTION_STATUS_EXPIRE', 8);           // 期限切れ
define('MDL_PG_MULPAY_ACTION_STATUS_CANCEL', 9);           // キャンセル


// 決済状態
define('MDL_PG_MULPAY_PAY_STATUS_UNSETTLED', 0);      // 未決済
define('MDL_PG_MULPAY_PAY_STATUS_UNPROCESSED', MDL_PG_MULPAY_PAY_STATUS_UNSETTLED);      // 未決済
define('MDL_PG_MULPAY_PAY_STATUS_AUTHENTICATED', MDL_PG_MULPAY_PAY_STATUS_UNSETTLED);      // 未決済
define('MDL_PG_MULPAY_PAY_STATUS_AUTHPROCESS', MDL_PG_MULPAY_PAY_STATUS_UNSETTLED);      // 未決済

define('MDL_PG_MULPAY_PAY_STATUS_REQUEST_SUCCESS', 1);// 要求成功
define('MDL_PG_MULPAY_PAY_STATUS_REQSUCCESS', MDL_PG_MULPAY_PAY_STATUS_REQUEST_SUCCESS);// 要求成功

define('MDL_PG_MULPAY_PAY_STATUS_PAY_SUCCESS', 2);    // 支払い完了
define('MDL_PG_MULPAY_PAY_STATUS_PAYSUCCESS', MDL_PG_MULPAY_PAY_STATUS_PAY_SUCCESS);    // 支払い完了

define('MDL_PG_MULPAY_PAY_STATUS_EXPIRE', 3);         // 期限切れ
define('MDL_PG_MULPAY_PAY_STATUS_CANCEL', 4);       // キャンセル(PayPal)
define('MDL_PG_MULPAY_PAY_STATUS_REGISTER', 5);     // 登録済み(継続課金)
define('MDL_PG_MULPAY_PAY_STATUS_CERT_DONE', 6);
define('MDL_PG_MULPAY_PAY_STATUS_FAIL', 99);           // 決済失敗
define('MDL_PG_MULPAY_PAY_STATUS_PAYFAIL', MDL_PG_MULPAY_PAY_STATUS_FAIL);           // 決済失敗

// カード取引状態
define('MDL_PG_MULPAY_PAY_STATUS_AUTH', 11);         // 仮売上済み
define('MDL_PG_MULPAY_PAY_STATUS_COMMIT', 12);       // 実売上済み
define('MDL_PG_MULPAY_PAY_STATUS_SALES', 12);       // 実売上済み
define('MDL_PG_MULPAY_PAY_STATUS_CAPTURE', 13);      // 即時売上げ済み
define('MDL_PG_MULPAY_PAY_STATUS_VOID', 14);         // 取消済み
define('MDL_PG_MULPAY_PAY_STATUS_RETURN', 15);       // 返品済み
define('MDL_PG_MULPAY_PAY_STATUS_RETURNX', 16);      // 月跨ぎ返品済み
define('MDL_PG_MULPAY_PAY_STATUS_SAUTH', 17);        // 簡易オーソリ済み
define('MDL_PG_MULPAY_PAY_STATUS_CHECK', 18);        // 有効性チェック済み

define('MDL_PG_MULPAY_PAY_STATUS_EXCEPT', 19);      // 例外エラー

// ドコモ継続課金
define('MDL_PG_MULPAY_PAY_STATUS_REGISTER', 30);    // 契約中
define('MDL_PG_MULPAY_PAY_STATUS_END', 31); // 終了
define('MDL_PG_MULPAY_PAY_STATUS_ERASE', 32);   // 抹消
define('MDL_PG_MULPAY_PAY_STATUS_RUN-CHANGE', 33);  // 変更中
define('MDL_PG_MULPAY_PAY_STATUS_RUN-END', 34); // 終了中


// 決済通信タイムアウト値 (接続タイムアウト、ソケットタイムアウト共通)
define('MDL_PG_MULPAY_HTTP_TIMEOUT', 10);

// 遷移URLデフォルト
define("MDL_PG_MULPAY_COMPLETE_URL", HTTPS_URL . "shopping/load_payment_module.php");  // 信用要求成功URL
define("MDL_PG_MULPAY_RETURN_URL", HTTPS_URL  . "shopping/load_payment_module.php");  // キャンセル時
define("MDL_PG_MULPAY_ERROR_URL", HTTPS_URL  . "shopping/load_payment_module.php");  // エラー時


// 自由項目3
define('MDL_PG_MYLPAY_CLIENT_FIELD3', 'EC-CUBE');
// TenantNo
define('MDL_PG_MULPAY_TENANTNO', 'TenantNo');
// SeqMode
define('MDL_PG_MULPAY_SEQMODE', '0');
// カード登録件数
define('MDL_PG_MULPAY_CARDCOUNT', 5);

// 処理区分
$arrJobCd = array(
    'AUTH',
    'CHECK',
    'CAPTURE',
    'SAUTH',
);

//コンビニの種類
define('CONVENI_LOSON', '00001');
define('CONVENI_FAMILYMART', '00002');
define('CONVENI_SUNKUS', '00003');
define('CONVENI_CIRCLEK', '00004');
define('CONVENI_MINISTOP', '00005');
define('CONVENI_DAILYYAMAZAKI', '00006');
define('CONVENI_SEVENELEVEN', '00007');
define('CONVENI_SEICOMART', '00008');
define('CONVENI_THREEF', '00009');

// 禁止半角記号
$arrProhiditedKigo = array('^','`','{','|','}','~','&','<','>','"','\'');

// カード番号桁数
define('CREDIT_NO_MIN_LEN', 10);
define('CREDIT_NO_MAX_LEN', 16);
// セキュリティコード桁数
define('SECURITY_CODE_LEN', 4);

// 入力パラメータ桁数
define('TDTENANT_NAME_LEN', 18);
define('PAYMENT_TERM_DAY_LEN', 2);
define('PAYMENT_TERM_DAY_MAX', 30);
define('PAYMENT_TERM_SEC_LEN', 5);
define('PAYMENT_TERM_SEC_MAX', 86400);
define('PAYMENT_TERM_MIN', 300);
define('PAYMENT_TERM_MAX', 30);
define('REGISTER_DISP_LEN', 16);
define('RECEIPT_DISP_LEN', 30);
define('SUICA_ADDINFO_LEN', 256);
define('EDY_ADDINFO1_LEN', 180);
define('EDY_ADDINFO2_LEN', 320);
define('RECEIPT_DISP11_LEN', 42);
define('RECEIPT_DISP12_LEN', 4);
define('RECEIPT_DISP13_LEN', 2);
define('CLIENT_FIELD_LEN', 100);
define('RECEIPT_DISP12_TOTAL_LEN', 10);
define('SUICA_ITEM_NAME_LEN', 40);
define('CUSTOMER_NAME_LEN', 40);
define('CUSTOMER_KANA_LEN', 40);
define('TEL_NO_LEN', 13);
define('EMAIL_LEN', 245);
define('EMAIL_ALL_LEN', 256);
define('PAYPAL_ITEM_NAME', 32);

// 支払上限金額
define('CONVENI_RULE_MAX', 299999);
define('SUICA_RULE_MAX', 20000);
define('EDY_RULE_MAX', 50000);
define('CREDIT_RULE_MAX', '');
define('PAYEASY_RULE_MAX', 999999);
define('PAYPAL_RULE_MAX', 999999);
define('WEBMONEY_RULE_MAX', 999999);
define('NETID_RULE_MAX', 999999);
define('AU_RULE_MAX', 9999999);
define('DOCOMO_RULE_MAX', 30000);
define('SB_RULE_MAX', 100000);

//定期購入フラグ
define('MDL_PG_MULPAY_SUBS_STATUS_NEW', '1');      // 新規依頼
define('MDL_PG_MULPAY_SUBS_STATUS_WAIT', '2');      // 次回待機（キャンセル可能）
define('MDL_PG_MULPAY_SUBS_STATUS_WAIT_NOCANCEL', '3');      // 次回待機（キャンセル不可能）
define('MDL_PG_MULPAY_SUBS_STATUS_CANCEL_REQUEST', '4');      // キャンセル依頼中
define('MDL_PG_MULPAY_SUBS_STATUS_CANCEL', '5');      // キャンセル済み
define('MDL_PG_MULPAY_SUBS_STATUS_PENDING', '6');      // 売上処理中
define('MDL_PG_MULPAY_SUBS_STATUS_SALES', '7');      // 売上成功・発送手配中
define('MDL_PG_MULPAY_SUBS_STATUS_SALES_FAIL', '8');      // 売上失敗
define('MDL_PG_MULPAY_SUBS_STATUS_END', '9');       // 終了・完了

// Extended
define('MDL_PG_MULPAY_2CLICK_KEY', '6c20d7b3a05a00dec0c91f96d84fec75a106df10');
define('MDL_PG_MULPAY_REGIST_CARD_NUM', 5);

require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'SC_Mdl_PG_MULPAY_Ex.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'util_extends/SC_Util_PG_MULPAY_Ex.php');

