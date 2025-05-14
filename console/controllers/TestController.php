<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/22
 * Time: 2:29 PM
 */

namespace console\controllers;


use common\extensions\pay\easyeuro\Service;
use common\helpers\Email;
use common\helpers\Sms;
use common\models\BoutiqueGroup;
use common\models\Dropoff;
use common\models\Order;
use common\models\Pickup;
use common\models\TicketData;
use common\models\BoutiqueOrder;
use yii\console\Controller;
use yii;

class TestController extends Controller
{
    public function actionIndex()
    {
//        $order = Order::findOne([
//            'id' => '3',
//        ]);
//        Email::dropoffPaymentNotify($order);
    }

    public function actionBatchRefund()
    {
        /**
         * @var Order[] $orders
         */
        $orders = Order::find()->all();
        foreach ($orders as $order) {
            if ($order->channel == \common\definitions\Order::CHANNEL_EASYEURO) {

                try {
                    Yii::$app->easyeuro->refund([
                        'out_trade_no' => $order->sn . '_wechat',
                        'out_refund_no' => $order->sn . '_wechatr',
                        'total_fee' => YII_DEBUG ? 1 : (int)($order->real_amount * 100),
                        'refund_fee' => YII_DEBUG ? 1 : (int)($order->real_amount * 100),
                    ]);
                } catch (yii\base\UserException $e) {
                    echo 'wechat refund fail';
                }

                try {
                    Yii::$app->easyeuro->refund([
                        'out_trade_no' => $order->sn . '_alipay',
                        'out_refund_no' => $order->sn . '_alipayr',
                        'total_fee' => YII_DEBUG ? 1 : (int)($order->real_amount * 100),
                        'refund_fee' => YII_DEBUG ? 1 : (int)($order->real_amount * 100),
                    ]);
                } catch (yii\base\UserException $e) {
                    echo 'alipay refund fail';
                }
                echo PHP_EOL;

            }
        }
    }

    public function actionSms()
    {
        Yii::$app->externalVerifyCodeSms->sendSms('447410938889', ['code' => '123456']);
    }

    public function actionRefund($sn)
    {
        print_r(Yii::$app->order->refund($sn));
    }

    public function actionQuery($sn)
    {
        // 初始化globepay refund
        $result = Yii::$app->globepay->driver('globepayorderquery', [
            'orderId' => $sn,
        ])->orderQuery();

        print_r($result);
    }

    public function actionGoogle()
    {
        $result = Yii::$app->googlemap->getGeoCodeObject('E3 3TW, UK');

        print_r($result);
    }

    public function actionInvoice($order_id)
    {
        // 761 X 1076
        $order = Order::findOne($order_id);

        $logo = Yii::getAlias('@common/resources/images/logo.png');
        $fonts = Yii::getAlias('@common/resources/fonts/DroidSansFallback.ttf');
        $chineseFont = \TCPDF_FONTS::addTTFfont($fonts);

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 18);
        $pdf->SetXY(18, 16);
        $pdf->Write(0, 'Liuxueseng in UK Ltd');

        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY(18, 30);
        $pdf->Write(0, 'Address:');

        $pdf->SetXY(18, 35);
        $pdf->Write(0, 'Berkeley Suite, 35 Berkeley Sqaure');

        $pdf->SetXY(18, 40);
        $pdf->Write(0, 'Mayfair, London');

        $pdf->SetXY(18, 45);
        $pdf->Write(0, 'United Kingdom');

        $pdf->SetXY(18, 50);
        $pdf->Write(0, 'W1J 5BF');

        $pdf->SetXY(18, 60);
        $pdf->Write(0, 'Tel: +44 (0)20 7962 7014');

        $pdf->SetXY(18, 65);
        $pdf->Write(0, 'Email: finance@liuxuesenginuk.com');

        $pdf->Image($logo, $pdf->getPageWidth() - 52 - 8, 12, 52, 52, 'PNG', '', '', true, 500);

        $pdf->Line(12, 76.2, $pdf->getPageWidth() - 12, 76.2);
        $pdf->Line(12, 76.8, $pdf->getPageWidth() - 12, 76.8);

        $pdf->SetFont($chineseFont, 'B', 20);
        $pdf->SetXY(154, 85);
        $pdf->Write(0, 'INVOICE 发票');

        $pdf->SetXY(12, 102);
        $pdf->SetFont($chineseFont, '', 10);
        $pdf->Write(0, 'Bill To / 收票人');

        // 客户名
        $pdf->SetX(40);
        $pdf->Write(0, $order->member->profile->name_of_chinese);

        // 客户地址
        $pdf->SetXY(40, 107);
        $pdf->Write(0, $order->member->profile->street_of_england);

