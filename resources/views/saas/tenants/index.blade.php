@extends('layout.master')

@section('title', 'Admin SaaS - Empresas')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Administración de Empresas (Tenants)</h3>
            </div>
             <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">SaaS</li>
                    <li class="breadcrumb-item active"> Empresas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>RUC</th>
                            <th>Usuarios</th>
                            <th>Docs Emitidos</th>
                            <th>Plan Actual</th>
                            <th>Vence</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenants as $tenant)
                            @php
                                $sub = $tenant->subscriptions->first();
                            @endphp
                            <tr>
                                <td>{{ $tenant->name }}</td>
                                <td>{{ $tenant->tax_id }}</td>
                                <td>{{ $tenant->users_count }}</td>
                                <td>{{ $tenant->invoices_count }}</td>
                                <td>
                                    @if($sub)
                                        <span class="badge badge-info">{{ $sub->plan->name }}</span>
                                    @else
                                        <span class="badge badge-secondary">Sin Plan</span>
                                    @endif
                                </td>
                                <td>{{ $sub && $sub->end_date ? $sub->end_date->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($tenant->active)
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Suspendido</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Toggle Status --}}
                                    <form action="{{ route('saas.tenants.toggle', $tenant->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-xs {{ $tenant->active ? 'btn-danger' : 'btn-success' }}" title="{{ $tenant->active ? 'Suspender' : 'Activar' }}">
                                            <i data-feather="{{ $tenant->active ? 'slash' : 'check-circle' }}"></i>
                                        </button>
                                    </form>
                                    
                                    {{-- Manage/Dashboard Link --}}
                                    <a href="{{ route('settings.company.index', ['company_id' => $tenant->id]) }}" class="btn btn-xs btn-warning" title="Administrar / Ver Dashboard">
                                        <i data-feather="settings"></i>
                                    </a>

                                    {{-- Assign Plan Modal Trigger --}}
                                    <button class="btn btn-xs btn-primary" data-bs-toggle="modal" data-bs-target="#planModal{{$tenant->id}}" title="Asignar Plan">
                                        <i data-feather="upload-cloud"></i>
                                    </button>
                                </td>
                            </tr>
                            

                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $tenants->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@foreach($tenants as $tenant)
    {{-- Modal Assign Plan --}}
    <div class="modal fade" id="planModal{{$tenant->id}}" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('saas.plan.assign') }}" method="POST">
                @csrf
                <input type="hidden" name="company_id" value="{{ $tenant->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Asignar Plan a {{ $tenant->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Seleccionar Plan</label>
                    <select name="plan_id" class="form-select">
                        @foreach(\App\Models\Plan::all() as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->name }} - S/{{ $p->price_monthly }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Asignar Plan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

@endsection
