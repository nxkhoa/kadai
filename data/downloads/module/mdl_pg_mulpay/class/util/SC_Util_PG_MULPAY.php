<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');

/**
 * 決済モジュール用 汎用関数クラス
 */
class SC_Util_PG_MULPAY {

    function getJobCds($pay_id = MDL_PG_MULPAY_PAYID_CREDIT) {
        $arrJobCds = array(
            'CAPTURE' => '即時売上',
            'AUTH' => '仮売上',
            'SAUTH' => '簡易オーソリ',
            'CHECK' => '有効性チェック'
        );
        if ($pay_id != MDL_PG_MULPAY_PAYID_CREDIT
                && $pay_id != MDL_PG_MULPAY_PAYID_REGIST_CREDIT) { // クレジットカード以外
            unset($arrJobCds['SAUTH']);
            unset($arrJobCds['CHECK']);
        }
        return $arrJobCds;
    }

    function getCreditPayMethod() {
        $arrPayMethod = array(
    '1-0' => '一括払い',
    '2-2' => '分割2回払い',
    '2-3' => '分割3回払い',
    '2-4' => '分割4回払い',
    '2-5' => '分割5回払い',
    '2-6' => '分割6回払い',
    '2-7' => '分割7回払い',
    '2-8' => '分割8回払い',
    '2-9' => '分割9回払い',
    '2-10' => '分割10回払い',
    '2-11' => '分割11回払い',
    '2-12' => '分割12回払い',
    '2-13' => '分割13回払い',
    '2-14' => '分割14回払い',
    '2-15' => '分割15回払い',
    '2-16' => '分割16回払い',
    '2-17' => '分割17回払い',
    '2-18' => '分割18回払い',
    '2-19' => '分割19回払い',
    '2-20' => '分割20回払い',
    '2-21' => '分割21回払い',
    '2-22' => '分割22回払い',
    '2-23' => '分割23回払い',
    '2-24' => '分割24回払い',
    '2-26' => '分割26回払い',
    '2-30' => '分割30回払い',
    '2-32' => '分割32回払い',
    '2-34' => '分割34回払い',
    '2-36' => '分割36回払い',
    '2-37' => '分割37回払い',
    '2-40' => '分割40回払い',
    '2-42' => '分割42回払い',
    '2-48' => '分割48回払い',
    '2-50' => '分割50回払い',
    '2-54' => '分割54回払い',
    '2-60' => '分割60回払い',
    '2-72' => '分割72回払い',
    '2-84' => '分割84回払い',
    '3-0' => 'ボーナス一括',
    '4-2' => 'ボーナス分割2回払い',
    '5-0' => 'リボ払い',
        );
        return $arrPayMethod;
    }

    function getConveni() {
        $arrCONVENI = array(
            CONVENI_LOSON => 'ローソン',
            CONVENI_FAMILYMART => 'ファミリーマート',
//            CONVENI_SUNKUS => 'サンクス',
            CONVENI_CIRCLEK => 'サークルKサンクス',
            CONVENI_MINISTOP => 'ミニストップ',
            CONVENI_DAILYYAMAZAKI => 'デイリーヤマザキ',
            CONVENI_SEVENELEVEN => 'セブンイレブン',
            CONVENI_SEICOMART => 'セイコーマート',
            CONVENI_THREEF => 'スリーエフ',
        );
        return $arrCONVENI;
    }

    function getPayTypeFromPayId($paytype) {
        switch ($paytype) {
            case MDL_PG_MULPAY_PAYID_MOBILESUICA:
                return MULPAY_PAYTYPE_MOBILESUICA;
            case MDL_PG_MULPAY_PAYID_MOBILEEDY:
                return MULPAY_PAYTYPE_MOBILEEDY;
            case MDL_PG_MULPAY_PAYID_CVS:
                return MULPAY_PAYTYPE_CVS;
            case MDL_PG_MULPAY_PAYID_PAYEASY:
                return MULPAY_PAYTYPE_PAYEASY;
            case MDL_PG_MULPAY_PAYID_PAYPAL:
                return MULPAY_PAYTYPE_PAYPAL;
            case MDL_PG_MULPAY_PAYID_IDNET:
                return MULPAY_PAYTYPE_IDNET;
            case MDL_PG_MULPAY_PAYID_WEBMONEY:
                return MULPAY_PAYTYPE_WEBMONEY;
            case MDL_PG_MULPAY_PAYID_AU:
                return MULPAY_PAYTYPE_AU;
            case MDL_PG_MULPAY_PAYID_AUCONTINUANCE:
                return MULPAY_PAYTYPE_AUCONTINUANCE;
            case MDL_PG_MULPAY_PAYID_DOCOMO:
                return MULPAY_PAYTYPE_DOCOMO;
            case MDL_PG_MULPAY_PAYID_DOCOMOCONTINUANCE:
                return MULPAY_PAYTYPE_DOCOMOCONTINUANCE;
            case MDL_PG_MULPAY_PAYID_SB:
                return MULPAY_PAYTYPE_SB;
            case MDL_PG_MULPAY_PAYID_CREDIT:
            case MDL_PG_MULPAY_PAYID_REGIST_CREDIT:
            case MDL_PG_MULPAY_PAYID_CREDIT_CHECK:
            case MDL_PG_MULPAY_PAYID_CREDIT_SAUTH:
            default:
                return MULPAY_PAYTYPE_CREDIT;
        }
    }

