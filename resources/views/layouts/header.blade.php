@extends('layouts.app')

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/solicitations">Smart Solicitations</a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            @if (Auth::user()->name === 'Admin')
                <li class="nav-item">
                    <a class="nav-link" href="/logs">Logs do Sistema</a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" href="/solicitations/create">Criar Solicitação</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/logout">Sair</a>
            </li>
        </ul>
    </div>
</nav>