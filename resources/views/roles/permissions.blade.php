@extends('layout.master')

@section('title', 'Asignar Permisos')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                 <div class="col-6">
                    <h3>Permisos del Rol</h3>
                </div>
                <div class="col-6">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">Permisos</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between">
                <h5>Permisos para: <span class="text-primary">{{ $role->name }}</span></h5>
            </div>
            <div class="card-body">
                <form action="{{ route('roles.permissions.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        @foreach($permissionGroups as $module => $permissions)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 border shadow-sm">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 text-uppercase fw-bold">{{ $module }}</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="d-flex flex-column gap-2">
                                            @foreach($permissions as $perm)
                                                <div class="form-check">
                                                    <input class="form-check-input permission-checkbox" type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $perm->id }}" 
                                                           id="perm_{{ $perm->id }}"
                                                           {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="perm_{{ $perm->id }}">
                                                        {{ $perm->name }} <br>
                                                        <small class="text-muted">{{ $perm->key }}</small>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                     <div class="card-footer py-1 px-3 bg-light">
                                         <div class="form-check">
                                            <input class="form-check-input select-all-group" type="checkbox" id="select_all_{{ $module }}">
                                            <label class="form-check-label" for="select_all_{{ $module }}">
                                                Seleccionar Todo
                                            </label>
                                         </div>
                                     </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="card-footer text-end mt-3">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button class="btn btn-primary" type="submit">Guardar Permisos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Group Select All Logic
        $('.select-all-group').on('change', function() {
            var isChecked = $(this).is('checked');
            $(this).closest('.card').find('.permission-checkbox').prop('checked', this.checked);
        });
    </script>
@endsection
