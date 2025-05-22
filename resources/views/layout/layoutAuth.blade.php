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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');

            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const btn = form.querySelector('button[type="submit"].submit-btn');
                    if (!btn) return;

                    if (!form.checkValidity()) {
                        e.preventDefault();
                        form.classList.add('was-validated');
                        return;
                    }

                    btn.disabled = true;

                    if (!btn.querySelector('.spinner-grow')) {
                        const spinner = document.createElement('span');
                        spinner.className = 'spinner-border spinner-border-sm me-3 text-light';
                        spinner.role = 'status';
                        spinner.ariaHidden = 'true';
                        btn.prepend(spinner);
                    }

                    btn.childNodes.forEach(node => {
                        if (node.nodeType === 3) { // text node
                            node.textContent = 'Memproses...';
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>
