<?php /* Smarty version 2.6.27, created on 2016-09-02 11:27:52
         compiled from contents/recommend_search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', 'contents/recommend_search.tpl', 31, false),array('modifier', 'h', 'contents/recommend_search.tpl', 31, false),array('modifier', 'sfNoImageMainList', 'contents/recommend_search.tpl', 104, false),array('function', 'html_options', 'contents/recommend_search.tpl', 55, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@TEMPLATE_ADMIN_REALDIR)."admin_popup_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
<!--
self.moveTo(20,20);self.focus();

function func_submit( id ){
    var fm = window.opener.document.form<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['rank'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
;
    fm.product_id.value = id;
    fm.mode.value = 'set_item';
    fm.rank.value = '<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['rank'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
';
    fm.submit();
    window.close();
    return false;
}
//-->
</script>

<!--▼検索フォーム-->
<form name="form1" id="form1" method="post" action="#">
    <input type="hidden" name="<?php echo ((is_array($_tmp=@TRANSACTION_ID_NAME)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['transactionid'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
    <input name="mode" type="hidden" value="search" />
    <input name="search_pageno" type="hidden" value="" />
    <table class="form">
        <col width="20%" />
        <col width="80%" />
        <tr>
            <th>カテゴリ</th>
            <td>
                <select name="search_category_id">
                    <option value="" selected="selected">選択してください</option>
                    <?php echo smarty_function_html_options(array('options' => ((is_array($_tmp=$this->_tpl_vars['arrCatList'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)),'selected' => ((is_array($_tmp=$this->_tpl_vars['arrForm']['search_category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))), $this);?>

                </select>
            </td>
        </tr>
        <tr>
            <th>商品コード</th>
            <td><input type="text" name="search_product_code" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm']['search_product_code'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" size="35" class="box35" /></td>
        </tr>
        <tr>
            <th>商品名</th>
            <td><input type="text" name="search_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm']['search_name'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" size="35" class="box35" /></td>
        </tr>
        <tr>
            <th>商品ステータス</th>
            <td>
                <input type="hidden" name="search_status" value="1" />
                <label>
                    <input type="checkbox" name="search_status" value="" <?php if (((is_array($_tmp=$this->_tpl_vars['arrForm']['search_status'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) === ""): ?>checked<?php endif; ?> />
                    非公開の商品を含む
                </label>
            </td>
        </tr>
    </table>
    <div class="btn-area">
        <ul>
            <li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', 'search', '', ''); return false;"><span class="btn-next">検索を開始</span></a></li>
        </ul>
    </div>
        <?php if (is_numeric ( ((is_array($_tmp=$this->_tpl_vars['tpl_linemax'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?>
    <p><?php echo ((is_array($_tmp=$this->_tpl_vars['tpl_linemax'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
件が該当しました。</p>
    <?php echo ((is_array($_tmp=$this->_tpl_vars['tpl_strnavi'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>


    <table id="recommend-search-results" class="list">
        <col width="15%" />
        <col width="12.5%" />
        <col width="60%" />
        <col width="12.5%" />
        <tr>
            <th>商品画像</th>
            <th>商品コード</th>
            <th>商品名</th>
            <th>決定</th>
        </tr>

        <?php $_from = ((is_array($_tmp=$this->_tpl_vars['arrProducts'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['arr']):
        $this->_foreach['loop']['iteration']++;
?>
        <!--▼商品<?php echo ((is_array($_tmp=$this->_foreach['loop']['iteration'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
-->
        <tr class="<?php if (((is_array($_tmp=$this->_tpl_vars['arr']['status'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == '2'): ?>hidden<?php endif; ?>">
            <td class="center">
                <img src="<?php echo ((is_array($_tmp=@IMAGE_SAVE_URLPATH)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr']['main_list_image'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfNoImageMainList', true, $_tmp) : SC_Utils_Ex::sfNoImageMainList($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" style="max-width: 65px;max-height: 65;" alt="" />
            </td>
            <td>
                <?php $this->assign('codemin', ($this->_tpl_vars['arr']['product_code_min'])); ?>
                <?php $this->assign('codemax', ($this->_tpl_vars['arr']['product_code_max'])); ?>
                                <?php if (((is_array($_tmp=$this->_tpl_vars['codemin'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) != ((is_array($_tmp=$this->_tpl_vars['codemax'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
                    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['codemin'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
～<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['codemax'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>

                <?php else: ?>
                    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['codemin'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>

                <?php endif; ?>
            </td>
            <td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr']['name'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</td>
            <td class="center"><a href="" onclick="return func_submit(<?php echo ((is_array($_tmp=$this->_tpl_vars['arr']['product_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
)">決定</a></td>
        </tr>
        <!--▲商品<?php echo ((is_array($_tmp=$this->_foreach['loop']['iteration'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
-->
        <?php endforeach; endif; unset($_from); ?>
        <?php if (! ((is_array($_tmp=$this->_tpl_vars['tpl_linemax'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) > 0): ?>
        <tr>
            <td colspan="4">商品が登録されていません</td>
        </tr>
        <?php endif; ?>

    </table>
    <?php endif; ?>
    
</form>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@TEMPLATE_ADMIN_REALDIR)."admin_popup_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>