@extends('layout.layout')
@section('title', 'Event Management')
@php
    $title = 'Event Management';
    $subTitle = 'Event Management';
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
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Event</h5>
                </div>
                <div class="card-body form-wizard">
                    <form id="addEventForm" action="{{ route('store.event') }}" class="needs-validation" novalidate
                        method="POST" data-table="{{ $shorten_name }}-EventsTable" enctype="multipart/form-data">
                        @csrf
                        <div class="form-wizard-header overflow-x-auto scroll-sm pb-8 mb-32">
                            <ul class="list-unstyled form-wizard-list">
                                <li class="form-wizard-list__item active">
                                    <div class="form-wizard-list__line">
                                        <span class="count">1</span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Data Event </span>
                                </li>
                                <li class="form-wizard-list__item">
                                    <div class="form-wizard-list__line">
                                        <span class="count">2</span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Timeline & Tahapan Event</span>
                                </li>
                                <li class="form-wizard-list__item">
                                    <div class="form-wizard-list__line">
                                        <span class="count">3</span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Pengisi/Tamu Undangan Event</span>
                                </li>
                                <li class="form-wizard-list__item">
                                    <div class="form-wizard-list__line">
                                        <span class="count">4</span>
                                    </div>
                                    <span class="text text-xs fw-semibold">Peminjaman Aset (Gedung/Kendaraan)</span>
                                </li>
                            </ul>
                        </div>
                        <fieldset class="wizard-fieldset show">
                            <h6 class="text-md text-neutral-500">Informasi Event</h6>
                            <div class="row gy-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nama Event <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="title" rows="4" cols="50" placeholder="Masukkan nama event..."
                                        required></textarea>

                                    <div class="invalid-feedback">
                                        Nama Event wajib diisi!
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tema Event <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="theme" rows="4" cols="50" placeholder="Masukkan tema event..."
                                        required></textarea>
                                    <div class="invalid-feedback">
                                        Tema Event wajib diisi!
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Deskripsi Event <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="description" rows="4" cols="50"
                                        placeholder="Masukkan deskripsi event..." required></textarea>
                                    <div class="invalid-feedback">
                                        Deskripsi Event wajib diisi!
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Sasaran <span class="text-danger">*</span></label>
                                    <select class="form-select" name="scope" required>
                                        <option disabled selected>Pilih Sasaran</option>
                                        <option value="Internal Organisasi">Internal Organisasi</option>
                                        @if (Auth::user()->organizer->organizer_type === 'HMJ' || Auth::user()->organizer->organizer_type === 'Jurusan')
                                            <option value="Internal Jurusan">Internal Jurusan</option>
                                        @endif
                                        <option value="Internal Kampus">Internal Kampus</option>
                                        <option value="Umum">Umum</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Sasaran Event wajib diisi!
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Kategori Event <span class="text-danger">*</span></label>
                                    <select class="form-select" name="event_category" required>
                                        <option disabled selected>Pilih Kategori Event</option>
                                        <option value="Seminar">Seminar</option>
                                        <option value="Kuliah Tamu">Kuliah Tamu</option>
                                        <option value="Pelatihan">Pelatihan</option>
                                        <option value="Workshop">Workshop</option>
                                        <option value="Kompetisi">Kompetisi</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Kategori Event Event wajib diisi!
                                    </div>
                                </div>
                                @if (Auth::user()->organizer->organizer_type === 'Kampus' || Auth::user()->organizer->organizer_type === 'Jurusan')
                                    <div class="col-md-3">
                                        <label class="form-label">Ketua Pelaksana Event <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Masukkan kuota event..."
                                            name="event_leader" required>
                                        <div class="invalid-feedback">
                                            Ketua Pelaksana Event wajib diisi!
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-3">
                                        <label class="form-label">Ketua Pelaksana Event <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="event_leader" required>
                                            <option disabled selected>Pilih Ketua Pelaksana</option>
                                            @foreach ($team_members as $member)
                                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Ketua Pelaksana Event wajib diisi!
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-3">
                                    <label class="form-label">Kuota <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" placeholder="Masukkan kuota event..."
                                        name="quota" required>
                                    <div class="invalid-feedback">
                                        Kuota Event wajib diisi!
                                    </div>
                                </div>
                                {{-- <div class="col-md-4">
                                    <label class="form-label">Harga Event <span class="text-neutral-500">(Jika gratis
                                            dikosongi)</span> </label>
                                    <input type="number" class="form-control" placeholder="Masukkan kuota event..."
                                        name="price">
                                </div> --}}

                                <div class="col-md-3">
                                    <label class="form-label">Status Biaya <span class="text-danger">*</span></label>
                                    <select class="form-select" name="is_free" id="is_free" required>
                                        <option value="0">Berbayar</option>
                                        <option value="1">Gratis</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Status Biaya wajib diisi!
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Publikasi <span class="text-danger">*</span></label>
                                    <select class="form-select" name="is_publish" id="is_publish" required>
                                        <option value="1">Ya</option>
                                        <option value="0">Tidak</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Status Publikasi wajib diisi!
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" id="benefit_label">Benefit Event </label>
                                    <input class="form-control" name="benefit" id="benefit"
                                        placeholder="Masukkan benefit event..." />
                                    <div class="invalid-feedback">Benefit wajib diisi jika status publikasi adalah "Ya"!
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" id="cp_label">Contact Person </label>
                                    <input class="form-control" name="contact_person" id="contact_person"
                                        placeholder="Masukkan contact person..." />
                                    <div class="invalid-feedback">Contact Person wajib diisi jika status publikasi adalah
                                        "Ya"!</div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label" id="pamphlet_label">
                                        Pamflet <span class="text-neutral-500">(Rasio 7:8, Min. 350x400
                                            px)</span>
                                    </label>
                                    <input type="file" class="form-control form-control-sm" name="pamphlet_path"
                                        id="pamphlet" accept=".jpg,.jpeg,.png">
                                    <div class="invalid-feedback">Pamflet wajib diisi jika status publikasi
                                        adalah "Ya"!</div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" id="banner_label">
                                        Banner <span class="text-neutral-500">(Rasio 5:3, Min. 832x500
                                            px)</span>
                                    </label>
                                    <input type="file" class="form-control form-control-sm" name="banner_path"
                                        id="banner" accept=".jpg,.jpeg,.png">
                                    <div class="invalid-feedback">Banner wajib diisi jika status publikasi
                                        adalah "Ya"!</div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Sponsor <span class="text-neutral-500">(Dapat Upload
                                            > 1 gambar)</span></label>
                                    <input type="file" class="form-control form-control-sm" name="sponsored_by[]"
                                        accept=".jpg,.jpeg,.png" multiple>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Media Partner <span class="text-neutral-500">(Dapat
                                            Upload > 1 gambar)</span></label>
                                    <input type="file" class="form-control form-control-sm" name="media_partner[]"
                                        accept=".jpg,.jpeg,.png" multiple>
                                </div>
                                <div id="event-prices-container">
                                    <div class="event-price">
                                        <div class="row gy-3 mt-3 border-3 border-dashed border-primary p-3 mb-3 rounded">
                                            <div class="col-md-4">
                                                <label class="form-label">Nama Kategori Harga <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control"
                                                    placeholder="Misal: Mahasiswa Polinema" id="category_price_name"
                                                    name="name_category_prices[]" required>
                                                <div class="invalid-feedback">
                                                    Nama Kategori Harga wajib diisi!
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Harga <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control"
                                                    placeholder="Masukkan harga event" id="price-input" name="prices[]"
                                                    required>
                                                <div class="invalid-feedback">
                                                    Harga wajib diisi!
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Cakupan User <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" id="scope-select" name="scopes[]" required>
                                                    <option disabled selected>Pilih Cakupan User</option>
                                                    @if (Auth::user()->organizer->organizer_type === 'HMJ' || Auth::user()->organizer->organizer_type === 'Jurusan')
                                                        <option value="Internal Jurusan">Internal Jurusan
                                                            {{ Auth::user()->jurusan->kode_jurusan ?? '' }}</option>
                                                    @endif
                                                    <option value="Internal Kampus">Internal Kampus
                                                    </option>
                                                    <option value="Eksternal Kampus">Eksternal Kampus
                                                    </option>
                                                    <option value="Umum">Umum</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Cakupan User wajib diisi!
                                                </div>
                                            </div>
                                            <div class="text-start mt-3">
                                                <a href="#" class="btn btn-sm btn-danger-600 remove-event-price"
                                                    style="display: none;">Hapus</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="#" id="add-event-price"
                                        class="btn btn-sm btn-dark d-inline-flex align-items-center gap-2 rounded-pill">
                                        <iconify-icon icon="zondicons:add-outline" class="menu-icon"></iconify-icon>
                                        Tambah Kategori Harga
                                    </a>
                                </div>
                                <hr class="my-3">
                                <div class="form-group text-end">
                                    <button type="button"
                                        class="form-wizard-next-btn btn btn-sm btn-primary-600 px-32">Lanjut</button>
                                </div>
                            </div>

                        </fieldset>
                        <fieldset class="wizard-fieldset">
                            <h6 class="text-md text-neutral-500">Informasi Timeline & Tahapan Event</h6>
                            <div class="row gy-3" id="registration_fields">
                                <div class="col-md-4">
                                    <label class="form-label" id="registration_start_label">Tanggal dan Jam Mulai
                                        Pendaftaran</label>
                                    <input type="text" class="form-control date-time-registration-pickr"
                                        name="date_registration_start" id="registration_start"
                                        placeholder="Masukkan Tanggal Mulai Pendaftaran">
                                    <div class="invalid-feedback">
                                        Tanggal dan Jam Mulai Pendaftaran wajib diisi!
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" id="registration_end_label">Tanggal dan Jam Akhir
                                        Pendaftaran</label>
                                    <input type="text" class="form-control date-time-registration-pickr"
                                        name="date_registration_end" id="registration_end"
                                        placeholder="Masukkan Tanggal Akhir Pendaftaran">
                                    <div class="invalid-feedback">
                                        Tanggal dan Jam Akhir Pendaftaran wajib diisi!
                                    </div>
                                </div>
                            </div>

                            <div id="event-steps-container">
                                <div class="event-step">
                                    <div class="row gy-3 mt-3 border-3 border-dashed border-primary p-3 mb-3 rounded">
                                        <div class="col-md-6 event_step_name d-none">
                                            <label class="form-label">Nama Tahapan Event <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                placeholder="Masukkan nama tahapan event" name="step_names[]">
                                            <div class="invalid-feedback">
                                                Nama Tahapan Event wajib diisi!
                                            </div>
                                        </div>
                                        <div class="col-md-6 event_step_description d-none">
                                            <label class="form-label">Deskripsi Tahapan Event </label>
                                            <textarea class="form-control" name="event_step_descriptions[]" rows="4" cols="50"
                                                placeholder="Masukkan deskripsi tahapan event..."></textarea>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Tanggal Pelaksanaan <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control date-execution-pickr"
                                                name="event_dates[]" required>
                                            <div class="invalid-feedback">
                                                Tanggal Pelaksanaan wajib diisi!
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label"> Jam Mulai Pelaksanaan <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control time-start-execution-pickr"
                                                name="event_time_starts[]" required>
                                            <div class="invalid-feedback">
                                                Jam Mulai Pelaksanaan wajib diisi!
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Jam Selesai Pelaksanaan <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control time-end-execution-pickr"
                                                name="event_time_ends[]" required>
                                            <div class="invalid-feedback">
                                                Jam Selesai Pelaksanaan wajib diisi!
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Sistem Pelaksanaan <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select execution-system" name="execution_systems[]"
                                                required>
                                                <option disabled selected>Pilih Sistem Pelaksanaan</option>
                                                <option value="offline">Offline</option>
                                                <option value="online">Online</option>
                                                <option value="hybrid">Hybrid</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Sistem Pelaksanaan wajib diisi!
                                            </div>

                                        </div>
                                        <div class="col-md-3 location-container">
                                            <label class="form-label">Lokasi Event <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select location-type" name="location_type[]" required>
                                                <option value="campus">Gunakan Aset Kampus</option>
                                                <option value="manual">Isi Manual</option>
                                            </select>
                                            <select class="form-select mt-3 asset-select" name="locations[]"
                                                id="campus-asset">
                                                <option value="" disabled selected>Pilih Aset Kampus...</option>

                                                {{-- Fasilitas Umum --}}
                                                @if ($buildings->where('facility_scope', 'umum')->isNotEmpty())
                                                    <optgroup label="Fasilitas Umum">
                                                        @foreach ($buildings->where('facility_scope', 'umum')->sortBy('name') as $asset)
                                                            <option value="{{ $asset->id }}">{{ $asset->name }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif

                                                {{-- Fasilitas Berdasarkan Jurusan --}}
                                                @foreach ($buildings->where('facility_scope', '!=', 'umum')->groupBy('facility_scope') as $facilityScope => $groupedAssets)
                                                    @foreach ($groupedAssets->groupBy('jurusan.nama')->sortKeys() as $namaJurusan => $jurusanBuildings)
                                                        <optgroup label="Fasilitas {{ ucwords($namaJurusan) }}">
                                                            @foreach ($jurusanBuildings->sortBy('name') as $asset)
                                                                <option value="{{ $asset->id }}">{{ $asset->name }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                            <input type="text" class="form-control location-input" name="locations[]"
                                                placeholder="Masukkan lokasi event..." style="display: none;">
                                            <div class="invalid-feedback">
                                                Lokasi event wajib diisi!
                                            </div>
                                            <input type="text" class="form-control address-location-input"
                                                name="address_locations[]" placeholder="Masukkan alamat lokasi event..."
                                                style="display: none;">
                                            <div class="invalid-feedback">
                                                Lokasi event wajib diisi!
                                            </div>
                                        </div>
                                        <div class="text-start mt-3">
                                            <a href="#" class="btn btn-sm btn-danger-600 remove-event-step"
                                                style="display: none;">Hapus</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="#" id="add-event-step"
                                    class="btn btn-sm btn-dark d-inline-flex align-items-center gap-2 rounded-pill">
                                    <iconify-icon icon="zondicons:add-outline" class="menu-icon"></iconify-icon> Tambah
                                    Hari
                                </a>
                            </div>
                            <hr class="my-3">
                            <div class="form-group d-flex justify-content-between align-items-center">
                                <button type="button"
                                    class="form-wizard-previous-btn btn btn-sm btn-neutral-500 border-neutral-100 px-32">
                                    Kembali
                                </button>
                                <button type="button" class="form-wizard-next-btn btn btn-sm btn-primary-600 px-32">
                                    Lanjut
                                </button>
                            </div>
                        </fieldset>
                        <fieldset class="wizard-fieldset">
                            <h6 class="text-md text-neutral-500">Pengisi Tamu Undangan Event</h6>
                            <div id="event-speakers-container">
                                <div class="event-speaker">
                                    <div class="row gy-3 mt-3 border-3 border-dashed border-primary p-3 mb-3 rounded">
                                        <div class="col-md-3">
                                            <label class="form-label">Tanggal & Jam Pelaksanaan </label>
                                            <select class="form-select event-date-select" name="event_date_speakers[]">
                                                <option value="" disabled selected>Pilih Tanggal</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Tanggal & Jam Selesai Pelaksanaan wajib diisi!
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Nama <span class="text-danger"></label>
                                            <input type="text" class="form-control" name="speaker_name[]"
                                                placeholder="Masukkan Nama...">
                                            <div class="invalid-feedback">
                                                Nama Pengisi Acara wajib diisi!
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label" for="role">Peran <span
                                                        class="text-danger"></label>
                                                <select class="form-select role" name="role[]">
                                                    <option value="Keynote Speaker">Keynote Speaker</option>
                                                    <option value="Pemateri">Pemateri / Narasumber</option>
                                                    <option value="Moderator">Moderator</option>
                                                    <option value="Panelist">Panelist</option>
                                                    <option value="MC">MC (Master of Ceremony)</option>
                                                    <option value="other">Lainnya</option>
                                                </select>
                                                <input type="text" class="form-control mt-3 other_role d-none"
                                                    name="other_role[]" placeholder="Masukkan peran lainnya" disabled>
                                            </div>
                                            <div class="invalid-feedback">
                                                Peran wajib diisi!
                                            </div>
                                        </div>
                                        <div class="text-start mt-3">
                                            <a href="#" class="btn btn-sm btn-danger-600 remove-event-speaker"
                                                style="display: none;">Hapus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center align-items-center">
                                <a href="#" id="add-event-speaker"
                                    class="btn btn-sm btn-dark d-inline-flex align-items-center gap-2 rounded-pill"><iconify-icon
                                        icon="zondicons:add-outline" class="menu-icon"></iconify-icon> Tambah
                                    Pengisi
                                    Acara</a>
                            </div>
                            <hr class="my-3">

                            <div class="form-group d-flex justify-content-between align-items-center">
                                <button type="button"
                                    class="form-wizard-previous-btn btn btn-sm btn-neutral-500 border-neutral-100 px-32">
                                    Kembali
                                </button>
                                <button type="button" class="form-wizard-next-btn btn btn-sm btn-primary-600 px-32">
                                    Lanjut
                                </button>
                            </div>
                        </fieldset>
                        <fieldset class="wizard-fieldset">
                            <h6 class="text-md text-neutral-500">Peminjaman Aset (Gedung/Kendaraan)</h6>
                            <p class="text-md">Daftar Peminjaman</p>
                            <table id="borrow-list-table"
                                class="table bordered-table mb-0 w-100 h-100 row-border order-column  sm-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Tempat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak meminjam gedung kampus.</td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                            <div id="asset-loans-container">
                                <h6 class="text-md text-neutral-500">Peminjaman/Izin Dekorasi</h6>
                                <div class="asset-loan">
                                    <div class="row gy-3 mt-3 border-3 border-dashed border-primary p-3 mb-3 rounded">
                                        <div class="col-md-3">
                                            <label class="form-label">Tanggal Peminjaman </label>
                                            <input type="text" class="form-control date-loans-pickr"
                                                name="loan_dates[]">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Jam Mulai Peminjaman </label>
                                            <input type="text" class="form-control time-loans-pickr"
                                                name="loan_time_starts[]">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Jam Selesai Peminjaman </label>
                                            <input type="text" class="form-control time-loans-pickr"
                                                name="loan_time_ends[]">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Aset Kampus </label>
                                            <select class="form-select asset-selected" name="loan_assets[]">
                                                <option disabled selected>Pilih Aset Kampus</option>
                                                {{-- Optgroup Gedung --}}
                                                {{-- akan diisi otomatis --}}
                                                <optgroup label="Gedung (Izin Dekorasi)"></optgroup>

                                                {{-- Optgroup Kendaraan --}}
                                                @if (!empty($transportations))
                                                    <optgroup label="Kendaraan (Peminjaman)">
                                                        @foreach ($transportations as $transport)
                                                            <option value="{{ $transport->id }}">{{ $transport->name }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="text-start mt-3">
                                            <a href="#" class="btn btn-sm btn-danger-600 remove-asset-loan"
                                                style="display: none;">Hapus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center align-items-center">
                                <a href="#" id="add-asset-loan"
                                    class="btn btn-sm btn-dark d-inline-flex align-items-center gap-2 rounded-pill"><iconify-icon
                                        icon="zondicons:add-outline" class="menu-icon"></iconify-icon> Tambah
                                    Peminjaman</a>
                            </div>
                            <hr class="my-3">

                            <div class="form-group d-flex justify-content-between align-items-center ">
                                <button type="button"
                                    class="form-wizard-previous-btn btn btn-sm btn-neutral-500 border-neutral-100 px-32">
                                    Kembali
                                </button>
                                <button type="submit" class="btn btn-sm btn-primary-600 px-32">
                                    Simpan
                                </button>
                            </div>
                        </fieldset>
                        {{-- Start of Dynamic Form --}}
                        {{-- <fieldset class="wizard-fieldset">
                            <h6 class="text-md text-neutral-500">Form Feedback Event</h6>
                            <div id="form-container">
                                <div class="card shadow-sm mb-3 field-wrapper">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="fw-bold">Pertanyaan</label>
                                            <input type="text" class="form-control" name="questions[]"
                                                placeholder="Masukkan pertanyaan" required>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="fw-bold">Tipe Jawaban</label>
                                            <select class="form-control" name="field_types[]"
                                                onchange="changeInputType(this)">
                                                <option value="text">Jawaban Singkat</option>
                                                <option value="textarea">Paragraf</option>
                                                <option value="number">Angka</option>
                                                <option value="email">Email</option>
                                                <option value="date">Tanggal</option>
                                                <option value="file">File Upload</option>
                                                <option value="checkbox">Pilihan Ganda</option>
                                                <option value="scale">Skala</option>
                                                <option value="star">Penilaian Bintang</option>
                                            </select>
                                        </div>

                                        <div class="form-group mt-3 input-container">
                                            <input type="text" class="form-control" name="answers[]"
                                                placeholder="Jawaban pengguna">
                                        </div>
                                    </div>

                                    <div class="card-footer d-flex justify-content-end gap-3">
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="removeField(this)">
                                            <iconify-icon icon="fluent:delete-32-regular"
                                                class="menu-icon text-xl"></iconify-icon>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="duplicateField(this)">
                                            <iconify-icon icon="iconamoon:copy-bold"
                                                class="menu-icon text-xl"></iconify-icon>
                                        </button>
                                        <div class="form-switch switch-primary d-flex align-items-center gap-3">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="is_required" id="switch1" checked>
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                                for="switch1">Required</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="button"
                                    class="btn btn-sm btn-dark d-inline-flex align-items-center gap-2 rounded-pill"
                                    onclick="addField()">
                                    <iconify-icon icon="zondicons:add-outline" class="menu-icon"></iconify-icon>
                                    Tambah Pertanyaan
                                </button>
                            </div>
                            <hr class="my-3">

                            <div class="form-group d-flex justify-content-between align-items-center">
                                <button type="button"
                                    class="form-wizard-previous-btn btn btn-sm btn-neutral-500 border-neutral-100 px-32">
                                    Kembali
                                </button>
                                <button type="submit" class="btn btn-sm btn-primary-600 px-32">
                                    Tambah
                                </button>
                            </div>
                        </fieldset> --}}
                        {{-- End of Dynamic Form --}}
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simpan daftar aset dalam variabel JavaScript
        const assetOptions = `
        <option value="" disabled selected>Pilih Aset Kampus...</option>

        {{-- Fasilitas Umum --}}
        @if ($buildings->where('facility_scope', 'umum')->isNotEmpty())
            <optgroup label="Fasilitas Umum">
                @foreach ($buildings->where('facility_scope', 'umum')->sortBy('name') as $asset)
                    <option value="{{ $asset->id }}">{{ $asset->name }}
                    </option>
                @endforeach
            </optgroup>
        @endif

        {{-- Fasilitas Berdasarkan Jurusan --}}
        @foreach ($buildings->where('facility_scope', '!=', 'umum')->groupBy('facility_scope') as $facilityScope => $groupedAssets)
            @foreach ($groupedAssets->groupBy('jurusan.nama')->sortKeys() as $namaJurusan => $jurusanBuildings)
                <optgroup label="Fasilitas {{ ucwords($namaJurusan) }}">
                    @foreach ($jurusanBuildings->sortBy('name') as $asset)
                        <option value="{{ $asset->id }}">{{ $asset->name }}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        @endforeach
        `;
    </script>
@endsection
@push('script')
    <script src="{{ asset('assets/libs/flatpickr.js/flatpickr.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Mengatur multiple input benefit dan contact person
            const benefitInput = new Choices('#benefit', {
                removeItemButton: true, // Aktifkan tombol remove
                placeholderValue: 'Masukkan benefit event...', // Placeholder untuk input
                maxItemCount: 10, // Maksimal jumlah input
                searchEnabled: false, // Matikan fitur pencarian
                delimiter: '|',
            });

            // Inisialisasi Choices.js untuk input Contact Person
            const contactPersonInput = new Choices('#contact_person', {
                removeItemButton: true, // Aktifkan tombol remove
                placeholderValue: 'Masukkan contact person...',
                maxItemCount: 10,
                searchEnabled: false,
                delimiter: '|',
            });
            // Mengatur inputan yang required jika event dipublish
            const isPublish = document.getElementById('is_publish');
            const pamphlet = document.getElementById('pamphlet');
            const banner = document.getElementById('banner');
            const benefit = document.getElementById('benefit');
            const cp = document.getElementById('cp');
            const registrationStart = document.getElementById('registration_start');
            const registrationEnd = document.getElementById('registration_end');

            const pamphletLabel = document.getElementById('pamphlet_label');
            const bannerLabel = document.getElementById('banner_label');
            const benefitLabel = document.getElementById('benefit_label');
            const cpLabel = document.getElementById('cp_label');
            const registrationStartLabel = document.getElementById('registration_start_label');
            const registrationEndLabel = document.getElementById('registration_end_label');

            // Fungsi untuk menambahkan atau menghapus atribut "required" berdasarkan status publikasi
            function toggleRequiredFields() {
                const isPublishActive = isPublish.value === "1"; // Jika "Ya"
                const fields = [pamphlet, banner, benefit, cp, registrationStart, registrationEnd];
                const labels = [pamphletLabel, bannerLabel, benefitLabel, cpLabel, registrationStartLabel,
                    registrationEndLabel
                ];

                fields.forEach(field => {
                    if (field) {
                        if (isPublishActive) {
                            field.setAttribute('required', 'required');
                        } else {
                            field.removeAttribute('required');
                        }
                    }
                });

                labels.forEach(label => {
                    if (label) {
                        if (isPublishActive) {
                            if (!label.innerHTML.includes('<span class="text-danger">*</span>')) {
                                label.innerHTML += ' <span class="text-danger">*</span>';
                            }
                        } else {
                            label.innerHTML = label.innerHTML.replace(' <span class="text-danger">*</span>',
                                '');
                        }
                    }
                });
            }

            // Tambahkan event listener untuk memantau perubahan dropdown "Publikasi"
            if (isPublish) {
                isPublish.addEventListener('change', toggleRequiredFields);
            }

            // Panggil fungsi saat halaman dimuat untuk sinkronisasi awal
            toggleRequiredFields();
        });
    </script>
    {{-- Start of Handle event prices input --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusBiaya = document.getElementById('is_free');
            const eventPricesContainer = document.getElementById('event-prices-container');
            const addEventPriceButton = document.getElementById('add-event-price');
            const inputCategoryPriceName = document.getElementById('category_price_name');
            const inputPrice = document.getElementById('price-input');
            const selectScope = document.getElementById('scope-select');

            // Default: Sembunyikan event-prices-container jika status adalah gratis
            // Fungsi untuk memperbarui tampilan saat halaman dimuat
            if (statusBiaya.value === '1') {
                eventPricesContainer.style.display = 'none';
                addEventPriceButton.classList.add('d-none'); // Sembunyikan tombol
                inputCategoryPriceName.removeAttribute('required');
                inputPrice.removeAttribute('required');
                selectScope.removeAttribute('required');
            } else {
                eventPricesContainer.style.display = 'block';
                addEventPriceButton.classList.remove('d-none'); // Tampilkan tombol
                inputCategoryPriceName.setAttribute('required', true);
                inputPrice.setAttribute('required', true);
                selectScope.setAttribute('required', true);

            }

            // Event listener untuk mengubah tampilan berdasarkan pilihan
            statusBiaya.addEventListener('change', function() {
                if (statusBiaya.value === '0') {
                    eventPricesContainer.style.display = 'block'; // Tampilkan container
                    addEventPriceButton.classList.remove('d-none'); // Tampilkan tombol
                    addEventPriceButton.classList.add('d-inline-flex');
                    inputCategoryPriceName.setAttribute('required', true);
                    inputPrice.setAttribute('required', true);
                    selectScope.setAttribute('required', true);
                    updateRemoveEventPriceButtons();
                } else {
                    eventPricesContainer.style.display = 'none'; // Sembunyikan container
                    addEventPriceButton.classList.remove('d-inline-flex'); // Hapus kelas tampil
                    addEventPriceButton.classList.add('d-none'); // Tambahkan kelas sembunyi
                    inputCategoryPriceName.removeAttribute('required');
                    inputPrice.removeAttribute('required');
                    selectScope.removeAttribute('required');
                }
            });


            // Tambah kategori harga
            addEventPriceButton.addEventListener('click', function(e) {
                e.preventDefault();
                const eventPriceTemplate = `
                <div class="event-price">
                    <div class="row gy-3 mt-3 border-3 border-dashed border-primary p-3 mb-3 rounded">
                        <div class="col-md-4">
                            <label class="form-label">Nama Kategori Harga <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control"
                                placeholder="Misal: Mahasiswa Polinema" name="name_category_prices[]"
                                required>
                            <div class="invalid-feedback">
                                Nama Kategori Harga wajib diisi!
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga <span class="text-danger">*</span></label>
                            <input type="number" class="form-control"
                                placeholder="Masukkan harga event" name="prices[]" required>
                            <div class="invalid-feedback">
                                Harga wajib diisi!
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cakupan User <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="scope-select" name="scopes[]" required>
                                <option disabled selected>Pilih Cakupan User</option>
                                @if (Auth::user()->organizer->organizer_type === 'HMJ' || Auth::user()->organizer->organizer_type === 'Jurusan')
                                    <option value="Internal Jurusan">Internal Jurusan
                                        {{ Auth::user()->jurusan->kode_jurusan ?? '' }}</option>
                                @endif
                                <option value="Internal Kampus">Internal Kampus
                                </option>
                                <option value="Eksternal Kampus">Eksternal Kampus
                                </option>
                                <option value="Umum">Umum</option>
                            </select>
                            <div class="invalid-feedback">
                                Cakupan User wajib diisi!
                            </div>
                        </div>
                        <div class="text-start mt-3">
                            <a href="#" class="btn btn-sm btn-danger-600 remove-event-price"
                                style="display: none;">Hapus</a>
                        </div>
                    </div>
                </div>
                `;

                const container = document.getElementById('event-prices-container');
                container.insertAdjacentHTML('beforeend', eventPriceTemplate);

                updateRemoveEventPriceButtons();
            });

            // Update tombol "Hapus" berdasarkan jumlah kategori harga
            function updateRemoveEventPriceButtons() {
                const removeButtons = eventPricesContainer.querySelectorAll('.remove-event-price');
                const eventPrices = eventPricesContainer.querySelectorAll('.event-price');

                removeButtons.forEach((btn, index) => {
                    btn.style.display = eventPrices.length > 1 ? 'inline-block' : 'none';
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (eventPrices.length > 1) {
                            btn.closest('.event-price').remove();
                            updateRemoveEventPriceButtons();
                        }
                    });
                });
            }

            // Update tombol "Hapus" pada halaman load awal
            updateRemoveEventPriceButtons();
        });
    </script>
    {{-- End of Handle event prices input --}}

    {{-- Start of Handle event steps, event speaker, and asset loan input --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const eventStepsContainer = document.getElementById('event-steps-container');
            const addEventStepButton = document.getElementById('add-event-step');

            const eventSpeakersContainer = document.getElementById('event-speakers-container');
            const addEventSpeakerButton = document.getElementById('add-event-speaker');

            const assetLoansContainer = document.getElementById('asset-loans-container');
            const addAssetLoanButton = document.getElementById('add-asset-loan');

            let eventDetails = [];
            let eventDates = [];

            function eventDateTimePickrChange() {
                // Inisialisasi DateTime Picker untuk tanggal
                flatpickr(".date-time-registration-pickr", {
                    dateFormat: "Y-m-d H:i",
                    minDate: "today",
                    enableTime: true,
                    time_24hr: true,
                });

                flatpickr(".date-execution-pickr", {
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    time_24hr: true,
                    onChange(selectedDates, dateStr, instance) {
                        const parentElement = instance.input.closest(".event-step");
                        const timeStartInput = parentElement.querySelector(".time-start-execution-pickr");
                        const timeEndInput = parentElement.querySelector(".time-end-execution-pickr");
                        const oldDate = instance.input.dataset.oldDate;
                        instance.input.dataset.oldDate = dateStr;

                        updateEventDetails(parentElement, dateStr, timeStartInput.value, timeEndInput
                            .value);

                        if (oldDate) {
                            const index = eventDates.indexOf(oldDate);
                            if (index !== -1) {
                                eventDates[index] = dateStr;
                            } else {
                                eventDates.push(dateStr);
                            }
                        } else {
                            eventDates.push(dateStr);
                        }

                        updateEventDateDropdown();
                    },
                });

                // Inisialisasi Time Picker untuk waktu mulai
                flatpickr(".time-start-execution-pickr", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    onChange(selectedDates, timeStr, instance) {
                        const parentElement = instance.input.closest(".event-step");
                        const dateInput = parentElement.querySelector(".date-execution-pickr");
                        const timeEndInput = parentElement.querySelector(".time-end-execution-pickr");

                        updateEventDetails(parentElement, dateInput.value, timeStr, timeEndInput.value);
                    },
                });

                // Inisialisasi Time Picker untuk waktu selesai
                flatpickr(".time-end-execution-pickr", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    onChange(selectedDates, timeStr, instance) {
                        const parentElement = instance.input.closest(".event-step");
                        const dateInput = parentElement.querySelector(".date-execution-pickr");
                        const timeStartInput = parentElement.querySelector(".time-start-execution-pickr");

                        updateEventDetails(parentElement, dateInput.value, timeStartInput.value, timeStr);
                    },
                });
                // Inisialisasi DateTime Picker untuk tanggal
                flatpickr(".date-loans-pickr", {
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    enableTime: true,
                    time_24hr: true,
                });

                // Inisialisasi Time Picker untuk waktu mulai
                flatpickr(".time-start-execution-pickr", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,

                });

                // Inisialisasi Time Picker untuk waktu selesai
                flatpickr(".time-loans-pickr", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,

                });
            }

            // Event Listener untuk dropdown asset-select
            document.addEventListener("change", function(event) {
                if (event.target.matches(".asset-select, .asset-select-offline")) {
                    const select = event.target;
                    const parentElement = select.closest(".event-step");
                    const dateInput = parentElement.querySelector(".date-execution-pickr");
                    const timeStartInput = parentElement.querySelector(".time-start-execution-pickr");
                    const timeEndInput = parentElement.querySelector(".time-end-execution-pickr");

                    // Ambil nilai ID dan teks dari opsi yang dipilih
                    const selectedOptionId = select.value; // ID lokasi yang dipilih
                    // Ambil teks dari opsi yang dipilih
                    const selectedOptionText = select.selectedOptions[0]?.text || "Lokasi tidak tersedia";


                    // Perbarui detail event dengan nama lokasi
                    updateEventDetails(parentElement, dateInput.value, timeStartInput.value, timeEndInput
                        .value, selectedOptionText);

                    // Dapatkan lokasi sebelumnya dari atribut data
                    const prevLocationId = select.getAttribute("data-prev-location");

                    // Update select options dan array
                    updateSelectOptions(selectedOptionId, selectedOptionText, prevLocationId);

                    // Perbarui data-prev-location dengan lokasi yang baru dipilih
                    select.setAttribute("data-prev-location", selectedOptionId);
                }
            });

            // Array untuk menyimpan lokasi yang sudah dipilih
            let selectedLocations = [];

            // Fungsi untuk memperbarui opsi select dan array
            function updateSelectOptions(selectedLocationId, selectedLocationName, prevLocationId = null) {
                // Jika ada lokasi sebelumnya (prevLocationId), hapus dari array
                if (prevLocationId) {
                    selectedLocations = selectedLocations.filter(
                        (location) => location.id !== prevLocationId
                    );
                }

                // Tambahkan lokasi baru ke array
                if (!selectedLocations.find((location) => location.id === selectedLocationId)) {
                    selectedLocations.push({
                        id: selectedLocationId,
                        name: selectedLocationName,
                    });
                }

                // Perbarui select options di DOM
                renderSelectOptions();
            }

            // Fungsi untuk me-render ulang select options berdasarkan array
            function renderSelectOptions() {
                const selectElements = document.querySelectorAll(".asset-selected");

                // Loop melalui semua elemen select
                selectElements.forEach((locationSelected) => {
                    // Temukan optgroup Gedung
                    const optgroup = locationSelected.querySelector(
                        'optgroup[label="Gedung (Izin Dekorasi)"]');
                    if (optgroup) {
                        // Hapus semua opsi di optgroup Gedung
                        optgroup.innerHTML = "";

                        // Tambahkan opsi baru dari array
                        selectedLocations.forEach((location) => {
                            const newOption = document.createElement("option");
                            newOption.value = location.id;
                            newOption.textContent = location.name;
                            optgroup.appendChild(newOption);
                        });
                    }
                });
            }


            // Fungsi untuk mengupdate data event berdasarkan elemen terkait
            function updateEventDetails(parentElement, date, timeStart, timeEnd, locationName) {
                const index = Array.from(document.querySelectorAll(".event-step")).indexOf(parentElement);

                if (!date || !timeStart || !timeEnd || !locationName) {
                    eventDetails.splice(index, 1); // Hapus data jika ada input yang kosong
                } else {
                    eventDetails[index] = {
                        date,
                        timeStart,
                        timeEnd,
                        location: locationName, // Simpan nama lokasi
                    };
                }

                updateEventTable();
            }


            // Fungsi untuk memperbarui tabel event
            function updateEventTable() {
                const tableBody = document.querySelector("#borrow-list-table tbody");
                tableBody.innerHTML = "";

                if (eventDetails.length === 0) {
                    tableBody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center">Tidak meminjam gedung kampus.</td>
            </tr>
        `;
                } else {
                    eventDetails.forEach(({
                        date,
                        timeStart,
                        timeEnd,
                        location
                    }) => {
                        const formattedDate = new Date(date).toLocaleDateString("id-ID", {
                            day: "2-digit",
                            month: "long",
                            year: "numeric",
                        });

                        tableBody.innerHTML += `
                <tr>
                    <td>${formattedDate}</td>
                    <td>${timeStart} - ${timeEnd}</td>
                    <td>${location}</td>
                </tr>
            `;
                    });
                }
            }


            function handleExecutionSystemChange(eventStep) {
                const executionSystem = eventStep.querySelector('.execution-system');
                const locationContainer = eventStep.querySelector('.location-container');
                const locationType = eventStep.querySelector('.location-type');
                const assetSelect = eventStep.querySelector('.asset-select');
                // const locationInput = eventStep.querySelector('.location-input');

                executionSystem.addEventListener('change', function() {
                    // Bersihkan konten dalam locationContainer setiap kali sistem pelaksanaan berubah
                    locationContainer.innerHTML = `
            <label class="form-label">Lokasi Event <span class="text-danger">*</span></label>
            <select class="form-select location-type" name="location_type[]">
                <option value="campus">Gunakan Aset Kampus</option>
                <option value="manual">Isi Manual</option>
            </select>
            <select class="form-select mt-3 asset-select" name="locations[]" id="campus-asset" required>
                ${assetOptions} 
            </select>
            <input type="text" class="form-control mt-3 location-input" name="locations[]" placeholder="Masukkan lokasi event..." style="display: none;" disabled>
            <input type="text" class="form-control mt-3  address-location-input" name="address_locations[]" placeholder="Masukkan alamat lokasi event..." style="display: none;" disabled>
        `;

                    const newLocationType = locationContainer.querySelector('.location-type');
                    const newAssetSelect = locationContainer.querySelector('.asset-select');
                    const newLocationInput = locationContainer.querySelector('.location-input');
                    const newAddressLocationInput = locationContainer.querySelector(
                        '.address-location-input');

                    if (this.value === 'online') {
                        newLocationType.value = 'manual';
                        // newLocationType.setAttribute('disabled', true);
                        newAssetSelect.style.display = 'none';
                        newAssetSelect.removeAttribute('required');
                        newAssetSelect.setAttribute('disabled', true);
                        newLocationInput.style.display = 'block';
                        newLocationInput.setAttribute('required', true);
                        newLocationInput.removeAttribute('disabled');
                    } else if (this.value === 'offline') {
                        // newLocationType.removeAttribute('disabled');
                        // newLocationInput.removeAttribute('required');

                        newLocationType.addEventListener('change', function() {
                            if (this.value === 'manual') {
                                // Sembunyikan assetSelect dan aktifkan locationInput
                                newAssetSelect.style.display = 'none';
                                newAssetSelect.removeAttribute('required');
                                newAssetSelect.setAttribute('disabled', true);

                                newLocationInput.style.display = 'block';
                                newLocationInput.setAttribute('required', true);
                                newLocationInput.removeAttribute('disabled');

                                newAddressLocationInput.style.display = 'block';
                                newAddressLocationInput.removeAttribute('disabled');
                                newAddressLocationInput.setAttribute('required', true);
                            } else {
                                // Tampilkan assetSelect dan nonaktifkan locationInput
                                newAssetSelect.style.display = 'block';
                                newAssetSelect.removeAttribute('disabled');
                                newAssetSelect.setAttribute('required', true);

                                newLocationInput.style.display = 'none';
                                newLocationInput.removeAttribute('required');
                                newLocationInput.setAttribute('disabled', true);

                                newAddressLocationInput.style.display = 'none';
                                newAddressLocationInput.removeAttribute('required');
                                newAddressLocationInput.setAttribute('disabled', true);
                            }
                        });
                    } else if (this.value === 'hybrid') {
                        locationContainer.innerHTML = `
                <label class="form-label">Lokasi Offline <span class="text-danger">*</span></label>
                <select class="form-select location-type-offline" name="location_type[]">
                    <option value="campus">Gunakan Aset Kampus</option>
                    <option value="manual">Isi Manual</option>
                </select>
                <select class="form-select mt-3 asset-select-offline" name="locations_offline[]" id="campus-asset" required>
                    ${assetOptions} 
                </select>
                <input type="text" class="form-control mt-3 location-input-offline" name="locations_offline[]" placeholder="Masukkan lokasi offline..." style="display: none;" disabled>
                <input type="text" class="form-control mt-3 address-location-input-offline" name="address_locations_offline[]" placeholder="Masukkan alamat lokasi event..." style="display: none;">

                <label class="form-label mt-2">Lokasi Online <span class="text-danger">*</span></label></label>
                <input type="text" class="form-control location-input-online" name="locations_online[]" placeholder="Masukkan lokasi online..." required>
            `;

                        const locationTypeOffline = locationContainer.querySelectorAll(
                            '.location-type-offline');
                        const assetSelectOffline = locationContainer.querySelectorAll(
                            '.asset-select-offline');
                        const locationInputOffline = locationContainer.querySelectorAll(
                            '.location-input-offline');
                        const addressLocationInputOffline = locationContainer.querySelectorAll(
                            '.address-location-input-offline');

                        // Iterasi untuk setiap elemen locationTypeOffline
                        locationTypeOffline.forEach((element, index) => {
                            element.addEventListener('change', function() {
                                if (this.value === 'manual') {
                                    assetSelectOffline[index].style.display = 'none';
                                    assetSelectOffline[index].removeAttribute('required');
                                    assetSelectOffline[index].setAttribute('disabled',
                                        true);
                                    locationInputOffline[index].style.display = 'block';
                                    locationInputOffline[index].setAttribute('required',
                                        true);
                                    locationInputOffline[index].removeAttribute('disabled');

                                    addressLocationInputOffline[index].style.display =
                                        'block';
                                    addressLocationInputOffline[index].removeAttribute(
                                        'disabled');
                                    addressLocationInputOffline[index].setAttribute(
                                        'required', true);

                                } else {
                                    assetSelectOffline[index].style.display = 'block';
                                    assetSelectOffline[index].removeAttribute('disabled');
                                    assetSelectOffline[index].setAttribute('required',
                                        true);
                                    locationInputOffline[index].style.display = 'none';
                                    locationInputOffline[index].removeAttribute('required');
                                    locationInputOffline[index].setAttribute('disabled',
                                        true);

                                    addressLocationInputOffline[index].style.display =
                                        'none';
                                    addressLocationInputOffline[index].removeAttribute(
                                        'required');
                                    addressLocationInputOffline[index].setAttribute(
                                        'disabled', true);
                                }
                            });
                        });

                    }

                    // Tambahkan event listener ke elemen-elemen baru jika diperlukan
                    // newLocationType.addEventListener('change', function() {
                    //     if (this.value === 'manual') {
                    //         newAssetSelect.style.display = 'none';
                    //         newLocationInput.style.display = 'block';
                    //     } else {
                    //         newAssetSelect.style.display = 'block';
                    //         newLocationInput.style.display = 'none';
                    //     }
                    // });
                });
            }


            function updateRemoveStepButtons() {
                const removeButtons = document.querySelectorAll('.remove-event-step');
                const eventStepNames = document.querySelectorAll('.event_step_name');
                const eventStepDescriptions = document.querySelectorAll('.event_step_description');

                // Tentukan apakah elemen name dan description harus ditampilkan
                const shouldShow = removeButtons.length > 1;

                // Perbarui tombol "Hapus"
                removeButtons.forEach((btn) => {
                    btn.style.display = shouldShow ? 'inline' : 'none';

                    // Tambahkan event listener untuk tombol hapus
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        btn.closest('.event-step').remove();
                        updateRemoveStepButtons();
                    });
                });

                // Perbarui visibilitas elemen nama tahapan dan deskripsi
                eventStepNames.forEach((name) => {
                    name.classList.toggle('d-none', !shouldShow);
                    const input = name.querySelector('input');
                    if (input) {
                        if (shouldShow) {
                            input.setAttribute('required', 'required');
                        } else {
                            input.removeAttribute('required');
                        }
                    }
                });

                eventStepDescriptions.forEach((desc) => {
                    desc.classList.toggle('d-none', !shouldShow);
                });
            }


            addEventStepButton.addEventListener('click', function(e) {
                e.preventDefault();
                const newStep = document.createElement('div');
                newStep.classList.add('event-step',
                    'mb-3', 'rounded');
                newStep.innerHTML = `
            <div class="row gy-3 mt-3 border-3 border-dashed border-primary p-3 mb-3 rounded">
                <div class="col-md-6 event_step_name">
                    <label class="form-label">Nama Tahapan Event <span class="text-danger">*</span></label>
                    <input type="text" class="form-control"
                        placeholder="Masukkan nama tahapan event" name="step_names[]" required>
                        <div class="invalid-feedback">
                            Nama Tahapan Event wajib diisi!
                        </div>
                </div>
                <div class="col-md-6 event_step_description">
                    <label class="form-label">Deskripsi Tahapan Event </label>
                    <textarea class="form-control" name="event_step_descriptions[]" rows="4" cols="50"
                        placeholder="Masukkan deskripsi tahapan event..."></textarea>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control date-execution-pickr"
                        name="event_dates[]" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label"> Jam Mulai Pelaksanaan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control time-start-execution-pickr"
                        name="event_time_starts[]" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jam Selesai Pelaksanaan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control time-end-execution-pickr"
                        name="event_time_ends[]" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sistem Pelaksanaan <span class="text-danger">*</span></label>
                    <select class="form-select execution-system" name="execution_systems[]" required>
                        <option disabled selected>Pilih Sistem Pelaksanaan</option>
                        <option value="offline">Offline</option>
                        <option value="online">Online</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>
                <div class="col-md-3 location-container">
                    <label class="form-label">Lokasi Event <span class="text-danger">*</span></label>
                    <select class="form-select location-type" name="location_type[]">
                        <option value="campus">Gunakan Aset Kampus</option>
                        <option value="manual">Isi Manual</option>
                    </select>
                    <select class="form-select mt-3 asset-select" name="locations[]" id="campus-asset">
                        ${assetOptions} 
                    </select>
                    <input type="text" class="form-control mt-3 location-input" name="locations[]" placeholder="Masukkan lokasi event..." style="display: none;">
                </div>
                <div class="text-start mt-3">
                    <a href="#" class="btn btn-sm btn-danger-600 remove-event-step" style="display: none;">Hapus</a></div>
            </div>
        `;

                eventStepsContainer.appendChild(newStep);
                handleExecutionSystemChange(newStep);
                eventDateTimePickrChange();
                updateRemoveStepButtons();
            });


            function updateEventDateDropdown() {
                document.querySelectorAll('.event-date-select').forEach(select => {
                    select.innerHTML = `<option value="" disabled selected>Pilih Tanggal</option>`;

                    eventDates.forEach(date => {
                        const formattedDate = new Date(date).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });
                        select.innerHTML += `<option value="${date}">${formattedDate}</option>`;
                    });
                });
            }



            function updateRemoveSpeakerButtons() {
                const removeSpeakerButtons = document.querySelectorAll('.remove-event-speaker');
                removeSpeakerButtons.forEach((btn) => {
                    btn.style.display = removeSpeakerButtons.length > 1 ? 'inline' : 'none';
                    btn.onclick = function(e) {
                        e.preventDefault();
                        btn.closest('.event-speaker').remove(); // Pakai selector yang benar
                        updateRemoveSpeakerButtons();
                    };
                });
            }
            // Event delegation untuk menangani perubahan dropdown "role"
            document.addEventListener('change', function(event) {
                if (event.target.classList.contains('role')) {
                    handleOtherRoleSelected(event.target);
                }
            });

            // Fungsi untuk menangani perubahan role
            function handleOtherRoleSelected(roleSelect) {
                const eventSpeaker = roleSelect.closest('.event-speaker'); // Cari elemen terdekat
                if (!eventSpeaker) return; // Jika tidak ditemukan, hentikan fungsi

                const otherRoleInput = eventSpeaker.querySelector('.other_role'); // Ambil input "other role"

                if (roleSelect.value === 'other') {
                    otherRoleInput.classList.remove('d-none');
                    otherRoleInput.classList.add('d-block');
                    otherRoleInput.removeAttribute('disabled');
                    otherRoleInput.setAttribute('required', true);
                } else {
                    otherRoleInput.classList.remove('d-block');
                    otherRoleInput.classList.add('d-none');
                    otherRoleInput.removeAttribute('required');
                    otherRoleInput.setAttribute('disabled', true);
                }
            }
            addEventSpeakerButton.addEventListener('click', function(e) {
                e.preventDefault();
                const newSpeaker = document.createElement('div');
                newSpeaker.classList.add('event-speaker');
                newSpeaker.innerHTML = `
                <div class="row gy-3 mt-3 border-3 border-dashed border-primary p-3 mb-3 rounded">
                <div class="col-md-3">
                    <label class="form-label">Tanggal & Jam Pelaksanaan <span class="text-danger">*</span></label>
                    <select class="form-select event-date-select" name="event_date_speakers[]">
                        <option value="" disabled selected>Pilih Tanggal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="speaker_name[]"
                        placeholder="Masukkan Nama..." >
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label" for="role">Peran <span
                                class="text-danger">*</span></label>
                        <select class="form-select role" name="role[]">
                            <option value="Keynote Speaker">Keynote Speaker</option>
                            <option value="Pemateri">Pemateri / Narasumber</option>
                            <option value="Moderator">Moderator</option>
                            <option value="Panelist">Panelist</option>
                            <option value="MC">MC (Master of Ceremony)</option>
                            <option value="other">Lainnya</option>
                        </select>
                        <input type="text" class="form-control mt-3 other_role d-none"
                            name="other_role[]" placeholder="Masukkan peran lainnya" disabled>
                    </div>
                </div>
                <div class="text-start mt-3">
                    <a href="#" class="btn btn-sm btn-danger-600 remove-event-speaker"
                        style="display: none;">Hapus</a>
                </div>
                </div>
                `;

                eventSpeakersContainer.appendChild(newSpeaker);
                eventDateTimePickrChange();
                updateEventDateDropdown();
                updateRemoveSpeakerButtons();
            });

            // Tambah peminjaman aset
            addAssetLoanButton.addEventListener('click', function(e) {
                e.preventDefault();
                const assetLoanTemplate = `
                <div class="asset-loan">
                <div class="row gy-3 mt-3 border-3 border-dashed border-primary p-3 mb-3 rounded">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Peminjaman <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control date-loans-pickr"
                            name="loan_dates[]">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jam Mulai Peminjaman <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control time-loans-pickr"
                            name="loan_time_starts[]">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jam Selesai Peminjaman <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control time-loans-pickr"
                            name="loan_time_ends[]">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Aset Kampus <span
                                class="text-danger">*</span></label>
                        <select class="form-select asset-selected" name="loan_assets[]">
                            <option disabled selected>Pilih Aset Kampus</option>
                            {{-- Optgroup Gedung --}}
                            {{-- akan diisi otomatis --}}
                            <optgroup label="Gedung (Izin Dekorasi)"></optgroup>

                            {{-- Optgroup Kendaraan --}}
                            @if (!empty($transportations))
                                <optgroup label="Kendaraan (Peminjaman)">
                                    @foreach ($transportations as $transport)
                                        <option value="{{ $transport->id }}">{{ $transport->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>
                    <div class="text-start mt-3">
                        <a href="#" class="btn btn-sm btn-danger-600 remove-asset-loan"
                            style="display: none;">Hapus</a>
                    </div>
                </div>
            `;

                const containerLoan = document.getElementById('asset-loans-container');
                containerLoan.insertAdjacentHTML('beforeend', assetLoanTemplate);
                renderSelectOptions();
                eventDateTimePickrChange();
                updateRemoveAssetLoanButtons();
            });

            // Update tombol "Hapus" berdasarkan jumlah kategori harga
            function updateRemoveAssetLoanButtons() {
                const removeAssetLoanButtons = assetLoansContainer.querySelectorAll('.remove-asset-loan');
                const assetLoans = assetLoansContainer.querySelectorAll('.asset-loan');

                removeAssetLoanButtons.forEach((btn, index) => {
                    btn.style.display = assetLoans.length > 1 ? 'inline-block' : 'none';
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (assetLoans.length > 1) {
                            btn.closest('.asset-loan').remove();
                            updateRemoveAssetLoanButtons();
                            renderSelectOptions();
                            eventDateTimePickrChange();
                        }
                    });
                });
            }

            handleExecutionSystemChange(eventStepsContainer.querySelector('.event-step'));
            handleOtherRoleSelected(eventSpeakersContainer.querySelector('.event-speaker'));
            updateRemoveStepButtons();
            eventDateTimePickrChange();
            updateEventDateDropdown();
            updateRemoveSpeakerButtons();
            updateRemoveAssetLoanButtons();

        });
    </script>
    {{-- End of Handle event steps and event speaker input --}}


    {{-- Start of Handle wizard form --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const form = document.querySelector("#addEventForm");
            const fieldsets = form.querySelectorAll("fieldset");
            const nextBtns = form.querySelectorAll(".form-wizard-next-btn");
            const prevBtns = form.querySelectorAll(".form-wizard-previous-btn");
            const stepIndicators = document.querySelectorAll(".form-wizard-list__item");
            let currentStep = 0;

            function showStep(step) {
                fieldsets.forEach((fieldset, index) => {
                    fieldset.classList.toggle("show", index === step);
                });

                prevBtns.forEach(btn => btn.style.display = step > 0 ? "inline-block" : "none");
                nextBtns.forEach(btn => btn.style.display = step < fieldsets.length - 1 ? "inline-block" : "none");

                stepIndicators.forEach((indicator, index) => {
                    indicator.classList.toggle("active", index === step);
                    if (index < step) {
                        indicator.classList.add("activated");
                    } else {
                        indicator.classList.remove("activated");
                    }
                });
            }

            function validateStep(step) {
                let valid = true;
                const inputs = fieldsets[step].querySelectorAll("input, textarea, select");
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        valid = false;
                        input.classList.add("is-invalid");
                    } else {
                        input.classList.remove("is-invalid");
                    }
                });
                return valid;
            }

            nextBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    if (validateStep(currentStep)) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            prevBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    currentStep--;
                    showStep(currentStep);
                });
            });

            showStep(currentStep);

            form.addEventListener("submit", function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add("was-validated");
            });
        });
    </script>
    {{-- End of handle wizard form --}}

    {{-- Start of handle validation input wizard --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const forms = document.querySelectorAll(".needs-validation");

            Array.from(forms).forEach(form => {
                // Validasi saat submit
                form.addEventListener("submit", event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        form.classList.add("was-validated");
                    }
                }, false);

                // Perbarui validasi saat ada input
                form.querySelectorAll("input, textarea, select").forEach(input => {
                    input.addEventListener("input", () => {
                        if (input.checkValidity()) {
                            input.classList.remove("is-invalid");
                            input.classList.add("is-valid"); // Tanda centang hijau
                        } else {
                            input.classList.remove("is-valid");
                            input.classList.add("is-invalid"); // Error merah
                        }
                    });
                });
            });
        });
    </script>
    {{-- End of handle validation input wizard --}}
    {{-- @include('components.script-crud') --}}

    {{-- Start of Handle Form --}}
    {{-- <script>
        function toggleRemoveButtons() {
            let fields = document.querySelectorAll(".field-wrapper");
            let removeButtons = document.querySelectorAll(".field-wrapper .btn-outline-danger");

            removeButtons.forEach(button => {
                button.style.display = fields.length > 1 ? "inline-block" : "none";
            });
        }

        function addField() {
            let container = document.getElementById("form-container");

            // Buat elemen baru dengan template dasar
            let newField = document.createElement("div");
            newField.classList.add("field-wrapper");

            newField.innerHTML = `
            <div class="card shadow-sm mb-3 field-wrapper">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="fw-bold">Pertanyaan</label>
                                            <input type="text" class="form-control" name="questions[]"
                                                placeholder="Masukkan pertanyaan" required>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label class="fw-bold">Tipe Jawaban</label>
                                            <select class="form-control" name="field_types[]"
                                                onchange="changeInputType(this)">
                                                <option value="text">Jawaban Singkat</option>
                                                <option value="textarea">Paragraf</option>
                                                <option value="number">Angka</option>
                                                <option value="email">Email</option>
                                                <option value="date">Tanggal</option>
                                                <option value="file">File Upload</option>
                                                <option value="checkbox">Pilihan Ganda</option>
                                                <option value="scale">Skala</option>
                                                <option value="star">Penilaian Bintang</option>
                                            </select>
                                        </div>

                                        <div class="form-group mt-3 input-container">
                                            <input type="text" class="form-control" name="answers[]"
                                                placeholder="Jawaban pengguna">
                                        </div>
                                    </div>

                                    <div class="card-footer d-flex justify-content-end gap-3">
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="removeField(this)">
                                            <iconify-icon icon="fluent:delete-32-regular"
                                                class="menu-icon text-xl"></iconify-icon>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="duplicateField(this)">
                                            <iconify-icon icon="iconamoon:copy-bold"
                                                class="menu-icon text-xl"></iconify-icon>
                                        </button>
                                        <div class="form-switch switch-primary d-flex align-items-center gap-3">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                name="is_required" id="switch1" checked>
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                                for="switch1">Required</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
    `;

            // Tambahkan elemen baru ke dalam form
            container.appendChild(newField);

            // Perbarui tombol hapus
            toggleRemoveButtons();
        }


        function removeField(button) {
            if (document.querySelectorAll(".field-wrapper").length > 1) {
                button.closest(".field-wrapper").remove();
                toggleRemoveButtons();
            }
        }

        function duplicateField(button) {
            let original = button.closest(".field-wrapper");
            let clone = original.cloneNode(true);

            // Salin nilai terpilih pada select
            let originalSelect = original.querySelector("select");
            let clonedSelect = clone.querySelector("select");
            clonedSelect.value = originalSelect.value; // Menyalin pilihan yang sama

            // Tambahkan elemen duplikat ke dalam container
            document.getElementById("form-container").appendChild(clone);

            // Perbarui tombol hapus
            toggleRemoveButtons();
        }


        function changeInputType(select) {
            let container = select.closest(".field-wrapper").querySelector(".input-container");
            let fieldType = select.value;
            let newInput = "";

            if (fieldType === "textarea") {
                newInput =
                    `<textarea class="form-control" name="answers[]" rows="3" placeholder="Masukkan jawaban..."></textarea>`;
            } else if (fieldType === "checkbox") {
                newInput = `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="answers[]" value="Pilihan 1">
                    <label class="form-check-label">Pilihan 1</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="answers[]" value="Pilihan 2">
                    <label class="form-check-label">Pilihan 2</label>
                </div>
            `;
            } else if (fieldType === "scale") {
                newInput = `
                <label>Skala</label>
                <input type="number" class="form-control" name="scale_min[]" value="1" min="1">
                <input type="number" class="form-control" name="scale_max[]" value="5" min="2">
                <input type="text" class="form-control" name="start_label[]" placeholder="Masukkan Label Awal" required>
                <input type="text" class="form-control" name="end_label[]" placeholder="Masukkan Label Terakhir" required>
            `;
            } else if (fieldType === "star") {
                newInput = `
                <label>Jumlah Bintang</label>
                <input type="number" class="form-control" name="star_count[]" value="5" min="1" max="10">
            `;
            } else {
                newInput =
                    `<input type="${fieldType}" class="form-control" name="answers[]" placeholder="Jawaban pengguna">`;
            }

            container.innerHTML = newInput;
        }
        document.addEventListener("DOMContentLoaded", toggleRemoveButtons);
    </script> --}}
    {{-- End of Handle Form --}}
@endpush
