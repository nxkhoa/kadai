<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->
<input type="hidden" name="mode" value="" />
                            <div class="cartin_btn" style="margin-left:1em;">
                                <div id="cartbtn_default_<!--{$id}-->">
                                    <input type="image" src="<!--{$smarty.const.PLUGIN_HTML_URLPATH}-->Pg2Click/btn_2click.png" alt="2クリック決済" name="plg_pg2click" id="plg_pg2click" onmouseover="chgImgImageSubmit('<!--{$smarty.const.PLUGIN_HTML_URLPATH}-->Pg2Click/btn_2click_on.png','plg_pg2click')" onmouseout="chgImgImageSubmit('<!--{$smarty.const.PLUGIN_HTML_URLPATH}-->Pg2Click/btn_2click.png','plg_pg2click')" onclick="$('input[name=mode]').val('plg_pg2click');fnInCart(this.form);return false;">

                                </div>
                            </div>

