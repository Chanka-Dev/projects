@extends('layouts.guest')

@section('content')
<p class="login-box-msg">Ingresa con tu cuenta</p>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="input-group mb-3">
        <input id="email" type="email"
               class="form-control @error('email') is-invalid @enderror"
               name="email" value="{{ old('email') }}"
               placeholder="Email" required autocomplete="email" autofocus>
        <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
        </div>
        @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="input-group mb-3">
        <input id="password" type="password"
               class="form-control @error('password') is-invalid @enderror"
               name="password" placeholder="Contraseña"
               required autocomplete="current-password">
        <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="row">
        <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Recordarme</label>
            </div>
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </div>
    </div>
</form>

@if (Route::has('password.request'))
<p class="mt-3 mb-1">
    <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
</p>
@endif
@endsection