    function convCVSText($txt) {
        return mb_convert_kana($txt, 'KASV', 'UTF-8');
    }

    function convTdTenantName($shop_name) {
        if (SC_Utils_Ex::isBlank($shop_name)) return '';
        $shop_name = mb_convert_encoding($shop_name, "EUC-JP", "UTF-8");
        $enc_name = base64_encode($shop_name);
        if (strlen($enc_name) <= 25) {
            return $enc_name;
        }
        return '';
    }

    function installPluginFromPluginInfo($plugin_code, $is_enable = false, $priority = '0') {
        if (file_exists(MDL_PG_MULPAY_PATH . 'plugins/' . $plugin_code . '/plugin_info.php')) {
            $plugin_info_text = file_get_contents(MDL_PG_MULPAY_PATH . 'plugins/' . $plugin_code . '/plugin_info.php');
            $plugin_info_text = str_replace('plugin_info', 'plugin_info_' . $plugin_code, $plugin_info_text);
            $plugin_info_text = str_replace(array('<?php', '?>'), '', $plugin_info_text);
            eval($plugin_info_text);
            $objReflection = new ReflectionClass('plugin_info_' . $plugin_code);
            $arrPluginInfo = SC_Util_PG_MULPAY_Ex::getPluginInfo($objReflection);
            return SC_Util_PG_MULPAY_Ex::installPlugin($arrPluginInfo, $is_enable, $priority);
        }
        return false;
    }

    /**
     * プラグイン情報を取得します.
     *
     * @param ReflectionClass $objReflection
     * @return array プラグイン情報の配列
     */
    function getPluginInfo(ReflectionClass $objReflection) {
        $arrStaticProps = $objReflection->getStaticProperties();
        $arrConstants   = $objReflection->getConstants();

        $arrPluginInfoKey = array(
            'PLUGIN_CODE',
            'PLUGIN_NAME',
            'CLASS_NAME',
            'PLUGIN_VERSION',
            'COMPLIANT_VERSION',
            'AUTHOR',
            'DESCRIPTION',
            'PLUGIN_SITE_URL',
            'AUTHOR_SITE_URL',
            'HOOK_POINTS',
        );
        $arrPluginInfo = array();
        foreach ($arrPluginInfoKey as $key) {
            // クラス変数での定義を優先
            if (isset($arrStaticProps[$key])) {
                $arrPluginInfo[$key] = $arrStaticProps[$key];
            // クラス変数定義がなければ, クラス定数での定義を読み込み.
            } elseif ($arrConstants[$key]) {
                $arrPluginInfo[$key] = $arrConstants[$key];
            } else {
                $arrPluginInfo[$key] = null;
            }
        }
        return $arrPluginInfo;
    }

    function installPlugin($arrPluginInfo, $is_enable = false, $plugin_priority = '0') {
        // プラグインコード
        $plugin_code = $arrPluginInfo['PLUGIN_CODE'];
        // プラグイン名
        $plugin_name = $arrPluginInfo['PLUGIN_NAME'];

        $plugin_id = SC_Util_PG_MULPAY_Ex::getPluginId($plugin_code);
        $plugin_id = SC_Util_PG_MULPAY_Ex::registerPluginData($plugin_id, $arrPluginInfo, $is_enable, $plugin_priority);

        $plugin_dir_path = PLUGIN_UPLOAD_REALDIR . $plugin_code . '/';
        $plugin_html_dir = PLUGIN_HTML_REALDIR . $plugin_code;
        if (!file_exists(PLUGIN_UPLOAD_REALDIR)) {
            mkdir(PLUGIN_UPLOAD_REALDIR, 0777);
        }
        if (!file_exists($plugin_dir_path)) {
            mkdir($plugin_dir_path, 0777);
        }
        SC_Utils_Ex::copyDirectory(MDL_PG_MULPAY_PATH . 'plugins/' . $plugin_code . '/', $plugin_dir_path);

        $plugin = SC_Plugin_Util_Ex::getPluginByPluginId($plugin_id);
        $plugin_class_file_path = $plugin_dir_path . $plugin['class_name'] . '.php';
        require_once($plugin_class_file_path);

        if (!file_exists(PLUGIN_HTML_REALDIR)) {
            mkdir(PLUGIN_HTML_REALDIR, 0777);
        }
        if (!file_exists($plugin_html_dir)) {
            mkdir($plugin_html_dir, 0777);
        }

        if (method_exists($plugin['class_name'], 'install') === true) {
            call_user_func(array($plugin['class_name'], 'install'), $plugin);
        }

        if ($is_enable && method_exists($plugin['class_name'], 'enable') === true) {
            call_user_func(array($plugin['class_name'], 'enable'), $plugin);
        }

        return $plugin_id;
    }

