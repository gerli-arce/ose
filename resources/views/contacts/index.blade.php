@extends('layout.master')

@section('title', 'Directorio de Contactos')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Contactos</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Contactos</li>
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
                    <h5>Lista de Contactos</h5>
                    <a href="{{ route('contacts.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo Contacto</a>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('contacts.index') }}" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>Todos los tipos</option>
                                <option value="customer" {{ request('type') == 'customer' ? 'selected' : '' }}>Clientes</option>
                                <option value="supplier" {{ request('type') == 'supplier' ? 'selected' : '' }}>Proveedores</option>
                            </select>
                        </div>
                         <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>Todos los estados</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, RUC..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fa fa-search"></i> Filtrar</button>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre / Razón Social</th>
                                    <th>Tipo</th>
                                    <th>Documento</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $contact->business_name ?: $contact->name }}</span>
                                            @if($contact->business_name && $contact->name)
                                                <small class="text-muted">{{ $contact->name }}</small> <!-- Contact person if needed -->
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($contact->type == 'customer') <span class="badge bg-success">Cliente</span>
                                        @elseif($contact->type == 'supplier') <span class="badge bg-info">Proveedor</span>
                                        @else <span class="badge bg-warning">Ambos</span>
                                        @endif
                                    </td>
                                    <td>{{ $contact->tax_id }}</td>
                                    <td>{{ $contact->phone ?? '-' }}</td>
                                    <td>
                                        @if($contact->active) <span class="text-success"><i class="fa fa-check-circle"></i> Activo</span>
                                        @else <span class="text-danger"><i class="fa fa-times-circle"></i> Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-xs btn-info" title="Ver Detalle"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-xs btn-primary ms-1" title="Editar"><i class="fa fa-pencil"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No se encontraron contactos.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="d-flex justify-content-center mt-3">
                        {{ $contacts->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
