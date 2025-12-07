@extends('layout.master')

@section('title', 'Detalle de Contacto')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Detalle de Contacto</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contacts.index') }}">Contactos</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-xl-4 col-md-12 box-col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between">
                    <h5 class="mb-0">Perfil</h5>
                    <a href="{{ route('contacts.edit', $contact->id) }}"><i data-feather="edit"></i></a>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avtar avtar-xl bg-light-primary rounded-circle d-flex justify-content-center align-items-center mx-auto mb-3" style="width: 80px; height: 80px;">
                            <span class="fs-1 fw-bold text-primary">{{ substr($contact->name, 0, 1) }}</span>
                        </div>
                        <h5 class="mb-1">{{ $contact->business_name ?: $contact->name }}</h5>
                        <p class="text-muted mb-0">{{ $contact->tax_id }}</p>
                        <span class="badge {{ $contact->active ? 'bg-success' : 'bg-danger' }} mt-2">{{ $contact->active ? 'ACTIVO' : 'INACTIVO' }}</span>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <strong>Tipo:</strong> 
                            <span class="float-end text-uppercase">{{ $contact->type }}</span>
                        </div>
                        <div class="list-group-item px-0">
                            <strong>Email:</strong> 
                            <span class="float-end">{{ $contact->email ?? '-' }}</span>
                        </div>
                        <div class="list-group-item px-0">
                            <strong>Teléfono:</strong> 
                            <span class="float-end">{{ $contact->phone ?? '-' }}</span>
                        </div>
                        <div class="list-group-item px-0">
                            <strong>Dirección:</strong> 
                            <p class="mb-0 text-end small text-muted">{{ $contact->address ?? 'Sin dirección' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header pb-0">
                    <h5 class="mb-0">Datos Comerciales</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                         <div class="list-group-item px-0">
                            <strong>Límite Crédito:</strong> 
                            <span class="float-end">{{ number_format($contact->credit_limit, 2) }}</span>
                        </div>
                        <div class="list-group-item px-0">
                            <strong>Días Crédito:</strong> 
                            <span class="float-end">{{ $contact->payment_terms }} días</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents / History -->
        <div class="col-xl-8 col-md-12 box-col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs border-tab" id="info-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="history-tab" data-bs-toggle="tab" href="#history" role="tab">Historial de Documentos</a></li>
                        <li class="nav-item"><a class="nav-link" id="account-tab" data-bs-toggle="tab" href="#account" role="tab">Estado de Cuenta</a></li>
                    </ul>
                    <div class="tab-content" id="info-tabContent">
                        <!-- History Tab -->
                        <div class="tab-pane fade show active" id="history" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Comprobante</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                No hay documentos registrados aún. (Módulo de Ventas Pendiente)
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Account Status Tab -->
                        <div class="tab-pane fade" id="account" role="tabpanel">
                            <div class="row mt-4 mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-light-danger border-0">
                                        <div class="card-body">
                                            <h6 class="mb-2">Deuda Pendiente</h6>
                                            <h3>S/ 0.00</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h6 class="mb-3">Documentos Pendientes de Pago</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Emisión</th>
                                        <th>Vencimiento</th>
                                        <th>Documento</th>
                                        <th>Total</th>
                                        <th>Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Sin deuda pendiente.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
