<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');
require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Base_Ex.php');

/**
 * 決済モジュール 決済処理: 会員処理
 */
class SC_Mdl_PG_MULPAY_Client_Member extends SC_Mdl_PG_MULPAY_Client_Base_Ex {

    /**
     * コンストラクタ
     *
     * @return void
     */
    function __construct() {
    }

    function checkMember($arrCustomer) {
        $ret = $this->getMember($arrCustomer);
        if(!$ret) {
            // エラーなら無視
            return true;
        }
        $arrResult = $this->getResults();
        if (SC_Utils_Ex::isBlank($arrResult)) {
            return true;
        }

        if (SC_Utils_Ex::isBlank($arrResult['MemberName'])) {
            $this->updateMember($arrCustomer);
            return true;
        }

        if (SC_Utils_Ex::isBlank($arrCustomer['secret_key'])) {
            $arrCustomer = SC_Helper_Customer_Ex::sfGetCustomerData($arrCustomer['customer_id']);
        }
        if ($arrCustomer['secret_key'] == $arrResult['MemberName']) {
            return true;
        }
        $serverName = mb_convert_encoding($arrResult['MemberName'],'UTF-8','SJIS-win');
        $checkName = SC_Util_PG_MULPAY_Ex::convCVSText($arrCustomer['name01'] . $arrCustomer['name02']);
        if ($serverName == $checkName) {
            $this->updateMember($arrCustomer);
            return true;
        }

        $this->updateMember($arrCustomer);
        return $this->deleteCardAll($arrCustomer);
        
    }

    function searchCard($arrCustomer, $arrParam = array(), $is_check = true) {
        if (SC_Utils_Ex::isBlank($arrCustomer['customer_id']) || $arrCustomer['customer_id'] == '0') {
            return true;
        }

        if ($is_check) {
            $this->checkMember($arrCustomer);
        }

        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'SearchCard.idPass';

        $arrSendKey = array(
            'SiteID',
            'SitePass',
            'MemberID',
            'CardSeq',
        );
        $arrSendData = $this->getSendData($arrSendKey, $arrCustomer, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        return true;
    }

    function deleteCardAll($arrCustomer) {
        $ret = $this->searchCard($arrCustomer, array(), false);
        if (!$ret) {
            return false;
        }
        // 逆順並び替え
        $arrCardSeq = array();
        foreach ($this->arrResults as $arrData) {
            $arrCardSeq[] = $arrData['CardSeq'];
        }
        rsort($arrCardSeq);
        foreach ($arrCardSeq as $seq) {
            $this->deleteCard($arrCustomer, array('CardSeq' => $seq));
        }
        return true;
    }

    function deleteCard($arrCustomer, $arrParam = array()) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'DeleteCard.idPass';

        $arrSendKey = array(
            'SiteID',
            'SitePass',
            'MemberID',
            'CardSeq',
        );

        $arrSendData = $this->getSendData($arrSendKey, $arrCustomer, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        return true;
    }

    function saveCard($arrCustomer, $arrParam) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'SaveCard.idPass';

        $arrSendKey = array(
            'SiteID',
            'SitePass',
            'MemberID',
            'DefaultFlag',
            'CardNo',
            'Expire',
            'HolderName',
        );

        if (!SC_Utils_Ex::isBlank($arrParam['CardSeq'])) {
            $arrSendKey[] = 'CardSeq';
        }
        if (SC_Utils_Ex::isBlank($arrParam['DefaultFlag'])) {
            $arrParam['DefaultFlag'] = '1';
        }

        $arrSendData = $this->getSendData($arrSendKey, $arrCustomer, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        return true;
    }

    function deleteMember($arrCustomer) {
        if (SC_Utils_Ex::isBlank($arrCustomer['customer_id']) || $arrCustomer['customer_id'] == '0') {
            return true;
        }

        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'DeleteMember.idPass';

        $arrSendKey = array(
            'SiteID',
            'SitePass',
            'MemberID',
        );

        $arrSendData = $this->getSendData($arrSendKey, $arrCustomer, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        return true;
    }

    function updateMember($arrCustomer) {
        if (SC_Utils_Ex::isBlank($arrCustomer['customer_id']) || $arrCustomer['customer_id'] == '0') {
            return true;
        }

        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'UpdateMember.idPass';

        $arrSendKey = array(
            'SiteID',
            'SitePass',
            'MemberID',
            'MemberName',
        );

        $arrSendData = $this->getSendData($arrSendKey, $arrCustomer, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        return true;
    }

    function saveMember($arrCustomer) {
        if (SC_Utils_Ex::isBlank($arrCustomer['customer_id']) || $arrCustomer['customer_id'] == '0') {
            return true;
        }

        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'SaveMember.idPass';

        $arrSendKey = array(
            'SiteID',
            'SitePass',
            'MemberID',
            'MemberName',
        );

        $arrSendData = $this->getSendData($arrSendKey, $arrCustomer, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        return true;
    }

    function getMember($arrCustomer) {
        if (SC_Utils_Ex::isBlank($arrCustomer['customer_id']) || $arrCustomer['customer_id'] == '0') {
            return false;
        }

        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $arrMdlSetting['server_url'] . 'SearchMember.idPass';

        $arrSendKey = array(
            'SiteID',
            'SitePass',
            'MemberID',
        );

        $arrSendData = $this->getSendData($arrSendKey, $arrCustomer, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($server_url, $arrSendData);
        if (!$ret) {
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        return true;
    }

    function doPaymentRequest($arrOrder, $arrParam, $arrPaymentInfo) {
    }

    function getTargetPoint() {
        return '';
    }

    function getSendParam($arrData) {

    }

}
