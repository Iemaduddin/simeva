 <!-- Modal Add Uer -->
 <div class="modal fade" id="modalAddParticipant" tabindex="-1" aria-labelledby="modalAddParticipantLabel"
     aria-hidden="true">
     <div class="modal-dialog modal-xl modal-dialog modal-dialog-centered">
         <div class="modal-content radius-16 bg-base">
             <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                 <h1 class="modal-title fs-5" id="modalAddParticipantLabel">Tambah Peserta</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body p-24">
                 <form id="addEventParticipantForm"
                     action="{{ route('add.eventParticipant', ['eventId' => $event->id]) }}" method="POST"
                     data-table="eventParticipantTable">
                     @csrf
                     <div class="row">
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Lengkap<span
                                     class="text-danger">*</span></label>
                             <input type="text" name="name" class="form-control radius-8"
                                 placeholder="Masukkan Nama Lengkap" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Username <span
                                     class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="text" name="username"
                                 placeholder="Masukkan Username" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span
                                     class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="email" name="email"
                                 placeholder="Masukkan Email" required>
                         </div>
                         <div class="col-md-4 mb-20">
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
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor
                                 Handphone <span class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="number" name="phone_number"
                                 placeholder="Masukkan No HP" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                 Provinsi <span class="text-danger">*</span>
                             </label>
                             <div class="d-flex align-items-center flex-wrap gap-28">
                                 <select class="form-select bg-base form-select-sm w-100 select-province"
                                     name="province" required>
                                     <option value="" disabled selected>Pilih Provinsi</option>
                                 </select>
                             </div>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                 Kabupaten/Kota <span class="text-danger">*</span>
                             </label>
                             <div class="d-flex align-items-center flex-wrap gap-28">
                                 <select class="form-select bg-base form-select-sm w-100 select-city" name="city"
                                     required>
                                     <option value="" disabled selected>Pilih Kabupaten/Kota</option>
                                 </select>
                             </div>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                 Kecamatan <span class="text-danger">*</span>
                             </label>
                             <div class="d-flex align-items-center flex-wrap gap-28">
                                 <select class="form-select bg-base form-select-sm w-100 select-subdistrict"
                                     name="subdistrict"required>
                                     <option value="" disabled selected>Pilih Kecamatan</option>
                                 </select>
                             </div>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                 Kelurahan/Desa <span class="text-danger">*</span>
                             </label>
                             <div class="d-flex align-items-center flex-wrap gap-28">
                                 <select class="form-select bg-base form-select-sm w-100 select-village" name="village"
                                     required>
                                     <option value="" disabled selected>Pilih Kelurahan/Desa</option>
                                 </select>
                             </div>
                         </div>
                         @if (!$event->is_free)
                             <div class="col-md-4 mb-20">
                                 <label class="form-label fw-semibold text-primary-light text-sm mb-8">Bukti Pembayaran
                                     <span class="text-danger">*</span></label>
                                 <input type="file" accept=".pdf,.png,.jpg,.jpeg"
                                     class="form-control radius-8 bg-base" name="proof_of_payment" required>
                             </div>
                         @endif
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Alamat lengkap <span
                                     class="text-danger">*</span></label>
                             <textarea class="form-control radius-8 bg-base" name="address" placeholder="Masukkan Alamat Lengkap"></textarea>
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
