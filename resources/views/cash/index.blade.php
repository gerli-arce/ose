@extends('layout.master')

@section('title', 'Cajas & Efectivo')

@section('main-content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Cajas (Puntos de Venta)</h3>
            </div>
            <div class="col-6">
                 <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item active"> Cajas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        @foreach($registers as $register)
        <div class="col-md-4">
            <div class="card">
                <div class="card-header pb-2 d-flex justify-content-between align-items-center">
                    <h5>{{ $register->name }} ({{ $register->branch->name }})</h5>
                    <span class="badge {{ $register->status == 'open' ? 'badge-success' : 'badge-secondary' }}">
                        {{ $register->status == 'open' ? 'ABIERTA' : 'CERRADA' }}
                    </span>
                </div>
                <div class="card-body">
                    @if($register->status == 'open')
                        <p class="text-muted">Aperturada por: <strong>{{ $register->currentSession->user->name ?? 'N/A' }}</strong></p>
                        <p class="text-muted">Hora: {{ $register->currentSession->opened_at->format('d/m/Y H:i') }}</p>
                        <a href="{{ route('cash.show', $register->id) }}" class="btn btn-primary w-100 mt-2">Gestionar Caja</a>
                    @else
                         <p class="text-muted">Caja cerrada. Inicie turno para operar.</p>
                         <button class="btn btn-outline-primary w-100 mt-2" data-bs-toggle="modal" data-bs-target="#openModal{{$register->id}}">Abrir Caja</button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal Open -->
        <div class="modal fade" id="openModal{{$register->id}}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Apertura de Caja</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('cash.open') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cash_register_id" value="{{ $register->id }}">
                        <div class="modal-body">
                            <label class="form-label">Saldo Inicial</label>
                            <input type="number" name="opening_balance" class="form-control" step="0.01" min="0" value="0.00" required>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Abrir Caja</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
        
        <div class="col-md-4">
             <div class="card border-dashed">
                <div class="card-body text-center p-5">
                     <p class="mb-0 text-muted">¿Necesitas otra caja?</p>
                     <p>Configura nuevas cajas en Configuración > Sucursales</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
