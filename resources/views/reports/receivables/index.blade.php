@extends('layout.master')

@section('title', 'Cuentas por Cobrar')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6"><h3>Cuentas por Cobrar (Saldos Clientes)</h3></div>
        </div>
    </div>
</div>

<div class="container-fluid">
     <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Buscar por Cliente</label>
                    <input type="text" name="customer" class="form-control" placeholder="Nombre o Razón Social..." value="{{ $customerName }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Buscar</button>
                    <button type="submit" name="export" value="true" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Exportar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($receivables as $row)
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header bg-light d-flex justify-content-between">
                    <span>
                        <h6 class="mb-0">{{ $row['customer_name'] }} <small class="text-muted">({{ $row['customer_doc'] }})</small></h6>
                    </span>
                    <span class="badge bg-danger">Saldo: S/ {{ number_format($row['total_balance'], 2) }}</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Emisión</th>
                                <th>Doc.</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Acuenta</th>
                                <th class="text-end">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($row['invoices'] as $inv)
                            <tr>
                                <td>{{ $inv->issue_date->format('d/m/Y') }}</td>
                                <td>{{ $inv->series->prefix }}-{{ $inv->number }}</td>
                                <td class="text-end">{{ number_format($inv->total, 2) }}</td>
                                <td class="text-end text-success">{{ number_format($inv->total - $inv->balance, 2) }}</td>
                                <td class="text-end text-danger fw-bold">{{ number_format($inv->balance, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer py-2 text-end">
                     <small>Total Facturado: {{ number_format($row['total_billed'], 2) }} | Total Cobrado: {{ number_format($row['total_paid'], 2) }}</small>
                </div>
            </div>
        </div>
        @empty
         <div class="col-12">
             <div class="alert alert-success">No hay cuentas por cobrar pendientes con los filtros seleccionados.</div>
         </div>
        @endforelse
    </div>
</div>
@endsection
