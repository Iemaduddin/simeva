<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    @include('components/landingpage/head')
</head>

<body>
    <!--==================== Preloader Start ====================-->
    {{-- <div class="preloader">

    </div> --}}
    <!--==================== Preloader End ====================-->

    <!--==================== Overlay Start ====================-->
    <div class="overlay"></div>
    <!--==================== Overlay End ====================-->

    <!--==================== Sidebar Overlay End ====================-->
    <div class="side-overlay"></div>
    <!--==================== Sidebar Overlay End ====================-->

    <!-- ==================== Scroll to Top End Here ==================== -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- ==================== Scroll to Top End Here ==================== -->


    <main class="dashboard-main">
        @include('components/landingpage/navbar')

        @yield('content')

        @include('components/landingpage/footer')
    </main>

    @include('components/landingpage/script')
    <script>
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('button[type="submit"].submit-btn');
            if (btn) {
                // Disable tombol
                btn.disabled = true;

                // Cek apakah spinner sudah ada
                if (!btn.querySelector('.spinner-border')) {
                    const spinner = document.createElement('span');
                    spinner.className = 'spinner-border spinner-border-sm me-3';
                    spinner.role = 'status';
                    spinner.ariaHidden = 'true';
                    btn.prepend(spinner);
                }

                // Ubah text button ke "Memproses..." hanya jika text node
                btn.childNodes.forEach(node => {
                    if (node.nodeType === 3) { // text node
                        node.textContent = 'Memproses...';
                    }
                });
            }
        });
    </script>
</body>

</html>
