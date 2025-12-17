@extends('layout.master')

@section('title', 'Notas de Crédito')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Notas de Crédito</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.documents.index') }}">Ventas</a></li>
                    <li class="breadcrumb-item active">Notas de Crédito</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">
                        <i class="fa fa-file-invoice me-2"></i>
                        Listado de Notas de Crédito Electrónicas
                    </h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('sales.documents.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Volver a Ventas
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Filtros -->
            <form method="GET" class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" class="form-control" name="start_date" 
                           value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" class="form-control" name="end_date" 
                           value="{{ request('end_date', now()->format('Y-m-d')) }}">
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
                            <th>Número</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Doc. Afectado</th>
                            <th>Tipo NC</th>
                            <th class="text-end">Total</th>
                            <th>Estado SUNAT</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                        <tr>
                            <td>
                                <a href="{{ route('sales.credit-notes.show', $doc->id) }}" class="fw-bold">
                                    {{ $doc->full_number }}
                                </a>
                            </td>
                            <td>{{ $doc->issue_date->format('d/m/Y') }}</td>
                            <td>
                                {{ $doc->customer->name ?? '-' }}
                                <br><small class="text-muted">{{ $doc->customer->tax_id ?? '' }}</small>
                            </td>
                            <td>
                                @if($doc->relatedDocument)
                                    <a href="{{ route('sales.documents.show', $doc->relatedDocument->id) }}">
                                        {{ $doc->relatedDocument->full_number }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $doc->creditNoteType?->code ?? '' }} - {{ Str::limit($doc->creditNoteType?->name ?? '', 20) }}
                                </span>
                            </td>
                            <td class="text-end text-danger fw-bold">
                                - S/ {{ number_format($doc->total, 2) }}
                            </td>
                            <td>
                                @switch($doc->sunat_status)
                                    @case('accepted')
                                        <span class="badge bg-success">Aceptado</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">Rechazado</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">-</span>
                                @endswitch
                            </td>
                            <td>
                                <a href="{{ route('sales.credit-notes.show', $doc->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                No hay notas de crédito en el período seleccionado.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
