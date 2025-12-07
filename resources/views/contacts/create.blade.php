@extends('layout.master')

@section('title', 'Nuevo Contacto')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
             <div class="col-6">
                <h3>Nuevo Contacto</h3>
            </div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contacts.index') }}">Contactos</a></li>
                    <li class="breadcrumb-item active">Crear</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Información del Contacto</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('contacts.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Tipo y Documento -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tipo de Contacto <span class="text-danger">*</span></label>
                                <select class="form-select" name="type" required>
                                    <option value="customer">Cliente</option>
                                    <option value="supplier">Proveedor</option>
                                    <option value="both">Ambos (Cliente/Proveedor)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Número de Documento (RUC/DNI) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input class="form-control" name="tax_id" type="text" required placeholder="Ej: 20600000001">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-search-dni"><i class="fa fa-search"></i> Reniec/Sunat</button>
                                </div>
                                 @error('tax_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4 mb-3 pt-4">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" id="active" name="active" type="checkbox" role="switch" checked>
                                    <label class="form-check-label" for="active">Contacto Activo</label>
                                </div>
                            </div>

                            <hr>

                            <!-- Datos Principales -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Razón Social / Nombre Completo <span class="text-danger">*</span></label>
                                <input class="form-control" name="name" type="text" required placeholder="Nombre legal">
                                <small class="text-muted">Nombre que aparecerá en comprobantes.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre Comercial</label>
                                <input class="form-control" name="business_name" type="text" placeholder="Marca o nombre comercial">
                            </div>

                            <!-- Datos de Contacto -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input class="form-control" name="email" type="email" placeholder="contacto@empresa.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono / Celular</label>
                                <input class="form-control" name="phone" type="text" placeholder="999888777">
                            </div>
                             <div class="col-md-12 mb-3">
                                <label class="form-label">Dirección Fiscal / Entrega</label>
                                <input class="form-control" name="address" type="text" placeholder="Av. Principal 123, Distrito, Ciudad">
                            </div>

                            <hr>
                            
                            <!-- Datos Comerciales -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Días de Crédito (Payment Terms)</label>
                                <input class="form-control" name="payment_terms" type="number" value="0">
                                <small class="text-muted">0 para contado.</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Límite de Crédito</label>
                                <input class="form-control" name="credit_limit" type="number" step="0.01" placeholder="0.00">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Observaciones</label>
                                <textarea class="form-control" name="observations" rows="2"></textarea>
                            </div>

                        </div>
                        
                        <div class="card-footer text-end">
                            <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button class="btn btn-primary" type="submit">Guardar Contacto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
