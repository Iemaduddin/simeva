@extends('layout.layout')
@section('title', 'Users Management')
@php
    $title = 'Users Management';
    $subTitle = 'Users Management';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title mb-0 align-content-center">Stakeholder User</h5>
            <button class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#modalAddStakeholderUser">
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Tambah User Baru
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 w-100 h-100 row-border order-column  sm-table"
                    id="stakeholderUserTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Nomor Handphone</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('dashboardPage.users.stakeholder.modal.add-user')
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            let stakeholderTable = $('#stakeholderUserTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('stakeholderUsers.getData') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex', // Data dari addIndexColumn di server-side
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                ],
            });
        });
    </script>


    @include('components.script-crud')

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addStakeholderUserForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const notyf = new Notyf();

                fetch('{{ route('add.stakeholderUser') }}', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            notyf.success(data.message); // Alert Berhasil
                            $('#modalAddStakeholderUser').modal('hide');
                            this.reset();
                            // Memperbarui DataTable
                            $('#stakeholderUserTable').DataTable().ajax.reload();
                        } else {
                            notyf.error(data.message); // Alert Gagal
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        notyf.error('Terjadi kesalahan pada server!');
                    });
            });
            // Event delegation untuk form update
            document.body.addEventListener('submit', function(e) {
                const form = e.target.closest('#updateStakeholderUserForm-' + e.target.dataset.userId);
                if (form) {
                    e.preventDefault();
                    const notyf = new Notyf();
                    const formData = new FormData(form);
                    const actionUrl = form.getAttribute('action');

                    fetch(actionUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                notyf.success(data.message); // Alert Berhasil
                                const modalId = form.closest('.modal')
                                    .id; // Dapatkan ID modal yang benar

                                // Tutup modal
                                $(`#${modalId}`).modal('hide');
                                form.reset();
                                // Memperbarui DataTable
                                $('#stakeholderUserTable').DataTable().ajax.reload();
                            } else {
                                notyf.error(data.message); // Alert Gagal
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            notyf.error('Terjadi kesalahan pada server!');
                        });
                }
            });

            // Event delegation untuk delete form
            let currentDeleteForm = null; // Variabel untuk menyimpan form yang akan dihapus

            document.body.addEventListener('submit', function(e) {
                const deleteForm = e.target.closest('.delete-user-form');

                if (deleteForm) {
                    e.preventDefault(); // Mencegah form langsung submit
                    currentDeleteForm = deleteForm; // Simpan form ke variabel sementara

                    // Tampilkan modal konfirmasi
                    $('#confirmDeleteStakeHolderUser').modal('show');
                }
            });

            // Ketika tombol Delete di modal ditekan
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (currentDeleteForm) {
                    const formData = new FormData(currentDeleteForm);
                    const notyf = new Notyf();

                    fetch(currentDeleteForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                console.log('halo');
                                notyf.success('User berhasil dihapus');
                                $('#stakeholderUserTable').DataTable().ajax.reload();
                            } else {
                                notyf.error(data.message || 'Gagal menghapus user');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            notyf.error('Terjadi kesalahan pada server');
                        })
                        .finally(() => {
                            // Tutup modal setelah proses selesai
                            $('#confirmDeleteStakeHolderUser').modal('hide');
                        });
                }
            });
        });
    </script> --}}
@endpush
