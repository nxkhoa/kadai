<!--{*
 * Copyright(c) 2013 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2013/04/04
 *}-->
<!--{if !$is_link_load}--> <!--{* 多重ロードを防ぐ *}-->

<script type="text/javascript">//<![CDATA[
    $(function(){
        $('nav#mypage_nav li:last').after('<li class="nav_change_card"><a href="change_card.php" class="ui-link" rel="external">カード情報編集</a></li>');
        });

//]]></script>

<!--{/if}-->
<!--{assign var="is_link_load" value="1"}-->

