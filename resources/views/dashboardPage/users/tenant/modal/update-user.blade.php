<!-- Modal Update User -->
<div class="modal fade" id="modalUpdateTenantUser-{{ $user->id }}" tabindex="-1"
    aria-labelledby="modalUpdateTenantUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalUpdateTenantUserLabel">Ubah Data User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form id="updateTenantUserForm-{{ $user->id }}" data-user-id={{ $user->id }}
                    action="{{ route('update.tenantUser', $user->id) }}" method="POST" data-table="tenantUserTable">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control radius-8"
                                value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor Handphone/WA
                                <span class="text-danger">*</span></label>
                            <input class="form-control radius-8 bg-base" type="number" name="phone_number"
                                value="{{ $user->phone_number }}" required>
                        </div>
                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Username <span
                                    class="text-danger">*</span></label>
                            <input class="form-control radius-8 bg-base" type="text" name="username"
                                value="{{ $user->username }}" required>
                        </div>
                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span
                                    class="text-danger">*</span></label>
                            <input class="form-control radius-8 bg-base" type="email" name="email"
                                value="{{ $user->email }}"required>
                        </div>
                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Password <span
                                    class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="password" class="form-control radius-8 bg-base" name="password"
                                    id="password-{{ $user->id }}" placeholder="Masukkan Password">
                                <span
                                    class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                    data-toggle="password-{{ $user->id }}"></span>
                            </div>
                        </div>
                        <div class="modal-footer d-flex align-items-end justify-content-end gap-3 mt-24">
                            <button type="reset"
                                class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-24 py-12 radius-8"
                                data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit"
                                class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                                Ubah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // ================== Password Show Hide Js Start ==========
    $(document).on("click", ".toggle-password", function() {
        $(this).toggleClass("ri-eye-off-line");

        // Ambil ID dari data-toggle tanpa #
        let inputId = $(this).attr("data-toggle");
        let input = $("#" + inputId); // Seleksi berdasarkan ID

        // Toggle type antara password dan text
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    // ========================= Password Show Hide Js End ===========================
</script>
