@extends('layout.layout')
@section('title', 'Events Management')
@php
    $title = 'Events Management';
    $subTitle = 'Events Management';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-header">
            <h5 class="card-title mb-0 align-content-center">Daftar Event</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <p class="mb-0 fw-semibold">Penyelenggara:</p>
                    <select class="form-select form-select-sm w-auto rounded-3" id="organizer">
                        @foreach ($organizers as $type => $organizersGroup)
                            <optgroup label="{{ $type }}">
                                @foreach ($organizersGroup as $organizer)
                                    <option value="{{ $organizer->id }}">{{ $organizer->user->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <table class="table bordered-table mb-0 w-100 h-100 row-border order-column  sm-table" id="listEventTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Nama Event</th>
                            <th>Status</th>
                            <th>Sasaran</th>
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
            let listEventTable = $('#listEventTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('listEvent.getData') }}",
                    type: 'GET',
                    data: function(d) {
                        d.organizer_id = $('#organizer').val();
                    }
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
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'scope',
                        name: 'scope'
                    },
                ],
            });

            $('#organizer').change(function() {
                listEventTable.ajax.reload();
            });
        });
    </script>
    @include('components.script-crud')
@endpush
