 <!-- Modal Add Uer -->
 <div class="modal fade" id="modalAddProdi" tabindex="-1" aria-labelledby="modalAddProdiLabel" aria-hidden="true">
     <div class="modal-dialog modal-sm modal-dialog modal-dialog-centered">
         <div class="modal-content radius-16 bg-base">
             <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                 <h1 class="modal-title fs-5" id="modalAddProdiLabel">Tambah Prodi</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body p-24">
                 <form id="addProdiForm" action="{{ route('add.prodi') }}" method="POST" data-table="prodiTable">
                     @csrf
                     <div class="row">
                         <div class="col-12 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Prodi<span
                                     class="text-danger">*</span></label>
                             <input type="text" name="nama_prodi" class="form-control radius-8"
                                 placeholder="Masukkan Nama Prodi" required>
                         </div>
                         <div class="col-12 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Kode Prodi<span
                                     class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="text" name="kode_prodi"
                                 placeholder="Masukkan Kode Prodi" required>
                         </div>
                         <div class="col-12 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jurusan <span
                                     class="text-danger">*</span></label>
                             <select class="form-select bg-base form-select-sm w-100" name="jurusan_id" required>
                                 <option value="" disabled selected>Pilih Jurusan</option>
                                 @foreach ($jurusans as $jurusan)
                                     <option value="{{ $jurusan->id }}"
                                         {{ isset($prodi) && $jurusan->id == $prodi->jurusan_id ? 'selected' : '' }}>
                                         {{ $jurusan->nama }} ({{ $jurusan->kode_jurusan }})
                                     </option>
                                 @endforeach
                             </select>
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
