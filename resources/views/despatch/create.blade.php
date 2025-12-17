@extends('layout.master')

@section('title', 'Nueva Guía de Remisión')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Nueva Guía de Remisión</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('despatch.index') }}">Guías</a></li>
                    <li class="breadcrumb-item active">Nueva</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <form action="{{ route('despatch.store') }}" method="POST" id="despatchForm">
        @csrf
        
        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-lg-8">
                <!-- Datos Básicos -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-info-circle me-1"></i> Datos de la Guía</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Serie <span class="text-danger">*</span></label>
                                <select name="series_id" class="form-select" required>
                                    @foreach($series as $s)
                                    <option value="{{ $s->id }}">{{ $s->prefix }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Emisión <span class="text-danger">*</span></label>
                                <input type="date" name="issue_date" class="form-control" 
                                       value="{{ old('issue_date', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Traslado <span class="text-danger">*</span></label>
                                <input type="date" name="transfer_date" class="form-control" 
                                       value="{{ old('transfer_date', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Motivo <span class="text-danger">*</span></label>
                                <select name="transfer_reason_id" class="form-select" required>
                                    @foreach($transferReasons as $reason)
                                    <option value="{{ $reason->id }}">{{ $reason->code }} - {{ $reason->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label class="form-label">Modalidad de Transporte <span class="text-danger">*</span></label>
                                <select name="transport_modality_id" id="transportModality" class="form-select" required>
                                    @foreach($modalities as $m)
                                    <option value="{{ $m->id }}" data-code="{{ $m->code }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Peso Bruto (kg) <span class="text-danger">*</span></label>
                                <input type="number" name="gross_weight" class="form-control" 
                                       step="0.01" min="0.01" value="{{ old('gross_weight', '1.00') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Número de Bultos <span class="text-danger">*</span></label>
                                <input type="number" name="package_count" class="form-control" 
                                       min="1" value="{{ old('package_count', '1') }}" required>
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
                            <!-- Origen -->
                            <div class="col-md-6">
                                <h6 class="text-success"><i class="fa fa-circle me-1"></i> Punto de Partida</h6>
                                <div class="mb-3">
                                    <label class="form-label">Dirección <span class="text-danger">*</span></label>
                                    <textarea name="origin_address" class="form-control" rows="2" required>{{ old('origin_address') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ubigeo <span class="text-danger">*</span></label>
                                    @include('components.ubigeo-selector', [
                                        'selectedUbigeoId' => old('origin_ubigeo_id'),
                                        'namePrefix' => 'origin',
                                        'required' => true
                                    ])
                                </div>
                            </div>
                            
                            <!-- Destino -->
                            <div class="col-md-6">
                                <h6 class="text-danger"><i class="fa fa-circle me-1"></i> Punto de Llegada</h6>
                                <div class="mb-3">
                                    <label class="form-label">Dirección <span class="text-danger">*</span></label>
                                    <textarea name="destination_address" class="form-control" rows="2" required>{{ old('destination_address') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ubigeo <span class="text-danger">*</span></label>
                                    @include('components.ubigeo-selector', [
                                        'selectedUbigeoId' => old('destination_ubigeo_id'),
                                        'namePrefix' => 'destination',
                                        'required' => true
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fa fa-list me-1"></i> Bienes a Transportar</h6>
                        <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                            <i class="fa fa-plus me-1"></i> Agregar Item
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="40%">Descripción</th>
                                    <th width="15%">Cantidad</th>
                                    <th width="15%">Unidad</th>
                                    <th width="25%">Producto</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr class="item-row">
                                    <td>
                                        <input type="text" name="items[0][description]" class="form-control form-control-sm" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][quantity]" class="form-control form-control-sm" 
                                               step="0.01" min="0.01" value="1" required>
                                    </td>
                                    <td>
                                        <select name="items[0][unit_code]" class="form-select form-select-sm" required>
                                            <option value="NIU">Unidad</option>
                                            <option value="KGM">Kilogramo</option>
                                            <option value="LTR">Litro</option>
                                            <option value="MTR">Metro</option>
                                            <option value="BX">Caja</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="items[0][product_id]" class="form-select form-select-sm">
                                            <option value="">-- Seleccionar --</option>
                                            @foreach($products as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-item" disabled>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-lg-4">
                <!-- Destinatario -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-user me-1"></i> Destinatario</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tipo Documento</label>
                            <select name="recipient_document_type" class="form-select">
                                <option value="6">RUC</option>
                                <option value="1">DNI</option>
                                <option value="0">Otros</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Número Documento</label>
                            <input type="text" name="recipient_document_number" class="form-control" 
                                   value="{{ old('recipient_document_number') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Razón Social / Nombre</label>
                            <input type="text" name="recipient_name" class="form-control" 
                                   value="{{ old('recipient_name') }}">
                        </div>
                    </div>
                </div>

                <!-- Transporte -->
                <div class="card mb-3" id="transportCard">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-truck me-1"></i> Datos del Transporte</h6>
                    </div>
                    <div class="card-body">
                        <!-- Transportista (para transporte público) -->
                        <div id="transporterSection">
                            <div class="mb-3">
                                <label class="form-label">Transportista</label>
                                <select name="transporter_id" class="form-select">
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($transporters as $t)
                                    <option value="{{ $t->id }}">{{ $t->business_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Conductor -->
                        <div class="mb-3">
                            <label class="form-label">DNI Conductor</label>
                            <input type="text" name="driver_document_number" class="form-control" maxlength="8">
                            <input type="hidden" name="driver_document_type" value="1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nombre Conductor</label>
                            <input type="text" name="driver_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Licencia de Conducir</label>
                            <input type="text" name="driver_license" class="form-control">
                        </div>

                        <!-- Vehículo -->
                        <div class="mb-3">
                            <label class="form-label">Vehículo</label>
                            <select name="vehicle_id" class="form-select">
                                <option value="">-- Seleccionar --</option>
                                @foreach($vehicles as $v)
                                <option value="{{ $v->id }}">{{ $v->plate_number }} - {{ $v->brand }} {{ $v->model }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-comment me-1"></i> Observaciones</h6>
                    </div>
                    <div class="card-body">
                        <textarea name="observation" class="form-control" rows="3">{{ old('observation') }}</textarea>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="card">
                    <div class="card-body d-grid gap-2">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="send_to_sunat" id="sendToSunat" checked>
                            <label class="form-check-label" for="sendToSunat">
                                Enviar a SUNAT al guardar
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-save me-1"></i> Guardar Guía
                        </button>
                        <a href="{{ route('despatch.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-times me-1"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let itemIndex = 1;

document.addEventListener('DOMContentLoaded', function() {
    // Agregar item
    document.getElementById('addItemBtn').addEventListener('click', addItem);
    
    // Eliminar item
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const row = e.target.closest('.item-row');
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                updateRemoveButtons();
            }
        }
    });

    // Cambio de modalidad de transporte
    document.getElementById('transportModality').addEventListener('change', function() {
        const code = this.options[this.selectedIndex].dataset.code;
        const transporterSection = document.getElementById('transporterSection');
        
        // Mostrar/ocultar sección de transportista
        if (code === '01') { // Transporte público
            transporterSection.style.display = 'block';
        } else {
            transporterSection.style.display = 'none';
        }
    });
});

function addItem() {
    const tbody = document.getElementById('itemsBody');
    const products = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name]));
    
    let productOptions = '<option value="">-- Seleccionar --</option>';
    products.forEach(p => {
        productOptions += `<option value="${p.id}">${p.name}</option>`;
    });
    
    const row = document.createElement('tr');
    row.className = 'item-row';
    row.innerHTML = `
        <td>
            <input type="text" name="items[${itemIndex}][description]" class="form-control form-control-sm" required>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][quantity]" class="form-control form-control-sm" 
                   step="0.01" min="0.01" value="1" required>
        </td>
        <td>
            <select name="items[${itemIndex}][unit_code]" class="form-select form-select-sm" required>
                <option value="NIU">Unidad</option>
                <option value="KGM">Kilogramo</option>
                <option value="LTR">Litro</option>
                <option value="MTR">Metro</option>
                <option value="BX">Caja</option>
            </select>
        </td>
        <td>
            <select name="items[${itemIndex}][product_id]" class="form-select form-select-sm">
                ${productOptions}
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    itemIndex++;
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.item-row');
    rows.forEach((row, index) => {
        const btn = row.querySelector('.remove-item');
        btn.disabled = rows.length <= 1;
    });
}
</script>
@endpush
