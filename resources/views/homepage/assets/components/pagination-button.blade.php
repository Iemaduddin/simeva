  {{-- Pagination --}}
  <ul class="pagination mt-40 flex-align gap-12 flex-wrap justify-content-center" id="showing-text">
      {{-- Tombol Previous --}}
      <li class="page-item {{ $assets->onFirstPage() ? 'disabled' : '' }}">
          <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
              href="#" data-page="{{ $assets->currentPage() - 1 }}">
              <i class="ph-bold ph-caret-left"></i>
          </a>
      </li>

      {{-- Nomor halaman --}}
      @for ($i = 1; $i <= $assets->lastPage(); $i++)
          <li class="page-item {{ $i == $assets->currentPage() ? 'active' : '' }}">
              <a class="page-link {{ $i == $assets->currentPage() ? 'bg-main-600 text-white' : 'text-neutral-700' }} fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                  href="#" data-page="{{ $i }}">
                  {{ $i }}
              </a>
          </li>
      @endfor

      {{-- Tombol Next --}}
      <li class="page-item {{ !$assets->hasMorePages() ? 'disabled' : '' }}">
          <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
              href="#" data-page="{{ $assets->currentPage() + 1 }}">
              <i class="ph-bold ph-caret-right"></i>
          </a>
      </li>
  </ul>
