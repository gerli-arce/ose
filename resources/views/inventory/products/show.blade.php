@extends('layout.master')

@section('title', 'Detalle de Producto')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>{{ $product->name }}</h3>
                <p class="text-muted text-small">{{ $product->code }}</p>
            </div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Productos</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Info Card -->
        <div class="col-xl-4 box-col-6">
            <div class="card">
                <div class="card-body">
                    <div class="product-details text-center">
                        <img src="{{ $product->image_path ? asset($product->image_path) : asset('assets/images/product/1.png') }}" class="img-fluid rounded mb-3" style="max-height: 200px;">
                        <h4>S/ {{ number_format($product->sale_price, 2) }}</h4>
                        <span>Precio de Venta</span>
                    </div>
                    <hr>
                    <div class="mt-3">
                         <p><strong>Categoría:</strong> {{ $product->category->name ?? 'Sin Categoría' }}</p>
                         <p><strong>Unidad:</strong> {{ $product->unit->name }}</p>
                         <p><strong>Tipo:</strong> {{ $product->is_service ? 'Servicio' : 'Producto Físico' }}</p>
                         <p><strong>Costo Ref:</strong> S/ {{ number_format($product->cost_price, 2) }}</p>
                         <div class="mt-3">
                            <span class="badge {{ $product->active ? 'bg-success' : 'bg-danger' }}">{{ $product->active ? 'Activo' : 'Inactivo' }}</span>
                         </div>
                    </div>
                     <div class="mt-4 text-center">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-block">Editar Producto</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock and Movements -->
        <div class="col-xl-8 box-col-6">
            <!-- Stock by Warehouse -->
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Stock por Almacén</h5>
                </div>
                <div class="card-body">
                    @if($product->is_service)
                        <div class="alert alert-info">Este es un servicio, no gestiona inventario.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Almacén</th>
                                        <th>Sucursal</th>
                                        <th class="text-end">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stockByWarehouse as $stock)
                                    <tr>
                                        <td>{{ $stock->warehouse->name }}</td>
                                        <td>{{ $stock->warehouse->branch->name ?? '-' }}</td>
                                        <td class="text-end fw-bold {{ $stock->quantity <= 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($stock->quantity, 2) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay stock registrado.</td>
                                    </tr>
                                    @endforelse
                                    <tr class="table-active">
                                        <td colspan="2" class="text-end fw-bold">TOTAL:</td>
                                        <td class="text-end fw-bold">{{ number_format($totalStock, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Movements (Placeholder for now, or simple query) -->
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between">
                    <h5>Últimos Movimientos</h5>
                     <a href="{{ route('movements.index', ['product_id' => $product->id]) }}" class="btn btn-xs btn-outline-primary">Ver Kardex Completo</a>
                </div>
                <div class="card-body">
                     <p class="text-muted text-center pt-3">
                        <a href="{{ route('movements.create') }}" class="btn btn-primary btn-sm">Registrar Ajuste Manual</a>
                     </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
