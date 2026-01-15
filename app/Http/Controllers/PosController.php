<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        // Mengambil semua produk diurutkan dari yang terbaru
        $products = Product::latest()->get();
        return view('pos.index', compact('products'));
    }
}