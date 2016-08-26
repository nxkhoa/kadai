<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->

受注情報で支払い方法が一致しない決済通知を受信しました。
対象の受注に対して通知データが処理出来ませんでした。

受注ID: <!--{$arrParam.order_id|h}-->
決済オーダーID： <!--{$arrParam.OrderID|h}-->
通知決済方法: <!--{$arrParam.pay_type|h}-->
受注決済方法: <!--{$arrParam.payment_method|h}-->

利用金額: <!--{$arrParam.Amount|h}-->円
受付日時: <!--{$arrParam.ReceiptDate|h}-->
処理日時: <!--{$arrParam.TranDate|h}-->

大変お手数ですが、ご確認お願い致します。

GMO-PGから結果通知プログラムURLに結果を返却した際、EC-CUBE側
の受注データの決済方法と、通知された決済の種類(PayType)が異な
るため「不一致」となり、本メールが送信されています。

まずは、EC-CUBE管理画面とPGマルチペイメントサービスのショップ
管理画面とで決済データをご確認いただき、決済結果に相違がないこと
をご確認ください。

