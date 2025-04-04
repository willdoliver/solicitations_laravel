@extends('layouts.header')

@section('content')
    <h1>Solicitação {{ $solicitation->id }}</h1>
    <p>ID: {{ $solicitation->id }}</p>
    <p>Título: {{ $solicitation->title }}</p>
    <p>Descrição: {{ $solicitation->description }}</p>
    <p>Categoria: {{ $solicitation->category }}</p>
    <p>Status: {{ $solicitation->status }}</p>
    <p>Data de Criação: {{ $solicitation->created_at }}</p>
    <p>Solicitante: {{ $solicitation->user->name }}</p>

    <a href="{{ route('solicitations.index') }}" class="btn btn-secondary">Voltar</a>
@endsection