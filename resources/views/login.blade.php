@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
                <div class="card-body">
                    <form method="POST" action="{{ route('auth') }}">
                        <div class="form-group row">
                            <div class="col-md-12 text-center">
                                <h2 class="font-weight-bold">Identificação MG Papelaria</h2>
                                <!-- <h2 class="font-weight-bold">{{ $redirect_uri }}</h2> -->
                                
                            </div>
                        </div>
                        @csrf
                        <div class="form-group row">
                            <label for="usuario" class="col-md-4 col-form-label text-md-right">{{ __('Usuário') }}</label>
                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="senha" class="col-md-4 col-form-label text-md-right">{{ __('Senha') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">
                                @if(!empty($error))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Usuário ou senha inválidos</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <input type="text" name="redirect_uri" value="{{ $redirect_uri }}" hidden>
                        <div class="form-group row">
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-8">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
@endsection
