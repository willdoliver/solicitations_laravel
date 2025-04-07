@extends('layouts.header')

@section('content')

    <h1>Logs do Sistema</h1>

    <div class="mb-3">
        <form action="{{ route('logs.index') }}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" name="search"
                    placeholder="Buscar por solicitação, ação ou descrição"
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
    </div>

    <table class="table table-striped" id="solicitations-table">
        <thead>
            <tr>
                <th># Solicitação</th>
                <th>Ação</th>
                <th>Descrição</th>
                <th>Solicitante</th>
                <th>Data de Criação</th>
            </tr>
        </thead>
        <tbody>
            @if ($logs->isEmpty())
                <tr>
                    <td colspan="5">Nenhum log encontrada.</td>
                </tr>
            @endif
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->solicitation_id }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->user->name }}</td>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
    