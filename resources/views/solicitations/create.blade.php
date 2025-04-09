@extends('layouts.app')

@section('title', 'Criar Nova Solicitação')

@section('content')
<div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Criar Nova Solicitação</h4>
            <a href="{{ route('solicitations.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Voltar
            </a>
        </div>

        <form action="{{ route('solicitations.store') }}" method="POST" novalidate>
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="title">Título <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('title') is-invalid @enderror"
                           id="title"
                           name="title"
                           value="{{ old('title') }}"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category">Categoria <span class="text-danger">*</span></label>
                    <select class="form-control @error('category') is-invalid @enderror"
                            id="category"
                            name="category"
                            required>
                        <option value="" disabled {{ old('category') ? '' : 'selected' }}>Selecione uma categoria...</option> {{-- Placeholder --}}
                        <option value="TI" {{ old('category') == 'TI' ? 'selected' : '' }}>TI</option>
                        <option value="Suprimentos" {{ old('category') == 'Suprimentos' ? 'selected' : '' }}>Suprimentos</option>
                        <option value="RH" {{ old('category') == 'RH' ? 'selected' : '' }}>RH</option>
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i> Criar Solicitação
                </button>
            </div>
        </form>
    </div>
@endsection