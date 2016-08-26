<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'client_extends/SC_Mdl_PG_MULPAY_Client_Member_Ex.php');

/**
 * プラグインの処理クラス
 */
class LC_PgMulpayCore {

    function actionPrefilterTransform($class_name, &$source, &$objPage, $filename, $objPlugin) {
        switch($filename){
            case 'basis/payment_input.tpl':
                // 支払い方法登録画面
                $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'admin/';
                $template_file = 'basis_payment_input_add.tpl';
                $objTransform = new SC_Helper_Transform($source);
                $objTransform->select('table.form')->appendChild(file_get_contents($template_dir . $template_file));
                $source = $objTransform->getHTML();
                break;
            default:
                break;
        }
    }

    function actionHook($class_name, $hook_point, &$objPage, $objPlugin) {
        switch ($class_name) {
        case 'LC_Page_Shopping_Complete_Ex':
            if (!SC_Utils_Ex::isBlank($_SESSION['order_id'])) {
                $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($_SESSION['order_id']);
                if(!SC_Utils_Ex::isBlank($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYVIEW])) {
                    $objPage->arrOther = unserialize($arrOrder[MDL_PG_MULPAY_ORDER_COL_PAYVIEW]);
                }
            }
            break;
        case 'LC_Page_Shopping_Payment_Ex':
            if ($hook_point == 'before') {
                if ($objPage->getMode() == 'select_deliv' && SC_Display_Ex::detectDevice() != DEVICE_TYPE_MOBILE) {
                    $objPurchase = new SC_Helper_Purchase_Ex();

                    $objSiteSess = new SC_SiteSession_Ex();
                    $objCartSess = new SC_CartSession_Ex();
                    $objFormParam = new SC_FormParam_Ex();
                    $objDelivery = new SC_Helper_Delivery_Ex();


                    $is_multiple = $objPurchase->isMultiple();

                    $arrShipping = $objPurchase->getShippingTemp($is_multiple);
                    $tpl_uniqid = $objSiteSess->getUniqId();

                    $cart_key = $objCartSess->getKey();
                    $objPage->cartKey = $cart_key;
                    $objPurchase->verifyChangeCart($tpl_uniqid, $objCartSess);
                    $arrDeliv = $objDelivery->getList($cart_key);
                    $is_single_deliv = $objPage->isSingleDeliv($arrDeliv);

                    $arrOrderTemp = $objPurchase->getOrderTemp($tpl_uniqid);

                    // ここまで本体処理 ここからmode処理
                    $objPage->setFormParams($objFormParam, $arrOrderTemp, true, $arrShipping);
                    $objFormParam->setParam($_POST);
                    $arrErr = $objFormParam->checkError();
                    if (SC_Utils_Ex::isBlank($arrErr)) {
                        $deliv_id = $objFormParam->getValue('deliv_id');
                        $arrSelectedDeliv = $objPage->getSelectedDeliv($objCartSess, $deliv_id);
                        $arrSelectedDeliv['error'] = false;
                    } else {
                        $arrSelectedDeliv = array('error' => true);
                    }

                    $this->lfCheckPayment($arrSelectedDeliv['arrPayment'], $objPage);

                    echo SC_Utils_Ex::jsonEncode($arrSelectedDeliv);
                    exit;
                }
            } else if($hook_point == 'after') {
                $this->lfCheckPayment($objPage->arrPayment, $objPage);
            }
            break;
        case 'LC_Page_Admin_Basis_PaymentInput_Ex':
            if ($hook_point == 'after') {
                $objPage->arrCONVENI = SC_Util_PG_MULPAY_Ex::getConveni();
                $objDate = new SC_Date_Ex();
                $objPage->arrHour = $objDate->getHour();
                $objPage->arrMinutes = $objDate->getMinutes();
                $objPage->arrAllowFlags = array('1' => '許可', '0' => '不許可');
                $objPage->arrEnableFlags = array('1' => '利用する', '0' => '利用しない');
                $objPage->arrPayMethod = SC_Util_PG_MULPAY_Ex::getCreditPayMethod();

                $arrPayConfig = SC_Util_PG_MULPAY_Ex::getPaymentTypeConfig($objPage->tpl_payment_id);
                if (SC_Utils_Ex::isBlank($arrPayConfig)) {
                    SC_Util_PG_MULPAY_Ex::printLog('no mulpay payment payment_id:' . $objPage->tpl_payment_id);
                    return;
                }

                $objPage->plg_pg_mulpay_payid = $arrPayConfig[MDL_PG_MULPAY_PAYMENT_COL_PAYID];
                $objPage->arrPgMulpayJobCds = SC_Util_PG_MULPAY_Ex::getJobCds($objPage->plg_pg_mulpay_payid);

                SC_Util_PG_MULPAY_Ex::printLog('is mulpay payment payment_id:' . $objPage->tpl_payment_id . ' pay_id:' . $objPage->plg_pg_mulpay_payid);

                $objFormParam = new SC_FormParam_Ex();
                $this->lfInitParamPaymentInput($objFormParam, $objPage->plg_pg_mulpay_payid, $arrPayConfig);
                $objFormParam->setParam($_REQUEST);
                $objFormParam->convParam();
                switch ($objPage->getMode()) {
                    case 'edit':
                        $arrErr = $this->lfCheckErrorPaymentInput($objFormParam, $objPage->plg_pg_mulpay_payid);
                        $objPage->arrErr = array_merge($objPage->arrErr, (array)$arrErr);
                        if (SC_Utils_Ex::isBlank($objPage->arrErr)) {
                            SC_Util_PG_MULPAY_Ex::setPaymentTypeConfig($objPage->tpl_payment_id, $objFormParam->getHashArray());
                        } else {
                            SC_Util_PG_MULPAY_Ex::printLog($objPage->arrErr);
                            $objPage->tpl_onload = '';
                        }
                        break;
                    default:
                        break;
                }
                $objPage->arrForm = array_merge($objPage->arrForm, (array)$objFormParam->getFormParamList());
            }
            break;
        }
    }

    function lfCheckPayment(&$arrPayment, &$objPage) {
        if (SC_Utils_Ex::isBlank($arrPayment)) {
            return;
        }
        $objPage->arrPgPayConfig = array();
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();
        $arrConfig = $objMdl->getSubData();

        foreach($arrPayment as $key => $data) {
            $arrPayConfig = SC_Util_PG_MULPAY_Ex::getPaymentTypeConfig($data['payment_id']);
            $objPage->arrPgPayConfig[$payment_id] = $arrPayConfig;
            $pay_id = $arrPayConfig[MDL_PG_MULPAY_PAYMENT_COL_PAYID];

            if ($pay_id == MDL_PG_MULPAY_PAYID_REGIST_CREDIT) {
                $objCustomer = new SC_Customer_Ex();
                if(!$objCustomer->isLoginSuccess(true)) {
                    unset($arrPayment[$key]);
                } else if ($objPage->tpl_plg_pg_mulpay_is_subscription && $arrConfig['subs']['subs_after_payment'] == '1') {
                    unset($arrPayment[$key]);
                } else {
                    $objClient = new SC_Mdl_PG_MULPAY_Client_Member_Ex();
                    $ret = $objClient->searchCard(array('customer_id' => $objCustomer->getValue('customer_id')));
                    if(!$ret) {
                        unset($arrPayment[$key]);
                    } else {
                        $arrResults = $objClient->getResults();
                        if(SC_Utils_Ex::isBlank($arrResults['CardSeq'])) {
                            unset($arrPayment[$key]);
                        }
                    }
                }
            } else if ($pay_id == MDL_PG_MULPAY_PAYID_IDNET) {
                if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_PC) {
                    unset($arrPayment[$key]);
                }

            } else if ($pay_id == MDL_PG_MULPAY_PAYID_PAYPAL) {
                if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
                    unset($arrPayment[$key]);
                }
            }
        }
        $arrTemp = $arrPayment;
        $arrPayment = array();
        foreach($arrTemp as $data) {
            $arrPayment[] = $data;
        }
    }

    function lfCheckErrorPaymentInput(&$objFormParam, $pay_id) {
        $arrRet =  $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrRet);
        $objErr->arrErr = $objFormParam->checkError();
        $objErr->doFunc(array('お問合せ先電話番号', 'ReceiptsDisp12_1', 'ReceiptsDisp12_2', 'ReceiptsDisp12_3'), array('TEL_CHECK'));
        $objErr->doFunc(array('表示電話番号', 'ServiceTel_1', 'ServiceTel_2', 'ServiceTel_3'), array('TEL_CHECK'));

        if (!SC_Utils_Ex::isBlank($arrRet['PaymentTermDay']) && $arrRet['PaymentTermDay'] > PAYMENT_TERM_DAY_MAX) {
            $objErr->arrErr['PaymentTermDay'] = '※ 支払い期限は最大' . PAYMENT_TERM_DAY_MAX . '日まで設定可能です。';
        }
        if (!SC_Utils_Ex::isBlank($arrRet['PaymentTermSec']) && $arrRet['PaymentTermSec'] > PAYMENT_TERM_SEC_MAX) {
            $objErr->arrErr['PaymentTermDay'] = '※ 支払い期限は最大' . PAYMENT_TERM_SEC_MAX . '秒まで設定可能です。';
        }

        if (!SC_Utils_Ex::isBlank($arrRet['ReceiptsDisp11'])) {
            for ($i = 0; $i < mb_strlen($arrRet['ReceiptsDisp11']); $i++) {
                $tmp = mb_substr($arrRet['ReceiptsDisp11'], $i , 1);
                if (SC_Util_PG_MULPAY_Ex::isProhibitedChar($tmp)) {
                   $objErr->arrErr['ReceiptsDisp11'] = '※ お問い合わせ先に禁止されている文字「' . $tmp . '」が含まれています。
';
                }
            }
            if (SC_Utils_Ex::isBlank($objErr->arrErr['ReceiptsDisp11'])) {
                $substr_byte_str = SC_Util_PG_MULPAY_Ex::subString($arrRet['ReceiptsDisp11'], RECEIPT_DISP11_LEN);
                if ($substr_byte_str !== $arrRet['ReceiptsDisp11']) {
                    $objErr->arrErr['ReceiptsDisp11'] = '※ お問い合わせ先には' . RECEIPT_DISP11_LEN . 'byte以内の文字列を設定して下さい。';
                }
            }
        }

        if (!SC_Utils_Ex::isBlank($arrRet['DocomoDisp1'])) {
            for ($i = 0; $i < mb_strlen($arrRet['DocomoDisp1']); $i++) {
                $tmp = mb_substr($arrRet['DocomoDisp1'], $i , 1);
                if (SC_Util_PG_MULPAY_Ex::isProhibitedChar($tmp)) {
                   $objErr->arrErr['DocomoDisp1'] = '※ ドコモ表示項目1に禁止されている文字「' . $tmp . '」が含まれています。';
                }
            }
        }
        if (!SC_Utils_Ex::isBlank($arrRet['DocomoDisp2'])) {
            for ($i = 0; $i < mb_strlen($arrRet['DocomoDisp2']); $i++) {
                $tmp = mb_substr($arrRet['DocomoDisp2'], $i , 1);
                if (SC_Util_PG_MULPAY_Ex::isProhibitedChar($tmp)) {
                   $objErr->arrErr['DocomoDisp2'] = '※ ドコモ表示項目2に禁止されている文字「' . $tmp . '」が含まれています。';
                }
            }
        }

        if (!SC_Utils_Ex::isBlank($arrRet['TdTenantName'])) {
            if (SC_Util_PG_MULPAY_Ex::convTdTenantName($arrRet['TdTenantName']) == '') {
                $objErr->arrErr['TdTenantName'] = '※ 変換後のデータが25byte以内の必要があります。文字によってバイト数が変動しますので文字を変える等して下さい。';
            }
        }

        return $objErr->arrErr;
    }

    function lfInitParamPaymentInput(&$objFormParam, $pay_id, &$arrPayConfig) {

        if ($pay_id == MDL_PG_MULPAY_PAYID_CREDIT || $pay_id == MDL_PG_MULPAY_PAYID_REGIST_CREDIT
            || $pay_id == MDL_PG_MULPAY_PAYID_CREDIT_CHECK || $pay_id == MDL_PG_MULPAY_PAYID_CREDIT_SAUTH
            || $pay_id == MDL_PG_MULPAY_PAYID_PAYPAL || $pay_id == MDL_PG_MULPAY_PAYID_IDNET
            || $pay_id == MDL_PG_MULPAY_PAYID_AU || $pay_id == MDL_PG_MULPAY_PAYID_SB
            || $pay_id == MDL_PG_MULPAY_PAYID_DOCOMO) {
            $key = 'JobCd';
            $objFormParam->addParam('処理区分', $key , STEXT_LEN, 'a',
                 array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'ALNUM_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
        }
        
        if ($pay_id == MDL_PG_MULPAY_PAYID_CREDIT || $pay_id == MDL_PG_MULPAY_PAYID_REGIST_CREDIT
                || $pay_id == MDL_PG_MULPAY_PAYID_CREDIT_CHECK || $pay_id == MDL_PG_MULPAY_PAYID_CREDIT_SAUTH) {
            $key = 'credit_pay_methods';
            $objFormParam->addParam('支払種別', $key, INT_LEN, 'a',
                    array('EXIST_CHECK', 'MAX_LENGTH_CHECK'),
                    SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                        );
        }

        $key = 'enable_mail';
        $objFormParam->addParam('メール送信有無', $key, INT_LEN, 'n',
                array('MAX_LENGTH_CHECK', 'NUM_CHECK'),
                SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '0' : $arrPayConfig[$key]
                );

        $key = 'enable_cvs_mails';
        $objFormParam->addParam('コンビニメール送信有無', $key, INT_LEN, 'n',
                array('MAX_LENGTH_CHECK', 'NUM_CHECK'),
                SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '0' : $arrPayConfig[$key]
                );


        $key = 'use_securitycd';
        $objFormParam->addParam('セキュリティコード入力必須化', $key , INT_LEN, 'n',
             array('MAX_LENGTH_CHECK', 'NUM_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '0' : $arrPayConfig[$key]
             );

        $key = 'use_securitycd_option';
        $objFormParam->addParam('セキュリティコード空許可', $key , INT_LEN, 'n',
             array('MAX_LENGTH_CHECK', 'NUM_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '0' : $arrPayConfig[$key]
             );

        $key = 'TdFlag';
        $objFormParam->addParam('本人認証サービス', $key , INT_LEN, 'n',
             array('MAX_LENGTH_CHECK', 'NUM_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '0' : $arrPayConfig[$key]
             );

        $key = 'TdTenantName';
        $objFormParam->addParam('3Dセキュア表示店舗名', $key , TDTENANT_NAME_LEN, 'a',
             array('MAX_LENGTH_CHECK', 'GRAPH_CHECK', 'SPTAB_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
             );

        $key = 'ClientField1';
        $objFormParam->addParam('自由項目1', $key , CLIENT_FIELD_LEN, 'KVa',
             array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
             );

        $key = 'ClientField2';
        $objFormParam->addParam('自由項目2', $key , CLIENT_FIELD_LEN, 'KVa',
             array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
             );

        if ($pay_id == MDL_PG_MULPAY_PAYID_CVS) {
            $key = 'conveni';
            $objFormParam->addParam('コンビニ選択', $key , INT_LEN, 'n',
                 array('MAX_LENGTH_CHECK', 'EXIST_CHECK', 'NUM_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
        }

        $key = 'PaymentTermDay';
        $objFormParam->addParam('支払期限', $key , PAYMENT_TERM_DAY_LEN, 'n',
             array('MAX_LENGTH_CHECK', 'NUM_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
             );

        $key = 'PaymentTermSec';
        $objFormParam->addParam('支払期限', $key , PAYMENT_TERM_SEC_LEN, 'n',
             array('MAX_LENGTH_CHECK', 'NUM_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
             );

        for ($i = 1; $i <= 8; $i++) {
            $key = 'RegisterDisp' . $i;
            if ($pay_id == MDL_PG_MULPAY_PAYID_CVS) {
                $name = 'POSレジ表示欄' . $i;
                $objFormParam->addParam($name, $key , REGISTER_DISP_LEN, 'KASV',
                     array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
                     SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                     );
            } else if ($pay_id == MDL_PG_MULPAY_PAYID_ATM) {
                $name = 'ATM表示欄' . $i;
                $objFormParam->addParam($name, $key , REGISTER_DISP_LEN, 'KASV',
                     array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
                     SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                     );
            }
        }

        for ($i = 1; $i <= 10; $i++) {
            $key = 'ReceiptsDisp' . $i;
            if ($pay_id == MDL_PG_MULPAY_PAYID_CVS) {
                $name = 'POSレジ表示欄' . $i;
                $objFormParam->addParam($name, $key , RECEIPT_DISP_LEN, 'KASV',
                     array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
                     SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                     );
            } else if ($pay_id == MDL_PG_MULPAY_PAYID_PAYEASY || $pay_id == MDL_PG_MULPAY_PAYID_ATM) {
                $name = '利用明細表示欄' . $i;
                $objFormParam->addParam($name, $key , RECEIPT_DISP_LEN, 'KASV',
                     array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
                     SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                     );
            }
        }

        if ($pay_id == MDL_PG_MULPAY_PAYID_CVS || $pay_id == MDL_PG_MULPAY_PAYID_PAYEASY || $pay_id == MDL_PG_MULPAY_PAYID_ATM) {
            $key = 'ReceiptsDisp11';
            $name = 'お問合せ先';
            $objFormParam->addParam($name, $key , RECEIPT_DISP11_LEN, 'KV',
                 array('MAX_LENGTH_CHECK', 'SPTAB_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
            $key = 'ReceiptsDisp12_1';
            $name = 'お問合せ先電話番号1';
            $objFormParam->addParam($name, $key , TEL_NO_LEN, 'n',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
            $key = 'ReceiptsDisp12_2';
            $name = 'お問合せ先電話番号2';
            $objFormParam->addParam($name, $key , TEL_NO_LEN, 'n',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
            $key = 'ReceiptsDisp12_3';
            $name = 'お問合せ先電話番号3';
            $objFormParam->addParam($name, $key , TEL_NO_LEN, 'n',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );

            $key = 'ReceiptsDisp13_1';
            $name = 'お問合せ先受付時間1';
            $objFormParam->addParam($name, $key , 2, 'n',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
            $key = 'ReceiptsDisp13_2';
            $name = 'お問合せ先受付時間2';
            $objFormParam->addParam($name, $key , 2, 'n',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
            $key = 'ReceiptsDisp13_3';
            $name = 'お問合せ先受付時間3';
            $objFormParam->addParam($name, $key , 2, 'n',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
            $key = 'ReceiptsDisp13_4';
            $name = 'お問合せ先受付時間4';
            $objFormParam->addParam($name, $key , 2, 'n',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
        }

        $key = 'EdyAddInfo1';
        $objFormParam->addParam('決済開始メール付加情報', $key , EDY_ADDINFO1_LEN, '',
             array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
             );

        $key = 'EdyAddInfo2';
        $objFormParam->addParam('決済完了メール付加情報', $key , EDY_ADDINFO2_LEN, '',
             array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
             );

        $key = 'SuicaAddInfo1';
        $objFormParam->addParam('決済開始メール付加情報', $key , SUICA_ADDINFO_LEN, '',
             array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
             );

        $key = 'SuicaAddInfo2';
        $objFormParam->addParam('決済完了メール付加情報', $key , SUICA_ADDINFO_LEN, '',
             array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
             );

        $key = 'SuicaAddInfo3';
        $objFormParam->addParam('決済内容確認画面付加情報', $key , SUICA_ADDINFO_LEN, '',
             array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
             );

        $key = 'SuicaAddInfo4';
        $objFormParam->addParam('決済完了画面付加情報', $key , SUICA_ADDINFO_LEN, '',
             array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
             );

        $key = 'Currency';
        $objFormParam->addParam('通貨コード', $key , 3, 'a',
             array('MAX_LENGTH_CHECK', 'ALNUM_CHECK', 'SPTAB_CHECK'),
             SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? 'JPY' : $arrPayConfig[$key]
             );

        if ($pay_id == MDL_PG_MULPAY_PAYID_AU || $pay_id == MDL_PG_MULPAY_PAYID_AUCONTINUANCE) {
            $key = 'ServiceName';
            $objFormParam->addParam('サービス名', $key , 48, 'KVSA',
                 array('MAX_LENGTH_CHECK', 'SPTAB_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
            $key = 'ServiceTel_1';
            $objFormParam->addParam('表示電話番号1', $key , TEL_NO_LEN, 'a',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
            $key = 'ServiceTel_2';
            $objFormParam->addParam('表示電話番号2', $key , TEL_NO_LEN, 'a',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
            $key = 'ServiceTel_3';
            $objFormParam->addParam('表示電話番号3', $key , TEL_NO_LEN, 'a',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
        }

        if ($pay_id == MDL_PG_MULPAY_PAYID_DOCOMO || $pay_id == MDL_PG_MULPAY_PAYID_DOCOMOCONTINUANCE) {
            $key = 'DocomoDisp1';
            $objFormParam->addParam('ドコモ表示項目1', $key , 40, 'KVSA',
                 array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
            $key = 'DocomoDisp2';
            $objFormParam->addParam('ドコモ表示項目2', $key , 40, 'KVSA',
                 array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
        }

        if ($pay_id == MDL_PG_MULPAY_PAYID_DOCOMOCONTINUANCE) {
            $key = 'ConfirmBaseDate';
            $objFormParam->addParam('確定基準日', $key , 2, 'n',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );

        }

        if ($pay_id == MDL_PG_MULPAY_PAYID_PAYEASY) {
            $key = 'SelectPageCall_PC';
            $objFormParam->addParam('ネットバンキング用金融機関選択画面(PC)', $key , URL_LEN, '',
                 array('MAX_LENGTH_CHECK', 'SPTAB_CHECK', 'URL_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
            $key = 'SelectPageCall_Mobile';
            $objFormParam->addParam('ネットバンキング用金融機関選択画面(携帯)', $key , URL_LEN, '',
                 array('MAX_LENGTH_CHECK', 'SPTAB_CHECK', 'URL_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
        }

        if ($pay_id == MDL_PG_MULPAY_PAYID_AUCONTINUANCE) {
            $key = 'AccountTiming';
            $objFormParam->addParam('課金タイミング', $key , 2, 'a',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
            $key = 'AccountTimingKbn';
            $objFormParam->addParam('課金タイミング区分', $key , 2, 'a',
                 array('MAX_LENGTH_CHECK', 'NUM_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '02' : sprintf('%02d',$arrPayConfig[$key])
                 );
            $key = 'Commodity';
            $objFormParam->addParam('摘要', $key , 48, 'KVSA',
                 array('MAX_LENGTH_CHECK', 'SPTAB_CHECK', 'EXIST_CHECK'),
                 SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '' : $arrPayConfig[$key]
                 );
        }

        $key = 'order_mail_title1';
        if (!isset($arrPayConfig[$key])) {
            $def_title = 'お支払いについて';
        } else {
            $def_title = '';
        }
        $objFormParam->addParam('決済完了案内タイトル', $key, STEXT_LEN, '',
            array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
            SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? $def_title : $arrPayConfig[$key]
            );

        $key = 'order_mail_body1';
        $objFormParam->addParam('決済完了案内本文', $key, MLTEXT_LEN, '',
            array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
            SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? $this->lfGetMailDefBody($pay_id) : $arrPayConfig[$key]
            );
        
        if ($pay_id == MDL_PG_MULPAY_PAYID_CVS) {
            $arrCONVENI = SC_Util_PG_MULPAY_Ex::getConveni();
            foreach ($arrCONVENI as $key => $name) {
                $ckey = 'order_mail_title_' . $key;
                if (!isset($arrPayConfig[$key])) {
                    $def_title = $name . 'でのお支払い';
                }
                $objFormParam->addParam($name . '決済完了案内タイトル', $ckey, STEXT_LEN, '',
                     array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
                     SC_Utils_Ex::isBlank($arrPayConfig[$ckey]) ? $def_title : $arrPayConfig[$ckey]
                     );
                $ckey = 'order_mail_body_' . $key;
                $objFormParam->addParam($name . '決済完了案内本文', $ckey, MLTEXT_LEN, '',
                     array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
                     SC_Utils_Ex::isBlank($arrPayConfig[$ckey]) ? $this->lfGetMailDefBody($pay_id, $key) : $arrPayConfig[$ckey]
                     );
            }
        }

    }

    function lfGetMailDefBody($pay_id, $cvs_id = '') {
        $arrPaymentTypeCodes = SC_Util_PG_MULPAY_Ex::getPaymentTypeCodes();
        if (SC_Utils_Ex::isBlank($cvs_id)) {
            $filename = strtolower($arrPaymentTypeCodes[$pay_id]) . '.tpl';
        }else{
            $filename = strtolower($arrPaymentTypeCodes[$pay_id]) . '_' . $cvs_id . '.tpl';
        }
        $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'admin/mail/';
        if (is_file($template_dir . $filename)) {
            return file_get_contents($template_dir . $filename);
        }
        return '';
    }

}
