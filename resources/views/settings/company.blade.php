@extends('layout.master')

@section('title', 'Configuración de Empresa')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Configuración de Empresa</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item active">Empresa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <form action="{{ route('settings.company.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Datos de la Empresa -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title mb-0">Perfil de Empresa</h4>
                            <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-auto">
                                    @if(isset($config['logo_path']))
                                        <img class="img-70 rounded-circle" alt="" src="{{ asset($config['logo_path']) }}">
                                    @else
                                        <img class="img-70 rounded-circle" alt="" src="{{ asset('assets/images/dashboard/1.png') }}">
                                    @endif
                                </div>
                                <div class="col">
                                    <h3 class="mb-1">{{ $company->name }}</h3>
                                    <p class="mb-4">{{ $company->trade_name ?? 'Nombre Comercial' }}</p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cargar Logo</label>
                                <input class="form-control" type="file" name="logo" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Razón Social</label>
                                <input class="form-control" name="name" value="{{ $company->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombre Comercial</label>
                                <input class="form-control" name="trade_name" value="{{ $company->trade_name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">RUC / ID Tributario</label>
                                <input class="form-control" name="tax_id" value="{{ $company->tax_id }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email de Contacto</label>
                                <input class="form-control" name="email" value="{{ $company->email }}" type="email">
                            </div>
                             <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input class="form-control" name="phone" value="{{ $company->phone }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dirección Fiscal</label>
                                <textarea class="form-control" name="address" rows="3">{{ $company->address_id ?? '' }}</textarea> <!-- Using address_id column as text for now as per controller logic -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuraciones -->
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h4 class="card-title mb-0">Configuración General</h4>
                            <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Moneda por Defecto</label>
                                    <select class="form-select" name="default_currency">
                                        <option value="PEN" {{ ($config['default_currency'] ?? 'PEN') == 'PEN' ? 'selected' : '' }}>Soles (PEN)</option>
                                        <option value="USD" {{ ($config['default_currency'] ?? '') == 'USD' ? 'selected' : '' }}>Dólares (USD)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Régimen Fiscal</label>
                                    <select class="form-select" name="fiscal_regime">
                                        <option value="General" {{ ($config['fiscal_regime'] ?? '') == 'General' ? 'selected' : '' }}>Régimen General</option>
                                        <option value="Mype" {{ ($config['fiscal_regime'] ?? '') == 'Mype' ? 'selected' : '' }}>Mype Tributario</option>
                                        <option value="Especial" {{ ($config['fiscal_regime'] ?? '') == 'Especial' ? 'selected' : '' }}>Régimen Especial</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">IGV (%)</label>
                                    <input class="form-control" type="number" name="igv_percent" value="{{ $config['igv_percent'] ?? 18 }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Entorno Facturación Electrónica</label>
                                    <select class="form-select" name="electronic_env">
                                        <option value="beta" {{ ($config['electronic_env'] ?? 'beta') == 'beta' ? 'selected' : '' }}>Beta / Pruebas</option>
                                        <option value="production" {{ ($config['electronic_env'] ?? '') == 'production' ? 'selected' : '' }}>Producción</option>
                                    </select>
                                </div>
                                
                                <div class="col-12 mt-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" id="invoice_auto_numbering" name="invoice_auto_numbering" type="checkbox" role="switch" {{ ($config['invoice_auto_numbering'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="invoice_auto_numbering">Numeración Automática de Comprobantes</label>
                                    </div>
                                    <p class="text-muted small">Si se desactiva, deberá ingresar el correlativo manualmente.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-primary" type="submit">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
