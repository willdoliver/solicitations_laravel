@extends('layouts.app')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                <h2 class="card-title">Login</h2>
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="form-group">
                            <label for="nome">Usuário</label>
                            <input type="text" class="form-control" id="nome-email" name="nome-email" placeholder="Nome ou E-mail">
                        </div>
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Senha">
                        </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>