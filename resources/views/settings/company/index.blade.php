@extends('layout.master')

@section('title', 'Admin Empresa')

@section('css')
<style>
    .sale-chart .card-body .d-flex {
        align-items: center;
    }
    .sale-chart .card-body .sale-detail {
        display: flex;
        align-items: center;
    }
    .sale-chart .card-body .sale-detail .icon {
        width: 50px;
        height: 50px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff3f9;
        flex-shrink: 0;
    }
    .sale-chart .card-body .sale-detail .icon svg {
        width: auto;
        height: 24px;
        stroke: #275070;
    }
    .sale-chart .card-body .sale-detail .sale-content {
        margin-left: 20px;
    }
    .sale-chart .card-body .sale-detail .sale-content h3 {
        margin-bottom: 0;
        color: #2c323f;
    }
    .sale-chart .card-body .sale-detail .sale-content p {
        font-weight: 500;
        margin: 0;
        color: #9993b4;
        font-size: 18px;
        letter-spacing: .5px;
    }
    .sale-chart .card-body .small-chart-view {
        min-height: 90px !important;
        max-height: 90px;
        margin-top: -25px;
        width: 115px !important;
        margin-left: auto;
    }
    .sale-chart .income-chart .apexcharts-tooltip.apexcharts-theme-light,
    .sale-chart .visitor-chart .apexcharts-tooltip.apexcharts-theme-light {
        background-color: #955670;
    }
