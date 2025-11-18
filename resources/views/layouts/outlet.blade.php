@include('layouts.link')
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@php
    use Illuminate\Support\Facades\Storage;
    $title = 'Pesanan Bahan Baku - ' . $outlet->nama;
@endphp

<title>Seroo - {{ $title }}</title>

<style>
    /* .outlet-header {
        background: linear-gradient(135deg, #9c1515 0%);
        color: white;
        padding: 20px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    } */

    .outlet-name {
        font-size: 1.3rem;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .nav-item .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .nav-item .nav-link:hover,
    .nav-item .nav-link.active {
        color: white !important;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 5px;
    }

    .product-card {
        transition: all 0.3s ease;
        height: 100%;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }

    .product-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px 8px 0 0;
    }

    .badge-stock {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 0.75rem;
        padding: 5px 10px;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        padding: 2px 7px;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .btn-add-cart {
        background: #9c1515;
        color: white;
        border: none;
        width: 100%;
        padding: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-add-cart:hover {
        background: #7d1111;
        color: white;
        transform: scale(1.02);
    }

    .cart-floating {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .cart-sidebar {
        position: fixed;
        right: -400px;
        top: 0;
        width: 400px;
        height: 100vh;
        background: white;
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.2);
        transition: right 0.3s ease;
        z-index: 1050;
        overflow-y: auto;
    }

    .cart-sidebar.show {
        right: 0;
    }

    .cart-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 1040;
    }

    .cart-overlay.show {
        display: block;
    }

    @media (max-width: 768px) {
        .cart-sidebar {
            width: 100%;
            right: -100%;
        }

        .product-image,
        .product-placeholder {
            height: 150px;
        }

        .outlet-name {
            font-size: 1.2rem;
        }
    }
</style>

<body class="bg-light d-flex flex-column min-vh-100">
    <!-- Header Outlet -->
    <div class="outlet-header bg-maron text-white p-2">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start mb-3 mb-md-0">
                    <img src="{{ asset('assets/img/logo/brand.png') }}" width="100" alt="Seroo Logo" class="img-fluid m-auto">
                </div>
                <div class="col-md-8 text-center">
                    <div class="outlet-name">{{ strtoupper($outlet->nama) }}</div>
                </div>
                <div class="col-md-2 text-center text-md-end">
                    <div class="text-white">
                        <small class="d-block text-white-50">Total Piutang</small>
                        <h6 class="mb-0">Rp 5.250.000</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-maron sticky-top" >
        <div class="container">
            {{-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button> --}}
            <div class="navbar-collapse justify-content-center" id="navbarNav">
                <ul class="d-flex justify-content-between">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('outlet.belanja') ? 'active font-weight-bold' : '' }}" href="{{ route('outlet.belanja', ['token' => $outlet->barcode_token]) }}">
                            Belanja
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('outlet.pesanan', 'outlet.pesanan.detail') ? 'active font-weight-bold' : '' }}" href="{{ route('outlet.pesanan', ['token' => $outlet->barcode_token]) }}" >
                            Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(['outlet.kasbon', 'outlet.kasbon.detail']) ? 'active font-weight-bold' : '' }}" href="{{ route('outlet.kasbon', ['token' => $outlet->barcode_token]) }}">
                            Kasbon
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4 flex-grow-1">
        @yield('content')
    </div>
    <!-- Floating Cart Button -->
    <button class="btn btn-maron btn-lg rounded-circle cart-floating shadow-lg" onclick="toggleCart()"
        id="floatingCartBtn">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-badge" id="cartBadge">0</span>
    </button>

    <!-- Cart Sidebar -->
    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
    <div class="cart-sidebar" id="cartSidebar">
        <div class="p-3 bg-maron text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Keranjang</h5>
            <button class="btn btn-light btn-sm" onclick="toggleCart()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-3" id="cartItems">
            <div class="text-center text-muted py-5">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                <p>Keranjang masih kosong</p>
            </div>
        </div>
        <div class="p-3 border-top bg-light">
            <div class="d-flex justify-content-between mb-3">
                <strong>Total:</strong>
                <strong class="text-maron" id="cartTotal">Rp 0</strong>
            </div>
            <button class="btn bg-maron text-white w-100 mb-2" onclick="checkout()" id="checkoutBtn" disabled>
                <i class="fas fa-check me-1"></i> Checkout
            </button>
            <button class="btn btn-outline-danger w-100" onclick="clearCart()">
                <i class="fas fa-trash me-1"></i> Kosongkan Keranjang
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-auto pt-4 bg-white border-top">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-4 text-center">
                    <h6 class="fw-bold mb-3">Wharehouse Teh Tarik Sero</h6>
                    <p class="mb-2 small text-muted">
                        Jl. Industri Raya No. 12<br>
                        Kawasan Logistik, Jakarta 11740<br>
                        Indonesia
                    </p>
                    <div class="small text-muted">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        Koordinat: -6.2000, 106.8166
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <h6 class="fw-bold mb-3">Jam Operasional</h6>
                    <ul class="list-unstyled small mb-0">
                        <li>Senin - Jumat: 08:00 - 17:00</li>
                        <li>Sabtu: 09:00 - 14:00</li>
                        <li>Minggu & Hari Besar: Tutup</li>
                    </ul>
                </div>
                <div class="col-md-3 text-center">
                    <h6 class="fw-bold mb-3"><i class="fas fa-headset me-2"></i>Hubungi Admin</h6>
                    <p class="small text-muted mb-2">Butuh bantuan terkait pesanan atau kasbon?</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="hubungiAdmin()">
                            <i class="fab fa-whatsapp me-1"></i> WhatsApp
                        </button>
                    </div>
                </div>
                {{-- <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Informasi</h6>
                    <ul class="list-unstyled small mb-0">
                        <li><i class="fas fa-shield-alt me-1"></i>Data aman</li>
                        <li><i class="fas fa-sync-alt me-1"></i>Realtime update</li>
                        <li><i class="fas fa-qrcode me-1"></i>Pemesanan via QR</li>
                    </ul>
                </div> --}}
            </div>
            <hr class="mt-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center py-3 small">
                <div class="text-muted mb-2 mb-md-0">&copy; {{ date('Y') }} Seroo. All rights reserved.</div>
                <div class="text-muted">
                    <i class="fas fa-database me-1"></i> Versi Outlet UI 1.0.0
                </div>
            </div>
        </div>
    </footer>

    @include('layouts.script')

    <script>
        let cart = [];

        // Show Section
        function showSection(section) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(s => s.style.display = 'none');

            // Remove active class from all nav links
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));

            // Show selected section
            document.getElementById('section-' + section).style.display = 'block';

            // Add active class to clicked nav link
            event.target.classList.add('active');
        }

        // Filter Products
        function filterProducts() {
            const searchValue = document.getElementById('searchProduct').value.toLowerCase();
            const products = document.querySelectorAll('.product-item');

            products.forEach(product => {
                const productName = product.getAttribute('data-name');
                if (productName.includes(searchValue)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }

        // Toggle Cart
        function toggleCart() {
            document.getElementById('cartSidebar').classList.toggle('show');
            document.getElementById('cartOverlay').classList.toggle('show');
        }

        // Add to Cart
        function addToCart(id, nama, harga, satuan, stok) {
            const existingItem = cart.find(item => item.id === id);

            if (existingItem) {
                if (existingItem.quantity < stok) {
                    existingItem.quantity++;
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Tidak Cukup',
                        text: 'Jumlah pesanan melebihi stok yang tersedia'
                    });
                    return;
                }
            } else {
                if (stok > 0) {
                    cart.push({
                        id: id,
                        nama: nama,
                        harga: harga,
                        satuan: satuan,
                        stok: stok,
                        quantity: 1
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stok Habis',
                        text: 'Bahan baku ini sedang tidak tersedia'
                    });
                    return;
                }
            }

            updateCart();

            // Show success notification
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Ditambahkan ke keranjang',
                showConfirmButton: false,
                timer: 1500
            });
        }

        // Update Cart Display
        function updateCart() {
            const cartItemsDiv = document.getElementById('cartItems');
            const cartBadge = document.getElementById('cartBadge');
            const cartTotal = document.getElementById('cartTotal');
            const checkoutBtn = document.getElementById('checkoutBtn');

            if (cart.length === 0) {
                cartItemsDiv.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                        <p>Keranjang masih kosong</p>
                    </div>
                `;
                cartBadge.textContent = '0';
                checkoutBtn.disabled = true;
                return;
            }

            let html = '';
            let total = 0;

            cart.forEach(item => {
                const subtotal = item.harga * item.quantity;
                total += subtotal;

                html += `
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${item.nama}</h6>
                                    <small class="text-muted">Rp ${item.harga.toLocaleString('id-ID')}</small>
                                </div>
                                <button class="btn btn-sm btn-danger" onclick="removeFromCart(${item.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" onclick="updateQuantity(${item.id}, -1)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" disabled>
                                        ${item.quantity} ${item.satuan}
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="updateQuantity(${item.id}, 1)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <strong class="text-maron">Rp ${subtotal.toLocaleString('id-ID')}</strong>
                            </div>
                        </div>
                    </div>
                `;
            });

            cartItemsDiv.innerHTML = html;
            cartBadge.textContent = cart.length;
            cartTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
            checkoutBtn.disabled = false;
        }

        // Update Quantity
        function updateQuantity(id, change) {
            const item = cart.find(i => i.id === id);
            if (!item) return;

            const newQuantity = item.quantity + change;

            if (newQuantity <= 0) {
                removeFromCart(id);
                return;
            }

            if (newQuantity > item.stok) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Tidak Cukup',
                    text: 'Jumlah pesanan melebihi stok yang tersedia'
                });
                return;
            }

            item.quantity = newQuantity;
            updateCart();
        }

        // Remove from Cart
        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            updateCart();
        }

        // Clear Cart
        function clearCart() {
            Swal.fire({
                title: 'Kosongkan Keranjang?',
                text: 'Semua item akan dihapus dari keranjang',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Kosongkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    cart = [];
                    updateCart();
                    Swal.fire('Terhapus!', 'Keranjang telah dikosongkan', 'success');
                }
            });
        }

        // Checkout
        function checkout() {
            if (cart.length === 0) return;

            // Direct submit on checkout without confirmation popup.
            // Assumption: use tomorrow as default delivery date and empty notes.
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const yyyy = tomorrow.getFullYear();
            const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
            const dd = String(tomorrow.getDate()).padStart(2, '0');
            const defaultDeliveryDate = `${yyyy}-${mm}-${dd}`;

            submitOrder({ delivery_date: defaultDeliveryDate, notes: '' });
        }

        // Submit Order
        function submitOrder(orderData) {
            Swal.fire({
                title: 'Memproses...',
                text: 'Mengirim pesanan Anda',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Prepare order data
            const formData = {
                cart: cart,
                delivery_date: orderData.delivery_date,
                notes: orderData.notes,
                _token: '{{ csrf_token() }}'
            };

            // Submit via AJAX
            fetch('{{ route('outlet.order.store', $token) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pesanan Berhasil!',
                            text: 'Pesanan Anda telah dikirim dan menunggu approval',
                            confirmButtonColor: '#9c1515'
                        }).then(() => {
                            // clear cart and redirect to order detail
                            cart = [];
                            updateCart();
                            toggleCart();
                            // Build detail URL and redirect
                            const detailBase = "{{ url('outlet/' . $token . '/pesanan') }}";
                            if (data.pesanan_id) {
                                window.location.href = detailBase + '/' + data.pesanan_id;
                            } else if (data.nobukti) {
                                // fallback: try to redirect using nobukti if controller returns it
                                window.location.href = detailBase;
                            }
                        });
                    } else if (data.errors && Array.isArray(data.errors)) {
                        // Show detailed stock errors
                        let html = '<div class="text-start small">Beberapa item tidak tersedia dalam jumlah yang diminta:<ul>';
                        data.errors.forEach(err => {
                            html += `<li>${err.nama} — Diminta: ${err.requested}, Tersedia: ${err.available}</li>`;
                        });
                        html += '</ul></div>';

                        Swal.fire({
                            icon: 'warning',
                            title: 'Stok Tidak Cukup',
                            html: html,
                            confirmButtonColor: '#9c1515'
                        });
                    } else {
                        throw new Error(data.message || 'Gagal mengirim pesanan');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message
                    });
                });
        }

        // Hubungi Admin (global footer action)
        function hubungiAdmin() {
            Swal.fire({
                icon: 'info',
                title: 'Hubungi Admin',
                html: `<div class='text-start small'>
                        <p class='mb-2'><strong>WhatsApp:</strong> +62 812-3456-7890</p>
                        <p class='mb-2'><strong>Email:</strong> support@seroo.example</p>
                        <p class='mb-0 text-muted'>Jam tanggapan: 08:00 - 17:00 WIB</p>
                       </div>`,
                confirmButtonColor: '#9c1515',
                confirmButtonText: 'Tutup'
            });
        }
    </script>
</body>

</html>
