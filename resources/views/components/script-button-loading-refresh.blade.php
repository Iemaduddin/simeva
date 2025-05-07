<script>
    document.addEventListener('submit', function(e) {
        const form = e.target;
        const btn = form.querySelector('button[type="submit"]');

        if (btn) {
            btn.disabled = true;

            if (!btn.querySelector('.spinner-border')) {
                const spinner = document.createElement('span');
                spinner.className = 'spinner-border spinner-border-sm me-3';
                spinner.role = 'status';
                spinner.ariaHidden = 'true';
                btn.prepend(spinner);
            }

            btn.childNodes.forEach(node => {
                if (node.nodeType === 3) {
                    node.textContent = 'Memproses...';
                }
            });
        }
    });
</script>
