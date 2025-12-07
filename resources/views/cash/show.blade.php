@extends('layout.master')

@section('title', 'Gestión de Caja')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Gestión de Caja: {{ $register->name }}</h3>
            </div>
             <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cash.index') }}">Cajas</a></li>
                    <li class="breadcrumb-item active"> Gestión</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    @if(!$session)
        <div class="alert alert-warning">Esta caja nunca ha sido aperturada.</div>
    @else
    
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted">Saldo Inicial</h6>
                    <h3>{{ number_format($session->opening_balance, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
             <div class="card text-center text-success">
                <div class="card-body">
                    <h6 class="text-muted">Ingresos</h6>
                    <h3>+{{ number_format($income, 2) }}</h3>
                </div>
            </div>
        </div>
         <div class="col-md-3">
             <div class="card text-center text-danger">
                <div class="card-body">
                    <h6 class="text-muted">Egresos</h6>
                    <h3>-{{ number_format($expense, 2) }}</h3>
                </div>
            </div>
        </div>
         <div class="col-md-3">
             <div class="card text-center bg-primary text-white">
                <div class="card-body">
                     <h6 class="text-white">Saldo Actual Teorico</h6>
                    <h3 class="text-white">{{ number_format($currentBalance, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions & Table -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Movimientos de Turno Actual</h5>
                    @if($session->status == 'open')
                    <div>
                         <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#incomeModal"><i data-feather="arrow-up"></i> Ingreso</button>
                         <button class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#expenseModal"><i data-feather="arrow-down"></i> Gasto/Salida</button>
                         <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#closeModal"><i data-feather="lock"></i> Cerrar Caja</button>
                    </div>
                    @else
                        <span class="badge badge-secondary">SESIÓN CERRADA</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>Ref.</th>
                                    <th class="text-end">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($session->movements->sortByDesc('id') as $move)
                                    <tr>
                                        <td>{{ $move->created_at->format('H:i') }}</td>
                                        <td>
                                            @if($move->type == 'income')
                                                <span class="badge badge-light-success">INGRESO</span>
                                            @else
                                                <span class="badge badge-light-danger">SALIDA</span>
                                            @endif
                                        </td>
                                        <td>{{ $move->description }}</td>
                                        <td>{{ $move->related_type ? class_basename($move->related_type) . ' #' . $move->related_id : '-' }}</td>
                                        <td class="text-end fw-bold {{ $move->type == 'income' ? 'text-success' : 'text-danger' }}">
                                            {{ $move->type == 'income' ? '+' : '-' }}{{ number_format($move->amount, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">No hay movimientos registrados.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @if($session->status == 'open')
    <!-- Income Modal -->
    <div class="modal fade" id="incomeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Registrar Ingreso</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                 <form action="{{ route('cash.movement') }}" method="POST">
                    @csrf
                    <input type="hidden" name="cash_register_session_id" value="{{ $session->id }}">
                    <input type="hidden" name="type" value="income">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Monto</label>
                            <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Referencia</label>
                            <input type="text" name="reference" class="form-control" placeholder="N° Operación, Recibo...">
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <input type="text" name="description" class="form-control" required placeholder="Ej: Venta manual sin boleta, Ingreso de sencillo...">
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-success">Registrar Ingreso</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Expense Modal -->
    <div class="modal fade" id="expenseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                 <div class="modal-header"><h5 class="modal-title">Registrar Gasto / Salida</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                 <form action="{{ route('cash.movement') }}" method="POST">
                    @csrf
                    <input type="hidden" name="cash_register_session_id" value="{{ $session->id }}">
                    <input type="hidden" name="type" value="expense">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Monto</label>
                            <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Referencia</label>
                            <input type="text" name="reference" class="form-control" placeholder="N° Factura, Recibo...">
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <input type="text" name="description" class="form-control" required placeholder="Ej: Pago de luz, Compra de insumos...">
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-danger">Registrar Salida</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Close Modal -->
    <div class="modal fade" id="closeModal" tabindex="-1">
         <div class="modal-dialog">
            <div class="modal-content">
                 <div class="modal-header"><h5 class="modal-title">Cerrar Caja (Arqueo)</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                 <form action="{{ route('cash.close', $session->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                         <div class="alert alert-info">
                             Saldo Teórico en Sistema: <strong>{{ number_format($currentBalance, 2) }}</strong>
                         </div>
                        <div class="mb-3">
                            <label class="form-label">Total Efectivo Real (Contado)</label>
                            <input type="number" name="closing_balance" class="form-control" step="0.01" min="0" required>
                            <small class="text-muted">Ingrese la cantidad de dinero encontrada físicamente.</small>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observations" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-dark">Cerrar Turno</button></div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @endif
</div>
@endsection
