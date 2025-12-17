@extends('layout.master')

@section('title', 'Dashboard - Facturación Electrónica')

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        border-radius: 12px;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .stat-label {
        color: #6c757d;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-change {
        font-size: 0.8rem;
        font-weight: 600;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
    .sunat-status-card {
        border-left: 4px solid;
    }
    .sunat-status-card.success { border-color: #28a745; }
    .sunat-status-card.warning { border-color: #ffc107; }
    .sunat-status-card.danger { border-color: #dc3545; }
    .sunat-status-card.info { border-color: #17a2b8; }
    .top-item {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .top-item:last-child {
        border-bottom: none;
    }
    .top-rank {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 12px;
    }
    .alert-item {
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 10px;
    }
    .quick-action-btn {
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        transition: all 0.2s;
    }
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .quick-action-btn i {
        font-size: 24px;
        display: block;
        margin-bottom: 8px;
    }
</style>
@endpush

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Dashboard SUNAT</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Alertas -->
    @if(count($alerts) > 0)
    <div class="row mb-4">
        <div class="col-12">
            @foreach($alerts as $alert)
            <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fa {{ $alert['icon'] }} me-2"></i>
                <span class="flex-grow-1">{{ $alert['message'] }}</span>
                @if(isset($alert['action']))
                <a href="{{ $alert['action'] }}" class="btn btn-sm btn-{{ $alert['type'] }} ms-2">{{ $alert['action_text'] }}</a>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Widgets de Estadísticas -->
    <div class="row">
        <!-- Ventas del Día -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label mb-1">Ventas Hoy</p>
                            <h3 class="stat-value text-primary">S/ {{ number_format($salesToday->total ?? 0, 2) }}</h3>
                            <small class="text-muted">{{ $salesToday->count ?? 0 }} documentos</small>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas de la Semana -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label mb-1">Ventas Semana</p>
                            <h3 class="stat-value text-success">S/ {{ number_format($salesWeek->total ?? 0, 2) }}</h3>
                            <small class="text-muted">{{ $salesWeek->count ?? 0 }} documentos</small>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="fa fa-line-chart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas del Mes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label mb-1">Ventas del Mes</p>
                            <h3 class="stat-value text-info">S/ {{ number_format($salesMonth->total ?? 0, 2) }}</h3>
                            <div class="d-flex align-items-center">
                                <small class="text-muted me-2">{{ $salesMonth->count ?? 0 }} docs</small>
                                @if($monthChange != 0)
                                <span class="stat-change {{ $monthChange > 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fa fa-{{ $monthChange > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ abs($monthChange) }}%
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fa fa-bar-chart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado SUNAT -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stat-label mb-1">Estado SUNAT</p>
                            @if($sunatConfigured)
                            <h3 class="stat-value text-success"><i class="fa fa-check-circle"></i></h3>
                            <small class="text-success">Configurado</small>
                            @else
                            <h3 class="stat-value text-warning"><i class="fa fa-exclamation-circle"></i></h3>
                            <small class="text-warning">Sin configurar</small>
                            @endif
                        </div>
                        <div class="stat-icon bg-{{ $sunatConfigured ? 'success' : 'warning' }} bg-opacity-10 text-{{ $sunatConfigured ? 'success' : 'warning' }}">
                            <i class="fa fa-cloud"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <div class="row g-2">
                        <div class="col-auto">
                            <a href="{{ route('sales.documents.create') }}" class="btn btn-primary quick-action-btn">
                                <i class="fa fa-file-text-o"></i>
                                Nueva Factura
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('sales.documents.create', ['type' => '03']) }}" class="btn btn-success quick-action-btn">
                                <i class="fa fa-file-o"></i>
                                Nueva Boleta
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('sales.credit-notes.create') }}" class="btn btn-warning quick-action-btn">
                                <i class="fa fa-minus-circle"></i>
                                Nota Crédito
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('despatch.index') }}" class="btn btn-info quick-action-btn">
                                <i class="fa fa-truck"></i>
                                Guías Remisión
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('settings.sunat.index') }}" class="btn btn-secondary quick-action-btn">
                                <i class="fa fa-cog"></i>
                                Config. SUNAT
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <!-- Gráfico de Ventas -->
        <div class="col-xl-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fa fa-line-chart me-2"></i>Ventas - Últimos 30 días</h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary active" data-period="30days">30 días</button>
                        <button type="button" class="btn btn-outline-primary" data-period="7days">7 días</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado de Documentos SUNAT -->
        <div class="col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa fa-cloud me-2"></i>Estado Documentos SUNAT</h5>
                </div>
                <div class="card-body">
                    <div class="sunat-status-card success bg-success bg-opacity-10 p-3 rounded mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Aceptados</small>
                                <h4 class="mb-0 text-success">{{ $salesMonth->count - $pendingDocs - $rejectedDocs }}</h4>
                            </div>
                            <i class="fa fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                    
                    <div class="sunat-status-card warning bg-warning bg-opacity-10 p-3 rounded mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Pendientes</small>
                                <h4 class="mb-0 text-warning">{{ $pendingDocs }}</h4>
                            </div>
                            <i class="fa fa-clock-o fa-2x text-warning"></i>
                        </div>
                    </div>
                    
                    <div class="sunat-status-card danger bg-danger bg-opacity-10 p-3 rounded mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Rechazados</small>
                                <h4 class="mb-0 text-danger">{{ $rejectedDocs }}</h4>
                            </div>
                            <i class="fa fa-times-circle fa-2x text-danger"></i>
                        </div>
                    </div>
                    
                    <div class="sunat-status-card info bg-info bg-opacity-10 p-3 rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Resúmenes Pendientes</small>
                                <h4 class="mb-0 text-info">{{ $pendingSummaries }}</h4>
                            </div>
                            <i class="fa fa-list-alt fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda fila de gráficos -->
    <div class="row">
        <!-- Ventas por Tipo de Documento -->
        <div class="col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa fa-bar-chart me-2"></i>Ventas por Tipo de Documento</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="typeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribución por Método de Pago -->
        <div class="col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa fa-pie-chart me-2"></i>Distribución por Método de Pago</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documentos Recientes y Rankings -->
    <div class="row">
        <!-- Documentos Recientes -->
        <div class="col-xl-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fa fa-file-text me-2"></i>Documentos Recientes</h5>
                    <a href="{{ route('sales.documents.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Documento</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>SUNAT</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDocuments as $doc)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $doc->documentType?->code == '01' ? 'primary' : 'success' }} me-1">
                                            {{ $doc->documentType?->code }}
                                        </span>
                                        {{ $doc->full_number ?? $doc->series?->prefix . '-' . str_pad($doc->number, 8, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($doc->customer?->name ?? 'N/A', 25) }}</small>
                                    </td>
                                    <td>
                                        <strong>S/ {{ number_format($doc->total, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($doc->eDocument)
                                            @if($doc->eDocument->response_status == 'accepted')
                                            <span class="badge bg-success"><i class="fa fa-check"></i></span>
                                            @elseif($doc->eDocument->response_status == 'rejected')
                                            <span class="badge bg-danger"><i class="fa fa-times"></i></span>
                                            @else
                                            <span class="badge bg-warning"><i class="fa fa-clock-o"></i></span>
                                            @endif
                                        @else
                                        <span class="badge bg-secondary"><i class="fa fa-minus"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('sales.documents.show', $doc->id) }}"><i class="fa fa-eye me-2"></i>Ver</a></li>
                                                <li><a class="dropdown-item" href="{{ route('pdf.document.a4', $doc->id) }}" target="_blank"><i class="fa fa-file-pdf-o me-2"></i>PDF</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fa fa-file-text-o fa-3x mb-2"></i>
                                        <p>No hay documentos recientes</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Productos y Clientes -->
        <div class="col-xl-6 mb-4">
            <div class="row">
                <!-- Top Productos -->
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fa fa-trophy me-2 text-warning"></i>Top 5 Productos del Mes</h5>
                        </div>
                        <div class="card-body">
                            @forelse($topProducts as $index => $product)
                            <div class="top-item d-flex align-items-center">
                                <div class="top-rank bg-{{ $index < 3 ? ['warning', 'secondary', 'info'][$index] : 'light' }} text-{{ $index < 3 ? 'white' : 'dark' }} me-3">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-grow-1">
                                    <strong>{{ Str::limit($product->name, 30) }}</strong>
                                    <small class="text-muted d-block">{{ number_format($product->total_qty) }} unidades</small>
                                </div>
                                <strong class="text-success">S/ {{ number_format($product->total_amount, 2) }}</strong>
                            </div>
                            @empty
                            <p class="text-muted text-center mb-0">Sin datos este mes</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top Clientes -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fa fa-users me-2 text-primary"></i>Top 5 Clientes del Mes</h5>
                        </div>
                        <div class="card-body">
                            @forelse($topCustomers as $index => $customer)
                            <div class="top-item d-flex align-items-center">
                                <div class="top-rank bg-{{ $index < 3 ? ['primary', 'success', 'info'][$index] : 'light' }} text-{{ $index < 3 ? 'white' : 'dark' }} me-3">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-grow-1">
                                    <strong>{{ Str::limit($customer->name, 25) }}</strong>
                                    <small class="text-muted d-block">{{ $customer->doc_count }} documentos</small>
                                </div>
                                <strong class="text-primary">S/ {{ number_format($customer->total_amount, 2) }}</strong>
                            </div>
                            @empty
                            <p class="text-muted text-center mb-0">Sin datos este mes</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Colores del tema
    const colors = {
        primary: '#7366ff',
        success: '#54ba4a',
        warning: '#ffaa05',
        danger: '#fc4438',
        info: '#16c7f9',
        secondary: '#6c757d'
    };

    // Gráfico de Ventas (Línea)
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Ventas (S/)',
                data: {!! json_encode($chartValues) !!},
                borderColor: colors.primary,
                backgroundColor: 'rgba(115, 102, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointBackgroundColor: colors.primary
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'S/ ' + context.parsed.y.toLocaleString('es-PE', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'S/ ' + value.toLocaleString();
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Gráfico por Tipo de Documento (Barras)
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($typeLabels) !!},
            datasets: [{
                label: 'Monto (S/)',
                data: {!! json_encode($typeValues) !!},
                backgroundColor: [colors.primary, colors.success, colors.warning, colors.info],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const counts = {!! json_encode($typeCounts) !!};
                            return [
                                'Monto: S/ ' + context.parsed.y.toLocaleString('es-PE', {minimumFractionDigits: 2}),
                                'Cantidad: ' + counts[context.dataIndex] + ' docs'
                            ];
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'S/ ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Gráfico por Método de Pago (Pie/Doughnut)
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($paymentLabels) !!},
            datasets: [{
                data: {!! json_encode($paymentValues) !!},
                backgroundColor: [colors.primary, colors.success, colors.warning, colors.info, colors.secondary],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': S/ ' + context.parsed.toLocaleString('es-PE', {minimumFractionDigits: 2}) + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Cambiar período del gráfico
    document.querySelectorAll('[data-period]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('[data-period]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Aquí se podría hacer una petición AJAX para obtener nuevos datos
            // Por ahora solo es visual
        });
    });
});
</script>
@endpush
