@extends('layout.landingPageLayout')

@section('title', 'Event Tersimpan')
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
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Daftar Event yang Disimpan</h1>
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
                                <span class="text-main-two-600">Daftar Event yang Disimpan </span>
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
                <h5 class="text-neutral-700"> Daftar event yang disimpan</h5>
                @if (count($events) != 0)
                    <button type="button"
                        class="btn btn-outline-danger py-12 px-24 rounded-pill flex-align gap-8 aos-init aos-animate fw-semibold remove-all-saved-event"
                        data-aos="fade-left">
                        <i class="ph-bold ph-trash d-flex text-lg"></i>
                        Hapus Semua
                    </button>
                @endif
            </div>
            <div class="row gy-4">
                @include('homepage.events.components.event-card-vertical', [
                    'events' => $events,
                    'message' => 'Tidak ada event yang disimpan',
                ])
            </div>



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
                const removeAllBtn = document.querySelector(".remove-all-saved-event");

                // Ambil semua ID aset dari tombol bookmark
                const eventIds = Array.from(buttons).map(button => button.dataset.eventId);

                try {
                    const response = await fetch("{{ route('saved.item.check') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            itemType: 'event',
                            event_ids: eventIds
                        })
                    });

                    const savedEvents = await response.json();

                    buttons.forEach(button => {
                        if (savedEvents.includes(button.dataset.eventId)) {
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
                        const eventId = this.dataset.eventId;
                        const eventItem = this.closest(".event-card");
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
                                    itemType: 'event',
                                    event_id: eventId
                                })
                            });

                            const result = await response.json();

                            if (response.ok) {
                                if (isSaved) {
                                    this.classList.add("bg-white", "text-main-two-600");
                                    this.classList.remove("bg-main-two-600", "text-white");

                                    // Hapus elemen dari DOM
                                    eventItem.remove();
                                } else {
                                    this.classList.add("bg-main-two-600", "text-white");
                                    this.classList.remove("bg-white", "text-main-two-600");
                                }
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
                            document.querySelectorAll(".event-card").forEach(item => item.remove());
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
