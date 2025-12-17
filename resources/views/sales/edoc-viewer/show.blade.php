@extends('layout.master')

@section('title', 'Visor de Documento Electrónico')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/vs2015.min.css">
<style>
    .xml-viewer {
        background: #1e1e1e;
        color: #d4d4d4;
        padding: 15px;
        border-radius: 8px;
        max-height: 500px;
        overflow: auto;
        font-family: 'Consolas', 'Monaco', monospace;
        font-size: 12px;
        line-height: 1.5;
    }
    .xml-viewer pre {
        margin: 0;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #a0aec0;
        border: 2px solid white;
    }
    .timeline-item.success::before {
        background: #48bb78;
    }
    .timeline-item.error::before {
        background: #f56565;
    }
    .timeline-item.pending::before {
        background: #ed8936;
    }
    .response-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
    }
    .hash-display {
        font-family: monospace;
        font-size: 11px;
        background: #f7fafc;
        padding: 8px 12px;
        border-radius: 6px;
        word-break: break-all;
        border: 1px solid #e2e8f0;
    }
    .file-info {
        background: #f7fafc;
        border-radius: 8px;
        padding: 15px;
    }
    .file-icon {
        font-size: 2rem;
        color: #4a5568;
    }
</style>
@endpush

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Visor de Documento Electrónico</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.documents.index') }}">Ventas</a></li>
                    <li class="breadcrumb-item active">Visor E-Doc</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Información del Documento -->
        <div class="col-md-4">
            <!-- Identificación -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fa fa-file-text me-1"></i>
                        {{ $document->documentType?->name ?? 'Documento' }}
                    </h6>
                </div>
                <div class="card-body">
                    <h4 class="text-primary mb-3">
                        {{ $document->series?->prefix ?? '' }}-{{ str_pad($document->number, 8, '0', STR_PAD_LEFT) }}
                    </h4>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted">Fecha:</td>
                            <td>{{ $document->issue_date?->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Cliente:</td>
                            <td>{{ $document->customer?->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total:</td>
                            <td><strong>S/ {{ number_format($document->total, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estado SUNAT:</td>
                            <td>
                                @switch($document->sunat_status)
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
                    </table>
                </div>
            </div>

            <!-- Respuesta SUNAT -->
            @if($document->eDocument)
            <div class="card response-card mb-3">
                <div class="card-body">
                    <h6 class="text-white-50 mb-2">Respuesta SUNAT</h6>
                    @if($document->eDocument->response_code)
                        <div class="mb-2">
                            <span class="badge bg-light text-dark fs-6">{{ $document->eDocument->response_code }}</span>
                        </div>
                    @endif
                    <p class="mb-0 small">
                        {{ $document->eDocument->response_message ?? 'Sin mensaje de respuesta' }}
                    </p>
                </div>
            </div>
            @endif

            <!-- Hash -->
            @if($document->eDocument?->hash)
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-key me-1"></i> Hash (DigestValue)</h6>
                </div>
                <div class="card-body">
                    <div class="hash-display">
                        {{ $document->eDocument->hash }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Archivos -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-folder-open me-1"></i> Archivos Electrónicos</h6>
                </div>
                <div class="card-body">
                    <!-- XML -->
                    <div class="file-info mb-3">
                        <div class="d-flex align-items-center">
                            <div class="file-icon me-3">
                                <i class="fa fa-file-code-o"></i>
                            </div>
                            <div class="flex-grow-1">
                                <strong>XML Firmado</strong>
                                @if($document->eDocument?->xml_path)
                                    <br><small class="text-muted">{{ basename($document->eDocument->xml_path) }}</small>
                                @else
                                    <br><small class="text-danger">No disponible</small>
                                @endif
                            </div>
                            @if($document->eDocument?->xml_path)
                            <div>
                                <button class="btn btn-sm btn-primary me-1" onclick="viewXml()">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <a href="{{ route('edoc-viewer.download-xml', $document->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-download"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- CDR -->
                    <div class="file-info">
                        <div class="d-flex align-items-center">
                            <div class="file-icon me-3">
                                <i class="fa fa-file-archive-o"></i>
                            </div>
                            <div class="flex-grow-1">
                                <strong>CDR (Respuesta)</strong>
                                @if($document->eDocument?->cdr_path)
                                    <br><small class="text-muted">{{ basename($document->eDocument->cdr_path) }}</small>
                                @else
                                    <br><small class="text-danger">No disponible</small>
                                @endif
                            </div>
                            @if($document->eDocument?->cdr_path)
                            <div>
                                <button class="btn btn-sm btn-success me-1" onclick="viewCdr()">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <a href="{{ route('edoc-viewer.download-cdr', $document->id) }}" 
                                   class="btn btn-sm btn-outline-success">
                                    <i class="fa fa-download"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card">
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('sales.documents.show', $document->id) }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i> Volver al Documento
                    </a>
                    <a href="{{ route('pdf.document.view', $document->id) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="fa fa-file-pdf-o me-1"></i> Ver PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Visor XML/CDR -->
        <div class="col-md-8">
            <!-- Tabs -->
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tabXml">
                        <i class="fa fa-code me-1"></i> XML Firmado
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tabCdr">
                        <i class="fa fa-file-archive-o me-1"></i> CDR (Respuesta)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tabHistory">
                        <i class="fa fa-history me-1"></i> Histórico de Envíos
                        @if($attempts->count() > 0)
                            <span class="badge bg-secondary ms-1">{{ $attempts->count() }}</span>
                        @endif
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Tab XML -->
                <div class="tab-pane fade show active" id="tabXml">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">XML Firmado (UBL 2.1)</h6>
                            @if($document->eDocument?->xml_path)
                            <a href="{{ route('edoc-viewer.download-xml', $document->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-download me-1"></i> Descargar
                            </a>
                            @endif
                        </div>
                        <div class="card-body p-0">
                            <div class="xml-viewer" id="xmlViewer">
                                <pre id="xmlContent">
                                    <div class="text-center py-5 text-muted">
                                        <i class="fa fa-spinner fa-spin fa-2x mb-2"></i>
                                        <br>Cargando XML...
                                    </div>
                                </pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab CDR -->
                <div class="tab-pane fade" id="tabCdr">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">CDR - Constancia de Recepción SUNAT</h6>
                            @if($document->eDocument?->cdr_path)
                            <a href="{{ route('edoc-viewer.download-cdr', $document->id) }}" 
                               class="btn btn-sm btn-outline-success">
                                <i class="fa fa-download me-1"></i> Descargar ZIP
                            </a>
                            @endif
                        </div>
                        <div class="card-body p-0">
                            <div class="xml-viewer" id="cdrViewer">
                                <pre id="cdrContent">
                                    <div class="text-center py-5 text-muted">
                                        <i class="fa fa-file-archive-o fa-2x mb-2"></i>
                                        <br>Click en "Ver" para cargar el contenido del CDR
                                    </div>
                                </pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Histórico -->
                <div class="tab-pane fade" id="tabHistory">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Histórico de Intentos de Envío</h6>
                        </div>
                        <div class="card-body">
                            @if($attempts->count() > 0)
                            <div class="timeline">
                                @foreach($attempts as $attempt)
                                <div class="timeline-item {{ $attempt->status }}">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div>
                                            <span class="badge bg-{{ $attempt->status_color }}">
                                                {{ $attempt->status_name }}
                                            </span>
                                            <span class="text-muted ms-2">{{ $attempt->attempt_type_name }}</span>
                                        </div>
                                        <small class="text-muted">
                                            {{ $attempt->attempted_at->format('d/m/Y H:i:s') }}
                                        </small>
                                    </div>
                                    
                                    @if($attempt->response_code)
                                    <div class="small">
                                        <strong>Código:</strong> {{ $attempt->response_code }}
                                    </div>
                                    @endif
                                    
                                    @if($attempt->response_message)
                                    <div class="small text-muted">
                                        {{ Str::limit($attempt->response_message, 150) }}
                                    </div>
                                    @endif

                                    @if($attempt->ticket)
                                    <div class="small mt-1">
                                        <strong>Ticket:</strong> 
                                        <code>{{ $attempt->ticket }}</code>
                                    </div>
                                    @endif

                                    @if($attempt->error_details)
                                    <div class="mt-2">
                                        <button class="btn btn-xs btn-outline-danger" 
                                                onclick="showErrorDetails('{{ addslashes($attempt->error_details) }}')">
                                            Ver detalles del error
                                        </button>
                                    </div>
                                    @endif

                                    @if($attempt->user)
                                    <div class="small text-muted mt-1">
                                        <i class="fa fa-user me-1"></i> {{ $attempt->user->name }}
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4 text-muted">
                                <i class="fa fa-inbox fa-3x mb-2"></i>
                                <p>No hay intentos de envío registrados.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles de error -->
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Detalles del Error</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="errorDetails" class="bg-light p-3" style="max-height: 400px; overflow: auto;"></pre>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/xml.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar XML automáticamente
    viewXml();
});

function viewXml() {
    fetch('{{ route("edoc-viewer.view-xml", $document->id) }}')
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                const content = escapeHtml(res.content);
                document.getElementById('xmlContent').innerHTML = content;
                hljs.highlightElement(document.getElementById('xmlContent'));
            } else {
                document.getElementById('xmlContent').innerHTML = `<div class="text-danger">${res.message}</div>`;
            }
        })
        .catch(err => {
            document.getElementById('xmlContent').innerHTML = '<div class="text-danger">Error al cargar XML</div>';
        });
}

function viewCdr() {
    document.getElementById('cdrContent').innerHTML = '<div class="text-center py-3"><i class="fa fa-spinner fa-spin"></i> Cargando...</div>';
    
    fetch('{{ route("edoc-viewer.view-cdr", $document->id) }}')
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                const content = escapeHtml(res.content);
                document.getElementById('cdrContent').innerHTML = content;
                hljs.highlightElement(document.getElementById('cdrContent'));
                
                // Cambiar a tab de CDR
                const cdrTab = document.querySelector('[href="#tabCdr"]');
                if (cdrTab) {
                    new bootstrap.Tab(cdrTab).show();
                }
            } else {
                document.getElementById('cdrContent').innerHTML = `<div class="text-danger">${res.message}</div>`;
            }
        })
        .catch(err => {
            document.getElementById('cdrContent').innerHTML = '<div class="text-danger">Error al cargar CDR</div>';
        });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showErrorDetails(details) {
    document.getElementById('errorDetails').textContent = details;
    new bootstrap.Modal(document.getElementById('errorModal')).show();
}
</script>
@endpush
