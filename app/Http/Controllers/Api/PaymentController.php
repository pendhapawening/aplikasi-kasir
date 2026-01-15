<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\TripayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    /**
     * Get available payment channels
     */
    public function getPaymentChannels()
    {
        $result = $this->tripayService->getPaymentChannels();

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Payment Channels Retrieved',
                'data' => $result['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'data' => null
        ], 500);
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($transactionId)
    {
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction Not Found',
                'data' => null
            ], 404);
        }

        // If cash payment, return current status
        if ($transaction->isCash()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment Status Retrieved',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'payment_type' => 'cash',
                    'payment_status' => $transaction->payment_status,
                    'paid_at' => $transaction->paid_at
                ]
            ]);
        }

        // If online payment, check with Tripay
        if ($transaction->isOnline() && $transaction->tripay_reference) {
            $result = $this->tripayService->getTransactionDetail($transaction->tripay_reference);

            if ($result['success']) {
                $tripayData = $result['data'];
                
                // Update transaction status based on Tripay response
                $status = strtolower($tripayData['status']);
                if ($status === 'paid') {
                    $transaction->update([
                        'payment_status' => 'paid',
                        'paid_at' => now()
                    ]);
                } elseif (in_array($status, ['expired', 'failed'])) {
                    $transaction->update([
                        'payment_status' => $status
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Payment Status Retrieved',
                    'data' => [
                        'transaction_id' => $transaction->id,
                        'payment_type' => 'online',
                        'payment_status' => $transaction->payment_status,
                        'tripay_status' => $tripayData['status'],
                        'tripay_reference' => $transaction->tripay_reference,
                        'checkout_url' => $transaction->tripay_checkout_url,
                        'paid_at' => $transaction->paid_at
                    ]
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment Status Retrieved',
            'data' => [
                'transaction_id' => $transaction->id,
                'payment_type' => $transaction->payment_type,
                'payment_status' => $transaction->payment_status,
                'paid_at' => $transaction->paid_at
            ]
        ]);
    }

    /**
     * Handle Tripay callback
     */
    public function handleCallback(Request $request)
    {
        try {
            $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
            $json = $request->getContent();
            $data = json_decode($json, true);

            Log::info('Tripay Callback Received', $data);

            // Validate signature
            if (!$this->tripayService->validateCallbackSignature(
                $callbackSignature,
                $data['merchant_ref'],
                $data['amount'],
                $data['status']
            )) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Signature'
                ], 400);
            }

            // Find transaction by merchant_ref
            $transaction = Transaction::where('tripay_merchant_ref', $data['merchant_ref'])->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction Not Found'
                ], 404);
            }

            // Update transaction status
            $status = strtolower($data['status']);
            
            if ($status === 'paid') {
                $transaction->update([
                    'payment_status' => 'paid',
                    'paid_at' => now()
                ]);

                Log::info('Transaction Paid', ['transaction_id' => $transaction->id]);
            } elseif (in_array($status, ['expired', 'failed'])) {
                $transaction->update([
                    'payment_status' => $status
                ]);

                // Restore stock if payment failed/expired
                foreach ($transaction->items as $item) {
                    $item->product->increment('stock', $item->qty);
                }

                Log::info('Transaction ' . ucfirst($status), ['transaction_id' => $transaction->id]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Callback Processed'
            ]);

        } catch (\Exception $e) {
            Log::error('Tripay Callback Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Callback Processing Failed'
            ], 500);
        }
    }
}
