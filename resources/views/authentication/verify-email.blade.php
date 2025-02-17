@extends('layout.layoutAuth')
@section('title', 'Verifikasi Email')
@section('content')
    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="{{ asset('assets/images/auth/registerPage.png') }}" alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <a href="{{ route('home') }}" class="mb-40 max-w-290-px">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="">
                    </a>
                    <h4 class="mb-12">Email verifikasi telah dikirimkan</h4>
                    <p class="mb-32 text-secondary-light text-md">Silakan cek email Anda untuk verifikasi</p>
                </div>
                <form action="{{ route('verification.send') }}" method="POST">
                    @csrf
                    @method('post')

                    <input type="submit" value="Kirimkan ulang email verifikasi"
                        class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32"></input>
                </form>
            </div>
        </div>
    </section>
@endsection
<x-script />
