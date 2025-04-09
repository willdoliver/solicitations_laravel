@extends('layouts.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Detalhes da Solicitação #{{ $solicitation->id }}</h4>
            <div>
                @if($solicitation->status !== 'concluida')
                    <a href="{{ route('solicitations.edit', $solicitation->id) }}" class="btn btn-sm btn-outline-primary mr-2">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </a>
                @endif
                <a href="{{ route('solicitations.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID:</dt>
                <dd class="col-sm-9">{{ $solicitation->id }}</dd>

                <dt class="col-sm-3">Título:</dt>
                <dd class="col-sm-9">{{ $solicitation->title }}</dd>

                <dt class="col-sm-3">Categoria:</dt>
                <dd class="col-sm-9">{{ $solicitation->category }}</dd>

                <dt class="col-sm-3">Status:</dt>
                <dd class="col-sm-9">
                    @php
                        $statusValue = $solicitation->status;
                        $badgeClass = match(strtolower($statusValue)) {
                            'aberta' => 'badge-warning',
                            'em_andamento' => 'badge-info',
                            'concluida' => 'badge-success',
                            default => 'badge-secondary',
                        };
                        $statusLabel = match(strtolower($statusValue)) {
                            'aberta' => 'Aberta',
                            'em_andamento' => 'Em Andamento',
                            'concluida' => 'Concluída',
                            default => ucfirst($statusValue),
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }} p-2">{{ $statusLabel }}</span>
                </dd>

                <dt class="col-sm-3">Solicitante:</dt>
                <dd class="col-sm-9">{{ $solicitation->user->name ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Data de Criação:</dt>
                <dd class="col-sm-9">{{ $solicitation->created_at->format('d/m/Y \à\s H:i') }}</dd>

                <dt class="col-sm-3">Última Atualização:</dt>
                <dd class="col-sm-9">{{ $solicitation->updated_at->format('d/m/Y \à\s H:i') }}</dd>

                <dt class="col-sm-3">Descrição:</dt>
                <dd class="col-sm-9">{!! nl2br(e($solicitation->description ?: '-')) !!}</dd>
            </dl>
        </div>
    </div>
@endsection