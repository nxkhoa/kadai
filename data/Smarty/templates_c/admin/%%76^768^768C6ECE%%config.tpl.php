<?php /* Smarty version 2.6.27, created on 2016-08-19 17:09:39
         compiled from E:/ECCube/eccube-2.13.5/html/../data/downloads/module/mdl_pg_mulpay/templates/admin/config.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', 'E:/ECCube/eccube-2.13.5/html/../data/downloads/module/mdl_pg_mulpay/templates/admin/config.tpl', 82, false),array('modifier', 'h', 'E:/ECCube/eccube-2.13.5/html/../data/downloads/module/mdl_pg_mulpay/templates/admin/config.tpl', 84, false),array('modifier', 'escape', 'E:/ECCube/eccube-2.13.5/html/../data/downloads/module/mdl_pg_mulpay/templates/admin/config.tpl', 106, false),array('modifier', 'sfGetErrorColor', 'E:/ECCube/eccube-2.13.5/html/../data/downloads/module/mdl_pg_mulpay/templates/admin/config.tpl', 132, false),array('function', 'html_radios', 'E:/ECCube/eccube-2.13.5/html/../data/downloads/module/mdl_pg_mulpay/templates/admin/config.tpl', 123, false),array('function', 'html_checkboxes', 'E:/ECCube/eccube-2.13.5/html/../data/downloads/module/mdl_pg_mulpay/templates/admin/config.tpl', 194, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@TEMPLATE_ADMIN_REALDIR)."admin_popup_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'css/contents.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
<!--
self.moveTo(20,20);self.focus();

function win_open(URL){
    var WIN;
    WIN = window.open(URL);
    WIN.focus();
}

function site_win(){
    var server_url = document.form1.kanri_server_url.value;
    if (server_url == '') {
        alert("管理画面サーバURLを設定してください。");
        return;
    }
    var site_id = document.form1.site_id.value;
        if (site_id == '') {
        alert("サイトIDを設定してください。");
        return;
    }

    var WIN;
    WIN = window.open(server_url + 'site/' +site_id + '/index');
    WIN.focus();
}

function shop_win(){
    var server_url = document.form1.kanri_server_url.value;
    if (server_url == '') {
        alert("管理画面サーバURLを設定してください。");
        return;
    }
    var shop_id = document.form1.ShopID.value;
        if (shop_id == '') {
        alert("ショップIDを設定してください。");
        return;
    }

    var WIN;
    WIN = window.open(server_url + 'shop/' + shop_id + '/index');
    WIN.focus();
}

function connect_select() {
    var connect_type = $("input:radio[name='connect_server_type']:checked").val();
    if (connect_type == '3') {
        $('#server_url').show();
        $('#kanri_server_url').show();
    } else {
        $('#server_url').hide();
        $('#kanri_server_url').hide();
    }
}

$(function(){
    connect_select();
    $("input:radio[name='connect_server_type']").change(function() {
        connect_select();
        });
});
//-->
</script>
<style type="text/css">
.info { font-size: 90%; }
#test-area{
    padding: 10px 0px;
    background: #f5f5f5;
    text-align: center;
}
</style>



<h1><span class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['tpl_subtitle'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span></h1>
<span>
<?php echo ((is_array($_tmp=@MDL_PG_MULPAY_MODULE_NAME)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
をご利用頂く為には<?php echo ((is_array($_tmp=((is_array($_tmp=@MDL_PG_MULPAY_COMPANY_NAME)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
とご契約を行っていただく必要があります。
</span>
<p style="margin:12px 0px 12px 0px;display:block; height:35px;" align="center">
<a href="http://www.ec-cube.net/rd.php?aid=a5177c923cb929" target="_blank" onmouseover="chgImg('<?php echo ((is_array($_tmp=@MDL_PG_MULPAY_MEDIAFILE_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
about_pg_on.jpg','pg');" onmouseout="chgImg('<?php echo ((is_array($_tmp=@MDL_PG_MULPAY_MEDIAFILE_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
about_pg.jpg','pg')">
<img width="304" height="35" border="0" name="pg" alt="PGマルチペイメントサービスについて" src="<?php echo ((is_array($_tmp=@MDL_PG_MULPAY_MEDIAFILE_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
about_pg.jpg" />
</a>
</p>
<div id="test-area">
<span>
ご希望の方は、以下のURLよりテスト環境を申し込むことで、<br />テスト運用に必要なアカウント情報を取得することが出来ます。
</span>
<p style="margin:12px 0px 0px 0px;display:block;height:35px;" align="center">
<a href="http://www.ec-cube.net/rd.php?aid=a5177c98d5464d" target="_blank" onmouseover="chgImg('<?php echo ((is_array($_tmp=@MDL_PG_MULPAY_MEDIAFILE_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
test_on.jpg','test');" onmouseout="chgImg('<?php echo ((is_array($_tmp=@MDL_PG_MULPAY_MEDIAFILE_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
test.jpg','test');">
<img width="304" height="35" border="0" name="test" alt="テストアカウントについて" src="<?php echo ((is_array($_tmp=@MDL_PG_MULPAY_MEDIAFILE_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
test.jpg" />
</a></p>
</div>

<h2><?php echo ((is_array($_tmp=((is_array($_tmp=@MDL_PG_MULPAY_SERVICE_NAME)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
共通設定</h2>
<span>
設定方法については、以下のサイト内の「<a href="http://www.ec-cube.net/products/detail.php?product_id=323" target="_blank">マニュアルダウンロード</a>」をご参照下さい。<br />
<a href="http://www.ec-cube.net/products/detail.php?product_id=323" target="_blank">http://www.ec-cube.net/products/detail.php?product_id=323</a>
</span>
<form name="form1" id="form1" method="post" action="<?php echo ((is_array($_tmp=((is_array($_tmp=$_SERVER['REQUEST_URI'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
  <input type="hidden" name="mode" value="" />
  <input type="hidden" name="<?php echo ((is_array($_tmp=@TRANSACTION_ID_NAME)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['transactionid'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
  <table class="form">
    <colgroup width="20%">
    <colgroup width="80%">
    <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr']['err'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) != ""): ?>
    <tr>
      <td colspan="2"><span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr']['err'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span></td>
    </tr>
    <?php endif; ?>

    <?php $this->assign('key', 'connect_server_type'); ?>
    <tr id="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
">
      <th>接続先<span class="attention"> *</span></th>
      <td>
      <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span><?php endif; ?>
      <?php echo smarty_function_html_radios(array('name' => ($this->_tpl_vars['key']),'options' => ((is_array($_tmp=$this->_tpl_vars['arrConnectServerType'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)),'selected' => ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)),'separator' => "&nbsp;"), $this);?>

      </td>
    </tr>

    <?php $this->assign('key', 'server_url'); ?>
    <tr id="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
">
      <th>接続先サーバーURL<span class="attention"> *</span></th>
      <td>
        <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span><?php endif; ?>
        <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="ime-mode:disabled; <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" class="box60" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
      </td>
    </tr>

    <?php $this->assign('key', 'kanri_server_url'); ?>
    <tr id="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
">
      <th>管理画面サーバーURL<span class="attention"> *</span></th>
      <td>
        <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span><?php endif; ?>
        <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="ime-mode:disabled; <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" class="box60" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
      </td>
    </tr>

    <?php $this->assign('key', 'site_id'); ?>
    <tr id="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
">
      <th>サイトID<span class="attention"> *</span></th>
      <td>
        <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span><?php endif; ?>
        <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="ime-mode:disabled; <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" class="box40" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
        <br />
        <span class="info">*PGマルチペイメントサービスの管理画面にログインするIDとは異なりますので、ご注意ください。</span>
      </td>
    </tr>

    <?php $this->assign('key', 'site_pass'); ?>
    <tr id="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
">
      <th>サイトパスワード<span class="attention"> *</span></th>
      <td>
        <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span><?php endif; ?>
        <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="ime-mode:disabled; <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" class="box40" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
        <br />
        <span class="info">*PGマルチペイメントサービスの管理画面にログインするパスワードとは異なりますので、ご注意ください。</span>
      </td>
    </tr>

    <?php $this->assign('key', 'ShopID'); ?>
    <tr id="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
">
      <th>ショップID<span class="attention"> *</span></th>
      <td>
        <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span><?php endif; ?>
        <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="ime-mode:disabled; <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" class="box40" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
        <br />
        <span class="info">*PGマルチペイメントサービスの管理画面にログインするIDとは異なりますので、ご注意ください。</span>
      </td>
    </tr>

    <?php $this->assign('key', 'ShopPass'); ?>
    <tr id="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
">
      <th>ショップパスワード<span class="attention"> *</span></th>
      <td>
        <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span><?php endif; ?>
        <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="ime-mode:disabled; <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" class="box40" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
        <br />
        <span class="info">*PGマルチペイメントサービスの管理画面にログインするパスワードとは異なりますので、ご注意ください。</span>
      </td>
    </tr>

    <?php $this->assign('key', 'enable_payment_type'); ?>
    <tr id="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
">
      <th>有効にする決済方法<span class="attention"> *</span></th>
      <td>
        <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span><?php endif; ?>
        <?php echo smarty_function_html_checkboxes(array('name' => ($this->_tpl_vars['key']),'options' => ((is_array($_tmp=$this->_tpl_vars['arrPayments'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)),'selected' => ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)),'separator' => "<br />"), $this);?>

        <br />
        <span class="info">*決済方法毎の詳細設定は有効後に<a href="javascript:void(win_open('./basis/payment.php'));">支払方法設定画面</a>で行います。</span>
      </td>
    </tr>

    <tr>
      <th>決済用テンプレート初期化</th>
      <td>
        <input type="checkbox" name="is_tpl_init" value="1" id="is_tpl_init" /><label for="is_tpl_init">決済用テンプレートを初期化する。</label>
        <br />
        <span class="attention">*ページやブロックのデザインテンプレートの内容を初期化します。</span><br />
        <span class="info">*決済に関するテンプレートを修正されている場合には取り扱いに注意して下さい。<br />
        初期化時には、初期化前のテンプレートデータを保存ディレクトリ内にバックアップが作成されます。
        </span>
      </td>
    </tr>

    <tr>
      <th>結果通知プログラムURL</th>
      <td>
        <?php echo ((is_array($_tmp=((is_array($_tmp=@MDL_PG_MULPAY_SETTLEMENT_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
<br />
        <span class="info">*ショップ管理画面よりログインして頂き、タブ「ショップの管理」＞タブ「メール/結果通知設定」で結果通知プログラムURLに設定してください。</span>
      </td>
    </tr>
    <tr>
      <th>サイト管理</th>
      <td>
        <a href="javascript:void(site_win());">＞＞サイト管理画面</a><br />
        <span class="info">*本番環境管理画面はGMOペイメントゲートウェイ株式会社より発行 れた「クライアント証明書」がインストールされたブラウザでアクセスする必要があります。</span>
      </td>
    </tr>

    <tr>
      <th>ショップ管理</th>
      <td>
        <a href="javascript:void(shop_win());">＞＞ショップ管理画面</a><br />
        <span class="info">*本番環境管理画面はGMOペイメントゲートウェイ株式会社より発行 れた「クライアント証明書」がインストールされたブラウザでアクセスする必要があります。</span>
      </td>
    </tr>

  </table>
  <div class="btn-area">
    <ul>
      <li>
        <a class="btn-action" href="javascript:;" onclick="document.form1.mode.value='register';document.body.style.cursor = 'wait';document.form1.submit();return false;"><span class="btn-next">この内容で登録する</span></a>
      </li>
    </ul>
  </div>

</form>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@TEMPLATE_ADMIN_REALDIR)."admin_popup_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>