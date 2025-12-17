@extends('layout.master')

@section('title', 'Comunicaciones de Baja')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Comunicaciones de Baja</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.documents.index') }}">Ventas</a></li>
                    <li class="breadcrumb-item active">Com. de Baja</li>
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
                        <i class="fa fa-ban me-2"></i>
                        Historial de Comunicaciones de Baja
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
                            <th>Fecha</th>
                            <th>Documentos</th>
                            <th>Ticket</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($voidedDocuments as $voided)
                        <tr>
                            <td>
                                <a href="{{ route('sales.voided.show', $voided->id) }}" class="fw-bold">
                                    {{ $voided->identifier }}
                                </a>
                            </td>
                            <td>{{ $voided->voided_date->format('d/m/Y') }}</td>
                            <td>
                                @foreach($voided->items as $item)
                                    <span class="badge bg-secondary">
                                        {{ $item->full_number }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @if($voided->ticket)
                                    <code>{{ $voided->ticket }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @switch($voided->status)
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
                                <a href="{{ route('sales.voided.show', $voided->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                No hay comunicaciones de baja registradas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                {{ $voidedDocuments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
