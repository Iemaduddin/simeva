<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Undangan</title>
    <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 2cm, 2cm, 2cm, 3cm;
            color: #000;
        }

        .kop {
            text-align: center;
        }

        .kop .title {
            font-size: 16pt;
        }

        .kop .subtitle {
            font-size: 14pt;
            font-weight: bold;
        }

        .kop p {
            margin: 0;
            font-size: 10pt;
        }


        .content p {
            text-align: justify;
        }

        .signature td {
            /* padding-top: 40px; */
            vertical-align: top;
        }

        .foot-number {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12pt;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: left;
            font-size: 12px;
        }

        @media print {
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
            }

            @page {
                size: A4;
                margin: 2cm 2cm 2cm 2cm;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div id="invitationLetter">
        @if ($organizer->organizer_type == 'Kampus' || $organizer->organizer_type == 'Jurusan')
            <div class="row align-items-center justify-content-between">
                <div class="col-2 d-flex justify-content-start">
                    <img src="{{ asset('assets/images/logo_polinema_outline.png') }}" alt="Logo Polinema" width="120px"
                        class="me-5">
                </div>
                <div class="col-10 kop">
                    <div class="title">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, <br>
                        RISET, DAN TEKNOLOGI</div>
                    <div class="subtitle">POLITEKNIK NEGERI MALANG</div>
                    <div class="subtitle">{{ strtoupper($organizer->user->name) }}</div>
                    <p>Jl. Soekarno Hatta No. 9, Jatimulyo, Lowokwaru, Malang 65141</p>
                    <p>Telepon: (0341) 404424/404425, Fax: (0341) 404420 <br> Laman: www.polinema.ac.id</p>
                </div>
            </div>
        @else
            <div class="row align-items-center justify-content-between">
                <div class="col-1 d-flex justify-content-start">
                    <img src="{{ asset('assets/images/logo_polinema.png') }}" alt="Logo Polinema" width="100px"
                        class="me-5">
                </div>
                <div class="col-10 kop">
                    <div class="title">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, <br>
                        RISET, DAN TEKNOLOGI</div>
                    <div class="subtitle">POLITEKNIK NEGERI MALANG</div>
                    <div class="subtitle">{{ strtoupper($organizer->user->name) }}</div>
                    <p>Jl. Soekarno Hatta No. 9, Jatimulyo, Lowokwaru, Malang 65141</p>
                    <p>Telepon: (0341) 404424/404425, Fax: (0341) 404420 <br> Laman: www.polinema.ac.id</p>
                </div>
                <div class="col-1 d-flex justify-content-end">
                    <img src="{{ asset('storage/' . $organizer->logo) }}" alt="Logo Organizer" width="100px">
                </div>
            </div>
        @endif

        <hr>

        <div class="row mb-4">
            <div class="col-8">
                <table>
                    <tr>
                        <td>Nomor</td>
                        <td>:</td>
                        <td>{{ $letter_number }}</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Hal</td>
                        <td>:</td>
                        <td>Undangan {{ $speaker->role }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-4 text-end">
                @php
                    \Carbon\Carbon::setLocale('id');
                    $today = \Carbon\Carbon::now()->translatedFormat('d F Y');
                @endphp
                <p>Malang, {{ $today }}</p>
            </div>
        </div>

        <div class="content mb-4">
            <p>Yth. {{ $speaker->name }}</p>

            Dengan hormat,

            Sehubungan dengan akan diselenggarakannya kegiatan “{{ ucwords($speaker->event_step->event->title) }}”,
            maka
            dengan
            ini kami
            mengharap
            kehadiran Saudara/i/Bapak/Ibu pada:

            <table style="margin-left: 2em;">
                <tr>
                    <td>hari, tanggal</td>
                    <td>: {{ \Carbon\Carbon::parse($speaker->event_step->event_date)->translatedFormat('l, d F Y') }}
                    </td>

                </tr>
                <tr>
                    <td>pukul</td>
                    <td>: {{ \Carbon\Carbon::parse($speaker->event_step->event_time_start)->translatedFormat('H.i') }}
                        -
                        {{ \Carbon\Carbon::parse($speaker->event_step->event_time_end)->translatedFormat('H.i') }}
                        WIB</td>
                </tr>
                @php
                    $event_location = '';
                    $event_execution_type = '';
                    $location_decode = json_decode($speaker->event_step->location ?? '[]', true);

                    if (isset($location_decode[0])) {
                        if (
                            $location_decode[0]['type'] === 'offline' &&
                            $speaker->event_step->location_type === 'campus'
                        ) {
                            $asset = \App\Models\Asset::where('id', $location_decode[0]['location'])->first();
                            $assetName = \App\Models\Asset::where('id', $location_decode[0]['location'])->value('name');
                            $jurusan = null;
                            if ($asset->jurusan_id) {
                                $jurusan = $asset->jurusan->nama;
                            }
                            $isBooked = \App\Models\AssetBooking::where('asset_id', $asset->id)
                                ->where('event_id', $speaker->event_step->event->id)
                                ->whereIn('status', ['booked', 'approved', 'submission_full_payment'])
                                ->exists();

                            if ($isBooked) {
                                $event_location = $assetName . ' ' . $jurusan ?? '';
                            } else {
                                $event_location = $assetName . ' ' . ($jurusan ?? '') . ' (Belum Disetujui)';
                            }
                            $event_execution_type = 'offline';
                        } elseif (
                            $location_decode[0]['type'] === 'offline' &&
                            $speaker->event_step->location_type === 'manual'
                        ) {
                            $event_location =
                                $location_decode[0]['location'] . ' (' . $location_decode[0]['address'] . ')';
                            $event_execution_type = 'offline';
                        } elseif ($location_decode[0]['type'] === 'online') {
                            $event_location = $location_decode[0]['location'];
                            $event_execution_type = 'online';
                        } elseif (
                            $location_decode[0]['type'] === 'hybrid' &&
                            $speaker->event_step->location_type === 'campus'
                        ) {
                            $asset = \App\Models\Asset::where('id', $location_decode[0]['location_offline'])->first();
                            $assetName = \App\Models\Asset::where('id', $location_decode[0]['location_offline'])->value(
                                'name',
                            );
                            $jurusan = null;
                            if ($asset->jurusan_id) {
                                $jurusan = $asset->jurusan->nama;
                            }
                            $isBooked = \App\Models\AssetBooking::where('asset_id', $asset->id)
                                ->where('event_id', $speaker->event_step->event->id)
                                ->whereIn('status', ['booked', 'approved', 'submission_full_payment'])
                                ->exists();

                            if ($isBooked) {
                                $event_location =
                                    'Offline: ' .
                                    $assetName .
                                    ' ' .
                                    $jurusan .
                                    '<br>Online: ' .
                                    $location_decode[0]['location_online'];
                            } else {
                                $event_location =
                                    'Offline: ' .
                                    $assetName .
                                    ($isValidBooking ? ' ' . ($jurusan ?? '') : ' (Belum Disetujui)') .
                                    '<br>Online: ' .
                                    $location_decode[0]['location_online'];
                            }
                            $event_execution_type = 'hybrid';
                        }
                    }
                @endphp
                <tr>
                    <td>tempat</td>
                    @if ($speaker->event_step->location_type == 'campus')
                        <td>: {!! $event_location !!}
                            {{ $event_execution_type == 'offline' ? 'Politeknik Negeri Malang' : '' }}</td>
                    @else
                        <td>: {!! $event_location !!}</td>
                    @endif
                </tr>
            </table>
            Demikian surat undangan ini kami buat. Mengingat pentingnya acara tersebut, besar harapan kami agar
            Saudara/i/Bapak/Ibu berkenan hadir. Atas perhatiannya kami mengucapkan terima kasih.

            <br><br>
        </div>

        <table class="w-100 signature">
            <tr>
                <td></td>
                <td>Hormat kami,</td>
            </tr>
            <tr>
                <td>{{ $leader->position }} {{ $organizer->shorten_name }},</td>
                <td>Ketua Pelaksana,</td>
            </tr>
            <tr>
                <td style="padding-top: 60px;">{{ $leader->name }}</td>
                <td style="padding-top: 60px;">{{ $event_leader->name }}</td>
            </tr>
            <tr>
                <td>NIM. {{ $leader->nim }}</td>
                <td>NIM. {{ $event_leader->nim }}</td>
            </tr>
        </table>

        <div class="mt-5 footer">
            <hr>
            <p class="fw-medium foot-number mt-0">FRM.BAA.03.19.00</p>
        </div>



        <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                window.print();
            });
        </script>
</body>

</html>
