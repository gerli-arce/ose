@extends('layout.master')

@section('title', 'Anular Documento')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Comunicación de Baja</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.documents.index') }}">Ventas</a></li>
                    <li class="breadcrumb-item active">Anular Documento</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fa fa-ban me-2"></i>
                        Anular Documento ante SUNAT
                    </h5>
                </div>
                <div class="card-body">
                    <form id="voidForm">
                        @csrf
                        <input type="hidden" name="document_id" value="{{ $document->id }}">

                        <!-- Documento a Anular -->
                        <div class="alert alert-danger">
                            <h6 class="alert-heading mb-3">
                                <i class="fa fa-exclamation-triangle me-1"></i> Documento a Anular
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Tipo:</strong><br>
                                    {{ $document->documentType->name ?? 'N/A' }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Número:</strong><br>
                                    <span class="badge bg-dark fs-6">
                                        {{ $document->series->prefix ?? '' }}-{{ str_pad($document->number, 8, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Total:</strong><br>
                                    <span class="h5">S/ {{ number_format($document->total, 2) }}</span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Cliente:</strong><br>
                                    {{ $document->customer->name ?? 'N/A' }}
                                    ({{ $document->customer->tax_id ?? '' }})
                                </div>
                                <div class="col-md-6">
                                    <strong>Fecha Emisión:</strong><br>
                                    {{ $document->issue_date->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Advertencia -->
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-circle me-1"></i>
                            <strong>Importante:</strong>
                            <ul class="mb-0 mt-2">
                                <li>La anulación es <strong>irreversible</strong> una vez aceptada por SUNAT.</li>
                                <li>El documento quedará marcado como <strong>ANULADO</strong> en el sistema.</li>
                                <li>Asegúrese de tener un motivo válido para la anulación.</li>
                            </ul>
                        </div>

                        <!-- Motivo -->
                        <div class="mb-4">
                            <label class="form-label"><strong>Motivo de la Anulación</strong> <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="reason" rows="3" 
                                      placeholder="Escriba el motivo por el cual se anula este documento..." 
                                      required maxlength="500"></textarea>
                            <small class="text-muted">Máximo 500 caracteres</small>
                        </div>

                        <!-- Opciones -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="sendSunat" name="send_to_sunat" checked>
                            <label class="form-check-label" for="sendSunat">
                                Enviar Comunicación de Baja a SUNAT inmediatamente
                            </label>
                        </div>

                        <!-- Acciones -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sales.documents.show', $document->id) }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left me-1"></i> Cancelar
                            </a>
                            <button type="button" class="btn btn-danger" onclick="confirmVoid()">
                                <i class="fa fa-ban me-1"></i> Anular Documento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmVoid() {
    const reason = document.querySelector('[name=reason]').value.trim();
    
    if (!reason) {
        Swal.fire('Error', 'Debe ingresar el motivo de la anulación.', 'error');
        return;
    }

    Swal.fire({
        title: '¿Anular este documento?',
        html: `
            <p>Está a punto de anular:</p>
            <strong>{{ $document->documentType->name ?? '' }} {{ $document->series->prefix ?? '' }}-{{ str_pad($document->number, 8, '0', STR_PAD_LEFT) }}</strong>
            <p class="text-danger mt-2"><strong>Esta acción no se puede deshacer.</strong></p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Sí, anular documento',
        cancelButtonText: 'No, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            submitVoid();
        }
    });
}

function submitVoid() {
    const data = {
        _token: '{{ csrf_token() }}',
        document_id: '{{ $document->id }}',
        reason: document.querySelector('[name=reason]').value,
        send_to_sunat: document.getElementById('sendSunat').checked ? 1 : 0
    };

    fetch('{{ route("sales.voided.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            Swal.fire({
                title: 'Comunicación de Baja Creada',
                text: res.message,
                icon: 'success'
            }).then(() => {
                window.location.href = res.redirect;
            });
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        Swal.fire('Error', 'Error de conexión', 'error');
    });
}
</script>
@endsection
