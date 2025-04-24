<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notyf = new Notyf({
            duration: 4000,
            position: {
                x: 'right',
                y: 'bottom',
            }
        });
        document.body.addEventListener('submit', function(e) {
            const form = e.target;
            if (!form.matches('form[data-table]'))
                return; // Hanya menangani form dengan atribut data-table

            e.preventDefault();

            const formData = new FormData(form);
            const actionUrl = form.getAttribute('action');
            const tableId = form.dataset.table; // Ambil nilai dari data-table

            fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        notyf.success(data.message);
                        form.reset();
                        const modal = form.closest('.modal');
                        if (modal) {
                            const modalId = modal.id;
                            $(`#${modalId}`).modal('hide'); // Tutup modal
                        }
                        if (tableId) {
                            $(`#${tableId}`).DataTable().ajax.reload(); // Reload DataTable
                        }
                    }
                })
                .catch(error => {
                    if (error.errors) {
                        Object.values(error.errors).forEach(messages => {
                            messages.forEach(message => {
                                notyf.error(message);
                            });
                        });
                    } else {
                        notyf.error(error.message || 'Terjadi kesalahan pada server!');
                    }
                });
        });
    });
</script>
