<?php
/*
 * Copyright(c) 2013 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 *
 * Copyright(c) 2000-2009 LOCKON CO.,LTD. All Rights Reserved.
 * http://www.lockon.co.jp/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');

/**
 * 決済モジュール基本クラス
 */
class SC_Mdl_PG_MULPAY {

    /** サブデータを保持する変数 */
    var $subData = null;

    /** モジュール情報 */
    var $moduleInfo = array(
        'paymentName' => MDL_PG_MULPAY_SERVICE_NAME,
        'moduleName'  => MDL_PG_MULPAY_MODULE_NAME,
        'moduleCode'  => MDL_PG_MULPAY_CODE,
        'moduleVersion' => MDL_PG_MULPAY_VERSION,
    );

    /**
     * テーブル拡張設定.拡張したいテーブル情報を配列で記述する.
     * $updateTable = array(
     *     array(
     *       'name' => 'テーブル名',
     *       'cols' => array(
     *          array('name' => 'カラム名', 'type' => '型名'),
     *          array('name' => 'カラム名', 'type' => '型名'),
     *       ),
     *     ),
     *     array(
     *       ...
     *     ),
     *     array(
     *       ...
     *     ),
     * );
     */
    var $updateTable = array(
        // dtb_paymentの更新
        array(
            'name' => 'dtb_payment',
            'cols'  => array(
                array('name' => 'module_code', 'type' => 'text'),
            ),
        )
    );

    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $updateFile = array();

    /**
     * LC_Mdl_PG_MULPAY:install()を呼んだ際にdtb_moduleのsub_dataカラムへ登録される値
     * シリアライズされて保存される.
     *
     * master_settings => 初期データなど
     * user_settings => 設定情報など、ユーザの入力によるデータ
     */
    var $installSubData = array(
        // 初期データなどを保持する
        'master_settings' => array(
        ),
        // 設定情報など、ユーザの入力によるデータを保持する
        'user_settings' => array(
        ),
    );


    /**
     * コンストラクタ
     *
     * @return void
     */
    function SC_Mdl_PG_MULPAY() {
        $this->updateFile = array(
            array(
                "src" => MDL_PG_MULPAY_SETTLEMENT_FILE,
                "dst" => MDL_PG_MULPAY_SETTLEMENT_PATH
            ),
            array(
                "src" => 'media/loading.gif',
                "dst" => MDL_PG_MULPAY_MEDIAFILE_PATH . 'loading.gif'
            ),
            array(
                "src" => 'media/about_pg.jpg',
                "dst" => MDL_PG_MULPAY_MEDIAFILE_PATH . 'about_pg.jpg'
            ),
            array(
                "src" => 'media/about_pg_on.jpg',
                "dst" => MDL_PG_MULPAY_MEDIAFILE_PATH . 'about_pg_on.jpg'
            ),
            array(
                "src" => 'media/test.jpg',
                "dst" => MDL_PG_MULPAY_MEDIAFILE_PATH . 'test.jpg'
            ),
            array(
                "src" => 'media/test_on.jpg',
                "dst" => MDL_PG_MULPAY_MEDIAFILE_PATH . 'test_on.jpg'
            ),
        );
    }

    /**
     * SC_Mdl_PG_MULPAYのインスタンスを取得する
     *
     * @return SC_Mdl_PG_MULPAY
     */
    function &getInstance() {
        static $_objSC_Mdl_PG_MULPAY;
        if (empty($_objSC_Mdl_PG_MULPAY)) {
            $_objSC_Mdl_PG_MULPAY = new SC_Mdl_PG_MULPAY();
        }
        $_objSC_Mdl_PG_MULPAY->init();
        return $_objSC_Mdl_PG_MULPAY;
    }

    /**
     * 初期化処理.
     */
    function init() {
        foreach ($this->moduleInfo as $k => $v) {
            $this->$k = $v;
        }
    }

    /**
     * 終了処理.
     */
    function destroy() {
    }

    /**
     * モジュール表示用名称を取得する
     *
     * @return string
     */
    function getName() {
        return $this->moduleName;
    }

    /**
     * 支払い方法名(決済モジュールの場合のみ)
     *
     * @return string
     */
    function getPaymentName() {
        return $this->paymentName;
    }

    /**
     * モジュールコードを取得する
     *
     * @param boolean $toLower trueの場合は小文字へ変換する.デフォルトはfalse.
     * @return string
     */
    function getCode($toLower = false) {
        $moduleCode = $this->moduleCode;
        return $toLower ? strtolower($moduleCode) : $moduleCode;
    }

    /**
     * モジュールバージョンを取得する
     *
     * @return string
     */
    function getVersion() {
        return $this->moduleVersion;
    }

