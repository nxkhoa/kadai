<?php /* Smarty version 2.6.27, created on 2016-09-02 11:24:19
         compiled from products/product_rank.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', 'products/product_rank.tpl', 28, false),array('modifier', 'h', 'products/product_rank.tpl', 36, false),array('modifier', 'sfNoImageMainList', 'products/product_rank.tpl', 90, false),array('function', 'from_to', 'products/product_rank.tpl', 84, false),)), $this); ?>

<script type="text/javascript">//<![CDATA[
    $(function() {
        $('h2').breadcrumbs({
            'bread_crumbs': <?php echo ((is_array($_tmp=$this->_tpl_vars['tpl_bread_crumbs'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>

        });
    });
//]]></script>

<form name="form1" id="form1" method="post" action="?" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo ((is_array($_tmp=@TRANSACTION_ID_NAME)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['transactionid'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
    <input type="hidden" name="mode" value="edit" />
    <input type="hidden" name="parent_category_id" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm']['parent_category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" />
    <input type="hidden" name="category_id" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm']['category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" />
    <input type="hidden" name="product_id" value="" />
    <input type="hidden" name="pageno" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tpl_pageno'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" />
    <div id="products" class="contents-main">

                <div id="products-rank-left">
            <ul>
                <li>
                    <a href="?"><img src="<?php echo ((is_array($_tmp=$this->_tpl_vars['TPL_URLPATH'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
img/contents/folder_close.gif" alt="フォルダ" />&nbsp;ホーム</a>
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@TEMPLATE_ADMIN_REALDIR)."products/product_rank_tree_fork.tpl", 'smarty_include_vars' => array('children' => ((is_array($_tmp=$this->_tpl_vars['arrTree'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)),'treeID' => 'f0','display' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </li>
            </ul>
        </div>
        
        <!--▼画面右-->
        <div id="products-rank-right">
            <h2></h2>
            <?php if (count ( ((is_array($_tmp=$this->_tpl_vars['arrProductsList'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) ) > 0): ?>

                <p class="remark"><span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['tpl_linemax'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
件</span>が該当しました。</p>
                <div class="pager">
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['tpl_strnavi'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>

                </div>

                <?php if (((is_array($_tmp=@ADMIN_MODE)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == '1'): ?>
                    <p class="right"><a class="btn-normal" href="javascript:;" onclick="eccube.setModeAndSubmit('renumber', '', ''); return false;">内部順位再割り当て</a></p>
                <?php endif; ?>

                <table class="list" id="categoryTable">
                    <col width="20%" />
                    <col width="47.5%" />
                    <col width="10%" />
                    <col width="7.5%" />
                    <col width="15%" />
                    <tr class="nodrop nodrag">
                        <th>商品コード</th>
                        <th>商品名</th>
                        <th>商品画像</th>
                        <th>順位</th>
                        <th>移動</th>
                    </tr>

                    <?php $this->assign('rank', ((is_array($_tmp=$this->_tpl_vars['tpl_start_row'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))); ?>
                    <?php unset($this->_sections['cnt']);
$this->_sections['cnt']['name'] = 'cnt';
$this->_sections['cnt']['loop'] = is_array($_loop=((is_array($_tmp=$this->_tpl_vars['arrProductsList'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cnt']['show'] = true;
$this->_sections['cnt']['max'] = $this->_sections['cnt']['loop'];
$this->_sections['cnt']['step'] = 1;
$this->_sections['cnt']['start'] = $this->_sections['cnt']['step'] > 0 ? 0 : $this->_sections['cnt']['loop']-1;
if ($this->_sections['cnt']['show']) {
    $this->_sections['cnt']['total'] = $this->_sections['cnt']['loop'];
    if ($this->_sections['cnt']['total'] == 0)
        $this->_sections['cnt']['show'] = false;
} else
    $this->_sections['cnt']['total'] = 0;
if ($this->_sections['cnt']['show']):

            for ($this->_sections['cnt']['index'] = $this->_sections['cnt']['start'], $this->_sections['cnt']['iteration'] = 1;
                 $this->_sections['cnt']['iteration'] <= $this->_sections['cnt']['total'];
                 $this->_sections['cnt']['index'] += $this->_sections['cnt']['step'], $this->_sections['cnt']['iteration']++):
$this->_sections['cnt']['rownum'] = $this->_sections['cnt']['iteration'];
$this->_sections['cnt']['index_prev'] = $this->_sections['cnt']['index'] - $this->_sections['cnt']['step'];
$this->_sections['cnt']['index_next'] = $this->_sections['cnt']['index'] + $this->_sections['cnt']['step'];
$this->_sections['cnt']['first']      = ($this->_sections['cnt']['iteration'] == 1);
$this->_sections['cnt']['last']       = ($this->_sections['cnt']['iteration'] == $this->_sections['cnt']['total']);
?>
                        <tr>
                            <td><?php echo smarty_function_from_to(array('from' => ((is_array($_tmp=$this->_tpl_vars['arrProductsList'][$this->_sections['cnt']['index']]['product_code_min'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)),'to' => ((is_array($_tmp=$this->_tpl_vars['arrProductsList'][$this->_sections['cnt']['index']]['product_code_max'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)),'separator' => "～<br />"), $this);?>
</td>
                            <td>
                                <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrProductsList'][$this->_sections['cnt']['index']]['name'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>

                            </td>
                            <td align="center">
                                                                <img src="<?php echo ((is_array($_tmp=@IMAGE_SAVE_URLPATH)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrProductsList'][$this->_sections['cnt']['index']]['main_list_image'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfNoImageMainList', true, $_tmp) : SC_Utils_Ex::sfNoImageMainList($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" style="max-width: 65px;max-height: 65;" alt="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrProducts'][$this->_sections['cnt']['index']]['name'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" />
                            </td>
                            <?php $this->assign('rank', ($this->_tpl_vars['rank']+1)); ?>
                            <td align="center">
                                <?php echo ((is_array($_tmp=$this->_tpl_vars['rank'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>

                                <?php if (((is_array($_tmp=$this->_tpl_vars['arrProductsList'][$this->_sections['cnt']['index']]['status'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == '2'): ?><br />(非公開)<?php endif; ?>
                            </td>
                            <td align="center">
                                                        <?php if (! ( count ( ((is_array($_tmp=$this->_tpl_vars['arrProductsList'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) ) == 1 && ((is_array($_tmp=$this->_tpl_vars['rank'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 1 )): ?>
                            <input type="text" name="pos-<?php echo ((is_array($_tmp=$this->_tpl_vars['arrProductsList'][$this->_sections['cnt']['index']]['product_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" size="3" class="box3" />番目へ<a href="?" onclick="eccube.setModeAndSubmit('move','product_id', '<?php echo ((is_array($_tmp=$this->_tpl_vars['arrProductsList'][$this->_sections['cnt']['index']]['product_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
'); return false;">移動</a><br />
                            <?php endif; ?>
                            <?php if (! ( ((is_array($_tmp=$this->_sections['cnt']['first'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) && ((is_array($_tmp=$this->_tpl_vars['tpl_disppage'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 1 )): ?>
                            <a href="?" onclick="eccube.setModeAndSubmit('up','product_id', '<?php echo ((is_array($_tmp=$this->_tpl_vars['arrProductsList'][$this->_sections['cnt']['index']]['product_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
'); return false;">上へ</a>
                            <?php endif; ?>
                            <?php if (! ( ((is_array($_tmp=$this->_sections['cnt']['last'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) && ((is_array($_tmp=$this->_tpl_vars['tpl_disppage'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == ((is_array($_tmp=$this->_tpl_vars['tpl_pagemax'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?>
                            <a href="?" onclick="eccube.setModeAndSubmit('down','product_id', '<?php echo ((is_array($_tmp=$this->_tpl_vars['arrProductsList'][$this->_sections['cnt']['index']]['product_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
'); return false;">下へ</a>
                            <?php endif; ?>
                            </td>
                        </tr>
                    <?php endfor; endif; ?>
                </table>
            <?php else: ?>
                <p>カテゴリを選択してください。</p>
            <?php endif; ?>
        </div>
        <!--▲画面右-->

    </div>
</form>