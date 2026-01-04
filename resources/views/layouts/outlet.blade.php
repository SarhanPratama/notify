<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @php
$title = 'Pesanan Bahan Baku - ' . $outlet->nama;
    @endphp
    <title>Seroo - {{ $title }}</title>
    @include('layouts.link')
    <style>
    .cart-floating {
position: fixed;
bottom: 20px;
right: 10px;
z-index: 1000;
background-color: #9c1515;
color: white;
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
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    <!-- Header Outlet -->
 <div class="bg-maron text-white py-3">

    <div class="row align-items-center">
        <div class="col-md-2 text-center text-md-left mb-3 mb-md-0">
            <img src="{{ asset('assets/img/logo/brand.png') }}" width="100" alt="Seroo Logo" class="img-fluid mx-auto d-block d-md-inline">
        </div>
        <div class="col-md-8 text-center mb-3">
            <div class="h3 font-weight-bold mb-0">Wherehouse Teh Tarik Sero</div>
        </div>
        <div class="col-md-2 text-center text-md-right">
             <h5 class="mb-0 fw-bold">{{ strtoupper($outlet->nama) }}</h5>
             <h5 class="mb-0">{{ strtoupper($outlet->penanggung_jawab) }}</h5>
        </div>
    </div>
</div>

    <!-- Navigation -->
    <nav class="sticky-top bg-maron navbar-dark py-2">
<div class="container">
    <ul class="nav justify-content-center flex-nowrap">
        <li class="nav-item">
            <a class="nav-link text-white px-3 {{ request()->routeIs('outlet.belanja') ? 'active fw-bold border-bottom border-white' : '' }}"
               href="{{ route('outlet.belanja', ['token' => $outlet->barcode_token]) }}">

                <span class="fw-bold">Belanja</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white px-3 {{ request()->routeIs('outlet.pesanan', 'outlet.pesanan.detail') ? 'active fw-bold border-bottom border-white' : '' }}"
               href="{{ route('outlet.pesanan', ['token' => $outlet->barcode_token]) }}">

                <span class="fw-bold">Pesanan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white px-3 {{ request()->routeIs(['outlet.kasbon', 'outlet.kasbon.detail']) ? 'active fw-bold border-bottom border-white' : '' }}"
               href="{{ route('outlet.kasbon', ['token' => $outlet->barcode_token]) }}">

                {{-- <span class="d-none d-sm-inline">Kasbon</span> --}}
                <span class="fw-bold">Kasbon</span>
            </a>
        </li>
    </ul>
</div>
    </nav>
    <!-- Main Content -->

@yield('content')

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
                <a href="https://wa.me/6281373586179" class="btn btn-outline-primary btn-sm" >
                    WhatsApp
                </a>
            </div>
        </div>
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
</body>

</html>
