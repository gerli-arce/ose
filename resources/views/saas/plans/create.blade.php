@extends('layout.master')

@section('title', 'Crear Plan')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <h3>Crear Nuevo Plan</h3>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('saas.plans.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Plan</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Precio Mensual</label>
                        <input type="number" step="0.01" name="price_monthly" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Precio Anual (Opcional)</label>
                        <input type="number" step="0.01" name="price_yearly" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <h5 class="mt-4">Características (Features)</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Usuarios Máximos</label>
                        <input type="number" name="features[max_users]" class="form-control" placeholder="Ej: 5">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Facturas por Mes</label>
                        <input type="number" name="features[max_invoices]" class="form-control" placeholder="Ej: 100">
                    </div>
                     <div class="col-md-4">
                        <label class="form-label">Almacenamiento (GB)</label>
                        <input type="number" name="features[storage_gb]" class="form-control" placeholder="Ej: 1">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Plan</button>
            </form>
        </div>
    </div>
</div>
@endsection
