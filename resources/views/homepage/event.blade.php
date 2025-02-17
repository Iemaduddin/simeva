@extends('layout.landingPageLayout')

@section('title', 'Event')
@section('content')
    <!-- ==================== Breadcrumb Start Here ==================== -->
    <section class="breadcrumb py-120 bg-main-25 position-relative z-1 overflow-hidden mb-0">
        <img src="{{ asset('assets/images/shapes/shape1.png') }}" alt=""
            class="shape one animation-rotation d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape2.png') }}" alt=""
            class="shape two animation-scalation d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape3.png') }}" alt=""
            class="shape eight animation-walking d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape5.png') }}" alt=""
            class="shape six animation-walking d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape4.png') }}" alt="" class="shape four animation-scalation">
        <img src="{{ asset('assets/images/shapes/shape4.png') }}" alt="" class="shape nine animation-scalation">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="breadcrumb__wrapper">
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Daftar Event</h1>
                        <ul class="breadcrumb__list d-flex align-items-center justify-content-center gap-4">
                            <li class="breadcrumb__item">
                                <a href="{{ route('home') }}"
                                    class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium">
                                    <i class="text-lg d-inline-flex ph-bold ph-house"></i> Home</a>
                            </li>
                            <li class="breadcrumb__item">
                                <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                            </li>
                            <li class="breadcrumb__item">
                                <a href="{{ route('event') }}"
                                    class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium"> Event</a>
                            </li>
                            <li class="breadcrumb__item ">
                                <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                            </li>
                            <li class="breadcrumb__item">
                                <span class="text-main-two-600"> Daftar Event </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==================== Breadcrumb End Here ==================== -->
    <!-- ============================== Course List View Section Start ============================== -->
    <section class="course-list-view py-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="sidebar rounded-12 bg-main-25 p-32 border border-neutral-30">
                        <form action="#">
                            <div class="flex-between mb-24">
                                <h4 class="mb-0">Filter</h4>
                                <button type="button"
                                    class="sidebar-close text-xl text-neutral-500 d-lg-none hover-text-main-600">
                                    <i class="ph-bold ph-x"></i>
                                </button>
                            </div>

                            <div class="position-relative">
                                <input type="text" class="common-input pe-48 rounded-pill" placeholder="Search...">
                                <button type="submit"
                                    class="text-neutral-500 text-xl d-flex position-absolute top-50 translate-middle-y inset-inline-end-0 me-24 hover-text-main-600"><i
                                        class="ph-bold ph-magnifying-glass"></i></button>
                            </div>
                            <span class="d-block border border-neutral-30 border-dashed my-24"></span>

                            <h6 class="text-lg mb-24 fw-medium">Lingkup Event</h6>
                            <div class="d-flex flex-column gap-16">
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories" id="3254">
                                        <label class="form-check-label fw-normal flex-grow-1" for="3254">Semua
                                            Lingkup</label>
                                    </div>
                                    <span class="text-neutral-500">60</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories" id="1522">
                                        <label class="form-check-label fw-normal flex-grow-1" for="1522">Umum</label>
                                    </div>
                                    <span class="text-neutral-500">25</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories" id="2545">
                                        <label class="form-check-label fw-normal flex-grow-1" for="2545">Internal Kampus
                                            Polinema</label>
                                    </div>
                                    <span class="text-neutral-500">15</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories" id="3215">
                                        <label class="form-check-label fw-normal flex-grow-1" for="3215">Internal
                                            Jurusan Teknologi Informasi</label>
                                    </div>
                                    <span class="text-neutral-500">5</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories" id="5526">
                                        <label class="form-check-label fw-normal flex-grow-1" for="5526">Internal
                                            Jurusan Teknik Mesin</label>
                                    </div>
                                    <span class="text-neutral-500">3</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="1563">
                                        <label class="form-check-label fw-normal flex-grow-1" for="1563">Internal
                                            Jurusan Teknik Sipil</label>
                                    </div>
                                    <span class="text-neutral-500">5</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="4154">
                                        <label class="form-check-label fw-normal flex-grow-1" for="4154">Internal
                                            Jurusan Teknik Kimia</label>
                                    </div>
                                    <span class="text-neutral-500">0</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="categories1">
                                        <label class="form-check-label fw-normal flex-grow-1" for="categories1">Internal
                                            Jurusan Teknik Elektro</label>
                                    </div>
                                    <span class="text-neutral-500">5</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="4146">
                                        <label class="form-check-label fw-normal flex-grow-1" for="4146"> Internal
                                            Jurusan Administarasi Niaga</label>
                                    </div>
                                    <span class="text-neutral-500">2</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="4955">
                                        <label class="form-check-label fw-normal flex-grow-1" for="4955">Internal
                                            Jurusan Akuntansi</label>
                                    </div>
                                    <span class="text-neutral-500">0</span>
                                </div>
                            </div>

                            <span class="d-block border border-neutral-30 border-dashed my-24"></span>
                            <h6 class="text-lg mb-24 fw-medium">Penyelenggara Event</h6>
                            <div class="d-flex flex-column gap-16">
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="3254">
                                        <label class="form-check-label fw-normal flex-grow-1" for="3254">Semua
                                            Penyelenggara</label>
                                    </div>
                                    <span class="text-neutral-500">60</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="1522">
                                        <label class="form-check-label fw-normal flex-grow-1" for="1522">Jurusan
                                            Teknologi Informasi</label>
                                    </div>
                                    <span class="text-neutral-500">25</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="2545">
                                        <label class="form-check-label fw-normal flex-grow-1" for="2545">Jurusan
                                            Teknik Mesin</label>
                                    </div>
                                    <span class="text-neutral-500">15</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="3215">
                                        <label class="form-check-label fw-normal flex-grow-1" for="3215">Jurusan
                                            Teknik Elektro</label>
                                    </div>
                                    <span class="text-neutral-500">5</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="5526">
                                        <label class="form-check-label fw-normal flex-grow-1" for="5526">Jurusan
                                            Teknik Sipil</label>
                                    </div>
                                    <span class="text-neutral-500">3</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="1563">
                                        <label class="form-check-label fw-normal flex-grow-1" for="1563">Jurusan
                                            Akuntansi</label>
                                    </div>
                                    <span class="text-neutral-500">5</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="4154">
                                        <label class="form-check-label fw-normal flex-grow-1" for="4154">Jurusan
                                            Administrasi Niaga</label>
                                    </div>
                                    <span class="text-neutral-500">0</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="categories1">
                                        <label class="form-check-label fw-normal flex-grow-1" for="categories1">Jurusan
                                            Teknik Kimia</label>
                                    </div>
                                    <span class="text-neutral-500">5</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="4146">
                                        <label class="form-check-label fw-normal flex-grow-1" for="4146"> Dewan
                                            Perwakilan Mahasiswa</label>
                                    </div>
                                    <span class="text-neutral-500">2</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="4955">
                                        <label class="form-check-label fw-normal flex-grow-1" for="4955">Badan
                                            Eksekutif Mahasiswa</label>
                                    </div>
                                    <span class="text-neutral-500">0</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="categories"
                                            id="4955">
                                        <label class="form-check-label fw-normal flex-grow-1" for="4955">Himpunan
                                            Mahasiswa Teknologi Informasi</label>
                                    </div>
                                    <span class="text-neutral-500">0</span>
                                </div>
                                {{-- More --}}
                                <div class="d-none" id="organizer_more">
                                    <div class="flex-between gap-16 mb-10">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="4955">
                                            <label class="form-check-label fw-normal flex-grow-1" for="4955">Himpunan
                                                Mahasiswa Mesin</label>
                                        </div>
                                        <span class="text-neutral-500">0</span>
                                    </div>
                                    <div class="flex-between gap-16 mb-10">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="4955">
                                            <label class="form-check-label fw-normal flex-grow-1" for="4955">Himpunan
                                                Mahasiswa Elektro</label>
                                        </div>
                                        <span class="text-neutral-500">0</span>
                                    </div>
                                    <div class="flex-between gap-16 mb-10">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="4955">
                                            <label class="form-check-label fw-normal flex-grow-1" for="4955">Himpunan
                                                Mahasiswa Teknik Sipil</label>
                                        </div>
                                        <span class="text-neutral-500">0</span>
                                    </div>
                                    <div class="flex-between gap-16 mb-10">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="4955">
                                            <label class="form-check-label fw-normal flex-grow-1" for="4955">Himpunan
                                                Mahasiswa Akuntansi</label>
                                        </div>
                                        <span class="text-neutral-500">0</span>
                                    </div>
                                    <div class="flex-between gap-16 mb-10">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="4955">
                                            <label class="form-check-label fw-normal flex-grow-1" for="4955">Himpunan
                                                Mahasiswa Administrasi Niaga</label>
                                        </div>
                                        <span class="text-neutral-500">0</span>
                                    </div>
                                    <div class="flex-between gap-16 mb-10">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="4955">
                                            <label class="form-check-label fw-normal flex-grow-1" for="4955">Himpunan
                                                Mahasiswa Teknik Kimia</label>
                                        </div>
                                        <span class="text-neutral-500">0</span>
                                    </div>
                                    <div class="flex-between gap-16 mb-10">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="4955">
                                            <label class="form-check-label fw-normal flex-grow-1" for="4955">UKM
                                                Talitakum</label>
                                        </div>
                                        <span class="text-neutral-500">0</span>
                                    </div>
                                    <div class="flex-between gap-16 mb-10">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="4955">
                                            <label class="form-check-label fw-normal flex-grow-1" for="4955">UKM
                                                Olahraga</label>
                                        </div>
                                        <span class="text-neutral-500">0</span>
                                    </div>
                                    <div class="flex-between gap-16 mb-10">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="4955">
                                            <label class="form-check-label fw-normal flex-grow-1" for="4955">UKM
                                                KMK</label>
                                        </div>
                                        <span class="text-neutral-500">0</span>
                                    </div>
                                    <div class="flex-between gap-16 mb-10">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="4955">
                                            <label class="form-check-label fw-normal flex-grow-1" for="4955">Himpunan
                                                UKM PLFM</label>
                                        </div>
                                        <span class="text-neutral-500">0</span>
                                    </div>
                                </div>
                                <a href="#"
                                    class="text-sm text-main-600 fw-semibold text-md hover-text-decoration-underline"
                                    id="see_more_organizer">Lihat
                                    Selengkapnya</a>
                                <span class="d-block border border-neutral-30 border-dashed"></span>
                                <h6 class="text-lg fw-medium">Kategori Event</h6>
                                <div class="d-flex flex-column gap-16">
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="3254">
                                            <label class="form-check-label fw-normal flex-grow-1" for="3254">Semua
                                                Kategori</label>
                                        </div>
                                        <span class="text-neutral-500">60</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="1522">
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="1522">Seminar</label>
                                        </div>
                                        <span class="text-neutral-500">20</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="2545">
                                            <label class="form-check-label fw-normal flex-grow-1" for="2545">Internal
                                                Kuliah Tamu</label>
                                        </div>
                                        <span class="text-neutral-500">10</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="3215">
                                            <label class="form-check-label fw-normal flex-grow-1" for="3215">Internal
                                                Pelatihan</label>
                                        </div>
                                        <span class="text-neutral-500">8</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="5526">
                                            <label class="form-check-label fw-normal flex-grow-1" for="5526">Internal
                                                Workshop</label>
                                        </div>
                                        <span class="text-neutral-500">11</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="1563">
                                            <label class="form-check-label fw-normal flex-grow-1" for="1563">Internal
                                                Kompetisi</label>
                                        </div>
                                        <span class="text-neutral-500">8</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="4955">
                                            <label class="form-check-label fw-normal flex-grow-1" for="4955">Internal
                                                Lainnya</label>
                                        </div>
                                        <span class="text-neutral-500">3</span>
                                    </div>
                                </div>
                                <span class="d-block border border-neutral-30 border-dashed"></span>
                                <h6 class="text-lg fw-medium">Biaya Event</h6>
                                <div class="d-flex flex-column gap-16">
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="3254">
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="3254">Semua</label>
                                        </div>
                                        <span class="text-neutral-500">60</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="1522">
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="1522">Gratis</label>
                                        </div>
                                        <span class="text-neutral-500">35</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="categories"
                                                id="2545">
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="2545">Berbayar</label>
                                        </div>
                                        <span class="text-neutral-500">25</span>
                                    </div>
                                </div>
                                <span class="d-block border border-neutral-30 border-dashed"></span>

                                <button type="reset"
                                    class="btn btn-outline-main rounded-pill flex-center gap-16 fw-semibold w-100">
                                    <i class="ph-bold ph-arrow-clockwise d-flex text-lg"></i>
                                    Reset Filters
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8 ps-40">
                    <div class="course-list-wrapper">
                        <div class="flex-between gap-16 flex-wrap mb-40">
                            <span class="text-neutral-500">Showing 9 of 600 Results </span>
                            <div class="flex-align gap-16">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 flex-shrink-0">Sort By :</span>
                                    <select
                                        class="form-select ps-20 pe-28 py-8 fw-medium rounded-pill bg-main-25 border border-neutral-30 text-neutral-700">
                                        <option value="1">Newest</option>
                                        <option value="1">Trending</option>
                                        <option value="1">Popular</option>
                                    </select>
                                </div>
                                <button type="button"
                                    class="list-bar-btn text-xl w-40 h-40 bg-main-600 text-white rounded-8 flex-center d-lg-none">
                                    <i class="ph-bold ph-funnel"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row gy-4">
                            @foreach ($events as $event)
                                <div class="col-12">
                                    <div
                                        class="row course-item bg-main-25 rounded-16 p-20 border border-neutral-30 list-view">
                                        <div class="col-lg-5 ps-5 rounded-12 overflow-hidden position-relative h-100">
                                            <a href="{{ route('detail_event') }}">
                                                <img src="assets/images/thumbs/course-img1.png" alt="Course Image"
                                                    class="rounded-12 cover-img transition-2"
                                                    style="width:350px; height: 400px">
                                            </a>
                                            <div
                                                class="flex-align gap-8 {{ $event['category'] === 'internal_kampus' ? 'bg-main-600' : ($event['category'] === 'internal_jurusan' ? 'bg-warning' : 'bg-dark') }} rounded-pill px-24 py-12 text-white position-absolute inset-block-start-0 inset-inline-start-0 mt-20 ms-20 z-1">
                                                <span class="text-lg fw-medium">{{ $event['category'] }}</span>
                                            </div>
                                            <button type="button"
                                                class="wishlist-btn w-48 h-48 bg-white text-main-two-600 flex-center position-absolute inset-block-start-0 inset-inline-end-0 mt-20 me-20 z-1 text-2xl rounded-circle transition-2">
                                                <i class="ph ph-bookmark-simple"></i>
                                            </button>
                                        </div>
                                        <div class="col-lg-7 ps-20 ">
                                            <div class="">
                                                <h4 class="mb-15">
                                                    <a href="{{ route('detail_event') }}"
                                                        class="link text-line-2">{{ $event['title'] }}</a>
                                                </h4>
                                                <div class="flex-align gap-8">
                                                    <span class="text-neutral-700 text-2xl d-flex"><i
                                                            class="ph-bold ph-note"></i></span>
                                                    <span class="text-neutral-700 text-lg fw-medium">Pendaftaran</span>
                                                </div>
                                                <span
                                                    class="ms-30 my-5 btn btn-outline-main rounded-pill px-10 py-10 text-white text-sm fw-medium">{{ $event['registration_period'] }}</span>
                                                <div class="flex-align gap-8">
                                                    <span class="text-neutral-700 text-2xl d-flex"><i
                                                            class="ph-bold ph-timer"></i></span>
                                                    <span class="text-neutral-700 text-lg fw-medium">Pelaksanaan</span>
                                                </div>
                                                <span
                                                    class="ms-30 my-5 btn btn-main rounded-pill px-10 py-10 text-white text-sm fw-medium">{{ $event['event_period'] }}</span>

                                                <div class="flex-between gap-8 flex-wrap my-5">
                                                    <div class="flex-align gap-4">
                                                        <span class="text-neutral-700 text-2xl d-flex"><i
                                                                class="ph-bold ph-map-pin-area"></i></span>
                                                        <span
                                                            class="text-neutral-700 text-md fw-medium text-line-1">{{ $event['location'] }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-between gap-8 flex-wrap my-10">
                                                    <div class="flex-align gap-4">
                                                        <span class="text-neutral-700 text-2xl d-flex"><i
                                                                class="ph-bold ph-user-circle"></i></span>
                                                        <span class="text-neutral-700 text-md fw-medium text-line-1">Kuota:
                                                        </span><span>{{ $event['quota_left'] }}/{{ $event['quota'] }}</span>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center">
                                                    <div class="col-8  d-flex align-items-center">
                                                        <span class="text-neutral-700 text-2xl d-flex">
                                                            <img src="{{ asset($event['organizer_logo']) }}"
                                                                alt="Logo Organizers"
                                                                class="w-32 h-32 object-fit-cover rounded-circle">
                                                        </span>
                                                        <p class="text-neutral-700 text-md fw-medium text-line-1 ms-2">
                                                            {{ $event['organizer'] }}
                                                        </p>
                                                    </div>
                                                    <div class="col-4 text-center d-flex flex-column align-items-center">
                                                        <span
                                                            class="btn {{ $event['mode'] === 'Offline' ? 'btn-main' : ($event['mode'] === 'Online' ? 'btn-success' : 'btn-dark') }} rounded-10 px-10 py-10 text-white text-sm fw-medium">
                                                            {{ $event['mode'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                                                <h4 class="mb-0 text-main-two-600">{{ $event['price'] }}</h4>
                                                <a href="apply-admission.html"
                                                    class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold"
                                                    tabindex="0">
                                                    Daftar Sekarang
                                                    <i class="ph ph-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>
                    <ul class="pagination mt-40 flex-align gap-12 flex-wrap justify-content-center">
                        <li class="page-item">
                            <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                                href="#"><i class="ph-bold ph-caret-left"></i></a>
                        </li>
                        <li class="page-item">
                            <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                                href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                                href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                                href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                                href="#">...</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                                href="#"><i class="ph-bold ph-caret-right"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('script')
        <!-- ============================== Course List View Section End ============================== -->
        <script>
            // Function untuk menampilkan lebih banyak organizer
            document.getElementById('see_more_organizer').addEventListener('click', function(event) {
                event.preventDefault();
    
                const organizerMore = document.getElementById('organizer_more');
                const seeMoreLink = event.target;
    
                if (organizerMore.classList.contains('d-none')) {
                    organizerMore.classList.remove('d-none');
                    seeMoreLink.textContent = 'Lihat Lebih Sedikit';
                } else {
                    organizerMore.classList.add('d-none');
                    seeMoreLink.textContent = 'Lihat Selengkapnya';
                }
            });
        </script>
@endpush