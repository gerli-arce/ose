@extends('layout.master')

@section('title', 'Gestión de Usuarios')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jsgrid.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Usuarios</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Configuración</li>
                        <li class="breadcrumb-item active">Usuarios</li>
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
                        <h3 class="mb-3">Lista de Usuarios</h3>
                        <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo Usuario</a>
                    </div>
                    <div class="card-body">
                        <div id="users_grid"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jsgrid/jsgrid.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
        var usersData = {!! json_encode($usersData) !!};

        // Helper para notificaciones tipo Toast usando SweetAlert
        function showNotify(title, message, type) {
            swal({
                title: title,
                text: message,
                icon: type,
                timer: 2000,
                buttons: false
            });
        }

        $("#users_grid").jsGrid({
            width: "100%",
            height: "auto",
            inserting: false,
            editing: false,
            sorting: true,
            paging: true,
            data: usersData,
            fields: [
                { name: "name", type: "text", title: "Nombre", width: 100 },
                { name: "email", type: "text", title: "Email", width: 100 },
                { name: "roles", type: "text", title: "Roles", width: 100 },
                { name: "status", type: "text", title: "Estado", width: 50 },
                { 
                    type: "control", 
                    modeSwitchButton: false, 
                    editButton: false, 
                    headerTemplate: function() {
                        return "Acciones";
                    },
                    itemTemplate: function(value, item) {
                        var $editBtn = $("<a class='btn btn-primary btn-xs' href='" + item.edit_url + "'><i class='fa fa-pencil'></i></a>");
                        var $deleteBtn = $("<button class='btn btn-danger btn-xs ms-1'><i class='fa fa-trash'></i></button>")
                            .on("click", function(e) {
                                e.stopPropagation();
                                swal({
                                    title: "¿Estás seguro?",
                                    text: "Una vez eliminado, no podrás recuperar este usuario",
                                    icon: "warning",
                                    buttons: true,
                                    dangerMode: true,
                                })
                                .then((willDelete) => {
                                    if (willDelete) {
                                        $.ajax({
                                            url: item.delete_url,
                                            type: 'DELETE',
                                            data: {
                                                _token: '{{ csrf_token() }}'
                                            },
                                            success: function(result) {
                                                swal({
                                                    title: "Eliminado!",
                                                    text: result.message,
                                                    icon: "success",
                                                    timer: 2000,
                                                    buttons: false
                                                }).then(() => {
                                                    location.reload();
                                                });
                                            },
                                            error: function(err) {
                                                swal("Error!", err.responseJSON.error || "Error al eliminar", "error");
                                            }
                                        });
                                    }
                                });
                            });
                        return $("<div>").append($editBtn).append($deleteBtn);
                    }
                }
            ]
        });

        @if(session('success'))
            showNotify("Éxito!", "{{ session('success') }}", "success");
        @endif

        @if(session('error'))
            showNotify("Error!", "{{ session('error') }}", "error");
        @endif
    </script>
@endsection
