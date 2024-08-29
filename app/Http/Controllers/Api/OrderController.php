<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;
use App\Models\Payment;

class OrderController extends ApiController
{
    private $paymentService;

    public function __construct(PayPalService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function show($ordeId)
    {
        $orders = Order::with('payment')->where('id', $ordeId)->first();

        return $this->successResponse($orders, self::OK);
    }

    public function store(Request $request)
    {
        try {

            $product = Product::select('price')->where('id', $request->product_id)->first();

            $order = new Order;
            $order->user_id = Auth::user()->id;
            $order->product_id = $request->product_id;
            $order->total_amount = $product->price;
            $order->save();

            $paymentResponse = $this->paymentService->createPayment($order->total_amount);

            if ($paymentResponse['status'] != 'success') {

                return $this->errorResponse('Algo salio mal', self::BAD_REQUEST);
            }

            $payment = new Payment;
            $payment->order_id = $order->id;
            $payment->payment_type = 'paypal';
            $payment->token = $paymentResponse['payment_token'];
            $payment->save();

            $response = [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
            ];

            return $this->successResponse($response, self::OK);

        } catch (\Exception $e) {

            $exceptionArray = [
                'message'   => $e->getMessage(),
                'code'      => $e->getCode(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => json_encode($e->getTrace()),
            ];

            $content_error = [
                'url'       => \request()->url(),
                'request'   => \request()->all(),
                'response'  => null,
                'ip'        => \request()->ip(),
                'code'      => null,
                'error'     => $exceptionArray,
            ];

            saveAndSendError($content_error);

            return $this->errorResponse($e->getMessage(),$e->getCode());
        }
    }
}