    /**
     * プラグイン情報をDB登録.
     *
     * @param array $arrPluginInfo プラグイン情報を格納した連想配列.
     * @return array エラー情報を格納した連想配列.
     */
    function registerPluginData($plugin_id, $arrPluginInfo, $is_enable = false, $priority = '0') {
        // プラグイン情報をDB登録.
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $arr_sqlval_plugin = array();
        $arr_sqlval_plugin['plugin_name'] = $arrPluginInfo['PLUGIN_NAME'];
        $arr_sqlval_plugin['plugin_code'] = $arrPluginInfo['PLUGIN_CODE'];
        $arr_sqlval_plugin['class_name'] = $arrPluginInfo['CLASS_NAME'];
        $arr_sqlval_plugin['author'] = $arrPluginInfo['AUTHOR'];
        // AUTHOR_SITE_URLが定義されているか判定.
        $author_site_url = $arrPluginInfo['AUTHOR_SITE_URL'];
        if ($author_site_url !== null) {
            $arr_sqlval_plugin['author_site_url'] = $arrPluginInfo['AUTHOR'];
        }
        // PLUGIN_SITE_URLが定義されているか判定.
        $plugin_site_url = $arrPluginInfo['PLUGIN_SITE_URL'];
        if ($plugin_site_url !== null) {
            $arr_sqlval_plugin['plugin_site_url'] = $plugin_site_url;
        }
        $arr_sqlval_plugin['plugin_version'] = $arrPluginInfo['PLUGIN_VERSION'];
        $arr_sqlval_plugin['compliant_version'] = $arrPluginInfo['COMPLIANT_VERSION'];
        $arr_sqlval_plugin['plugin_description'] = $arrPluginInfo['DESCRIPTION'];
        $arr_sqlval_plugin['priority'] = $priority;
        $arr_sqlval_plugin['enable'] = $is_enable ? PLUGIN_ENABLE_TRUE : PLUGIN_ENABLE_FALSE;
        $arr_sqlval_plugin['update_date'] = 'CURRENT_TIMESTAMP';

        if (SC_Utils_Ex::isBlank($plugin_id)) {
            $plugin_id = $objQuery->nextVal('dtb_plugin_plugin_id');
            $arr_sqlval_plugin['plugin_id'] = $plugin_id;
            $objQuery->insert('dtb_plugin', $arr_sqlval_plugin);
        } else {
            $objQuery->update('dtb_plugin', $arr_sqlval_plugin, 'plugin_id = ?', array($plugin_id));
        }

        // フックポイントをDB登録.
        $hook_point = $arrPluginInfo['HOOK_POINTS'];
        if ($hook_point !== null) {
            // 一回削除する
            $objQuery->delete('dtb_plugin_hookpoint', 'plugin_id = ?', array($plugin_id));
            // フックポイントが配列で定義されている場合
            if (is_array($hook_point)) {
                foreach ($hook_point as $h) {
                    $arr_sqlval_plugin_hookpoint = array();
                    $id = $objQuery->nextVal('dtb_plugin_hookpoint_plugin_hookpoint_id');
                    $arr_sqlval_plugin_hookpoint['plugin_hookpoint_id'] = $id;
                    $arr_sqlval_plugin_hookpoint['plugin_id'] = $plugin_id;
                    $arr_sqlval_plugin_hookpoint['hook_point'] = $h[0];
                    $arr_sqlval_plugin_hookpoint['callback'] = $h[1];
                    $arr_sqlval_plugin_hookpoint['update_date'] = 'CURRENT_TIMESTAMP';
                    $objQuery->insert('dtb_plugin_hookpoint', $arr_sqlval_plugin_hookpoint);
                }
            // 文字列定義の場合
            } else {
                $array_hook_point = explode(',', $hook_point);
                foreach ($array_hook_point as $h) {
                    $arr_sqlval_plugin_hookpoint = array();
                    $id = $objQuery->nextVal('dtb_plugin_hookpoint_plugin_hookpoint_id');
                    $arr_sqlval_plugin_hookpoint['plugin_hookpoint_id'] = $id;
                    $arr_sqlval_plugin_hookpoint['plugin_id'] = $plugin_id;
                    $arr_sqlval_plugin_hookpoint['hook_point'] = $h;
                    $arr_sqlval_plugin_hookpoint['update_date'] = 'CURRENT_TIMESTAMP';
                    $objQuery->insert('dtb_plugin_hookpoint', $arr_sqlval_plugin_hookpoint);
                }
            }
        }
        $objQuery->commit();
        return $plugin_id;
    }

    /**
     * 既にインストールされているプラグインのIDを取得します。
     *
     * @param string $plugin_code プラグインコード
     * @return integer インストール済の場合 plugin_id インストールされていない場合false
     */
    function getPluginId($plugin_code) {
        $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode($plugin_code);
        if (!empty($plugin)) {
            return $plugin['plugin_id'];
        }
        return false;
    }

