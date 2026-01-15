<div class="flex flex-col h-screen bg-stone-100 font-sans text-stone-800 overflow-hidden">
    <!-- Navbar Sederhana -->
    <nav class="bg-white shadow-sm px-6 py-3 flex justify-between items-center z-10 shrink-0">
        <div class="flex items-center gap-2">
            <div class="bg-amber-600 text-white p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-stone-800 tracking-tight">CafePOS <span class="text-amber-600">Pro</span></h1>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-stone-500">{{ now()->format('D, d M Y') }}</span>
            <div class="h-8 w-8 bg-stone-200 rounded-full overflow-hidden">
                <img src="https://ui-avatars.com/api/?name=Admin&background=d97706&color=fff" alt="Admin">
            </div>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden relative">
        <!-- Left Side: Products -->
        <div class="flex-1 flex flex-col overflow-hidden w-full lg:w-2/3 xl:w-3/4">
            <!-- Filters -->
            <div class="p-4 bg-white border-b border-stone-200 flex flex-col sm:flex-row gap-4 justify-between items-center shrink-0">
                <div class="flex gap-2 overflow-x-auto w-full sm:w-auto pb-2 sm:pb-0 no-scrollbar">
                    <button wire:click="$set('category', '')" class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $category === '' ? 'bg-amber-600 text-white shadow-md' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                        Semua
                    </button>
                    <button wire:click="$set('category', 'food')" class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $category === 'food' ? 'bg-amber-600 text-white shadow-md' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                        Makanan
                    </button>
                    <button wire:click="$set('category', 'drink')" class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $category === 'drink' ? 'bg-amber-600 text-white shadow-md' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                        Minuman
                    </button>
                    <button wire:click="$set('category', 'snack')" class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $category === 'snack' ? 'bg-amber-600 text-white shadow-md' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                        Snack
                    </button>
                </div>
                <div class="relative w-full sm:w-64">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-2 rounded-full border border-stone-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-stone-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="flex-1 overflow-y-auto p-4 bg-stone-50">
                @if($products->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-stone-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>Produk tidak ditemukan</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($products as $product)
                            <div wire:click="addToCart({{ $product->id }})" class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all cursor-pointer overflow-hidden group border border-stone-100 relative">
                                <div class="aspect-square bg-stone-200 relative overflow-hidden">
                                    @if($product->photo)
                                        <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-stone-400 bg-stone-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                    @if($product->stock <= 0)
                                        <div class="absolute inset-0 bg-white/60 flex items-center justify-center">
                                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Habis</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <h3 class="font-semibold text-stone-800 text-sm truncate">{{ $product->name }}</h3>
                                    <p class="text-amber-600 font-bold text-sm mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="text-xs text-stone-500 mt-1">Stok: {{ $product->stock }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Side: Cart (Sidebar on Desktop, Modal on Mobile) -->
        <div class="{{ $isCartOpen ? 'translate-x-0' : 'translate-x-full' }} lg:translate-x-0 fixed lg:static inset-0 z-20 lg:z-auto bg-white lg:w-1/3 xl:w-1/4 border-l border-stone-200 shadow-2xl lg:shadow-none transition-transform duration-300 flex flex-col h-full">
            <!-- Cart Header -->
            <div class="p-4 border-b border-stone-200 flex justify-between items-center bg-stone-50">
                <h2 class="font-bold text-lg text-stone-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Keranjang
                </h2>
                <button wire:click="$set('isCartOpen', false)" class="lg:hidden text-stone-500 hover:text-stone-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                @forelse($cart as $item)
                    <div class="flex gap-3 items-start">
                        <div class="h-16 w-16 bg-stone-100 rounded-lg overflow-hidden shrink-0">
                            @if($item['image'])
                                <img src="{{ asset('storage/' . $item['image']) }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-stone-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-stone-800 text-sm line-clamp-1">{{ $item['name'] }}</h4>
                            <p class="text-amber-600 font-bold text-sm">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <button wire:click="updateQty({{ $item['id'] }}, -1)" class="h-6 w-6 rounded bg-stone-200 hover:bg-stone-300 flex items-center justify-center text-stone-600">-</button>
                                <span class="text-sm font-medium w-4 text-center">{{ $item['qty'] }}</span>
                                <button wire:click="updateQty({{ $item['id'] }}, 1)" class="h-6 w-6 rounded bg-stone-200 hover:bg-stone-300 flex items-center justify-center text-stone-600">+</button>
                            </div>
                        </div>
                        <button wire:click="removeFromCart({{ $item['id'] }})" class="text-red-400 hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-40 text-stone-400">
                        <p class="text-sm">Keranjang kosong</p>
                    </div>
                @endforelse
            </div>

            <!-- Cart Footer -->
            <div class="p-4 bg-stone-50 border-t border-stone-200 space-y-3">
                <div class="flex justify-between text-sm text-stone-600">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-stone-600">
                    <span>PPN (11%)</span>
                    <span>Rp {{ number_format($this->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold text-stone-800 pt-2 border-t border-stone-200">
                    <span>Total</span>
                    <span>Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>
                
                <button wire:click="checkout" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-xl shadow-lg transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" @if(empty($cart)) disabled @endif>
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Floating Button -->
    <div class="lg:hidden fixed bottom-6 right-6 z-10">
        <button wire:click="$set('isCartOpen', true)" class="bg-amber-600 text-white p-4 rounded-full shadow-xl flex items-center gap-2 hover:bg-amber-700 transition-colors relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            @if(count($cart) > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold h-6 w-6 flex items-center justify-center rounded-full border-2 border-white">{{ count($cart) }}</span>
            @endif
        </button>
    </div>
</div>