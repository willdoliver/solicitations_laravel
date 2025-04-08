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

    <div class="mb-3 d-flex justify-content-between">
        <form action="{{ route('solicitations.index') }}" method="GET" style="width: 100%;">
            <div class="input-group d-flex">
                <input type="text" class="form-control flex-grow-1" name="search"
                    placeholder="Buscar por título ou descrição"
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
        <div class="export-csv">
            <a href="{{ route('solicitations.export', ['search' => request('search')]) }}" class="btn btn-success">Exportar para CSV</a>
        </div>
    </div>


    <table class="table table-striped" id="solicitations-table">
        <thead>
            <tr>
            <th onclick="sortTable('title')">
                    Título
                    <i id="title-sort-icon" class="fas fa-sort"></i>
                </th>
                <th>Descrição</th>
                <th onclick="sortTable('category')">
                    Categoria
                    <i id="category-sort-icon" class="fas fa-sort"></i>
                </th>
                <th onclick="sortTable('created_at')">
                    Data de Criação
                    <i id="created_at-sort-icon" class="fas fa-sort"></i>
                </th>
                <th>Solicitante</th>
                <th onclick="sortTable('status')">
                    Status
                    <i id="status-sort-icon" class="fas fa-sort"></i>
                </th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @if (!isset($solicitations) || $solicitations->isEmpty())
                <tr>
                    <td colspan="7">Nenhuma solicitação encontrada.</td>
                </tr>
            @else
                @foreach($solicitations as $solicitation)
                    <tr data-title="{{ $solicitation->title }}"
                        data-category="{{ $solicitation->category }}"
                        data-created_at="{{ $solicitation->created_at->timestamp }}"
                        data-status="{{ $solicitation->status }}
                    ">
                        <td>
                            <a href="{{ route('solicitations.show', $solicitation->id) }}">
                                {{ $solicitation->title }}
                            </a>
                        </td>
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

                        <td class="d-flex justify-content-end">
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
            @endif
        </tbody>
    </table>

    <a href="solicitations/create" class="btn btn-success">
        <i class="fa fa-plus"></i> Solicitação
    </a>
@endsection

<style>
    .export-csv {
        text-align: right;
    }

    .btn {
        margin: 0 0.3em;
    }
</style>

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

    let currentOrderBy = 'created_at';
    let currentOrderDirection = 'desc';

    function sortTable(column) {
        const table = document.getElementById('solicitations-table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        // Determine the new order direction
        let newOrderDirection = 'asc';
        if (currentOrderBy === column && currentOrderDirection === 'asc') {
            newOrderDirection = 'desc';
        }

        // Sort the rows
        rows.sort((a, b) => {
            const aValue = a.dataset[column];
            const bValue = b.dataset[column];

            let comparison = 0;

            if (column === 'created_at') {
                comparison = parseInt(aValue) - parseInt(bValue);
            } else {
                comparison = aValue.localeCompare(bValue);
            }

            return newOrderDirection === 'asc' ? comparison : -comparison;
        });

        // Update the table
        rows.forEach(row => tbody.appendChild(row));

        // Update the current order
        currentOrderBy = column;
        currentOrderDirection = newOrderDirection;

        // Update the sort icons
        updateSortIcons();
    }

    function updateSortIcons() {
        const icons = document.querySelectorAll('i[id$="-sort-icon"]');

        icons.forEach(icon => {
            icon.classList.remove('fa-sort-up', 'fa-sort-down');
            icon.classList.add('fa-sort');

        });

        const currentIcon = document.getElementById(currentOrderBy + '-sort-icon');
        if(currentIcon){
            currentIcon.classList.remove('fa-sort');
            currentIcon.classList.add(currentOrderDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down');
        }
    }

    updateSortIcons();
</script>
    