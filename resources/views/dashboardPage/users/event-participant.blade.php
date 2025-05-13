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


    @include('components.script-crud')
@endpush
