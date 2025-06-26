@extends('layouts.master')

@section('content')
    <style>
        /* General Styling */
        body {
            background-color: #f4f6f9; /* Light gray background for the page */
        }

        .card {
            border: none; /* Remove default card border, rely on shadow */
            border-radius: 0.75rem; /* Softer border radius */
        }

        .card-header.bg-maron {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2); /* Softer border for header */
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        .form-label {
            font-weight: 600; /* Slightly bolder labels */
            color: #495057; /* Darker label color */
            margin-bottom: 0.5rem;
        }

        .form-control, /* Default size, equivalent to BS sm due to font-size */
        .form-select { /* Assuming .form-select is a custom class or BS5, for BS4 use .form-control for selects */
            border: 1px solid #ced4da; /* Standard border color */
            border-radius: 0.375rem; /* Consistent border radius */
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            font-size: 0.875rem; /* Consistent font size for inputs (like BS .form-control-sm) */
            height: calc(1.5em + 0.75rem + 2px); /* Bootstrap 4 .form-control-sm height */
            padding: 0.375rem 0.75rem; /* Bootstrap 4 .form-control-sm padding */
        }
        /* Ensure selects also get this base styling if not using .form-control */
        select.form-control { /* Apply to select elements with .form-control */
             height: calc(1.5em + 0.75rem + 2px);
        }


        .form-control:focus,
        select.form-control:focus { /* Added select.form-control here */
            border-color: #8e1616; /* Maron color for focus */
            box-shadow: 0 0 0 0.2rem rgba(142, 22, 22, 0.25); /* Maron shadow for focus */
        }

        /* Specific for Select2 if used with Bootstrap 4 theme */
        .select2-container--bootstrap4 .select2-selection--single {
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            height: calc(1.5em + 0.75rem + 2px) !important; /* Match form-control height */
            padding: 0.375rem 0.75rem !important;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 1.5 !important;
            padding-left: 0 !important;
            color: #495057 !important; /* Match form text color */
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 0.75rem) !important;
            right: 0.5rem !important;
        }
        .select2-container--bootstrap4.select2-container--focus .select2-selection--single {
            border-color: #8e1616 !important;
            box-shadow: 0 0 0 0.2rem rgba(142, 22, 22, 0.25) !important;
        }
        .select2-container--bootstrap4 .select2-dropdown {
            border-color: #8e1616 !important;
            border-radius: 0.375rem !important;
        }
        .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: #8e1616 !important;
            color: white !important;
        }


        /* Image Preview Styling */
        .preview-container {
            background-color: #f8f9fa;
            border: 2px dashed #ced4da; /* Dashed border */
            border-radius: 0.5rem; /* Consistent border radius */
            padding: 1rem;
            transition: border-color 0.3s ease-in-out;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 220px; /* Ensure a minimum height */
            position: relative; /* For placeholder positioning */
        }

        .preview-container:hover {
            border-color: #8e1616; /* Maron color on hover */
        }

        .preview-img {
            max-width: 200px; /* Max width */
            max-height: 200px; /* Max height */
            object-fit: cover;
            border: 3px solid #ffffff; /* White border around image */
            border-radius: 0.375rem; /* Consistent border radius */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            transition: transform 0.3s ease-in-out;
        }
        .preview-img:hover {
            transform: scale(1.03);
        }
        .preview-img[src=""],
        .preview-img[src="#"] { /* Style for when src is empty or placeholder */
            display: none;
        }
        #fotoPreviewPlaceholder {
            color: #6c757d; /* Muted text color */
            font-style: italic;
        }


        /* File Input Styling for Bootstrap 4 */
        .custom-file-input ~ .custom-file-label::after {
            content: "Pilih File"; /* Localize browse button text */
        }
        .custom-file-label {
            font-size: 0.875rem; /* Match other inputs */
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            line-height: 1.5;
            border-radius: 0.375rem;
        }
        .custom-file-input:focus ~ .custom-file-label {
             border-color: #8e1616;
             box-shadow: 0 0 0 0.2rem rgba(142, 22, 22, 0.25);
        }


        /* Button Styling */
        .btn-maron {
            background-color: #8e1616;
            color: #fff;
            border-color: #8e1616;
            font-weight: 500;
        }
        .btn-maron:hover {
            background-color: #7a1313;
            color: #fff;
            border-color: #6d1111;
        }

        .text-sm {
            font-size: 0.875rem !important;
        }
    </style>

    @include('layouts.breadcrumbs')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-lg">
                    <div class="card-header py-3 text-white d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-user-edit mr-2"></i>Tambah Data Karyawan
                        </h6>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-light" title="Kembali ke Daftar Karyawan">
                            Kembali
                        </a>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-7 col-lg-8">
                                    <h5 class="mb-3" style="color: #8e1616;">Informasi Pribadi</h5>
                                    <div class="form-row">
                                        <div class="form-group col-lg-6">
                                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama lengkap" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="cth: karyawan@email.com" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="tgl_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="telepon" class="form-label">Telepon <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="telepon" name="telepon" pattern="^\+62\d{8,13}$" placeholder="+628xxxxxxxxxx" required>
                                            <small class="form-text text-muted">Format: +6281234567890</small>
                                        </div>
                                        <div class="form-group col-12">
                                            <label for="alamat" class="form-label">Alamat</label>
                                            <textarea class="form-control" name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <h5 class="mb-3" style="color: #8e1616;">Informasi Pekerjaan</h5>
                                    <div class="form-row">
                                        <div class="form-group col-lg-6">
                                            <label for="id_roles" class="form-label">Role <span class="text-danger">*</span></label>
                                            {{-- For BS4, select should have .form-control. If using select2, it will be styled by select2. --}}
                                            <select class="form-control select2-single" id="id_roles" name="id_roles" data-placeholder="-- Pilih Role --" required>
                                                <option></option> {{-- Empty option for placeholder with Select2 --}}
                                                @foreach ($roles as $id => $name)
                                                    <option value="{{ $id }}">{{ ucwords($name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="id_cabang" class="form-label">Cabang <span class="text-danger">*</span></label>
                                            <select class="form-control select2-single" id="id_cabang" name="id_cabang" data-placeholder="-- Pilih Cabang --" required>
                                                <option></option> {{-- Empty option for placeholder with Select2 --}}
                                                @foreach ($cabang as $id => $nama)
                                                    <option value="{{ $id }}">{{ ucwords($nama) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5 col-lg-4">
                                    <div class="sticky-top" style="top: 20px;">
                                        <h5 class="mb-3 text-center" style="color: #8e1616;">Foto Karyawan</h5>
                                        <div class="preview-container mb-3">
                                            <img id="fotoPreview" src="#" alt="Preview Foto Karyawan" class="preview-img">
                                            <span id="fotoPreviewPlaceholder" class="text-muted">Belum ada foto</span>
                                        </div>
                                        {{-- Bootstrap 4 Custom File Input --}}
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="foto" id="fotoInput" onchange="previewImage(event); updateFileName(this);" accept="image/png, image/jpeg, image/jpg">
                                            <label class="custom-file-label" for="fotoInput" data-browse="Pilih">Pilih foto...</label>
                                        </div>
                                        <small class="form-text text-muted d-block text-center mt-1">Max. 2MB (JPG, JPEG, PNG)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4 pt-3 border-top">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="reset" class="btn btn-outline-secondary mr-2">
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-maron">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to preview image
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('fotoPreview');
            const placeholder = document.getElementById('fotoPreviewPlaceholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#'; // Clear preview
                preview.style.display = 'none';
                if (placeholder) placeholder.style.display = 'block';
            }
        }

        // Function to update Bootstrap 4 custom file input label
        function updateFileName(input) {
            var fileName = input.files[0] ? input.files[0].name : "Pilih foto...";
            var nextSibling = input.nextElementSibling;
            nextSibling.innerText = fileName;
        }

         // Initialize preview state on page load
         document.addEventListener('DOMContentLoaded', function() {
            const preview = document.getElementById('fotoPreview');
            const placeholder = document.getElementById('fotoPreviewPlaceholder');
            if (!preview.getAttribute('src') || preview.getAttribute('src') === '#') {
                preview.style.display = 'none';
                if (placeholder) placeholder.style.display = 'block';
            } else {
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            }

            // Phone number auto-prefix
            const phoneInput = document.getElementById('telepon');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(event) {
                    let value = event.target.value;
                    value = value.replace(/[^\d+]/g, ''); // Allow only digits and '+'

                    if (value.startsWith('0')) { // If starts with 0, replace with +62
                        value = '+62' + value.substring(1);
                    } else if (value.startsWith('62')) { // If starts with 62, ensure + is there
                         value = '+' + value;
                    } else if (value && !value.startsWith('+') && value.length > 0) { // If doesn't start with +, add +62
                        value = '+62' + value;
                    } else if (value.startsWith('+') && !value.startsWith('+62')) { // If starts with + but not +62, correct it
                        value = '+62' + value.substring(1);
                    }
                    event.target.value = value;
                });
            }

            // Initialize Select2 if you are using it (ensure jQuery and Select2 are loaded)
            // if (typeof $ !== 'undefined' && $.fn.select2) {
            //     $('.select2-single').select2({
            //         theme: 'bootstrap4', // Use Bootstrap 4 theme for Select2
            //         // placeholder: $(this).data('placeholder'), // Already set by data-placeholder
            //         allowClear: true // If you want a clear button
            //     });
            // }
        });
    </script>
@endsection
