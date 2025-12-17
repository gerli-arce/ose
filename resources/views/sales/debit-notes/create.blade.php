@extends('layout.master')

@section('title', 'Nueva Nota de Débito')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Nueva Nota de Débito</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.debit-notes.index') }}">Notas de Débito</a></li>
                    <li class="breadcrumb-item active">Nueva</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <form id="debitNoteForm">
        @csrf
        <input type="hidden" name="related_document_id" value="{{ $originalDocument->id }}">

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fa fa-plus-circle me-2"></i>
                            Nota de Débito - Cargo Adicional
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Documento Original -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">
                                <i class="fa fa-file-text me-1"></i> Documento de Referencia
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Tipo:</strong> {{ $originalDocument->documentType->name ?? 'N/A' }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Número:</strong>
                                    <span class="badge bg-dark">
                                        {{ $originalDocument->series->prefix ?? '' }}-{{ str_pad($originalDocument->number, 8, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Total:</strong> S/ {{ number_format($originalDocument->total, 2) }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <strong>Cliente:</strong> {{ $originalDocument->customer->name ?? 'N/A' }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Fecha:</strong> {{ $originalDocument->issue_date->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Tipo y Motivo -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Tipo de Nota de Débito</strong> <span class="text-danger">*</span></label>
                                <select class="form-select" name="debit_note_type_id" id="debitNoteType" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($debitNoteTypes as $type)
                                        <option value="{{ $type->id }}" data-name="{{ $type->name }}">
                                            {{ $type->code }} - {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Motivo del Cargo</strong> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="note_reason" id="noteReason" 
                                       placeholder="Descripción del motivo..." required maxlength="500">
                            </div>
                        </div>

                        <!-- Items de Cargo -->
                        <h6 class="mb-3">
                            <i class="fa fa-list me-1"></i> Conceptos a Cargar
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50%">Descripción</th>
                                        <th style="width: 15%">Cantidad</th>
                                        <th style="width: 20%">Precio Unit.</th>
                                        <th style="width: 15%" class="text-end">Subtotal</th>
                                        <th style="width: 40px"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    <tr class="item-row">
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="items[0][description]" placeholder="Descripción del cargo" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm item-qty" 
                                                   name="items[0][quantity]" value="1" min="0.01" step="0.01" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm item-price" 
                                                   name="items[0][unit_price]" value="0.00" min="0.01" step="0.01" required>
                                        </td>
                                        <td class="text-end item-subtotal">0.00</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn-remove-item" disabled>
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
                                                <i class="fa fa-plus me-1"></i> Agregar Concepto
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Opciones -->
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="sendSunat" name="send_to_sunat" checked>
                            <label class="form-check-label" for="sendSunat">
                                Enviar a SUNAT inmediatamente
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral - Totales -->
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 80px">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="fa fa-calculator me-1"></i> Totales del Cargo</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td>Subtotal:</td>
                                <td class="text-end" id="totalSubtotal">S/ 0.00</td>
                            </tr>
                            <tr>
                                <td>IGV (18%):</td>
                                <td class="text-end" id="totalIgv">S/ 0.00</td>
                            </tr>
                            <tr class="border-top">
                                <td><strong>Total Cargo:</strong></td>
                                <td class="text-end h4 text-danger" id="totalAmount">S/ 0.00</td>
                            </tr>
                        </table>

                        <hr>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-danger" id="submitBtn" onclick="submitForm()">
                                <i class="fa fa-paper-plane me-1"></i> Emitir Nota de Débito
                            </button>
                            <a href="{{ route('sales.documents.show', $originalDocument->id) }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left me-1"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
let itemIndex = 1;

document.addEventListener('DOMContentLoaded', function() {
    // Auto-completar motivo según tipo seleccionado
    document.getElementById('debitNoteType').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (selected.dataset.name) {
            document.getElementById('noteReason').value = selected.dataset.name;
        }
    });

    // Calcular al cambiar cantidades o precios
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price')) {
            calculateTotals();
        }
    });

    // Agregar item
    document.getElementById('addItemBtn').addEventListener('click', addItem);

    // Eliminar item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-item') || e.target.closest('.btn-remove-item')) {
            const btn = e.target.classList.contains('btn-remove-item') ? e.target : e.target.closest('.btn-remove-item');
            if (!btn.disabled) {
                btn.closest('tr').remove();
                updateRemoveButtons();
                calculateTotals();
            }
        }
    });
});

function addItem() {
    const tbody = document.getElementById('itemsBody');
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td>
            <input type="text" class="form-control form-control-sm" 
                   name="items[${itemIndex}][description]" placeholder="Descripción del cargo" required>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm item-qty" 
                   name="items[${itemIndex}][quantity]" value="1" min="0.01" step="0.01" required>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm item-price" 
                   name="items[${itemIndex}][unit_price]" value="0.00" min="0.01" step="0.01" required>
        </td>
        <td class="text-end item-subtotal">0.00</td>
        <td>
            <button type="button" class="btn btn-sm btn-danger btn-remove-item">
                <i class="fa fa-times"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    itemIndex++;
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.item-row');
    rows.forEach((row, index) => {
        const btn = row.querySelector('.btn-remove-item');
        btn.disabled = rows.length === 1;
    });
}

function calculateTotals() {
    let subtotal = 0;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const itemSubtotal = qty * price;
        
        row.querySelector('.item-subtotal').textContent = itemSubtotal.toFixed(2);
        subtotal += itemSubtotal;
    });

    const igv = subtotal * 0.18;
    const total = subtotal + igv;

    document.getElementById('totalSubtotal').textContent = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('totalIgv').textContent = 'S/ ' + igv.toFixed(2);
    document.getElementById('totalAmount').textContent = 'S/ ' + total.toFixed(2);
}

function submitForm() {
    const form = document.getElementById('debitNoteForm');
    const formData = new FormData(form);
    
    // Validar
    if (!formData.get('debit_note_type_id')) {
        Swal.fire('Error', 'Seleccione el tipo de nota de débito.', 'error');
        return;
    }
    if (!formData.get('note_reason')) {
        Swal.fire('Error', 'Ingrese el motivo del cargo.', 'error');
        return;
    }

    // Construir data
    const items = [];
    document.querySelectorAll('.item-row').forEach((row, i) => {
        items.push({
            description: row.querySelector('[name*="description"]').value,
            quantity: parseFloat(row.querySelector('.item-qty').value) || 0,
            unit_price: parseFloat(row.querySelector('.item-price').value) || 0
        });
    });

    const data = {
        _token: '{{ csrf_token() }}',
        related_document_id: '{{ $originalDocument->id }}',
        debit_note_type_id: formData.get('debit_note_type_id'),
        note_reason: formData.get('note_reason'),
        send_to_sunat: document.getElementById('sendSunat').checked ? 1 : 0,
        items: items
    };

    Swal.fire({
        title: '¿Emitir Nota de Débito?',
        text: 'Se generará un cargo adicional al documento de referencia.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Sí, emitir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("sales.debit-notes.store") }}', {
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
                        title: 'Nota de Débito Emitida',
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
    });
}
</script>
@endsection
