<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $events = [
            [
                'title' => 'Introduction to Digital Marketing',
                'category' => 'internal_kampus',
                'image' => 'assets/images/thumbs/course-img1.png',
                'location' => 'Graha Polinema, Aula Pertamina, Mini Soccer',
                'quota_left' => 0,
                'quota' => 7,
                'organizer' => 'BEM',
                'organizer_logo' => 'assets/images/logo_organizers/bem.png',
                'registration_period' => '5 Oktober 2025 - 15 Oktober 2025',
                'event_period' => '20 Oktober 2025 - 15 November 2025',
                'price' => '200.000',
                'mode' => 'Offline',
            ],
            [
                'title' => 'Introduction to Digital Marketing',
                'category' => 'internal_jurusan',
                'image' => 'assets/images/thumbs/course-img1.png',
                'location' => 'Zoom Meeting',
                'quota_left' => 240,
                'quota' => 500,
                'organizer' => 'HMTI',
                'organizer_logo' => 'assets/images/logo_organizers/hmti.png',
                'registration_period' => '5 Oktober 2025 - 15 Oktober 2025',
                'event_period' => '20 Oktober 2025 - 15 November 2025',
                'price' => 'Gratis',
                'mode' => 'Online',
            ],
            [
                'title' => 'Introduction to Digital Marketing',
                'category' => 'umum',
                'image' => 'assets/images/thumbs/course-img1.png',
                'location' => 'Graha Polinema & Zoom Meeting',
                'quota_left' => 40,
                'quota' => 200,
                'organizer' => 'JTI',
                'organizer_logo' => 'assets/images/logo_organizers/jti.png',
                'registration_period' => '5 Oktober 2025 - 15 Oktober 2025',
                'event_period' => '20 Oktober 2025 - 15 November 2025',
                'price' => '50.000',
                'mode' => 'Hybrid',
            ],

        ];
        return view('homepage.home', compact('events'));
    }

    public function event()
    {
        $events = [
            [
                'title' => 'Seminar Nasional Himpunan Mahasiswa Teknologi Informasi Politeknik Negeri Malang Tahun 2025',
                'category' => 'internal_kampus',
                'image' => 'assets/images/thumbs/course-img1.png',
                'location' => 'Graha Polinema, Aula Pertamina, Mini Soccer',
                'quota_left' => 0,
                'quota' => 7,
                'organizer' => 'BEM',
                'organizer_logo' => 'assets/images/logo_organizers/bem.png',
                'registration_period' => '5 Oktober 2025 - 15 Oktober 2025',
                'event_period' => '20 Oktober 2025 - 15 November 2025',
                'price' => '200.000',
                'mode' => 'Offline',
            ],
            [
                'title' => 'Seminar Nasional Himpunan Mahasiswa Teknologi Informasi Politeknik Negeri Malang Tahun 2025',
                'category' => 'internal_jurusan',
                'image' => 'assets/images/thumbs/course-img1.png',
                'location' => 'Zoom Meeting',
                'quota_left' => 240,
                'quota' => 500,
                'organizer' => 'HMTI',
                'organizer_logo' => 'assets/images/logo_organizers/hmti.png',
                'registration_period' => '5 Oktober 2025 - 15 Oktober 2025',
                'event_period' => '20 Oktober 2025 - 15 November 2025',
                'price' => 'Gratis',
                'mode' => 'Online',
            ],
            [
                'title' => 'Seminar Nasional Himpunan Mahasiswa Teknologi Informasi Politeknik Negeri Malang Tahun 2025',
                'category' => 'umum',
                'image' => 'assets/images/thumbs/course-img1.png',
                'location' => 'Graha Polinema & Zoom Meeting',
                'quota_left' => 40,
                'quota' => 200,
                'organizer' => 'JTI',
                'organizer_logo' => 'assets/images/logo_organizers/jti.png',
                'registration_period' => '5 Oktober 2025 - 15 Oktober 2025',
                'event_period' => '20 Oktober 2025 - 15 November 2025',
                'price' => '50.000',
                'mode' => 'Hybrid',
            ],

        ];
        return view('homepage.event', compact('events'));
    }
    public function organizer()
    {
        $organizers_jurusan = [
            [
                'name' => 'Jurusan Teknologi Informasi',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/jti.png',
            ],
            [
                'name' => 'Jurusan Teknik Mesin',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/j_tm.png',
            ],
            [
                'name' => 'Jurusan Teknik Elektro',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/j_elektro.png',
            ],
            [
                'name' => 'Jurusan Teknik Sipil',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/j_ts.jpg',
            ],
            [
                'name' => 'Jurusan Akuntansi',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/j_akuntansi.png',
            ],
            [
                'name' => 'Jurusan Administrasi Niaga',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/j_an.png',
            ],
            [
                'name' => 'Jurusan Teknik Kimia',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/j_tk.png',
            ],
        ];
        $organizers_lt = [
            [
                'name' => 'Dewan Perwakilan Mahasiswa',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/dpm.png',
            ],
            [
                'name' => 'Badan Eksekutif Mahasiswa',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/bem.png',
            ],
        ];
        $organizers_hmj = [
            [
                'name' => 'Himpunan Mahasiswa Teknologi Informasi',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hmti.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Teknik Mesin',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/bem.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Elektro',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/bem.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Teknik Sipil',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hms.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Akuntansi',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hma.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Administrasi Niaga',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/himania.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Teknik Kimia',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hmtk.png',
            ],
        ];
        $organizers_ukm = [
            [
                'name' => 'Himpunan Mahasiswa Teknologi Informasi',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hmti.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Teknik Mesin',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/bem.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Elektro',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/bem.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Teknik Sipil',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hms.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Akuntansi',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hma.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Administrasi Niaga',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/himania.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Teknik Kimia',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hmtk.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Teknik Kimia',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hmtk.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Teknik Kimia',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hmtk.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Teknik Kimia',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hmtk.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Teknik Kimia',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hmtk.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Teknik Kimia',
                'description' => '
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse in expedita unde assumenda accusamus, sequi quia quasi recusandae quibusdam',
                'image' => 'assets/images/logo_organizers/hmtk.png',
            ],
        ];

        return view('homepage.organizer', compact('organizers_jurusan', 'organizers_lt', 'organizers_hmj', 'organizers_ukm'));
    }
    public function detail_organizer()
    {
        $events = [
            [
                'title' => 'Seminar Nasional Himpunan Mahasiswa Teknologi Informasi Politeknik Negeri Malang Tahun 2025',
                'category' => 'internal_kampus',
                'image' => 'assets/images/thumbs/course-img1.png',
                'location' => 'Graha Polinema, Aula Pertamina, Mini Soccer',
                'quota_left' => 0,
                'quota' => 7,
                'organizer' => 'BEM',
                'organizer_logo' => 'assets/images/logo_organizers/bem.png',
                'registration_period' => '5 Oktober 2025 - 15 Oktober 2025',
                'event_period' => '20 Oktober 2025 - 15 November 2025',
                'price' => '200.000',
                'mode' => 'Offline',
            ],
            [
                'title' => 'Introduction to Digital Marketing',
                'category' => 'internal_jurusan',
                'image' => 'assets/images/thumbs/course-img1.png',
                'location' => 'Zoom Meeting',
                'quota_left' => 240,
                'quota' => 500,
                'organizer' => 'HMTI',
                'organizer_logo' => 'assets/images/logo_organizers/hmti.png',
                'registration_period' => '5 Oktober 2025 - 15 Oktober 2025',
                'event_period' => '20 Oktober 2025 - 15 November 2025',
                'price' => 'Gratis',
                'mode' => 'Online',
            ],
            [
                'title' => 'Introduction to Digital Marketing',
                'category' => 'umum',
                'image' => 'assets/images/thumbs/course-img1.png',
                'location' => 'Graha Polinema & Zoom Meeting',
                'quota_left' => 40,
                'quota' => 200,
                'organizer' => 'JTI',
                'organizer_logo' => 'assets/images/logo_organizers/jti.png',
                'registration_period' => '5 Oktober 2025 - 15 Oktober 2025',
                'event_period' => '20 Oktober 2025 - 15 November 2025',
                'price' => '50.000',
                'mode' => 'Hybrid',
            ],

        ];
        return view('homepage.detail_organizer', compact('events'));
    }
    public function detail_event()
    {
        $events = [
            [
                'title' => 'Seminar Nasional Himpunan Mahasiswa Teknologi Informasi Politeknik Negeri Malang Tahun 2025',
                'category' => 'internal_kampus',
                'image' => 'assets/images/thumbs/course-img1.png',
                'location' => 'Graha Polinema, Aula Pertamina, Mini Soccer',
                'quota_left' => 0,
                'quota' => 7,
                'organizer' => 'BEM',
                'organizer_logo' => 'assets/images/logo_organizers/bem.png',
                'registration_period' => '5 Oktober 2025 - 15 Oktober 2025',
                'event_period' => '20 Oktober 2025 - 15 November 2025',
                'price' => '200.000',
                'mode' => 'Offline',
            ],
            [
                'title' => 'Introduction to Digital Marketing',
                'category' => 'internal_jurusan',
                'image' => 'assets/images/thumbs/course-img1.png',
                'location' => 'Zoom Meeting',
                'quota_left' => 240,
                'quota' => 500,
                'organizer' => 'HMTI',
                'organizer_logo' => 'assets/images/logo_organizers/hmti.png',
                'registration_period' => '5 Oktober 2025 - 15 Oktober 2025',
                'event_period' => '20 Oktober 2025 - 15 November 2025',
                'price' => 'Gratis',
                'mode' => 'Online',
            ],
            [
                'title' => 'Introduction to Digital Marketing',
                'category' => 'umum',
                'image' => 'assets/images/thumbs/course-img1.png',
                'location' => 'Graha Polinema & Zoom Meeting',
                'quota_left' => 40,
                'quota' => 200,
                'organizer' => 'JTI',
                'organizer_logo' => 'assets/images/logo_organizers/jti.png',
                'registration_period' => '5 Oktober 2025 - 15 Oktober 2025',
                'event_period' => '20 Oktober 2025 - 15 November 2025',
                'price' => '50.000',
                'mode' => 'Hybrid',
            ],

        ];
        return view('homepage.detail_event', compact('events'));
    }
    public function calender()
    {
        return view('homepage.calender');
    }

    public function tutorial()
    {
        return view('homepage.tutorial');
    }

    public function chatMessage()
    {
        return view('chatMessage');
    }

    public function chatempty()
    {
        return view('chatempty');
    }

    public function veiwDetails()
    {
        return view('veiwDetails');
    }

    public function email()
    {
        return view('email');
    }

    public function error1()
    {
        return view('error');
    }

    public function faq()
    {
        return view('faq');
    }

    public function gallery()
    {
        return view('gallery');
    }

    public function kanban()
    {
        return view('kanban');
    }

    public function pricing()
    {
        return view('pricing');
    }

    public function termsCondition()
    {
        return view('termsCondition');
    }

    public function widgets()
    {
        return view('widgets');
    }

    public function chatProfile()
    {
        return view('chatProfile');
    }

    public function blankPage()
    {
        return view('blankPage');
    }

    public function comingSoon()
    {
        return view('comingSoon');
    }

    public function starred()
    {
        return view('starred');
    }

    public function testimonials()
    {
        return view('testimonials');
    }

    public function maintenance()
    {
        return view('maintenance');
    }
}