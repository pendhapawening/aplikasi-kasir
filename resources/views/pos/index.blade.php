@extends('layouts.app')

@section('title', 'Point of Sales')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Point of Sale</h1>
            <p class="text-gray-600 mt-1">Kelola transaksi penjualan dengan mudah</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Products Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Daftar Produk</h2>
                    </div>

                    <div class="p-6">
                        @if($products->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($products as $product)
                                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                                        @if($product->photo)
                                            <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="w-full h-32 object-cover">
                                        @else
                                            <div class="w-full h-32 bg-gray-100 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif

                                        <div class="p-4">
                                            <h3 class="font-medium text-gray-900 text-sm mb-1">{{ $product->name }}</h3>
                                            @if($product->category)
                                                <p class="text-xs text-gray-500 mb-2">{{ $product->category }}</p>
                                            @endif
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-lg font-bold text-amber-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                                <span class="text-xs text-gray-500">Stok: {{ $product->stock ?? 0 }}</span>
                                            </div>

                                            <button
                                                onclick="addToCart({{ $product->id }}, {{ json_encode($product->name) }}, {{ $product->price }}, {{ $product->stock ?? 0 }})"
                                                class="w-full bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                                {{ ($product->stock ?? 0) <= 0 ? 'disabled' : '' }}
                                            >
                                                <i class="fas fa-cart-plus mr-2"></i>Tambah ke Keranjang
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-box-open text-gray-400 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada produk</h3>
                                <p class="text-gray-600">Tambahkan produk terlebih dahulu untuk mulai berjualan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cart & Payment Section -->
            <div class="space-y-6">
                <!-- Cart -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Keranjang Belanja</h2>
                    </div>

                    <div id="cart-items" class="p-6 max-h-96 overflow-y-auto">
                        <div id="empty-cart" class="text-center py-8">
                            <i class="fas fa-shopping-cart text-gray-300 text-3xl mb-3"></i>
                            <p class="text-gray-500">Keranjang masih kosong</p>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 bg-gray-50">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Subtotal:</span>
                                <span id="subtotal">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total:</span>
                                <span id="total" class="text-amber-600">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Pembayaran</h2>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Payment Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pembayaran</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="payment_type" value="cash" checked class="text-amber-600 focus:ring-amber-500" onchange="togglePaymentType()">
                                        <span class="ml-2 text-sm">Tunai</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="payment_type" value="online" class="text-amber-600 focus:ring-amber-500" onchange="togglePaymentType()">
                                        <span class="ml-2 text-sm">Online</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Cash Payment -->
                            <div id="cash-payment" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar</label>
                                    <input
                                        type="number"
                                        id="paid-amount"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                        placeholder="0"
                                        oninput="calculateChange()"
                                    >
                                </div>
                                <div class="bg-gray-50 p-3 rounded-md">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>Total:</span>
                                        <span id="change-total">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>Bayar:</span>
                                        <span id="change-paid">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between text-sm font-medium">
                                        <span>Kembalian:</span>
                                        <span id="change-amount" class="text-green-600">Rp 0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Online Payment -->
                            <div id="online-payment" class="hidden space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                                    <select id="tripay-method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                        <option value="">Pilih metode...</option>
                                    </select>
                                </div>
                                <div id="payment-info" class="hidden bg-blue-50 p-3 rounded-md text-sm text-blue-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Anda akan diarahkan ke halaman pembayaran setelah checkout
                                </div>
                            </div>

                            <!-- Checkout Button -->
                            <button
                                id="checkout-btn"
                                onclick="checkout()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled
                            >
                                <i class="fas fa-credit-card mr-2"></i>Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="text-center">
                <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Transaksi Berhasil!</h3>
                <p id="success-message" class="text-gray-600 mb-6"></p>
                <button onclick="closeModal()" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-md">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loading-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6">
            <div class="text-center">
                <i class="fas fa-spinner fa-spin text-amber-600 text-2xl mb-3"></i>
                <p class="text-gray-600">Memproses transaksi...</p>
            </div>
        </div>
    </div>
</div>

<script>
// CSRF Token
const csrfToken = '{{ csrf_token() }}';

// Cart data
let cart = [];
let paymentChannels = [];

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
    loadPaymentChannels();
    updateCartDisplay();
});

// Load cart from localStorage
function loadCart() {
    const savedCart = localStorage.getItem('pos_cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
    }
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('pos_cart', JSON.stringify(cart));
}

// Load payment channels
async function loadPaymentChannels() {
    try {
        const response = await fetch('/api/payment/channels', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        const data = await response.json();
        if (data.success) {
            paymentChannels = data.data;
            populatePaymentMethods();
        }
    } catch (error) {
        console.error('Failed to load payment channels:', error);
    }
}

// Populate payment methods
function populatePaymentMethods() {
    const select = document.getElementById('tripay-method');
    select.innerHTML = '<option value="">Pilih metode...</option>';

    paymentChannels.forEach(channel => {
        const option = document.createElement('option');
        option.value = channel.code;
        option.textContent = channel.name;
        select.appendChild(option);
    });
}

// Get auth token (assuming Sanctum)
function getAuthToken() {
    return document.querySelector('meta[name="api-token"]')?.getAttribute('content') || '';
}

// Add to cart
function addToCart(id, name, price, stock) {
    if (stock <= 0) return;

    const existingItem = cart.find(item => item.id === id);
    if (existingItem) {
        if (existingItem.qty >= stock) {
            alert('Stok tidak mencukupi!');
            return;
        }
        existingItem.qty++;
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            qty: 1,
            stock: stock
        });
    }

    saveCart();
    updateCartDisplay();
}