    /**
     * サブデータを取得する.
     *
     * @return mixed|null
     */
    function getSubData($key = null) {
        if (isset($this->subData)) {
            if (is_null($key)) {
                return $this->subData;
            } else {
                return $this->subData[$key];
            }
        }

        $moduleCode = $this->getCode(true);
        $objQuery =& SC_Query::getSingletonInstance();
        $ret = $objQuery->get('sub_data', 'dtb_module', 'module_code =?', array($moduleCode));

        if (isset($ret)) {
            $this->subData = unserialize($ret);
            if (is_null($key)) {
                return $this->subData;
            } else {
                return $this->subData[$key];
            }
        }
        return null;
    }

    /**
     * サブデータをDBへ登録する
     * $keyがnullの時は全データを上書きする
     *
     * @param mixed $data
     * @param string $key
     */
    function registerSubData($data, $key = null) {
        $subData = $this->getSubData();

        if (is_null($key)) {
            $subData = $data;
        } else {
            $subData[$key] = $data;
        }

        $arrUpdate = array('sub_data' => serialize($subData));
        $objQuery =& SC_Query::getSingletonInstance();
        $objQuery->update('dtb_module', $arrUpdate, 'module_code = ?', array($this->getCode(true)));

        $this->subData = $subData;
    }

    function getUserSettings($key = null) {
        $subData = $this->getSubData();
        $returnData = null;

        if (is_null($key)) {
            $returnData = isset($subData['user_settings'])
                ? $subData['user_settings']
                : null;
        } else {
            $returnData = isset($subData['user_settings'][$key])
                ? $subData['user_settings'][$key]
                : null;
        }

        return $returnData;
    }

    function registerUserSettings($data) {
        $this->registerSubData($data, 'user_settings');
    }

    /**
     * ログを出力.
     *
     * @param string $msg
     * @param mixed $data
     */
    function printLog($msg, $date = null) {
        $path = DATA_REALDIR . 'logs/' . $this->getCode(true) . '_' .  date('Ymd') .  '.log';
        $text = '';
        if (is_array($msg)) {
            $text = print_r($msg, true);
        } else {
            $text = $msg;
        }
        GC_Utils_Ex::gfPrintLog($text, $path);
    }

    /**
     * インストール処理
     *
     * @param boolean $force true時、上書き登録を行う
     */
    function install($force = false) {
        // カラムの更新
        $this->updateTable();

        $subData = $this->getSubData();
        if (is_null($subData) || $force) {
            $this->registerSubdata(
                $this->installSubData['master_settings'],
                'master_settings'
            );
        }
    }

    /**
     * カラムの更新を行う.
     *
     */
    function updateTable() {
        $objDB = new SC_Helper_DB_Ex();
        foreach ($this->updateTable as $table) {
            foreach($table['cols'] as $col) {
                $objDB->sfColumnExists(
                    $table['name'], $col['name'], $col['type'], "", $add = true
                );
            }
        }
    }

    /**
     * mkdirr 再帰的ディレクトリ作成
     *  refs: http://www.php.net/manual/ja/function.mkdir.php
     * @return boolean true:成功 false:失敗
     */
    function mkdirr($path, $mode) {
        is_dir( dirname($path) ) || $this->mkdirr( dirname($path), $mode );
        return is_dir($path) || @mkdir($path, $mode);
    }

    /**
     * ファイルをコピーする
     *
     * @return boolean
     */
    function updateFile() {
        foreach($this->updateFile as $file) {
            $dst_file = $file['dst'];
            $src_file = MDL_PG_MULPAY_PATH . 'copy/' . $file['src'];
            // ファイルがない、またはファイルはあるが異なる場合
            if (!file_exists($dst_file) || sha1_file($src_file) != sha1_file($dst_file)) {
                if (is_writable($dst_file) || is_writable(dirname($dst_file)) || $this->mkdirr(dirname($dst_file),0777)) {
                    if (file_exists($dst_file)) {
                        // _Exファイルは上書き対象外
                        if (substr($dst_file,-7) == '_Ex.php') {
                            continue;
                        } else {
                            @copy($dst_file, $dst_file . '.bak.' . date('YmdHis'));
                        }
                    }
                    if (!copy($src_file, $dst_file)) {
                        $this->failedCopyFile[] = $dst_file;
                    }
                } else {
                    $this->failedCopyFile[] = $dst_file;
                }
            }
        }
    }

    /**
     * コピーに失敗したファイルを取得する
     *
     * @return array
     */
    function getFailedCopyFile() {
        return $this->failedCopyFile;
    }

}
