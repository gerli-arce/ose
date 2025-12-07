@extends('layout.master')

@section('title', 'Kardex / Movimientos')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Kardex de Inventario</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Movimientos</li>
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
                    <h5>Historial de Movimientos</h5>
                    <a href="{{ route('movements.create') }}" class="btn btn-primary"><i class="fa fa-exchange"></i> Registrar Movimiento Manual</a>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Almacén</label>
                            <select name="warehouse_id" class="form-select">
                                <option value="">Todos</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Producto</label>
                            <select name="product_id" class="form-select">
                                <option value="">Todos</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }} ({{ $product->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                             <button type="submit" class="btn btn-primary w-100"><i class="fa fa-search"></i> Filtrar</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Almacén</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Obs</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movements as $movement)
                                <tr>
                                    <td>{{ $movement->date->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($movement->type == 'in') <span class="badge bg-success">ENTRADA</span>
                                        @elseif($movement->type == 'out') <span class="badge bg-danger">SALIDA</span>
                                        @elseif($movement->type == 'adjustment') <span class="badge bg-warning text-dark">AJUSTE</span>
                                        @else <span class="badge bg-secondary">{{ strtoupper($movement->type) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $movement->warehouse->name }}</td>
                                    <td>{{ $movement->product->name }} <small class="text-muted">({{ $movement->product->code }})</small></td>
                                    <td class="fw-bold">{{ number_format($movement->quantity, 2) }}</td>
                                    <td>{{ Str::limit($movement->observations, 30) }}</td>
                                    <td>-</td> <!-- Needs User relation in model if tracking who did it -->
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No se encontraron movimientos.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="d-flex justify-content-center mt-3">
                        {{ $movements->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
