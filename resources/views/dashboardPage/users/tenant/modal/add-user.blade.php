 <!-- Modal Add Uer -->
 <div class="modal fade" id="modalAddTenantUser" tabindex="-1" aria-labelledby="modalAddTenantUserLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
         <div class="modal-content radius-16 bg-base">
             <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                 <h1 class="modal-title fs-5" id="modalAddTenantUserLabel">Tambah User</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body p-24">
                 <form id="addTenantUserForm" action="{{ route('add.tenantUser') }}" method="POST"
                     data-table="tenantUserTable">
                     @csrf
                     <div class="row">
                         <div class="col-6 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama <span
                                     class="text-danger">*</span></label>
                             <input type="text" name="name" class="form-control radius-8"
                                 placeholder="Masukkan Nama " required>
                         </div>
                         <div class="col-md-6 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor Handphone/WA
                                 <span class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="number" name="phone_number"
                                 placeholder="Masukkan Nomor Handphone/WA" required>
                         </div>
                         <div class="col-md-6 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Username <span
                                     class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="text" name="username"
                                 placeholder="Masukkan username" required>
                         </div>
                         <div class="col-md-6 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span
                                     class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="email" name="email"
                                 placeholder="Masukkan Email" required>
                         </div>
                         <div class="col-md-6 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Password <span
                                     class="text-danger">*</span></label>
                             <div class="position-relative">
                                 <input type="password" class="form-control radius-8 bg-base" name="password"
                                     id="password" placeholder="Masukkan Password" required>
                                 <span
                                     class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                     data-toggle="password"></span>
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
                                 Tambah
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
         const inputId = $(this).data("toggle"); // tanpa #
         const input = $("#" + inputId);

         // Toggle type input
         if (input.attr("type") === "password") {
             input.attr("type", "text");
             $(this).removeClass("ri-eye-line").addClass("ri-eye-off-line");
         } else {
             input.attr("type", "password");
             $(this).removeClass("ri-eye-off-line").addClass("ri-eye-line");
         }
     });
     // ========================= Password Show Hide Js End ===========================
 </script>