// Update cart item quantity
function updateQuantity(id, newQty) {
    const item = cart.find(item => item.id === id);
    if (item) {
        if (newQty <= 0) {
            removeFromCart(id);
            return;
        }
        if (newQty > item.stock) {
            alert('Stok tidak mencukupi!');
            return;
        }
        item.qty = newQty;
        saveCart();
        updateCartDisplay();
    }
}

// Remove from cart
function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    saveCart();
    updateCartDisplay();
}

// Update cart display
function updateCartDisplay() {
    const cartItems = document.getElementById('cart-items');
    const emptyCart = document.getElementById('empty-cart');
    const subtotalEl = document.getElementById('subtotal');
    const totalEl = document.getElementById('total');
    const checkoutBtn = document.getElementById('checkout-btn');

    if (cart.length === 0) {
        cartItems.innerHTML = '<div id="empty-cart" class="text-center py-8"><i class="fas fa-shopping-cart text-gray-300 text-3xl mb-3"></i><p class="text-gray-500">Keranjang masih kosong</p></div>';
        subtotalEl.textContent = 'Rp 0';
        totalEl.textContent = 'Rp 0';
        checkoutBtn.disabled = true;
        return;
    }

    let subtotal = 0;
    let html = '';

    cart.forEach(item => {
        const itemTotal = item.price * item.qty;
        subtotal += itemTotal;

        html += `
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900 text-sm">${item.name}</h4>
                    <p class="text-xs text-gray-500">Rp ${item.price.toLocaleString('id-ID')}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="updateQuantity(${item.id}, ${item.qty - 1})" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-minus text-xs"></i>
                    </button>
                    <span class="text-sm font-medium w-8 text-center">${item.qty}</span>
                    <button onclick="updateQuantity(${item.id}, ${item.qty + 1})" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                    <button onclick="removeFromCart(${item.id})" class="text-red-400 hover:text-red-600 ml-2">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
            </div>
            <div class="text-right text-sm text-gray-600 py-1">
                Subtotal: Rp ${itemTotal.toLocaleString('id-ID')}
            </div>
        `;
    });

    cartItems.innerHTML = html;
    subtotalEl.textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
    totalEl.textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
    checkoutBtn.disabled = false;

    calculateChange();
}

// Toggle payment type
function togglePaymentType() {
    const cashPayment = document.getElementById('cash-payment');
    const onlinePayment = document.getElementById('online-payment');
    const paymentType = document.querySelector('input[name="payment_type"]:checked').value;

    if (paymentType === 'cash') {
        cashPayment.classList.remove('hidden');
        onlinePayment.classList.add('hidden');
    } else {
        cashPayment.classList.add('hidden');
        onlinePayment.classList.remove('hidden');
        document.getElementById('payment-info').classList.remove('hidden');
    }
}

// Calculate change
function calculateChange() {
    const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const paid = parseInt(document.getElementById('paid-amount').value) || 0;
    const change = paid - total;

    document.getElementById('change-total').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('change-paid').textContent = `Rp ${paid.toLocaleString('id-ID')}`;
    document.getElementById('change-amount').textContent = `Rp ${Math.max(0, change).toLocaleString('id-ID')}`;
}

// Checkout
async function checkout() {
    if (cart.length === 0) return;

    const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

    let checkoutData = {
        total_price: totalPrice,
        items: cart.map(item => ({
            id: item.id,
            qty: item.qty,
            price: item.price
        })),
        payment_type: paymentType
    };

    if (paymentType === 'cash') {
        const paidAmount = parseInt(document.getElementById('paid-amount').value) || 0;
        if (paidAmount < totalPrice) {
            alert('Jumlah bayar kurang dari total!');
            return;
        }
        checkoutData.paid_amount = paidAmount;
        checkoutData.change = paidAmount - totalPrice;
    } else {
        const tripayMethod = document.getElementById('tripay-method').value;
        if (!tripayMethod) {
            alert('Pilih metode pembayaran online!');
            return;
        }
        checkoutData.tripay_method = tripayMethod;
    }

    // Show loading
    document.getElementById('loading-modal').classList.remove('hidden');

    try {
        const response = await fetch('/api/transactions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify(checkoutData)
        });

        const data = await response.json();

        if (data.success) {
            // Clear cart
            cart = [];
            saveCart();
            updateCartDisplay();

            // Reset payment
            document.getElementById('paid-amount').value = '';
            calculateChange();

            // Show success
            document.getElementById('loading-modal').classList.add('hidden');
            document.getElementById('success-message').textContent = paymentType === 'cash' ?
                'Transaksi tunai berhasil diproses!' :
                'Transaksi online berhasil dibuat. Anda akan diarahkan ke halaman pembayaran.';
            document.getElementById('success-modal').classList.remove('hidden');

            // Redirect for online payment
            if (paymentType === 'online' && data.data.checkout_url) {
                setTimeout(() => {
                    window.open(data.data.checkout_url, '_blank');
                }, 2000);
            }
        } else {
            throw new Error(data.message || 'Transaksi gagal');
        }
    } catch (error) {
        document.getElementById('loading-modal').classList.add('hidden');
        alert('Error: ' + error.message);
    }
}

// Close modal
function closeModal() {
    document.getElementById('success-modal').classList.add('hidden');
}
</script>
@endsection
