@extends('layout.layout')
@section('title', 'My Profile')
@php
    $title = 'My Profile';
    $subTitle = 'My Profile';
@endphp
@section('css')
    <link href="{{ URL::asset('assets/libs/choices.js/choices.js.min.css') }}" rel="stylesheet">
    <style>
        /* Styling untuk menyesuaikan dengan form-control Bootstrap */
        .choices {
            width: 100% !important;
            max-width: 100% !important;
        }

        .choices__inner {
            width: 100% !important;
            min-height: calc(1.5em + 0.75rem + 2px) !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 1rem !important;
            font-weight: 400 !important;
            line-height: 1.5 !important;
            color: var(--bs-body-color) !important;
            background-color: #fff !important;
            background-clip: padding-box;
            border: var(--bs-border-width) solid var(--bs-border-color);
            border-radius: var(--bs-border-radius);
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            min-width: 0 !important;
            max-width: 100% !important;
        }

        .choices.is-focused .choices__inner {
            border-color: #86b7fe !important;
            outline: 0 !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }

        .choices__input {
            background-color: transparent !important;
            margin: 0 !important;
            padding: 0 !important;
            border: 0 !important;
            width: 100% !important;
            vertical-align: baseline !important;
            margin-bottom: 0 !important;
        }

        .choices__list--dropdown {
            border: 1px solid #ced4da !important;
            border-radius: 0.25rem !important;
            margin-top: 2px !important;
        }

        /* Untuk multiple select */
        .choices__list--multiple .choices__item {
            background-color: #0d6efd !important;
            border: 1px solid #0d6efd !important;
            border-radius: 0.25rem !important;
            color: white !important;
            margin: 2px !important;
        }
    </style>
