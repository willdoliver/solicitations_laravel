<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <a class="navbar-brand" href="{{ route('solicitations.index') }}">Smart Solicitations</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
             <li class="nav-item {{ request()->routeIs('solicitations.create') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('solicitations.create') }}">Criar Solicitação</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="navbar-text mr-3">Olá, {{ Auth::user()->name }}</span>
            </li>
            @if (Auth::user()->name === 'Admin')
                <li class="nav-item {{ request()->routeIs('logs.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('logs.index') }}">Logs do Sistema</a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   Sair
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>