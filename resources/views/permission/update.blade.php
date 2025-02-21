<div class="modal fade" id="permissionUpdateModal" tabindex="-1" aria-labelledby="permissionUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 font-weight-bold" id="permissionUpdateModalLabel">Form Update role</h1>
          <i class="bi bi-x-lg btn btn-outline-danger btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
          {{-- <button type="button" class="btn-close btn-outline-primary" data-bs-dismiss="modal" aria-label="Close"></button> --}}
        </div>
        <form id="updateForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="modal_id" name="id">
            <div class="modal-body text-sm">
                <div class="mb-3">
                    <label for="cabang">Permission</label>
                    <input type="text" name="name" class="form-control form-control-sm" id="modal_name"  placeholder="Masukkan permission" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-outline-primary btn-sm">Update</button>
            </div>
        </form>
      </div>
    </div>
  </div>

  <script>
document.addEventListener("DOMContentLoaded", function () {
    // Ubah selector sesuai dengan class yang ada di button
    const editButtons = document.querySelectorAll(".btn-outline-warning");
    const modalName = document.getElementById("modal_name");
    const modalId = document.getElementById("modal_id");
    const form = document.getElementById("updateForm");

    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Debugging untuk melihat data
            console.log("ID:", this.dataset.id);
            console.log("Name:", this.dataset.name);

            modalId.value = this.dataset.id;
            modalName.value = this.dataset.name;

            // Set action form
            form.action = `/admin/permission/${this.dataset.id}`;
        });
    });
});
</script>

