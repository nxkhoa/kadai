<!--{*
 * Copyright(c) 2012 GMO Payment Gateway, Inc. All rights reserved.
 * http://www.gmo-pg.com/
 * Updated: 2012/07/30
 *}-->

受注情報で取引IDが一致しない決済通知を受信しました。

受注ID: <!--{$arrParam.order_id|h}-->
決済オーダーID： <!--{$arrParam.OrderID|h}-->
通知取引ID:  <!--{$arrParam.AccessID|h}-->
受注取引ID:  <!--{$arrOrder.AccessID|h}-->
利用金額: <!--{$arrParam.Amount|h}-->円
決済方法: <!--{$arrParam.pay_type|h}-->
受付日時: <!--{$arrParam.ReceiptDate|h}-->
処理日時: <!--{$arrParam.TranDate|h}-->

大変お手数ですが、ご確認お願い致します。

対象の受注に対して通知データは処理出来ましたが、取引IDが異なる
ため念のため多重決済などが無いか確認することを推奨いたします。

GMO-PGから結果通知プログラムURLに結果を返却した際、EC-CUBE側
の受注の取引ID(AccessID)が異なるため「不一致」となり、本メール
が送信されています。

まずは、EC-CUBE管理画面とPGマルチペイメントサービスのショップ
管理画面とで決済データをご確認いただき、決済結果に相違がないこと
をご確認ください。

