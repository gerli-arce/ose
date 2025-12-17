@extends('layout.master')

@section('title', 'Cotizaciones')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Cotizaciones</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Cotizaciones</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Listado de Cotizaciones</h5>
                    <a href="{{ route('quotations.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus me-1"></i> Nueva Cotización
                    </a>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-2">
                            <label class="form-label">Estado</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cliente</label>
                            <select name="customer_id" class="form-select">
                                <option value="">Todos</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Desde</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Hasta</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Buscar</label>
                            <input type="text" name="search" class="form-control" placeholder="Número..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Tabla -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Número</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Vence</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quotations as $quotation)
                                <tr>
                                    <td>
                                        <a href="{{ route('quotations.show', $quotation->id) }}" class="fw-bold text-primary">
                                            {{ $quotation->full_number }}
                                        </a>
                                    </td>
                                    <td>{{ $quotation->issue_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span title="{{ $quotation->customer?->name }}">
                                            {{ Str::limit($quotation->customer?->name ?? 'N/A', 30) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($quotation->is_expired && $quotation->status !== 'invoiced')
                                        <span class="text-danger">
                                            <i class="fa fa-exclamation-circle"></i>
                                            {{ $quotation->expiry_date->format('d/m/Y') }}
                                        </span>
                                        @else
                                        {{ $quotation->expiry_date->format('d/m/Y') }}
                                        @if($quotation->days_to_expire <= 3 && $quotation->status !== 'invoiced')
                                        <small class="text-warning">({{ $quotation->days_to_expire }}d)</small>
                                        @endif
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $quotation->currency?->symbol ?? 'S/' }} {{ number_format($quotation->total, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $quotation->status_color }}">
                                            {{ $quotation->status_name }}
                                        </span>
                                        @if($quotation->salesDocument)
                                        <br>
                                        <small>
                                            <a href="{{ route('sales.documents.show', $quotation->sales_document_id) }}">
                                                Ver factura
                                            </a>
                                        </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('quotations.show', $quotation->id) }}">
                                                        <i class="fa fa-eye me-2"></i>Ver
                                                    </a>
                                                </li>
                                                @if($quotation->canEdit())
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('quotations.edit', $quotation->id) }}">
                                                        <i class="fa fa-edit me-2"></i>Editar
                                                    </a>
                                                </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('quotations.duplicate', $quotation->id) }}">
                                                        <i class="fa fa-copy me-2"></i>Duplicar
                                                    </a>
                                                </li>
                                                @if($quotation->canConvertToInvoice())
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item text-success" href="#" 
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#convertModal{{ $quotation->id }}">
                                                        <i class="fa fa-file-text me-2"></i>Convertir a Factura
                                                    </a>
                                                </li>
                                                @endif
                                                @if($quotation->status === 'draft')
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('quotations.destroy', $quotation->id) }}" method="POST" 
                                                          onsubmit="return confirm('¿Eliminar esta cotización?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fa fa-trash me-2"></i>Eliminar
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Convertir -->
                                @if($quotation->canConvertToInvoice())
                                <div class="modal fade" id="convertModal{{ $quotation->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('quotations.convert', $quotation->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Convertir a Factura/Boleta</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Convertir cotización <strong>{{ $quotation->full_number }}</strong> a:</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tipo de Documento</label>
                                                        <select name="document_type" class="form-select" required>
                                                            <option value="01">Factura</option>
                                                            <option value="03">Boleta</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Serie</label>
                                                        <select name="series_id" class="form-select" required>
                                                            <!-- Cargar series disponibles via JS o desde controlador -->
                                                            @foreach(\App\Models\DocumentSeries::where('company_id', session('current_company_id'))->get() as $series)
                                                            <option value="{{ $series->id }}">{{ $series->prefix }} ({{ $series->documentType?->name ?? 'N/A' }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fa fa-check me-1"></i>Convertir
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fa fa-file-text-o fa-3x mb-2"></i>
                                        <p>No hay cotizaciones registradas</p>
                                        <a href="{{ route('quotations.create') }}" class="btn btn-primary">
                                            Crear primera cotización
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $quotations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
