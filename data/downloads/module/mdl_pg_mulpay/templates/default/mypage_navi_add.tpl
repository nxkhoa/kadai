<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/08/20
 *}-->
<!--{if !$is_link_load}--> <!--{* 多重ロードを防ぐ *}-->

<script type="text/javascript">//<![CDATA[
    $(function(){
        $('div#mynavi_area li:last').after('<li><a href="change_card.php" class="">カード情報編集</a></li>');
        });

//]]></script>

<!--{/if}-->
<!--{assign var="is_link_load" value="1"}-->

