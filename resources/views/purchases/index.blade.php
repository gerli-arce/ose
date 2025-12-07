@extends('layout.master')

@section('title', 'Compras & Gastos')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Compras & Gastos</h3>
            </div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Compras</li>
                    <li class="breadcrumb-item active"> Listado</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Documentos de Compra</h5>
                    <a href="{{ route('purchases.create') }}" class="btn btn-primary">Registrar Compra</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Documento</th>
                                    <th>Referencia</th>
                                    <th>Estado</th>
                                    <th class="text-end">Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                    <tr>
                                        <td>{{ $purchase->issue_date->format('d/m/Y') }}</td>
                                        <td>{{ $purchase->supplier->business_name ?? $purchase->supplier->name }}</td>
                                        <td>{{ $purchase->documentType->name }}</td>
                                        <td>{{ $purchase->series }}-{{ $purchase->number }}</td>
                                        <td>
                                            <span class="badge {{ $purchase->status === 'registered' ? 'badge-primary' : 'badge-secondary' }}">
                                                {{ ucfirst($purchase->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ number_format($purchase->total, 2) }}</td>
                                        <td>
                                            {{-- <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-xs btn-info"><i data-feather="eye"></i></a> --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No hay compras registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-3">
                        {{ $purchases->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
