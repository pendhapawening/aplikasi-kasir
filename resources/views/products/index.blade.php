@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Manajemen Produk</h1>
        <a href="{{ route('products.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Tambah Produk
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="border rounded-lg overflow-hidden hover:shadow-lg transition">
                        @if($product->photo)
                            <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif

                        <div class="p-4">
                            <h3 class="font-semibold text-lg text-gray-900">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $product->category }}</p>
                            <p class="text-xl font-bold text-amber-600 mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                            @if($product->stock !== null)
                                <p class="text-sm text-gray-600">Stok: {{ $product->stock }}</p>
                            @endif

                            <div class="flex items-center justify-between mt-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                </span>

                                <div class="flex space-x-2">
                                    <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-box-open text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada produk</h3>
                        <p class="text-gray-600 mb-4">Mulai tambahkan produk untuk cafe Anda</p>
                        <a href="{{ route('products.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg">
                            Tambah Produk Pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        @if($products->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
