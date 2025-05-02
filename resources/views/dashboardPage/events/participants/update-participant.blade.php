 <!-- Modal Add Uer -->
 <div class="modal fade" id="modalUpdateParticipant-{{ $participant->user->id }}" tabindex="-1"
     aria-labelledby="modalUpdateParticipantLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl modal-dialog modal-dialog-centered">
         <div class="modal-content radius-16 bg-base">
             <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                 <h1 class="modal-title fs-5" id="modalUpdateParticipantLabel">Perbarui Data Peserta</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body p-24">
                 <form id="addEventParticipantForm"
                     action="{{ route('update.eventParticipant', ['id' => $participant->id]) }}" method="POST"
                     data-table="eventParticipantTable">
                     @method('PUT')
                     @csrf
                     <div class="row">
                         <input type="hidden" name="event_id" value="{{ $event_id }}">
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Lengkap <span
                                     class="text-danger">*</span></label>
                             <input type="text" name="name" class="form-control radius-8"
                                 placeholder="Masukkan Nama Lengkap" value="{{ $participant->user->name }}" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Username <span
                                     class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="text" name="username"
                                 placeholder="Masukkan Username" value="{{ $participant->user->username }}" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span
                                     class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="email" name="email"
                                 placeholder="Masukkan Email" value="{{ $participant->user->email }}" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Password </label>
                             <div class="position-relative">
                                 <input type="password" class="form-control radius-8 bg-base" name="password"
                                     id="password" placeholder="Masukkan Password">
                                 <span
                                     class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                     data-toggle="password"></span>
                             </div>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor
                                 Handphone <span class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="number" name="phone_number"
                                 placeholder="Masukkan No HP" value="{{ $participant->user->phone_number }}" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                 Provinsi <span class="text-danger">*</span>
                             </label>
                             <div class="d-flex align-items-center flex-wrap gap-28">
                                 <select class="form-select bg-base form-select-sm w-100 select-province"
                                     name="province" data-selected="{{ $participant->user->province }}" required>
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
                                     data-selected="{{ $participant->user->city }}" required>
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
                                     name="subdistrict" data-selected="{{ $participant->user->subdistrict }}" required>
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
                                     data-selected="{{ $participant->user->village }}" required>
                                     <option value="" disabled selected>Pilih Kelurahan/Desa</option>
                                 </select>
                             </div>
                         </div>
                         @if (!$event->is_free)
                             <div class="col-md-4 mb-20">
                                 <label class="form-label fw-semibold text-primary-light text-sm mb-8">Bukti
                                     Pembayaran</label>
                                 <input type="file" accept=".pdf,.png,.jpg,.jpeg"
                                     class="form-control radius-8 bg-base" name="proof_of_payment">
                             </div>
                         @endif
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Alamat lengkap <span
                                     class="text-danger">*</span></label>
                             <textarea class="form-control radius-8 bg-base" name="address" placeholder="Masukkan Alamat Lengkap">{{ $participant->user->address }}</textarea>
                         </div>
                         <div class="modal-footer d-flex align-items-end justify-content-end gap-3 mt-24">
                             <button type="reset"
                                 class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-24 py-12 radius-8"
                                 data-bs-dismiss="modal">
                                 Batal
                             </button>
                             <button type="submit"
                                 class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                                 Perbarui
                             </button>
                         </div>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
