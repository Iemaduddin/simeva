<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berkas Booking Aset</title>
    <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">

    <style>
        body {
            font-family: 'Arial', Times, serif;
            font-size: 12pt;
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
            color: #000;
        }

        .kop {
            text-align: center;
        }

        .kop .title {
            font-size: 12pt;
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
            font-size: 15px;
        }

        .signature td {
            /* padding-top: 40px; */
            vertical-align: top;
            font-size: 15px;
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

        .thick-thin-hr {
            height: 6px;
            background: linear-gradient(to bottom, black 2px, transparent 2px, transparent 4px, black 4px);
        }

        .justify-list li {
            text-align: justify;
            font-size: 15px;
        }

        .p {
            text-align: justify;
            font-size: 15px;
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
                margin: 1.5cm 1.5cm 1.5cm 1.5cm;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    @php
        use Carbon\Carbon;

        function formatRangeDate($start, $end)
        {
            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);

            if ($startDate->isSameDay($endDate)) {
                return $startDate->translatedFormat('l, d F Y') .
                    ' pukul ' .
                    $startDate->format('H:i') .
                    ' s/d ' .
                    $endDate->format('H:i');
            }

            return $startDate->translatedFormat('l, d F Y H:i') . ' s/d ' . $endDate->translatedFormat('l, d F Y H:i');
        }

        function formatRangeTime($start, $end)
        {
            return Carbon::parse($start)->format('H:i') . ' s/d ' . Carbon::parse($end)->format('H:i');
        }

    @endphp
    <div id="bookingLetterPage1">

        <div class="row align-items-center justify-content-between">
            <div class="col-1 d-flex justify-content-start">
                <img src="{{ asset('assets/images/logo_polinema.png') }}" alt="Logo Polinema" width="100px">
            </div>
            <div class="col-10 kop">
                <div class="title">KEMENTERIAN PENDIDIKAN, RISET, DAN TEKNOLOGI</div>
                <div class="subtitle">POLITEKNIK NEGERI MALANG</div>
                <p>Jalan Soekarno-Hatta No.9 PO Box 04 Malang 65141</p>
                <p>Telp. (0341) 404424 Fax. (0341) 404423 <br> http://www.polinema.ac.id</p>
            </div>
            <div class="col-1 d-flex justify-content-end">
                <img src="{{ asset('assets/images/kan_polinema.jpg') }}" alt="Logo Polinema" width="100px">
            </div>
        </div>
        <hr class="thick-thin-hr">
        <h6 class="text-center mb-4">SURAT PERNYATAAN</h6>
        <div class="row mb-0" style="font-size: 15px">
            <p class="mb-0">Saya yang bertanda tangan di bawah ini :</p>
            <div class="col-12 ps-5">
                <table>
                    <tr>
                        <td>NAMA</td>
                        <td>:</td>
                        <td>{{ $assetBooking->user->name }}</td>
                    </tr>
                    <tr>
                        <td>ALAMAT</td>
                        <td>:</td>
                        <td>{{ $assetBooking->user->address }}</td>
                    </tr>
                    <tr>
                        <td>NO. HP</td>
                        <td>:</td>
                        <td>{{ $assetBooking->user->phone_number }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="content">
            <p class="mb-0">Menyatakan dengan sesungguhnya bahwa:</p>
            <ol class="justify-list">
                <li>Akan menggunakan fasilitas sarana prasarana aset Barang Milik Negara (BMN)
                    Politeknik Negeri Malang yang saya pergunakan dengan sebaik-baiknya dan penuh
                    tanggung jawab
                </li>
                <li>
                    Mematuhi waktu penggunaan aset BMN Politeknik Negeri Malang yang ditetapkan oleh
                    manajemen, sesuai pada form isian peminjaman/booking aset BMN.
                </li>
                <li>
                    Tidak memindah-mindahkan fasilitas yang ada (kursi, meja, dll) tanpa ijin dari Petugas /
                    Crew Pelaksana Lapangan Politeknik Negeri Malang atau UPT. Pengelola Usaha
                    Politeknik Negeri Malang.

                </li>
                <li>
                    Mematuhi segala ketentuan yang berlaku dan bersedia bertanggung jawab pada setiap
                    kerusakan ataupun kehilangan aset BMN Politeknik Negeri Malang yang ditimbulkan
                    dari kegiatan/acara tersebut.

                </li>
                <li>
                    Apabila terjadi kerusakan dan ataupun kehilangan fasilitas milik Politeknik Negeri
                    Malang, kami bersedia dan bertanggung
                    jawab untuk mengganti biaya
                    kerusakan/kehilangan sesuai ketentuan dari Politeknik Negeri Malang.
                </li>
                <li>
                    Pihak Manajemen Politeknik Negeri Malang BERHAK menghentikan kegiatan/acara jika ;
                    <ul>
                        <li>
                            Penggunaan aset BMN melanggar batas waktu sesuai yang tertera dalam form
                            peminjaman/booking aset BMN.

                        </li>
                        <li>
                            Acara yang diselenggarakan TIDAK sesuai dengan yang tertera dalam form
                            peminjaman/booking aset BMN.
                        </li>
                        <li>
                            Belum menyelesaikan pelunasan biaya sewa.
                        </li>
                        <li>
                            Melanggar ketentuan yang telah ditetapkan manajemen.
                        </li>
                    </ul>
                </li>
                <li>
                    Pengecualian terhadap semua ketentuan di atas akan diputuskan tersendiri secara
                    kekeluargaan jika diperlukan.
                </li>
            </ol>
            <p>Demikian surat pernyataan ini dibuat untuk dapat patuhi oleh Pengguna/Penyewa dalam
                melaksanakan kegiatan.
            </p>
        </div>
        <div style="width: 200px; margin-left: auto; text-align: left;font-size:15px">
            <p class="mb-0">Malang, {{ Carbon::today()->translatedFormat('d F Y') }}</p>
            <p class="mb-0">Pengguna/Penyewa</p>
            <p style="padding-top: 20px; padding-bottom: 20px; font-size: 10px;"><i>materai 10.000</i></p>
            <p>( {{ $assetBooking->user->name }} )</p>
        </div>

    </div>
    <div style="page-break-before: always"></div>


    <div id="bookingLetterPage2">
        <div class="row align-items-center justify-content-between">
            <div class="col-1 d-flex justify-content-start">
                <img src="{{ asset('assets/images/logo_polinema.png') }}" alt="Logo Polinema" width="100px">
            </div>
            <div class="col-10 kop">
                <div class="title">KEMENTERIAN PENDIDIKAN, RISET, DAN TEKNOLOGI</div>
                <div class="subtitle">POLITEKNIK NEGERI MALANG</div>
                <p>Jalan Soekarno-Hatta No.9 PO Box 04 Malang 65141</p>
                <p>Telp. (0341) 404424 Fax. (0341) 404423 <br> http://www.polinema.ac.id</p>
            </div>
            <div class="col-1 d-flex justify-content-end">
                <img src="{{ asset('assets/images/kan_polinema.jpg') }}" alt="Logo Polinema" width="100px">
            </div>
        </div>
        <hr class="thick-thin-hr">
        <h6 class="text-center mb-4">
            FORMULIR PEMINJAMAN <br>
            {{ $assetBooking->asset->name }} <br>
        </h6>
        <div class="row mb-4" style="font-size: 15px">
            <p class="mb-0">Saya yang bertanda tangan di bawah ini :</p>
            <div class="col-12 ps-5">
                <table>
                    <tr>
                        <td>Nama Lengkap</td>
                        <td>:</td>
                        <td>{{ $assetBooking->user->name }}</td>
                    </tr>
                    <tr>
                        <td>Alamat Lengkap</td>
                        <td>:</td>
                        <td>{{ $assetBooking->user->address }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td>{{ $assetBooking->user->email }}</td>
                    </tr>
                    <tr>
                        <td>Nomor Telepon/HP</td>
                        <td>:</td>
                        <td>{{ $assetBooking->user->phone_number }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row mb-4" style="font-size: 15px">
            <p class="mb-0">Bermaksud meminjam/menyewa aset BMN Politeknik Negeri Malang pada :</p>
            <div class="col-12 ps-5">
                <table>
                    <tr>
                        <td>Hari, Tanggal</td>
                        <td>:</td>
                        <td>{{ Carbon::parse($assetBooking->usage_date_start)->translatedFormat('l, d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Acara</td>
                        <td>:</td>
                        <td>{{ $assetBooking->usage_event_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Jam Acara/Kegiatan</td>
                        <td>:</td>
                        <td>{{ formatRangeTime($assetBooking->usage_date_start, $assetBooking->usage_date_end) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Hari, Tanggal Loading</td>
                        <td>:</td>
                        <td>{{ formatRangeDate($assetBooking->loading_date_start, $assetBooking->loading_date_end) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Hari, Tanggal Bongkar</td>
                        <td>:</td>
                        <td>{{ formatRangeDate($assetBooking->unloading_date_start, $assetBooking->unloading_date_end) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="content mb-2">
            <p class="fw-bold">Syarat dan ketentuan :</p>
            <ol class="justify-list">
                <li>
                    Mengisi Form surat pernyataan yang disediakan.
                </li>
                <li>
                    Mentaati segala peraturan yang ditetapkan oleh manajemen.
                </li>
                <li>
                    Melakukan pembayaran sewa secara Transfer pada Bank yang ditunjuk.
                </li>
                <li>
                    Uang Muka wajib dibayarkan minimal 30% dari total biaya sewa paling lambat 3 hari
                    setelah booking.
                </li>
                <li>
                    Pelunasan sebesar 70% dibayarkan paling lambat H-7 sebelum acara pelaksanaan.
                </li>
                <li>
                    Jika terjadi pembatalan oleh Pihak Penyewa, maka uang yang telah disetorkan tidak
                    dapat ditarik kembali dengan alasan apapun, namun jika terjadi pembatalan oleh Pihak
                    Penyedia Jasa (Politeknik Negeri Malang) maka uang yang sudah disetorkan akan
                    dikembalikan 100%.
                </li>
                <li>
                    Melampirkan foto copy identitas diri (KTP/SIM) sebanyak 1 (satu) lembar.
                </li>
            </ol>
            <p>Demikian surat pernyataan ini dibuat untuk dapat patuhi oleh Pengguna/Penyewa dalam
                melaksanakan kegiatan.</p>
        </div>
        <div style="width: 200px; margin-left: auto; text-align: left;font-size:15px">
            <p class="mb-0">Malang, {{ Carbon::today()->translatedFormat('d F Y') }}</p>
            <p class="mb-0">Pengguna/Penyewa</p>
            <p style="padding-top: 20px; padding-bottom: 20px; font-size: 10px;"><i>materai 10.000</i></p>
            <p>( {{ $assetBooking->user->name }} )</p>
        </div>
    </div>
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            window.print();
        });
    </script>
</body>

</html>
