<?php
/*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 */

/**
 * プラグインの処理クラス
 */
class LC_Pg2Click {

    function actionPrefilterTransform($class_name, &$source, &$objPage, $filename, $objPlugin) {
        $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("Pg2Click");
        if (SC_Utils_Ex::isBlank($plugin['free_field1'])) return;

        if(strpos($filename, 'cart/index.tpl') !== false) {
                $template_file = 'cart_index_2click_add.tpl';
                $objTransform = new SC_Helper_Transform($source);
                switch($objPage->arrPageLayout['device_type_id']){
                    case DEVICE_TYPE_MOBILE:
                        $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'mobile/';
                        $objTransform->select('form')->insertAfter(file_get_contents($template_dir . $template_file));
                        break;
                    case DEVICE_TYPE_SMARTPHONE:
                        $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'sphone/';
                        $objTransform->select('div.btn_area_btm')->appendChild(file_get_contents($template_dir . $template_file));
                        break;
                    case DEVICE_TYPE_PC:
                        $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'default/';
                        $objTransform->select('div.btn_area > ul')->appendChild(file_get_contents($template_dir . $template_file));
                        break;
                    default:
                        break;
                }

                $source = $objTransform->getHTML();
        } else if(strpos($filename, 'products/detail.tpl') !== false) {
                $template_file = 'products_detail_2click_add.tpl';
                $objTransform = new SC_Helper_Transform($source);
                switch ($objPage->arrPageLayout['device_type_id']){
                    case DEVICE_TYPE_MOBILE:
                        break;
                    case DEVICE_TYPE_SMARTPHONE:
                        $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'sphone/';
                        $objTransform->select('div#cartbtn_default')->insertAfter(file_get_contents($template_dir . $template_file));
                        break;
                    case DEVICE_TYPE_PC:
                        $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'default/';
                        $objTransform->select('div.cartin')->insertAfter(file_get_contents($template_dir . $template_file));
                        break;
                    default:
                        break;
                }

                $source = $objTransform->getHTML();
        } else if(strpos($filename, 'products/select_item.tpl') !== false) {
                $template_file = 'products_detail_2click_add.tpl';
                $objTransform = new SC_Helper_Transform($source);
                switch ($objPage->arrPageLayout['device_type_id']){
                    case DEVICE_TYPE_MOBILE:
                        $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'mobile/';
                        $objTransform->select('form')->insertAfter(file_get_contents($template_dir . $template_file));
                        break;
                    case DEVICE_TYPE_SMARTPHONE:
                        break;
                    case DEVICE_TYPE_PC:
                        break;
                    default:
                        break;
                }

                $source = $objTransform->getHTML();
        } else if(strpos($filename, 'products/list.tpl') !== false) {
                $template_file = 'products_list_2click_add.tpl';
                $objTransform = new SC_Helper_Transform($source);
                switch ($objPage->arrPageLayout['device_type_id']){
                    case DEVICE_TYPE_MOBILE:
                        break;
                    case DEVICE_TYPE_SMARTPHONE:
                        break;
                    case DEVICE_TYPE_PC:
                        $template_dir = MDL_PG_MULPAY_TEMPLATE_PATH . 'default/';
                        $objTransform->select('div.cartin_btn')->insertAfter(file_get_contents($template_dir . $template_file));
                        break;
                    default:
                        break;
                }

                $source = $objTransform->getHTML();
        }
    }

