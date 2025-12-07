@extends('layout.master')

@section('title', 'Editar Rol')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                 <div class="col-6">
                    <h3>Editar Rol</h3>
                </div>
                <div class="col-6">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Detalles del Rol: {{ $role->name }}</h5>
            </div>
            <div class="card-body">
                <form id="editRoleForm">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="name">Nombre</label>
                            <input class="form-control" id="name" name="name" type="text" value="{{ $role->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="slug">Slug (Identificador)</label>
                            <input class="form-control" id="slug" name="slug" type="text" value="{{ $role->slug }}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label" for="description">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $role->description }}</textarea>
                        </div>
                    </div>
                    
                    <div class="card-footer text-end">
                        <button class="btn btn-secondary" type="button" onclick="window.history.back()">Cancelar</button>
                        <button class="btn btn-primary" type="submit">Actualizar Rol</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#editRoleForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('roles.update', $role->id) }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message);
                    window.location.href = "{{ route('roles.index') }}";
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '\n';
                    });
                    alert(errorMsg || "Error al actualizar rol");
                }
            });
        });
    </script>
@endsection
