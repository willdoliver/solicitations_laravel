@extends('layouts.app')

@section('title', 'Lista de Solicitações')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Solicitações</h1>
        <a href="{{ route('solicitations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Nova Solicitação
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('solicitations.index') }}" method="GET">
                <div class="row align-items-end">

                    <div class="col-md-8 col-lg-9 mb-2 mb-md-0">
                        <label for="search" class="sr-only">Buscar</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search"
                                   placeholder="Buscar por título ou descrição..."
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>

                                @if(request('search'))
                                    <a href="{{ route('solicitations.index') }}" class="btn btn-outline-secondary" title="Limpar busca">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 text-md-right">
                        <a href="{{ route('solicitations.export', ['search' => request('search')]) }}" class="btn btn-outline-info btn-block">
                            <i class="fas fa-file-csv mr-1"></i> Exportar para CSV
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0" id="solicitations-table">
                <thead class="thead-light">
                        <tr>
                            <th scope="col" onclick="sortTable('title')" style="cursor: pointer;">
                                Título <i id="title-sort-icon" class="fas fa-sort ml-1"></i>
                            </th>
                            <th>Descrição</th> 
                            <th scope="col" onclick="sortTable('category')" style="cursor: pointer;">
                                Categoria <i id="category-sort-icon" class="fas fa-sort ml-1"></i>
                            </th>
                            <th scope="col" onclick="sortTable('created_at')" style="cursor: pointer;">
                                Data Criação <i id="created_at-sort-icon" class="fas fa-sort ml-1"></i>
                            </th>
                            <th scope="col" onclick="sortTable('user_name')" style="cursor: pointer;">
                                Solicitante <i id="user_name-sort-icon" class="fas fa-sort ml-1"></i>
                            </th>
                            <th scope="col" onclick="sortTable('status')" style="cursor: pointer;">
                                Status <i id="status-sort-icon" class="fas fa-sort ml-1"></i>
                            </th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitations as $solicitation)
                            @php
                                $displayStatus = match(strtolower($solicitation->status)) {
                                    'aberta' => 'Aberta',
                                    'em_andamento' => 'Em Andamento',
                                    'concluida' => 'Concluída',
                                    default => ucfirst($solicitation->status),
                                };
                            @endphp
                            <tr data-title="{{ $solicitation->title }}"
                                data-category="{{ $solicitation->category }}"
                                data-created_at="{{ $solicitation->created_at->timestamp }}"
                                data-user_name="{{ $solicitation->user->name ?? '' }}"
                                data-status="{{ $displayStatus }}"
                                >
                                <td>
                                    <a href="{{ route('solicitations.show', $solicitation->id) }}">
                                        {{ $solicitation->title }}
                                    </a>
                                </td>
                                <td>{{ Str::limit($solicitation->description, 100) }}</td>
                                <td>{{ $solicitation->category }}</td>
                                <td>{{ $solicitation->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $solicitation->user->name ?? 'N/A' }}</td>

                                <td>
                                    <select id="status-{{ $solicitation->id }}"
                                            onchange="showSaveButton({{ $solicitation->id }})"
                                            class="form-control form-control-sm"
                                            {{ $solicitation->status == 'Concluída' ? 'disabled' : '' }}
                                    >
                                        <option value="aberta" {{ $solicitation->status == 'aberta' ? 'selected' : '' }}>Aberta</option>
                                        <option value="em_andamento" {{ $solicitation->status == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                        <option value="concluida" {{ $solicitation->status == 'concluida' ? 'selected' : '' }}>Concluída</option>
                                    </select>
                                </td>

                                <td class="text-right"> 
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button style="display: none;"
                                                id="save-button-{{ $solicitation->id }}"
                                                class="btn btn-primary"
                                                onclick="atualizarStatus({{ $solicitation->id }})">
                                            <i class="fas fa-save"></i> Salvar
                                        </button>
                                        <a href="{{ route('solicitations.show', $solicitation->id) }}" class="btn btn-success" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('solicitations.edit', $solicitation->id) }}" class="btn btn-info" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" title="Excluir"
                                                onclick="if(confirm('Tem certeza que deseja excluir?')) { document.getElementById('delete-form-{{ $solicitation->id }}').submit(); }">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $solicitation->id }}" action="{{ route('solicitations.destroy', $solicitation->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Nenhuma solicitação encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div> 

@endsection
