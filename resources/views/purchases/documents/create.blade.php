@extends('layout.master')

@section('title', 'Registrar Compra')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Registrar Compra (Gastos/Ingresos)</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('purchases.documents.index') }}">Compras</a></li>
                    <li class="breadcrumb-item active">Nuevo</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('purchases.documents.store') }}" method="POST" id="purchaseForm">
                @csrf
                <!-- 1. Header -->
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Datos del Documento</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Proveedor</label>
                                <select class="form-select select2" name="supplier_id" required>
                                    <option value="">Seleccione Proveedor</option>
                                    @foreach($suppliers as $sup)
                                        <option value="{{ $sup->id }}">{{ $sup->name }} ({{ $sup->tax_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tipo Doc.</label>
                                <select class="form-select" name="document_type_id" required>
                                    @foreach($documentTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Serie</label>
                                <input type="text" class="form-control" name="series" placeholder="F001" required>
                            </div>
                             <div class="col-md-2">
                                <label class="form-label">Número</label>
                                <input type="text" class="form-control" name="number" placeholder="000123" required>
                            </div>
                             <div class="col-md-2">
                                <label class="form-label">Moneda</label>
                                <select class="form-select" name="currency_id" required>
                                    @foreach($currencies as $curr)
                                        <option value="{{ $curr->id }}">{{ $curr->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Emisión</label>
                                <input type="date" class="form-control" name="issue_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                             <div class="col-md-3">
                                <label class="form-label">Fecha Vencimiento</label>
                                <input type="date" class="form-control" name="due_date" value="{{ date('Y-m-d') }}">
                            </div>
                             <div class="col-md-6">
                                <div class="form-check checkbox checkbox-primary mt-4">
                                    <input class="form-check-input" id="update_stock" name="update_stock" type="checkbox" value="1">
                                    <label class="form-check-label" for="update_stock">Afectar Inventario (Ingresar Mercadería)</label>
                                </div>
                            </div>
                             <div class="col-md-4 d-none" id="warehouse_container">
                                <label class="form-label">Almacén Destino</label>
                                <select class="form-select" name="warehouse_id">
                                     <option value="">Seleccione Almacén</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Items -->
                <div class="card">
                     <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h5>Ítems</h5>
                        <button type="button" class="btn btn-secondary btn-sm" id="btn-add-row"><i class="fa fa-plus"></i> Agregar</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th width="40%">Producto</th>
                                        <th width="15%">Cantidad</th>
                                        <th width="15%">Costo Unit.</th>
                                        <th width="20%">Total</th>
                                        <th width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Rows added via JS -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                        <td class="text-end fw-bold" id="subtotalDisplay">0.00</td>
                                        <td></td>
                                    </tr>
                                     <tr>
                                        <td colspan="3" class="text-end fw-bold">IGV (18%):</td>
                                        <td class="text-end fw-bold" id="taxDisplay">0.00</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td class="text-end fw-bold" id="totalDisplay">0.00</td>
                                        <input type="hidden" name="total" id="totalInput">
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                         <div class="mt-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observations" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('purchases.documents.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Registrar Compra</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Product Search Modal for Purchases? Or use Select2 AJAX? 
     For simplicity in Phase 1, we use a simple Select2 on the row or a predefined list if products are few. 
     Given we have 'products.search' route? No, we have 'invoices.search.products'. 
     Let's reuse that or build a simple fetch.
-->
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemsTable = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
        const btnAddRow = document.getElementById('btn-add-row');
        const updateStockCheckbox = document.getElementById('update_stock');
        const warehouseContainer = document.getElementById('warehouse_container');

        // Toggle Warehouse Select
        updateStockCheckbox.addEventListener('change', function() {
             if(this.checked) {
                 warehouseContainer.classList.remove('d-none');
                 document.querySelector('[name="warehouse_id"]').setAttribute('required', 'required');
             } else {
                 warehouseContainer.classList.add('d-none');
                 document.querySelector('[name="warehouse_id"]').removeAttribute('required');
                 document.querySelector('[name="warehouse_id"]').value = '';
             }
        });

        // Add Row
        btnAddRow.addEventListener('click', function() {
            addRow();
        });

        async function addRow() {
            const rowCount = itemsTable.rows.length;
            const newRow = itemsTable.insertRow();
            
            newRow.innerHTML = `
                <td>
                    <select name="items[${rowCount}][product_id]" class="form-select product-select" required onchange="updateRow(this)">
                        <option value="">Cargando productos...</option>
                    </select>
                </td>
                <td>
                    <input type="number" step="0.01" name="items[${rowCount}][quantity]" class="form-control quantity-input" value="1" oninput="calculateRow(this)" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="items[${rowCount}][unit_price]" class="form-control price-input" value="0.00" oninput="calculateRow(this)" required>
                </td>
                <td class="text-end line-total">0.00</td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-xs" onclick="removeRow(this)"><i class="fa fa-trash"></i></button></td>
            `;
            
            const select = newRow.querySelector('.product-select');
            await fetchProducts(select);
            
            // Initialize Select2 if available (check for jQuery)
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $(select).select2({
                    placeholder: "Buscar Producto",
                    width: '100%'
                });
                $(select).on('change', function() {
                    updateRow(this);
                });
            }
        }

        async function fetchProducts(selectElement) {
             try {
                 const response = await fetch('{{ route('invoices.search.products') }}?q=');
                 const products = await response.json();
                 
                 selectElement.innerHTML = '<option value="">Seleccione Producto</option>';
                 
                 products.forEach(p => {
                     const option = document.createElement('option');
                     option.value = p.id;
                     option.textContent = p.name + (p.sku ? ' (' + p.sku + ')' : '');
                     option.dataset.price = p.cost_price || 0; // Ensure model has cost_price
                     selectElement.appendChild(option);
                 });
             } catch (e) {
                 console.error("Error loading products", e);
                 selectElement.innerHTML = '<option value="">Error cargar</option>';
             }
        }
        
        // Initial Row
        addRow();
        
        window.removeRow = function(btn) {
            const row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
            calculateTotals();
        }

        window.updateRow = function(select) {
            const row = select.closest('tr');
            const priceInput = row.querySelector('.price-input');
            const selectedOption = select.options[select.selectedIndex];
            
            // For Select2, verified via DOM or using data adapter. 
            // If Select2 is used, 'this' might be the original select but DOM might be hidden.
            // But 'change' event on original select works.
            
            if(selectedOption && selectedOption.dataset.price) {
                 priceInput.value = selectedOption.dataset.price;
            }
             calculateRow(priceInput);
        }

        window.calculateRow = function(element) {
            const row = element.closest('tr');
            const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const total = qty * price;
            
            row.querySelector('.line-total').textContent = total.toFixed(2);
            calculateTotals();
        }

        function calculateTotals() {
            let subtotal = 0;
            const rows = itemsTable.rows;
            
            for(let i=0; i < rows.length; i++) {
                const totalText = rows[i].querySelector('.line-total').textContent;
                subtotal += parseFloat(totalText) || 0;
            }

            const tax = subtotal * 0.18; // IGV 18%
            const total = subtotal + tax;

            document.getElementById('subtotalDisplay').textContent = subtotal.toFixed(2);
            document.getElementById('taxDisplay').textContent = tax.toFixed(2);
            document.getElementById('totalDisplay').textContent = total.toFixed(2);
            document.getElementById('totalInput').value = total.toFixed(2);
        }
    });
</script>
@endsection
