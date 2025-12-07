@extends('layout.master')

@section('title', 'Series de Documentos')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jsgrid.css') }}">
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Series de Documentos</h3>
            </div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Ventas</li>
                    <li class="breadcrumb-item active"> Series</li>
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
                    <h5>Listado de Series</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSeriesModal" id="btnNewSeries">Nueva Serie</button>
                </div>
                <div class="card-body">
                    <div class="basic_table" id="series_grid"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create/Edit -->
<div class="modal fade" id="createSeriesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Serie</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="seriesForm">
                @csrf
                <input type="hidden" id="series_id" name="series_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="branch_id">Sucursal</label>
                        <select class="form-select" id="branch_id" name="branch_id" required>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="document_type_id">Tipo de Documento</label>
                        <select class="form-select" id="document_type_id" name="document_type_id" required>
                            @foreach($documentTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->code }} - {{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="prefix">Prefijo (Serie)</label>
                        <input class="form-control" id="prefix" name="prefix" type="text" placeholder="F001" maxlength="4" required>
                        <small class="text-muted">Ej: F001, B001</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="current_number">Correlativo Actual</label>
                        <input class="form-control" id="current_number" name="current_number" type="number" value="0" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Guardar</button>
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
        var seriesData = @json($series);

        $("#series_grid").jsGrid({
            width: "100%",
            filtering: true,
            editing: false,
            inserting: false,
            sorting: true,
            paging: true,
            autoload: true,
            pageSize: 10,
            pageButtonCount: 5,
            data: seriesData,
            fields: [
                { name: "branch", title: "Sucursal", type: "text", width: 100 },
                { name: "type", title: "Tipo Documento", type: "text", width: 150 },
                { name: "prefix", title: "Serie", type: "text", width: 50, align: "center" },
                { name: "current_number", title: "Correlativo", type: "number", width: 50, align: "center" },
                { title: "Acciones", width: 50, sorting: false, filtering: false, itemTemplate: function(value, item) {
                     return $("<button>").attr("type", "button").addClass("btn btn-xs btn-primary me-2").text("Editar")
                        .click(function() {
                            editSeries(item);
                        })
                        .add(
                            $("<button>").attr("type", "button").addClass("btn btn-xs btn-danger").text("Eliminar")
                            .click(function() {
                                deleteSeries(item.id);
                            })
                        );
                }}
            ]
        });

        // Clear modal on open
        $('#btnNewSeries').click(function() {
            $('#seriesForm')[0].reset();
            $('#series_id').val('');
            $('#modalTitle').text('Nueva Serie');
            $('#branch_id').prop('disabled', false);
            $('#document_type_id').prop('disabled', false);
        });

        function editSeries(item) {
            $('#series_id').val(item.id);
            $('#branch_id').find('option:contains("' + item.branch + '")').prop('selected', true); // Simple match
            // Ideally pass IDs in json, but for now this works or we can fetch via AJAX
            // Let's fetch via AJAX to be safe
            $.get('/sales/series/' + item.id + '/edit', function(data) {
                $('#branch_id').val(data.branch_id).prop('disabled', true); // Lock branch/type on edit usually
                $('#document_type_id').val(data.document_type_id).prop('disabled', true);
                $('#prefix').val(data.prefix);
                $('#current_number').val(data.current_number);
                $('#modalTitle').text('Editar Serie');
                $('#createSeriesModal').modal('show');
            });
        }

        $('#seriesForm').on('submit', function(e) {
            e.preventDefault();
            var id = $('#series_id').val();
            var url = id ? '/sales/series/' + id : "{{ route('series.store') }}";
            var method = id ? 'PUT' : 'POST';
            
            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire('Éxito', response.message, 'success').then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = 'Error al guardar';
                     if(errors) {
                        errorMessage = Object.values(errors).flat().join('<br>');
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        });

        function deleteSeries(id) {
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
                        url: "/sales/series/" + id,
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(response) {
                             Swal.fire('Eliminado!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
