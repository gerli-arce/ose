@extends('layout.master')

@section('title', 'Detalle de Compra')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Compra {{ $document->series }}-{{ $document->number }}</h3>
            </div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchases.documents.index') }}">Compras</a></li>
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
                             <h6 class="mb-3">Proveedor:</h6>
                            <div><strong>{{ $document->supplier->name ?? 'Desconocido' }}</strong></div>
                            <div>{{ $document->supplier->address ?? '' }}</div>
                            <div>{{ $document->supplier->tax_id ?? '' }}</div>
                        </div>
                        <div class="col-sm-6 text-end">
                            <h6 class="mb-3">Detalles:</h6>
                            <div>Fecha: {{ $document->issue_date->format('d/m/Y') }}</div>
                            <div>Vencimiento: {{ $document->due_date ? $document->due_date->format('d/m/Y') : '-' }}</div>
                            <div>Moneda: {{ $document->currency->code ?? '' }}</div>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Cant.</th>
                                    <th>Descripción</th>
                                    <th class="text-end">Costo Unit.</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($document->items as $item)
                                <tr>
                                    <td>{{ number_format($item->quantity, 2) }}</td>
                                    <td>{{ $item->product->name ?? $item->description }}</td>
                                    <td class="text-end">{{ number_format($item->unit_cost, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->line_total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                             <p class="lead">Estado: 
                                 <span class="badge {{ $document->status == 'paid' ? 'bg-success' : ($document->status == 'partial' ? 'bg-warning' : 'bg-primary') }}">
                                     {{ ucfirst($document->status) }}
                                 </span>
                             </p>
                             <div class="mt-3">
                                 <strong>Observaciones:</strong>
                                 <p>{{ $document->observations }}</p>
                             </div>
                        </div>
                        <div class="col-sm-4">
                             <table class="table table-sm text-end">
                                 <tr>
                                     <td>Subtotal:</td>
                                     <td>{{ number_format($document->subtotal, 2) }}</td>
                                 </tr>
                                 <tr>
                                     <td>IGV:</td>
                                     <td>{{ number_format($document->tax_total, 2) }}</td>
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
             <!-- Payments History -->
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Historial de Pagos</h5>
                </div>
                <div class="card-body">
                    @if($document->payments->count() > 0)
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Método</th>
                                <th>Referencia</th>
                                <th class="text-end">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($document->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                <td>{{ $payment->paymentMethod->name ?? '' }}</td>
                                <td>{{ $payment->reference }}</td>
                                <td class="text-end">{{ number_format($payment->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-muted">No hay pagos registrados.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-xl-3">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Pagos</h5>
                </div>
                <div class="card-body">
                     <p>Pagado: S/ {{ number_format($document->payments->sum('amount'), 2) }}</p>
                     <p>Saldo: S/ {{ number_format($document->total - $document->payments->sum('amount'), 2) }}</p>

                     @if($document->status != 'paid')
                     <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#paymentModal">Registrar Pago</button>
                     @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('purchases.payments.store') }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Pago a Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="purchase_document_id" value="{{ $document->id }}">
                <div class="mb-3">
                    @php $balance = round($document->total - $document->payments->sum('amount'), 2); @endphp
                    <label class="form-label">Monto a Pagar</label>
                    <input type="number" step="0.01" class="form-control" name="amount" max="{{ $balance }}" value="{{ $balance }}" required>
                    <small class="text-muted">Saldo pendiente: S/ {{ number_format($balance, 2) }}</small>
                </div>
                 <div class="mb-3">
                    <label class="form-label">Fecha Pago</label>
                    <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Método de Pago</label>
                    <select class="form-select" name="payment_method_id">
                         @foreach(\App\Models\PaymentMethod::all() as $pm)
                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Referencia / Nro Operación</label>
                    <input type="text" class="form-control" name="reference">
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
