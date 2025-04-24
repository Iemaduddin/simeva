 <!-- Modal Add Uer -->
 <div class="modal fade" id="modalAddTeamMember" tabindex="-1" aria-labelledby="modalAddTeamMemberLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog modal-dialog-centered">
         <div class="modal-content radius-16 bg-base">
             <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                 <h1 class="modal-title fs-5" id="modalAddTeamMemberLabel">Tambah Anggota Tim</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body p-24">
                 <form id="addTeamMemberForm" action="{{ route('add.team-member') }}" method="POST"
                     data-table="{{ $shorten_name }}-TeamMembersTable">
                     @csrf
                     <div class="row">
                         <div class="col-12 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Anggota<span
                                     class="text-danger">*</span></label>
                             <input type="text" name="name" class="form-control radius-8"
                                 placeholder="Masukkan Nama Anggota" required>
                         </div>
                         <div class="col-12 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tingkatan<span
                                     class="text-danger">*</span></label>
                             <select class="form-select" name="level" required>
                                 <option disabled selected>Pilih Tingkatan</option>
                                 <option value="SC">Steering Committee</option>
                                 <option value="OC">Organizing Committee</option>
                             </select>
                         </div>
                         <div class="col-12 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jabatan<span
                                     class="text-danger">*</span></label>
                             <input type="text" name="position" class="form-control radius-8"
                                 placeholder="Masukkan Jabatan" required>
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
