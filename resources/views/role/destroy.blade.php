<div class="modal fade" id="roleDestroyModal" tabindex="-1" aria-labelledby="roleDestroyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 font-weight-bold" id="roleDestroyModalLabel">Konfirmasi Hapus</h1>
          <i class="bi bi-x-lg btn btn-outline-danger btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
          {{-- <button type="button" class="btn-close btn-outline-primary" data-bs-dismiss="modal" aria-label="Close"></button> --}}
        </div>
        <div class="modal-body">
            <p>Apakah anda yakin ingin menghapus role <strong id="delete_role_name"></strong>?</p>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" id="delete_id" name="id">
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
            </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('roleDestroyModal');

        deleteModal.addEventListener('show.bs.modal', function(event) {
            // Button yang trigger modal
            const button = event.relatedTarget;

            // Ambil data dari button
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');

            // Update form dan konten modal
            document.getElementById('delete_id').value = id;
            document.getElementById('delete_role_name').textContent = name;
            document.getElementById('deleteForm').action = `/admin/role/${id}`;
        });
    });
    </script>
