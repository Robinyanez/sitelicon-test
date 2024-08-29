<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\PayPalService;
use App\Http\Controllers\Api\ApiController;

class PaymentController extends ApiController
{
    private $paymentService;

    public function __construct(PayPalService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function confirmationPayment(Request $request)
    {
        try {

            $paymentResponse = $this->paymentService->confirmPayment($request->payment_source,$request->order_id,$request->payment_id);

            if ($paymentResponse['status'] != 'success') {

                return $this->errorResponse('Algo salio mal', self::BAD_REQUEST);
            }

            $order = Order::find($request->order_id);
            $order->payment_status = 'confirmed';
            $order->save();

            $payment = Payment::find($request->payment_id);
            $payment->status = 'confirmed';
            $payment->save();

            return $this->successResponse('confirmed', self::OK);

        } catch (\Exception $e) {
            //throw $th;
        }
    }

    /* public function authorizationPayment(Request $request)
    {
        try {

            $authorizationResponse = $this->paymentService->authorizePayment($request->order_id,$request->payment_id);

            if ($authorizationResponse['status'] != 'success') {

                return $this->errorResponse('Algo salio mal', self::BAD_REQUEST);
            }

            $order = Order::find($request->order_id);
            $order->payment_status = 'authorized';
            $order->save();

            $payment = Payment::find($request->payment_id);
            $payment->status = 'authorized';
            $payment->save();

            return $this->successResponse('authorized', self::OK);

        } catch (\Exception $e) {
            //throw $th;
        }
    } */

    public function payment(Request $request)
    {
        try {

            $paymentResponse = $this->paymentService->capturePayment($request->order_id,$request->payment_id);

            if ($paymentResponse['status'] != 'success') {

                return $this->errorResponse('Algo salio mal', self::BAD_REQUEST);
            }

            $order = Order::find($request->order_id);
            $order->payment_status = 'paid';
            $order->save();

            $payment = Payment::find($request->payment_id);
            $payment->status = 'paid';
            $payment->save();

            return $this->successResponse('paid', self::OK);

        } catch (\Exception $e) {

            //throw $th;
        }
    }
}
