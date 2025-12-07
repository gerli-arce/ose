@extends('layout.master')

@section('title', 'Registrar Compra')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single { height: 38px; border-color: #ced4da; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 38px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 38px; }
    </style>
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Registrar Compra</h3>
            </div>
             <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Compras</li>
                    <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Listado</a></li>
                    <li class="breadcrumb-item active"> Nuevo</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <form id="purchaseForm" method="POST" action="{{ route('purchases.store') }}">
        @csrf
        
        <!-- General Data -->
        <div class="card">
            <div class="card-header pb-2">
                <h5>Datos de la Compra</h5>
            </div>
            <div class="card-body pt-2">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Proveedor</label>
                        <select class="form-select select2" name="supplier_id" required>
                            <option value="">Buscar Proveedor...</option>
                             @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->tax_id }} - {{ $supplier->business_name ?? $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                         <label class="form-label">Almacén de Destino (Ingreso)</label>
                        <select class="form-select" name="warehouse_id" required>
                             @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha de Emisión</label>
                        <input class="form-control" name="issue_date" type="date" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tipo Documento</label>
                        <select class="form-select" name="document_type_id" required>
                            @foreach($documentTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Serie</label>
                        <input class="form-control" name="series" type="text" placeholder="F001" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Número</label>
                        <input class="form-control" name="number" type="text" placeholder="0000123" required>
                    </div>
                     <div class="col-md-3">
                        <label class="form-label">Moneda</label>
                        <select class="form-select" name="currency_id" required>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="card">
            <div class="card-header pb-2 d-flex justify-content-between align-items-center">
                <h5>Detalle de Productos / Servicios</h5>
            </div>
            <div class="card-body pt-2 p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="itemsTable">
                        <thead class="bg-light">
                            <tr>
                                <th width="40%">Producto</th>
                                <th width="15%">Cantidad</th>
                                <th width="15%">Costo Unit.</th>
                                <th width="15%">Total</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Rows added via JS --}}
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">
                                    <button type="button" class="btn btn-light btn-sm w-100" id="btnAddRow"><i data-feather="plus"></i> Agregar Fila</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-end fw-bold">Subtotal:</td>
                                <td class="text-end"><span id="summary_subtotal">0.00</span></td>
                            </tr>
                            <tr>
                                <td class="text-end fw-bold">IGV (18%):</td>
                                <td class="text-end"><span id="summary_tax">0.00</span></td>
                            </tr>
                            <tr>
                                <td class="text-end fw-bold fs-5">Total:</td>
                                <td class="text-end fs-5"><span id="summary_total">0.00</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

         <div class="card">
             <div class="card-footer text-end">
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Registrar Compra</button>
            </div>
        </div>
    </form>
</div>

<template id="rowTemplate">
    <tr>
        <td>
            <select class="form-control product-select" name="items[INDEX][product_id]" required></select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm item-quantity" name="items[INDEX][quantity]" value="1" min="0.01" step="0.01" required>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm item-price" name="items[INDEX][unit_price]" value="0.00" min="0.01" step="0.01" required>
        </td>
        <td class="text-end">
            <span class="item-total">0.00</span>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-xs btn-danger btn-remove"><i data-feather="trash-2"></i></button>
        </td>
    </tr>
</template>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });

        let rowIndex = 0;

        function addRow() {
            const template = document.getElementById('rowTemplate').innerHTML;
            const newRowHtml = template.replace(/INDEX/g, rowIndex);
            $('#itemsTable tbody').append(newRowHtml);
            
            const newRow = $('#itemsTable tbody tr').last();
            initRow(newRow);
            
            rowIndex++;
            feather.replace();
        }

        function initRow($row) {
             $row.find('.product-select').select2({
                placeholder: 'Buscar producto...',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route("invoices.search.products") }}', // Reuse search endpoint
                    dataType: 'json',
                    delay: 250,
                    data: function (params) { return { term: params.term }; },
                    processResults: function (data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name + ' (' + item.code + ')',
                                    cost: item.cost_price || 0
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

             $row.find('.product-select').on('select2:select', function (e) {
                const data = e.params.data;
                $row.find('.item-price').val(data.cost);
                calculateRow($row);
            });

            $row.find('.btn-remove').click(function() {
                $row.remove();
                calculateTotal();
            });

            $row.find('.item-quantity, .item-price').on('input', function() {
                calculateRow($row);
            });
        }

        function calculateRow($row) {
            const qty = parseFloat($row.find('.item-quantity').val()) || 0;
            const price = parseFloat($row.find('.item-price').val()) || 0;
            const total = qty * price;
            $row.find('.item-total').text(total.toFixed(2));
            calculateTotal();
        }

        function calculateTotal() {
            let totalTaxable = 0;
            $('#itemsTable tbody tr').each(function() {
                const qty = parseFloat($(this).find('.item-quantity').val()) || 0;
                const price = parseFloat($(this).find('.item-price').val()) || 0;
                totalTaxable += (qty * price);
            });

            const igvInfo = totalTaxable * 0.18;
            const total = totalTaxable + igvInfo;

            $('#summary_subtotal').text(totalTaxable.toFixed(2));
            $('#summary_tax').text(igvInfo.toFixed(2));
            $('#summary_total').text(total.toFixed(2));
        }

        addRow();
        $('#btnAddRow').click(addRow);
    });
</script>
@endsection
