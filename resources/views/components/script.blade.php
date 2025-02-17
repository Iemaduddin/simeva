    <!-- jQuery library js -->
    <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <!-- Apex Chart js -->
    <script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>
    <!-- Data Table js -->
    <script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
    <!-- Iconify Font js -->
    <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
    <!-- jQuery UI js -->
    <script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
    <!-- Vector Map js -->
    <script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- Popup js -->
    <script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script>
    <!-- Slick Slider js -->
    <script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
    <!-- prism js -->
    <script src="{{ asset('assets/js/lib/prism.js') }}"></script>
    <!-- file upload js -->
    <script src="{{ asset('assets/js/lib/file-upload.js') }}"></script>
    <!-- audioplayer -->
    <script src="{{ asset('assets/js/lib/audioplayer.js') }}"></script>

    <!-- main js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- notify js -->
    <script src="{{ asset('assets/js/notyf.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("input[type=number]").forEach(function(input) {
                // Mencegah karakter minus (-) dan 'e' di input
                input.addEventListener("keydown", function(event) {
                    if (event.key === "-" || event.key === "e") {
                        event.preventDefault();
                    }
                });

                // Memastikan input hanya angka positif (0-9)
                input.addEventListener("input", function() {
                    this.value = this.value.replace(/[^0-9]/g, "");
                });
            });
        });
    </script>


    @stack('script')
