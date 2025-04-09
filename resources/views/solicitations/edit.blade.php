@extends('layouts.app')

@section('title', 'Editar Solicitação #' . $solicitation->id)

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Editar Solicitação #{{ $solicitation->id }}</h4>
            <a href="{{ route('solicitations.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Voltar
            </a>
        </div>

        <form action="{{ route('solicitations.update', $solicitation->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <fieldset {{ $solicitation->status == 'concluida' ? 'disabled' : '' }}>
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Título <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title', $solicitation->title) }}" {{-- Use old() helper --}}
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
                                  rows="4">{{ old('description', $solicitation->description) }}</textarea>
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
                            <option value="TI" {{ old('category', $solicitation->category) == 'TI' ? 'selected' : '' }}>TI</option>
                            <option value="Suprimentos" {{ old('category', $solicitation->category) == 'Suprimentos' ? 'selected' : '' }}>Suprimentos</option>
                            <option value="RH" {{ old('category', $solicitation->category) == 'RH' ? 'selected' : '' }}>RH</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror"
                                id="status"
                                name="status"
                                required>
                            <option value="aberta" {{ old('status', $solicitation->status) == 'aberta' ? 'selected' : '' }}>Aberta</option>
                            <option value="em_andamento" {{ old('status', $solicitation->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ old('status', $solicitation->status) == 'concluida' ? 'selected' : '' }}>Concluída</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </fieldset>

            <div class="card-footer text-right">
                @if ($solicitation->status == 'concluida')
                    <div class="alert alert-warning text-left mb-0">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Não é possível editar ou excluir uma solicitação já concluída.
                    </div>
                @else
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Atualizar
                    </button>
                    <button type="button" class="btn btn-danger" onclick="excluirSolicitacao({{ $solicitation->id }})">
                        <i class="fas fa-trash-alt mr-1"></i> Excluir
                    </button>
                @endif
            </div>

        </form>
    </div>

    <form id="delete-form-{{ $solicitation->id }}" action="{{ route('solicitations.destroy', $solicitation->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection
