@extends('layout.layoutAuth')
@section('title', 'Reset Password')
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
                    <h4 class="mb-12">Reset Your Password</h4>
                    <p class="mb-32 text-secondary-light text-lg">Enter the email address associated with your account
                        and we will send you a link to reset your password.</p>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger bg-danger-100 text-danger-600 border-danger-600 border-start-width-4-px border-top-0 border-end-0 border-bottom-0 px-24 py-13 mb-0 fw-semibold text-lg radius-4 d-flex align-items-center justify-content-between"
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

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('post')

                    {{-- Email/Username --}}
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:user"></iconify-icon>
                        </span>
                        <input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="email"
                            placeholder="Masukkan Email Anda" required>
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

                    {{-- Retype Password --}}
                    <div class="position-relative mb-20">
                        <div class="icon-field">
                            <span class="icon top-50 translate-middle-y">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input type="password" class="form-control h-56-px bg-neutral-50 radius-12"
                                name="password_confirmation" id="password_confirmation"
                                placeholder="Masukkan Ulang Password Anda" required>
                        </div>
                        <span
                            class="toggle-password-confirmation ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                            data-toggle="password_confirmation"></span>
                    </div>
                    {{-- hidden token --}}
                    <input type="hidden" class="form-control" name="token" id="token" value="{{ $token }}">
                    <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
                        Ubah Password
                    </button>
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