    function setBlocData($plugin_id, $bloc_data, $device_type_id, $bloc_name, $filename, $php_path = "", $is_force = false) {
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        $bloc_dir = $objLayout->getTemplatePath($device_type_id) . BLOC_DIR;
        $tpl_path = $filename . '.tpl';

        $where = 'filename = ?';
        $arrval = array($filename);

        $arrExists = $objLayout->getBlocs($device_type_id, $where, $arrval);
        $exists_file = $bloc_dir . $arrExists[0]['filename'] . '.tpl';
        if (file_exists($exists_file)) {
            if ($is_force) {
                @copy($exists_file, $exists_file . '.bak.' . date('YmdHis'));
                unlink($exists_file);
            } else {
                return $arrExists[0]['bloc_id'];
            }
        }

        $sqlval_bloc = array();
        $sqlval_bloc['device_type_id'] = $device_type_id;
        $sqlval_bloc['bloc_name'] = $bloc_name;
        $sqlval_bloc['tpl_path'] = $filename . '.tpl';
        $sqlval_bloc['filename'] = $filename;
        $sqlval_bloc['update_date'] = "CURRENT_TIMESTAMP";
        if (!SC_Utils_Ex::isBlank($php_path)) {
            $sqlval_bloc['php_path'] = $php_path;
        }
        $sqlval_bloc['deletable_flg'] = 0;
        $sqlval_bloc['plugin_id'] = $plugin_id;
        $objQuery->setOrder('');

        if (SC_Utils_Ex::isBlank($arrExists[0]['bloc_id'])) {
            $sqlval_bloc['bloc_id'] = $objQuery->max('bloc_id', "dtb_bloc", "device_type_id = ?", array($device_type_id)) + 1;
            $sqlval_bloc['create_date'] = "CURRENT_TIMESTAMP";
            $objQuery->insert("dtb_bloc", $sqlval_bloc);
            $bloc_id = $sqlval_bloc['bloc_id'];
        } else {
            $objQuery->update("dtb_bloc", $sqlval_bloc, "device_type_id = ? AND bloc_id = ?", array($device_type_id, $arrExists[0]['bloc_id']));
            $bloc_id = $arrExists[0]['bloc_id'];
        }

        $bloc_path = $bloc_dir . $tpl_path;
        if (!SC_Helper_FileManager_Ex::sfWriteFile($bloc_path, $bloc_data)) {
            $objQuery->rollback();
            return false;
        }

        $objQuery->commit();
        return $bloc_id;
    }

    function setPageData($tpl_data, $page_name, $url, $filename, $device_type_id, $is_force = false) {
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        $tpl_dir = $objLayout->getTemplatePath($device_type_id);
        $tpl_path = $filename . '.tpl';

        $arrExists = $objLayout->getPageProperties($device_type_id, null, 'device_type_id = ? and filename = ?', array($device_type_id, $filename));

        $exists_file = $tpl_dir . $arrExists[0]['filename'] . '.tpl';
        if (file_exists($exists_file)) {
            if ($is_force) {
                @copy($exists_file, $exists_file . '.bak.' . date('YmdHis'));
                unlink($exists_file);
            } else {
                return $arrExists[0]['page_id'];
            }
        }
        $table = 'dtb_pagelayout';
        $arrValues = array();
        $arrValues['device_type_id'] = $device_type_id;
        $arrValues['header_chk'] = 1;
        $arrValues['footer_chk'] = 1;
        $arrValues['page_name'] = $page_name;
        $arrValues['url'] = $url;
        $arrValues['filename'] = $filename;
        $arrValues['edit_flg'] = '2';
        $arrValues['update_url'] = $_SERVER['HTTP_REFERER'];
        $arrValues['update_date'] = 'CURRENT_TIMESTAMP';
        $objQuery->setOrder('');
        if (SC_Utils_Ex::isBlank($arrExists[0]['page_id'])) {
            $arrValues['page_id'] = 1 + $objQuery->max('page_id', $table, 'device_type_id = ?',
                                                       array($arrValues['device_type_id']));
            $arrValues['create_date'] = 'CURRENT_TIMESTAMP';
            $objQuery->insert($table, $arrValues);
            $page_id = $arrValues['page_id'];
        } else {
            $objQuery->update($table, $arrValues, 'page_id = ? AND device_type_id = ?',
                                                       array($arrExists[0]['page_id'], $arrValues['device_type_id']));
            $page_id = $arrExists[0]['page_id'];
        }

        $tpl_path = $tpl_dir . $filename . '.tpl';

        if (!SC_Helper_FileManager_Ex::sfWriteFile($tpl_path, $tpl_data)) {
            $objQuery->rollback();
            return false;
        }
        $objQuery->commit();
        return $page_id;
    }

