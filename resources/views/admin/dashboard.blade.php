{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', __('messages.dashboard_admin'))

@section('content')
    <h1>{{ __('messages.bienvenida_admin') }}</h1>
    <p>{{ __('messages.dashboard_admin_descripcion') }}</p>
@endsection
