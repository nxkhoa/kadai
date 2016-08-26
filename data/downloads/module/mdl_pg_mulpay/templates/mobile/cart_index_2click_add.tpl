<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->
            <br>
            <form name="form<!--{$key}-->" id="form<!--{$key}-->" method="post" action="?" utn>
                <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->">
                <input type="hidden" name="mode" value="plg_pg2click">
                <input type="hidden" name="cart_no" value="">
                <input type="hidden" name="cartKey" value="<!--{$key}-->">
                <center><input type="submit" value="2クリックで購入"></center>
            </form>
            <br>


