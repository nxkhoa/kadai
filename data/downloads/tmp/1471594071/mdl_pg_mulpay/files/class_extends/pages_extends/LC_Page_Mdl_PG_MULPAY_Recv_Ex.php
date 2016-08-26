<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */
require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASS_PATH . 'pages/LC_Page_Mdl_PG_MULPAY_Recv.php');

/**
 * 結果非同期受信ヘルパークラス
 */
class LC_Page_Mdl_PG_MULPAY_Recv_Ex extends LC_Page_Mdl_PG_MULPAY_Recv {

    function LC_Page_Mdl_PG_MULPAY_Recv_Ex() {
        parent::LC_Page_Mdl_PG_MULPAY_Recv();
    }

}
?>
