 <!-- Modal Add Uer -->
 <div class="modal fade" id="modalAddJurusan" tabindex="-1" aria-labelledby="modalAddJurusanLabel" aria-hidden="true">
     <div class="modal-dialog modal-sm modal-dialog modal-dialog-centered">
         <div class="modal-content radius-16 bg-base">
             <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                 <h1 class="modal-title fs-5" id="modalAddJurusanLabel">Tambah Jurusan</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body p-24">
                 <form id="addJurusanForm" action="{{ route('add.jurusan') }}" method="POST" data-table="jurusanTable">
                     @csrf
                     <div class="row">
                         <div class="col-12 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Jurusan<span
                                     class="text-danger">*</span></label>
                             <input type="text" name="nama" class="form-control radius-8"
                                 placeholder="Masukkan Nama Jurusan" required>
                         </div>
                         <div class="col-12 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Kode jurusan<span
                                     class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="text" name="kode_jurusan"
                                 placeholder="Masukkan Kode Jurusan" required>
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
