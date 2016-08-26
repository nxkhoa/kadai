<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASS_PATH . 'pages/LC_Page_Mdl_PG_MULPAY_Config.php');

/**
 * 決済モジュール オーナーズストア モジュール設定画面クラス
 */
class LC_Page_Mdl_PG_MULPAY_Config_Ex extends LC_Page_Mdl_PG_MULPAY_Config {

    function LC_Page_Mdl_PG_MULPAY_Config_Ex() {
        parent::LC_Page_Mdl_PG_MULPAY_Config();
    }

}
?>
