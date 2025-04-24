@extends('layout.landingPageLayout')

@section('title', 'Profil')
@section('content')
    <!-- ==================== Breadcrumb Start Here ==================== -->
    <section class="breadcrumb py-120 bg-main-25 position-relative z-1 overflow-hidden mb-0">
        <img src="{{ asset('assets/images/shapes/shape1.png') }}" alt=""
            class="shape one animation-rotation d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape2.png') }}" alt=""
            class="shape two animation-scalation d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape3.png') }}" alt=""
            class="shape eight animation-walking d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape5.png') }}" alt=""
            class="shape six animation-walking d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape4.png') }}" alt="" class="shape four animation-scalation">
        <img src="{{ asset('assets/images/shapes/shape4.png') }}" alt="" class="shape nine animation-scalation">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="breadcrumb__wrapper">
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Data Profil</h1>
                        <ul class="breadcrumb__list d-flex align-items-center justify-content-center gap-4">
                            <li class="breadcrumb__item">
                                <a href="{{ route('home') }}"
                                    class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium">
                                    <i class="text-lg d-inline-flex ph-bold ph-house"></i> Beranda</a>
                            </li>
                            <li class="breadcrumb__item">
                                <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                            </li>
                            <li class="breadcrumb__item">
                                <span class="text-main-two-600">Data Profil </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <ul class="nav bordered-tab border border-top-0 border-start-0 border-end-0 d-inline-flex nav-pills mb-16"
            id="pills-tab" role="tablist">
            @hasrole('Tenant')
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 rounded-pill py-10 active" id="pills-asset-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-asset" type="button" role="tab" aria-controls="pills-asset"
                        aria-selected="false">Peminjaman Aset</button>
                </li>
            @endhasrole
            <li class="nav-item" role="presentation">
                <button class="nav-link px-16 rounded-pill  py-10 " id="pills-editprofile-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-editprofile" type="button" role="tab" aria-controls="pills-editprofile"
                    aria-selected="true">Edit Profile</button>
            </li>
            @hasrole('Participant')
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 rounded-pill py-10" id="pills-prodi-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-prodi" type="button" role="tab" aria-controls="pills-prodi"
                        aria-selected="false">Event</button>
                </li>
            @endhasrole
        </ul> --}}

    {{-- content --}}
    <div class="container border border-main  rounded-12 p-24 my-20 ">
        <h5 class="mb-0">Informasi Pengguna</h5>
        <span class="d-block border border-main-50 my-24 border-dashed"></span>
        <form id="updateProfileForm" action="{{ route('profileUser.update', $user->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="row gy-4">
                <div class="col-sm-6">
                    <label for="name" class="text-neutral-700 text-lg fw-medium mb-12">Nama Lengkap
                        <span class="text-danger-600">*</span> </label>
                    <input type="text"
                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600" id="name"
                        name="name" value="{{ $user->name }}" placeholder="Masukkan Nama Lengkap..." required>
                </div>
                <div class="col-sm-6">
                    <label for="username" class="text-neutral-700 text-lg fw-medium mb-12">Username
                        <span class="text-danger-600">*</span> </label>
                    <input type="text"
                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600" id="username"
                        name="username" value="{{ $user->username }}" placeholder="Masukkan Username..." required>
                </div>
                <div class="col-sm-6">
                    <label for="email" class="text-neutral-700 text-lg fw-medium mb-12">Email <span
                            class="text-danger-600">*</span> </label>
                    <input type="email"
                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600" id="email"
                        name="email" value="{{ $user->email }}" placeholder="Masukkan Email..." required>
                </div>
                <div class="col-sm-6">
                    <label for="password" class="text-neutral-700 text-lg fw-medium mb-12">Password</label>
                    <div class="position-relative">
                        <input type="password"
                            class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                            id="password" placeholder="Enter Your Password...">
                        <span
                            class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph-bold ph-eye-closed"
                            id="#password"></span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="Phone" class="text-neutral-700 text-lg fw-medium mb-12">Nomor
                        Handphone/WA
                        <span class="text-danger-600">*</span> </label>
                    <input type="number"
                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600" id="Phone"
                        name="phone_number" value="{{ $user->phone_number }}" placeholder="Masukkan Nomor Handphone/WA..."
                        required>
                </div>
                <div class="col-sm-6">
                    <label for="selectProvince" class="text-neutral-700 text-lg fw-medium mb-12">Provinsi
                        <span class="text-danger-600">*</span> </label>
                    <select id="selectProvince"
                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600 form-select py-14 select-province"
                        name="province" data-selected="{{ $user->province }}" required>
                        <option value="" disabled selected>Pilih Provinsi</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="selectCity" class="text-neutral-700 text-lg fw-medium mb-12">Kabupaten/Kota
                        <span class="text-danger-600">*</span> </label>
                    <select id="selectCity"
                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600 form-select py-14 select-city"
                        name="city" data-selected="{{ $user->city }}" required>
                        <option value="" disabled selected>Pilih Kabupaten/Kota</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="selectSubdistrict" class="text-neutral-700 text-lg fw-medium mb-12">Kecamatan
                        <span class="text-danger-600">*</span> </label>
                    <select id="selectSubdistrict"
                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600 form-select py-14 select-subdistrict"
                        name="subdistrict" data-selected="{{ $user->subdistrict }}" required>
                        <option value="" disabled selected>Pilih Kecamatan</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="selectVillage" class="text-neutral-700 text-lg fw-medium mb-12">Desa/Kelurahan
                        <span class="text-danger-600">*</span> </label>
                    <select id="selectVillage"
                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600 form-select py-14 select-village"
                        name="village" data-selected="{{ $user->village }}">
                        <option value="" disabled selected>Pilih Desa/Kelurahan</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="upPhotoProfile" class="text-neutral-700 text-lg fw-medium mb-12 d-block">Foto
                        Profil</label>
                    <input id="upPhotoProfile"
                        class="form-control mt-20 rounded-pill border-transparent focus-border-main-600 d-block"
                        type="file" accept=".png,.jpeg,.jpg" name="profile_picture">
                </div>

                <div class="col-sm-12">
                    <label for="address" class="text-neutral-700 text-lg fw-medium mb-12">Alamat Lengkap
                    </label>
                    <textarea class="common-input bg-main-25 rounded-24 border-transparent focus-border-main-600" id="address"
                        name="address" placeholder="Masukkan Alamat Lengkap..." required>{{ $user->address }}</textarea>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-24">
                <button type="submit" class="btn btn-main rounded-pill flex-center gap-8">
                    Perbarui Data
                    <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                </button>
            </div>

        </form>
    </div>



