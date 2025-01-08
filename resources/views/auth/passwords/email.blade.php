@extends('layouts.auth')

@section('content')
<div class="form-group mt-4">
    <div class="col-xs-12">
        <h3>{{ __('Reset Password') }}</h3>
        <p class="text-muted">¡Ingrese su correo electrónico y le enviaremos las instrucciones! </p>
    </div>
</div>

@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif

<form class="form-material text-center" method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="form-group ">
        <div class="col-xs-12">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" 
                    value="{{ $email ?? old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}" autofocus />

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group text-center m-t-20">
        <div class="col-xs-12">
            <button class="btn c-melon btn-lg btn-block text-uppercase btn-rounded" type="submit">
                {{ __('Send Password Reset Link') }}
            </button>
        </div>
    </div>
</form>
<div class="form-group m-b-0">
    <div class="col-sm-12 text-center">
        <p>¿Ya tienes una cuenta? <a href="{{url('/login')}}" class="t-melon m-l-5"><b>{{ __('Login') }}</b></a></p>
    </div>
</div>
@endsection
