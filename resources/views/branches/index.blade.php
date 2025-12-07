@extends('layout.master')

@section('title', 'Gestión de Sucursales')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jsgrid.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Sucursales</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item active">Sucursales</li>
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
                        <h3 class="mb-3">Lista de Sucursales</h3>
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createBranchModal"><i class="fa fa-plus"></i> Nueva Sucursal</button>
                    </div>
                    <div class="card-body">
                        <div id="branches_grid"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createBranchModal" tabindex="-1" role="dialog" aria-labelledby="createBranchModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBranchModalLabel">Nueva Sucursal</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createBranchForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input class="form-control" name="name" type="text" required placeholder="Ej: Oficina Principal">
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Código (Serie)</label>
                            <input class="form-control" name="code" type="text" required placeholder="Ej: 001">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dirección (Opcional)</label>
                            <input class="form-control" name="address" type="text" placeholder="Dirección física">
                        </div>
                        <div class="mb-3">
                             <div class="form-check form-switch">
                                <input class="form-check-input" id="active" name="active" type="checkbox" role="switch" checked>
                                <label class="form-check-label" for="active">Sucursal Activa</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editBranchModal" tabindex="-1" role="dialog" aria-labelledby="editBranchModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBranchModalLabel">Editar Sucursal</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBranchForm">
                         @csrf
                         @method('PUT')
                         <input type="hidden" id="edit_branch_id">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input class="form-control" id="edit_name" name="name" type="text" required>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Código (Serie)</label>
                            <input class="form-control" id="edit_code" name="code" type="text" required>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input class="form-control" id="edit_address" name="address" type="text">
                        </div>
                        <div class="mb-3">
                             <div class="form-check form-switch">
                                <input class="form-check-input" id="edit_active" name="active" type="checkbox" role="switch">
                                <label class="form-check-label" for="edit_active">Sucursal Activa</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jsgrid/jsgrid.min.js') }}"></script>
    <script>
        var branchesData = {!! json_encode($branchesData) !!};

        $("#branches_grid").jsGrid({
            width: "100%",
            height: "auto",
            inserting: false,
            editing: false,
            sorting: true,
            paging: true,
            data: branchesData,
            fields: [
                { name: "name", type: "text", title: "Nombre", width: 150 },
                { name: "code", type: "text", title: "Código", width: 80 },
                { name: "status_label", type: "text", title: "Estado", width: 80 },
                { 
                    type: "control", 
                    modeSwitchButton: false, 
                    editButton: false, 
                    headerTemplate: function() {
                        return "Acciones";
                    },
                    itemTemplate: function(value, item) {
                        var $editBtn = $("<button class='btn btn-primary btn-xs' title='Editar'><i class='fa fa-pencil'></i></button>")
                            .on("click", function() {
                                openEditModal(item);
                            });

                        var $deleteBtn = $("<button class='btn btn-danger btn-xs ms-1' title='Eliminar'><i class='fa fa-trash'></i></button>")
                            .on("click", function(e) {
                                e.stopPropagation();
                                if(confirm("¿Estás seguro de eliminar esta sucursal?")) {
                                    $.ajax({
                                        url: item.delete_url,
                                        type: 'DELETE',
                                        data: { _token: '{{ csrf_token() }}' },
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
                        return $("<div>").append($editBtn).append($deleteBtn);
                    }
                }
            ]
        });

        // Create Form
        $('#createBranchForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('branches.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert("Error al crear sucursal");
                }
            });
        });

        // Edit Logic
        function openEditModal(item) {
             $.get(item.edit_url, function(data) {
                 var branch = data.branch;
                 $('#edit_branch_id').val(branch.id);
                 $('#edit_name').val(branch.name);
                 $('#edit_code').val(branch.code);
                 // $('#edit_address').val(branch.address); 
                 $('#edit_active').prop('checked', branch.active);
                 
                 // Update form action url dynamically shouldn't be hardcoded if resource, but we construct it
                 var updateUrl = "{{ url('branches') }}/" + branch.id;
                 $('#editBranchForm').attr('action', updateUrl); 
                 
                 $('#editBranchModal').modal('show');
             });
        }

        $('#editBranchForm').on('submit', function(e) {
             e.preventDefault();
             var url = $(this).attr('action');
             // Add active checkbox explicitly if unchecked (HTML forms don't send unchecked checkboxes)
             // However, PHP controller handles validation 'has' check properly for "on"
             
             // Construct data manually to ensure checkbox state is clear?
             // $(this).serialize() is enough if controller expects "on" or nothing
             
             $.ajax({
                url: url,
                method: "POST", // Method spoofing via _method is included in blade
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert("Error al actualizar sucursal");
                }
            });
        });

    </script>
@endsection
