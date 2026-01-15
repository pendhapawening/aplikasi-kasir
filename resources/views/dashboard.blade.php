@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-600">{{ now()->format('l, d F Y') }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Sales Today -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Penjualan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Transactions Today -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-receipt text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Transaksi Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $todayTransactions }}</p>
                </div>
            </div>
        </div>

        <!-- Cash Sales -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Penjualan Tunai</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($cashSales, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Online Sales -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-mobile-alt text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Penjualan Online</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($onlineSales, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Best Selling Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Terlaris Hari Ini</h3>
            @if($bestSelling->count() > 0)
                <div class="space-y-3">
                    @foreach($bestSelling as $product)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">{{ $product->name }}</span>
                            <span class="bg-amber-100 text-amber-800 px-2 py-1 rounded-full text-sm">{{ $product->total_quantity }} pcs</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Belum ada penjualan hari ini</p>
            @endif
        </div>

        <!-- Tripay Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pembayaran Tripay</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">Menunggu Pembayaran</span>
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm">{{ $tripayPending }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">Sudah Dibayar</span>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">{{ $tripayPaid }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
