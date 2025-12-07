@extends('layout.master')

@section('title', 'Bancos & Cuentas')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Bancos & Cuentas</h3>
            </div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active"> Bancos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Accounts List -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header pb-2 d-flex justify-content-between align-items-center">
                    <h5>Mis Cuentas</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newAccountModal"><i data-feather="plus"></i></button>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($accounts as $acc)
                            <a href="{{ route('banks.show', $acc->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ isset($account) && $account->id == $acc->id ? 'active' : '' }}">
                                <div>
                                    <h6 class="mb-0">{{ $acc->bank_name }}</h6>
                                    <small>{{ $acc->account_number }} ({{ $acc->currency->code }})</small>
                                </div>
                                <span class="badge badge-light-primary">{{ number_format($acc->current_balance, 2) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions (Only if account selected) -->
        <div class="col-md-8">
            @if(isset($account))
                <div class="card">
                    <div class="card-header pb-2 d-flex justify-content-between align-items-center">
                        <div>
                             <h5>{{ $account->bank_name }} - {{ $account->currency->code }}</h5>
                             <small class="text-muted">Saldo Actual: {{ number_format($account->current_balance, 2) }}</small>
                        </div>
                        <div>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#depositModal">Depósito</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#withdrawalModal">Retiro</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Ref.</th>
                                        <th>Descripción</th>
                                        <th class="text-center">Conciliado</th>
                                        <th class="text-end">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($account->transactions as $tx)
                                        <tr>
                                            <td>{{ $tx->transaction_date->format('d/m/Y') }}</td>
                                            <td>{{ ucfirst($tx->type) }}</td>
                                            <td>{{ $tx->reference }}</td>
                                            <td>{{ $tx->description }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('banks.transactions.toggle', $tx->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs {{ $tx->is_reconciled ? 'btn-success' : 'btn-outline-secondary' }}" title="Cambiar estado">
                                                        <i data-feather="{{ $tx->is_reconciled ? 'check-circle' : 'circle' }}"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-end {{ $tx->type == 'deposit' ? 'text-success' : 'text-danger' }}">
                                                {{ $tx->type == 'deposit' ? '+' : '-' }}{{ number_format($tx->amount, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center">No hay movimientos.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tx Modals -->
                <!-- Deposit -->
                <div class="modal fade" id="depositModal" tabindex="-1">
                    <div class="modal-dialog">
                         <form class="modal-content" action="{{ route('banks.transaction') }}" method="POST">
                            @csrf
                            <input type="hidden" name="bank_account_id" value="{{ $account->id }}">
                            <input type="hidden" name="type" value="deposit">
                            <div class="modal-header"><h5 class="modal-title">Registrar Depósito</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body">
                                <div class="mb-3"><label>Fecha</label><input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                                <div class="mb-3"><label>Monto</label><input type="number" name="amount" class="form-control" step="0.01" required></div>
                                <div class="mb-3"><label>Referencia</label><input type="text" name="reference" class="form-control"></div>
                                <div class="mb-3"><label>Descripción</label><input type="text" name="description" class="form-control"></div>
                            </div>
                            <div class="modal-footer"><button type="submit" class="btn btn-success">Guardar</button></div>
                        </form>
                    </div>
                </div>
                 <!-- Withdrawal -->
                <div class="modal fade" id="withdrawalModal" tabindex="-1">
                    <div class="modal-dialog">
                         <form class="modal-content" action="{{ route('banks.transaction') }}" method="POST">
                            @csrf
                            <input type="hidden" name="bank_account_id" value="{{ $account->id }}">
                            <input type="hidden" name="type" value="withdrawal">
                            <div class="modal-header"><h5 class="modal-title">Registrar Retiro</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body">
                                <div class="mb-3"><label>Fecha</label><input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                                <div class="mb-3"><label>Monto</label><input type="number" name="amount" class="form-control" step="0.01" required></div>
                                <div class="mb-3"><label>Referencia</label><input type="text" name="reference" class="form-control"></div>
                                <div class="mb-3"><label>Descripción</label><input type="text" name="description" class="form-control"></div>
                            </div>
                            <div class="modal-footer"><button type="submit" class="btn btn-danger">Guardar</button></div>
                        </form>
                    </div>
                </div>

            @else
                <div class="alert alert-info">Seleccione una cuenta bancaria para ver sus movimientos.</div>
            @endif
        </div>
    </div>

    <!-- New Account Modal -->
    <div class="modal fade" id="newAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('banks.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Nueva Cuenta Bancaria</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label>Banco</label><input type="text" name="bank_name" class="form-control" required placeholder="BCP, BBVA..."></div>
                    <div class="mb-3"><label>Número de Cuenta</label><input type="text" name="account_number" class="form-control" required></div>
                    <div class="mb-3"><label>Moneda</label>
                        <select name="currency_id" class="form-select">
                            @foreach($currencies as $curr)
                                <option value="{{ $curr->id }}">{{ $curr->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label>Saldo Inicial</label><input type="number" name="initial_balance" class="form-control" step="0.01" value="0.00"></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Crear Cuenta</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
