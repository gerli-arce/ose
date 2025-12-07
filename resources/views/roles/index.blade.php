@extends('layout.master')

@section('title', 'Gestión de Roles')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jsgrid.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Roles</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Configuración</li>
                        <li class="breadcrumb-item active">Roles</li>
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
                        <h3 class="mb-3">Lista de Roles</h3>
                        <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo Rol</a>
                    </div>
                    <div class="card-body">
                        <div id="roles_grid"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jsgrid/jsgrid.min.js') }}"></script>
    <script>
        var rolesData = {!! json_encode($rolesData) !!};

        $("#roles_grid").jsGrid({
            width: "100%",
            height: "auto",
            inserting: false,
            editing: false,
            sorting: true,
            paging: true,
            data: rolesData,
            fields: [
                { name: "name", type: "text", title: "Nombre del Rol", width: 100 },
                { name: "slug", type: "text", title: "Slug", width: 80 },
                { name: "description", type: "text", title: "Descripción", width: 150 },
                { name: "permissions", type: "number", title: "Permisos", width: 50 },
                { 
                    type: "control", 
                    modeSwitchButton: false, 
                    editButton: false, 
                    headerTemplate: function() {
                        return "Acciones";
                    },
                    itemTemplate: function(value, item) {
                        var $editBtn = $("<a class='btn btn-primary btn-xs' href='" + item.edit_url + "' title='Editar'><i class='fa fa-pencil'></i></a>");
                        var $permBtn = $("<a class='btn btn-secondary btn-xs ms-1' href='/roles/" + item.id + "/permissions' title='Permisos'><i class='fa fa-key'></i></a>");
                        
                        var $deleteBtn = $("<button class='btn btn-danger btn-xs ms-1' title='Eliminar'><i class='fa fa-trash'></i></button>")
                            .on("click", function(e) {
                                e.stopPropagation();
                                if(confirm("¿Estás seguro de eliminar este rol?")) {
                                    $.ajax({
                                        url: item.delete_url,
                                        type: 'DELETE',
                                        data: {
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(result) {
                                            alert(result.message);
                                            location.reload();
                                        },
                                        error: function(err) {
                                            alert(err.responseJSON.error || "Error al eliminar");
                                        }
                                    });
                                }
                            });
                        return $("<div>").append($editBtn).append($permBtn).append($deleteBtn);
                    }
                }
            ]
        });
    </script>
@endsection
