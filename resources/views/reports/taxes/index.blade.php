@extends('layout.master')

@section('title', 'Reporte de Impuestos')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6"><h3>Reporte de Impuestos (IGV)</h3></div>
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
                    <button type="submit" name="export" value="true" class="btn btn-success"><i class="fa fa-file-excel-o"></i> CSV</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted">Ventas Gravadas</h6>
                    <h3>{{ number_format($totals['total_taxable'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted">Ventas Exoneradas</h6>
                    <h3>{{ number_format($totals['total_exempt'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center text-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total IGV</h6>
                    <h3>{{ number_format($totals['total_igv'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white">Total General</h6>
                    <h3 class="text-white">{{ number_format($totals['total'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
             <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Serie-Num</th>
                            <th>Cliente</th>
                            <th>RUC</th>
                            <th class="text-end">Gravado</th>
                            <th class="text-end">Exonerado</th>
                            <th class="text-end">IGV</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                         @forelse($sales as $sale)
                         @php
                            $gravado = $sale->tax_total > 0 ? $sale->subtotal : 0;
                            $exonerado = $sale->tax_total == 0 ? $sale->subtotal : 0;
                         @endphp
                        <tr>
                            <td>{{ $sale->issue_date->format('d/m/Y') }}</td>
                            <td>{{ $sale->documentType->name }}</td>
                            <td>{{ $sale->series->prefix }}-{{ $sale->number }}</td>
                            <td>{{ Str::limit($sale->customer->name ?? $sale->customer->business_name, 30) }}</td>
                            <td>{{ $sale->customer->tax_id }}</td>
                            <td class="text-end">{{ number_format($gravado, 2) }}</td>
                            <td class="text-end">{{ number_format($exonerado, 2) }}</td>
                            <td class="text-end">{{ number_format($sale->tax_total, 2) }}</td>
                            <td class="text-end fw-bold">{{ number_format($sale->total, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center">Sin datos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
             </div>
             <div class="mt-2">{{ $sales->links() }}</div>
        </div>
    </div>
</div>
@endsection
