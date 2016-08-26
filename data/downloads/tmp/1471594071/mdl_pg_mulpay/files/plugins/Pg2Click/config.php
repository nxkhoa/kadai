<?php
/**
 *
 * @copyright 2012 GMO Payment Gateway, Inc. All Rights Reserved.
 * @link http://www.gmo-pg.com/
 *
 */

if (is_file(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php')) {
    require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
}

/**
 * プラグイン設定画面
 *
 * @package Pg2Click
 * @author GMO Payment Gateway, Inc.
 * @version $Id: $
 */

// {{{ requires
require_once PLUGIN_UPLOAD_REALDIR .  'Pg2Click/LC_Page_Plugin_Pg2Click_Config.php';

// }}}
// {{{ generate page
$objPage = new LC_Page_Plugin_Pg2Click_Config();
$objPage->init();
$objPage->process();

