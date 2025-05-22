@extends('layout.layout')
@section('title', 'Team Members Management')
@php
    $title = 'Team Members Management';
    $subTitle = 'Team Members Management';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title mb-0 align-content-center">Event</h5>
            <button class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#modalAddTeamMember">
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Tambah Anggota Tim
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 w-100 h-100 row-border order-column  sm-table"
                    id="{{ $shorten_name }}-TeamMembersTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Prodi</th>
                            <th>Tingkatan</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('dashboardPage.team-members.modal.add-team')
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            const shortenName = "{{ $shorten_name }}";
            const tableId = `${shortenName}-TeamMembersTable`;
            let teamMembersTable = $(`#${tableId }`).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('team-members.getData', ':shortenName') }}".replace(':shortenName',
                        shortenName),
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex', // Data dari addIndexColumn di server-side
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'prodi.nama_prodi',
                        name: 'prodi.nama_prodi'
                    },
                    {
                        data: 'level',
                        name: 'level'
                    },
                    {
                        data: 'position',
                        name: 'poosition'
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
@endpush