    function setOrderPayData($arrOrder, $arrData, $is_only_log = false) {
        if(isset($arrData[0]) and is_array($arrData[0])) {
            $arrTemp = $arrData[0];
            unset($arrData[0]);
            $arrData = array_merge((array)$arrData, (array)$arrTemp);
        }
        foreach ($arrData as $key => $val) {
            if (!$val || is_array($val) || preg_match('/^[\w\s]+$/i', $val)) {
                continue;
            }
            $temp = mb_convert_encoding($val, 'sjis-win', CHAR_CODE);
            $temp = mb_convert_encoding($temp, CHAR_CODE, 'sjis-win');
            if ($val !== $temp) {
                $temp = mb_convert_encoding($val, CHAR_CODE, 'sjis-win');
                $temp = mb_convert_encoding($temp, 'sjis-win', CHAR_CODE);
                if ($val === $temp) {
                    $arrData[$key] = mb_convert_encoding($val, CHAR_CODE, 'sjis-win');
                } else {
                    $arrData[$key] = 'unknown encoding strings';
                }
            }
        }

        $objPurchase = new SC_Helper_Purchase_Ex();

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $sqlval = array();

        $arrOrder = $objPurchase->getOrder($arrOrder['order_id']);
        if (SC_Utils_Ex::isBlank($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYLOG])) {
            $arrLog = array();
        } else {
            $arrLog = unserialize($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYLOG]);
        }
        $arrLog[] = array( date('Y-m-d H:i:s') => $arrData );
        $sqlval[MDL_PG_MULPAY_ORDER_COL_PAYLOG] = serialize($arrLog);

        if (!$is_only_log) {
            if (SC_Utils_Ex::isBlank($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYDATA])) {
                $arrPayData = array();
            } else {
                $arrPayData = unserialize($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYDATA]);
            }

            foreach ($arrData as $key => $val) {
                if (SC_Utils_Ex::isBlank($val) && !SC_Utils_Ex::isBlank($arrPayData[$key])) {
                    unset($arrData[$key]);
                }
            }

            $arrPayData = array_merge($arrPayData, (array)$arrData);

            $sqlval[MDL_PG_MULPAY_ORDER_COL_PAYDATA] = serialize($arrPayData);

            if (!SC_Utils_Ex::isBlank($arrData['pay_status'])) {
                $sqlval[MDL_PG_MULPAY_ORDER_COL_PAYSTATUS] = $arrData['pay_status'];
            }
        }

        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        if (MDL_PG_MULPAY_DEBUG) {
            $arrBacktrace = debug_backtrace();
            if (is_object($arrBacktrace[0]['object'])) {
                $class_name = get_class($arrBacktrace[0]['object']);
                $parent_class_name = get_parent_class($arrBacktrace[0]['object']);
                $class_msg = $parent_class_name . ' -> ' . $class_name . ' -> ';
            }
        } else {
            $class_msg = basename($_SERVER["SCRIPT_NAME"]);
        }
        $objMdl->printLog($class_msg . ' set payment data:' . print_r($arrData, true));

        $newStatus = null;
        $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $newStatus, null, null, $sqlval);

        $objQuery->commit();
    }

    function getOrderPayData($order_id) {
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrOrder = $objPurchase->getOrder($order_id);
        if ($arrOrder['del_flg'] == '1') {
            $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
            $objMdl->printLog('getOrderPayData Error: deleted order. order_id = ' . $order_id);
            return false;
        }
        if (SC_Utils_Ex::isBlank($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYDATA])) {
            $arrPayData = array();
        } else {
            $arrPayData = unserialize($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYDATA]);
        }
        if (SC_Utils_Ex::isBlank($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYLOG])) {
            $arrPayData['payment_log'] = array();
        } else {
            $arrPayData['payment_log'] = unserialize($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYLOG]);
        }
        if(isset($arrPayData[0]) and is_array($arrPayData[0])) {
            $arrTemp = $arrPayData[0];
            unset($arrPayData[0]);
            $arrData = array_merge((array)$arrData, (array)$arrTemp);
        }
        $arrOrder = array_merge($arrOrder, (array)$arrPayData);
        return $arrOrder;
    }

    /**
     * 支払方法情報を取得する
     *
     * @param integer $payment_id 支払いID
     * @return array 支払方法情報。決済モジュール管理対象である場合、内部識別コードを同時に設定する
     */
    function getPaymentInfo($payment_id) {
        $objMdl =& SC_Mdl_PG_MULPAY::getInstance();
        $objQuery =& SC_Query::getSingletonInstance();

        $arrData = $objQuery->getRow('*', 'dtb_payment', 'payment_id = ? and module_code = ?', array($payment_id, $objMdl->getCode(true)));
        if (SC_Utils_Ex::isBlank($arrData)) {
            return false;
        }

        // 決済モジュールの対象決済であるかの判断と内部識別コードの設定を同時に行う。
        $arrPaymentCode = SC_Util_PG_MULPAY_Ex::getPaymentTypeCodes();
        $arrData[MDL_PG_MULPAY_CODE . '_payment_code'] = $arrPaymentCode[$arrData[MDL_PG_MULPAY_PAYMENT_COL_PAYID]];
        return $arrData;
    }

    function getPaymentTypeConfig($payment_id) {
        $arrData = SC_Util_PG_MULPAY_Ex::getPaymentInfo($payment_id);
        if (!SC_Utils_Ex::isBlank($arrData[MDL_PG_MULPAY_PAYMENT_COL_CONFIG])) {
            $arrTemp = unserialize($arrData[MDL_PG_MULPAY_PAYMENT_COL_CONFIG]);
            if ($arrTemp !== false) {
                $arrData = array_merge($arrData, unserialize($arrData[MDL_PG_MULPAY_PAYMENT_COL_CONFIG]));
            } else {
                SC_Util_PG_MULPAY_Ex::printLog('bronken config dtb_payment.' . MDL_PG_MULPAY_PAYMENT_COL_CONFIG . ' payment_id = ' . $payment_id);
            }
        }
        return $arrData;
    }

    function getMulpayPayments() {
        $objMdl =& SC_Mdl_PG_MULPAY::getInstance();
        $objQuery =& SC_Query::getSingletonInstance();
        $arrVal = array($objMdl->getCode(true));
        $objQuery->setOrder('rank desc');
        return $objQuery->select('*', 'dtb_payment', 'module_code = ? AND del_flg = 0', $arrVal);
    }

    function isRegistCardPaymentEnable() {
        $objMdl =& SC_Mdl_PG_MULPAY::getInstance();
        $objQuery =& SC_Query::getSingletonInstance();
        $arrVal = array($objMdl->getCode(true), (string)MDL_PG_MULPAY_PAYID_REGIST_CREDIT);
        if ($objQuery->count("dtb_payment", "module_code = ? AND " . MDL_PG_MULPAY_PAYMENT_COL_PAYID . " = ? AND del_flg = 0", $arrVal)) {
            return true;
        }
        return false;

    }

    function setPaymentTypeConfig($payment_id, $arrData) {
        if (SC_Utils_Ex::isBlank($arrData)) {
            $arrData = array();
        }
        SC_Util_PG_MULPAY_Ex::printLog('set paymentTypeConfig payment_id:' . $payment_id);
        SC_Util_PG_MULPAY_Ex::printLog($arrData);
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objMdl =& SC_Mdl_PG_MULPAY::getInstance();
        $arrVal = array(MDL_PG_MULPAY_PAYMENT_COL_CONFIG => serialize($arrData));
        $objQuery->update('dtb_payment', $arrVal, 'payment_id = ? AND module_code = ?', array($payment_id, $objMdl->getCode(true)));
    }

    function getPaymentStatus() {
        $arrRet = array(
                    MDL_PG_MULPAY_PAY_STATUS_UNSETTLED => '未決済',
                    MDL_PG_MULPAY_PAY_STATUS_REQUEST_SUCCESS => '要求成功',
                    MDL_PG_MULPAY_PAY_STATUS_PAY_SUCCESS => '支払い完了',
                    MDL_PG_MULPAY_PAY_STATUS_EXPIRE => '期限切れ',
                    MDL_PG_MULPAY_PAY_STATUS_CANCEL => 'キャンセル',
                    MDL_PG_MULPAY_PAY_STATUS_FAIL => '決済失敗',
                    MDL_PG_MULPAY_PAY_STATUS_AUTH => '仮売上済み',
                    MDL_PG_MULPAY_PAY_STATUS_COMMIT => '実売上済み',
                    MDL_PG_MULPAY_PAY_STATUS_SALES => '実売上済み',
                    MDL_PG_MULPAY_PAY_STATUS_CAPTURE => '即時売上済み',
                    MDL_PG_MULPAY_PAY_STATUS_VOID => '取消済み',
                    MDL_PG_MULPAY_PAY_STATUS_RETURN => '返品済み',
                    MDL_PG_MULPAY_PAY_STATUS_RETURNX => '月跨ぎ返品済み',
//                    MDL_PG_MULPAY_PAY_STATUS_SAUTH => '簡易オーソリ済み',
//                    MDL_PG_MULPAY_PAY_STATUS_CHECK => '有効性チェック済み',
                    MDL_PG_MULPAY_PAY_STATUS_EXCEPT => '例外エラー',
                );
        if (SC_Util_PG_MULPAY_Ex::isPluginEnable('PgCarrierSubs')) {
            $arrRet[MDL_PG_MULPAY_PAY_STATUS_REGISTER] = '継続課金登録済';
        }
        return $arrRet;
    }

    /**
     * 決済モジュールで利用出来る決済方式の名前一覧を取得する
     *
     * @param integer $payment_id 支払いID
     * @return array 支払方法
     */
    function getPaymentTypeNames() {
        $arrRet = array(
                    MDL_PG_MULPAY_PAYID_CREDIT => MDL_PG_MULPAY_PAYNAME_CREDIT,
                    MDL_PG_MULPAY_PAYID_REGIST_CREDIT => MDL_PG_MULPAY_PAYNAME_REGIST_CREDIT,
//                    MDL_PG_MULPAY_PAYID_CREDIT_CHECK => MDL_PG_MULPAY_PAYNAME_CREDIT_CHECK,
//                    MDL_PG_MULPAY_PAYID_CREDIT_SAUTH => MDL_PG_MULPAY_PAYNAME_CREDIT_SAUTH,
                    MDL_PG_MULPAY_PAYID_CVS => MDL_PG_MULPAY_PAYNAME_CVS,
                    MDL_PG_MULPAY_PAYID_PAYEASY => MDL_PG_MULPAY_PAYNAME_PAYEASY,
                    MDL_PG_MULPAY_PAYID_ATM => MDL_PG_MULPAY_PAYNAME_ATM,
                    MDL_PG_MULPAY_PAYID_MOBILEEDY => MDL_PG_MULPAY_PAYNAME_MOBILEEDY,
                    MDL_PG_MULPAY_PAYID_MOBILESUICA =>  MDL_PG_MULPAY_PAYNAME_MOBILESUICA,
                    MDL_PG_MULPAY_PAYID_PAYPAL => MDL_PG_MULPAY_PAYNAME_PAYPAL,
                    MDL_PG_MULPAY_PAYID_IDNET => MDL_PG_MULPAY_PAYNAME_IDNET,
                    MDL_PG_MULPAY_PAYID_WEBMONEY => MDL_PG_MULPAY_PAYNAME_WEBMONEY,
                    MDL_PG_MULPAY_PAYID_AU => MDL_PG_MULPAY_PAYNAME_AU,
                    MDL_PG_MULPAY_PAYID_DOCOMO => MDL_PG_MULPAY_PAYNAME_DOCOMO,
                    MDL_PG_MULPAY_PAYID_SB => MDL_PG_MULPAY_PAYNAME_SB,
                    MDL_PG_MULPAY_PAYID_COLLECT => MDL_PG_MULPAY_PAYNAME_COLLECT,
                );

        if (SC_Util_PG_MULPAY_Ex::isPluginEnable('PgCarrierSubs')) {
            $arrRet[MDL_PG_MULPAY_PAYID_AUCONTINUANCE] = MDL_PG_MULPAY_PAYNAME_AUCONTINUANCE;
            $arrRet[MDL_PG_MULPAY_PAYID_DOCOMOCONTINUANCE] = MDL_PG_MULPAY_PAYNAME_DOCOMOCONTINUANCE;
        }
        return $arrRet;
    }

    /**
     * 決済モジュールで利用出来る決済方式の内部名一覧を取得する
     *
     * @return array 支払方法コード
     */
    function getPaymentTypeCodes() {
        return array(
                    MDL_PG_MULPAY_PAYID_CREDIT => MDL_PG_MULPAY_PAYCODE_CREDIT,
                    MDL_PG_MULPAY_PAYID_REGIST_CREDIT => MDL_PG_MULPAY_PAYCODE_REGIST_CREDIT,
                    MDL_PG_MULPAY_PAYID_CREDIT_CHECK => MDL_PG_MULPAY_PAYCODE_CREDIT_CHECK,
                    MDL_PG_MULPAY_PAYID_CREDIT_SAUTH => MDL_PG_MULPAY_PAYCODE_CREDIT_SAUTH,
                    MDL_PG_MULPAY_PAYID_CVS => MDL_PG_MULPAY_PAYCODE_CVS,
                    MDL_PG_MULPAY_PAYID_PAYEASY => MDL_PG_MULPAY_PAYCODE_PAYEASY,
                    MDL_PG_MULPAY_PAYID_ATM => MDL_PG_MULPAY_PAYCODE_ATM,
                    MDL_PG_MULPAY_PAYID_MOBILEEDY => MDL_PG_MULPAY_PAYCODE_MOBILEEDY,
                    MDL_PG_MULPAY_PAYID_MOBILESUICA =>  MDL_PG_MULPAY_PAYCODE_MOBILESUICA,
                    MDL_PG_MULPAY_PAYID_PAYPAL => MDL_PG_MULPAY_PAYCODE_PAYPAL,
                    MDL_PG_MULPAY_PAYID_IDNET => MDL_PG_MULPAY_PAYCODE_IDNET,
                    MDL_PG_MULPAY_PAYID_WEBMONEY => MDL_PG_MULPAY_PAYCODE_WEBMONEY,
                    MDL_PG_MULPAY_PAYID_AU => MDL_PG_MULPAY_PAYCODE_AU,
                    MDL_PG_MULPAY_PAYID_DOCOMO => MDL_PG_MULPAY_PAYCODE_DOCOMO,
                    MDL_PG_MULPAY_PAYID_SB => MDL_PG_MULPAY_PAYCODE_SB,
                    MDL_PG_MULPAY_PAYID_COLLECT => MDL_PG_MULPAY_PAYCODE_COLLECT,

                    MDL_PG_MULPAY_PAYID_AUCONTINUANCE => MDL_PG_MULPAY_PAYCODE_AUCONTINUANCE,
                    MDL_PG_MULPAY_PAYID_DOCOMOCONTINUANCE => MDL_PG_MULPAY_PAYCODE_DOCOMOCONTINUANCE,
                );

    }

    /**
     * 禁止文字か判定を行う。
     *
     * @param string $value 判定対象
     * @return boolean 結果
     */
    function isProhibitedChar($value) {
        $check_char = mb_convert_encoding($value, "SJIS-win", "UTF-8");
        if (hexdec('8740') <= hexdec(bin2hex($check_char)) && hexdec('879E') >= hexdec(bin2hex($check_char))) {
            return true;
        }
        if ((hexdec('ED40') <= hexdec(bin2hex($check_char)) && hexdec('ED9E') >= hexdec(bin2hex($check_char))) ||
            (hexdec('ED9F') <= hexdec(bin2hex($check_char)) && hexdec('EDFC') >= hexdec(bin2hex($check_char))) ||
            (hexdec('EE40') <= hexdec(bin2hex($check_char)) && hexdec('EE9E') >= hexdec(bin2hex($check_char))) ||
            (hexdec('FA40') <= hexdec(bin2hex($check_char)) && hexdec('FA9E') >= hexdec(bin2hex($check_char))) ||
            (hexdec('FA9F') <= hexdec(bin2hex($check_char)) && hexdec('FAFC') >= hexdec(bin2hex($check_char))) ||
            (hexdec('FB40') <= hexdec(bin2hex($check_char)) && hexdec('FB9E') >= hexdec(bin2hex($check_char))) ||
            (hexdec('FB9F') <= hexdec(bin2hex($check_char)) && hexdec('FBFC') >= hexdec(bin2hex($check_char))) ||
            (hexdec('FC40') <= hexdec(bin2hex($check_char)) && hexdec('FC4B') >= hexdec(bin2hex($check_char)))){
            return true;
        }
        if ((hexdec('EE9F') <= hexdec(bin2hex($check_char)) && hexdec('EEFC') >= hexdec(bin2hex($check_char))) ||
            (hexdec('F040') <= hexdec(bin2hex($check_char)) && hexdec('F9FC') >= hexdec(bin2hex($check_char)))) {
            return true;
        }

        return false;
    }

    /**
     * 禁止文字を全角スペースに置換する。
     *
     * @param string $value 対象文字列
     * @return string 結果
     */
    function convertProhibitedChar($value) {
        $ret = $value;
        for ($i = 0; $i < mb_strlen($value); $i++) {
            $tmp = mb_substr($value, $i , 1);
            if (SC_Util_PG_MULPAY_Ex::isProhibitedChar($tmp)) {
               $ret = str_replace($tmp, "　", $value);
            }
        }
        return $ret;
    }

    /**
     * 禁止半角記号を半角スペースに変換する。
     *
     * @param string $value
     * @return string 変換した値
     */
    function convertProhibitedKigo($value) {
        global $arrProhiditedKigo;
        foreach ($arrProhiditedKigo as $prohidited_kigo) {
            if(strstr($value, $prohidited_kigo)) {
                $value = str_replace($prohidited_kigo, " ", $value);
            }
        }
        return $value;
    }

    /**
     * 文字列から指定バイト数を切り出す。
     *
     * @param string $value
     * @param integer $len
     * @return string 結果
     */
    function subString($value, $len) {
        $value = mb_convert_encoding($value, "SJIS", "UTF-8");
        for ($i = 1; $i <= mb_strlen($value); $i++) {
            $tmp = mb_substr($value, 0 , $i);
            if (strlen($tmp) <= $len) {
                $ret = mb_convert_encoding($tmp, "UTF-8", "SJIS");
            } else {
                break;
            }
        }
        return $ret;
    }

    /**
     * 日付をISO8601形式にフォーマットする
     *
     * @param string $date
     * @return string ISO8601 format date
     **/
    function formatISO8601($date) {
        $n = sscanf($date, '%4s%2s%2s%2s%2s%2s', $year, $month, $day, $hour, $min, $sec);
        return sprintf('%s-%s-%s %s:%s:%s', $year, $month, $day, $hour, $min, $sec);
    }

    /**
     * 配列データからログに記録しないデータをマスクする
     *
     * @param array $arrData
     * @return array マスク後データ
     */
    function setMaskData($arrData) {
        foreach ($arrData as $key => $val) {
            switch($key) {
                case 'CardNo':
                    $arrData[$key] = str_repeat('*', 13) . substr($val,-3);
                    break;
                case 'SecurityCode':
                    $arrData[$key] = str_repeat('*', 4);
                    break;
                case 'MemberName':
                case 'CustomerName':
                case 'CustomerKana':
                case 'ShopPass':
                case 'SitePass':
                case 'MemberName':
                case 'MailAddress':
                    $arrData[$key] = str_repeat('*', 6);
                    break;
                default:
                    break;
            }
        }
        return $arrData;
    }

    function printLog($msg) {
        if (is_array($msg) || is_object($msg)) {
            $msg = print_r($msg,true);
        }
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $objMdl->printLog($msg);
    }

    function isPluginEnable($plugin_code) {
        $arrPlugin = SC_Plugin_Util_Ex::getPluginByPluginCode($plugin_code);
        if ($arrPlugin['enable'] == '1') {
            return true;
        } else {
            return false;
        }
    }

    function getPluginConfig($plugin_code) {
        $arrPlugin = SC_Plugin_Util_Ex::getPluginByPluginCode($plugin_code);
        if ($arrPlugin['enable'] != '1') {
            return false;
        }
        if ($arrPlugin['free_field1'] != "") {
            $arrConfig = unserialize($arrPlugin['free_field1']);
        }
        return $arrConfig;
    }

}

