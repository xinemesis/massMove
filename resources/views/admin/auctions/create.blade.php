@extends('layouts.app')

@section('title', 'Nueva Subasta')

@section('content')
<div class="container">
    <h2>Crear Nueva Subasta</h2>
    <form action="{{ route('admin.auctions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nombre del Auto</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio Inicial</label>
            <input type="number" name="starting_price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de Cierre</label>
            <input type="datetime-local" name="end_time" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Subasta</button>
    </form>
</div>
@endsection