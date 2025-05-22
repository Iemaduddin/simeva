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
            margin: 2cm 2cm 2cm 3cm;
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

            /* Tambah padding bawah supaya garis tidak terlalu menempel ke bawah */
            /* padding-bottom: 10px; */
            /* Tambah padding atas supaya teks agak turun dari atas */
            padding-top: 10px;
            background: white;
            /* supaya garis footer jelas di print */
        }

        .footer hr {
            margin-top: 15px;
            /* jarak garis ke atas */
            margin-bottom: 5px;
            /* jarak garis ke bawah */
            border: 1px solid #000;
            /* pastikan garis terlihat */
        }


        @media print {
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                /* padding-bottom: 10px; */
                /* padding-top: 10px; */
                margin-top: 30px;
                background: white;
            }

            @page {
                size: A4;
                margin: 1.5cm 2cm 2cm 2cm;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    @foreach ($assetBookings->unique('asset_id') as $index => $booking)
        <div id="loanFormPage1">
            @if ($organizer->organizer_type == 'Kampus' || $organizer->organizer_type == 'Jurusan')
                <div class="row align-items-center justify-content-between">
                    <div class="col-2 d-flex justify-content-start">
                        <img src="{{ asset('assets/images/logo_polinema_outline.png') }}" alt="Logo Polinema"
                            width="120px" class="me-5">
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
                            <td>{{ $letter_number[$booking->asset->id] }}</td>
                        </tr>
                        <tr>
                            <td>Lampiran</td>
                            <td>:</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Hal</td>
                            <td>:</td>
                            <td>Peminjaman Aset</td>
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

            <div class="content mb-2">
                <p>Yth.
                    {{ $booking->asset->facility_scope == 'umum' ? 'Wakil Direktur II' : 'Ketua Jurusan ' . $booking->jurusan->nama }}
                </p>

                Dengan hormat, <br>
                <p class="text-justify">
                    Sehubungan dengan akan diselenggarakannya kegiatan
                    <strong>“{{ ucwords($booking->event->title) }}”</strong>,
                    kami memohon bantuan untuk peminjaman <strong>{{ $booking->asset->name }}</strong> beserta
                    fasilitas
                    yang tersedia di dalamnya,
                    termasuk penggunaan daya listrik pada gedung tersebut.
                    Kegiatan tersebut akan diselenggarakan pada waktu yang telah dilampirkan.
                </p>

                <p class="text-justify">
                </p>

                <p class="text-justify">
                    Demikian surat permohonan ini kami sampaikan. Atas perhatian dan bantuan yang diberikan, kami
                    ucapkan terima kasih.
                </p>
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
                    <td style="padding-top: 40px;">{{ $leader->name }}</td>
                    <td style="padding-top: 40px;">{{ $event_leader->name }}</td>
                </tr>
                <tr>
                    <td>NIM. {{ $leader->nim }}</td>
                    <td>NIM. {{ $event_leader->nim }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">Mengetahui dan menyetujui,</td>
                </tr>
                <tr>
                    <td>Dosen Pembina Kemahasiswaan,</td>
                    <td>Presiden BEM,</td>
                </tr>
                <tr>
                    <td style="padding-top: 40px;">{{ $dpk->name }}</td>
                    <td style="padding-top: 40px;">{{ $presiden->name }}</td>
                </tr>
                <tr>
                    <td>NIP. {{ $dpk->identifier }}</td>
                    <td>NIM. {{ $presiden->identifier }}</td>
                </tr>
                <tr>
                    <td>Wakil Direktur III,</td>
                    <td>Ketua Jurusan {{ $kajur->jurusan->kode_jurusan }},</td>
                </tr>
                <tr>
                    <td style="padding-top: 40px;">{{ $wadir3->name }}</td>
                    <td style="padding-top: 40px;">{{ $kajur->name }}</td>
                </tr>
                <tr>
                    <td>NIP. {{ $wadir3->identifier }}</td>
                    <td>NIP. {{ $kajur->identifier }}</td>
                </tr>
                <tr>
                    <td></td>
                    {{-- <td>{{ $contact_person }}</td> --}}
                </tr>
            </table>

            <div class="mt-5 footer">
                <hr>
                <p class="fw-medium foot-number mt-0">FRM.BAA.03.18.00</p>
            </div>
        </div>
        <div style="page-break-before: always"></div>

        <div id="loanFormPage2">
            @if ($organizer->organizer_type == 'Kampus' || $organizer->organizer_type == 'Jurusan')
                <div class="row align-items-center justify-content-between">
                    <div class="col-2 d-flex justify-content-start">
                        <img src="{{ asset('assets/images/logo_polinema_outline.png') }}" alt="Logo Polinema"
                            width="120px" class="me-5">
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
                <p><i>Lampiran 1</i></p>
                <p>Nomor &nbsp;&nbsp;&nbsp; : {{ $letter_number[$booking->asset->id] }}</p>
                <div class="col-12">
                    <h5 class="mb-3 text-center">Kegiatan {{ $booking->event->title }}</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="align-middle text-center">No.</th>
                                <th class="align-middle text-center">Acara</th>
                                <th class="align-middle text-center">Tanggal Peminjaman</th>
                                <th class="align-middle text-center">Pukul</th>
                                <th class="align-middle text-center">Tempat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $assetBookingsPerAsset = $assetBookings->where('asset_id', $booking->asset->id);
                                $i = 0;
                            @endphp

                            @foreach ($assetBookingsPerAsset as $book)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $book->usage_event_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($book->usage_date_start)->format('d F Y') }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($book->usage_date_start)->format('H.i') }} -
                                        {{ \Carbon\Carbon::parse($book->usage_date_end)->format('H.i') }}
                                    </td>

                                    @if ($i == 0)
                                        <td rowspan="{{ $assetBookingsPerAsset->count() }}"
                                            class="align-middle text-center">
                                            {{ $book->asset->name }}
                                        </td>
                                    @endif
                                </tr>
                                @php $i++; @endphp
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div style="page-break-before: always;"></div>

        <div id="loanFormPage3">
            @if ($organizer->organizer_type == 'Kampus' || $organizer->organizer_type == 'Jurusan')
                <div class="row align-items-center justify-content-between">
                    <div class="col-2 d-flex justify-content-start">
                        <img src="{{ asset('assets/images/logo_polinema_outline.png') }}" alt="Logo Polinema"
                            width="120px" class="me-5">
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
                <p><i>Lampiran 2</i></p>
                <p>Nomor &nbsp;&nbsp;&nbsp; : {{ $letter_number[$booking->asset->id] }}</p>
                <div class="col-12">
                    <h5 class="mb-3 text-center fw-bold"> DAFTAR NAMA PESERTA DAN PANITIA</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="align-middle text-center">No.</th>
                                <th class="align-middle text-center">Nama</th>
                                <th class="align-middle text-center">Program Studi</th>
                                <th class="align-middle text-center">Jabatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $assetBookingsPerAsset = $assetBookings->where('asset_id', $booking->asset->id);
                                $i = 0;
                            @endphp

                            @foreach ($team_members as $member)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->prodi->nama_prodi ?? '-' }}</td>
                                    <td>{{ $member->position }}</td>
                                </tr>
                                @php $i++; @endphp
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if ($index < count($assetBookings->unique('asset_id')) - 1)
            <div style="page-break-before: always;"></div>
        @endif
        <div class="mt-5 footer">
            <hr>
            <p class="fw-medium foot-number mt-0">FRM.BAA.03.18.00</p>
        </div>
    @endforeach



    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            window.print();
        });
    </script>
</body>

</html>
