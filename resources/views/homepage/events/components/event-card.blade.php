<div class="row gy-4 event-card" id="event-results">
    @forelse ($events as $event)
        @php
            $organizer = $event->organizers;
            $user = $organizer->user;
            $jurusan = $user->jurusan;
            $userLogin = Auth::user();
            $userCategory = optional($userLogin)->category_user;
            $userJurusanId = $userLogin->jurusan_id ?? null;
            $eventScope = $event->scope;
            $organizerJurusanId = $organizer->user->jurusan_id ?? null;

            $showEvent = false;
            if ($eventScope === 'Internal Jurusan' && $userJurusanId && $userJurusanId == $organizerJurusanId) {
                // Jika scope dan category_user cocok
                $showEvent = true;
            } elseif ($eventScope === 'Umum') {
                // Semua boleh ikut event umum
                $showEvent = true;
            } elseif ($eventScope === $userCategory) {
                // Untuk internal jurusan, cocokkan jurusan
                $showEvent = true;
            }
        @endphp
        @auth
            @continue(!$showEvent)
        @endauth
        <div class="col-12">
            <div class="row course-item bg-main-25 rounded-16 p-20 border border-neutral-30 list-view">
                <div class="col-lg-5 ps-5 rounded-12 overflow-hidden position-relative h-100">
                    <a href="{{ route('detail_event', $event->id) }}">
                        <img src="{{ asset('storage/' . $event->pamphlet_path) }}" alt="Event Image"
                            class="rounded-12 cover-img transition-2" style="width:350px; height: 400px">
                    </a>
                    @php
                        if ($event->scope === 'Internal Organisasi') {
                            $statusText = 'Internal ' . $event->organizers->shorten_name;
                            $badgeClass = 'btn-secondary';
                        } elseif (
                            $event->scope === 'Internal Jurusan' &&
                            $event->organizers->organizer_type === 'Jurusan'
                        ) {
                            $statusText = 'Internal ' . $event->organizers->shorten_name;
                            $badgeClass = 'btn-warning';
                        } elseif (
                            $event->scope === 'Internal Jurusan' &&
                            $event->organizers->organizer_type === 'HMJ'
                        ) {
                            $statusText = 'Internal J' . $jurusan->kode_jurusan;
                            $badgeClass = 'btn-warning';
                        } elseif ($event->scope === 'Internal Kampus') {
                            $statusText = 'Internal Kampus';
                            $badgeClass = 'btn-main';
                        } elseif ($event->scope === 'Umum') {
                            $statusText = 'Umum';
                            $badgeClass = 'btn-dark';
                        }
                    @endphp
                    <div
                        class="flex-align gap-8 btn {{ $badgeClass }} rounded-pill px-24 py-12 text-white position-absolute inset-block-start-0 inset-inline-start-0 mt-20 ms-20 z-1">
                        <span class="text-lg fw-medium">{{ $statusText }}</span>
                    </div>
                    @if (auth()->check() && auth()->user()->hasRole('Participant'))
                        <button type="button"
                            class="wishlist-btn w-48 h-48 bg-white text-main-two-600 flex-center position-absolute inset-block-start-0 inset-inline-end-0 mt-20 me-20 z-1 text-2xl rounded-circle transition-2"
                            data-event-id="{{ $event->id }}">
                            <i class="ph ph-bookmark-simple"></i>
                        </button>
                    @endif
                </div>
                <div class="col-lg-7 ps-20 ">
                    <div class="">
                        <h4 class="mb-15">
                            <a href="{{ route('detail_event', $event->id) }}"
                                class="link text-line-2">{{ $event->title }}</a>
                        </h4>
                        <div class="flex-align gap-8">
                            <span class="text-neutral-700 text-2xl d-flex"><i class="ph-bold ph-note"></i></span>
                            <span class="text-neutral-700 text-lg fw-medium">Pendaftaran</span>
                        </div>
                        <span
                            class="ms-30 my-5 btn btn-outline-main rounded-pill px-10 py-10 text-white text-sm fw-medium">
                            {{ \Carbon\Carbon::parse($event->registration_date_start)->translatedFormat('d F Y') }}
                            -
                            {{ \Carbon\Carbon::parse($event->registration_date_end)->translatedFormat('d F Y') }}
                        </span>
                        <div class="flex-align gap-8">
                            <span class="text-neutral-700 text-2xl d-flex"><i class="ph-bold ph-timer"></i></span>
                            <span class="text-neutral-700 text-lg fw-medium">Pelaksanaan</span>
                        </div>
                        @php
                            $dates = $event->steps->pluck('event_date')->sort(); // sort ascending

                            if ($dates->count() > 1) {
                                $firstDate = \Carbon\Carbon::parse($dates->first());
                                $lastDate = \Carbon\Carbon::parse($dates->last());

                                // Jika bulan sama
                                if ($firstDate->format('F') === $lastDate->format('F')) {
                                    $displayDate =
                                        $firstDate->format('d') .
                                        ' - ' .
                                        $lastDate->format('d') .
                                        ' ' .
                                        $lastDate->translatedFormat('F Y');
                                } else {
                                    $displayDate =
                                        $firstDate->translatedFormat('d M') .
                                        ' - ' .
                                        $lastDate->translatedFormat('d M Y');
                                }
                            } elseif ($dates->count() === 1) {
                                $displayDate = \Carbon\Carbon::parse($dates->first())->translatedFormat('d F Y');
                            } else {
                                $displayDate = '-';
                            }
                        @endphp
                        <span
                            class="ms-30 my-5 btn btn-main rounded-pill px-10 py-10 text-white text-sm fw-medium">{{ $displayDate }}</span>
                        @php

                            $allLocations = [];

                            foreach ($event->steps as $step) {
                                foreach (json_decode($step->location ?? '[]', true) as $loc) {
                                    if ($loc['type'] === 'offline') {
                                        // Cek apakah location adalah UUID
                                        if (isset($loc['location'])) {
                                            $assetName = \App\Models\Asset::find($loc['location'])?->name;
                                            if ($assetName) {
                                                $allLocations[] = $assetName;
                                            }
                                        } else {
                                            if (isset($loc['location'])) {
                                                $allLocations[] = $loc['location'];
                                            }
                                        }
                                    } elseif ($loc['type'] === 'online') {
                                        if (isset($loc['location'])) {
                                            $allLocations[] = $loc['location'];
                                        }
                                    } elseif ($loc['type'] === 'hybrid') {
                                        // Offline bagian
                                        if (isset($loc['location_offline'])) {
                                            if (\Ramsey\Uuid\Uuid::isValid($loc['location_offline'])) {
                                                $assetName = \App\Models\Asset::find($loc['location_offline'])?->name;
                                                if ($assetName) {
                                                    $allLocations[] = $assetName;
                                                }
                                            } else {
                                                $allLocations[] = $loc['location_offline'];
                                            }
                                        }

                                        // Online bagian
                                        if (isset($loc['location_online'])) {
                                            $allLocations[] = $loc['location_online'];
                                        }
                                    }
                                }
                            }

                            // Buang duplikat & gabung dengan koma
                            $locationString = implode(', ', array_unique($allLocations));
                        @endphp
                        <div class="flex-between gap-8 flex-wrap my-5">
                            <div class="flex-align gap-4">
                                <span class="text-neutral-700 text-2xl d-flex"><i
                                        class="ph-bold ph-map-pin-area"></i></span>
                                <span
                                    class="text-neutral-700 text-md fw-medium text-line-1">{{ $locationString }}</span>
                            </div>
                        </div>
                        <div class="flex-between gap-8 flex-wrap my-10">
                            <div class="flex-align gap-4">
                                <span class="text-neutral-700 text-2xl d-flex"><i
                                        class="ph-bold ph-user-circle"></i></span>
                                <span class="text-neutral-700 text-md fw-medium text-line-1">Kuota:
                                </span><span>{{ $event->remaining_quota }}/{{ $event->quota }}</span>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-8  d-flex align-items-center">
                                <span class="text-neutral-700 text-2xl d-flex">
                                    <img src="{{ $event->organizers->logo ? asset('storage/' . $event->organizers->logo) : asset('assets/images/banner.png') }}"
                                        alt="Logo Organizers" class="w-32 h-32 object-fit-cover rounded-circle">
                                </span>
                                <p class="text-neutral-700 text-md fw-medium text-line-1 ms-2">
                                    {{ $event->organizers->shorten_name }}
                                </p>
                            </div>
                            @php
                                // Ambil semua execution_system dari step yang ada
                                $executionSystems = $event->steps->pluck('execution_system')->unique();

                                if ($executionSystems->count() === 1) {
                                    // Semua step sama jenis pelaksanaannya
                                    $executionSystemDisplay = ucfirst($executionSystems->first()); // Offline, Online, Hybrid
                                } else {
                                    // Ada campuran jenis
                                    $executionSystemDisplay = 'Hybrid';
                                }

                                if ($executionSystemDisplay === 'Offline') {
                                    $executionBadgeClass = 'btn-main';
                                } elseif ($executionSystemDisplay === 'Online') {
                                    $executionBadgeClass = 'btn-success';
                                } else {
                                    $executionBadgeClass = 'btn-dark';
                                }
                            @endphp
                            <div class="col-4 text-center d-flex flex-column align-items-center">
                                <span
                                    class="btn {{ $executionBadgeClass }} rounded-10 px-10 py-10 text-white text-sm fw-medium">
                                    {{ $executionSystemDisplay }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                        @php
                            $lowestPrice = $event->prices->pluck('price')->filter()->min();
                        @endphp

                        <h4
                            class="mb-0 {{ $lowestPrice && $lowestPrice != 0 ? 'text-success-600' : 'text-main-two-600' }}">
                            {{ $lowestPrice && $lowestPrice != 0 ? 'Rp' . number_format($lowestPrice, 0, ',', '.') : 'Gratis' }}
                        </h4>

                        <a href="{{ route('detail_event', $event->id) }}"
                            class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold"
                            tabindex="0">
                            Daftar Sekarang
                            <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center">
            <p class="text-muted">Belum ada event di kategori ini.</p>
        </div>
    @endforelse

</div>
