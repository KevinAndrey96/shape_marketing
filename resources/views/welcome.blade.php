@extends('layouts.master')
@section('title', 'Welcome')
@section('content')
    @parent
    <div id="app">
        <app></app>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
@stop