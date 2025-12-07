@extends('layout.master')

@section('title', 'Bancos & Cuentas')

@section('main-content')
{{-- Reuse the structure but without selected account --}}
@include('bank.show', ['account' => null])
@endsection
