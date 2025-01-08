@extends('layouts.auth')

@section('content')
<form class="form-material text-center" method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group m-t-40">
        <div class="col-xs-12">
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" 
                    value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}" autofocus />

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" 
                    required autocomplete="current-password" placeholder="{{ __('Password') }}" />

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <div class="d-flex no-block align-items-center justify-content-between">
                <div class="custom-control custom-checkbox mr-2">
                    <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                    <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                </div>

                @if (Route::has('password.request'))
                    <a class="text-muted ml-2" href="{{ route('password.request') }}">
                        <i class="fas fa-lock m-r-5"></i>
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
    <div class="form-group text-center m-t-20">
        <div class="col-xs-12">
            <button class="btn c-melon btn-lg btn-block text-uppercase btn-rounded" type="submit">
                {{ __('Login') }}
            </button>
        </div>
    </div>
</form>
{{-- <div class="form-group m-b-0">
    <div class="col-sm-12 text-center">
        ¿No tienes una cuenta? <a href="{{url('/register')}}" class="text-primary m-l-5"><b>{{ __('Register') }}</b></a>
    </div>
</div> --}}
@endsection
