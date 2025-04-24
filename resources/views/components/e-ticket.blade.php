<!-- resources/views/tickets/e-ticket.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>E-Ticket: {{ $participant->event->title }}</title>
    <link rel="stylesheet" href="assets/css/lib/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .steps-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .step-box {
            width: 48%;
            /* 2 kolom dengan jarak */
            box-sizing: border-box;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .detail-value {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .detail-location {
            font-size: 12px;
            margin-bottom: 3px;
        }

        .ticket-container {
            width: 100%;
            max-width: 794px;
            margin: 0 auto;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .date-box {
            position: absolute;
            left: 80px;
            top: 160px;
            background-color: #3eb5ff;
            color: white;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .date-day {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
            padding: 0;
            line-height: 1;
        }

        .date-month {
            font-size: 18px;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
            line-height: 1;
        }

        .date-year {
            font-size: 18px;
            margin: 0;
            padding: 0;
            line-height: 1;
        }

        .ticket-body {
            padding: 20px;
        }

        .event-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .location {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            color: #666;
        }

        .location-icon {
            width: 20px;
            height: 20px;
            margin-right: 5px;
            background-color: #6b4ee6;
            border-radius: 50%;
            display: inline-block;
        }

        .ticket-details {
            display: flex;
            border-top: 1px dashed #ccc;
            border-bottom: 1px dashed #ccc;
            padding: 20px 0;
        }

        .detail-column {
            flex: 1;
        }

        .detail-label {
            color: #999;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .detail-value {
            font-weight: bold;
            font-size: 16px;
        }

        .ticket-info {
            margin-top: 20px;
        }

        .info-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-list {
            padding-left: 20px;
            margin: 0;
        }

        .info-list li {
            margin-bottom: 8px;
            color: #666;
        }

        .qr-section {
            text-align: right;
            float: right;
            margin-top: -180px;
            margin-right: 20px;
        }

        .qr-code {
            width: 150px;
            height: 150px;
        }

        .ticket-number {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="fluid-container card">
        <div class="card-header">
            @php
                $bannerPath = public_path('storage/' . $participant->event->banner_path);
                $bannerHeight = count($participant->event->steps) == 1 ? '300px' : '250px';
            @endphp

            @if (file_exists($bannerPath))
                <img class="cover-img" src="{{ $bannerPath }}" alt="Event Header"
                    style="width: 100%; height: {{ $bannerHeight }};">
            @else
                <p>Banner tidak ditemukan.</p>
            @endif

        </div>

        <div class="card-body">
            <center>
                <h1 class="event-title">{{ $participant->event->title }}</h1>
            </center>

            <div class="ticket-details">
                <div class="detail-column">
                    <div class="detail-label">Kode Tiket</div>
                    <div class="detail-value">{{ $participant->ticket_code }}</div>
                </div>
            </div>
            @if (count($participant->event->steps) == 1)
                @php
                    $location_offline = null;
                    $location_online = null;
                    $location = null;

                    $step = $participant->event->steps->first();
                    $location_decode = json_decode($step->location, true);
                    $executionSystemDisplay = $location_decode[0]['type'];
                    if ($location_decode[0]['type'] == 'hybrid') {
                        $location_off = $location_decode[0]['location_offline'];

                        $asset = \App\Models\Asset::findOrFail($location_off);
                        if ($asset) {
                            $location_offline = $asset->name;
                        }
                        $location_online = $location_decode[0]['location_online'];
                    } else {
                        $loc = $location_decode[0]['location'];
                        $asset = \App\Models\Asset::findOrFail($loc);
                        if ($asset) {
                            $location = $asset->name;
                        }
                    }

                    $tanggal = \Carbon\Carbon::parse($step->event_date)->translatedFormat('d F Y');
                    $jamMulai = \Carbon\Carbon::parse($step->event_time_start)->translatedFormat('H.i');
                    $jamSelesai = \Carbon\Carbon::parse($step->event_time_end)->translatedFormat('H.i');
                @endphp
                <div class="ticket-details">
                    <div class="detail-column">
                        <div class="detail-label">Pelaksanaan</div>
                        <div class="detail-value fw-bold">{{ ucwords($executionSystemDisplay) }}</div>
                        <div class="detail-location">Tanggal : {{ $tanggal }},
                            {{ $jamMulai }}-{{ $jamSelesai }}
                        </div>
                        @if ($executionSystemDisplay == 'hybrid')
                            <div class="detail-location">Lokasi Offline: {{ $location_offline }}</div>
                            <div class="detail-location">Lokasi Online: {{ $location_online }}</div>
                        @else
                            <div class="detail-location">{{ $location }}</div>
                        @endif
                    </div>
                </div>
            @elseif(count($participant->event->steps) > 1)
                @php
                    $location_offline = null;
                    $location_online = null;
                    $location = null;
                    $steps = $participant->event->steps;
                @endphp
                <div class="ticket-details">
                    <div class="detail-column">
                        <div class="detail-label">Pelaksanaan</div>
                        <table width="100%" cellpadding="10" cellspacing="0">
                            @foreach ($steps->chunk(2) as $stepPair)
                                <tr>
                                    @foreach ($stepPair as $step)
                                        @php
                                            $location_decode = json_decode($step->location, true);
                                            $executionSystemDisplay = $location_decode[0]['type'];

                                            if ($executionSystemDisplay === 'hybrid') {
                                                $location_off = $location_decode[0]['location_offline'];
                                                $location_offline =
                                                    $step->location_type === 'campus'
                                                        ? optional(\App\Models\Asset::find($location_off))->name ??
                                                            $location_off
                                                        : $location_off;

                                                $location_online = $location_decode[0]['location_online'];
                                            } else {
                                                $loc = $location_decode[0]['location'];
                                                $location =
                                                    $step->location_type === 'campus'
                                                        ? optional(\App\Models\Asset::find($loc))->name ?? $loc
                                                        : $loc;
                                            }

                                            $tanggal = \Carbon\Carbon::parse($step->event_date)->translatedFormat(
                                                'd F Y',
                                            );
                                            $jamMulai = \Carbon\Carbon::parse(
                                                $step->event_time_start,
                                            )->translatedFormat('H.i');
                                            $jamSelesai = \Carbon\Carbon::parse(
                                                $step->event_time_end,
                                            )->translatedFormat('H.i');
                                        @endphp
                                        <td width="50%"
                                            style="vertical-align: top; border: 1px solid #ccc; padding: 8px;">
                                            <strong>{{ ucfirst($executionSystemDisplay) }}</strong><br>
                                            <span>Tanggal: {{ $tanggal }},
                                                {{ $jamMulai }}–{{ $jamSelesai }}</span><br>
                                            @if ($executionSystemDisplay === 'hybrid')
                                                <span>Lokasi Offline: {{ $location_offline }}</span><br>
                                                <span>Lokasi Online: {{ $location_online }}</span>
                                            @else
                                                <span>Lokasi: {{ $location }}</span>
                                            @endif
                                        </td>
                                    @endforeach

                                    {{-- Jika cuma ada 1 data di baris ini, tambahkan kolom kosong --}}
                                    @if ($stepPair->count() < 2)
                                        <td width="50%"></td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            @endif
            <div class="ticket-details">
                <div class="detail-column">
                    <div class="detail-label">Nama</div>
                    <div class="detail-value">{{ $participant->user->name }}</div>
                </div>
                <div class="detail-column">
                    @php
                        $user = $participant->user;
                        if ($user->category_user == 'Internal Kampus') {
                            $categoryUser = 'Mahasiswa J' . $user->jurusan->kode_jurusan;
                        } else {
                            $categoryUser = 'Eksternal Kampus';
                        }
                    @endphp
                    <div class="detail-label">Asal</div>
                    <div class="detail-value">{{ $categoryUser }}</div>
                </div>
            </div>
            <table>
                <tr>
                    <td style="width:70%">
                        <div class="ticket-info" style="font-size: 14px">
                            <div class="info-title">Informasi Tiket</div>
                            <ul class="info-list">
                                <li>Tunjukkan e-Tiket/QR Code yang telah diterima kepada panitia di lokasi Event.</li>
                                <li>Setelah sudah terverifikasi, Pemilik tiket dapat memasuki Event.</li>
                                <li>Pengunjung WAJIB untuk mematuhi aturan yang berlaku selama acara berlangsung.</li>
                            </ul>
                        </div>
                    </td>
                    <td style="width:30%">
                        <center>
                            <img src="data:image/png;base64,{{ $qrCode }}" width="200px">
                            <span class="fw-bold">Scan for attendance</span>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
