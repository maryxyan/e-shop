@extends('layouts.front.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">
        <div class="col-md-6 col-lg-5">
            @include('layouts.errors-and-messages')
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4 fs-3 text-body-emphasis">Creează cont nou</h3>
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="row mb-3{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 col-form-label text-md-end text-body">Nume</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus required>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 col-form-label text-md-end text-body">Adresă email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 col-form-label text-md-end text-body">Parolă</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end text-body">Confirmă parola</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4 d-grid">
                                <button type="submit" class="btn btn-primary fs-6 py-2">Înregistrează-te</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="mb-0 small text-body-secondary"><a href="{{ route('login') }}" class="text-decoration-none">Ai deja cont? Autentificare</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

