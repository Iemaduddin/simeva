<div class="modal fade" id="assignPermissionModal-{{ $user->id }}" tabindex="-1"
    aria-labelledby="assignPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="assignPermissionModalLabel">Manajemen Permission User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form id="assignPermissionForm" data-user-id={{ $user->id }}
                    action="{{ route('assignPermissionToUser', $user->id) }}" method="POST"
                    data-table="stakeholderUserTable">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light mb-8">Nama <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control radius-8"
                                value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light mb-8">Role <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control radius-8"
                                value="{{ $user->getRoleNames()->implode(', ') }}" disabled>
                        </div>

                        @php
                            use App\Models\Permission;

                            // Ambil semua permission dan kelompokkan berdasarkan kategori utama
                            $permissions = Permission::all();
                            $groupedPermissions = [];

                            foreach ($permissions as $permission) {
                                $parts = explode('.', $permission->name, 2);
                                $subParts = explode('_', $parts[0], 2);
                                $mainCategory = ucfirst($subParts[0]); // Ambil bagian utama
                                $subCategory = isset($subParts[1]) ? ucfirst(str_replace('_', ' ', $subParts[1])) : '';
                                $action = isset($parts[1]) ? ucwords(str_replace('_', ' ', $parts[1])) : '';

                                $label = trim("{$action} {$mainCategory} {$subCategory}");

                                $groupedPermissions[$mainCategory][] = [
                                    'name' => $permission->name,
                                    'label' => $label,
                                ];
                            }
                            ksort($groupedPermissions);

                            // Ambil permission yang dimiliki user (dari role & langsung)
                            $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();

                        @endphp

                        <div class="col-md-12 mb-20">
                            <div class="row">
                                <p class="fw-semibold text-primary-light my-0">Permissions</p>
                                <hr>
                                @foreach ($groupedPermissions as $group => $perms)
                                    <div class="col-md-4">
                                        <p class="fw-bold text-md mt-3">{{ $group }}</p>
                                        @foreach ($perms as $perm)
                                            <div class="form-check style-check d-flex align-items-center my-1">
                                                <input class="form-check-input radius-4 border border-neutral-400 "
                                                    type="checkbox" name="permissions[]" value="{{ $perm['name'] }}"
                                                    id="perm_{{ Str::slug($perm['name']) }}"
                                                    {{ in_array($perm['name'], $userPermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2"
                                                    for="perm_{{ Str::slug($perm['name']) }}">
                                                    {{ $perm['label'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>




                        <div class="d-flex align-items-end justify-content-end gap-3 mt-24">
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
