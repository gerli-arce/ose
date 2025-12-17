@extends('layout.master')

@section('title', 'Guía de Remisión ' . $despatch->full_number)

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Guía de Remisión {{ $despatch->full_number }}</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('despatch.index') }}">Guías</a></li>
                    <li class="breadcrumb-item active">{{ $despatch->full_number }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Columna principal -->
        <div class="col-lg-8">
            <!-- Datos Básicos -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-truck me-2"></i>
                        {{ $despatch->full_number }}
                    </h5>
                    <span class="badge bg-{{ $despatch->sunat_status_color }} fs-6">
                        {{ $despatch->sunat_status_name }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="40%">Fecha Emisión:</td>
                                    <td><strong>{{ $despatch->issue_date->format('d/m/Y') }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Fecha Traslado:</td>
                                    <td><strong>{{ $despatch->transfer_date->format('d/m/Y') }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Motivo:</td>
                                    <td>{{ $despatch->transferReason?->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Modalidad:</td>
                                    <td>
                                        <span class="badge bg-{{ $despatch->isPublicTransport() ? 'info' : 'secondary' }}">
                                            {{ $despatch->transportModality?->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="40%">Peso Bruto:</td>
                                    <td><strong>{{ number_format($despatch->gross_weight, 2) }} kg</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Bultos:</td>
                                    <td><strong>{{ $despatch->package_count }}</strong></td>
                                </tr>
                                @if($despatch->salesDocument)
                                <tr>
                                    <td class="text-muted">Doc. Relacionado:</td>
                                    <td>
                                        <a href="{{ route('sales.documents.show', $despatch->salesDocument->id) }}">
                                            {{ $despatch->salesDocument->full_number ?? 'N/A' }}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Origen y Destino -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-map-marker me-1"></i> Origen y Destino</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                <h6 class="text-success"><i class="fa fa-circle me-1"></i> Punto de Partida</h6>
                                <p class="mb-1"><strong>{{ $despatch->origin_address }}</strong></p>
                                <small class="text-muted">
                                    Ubigeo: {{ $despatch->originUbigeo?->code ?? 'N/A' }}
                                    @if($despatch->originUbigeo)
                                    - {{ $despatch->originUbigeo->full_name }}
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-danger bg-opacity-10 rounded">
                                <h6 class="text-danger"><i class="fa fa-circle me-1"></i> Punto de Llegada</h6>
                                <p class="mb-1"><strong>{{ $despatch->destination_address }}</strong></p>
                                <small class="text-muted">
                                    Ubigeo: {{ $despatch->destinationUbigeo?->code ?? 'N/A' }}
                                    @if($despatch->destinationUbigeo)
                                    - {{ $despatch->destinationUbigeo->full_name }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-list me-1"></i> Bienes Transportados</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th>Producto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($despatch->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ number_format($item->quantity, 2) }}</td>
                                <td>{{ $item->unit_code }}</td>
                                <td>{{ $item->product?->name ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Transporte -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-truck me-1"></i> Datos del Transporte</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($despatch->transporter)
                        <div class="col-md-6">
                            <h6>Transportista</h6>
                            <p class="mb-1">{{ $despatch->transporter->business_name }}</p>
                            <small class="text-muted">{{ $despatch->transporter->document_type }}: {{ $despatch->transporter->document_number }}</small>
                        </div>
                        @endif
                        
                        @if($despatch->driver_name)
                        <div class="col-md-6">
                            <h6>Conductor</h6>
                            <p class="mb-1">{{ $despatch->driver_name }}</p>
                            <small class="text-muted">
                                DNI: {{ $despatch->driver_document_number }}
                                @if($despatch->driver_license)
                                | Licencia: {{ $despatch->driver_license }}
                                @endif
                            </small>
                        </div>
                        @endif
                        
                        @if($despatch->vehicle)
                        <div class="col-md-6 mt-3">
                            <h6>Vehículo</h6>
                            <p class="mb-1"><strong>{{ $despatch->vehicle->plate_number }}</strong></p>
                            <small class="text-muted">
                                {{ $despatch->vehicle->brand }} {{ $despatch->vehicle->model }}
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna lateral -->
        <div class="col-lg-4">
            <!-- Estado SUNAT -->
            @if($despatch->eDocument)
            <div class="card mb-3">
                <div class="card-header bg-{{ $despatch->sunat_status_color }} text-white">
                    <h6 class="mb-0">Respuesta SUNAT</h6>
                </div>
                <div class="card-body">
                    @if($despatch->eDocument->response_code)
                    <p class="mb-1">
                        <strong>Código:</strong> {{ $despatch->eDocument->response_code }}
                    </p>
                    @endif
                    @if($despatch->eDocument->response_message)
                    <p class="mb-0 small">{{ $despatch->eDocument->response_message }}</p>
                    @endif
                    @if($despatch->eDocument->hash)
                    <hr>
                    <small class="text-muted">Hash:</small>
                    <code class="d-block small">{{ Str::limit($despatch->eDocument->hash, 40) }}</code>
                    @endif
                </div>
            </div>
            @endif

            <!-- Destinatario -->
            @if($despatch->recipient_name)
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-user me-1"></i> Destinatario</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $despatch->recipient_name }}</strong></p>
                    <small class="text-muted">{{ $despatch->recipient_document_type }}: {{ $despatch->recipient_document_number }}</small>
                </div>
            </div>
            @endif

            <!-- Observaciones -->
            @if($despatch->observation)
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-comment me-1"></i> Observaciones</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $despatch->observation }}</p>
                </div>
            </div>
            @endif

            <!-- Acciones -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-cog me-1"></i> Acciones</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    @if(!$despatch->isAccepted())
                    <form action="{{ route('despatch.resend', $despatch->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fa fa-refresh me-1"></i> Reenviar a SUNAT
                        </button>
                    </form>
                    @endif

                    @if($despatch->eDocument?->xml_path)
                    <a href="{{ route('files.download', ['path' => $despatch->eDocument->xml_path]) }}" 
                       class="btn btn-outline-dark">
                        <i class="fa fa-file-code-o me-1"></i> Descargar XML
                    </a>
                    @endif

                    @if($despatch->eDocument?->cdr_path)
                    <a href="{{ route('files.download', ['path' => $despatch->eDocument->cdr_path]) }}" 
                       class="btn btn-outline-success">
                        <i class="fa fa-file-archive-o me-1"></i> Descargar CDR
                    </a>
                    @endif

                    <a href="{{ route('despatch.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
