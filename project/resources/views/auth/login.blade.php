@extends('layouts.front.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">
        <div class="col-md-6 col-lg-4">
            @include('layouts.errors-and-messages')
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4 fs-3 text-body-emphasis">Autentificare în contul dvs.</h3>
                    <form action="{{ route('login') }}" method="post">
                        {{ csrf_field() }}
                        <div class="mb-3">
                            <label for="email" class="form-label text-body">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" autofocus>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label text-body">Parolă</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Parola ta">
                        </div>
                        <div class="d-grid mb-3">
                            <button class="btn btn-primary fs-6 py-2" type="submit">Autentificare acum</button>
                        </div>
                        <div class="text-center text-body-secondary">
                            <a href="{{route('password.request')}}">Am uitat parola?</a>
                            <div class="mt-3">
                                <a href="{{route('register')}}" class="text-decoration-none">Nu ai cont? <strong>Înregistrează-te</strong></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

