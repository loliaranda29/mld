@extends('layouts.app')

@section('title', 'Inicio Funcionario')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-3">
      @include('components.menu-funcionario')
    </div>
    <div class="col-md-9">
      <h1>Bienvenido funcionario</h1>
    </div>
  </div>
</div>
@endsection

