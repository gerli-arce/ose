@extends('layout.master')

@section('title', 'Registrar Movimiento Manual')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
             <div class="col-6">
                <h3>Registrar Movimiento</h3>
            </div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('movements.index') }}">Movimientos</a></li>
                    <li class="breadcrumb-item active">Registrar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-xl-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Ajuste de Inventario</h5>
                    <span>Registre entradas o salidas manuales (por compra, merma, ajuste, etc).</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('movements.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Fecha y Hora</label>
                            <input class="form-control" name="date" type="datetime-local" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>

                        <div class="mb-3">
                             <label class="form-label">Tipo de Movimiento</label>
                             <select class="form-select" name="type" required>
                                 <option value="in">Entrada (+ Stock)</option>
                                 <option value="out">Salida (- Stock)</option>
                                 <option value="adjustment">Ajuste (Verificar lógica)</option>
                             </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Almacén Destino/Origen</label>
                            <select class="form-select" name="warehouse_id" required>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Producto</label>
                            <select class="form-select" name="product_id" required>
                                <option value="">Seleccione Producto...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cantidad</label>
                            <input class="form-control" name="quantity" type="number" step="0.01" min="0.01" required placeholder="0.00">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones / Motivo</label>
                            <textarea class="form-control" name="observations" rows="3" placeholder="Ej: Ajuste de inventario inicial"></textarea>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('movements.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button class="btn btn-primary" type="submit">Registrar Movimiento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
