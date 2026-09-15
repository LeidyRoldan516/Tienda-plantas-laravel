{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.cliente')

@section('title', __('messages.dashboard_cliente'))

@section('content')
    <h1>{{ __('messages.bienvenida_cliente') }}</h1>
    <p>{{ __('messages.dashboard_cliente_descripcion') }}</p>
@endsection
