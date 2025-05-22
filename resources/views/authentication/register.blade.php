@extends('layout.layoutAuth')
@section('title', 'Register')
@section('content')
    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="{{ asset('assets/images/auth/registerPage.png') }}" class="w-75" alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <!-- Logo Start -->
                    <div class="logo d-flex ">
                        <!-- Logo Section -->
                        <a href="{{ route('home') }}" class="link me-12">
                            <img src="{{ asset('assets/images/logo_polinema.png') }}" alt="Logo Polinema"
                                style="width: 4em;">
                        </a>
                        <!-- Title Section -->
                        <h2 class="text-main-600 m-0 fst-italic fw-bold"><i>SIMEVA</i></h2>
                    </div>
                    <!-- Logo End  -->
                    <h4 class="mb-12">Sign Up to your Account</h4>
                    <p class="mb-32 text-secondary-light text-lg">Welcome back! please enter your detail</p>
                </div>
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    @method('post')
                    <div class="btn-group mb-16 w-100" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="role_type" id="btnparticipant" value="participant"
                            checked>
                        <label class="btn btn-outline-dark px-20 py-11 w-100" for="btnparticipant">Participant</label>

                        <input type="radio" class="btn-check" name="role_type" id="btntenant" value="tenant">
                        <label class="btn btn-outline-dark px-20 py-11 w-100" for="btntenant">Tenant</label>
                    </div>
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="f7:person-crop-circle"></iconify-icon>
                        </span>
                        <input type="text" name="name" class="form-control h-56-px bg-neutral-50 radius-12"
                            placeholder="Nama Lengkap" required>
                    </div>
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="f7:person"></iconify-icon>
                        </span>
                        <input type="text" name="username" class="form-control h-56-px bg-neutral-50 radius-12"
                            placeholder="Username" required>
                    </div>
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>
                        <input type="email" name="email" class="form-control h-56-px bg-neutral-50 radius-12"
                            placeholder="Email" required>
                    </div>
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:phone-call"></iconify-icon>
                        </span>
                        <input type="number" name="phone_number" min="0"
                            class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Nomor Handphone/WA">
                    </div>
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:location-pin"></iconify-icon>
                        </span>
                        <input type="text" name="address" class="form-control h-56-px bg-neutral-50 radius-12"
                            placeholder="Alamat">
                    </div>
                    <div class="mb-20">
                        {{-- Password --}}
                        <div class="position-relative mb-20">
                            <div class="icon-field">
                                <span class="icon top-50 translate-middle-y">
                                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                                </span>
                                <input type="password" class="form-control h-56-px bg-neutral-50 radius-12" name="password"
                                    id="password" placeholder="Password" required>
                            </div>
                            <span
                                class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                data-toggle="password"></span>
                        </div>
                        <span class="mt-12 text-sm text-secondary-light">Your password must have at least 8
                            characters</span>
                    </div>

                    <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 ">
                        Sign Up</button>

                    <div class="mt-32 text-center text-sm">
                        <p class="mb-0">Already have an account? <a href="{{ route('login') }}"
                                class="text-primary-600 fw-semibold">Sign In</a></p>
                    </div>

                </form>
            </div>
        </div>
    </section>
@endsection
<x-script />
