@extends('layout.master')

@section('title', 'Nota de Crédito ' . $creditNote->full_number)

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>{{ $creditNote->full_number }}</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.credit-notes.index') }}">Notas de Crédito</a></li>
                    <li class="breadcrumb-item active">{{ $creditNote->full_number }}</li>
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
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-file-invoice me-2"></i>
                        Nota de Crédito Electrónica
                    </h5>
                    <div>
                        @switch($creditNote->sunat_status)
                            @case('accepted')
                                <span class="badge bg-success fs-6">SUNAT: Aceptado</span>
                                @break
                            @case('rejected')
                                <span class="badge bg-danger fs-6">SUNAT: Rechazado</span>
                                @break
                            @case('pending')
                                <span class="badge bg-light text-dark fs-6">SUNAT: Pendiente</span>
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="card-body">
                    <!-- Cabecera -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">EMISOR</h6>
                            <p class="mb-0">
                                <strong>{{ $creditNote->company->name ?? 'Empresa' }}</strong><br>
                                RUC: {{ $creditNote->company->tax_id ?? '-' }}<br>
                                {{ $creditNote->company->address ?? '' }}
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <h3 class="text-warning mb-0">{{ $creditNote->full_number }}</h3>
                            <p class="text-muted mb-0">
                                Fecha: {{ $creditNote->issue_date->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- Cliente -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">CLIENTE</h6>
                            <p class="mb-0">
                                <strong>{{ $creditNote->customer->business_name ?? $creditNote->customer->name }}</strong><br>
                                {{ $creditNote->customer->identityDocumentType?->abbreviation ?? 'DOC' }}: {{ $creditNote->customer->tax_id }}<br>
                                {{ $creditNote->customer->address ?? '' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">DOCUMENTO AFECTADO</h6>
                            <p class="mb-0">
                                @if($creditNote->relatedDocument)
                                    <a href="{{ route('sales.documents.show', $creditNote->relatedDocument->id) }}" class="fw-bold">
                                        {{ $creditNote->relatedDocument->documentType->name ?? '' }}:
                                        {{ $creditNote->relatedDocument->full_number }}
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        Fecha: {{ $creditNote->relatedDocument->issue_date->format('d/m/Y') }}
                                    </small>
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Motivo -->
                    <div class="alert alert-warning">
                        <h6 class="alert-heading mb-1">
                            <i class="fa fa-info-circle me-1"></i>
                            Motivo: {{ $creditNote->creditNoteType?->code }} - {{ $creditNote->creditNoteType?->name }}
                        </h6>
                        <p class="mb-0">{{ $creditNote->note_reason }}</p>
                    </div>

                    <!-- Items -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">P. Unit</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($creditNote->items as $item)
                                <tr>
                                    <td><small>{{ $item->code }}</small></td>
                                    <td>{{ $item->description }}</td>
                                    <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totales -->
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <table class="table table-sm">
                                <tr>
                                    <td>Op. Gravada:</td>
                                    <td class="text-end">S/ {{ number_format($creditNote->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>IGV (18%):</td>
                                    <td class="text-end">S/ {{ number_format($creditNote->tax_total, 2) }}</td>
                                </tr>
                                <tr class="table-warning">
                                    <td><strong>TOTAL NC:</strong></td>
                                    <td class="text-end">
                                        <strong class="h4 text-danger">S/ {{ number_format($creditNote->total, 2) }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-md-4">
            <!-- Acciones -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-cogs me-1"></i> Acciones</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    @if($creditNote->sunat_status === 'pending' || $creditNote->sunat_status === 'rejected')
                        <form action="{{ route('sales.credit-notes.resend', $creditNote->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fa fa-paper-plane me-1"></i> Reenviar a SUNAT
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('sales.credit-notes.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Volver al listado
                    </a>
                    
                    <a href="{{ route('sales.documents.show', $creditNote->relatedDocument->id ?? 0) }}" 
                       class="btn btn-outline-info">
                        <i class="fa fa-file me-1"></i> Ver documento original
                    </a>
                </div>
            </div>

            <!-- Estado SUNAT -->
            @if($creditNote->eDocument)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-cloud me-1"></i> Estado SUNAT</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td>
                                @switch($creditNote->eDocument->response_status)
                                    @case('accepted')
                                        <span class="badge bg-success">Aceptado</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">Rechazado</span>
                                        @break
                                    @default
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                @endswitch
                            </td>
                        </tr>
                        @if($creditNote->eDocument->response_code)
                        <tr>
                            <td><strong>Código:</strong></td>
                            <td>{{ $creditNote->eDocument->response_code }}</td>
                        </tr>
                        @endif
                        @if($creditNote->eDocument->response_message)
                        <tr>
                            <td colspan="2">
                                <strong>Mensaje:</strong><br>
                                <small class="text-muted">{{ $creditNote->eDocument->response_message }}</small>
                            </td>
                        </tr>
                        @endif
                        @if($creditNote->eDocument->sent_at)
                        <tr>
                            <td><strong>Enviado:</strong></td>
                            <td>{{ $creditNote->eDocument->sent_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
