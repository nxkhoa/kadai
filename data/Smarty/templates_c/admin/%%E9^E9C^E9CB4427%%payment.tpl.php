<?php /* Smarty version 2.6.27, created on 2016-08-19 17:10:11
         compiled from basis/payment.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', 'basis/payment.tpl', 4, false),array('modifier', 'h', 'basis/payment.tpl', 16, false),array('modifier', 'n2s', 'basis/payment.tpl', 21, false),)), $this); ?>


<form name="form1" id="form1" method="post" action="?">
    <input type="hidden" name="<?php echo ((is_array($_tmp=@TRANSACTION_ID_NAME)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['transactionid'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"><input type="hidden" name="mode" value="edit"><input type="hidden" name="payment_id" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['tpl_payment_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"><div id="basis" class="contents-main">
        <div class="btn">
            <ul><li><a class="btn-action" href="javascript:;" name="subm2" onclick="eccube.changeAction('./payment_input.php'); eccube.setModeAndSubmit('','',''); return false;">
                    <span class="btn-next">支払方法を新規入力</span></a></li>
            </ul></div>
        <table class="list"><col width="5%"><col width="30%"><col width="20%"><col width="20%"><col width="5%"><col width="5%"><col width="15%"><tr><th class="center">ID</th>
                <th>支払方法</th>
                <th>手数料（円）</th>
                <th>利用条件</th>
                <th>編集</th>
                <th>削除</th>
                <th>移動</th>
            </tr><?php unset($this->_sections['cnt']);
$this->_sections['cnt']['name'] = 'cnt';
$this->_sections['cnt']['loop'] = is_array($_loop=((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><tr><td class="center"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['payment_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</td>
                <td class="center"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['payment_method'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</td>
                <?php if (((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['charge_flg'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 2): ?>
                    <td class="center">-</td>
                <?php else: ?>
                    <td class="right"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['charge'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('n2s', true, $_tmp) : smarty_modifier_n2s($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</td>
                <?php endif; ?>
                <td class="center">
                    <?php if (((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['rule_max'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) > 0): ?><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['rule_max'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('n2s', true, $_tmp) : smarty_modifier_n2s($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
<?php else: ?>0<?php endif; ?>円
                    <?php if (((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['upper_rule'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) > 0): ?>～<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['upper_rule'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('n2s', true, $_tmp) : smarty_modifier_n2s($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
円<?php elseif (((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['upper_rule'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == '0'): ?><?php else: ?>～無制限<?php endif; ?></td>
                <td class="center"><?php if (((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['fix'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) != 1): ?><a href="?" onclick="eccube.changeAction('./payment_input.php'); eccube.setModeAndSubmit('pre_edit', 'payment_id', <?php echo ((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['payment_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
); return false;">編集</a><?php else: ?>-<?php endif; ?></td>
                <td class="center"><?php if (((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['fix'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) != 1): ?><a href="?" onclick="eccube.setModeAndSubmit('delete', 'payment_id', <?php echo ((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['payment_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
); return false;">削除</a><?php else: ?>-<?php endif; ?></td>
                <td class="center">
                <?php if (((is_array($_tmp=$this->_sections['cnt']['iteration'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) != 1): ?>
                <a href="?" onclick="eccube.setModeAndSubmit('up','payment_id', <?php echo ((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['payment_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
); return false;">上へ</a>
                <?php endif; ?>
                <?php if (((is_array($_tmp=$this->_sections['cnt']['iteration'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) != ((is_array($_tmp=$this->_sections['cnt']['last'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
                <a href="?" onclick="eccube.setModeAndSubmit('down','payment_id', <?php echo ((is_array($_tmp=$this->_tpl_vars['arrPaymentListFree'][$this->_sections['cnt']['index']]['payment_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
); return false;">下へ</a>
                <?php endif; ?>
                </td>
            </tr><?php endfor; endif; ?></table></div>
</form>