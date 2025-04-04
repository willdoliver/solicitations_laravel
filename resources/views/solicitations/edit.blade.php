@extends('layouts.header')

@section('content')
    <h1>Editar Solicitação {{ $solicitation->id }}</h1>
    <form 
        @if($solicitation->status == 'concluida') disabled @endif
        action="{{ route('solicitations.update', $solicitation->id) }}" method="post"
    >
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $solicitation->title }}">
        </div>
        <div class="form-group">
            <label for="description">Descrição</label>
            <textarea class="form-control" id="description" name="description">{{ $solicitation->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="category">Categoria</label>
            <select class="form-control" id="category" name="category">
                <option value="TI" {{ $solicitation->category == 'TI' ? 'selected' : '' }}>TI</option>
                <option value="Suprimentos" {{ $solicitation->category == 'Suprimentos' ? 'selected' : '' }}>Suprimentos</option>
                <option value="RH" {{ $solicitation->category == 'RH' ? 'selected' : '' }}>RH</option>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status">
                <option value="aberta" {{ $solicitation->status == 'aberta' ? 'selected' : '' }}>Aberta</option>
                <option value="em_andamento" {{ $solicitation->status == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                <option value="concluida" {{ $solicitation->status == 'concluida' ? 'selected' : '' }}>Concluída</option>
            </select>
        </div>

        @if ($solicitation->status == 'concluida')
            <div class="alert alert-warning">
                Não é possível editar uma solicitação já concluída
            </div>
        @else
            <a href="{{ route('solicitations.index') }}" class="btn btn-secondary">Voltar</a>
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <button type="button" class="btn btn-danger" onclick="excluirSolicitacao({{ $solicitation->id }})">Excluir</button>
        @endif


    </form>
@endsection

<script src="https://unpkg.com/axios@0.21.1/dist/axios.min.js"></script>
<script>
    function excluirSolicitacao(id) {
        if (confirm("Tem certeza que deseja excluir essa solicitação?")) {
            axios.delete("/solicitations/" + id)
            .then(function (response) {
                window.location.href = response.data.redirect_url;
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    }
</script>