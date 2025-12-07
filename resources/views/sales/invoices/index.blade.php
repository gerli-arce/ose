@extends('layout.master')

@section('title', 'Comprobantes de Pago')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Ventas y Facturación</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Comprobantes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between">
                    <h5>Listado de Comprobantes</h5>
                    <a href="{{ route('sales.invoices.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nueva Venta</a>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-2">
                             <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                             <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="document_type_id" class="form-select">
                                <option value="">Todos Tipos</option>
                                @foreach($documentTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                         <!-- Add more filters if needed -->
                        <div class="col-md-2">
                             <button type="submit" class="btn btn-primary w-100"><i class="fa fa-search"></i> Filtrar</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Emisión</th>
                                    <th>Documento</th>
                                    <th>Cliente</th>
                                    <th>Moneda</th>
                                    <th class="text-end">Total</th>
                                    <th>Pago</th>
                                    <th>SUNAT</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $doc)
                                <tr>
                                    <td>{{ $doc->issue_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $doc->documentType->short_name ?? $doc->documentType->name }}</span>
                                        <br>
                                        {{ $doc->series->prefix ?? '???' }}-{{ str_pad($doc->number, 8, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td>{{ $doc->customer->name ?? 'Varios' }}</td>
                                    <td>{{ $doc->currency->code ?? 'PEN' }}</td>
                                    <td class="text-end font-weight-bold">{{ number_format($doc->total, 2) }}</td>
                                    <td>
                                        @if($doc->payment_status == 'paid') <span class="badge bg-success">Pagado</span>
                                        @elseif($doc->payment_status == 'partial') <span class="badge bg-warning">Parcial</span>
                                        @else <span class="badge bg-danger">Pendiente</span>
                                        @endif
                                    </td>
                                    <td>
                                         @if($doc->eDocument)
                                            @if($doc->eDocument->response_status == 'accepted') <span class="badge bg-success">Aceptado</span>
                                            @elseif($doc->eDocument->response_status == 'rejected') <span class="badge bg-danger">Rechazado</span>
                                            @else <span class="badge bg-secondary">Pendiente</span>
                                            @endif
                                         @else
                                            <span class="text-muted">-</span>
                                         @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('sales.documents.show', $doc->id) }}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">No se encontraron documentos.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="d-flex justify-content-center mt-3">
                        {{ $documents->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
