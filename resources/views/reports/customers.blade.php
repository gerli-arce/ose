@extends('layout.master')

@section('title', 'Reporte de Clientes')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Reporte de Clientes (Top)</h3>
            </div>
             <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Reportes</li>
                    <li class="breadcrumb-item active"> Clientes</li>
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
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i data-feather="search"></i> Buscar</button>
                    <button type="submit" name="export" value="true" class="btn btn-success"><i data-feather="download"></i> CSV</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>RUC/DNI</th>
                            <th class="text-center">Cant. Facturas</th>
                            <th class="text-end">Total Comprado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $item)
                            <tr>
                                <td>{{ $item->customer->business_name ?? $item->customer->name }}</td>
                                <td>{{ $item->customer->tax_id }}</td>
                                <td class="text-center">{{ $item->total_invoices }}</td>
                                <td class="text-end">{{ number_format($item->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">No hay datos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="mt-3">
                {{ $customers->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
