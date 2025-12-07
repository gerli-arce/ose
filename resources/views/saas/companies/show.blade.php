@extends('layout.master')

@section('title', 'Detalle Empresa')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6"><h3>{{ $company->name }}</h3></div>
            <div class="col-6 text-end">
                <form action="{{ route('saas.companies.status', $company->id) }}" method="POST" class="d-inline">
                    @csrf
                    @if($company->active)
                        <button type="submit" class="btn btn-danger">Suspender Acceso</button>
                    @else
                        <button type="submit" class="btn btn-success">Activar Acceso</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Información</h5>
                </div>
                <div class="card-body">
                    <p><strong>RUC:</strong> {{ $company->tax_id }}</p>
                    <p><strong>Email:</strong> {{ $company->email }}</p>
                    <p><strong>Teléfono:</strong> {{ $company->phone }}</p>
                    <p><strong>Estado:</strong> {{ $company->active ? 'Activo' : 'Suspendido' }}</p>
                </div>
            </div>
        </div>

        <!-- Subscription -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Suscripción Actual</h5>
                </div>
                <div class="card-body">
                    @if($currentSubscription)
                        <h3>{{ $currentSubscription->plan->name }}</h3>
                        <p class="mb-1">Estado: <span class="badge bg-primary">{{ ucfirst($currentSubscription->status) }}</span></p>
                        <p class="mb-1">Inicio: {{ $currentSubscription->start_date }}</p>
                        <p class="mb-1">Fin: {{ $currentSubscription->end_date ?? 'Indefinido' }}</p>
                    @else
                        <p class="text-danger">No tiene suscripción activa.</p>
                    @endif
                    <hr>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#subscriptionModal">Cambiar Plan / Renovar</button>
                </div>
            </div>
        </div>

        <!-- Usage Stats -->
        <div class="col-md-4">
            <div class="card">
                 <div class="card-header">
                    <h5>Consumo (Mes Actual)</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Usuarios Activos</span>
                        <span class="fw-bold">{{ $userCount }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Facturas Emitidas</span>
                        <span class="fw-bold">{{ $invoiceCount }}</span>
                    </div>
                     <!-- Add more stats as needed -->
                </div>
            </div>
        </div>
    </div>

    <!-- Users List (Optional) -->
    <div class="card">
        <div class="card-header">
            <h5>Usuarios</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($company->users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->pivot->is_owner ? 'Owner' : 'User' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Update Subscription -->
<div class="modal fade" id="subscriptionModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('saas.subscriptions.update', $company->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Actualizar Suscripción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Plan</label>
                        <select name="plan_id" class="form-select" required>
                            @foreach($plans as $p)
                                <option value="{{ $p->id }}" {{ ($currentSubscription && $currentSubscription->plan_id == $p->id) ? 'selected' : '' }}>
                                    {{ $p->name }} - S/ {{ $p->price_monthly }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                         <label class="form-label">Fecha Inicio</label>
                         <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                     <div class="mb-3">
                         <label class="form-label">Fecha Fin (Opcional)</label>
                         <input type="date" name="end_date" class="form-control" placeholder="Dejar vacio para indefinido">
                         <small class="text-muted">Si es mensual, calcular +30 días.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                         <select name="status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="trial">Trial</option>
                            <option value="past_due">Past Due</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
