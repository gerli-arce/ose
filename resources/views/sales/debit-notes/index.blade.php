@extends('layout.master')

@section('title', 'Notas de Débito')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Notas de Débito Electrónicas</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.documents.index') }}">Ventas</a></li>
                    <li class="breadcrumb-item active">Notas de Débito</li>
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
                        <i class="fa fa-file-text-o me-2"></i>
                        Notas de Débito Emitidas
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
                    <label class="form-label">Estado SUNAT</label>
                    <select class="form-select" name="status">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
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
                            <th>Número</th>
                            <th>Fecha</th>
                            <th>Documento Afectado</th>
                            <th>Cliente</th>
                            <th>Tipo ND</th>
                            <th class="text-end">Total</th>
                            <th>Estado SUNAT</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($debitNotes as $nd)
                        <tr>
                            <td>
                                <a href="{{ route('sales.debit-notes.show', $nd->id) }}" class="fw-bold text-danger">
                                    {{ $nd->series->prefix ?? '' }}-{{ str_pad($nd->number, 8, '0', STR_PAD_LEFT) }}
                                </a>
                            </td>
                            <td>{{ $nd->issue_date->format('d/m/Y') }}</td>
                            <td>
                                @if($nd->relatedDocument)
                                    <a href="{{ route('sales.documents.show', $nd->relatedDocument->id) }}" class="text-muted">
                                        {{ $nd->relatedDocument->series->prefix ?? '' }}-{{ str_pad($nd->relatedDocument->number, 8, '0', STR_PAD_LEFT) }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($nd->customer->name ?? '-', 25) }}</td>
                            <td>
                                <span class="badge bg-info">{{ $nd->debitNoteType->name ?? '-' }}</span>
                            </td>
                            <td class="text-end text-danger fw-bold">
                                + S/ {{ number_format($nd->total, 2) }}
                            </td>
                            <td>
                                @switch($nd->sunat_status)
                                    @case('accepted')
                                        <span class="badge bg-success">Aceptado</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">Rechazado</span>
                                        @break
                                    @default
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                @endswitch
                            </td>
                            <td>
                                <a href="{{ route('sales.debit-notes.show', $nd->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                No hay notas de débito registradas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $debitNotes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
