@extends('layout.master')

@section('title', 'Editar Producto')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
             <div class="col-6">
                <h3>Editar Producto</h3>
            </div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Productos</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Editando: {{ $product->name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Código (SKU) <span class="text-danger">*</span></label>
                                        <input class="form-control" name="code" type="text" required value="{{ $product->code }}">
                                        @error('code') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Código de Barras</label>
                                        <input class="form-control" name="barcode" type="text" value="{{ $product->barcode }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                                        <input class="form-control" name="name" type="text" required value="{{ $product->name }}">
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Categoría</label>
                                        <select class="form-select" name="product_category_id">
                                            <option value="">Seleccione...</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ $product->product_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Unidad Medida <span class="text-danger">*</span></label>
                                        <select class="form-select" name="unit_id" required>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ $product->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }} ({{ $unit->symbol }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3 pt-4">
                                        <div class="form-check">
                                            <input class="form-check-input" id="is_service" name="is_service" type="checkbox" {{ $product->is_service ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_service">Es un Servicio</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Descripción</label>
                                        <textarea class="form-control" name="description" rows="3">{{ $product->description }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="mb-4 fw-bold text-dark border-bottom pb-2">Precios e Imagen</h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-dark">Precio Venta (Inc. IGV)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">S/</span>
                                                <input class="form-control" name="sale_price" type="number" step="0.01" value="{{ $product->sale_price }}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-dark">Costo Referencial</label>
                                            <div class="input-group">
                                                <span class="input-group-text">S/</span>
                                                <input class="form-control" name="cost_price" type="number" step="0.01" value="{{ $product->cost_price }}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-dark">Imagen</label>
                                            <div id="dropZone" class="mb-3 text-center p-3 border rounded bg-white position-relative" 
                                                 style="min-height: 200px; display: flex; align-items: center; justify-content: center; cursor: pointer; border-style: dashed !important; border-width: 2px !important; border-color: #dee2e6 !important;">
                                                
                                                <div id="placeholderText" class="{{ $product->image_path ? 'd-none' : '' }}">
                                                    <i class="fa fa-cloud-upload text-primary mb-2" style="font-size: 2rem;"></i>
                                                    <div class="small fw-bold text-dark">Click, Arrastrar o Pegar (Ctrl+V)</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">Soporta: JPG, PNG, WEBP</div>
                                                </div>

                                                <img id="imagePreview" src="{{ $product->image_path ? asset($product->image_path) : '' }}" 
                                                     class="img-fluid rounded {{ !$product->image_path ? 'd-none' : '' }}" 
                                                     style="max-height: 180px; width: auto;">
                                                     
                                                <input class="d-none" name="image" id="imageInput" type="file" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" id="active" name="active" type="checkbox" role="switch" {{ $product->active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active">Activo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer text-end">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button class="btn btn-primary" type="submit">Actualizar Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const dropZone = document.getElementById('dropZone');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const placeholderText = document.getElementById('placeholderText');

    // Click to upload
    dropZone.addEventListener('click', () => imageInput.click());

    // File selection
    imageInput.addEventListener('change', handleFileSelect);

    // Drag & Drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#007bff';
        dropZone.style.backgroundColor = '#f8f9fa';
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#dee2e6';
        dropZone.style.backgroundColor = '#fff';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#dee2e6';
        dropZone.style.backgroundColor = '#fff';
        
        if (e.dataTransfer.files.length > 0) {
            imageInput.files = e.dataTransfer.files;
            handleFileSelect({ target: imageInput });
        }
    });

    // Paste from Clipboard
    document.addEventListener('paste', (e) => {
        const items = e.clipboardData.items;
        for (let i = 0; i < items.length; i++) {
            if (items[i].type.indexOf('image') !== -1) {
                const blob = items[i].getAsFile();
                
                // Create a File object from Blob to set to Input
                const file = new File([blob], "pasted_image.png", { type: blob.type });
                
                // Set to input (using DataTransfer)
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                imageInput.files = dataTransfer.files;
                
                handleFileSelect({ target: imageInput });
                break;
            }
        }
    });

    function handleFileSelect(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                imagePreview.src = ev.target.result;
                imagePreview.classList.remove('d-none');
                placeholderText.classList.add('d-none');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
