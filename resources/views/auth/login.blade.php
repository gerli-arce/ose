@extends('others.layout_others.master')

@section('others-css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
@endsection

@section('others-content')
<div class="container-fluid">
    <div class="row">
      <div class="col-12 p-0">
        <div class="login-card">
          <div>
            <div><a class="logo text-center" href="{{ route('dashboard') }}"><img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo.png') }}" alt="logo image"></a></div>
            <div class="login-main">
              <form class="theme-form" method="POST" action="{{ route('login') }}">
                @csrf
                <h4>Iniciar Sesión</h4>
                <p>Ingresa tus datos para acceder</p>
                
                 @if($errors->any())
                    <div class="alert alert-danger inverse alert-dismissible fade show" role="alert">
                        <i class="icon-alert"></i>
                         <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        <p><b>Error!</b></p>
                        <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label class="col-form-label">Correo Electrónico</label>
                    <input class="form-control" type="email" name="email" required="" placeholder="admin@empresa.com" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label class="col-form-label">Contraseña</label>
                    <div class="form-input position-relative">
                        <input class="form-control" type="password" name="password" required="" placeholder="*********">
                    </div>
                </div>
                <div class="form-group mb-0">
                    <div class="checkbox p-0">
                        <input id="checkbox1" type="checkbox" name="remember">
                        <label class="text-muted" for="checkbox1">Recordar contraseña</label>
                    </div>
                    <a class="link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    <div class="text-end mt-3">
                        <button class="btn btn-primary btn-block w-100" type="submit">Ingresar</button>
                    </div>
                </div>
                
                {{-- Removed Social Login for Production ease, can be added back if needed --}}
                
                <p class="mt-4 mb-0 text-center">Don't have account?<a class="ms-2" href="{{ route('sign-up')}}">Create Account</a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

@section('others-scripts')
<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
{{-- Retaining original script structure potentially for future js validation --}}
@endsection
