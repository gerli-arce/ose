@extends('layout.master')

@section('title', 'Empresas SaaS')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <h3>Gestión de Empresas</h3>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
             <form method="GET" class="mb-3">
                 <div class="row">
                     <div class="col-md-3">
                         <select name="status" class="form-select" onchange="this.form.submit()">
                             <option value="">Todos los estados</option>
                             <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                             <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendidos</option>
                         </select>
                     </div>
                 </div>
             </form>

             <table class="table table-hover">
                 <thead>
                     <tr>
                         <th>Empresa</th>
                         <th>RUC</th>
                         <th>Plan Actual</th>
                         <th>Estado</th>
                         <th>Acciones</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach($companies as $company)
                     <tr>
                         <td>{{ $company->name }} <br> <small class="text-muted">{{ $company->email }}</small></td>
                         <td>{{ $company->tax_id }}</td>
                         <td>
                             @if($company->subscriptions->first())
                                <span class="badge bg-info">{{ $company->subscriptions->first()->plan->name }}</span>
                             @else
                                <span class="badge bg-secondary">Sin Plan</span>
                             @endif
                         </td>
                         <td>
                             @if($company->active)
                                <span class="badge bg-success">Activo</span>
                             @else
                                <span class="badge bg-danger">Suspendido</span>
                             @endif
                         </td>
                         <td>
                             <a href="{{ route('saas.companies.show', $company->id) }}" class="btn btn-sm btn-primary">Ver Detalle</a>
                         </td>
                     </tr>
                     @endforeach
                 </tbody>
             </table>
             <div class="mt-3">
                 {{ $companies->links() }}
             </div>
        </div>
    </div>
</div>
@endsection