</style>
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>{{ $company->name }}</h3>
                <div class="mt-2">
                    <span class="badge badge-light text-dark border me-1">
                        <i class="fa fa-building-o me-1"></i> RUC: {{ $company->tax_id }}
                    </span>
                    <span class="badge badge-primary">
                        <i class="fa fa-star me-1"></i> Plan: {{ $planUsage['plan_name'] }}
                    </span>
                </div>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">SaaS</li>
                    <li class="breadcrumb-item active">Configuración Empresa</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    {{-- Tabs Navigation --}}
    <ul class="nav nav-tabs border-tab" id="settingsParams" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="summary-tab" data-bs-toggle="tab" href="#summary" role="tab">
                <i data-feather="activity"></i> Resumen
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="general-tab" data-bs-toggle="tab" href="#general" role="tab">
                <i data-feather="briefcase"></i> Datos de Empresa
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="electronic-tab" data-bs-toggle="tab" href="#electronic" role="tab">
                <i data-feather="shield"></i> Facturación Electrónica
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="billing-tab" data-bs-toggle="tab" href="#billing" role="tab">
                <i data-feather="file-text"></i> Parámetros
            </a>
        </li>
    </ul>

    <div class="tab-content mt-4" id="settingsParamsContent">
        
        {{-- TAB 1: RESUMEN / DASHBOARD --}}
        <div class="tab-pane fade show active" id="summary" role="tabpanel">
            {{-- KPI Cards --}}
            <div class="row">
                <!-- Facturas Mes -->
                <div class="col-xl-3 col-md-6 col-sm-6">
                    <div class="card sale-chart">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="sale-detail">
                                        <div class="icon"><i data-feather="file-text"></i></div>
                                        <div class="sale-content">
                                            <h3>Facturas Mes</h3>
                                            <p>{{ $stats['month_invoices_count'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="small-chart-view sales-chart" id="sales-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ventas Mes -->
                <div class="col-xl-3 col-md-6 col-sm-6">
                    <div class="card sale-chart">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="sale-detail">
                                        <div class="icon"><i data-feather="dollar-sign"></i></div>
                                        <div class="sale-content">
                                            <h3>Ventas Mes</h3>
                                            <p>{{ $settings['default_currency'] ?? 'S/' }} {{ number_format($stats['month_total'], 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="small-chart-view income-chart" id="income-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Usuarios -->
                <div class="col-xl-3 col-md-6 col-sm-6">
                    <div class="card sale-chart">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="sale-detail">
                                        <div class="icon"><i data-feather="users"></i></div>
                                        <div class="sale-content">
                                            <h3>Usuarios</h3>
                                            <p>{{ $stats['active_users'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="small-chart-view visitor-chart" id="visitor-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Productos -->
                <div class="col-xl-3 col-md-6 col-sm-6">
                    <div class="card sale-chart">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="sale-detail">
                                        <div class="icon"><i data-feather="shopping-bag"></i></div>
                                        <div class="sale-content">
                                            <h3>Productos</h3>
                                            <p>{{ $stats['products_count'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="small-chart-view order-chart" id="order-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Historial de Ventas (6 meses)</h5>
                        </div>
                        <div class="card-body">
                             <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Limits & Usage --}}
                <div class="col-xl-4 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Consumo del Plan</h5>
                        </div>
                        <div class="card-body">
                             {{-- Users Limit --}}
                             <h6>Usuarios <span class="float-end text-muted">{{ $stats['active_users'] }} / {{ $planUsage['max_users'] }}</span></h6>
                             <div class="progress mb-4" style="height: 5px;">
                                 @php $userPerc = $planUsage['max_users'] > 0 ? ($stats['active_users'] / $planUsage['max_users']) * 100 : 0; @endphp
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $userPerc }}%"></div>
                            </div>
                            
                            {{-- Invoices Limit --}}
                             <h6>Facturas / Mes <span class="float-end text-muted">{{ $stats['month_invoices_count'] }} / {{ $planUsage['max_invoices_per_month'] ?? 'N/A' }}</span></h6>
                        </div>
                    </div>
                    
                    {{-- Quick Stats --}}
                    <div class="card">
                         <div class="card-body">
                             <p><strong>Clientes:</strong> {{ $stats['customers_count'] }}</p>
                             <p><strong>Proveedores:</strong> {{ $stats['suppliers_count'] }}</p>
                             <p><strong>Sucursales:</strong> {{ $stats['branches_count'] }}</p>
                             <hr>
                             <p class="mb-0"><strong>Total Facturado Año:</strong> S/ {{ number_format($stats['year_total'], 2) }}</p>
                         </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- TAB 2: GENERAL DATA --}}
        <div class="tab-pane fade" id="general" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5>Datos Generales de la Empresa</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.company.general') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Razón Social / Nombre</label>
                                <input type="text" name="name" class="form-control" value="{{ $company->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre Comercial</label>
                                <input type="text" name="trade_name" class="form-control" value="{{ $company->trade_name }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">RUC (Tax ID)</label>
                                <input type="text" name="tax_id" class="form-control" value="{{ $company->tax_id }}" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $company->email }}" required>
                            </div>
                             <div class="col-md-4 mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="phone" class="form-control" value="{{ $company->phone }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Logo Actual</label>
                            @if($company->logo_path)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($company->logo_path) }}" alt="Logo" height="50">
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <small class="text-muted">Subir nueva imagen para actualizar.</small>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- TAB 3: ELECTRONIC BILLING --}}
        <div class="tab-pane fade" id="electronic" role="tabpanel">
             <div class="card">
                <div class="card-header">
                    <h5>Configuración SUNAT (Facturación Electrónica)</h5>
                </div>
                <div class="card-body">
                     <form action="{{ route('settings.company.electronic') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label class="form-label">Usuario SOL</label>
                                <input type="text" name="sunat_sol_user" class="form-control" value="{{ $company->sunat_sol_user }}" placeholder="Ej: MODDATOS">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Clave SOL</label>
                                <input type="password" name="sunat_sol_password" class="form-control" placeholder="Dejar vacío para no cambiar">
                                <small class="text-muted">Se guardará encriptado.</small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Certificado Digital (.pfx / .p12)</label>
                                <input type="file" name="certificate" class="form-control">
                                @if($company->sunat_cert_path)
                                    <span class="badge badge-success mt-1">Certificado Cargado</span>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contraseña Certificado</label>
                                <input type="password" name="sunat_cert_password" class="form-control" placeholder="Dejar vacío para no cambiar">
                            </div>
                        </div>
                        
                         <div class="mb-3">
                            <label class="form-label">Ambiente</label>
                            <select name="sunat_env" class="form-select">
                                <option value="beta" {{ $company->sunat_env == 'beta' ? 'selected' : '' }}>Beta (Pruebas)</option>
                                <option value="production" {{ $company->sunat_env == 'production' ? 'selected' : '' }}>Producción</option>
                            </select>
                        </div>

                         <div class="text-end">
                            <button type="submit" class="btn btn-primary">Actualizar Credenciales</button>
                        </div>
                     </form>
                </div>
             </div>
        </div>

        {{-- TAB 4: BILLING PARAMS --}}
        <div class="tab-pane fade" id="billing" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5>Parámetros de Facturación</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.company.billing') }}" method="POST">
                        @csrf
                         <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Moneda por Defecto</label>
                                <select name="default_currency" class="form-select">
                                    <option value="PEN" {{ ($settings['default_currency'] ?? '') == 'PEN' ? 'selected' : '' }}>Soles (PEN)</option>
                                    <option value="USD" {{ ($settings['default_currency'] ?? '') == 'USD' ? 'selected' : '' }}>Dólares (USD)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">IGV (%)</label>
                                <input type="number" name="default_tax_rate" class="form-control" value="{{ $settings['default_tax_rate'] ?? '18' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Plantilla PDF</label>
                                <select name="invoice_pdf_template" class="form-select">
                                    <option value="default" {{ ($settings['invoice_pdf_template'] ?? '') == 'default' ? 'selected' : '' }}>Default</option>
                                    <option value="simple" {{ ($settings['invoice_pdf_template'] ?? '') == 'simple' ? 'selected' : '' }}>Simple</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                             <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="autoNum" name="invoice_auto_numbering" value="1" {{ ($settings['invoice_auto_numbering'] ?? '0') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="autoNum">Auto-numeración de comprobantes</label>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Guardar Parámetros</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof ApexCharts === 'undefined') {
            console.error('ApexCharts library not loaded!');
            return;
        }

        // Data from Controller
        const chartData = @json($chartData);
        // Colors (Fallback to config if avail, else hardcoded)
        const primaryColor = (typeof KohoAdminConfig !== 'undefined') ? KohoAdminConfig.primary : '#534686';
        const secondaryColor = (typeof KohoAdminConfig !== 'undefined') ? KohoAdminConfig.secondary : '#FFA47A';
        
        // Config Common for Sparklines
        const sparklineOptions = {
            chart: { 
                type: "bar", 
                height: 90, 
                width: 115, // Explicit width
                stacked: true, 
                toolbar: { show: false },
                sparkline: { enabled: true } // Try sparkline mode true 
            },
            plotOptions: { bar: { horizontal: false, columnWidth: "40px", borderRadius: 2 } },
            grid: { show: false, padding: { left: 0, right: 0, top: 0, bottom: 0 } },
            dataLabels: { enabled: false },
            legend: { show: false },
            xaxis: { show: false, labels: { show: false }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: { show: false, labels: { show: false } },
            tooltip: { enabled: true },
            fill: { opacity: 1 }
        };

        // 1. Invoices Chart
        if(document.querySelector("#sales-chart")) {
            new ApexCharts(document.querySelector("#sales-chart"), {
                ...sparklineOptions,
                series: [{ name: "Facturas", data: chartData.invoices }],
                colors: [primaryColor, "#dad8e0"], // Stacked often needs 2 colors if multiple series, but here single.
            }).render();
        }

        // 2. Sales Chart
        if(document.querySelector("#income-chart")) {
            new ApexCharts(document.querySelector("#income-chart"), {
                ...sparklineOptions,
                series: [{ name: "Ventas", data: chartData.sales }],
                colors: [secondaryColor, "#faded1"],
            }).render();
        }

        // 3. Users Chart
        if(document.querySelector("#visitor-chart")) {
            new ApexCharts(document.querySelector("#visitor-chart"), {
                ...sparklineOptions,
                series: [{ name: "Usuarios", data: chartData.users }],
                colors: [secondaryColor, "#faded1"],
            }).render();
        }

        // 4. Products Chart
        if(document.querySelector("#order-chart")) {
            new ApexCharts(document.querySelector("#order-chart"), {
                ...sparklineOptions,
                series: [{ name: "Productos", data: chartData.products }],
                colors: [primaryColor, "#dad8e0"],
            }).render();
        }

        // 5. Main Sales History Chart (Chart.js)
        if(document.getElementById('salesChart')) {
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Ventas (S/)',
                        data: chartData.sales,
                        backgroundColor: 'rgba(36, 105, 92, 0.7)',
                        borderColor: 'rgba(36, 105, 92, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
