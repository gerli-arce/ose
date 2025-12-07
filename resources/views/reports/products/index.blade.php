@extends('layout.master')

@section('title', 'Reporte de Productos')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6"><h3>Reporte de Productos Vendidos</h3></div>
        </div>
    </div>
</div>

<div class="container-fluid">
     <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Desde</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Buscar</button>
                    <button type="submit" name="export" value="true" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Exportar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
             <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Código</th>
                            <th class="text-end">Cant. Vendida</th>
                            <th class="text-end">Total Vendido (S/)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->product->code ?? '-' }}</td>
                            <td class="text-end">{{ $item->total_qty }}</td>
                            <td class="text-end fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">Sin datos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
             </div>
             <div class="mt-3">{{ $products->withQueryString()->links() }}</div>
        </div>
    </div>
</div>
@endsection
