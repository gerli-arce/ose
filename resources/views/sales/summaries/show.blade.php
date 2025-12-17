@extends('layout.master')

@section('title', 'Resumen ' . $summary->identifier)

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>{{ $summary->identifier }}</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.summaries.index') }}">Resúmenes</a></li>
                    <li class="breadcrumb-item active">{{ $summary->identifier }}</li>
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
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-file-text me-2"></i>
                        Resumen Diario de Boletas
                    </h5>
                    <div>
                        @switch($summary->status)
                            @case('accepted')
                                <span class="badge bg-success fs-6">ACEPTADO</span>
                                @break
                            @case('rejected')
                                <span class="badge bg-danger fs-6">RECHAZADO</span>
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
                        <div class="col-md-3">
                            <strong>Identificador:</strong><br>
                            <code class="h5">{{ $summary->identifier }}</code>
                        </div>
                        <div class="col-md-3">
                            <strong>Fecha Resumen:</strong><br>
                            {{ $summary->summary_date->format('d/m/Y') }}
                        </div>
                        <div class="col-md-3">
                            <strong>Fecha Documentos:</strong><br>
                            {{ $summary->reference_date->format('d/m/Y') }}
                        </div>
                        <div class="col-md-3">
                            <strong>Total:</strong><br>
                            <span class="h5 text-primary">S/ {{ number_format($summary->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <hr>

                    <!-- Documentos Incluidos -->
                    <h6 class="mb-3">
                        <i class="fa fa-list me-1"></i> Documentos Incluidos 
                        <span class="badge bg-secondary">{{ $summary->items->count() }}</span>
                    </h6>
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Número</th>
                                    <th>Cliente</th>
                                    <th>Estado</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($summary->items as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-dark">{{ $item->document_type_code }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('sales.documents.show', $item->sales_document_id) }}">
                                            {{ $item->full_number }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ Str::limit($item->salesDocument?->customer?->name ?? '-', 25) }}
                                    </td>
                                    <td>
                                        @switch($item->status_code)
                                            @case('1')
                                                <span class="badge bg-success">Agregar</span>
                                                @break
                                            @case('2')
                                                <span class="badge bg-warning text-dark">Modificar</span>
                                                @break
                                            @case('3')
                                                <span class="badge bg-danger">Anular</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total Resumen:</th>
                                    <th class="text-end">S/ {{ number_format($summary->total_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Respuesta SUNAT -->
                    @if($summary->response_message)
                    <div class="alert {{ $summary->status === 'accepted' ? 'alert-success' : 'alert-danger' }} mt-4">
                        <h6 class="alert-heading">
                            <i class="fa fa-{{ $summary->status === 'accepted' ? 'check' : 'times' }}-circle me-1"></i>
                            Respuesta SUNAT
                        </h6>
                        <p class="mb-0">
                            <strong>Código:</strong> {{ $summary->response_code ?? '-' }}<br>
                            <strong>Mensaje:</strong> {{ $summary->response_message }}
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
                    @if($summary->status === 'pending')
                        <form action="{{ route('sales.summaries.resend', $summary->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-paper-plane me-1"></i> Enviar a SUNAT
                            </button>
                        </form>
                    @elseif($summary->status === 'sent')
                        <form action="{{ route('sales.summaries.check-status', $summary->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info w-100">
                                <i class="fa fa-refresh me-1"></i> Consultar Estado
                            </button>
                        </form>
                        <small class="text-muted text-center">
                            Ticket: <code>{{ $summary->ticket }}</code>
                        </small>
                    @endif
                    
                    <a href="{{ route('sales.summaries.index') }}" class="btn btn-outline-secondary">
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
                            <td>{{ ucfirst($summary->status) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Documentos:</strong></td>
                            <td>{{ $summary->total_documents }}</td>
                        </tr>
                        @if($summary->ticket)
                        <tr>
                            <td><strong>Ticket:</strong></td>
                            <td><code class="small">{{ $summary->ticket }}</code></td>
                        </tr>
                        @endif
                        @if($summary->sent_at)
                        <tr>
                            <td><strong>Enviado:</strong></td>
                            <td>{{ $summary->sent_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endif
                        @if($summary->status_checked_at)
                        <tr>
                            <td><strong>Última consulta:</strong></td>
                            <td>{{ $summary->status_checked_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endif
                    </table>

                    @if($summary->xml_path || $summary->cdr_path)
                    <hr>
                    <div class="d-grid gap-2">
                        @if($summary->xml_path)
                        <a href="{{ route('files.download', ['path' => $summary->xml_path]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-download me-1"></i> Descargar XML
                        </a>
                        @endif
                        @if($summary->cdr_path)
                        <a href="{{ route('files.download', ['path' => $summary->cdr_path]) }}" class="btn btn-sm btn-outline-success">
                            <i class="fa fa-download me-1"></i> Descargar CDR
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
