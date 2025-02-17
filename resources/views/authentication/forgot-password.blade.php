@extends('layout.layoutAuth')
@section('title', 'Forgot Password')
@section('content')
    <section class="auth forgot-password-page bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="{{ asset('assets/images/auth/forgotPasswordPage.png') }}" alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <h4 class="mb-12">Forgot Password</h4>
                    <p class="mb-32 text-secondary-light text-lg">Enter the email address associated with your account
                        and we will send you a link to reset your password.</p>
                </div>
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
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>
                        <input type="email" name="email" class="form-control h-56-px bg-neutral-50 radius-12"
                            placeholder="Enter Email">
                    </div>
                    <button type="submit"
                        class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Send</button>

                    <div class="text-center">
                        <a href="{{ route('showLoginPage') }}" class="text-primary-600 fw-bold mt-24">Back to Sign
                            In</a>
                    </div>

                    <div class="mt-120 text-center text-sm">
                        <p class="mb-0">Already have an account? <a href="{{ route('showLoginPage') }}"
                                class="text-primary-600 fw-semibold">Sign In</a></p>
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
