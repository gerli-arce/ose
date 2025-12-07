@extends('layout.master')

@section('title', 'Editar Plan')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <h3>Editar Plan: {{ $plan->name }}</h3>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('saas.plans.update', $plan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Plan</label>
                        <input type="text" name="name" class="form-control" value="{{ $plan->name }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Precio Mensual</label>
                        <input type="number" step="0.01" name="price_monthly" class="form-control" value="{{ $plan->price_monthly }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Precio Anual (Opcional)</label>
                        <input type="number" step="0.01" name="price_yearly" class="form-control" value="{{ $plan->price_yearly }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="description" class="form-control" rows="3">{{ $plan->description }}</textarea>
                </div>

                <h5 class="mt-4">Características (Features)</h5>
                @php
                    $features = $plan->features->pluck('value', 'key')->toArray();
                @endphp
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Usuarios Máximos</label>
                        <input type="number" name="features[max_users]" class="form-control" value="{{ $features['max_users'] ?? '' }}" placeholder="Ej: 5">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Facturas por Mes</label>
                        <input type="number" name="features[max_invoices]" class="form-control" value="{{ $features['max_invoices'] ?? '' }}" placeholder="Ej: 100">
                    </div>
                     <div class="col-md-4">
                        <label class="form-label">Almacenamiento (GB)</label>
                        <input type="number" name="features[storage_gb]" class="form-control" value="{{ $features['storage_gb'] ?? '' }}" placeholder="Ej: 1">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Plan</button>
            </form>
        </div>
    </div>
</div>
@endsection
