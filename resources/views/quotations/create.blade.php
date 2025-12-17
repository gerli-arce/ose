@extends('layout.master')

@section('title', 'Nueva Cotización')

@push('styles')
<style>
    .item-row { background: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 10px; }
    .item-row:hover { background: #e9ecef; }
    .btn-remove-item { opacity: 0.6; }
    .btn-remove-item:hover { opacity: 1; }
    .totals-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; }
</style>
@endpush

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Nueva Cotización</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">Cotizaciones</a></li>
                    <li class="breadcrumb-item active">Nueva</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('quotations.store') }}" method="POST" id="quotationForm">
    @csrf
    <div class="container-fluid">
        <div class="row">
            <!-- Columna Izquierda: Datos Generales -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-file-text-o me-2"></i>Datos de la Cotización</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Número</label>
                                <div class="input-group">
                                    <span class="input-group-text">COT-</span>
                                    <input type="text" class="form-control" value="{{ str_pad($nextNumber, 6, '0', STR_PAD_LEFT) }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Fecha de Emisión <span class="text-danger">*</span></label>
                                <input type="date" name="issue_date" class="form-control" 
                                       value="{{ old('issue_date', date('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Validez (días) <span class="text-danger">*</span></label>
                                <input type="number" name="validity_days" class="form-control" 
                                       value="{{ old('validity_days', 15) }}" min="1" max="365" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Moneda <span class="text-danger">*</span></label>
                                <select name="currency_id" class="form-select" required>
                                    @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ $currency->code == 'PEN' ? 'selected' : '' }}>
                                        {{ $currency->code }} - {{ $currency->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Cliente <span class="text-danger">*</span></label>
                                <select name="customer_id" class="form-select" required id="customerSelect">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->tax_id }} - {{ $customer->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fa fa-list me-2"></i>Productos / Servicios</h5>
                        <button type="button" class="btn btn-success btn-sm" id="addItemBtn">
                            <i class="fa fa-plus me-1"></i>Agregar Item
                        </button>
                    </div>
                    <div class="card-body" id="itemsContainer">
                        <!-- Items dinámicos -->
                    </div>
                </div>

                <!-- Notas y Términos -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-sticky-note me-2"></i>Notas y Términos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Notas (visible para el cliente)</label>
                                <textarea name="notes" class="form-control" rows="3" 
                                          placeholder="Notas adicionales...">{{ old('notes') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Términos y Condiciones</label>
                                <textarea name="terms" class="form-control" rows="3" 
                                          placeholder="Términos y condiciones...">{{ old('terms', 'Precios sujetos a variación sin previo aviso. Cotización válida por el período indicado.') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Totales y Acciones -->
            <div class="col-lg-4">
                <div class="card mb-4 totals-section">
                    <div class="card-body">
                        <h5 class="text-white mb-4">Resumen</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong id="subtotalDisplay">S/ 0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Descuento:</span>
                            <strong id="discountDisplay">S/ 0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>IGV (18%):</span>
                            <strong id="taxDisplay">S/ 0.00</strong>
                        </div>
                        <hr class="bg-white">
                        <div class="d-flex justify-content-between">
                            <span class="fs-5">TOTAL:</span>
                            <strong class="fs-4" id="totalDisplay">S/ 0.00</strong>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save me-2"></i>Guardar Cotización
                            </button>
                            <a href="{{ route('quotations.index') }}" class="btn btn-outline-secondary">
                                <i class="fa fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Template para item -->
<template id="itemTemplate">
    <div class="item-row" data-index="__INDEX__">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Producto</label>
                <select name="items[__INDEX__][product_id]" class="form-select product-select">
                    <option value="">-- Personalizado --</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->sale_price ?? $product->price }}">
                        {{ $product->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Descripción <span class="text-danger">*</span></label>
                <input type="text" name="items[__INDEX__][description]" class="form-control item-description" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Cantidad <span class="text-danger">*</span></label>
                <input type="number" name="items[__INDEX__][quantity]" class="form-control item-qty" 
                       value="1" min="0.0001" step="0.0001" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Precio Unit. <span class="text-danger">*</span></label>
                <input type="number" name="items[__INDEX__][unit_price]" class="form-control item-price" 
                       value="0" min="0" step="0.0001" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Desc. %</label>
                <input type="number" name="items[__INDEX__][discount_percent]" class="form-control item-discount" 
                       value="0" min="0" max="100" step="0.01">
            </div>
            <div class="col-md-2">
                <label class="form-label">Subtotal</label>
                <input type="text" class="form-control item-subtotal" readonly>
            </div>
            <div class="col-md-2">
                <label class="form-label">Total</label>
                <input type="text" class="form-control item-total fw-bold" readonly>
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-outline-danger btn-remove-item">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('itemsContainer');
    const template = document.getElementById('itemTemplate');
    const addBtn = document.getElementById('addItemBtn');
    let itemIndex = 0;

    function addItem() {
        const html = template.innerHTML.replace(/__INDEX__/g, itemIndex);
        const div = document.createElement('div');
        div.innerHTML = html;
        container.appendChild(div.firstElementChild);
        
        setupItemEvents(container.lastElementChild);
        itemIndex++;
        calculateTotals();
    }

    function setupItemEvents(row) {
        const productSelect = row.querySelector('.product-select');
        const descInput = row.querySelector('.item-description');
        const priceInput = row.querySelector('.item-price');
        const qtyInput = row.querySelector('.item-qty');
        const discountInput = row.querySelector('.item-discount');
        const removeBtn = row.querySelector('.btn-remove-item');

        productSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option.value) {
                descInput.value = option.text;
                priceInput.value = option.dataset.price || 0;
            }
            calculateItemTotal(row);
            calculateTotals();
        });

        [qtyInput, priceInput, discountInput].forEach(input => {
            input.addEventListener('input', () => {
                calculateItemTotal(row);
                calculateTotals();
            });
        });

        removeBtn.addEventListener('click', () => {
            if (container.children.length > 1) {
                row.remove();
                calculateTotals();
            } else {
                alert('Debe haber al menos un item');
            }
        });
    }

    function calculateItemTotal(row) {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const discountPct = parseFloat(row.querySelector('.item-discount').value) || 0;

        const gross = qty * price;
        const discount = gross * (discountPct / 100);
        const subtotal = gross - discount;
        const tax = subtotal * 0.18;
        const total = subtotal + tax;

        row.querySelector('.item-subtotal').value = subtotal.toFixed(2);
        row.querySelector('.item-total').value = total.toFixed(2);
    }

    function calculateTotals() {
        let subtotal = 0;
        let discount = 0;
        let tax = 0;
        let total = 0;

        container.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const discountPct = parseFloat(row.querySelector('.item-discount').value) || 0;

            const gross = qty * price;
            const itemDiscount = gross * (discountPct / 100);
            const itemSubtotal = gross - itemDiscount;
            const itemTax = itemSubtotal * 0.18;
            const itemTotal = itemSubtotal + itemTax;

            subtotal += itemSubtotal;
            discount += itemDiscount;
            tax += itemTax;
            total += itemTotal;
        });

        document.getElementById('subtotalDisplay').textContent = 'S/ ' + subtotal.toFixed(2);
        document.getElementById('discountDisplay').textContent = 'S/ ' + discount.toFixed(2);
        document.getElementById('taxDisplay').textContent = 'S/ ' + tax.toFixed(2);
        document.getElementById('totalDisplay').textContent = 'S/ ' + total.toFixed(2);
    }

    addBtn.addEventListener('click', addItem);

    // Agregar primer item
    addItem();
});
</script>
@endpush
