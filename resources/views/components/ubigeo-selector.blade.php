{{--
Componente de selectores encadenados de ubigeo
Uso:
@include('components.ubigeo-selector', [
    'selectedUbigeoId' => $company->ubigeo_id ?? null,
    'namePrefix' => 'company',
    'required' => true
])
--}}

@php
    $namePrefix = $namePrefix ?? '';
    $required = $required ?? false;
    $selectedUbigeo = null;
    
    if (isset($selectedUbigeoId) && $selectedUbigeoId) {
        $selectedUbigeo = \App\Models\Ubigeo::find($selectedUbigeoId);
    }
    
    $selectedDepartment = $selectedUbigeo?->getDepartment();
    $selectedProvince = $selectedUbigeo?->getProvince();
@endphp

<div class="ubigeo-selector" data-prefix="{{ $namePrefix }}">
    <div class="row">
        <!-- Departamento -->
        <div class="col-md-4 mb-3">
            <label class="form-label">
                Departamento
                @if($required) <span class="text-danger">*</span> @endif
            </label>
            <select class="form-select ubigeo-department" 
                    name="{{ $namePrefix }}[department_code]"
                    {{ $required ? 'required' : '' }}>
                <option value="">Seleccione...</option>
                @foreach(\App\Models\Ubigeo::getDepartments() as $dept)
                    <option value="{{ $dept->department_code }}" 
                            {{ $selectedDepartment && $selectedDepartment->id == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Provincia -->
        <div class="col-md-4 mb-3">
            <label class="form-label">
                Provincia
                @if($required) <span class="text-danger">*</span> @endif
            </label>
            <select class="form-select ubigeo-province" 
                    name="{{ $namePrefix }}[province_code]"
                    {{ $required ? 'required' : '' }}
                    disabled>
                <option value="">Seleccione departamento...</option>
                @if($selectedProvince)
                    @foreach(\App\Models\Ubigeo::getProvincesByDepartment($selectedDepartment->department_code) as $prov)
                        <option value="{{ $prov->province_code }}" 
                                {{ $selectedProvince->id == $prov->id ? 'selected' : '' }}>
                            {{ $prov->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Distrito -->
        <div class="col-md-4 mb-3">
            <label class="form-label">
                Distrito
                @if($required) <span class="text-danger">*</span> @endif
            </label>
            <select class="form-select ubigeo-district" 
                    name="{{ $namePrefix }}[ubigeo_id]"
                    {{ $required ? 'required' : '' }}
                    disabled>
                <option value="">Seleccione provincia...</option>
                @if($selectedUbigeo && $selectedUbigeo->isDistrict())
                    @foreach(\App\Models\Ubigeo::getDistrictsByProvince($selectedProvince->province_code) as $dist)
                        <option value="{{ $dist->id }}" 
                                data-code="{{ $dist->code }}"
                                {{ $selectedUbigeo->id == $dist->id ? 'selected' : '' }}>
                            {{ $dist->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <!-- Campo oculto para el código completo -->
    <input type="hidden" name="{{ $namePrefix }}[ubigeo]" class="ubigeo-code" 
           value="{{ $selectedUbigeo?->code ?? '' }}">
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initUbigeoSelectors();
});

function initUbigeoSelectors() {
    document.querySelectorAll('.ubigeo-selector').forEach(container => {
        const prefix = container.dataset.prefix;
        const deptSelect = container.querySelector('.ubigeo-department');
        const provSelect = container.querySelector('.ubigeo-province');
        const distSelect = container.querySelector('.ubigeo-district');
        const codeInput = container.querySelector('.ubigeo-code');

        // Cambio de departamento
        deptSelect.addEventListener('change', function() {
            const deptCode = this.value;
            
            // Limpiar provincia y distrito
            provSelect.innerHTML = '<option value="">Cargando...</option>';
            provSelect.disabled = true;
            distSelect.innerHTML = '<option value="">Seleccione provincia...</option>';
            distSelect.disabled = true;
            codeInput.value = '';

            if (!deptCode) {
                provSelect.innerHTML = '<option value="">Seleccione departamento...</option>';
                return;
            }

            // Cargar provincias
            fetch(`/api/ubigeos/provinces?department_code=${deptCode}`)
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        provSelect.innerHTML = '<option value="">Seleccione...</option>';
                        res.data.forEach(prov => {
                            const option = document.createElement('option');
                            option.value = prov.code;
                            option.textContent = prov.name;
                            provSelect.appendChild(option);
                        });
                        provSelect.disabled = false;
                    }
                })
                .catch(err => {
                    console.error('Error al cargar provincias:', err);
                    provSelect.innerHTML = '<option value="">Error al cargar</option>';
                });
        });

        // Cambio de provincia
        provSelect.addEventListener('change', function() {
            const provCode = this.value;
            
            // Limpiar distrito
            distSelect.innerHTML = '<option value="">Cargando...</option>';
            distSelect.disabled = true;
            codeInput.value = '';

            if (!provCode) {
                distSelect.innerHTML = '<option value="">Seleccione provincia...</option>';
                return;
            }

            // Cargar distritos
            fetch(`/api/ubigeos/districts?province_code=${provCode}`)
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        distSelect.innerHTML = '<option value="">Seleccione...</option>';
                        res.data.forEach(dist => {
                            const option = document.createElement('option');
                            option.value = dist.id;
                            option.dataset.code = dist.code;
                            option.textContent = dist.name;
                            distSelect.appendChild(option);
                        });
                        distSelect.disabled = false;
                    }
                })
                .catch(err => {
                    console.error('Error al cargar distritos:', err);
                    distSelect.innerHTML = '<option value="">Error al cargar</option>';
                });
        });

        // Cambio de distrito
        distSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.code) {
                codeInput.value = selectedOption.dataset.code;
            } else {
                codeInput.value = '';
            }
        });
    });
}
</script>
@endpush
