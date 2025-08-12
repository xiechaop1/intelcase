<?php
/**
 * Created by PhpStorm.
 * User: Choice
 * Date: 2019/4/17
 * Time: 3:24 PM
 */

namespace common\helpers;

use common\models\Subscribed;
use yii;

class Payment
{
    public static function checkTotalAmount($payments, $subTotalPrice, $nowPayment = [])
    {
        $payTotal = 0;
//        $payStatus = Subscribed::SUB_PAY_WAIT;
        if ($subTotalPrice == 0) return False;
        if (!empty($payments)) {
            foreach ($payments as $pay) {
                if ($pay->pay_type == \common\models\Payment::PAYMENT_TYPE_PAY) {
                    $payTotal += $pay->recv_amount + $pay->fee;
                } else {
                    $payTotal -= $pay->recv_amount - $pay->fee;
                }
            }
        }

        if (!empty($nowPayment)) {
            if ($nowPayment->pay_type == \common\models\Payment::PAYMENT_TYPE_PAY) {
                $payTotal += $nowPayment->amount + $pay->fee;
            } else {
                $payTotal -= $nowPayment->amount - $pay->fee;
            }
        }

        if (!empty($payments) || !empty($nowPayment)) {
            if ($payTotal >= $subTotalPrice) {
                $payStatus = Subscribed::SUB_PAY_FULLY;
            } else {
                $payStatus = Subscribed::SUB_PAY_PARTLY;
            }
        } else {
            $payStatus = Subscribed::SUB_PAY_WAIT;
        }

        return $payStatus;

    }




}