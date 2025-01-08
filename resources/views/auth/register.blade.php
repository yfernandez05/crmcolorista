@extends('layouts.auth')

@section('content')
<form class="form-material text-center" method="POST" action="{{ route('register') }}">
    @csrf

    <div class="form-group m-t-40">
        <div class="col-xs-12">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" 
                value="{{ old('name') }}" required autocomplete="name" placeholder="{{ __('Name') }}" autofocus />

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" 
                    value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}"/>

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
                    required autocomplete="new-password" placeholder="{{ __('Password') }}" />

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" 
                    required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}" />
        </div>
    </div>
    <div class="form-group text-center m-t-20">
        <div class="col-xs-12">
            <button class="btn btn-success btn-lg btn-block text-uppercase btn-rounded" type="submit">
                {{ __('Register') }}
            </button>
        </div>
    </div>
</form>
<div class="form-group m-b-0">
    <div class="col-sm-12 text-center">
        <p>¿Ya tienes una cuenta? <a href="{{url('/login')}}" class="text-success m-l-5"><b>{{ __('Login') }}</b></a></p>
    </div>
</div>
@endsection
