<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->
    <hr>
    2クリックで購入したい場合は下記数量を入力して、「2クリックで購入」ボタンを押して下さい。
    <form method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->">
        <input type="text" name="quantity" size="3" value="<!--{$arrForm.quantity.value|default:1|h}-->" maxlength=<!--{$smarty.const.INT_LEN}--> istyle="4"><br>
        <input type="hidden" name="mode" value="plg_pg2click">
        <input type="hidden" name="classcategory_id1" value="<!--{$arrForm.classcategory_id1.value}-->">
        <input type="hidden" name="classcategory_id2" value="<!--{$arrForm.classcategory_id2.value}-->">
        <input type="hidden" name="product_id" value="<!--{$tpl_product_id}-->">
        <input type="hidden" name="product_class_id" value="<!--{$tpl_product_class_id}-->">
        <input type="hidden" name="product_type" value="<!--{$tpl_product_type}-->">
        <center><input type="submit" name="submit" value="2クリックで購入"></center>
    </form>

