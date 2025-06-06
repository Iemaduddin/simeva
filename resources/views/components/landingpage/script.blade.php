<!-- Jquery js -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<!-- Bootstrap Bundle Js -->
<script src="{{ asset('assets/js/boostrap.bundle.min.js') }}"></script>
<!-- select2 Js -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<!-- Phosphor Icon Js -->
<script src="{{ asset('assets/js/phosphor-icon.js') }}"></script>
<!-- Slick js -->
<script src="{{ asset('assets/js/slick.min.js') }}"></script>
<!-- Slick js -->
<script src="{{ asset('assets/js/counter.min.js') }}"></script>
<!-- magnific popup -->
<script src="{{ asset('assets/js/magnific-popup.min.js') }}"></script>
<!-- Data Table js -->
<script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
<!-- Jquery Ui js -->
<script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
<!-- plyr Js -->
<script src="{{ asset('assets/js/plyr.js') }}"></script>
<!-- vanilla Tilt -->
<script src="{{ asset('assets/js/vanilla-tilt.min.js') }}"></script>
<!-- wow -->
<script src="{{ asset('assets/js/wow.min.js') }}"></script>

<script src="{{ asset('assets/js/aos.js') }}"></script>

<!-- main js -->
<script src="{{ asset('assets/js/main.js') }}"></script>
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
