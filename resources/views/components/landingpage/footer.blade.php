<!-- ==================== Footer Start Here ==================== -->
<footer class="footer position-relative z-1 bg-main-600">
    <img src="{{ asset('assets/images/shapes/shape2.png') }}" alt="" class="shape five animation-scalation">
    <img src="{{ asset('assets/images/shapes/shape6.png') }}" alt="" class="shape one animation-scalation">

    <div class="pt-50 pb-10">
        <div class="container container-two">
            <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1">
                <div class="col" data-aos="fade-up" data-aos-duration="300">
                    <div class="footer-item">
                        <!-- Logo Start -->
                        <div class="logo d-flex align-items-center justify-content-center">
                            <!-- Logo Section -->
                            <a href="{{ route('home') }}" class="link me-12">
                                <img src="{{ asset('assets/images/logo_polinema.png') }}" alt="Logo Polinema"
                                    style="width: 3em;">
                            </a>
                            <!-- Title Section -->
                            <h2 class="text-white m-0"><i>SIMEVA</i></h2>
                        </div>
                        <!-- Logo End  -->
                        <p class="my-32 text-white">Sistem Informasi Manajemen Event dan Aset</p>
                        <ul class="flex-align gap-18">
                            <li>
                                <img src="{{ asset('assets/images/logo_polinema_rek.jpg') }}" alt="Logo Polinema Rek">
                            </li>
                            <li>
                                <img src="{{ asset('assets/images/logo_Fighting_Polinema_Joss.png') }}"
                                    alt="Logo Polinema Joss">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xxl-2" data-aos="fade-up" data-aos-duration="400">
                    <div class="footer-item">
                        <h4 class="footer-item__title mb-32 text-white">Akses Cepat</h4>
                        <ul class="footer-menu">
                            <li class="mb-16">
                                <a href="{{ route('home') }}"
                                    class="text-white hover-text-decoration-underline">Beranda</a>
                            </li>
                            <li class="mb-16">
                                <a href="{{ route('home') }}"
                                    class="text-white hover-text-decoration-underline">Event</a>
                            </li>
                            <li class="mb-16">
                                <a href="{{ route('home') }}"
                                    class="text-white hover-text-decoration-underline">Penyelenggara</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xxl-1" data-aos="fade-up" data-aos-duration="600">
                    <div class="footer-item">
                        <h4 class="footer-item__title mb-32">&nbsp;</h4>
                        <ul class="footer-menu">
                            <li class="mb-16">
                                <a href="courses.html" class="text-white hover-text-decoration-underline">Kalender</a>
                            </li>
                            <li class="mb-16">
                                <a href="courses.html" class="text-white hover-text-decoration-underline">Aset
                                    BMN</a>
                            </li>
                            <li class="mb-16">
                                <a href="courses.html" class="text-white hover-text-decoration-underline">Panduan</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col" data-aos="fade-up" data-aos-duration="800">
                    <div class="footer-item">
                        <h4 class="footer-item__title mb-32 text-white">Hubungi Kami</h4>
                        <div class="flex-align gap-20 mb-24">
                            <span class="icon d-flex text-32 text-white"><i class="ph ph-phone"></i></span>
                            <div class="">
                                <a href="tel:(207)555-0119" class="text-white d-block hover-text-white mb-4">021 -
                                    2324441</a>
                            </div>
                        </div>
                        <div class="flex-align gap-20 mb-24">
                            <span class="icon d-flex text-32 text-white"><i class="ph ph-whatsapp-logo"></i></span>
                            <div class="">
                                <a href="https://api.whatsapp.com/send/?phone=0821323232323232"
                                    class="text-white d-block hover-text-white mb-4">082331440024</a>
                            </div>
                        </div>
                        <div class="flex-align gap-20 mb-24">
                            <span class="icon d-flex text-32 text-white"><i class="ph ph-envelope-open"></i></span>
                            <div class="">
                                <a href="mailto:simeva.polinema.ac.id"
                                    class="text-white d-block hover-text-white mb-4">simeva.polinema.ac.id</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4" data-aos="fade-up" data-aos-duration="1000">
                    <div class="footer-item">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.502734311811!2d112.61354061144864!3d-7.946885879135478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78827687d272e7%3A0x789ce9a636cd3aa2!2sState%20Polytechnic%20of%20Malang!5e0!3m2!1sen!2sid"
                            width="100%" height="250" style="border:0;" allowfullscreen loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <!-- bottom Footer -->
        <div class="bottom-footer border-top border-dashed border-main-100 border-0 py-20">
            <div class="container container-two">
                <div class="bottom-footer__inner d-flex justify-content-center gap-3 flex-wrap text-center">
                    <p class="bottom-footer__text text-white"> Copyright &copy;<span id="yearNow"></span>
                        <span class="fw-semibold">Politeknik Negeri Malang</span> All
                        Rights Reserved.
                    </p>
                    <script>
                        document.getElementById('yearNow').textContent = new Date().getFullYear();
                    </script>
                </div>
            </div>
        </div>
    </div>

</footer>
<!-- ==================== Footer End Here ==================== -->
