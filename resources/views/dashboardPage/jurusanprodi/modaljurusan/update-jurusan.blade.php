 <!-- Modal Update Uer -->
 <div class="modal fade" id="modalUpdateJurusan-{{ $jurusan->id }}" tabindex="-1"
     aria-labelledby="modalUpdateJurusanLabel" aria-hidden="true">
     <div class="modal-dialog modal-sm modal-dialog modal-dialog-centered">
         <div class="modal-content radius-16 bg-base">
             <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                 <h1 class="modal-title fs-5" id="modalUpdateJurusanLabel">Ubah Data Jurusan</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body p-24">
                 <form id="updateJurusanForm-{{ $jurusan->id }}" data-jurusan-id={{ $jurusan->id }}
                     action="{{ route('update.jurusan', $jurusan->id) }}" method="POST" data-table="jurusanTable">
                     @csrf
                     @method('PUT')
                     <div class="row">
                         <div class="col-12 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Jurusan<span
                                     class="text-danger">*</span></label>
                             <input type="text" name="nama" class="form-control radius-8"
                                 value="{{ $jurusan->nama }}" required>
                         </div>
                         <div class="col-12 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Kode Jurusan <span
                                     class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="text" name="kode_jurusan"
                                 value="{{ $jurusan->kode_jurusan }}"required>
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
