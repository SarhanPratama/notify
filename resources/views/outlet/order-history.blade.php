@include('layouts.link')

<title>Seroo - {{ $title }}</title>

<style>
    .bg-maron {
        background-color: #9c1515;
    }
    .text-maron {
        color: #861414;
    }
    .outlet-header {
        background: linear-gradient(135deg, #9c1515 0%, #861414 100%);
        color: white;
        padding: 20px 0;
        margin-bottom: 30px;
    }
    .order-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8em;
        font-weight: 600;
    }
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }
    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }
    .status-completed {
        background-color: #d1ecf1;
        color: #0c5460;
    }
</style>

<body style="background-color: #f8f9fc;">

    <!-- Outlet Header -->
    <header class="outlet-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/logo/brand.png') }}" alt="Seroo Logo" width="60" class="me-3">
                        <div>
                            <h2 class="mb-1">{{ $outlet->nama }}</h2>
                            <p class="mb-0 opacity-75">📋 Riwayat Pesanan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('outlet.order.form', $token) }}" class="btn btn-light btn-sm me-2">
                        <i class="fas fa-plus me-1"></i> Buat Pesanan Baru
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mb-5">
        <!-- Summary Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center border-0 bg-warning text-white">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $orders->where('status', 'pending')->count() }}</h3>
                        <small>Menunggu Approval</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-0 bg-success text-white">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $orders->where('status', 'approved')->count() }}</h3>
                        <small>Disetujui</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-0 bg-info text-white">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $orders->where('status', 'completed')->count() }}</h3>
                        <small>Selesai</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-0 bg-primary text-white">
                    <div class="card-body">
                        <h3 class="mb-0">{{ $orders->total() }}</h3>
                        <small>Total Pesanan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="row">
            @forelse($orders as $order)
            <div class="col-lg-6 mb-4">
                <div class="card order-card h-100">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-maron">
                                <i class="fas fa-receipt me-1"></i>
                                {{ $order->nobukti }}
                            </h6>
                            <span class="status-badge status-{{ $order->status }}">
                                @switch($order->status)
                                    @case('pending')
                                        🔄 Menunggu Approval
                                        @break
                                    @case('approved')
                                        ✅ Disetujui
                                        @break
                                    @case('rejected')
                                        ❌ Ditolak
                                        @break
                                    @case('completed')
                                        🎉 Selesai
                                        @break
                                    @default
                                        📋 {{ ucfirst($order->status) }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Tanggal Pesanan</small>
                                <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Tanggal Pengiriman</small>
                                <strong>{{ \Carbon\Carbon::parse($order->tanggal_pengiriman)->format('d/m/Y') ?? '-' }}</strong>
                            </div>
                        </div>

                        <!-- Items Summary -->
                        <div class="mb-3">
                            <small class="text-muted d-block mb-2">Item yang Dipesan:</small>
                            <div class="bg-light p-2 rounded">
                                @if($order->mutasi && $order->mutasi->count() > 0)
                                    @foreach($order->mutasi->take(3) as $item)
                                    <div class="d-flex justify-content-between align-items-center small">
                                        <span>{{ $item->bahanBaku->nama ?? 'Item tidak ditemukan' }}</span>
                                        <span class="fw-bold">{{ $item->quantity }} {{ $item->bahanBaku->satuan->nama ?? 'pcs' }}</span>
                                    </div>
                                    @endforeach
                                    @if($order->mutasi->count() > 3)
                                    <small class="text-muted">dan {{ $order->mutasi->count() - 3 }} item lainnya</small>
                                    @endif
                                @else
                                    <small class="text-muted">Tidak ada detail item</small>
                                @endif
                            </div>
                        </div>

                        <!-- Total Amount -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold">Total:</span>
                            <span class="h6 mb-0 text-success">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>

                        <!-- Catatan -->
                        @if($order->catatan)
                        <div class="mb-3">
                            <small class="text-muted d-block">Catatan:</small>
                            <small class="fst-italic">"{{ $order->catatan }}"</small>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('outlet.order.detail', [$token, $order->id]) }}" 
                               class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                            
                            @if($order->status === 'pending')
                            <button class="btn btn-sm btn-outline-warning" onclick="showOrderStatus('{{ $order->nobukti }}', 'pending')">
                                <i class="fas fa-clock me-1"></i> Status
                            </button>
                            @elseif($order->status === 'approved')
                            <button class="btn btn-sm btn-outline-success" onclick="showOrderStatus('{{ $order->nobukti }}', 'approved')">
                                <i class="fas fa-check me-1"></i> Disetujui
                            </button>
                            @elseif($order->status === 'completed')
                            <button class="btn btn-sm btn-outline-info" onclick="showOrderStatus('{{ $order->nobukti }}', 'completed')">
                                <i class="fas fa-star me-1"></i> Selesai
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-light border-top-0">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Terakhir diupdate: {{ $order->updated_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted mb-3">Belum Ada Pesanan</h4>
                        <p class="text-muted mb-4">Anda belum membuat pesanan apapun. Mulai pesan bahan baku untuk outlet Anda.</p>
                        <a href="{{ route('outlet.order.form', $token) }}" class="btn bg-maron text-white">
                            <i class="fas fa-plus me-1"></i> Buat Pesanan Pertama
                        </a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

    @include('layouts.script')

    <script>
        function showOrderStatus(nobukti, status) {
            let title = 'Status Pesanan';
            let html = '';
            let icon = 'info';

            switch(status) {
                case 'pending':
                    html = `
                        <p><strong>Pesanan ${nobukti}</strong></p>
                        <p>📋 Status: <span class="text-warning">Menunggu Approval</span></p>
                        <p>Pesanan Anda sedang ditinjau oleh tim gudang. Mohon tunggu konfirmasi.</p>
                    `;
                    icon = 'warning';
                    break;
                case 'approved':
                    html = `
                        <p><strong>Pesanan ${nobukti}</strong></p>
                        <p>✅ Status: <span class="text-success">Disetujui</span></p>
                        <p>Pesanan Anda telah disetujui dan sedang diproses untuk pengiriman.</p>
                    `;
                    icon = 'success';
                    break;
                case 'completed':
                    html = `
                        <p><strong>Pesanan ${nobukti}</strong></p>
                        <p>🎉 Status: <span class="text-info">Selesai</span></p>
                        <p>Pesanan telah selesai dan barang sudah diterima.</p>
                    `;
                    icon = 'success';
                    break;
            }

            Swal.fire({
                title: title,
                html: html,
                icon: icon,
                confirmButtonText: 'OK'
            });
        }

        // Auto refresh setiap 5 menit untuk update status
        setInterval(function() {
            location.reload();
        }, 300000); // 5 menit
    </script>

</body>
</html>