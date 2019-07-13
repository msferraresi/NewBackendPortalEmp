@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('TSA - Arsat') }}</div>

                <div class="card-body">
                <form method="POST" action="{{route('loginAttempt')}}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @if(Session::has('credenciales'))
                                <div class="row">
                                    <div class="col-md-12">
                                    <span  style="list-style: none;color: red; font-weight:800;">{{Session::get('credenciales')}}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if (Session::has('credenciales'))
                        <span  style="list-style: none;color: red; font-weight:800;">{{Session::get('error')}}</span>
                        @endif
                        <div class="form-group row mb-0 justify-content-center">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Login') }}
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
