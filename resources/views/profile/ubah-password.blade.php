    <div class="modal fade" id="changepasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header color">
                    <h5 class="modal-title text-light fw-bold" id="changePasswordModalLabel">Ubah Password</h5>
                    <i class="bi bi-x-lg btn btn-outline-light btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <form action="{{ route('user-password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-body text-sm">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <div class="input-group input-group-sm">
                                <input type="password" class="form-control" id="current_password"
                                    name="current_password" required>
                                <button class="btn toggle-password" type="button" style="background-color: #6777ef"
                                    data-target="current_password">
                                    <i class="fa fa-eye text-light" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <div class="input-group input-group-sm">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn toggle-password" type="button" style="background-color: #6777ef"
                                    data-target="password">
                                    <i class="fa fa-eye text-light" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="form-text">Password minimal 8 karakter</div>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="input-group input-group-sm">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                                <button class="btn toggle-password" type="button" style="background-color: #6777ef"
                                    data-target="password_confirmation">
                                    <i class="fa fa-eye text-light"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
