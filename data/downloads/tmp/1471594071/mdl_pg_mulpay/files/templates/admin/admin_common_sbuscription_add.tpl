<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->


<script type="text/javascript">//<![CDATA[

    $(function(){
        $('li#navi-basis ul:first li:last').after('<li id="navi-basis-plg_pgmulpaysubscription_subs_config"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->basis/plg_pgmulpaysubscription_subs_config.php"><span>定期購入管理</span></a></li>');
        $('li#navi-order ul:first li:last').after('<li id="navi-order-plg_pgmulpaysubscription_subs_order"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/plg_pgmulpaysubscription_subs_order.php"><span>定期購入受注管理</span></a></li>');
        $('li#navi-order ul:first li:last').after('<li id="navi-order-plg_pgmulpaysubscription_subs_payment"><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/plg_pgmulpaysubscription_subs_payment.php"><span>定期購入課金管理</span></a></li>');

    });
//]]></script>
