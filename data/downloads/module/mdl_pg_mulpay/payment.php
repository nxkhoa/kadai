<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */
session_cache_limiter('private-no-expire');

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . "pages_extends/LC_Page_Mdl_PG_MULPAY_Helper_Ex.php");

// }}}
// {{{ generate page

$objPage = new LC_Page_Mdl_PG_MULPAY_Helper_Ex();
$objPage->init();
$objPage->process();
