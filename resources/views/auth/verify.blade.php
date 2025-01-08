@extends('layouts.auth')

@section('content')
<div class="form-group mt-4">
    <div class="col-xs-12">
        <h3>{{ __('Verify Your Email Address') }}</h3>
    </div>
</div>

@if (session('resent'))
    <div class="alert alert-success" role="alert">
        {{ __('A fresh verification link has been sent to your email address.') }}
    </div>
@endif

<div class="form-group mt-4">
    <div class="col-xs-12">
        {{ __('Before proceeding, please check your email for a verification link.') }}
        {{ __('If you did not receive the email') }},
        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
        </form>
    </div>
</div>
<div class="form-group m-b-0">
    <div class="col-sm-12 text-center">
        <p>¿Ya tienes una cuenta? <a href="{{url('/login')}}" class="text-success m-l-5"><b>{{ __('Login') }}</b></a></p>
    </div>
</div>
@endsection
