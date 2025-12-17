@extends('layout.master')

@section('title', 'Resúmenes Diarios')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Resúmenes Diarios de Boletas</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.documents.index') }}">Ventas</a></li>
                    <li class="breadcrumb-item active">Resúmenes Diarios</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    {{-- Alerta de boletas pendientes --}}
    @if($pendingBoletas > 0)
    <div class="alert alert-warning d-flex justify-content-between align-items-center">
        <div>
            <i class="fa fa-exclamation-triangle me-2"></i>
            <strong>{{ $pendingBoletas }} boletas</strong> emitidas hoy pendientes de incluir en resumen diario.
        </div>
        <a href="{{ route('sales.summaries.create') }}" class="btn btn-warning">
            <i class="fa fa-plus me-1"></i> Generar Resumen
        </a>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">
                        <i class="fa fa-file-text me-2"></i>
                        Historial de Resúmenes Diarios
                    </h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('sales.summaries.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus me-1"></i> Nuevo Resumen
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Filtros -->
            <form method="GET" class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select class="form-select" name="status">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Enviado</option>
                        <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Aceptado</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search me-1"></i> Filtrar
                    </button>
                </div>
            </form>

            <!-- Tabla -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Identificador</th>
                            <th>Fecha Resumen</th>
                            <th>Fecha Docs.</th>
                            <th class="text-center">Documentos</th>
                            <th class="text-end">Total</th>
                            <th>Ticket</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summaries as $summary)
                        <tr>
                            <td>
                                <a href="{{ route('sales.summaries.show', $summary->id) }}" class="fw-bold">
                                    {{ $summary->identifier }}
                                </a>
                            </td>
                            <td>{{ $summary->summary_date->format('d/m/Y') }}</td>
                            <td>{{ $summary->reference_date->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $summary->items_count }}</span>
                            </td>
                            <td class="text-end">
                                S/ {{ number_format($summary->total_amount, 2) }}
                            </td>
                            <td>
                                @if($summary->ticket)
                                    <code class="small">{{ Str::limit($summary->ticket, 15) }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @switch($summary->status)
                                    @case('accepted')
                                        <span class="badge bg-success">Aceptado</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">Rechazado</span>
                                        @break
                                    @case('sent')
                                        <span class="badge bg-info">Enviado</span>
                                        @break
                                    @default
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                @endswitch
                            </td>
                            <td>
                                <a href="{{ route('sales.summaries.show', $summary->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                No hay resúmenes diarios registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $summaries->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
