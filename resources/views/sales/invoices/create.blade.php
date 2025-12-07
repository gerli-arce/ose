@extends('layout.master')

@section('title', 'Nueva Venta')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Emitir Comprobante</h3>
            </div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sales.documents.index') }}">Ventas</a></li>
                    <li class="breadcrumb-item active">Nuevo</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" id="sales-app">
    <!-- Vue/JS App wrapper -->
    <div class="row">
        <!-- Main Form -->
        <div class="col-md-9">
             <div class="card">
                <div class="card-body">
                    <form id="invoiceForm">
                        @csrf
                        <!-- Row 1: Header -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Tipo Documento</label>
                                <select class="form-select" name="document_type_id" id="document_type_id">
                                    @foreach($documentTypes as $type)
                                        <option value="{{ $type->id }}" data-code="{{ $type->code }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Serie</label>
                                <select class="form-select" name="series" id="series">
                                    @foreach($series as $s)
                                        <option value="{{ $s->prefix }}" data-type="{{ $s->document_type_id }}">{{ $s->prefix }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Emisión</label>
                                <input type="date" class="form-control" name="issue_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cliente</label>
                                <div class="input-group">
                                    <!-- Search mechanism simplified. Ideally Select2 ajax -->
                                    <select class="form-select" name="customer_id" required>
                                        <option value="">Seleccione Cliente...</option>
                                        @foreach(\App\Models\Contact::where('company_id', session('current_company_id'))->get() as $contact)
                                            <option value="{{ $contact->id }}">{{ $contact->name }} ({{ $contact->tax_id }})</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Row 2: Items -->
                        <h6 class="mb-3">Detalle de Productos</h6>
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered" id="itemsTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th width="10%">Cant.</th>
                                        <th width="15%">P. Unit</th>
                                        <th width="15%">Total</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    <!-- Rows added via JS -->
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mb-4">
                            <!-- Helper to add items -->
                            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#productModal">
                                <i class="fa fa-search"></i> Agregar Producto
                            </button>
                        </div>
                        
                        <div class="row justify-content-end">
                            <div class="col-md-4">
                                <table class="table table-sm text-end">
                                    <tr>
                                        <td><strong>Op. Gravada:</strong></td>
                                        <td>S/ <span id="subtotalDisplay">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>IGV (18%):</strong></td>
                                        <td>S/ <span id="igvDisplay">0.00</span></td>
                                    </tr>
                                    <tr class="table-active">
                                        <td><strong class="h5">TOTAL:</strong></td>
                                        <td><strong class="h5">S/ <span id="totalDisplay">0.00</span></strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="card-footer text-end mt-3">
                            <input type="hidden" name="subtotal" id="inputSubtotal">
                            <input type="hidden" name="total_igv" id="inputIgv">
                            <input type="hidden" name="total" id="inputTotal">
                            
                            <div class="form-check form-check-inline mb-3">
                                <input class="form-check-input" type="checkbox" id="sendSunat" name="send_to_sunat" checked>
                                <label class="form-check-label" for="sendSunat">Enviar a SUNAT ahora</label>
                            </div>
                            <br>
                            <a href="{{ route('sales.documents.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="button" class="btn btn-primary" onclick="submitInvoice()">Emitir Comprobante</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Info -->
        <div class="col-md-3">
            <div class="card bg-info-light">
                <div class="card-body">
                    <h6>Información</h6>
                    <p class="small text-muted">Asegúrese de seleccionar la serie correcta para su establecimiento.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Search Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buscar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3" id="productSearch" placeholder="Buscar por nombre o código..." onkeyup="filterProducts()">
                <div class="table-responsive" style="max-height: 300px;">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="productList">
                            @foreach(\App\Models\Product::where('company_id', session('current_company_id'))->where('active', true)->get() as $p)
                            <tr class="product-row" data-search="{{ strtolower($p->name . ' ' . $p->code) }}">
                                <td>{{ $p->code }}</td>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->sale_price }}</td>
                                <td>{{ $p->is_service ? '-' : $p->stocks->sum('quantity') }}</td>
                                <td>
                                    <button class="btn btn-xs btn-success" 
                                        onclick="addItem('{{ $p->id }}', '{{ $p->code }}', '{{ addslashes($p->name) }}', {{ $p->sale_price }})">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let items = [];

    function addItem(id, code, name, price) {
        items.push({
            product_id: id,
            code: code,
            description: name,
            quantity: 1,
            unit_price: parseFloat(price),
            total: parseFloat(price)
        });
        renderItems();
        var modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
        modal.hide();
    }

    function removeItem(index) {
        items.splice(index, 1);
        renderItems();
    }

    function updateItem(index, field, value) {
        if (field === 'quantity') items[index].quantity = parseFloat(value);
        if (field === 'unit_price') items[index].unit_price = parseFloat(value);
        
        items[index].total = items[index].quantity * items[index].unit_price;
        renderItems();
    }

    function renderItems() {
        const tbody = document.getElementById('itemsBody');
        tbody.innerHTML = '';
        
        let subtotal = 0;

        items.forEach((item, index) => {
            subtotal += item.total;
            
            const tr = `
                <tr>
                    <td><small>${item.code}</small><br>${item.description}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm" value="${item.quantity}" min="0.1" step="0.1" 
                            onchange="updateItem(${index}, 'quantity', this.value)">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" value="${item.unit_price.toFixed(2)}" step="0.01" 
                            onchange="updateItem(${index}, 'unit_price', this.value)">
                    </td>
                    <td class="text-end">${item.total.toFixed(2)}</td>
                    <td><button type="button" class="btn btn-xs btn-danger" onclick="removeItem(${index})"><i class="fa fa-times"></i></button></td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', tr);
        });

        // Totals (Simplified: All Price Include IGV)
        // If Price Inc IGV: Base = Total / 1.18, IGV = Total - Base
        const total = subtotal;
        const base = total / 1.18;
        const igv = total - base;

        document.getElementById('subtotalDisplay').innerText = base.toFixed(2);
        document.getElementById('igvDisplay').innerText = igv.toFixed(2);
        document.getElementById('totalDisplay').innerText = total.toFixed(2);
        
        document.getElementById('inputSubtotal').value = base.toFixed(2);
        document.getElementById('inputIgv').value = igv.toFixed(2);
        document.getElementById('inputTotal').value = total.toFixed(2);
    }

    function filterProducts() {
        const term = document.getElementById('productSearch').value.toLowerCase();
        const rows = document.querySelectorAll('.product-row');
        rows.forEach(row => {
            const txt = row.getAttribute('data-search');
            row.style.display = txt.includes(term) ? '' : 'none';
        });
    }

    function submitInvoice() {
        if (items.length === 0) {
            alert('Agregue al menos un producto.');
            return;
        }

        const data = {
            _token: '{{ csrf_token() }}',
            document_type_id: document.getElementById('document_type_id').value,
            series: document.getElementById('series').value,
            issue_date: document.querySelector('[name=issue_date]').value,
            customer_id: document.querySelector('[name=customer_id]').value,
            subtotal: document.getElementById('inputSubtotal').value,
            total_igv: document.getElementById('inputIgv').value,
            total: document.getElementById('inputTotal').value,
            send_to_sunat: document.getElementById('sendSunat').checked ? 1 : 0,
            items: items.map(i => ({
                product_id: i.product_id,
                quantity: i.quantity,
                unit_price: i.unit_price,
                total: i.total,
                igv: (i.total - (i.total/1.18)),
                code: i.code,
                description: i.description
            }))
        };

        fetch('{{ route('sales.documents.store') }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                window.location.href = res.redirect;
            } else {
                alert('Error: ' + res.message);
            }
        })
        .catch(err => alert('Error de red'));
    }
</script>
@endsection
