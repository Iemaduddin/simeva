@extends('layout.layout')
@section('title', 'Users Management')
@php
    $title = 'Users Management';
    $subTitle = 'Users Management';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-header">
            <h5 class="card-title mb-0 align-content-center">Peserta Event</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 w-100 h-100 row-border order-column  sm-table"
                    id="eventParticipantTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Nama</th>
                            <th>Event</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>No WA</th>
                            <th>Asal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            let eventParticipantTable = $('#eventParticipantTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('events.getDataParticipantsForAdmin') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'event.title',
                        name: 'event.title',
                        render: function(data, type, row) {
                            return `${data}<br><p>(${row.event.organizers.shorten_name})</p>`;
                        }
                    },
                    {
                        data: 'user.username',
                        name: 'user.username'
                    },
                    {
                        data: 'user.email',
                        name: 'user.email'
                    },
                    {
                        data: 'user.phone_number',
                        name: 'user.phone_number'
                    },
                    {
                        data: 'category_user',
                        name: 'category_user'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    }
                ],
            });
        });
    </script>
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

            $('#modalAddMahasiswaUser').on('show.bs.modal', function() {
                $('.select-province, .select-city, .select-subdistrict, .select-village').empty().append(
                    '<option value="">Pilih</option>');
                loadProvinces($('.select-province'));
            });

            $(document).on('click', '[data-bs-target^="#modalUpdateParticipant-"]', function() {
                let targetModal = $($(this).data('bs-target'));
                let selectedProvince = targetModal.find('.select-province').data('selected');
                let selectedCity = targetModal.find('.select-city').data('selected');
                let selectedSubdistrict = targetModal.find('.select-subdistrict').data('selected');
                let selectedVillage = targetModal.find('.select-village').data('selected');

                loadProvinces(targetModal.find('.select-province'), selectedProvince);
                if (selectedProvince) {
                    loadDropdown(`${apiBaseUrl}/regencies/${selectedProvince}.json`, targetModal.find(
                        '.select-city'), selectedCity, function() {
                        if (selectedCity) {
                            loadDropdown(`${apiBaseUrl}/districts/${selectedCity}.json`, targetModal
                                .find('.select-subdistrict'), selectedSubdistrict,
                                function() {
                                    if (selectedSubdistrict) {
                                        loadDropdown(
                                            `${apiBaseUrl}/villages/${selectedSubdistrict}.json`,
                                            targetModal.find('.select-village'),
                                            selectedVillage);
                                    }
                                });
                        }
                    });
                }
            });
        });
    </script>

    @include('components.script-crud')
@endpush
