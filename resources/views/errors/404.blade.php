@extends('layout.layoutAuth')

@section('title', 'Not Found')

@section('content')
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="card text-center p-5">
            <div class="card-body">
                <img src="{{ asset('assets/images/error-404.png') }}" alt="Error Image" class="mb-4"
                    style="max-width: 300px;">
                <h6 class="mb-3">Page Not Found</h6>
                <p class="text-secondary-light">Sorry, the page you are looking for doesn't exist.</p>
                <a href="/" class="btn btn-primary-600  px-4 py-2">Back to Home</a>
            </div>
        </div>
    </div>
@endsection
