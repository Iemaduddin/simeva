@extends('layout.layout')
@section('title', 'Event Management')
@php
    $title = 'Event Management';
    $subTitle = 'Event Management';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title mb-0 align-content-center">Daftar Event</h5>
            <a href="{{ route('add.event.page') }}"
                class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Tambah Event
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 w-100 h-100 row-border order-column  sm-table"
                    id="{{ $shorten_name }}-EventsTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Nama</th>
                            <th>Kuota</th>
                            <th>Sasaran</th>
                            <th>Pendaftaran</th>
                            <th>Pelaksanaan</th>
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
            const shortenName = "{{ $shorten_name }}";
            const tableId = `${shortenName}-EventsTable`;
            let eventsTable = $(`#${tableId }`).DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('events.getData', ':shortenName') }}".replace(':shortenName',
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'quota',
                        name: 'quota'
                    },
                    {
                        data: 'scope',
                        name: 'scope'
                    },
                    {
                        data: 'register_date',
                        name: 'register_date',
                        render: function(data, type, row) {
                            function formatDate(dateStr) {
                                let date = new Date(dateStr);
                                let options = {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                };
                                return date.toLocaleDateString('id-ID', options).replace(
                                        '.', ':')
                                    .replace(':', '.');
                            }

                            return `${formatDate(row.registration_date_start)} - <br> ${formatDate(row.registration_date_end)}`;
                        }
                    },
                    {
                        data: 'event_date_location',
                        name: 'event_date_location',
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                ],
            });
        });
    </script>
    @include('components.script-crud')
@endpush
