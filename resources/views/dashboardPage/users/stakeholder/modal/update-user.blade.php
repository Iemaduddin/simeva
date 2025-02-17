 <!-- Modal Update Uer -->
 <div class="modal fade" id="modalUpdateStakeholderUser-{{ $user->id }}" tabindex="-1"
     aria-labelledby="modalUpdateStakeholderUserLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
         <div class="modal-content radius-16 bg-base">
             <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                 <h1 class="modal-title fs-5" id="modalUpdateStakeholderUserLabel">Ubah Data User</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body p-24">
                 <form id="updateStakeholderUserForm-{{ $user->id }}" data-user-id={{ $user->id }}
                     action="{{ route('update.stakeholderUser', $user->id) }}" method="POST"
                     data-table="stakeholderUserTable">
                     @csrf
                     @method('PUT')
                     <div class="row">
                         <div class="col-12 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama <span
                                     class="text-danger">*</span></label>
                             <input type="text" name="name" class="form-control radius-8"
                                 value="{{ $user->name }}" required>
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
                         <div class="col-md-6 position-relative mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Password <span
                                     class="text-danger">*</span></label>
                             <input type="password" class="form-control radius-8 bg-base" name="password"
                                 id="password-{{ $user->id }}" placeholder="Masukkan Password">
                             <span
                                 class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                 data-toggle="password"></span>
                         </div>
                         <div class="col-12 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Role <span
                                     class="text-danger">*</span></label>
                             <div class="d-flex align-items-center flex-wrap gap-28">
                                 @foreach ($roles as $role)
                                     <div class="form-check checked-success d-flex align-items-center gap-2">
                                         <!-- Pastikan ID menggunakan user.id dan role.name yang terdefinisi dengan baik -->
                                         <input class="form-check-input" type="radio" name="role"
                                             id="role_{{ $user->id }}_{{ $role->name }}"
                                             value="{{ $role->name }}"
                                             {{ in_array($role->name, $user->getRoleNames()->toArray()) ? 'checked' : '' }}
                                             required>
                                         <label
                                             class="form-check-label line-height-1 fw-medium text-secondary-light text-md d-flex align-items-center gap-1"
                                             for="role_{{ $user->id }}_{{ $role->name }}">
                                             {{ $role->name }}
                                         </label>
                                     </div>
                                 @endforeach
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
