@extends('layouts.header')

@section('content')
    <h1>Criar Solicitação</h1>
    <form action="{{ route('solicitations.store') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" class="form-control" id="title" name="title">
        </div>
        <div class="form-group">
            <label for="description">Descrição</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="form-group">
            <label for="category">Categoria</label>
            <select class="form-control" id="category" name="category">
                <option value="TI">TI</option>
                <option value="Suprimentos">Suprimentos</option>
                <option value="RH">RH</option>
            </select>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a href="{{ route('solicitations.index') }}" class="btn btn-secondary">Voltar</a>
        <button type="submit" class="btn btn-primary">Criar</button>
    </form>
@endsection