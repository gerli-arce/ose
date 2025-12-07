@extends('layout.master')

@section('title', 'Compras y Gastos')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Compras y Gastos</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Compras</li>
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
                    <h5>Listado de Compras</h5>
                    <a href="{{ route('purchases.documents.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Registrar Compra</a>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-2">
                             <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                             <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="supplier_id" class="form-select">
                                <option value="">Todos los Proveedores</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                             <button type="submit" class="btn btn-primary w-100"><i class="fa fa-search"></i> Filtrar</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Documento</th>
                                    <th>Moneda</th>
                                    <th class="text-end">Total</th>
                                    <th>Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $pus)
                                <tr>
                                    <td>{{ $pus->issue_date ? $pus->issue_date : '-' }}</td>
                                    <td>{{ $pus->supplier->name ?? 'Varios' }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $pus->documentType->short_name ?? $pus->documentType->name }}</span>
                                        <br>
                                        {{ $pus->series }}-{{ $pus->number }}
                                    </td>
                                    <td>{{ $pus->currency->code ?? 'PEN' }}</td>
                                    <td class="text-end font-weight-bold">{{ number_format($pus->total, 2) }}</td>
                                    <td>
                                        @if($pus->status == 'paid') <span class="badge bg-success">Pagado</span>
                                        @elseif($pus->status == 'partial') <span class="badge bg-warning">Parcial</span>
                                        @elseif($pus->status == 'registered') <span class="badge bg-primary">Registrado</span>
                                        @else <span class="badge bg-secondary">{{ $pus->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('purchases.documents.show', $pus->id) }}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No se encontraron compras.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="d-flex justify-content-center mt-3">
                        {{ $purchases->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
