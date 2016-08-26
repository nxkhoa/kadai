<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */
require_once('../require.php');
require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'pages_extends/LC_Page_Admin_Order_PgMulpayUtils_Payment_Status_Ex.php');

// }}}
// {{{ generate page

$objPage = new LC_Page_Admin_Order_PgMulpayUtils_Payment_Status_Ex();
$objPage->init();
$objPage->process();

