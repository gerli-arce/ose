@extends('layout.master')

@section('title', 'Gestionar Planes')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6"><h3>Planes SaaS</h3></div>
            <div class="col-6 text-end">
                <a href="{{ route('saas.plans.create') }}" class="btn btn-primary">Crear Plan</a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        @foreach($plans as $plan)
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="text-white">{{ $plan->name }}</h4>
                    <h2 class="text-white mt-2">S/ {{ number_format($plan->price_monthly, 2) }} <small class="fs-6">/mes</small></h2>
                </div>
                <div class="card-body">
                    <p>{{ $plan->description }}</p>
                    <hr>
                    <ul class="list-group list-group-flush">
                        @php
                            $labels = [
                                'max_users' => 'Usuarios Permitidos',
                                'max_invoices' => 'Facturas por Mes',
                                'storage_gb' => 'Almacenamiento (GB)'
                            ];
                        @endphp
                        @foreach($plan->features as $feature)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $labels[$feature->key] ?? $feature->key }}
                            <span class="badge bg-secondary rounded-pill">{{ $feature->value }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('saas.plans.edit', $plan->id) }}" class="btn btn-outline-primary">Editar</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