    function actionHook($class_name, $hook_point, &$objPage, $objPlugin) {
        $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("Pg2Click");
        if (SC_Utils_Ex::isBlank($plugin['free_field1'])) return;

        switch ($class_name) {
        case 'LC_Page_Products_Detail_Ex':
            if ($objPage->getMode() == 'plg_pg2click') {
                $_POST['mode'] = 'cart';
                $_REQUEST['mode'] = 'cart';
                $objSess = new SC_Session_Ex();
                $objSess->SetSession('plg_pg2click_payment_item', true);
            } else {
                $objSess = new SC_Session_Ex();
                $objSess->SetSession('plg_pg2click_payment_item', false);
            }
            break;
        case 'LC_Page_Products_List_Ex':
            if ($objPage->getMode() == 'plg_pg2click') {
                $_POST['mode'] = 'cart';
                $_REQUEST['mode'] = 'cart';
                $objSess = new SC_Session_Ex();
                $objSess->SetSession('plg_pg2click_payment_item', true);
            } else {
                $objSess = new SC_Session_Ex();
                $objSess->SetSession('plg_pg2click_payment_item', false);
            }
            break;
        case 'LC_Page_Cart_Ex':
            $objSess = new SC_Session_Ex();
            $objSess->SetSession('plg_pg2click_payment_confirm', false);
            if ($objPage->getMode() == 'up'
                    || $objPage->getMode() == 'down'
                    || $objPage->getMode() == 'setQuantity'
                    || $objPage->getMode() == 'delete') {
                $objSess->SetSession('plg_pg2click_payment', false);
            } else if ($objPage->getMode() == 'plg_pg2click') {
                $_POST['mode'] = 'confirm';
                $_REQUEST['mode'] = 'confirm';
                $objSess->SetSession('plg_pg2click_payment', true);
            } else {
                // 商品からの遷移の場合
                if ($objSess->GetSession('plg_pg2click_payment_item')) {
                    $objSess->SetSession('plg_pg2click_payment_item', false);

                    $objCartSess = new SC_CartSession_Ex();
                    $cartKeys = $objCartSess->getKeys();
                    if (count($cartKeys) == 1) {
                        $objSess->SetSession('plg_pg2click_payment', true);
                        $_POST['mode'] = 'confirm';
                        $_REQUEST['mode'] = 'confirm';
                        $_POST['cartKey'] = $cartKeys[0];
                        $_REQUEST['cartKey'] = $cartKeys[0];
                        $objSess = new SC_Session_Ex();
                        $objSess->SetSession('plg_pg2click_payment', true);
                    } else {
                        $objSess->SetSession('plg_pg2click_payment', false);
                    }
                } else {
                    $objSess->SetSession('plg_pg2click_payment', false);
                }
            }
            break;
        case 'LC_Page_Shopping_Ex':
            $objSess = new SC_Session_Ex();
            $objSess->SetSession('plg_pg2click_payment_confirm', false);
            if ($objPage->getMode() == 'nonmember_confirm'
                    || $objPage->getMode() == 'return'
                    || $objPage->getMode() == 'multiple') {

                if($objSess->GetSession('plg_pg2click_payment')) {
                    $objSess->SetSession('plg_pg2click_payment', false);
                }
            }
            break;
        case 'LC_Page_Shopping_Deliv_Ex':
            $objSess = new SC_Session_Ex();
            $objSess->SetSession('plg_pg2click_payment_confirm', false);
            if ($objPage->getMode() == 'delete'
                    || $objPage->getMode() == 'return'
                    || $objPage->getMode() == 'multiple') {

                if($objSess->GetSession('plg_pg2click_payment')) {
                    $objSess->SetSession('plg_pg2click_payment', false);
                }
            } else {
                if($objSess->GetSession('plg_pg2click_payment')) {
                    $_POST['mode'] = 'customer_addr';
                    $_REQUEST['mode'] = 'customer_addr';
                    $_POST['deliv_check'] = '-1';
                }
            }
            break;
        case 'LC_Page_Shopping_Payment_Ex':
            $objSess = new SC_Session_Ex();
            $objSess->SetSession('plg_pg2click_payment_confirm', false);
            if ($objPage->getMode() == 'select_deliv'
                    || $objPage->getMode() == 'return') {

                if($objSess->GetSession('plg_pg2click_payment')) {
                    $objSess->SetSession('plg_pg2click_payment', false);
                }
            } else {
                if($objSess->GetSession('plg_pg2click_payment')) {

                    $objSiteSess = new SC_SiteSession_Ex();
                    $objCartSess = new SC_CartSession_Ex();
                    $objPurchase = new SC_Helper_Purchase_Ex();
                    $objCustomer = new SC_Customer_Ex();

                    $is_multiple = $objPurchase->isMultiple();
                    $arrShipping = $objPurchase->getShippingTemp($is_multiple);
                    $tpl_uniqid = $objSiteSess->getUniqId();
                    $cart_key = $objCartSess->getKey();

                    if (class_exists('SC_Helper_Delivery_Ex')) {
                        $objDelivery = new SC_Helper_Delivery_Ex();
                        $arrDeliv = $objDelivery->getList($cart_key);
                    } else {
                        $arrDeliv = $objPurchase->getDeliv($cart_key);
                    }
                    $is_single_deliv = $objPage->isSingleDeliv($arrDeliv);

                    $arrOrderTemp = $objPurchase->getOrderTemp($tpl_uniqid);

                    $arrPrices = $objCartSess->calculate($cart_key, $objCustomer);

                    $deliv_id = $arrDeliv[0]['deliv_id'];

                    if (SC_Utils_Ex::isBlank($deliv_id)) {
                        return;
                    }

                    if (!method_exists($objPurchase, 'getPaymentsByPrice')) {
                        $arrSelectedDeliv = $objPage->getSelectedDeliv($objCartSess, $deliv_id);
                        $arrPayment = $arrSelectedDeliv['arrPayment'];
                    } else {
                        $total = $objCartSess->getAllProductsTotal($cart_key, $deliv_id);
                        $arrPayment = $objPurchase->getPaymentsByPrice($total, $deliv_id);
                    }

                    if(SC_Utils_Ex::isBlank($arrPayment)) {
                        $objSess = new SC_Session_Ex();
                        if($objSess->GetSession('plg_pg2click_payment')) {
                            $objSess->SetSession('plg_pg2click_payment', false);
                        }
                        return;
                    }
                    $target_payment_id = '';
                    if ($objCustomer->isLoginSuccess(true)) {
                        $objQuery =& SC_Query_Ex::getSingletonInstance();
                        $objQuery->setOrder('create_date desc');
                        $objQuery->setLimit('1');
                        $arrOldOrder = $objQuery->getRow('*', 'dtb_order', 'customer_id = ? and del_flg = 0 and status <> ?', array($objCustomer->getValue('customer_id'), ORDER_PENDING));
                        $target_payment_id = $arrOldOrder['payment_id'];
                        $arrPaymentInfo = SC_Util_PG_MULPAY_Ex::getPaymentInfo($target_payment_id);
                        if (!SC_Utils_Ex::isBlank($target_payment_id)) {
                            if (!SC_Utils_Ex::isBlank($arrOldOrder[MDL_PG_MULPAY_ORDER_COL_PAYDATA])) {
                                $arrOldPayData =  unserialize($arrOldOrder[MDL_PG_MULPAY_ORDER_COL_PAYDATA]);
                                $objSess = new SC_Session_Ex();
                                $objSess->SetSession('plg_pg2click_oldpaydata', $arrOldPayData);
                            }

                            $flag = false;
                            // カード決済時のみ登録カード決済に切り替えできるか確認
                            if ($arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT) {
                                $arrMulpayPayments = SC_Util_PG_MULPAY_Ex::getMulpayPayments();
                                foreach ($arrMulpayPayments as $arrMulpayPaymentData) {
                                    if ($arrMulpayPaymentData[MDL_PG_MULPAY_PAYMENT_COL_PAYID] == MDL_PG_MULPAY_PAYID_REGIST_CREDIT) {
                                        foreach ($arrPayment as $arrPaymentData) {
                                            if ($arrPaymentData['payment_id'] == $arrMulpayPaymentData['payment_id']) {
                                                $target_payment_id = $arrMulpayPaymentData['payment_id'];
                                                $arrPaymentInfo = SC_Util_PG_MULPAY_Ex::getPaymentInfo($target_payment_id);
                                                $flag = true;
                                                break;
                                            } 
                                        }
                                    }
                                }
                            }else {
                                foreach ($arrPayment as $arrPaymentData) {
                                    if ($arrPaymentData['payment_id'] == $target_payment_id) {
                                        $flag = true;
                                        break;
                                    }
                                }
                            }
                            if (!$flag) {
                                $target_payment_id = '';
                            }
                        }
                    }
                    if ($target_payment_id == '') {
                        foreach ($arrPayment as $arrPaymentData) {
                            $arrPaymentInfo = SC_Util_PG_MULPAY_Ex::getPaymentInfo($arrPaymentData['payment_id']);
                            if ($arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID] == MDL_PG_MULPAY_PAYID_REGIST_CREDIT) {
                                $target_payment_id = $arrPaymentData['payment_id'];
                                break;
                            }
                        }
                    }

                    if ($target_payment_id != '' && $arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID] == MDL_PG_MULPAY_PAYID_REGIST_CREDIT) {

                        if($objCustomer->isLoginSuccess(true)) {
                            $objClient = new SC_Mdl_PG_MULPAY_Client_Member_Ex();
                            $ret = $objClient->searchCard(array('customer_id' => $objCustomer->getValue('customer_id')));
                            if($ret) {
                                $arrResults = $objClient->getResults();
                                if(!SC_Utils_Ex::isBlank($arrResults['CardNo'])) {
                                    $target_payment_id = $arrPaymentData['payment_id'];
                                    $objSess = new SC_Session_Ex();
                                    $objSess->SetSession('plg_pg2click_payment_card', false);
                                    foreach ($objClient->arrResults as $arrCardData) {
                                        if ($arrCardData['DefaultFlag'] == '1') {
                                            $objSess->SetSession('plg_pg2click_payment_card', $arrCardData);
                                        }
                                    }
                                    if (!$objSess->GetSession('plg_pg2click_payment_card')) {
                                        $objSess->SetSession('plg_pg2click_payment_card', $arrResults);
                                    }
                                } else {
                                    $target_payment_id = '';
                                }
                            } else {
                                $target_payment_id = '';
                            }
                        }
                    }

                    if ($target_payment_id == '') {
                        foreach ($arrPayment as $arrPaymentData) {
                            $arrPaymentInfo = SC_Util_PG_MULPAY_Ex::getPaymentInfo($arrPaymentData['payment_id']);
                            if ($arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID] == MDL_PG_MULPAY_PAYID_CREDIT) {
                                $target_payment_id = $arrPaymentData['payment_id'];
                            }
                        }
                    }

                    if (!SC_Utils_Ex::isBlank($target_payment_id)) {
                        $_POST['mode'] = 'confirm';
                        $_REQUEST['mode'] = 'confirm';
                        $_POST['deliv_id'] = $deliv_id;
                        $_POST['payment_id'] = $target_payment_id;
                    }
                }
            }
            break;
        case 'LC_Page_Shopping_Confirm_Ex':
            if ($hook_point == 'before') { 
                if ($objPage->getMode() == 'return') {
                    $objSess = new SC_Session_Ex();
                    if($objSess->GetSession('plg_pg2click_payment')) {
                        $objSess->SetSession('plg_pg2click_payment', false);
                    }
                } else {
                    $objSess = new SC_Session_Ex();
                    if($objSess->GetSession('plg_pg2click_payment')) {
                        $objSess->SetSession('plg_pg2click_payment', false);
                        $objSess->SetSession('plg_pg2click_payment_confirm', true);
                    }
                }
            } else if ($hook_point == 'after') {
                $objSess = new SC_Session_Ex();
                if($objSess->GetSession('plg_pg2click_payment_confirm')) {
                    $arrPaymentInfo = SC_Util_PG_MULPAY_Ex::getPaymentInfo($objPage->arrForm['payment_id']);
                    if ($arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID] == MDL_PG_MULPAY_PAYID_REGIST_CREDIT) {
                        $arrCardData = $objSess->GetSession('plg_pg2click_payment_card');
                        if (!SC_Utils_Ex::isBlank($arrCardData)) {
                            $objPage->arrForm['payment_method'] .= "(カード番号:" . $arrCardData['CardNo']
                                . ' 有効期限:' . substr($arrCardData['Expire'],2,2) . '月/'
                                . substr($arrCardData['Expire'],0,2) . '年)';
                        }
                    } else if ($arrPaymentInfo[MDL_PG_MULPAY_PAYMENT_COL_PAYID] == MDL_PG_MULPAY_PAYID_CVS) {
                        $arrOldParam = $objSess->GetSession('plg_pg2click_oldpaydata');
                        if (!SC_Utils_Ex::isBlank($arrOldParam['Convenience'])) {
                            $arrCONVENI = SC_Util_PG_MULPAY_Ex::getConveni();
                            $objPage->arrForm['payment_method'] .=  ' ' . $arrCONVENI[$arrOldParam['Convenience']];
                        }
                    }
                }
            }
            break;
        }
    }
}
