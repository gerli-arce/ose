@extends('layout.auth')

@section('title', 'Seleccionar Sucursal')

@section('auth-content')
<div class="login-card">
    <div>
        <div><a class="logo text-start" href="{{ route('login') }}"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt="looginpage"><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo_dark.png') }}" alt="looginpage"></a></div>
        <div class="login-main">
            <form class="theme-form" action="{{ route('select.branch.post') }}" method="POST">
                @csrf
                <h4>Seleccionar Sucursal</h4>
                <p>Estás accediendo a: <strong>{{ $company->name }}</strong></p>
                <p>Elige la sucursal de trabajo.</p>

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                
                <div class="form-group mt-3">
                    <label class="col-form-label">Sucursal</label>
                    <select class="form-select" name="branch_id" required>
                        <option value="" selected disabled>Seleccione una sucursal...</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }} ({{ $branch->code ?? 'S/C' }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-0 mt-4">
                    <button class="btn btn-primary btn-block w-100" type="submit">Ingresar al Sistema</button>
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('select.company') }}" class="me-3">Cambiar Empresa</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
