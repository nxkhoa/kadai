<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

require_once(MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php');

/**
 * 決済モジュール 決済処理 基底クラス
 */
class SC_Mdl_PG_MULPAY_Client_Base {
    var $arrErr = array();
    var $arrResults = null;

    function getSendData($arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting) {
        $arrSendData = array();
        foreach ($arrSendKey as $key) {
            switch ($key) {
            case 'ShopID':
            case 'ShopPass':
                $arrSendData[$key] = $arrMdlSetting[$key];
                break;
            case 'MemberID':
                $arrSendData[$key] = $arrOrder['customer_id'];
                break;
            case 'SiteID':
                $arrSendData[$key] = $arrMdlSetting['site_id'];
                break;
            case 'SitePass':
                $arrSendData[$key] = $arrMdlSetting['site_pass'];
                break;
            case 'ClientField3':
                // ※この部分の表記などについて修正・削除等、一切の変更は絶対に行わないで下さい。
                // 問題発生時の調査や解決などに支障が出るため、変更された場合はサポート等が
                // 出来ない場合がございます。
                $arrSendData[$key] = 'EC-CUBE' . MDL_PG_MULPAY_VERSION;
                // 修正不可ここまで 
                break;
            case 'CancelAmount':
            case 'Amount':
            case 'FirstAmount':
                $arrSendData[$key] = $arrOrder['payment_total'];
                break;
            case 'TdTenantName':
                $arrSendData[$key] = SC_Util_PG_MULPAY_Ex::convTdTenantName($arrPaymentInfo[$key]);
                if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE) {
                    $arrSendData[$key] = '';
                }
                break;
            case 'Expire':
                $arrSendData[$key] = $arrParam['Expire_year'] . $arrParam['Expire_month'];
                break;
            case 'Method':
                list($id, $num) = explode('-', $arrParam[$key]);
                $arrSendData[$key] = $id;
                if ($num > 0) {
                    $arrSendData['PayTimes'] = $num;
                }
                break;
            case 'ClientFieldFlag':
                $arrSendData[$key] = '1';
                break;
            case 'TdFlag':
                $arrSendData[$key] = $arrPaymentInfo[$key];
                if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE) {
                    $arrSendData[$key] = '0';
                }
                break;
            case 'HttpAccept':
                $arrSendData[$key] = $_SERVER['HTTP_ACCEPT'];
                break;
            case 'HttpUserAgent':
                $arrSendData[$key] = $_SERVER['HTTP_USER_AGENT'];
                break;
            case 'DeviceCategory':
                $arrSendData[$key] = '0';
                break;
            case 'MemberName':
                if (!SC_Utils_Ex::isBlank($arrOrder['secret_key'])) {
                    $arrSendData[$key] = $arrOrder['secret_key'];
                } else if (!SC_Utils_Ex::isBlank($arrOrder['customer_id']) && $arrOrder['customer_id'] != '0')  {
                    $arrCustomer = SC_Helper_Customer_Ex::sfGetCustomerData($arrOrder['customer_id']);
                    $arrSendData[$key] = $arrCustomer['secret_key'];
                }
                break;
            case 'CustomerName':
                $arrSendData[$key] = SC_Util_PG_MULPAY_Ex::convCVSText($arrOrder['order_name01'] . $arrOrder['order_name02']);
                break;
            case 'CustomerKana':
                $arrSendData[$key] = SC_Util_PG_MULPAY_Ex::convCVSText($arrOrder['order_kana01'] . $arrOrder['order_kana02']);
                break;
            case 'TelNo':
                $arrSendData[$key] = $arrOrder['order_tel01'] . '' . $arrOrder['order_tel02'] . '' . $arrOrder['order_tel03'];
                break;
            case 'MailAddress':
                if ($arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID] == MDL_PG_MULPAY_PAYID_CVS
                        && $arrPaymentInfo['enable_mail'] == '1'
                        && !SC_Utils_Ex::isBlank($arrPaymentInfo['enable_cvs_mails'])
                        && array_search($arrParam['Convenience'], $arrPaymentInfo['enable_cvs_mails']) !== FALSE) {
                    // CVS決済の場合は、個別のコンビニ設定も確認する。
                    $arrSendData[$key] = $arrOrder['order_email'];
                } else if ($arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID] == MDL_PG_MULPAY_PAYID_MOBILESUICA
                            || $arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID] == MDL_PG_MULPAY_PAYID_MOBILEEDY) {
                    if (!SC_Utils_Ex::isBlank($arrParam['MailAddress'])) {
                        $arrSendData[$key] = $arrParam['MailAddress'];
                    } else {
                        $arrSendData[$key] = $arrOrder['order_email'];
                    }
                } else if ($arrPaymentInfo['enable_mail'] == '1'
                        && $arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID] != MDL_PG_MULPAY_PAYID_CVS) {
                    if (!SC_Utils_Ex::isBlank($arrParam['MailAddress'])) {
                        $arrSendData[$key] = $arrParam['MailAddress'];
                    } else {
                        $arrSendData[$key] = $arrOrder['order_email'];
                    }
                }
                break;
            case 'ShopMailAddress':
                $arrSiteInfo = SC_Helper_DB_Ex::sfGetBasisData();
                $arrSendData[$key] = $arrSiteInfo['email01'];
                break;
            case 'ReserveNo':
                $arrSendData[$key] = $arrOrder['order_id'];
                break;
            case 'MemberNo':
            case 'MemberID':
                if (!SC_Utils_Ex::isBlank($arrOrder['customer_id']) && $arrOrder['customer_id'] != '0' ) {
                    $arrSendData[$key] = $arrOrder['customer_id'];
                } else {
                    $arrSendData[$key] = '';
                }
                break;
            case 'ServiceTel':
                $arrSendData[$key] = $arrPaymentInfo['ServiceTel_1'] . '' . $arrPaymentInfo['ServiceTel_2'] . '' . $arrPaymentInfo['ServiceTel_3'];
                break;
            case 'ReceiptsDisp12':
                $arrSendData[$key] = $arrPaymentInfo['ReceiptsDisp12_1'] . '' . $arrPaymentInfo['ReceiptsDisp12_2'] . '' . $arrPaymentInfo['ReceiptsDisp12_3'];
                break;
            case 'ReceiptsDisp13':
                $arrSendData[$key] = sprintf('%02d', $arrPaymentInfo['ReceiptsDisp13_1']) . ':' . sprintf('%02d', $arrPaymentInfo['ReceiptsDisp13_2']) . '-' . sprintf('%02d', $arrPaymentInfo['ReceiptsDisp13_3']) . ':' . sprintf('%02d', $arrPaymentInfo['ReceiptsDisp13_4']);
                break;
            case 'ItemName':
            case 'Commodity':
                if ($arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID] == MDL_PG_MULPAY_PAYID_AUCONTINUANCE) {
                    $arrSendData[$key] = $arrPaymentInfo[$key];
                } else {
                    $arrSendData[$key] = $this->getItemName($arrOrder['order_id']);
                }
                break;
            case 'RetURL':
            case 'RedirectURL':
                $arrSendData[$key] = SC_Utils_Ex::sfRmDupSlash(HTTPS_URL .'/shopping/load_payment_module.php?mode=pgreturn&order_id=' . $arrOrder['order_id'] . '&' . TRANSACTION_ID_NAME .  '=' . SC_Helper_Session_Ex::getToken());;
                if (SC_Display_Ex::detectDevice() === DEVICE_TYPE_MOBILE
                        || $arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID] == MDL_PG_MULPAY_PAYID_DOCOMO) {
                    // PC→携帯があるためドコモの場合セッションＩＤ付与 PCと携帯でセッション名が異なる問題がある
//                    $arrSendData[$key] .= '&' . session_name() . '=' . session_id();
                     $arrSendData[$key] .= '&' . ini_get("session.name") . '=' . session_id();
                }
                break;
            case 'CreateMember':
                $arrSendData[$key] = '1';
                break;
            case 'HolderName':
                if (SC_Utils_Ex::isBlank($arrParam['card_name1'])) {
                    $arrSendData[$key] = $arrParam['HolderName'];
                } else {
                    $arrSendData[$key] = $arrParam['card_name1'] . ' ' . $arrParam['card_name2'];
                }
                break;
            case 'FirstAccountDate':
                $arrPluginConfig = SC_Util_PG_MULPAY_Ex::getPluginConfig('PgCarrierSubs');
                if (!SC_Utils_Ex::isBlank($arrPluginConfig)) {
                    if ($arrPluginConfig['is_first_free'] == '1') {
                        $term = '+1 months';
                        if (date('d') > 28) {
                            $term .= ' -' .(date('d') - 28) . 'days';
                        }
                        $arrSendData[$key] = date('Ymd', strtotime($term));
                    } else {
                        $arrSendData[$key] = date('Ymd');
                    }
                }
                break;
            case 'FirstMonthFreeFlag':
                $arrPluginConfig = SC_Util_PG_MULPAY_Ex::getPluginConfig('PgCarrierSubs');
                if (!SC_Utils_Ex::isBlank($arrPluginConfig)) {
                    if ($arrPluginConfig['is_first_free'] == '1') {
                        $arrSendData[$key] = '1';
                    } else {
                        $arrSendData[$key] = '0';
                    }
                }
                break;
            case 'LastMonthFreeFlag':
                $arrSendData[$key] = '0';// 終了月無料にしない
                break;
            default:
                if (isset($arrParam[$key])) {
                    $arrSendData[$key] = $arrParam[$key];
                } elseif (isset($arrOrder[$key])) {
                    $arrSendData[$key] = $arrOrder[$key];
                } elseif (isset($arrPaymentInfo[$key])) {
                    $arrSendData[$key] = $arrPaymentInfo[$key];
                } elseif (isset($arrMdlSetting[$key])) {
                    $arrSendData[$key] = $arrMdlSetting[$key];
                }
            }
        }
        return $arrSendData;
    }

    function getItemName($order_id) {
        $arrOrderDetail = SC_Helper_Purchase_Ex::getOrderDetail($order_id, false);
        $ret = '';
        $ret = $arrOrderDetail[0]['product_name'];
        $ret = SC_Util_PG_MULPAY_Ex::convertProhibitedKigo($ret);
        $ret = SC_Util_PG_MULPAY_Ex::convertProhibitedChar($ret);
        $ret = mb_convert_kana($ret, 'KVSA', 'UTF-8');

        $ret = SC_Util_PG_MULPAY_Ex::subString($ret, SUICA_ITEM_NAME_LEN);
        return $ret;
    }

    function sendOrderRequest($url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting) {
        $arrReqParam = $arrParam;
        $arrOrder = SC_Util_PG_MULPAY_Ex::getOrderPayData($arrOrder['order_id']);
        if (SC_Utils_Ex::isBlank($arrOrder['OrderID'])) {
            $arrOrder['OrderID'] = $arrOrder['order_id'] . '-' . date('dHis');
            $OrderID = $arrOrder['OrderID'];
        }

        $arrSendData = $this->getSendData($arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        $ret = $this->sendRequest($url, $arrSendData);

        if ($ret) {
            $arrParam = $this->getResults();
        }else {
            $arrParam = array();
            $arrParam[0]['request_error'] = $this->getError();
        }

        $arrParam[0]['OrderID'] = $OrderID;
        $arrParam[0]['Amount'] = $arrOrder['payment_total'];

        if (!SC_Utils_Ex::isBlank($arrPaymentInfo['JobCd'])) {
            $arrParam[0]['JobCd'] = $arrPaymentInfo['JobCd'];
        }

        if (!SC_Utils_Ex::isBlank($arrReqParam['CardSeq'])) {
            $arrParam[0]['CardSeq'] = $arrReqParam['CardSeq'];
        }

        if (!SC_Utils_Ex::isBlank($arrReqParam['action_status'])) {
            $arrParam[0]['action_status'] = $arrReqParam['action_status'];
        }
        if (!SC_Utils_Ex::isBlank($arrReqParam['pay_status'])) {
            $arrParam[0]['pay_status'] = $arrReqParam['pay_status'];
        }
        if (!SC_Utils_Ex::isBlank($arrReqParam['success_pay_status'])
            && SC_Utils_Ex::isBlank($this->getError())) {
            $arrParam[0]['pay_status'] = $arrReqParam['success_pay_status'];
        }else if (SC_Utils_Ex::isBlank($arrReqParam['fail_pay_status'])
            && !SC_Utils_Ex::isBlank($this->getError())) {
            $arrParam[0]['pay_status'] = $arrReqParam['fail_pay_status'];
        }

        SC_Util_PG_MULPAY_Ex::setOrderPayData($arrOrder, $arrParam);

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        // 成功時のみ表示用データの構築
        $this->setOrderPaymentViewData($arrOrder, $arrParam, $arrPaymentInfo);
        return true;
    }

    function sendRequest($url, $arrSendData) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();

        $objMdl->printLog('SendRequest:' . $url);
        $objMdl->printLog(SC_Util_PG_MULPAY_Ex::setMaskData($arrSendData));

        $objReq = new HTTP_Request($url);
        $objReq->setMethod('POST');
        foreach ($arrSendData as $key => $value) {
            $objReq->addPostData($key, mb_convert_encoding($value, 'SJIS-win', 'UTF-8'));
        }
        $ret = $objReq->sendRequest();
        if (PEAR::isError($ret)) {
            $msg = '通信エラー:' . $ret->getMessage();
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }

        $r_code = $objReq->getResponseCode();
        switch ($r_code) {
            case 200:
                break;
            case 404:
                $msg = 'レスポンスエラー:RCODE:' . $r_code;
                $objMdl->printLog($msg);
                $this->setError($msg);
                return false;
                break;
            case 500:
            default:
                $msg = '決済サーバーエラー:RCODE:' . $r_code;
                $objMdl->printLog($msg);
                $this->setError($msg);
                return false;
                break;
        }

        $response_body = $objReq->getResponseBody();
        if (SC_Utils_Ex::isBlank($response_body)) {
            $msg = 'レスポンスデータエラー: レスポンスがありません。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }

        $arrRet = $this->parseResponse($response_body);
        $this->setResults($arrRet);

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        return true;
    }

    function setError($msg) {
        $this->arrErr[] = $msg;
        $this->arrErr = array_unique($this->arrErr);
    }

    function getError() {
        return $this->arrErr;
    }

    /**
     * レスポンスを解析する
     *
     * @param string $string レスポンス
     * @return array 解析結果
     */
    function parseResponse($string) {
        $arrRet = array();
        $string = trim($string);
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        $objMdl->printLog($string);
        if (strpos($string, 'ACS=1') === 0) {
            $regex = '|^ACS=1&ACSUrl\=(.+?)&PaReq\=(.+?)&MD\=(.+?)$|';
            $ret = preg_match_all($regex, $string, $matches);
            if ($ret !== false && $ret > 0) {
                $arrRet[0]['ACS'] = '1';
                $arrRet[0]['ACSUrl'] = $matches[1][0];
                $arrRet[0]['PaReq']  = $matches[2][0];
                $arrRet[0]['MD']     = $matches[3][0];
            } else {
                $this->setError('本人認証サービスの実行に失敗しました。');
                $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
                $msg = '-> 3D response failed: ' . $string;
                $objMdl->printLog($msg);
            }
        }else {
            $arrTmpAnd = explode('&', $string);
            foreach($arrTmpAnd as $eqString) {
                // $eqString -> CardSeq=2|0|1, DefaultFlag=0|0|0...
                list($key, $val) = explode('=', $eqString);
                if (strpos($key, '<') !== FALSE || strpos($key, '>') !== FALSE) {
                    $this->setError('不正な返答が返されました。接続先を確認して下さい。');
                    continue;
                }

                // $val -> 2|0|1, 0|0|0, ...
                if (preg_match('/|/', $val)) {
                    $arrTmpl = explode('|', $val);
                    $max = count($arrTmpl);
                    for($i = 0; $i < $max; $i++) {
                        $arrRet[$i][$key] = trim($arrTmpl[$i]);
                    }
                // $val -> 2, 0, 1...
                } else {
                    $arrRet[0][$key] = trim($val);
                }
            }
        }
        if (isset($arrRet[0]['ErrCode'])) {
            $this->setError($this->createErrCode($arrRet));
        }
        return $arrRet;
    }


    /**
     * エラーコード文字列を構築する
     *
     * @param array $arrRet
     * @return string
     */
    function createErrCode($arrRet) {
        require_once(MDL_PG_MULPAY_CLASSEX_PATH . 'util_extends/SC_Util_GetErrorMsg_Ex.php');

        $objErrMsg = new SC_Util_GetErrorMsg_Ex();
        $msg = '';
        foreach($arrRet as $key => $ret) {
            if (is_array($ret)) {
                $arrErrMsg = $objErrMsg->lfGetErrorInformation($ret['ErrInfo']) ;
                $error_text = SC_Utils_Ex::isBlank($arrErrMsg['message']) ? $arrErrMsg['context'] : $arrErrMsg['message'];
                $msg .= $error_text . '(' . sprintf('%s-%s', $ret['ErrCode'], $ret['ErrInfo']) .'),';
            } else if ($key == 'ErrInfo') {
                if (preg_match('/|/', $ret)) {
                    $arrTmplInfo = explode('|', $ret);
                    $arrTmplCode = explode('|', $arrRet['ErrCode']);
                } else {
                    $arrTmplInfo = array($ret);
                    $arrTmplCode = array($ret['ErrCode']);
                }
                foreach ($arrTmplInfo as $key2 => $err) {
                    $arrErrMsg = $objErrMsg->lfGetErrorInformation($err);
                    $error_text = SC_Utils_Ex::isBlank($arrErrMsg['message']) ? $arrErrMsg['context'] : $arrErrMsg['message'];
                    $msg .= $error_text . '(' . sprintf('%s-%s', $arrTmplCode[$key2], $arrTmplInfo[$key2]) .'),';
                }
            }
        }
        $msg = substr($msg, 0, strlen($msg)-1); // 最後の,をカット
        return $msg;
    }

    function setResults($arrResults) {
        $objMdl =& SC_Mdl_PG_MULPAY_Ex::getInstance();
        if (SC_Utils_Ex::isBlank($arrResults)) {
            $this->setError('不正な返答が返されました。決済状態が不明です。管理者に確認して下さい。');
        }
        $objMdl->printLog($arrResults);
        $this->arrResults = $arrResults;
    }

    function getResults() {
        if (SC_Utils_Ex::isBlank($this->arrResults[0]) && !SC_Utils_Ex::isBlank($this->arrResults)) {
            return $this->arrResults;
        }
        return $this->arrResults[0];
    }

    function setOrderPaymentViewData($arrOrder, $arrParam, $arrPaymentInfo) {
        $arrData = array();
        $arrResult = $this->getResults();
        $arrOrder = array_merge($arrOrder, $arrResult);

        if (!SC_Utils_Ex::isBlank($arrOrder['Approve'])) {
            $arrData['Approve']['name'] = '承認番号';
            $arrData['Approve']['value'] = $arrOrder['Approve'];
        }

        if (!SC_Utils_Ex::isBlank($arrOrder['CustID'])) {
            $arrData['CustID']['name'] = 'お客様番号';
            $arrData['CustID']['value'] = $arrOrder['CustID'];
        }

        if (!SC_Utils_Ex::isBlank($arrOrder['BkCode'])) {
            $arrData['BkCode']['name'] = '収納機関番号';
            $arrData['BkCode']['value'] = $arrOrder['BkCode'];
        }

        if (!SC_Utils_Ex::isBlank($arrOrder['ConfNo'])) {
            $arrData['ConfNo']['name'] = '確認番号';
            $arrData['ConfNo']['value'] = $arrOrder['ConfNo'];
        }

        if (!SC_Utils_Ex::isBlank($arrOrder['ReceiptNo'])) {
            $arrData['ReceiptNo']['name'] = '受付番号';
            $arrData['ReceiptNo']['value'] = $arrOrder['ReceiptNo'];
        }

        if (!SC_Utils_Ex::isBlank($arrOrder['EdyOrderNo'])) {
            $arrData['EdyOrderNo']['name'] = 'Edy注文番号';
            $arrData['EdyOrderNo']['value'] = $arrOrder['EdyOrderNo'];
        }

        if (!SC_Utils_Ex::isBlank($arrOrder['ManagementNo'])) {
            $arrData['ManagementNo']['name'] = '管理番号';
            $arrData['ManagementNo']['value'] = $arrOrder['ManagementNo'];
        }

        if (!SC_Utils_Ex::isBlank($arrOrder['PayInfoNo'])) {
            $arrData['PayInfoNo']['name'] = '決済情報番号';
            $arrData['PayInfoNo']['value'] = $arrOrder['PayInfoNo'];
        }

        if (!SC_Utils_Ex::isBlank($arrOrder['PaymentTerm'])) {
            $arrData['PaymentTerm']['name'] = 'お支払い期限';
            $year = substr($arrOrder['PaymentTerm'],0,4);
            $month = substr($arrOrder['PaymentTerm'],4,2);
            $day = substr($arrOrder['PaymentTerm'],6,2);
            $hour = substr($arrOrder['PaymentTerm'],8,2);
            $min = substr($arrOrder['PaymentTerm'],10,2);
            $sec = substr($arrOrder['PaymentTerm'],12,2);
            $arrData['PaymentTerm']['value'] = $year . '年' . $month . '月' . $day . '日 ' . $hour . '時' . $min . '分';
        }

        if (!SC_Utils_Ex::isBlank($arrPaymentInfo['order_mail_title1']) && !SC_Utils_Ex::isBlank($arrPaymentInfo['order_mail_body1'])) {
            $arrData['order_mail_title1']['name'] = $arrPaymentInfo['order_mail_title1'];
            $arrData['order_mail_title1']['value'] = $arrPaymentInfo['order_mail_body1'];
        }

        if (!SC_Utils_Ex::isBlank($arrOrder['Convenience'])) {
            $title_key = 'order_mail_title_' . $arrOrder['Convenience'];
            $body_key = 'order_mail_body_' . $arrOrder['Convenience'];
            if (!SC_Utils_Ex::isBlank($arrPaymentInfo[$title_key])
                    && !SC_Utils_Ex::isBlank($arrPaymentInfo[$body_key])) {
                $arrData[$title_key]['name'] = $arrPaymentInfo[$title_key];
                $arrData[$title_key]['value'] = $arrPaymentInfo[$body_key];
            }
        }

        if (!SC_Utils_Ex::isBlank($arrData)) {
            $arrData['title']['value'] = '1';
            $arrData['title']['name'] = $arrPaymentInfo['payment_method'];
            $sqlval[MDL_PG_MULPAY_ORDER_COL_PAYVIEW] = serialize($arrData);
            $objPurchase = new SC_Helper_Purchase_Ex();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], null, null, null, $sqlval);
        }
    }


    function doPaymentRequest($arrParam) {
    }


    function getSendParam($arrData) {

    }


}

