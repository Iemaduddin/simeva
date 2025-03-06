@extends('layout.layoutAuth')

@section('title', 'Forbidden')

@section('content')
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="card text-center p-5">
            <div class="card-body">
                <img src="{{ asset('assets/images/error-403.png') }}" alt="Error Image" class="mb-4"
                    style="max-width: 300px;">
                <h6 class="mb-3">403 - Access Denied</h6>
                <p class="text-secondary-light">Sorry, you don’t have permission to access this page.</p>
                <a href="{{ url()->previous() ?? '/' }}" class="btn btn-primary-600 radius-8 p-10">Back to Previous Page</a>

            </div>
        </div>
    </div>
@endsection
