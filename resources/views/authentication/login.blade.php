@extends('layout.layoutAuth')
@section('title', 'Login')
@section('content')
    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="{{ asset('assets/images/auth/loginPage.png') }}" alt="">
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
                    <h4 class="mb-12">Sign In to your Account</h4>
                    <p class="mb-32 text-secondary-light text-lg">Welcome back! please enter your detail</p>
                </div>
                @if (session('status'))
                    <div class="alert alert-success bg-success-100 text-success-600 border-success-600 border-start-width-4-px border-top-0 border-end-0 border-bottom-0 px-24 py-13 mb-24 fw-semibold text-lg radius-4 d-flex align-items-center justify-content-between"
                        role="alert">
                        <div class="d-flex align-items-center gap-2">
                            <iconify-icon icon="akar-icons:double-check" class="icon text-xl"></iconify-icon>
                            {{ session('status') }}
                        </div>
                        <button class="remove-button text-success-600 text-xxl line-height-1">
                            <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon>
                        </button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger bg-danger-100 text-danger-600 border-danger-600 border-start-width-4-px border-top-0 border-end-0 border-bottom-0 px-24 py-13 mb-24 fw-semibold text-lg radius-4 d-flex align-items-center justify-content-between"
                        role="alert">
                        <div class="d-flex align-items-center gap-2">
                            <iconify-icon icon="mingcute:delete-2-line" class="icon text-xl"></iconify-icon>
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                        <button class="remove-button text-danger-600 text-xxl line-height-1">
                            <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon>
                        </button>
                    </div>
                @endif
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    @method('post')
                    {{-- Email/Username --}}
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:user"></iconify-icon>
                        </span>
                        <input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="login"
                            placeholder="Masukkan Username/Email Anda"
                            @if (Cookie::has('userUsername')) value="{{ Cookie::get('userUsername') }}" @endif required>
                    </div>
                    {{-- Password --}}
                    <div class="position-relative mb-20">
                        <div class="icon-field">
                            <span class="icon top-50 translate-middle-y">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input type="password" class="form-control h-56-px bg-neutral-50 radius-12" name="password"
                                id="password" placeholder="Masukkan Password Anda" required>
                        </div>
                        <span
                            class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                            data-toggle="password"></span>
                    </div>
                    <div class="">
                        <div class="d-flex justify-content-between gap-2">
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input border border-neutral-300" type="checkbox" name="rememberMe"
                                    id="remeber">
                                <label class="form-check-label" for="remeber">Remember me </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-primary-600 fw-medium">Forgot
                                Password?</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
                        Sign In</button>

                    <div class="mt-32 text-center text-sm">
                        <p class="mb-0">Don’t have an account? <a href="{{ route('showRegisterPage') }}"
                                class="text-primary-600 fw-semibold">Sign Up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <x-script />
    <script>
        $(".remove-button").on("click", function() {
            $(this).closest(".alert").addClass("d-none")
        });
    </script>
@endsection
