<div class="profile-section">
    <h5 class="mb-3 text-danger">Hapus Akun</h5>
    <p class="text-muted">Setelah akun Anda dihapus, semua data dan resource akan dihapus secara permanen.</p>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
        <i class="fas fa-trash me-2"></i> Hapus Akun
    </button>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus Akun</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus akun Anda?</p>
                    <p class="text-danger">Tindakan ini tidak dapat dibatalkan!</p>

                    <form method="POST" action="{{ route('profile.destroy') }}" id="deleteUserForm">
                        @csrf
                        @method('DELETE')

                        <div class="mb-3">
                            <label for="password" class="form-label">Masukkan Password untuk Konfirmasi</label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="deleteUserForm" class="btn btn-danger">Hapus Akun</button>
                </div>
            </div>
        </div>
    </div>
</div>
