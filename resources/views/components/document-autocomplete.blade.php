{{--
Componente de autocompletado de RUC/DNI
Uso:
@include('components.document-autocomplete', [
    'inputId' => 'tax_id',
    'documentType' => 'RUC', // RUC o DNI
    'targetFields' => [
        'business_name' => 'business_name',
        'trade_name' => 'trade_name',
        'address' => 'address',
        'ubigeo' => 'ubigeo'
    ]
])
--}}

@php
    $inputId = $inputId ?? 'tax_id';
    $documentType = $documentType ?? 'RUC';
    $targetFields = $targetFields ?? [];
    $buttonClass = $buttonClass ?? 'btn-primary';
@endphp

<div class="input-group">
    <input type="text" 
           class="form-control" 
           id="{{ $inputId }}" 
           name="{{ $inputId }}"
           placeholder="{{ $documentType === 'RUC' ? '20123456789' : '12345678' }}"
           maxlength="{{ $documentType === 'RUC' ? 11 : 8 }}"
           pattern="[0-9]*"
           value="{{ old($inputId, $value ?? '') }}">
    <button type="button" 
            class="btn {{ $buttonClass }} btn-query-document" 
            data-input-id="{{ $inputId }}"
            data-document-type="{{ $documentType }}"
            data-target-fields='@json($targetFields)'>
        <i class="fa fa-search"></i> Consultar {{ $documentType }}
    </button>
</div>
<small class="text-muted query-status" id="{{ $inputId }}_status"></small>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initDocumentQuery();
});

function initDocumentQuery() {
    document.querySelectorAll('.btn-query-document').forEach(btn => {
        btn.addEventListener('click', function() {
            const inputId = this.dataset.inputId;
            const documentType = this.dataset.documentType;
            const targetFields = JSON.parse(this.dataset.targetFields || '{}');
            const input = document.getElementById(inputId);
            const statusEl = document.getElementById(inputId + '_status');
            
            if (!input || !input.value) {
                Swal.fire('Error', `Ingrese el ${documentType}`, 'error');
                return;
            }

            const documentNumber = input.value.trim();
            
            // Validar longitud
            const expectedLength = documentType === 'RUC' ? 11 : 8;
            if (documentNumber.length !== expectedLength) {
                Swal.fire('Error', `El ${documentType} debe tener ${expectedLength} dígitos`, 'error');
                return;
            }

            // Deshabilitar botón
            this.disabled = true;
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Consultando...';
            
            if (statusEl) {
                statusEl.textContent = 'Consultando...';
                statusEl.className = 'text-muted query-status';
            }

            // Consultar API
            const endpoint = documentType === 'RUC' 
                ? `/api/documents/query-ruc?ruc=${documentNumber}`
                : `/api/documents/query-dni?dni=${documentNumber}`;

            fetch(endpoint)
                .then(r => r.json())
                .then(res => {
                    this.disabled = false;
                    this.innerHTML = originalHtml;

                    if (res.success) {
                        // Autocompletar campos
                        Object.keys(targetFields).forEach(key => {
                            const fieldName = targetFields[key];
                            const field = document.querySelector(`[name="${fieldName}"]`);
                            
                            if (field && res.data[key]) {
                                field.value = res.data[key];
                                
                                // Trigger change event
                                field.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        });

                        if (statusEl) {
                            statusEl.textContent = `✓ Datos obtenidos de ${res.data.source || 'API'}`;
                            statusEl.className = 'text-success query-status';
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Datos obtenidos',
                            text: documentType === 'RUC' 
                                ? res.data.business_name 
                                : res.data.name,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        if (statusEl) {
                            statusEl.textContent = '✗ ' + res.message;
                            statusEl.className = 'text-danger query-status';
                        }

                        Swal.fire('Error', res.message, 'error');
                    }
                })
                .catch(err => {
                    this.disabled = false;
                    this.innerHTML = originalHtml;
                    
                    if (statusEl) {
                        statusEl.textContent = '✗ Error de conexión';
                        statusEl.className = 'text-danger query-status';
                    }

                    Swal.fire('Error', 'Error al consultar. Intente nuevamente.', 'error');
                    console.error('Error:', err);
                });
        });
    });
}
</script>
@endpush
