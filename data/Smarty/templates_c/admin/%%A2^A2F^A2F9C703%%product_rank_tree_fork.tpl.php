<?php /* Smarty version 2.6.27, created on 2016-09-02 11:24:19
         compiled from E:/ECCube/eccube-2.13.5/html/../data/Smarty/templates/admin/products/product_rank_tree_fork.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', 'E:/ECCube/eccube-2.13.5/html/../data/Smarty/templates/admin/products/product_rank_tree_fork.tpl', 23, false),array('modifier', 'sfCutString', 'E:/ECCube/eccube-2.13.5/html/../data/Smarty/templates/admin/products/product_rank_tree_fork.tpl', 35, false),array('modifier', 'h', 'E:/ECCube/eccube-2.13.5/html/../data/Smarty/templates/admin/products/product_rank_tree_fork.tpl', 35, false),)), $this); ?>

<ul <?php if (((is_array($_tmp=$this->_tpl_vars['treeID'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) != ""): ?>id="<?php echo ((is_array($_tmp=$this->_tpl_vars['treeID'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php endif; ?> style="<?php if (! ((is_array($_tmp=$this->_tpl_vars['display'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>display: none;<?php endif; ?>">
    <?php $_from = ((is_array($_tmp=$this->_tpl_vars['children'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['child']):
?>
        <li class="level<?php echo ((is_array($_tmp=$this->_tpl_vars['child']['level'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
">
                        <?php $this->assign('disp_name', ($this->_tpl_vars['child']['category_id']).".".($this->_tpl_vars['child']['category_name'])); ?>
            <?php if (((is_array($_tmp=$this->_tpl_vars['child']['level'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) != ((is_array($_tmp=@LEVEL_MAX)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
                <a href="?" onclick="eccube.setModeAndSubmit('tree', 'parent_category_id', <?php echo ((is_array($_tmp=$this->_tpl_vars['child']['category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
); return false;">
                <?php if (((is_array($_tmp=$this->_tpl_vars['arrForm']['parent_category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == ((is_array($_tmp=$this->_tpl_vars['child']['category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
                    <img src="<?php echo ((is_array($_tmp=$this->_tpl_vars['TPL_URLPATH'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
img/contents/folder_open.gif" alt="フォルダ" />
                <?php else: ?>
                    <img src="<?php echo ((is_array($_tmp=$this->_tpl_vars['TPL_URLPATH'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
img/contents/folder_close.gif" alt="フォルダ" />
                <?php endif; ?>
                <?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['disp_name'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfCutString', true, $_tmp, 10, false) : SC_Utils_Ex::sfCutString($_tmp, 10, false)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</a>
            <?php else: ?>
                <img src="<?php echo ((is_array($_tmp=$this->_tpl_vars['TPL_URLPATH'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
img/contents/folder_close.gif" alt="フォルダ" />
                <?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['disp_name'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfCutString', true, $_tmp, 10, false) : SC_Utils_Ex::sfCutString($_tmp, 10, false)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>

            <?php endif; ?>
            <?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['child']['category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrParentID'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?>
                <?php $this->assign('disp_child', 1); ?>
            <?php else: ?>
                <?php $this->assign('disp_child', 0); ?>
            <?php endif; ?>
            <?php if (isset ( $this->_tpl_vars['child']['children'] )): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@TEMPLATE_ADMIN_REALDIR)."products/product_rank_tree_fork.tpl", 'smarty_include_vars' => array('children' => ((is_array($_tmp=$this->_tpl_vars['child']['children'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)),'treeID' => "f".($this->_tpl_vars['child']['category_id']),'display' => ((is_array($_tmp=$this->_tpl_vars['disp_child'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php endif; ?>
        </li>
    <?php endforeach; endif; unset($_from); ?>
</ul>