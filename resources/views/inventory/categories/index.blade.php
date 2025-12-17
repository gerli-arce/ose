@extends('layout.master')

@section('title', 'Gestión de Categorías')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jsgrid.css') }}">
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Gestión de Categorías</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Inventario</li>
                    <li class="breadcrumb-item active"> Categorías</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h3 class="mb-3">Listado de Categorías</h3>
                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createCategoryModal">Nueva Categoría</button>
                </div>
                <div class="card-body">
                    <div class="basic_table" id="categories_grid"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Crear Nueva Categoría</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createCategoryForm">
                <div class="modal-body">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label" for="create_name">Nombre</label>
                            <input class="form-control" id="create_name" name="name" type="text" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="create_code">Código (Opcional)</label>
                            <input class="form-control" id="create_code" name="code" type="text">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="create_parent_id">Categoría Padre</label>
                            <select class="form-select" id="create_parent_id" name="parent_id">
                                <option value="">Ninguna (Categoría Principal)</option>
                                @foreach($allCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="create_active" name="active" value="1" checked>
                                <label class="form-check-label" for="create_active">Activo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Editar Categoría</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_category_id" name="id">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label" for="edit_name">Nombre</label>
                            <input class="form-control" id="edit_name" name="name" type="text" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="edit_code">Código (Opcional)</label>
                            <input class="form-control" id="edit_code" name="code" type="text">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="edit_parent_id">Categoría Padre</label>
                            <select class="form-select" id="edit_parent_id" name="parent_id">
                                <option value="">Ninguna (Categoría Principal)</option>
                                @foreach($allCategories as $cat)
                                    <option value="{{ $cat->id }}" id="edit_opt_{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_active" name="active" value="1">
                                <label class="form-check-label" for="edit_active">Activo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="submit">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jsgrid/jsgrid.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var categoriesData = @json($categoriesData);

        // --- Grid Initialization ---
        $("#categories_grid").jsGrid({
            width: "100%",
            filtering: true,
            editing: false,
            inserting: false,
            sorting: true,
            paging: true,
            autoload: true,
            pageSize: 10,
            pageButtonCount: 5,
            data: categoriesData,
            fields: [
                { name: "name", title: "Nombre", type: "text", width: 150 },
                { name: "code", title: "Código", type: "text", width: 100 },
                { name: "parent_name", title: "Categoría Padre", type: "text", width: 150 },
                { name: "active", title: "Estado", type: "select", width: 80, items: [{Name: '', Id: ''}, {Name: 'Activo', Id: true}, {Name: 'Inactivo', Id: false}], valueField: 'Id', textField: 'Name',
                    itemTemplate: function(value) {
                        return value ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>';
                    }
                },
                { name: "products_count", title: "Productos", type: "number", width: 80, filtering: false },
                { title: "Acciones", width: 100, sorting: false, filtering: false, itemTemplate: function(value, item) {
                    var $container = $("<div>").addClass("d-flex justify-content-center");
                    
                    var $editBtn = $("<button>").attr("type", "button").addClass("border-0 bg-transparent p-0 me-3").html('<i class="fa fa-pencil" style="font-size: 18px; color: #7366ff;"></i>')
                        .click(function() {
                            openEditModal(item.id);
                        });
                        
                    var $deleteBtn = $("<button>").attr("type", "button").addClass("border-0 bg-transparent p-0").html('<i class="fa fa-trash-o" style="font-size: 18px; color: #f73164;"></i>')
                        .click(function() {
                            deleteCategory(item.id);
                        });
                        
                    $container.append($editBtn).append($deleteBtn);
                    return $container;
                }}
            ]
        });

        // --- Create Category AJAX ---
        $('#createCategoryForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: "{{ route('categories.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    $('#createCategoryModal').modal('hide');
                    Swal.fire('Éxito', response.message, 'success').then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = "Error al crear categoría";
                    if(errors) {
                        errorMessage = Object.values(errors).flat().join('<br>');
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        });

        // --- Open Edit Modal & Fetch Data ---
        function openEditModal(catId) {
            $.ajax({
                url: "/inventory/categories/" + catId + "/edit",
                type: "GET",
                success: function(response) {
                    $('#edit_category_id').val(response.category.id);
                    $('#edit_name').val(response.category.name);
                    $('#edit_code').val(response.category.code);
                    $('#edit_parent_id').val(response.category.parent_id);
                    $('#edit_active').prop('checked', response.category.active);

                    // Prevent selecting self as parent (disable option)
                    $('#edit_parent_id option').prop('disabled', false); // Reset
                    $('#edit_opt_' + catId).prop('disabled', true);

                    $('#editCategoryForm').attr('data-cat-id', catId);
                    $('#editCategoryModal').modal('show');
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar la información.', 'error');
                }
            });
        }

        // --- Update Category AJAX ---
        $('#editCategoryForm').on('submit', function(e) {
            e.preventDefault();
            var catId = $(this).attr('data-cat-id');
            var formData = $(this).serialize();
            
            $.ajax({
                url: "/inventory/categories/" + catId,
                type: "PUT",
                data: formData,
                success: function(response) {
                    $('#editCategoryModal').modal('hide');
                    Swal.fire('Éxito', response.message, 'success').then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = "Error al actualizar";
                    if(errors) {
                        errorMessage = Object.values(errors).flat().join('<br>');
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        });

        // --- Delete Category AJAX ---
        function deleteCategory(catId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/inventory/categories/" + catId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire('Eliminado!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON.error || 'No se pudo eliminar.', 'error');
                        }
                    });
                }
            })
        }
    </script>
@endsection
