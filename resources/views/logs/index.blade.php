@extends('layouts.app')

@section('title', 'Logs do Sistema')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Logs do Sistema</h1>
        <a href="{{ route('solicitations.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Voltar para Solicitações
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('logs.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search"
                           placeholder="Buscar por Ação, Descrição ou ID da Solicitação..."
                           value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        @if(request('search'))
                            <a href="{{ route('logs.index') }}" class="btn btn-outline-secondary" title="Limpar busca">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Ação</th>
                            <th>Descrição</th>
                            <th>ID Solicitação</th>
                            <th>Usuário</th>
                            <th>Data/Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->action }}</td>
                                <td title="{{ $log->description }}">{{ Str::limit($log->description, 400) }}</td>
                                <td>
                                    @if($log->solicitation_id)
                                        <a href="{{ route('solicitations.show', $log->solicitation_id) }}">
                                            {{ $log->solicitation_id }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $log->user->name ?? 'Sistema' }}</td>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Nenhum log encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
