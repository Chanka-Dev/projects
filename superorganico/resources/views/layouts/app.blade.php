{{-- Este archivo redirige al layout de AdminLTE --}}
@extends('adminlte::page')

{{-- Mapear secciones personalizadas a las secciones de AdminLTE --}}
@if(View::hasSection('page_header'))
    @section('content_header')
        @yield('page_header')
    @endsection
@endif

@if(View::hasSection('page_content'))
    @section('content')
        @yield('page_content')
    @endsection
@endif
