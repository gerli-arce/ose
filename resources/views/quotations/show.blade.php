@extends('layout.master')

@section('title', 'Cotización ' . $quotation->full_number)

@push('styles')
<style>
    .status-badge { font-size: 0.9rem; padding: 8px 16px; }
    .info-label { color: #6c757d; font-size: 0.85rem; }
    .info-value { font-weight: 600; }
    .item-table th { background: #f8f9fa; }
    .totals-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .action-btn { min-width: 140px; }
</style>
@endpush

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Cotización {{ $quotation->full_number }}</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">Cotizaciones</a></li>
                    <li class="breadcrumb-item active">{{ $quotation->full_number }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Columna Principal -->
        <div class="col-lg-8">
            <!-- Información General -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fa fa-file-text-o me-2"></i>Información General</h5>
                    <span class="badge bg-{{ $quotation->status_color }} status-badge">
                        {{ $quotation->status_name }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <span class="info-label">Cliente</span>
                                <p class="info-value mb-0">{{ $quotation->customer?->name ?? 'N/A' }}</p>
                                <small class="text-muted">{{ $quotation->customer?->tax_id }}</small>
                            </div>
                            <div class="mb-3">
                                <span class="info-label">Fecha de Emisión</span>
                                <p class="info-value mb-0">{{ $quotation->issue_date->format('d/m/Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <span class="info-label">Fecha de Vencimiento</span>
                                <p class="info-value mb-0 {{ $quotation->is_expired ? 'text-danger' : '' }}">
                                    {{ $quotation->expiry_date->format('d/m/Y') }}
                                    @if(!$quotation->is_expired && $quotation->days_to_expire <= 5)
                                    <span class="badge bg-warning ms-1">{{ $quotation->days_to_expire }} días</span>
                                    @endif
                                    @if($quotation->is_expired)
                                    <span class="badge bg-danger ms-1">Vencida</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <span class="info-label">Moneda</span>
                                <p class="info-value mb-0">{{ $quotation->currency?->name ?? 'Soles' }} ({{ $quotation->currency?->code ?? 'PEN' }})</p>
                            </div>
                            <div class="mb-3">
                                <span class="info-label">Vendedor</span>
                                <p class="info-value mb-0">{{ $quotation->seller?->name ?? 'N/A' }}</p>
                            </div>
                            @if($quotation->salesDocument)
                            <div class="mb-3">
                                <span class="info-label">Documento Generado</span>
                                <p class="info-value mb-0">
                                    <a href="{{ route('sales.documents.show', $quotation->sales_document_id) }}" class="text-primary">
                                        <i class="fa fa-external-link me-1"></i>Ver Factura/Boleta
                                    </a>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa fa-list me-2"></i>Detalle de Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table item-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">P. Unitario</th>
                                    <th class="text-center">Desc.</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end">IGV</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotation->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->description }}</strong>
                                        @if($item->product)
                                        <br><small class="text-muted">SKU: {{ $item->product->sku ?? '-' }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-center">
                                        @if($item->discount_percent > 0)
                                        {{ number_format($item->discount_percent, 0) }}%
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="text-end">{{ number_format($item->subtotal, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->tax_amount, 2) }}</td>
                                    <td class="text-end fw-bold">{{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Notas -->
            @if($quotation->notes || $quotation->terms)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa fa-sticky-note me-2"></i>Notas y Términos</h5>
                </div>
                <div class="card-body">
                    @if($quotation->notes)
                    <div class="mb-3">
                        <strong>Notas:</strong>
                        <p class="mb-0">{{ $quotation->notes }}</p>
                    </div>
                    @endif
                    @if($quotation->terms)
                    <div>
                        <strong>Términos y Condiciones:</strong>
                        <p class="mb-0">{{ $quotation->terms }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Columna Lateral -->
        <div class="col-lg-4">
            <!-- Totales -->
            <div class="card mb-4 totals-card">
                <div class="card-body">
                    <h5 class="text-white mb-4">Resumen</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>{{ $quotation->currency?->symbol ?? 'S/' }} {{ number_format($quotation->subtotal, 2) }}</strong>
                    </div>
                    @if($quotation->discount_total > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Descuento:</span>
                        <strong>- {{ $quotation->currency?->symbol ?? 'S/' }} {{ number_format($quotation->discount_total, 2) }}</strong>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span>IGV (18%):</span>
                        <strong>{{ $quotation->currency?->symbol ?? 'S/' }} {{ number_format($quotation->tax_total, 2) }}</strong>
                    </div>
                    <hr class="bg-white">
                    <div class="d-flex justify-content-between">
                        <span class="fs-5">TOTAL:</span>
                        <strong class="fs-4">{{ $quotation->currency?->symbol ?? 'S/' }} {{ number_format($quotation->total, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa fa-bolt me-2"></i>Acciones</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($quotation->canEdit())
                        <a href="{{ route('quotations.edit', $quotation->id) }}" class="btn btn-warning action-btn">
                            <i class="fa fa-edit me-2"></i>Editar
                        </a>
                        @endif

                        @if($quotation->canConvertToInvoice())
                        <button type="button" class="btn btn-success action-btn" data-bs-toggle="modal" data-bs-target="#convertModal">
                            <i class="fa fa-file-text me-2"></i>Convertir a Factura
                        </button>
                        @endif

                        <a href="{{ route('quotations.duplicate', $quotation->id) }}" class="btn btn-info action-btn">
                            <i class="fa fa-copy me-2"></i>Duplicar
                        </a>

                        <a href="{{ route('quotations.pdf', $quotation->id) }}" target="_blank" class="btn btn-secondary action-btn">
                            <i class="fa fa-file-pdf-o me-2"></i>Descargar PDF
                        </a>

                        @if($quotation->status === 'sent' || $quotation->status === 'draft')
                        <hr>
                        <form action="{{ route('quotations.status', $quotation->id) }}" method="POST" class="d-grid gap-2">
                            @csrf
                            <input type="hidden" name="status" value="accepted">
                            <button type="submit" class="btn btn-outline-success">
                                <i class="fa fa-check me-2"></i>Marcar Aceptada
                            </button>
                        </form>
                        <form action="{{ route('quotations.status', $quotation->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fa fa-times me-2"></i>Marcar Rechazada
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Historial -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa fa-clock-o me-2"></i>Información</h5>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>Creada:</strong> {{ $quotation->created_at->format('d/m/Y H:i') }}<br>
                        <strong>Actualizada:</strong> {{ $quotation->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Convertir -->
@if($quotation->canConvertToInvoice())
<div class="modal fade" id="convertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('quotations.convert', $quotation->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Convertir a Factura/Boleta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Se creará un documento de venta basado en esta cotización.</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select" required id="docTypeSelect">
                            <option value="01">Factura</option>
                            <option value="03">Boleta de Venta</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Serie <span class="text-danger">*</span></label>
                        <select name="series_id" class="form-select" required>
                            @foreach(\App\Models\DocumentSeries::where('company_id', session('current_company_id'))->with('documentType')->get() as $series)
                            <option value="{{ $series->id }}" data-type="{{ $series->documentType?->code }}">
                                {{ $series->prefix }} ({{ $series->documentType?->name ?? 'N/A' }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <i class="fa fa-info-circle me-2"></i>
                        El documento se creará con fecha de hoy y los mismos items de la cotización.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check me-2"></i>Convertir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
