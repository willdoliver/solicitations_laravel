@extends('layouts.header')

@section('content')

    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    <h1>Solicitações</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Título</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Data de Criação</th>
                <th>Solicitante</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @if ($solicitations->isEmpty())
                <tr>
                    <td colspan="8">Nenhuma solicitação encontrada.</td>
                </tr>
            @endif
            @foreach($solicitations as $solicitation)
                <tr>
                    <td>
                        <a href="{{ route('solicitations.show', $solicitation->id) }}">
                            {{ $solicitation->id }}
                        </a>
                    </td>
                    <td>{{ $solicitation->title }}</td>
                    <td>{{ $solicitation->description }}</td>
                    <td>{{ $solicitation->category }}</td>
                    <td>{{ $solicitation->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $solicitation->user->name }}</td>

                    <td>
                        <select id="status-{{ $solicitation->id }}" onchange="showSaveButton({{ $solicitation->id }})" class="form-control"
                            {{ $solicitation->status == 'concluida' ? 'disabled' : '' }}
                        >
                            <option value="aberta" {{ $solicitation->status == 'aberta' ? 'selected' : '' }}>Aberta</option>
                            <option value="em_andamento" {{ $solicitation->status == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ $solicitation->status == 'concluida' ? 'selected' : '' }}>Concluída
                            </option>
                        </select>
                    </td>

                    <td style="display: flex; justify-content: flex-end;">
                        <button 
                            style="display: none;"
                            id="save-button-{{ $solicitation->id }}"
                            class="btn btn-primary"
                            onclick="atualizarStatus({{ $solicitation->id }})"
                        >Salvar</button>

                        <a href="{{ route('solicitations.show', $solicitation->id) }}">
                            <button class="btn btn-success" type="submit">Ver</button>
                        </a>
                        <a href="{{ route('solicitations.edit', $solicitation->id) }}">
                            <button class="btn btn-primary" type="submit">Editar</button>
                        </a>
                        <a href="#" onclick="document.getElementById('delete-form-{{ $solicitation->id }}').submit()">
                            <button class="btn btn-danger" type="button">Excluir</button>
                        </a>
                        <form id="delete-form-{{ $solicitation->id }}" action="{{ route('solicitations.destroy', $solicitation->id) }}" method="post" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="solicitations/create" class="btn btn-success">
        <i class="fa fa-plus"></i> Solicitação
    </a>
@endsection

<script src="https://unpkg.com/axios@0.21.1/dist/axios.min.js"></script>
<script>
    function showSaveButton(id) {
        var select = document.getElementById('status-' + id);
        var button = document.getElementById('save-button-' + id);
        if (select.value !== select.defaultValue) {
            button.style.display = 'block';
        } else {
            button.style.display = 'none';
        }
    }

    function hideSaveButton(id) {
        var button = document.getElementById('save-button-' + id);
        button.style.display = 'none';
    }

    function atualizarStatus(id) {
        var status = document.getElementById("status-" + id).value;
        axios.patch("/solicitations/" + id, { status: status })
        .then(function (response) {
            hideSaveButton(id);
            window.location.href = '/solicitations';
        })
        .catch(function (error) {
            console.log(error);
        });
    };
</script>
    