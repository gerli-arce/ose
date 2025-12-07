@extends('layout.master')

@section('title', 'Nuevo Usuario')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Nuevo Usuario</h3>
                </div>
                <div class="col-6">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                        <li class="breadcrumb-item active">Crear</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Detalles del Usuario</h5>
            </div>
            <div class="card-body">
                <form id="createUserForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="image">Foto de Perfil</label>
                            <input class="form-control" id="image" name="image" type="file" accept="image/*" onchange="previewImage(this)">
                            <div class="mt-2" id="imagePreview" style="display: none;">
                                <img id="preview" src="#" alt="Preview" style="max-width: 150px; border-radius: 5px; border: 1px solid #ccc;">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="name">Nombre Completo</label>
                            <input class="form-control" id="name" name="name" type="text" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="email">Correo Electrónico</label>
                            <input class="form-control" id="email" name="email" type="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="password">Contraseña</label>
                            <input class="form-control" id="password" name="password" type="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="password_confirmation">Confirmar Contraseña</label>
                            <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required>
                        </div>
                        
                         <div class="col-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" id="active" name="active" type="checkbox" role="switch" checked>
                                <label class="form-check-label" for="active">Usuario Activo</label>
                            </div>
                        </div>

                        <hr>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Roles</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($roles as $role)
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}">
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                             <label class="form-label fw-bold">Sucursales Asignadas</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($branches as $branch)
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" name="branches[]" value="{{ $branch->id }}" id="branch_{{ $branch->id }}">
                                        <label class="form-check-label" for="branch_{{ $branch->id }}">
                                            {{ $branch->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer text-end">
                        <button class="btn btn-secondary" type="button" onclick="window.history.back()">Cancelar</button>
                        <button class="btn btn-primary" type="submit">Guardar Usuario</button>
                    </div>
                </form>
            </div>
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
                    $('#preview').attr('src', e.target.result);
                    $('#imagePreview').show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#createUserForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('users.store') }}",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    swal({
                        title: "Creado!",
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
                        errorMsg = xhr.responseJSON.message || "Error al crear usuario";
                    }
                    swal("Error!", errorMsg, "error");
                }
            });
        });
    </script>
@endsection
