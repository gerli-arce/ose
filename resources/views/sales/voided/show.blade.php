@extends('layout.master')

@section('title', 'Com. de Baja ' . $voided->identifier)

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>{{ $voided->identifier }}</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.voided.index') }}">Com. de Baja</a></li>
                    <li class="breadcrumb-item active">{{ $voided->identifier }}</li>
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
                        <i class="fa fa-ban me-2"></i>
                        Comunicación de Baja
                    </h5>
                    <div>
                        @switch($voided->status)
                            @case('accepted')
                                <span class="badge bg-success fs-6">ACEPTADO</span>
                                @break
                            @case('rejected')
                                <span class="badge bg-light text-danger fs-6">RECHAZADO</span>
                                @break
                            @case('sent')
                                <span class="badge bg-info fs-6">ENVIADO</span>
                                @break
                            @default
                                <span class="badge bg-warning text-dark fs-6">PENDIENTE</span>
                        @endswitch
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información General -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <strong>Identificador:</strong><br>
                            <code class="h5">{{ $voided->identifier }}</code>
                        </div>
                        <div class="col-md-4">
                            <strong>Fecha Comunicación:</strong><br>
                            {{ $voided->voided_date->format('d/m/Y') }}
                        </div>
                        <div class="col-md-4">
                            <strong>Fecha Documento:</strong><br>
                            {{ $voided->reference_date->format('d/m/Y') }}
                        </div>
                    </div>

                    <hr>

                    <!-- Documentos Anulados -->
                    <h6 class="mb-3">
                        <i class="fa fa-file-text me-1"></i> Documentos Incluidos
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Número</th>
                                    <th>Cliente</th>
                                    <th>Motivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($voided->items as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-dark">{{ $item->document_type_code }}</span>
                                        {{ $item->salesDocument?->documentType?->name ?? '' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('sales.documents.show', $item->sales_document_id) }}">
                                            {{ $item->full_number }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $item->salesDocument?->customer?->name ?? '-' }}
                                    </td>
                                    <td>{{ $item->reason }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Respuesta SUNAT -->
                    @if($voided->response_message)
                    <div class="alert {{ $voided->status === 'accepted' ? 'alert-success' : 'alert-danger' }} mt-4">
                        <h6 class="alert-heading">
                            <i class="fa fa-{{ $voided->status === 'accepted' ? 'check' : 'times' }}-circle me-1"></i>
                            Respuesta SUNAT
                        </h6>
                        <p class="mb-0">
                            <strong>Código:</strong> {{ $voided->response_code ?? '-' }}<br>
                            <strong>Mensaje:</strong> {{ $voided->response_message }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-md-4">
            <!-- Estado y Acciones -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-cogs me-1"></i> Acciones</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    @if($voided->status === 'pending')
                        <form action="{{ route('sales.voided.resend', $voided->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-paper-plane me-1"></i> Enviar a SUNAT
                            </button>
                        </form>
                    @elseif($voided->status === 'sent')
                        <form action="{{ route('sales.voided.check-status', $voided->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info w-100">
                                <i class="fa fa-refresh me-1"></i> Consultar Estado
                            </button>
                        </form>
                        <small class="text-muted text-center">
                            Ticket: <code>{{ $voided->ticket }}</code>
                        </small>
                    @endif
                    
                    <a href="{{ route('sales.voided.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Volver al listado
                    </a>
                </div>
            </div>

            <!-- Información Técnica -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-info-circle me-1"></i> Información</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td>{{ ucfirst($voided->status) }}</td>
                        </tr>
                        @if($voided->ticket)
                        <tr>
                            <td><strong>Ticket:</strong></td>
                            <td><code>{{ $voided->ticket }}</code></td>
                        </tr>
                        @endif
                        @if($voided->sent_at)
                        <tr>
                            <td><strong>Enviado:</strong></td>
                            <td>{{ $voided->sent_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endif
                        @if($voided->status_checked_at)
                        <tr>
                            <td><strong>Última consulta:</strong></td>
                            <td>{{ $voided->status_checked_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endif
                        @if($voided->cdr_path)
                        <tr>
                            <td colspan="2">
                                <a href="{{ route('files.download', ['path' => $voided->cdr_path]) }}" class="btn btn-sm btn-outline-success w-100">
                                    <i class="fa fa-download me-1"></i> Descargar CDR
                                </a>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
