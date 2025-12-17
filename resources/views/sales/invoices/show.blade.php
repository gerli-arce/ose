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
                            <div><strong>{{ $document->company->name ?? 'Empresa' }}</strong></div>
                            <div>{{ $document->company->address ?? '' }}</div>
                            <div>RUC: {{ $document->company->tax_id ?? '' }}</div>
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
        </div>

        <!-- Sidebar Actions -->
        <div class="col-xl-3">
             <div class="card">
                <div class="card-header pb-0">
                    <h5>Facturación Electrónica</h5>
                </div>
                <div class="card-body">
                     @if($document->sunat_status === 'accepted')
                        <div class="alert alert-success mb-2">SUNAT Aceptado</div>
                     @elseif($document->sunat_status === 'rejected')
                        <div class="alert alert-danger mb-2">SUNAT Rechazado</div>
                     @else
                        <div class="alert alert-secondary mb-2">SUNAT Pendiente</div>
                     @endif

                     @if($document->eDocument)
                        <div class="mb-2 small">
                            <div><strong>Código:</strong> {{ $document->eDocument->response_code ?? '-' }}</div>
                            <div><strong>Mensaje:</strong> {{ $document->eDocument->response_message ?? '-' }}</div>
                            <div><strong>Hash:</strong> {{ $document->eDocument->hash ?? '-' }}</div>
                            @if($document->eDocument->cdr_path)
                                <div><a href="{{ route('files.download', ['path' => $document->eDocument->cdr_path]) }}" class="text-primary">Descargar CDR</a></div>
                            @endif
                            @if($document->eDocument->xml_path)
                                <div><a href="{{ route('files.download', ['path' => $document->eDocument->xml_path]) }}" class="text-primary">Descargar XML</a></div>
                            @endif
                        </div>
                     @endif

                     <form action="{{ route('sales.documents.resend.sunat', $document->id) }}" method="POST" class="d-grid gap-2">
                         @csrf
                         <button type="submit" class="btn btn-primary">Reenviar a SUNAT</button>
                     </form>
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

            {{-- Notas de Crédito --}}
            @if(in_array($document->documentType?->code ?? '', ['01', '03']))
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fa fa-file-invoice me-1"></i> Notas de Crédito</h6>
                </div>
                <div class="card-body">
                    @if($document->creditNotes && $document->creditNotes->count() > 0)
                        <ul class="list-group list-group-flush mb-3">
                            @foreach($document->creditNotes as $nc)
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <a href="{{ route('sales.credit-notes.show', $nc->id) }}">
                                    {{ $nc->full_number }}
                                </a>
                                <span class="text-danger">- S/ {{ number_format($nc->total, 2) }}</span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted small mb-3">No hay notas de crédito emitidas.</p>
                    @endif
                    
                    @if($document->canIssueCreditNote())
                        <a href="{{ route('sales.credit-notes.create', ['document_id' => $document->id]) }}" 
                           class="btn btn-warning w-100">
                            <i class="fa fa-plus me-1"></i> Emitir Nota de Crédito
                        </a>
                    @endif
                </div>
            </div>

            {{-- Notas de Débito --}}
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fa fa-file-text-o me-1"></i> Notas de Débito</h6>
                </div>
                <div class="card-body">
                    @if($document->debitNotes && $document->debitNotes->count() > 0)
                        <ul class="list-group list-group-flush mb-3">
                            @foreach($document->debitNotes as $nd)
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <a href="{{ route('sales.debit-notes.show', $nd->id) }}">
                                    {{ $nd->full_number }}
                                </a>
                                <span class="text-info">+ S/ {{ number_format($nd->total, 2) }}</span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted small mb-3">No hay notas de débito emitidas.</p>
                    @endif
                    
                    @if($document->status === 'emitted')
                        <a href="{{ route('sales.debit-notes.create', ['document_id' => $document->id]) }}" 
                           class="btn btn-info w-100">
                            <i class="fa fa-plus me-1"></i> Emitir Nota de Débito
                        </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Anulación de Documento (solo para facturas emitidas) --}}
            @if(in_array($document->documentType?->code ?? '', ['01', '07', '08']) && $document->status === 'emitted')
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fa fa-ban me-1"></i> Anulación</h6>
                </div>
                <div class="card-body">
                    @if($document->voided_document_id)
                        <div class="alert alert-dark mb-0">
                            <i class="fa fa-check-circle me-1"></i>
                            Documento anulado
                            @if($document->voided_at)
                                <br><small>{{ $document->voided_at->format('d/m/Y H:i') }}</small>
                            @endif
                        </div>
                    @else
                        <p class="text-muted small mb-3">
                            Generar Comunicación de Baja para anular este documento ante SUNAT.
                        </p>
                        <a href="{{ route('sales.voided.create', ['document_id' => $document->id]) }}" 
                           class="btn btn-danger w-100">
                            <i class="fa fa-ban me-1"></i> Anular Documento
                        </a>
                    @endif
                </div>
            </div>
            @endif
            
             <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-download me-1"></i> Descargas</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('pdf.document.a4', $document->id) }}" class="btn btn-outline-primary">
                        <i class="fa fa-file-pdf-o me-1"></i> PDF A4
                    </a>
                    <a href="{{ route('pdf.document.ticket', $document->id) }}" class="btn btn-outline-primary">
                        <i class="fa fa-file-pdf-o me-1"></i> PDF Ticket (80mm)
                    </a>
                    <a href="{{ route('pdf.document.view', $document->id) }}" target="_blank" class="btn btn-outline-secondary">
                        <i class="fa fa-eye me-1"></i> Ver PDF
                    </a>
                    @if($document->eDocument?->xml_path)
                    <a href="{{ route('files.download', ['path' => $document->eDocument->xml_path]) }}" class="btn btn-outline-dark">
                        <i class="fa fa-file-code-o me-1"></i> Descargar XML
                    </a>
                    @endif
                    @if($document->eDocument?->cdr_path)
                    <a href="{{ route('files.download', ['path' => $document->eDocument->cdr_path]) }}" class="btn btn-outline-success">
                        <i class="fa fa-file-archive-o me-1"></i> Descargar CDR
                    </a>
                    @endif
                    <hr>
                    <a href="{{ route('edoc-viewer.show', $document->id) }}" class="btn btn-dark">
                        <i class="fa fa-code me-1"></i> Visor XML/CDR
                    </a>
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
