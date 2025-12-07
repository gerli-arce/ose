@extends('layout.master')

@section('title', 'Reporte de Ventas')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6"><h3>Reporte de Ventas</h3></div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Reportes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha Fin</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="registered" {{ $status == 'registered' ? 'selected' : '' }}>Registrado</option>
                        <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>Pagado</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Anulado</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filtrar</button>
                    <button type="submit" name="export" value="true" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Exportar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Totals -->
    <div class="row">
        <div class="col-md-4">
             <div class="card bg-primary text-white">
                <div class="card-body p-3 text-center">
                    <h5 class="text-white">Total Ventas</h5>
                    <h3 class="text-white">{{ number_format($totals['total'], 2) }}</h3>
                </div>
            </div>
        </div>
         <div class="col-md-4">
             <div class="card bg-info text-white">
                <div class="card-body p-3 text-center">
                    <h5 class="text-white">Total IGV</h5>
                    <h3 class="text-white">{{ number_format($totals['tax'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Documento</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th class="text-end">Base (Op. Grav)</th>
                            <th class="text-end">IGV</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->issue_date->format('d/m/Y') }}</td>
                            <td>{{ $sale->series->prefix }}-{{ $sale->number }}</td>
                            <td>{{ $sale->customer->name ?? $sale->customer->business_name }}</td>
                            <td><span class="badge {{ $sale->status == 'cancelled' ? 'alert-danger' : 'alert-success' }}">{{ ucfirst($sale->status) }}</span></td>
                            <td class="text-end">{{ number_format($sale->subtotal, 2) }}</td>
                            <td class="text-end">{{ number_format($sale->tax_total, 2) }}</td>
                            <td class="text-end fw-bold">{{ number_format($sale->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">No se encontraron resultados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $sales->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
