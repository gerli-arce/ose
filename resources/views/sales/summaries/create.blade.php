@extends('layout.master')

@section('title', 'Generar Resumen Diario')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Generar Resumen Diario</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.summaries.index') }}">Resúmenes</a></li>
                    <li class="breadcrumb-item active">Generar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fa fa-file-text me-2"></i>
                        Resumen Diario de Boletas
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Selector de fecha --}}
                    <form method="GET" class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Fecha de Boletas</label>
                            <input type="date" class="form-control" name="date" 
                                   value="{{ $date }}" max="{{ today()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fa fa-search me-1"></i> Buscar Boletas
                            </button>
                        </div>
                    </form>

                    @if($existingSummary)
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            Ya existe un resumen para esta fecha:
                            <a href="{{ route('sales.summaries.show', $existingSummary->id) }}" class="alert-link">
                                {{ $existingSummary->identifier }}
                            </a>
                            (Estado: {{ ucfirst($existingSummary->status) }})
                        </div>
                    @endif

                    @if($pendingBoletas->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle me-1"></i>
                            No hay boletas pendientes de resumen para la fecha <strong>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</strong>.
                        </div>
                    @else
                        <div class="alert alert-success mb-4">
                            <h6 class="alert-heading">
                                <i class="fa fa-check-circle me-1"></i>
                                {{ $pendingBoletas->count() }} boletas pendientes de resumen
                            </h6>
                            <p class="mb-0">
                                Fecha: <strong>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</strong> |
                                Total: <strong>S/ {{ number_format($pendingBoletas->sum('total'), 2) }}</strong>
                            </p>
                        </div>

                        {{-- Tabla de boletas --}}
                        <div class="table-responsive mb-4" style="max-height: 400px;">
                            <table class="table table-sm table-bordered">
                                <thead class="bg-light sticky-top">
                                    <tr>
                                        <th>Número</th>
                                        <th>Cliente</th>
                                        <th class="text-end">Total</th>
                                        <th>Estado SUNAT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingBoletas as $boleta)
                                    <tr>
                                        <td>
                                            <code>{{ $boleta->series?->prefix }}-{{ str_pad($boleta->number, 8, '0', STR_PAD_LEFT) }}</code>
                                        </td>
                                        <td>
                                            {{ Str::limit($boleta->customer?->name ?? 'Cliente Varios', 30) }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($boleta->total, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($boleta->sunat_status ?? 'pending') }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Formulario de generación --}}
                        <form id="summaryForm">
                            @csrf
                            <input type="hidden" name="reference_date" value="{{ $date }}">
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="sendSunat" name="send_to_sunat" checked>
                                <label class="form-check-label" for="sendSunat">
                                    Enviar a SUNAT inmediatamente
                                </label>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('sales.summaries.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left me-1"></i> Cancelar
                                </a>
                                <button type="button" class="btn btn-primary" onclick="generateSummary()">
                                    <i class="fa fa-cogs me-1"></i> Generar Resumen Diario
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-info-circle me-1"></i> Información</h6>
                </div>
                <div class="card-body">
                    <h6>¿Qué es el Resumen Diario?</h6>
                    <p class="small text-muted">
                        El Resumen Diario es un documento electrónico obligatorio que informa a SUNAT 
                        sobre las boletas de venta emitidas. Debe enviarse dentro de los 7 días 
                        calendario siguientes a la emisión.
                    </p>
                    
                    <hr>
                    
                    <h6>Documentos incluidos:</h6>
                    <ul class="small text-muted">
                        <li>Boletas de Venta (03)</li>
                        <li>Notas de Crédito de Boletas</li>
                        <li>Notas de Débito de Boletas</li>
                    </ul>

                    <hr>

                    <h6>Identificador:</h6>
                    <p class="small text-muted">
                        <code>RC-YYYYMMDD-#####</code><br>
                        RC = Resumen de Comprobantes<br>
                        YYYYMMDD = Fecha de generación<br>
                        ##### = Correlativo del día
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function generateSummary() {
    Swal.fire({
        title: '¿Generar Resumen Diario?',
        html: 'Se incluirán <strong>{{ $pendingBoletas->count() }}</strong> boletas en el resumen.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, generar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            submitSummary();
        }
    });
}

function submitSummary() {
    const data = {
        _token: '{{ csrf_token() }}',
        reference_date: '{{ $date }}',
        send_to_sunat: document.getElementById('sendSunat').checked ? 1 : 0
    };

    fetch('{{ route("sales.summaries.store") }}', {
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
                title: 'Resumen Generado',
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
