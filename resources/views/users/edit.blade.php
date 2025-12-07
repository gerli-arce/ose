@extends('layout.master')

@section('title', 'Editar Usuario')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Editar Perfil</h3>
                </div>
                <div class="col-6">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="edit-profile">
            <form id="editUserForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Columna Izquierda: Imagen y Resumen -->
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h4 class="card-title mb-0">Perfil de Usuario</h4>
                                <div class="card-options">
                                    <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                    <a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="profile-title">
                                        <div class="d-flex">
                                            <div style="position: relative; cursor: pointer;" onclick="document.getElementById('image').click();" title="Click para cambiar foto">
                                                <img class="rounded-circle" alt="" src="{{ $user->image_url }}" id="profile-display" style="width: 100px; height: 100px; object-fit: cover; object-position: center; transition: opacity 0.3s;">
                                                <div class="profile-overlay" style="position: absolute; bottom: 0; right: 0; background: #fff; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                    <i data-feather="camera" style="width: 14px; height: 14px; color: #666;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h3 class="mb-1 f-20 txt-primary">{{ $user->name }}</h3>
                                                <p class="f-12">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <input class="form-control d-none" id="image" name="image" type="file" accept="image/*" onchange="previewImage(this)">
                                
                                @if($user->image)
                                    <div class="mt-3">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-light" onclick="viewImage($('#profile-display').attr('src'))" title="Ver Grande"><i class="fa fa-eye"></i></button>
                                            <a href="{{ $user->image_url }}" download class="btn btn-light" title="Descargar Original"><i class="fa fa-download"></i></a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Formulario Principal -->
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h4 class="card-title mb-0">Editar Datos</h4>
                                <div class="card-options">
                                    <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                    <a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="name">Nombre Completo</label>
                                        <input class="form-control" id="name" name="name" type="text" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="email">Correo Electrónico</label>
                                        <input class="form-control" id="email" name="email" type="email" value="{{ $user->email }}" required>
                                    </div>

                                    <div class="col-12 mt-2 mb-2">
                                        <div class="alert alert-light-primary d-flex align-items-center" role="alert">
                                            <i data-feather="info"></i>
                                            <span class="ms-3">Dejar contraseña en blanco si no desea cambiarla.</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="password">Nueva Contraseña</label>
                                        <input class="form-control" id="password" name="password" type="password">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="password_confirmation">Confirmar Contraseña</label>
                                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" id="active" name="active" type="checkbox" role="switch" {{ $user->active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active">Usuario Activo</label>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold">Roles</label>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($roles as $role)
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
                                                        {{ in_array($role->id, $userRolesIds) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                                        {{ $role->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold">Sucursales Asignadas</label>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($branches as $branch)
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="checkbox" name="branches[]" value="{{ $branch->id }}" id="branch_{{ $branch->id }}"
                                                            {{ in_array($branch->id, $userBranchesIds) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="branch_{{ $branch->id }}">
                                                        {{ $branch->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-primary" type="submit">Actualizar Perfil</button>
                                <button class="btn btn-light" type="button" onclick="window.history.back()">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#profile-display').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function viewImage(url) {
            swal({
                icon: url,
                buttons: {
                    confirm: {
                        text: "Cerrar",
                        value: true,
                        visible: true,
                        className: "btn btn-primary",
                        closeModal: true
                    }
                }
            });
        }

        $('#editUserForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('users.update', $user->id) }}",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    swal({
                        title: "Actualizado!",
                        text: response.message,
                        icon: "success",
                        timer: 2000,
                        buttons: false
                    })
                    .then(() => {
                        window.location.href = "{{ route('users.index') }}";
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMsg = '';
                    if(errors){
                        $.each(errors, function(key, value) {
                            errorMsg += value[0] + '\n';
                        });
                    } else {
                        errorMsg = xhr.responseJSON.message || "Error al actualizar usuario";
                    }
                    swal("Error!", errorMsg, "error");
                }
            });
        });
    </script>
@endsection
