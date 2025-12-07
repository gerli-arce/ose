@extends('layout.master')

@section('title', 'Inventario de Productos')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Productos y Servicios</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Productos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between">
                    <h5>Listado de Productos</h5>
                    <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo Producto</a>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <select name="category_id" class="form-select">
                                <option value="">Todas las Categorías</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                         <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="">Todos los Tipos</option>
                                <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>Productos (Bien)</option>
                                <option value="service" {{ request('type') == 'service' ? 'selected' : '' }}>Servicios</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o código..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                             <button type="submit" class="btn btn-primary w-100"><i class="fa fa-search"></i> Filtrar</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Tipo</th>
                                    <th>Precio Venta</th>
                                    <th>Stock Total</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>
                                        <img src="{{ $product->image_path ? asset($product->image_path) : asset('assets/images/product/1.png') }}" alt="" class="img-fluid" style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td>{{ $product->code }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $product->name }}</span>
                                    </td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td>
                                        @if($product->is_service) <span class="badge bg-info">Servicio</span>
                                        @else <span class="badge bg-success">Bien</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($product->sale_price, 2) }}</td>
                                    <td>
                                        @if(!$product->is_service)
                                            <span class="badge bg-secondary">{{ number_format($product->stocks->sum('quantity'), 2) }} {{ $product->unit->symbol ?? '' }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">No se encontraron productos.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="d-flex justify-content-center mt-3">
                        {{ $products->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
