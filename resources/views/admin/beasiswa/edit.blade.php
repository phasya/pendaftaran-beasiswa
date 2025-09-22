@extends('layouts.admin')

@section('title', 'Edit Beasiswa')

@section('content')
    <style>
        /* Status Badge Colors */
        .bg-success-soft {
            background-color: #d1edff !important;
        }

        .bg-warning-soft {
            background-color: #fff3cd !important;
        }

        .bg-danger-soft {
            background-color: #f8d7da !important;
        }

        .bg-info-soft {
            background-color: #d1ecf1 !important;
        }

        .bg-secondary-soft {
            background-color: #e2e3e5 !important;
        }

        /* Form sections */
        .form-section {
            border-left: 4px solid var(--mint-primary, #00c9a7);
            padding-left: 1rem;
            margin-left: 0.5rem;
        }

        .section-title {
            font-weight: 600;
            color: #495057;
            font-size: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        /* Dynamic item styling */
        .dynamic-item {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .dynamic-item:hover {
            border-color: #007bff;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.1);
        }

        .item-number {
            font-weight: bold;
            color: #007bff;
        }

        /* Auto-generated key display */
        .auto-key-display {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 8px 12px;
            font-family: 'Courier New', monospace;
            color: #6c757d;
            font-size: 0.9em;
        }

        /* Animation classes */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dynamic-item.new-item {
            animation: slideIn 0.5s ease-out;
        }

        /* Changes indicator */
        .changed-field {
            border-left: 4px solid #fbbf24 !important;
            background-color: #fef3c7 !important;
        }
    </style>

    <div class="container-fluid px-4 py-3">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2"><i class="fas fa-edit"></i> Edit Beasiswa</h1>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle me-2"></i>Perbarui informasi beasiswa yang sudah ada
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-graduation-cap text-warning me-2"></i>Form Edit Beasiswa
                                </h6>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-warning-soft text-warning">
                                    <i class="fas fa-edit me-1" style="font-size: 8px;"></i>Edit Mode
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.beasiswa.update', $beasiswa->id) }}" method="POST" id="beasiswaForm">
                            @csrf
                            @method('PUT')

                            <!-- Form Section 1: Basic Information -->
                            <div class="form-section mb-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-info-circle text-primary me-2"></i>Informasi Dasar
                                </h6>

                                <div class="mb-3">
                                    <label for="nama_beasiswa" class="form-label fw-semibold">
                                        <i class="fas fa-trophy text-warning me-2"></i>Nama Beasiswa
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nama_beasiswa') is-invalid @enderror"
                                        id="nama_beasiswa" name="nama_beasiswa"
                                        value="{{ old('nama_beasiswa', $beasiswa->nama_beasiswa) }}"
                                        placeholder="Contoh: Beasiswa Prestasi Akademik 2025" required>
                                    @error('nama_beasiswa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label fw-semibold">
                                        <i class="fas fa-align-left text-info me-2"></i>Deskripsi Beasiswa
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                        name="deskripsi" rows="4"
                                        placeholder="Jelaskan tujuan, target penerima, dan manfaat beasiswa ini..."
                                        required>{{ old('deskripsi', $beasiswa->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Form Section 2: Financial & Schedule -->
                            <div class="form-section mb-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-calendar-dollar text-success me-2"></i>Dana & Jadwal
                                </h6>

                                <div class="mb-3">
                                    <label for="jumlah_dana" class="form-label fw-semibold">
                                        <i class="fas fa-money-bill-wave text-success me-2"></i>Jumlah Dana
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('jumlah_dana') is-invalid @enderror"
                                            id="jumlah_dana" name="jumlah_dana"
                                            value="{{ old('jumlah_dana', $beasiswa->jumlah_dana) }}" min="0" step="100000"
                                            placeholder="5000000" required>
                                        @error('jumlah_dana')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_buka" class="form-label fw-semibold">
                                            <i class="fas fa-calendar-plus text-success me-2"></i>Tanggal Buka
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control @error('tanggal_buka') is-invalid @enderror"
                                            id="tanggal_buka" name="tanggal_buka"
                                            value="{{ old('tanggal_buka', \Carbon\Carbon::parse($beasiswa->tanggal_buka)->format('Y-m-d')) }}"
                                            required>
                                        @error('tanggal_buka')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_tutup" class="form-label fw-semibold">
                                            <i class="fas fa-calendar-times text-danger me-2"></i>Tanggal Tutup
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control @error('tanggal_tutup') is-invalid @enderror"
                                            id="tanggal_tutup" name="tanggal_tutup"
                                            value="{{ old('tanggal_tutup', \Carbon\Carbon::parse($beasiswa->tanggal_tutup)->format('Y-m-d')) }}"
                                            required>
                                        @error('tanggal_tutup')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Form Section 3: Dynamic Form Fields -->
                            <div class="form-section mb-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-wpforms text-primary me-2"></i>Konfigurasi Form Pendaftaran
                                </h6>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label fw-semibold mb-0">
                                            <i class="fas fa-list text-warning me-2"></i>Atur Field Form
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-success me-2"
                                                id="loadDefaultFieldsBtn">
                                                <i class="fas fa-magic me-2"></i>Muat Default
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="addFieldBtn">
                                                <i class="fas fa-plus me-2"></i>Tambah Field
                                            </button>
                                        </div>
                                    </div>

                                    <div id="formFieldsContainer" class="dynamic-container"
                                        style="max-height: 600px; overflow-y: auto;">
                                        <!-- Form fields will be added here dynamically -->
                                    </div>

                                    <div id="formFieldsEmpty" class="text-center py-5 text-muted" style="display: none;">
                                        <i class="fas fa-wpforms fa-3x mb-3 opacity-50"></i>
                                        <p class="mb-2">Belum ada field yang ditambahkan</p>
                                        <small>Klik "Tambah Field" atau "Muat Default" untuk memulai</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Section 4: Required Documents -->
                            <div class="form-section mb-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-file-upload text-primary me-2"></i>Dokumen yang Diperlukan
                                </h6>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label fw-semibold mb-0">
                                            <i class="fas fa-folder text-warning me-2"></i>Atur Dokumen Pendaftaran
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-success me-2"
                                                id="loadDefaultDocsBtn">
                                                <i class="fas fa-magic me-2"></i>Muat Default
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                id="addDocumentBtn">
                                                <i class="fas fa-plus me-2"></i>Tambah Dokumen
                                            </button>
                                        </div>
                                    </div>

                                    <div id="documentsContainer" class="dynamic-container"
                                        style="max-height: 600px; overflow-y: auto;">
                                        <!-- Documents will be added here dynamically -->
                                    </div>

                                    <div id="documentsEmpty" class="text-center py-5 text-muted" style="display: none;">
                                        <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                                        <p class="mb-2">Belum ada dokumen yang ditambahkan</p>
                                        <small>Klik "Tambah Dokumen" atau "Muat Default" untuk memulai</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Section 5: Status & Requirements -->
                            <div class="form-section mb-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-cogs text-warning me-2"></i>Status & Persyaratan
                                </h6>

                                <div class="mb-3">
                                    <label for="status" class="form-label fw-semibold">
                                        <i class="fas fa-toggle-on text-primary me-2"></i>Status Beasiswa
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="aktif" {{ old('status', $beasiswa->status) == 'aktif' ? 'selected' : '' }}>Aktif (Bisa dilamar)</option>
                                        <option value="nonaktif" {{ old('status', $beasiswa->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif (Tidak bisa dilamar)</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="persyaratan" class="form-label fw-semibold">
                                        <i class="fas fa-list-check text-info me-2"></i>Persyaratan Pendaftaran
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('persyaratan') is-invalid @enderror"
                                        id="persyaratan" name="persyaratan" rows="8"
                                        placeholder="Contoh:&#10;1. Mahasiswa aktif semester 3 ke atas&#10;2. IPK minimal 3.0&#10;3. Tidak sedang menerima beasiswa lain"
                                        required>{{ old('persyaratan', $beasiswa->persyaratan) }}</textarea>
                                    @error('persyaratan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Hidden inputs for dynamic data -->
                            <input type="hidden" name="form_fields_json" id="form_fields_json">
                            <input type="hidden" name="required_documents_json" id="required_documents_json">

                            <!-- Action Buttons -->
                            <div class="form-actions bg-light p-4 rounded-3 mt-4">
                                <div
                                    class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <small>Perubahan akan mempengaruhi data yang sudah ada</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.beasiswa.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Kembali
                                        </a>
                                        <button type="button" class="btn btn-outline-warning" id="resetFormBtn">
                                            <i class="fas fa-undo me-2"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-save me-2"></i>Update Beasiswa
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Field Template (Hidden) -->
    <template id="formFieldTemplate">
        <div class="dynamic-item card mb-3 border-1">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0 text-dark fw-bold">
                        Field <span class="item-number">1</span>
                    </h6>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-field-btn" title="Hapus field">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Nama Field <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control field-name" placeholder="Contoh: Nama Lengkap" required>
                        <small class="form-text text-muted">Label yang akan ditampilkan di form</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Key Field (Auto Generated)</label>
                        <div class="auto-key-display field-key-display">akan_dibuat_otomatis</div>
                        <small class="form-text text-muted">Key otomatis dibuat dari nama field</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tipe Field <span class="text-danger">*</span></label>
                        <select class="form-select field-type" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="text">Text Input</option>
                            <option value="email">Email</option>
                            <option value="number">Number</option>
                            <option value="date">Date</option>
                            <option value="textarea">Textarea</option>
                            <option value="select">Select/Dropdown</option>
                            <option value="radio">Radio Button</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="tel">Telephone</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Icon <span class="text-danger">*</span></label>
                        <select class="form-select field-icon" required>
                            <option value="">-- Pilih Icon --</option>
                            <optgroup label="Personal">
                                <option value="fas fa-user">User</option>
                                <option value="fas fa-id-card">ID Card</option>
                                <option value="fas fa-envelope">Email</option>
                                <option value="fas fa-phone">Phone</option>
                                <option value="fas fa-home">Address</option>
                            </optgroup>
                            <optgroup label="Academic">
                                <option value="fas fa-graduation-cap">Academic</option>
                                <option value="fas fa-university">University</option>
                                <option value="fas fa-book">Book</option>
                                <option value="fas fa-chart-line">Chart</option>
                                <option value="fas fa-calendar-check">Calendar</option>
                            </optgroup>
                            <optgroup label="General">
                                <option value="fas fa-info-circle">Info</option>
                                <option value="fas fa-comment-alt">Comment</option>
                                <option value="fas fa-list">List</option>
                                <option value="fas fa-check-circle">Check</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-semibold">Placeholder</label>
                        <input type="text" class="form-control field-placeholder" placeholder="Masukkan placeholder...">
                        <small class="form-text text-muted">Teks bantuan yang muncul di field</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Posisi <span class="text-danger">*</span></label>
                        <select class="form-select field-position" required>
                            <option value="">-- Pilih --</option>
                            <option value="personal">Data Personal</option>
                            <option value="academic">Data Akademik</option>
                            <option value="additional">Data Tambahan</option>
                        </select>
                    </div>
                </div>

                <!-- Options untuk Select/Radio/Checkbox -->
                <div class="field-options-section" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Opsi Pilihan <span class="text-danger">*</span></label>
                        <div class="field-options-container">
                            <!-- Options will be added dynamically -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary add-option-btn">
                            <i class="fas fa-plus me-1"></i>Tambah Opsi
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-semibold">Validasi</label>
                        <input type="text" class="form-control field-validation" placeholder="min:3|max:50">
                        <small class="form-text text-muted">Aturan validasi (contoh: required|min:3|max:50)</small>
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input field-required" type="checkbox" checked>
                            <label class="form-check-label fw-semibold">Field Wajib</label>
                        </div>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="mt-3 p-3 bg-light rounded border">
                    <h6 class="text-secondary mb-2 fw-semibold">Preview</h6>
                    <div class="field-preview">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-user text-primary me-2"></i>Nama Field <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" placeholder="Placeholder..." disabled>
                        <small class="form-text text-muted">Posisi: Data Personal</small>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Document Template (Hidden) -->
    <template id="documentTemplate">
        <div class="dynamic-item card mb-3 border-1">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0 text-dark fw-bold">
                        Dokumen <span class="item-number">1</span>
                    </h6>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-document-btn"
                            title="Hapus dokumen">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control document-name" placeholder="Contoh: Transkrip Nilai"
                            required>
                        <small class="form-text text-muted">Nama yang akan ditampilkan kepada pendaftar</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Key (Auto Generated)</label>
                        <div class="auto-key-display document-key-display">file_akan_dibuat_otomatis</div>
                        <small class="form-text text-muted">Key otomatis dibuat dari nama dokumen</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Icon <span class="text-danger">*</span></label>
                        <select class="form-select document-icon" required>
                            <option value="">-- Pilih Icon --</option>
                            <optgroup label="Documents">
                                <option value="fas fa-file-pdf">PDF Document</option>
                                <option value="fas fa-file-image">Image File</option>
                                <option value="fas fa-file-alt">Text Document</option>
                                <option value="fas fa-file-contract">Contract</option>
                            </optgroup>
                            <optgroup label="Identity">
                                <option value="fas fa-id-card">ID Card</option>
                                <option value="fas fa-passport">Passport</option>
                            </optgroup>
                            <optgroup label="Academic">
                                <option value="fas fa-graduation-cap">Academic</option>
                                <option value="fas fa-certificate">Certificate</option>
                                <option value="fas fa-award">Award</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Warna Icon <span class="text-danger">*</span></label>
                        <select class="form-select document-color" required>
                            <option value="">-- Pilih Warna --</option>
                            <option value="red">Merah</option>
                            <option value="blue">Biru</option>
                            <option value="green">Hijau</option>
                            <option value="yellow">Kuning</option>
                            <option value="purple">Ungu</option>
                            <option value="orange">Orange</option>
                            <option value="teal">Teal</option>
                            <option value="gray">Abu-abu</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-semibold">Format File <span class="text-danger">*</span></label>
                        <div class="format-checkboxes border p-3 rounded bg-light">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input document-format" type="checkbox" value="jpg">
                                        <label class="form-check-label">JPG</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input document-format" type="checkbox" value="jpeg">
                                        <label class="form-check-label">JPEG</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input document-format" type="checkbox" value="png">
                                        <label class="form-check-label">PNG</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input document-format" type="checkbox" value="pdf">
                                        <label class="form-check-label">PDF</label>
                                    </div>
                                </div>
                            </div>
                            <small class="form-text text-muted mt-2">Pilih minimal 1 format file yang diperbolehkan</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Ukuran Max (MB) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control document-max-size" min="1" max="10" value="5" required>
                            <span class="input-group-text">MB</span>
                        </div>
                        <small class="form-text text-muted">Maksimal 10MB</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <input type="text" class="form-control document-description"
                            placeholder="Deskripsi singkat dokumen (opsional)">
                        <small class="form-text text-muted">Keterangan tambahan untuk membantu pendaftar</small>
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input document-required" type="checkbox" checked>
                            <label class="form-check-label fw-semibold">Dokumen Wajib</label>
                        </div>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="mt-3 p-3 bg-light rounded border">
                    <h6 class="text-secondary mb-2 fw-semibold">Preview</h6>
                    <div class="document-preview d-flex align-items-center">
                        <div class="preview-icon me-3">
                            <i class="fas fa-file text-secondary" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h6 class="preview-name mb-1 text-dark">Nama Dokumen</h6>
                            <small class="preview-details text-muted">Format: - | Max: 5MB</small>
                            <div class="preview-required">
                                <span class="badge bg-warning text-dark">Wajib</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let fieldCounter = 0;
            let documentCounter = 0;
            let formFieldsData = [];
            let documentsData = [];
            let usedFieldKeys = new Set();
            let usedDocumentKeys = new Set();

            const formFieldsContainer = document.getElementById('formFieldsContainer');
            const formFieldsEmpty = document.getElementById('formFieldsEmpty');
            const addFieldBtn = document.getElementById('addFieldBtn');
            const loadDefaultFieldsBtn = document.getElementById('loadDefaultFieldsBtn');

            const documentsContainer = document.getElementById('documentsContainer');
            const documentsEmpty = document.getElementById('documentsEmpty');
            const addDocumentBtn = document.getElementById('addDocumentBtn');
            const loadDefaultDocsBtn = document.getElementById('loadDefaultDocsBtn');

            const resetFormBtn = document.getElementById('resetFormBtn');
            const beasiswaForm = document.getElementById('beasiswaForm');

            // Load existing data from server-side
            const existingFormFields = @json($beasiswa->form_fields ?? []);
            const existingDocuments = @json($beasiswa->required_documents ?? []);

            // Initialize data arrays
            if (existingFormFields && Array.isArray(existingFormFields) && existingFormFields.length > 0) {
                formFieldsData = existingFormFields.map(field => ({
                    name: field.name || '',
                    key: field.key || '',
                    type: field.type || '',
                    icon: field.icon || '',
                    placeholder: field.placeholder || '',
                    position: field.position || '',
                    options: field.options || [],
                    validation: field.validation || '',
                    required: field.required !== false
                }));
                formFieldsData.forEach(field => {
                    if (field.key) usedFieldKeys.add(field.key);
                });
            }

            if (existingDocuments && Array.isArray(existingDocuments) && existingDocuments.length > 0) {
                documentsData = existingDocuments.map(doc => ({
                    name: doc.name || '',
                    key: doc.key || '',
                    icon: doc.icon || '',
                    color: doc.color || '',
                    formats: Array.isArray(doc.formats) ? doc.formats : (typeof doc.formats === 'string' ? doc.formats.split(',') : ['pdf']),
                    max_size: parseInt(doc.max_size || 5),
                    description: doc.description || '',
                    required: doc.required !== false
                }));
                documentsData.forEach(d => {
                    if (d.key) usedDocumentKeys.add(d.key);
                });
            }

            // Utility functions
            function generateUniqueFieldKey(name) {
                let baseKey = name.toLowerCase()
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, '_')
                    .substring(0, 30);
                if (!baseKey) baseKey = 'field';

                let key = baseKey;
                let counter = 1;
                while (usedFieldKeys.has(key)) {
                    key = baseKey + '_' + counter;
                    counter++;
                }
                return key;
            }

            function generateUniqueDocumentKey(name) {
                let baseKey = name.toLowerCase()
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, '_')
                    .substring(0, 25);
                if (!baseKey) baseKey = 'dokumen';

                let key = 'file_' + baseKey;
                let counter = 1;
                while (usedDocumentKeys.has(key)) {
                    key = 'file_' + baseKey + '_' + counter;
                    counter++;
                }
                return key;
            }

            function removeFromUsedKeys(key, isDocument = false) {
                if (!key) return;
                if (isDocument) {
                    usedDocumentKeys.delete(key);
                } else {
                    usedFieldKeys.delete(key);
                }
            }

            // Form fields management
            function addFormField(data = null, index = null) {
                fieldCounter++;
                const template = document.getElementById('formFieldTemplate');
                const clone = template.content.cloneNode(true);

                const dataIndex = (index !== null) ? index : formFieldsData.length;

                // Set numbering
                clone.querySelector('.item-number').textContent = fieldCounter;
                const fieldElement = clone.querySelector('.dynamic-item');
                fieldElement.setAttribute('data-index', dataIndex);

                // Append to DOM first
                formFieldsContainer.appendChild(clone);

                // Get the actual DOM element that was just added
                const addedElement = formFieldsContainer.lastElementChild;

                // Setup events
                setupFormFieldEvents(addedElement, dataIndex);

                // Fill data if provided
                if (data) {
                    fillFormFieldData(addedElement, data);
                    if (data.key && !usedFieldKeys.has(data.key)) {
                        usedFieldKeys.add(data.key);
                    }
                }

                // Add to data array if new
                const newFieldData = data || {
                    name: '', key: '', type: '', icon: '', placeholder: '',
                    position: '', options: [], validation: '', required: true
                };

                if (!data) {
                    formFieldsData.push(newFieldData);
                }

                updateFormFieldsVisibility();

                // Animation and scroll
                if (addedElement) {
                    addedElement.classList.add('new-item');
                    setTimeout(() => addedElement.classList.remove('new-item'), 500);
                    addedElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            function setupFormFieldEvents(fieldElement, index) {
                const nameInput = fieldElement.querySelector('.field-name');
                const keyDisplay = fieldElement.querySelector('.field-key-display');
                const typeSelect = fieldElement.querySelector('.field-type');
                const iconSelect = fieldElement.querySelector('.field-icon');
                const placeholderInput = fieldElement.querySelector('.field-placeholder');
                const positionSelect = fieldElement.querySelector('.field-position');
                const validationInput = fieldElement.querySelector('.field-validation');
                const requiredCheckbox = fieldElement.querySelector('.field-required');
                const removeBtn = fieldElement.querySelector('.remove-field-btn');
                const optionsSection = fieldElement.querySelector('.field-options-section');
                const addOptionBtn = fieldElement.querySelector('.add-option-btn');
                const optionsContainer = fieldElement.querySelector('.field-options-container');

                function updateFormFieldData() {
                    if (typeof formFieldsData[index] === 'undefined') return;

                    // Auto-generate key if name changed and no key exists
                    if (nameInput && nameInput.value && nameInput.value !== formFieldsData[index].name) {
                        if (!formFieldsData[index].key) {
                            formFieldsData[index].key = generateUniqueFieldKey(nameInput.value);
                            usedFieldKeys.add(formFieldsData[index].key);
                            if (keyDisplay) keyDisplay.textContent = formFieldsData[index].key;
                        }
                    }

                    // Update all field data
                    formFieldsData[index].name = nameInput ? nameInput.value : '';
                    formFieldsData[index].type = typeSelect ? typeSelect.value : '';
                    formFieldsData[index].icon = iconSelect ? iconSelect.value : '';
                    formFieldsData[index].placeholder = placeholderInput ? placeholderInput.value : '';
                    formFieldsData[index].position = positionSelect ? positionSelect.value : '';
                    formFieldsData[index].validation = validationInput ? validationInput.value : '';
                    formFieldsData[index].required = requiredCheckbox ? requiredCheckbox.checked : false;

                    // Update options for select/radio/checkbox
                    if (['select', 'radio', 'checkbox'].includes(formFieldsData[index].type)) {
                        const optionInputs = fieldElement.querySelectorAll('.option-value');
                        const optionLabels = fieldElement.querySelectorAll('.option-label');
                        formFieldsData[index].options = [];

                        optionInputs.forEach((input, idx) => {
                            const label = optionLabels[idx];
                            if (input && input.value && label && label.value) {
                                formFieldsData[index].options.push({
                                    value: input.value,
                                    label: label.value
                                });
                            }
                        });
                    } else {
                        formFieldsData[index].options = [];
                    }
                }

                function toggleOptionsSection() {
                    const needsOptions = ['select', 'radio', 'checkbox'].includes(typeSelect.value);
                    optionsSection.style.display = needsOptions ? 'block' : 'none';

                    if (needsOptions && !fieldElement.querySelector('.option-value')) {
                        addOption();
                    }
                }

                function addOption(value = '', label = '') {
                    const optionDiv = document.createElement('div');
                    optionDiv.className = 'input-group mb-2';
                    optionDiv.innerHTML = `
                    <input type="text" class="form-control option-value" placeholder="Nilai opsi" value="${value}">
                    <input type="text" class="form-control option-label" placeholder="Label opsi" value="${label}">
                    <button type="button" class="btn btn-outline-danger remove-option-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                `;

                    optionsContainer.appendChild(optionDiv);

                    // Add event listeners to new option inputs
                    const valueInput = optionDiv.querySelector('.option-value');
                    const labelInput = optionDiv.querySelector('.option-label');
                    const removeBtn = optionDiv.querySelector('.remove-option-btn');

                    valueInput.addEventListener('input', updateFormFieldData);
                    labelInput.addEventListener('input', updateFormFieldData);
                    removeBtn.addEventListener('click', () => {
                        optionDiv.remove();
                        updateFormFieldData();
                    });
                }

                // Attach event listeners
                if (nameInput) nameInput.addEventListener('input', updateFormFieldData);
                if (typeSelect) {
                    typeSelect.addEventListener('change', () => {
                        toggleOptionsSection();
                        updateFormFieldData();
                    });
                }
                if (iconSelect) iconSelect.addEventListener('change', updateFormFieldData);
                if (placeholderInput) placeholderInput.addEventListener('input', updateFormFieldData);
                if (positionSelect) positionSelect.addEventListener('change', updateFormFieldData);
                if (validationInput) validationInput.addEventListener('input', updateFormFieldData);
                if (requiredCheckbox) requiredCheckbox.addEventListener('change', updateFormFieldData);
                if (addOptionBtn) addOptionBtn.addEventListener('click', () => addOption());

                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        if (confirm('Yakin ingin hapus field ini?')) {
                            removeFormField(index);
                        }
                    });
                }

                // Setup existing option remove buttons
                const existingOptionRemoves = fieldElement.querySelectorAll('.remove-option-btn');
                existingOptionRemoves.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const group = e.target.closest('.input-group');
                        if (group) {
                            group.remove();
                            updateFormFieldData();
                        }
                    });
                });

                toggleOptionsSection();
            }

            function fillFormFieldData(fieldElement, data) {
                const nameInput = fieldElement.querySelector('.field-name');
                const keyDisplay = fieldElement.querySelector('.field-key-display');
                const typeSelect = fieldElement.querySelector('.field-type');
                const iconSelect = fieldElement.querySelector('.field-icon');
                const placeholderInput = fieldElement.querySelector('.field-placeholder');
                const positionSelect = fieldElement.querySelector('.field-position');
                const validationInput = fieldElement.querySelector('.field-validation');
                const requiredCheckbox = fieldElement.querySelector('.field-required');
                const optionsContainer = fieldElement.querySelector('.field-options-container');

                if (nameInput) nameInput.value = data.name || '';
                if (keyDisplay) keyDisplay.textContent = data.key || 'akan_dibuat_otomatis';
                if (typeSelect) typeSelect.value = data.type || '';
                if (iconSelect) iconSelect.value = data.icon || '';
                if (placeholderInput) placeholderInput.value = data.placeholder || '';
                if (positionSelect) positionSelect.value = data.position || '';
                if (validationInput) validationInput.value = data.validation || '';
                if (requiredCheckbox) requiredCheckbox.checked = data.required !== false;

                // Fill options if they exist
                if (data.options && Array.isArray(data.options) && data.options.length > 0) {
                    optionsContainer.innerHTML = '';
                    data.options.forEach(opt => {
                        const optionDiv = document.createElement('div');
                        optionDiv.className = 'input-group mb-2';
                        optionDiv.innerHTML = `
                        <input type="text" class="form-control option-value" placeholder="Nilai opsi" value="${opt.value || ''}">
                        <input type="text" class="form-control option-label" placeholder="Label opsi" value="${opt.label || ''}">
                        <button type="button" class="btn btn-outline-danger remove-option-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                        optionsContainer.appendChild(optionDiv);

                        // Add event listeners for options
                        const valueInput = optionDiv.querySelector('.option-value');
                        const labelInput = optionDiv.querySelector('.option-label');
                        const removeBtn = optionDiv.querySelector('.remove-option-btn');

                        if (valueInput) valueInput.addEventListener('input', () => { });
                        if (labelInput) labelInput.addEventListener('input', () => { });
                        if (removeBtn) {
                            removeBtn.addEventListener('click', () => {
                                optionDiv.remove();
                            });
                        }
                    });
                }
            }

            // Document management functions
            function addDocumentItem(data = null, index = null) {
                documentCounter++;
                const template = document.getElementById('documentTemplate');
                const clone = template.content.cloneNode(true);

                const dataIndex = (index !== null) ? index : documentsData.length;

                clone.querySelector('.item-number').textContent = documentCounter;
                const docElement = clone.querySelector('.dynamic-item');
                docElement.setAttribute('data-index', dataIndex);

                documentsContainer.appendChild(clone);

                const addedElement = documentsContainer.lastElementChild;
                setupDocumentEvents(addedElement, dataIndex);

                if (data) {
                    fillDocumentData(addedElement, data);
                    if (data.key && !usedDocumentKeys.has(data.key)) {
                        usedDocumentKeys.add(data.key);
                    }
                }

                const newDocData = data || {
                    name: '', key: '', icon: '', color: '', formats: [],
                    max_size: 5, description: '', required: true
                };

                if (!data) {
                    documentsData.push(newDocData);
                }

                updateDocumentsVisibility();

                if (addedElement) {
                    addedElement.classList.add('new-item');
                    setTimeout(() => addedElement.classList.remove('new-item'), 500);
                    addedElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            function setupDocumentEvents(documentElement, index) {
                const nameInput = documentElement.querySelector('.document-name');
                const keyDisplay = documentElement.querySelector('.document-key-display');
                const iconSelect = documentElement.querySelector('.document-icon');
                const colorSelect = documentElement.querySelector('.document-color');
                const formatCheckboxes = documentElement.querySelectorAll('.document-format');
                const maxSizeInput = documentElement.querySelector('.document-max-size');
                const descriptionInput = documentElement.querySelector('.document-description');
                const requiredCheckbox = documentElement.querySelector('.document-required');
                const removeBtn = documentElement.querySelector('.remove-document-btn');

                function updateDocumentData() {
                    if (typeof documentsData[index] === 'undefined') return;

                    // Auto-generate key if name changed
                    if (nameInput && nameInput.value && nameInput.value !== documentsData[index].name) {
                        if (!documentsData[index].key) {
                            documentsData[index].key = generateUniqueDocumentKey(nameInput.value);
                            usedDocumentKeys.add(documentsData[index].key);
                            if (keyDisplay) keyDisplay.textContent = documentsData[index].key;
                        }
                    }

                    documentsData[index].name = nameInput ? nameInput.value : '';
                    documentsData[index].icon = iconSelect ? iconSelect.value : '';
                    documentsData[index].color = colorSelect ? colorSelect.value : '';
                    documentsData[index].formats = Array.from(formatCheckboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);
                    documentsData[index].max_size = parseInt(maxSizeInput?.value || 5);
                    documentsData[index].description = descriptionInput ? descriptionInput.value : '';
                    documentsData[index].required = requiredCheckbox ? requiredCheckbox.checked : false;
                }

                // Attach event listeners
                if (nameInput) nameInput.addEventListener('input', updateDocumentData);
                if (iconSelect) iconSelect.addEventListener('change', updateDocumentData);
                if (colorSelect) colorSelect.addEventListener('change', updateDocumentData);
                if (maxSizeInput) maxSizeInput.addEventListener('input', updateDocumentData);
                if (descriptionInput) descriptionInput.addEventListener('input', updateDocumentData);
                if (requiredCheckbox) requiredCheckbox.addEventListener('change', updateDocumentData);

                formatCheckboxes.forEach(cb => {
                    cb.addEventListener('change', updateDocumentData);
                });

                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        if (confirm('Yakin ingin hapus dokumen ini?')) {
                            removeDocumentItem(index);
                        }
                    });
                }
            }

            function fillDocumentData(documentElement, data) {
                const nameInput = documentElement.querySelector('.document-name');
                const keyDisplay = documentElement.querySelector('.document-key-display');
                const iconSelect = documentElement.querySelector('.document-icon');
                const colorSelect = documentElement.querySelector('.document-color');
                const maxSizeInput = documentElement.querySelector('.document-max-size');
                const descriptionInput = documentElement.querySelector('.document-description');
                const requiredCheckbox = documentElement.querySelector('.document-required');

                if (nameInput) nameInput.value = data.name || '';
                if (keyDisplay) keyDisplay.textContent = data.key || 'file_akan_dibuat_otomatis';
                if (iconSelect) iconSelect.value = data.icon || '';
                if (colorSelect) colorSelect.value = data.color || '';
                if (maxSizeInput) maxSizeInput.value = data.max_size || 5;
                if (descriptionInput) descriptionInput.value = data.description || '';
                if (requiredCheckbox) requiredCheckbox.checked = data.required !== false;

                // Set format checkboxes
                if (data.formats && Array.isArray(data.formats)) {
                    const formatCheckboxes = documentElement.querySelectorAll('.document-format');
                    formatCheckboxes.forEach(cb => {
                        cb.checked = data.formats.includes(cb.value);
                    });
                }
            }

            // Utility functions
            function removeFormField(index) {
                if (formFieldsData[index] && formFieldsData[index].key) {
                    removeFromUsedKeys(formFieldsData[index].key, false);
                }
                formFieldsData.splice(index, 1);
                updateFormFieldsDisplay();
                updateFormFieldsVisibility();
            }

            function removeDocumentItem(index) {
                if (documentsData[index] && documentsData[index].key) {
                    removeFromUsedKeys(documentsData[index].key, true);
                }
                documentsData.splice(index, 1);
                updateDocumentsDisplay();
                updateDocumentsVisibility();
            }

            function updateFormFieldsDisplay() {
                formFieldsContainer.innerHTML = '';
                fieldCounter = 0;
                formFieldsData.forEach((data, idx) => addFormField(data, idx));
            }

            function updateDocumentsDisplay() {
                documentsContainer.innerHTML = '';
                documentCounter = 0;
                documentsData.forEach((data, idx) => addDocumentItem(data, idx));
            }

            function updateFormFieldsVisibility() {
                formFieldsEmpty.style.display = formFieldsData.length === 0 ? 'block' : 'none';
            }

            function updateDocumentsVisibility() {
                documentsEmpty.style.display = documentsData.length === 0 ? 'block' : 'none';
            }

            function loadDefaultFormFields() {
                if (!confirm('Ini akan mengganti semua field yang sudah ada. Lanjutkan?')) return;

                // Clear existing
                usedFieldKeys.clear();

                formFieldsData = [
                    {
                        name: 'Nama Lengkap',
                        key: 'nama_lengkap',
                        type: 'text',
                        icon: 'fas fa-user',
                        placeholder: 'Masukkan nama lengkap',
                        position: 'personal',
                        validation: 'required|min:3|max:100',
                        required: true,
                        options: []
                    },
                    {
                        name: 'NIM',
                        key: 'nim',
                        type: 'number',
                        icon: 'fas fa-id-card',
                        placeholder: 'Masukkan NIM',
                        position: 'personal',
                        validation: 'required|digits_between:8,20',
                        required: true,
                        options: []
                    },
                    {
                        name: 'Email',
                        key: 'email',
                        type: 'email',
                        icon: 'fas fa-envelope',
                        placeholder: 'contoh@email.com',
                        position: 'personal',
                        validation: 'required|email',
                        required: true,
                        options: []
                    },
                    {
                        name: 'No. HP',
                        key: 'no_hp',
                        type: 'tel',
                        icon: 'fas fa-phone',
                        placeholder: '08xxxxxxxxxx',
                        position: 'personal',
                        validation: 'required|min:10|max:15',
                        required: true,
                        options: []
                    }
                ];

                formFieldsData.forEach(f => {
                    if (f.key) usedFieldKeys.add(f.key);
                });

                updateFormFieldsDisplay();
                updateFormFieldsVisibility();
            }

            function loadDefaultDocuments() {
                if (!confirm('Ini akan mengganti semua dokumen yang sudah ada. Lanjutkan?')) return;

                // Clear existing
                usedDocumentKeys.clear();

                documentsData = [
                    {
                        name: 'Transkrip Nilai',
                        key: 'file_transkrip_nilai',
                        icon: 'fas fa-file-pdf',
                        color: 'red',
                        formats: ['pdf'],
                        max_size: 5,
                        description: 'Transkrip nilai terbaru',
                        required: true
                    },
                    {
                        name: 'KTP',
                        key: 'file_ktp',
                        icon: 'fas fa-id-card',
                        color: 'blue',
                        formats: ['pdf', 'jpg', 'jpeg', 'png'],
                        max_size: 5,
                        description: 'Kartu Tanda Penduduk',
                        required: true
                    }
                ];

                documentsData.forEach(d => {
                    if (d.key) usedDocumentKeys.add(d.key);
                });

                updateDocumentsDisplay();
                updateDocumentsVisibility();
            }

            function resetForm() {
                if (confirm('Yakin ingin reset ke data asli? Semua perubahan akan hilang.')) {
                    location.reload();
                }
            }

            // Initial setup
            updateFormFieldsDisplay();
            updateDocumentsDisplay();
            updateFormFieldsVisibility();
            updateDocumentsVisibility();

            // Event bindings
            addFieldBtn.addEventListener('click', () => addFormField());
            loadDefaultFieldsBtn.addEventListener('click', loadDefaultFormFields);
            addDocumentBtn.addEventListener('click', () => addDocumentItem());
            loadDefaultDocsBtn.addEventListener('click', loadDefaultDocuments);
            resetFormBtn.addEventListener('click', resetForm);

            // Form submission - FIXED VERSION
            beasiswaForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Validation
                if (formFieldsData.length === 0) {
                    alert('Minimal harus ada 1 field form yang dikonfigurasi.');
                    return;
                }
                if (documentsData.length === 0) {
                    alert('Minimal harus ada 1 dokumen yang diperlukan.');
                    return;
                }

                // Clean and validate data before submission
                const cleanFormFields = formFieldsData.map(field => ({
                    name: field.name || '',
                    key: field.key || '',
                    type: field.type || '',
                    icon: field.icon || '',
                    placeholder: field.placeholder || '',
                    position: field.position || '',
                    validation: field.validation || '',
                    required: Boolean(field.required),
                    options: Array.isArray(field.options) ? field.options : []
                })).filter(field => field.name && field.key && field.type);

                const cleanDocuments = documentsData.map(doc => ({
                    name: doc.name || '',
                    key: doc.key || '',
                    icon: doc.icon || '',
                    color: doc.color || '',
                    formats: Array.isArray(doc.formats) ? doc.formats : [],
                    max_size: parseInt(doc.max_size) || 5,
                    description: doc.description || '',
                    required: Boolean(doc.required)
                })).filter(doc => doc.name && doc.key && doc.formats.length > 0);

                // Set JSON data to hidden inputs
                document.getElementById('form_fields_json').value = JSON.stringify(cleanFormFields);
                document.getElementById('required_documents_json').value = JSON.stringify(cleanDocuments);

                // Submit the form
                beasiswaForm.submit();
            });
        });
    </script>


@endsection