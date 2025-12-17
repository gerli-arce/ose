{{-- 
Ejemplo de uso del componente de autocompletado de RUC
Para formulario de empresa o contacto
--}}

<div class="mb-3">
    <label class="form-label">RUC <span class="text-danger">*</span></label>
    
    @include('components.document-autocomplete', [
        'inputId' => 'tax_id',
        'documentType' => 'RUC',
        'value' => $company->tax_id ?? '',
        'targetFields' => [
            'business_name' => 'business_name',
            'trade_name' => 'trade_name',
            'address' => 'address',
            'ubigeo' => 'ubigeo',
            'department' => 'department',
            'province' => 'province',
            'district' => 'district'
        ]
    ])
</div>

<div class="mb-3">
    <label class="form-label">Razón Social <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="business_name" 
           value="{{ old('business_name', $company->business_name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Nombre Comercial</label>
    <input type="text" class="form-control" name="trade_name" 
           value="{{ old('trade_name', $company->trade_name ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Dirección</label>
    <input type="text" class="form-control" name="address" 
           value="{{ old('address', $company->address ?? '') }}">
</div>

{{-- Para DNI (clientes) --}}
<div class="mb-3">
    <label class="form-label">DNI <span class="text-danger">*</span></label>
    
    @include('components.document-autocomplete', [
        'inputId' => 'tax_id',
        'documentType' => 'DNI',
        'value' => $contact->tax_id ?? '',
        'targetFields' => [
            'name' => 'name'
        ]
    ])
</div>

<div class="mb-3">
    <label class="form-label">Nombre Completo <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="name" 
           value="{{ old('name', $contact->name ?? '') }}" required>
</div>
