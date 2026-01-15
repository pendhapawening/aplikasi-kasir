<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Pos extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $cart = [];
    public $paymentType = 'cash'; // cash, online
    public $paidAmount = 0;
    public $customerPhone = '';
    public $isCartOpen = false; // Mobile cart toggle

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => '']
    ];

    public function mount()
    {
        $this->cart = [];
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });
    }

    public function getTaxProperty()
    {
        return $this->subtotal * 0.11; // PPN 11%
    }

    public function getTotalProperty()
    {
        return $this->subtotal + $this->tax;
    }

    public function getChangeProperty()
    {
        return max(0, (int)$this->paidAmount - (int)$this->total);
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product || $product->stock <= 0) {
            // Optional: Flash message
            return;
        }

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] < $product->stock) {
                $this->cart[$productId]['qty']++;
            }
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->photo,
                'qty' => 1,
                'stock' => $product->stock
            ];
        }
    }

    public function updateQty($productId, $change)
    {
        if (!isset($this->cart[$productId])) return;

        $newQty = $this->cart[$productId]['qty'] + $change;

        if ($newQty <= 0) {
            unset($this->cart[$productId]);
        } elseif ($newQty <= $this->cart[$productId]['stock']) {
            $this->cart[$productId]['qty'] = $newQty;
        }
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function checkout()
    {
        if (empty($this->cart)) return;

        // Logic transaksi sederhana
        // Di implementasi nyata, gunakan DB Transaction dan simpan ke tabel transactions
        
        $this->reset(['cart', 'paidAmount', 'customerPhone', 'paymentType']);
        $this->isCartOpen = false;
        
        session()->flash('message', 'Transaksi Berhasil!');
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        $products = $query->orderBy('name')->paginate(12);

        return view('livewire.pos', [
            'products' => $products
        ])->layout('layouts.app'); 
    }
}