@endsection
@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                        @if (auth()->user()->hasRole('Organizer'))
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link d-flex align-items-center px-24 @if (Auth::user()->hasRole('Organizer')) active @endif"
                                    id="pills-change-organizer-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-change-organizer" type="button" role="tab"
                                    aria-controls="pills-change-organizer" aria-selected="false" tabindex="-1">
                                    Data Penyelenggara
                                </button>
                            </li>
                        @endif
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link d-flex align-items-center px-24 @if (!Auth::user()->hasRole('Organizer')) active @endif"
                                id="pills-edit-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-profile"
                                type="button" role="tab" aria-controls="pills-edit-profile" aria-selected="true">
                                Pengaturan Akun
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        @if (Auth::user()->hasRole('Organizer'))
                            <div class="tab-pane fade  @if (Auth::user()->hasRole('Organizer')) show active @endif"
                                id="pills-change-organizer" role="tabpanel" aria-labelledby="pills-change-organizer-tab"
                                tabindex="0">
                                <!-- Upload Image Start -->
                                <h6 class="text-md text-primary-light mb-16">Logo Penyelenggara</h6>
                                <form action="{{ route('profileOrganizer.update', Auth::user()->organizer->id) }}"
                                    enctype="multipart/form-data" method="POST">
                                    @csrf
                                    <div class="mb-24 mt-16">
                                        <div class="avatar-upload">
                                            <div
                                                class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                                <input type='file' id="logoUpload" accept=".png, .jpg, .jpeg"
                                                    name="logo" hidden>
                                                <label for="logoUpload"
                                                    class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                    <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                                </label>
                                            </div>
                                            <div class="avatar-preview">
                                                @php
                                                    $logoPath = $user->organizer->logo;
                                                    $logo = $logoPath
                                                        ? asset('storage/' . $logoPath)
                                                        : asset($logoPath);
                                                @endphp

                                                <div id="logoPreview" style="background-image: url('{{ $logo }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Upload Image End -->
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="shorten_name"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Singkatan
                                                    Nama
                                                    <span class="text-danger-600">*</span></label>
                                                <input type="text" class="form-control radius-8" id="shorten_name"
                                                    name="shorten_name" value="{{ $user->organizer->shorten_name }}"
                                                    placeholder="Masukkan Singkatan Nama">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="description"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Deskripsi<span
                                                        class="text-danger-600">*</span></label></label>
                                                <textarea class="form-control" id="description" name="description" rows="4" cols="50"
                                                    placeholder="Masukkan nama event..." required>{{ $user->organizer->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="vision"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Visi
                                                    <span class="text-danger-600">*</span></label>
                                                <textarea class="form-control" id="vision"name="vision" rows="4" cols="50"
                                                    placeholder="Masukkan nama event..." required>{{ $user->organizer->vision }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-20">
                                                <label for="mision"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Misi
                                                    <span class="text-danger-600">*</span></label>
                                                <input class="form-control" name="mision"
                                                    value="{{ $user->organizer->mision }}" id="mision"
                                                    placeholder="Masukkan misi..." />
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Link
                                                    Instagram </label>
                                                <input type="text" class="form-control radius-8"
                                                    name="link_media_social[instagram]"
                                                    placeholder="https://instagram.com/username"
                                                    value="{{ $user->organizer->link_media_social['instagram'] }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Link
                                                    Youtube </label>
                                                <input type="text" class="form-control radius-8"
                                                    name="link_media_social[youtube]"
                                                    placeholder="https://youtube.com/..."
                                                    value="{{ $user->organizer->link_media_social['youtube'] }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Link
                                                    TikTok </label>
                                                <input type="text" class="form-control radius-8"
                                                    name="link_media_social[tiktok]" placeholder="https://tiktok.com/..."
                                                    value="{{ $user->organizer->link_media_social['tiktok'] }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="mb-20">
                                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Link
                                                    X </label>
                                                <input type="text" class="form-control radius-8"
                                                    name="link_media_social[x]" placeholder="https://x.com/..."
                                                    value="{{ $user->organizer->link_media_social['x'] ?? '' }}">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <button type="button"
                                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <div class="tab-pane fade @if (!auth()->user()->hasRole('Organizer')) show active @endif"
                            id="pills-edit-profile" role="tabpanel" aria-labelledby="pills-edit-profile-tab"
                            tabindex="0">
                            <form action="{{ route('profileUser.update', ['id' => Auth::user()->id]) }}"
                                enctype="multipart/form-data" method="POST">
                                @csrf
                                @if (!auth()->user()->hasRole('Organizer'))
                                    <!-- Upload Image Start -->
                                    <h6 class="text-md text-primary-light mb-16">Foto Profil</h6>
                                    <div class="mb-24 mt-16">
                                        <div class="avatar-upload">
                                            <div
                                                class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                                <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg"
                                                    name="profile_picture" hidden>
                                                <label for="imageUpload"
                                                    class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                    <iconify-icon icon="solar:camera-outline"
                                                        class="icon"></iconify-icon>
                                                </label>
                                            </div>
                                            <div class="avatar-preview">
                                                <div id="imagePreview">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Upload Image End -->
                                @endif

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                                Lengkap
                                                <span class="text-danger-600">*</span></label>
                                            <input type="text" class="form-control radius-8" id="name"
                                                name="name" value="{{ $user->name }}"
                                                placeholder="Masukkan Nama Lengkap">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="username"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Username
                                                <span class="text-danger-600">*</span></label>
                                            <input type="text" class="form-control radius-8" id="username"
                                                name="username" value="{{ $user->username }}"
                                                placeholder="Masukkan Username">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="email"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Email
                                                <span class="text-danger-600">*</span></label>
                                            <input type="email" class="form-control radius-8" id="email"
                                                name="email" value="{{ $user->email }}" placeholder="Masukkan Email">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="number"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor
                                                Handphone/WhatsApp</label>
                                            <input type="tel" class="form-control radius-8" id="number"
                                                name="phone_number"
                                                value="{{ Auth::user()->hasRole('Organizer') ? $user->organizer->whatsapp_number : $user->phone_number }}"
                                                placeholder="Masukkan Nomor HP/WA">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-20">
                                        <label for="your-password"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Password
                                            Baru
                                            <span class="text-danger-600">*</span></label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control radius-8" id="your-password"
                                                name="password" placeholder="Masukkan Password Baru">
                                            <span
                                                class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                                data-toggle="#your-password"
                                                style="z-index: 10; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-end gap-3">
                                    <button type="button"
                                        class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js') }}"></script>
    <script>
        const misionInput = new Choices('#mision', {
            removeItemButton: true, // Aktifkan tombol remove
            placeholderValue: 'Masukkan misi organizer...', // Placeholder untuk input
            maxItemCount: 10, // Maksimal jumlah input
            searchEnabled: false, // Matikan fitur pencarian
            delimiter: '|',
        });
        // ======================== Upload Image Start =====================
        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $("#imagePreview").css("background-image", "url(" + e.target.result + ")");
                    $("#imagePreview").hide();
                    $("#imagePreview").fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").change(function() {
            readURL(this);
        });

        function readURLOrganizer(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $("#logoPreview").css("background-image", "url(" + e.target.result + ")");
                    $("#logoPreview").hide();
                    $("#logoPreview").fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#logoUpload").change(function() {
            readURLOrganizer(this);
        });
        // ======================== Upload Image End =====================

        // ================== Password Show Hide Js Start ==========
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on("click", function() {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
        // Call the function
        initializePasswordToggle(".toggle-password");
    </script>
@endpush
