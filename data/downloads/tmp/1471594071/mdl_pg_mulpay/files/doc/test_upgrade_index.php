<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

// {{{ requires
require_once '../../require.php';
require_once DATA_REALDIR . 'module/Services/JSON.php';

/**
 * モジュール一覧を出力するテストスクリプト
 *
 * 新しいモジュールを管理画面に表示する場合は、下の配列の値を書き換える.
 * 追加する際には、新しく配列を追加すればよい.
 *
 * installed_flgとdownload_flgを1にすると「設定」ボタンが出るので、
 * data/downloads/module/mdl_***にモジュールが設置してあれば、
 * ダウンロードしなくてもすぐに使用可能な状態となります.
 * (codeがモジュールファイル設定先になります)
 *
 * product_idは重複しない値を設定してください.
 * 本番商品との重複を避けるためにも大きめの値を設定しておくとよいかもしれません.
 */
$GLOBALS['productsList'] = array(
    // サンプルモジュール

    array(
        'name' => 'GMO-PG決済モジュール(PGマルチペイメントサービス)',
    'code' => 'mdl_pg_mulpay',
        'main_list_comment' => '',
        //'main_list_image' => 'gmopg.gif',
        'main_list_image' => '01151935_496f1161879e6.gif',
        'version' => '4.3.2',
        'last_update_date' => '2015/07/01 00:00:00',
        'product_id' => '101001',
        'status' => 'SUCCESS',
        'installed_flg' => '1',
        'installed_version' => '4.3.2',
        'download_flg' => '1',
        'version_up_flg' => '0'
    )
);

switch(getMode()) {

case 'products_list':
    displayProductsList();
    break;

default:
    displayProductsList();
    break;
}

/**
 * モード取得.
 *
 * @return string
 */
function getMode() {
    if (isset($_GET['mode'])) {
        return $_GET['mode'];
    } elseif (isset($_POST['mode'])) {
        return $_POST['mode'];
    }
    return '';
}

/**
 * モジュールリスト一覧をjson出力する
 *
 */
function displayProductsList() {
    $arrRet = array(
        'status' => 'SUCCESS',
        'data'   => $GLOBALS['productsList']
    );

    // FIXME 一覧を取得するたびに更新されるのは微妙かも..
    updateModuleTable($GLOBALS['productsList']);

    $objJson = new Services_JSON();
    echo $objJson->encode($arrRet);
}

/**
 * dtb_moduleを更新する.
 *
 * @param array $arrProductsList
 */
function updateModuleTable($arrProductsList) {
    $table = 'dtb_module';
    $where = 'module_id = ?';
    $objQuery =& SC_Query_Ex::getSingletonInstance();

    $objQuery->begin();
    foreach ($arrProductsList as $arrProduct) {
        $count = $objQuery->count($table, $where, array($arrProduct['product_id']));
        if ($count) {
            $arrUpdate = array(
                'module_code' => $arrProduct['code'],
                'module_name' => $arrProduct['name'],
                'auto_update_flg' => '0',
                'del_flg' => '0',
                'update_date' => 'NOW()',
            );
            $objQuery->update($table, $arrUpdate, $where, array($arrProduct['product_id']));
        } else {
            $arrInsert = array(
                'module_id'   => $arrProduct['product_id'],
                'module_code' => $arrProduct['code'],
                'module_name' => $arrProduct['name'],
                'auto_update_flg' => '0',
                'del_flg' => '0',
                'update_date' => 'NOW()',
                'create_date' => 'NOW()',
            );
            $objQuery->insert($table, $arrInsert);
        }
    }
    $objQuery->commit();
}
?>
