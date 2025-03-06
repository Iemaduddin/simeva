    <!-- Modal Delete  -->
    <div class="modal fade" id="confirmDelete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-body p-24 text-center">
                    <span class="mb-16 fs-1 line-height-1 text-danger">
                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                    </span>
                    <h6 class="text-lg fw-semibold text-primary-light mb-0">
                        Apakah Anda yakin ingin menghapus data ini?
                    </h6>
                    <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                        <button type="button" data-bs-dismiss="modal"
                            class="w-50 border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8">
                            Batal
                        </button>
                        <button type="button" id="confirmDeleteBtn"
                            class="w-50 btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                if (!form.matches('form[data-table]')) {
                    console.log('takde');
                    return; // Hanya menangani form dengan atribut data-table
                }

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


            // Ketika tombol Delete di modal ditekan
            // Variabel untuk menyimpan form yang akan di-submit
            let currentDeleteForm = null;
            let currentTableId = null;
            // Event delegation untuk form delete (jurusan dan prodi)
            document.addEventListener('click', function(e) {
                // Cek apakah form yang di-submit adalah form delete jurusan atau prodi
                if (e.target.closest('.delete-btn')) {
                    console.log('yes');
                    e.preventDefault(); // Mencegah aksi default
                    const button = e.target.closest('.delete-btn');
                    const form = button.closest('form');

                    if (form) {
                        currentDeleteForm = form;
                        currentTableId = form.dataset.table || null;

                        $('#confirmDelete').modal('show'); // Tampilkan modal konfirmasi
                    }
                }
            });

            // Event ketika tombol konfirmasi delete di modal ditekan
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {

                if (currentDeleteForm) {
                    const formData = new FormData(currentDeleteForm);


                    fetch(currentDeleteForm.action, {
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
                                notyf.success(data.message || 'Data berhasil dihapus');
                                // Refresh tabel yang sesuai
                                if (currentTableId) {
                                    $(`#${currentTableId}`).DataTable().ajax.reload();
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            notyf.error(error.message || 'Terjadi kesalahan pada server');
                        })
                        .finally(() => {
                            $('#confirmDelete').modal('hide');
                            currentDeleteForm = null;
                            currentTableId = null;
                        });
                }
            });
        });
    </script>
