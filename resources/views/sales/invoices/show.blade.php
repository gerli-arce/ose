@extends('layout.master')

@section('title', 'Detalle de Comprobante')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Venta {{ $document->series->prefix ?? '???' }}-{{ str_pad($document->number, 8, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.documents.index') }}">Ventas</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Document -->
        <div class="col-xl-9">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6 class="mb-3">De:</h6>
                            <div><strong>{{ $document->company->firm_name }}</strong></div>
                            <div>{{ $document->company->address }}</div>
                            <div>RUC: {{ $document->company->ruc }}</div>
                        </div>
                        <div class="col-sm-6 text-end">
                            <h6 class="mb-3">Para:</h6>
                            <div><strong>{{ $document->customer->name ?? 'Cliente Genérico' }}</strong></div>
                            <div>{{ $document->customer->address ?? '' }}</div>
                            <div>{{ $document->customer->tax_id ?? '' }}</div>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Cant.</th>
                                    <th>Descripción</th>
                                    <th class="text-end">P. Unit</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($document->items as $item)
                                <tr>
                                    <td>{{ number_format($item->quantity, 2) }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                             <p class="lead">Estado: 
                                 <span class="badge {{ $document->payment_status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                     {{ ucfirst($document->payment_status) }}
                                 </span>
                             </p>
                             <p class="text-muted small">
                                 SON: {{ $document->total }} SOLES
                             </p>
                        </div>
                        <div class="col-sm-4">
                             <table class="table table-sm text-end">
                                 <tr>
                                     <td>Subtotal:</td>
                                     <td>{{ number_format($document->subtotal, 2) }}</td>
                                 </tr>
                                 <tr>
                                     <td>IGV:</td>
                                     <td>{{ number_format($document->total_igv, 2) }}</td>
                                 </tr>
                                 <tr>
                                     <td><strong>Total:</strong></td>
                                     <td><strong>{{ number_format($document->total, 2) }}</strong></td>
                                 </tr>
                             </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-xl-3">
             <div class="card">
                <div class="card-header pb-0">
                    <h5>Facturación Electrónica</h5>
                </div>
                <div class="card-body">
                     @if($document->eDocument)
                        <div class="alert {{ $document->eDocument->response_status == 'accepted' ? 'alert-success' : 'alert-warning' }}">
                            {{ strtoupper($document->eDocument->response_status) }}
                        </div>
                        @if($document->eDocument->response_status != 'accepted')
                        <a href="{{ route('edocs.send', $document->eDocument->id) }}" class="btn btn-primary w-100 mb-2">Enviar a SUNAT</a>
                        @endif
                     @else
                        <div class="alert alert-secondary">No generado</div>
                     @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <h5>Pagos</h5>
                </div>
                <div class="card-body">
                     <p>Pagado: S/ {{ number_format($document->payments->sum('amount'), 2) }}</p>
                     <p>Saldo: S/ {{ number_format($document->total - $document->payments->sum('amount'), 2) }}</p>

                     @if($document->payment_status != 'paid')
                     <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#paymentModal">Registrar Pago</button>
                     @endif
                </div>
            </div>
            
             <div class="card">
                <div class="card-body">
                    <button class="btn btn-outline-primary w-100 mb-2"><i class="fa fa-file-pdf-o"></i> Descargar PDF</button>
                    <button class="btn btn-outline-secondary w-100"><i class="fa fa-file-code-o"></i> Descargar XML</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('sales.payments.store') }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="sales_document_id" value="{{ $document->id }}">
                <div class="mb-3">
                    <label class="form-label">Monto</label>
                    <input type="number" step="0.01" class="form-control" name="amount" max="{{ $document->total - $document->payments->sum('amount') }}" value="{{ $document->total - $document->payments->sum('amount') }}" required>
                </div>
                 <div class="mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Método</label>
                    <select class="form-select" name="payment_method_id">
                        <option value="1">Efectivo</option>
                        <option value="2">Transferencia</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar Pago</button>
            </div>
        </div>
        </form>
    </div>
</div>

@endsection
