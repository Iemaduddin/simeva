@extends('layout.landingPageLayout')

@section('title', 'Panduan')
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
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Daftar Panduan</h1>
                        <ul class="breadcrumb__list d-flex align-items-center justify-content-center gap-4">
                            <li class="breadcrumb__item">
                                <a href="{{ route('home') }}"
                                    class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium">
                                    <i class="text-lg d-inline-flex ph-bold ph-house"></i> Beranda</a>
                            </li>
                            <li class="breadcrumb__item">
                                <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                            </li>
                            <li class="breadcrumb__item">
                                <span class="text-main-two-600"> Daftar Panduan </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container py-50">
        <div class="border border-neutral-30 rounded-12 bg-main-25 p-20 my-20">
            <h2 class="mt-24 mb-24">Sistem Informasi Manajemen Event dan Aset</h2>
            <p class="text-neutral-700">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Doloribus aperiam vel
                repellendus deleniti nihil, est repudiandae rem commodi consectetur quasi amet, assumenda omnis deserunt,
                fuga optio nam eaque dolores nemo culpa accusantium excepturi! Pariatur quas odit tempora dolores laudantium
                facere distinctio sapiente id ea aspernatur recusandae excepturi omnis iusto sint, consequatur in nesciunt
                dolore non nostrum asperiores fugiat voluptatem dolorem. Perspiciatis, ipsum eius tempora fuga fugiat
                debitis doloremque exercitationem assumenda dolorum iure? Magni nesciunt quam, veniam eius sed, ratione, rem
                voluptas molestias fugit eaque beatae laudantium atque minus. Expedita officia dicta repellat dolore error,
                ratione sit est quibusdam corporis, facere, explicabo aliquam quasi ad harum sunt dolorem. Numquam aliquam,
                perferendis ipsum ex sunt impedit rerum aperiam dolorem eum quia dignissimos, nostrum dolor aut fugiat vero
                deleniti inventore consequatur pariatur, voluptatum non facere adipisci corrupti qui maxime! Reiciendis,
                tempora corporis recusandae commodi sunt repudiandae suscipit magnam numquam. Aut perspiciatis maiores sunt,
                odit officiis tempora aliquid fugit modi itaque sed tempore beatae repellat dignissimos suscipit et error
                ratione? Ea, aliquid unde quibusdam reprehenderit consequatur a doloribus deleniti nam tenetur esse. Debitis
                a sint culpa dolor eius, ipsum nobis, repudiandae architecto quidem, voluptate pariatur. Laudantium culpa
                eligendi voluptates esse nulla architecto temporibus facere.</p>
        </div>
        <div class="accordion common-accordion style-three" id="accordionExampleTwo">

            <div class="accordion-item">
                <h2 class="accordion-header bg-main-25">
                    <button class="accordion-button bg-main-25 collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTutorialEvent" aria-expanded="false" aria-controls="collapseTutorialEvent">
                        Panduan Pendaftaran Event
                    </button>
                </h2>
                <div id="collapseTutorialEvent" class="accordion-collapse collapse" data-bs-parent="#accordionExampleTwo">
                    <div class="accordion-body p-0 bg-main-25">
                        <object class="pdf p-20 w-100"
                            data=
            "https://media.geeksforgeeks.org/wp-content/cdn-uploads/20210101201653/PDF.pdf"
                            height="1000px">
                        </object>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header bg-main-25">
                    <button class="accordion-button bg-main-25 collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTutorialAsset" aria-expanded="false" aria-controls="collapseTutorialAsset">
                        Panduan Peminjaman Aset oleh Pihak Eksternal
                    </button>
                </h2>
                <div id="collapseTutorialAsset" class="accordion-collapse collapse" data-bs-parent="#accordionExampleTwo">
                    <div class="accordion-body p-0 bg-main-25">
                        <object class="pdf p-20 w-100"
                            data=
            "https://media.geeksforgeeks.org/wp-content/cdn-uploads/20210101201653/PDF.pdf"
                            height="1000px">
                        </object>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== Breadcrumb End Here ==================== -->
@endsection
