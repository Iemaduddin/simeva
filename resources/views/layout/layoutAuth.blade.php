<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head />

<body>

    @yield('content')


    <!-- ..::  scripts  start ::.. -->
    <x-script script='{!! isset($script) ? $script : '' !!}' />
    <!-- ..::  scripts  end ::.. -->
    <script>
        // ================== Password Show Hide Js Start ==========
        document.querySelectorAll('.toggle-password, .toggle-password-confirmation').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-toggle');
                const passwordInput = document.getElementById(targetId);
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Ganti ikon mata (eye)
                this.classList.toggle('ri-eye-line');
                this.classList.toggle('ri-eye-off-line');
            });
        });
    </script>
</body>

</html>
