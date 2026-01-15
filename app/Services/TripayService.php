<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripayService
{
    private $apiKey;
    private $privateKey;
    private $merchantCode;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('tripay.api_key');
        $this->privateKey = config('tripay.private_key');
        $this->merchantCode = config('tripay.merchant_code');
        $this->baseUrl = config('tripay.base_url');
    }

    /**
     * Get available payment channels
     */
    public function getPaymentChannels()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/merchant/payment-channel');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()['data'] ?? []
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to get payment channels'
            ];
        } catch (\Exception $e) {
            Log::error('Tripay Get Channels Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Create transaction
     */
    public function createTransaction($data)
    {
        try {
            $merchantRef = 'TRX-' . time() . '-' . rand(1000, 9999);
            $amount = $data['amount'];

            $signature = hash_hmac('sha256', $this->merchantCode . $merchantRef . $amount, $this->privateKey);

            $payload = [
                'method' => $data['method'],
                'merchant_ref' => $merchantRef,
                'amount' => $amount,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'order_items' => $data['order_items'],
                'return_url' => config('tripay.return_url'),
                'expired_time' => (time() + (24 * 60 * 60)), // 24 hours
                'signature' => $signature
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->baseUrl . '/transaction/create', $payload);

            if ($response->successful()) {
                $result = $response->json();
                
                if ($result['success']) {
                    return [
                        'success' => true,
                        'data' => $result['data']
                    ];
                }

                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Transaction creation failed'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to create transaction'
            ];
        } catch (\Exception $e) {
            Log::error('Tripay Create Transaction Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction detail
     */
    public function getTransactionDetail($reference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/transaction/detail', [
                'reference' => $reference
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                if ($result['success']) {
                    return [
                        'success' => true,
                        'data' => $result['data']
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Failed to get transaction detail'
            ];
        } catch (\Exception $e) {
            Log::error('Tripay Get Detail Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate callback signature
     */
    public function validateCallbackSignature($callbackSignature, $merchantRef, $amount, $status)
    {
        $signature = hash_hmac('sha256', $this->merchantCode . $merchantRef . $amount . $status, $this->privateKey);
        return $signature === $callbackSignature;
    }
}
