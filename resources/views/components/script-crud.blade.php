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
                            class="w-50 btn btn-outline-neutral-900 text-md px-40 py-11 radius-8">
                            Batal
                        </button>
                        <button type="button" id="confirmDeleteBtn"
                            class="w-50 btn btn-danger border border-danger text-md px-24 py-12 radius-8">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Block  -->
    <div class="modal fade" id="confirmBlock" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-body p-24 text-center">
                    <span id="confirmBlockIconWrapper" class="mb-16 fs-1 line-height-1 text-danger">
                        <iconify-icon id="confirmBlockIcon" icon="ic:sharp-block" class="menu-icon"></iconify-icon>
                    </span>

                    <h6 id="confirmBlockMessage" class="text-lg fw-semibold text-primary-light mb-0">
                    </h6>
                    <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                        <button type="button" data-bs-dismiss="modal"
                            class="w-50 btn btn-outline-neutral-900 text-md px-40 py-11 radius-8">
                            Batal
                        </button>
                        <button type="button" id="confirmBlockBtn"
                            class="w-50 btn btn-danger border border-danger text-md px-24 py-12 radius-8">
                            <div id="buttonText"></div>
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
                    return; // Hanya menangani form dengan atribut data-table
                }

                e.preventDefault();

                const formData = new FormData(form);
                const actionUrl = form.getAttribute('action');
                const tableId = form.dataset.table; // Ambil nilai dari data-table

                // Menemukan tombol submit di dalam form
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {

                    // Simpan teks asli tombol jika belum disimpan
                    if (!submitButton.dataset.originalText) {
                        submitButton.dataset.originalText = submitButton.textContent.trim();
                    }
                    // Menonaktifkan tombol submit
                    submitButton.disabled = true;

                    // Menambahkan spinner jika belum ada
                    if (!submitButton.querySelector('.spinner-border')) {
                        const spinner = document.createElement('span');
                        spinner.className = 'spinner-border spinner-border-sm me-3';
                        spinner.role = 'status';
                        spinner.ariaHidden = 'true';
                        submitButton.prepend(spinner);
                    }

                    // Mengubah teks tombol menjadi "Memproses..."
                    submitButton.childNodes.forEach(node => {
                        if (node.nodeType === 3) { // text node
                            node.textContent = 'Memproses...';
                        }
                    });
                }

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
                    })
                    .finally(() => {
                        // Mengaktifkan kembali tombol submit setelah selesai
                        if (submitButton) {
                            submitButton.disabled = false;

                            // Mengembalikan teks tombol ke kondisi semula
                            submitButton.textContent = submitButton.dataset.originalText;

                            // Hapus spinner
                            const spinner = submitButton.querySelector('.spinner-border');
                            if (spinner) {
                                spinner.remove();
                            }
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
            let currentBlockForm = null;
            let currentTableIdBlock = null;
            let currentBlockActionType = null;
            // Event delegation untuk form block (jurusan dan prodi)
            document.addEventListener('click', function(e) {
                // Cek apakah form yang di-submit adalah form block jurusan atau prodi
                if (e.target.closest('.block-btn')) {
                    e.preventDefault(); // Mencegah aksi default
                    const button = e.target.closest('.block-btn');
                    const form = button.closest('form');

                    if (form) {
                        currentBlockForm = form;
                        currentTableIdBlock = form.dataset.table || null;
                        currentActionType = button.getAttribute('data-action');

                        const modalMessage = document.getElementById('confirmBlockMessage');
                        const buttonText = document.getElementById('buttonText');
                        const confirmButton = document.getElementById('confirmBlockBtn');

                        // Reset class warna tombol
                        confirmButton.classList.remove('btn-danger', 'btn-success', 'border-danger',
                            'border-success');
                        const iconWrapper = document.getElementById('confirmBlockIconWrapper');
                        const iconElement = document.getElementById('confirmBlockIcon');

                        // Reset warna dan icon
                        iconWrapper.classList.remove('text-danger', 'text-warning');
                        if (currentActionType === 'block') {
                            modalMessage.textContent = 'Apakah Anda yakin ingin memblokir user ini?';
                            buttonText.textContent = 'Blokir';
                            confirmButton.classList.add('btn-danger', 'border-danger');

                            iconWrapper.classList.add('text-danger');
                            iconElement.setAttribute('icon', 'ic:sharp-block');
                        } else if (currentActionType === 'unblock') {
                            modalMessage.textContent = 'Apakah Anda yakin ingin membuka blokir user ini?';
                            buttonText.textContent = 'Buka';
                            confirmButton.classList.add('btn-success', 'border-success');

                            iconWrapper.classList.add('text-warning');
                            iconElement.setAttribute('icon', 'gg:unblock');
                        } else if (currentActionType === 'block_event') {
                            modalMessage.textContent = 'Apakah Anda yakin ingin memblokir event ini?';
                            buttonText.textContent = 'Blokir';
                            confirmButton.classList.add('btn-danger', 'border-danger');

                            iconWrapper.classList.add('text-danger');
                            iconElement.setAttribute('icon', 'gg:unblock');
                        } else if (currentActionType === 'unblock_event') {
                            modalMessage.textContent = 'Apakah Anda yakin ingin membuka blokir event ini?';
                            buttonText.textContent = 'Buka';
                            confirmButton.classList.add('btn-success', 'border-success');

                            iconWrapper.classList.add('text-warning');
                            iconElement.setAttribute('icon', 'gg:unblock');
                        }


                        $('#confirmBlock').modal('show'); // Tampilkan modal konfirmasi
                    }
                }
            });

            // Event ketika tombol konfirmasi block di modal ditekan
            document.getElementById('confirmBlockBtn').addEventListener('click', function() {

                if (currentBlockForm) {
                    const formData = new FormData(currentBlockForm);


                    fetch(currentBlockForm.action, {
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
                                notyf.success(data.message || (currentActionType === 'block' ?
                                    'User berhasil diblokir' : 'User berhasil dibuka blokirnya'
                                ));
                                // Refresh tabel yang sesuai
                                if (currentTableIdBlock) {
                                    $(`#${currentTableIdBlock}`).DataTable().ajax.reload();
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            notyf.error(error.message || 'Terjadi kesalahan pada server');
                        })
                        .finally(() => {
                            $('#confirmBlock').modal('hide');
                            currentBlockForm = null;
                            currentTableIdBlock = null;
                        });
                }
            });
        });
    </script>
