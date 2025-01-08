@extends('layouts.auth')

@section('content')
<div class="form-group mt-4">
    <div class="col-xs-12">
        <h3>{{ __('Confirm Password') }}</h3>
        <p class="text-muted">{{ __('Please confirm your password before continuing.') }}</p>
    </div>
</div>

<form class="form-material text-center" method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <div class="form-group ">
        <div class="col-xs-12">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" 
                required autocomplete="current-password" placeholder="{{ __('Password') }}">

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group text-center m-t-20">
        <div class="col-xs-12">
            <button class="btn btn-success btn-lg btn-block text-uppercase btn-rounded" type="submit">
                {{ __('Confirm Password') }}
            </button>

            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </div>
    </div>
</form>
<div class="form-group m-b-0">
    <div class="col-sm-12 text-center">
        <p>¿Ya tienes una cuenta? <a href="{{url('/login')}}" class="text-success m-l-5"><b>{{ __('Login') }}</b></a></p>
    </div>
</div>
@endsection
