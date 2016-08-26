<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2013/07/12
 *}-->
<script type="text/javascript">//<![CDATA[

//]]>
</script>

<section id="undercolumn">
    <h2 class="title"><!--{$tpl_title|h}--></h2>

    <form name="form1" id="form1" method="POST" action="<!--{$tpl_url}-->" autocomplete="off">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="register" />
        <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />
        <!--{foreach from=$arrForm item=data key=key}-->
        <input type="hidden" name="<!--{$key}-->" value="<!--{$data.value|h}-->" />
        <!--{/foreach}-->

        <!--{if $tpl_form_bloc_path != ""}-->
        <!--{include file=$tpl_form_bloc_path}-->
        <!--{/if}-->
    </form>
</section>
