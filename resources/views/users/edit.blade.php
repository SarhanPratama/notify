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

        .card-header.bg-warning { /* Changed to bg-warning as per your latest code */
            border-bottom: 1px solid rgba(0, 0, 0, 0.125); /* Standard Bootstrap border */
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            color: #1f2d3d !important; /* Darker text for warning background for better contrast if needed */
        }
        .card-header.bg-warning .btn-outline-light {
            color: #1f2d3d; /* Darker button text/icon on warning for visibility */
            border-color: #1f2d3d;
        }
        .card-header.bg-warning .btn-outline-light:hover {
            color: #fff;
            background-color: #1f2d3d;
            border-color: #1f2d3d;
        }


        .form-label {
            font-weight: 600; /* Slightly bolder labels */
            color: #495057; /* Darker label color */
            margin-bottom: 0.5rem;
        }

        .form-control,
        select.form-control { /* Apply to select elements with .form-control */
            border: 1px solid #ced4da; /* Standard border color */
            border-radius: 0.375rem; /* Consistent border radius */
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            font-size: 0.875rem; /* Consistent font size for inputs (like BS .form-control-sm) */
            height: calc(1.5em + 0.75rem + 2px); /* Bootstrap 4 .form-control-sm height */
            padding: 0.375rem 0.75rem; /* Bootstrap 4 .form-control-sm padding */
        }


        .form-control:focus,
        select.form-control:focus {
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
            border-color: #8e1616 !important; /* Maron border for dropdown */
            border-radius: 0.375rem !important;
        }
        .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: #8e1616 !important; /* Maron for highlighted option */
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
            overflow: hidden; /* Prevent long file names from breaking layout */
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .custom-file-input:focus ~ .custom-file-label {
             border-color: #8e1616; /* Maron color */
             box-shadow: 0 0 0 0.2rem rgba(142, 22, 22, 0.25); /* Maron shadow */
        }


        /* Button Styling */
        .btn-warning.custom-update-btn { /* Custom class for warning button */
            color: #1f2d3d; /* Darker text for better contrast on warning */
            background-color: #ffc107;
            border-color: #ffc107;
            font-weight: 500;
        }
        .btn-warning.custom-update-btn:hover {
            color: #1f2d3d;
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .text-maron-heading { /* Custom class for headings */
            color: #8e1616;
        }

    </style>

    @include('layouts.breadcrumbs')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-lg">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-warning"> {{-- Changed to bg-warning --}}
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-user-edit mr-2"></i>Edit Data Karyawan
                        </h6>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-light" title="Kembali ke Daftar Karyawan">
                            Kembali
                        </a>
                    </div>

                    <div class="card-body p-4">
                        {{-- Ensure $user variable is passed to this view from the controller --}}
                        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-7 col-lg-12">
                                    <h5 class="mb-3 text-maron-heading">Informasi Pribadi</h5>
                                    <div class="form-row">
                                        <div class="form-group col-lg-6">
                                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="cth: karyawan@email.com" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="tgl_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir', $user->tgl_lahir) }}" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="telepon" class="form-label">Telepon <span class="text-danger">*</span></label>
                                            {{-- Ensure $user->telepon does not already contain +62 --}}
                                            <input type="text" class="form-control" id="telepon" name="telepon" pattern="^\+62\d{8,13}$" value="{{ old('telepon', ($user->telepon && !str_starts_with($user->telepon, '+62')) ? '+62'.ltrim($user->telepon, '0') : $user->telepon ) }}" placeholder="+628xxxxxxxxxx" required>
                                            <small class="form-text text-muted">Format: +6281234567890</small>
                                        </div>
                                        <div class="form-group col-12">
                                            <label for="alamat" class="form-label">Alamat</label>
                                            <textarea class="form-control" name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <h5 class="mb-3 text-maron-heading">Informasi Pekerjaan</h5>
                                    <div class="form-row">
                                        <div class="form-group col-lg-6">
                                            <label for="id_roles" class="form-label">Role <span class="text-danger">*</span></label>
                                            <select class="form-control select2-single" id="id_roles" name="id_roles" data-placeholder="-- Pilih Role --" required>
                                                <option></option>
                                                @foreach ($roles as $id => $name)
                                                    {{-- Assuming $userRole is a collection of role names for the user --}}
                                                    {{-- Or if $user->roles is a relationship, $user->roles->pluck('name')->toArray() --}}
                                                    <option value="{{ $id }}" {{ old('id_roles', ($userRole && in_array($name, $userRole->toArray())) ? $id : ($user->roles->contains($id) ? $id : '')) == $id ? 'selected' : '' }}>
                                                        {{ ucwords($name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="id_cabang" class="form-label">Cabang <span class="text-danger">*</span></label>
                                            <select class="form-control select2-single" id="id_cabang" name="id_cabang" data-placeholder="-- Pilih Cabang --" required>
                                                <option></option>
                                                @foreach ($cabang as $id => $nama)
                                                    <option value="{{ $id }}" {{ old('id_cabang', $user->id_cabang) == $id ? 'selected' : '' }}>
                                                        {{ ucwords($nama) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-md-5 col-lg-4">
                                    <div class="sticky-top" style="top: 20px;">
                                        <h5 class="mb-3 text-center text-maron-heading">Foto Karyawan</h5>
                                        <div class="preview-container mb-3">
                                            @if ($user->foto)
                                                <img id="fotoPreview" src="{{ asset('storage/' . $user->foto) }}" alt="Preview Foto Karyawan" class="preview-img" style="display: block;">
                                                <span id="fotoPreviewPlaceholder" class="text-muted" style="display: none;">Belum ada foto</span>
                                            @else
                                                <img id="fotoPreview" src="#" alt="Preview Foto Karyawan" class="preview-img">
                                                <span id="fotoPreviewPlaceholder" class="text-muted" style="display: block;">Belum ada foto</span>
                                            @endif
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="foto" id="fotoInput" onchange="previewImage(event); updateFileName(this);" accept="image/png, image/jpeg, image/jpg">
                                            <label class="custom-file-label" for="fotoInput" data-browse="Pilih">Pilih foto...</label>
                                        </div>
                                        <small class="form-text text-muted d-block text-center mt-1">Max. 2MB (JPG, JPEG, PNG)</small>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="row mt-4 pt-3 border-top">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="reset" class="btn btn-outline-secondary mr-2">
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-warning custom-update-btn"> {{-- Changed to btn-warning --}}
                                        Update
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
                // Don't clear if there's an existing image from the server
                // Only clear if the user selected a file then cleared the selection
                // This logic might need adjustment based on whether $user->foto exists
                const existingFotoSrc = "{{ $user->foto ? asset('storage/' . $user->foto) : '#' }}";
                if (preview.src !== existingFotoSrc && existingFotoSrc !== '#') {
                     // If current preview is not the initial server image, means user uploaded then removed
                } else if (existingFotoSrc === '#') { // No initial server image
                    preview.src = '#';
                    preview.style.display = 'none';
                    if (placeholder) placeholder.style.display = 'block';
                }
            }
        }

        function updateFileName(input) {
            var fileName = input.files[0] ? input.files[0].name : "Pilih foto...";
            var nextSibling = input.nextElementSibling;
            nextSibling.innerText = fileName;
        }

         document.addEventListener('DOMContentLoaded', function() {
            const preview = document.getElementById('fotoPreview');
            const placeholder = document.getElementById('fotoPreviewPlaceholder');
            const fotoSrc = preview.getAttribute('src');

            if (!fotoSrc || fotoSrc === '#') {
                preview.style.display = 'none';
                if (placeholder) placeholder.style.display = 'block';
            } else {
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            }

            const phoneInput = document.getElementById('telepon');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(event) {
                    let value = event.target.value;
                    value = value.replace(/[^\d+]/g, '');

                    if (value.startsWith('0')) {
                        value = '+62' + value.substring(1);
                    } else if (value.startsWith('62') && !value.startsWith('+62')) {
                         value = '+' + value;
                    } else if (value && !value.startsWith('+') && value.length > 0 && !value.startsWith('62')) {
                        value = '+62' + value;
                    } else if (value.startsWith('+') && !value.startsWith('+62')) {
                        value = '+62' + value.substring(1).replace(/\+/g, '');
                    }
                    event.target.value = value;
                });
                 // Trigger input event to format pre-filled value
                phoneInput.dispatchEvent(new Event('input'));
            }

            // if (typeof $ !== 'undefined' && $.fn.select2) {
            //     $('.select2-single').select2({
            //         theme: 'bootstrap4',
            //         allowClear: true
            //     });
            // }
        });
    </script>
@endsection
