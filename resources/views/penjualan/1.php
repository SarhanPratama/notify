@include('layouts.link')
@php
    $title = 'Checkout Bahan Baku';
@endphp

<title>Seroo - {{ $title }}</title>
<style>
    .text-maron {
        color: #861414;
    }
    .bg-maron {
        background-color: #9c1515;
    }
    .product-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .quantity-input {

        text-align: center;
    }
    .cart-item {
        border-bottom: 1px solid #eee;
        padding: 15px 0;
    }
    .empty-cart {
        text-align: center;
        padding: 30px;
        color: #6c757d;
    }
    .badge-stock {
        background-color: #e74a3b;
    }
    .badge-price {
        background-color: #1cc88a;
    }
    .btn-maron {
        background-color: #9c1515;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-maron:hover {
        background-color: #861414;
        color: white;
        transform: translateY(-2px);
    }
    .header-logo {
        max-height: 60px;
    }
    .navbar-brand {
        font-size: 1.5rem;
        font-weight: 700;
    }
    .company-header {
        background-color: #9c1515;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    .product-img-container {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .product-img {
        max-height: 100%;
        object-fit: contain;
    }
    .checkout-submit-btn {
        padding: 12px 24px;
        font-weight: 600;
        border-radius: 30px;
    }
    .page-title {
        position: relative;
        display: inline-block;
    }
    .page-title:after {
        content: '';
        position: absolute;
        width: 60%;
        height: 3px;
        background-color: #9c1515;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
    }
</style>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Company Header/Navbar -->
<header class="company-header py-3 mb-4 bg-maron">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/logo/brand.png') }}" alt="Seroo Logo" class="header-logo me-3">
                <span class="navbar-brand mb-0">
                    {{-- <span class="text-maron">SEROO</span>
                    <span class="text-muted fs-6">Coffee & Tea</span> --}}
                </span>
            </div>
            <div>
                <a href="#" class="btn btn-sm btn-outline-secondary me-2">
                    <i class="fas fa-user me-1"></i> Akun
                </a>
                <a href="#" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>

<main class="main-content">
    <section style="background-color: #f8f9fa; min-height: 100vh;">
        <div class="container-fluid py-5">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold text-maron page-title">
                        <i class="fas fa-shopping-basket me-2"></i>Checkout Bahan Baku
                    </h2>
                    <p class="text-muted mt-3">Pilih bahan baku yang diperlukan untuk produksi</p>
                </div>
            </div>

            <div class="row">
                <!-- Product Catalog -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-maron text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-boxes me-2"></i>Katalog Bahan Baku
                            </h5>
                        </div>
                        <div class="card-body p-4" style="max-height: 600px; overflow-y: auto;">
                            <div class="row">
                                @foreach($bahanBaku as $item)
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <div class="card product-card h-100">
                                        <div class="card-body">
                                            <div class="product-img-container mb-3">
                                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" class="img-fluid rounded product-img">
                                            </div>
                                            <h5 class="card-title fw-bold">{{ ucwords($item->nama) }}</h5>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span class="badge badge-stock text-white px-2 py-1">
                                                    Stok: {{ $item->saldoakhir }} {{ ucwords($item->satuan->nama) }}
                                                </span>
                                                <span class="badge badge-price text-white px-2 py-1">
                                                    Rp {{ number_format($item->harga, 0, ',', '.') }}/kg
                                                </span>
                                            </div>
                                            <div class="input-group input-group-sm  mt-3">
                                                <input type="number" id="qty-{{ $item->id }}" class="form-control quantity-input" min="1" max="{{ $item->stok }}" value="1">
                                                <button class="btn btn-sm btn-maron" onclick="addToCart('{{ $item->nama }}', {{ $item->harga }}, '{{ $item->id }}', {{ $item->id }})">
                                                     Tambah
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shopping Cart -->
                <div class="col-lg-4">
                    <div class="card shadow">
                        <div class="card-header bg-maron text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja
                                <span class="badge bg-white text-maron float-end" id="cart-count">0</span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div id="cart">
                                <div class="empty-cart">
                                    <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
                                    <p>Keranjang belanja kosong</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer py-3">
                            <button class="btn btn-maron w-100" onclick="showCheckout()">
                                <i class="fas fa-credit-card me-2"></i>Proses Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form (Hidden Initially) -->
            <div id="checkout-form" class="card mt-4 shadow" style="display: none;">
                <div class="card-header bg-maron text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>Form Checkout
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ url('bahan-baku.checkout.store') }}" method="POST" id="orderForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nama Outlet</label>
                                <input type="text" name="nama_outlet" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Pengiriman</label>
                                <input type="date" name="tanggal_pengiriman" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Pengiriman</label>
                            <textarea name="alamat_pengiriman" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-select" required>
                                <option value="">Pilih metode</option>
                                <option value="Tunai">Tunai</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Piutang">Piutang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
                        </div>

                        <!-- Hidden input for cart items -->
                        <input type="hidden" name="items" id="items-json">

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4" onclick="hideCheckout()">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </button>
                            <button type="submit" class="btn btn-maron checkout-submit-btn">
                                <i class="fas fa-paper-plane me-2"></i>Submit Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('assets/img/logo-white.png') }}" alt="Seroo Logo" class="me-2" style="height: 40px;">
                    <h5 class="mb-0">SEROO Coffee & Tea</h5>
                </div>
                <p class="small">Menyediakan bahan baku berkualitas tinggi untuk bisnis F&B Anda</p>
            </div>
            <div class="col-md-4 mb-3">
                <h5 class="mb-3">Kontak Kami</h5>
                <p class="small mb-1"><i class="fas fa-map-marker-alt me-2"></i>Jl. Raya Utama No. 123, Jakarta</p>
                <p class="small mb-1"><i class="fas fa-phone me-2"></i>(021) 1234-5678</p>
                <p class="small mb-1"><i class="fas fa-envelope me-2"></i>info@seroo.co.id</p>
            </div>
            <div class="col-md-4">
                <h5 class="mb-3">Follow Kami</h5>
                <div>
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-youtube fa-lg"></i></a>
                </div>
            </div>
        </div>
        <div class="text-center mt-4 pt-3 border-top border-secondary">
            <p class="small mb-0">&copy; {{ date('Y') }} SEROO Coffee & Tea. All rights reserved.</p>
        </div>
    </div>
