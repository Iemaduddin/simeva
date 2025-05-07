@extends('layout.landingPageLayout')

@section('title', 'Aset Tersimpan')
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
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Daftar Aset yang Disimpan</h1>
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
                                <span class="text-main-two-600">Daftar Aset yang Disimpan </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-40 ">
        <img src="{{ asset('assets/images/shapes/shape2.png') }}" alt="" class="shape six animation-scalation">


        <section class="container pt-10 pb-24">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-24 remove-row">
                <h5 class="text-neutral-700"> Daftar aset yang disimpan</h5>
                @if (count($assets) != 0)
                    <button type="button"
                        class="btn btn-outline-danger py-12 px-24 rounded-pill flex-align gap-8 aos-init aos-animate fw-semibold remove-all-saved-asset"
                        data-aos="fade-left">
                        <i class="ph-bold ph-trash d-flex text-lg"></i>
                        Hapus Semua
                    </button>
                @endif
            </div>
            <div class="row gy-4">
                @forelse ($assets as $asset)
                    <div class="col-lg-4 col-sm-6 wow fadeInUp  assets-item" data-type="{{ $asset->type }}"
                        data-facility="{{ $asset->facility_scope }}" data-jurusan-id="{{ $asset->jurusan_id }}"
                        data-is-public="{{ $asset->facility_scope === 'umum' ? 'true' : 'false' }}">
                        <div class="asset-item bg-white rounded-16 p-12 h-100 box-shadow-md">
                            <div class="asset-item__thumb rounded-12 overflow-hidden position-relative">
                                <a href="{{ route('asset-bmn.getData', $asset->id) }}" class="w-100 h-100">
                                    @php
                                        $asset_images = json_decode($asset->asset_images, true);
                                    @endphp
                                    <img src="{{ asset('storage/' . $asset_images[0]) }}" alt="Asset Image"
                                        class="asset-item__img rounded-12 cover-img transition-2">
                                </a>
                                <div
                                    class="{{ $asset->facility_scope === 'umum' ? 'bg-main-600' : 'bg-warning-600' }} rounded-pill px-24 py-12 text-white position-absolute inset-block-start-0 inset-inline-start-0 mt-20 ms-20 z-1">
                                    <span class="text-lg fw-medium">{{ strToUpper($asset->facility_scope) }}</span>
                                </div>
                                <button type="button"
                                    class="wishlist-btn w-48 h-48 bg-white text-main-two-600 flex-center position-absolute inset-block-start-0 inset-inline-end-0 mt-20 me-20 z-1 text-2xl rounded-circle transition-2"
                                    data-asset-id="{{ $asset->id }}">
                                    <i class="ph ph-bookmark-simple"></i>
                                </button>
                            </div>
                            <div class="p-12">
                                <div class="">
                                    <h4 class="mb-12">
                                        <a href="{{ route('asset-bmn.getData', $asset->id) }}"
                                            class="link text-line-2">{{ $asset->name }}</a>
                                    </h4>
                                    <span class="text-neutral-500 text-line-4">{{ $asset->description }}</span>
                                    <h5 class="mt-12">Fasilitas</h5>
                                    @php
                                        $facilityList = explode(',', $asset->facility);
                                    @endphp
                                    @foreach ($facilityList as $asset_facility)
                                        <div class="flex-align gap-8">
                                            <span class="text-neutral-700 text-2xl d-flex"><i
                                                    class="ph-bold ph-check text-main-600"></i></span>
                                            <span class="text-neutral-700 text-md ">{{ $asset_facility }}</span>
                                        </div>
                                    @endforeach
                                    <div class="flex-align gap-8 mt-20">
                                        <span
                                            class="badge {{ $asset->status === 'OPEN' ? 'badge-success' : 'badge-danger' }}rounded-10 px-10 py-10 bg-success text-white text-sm fw-bold ">
                                            {{ $asset->status }}
                                        </span>
                                        @if ($asset->available_at)
                                            <div class="row">
                                                <div class="col-12 fw-bold">Tersedia:</div>
                                                <div class="col-12">{{ implode(', ', explode('|', $asset->available_at)) }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div
                                    class="flex-between justify-content-center gap-8 pt-24 border-top border-neutral-50 mt-12 border-dashed border-0">
                                    <a href="{{ route('asset-bmn.getData', $asset->id) }}"
                                        class="btn btn-main rounded-pill flex-align gap-8" data-aos="fade-right">
                                        Cek Jadwal
                                        <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center">
                        <p class="text-neutral-700"> Tidak ada aset yang disimpan</p>
                    </div>
                @endforelse
            </div>
        </section>
        <!-- ================================== Asset Content End =========================== -->
    </section>

@endsection
@push('script')
    @if (auth()->check())
        <script>
            document.addEventListener("DOMContentLoaded", async function() {
                const buttons = document.querySelectorAll(".wishlist-btn");
                const removeRow = document.querySelector(".remove-row");
                const removeAllBtn = document.querySelector(".remove-all-saved-asset");

                // Ambil semua ID aset dari tombol bookmark
                const assetIds = Array.from(buttons).map(button => button.dataset.assetId);

                try {
                    const response = await fetch("{{ route('saved.item.check') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            itemType: 'asset',
                            asset_ids: assetIds
                        })
                    });

                    const savedAssets = await response.json();

                    buttons.forEach(button => {
                        if (savedAssets.includes(button.dataset.assetId)) {
                            button.classList.add("bg-main-two-600", "text-white");
                            button.classList.remove("bg-white", "text-main-two-600");
                        } else {
                            button.classList.add("bg-white", "text-main-two-600");
                            button.classList.remove("bg-main-two-600", "text-white");
                        }
                    });

                } catch (error) {
                    console.error("Error fetching saved:", error);
                }

                // Event listener untuk tombol bookmark
                buttons.forEach(button => {
                    button.addEventListener("click", async function() {
                        const assetId = this.dataset.assetId;
                        const assetItem = this.closest(".assets-item");
                        const isSaved = this.classList.contains("bg-main-two-600");

                        try {
                            const response = await fetch("{{ route('saved.item.toggle') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                },
                                body: JSON.stringify({
                                    itemType: 'asset',
                                    asset_id: assetId
                                })
                            });

                            const result = await response.json();

                            if (response.ok) {
                                if (isSaved) {
                                    this.classList.add("bg-white", "text-main-two-600");
                                    this.classList.remove("bg-main-two-600", "text-white");

                                    // Hapus elemen dari DOM
                                    assetItem.remove();
                                } else {
                                    this.classList.add("bg-main-two-600", "text-white");
                                    this.classList.remove("bg-white", "text-main-two-600");
                                }
                                this.style.transform = "scale(1.2)";
                                setTimeout(() => this.style.transform = "scale(1)", 200);
                            } else {
                                console.error("Error:", result.message);
                            }
                        } catch (error) {
                            console.error("Request failed:", error);
                        }
                    });
                });

                // Event listener untuk tombol "Remove All"
                removeAllBtn.addEventListener("click", async function() {
                    try {
                        const response = await fetch("{{ route('saved.item.removeAll') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').content,
                            }
                        });

                        const result = await response.json();

                        if (response.ok) {
                            // Hapus semua elemen aset dari DOM
                            document.querySelectorAll(".assets-item").forEach(item => item.remove());
                            removeAllBtn.remove();

                        } else {
                            console.error("Error:", result.message);
                        }
                    } catch (error) {
                        console.error("Request failed:", error);
                    }
                });
            });
        </script>
    @endif
@endpush
