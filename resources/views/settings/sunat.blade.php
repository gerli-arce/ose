@extends('layout.master')

@section('title', 'Configuración SUNAT')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Configuración SUNAT</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active">Configuración SUNAT</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Formulario de Configuración -->
        <div class="col-lg-8">
            <form action="{{ route('settings.sunat.update') }}" method="POST" enctype="multipart/form-data" id="sunatConfigForm">
                @csrf
                @method('PUT')

                <!-- Credenciales SOL -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa fa-key me-2"></i>Credenciales SOL</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            Las credenciales SOL son proporcionadas por SUNAT y se utilizan para el envío de comprobantes electrónicos.
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">RUC de la Empresa</label>
                                <input type="text" class="form-control" value="{{ $company->tax_id }}" readonly>
                                <small class="text-muted">Este RUC se usa para la autenticación</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Usuario SOL <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $company->tax_id }}</span>
                                    <input type="text" name="sunat_sol_user" class="form-control" 
                                           value="{{ old('sunat_sol_user', $company->sunat_sol_user) }}" 
                                           placeholder="MODDATOS" required>
                                </div>
                                <small class="text-muted">Ejemplo: MODDATOS</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contraseña SOL</label>
                                <div class="input-group">
                                    <input type="password" name="sunat_sol_password" class="form-control" 
                                           id="solPassword" placeholder="••••••••">
                                    <button type="button" class="btn btn-outline-secondary toggle-password" 
                                            data-target="solPassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @if($company->sunat_sol_password)
                                <small class="text-success"><i class="fa fa-check me-1"></i>Contraseña configurada</small>
                                @else
                                <small class="text-warning"><i class="fa fa-warning me-1"></i>Sin contraseña</small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ambiente <span class="text-danger">*</span></label>
                                <select name="sunat_env" class="form-select" required>
                                    <option value="beta" {{ $company->sunat_env == 'beta' ? 'selected' : '' }}>
                                        🧪 Beta / Pruebas
                                    </option>
                                    <option value="production" {{ $company->sunat_env == 'production' ? 'selected' : '' }}>
                                        🚀 Producción
                                    </option>
                                </select>
                                <small class="text-muted">Seleccione Beta para pruebas iniciales</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificado Digital -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fa fa-certificate me-2"></i>Certificado Digital</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fa fa-shield me-1"></i>
                            El certificado digital es requerido para firmar los comprobantes electrónicos. 
                            Debe ser emitido por una entidad certificadora autorizada (SUNAT, RENIEC, etc.).
                        </div>

                        @if($company->sunat_cert_path)
                        <div class="mb-4 p-3 bg-success bg-opacity-10 rounded border border-success">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-success mb-1">
                                        <i class="fa fa-file-o me-1"></i>Certificado cargado
                                    </h6>
                                    <small class="text-muted">{{ basename($company->sunat_cert_path) }}</small>
                                </div>
                                <form action="{{ route('settings.sunat.delete-certificate') }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('¿Está seguro de eliminar el certificado?')">
                                        <i class="fa fa-trash me-1"></i>Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Subir Certificado (.pfx, .p12, .pem)</label>
                                <input type="file" name="certificate" class="form-control" 
                                       accept=".pfx,.p12,.pem" id="certificateInput">
                                <small class="text-muted">Tamaño máximo: 5MB</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contraseña del Certificado</label>
                                <div class="input-group">
                                    <input type="password" name="sunat_cert_password" class="form-control" 
                                           id="certPassword" placeholder="••••••••">
                                    <button type="button" class="btn btn-outline-secondary toggle-password" 
                                            data-target="certPassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @if($company->sunat_cert_password)
                                <small class="text-success"><i class="fa fa-check me-1"></i>Contraseña configurada</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-save me-1"></i>Guardar Configuración
                    </button>
                </div>
            </form>
        </div>

        <!-- Panel de Estado -->
        <div class="col-lg-4">
            <!-- Prueba de Conexión -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa fa-plug me-2"></i>Prueba de Conexión</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Verifique que la configuración sea correcta probando la conexión con SUNAT.
                    </p>
                    <button type="button" id="testConnectionBtn" class="btn btn-outline-primary w-100 mb-3">
                        <i class="fa fa-refresh me-1"></i>Probar Conexión
                    </button>
                    
                    <div id="connectionResult" style="display: none;">
                        <!-- Resultado de la prueba -->
                    </div>
                </div>
            </div>

            <!-- Estado Actual -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa fa-info-circle me-2"></i>Estado Actual</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted">RUC:</td>
                            <td><strong>{{ $company->tax_id }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Usuario SOL:</td>
                            <td>
                                @if($company->sunat_sol_user)
                                <span class="text-success"><i class="fa fa-check me-1"></i>{{ $company->sunat_sol_user }}</span>
                                @else
                                <span class="text-danger"><i class="fa fa-times me-1"></i>No configurado</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Contraseña SOL:</td>
                            <td>
                                @if($company->sunat_sol_password)
                                <span class="text-success"><i class="fa fa-check me-1"></i>Configurada</span>
                                @else
                                <span class="text-danger"><i class="fa fa-times me-1"></i>No configurada</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Certificado:</td>
                            <td>
                                @if($company->sunat_cert_path)
                                <span class="text-success"><i class="fa fa-check me-1"></i>Cargado</span>
                                @else
                                <span class="text-danger"><i class="fa fa-times me-1"></i>No cargado</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ambiente:</td>
                            <td>
                                @if($company->sunat_env == 'production')
                                <span class="badge bg-success">Producción</span>
                                @else
                                <span class="badge bg-warning">Beta</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Información Importante -->
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fa fa-lightbulb-o me-2"></i>Información</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0 small">
                        <li class="mb-2">Las contraseñas se almacenan de forma <strong>encriptada</strong> en la base de datos.</li>
                        <li class="mb-2">El certificado debe ser válido y emitido por una entidad certificadora autorizada.</li>
                        <li class="mb-2">Para pruebas, use el ambiente <strong>Beta</strong>.</li>
                        <li class="mb-2">URL Beta: <code>https://e-beta.sunat.gob.pe</code></li>
                        <li>URL Producción: <code>https://e-factura.sunat.gob.pe</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Test connection
    document.getElementById('testConnectionBtn').addEventListener('click', function() {
        const btn = this;
        const resultDiv = document.getElementById('connectionResult');
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Verificando...';
        
        fetch('{{ route("settings.sunat.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-refresh me-1"></i>Probar Conexión';
            
            resultDiv.style.display = 'block';
            
            if (res.success) {
                let certInfo = '';
                if (res.certificate) {
                    const daysClass = res.certificate.days_remaining > 30 ? 'success' : 
                                     (res.certificate.days_remaining > 7 ? 'warning' : 'danger');
                    
                    certInfo = `
                        <div class="mt-3 p-3 bg-light rounded">
                            <h6 class="mb-2">Certificado Digital:</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr><td class="text-muted">Titular:</td><td>${res.certificate.subject}</td></tr>
                                <tr><td class="text-muted">Emisor:</td><td>${res.certificate.issuer}</td></tr>
                                <tr><td class="text-muted">Válido desde:</td><td>${res.certificate.valid_from}</td></tr>
                                <tr><td class="text-muted">Válido hasta:</td><td>${res.certificate.valid_to}</td></tr>
                                <tr>
                                    <td class="text-muted">Días restantes:</td>
                                    <td><span class="badge bg-${daysClass}">${res.certificate.days_remaining} días</span></td>
                                </tr>
                            </table>
                        </div>
                    `;
                }
                
                resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle me-1"></i>
                        <strong>¡Conexión exitosa!</strong>
                        <p class="mb-0 mt-1">${res.message}</p>
                    </div>
                    ${certInfo}
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fa fa-times-circle me-1"></i>
                        <strong>Error</strong>
                        <p class="mb-0 mt-1">${res.message}</p>
                        ${res.error ? `<code class="d-block mt-2">${res.error}</code>` : ''}
                    </div>
                `;
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-refresh me-1"></i>Probar Conexión';
            
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle me-1"></i>
                    Error de conexión. Intente nuevamente.
                </div>
            `;
        });
    });
});
</script>
@endpush
