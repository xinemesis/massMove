@extends('layouts.app')

@section('title', 'Editar Subasta')

@section('content')
<div class="container">
    <h2>Editar Subasta</h2>
    <form action="{{ route('admin.auctions.update', $auction->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nombre del Auto</label>
            <input type="text" name="name" class="form-control" value="{{ $auction->name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio Inicial</label>
            <input type="number" name="starting_price" class="form-control" value="{{ $auction->starting_price }}" required>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
    </form>
</div>
@endsection