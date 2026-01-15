<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\TripayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    protected $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    public function index(Request $request)
    {
        $query = Transaction::with('items.product', 'user')->orderBy('created_at', 'desc');

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment type
        if ($request->has('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        $transactions = $query->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'List Data Transaction',
            'data' => $transactions
        ]);
    }

    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Determine payment type
            $paymentType = $validated['payment_type'] ?? 'cash';
            $paymentStatus = $paymentType === 'cash' ? 'paid' : 'pending';

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'total_price' => $validated['total_price'],
                'paid_amount' => $validated['paid_amount'] ?? 0,
                'change' => $validated['change'] ?? 0,
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'payment_type' => $paymentType,
                'payment_status' => $paymentStatus,
                'paid_at' => $paymentType === 'cash' ? now() : null,
            ]);

            // Create transaction items
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['id']);

                // Check stock
                if ($product->stock < $item['qty']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                // Decrement stock
                $product->decrement('stock', $item['qty']);
            }

            // If online payment, create Tripay transaction
            if ($paymentType === 'online') {
                $user = auth()->user();
                
                // Prepare order items for Tripay
                $orderItems = [];
                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['id']);
                    $orderItems[] = [
                        'name' => $product->name,
                        'price' => $item['price'],
                        'quantity' => $item['qty']
                    ];
                }

                $tripayData = [
                    'method' => $validated['tripay_method'] ?? 'BRIVA',
                    'amount' => $validated['total_price'],
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => $validated['customer_phone'] ?? '08123456789',
                    'order_items' => $orderItems
                ];

                $tripayResult = $this->tripayService->createTransaction($tripayData);

                if ($tripayResult['success']) {
                    $tripayTransaction = $tripayResult['data'];
                    
                    $transaction->update([
                        'tripay_reference' => $tripayTransaction['reference'],
                        'tripay_merchant_ref' => $tripayTransaction['merchant_ref'],
                        'tripay_payment_method' => $tripayTransaction['payment_method'],
                        'tripay_payment_name' => $tripayTransaction['payment_name'],
                        'tripay_checkout_url' => $tripayTransaction['checkout_url']
                    ]);
                } else {
                    throw new \Exception('Failed to create payment: ' . $tripayResult['message']);
                }
            }

            DB::commit();

            $transaction->load('items.product');

            return response()->json([
                'success' => true,
                'message' => 'Transaction Created Successfully',
                'data' => $transaction
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transaction Failed: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        $transaction = Transaction::with('items.product', 'user')->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction Not Found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Transaction',
            'data' => $transaction
        ]);
    }
}