@endsection
@push('script')
    <script>
        $(document).ready(function() {
            let apiBaseUrl = "https://iemaduddin.github.io/api-wilayah-indonesia/api";
            let cachedProvinces = null;

            function loadDropdown(url, target, selected = null, callback = null) {
                target.empty().append('<option value="" disabled selected>Pilih</option>');
                $.get(url, function(data) {
                    data.forEach(item => {
                        target.append(
                            `<option value="${item.id}" ${item.id == selected ? 'selected' : ''}>${item.name}</option>`
                        );
                    });
                    if (callback) callback();
                });
            }

            function loadProvinces(target, selected = null) {
                if (cachedProvinces) {
                    target.empty().append('<option value="" disabled>Pilih Provinsi</option>');
                    cachedProvinces.forEach(item => {
                        target.append(`<option value="${item.id}">${item.name}</option>`);
                    });

                    // Set selected setelah dropdown diisi
                    if (selected) {
                        target.val(selected).trigger('change');
                    }
                } else {
                    $.get(`${apiBaseUrl}/provinces.json`, function(data) {
                        cachedProvinces = data;
                        loadProvinces(target, selected); // Load ulang dengan selected
                    });
                }
            }


            loadProvinces($('.select-province'));

            $(document).on('change', '.select-province', function() {
                let provinceId = $(this).val();
                let citySelect = $('.select-city');
                citySelect.empty().append(
                    '<option value="" disabled selected>Pilih Kabupaten/Kota</option>');
                $('.select-subdistrict, .select-village').empty().append(
                    '<option value="">Pilih Kabupaten/Kota</option>');
                if (provinceId) loadDropdown(`${apiBaseUrl}/regencies/${provinceId}.json`, citySelect);
            });

            $(document).on('change', '.select-city', function() {
                let cityId = $(this).val();
                let subdistrictSelect = $('.select-subdistrict');
                subdistrictSelect.empty().append(
                    '<option value="" disabled selected>Pilih Kecamatan</option>');
                $('.select-village').empty().append('<option value="">Pilih Kecamatan</option>');
                if (cityId) loadDropdown(`${apiBaseUrl}/districts/${cityId}.json`, subdistrictSelect);
            });

            $(document).on('change', '.select-subdistrict', function() {
                let subdistrictId = $(this).val();
                let villageSelect = $('.select-village');
                villageSelect.empty().append(
                    '<option value="" disabled selected>Pilih Kelurahan/Desa</option>');
                if (subdistrictId) loadDropdown(`${apiBaseUrl}/villages/${subdistrictId}.json`,
                    villageSelect);
            });




            let formContainer = $('#updateProfileForm');
            let selectedProvince = formContainer.find('.select-province').data('selected');
            let selectedCity = formContainer.find('.select-city').data('selected');
            let selectedSubdistrict = formContainer.find('.select-subdistrict').data('selected');
            let selectedVillage = formContainer.find('.select-village').data('selected');

            loadProvinces(formContainer.find('.select-province'), selectedProvince);
            if (selectedProvince) {
                loadDropdown(`${apiBaseUrl}/regencies/${selectedProvince}.json`, formContainer.find(
                    '.select-city'), selectedCity, function() {
                    if (selectedCity) {
                        loadDropdown(`${apiBaseUrl}/districts/${selectedCity}.json`,
                            formContainer
                            .find('.select-subdistrict'), selectedSubdistrict,
                            function() {
                                if (selectedSubdistrict) {
                                    loadDropdown(
                                        `${apiBaseUrl}/villages/${selectedSubdistrict}.json`,
                                        formContainer.find('.select-village'),
                                        selectedVillage);
                                }
                            });
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notyf = new Notyf({
                // duration: 4000,
                position: {
                    x: 'right',
                    y: 'bottom',
                },
                dismissible: true
            });

            // Handle profile update form submission
            const updateProfileForm = document.getElementById('updateProfileForm');
            if (updateProfileForm) {
                updateProfileForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const actionUrl = this.getAttribute('action');

                    // Add method-spoofing for Laravel
                    formData.append('_method', 'POST');

                    fetch(actionUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => Promise.reject(err));
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.status === 'success') {
                                // Show success message
                                notyf.success(data.message || 'Profile updated successfully!');

                                // Update form fields with new data if provided in response
                                if (data.user) {
                                    const user = data.user;

                                    // Update text inputs
                                    updateProfileForm.querySelector('#name').value = user.name || '';
                                    updateProfileForm.querySelector('#username').value = user
                                        .username || '';
                                    updateProfileForm.querySelector('#email').value = user.email || '';
                                    updateProfileForm.querySelector('#Phone').value = user
                                        .phone_number || '';
                                    updateProfileForm.querySelector('#address').value = user.address ||
                                        '';

                                    // Update select elements
                                    if (user.province) {
                                        const provinceSelect = updateProfileForm.querySelector(
                                            '#selectProvince');
                                        provinceSelect.dataset.selected = user.province;
                                        // You might need to reload province options here
                                    }

                                    if (user.city) {
                                        const citySelect = updateProfileForm.querySelector(
                                            '#selectCity');
                                        citySelect.dataset.selected = user.city;
                                        // You might need to reload city options here
                                    }

                                    if (user.subdistrict) {
                                        const subdistrictSelect = updateProfileForm.querySelector(
                                            '#selectSubdistrict');
                                        subdistrictSelect.dataset.selected = user.subdistrict;
                                        // You might need to reload subdistrict options here
                                    }

                                    if (user.village) {
                                        const villageSelect = updateProfileForm.querySelector(
                                            '#selectVillage');
                                        villageSelect.dataset.selected = user.village;
                                        // You might need to reload village options here
                                    }

                                    // If there's a profile picture preview, update it
                                    if (user.profile_picture_url) {
                                        const profilePicture = document.querySelector(
                                            '.profile-picture'
                                        ); // Add this class to your profile picture element
                                        if (profilePicture) {
                                            profilePicture.src = user.profile_picture_url;
                                        }
                                    }
                                }

                                // Clear password field
                                const passwordInput = updateProfileForm.querySelector('#password');
                                if (passwordInput) {
                                    passwordInput.value = '';
                                }

                                // Clear file input
                                const fileInput = updateProfileForm.querySelector('#upPhotoProfile');
                                if (fileInput) {
                                    fileInput.value = '';
                                }
                            }
                        })
                        .catch(error => {
                            if (error.errors) {
                                Object.values(error.errors).forEach(messages => {
                                    messages.forEach(message => {
                                        notyf.error(message);
                                    });
                                });
                            } else {
                                notyf.error(error.message ||
                                    'An error occurred while updating profile!');
                            }
                        });
                });
            }
        });
    </script>
@endpush
