@extends('layout.master')

@section('title', 'Nuevo Producto')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
             <div class="col-6">
                <h3>Nuevo Producto</h3>
            </div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Productos</a></li>
                    <li class="breadcrumb-item active">Crear</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Código (SKU) <span class="text-danger">*</span></label>
                                        <input class="form-control" name="code" type="text" required placeholder="Ej: PROD001">
                                        @error('code') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Código de Barras</label>
                                        <input class="form-control" name="barcode" type="text">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                                        <input class="form-control" name="name" type="text" required placeholder="Ej: Router WiFi TP-LINK">
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Categoría</label>
                                        <select class="form-select" name="product_category_id">
                                            <option value="">Seleccione...</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Unidad Medida <span class="text-danger">*</span></label>
                                        <select class="form-select" name="unit_id" required>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ $unit->code == 'NIU' ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->symbol }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3 pt-4">
                                        <div class="form-check">
                                            <input class="form-check-input" id="is_service" name="is_service" type="checkbox">
                                            <label class="form-check-label" for="is_service">Es un Servicio (No usa stock)</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Descripción</label>
                                        <textarea class="form-control" name="description" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="mb-3">Precios e Imagen</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Precio Venta (Inc. IGV)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">S/</span>
                                                <input class="form-control" name="sale_price" type="number" step="0.01" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Costo Referencial</label>
                                            <div class="input-group">
                                                <span class="input-group-text">S/</span>
                                                <input class="form-control" name="cost_price" type="number" step="0.01" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Imagen</label>
                                            <input class="form-control" name="image" type="file" accept="image/*">
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" id="active" name="active" type="checkbox" role="switch" checked>
                                            <label class="form-check-label" for="active">Activo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer text-end">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button class="btn btn-primary" type="submit">Guardar Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
