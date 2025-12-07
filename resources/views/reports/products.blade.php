@extends('layout.master')

@section('title', 'Reporte de Productos')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Reporte de Productos Vendidos</h3>
            </div>
             <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Reportes</li>
                    <li class="breadcrumb-item active"> Productos</li>
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
                            <th>Producto</th>
                            <th>Código</th>
                            <th class="text-end">Cantidad Vendida</th>
                            <th class="text-end">Total Recaudado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->product->code }}</td>
                                <td class="text-end">{{ $item->total_qty }}</td>
                                <td class="text-end">{{ number_format($item->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">No hay datos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="mt-3">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
