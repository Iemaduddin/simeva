@extends('layout.layout')
@section('title', 'Jurusan & Prodi Management')
@php
    $title = 'Jurusan & Prodi Management';
    $subTitle = 'Jurusan & Prodi Management';
@endphp

@section('content')
    <style>
        .bg-brown {
            background-color: #543A14;
        }
    </style>
    <div class="card p-0 overflow-hidden position-relative radius-12 h-100">
        <div class="card-body p-24 pt-10">
            <ul class="nav bordered-tab border border-top-0 border-start-0 border-end-0 d-inline-flex nav-pills mb-16"
                id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10 active" id="pills-jurusan-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-jurusan" type="button" role="tab" aria-controls="pills-jurusan"
                        aria-selected="true">Jurusan</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-16 py-10" id="pills-prodi-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-prodi" type="button" role="tab" aria-controls="pills-prodi"
                        aria-selected="false">Program Studi</button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-jurusan" role="tabpanel"
                    aria-labelledby="pills-jurusan-tab" tabindex="0">
                    {{-- content --}}
                    <div class="flex-grow-1">
                        <div class="basic-data-table">
                            <div class="d-flex justify-content-between mb-20">
                                <h5 class="card-title mb-0 align-content-center">Data Jurusan</h5>
                                <button
                                    class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                                    data-bs-toggle="modal" data-bs-target="#modalAddJurusan">
                                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                                    Tambah Jurusan Baru
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table bordered-table mb-0 w-100 h-100 row-border order-column  sm-table"
                                    id="jurusanTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Jurusan</th>
                                            <th>Kode Jurusan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>
                    @include('dashboardPage.jurusanprodi.modaljurusan.add-jurusan')
                </div>
                <div class="tab-pane fade" id="pills-prodi" role="tabpanel" aria-labelledby="pills-prodi-tab"
                    tabindex="0">
                    {{-- content --}}
                    <div class="flex-grow-1">
                        <div class="basic-data-table">
                            <div class="d-flex justify-content-between mb-20">
                                <h5 class="card-title mb-0 align-content-center">Data Program Studi</h5>
                                <button
                                    class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                                    data-bs-toggle="modal" data-bs-target="#modalAddProdi">
                                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                                    Tambah Prodi Baru
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table bordered-table mb-0 w-100 h-100 row-border order-column sm-table"
                                    id="prodiTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Prodi</th>
                                            <th>Kode Prodi</th>
                                            <th>Jurusan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    @include('dashboardPage.jurusanprodi.modalprodi.add-prodi')
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            let stakeholderTable = $('#jurusanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('jurusan.getData') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex', // Data dari addIndexColumn di server-side
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    }, {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'kode_jurusan',
                        name: 'kode_jurusan'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let stakeholderTable = $('#prodiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('prodi.getData') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex', // Data dari addIndexColumn di server-side
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    }, {
                        data: 'nama_prodi',
                        name: 'nama_prodi'
                    },
                    {
                        data: 'kode_prodi',
                        name: 'kode_prodi'
                    },
                    {
                        data: 'jurusan_id',
                        name: 'jurusan_id'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        });
    </script>
    @include('components.script-crud')
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notyf = new Notyf({
                duration: 4000,
                position: {
                    x: 'right',
                    y: 'bottom',
                }
            });

            // document.getElementById('addJurusanForm').addEventListener('submit', function(e) {
            //     e.preventDefault();
            //     const formData = new FormData(this);
            //     fetch('{{ route('add.jurusan') }}', {
            //             method: 'POST',
            //             body: formData,
            //         })
            //         .then(response => {
            //             if (!response.ok) {
            //                 return response.json().then(err => Promise.reject(err));
            //             }
            //             return response.json();
            //         })
            //         .then(data => {
            //             if (data.status === 'success') {
            //                 notyf.success(data.message);
            //                 $('#modalAddJurusan').modal('hide');
            //                 this.reset();
            //                 $('#jurusanTable').DataTable().ajax.reload();
            //             }
            //         })
            //         .catch(error => {
            //             if (error.errors) {
            //                 // Menampilkan pesan error validasi
            //                 Object.values(error.errors).forEach(messages => {
            //                     messages.forEach(message => {
            //                         notyf.error(message);
            //                     });
            //                 });
            //             } else {
            //                 notyf.error(error.message || 'Terjadi kesalahan pada server!');
            //             }
            //         });
            // });
            // // Event delegation untuk form update
            // document.body.addEventListener('submit', function(e) {
            //     const jurusanId = e.target.dataset.jurusanId;
            //     if (!e.target.matches(`#updateJurusanForm-${jurusanId}`)) return;
            //     e.preventDefault();

            //     const form = e.target;
            //     const formData = new FormData(form);
            //     const actionUrl = form.getAttribute('action');

            //     fetch(actionUrl, {
            //             method: 'POST',
            //             body: formData,
            //             headers: {
            //                 'X-Requested-With': 'XMLHttpRequest',
            //                 'Accept': 'application/json'
            //             }
            //         })
            //         .then(response => {
            //             if (!response.ok) {
            //                 return response.json().then(err => Promise.reject(err));
            //             }
            //             return response.json();
            //         })
            //         .then(data => {
            //             if (data.status === 'success') {
            //                 notyf.success(data.message); // Alert Berhasil
            //                 const modalId = form.closest('.modal')
            //                     .id; // Dapatkan ID modal yang benar

            //                 // Tutup modal
            //                 $(`#${modalId}`).modal('hide');
            //                 form.reset();
            //                 // Memperbarui DataTable
            //                 $('#jurusanTable').DataTable().ajax.reload();
            //             } else {
            //                 notyf.error(data.message); // Alert Gagal
            //             }
            //         })
            //         .catch(error => {
            //             if (error.errors) {
            //                 // Menampilkan pesan error validasi
            //                 Object.values(error.errors).forEach(messages => {
            //                     messages.forEach(message => {
            //                         notyf.error(message);
            //                     });
            //                 });
            //             } else {
            //                 notyf.error(error.message || 'Terjadi kesalahan pada server!');
            //             }
            //         });
            // });

            // Ketika tombol Delete di modal ditekan
            // Variabel untuk menyimpan form yang akan di-submit
            let currentDeleteForm = null;
            let currentTableId = null;
            // Event delegation untuk form delete (jurusan dan prodi)
            document.addEventListener('click', function(e) {
                // Cek apakah form yang di-submit adalah form delete jurusan atau prodi
                if (e.target.closest('.delete-btn')) {
                    console.log('yes');
                    e.preventDefault(); // Mencegah aksi default
                    const button = e.target.closest('.delete-btn');
                    const form = button.closest('form');

                    if (form) {
                        currentDeleteForm = form;
                        currentTableId = form.dataset.table || null;

                        $('#confirmDelete').modal('show'); // Tampilkan modal konfirmasi
                    }
                }
            });

            // Event ketika tombol konfirmasi delete di modal ditekan
            // Variabel untuk menyimpan form yang akan di-submit
            let currentDeleteForm = null;

            // Event delegation untuk form delete (jurusan dan prodi)
            document.addEventListener('submit', function(e) {
                const form = e.target;

                // Cek apakah form yang di-submit adalah form delete jurusan atau prodi
                if (form.matches('.delete-jurusan-form, .delete-prodi-form')) {
                    e.preventDefault();
                    currentDeleteForm = form;

                    // Tampilkan modal konfirmasi
                    $('#confirmDelete').modal('show');
                }
            });

            // Event ketika tombol konfirmasi delete di modal ditekan
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (currentDeleteForm) {
                    const formData = new FormData(currentDeleteForm);
                    const notyf = new Notyf({
                        duration: 3000,
                        position: {
                            x: 'right',
                            y: 'top',
                        }
                    });

                    // Tentukan tabel mana yang perlu di-refresh berdasarkan class form
                    const tableToRefresh = currentDeleteForm.classList.contains('delete-jurusan-form') ?
                        '#jurusanTable' :
                        '#prodiTable';

                    fetch(currentDeleteForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
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
                                notyf.success(data.message || 'Data berhasil dihapus');
                                // Refresh tabel yang sesuai
                                $(tableToRefresh).DataTable().ajax.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            notyf.error(error.message || 'Terjadi kesalahan pada server');
                        })
                        .finally(() => {
                            $('#confirmDelete').modal('hide');
                            currentDeleteForm = null;
                        });
                }
            });
        });
    </script> --}}
@endpush
