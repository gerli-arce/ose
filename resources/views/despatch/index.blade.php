@extends('layout.master')

@section('title', 'Guías de Remisión')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Guías de Remisión Electrónicas</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Guías de Remisión</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Guías</h5>
            <a href="{{ route('despatch.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Nueva Guía
            </a>
        </div>
        
        <div class="card-body">
            <!-- Filtros -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Estado SUNAT</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Aceptado</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fa fa-search"></i> Filtrar
                    </button>
                    <a href="{{ route('despatch.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-refresh"></i>
                    </a>
                </div>
            </form>

            <!-- Tabla -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Número</th>
                            <th>Fecha Emisión</th>
                            <th>Fecha Traslado</th>
                            <th>Motivo</th>
                            <th>Modalidad</th>
                            <th>Peso (kg)</th>
                            <th>Bultos</th>
                            <th>Estado SUNAT</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($despatches as $despatch)
                        <tr>
                            <td>
                                <a href="{{ route('despatch.show', $despatch->id) }}" class="text-decoration-none">
                                    <strong>{{ $despatch->full_number }}</strong>
                                </a>
                            </td>
                            <td>{{ $despatch->issue_date->format('d/m/Y') }}</td>
                            <td>{{ $despatch->transfer_date->format('d/m/Y') }}</td>
                            <td>
                                <small>{{ $despatch->transferReason?->name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $despatch->isPublicTransport() ? 'info' : 'secondary' }}">
                                    {{ $despatch->transportModality?->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ number_format($despatch->gross_weight, 2) }}</td>
                            <td>{{ $despatch->package_count }}</td>
                            <td>
                                <span class="badge bg-{{ $despatch->sunat_status_color }}">
                                    {{ $despatch->sunat_status_name }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('despatch.show', $despatch->id) }}" 
                                       class="btn btn-outline-primary" title="Ver">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @if(!$despatch->isAccepted())
                                    <form action="{{ route('despatch.resend', $despatch->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning" title="Reenviar a SUNAT">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fa fa-inbox fa-3x mb-2"></i>
                                <p>No hay guías de remisión registradas.</p>
                                <a href="{{ route('despatch.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus me-1"></i> Crear primera guía
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $despatches->links() }}
        </div>
    </div>
</div>
@endsection
