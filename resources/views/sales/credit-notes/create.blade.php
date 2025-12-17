@extends('layout.master')

@section('title', 'Nueva Nota de Crédito')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Emitir Nota de Crédito</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.documents.index') }}">Ventas</a></li>
                    <li class="breadcrumb-item active">Nueva NC</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Formulario Principal -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fa fa-file-invoice me-2"></i>
                        Nota de Crédito Electrónica
                    </h5>
                </div>
                <div class="card-body">
                    <form id="creditNoteForm">
                        @csrf
                        <input type="hidden" name="related_document_id" value="{{ $relatedDocument->id }}">

                        <!-- Documento Afectado -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">
                                <i class="fa fa-info-circle me-1"></i> Documento a Afectar
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Documento:</strong><br>
                                    <span class="badge bg-primary fs-6">
                                        {{ $relatedDocument->documentType->name }} {{ $relatedDocument->full_number }}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Cliente:</strong><br>
                                    {{ $relatedDocument->customer->name }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Total Original:</strong><br>
                                    <span class="h5 text-primary">S/ {{ number_format($relatedDocument->total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cabecera NC -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Serie NC</label>
                                <select class="form-select" name="series_id" id="series_id" required>
                                    @foreach($series as $s)
                                        <option value="{{ $s->id }}">{{ $s->prefix }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Emisión</label>
                                <input type="date" class="form-control" name="issue_date" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Nota de Crédito <span class="text-danger">*</span></label>
                                <select class="form-select" name="credit_note_type_id" id="credit_note_type_id" required>
                                    <option value="">Seleccione motivo...</option>
                                    @foreach($creditNoteTypes as $type)
                                        <option value="{{ $type->id }}" 
                                                data-affects-stock="{{ $type->affects_stock ? '1' : '0' }}">
                                            {{ $type->code }} - {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Motivo / Sustento <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="note_reason" rows="2" 
                                      placeholder="Describa el motivo de la nota de crédito..." required></textarea>
                        </div>

                        <hr>

                        <!-- Items -->
                        <h6 class="mb-3">
                            <i class="fa fa-list me-1"></i> Detalle de Items
                            <small class="text-muted">(Seleccione los items a incluir en la NC)</small>
                        </h6>

                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-sm" id="itemsTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%">
                                            <input type="checkbox" id="selectAll" checked onclick="toggleAll()">
                                        </th>
                                        <th>Producto</th>
                                        <th width="12%">Cant. Orig.</th>
                                        <th width="12%">Cant. NC</th>
                                        <th width="12%">P. Unit</th>
                                        <th width="12%">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    @foreach($relatedDocument->items as $index => $item)
                                    <tr data-index="{{ $index }}">
                                        <td class="text-center">
                                            <input type="checkbox" class="item-check" 
                                                   data-index="{{ $index }}" checked 
                                                   onchange="updateTotals()">
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $item->code }}</small><br>
                                            {{ $item->description }}
                                            <input type="hidden" class="item-product-id" value="{{ $item->product_id }}">
                                            <input type="hidden" class="item-code" value="{{ $item->code }}">
                                            <input type="hidden" class="item-description" value="{{ $item->description }}">
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ number_format($item->quantity, 2) }}</span>
                                            <input type="hidden" class="item-original-qty" value="{{ $item->quantity }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm item-qty" 
                                                   value="{{ $item->quantity }}" 
                                                   min="0.01" max="{{ $item->quantity }}" step="0.01"
                                                   onchange="updateItemTotal({{ $index }})">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm item-price" 
                                                   value="{{ number_format($item->unit_price, 2, '.', '') }}" 
                                                   step="0.01" readonly>
                                        </td>
                                        <td class="text-end">
                                            <strong class="item-total">{{ number_format($item->total, 2) }}</strong>
                                            <input type="hidden" class="item-total-value" value="{{ $item->total }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Totales -->
                        <div class="row justify-content-end">
                            <div class="col-md-5">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Op. Gravada:</strong></td>
                                        <td class="text-end">S/ <span id="subtotalDisplay">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>IGV (18%):</strong></td>
                                        <td class="text-end">S/ <span id="igvDisplay">0.00</span></td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><strong class="h5">TOTAL NC:</strong></td>
                                        <td class="text-end">
                                            <strong class="h5 text-danger">S/ <span id="totalDisplay">0.00</span></strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Hidden inputs -->
                        <input type="hidden" name="subtotal" id="inputSubtotal">
                        <input type="hidden" name="total_igv" id="inputIgv">
                        <input type="hidden" name="total" id="inputTotal">

                        <!-- Acciones -->
                        <div class="card-footer text-end mt-3">
                            <div class="form-check form-check-inline mb-3">
                                <input class="form-check-input" type="checkbox" id="sendSunat" name="send_to_sunat" checked>
                                <label class="form-check-label" for="sendSunat">Enviar a SUNAT ahora</label>
                            </div>
                            <br>
                            <a href="{{ route('sales.documents.show', $relatedDocument->id) }}" class="btn btn-secondary">
                                <i class="fa fa-times me-1"></i> Cancelar
                            </a>
                            <button type="button" class="btn btn-warning" onclick="submitCreditNote()">
                                <i class="fa fa-paper-plane me-1"></i> Emitir Nota de Crédito
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="col-md-4">
            <!-- Info del tipo de NC -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa fa-info-circle me-1"></i> Información</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">
                        La Nota de Crédito se emitirá para anular o modificar el documento original.
                    </p>
                    <div id="stockWarning" class="alert alert-warning d-none">
                        <i class="fa fa-exclamation-triangle me-1"></i>
                        <strong>Afecta inventario:</strong> Los productos seleccionados se reingresarán al stock.
                    </div>
                </div>
            </div>

            <!-- Historial de NC anteriores -->
            @if($relatedDocument->creditNotes->count() > 0)
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fa fa-history me-1"></i> NC Anteriores
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($relatedDocument->creditNotes as $nc)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $nc->full_number }}</span>
                            <span class="text-danger">- S/ {{ number_format($nc->total, 2) }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Inicializar totales al cargar
    document.addEventListener('DOMContentLoaded', function() {
        updateTotals();
        
        // Mostrar/ocultar warning de stock según tipo NC
        document.getElementById('credit_note_type_id').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const affectsStock = option.dataset.affectsStock === '1';
            document.getElementById('stockWarning').classList.toggle('d-none', !affectsStock);
        });
    });

    function toggleAll() {
        const selectAll = document.getElementById('selectAll').checked;
        document.querySelectorAll('.item-check').forEach(cb => {
            cb.checked = selectAll;
        });
        updateTotals();
    }

    function updateItemTotal(index) {
        const row = document.querySelector(`tr[data-index="${index}"]`);
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const total = qty * price;
        
        row.querySelector('.item-total').innerText = total.toFixed(2);
        row.querySelector('.item-total-value').value = total.toFixed(2);
        
        updateTotals();
    }

    function updateTotals() {
        let grandTotal = 0;
        
        document.querySelectorAll('#itemsBody tr').forEach(row => {
            const checked = row.querySelector('.item-check').checked;
            const total = parseFloat(row.querySelector('.item-total-value').value) || 0;
            
            if (checked) {
                grandTotal += total;
            }
            
            // Visual feedback
            row.classList.toggle('table-active', checked);
            row.querySelector('.item-qty').disabled = !checked;
        });

        // Calcular base e IGV (precio incluye IGV)
        const base = grandTotal / 1.18;
        const igv = grandTotal - base;

        document.getElementById('subtotalDisplay').innerText = base.toFixed(2);
        document.getElementById('igvDisplay').innerText = igv.toFixed(2);
        document.getElementById('totalDisplay').innerText = grandTotal.toFixed(2);
        
        document.getElementById('inputSubtotal').value = base.toFixed(2);
        document.getElementById('inputIgv').value = igv.toFixed(2);
        document.getElementById('inputTotal').value = grandTotal.toFixed(2);
    }

    function submitCreditNote() {
        // Validaciones
        if (!document.getElementById('credit_note_type_id').value) {
            Swal.fire('Error', 'Seleccione el tipo de nota de crédito.', 'error');
            return;
        }

        if (!document.querySelector('[name=note_reason]').value.trim()) {
            Swal.fire('Error', 'Ingrese el motivo de la nota de crédito.', 'error');
            return;
        }

        const selectedItems = [];
        document.querySelectorAll('#itemsBody tr').forEach(row => {
            if (row.querySelector('.item-check').checked) {
                const qty = row.querySelector('.item-qty').value;
                const price = row.querySelector('.item-price').value;
                const total = parseFloat(row.querySelector('.item-total-value').value);
                
                selectedItems.push({
                    product_id: row.querySelector('.item-product-id').value,
                    code: row.querySelector('.item-code').value,
                    description: row.querySelector('.item-description').value,
                    quantity: parseFloat(qty),
                    unit_price: parseFloat(price),
                    total: total,
                    igv: total - (total / 1.18)
                });
            }
        });

        if (selectedItems.length === 0) {
            Swal.fire('Error', 'Seleccione al menos un item para la nota de crédito.', 'error');
            return;
        }

        const data = {
            _token: '{{ csrf_token() }}',
            related_document_id: '{{ $relatedDocument->id }}',
            credit_note_type_id: document.getElementById('credit_note_type_id').value,
            series_id: document.getElementById('series_id').value,
            issue_date: document.querySelector('[name=issue_date]').value,
            note_reason: document.querySelector('[name=note_reason]').value,
            subtotal: document.getElementById('inputSubtotal').value,
            total_igv: document.getElementById('inputIgv').value,
            total: document.getElementById('inputTotal').value,
            send_to_sunat: document.getElementById('sendSunat').checked ? 1 : 0,
            items: selectedItems
        };

        // Confirmación
        Swal.fire({
            title: '¿Emitir Nota de Crédito?',
            html: `Se emitirá una NC por <strong>S/ ${data.total}</strong>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f0ad4e',
            confirmButtonText: 'Sí, emitir NC',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("sales.credit-notes.store") }}', {
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
                        Swal.fire('Éxito', res.message, 'success').then(() => {
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
