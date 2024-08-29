<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalService
{
    public function createPayment($amount)
    {
        try {

            $data = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => $amount
                        ]
                    ]
                ]
            ];

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->createOrder($data);

            if (isset($response['id']) && $response['id'] != null && $response['status'] == 'CREATED') {

                return [
                    'status' => 'success',
                    'payment_token' => $response['id']
                ];

            } else {

                return [
                    'status' => 'error',
                    'payment_token' => null
                ];
            }

        } catch (\Exception $e) {

            return [
                'status' => 'error'
            ];
        }
    }

    public function confirmPayment($paymentSource,$orderId,$paymentId)
    {
        try {

            $confirmResponse = Payment::select('token')->where('order_id', $orderId)->where('id', $paymentId)->first();

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $data = [
                'payment_source' => $paymentSource
            ];

            $response = $provider->confirmOrder($confirmResponse->token,$data);

            if (isset($response['id']) && $response['id'] != null && $response['status'] == 'APPROVED') {

                return [
                    'status' => 'success'
                ];

            } else {

                return [
                    'status' => 'error'
                ];
            }

        } catch (\Exception $e) {

            return [
                'status' => 'error'
            ];
        }
    }

    /* public function authorizePayment($orderId, $paymentId)
    {
        try {

            $authorizeResponse = Payment::select('token')->where('order_id', $orderId)->where('id', $paymentId)->first();

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->authorizePaymentOrder($authorizeResponse->token);

            if (isset($response['id']) && $response['id'] != null && $response['status'] == 'COMPLETED') {

                return [
                    'status' => 'success',
                    'authorize_id' => $response['purchase_units'][0]['payments']['authorizations'][0]['id']
                ];

            } else {

                return [
                    'status' => 'error',
                    'authorize_id' => null
                ];
            }

        } catch (\Exception $e) {

            return [
                'status' => 'error'
            ];
        }
    } */

    public function capturePayment($orderId, $paymentId)
    {
        try {

            $authorizeResponse = Payment::select('token')->where('order_id', $orderId)->where('id', $paymentId)->first();

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->capturePaymentOrder($authorizeResponse->token);

            if (isset($response['id']) && $response['id'] != null && $response['status'] == 'COMPLETED') {

                return [
                    'status' => 'success'
                ];

            } else {

                return [
                    'status' => 'error'
                ];
            }

        } catch (\Exception $e) {

            return [
                'status' => 'error'
            ];
        }
    }
}
