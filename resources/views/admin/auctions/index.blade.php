@extends('layouts.app')

@section('title', 'Gestión de Subastas')

@section('content')
<div class="container">
    <h2>Subastas Activas</h2>
    <a href="{{ route('admin.auctions.create') }}" class="btn btn-primary mb-3">Nueva Subasta</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Precio Inicial</th>
                <th>Fecha de Cierre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($auctions as $auction)
                <tr>
                    <td>{{ $auction->name }}</td>
                    <td>${{ number_format($auction->starting_price, 2) }}</td>
                    <td>{{ $auction->end_time }}</td>
                    <td>
                        <a href="{{ route('admin.auctions.edit', $auction->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('admin.auctions.destroy', $auction->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar esta subasta?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection