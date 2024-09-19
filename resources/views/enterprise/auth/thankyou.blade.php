@extends('layouts.enterprise.auth.layout')

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-header d-flex justify-content-center">
                        <a href="#" class="app-brand-link">
                            <span class="app-brand-logo demo">
                                <img src="{{ asset('logo.png') }}" class="img-fluid" width="140">
                            </span>
                        </a>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">Thanks for Registration</h5>
                        <p class="card-text">Your Password successfully set. Now you can Login!</p>
                        <div class="mb-3">
                            <a href="{{ route('enterprise.login.form') }}" class="btn d-grid w-100 btn"
                                style="background: #0EA7C1; color:white">{{ __('Log in') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
