@extends('layout.master')

@section('title', 'Nota de Débito ' . ($debitNote->series->prefix ?? '') . '-' . str_pad($debitNote->number, 8, '0', STR_PAD_LEFT))

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Nota de Débito {{ $debitNote->series->prefix ?? '' }}-{{ str_pad($debitNote->number, 8, '0', STR_PAD_LEFT) }}</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.debit-notes.index') }}">Notas de Débito</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Documento Principal -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-file-text me-2"></i>
                        Nota de Débito Electrónica
                    </h5>
                    <span class="badge bg-light text-dark fs-6">08</span>
                </div>
                <div class="card-body">
                    <!-- Información General -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Emisor</h6>
                            <div><strong>{{ $debitNote->company->name ?? 'N/A' }}</strong></div>
                            <div>RUC: {{ $debitNote->company->tax_id ?? '' }}</div>
                        </div>
                        <div class="col-md-6 text-end">
                            <h6 class="text-muted mb-2">Cliente</h6>
                            <div><strong>{{ $debitNote->customer->name ?? 'Cliente Varios' }}</strong></div>
                            <div>{{ $debitNote->customer->tax_id ?? '' }}</div>
                        </div>
                    </div>

                    <hr>

                    <!-- Documento de Referencia -->
                    @if($debitNote->relatedDocument)
                    <div class="alert alert-secondary">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Documento Afectado:</strong><br>
                                <a href="{{ route('sales.documents.show', $debitNote->relatedDocument->id) }}" class="h5">
                                    {{ $debitNote->relatedDocument->series->prefix ?? '' }}-{{ str_pad($debitNote->relatedDocument->number, 8, '0', STR_PAD_LEFT) }}
                                </a>
                            </div>
                            <div class="col-md-4">
                                <strong>Tipo de Nota:</strong><br>
                                <span class="badge bg-info">{{ $debitNote->debitNoteType->code ?? '' }} - {{ $debitNote->debitNoteType->name ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Fecha Emisión:</strong><br>
                                {{ $debitNote->issue_date->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Motivo -->
                    <div class="mb-4">
                        <strong>Motivo del Cargo:</strong>
                        <p class="mb-0">{{ $debitNote->note_reason }}</p>
                    </div>

                    <!-- Items -->
                    <h6 class="mb-3"><i class="fa fa-list me-1"></i> Detalle del Cargo</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Descripción</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">P. Unitario</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($debitNote->items as $item)
                                <tr>
                                    <td>{{ $item->description }}</td>
                                    <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Subtotal:</th>
                                    <th class="text-end">S/ {{ number_format($debitNote->subtotal, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">IGV (18%):</th>
                                    <th class="text-end">S/ {{ number_format($debitNote->tax_total, 2) }}</th>
                                </tr>
                                <tr class="table-danger">
                                    <th colspan="3" class="text-end">Total Cargo:</th>
                                    <th class="text-end h5">S/ {{ number_format($debitNote->total, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-md-4">
            <!-- Estado SUNAT -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-cloud me-1"></i> Estado SUNAT</h6>
                </div>
                <div class="card-body">
                    @switch($debitNote->sunat_status)
                        @case('accepted')
                            <div class="alert alert-success mb-3">
                                <i class="fa fa-check-circle me-1"></i> Aceptado por SUNAT
                            </div>
                            @break
                        @case('rejected')
                            <div class="alert alert-danger mb-3">
                                <i class="fa fa-times-circle me-1"></i> Rechazado por SUNAT
                            </div>
                            @break
                        @default
                            <div class="alert alert-warning mb-3">
                                <i class="fa fa-clock-o me-1"></i> Pendiente de envío
                            </div>
                    @endswitch

                    @if($debitNote->eDocument)
                        <table class="table table-sm table-borderless">
                            @if($debitNote->eDocument->response_code)
                            <tr>
                                <td><strong>Código:</strong></td>
                                <td>{{ $debitNote->eDocument->response_code }}</td>
                            </tr>
                            @endif
                            @if($debitNote->eDocument->response_message)
                            <tr>
                                <td colspan="2">
                                    <strong>Mensaje:</strong><br>
                                    <small>{{ $debitNote->eDocument->response_message }}</small>
                                </td>
                            </tr>
                            @endif
                            @if($debitNote->eDocument->hash)
                            <tr>
                                <td><strong>Hash:</strong></td>
                                <td><code class="small">{{ Str::limit($debitNote->eDocument->hash, 20) }}</code></td>
                            </tr>
                            @endif
                        </table>
                    @endif

                    <form action="{{ route('sales.debit-notes.resend', $debitNote->id) }}" method="POST" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-refresh me-1"></i> Reenviar a SUNAT
                        </button>
                    </form>
                </div>
            </div>

            <!-- Descargas -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-download me-1"></i> Descargas</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    @if($debitNote->eDocument?->xml_path)
                        <a href="{{ route('files.download', ['path' => $debitNote->eDocument->xml_path]) }}" 
                           class="btn btn-outline-primary">
                            <i class="fa fa-file-code-o me-1"></i> Descargar XML
                        </a>
                    @endif
                    @if($debitNote->eDocument?->cdr_path)
                        <a href="{{ route('files.download', ['path' => $debitNote->eDocument->cdr_path]) }}" 
                           class="btn btn-outline-success">
                            <i class="fa fa-file-archive-o me-1"></i> Descargar CDR
                        </a>
                    @endif
                    <a href="{{ route('sales.debit-notes.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