        $pdf->SetXY(40, 112);
        $pdf->Write(0, $order->member->profile->city_of_england);

        $pdf->SetXY(40, 122);
        $pdf->Write(0, $order->member->profile->postcode_of_england);

        $pdf->SetXY(40, 127);
        $pdf->Write(0, $order->member->mobile_section . ' ' . $order->member->mobile);

        $pdf->SetXY(120, 102);
        $pdf->Write(0, 'Invoice Number / 发票号');
        $pdf->SetX(166);
        $pdf->Write(0, '123123');

        $pdf->SetXY(122, 108);
        $pdf->Write(0, 'Invoice Date / 发票日期');
        $pdf->SetX(166);
        $pdf->Write(0, date('n/j/Y', time()));

        $pdf->SetXY(126.4, 114);
        $pdf->Write(0, 'PO Number / 订单号');
        $pdf->SetX(166);
        $pdf->Write(0, $order->sn);

//        $pdf->SetXY(12, 120);
        $pdf->setCellPaddings(1, 0, 0, 0);
        $pdf->setCellMargins(0, 0, 0, 0);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->SetFont($chineseFont, 'B', 10);
        $pdf->MultiCell(78, 8, 'Description / 服务描述', 1, 'L', 1, 1, 12, 140, true, 0, false, true, 8, 'M');
        $pdf->MultiCell(36, 8, 'Quantity / 数量', 1, 'L', 1, 1, 90, 140, true, 0, false, true, 8, 'M');
        $pdf->MultiCell(36, 8, 'Unit Price / 单价', 1, 'L', 1, 1, 126, 140, true, 0, false, true, 8, 'M');
        $pdf->MultiCell(36, 8, 'Amount / 应付款', 1, 'L', 1, 1, 162, 140, true, 0, false, true, 8, 'M');

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont($chineseFont, '', 10);
        $pdf->MultiCell(78, 8, 'Wedding photography / 婚纱旅拍', 1, 'L', 1, 1, 12, 148, true, 0, false, true, 8, 'M');
        $pdf->MultiCell(36, 8, '1', 1, 'L', 1, 1, 90, 148, true, 0, false, true, 8, 'M');
        $pdf->MultiCell(36, 8, $order->real_amount, 1, 'L', 1, 1, 126, 148, true, 0, false, true, 8, 'M');
        $pdf->MultiCell(36, 8, $order->real_amount, 1, 'L', 1, 1, 162, 148, true, 0, false, true, 8, 'M');


        $pdf->SetFont($chineseFont, 'B', null);
        $pdf->MultiCell(36, 8, 'Net', 'B', 'L', 1, 1, 126, 157, true, 0, false, true, 8, 'M');
        $pdf->MultiCell(36, 8, 'Discount', 'B', 'L', 1, 1, 126, 165.5, true, 0, false, true, 8, 'M');
        $pdf->MultiCell(36, 8, 'VAT', 'B', 'L', 1, 1, 126, 174, true, 0, false, true, 8, 'M');
        $pdf->MultiCell(36, 8, 'Total / 总金额', 'B', 'L', 1, 1, 126, 182.5, true, 0, false, false, 8, 'M', false);

        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell(36, 8, '£100.00', 'B', 'L', 1, 1, 162, 157, true, 0, false, true, 8, 'M');
        $pdf->MultiCell(36, 8, '£100.00', 'B', 'L', 1, 1, 162, 165.5, true, 0, false, true, 8, 'M');
        $pdf->MultiCell(36, 8, '£100.00', 'B', 'L', 1, 1, 162, 174, true, 0, false, true, 8, 'M');
        $pdf->MultiCell(36, 8, '£100.00', 'B', 'L', 1, 1, 162, 182.5, true, 0, false, true, 8, 'M');


        $pdf->SetXY(12, 212);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->write(0, 'Thank you for choosing us! If you have any questions, please do not hesitate to contact us.');

        $pdf->SetFont($chineseFont, 'B', 10);
        $pdf->SetXY(12, 218);
        $pdf->Write(0, '感谢您的支持! 若您有任何疑问,请随时联系我们。');

        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY(12, 230);
        $pdf->Write(0, 'Liuxueseng in UK Ltd register at Companies House, England & Wales.');

        $pdf->SetXY(12, 236);
        $pdf->Write(0, 'Company number. 11714213');

        $pdf->SetXY(12, 242);
        $pdf->Write(0, 'VAT number. 316698765');

        $pdf->Output(Yii::getAlias('@runtime/test.pdf'), 'F');
    }

    public function actionMail()
    {
        $ticketData = TicketData::find()->orderBy(['created_at' => SORT_DESC])->one();
        Email::ticketingPaymentNotify($ticketData);
    }
}