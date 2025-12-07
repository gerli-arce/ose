@extends('layout.master')

@section('title', 'Reporte de Ventas')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Reporte de Ventas</h3>
            </div>
             <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Reportes</li>
                    <li class="breadcrumb-item active"> Ventas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
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
                        <option value="">TODOS</option>
                        <option value="issued" {{ $status == 'issued' ? 'selected' : '' }}>Emitidos</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Anulados</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 me-2"><i data-feather="search"></i> Buscar</button>
                    <button type="submit" name="export" value="true" class="btn btn-success w-100"><i data-feather="download"></i> Excel</button>
                </div>
            </form>

            <div class="row mb-4">
                 <div class="col-md-4">
                    <div class="card bg-primary text-white text-center p-2">
                        <h6>Total Ventas</h6>
                        <h4>{{ number_format($totals['total'], 2) }}</h4>
                    </div>
                </div>
                 <div class="col-md-4">
                    <div class="card bg-secondary text-white text-center p-2">
                         <h6>Total IGV</h6>
                        <h4>{{ number_format($totals['tax'], 2) }}</h4>
                    </div>
                </div>
                 <div class="col-md-4">
                    <div class="card bg-light text-dark text-center p-2">
                         <h6>Base Imponible</h6>
                        <h4>{{ number_format($totals['subtotal'], 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Documento</th>
                            <th>Cliente</th>
                            <th>RUC/DNI</th>
                            <th>Estado</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>{{ $sale->issue_date->format('d/m/Y') }}</td>
                                <td>{{ $sale->documentType->name }} {{ $sale->series->prefix }}-{{ $sale->number }}</td>
                                <td>{{ $sale->customer->name ?? $sale->customer->business_name }}</td>
                                <td>{{ $sale->customer->tax_id }}</td>
                                <td>
                                    @if($sale->status == 'cancelled')
                                        <span class="badge badge-danger">Anulado</span>
                                    @else
                                        <span class="badge badge-success">Emitido</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($sale->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No se encontraron ventas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $sales->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