</footer>

@include('layouts.script')

<script>
    let cart = [];

    function addToCart(itemName, price, itemId, id) {
        const qty = parseInt(document.getElementById(`qty-${itemId}`).value);

        // Check if item already exists in cart
        const existingItem = cart.find(item => item.name === itemName);
        if (existingItem) {
            existingItem.quantity += qty;
        } else {
            cart.push({
                id: id,  // Database ID for the item
                name: itemName,
                price: price,
                quantity: qty
            });
        }

        updateCart();

        // Show toast notification instead of alert
        Toastify({
            text: `${qty} kg ${itemName} telah ditambahkan ke keranjang`,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#9c1515",
            stopOnFocus: true,
        }).showToast();
    }

    function updateCart() {
        const cartDiv = document.getElementById("cart");
        const cartCount = document.getElementById("cart-count");

        if (cart.length === 0) {
            cartDiv.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
                    <p>Keranjang belanja kosong</p>
                </div>
            `;
            cartCount.textContent = "0";
            return;
        }

        let html = '<div class="p-3">';
        let total = 0;

        cart.forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            total += subtotal;

            html += `
                <div class="cart-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 fw-bold">${item.name}</h6>
                            <small class="text-muted">Rp ${item.price.toLocaleString('id-ID')}/kg</small>
                        </div>
                        <div>
                            <span class="fw-bold">${item.quantity} kg</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="fw-bold text-maron">Rp ${subtotal.toLocaleString('id-ID')}</span>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${index})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        html += `
            <div class="mt-3 pt-3 border-top">
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total:</span>
                    <span class="text-maron fs-5">Rp ${total.toLocaleString('id-ID')}</span>
                </div>
            </div>
        </div>`;

        cartDiv.innerHTML = html;
        cartCount.textContent = cart.length;

        // Update the hidden input for form submission
        document.getElementById('items-json').value = JSON.stringify(cart);
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCart();
    }

    function showCheckout() {
        if (cart.length === 0) {
            // Show toast notification
            Toastify({
                text: "Keranjang belanja kosong. Silakan tambahkan bahan baku terlebih dahulu.",
                duration: 3000,
                gravity: "top",
                position: "center",
                backgroundColor: "#e74a3b",
                stopOnFocus: true,
            }).showToast();
            return;
        }
        document.getElementById("checkout-form").style.display = "block";
        window.scrollTo({
            top: document.getElementById("checkout-form").offsetTop - 20,
            behavior: 'smooth'
        });
    }

    function hideCheckout() {
        document.getElementById("checkout-form").style.display = "none";
    }

    // Set minimum date for delivery to tomorrow
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);

        const dateInput = document.querySelector('input[name="tanggal_pengiriman"]');
        if (dateInput) {
            dateInput.min = tomorrow.toISOString().split('T')[0];
            dateInput.value = tomorrow.toISOString().split('T')[0];
        }
    });

    // Submit order via AJAX
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (cart.length === 0) {
            Toastify({
                text: "Keranjang belanja kosong!",
                duration: 3000,
                gravity: "top",
                position: "center",
                backgroundColor: "#e74a3b",
                stopOnFocus: true,
            }).showToast();
            return;
        }

        const formData = new FormData(this);

        // Show loading spinner
        Swal.fire({
            title: 'Memproses Pesanan',
            text: 'Mohon tunggu sebentar...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit form
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Pesanan berhasil dibuat! Menunggu approval dari gudang.',
                    confirmButtonColor: '#9c1515'
                }).then(() => {
                    // Reset cart and redirect
                    cart = [];
                    updateCart();
                    hideCheckout();
                    window.location.href = data.redirect || '/pesanan';
                });
            } else {
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message || 'Terjadi kesalahan saat memproses pesanan.',
                    confirmButtonColor: '#9c1515'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat menghubungi server.',
                confirmButtonColor: '#9c1515'
            });
        });
    });
</script>

<!-- Add SweetAlert2 and Toastify -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
