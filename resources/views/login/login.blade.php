@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm"> 
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Login</h2>
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="form-group">
                            <label for="nome-email">Usuário</label>
                            <input type="text"
                                   class="form-control @error('nome-email', 'login') is-invalid @enderror"
                                   id="nome-email"
                                   name="nome-email"
                                   placeholder="Nome ou E-mail"
                                   value="{{ old('nome-email') }}"
                                   required autofocus>
                            @error('nome-email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="password"
                                   class="form-control @error('password', 'login') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Senha"
                                   required>
                             @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        @error('login')
                            <div class="alert alert-danger mt-3" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        @if ($errors->any() && !$errors->has('login') && !$errors->has('nome-email') && !$errors->has('password'))
                            <div class="alert alert-danger mt-3" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary btn-block mt-4">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection