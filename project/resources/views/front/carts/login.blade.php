@extends('layouts.front.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                @include('layouts.errors-and-messages')
                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow h-100">
                            <div class="card-body p-4">
                                <h4 class="card-title text-center mb-4 fs-4 text-body-emphasis">Autentificare</h4>
                                <form action="{{ route('cart.login') }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="mb-3">
                                        <label for="email" class="form-label text-body">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label text-body">Parolă</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Parola ta" required>
                                    </div>
                                    <div class="mb-4">
                                        <a href="{{ route('password.request') }}" class="text-muted small text-body-secondary">Am uitat parola?</a>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 fs-6 py-2">Autentificare acum</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow h-100">
                            <div class="card-body p-4">
                                <h4 class="card-title text-center mb-4 fs-4 text-body-emphasis">Cont nou</h4>
                                <form method="POST" action="{{ route('register') }}">
                                    {{ csrf_field() }}
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-body">Nume</label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label text-body">Adresă email</label>
                                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label text-body">Parolă</label>
                                        <input type="password" class="form-control" name="password" id="password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label text-body">Confirmă parola</label>
                                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary w-100 fs-6 py-2">Înregistrează-te</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

