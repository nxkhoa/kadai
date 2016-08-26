<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASS_PATH . 'SC_Mdl_PG_MULPAY.php');

/**
 * 決済モジュール基本クラス
 */
class SC_Mdl_PG_MULPAY_Ex extends SC_Mdl_PG_MULPAY {

    function SC_Mdl_PG_MULPAY_Ex() {
        parent::SC_Mdl_PG_MULPAY();
    }

}
?>
