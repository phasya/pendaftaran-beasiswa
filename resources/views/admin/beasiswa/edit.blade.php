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

                /* Alert styling */
                .alert-info-soft {
                    background-color: #d1ecf1;
                    border: 1px solid #bee5eb;
                    color: #0c5460;
                    border-radius: 8px;
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

                /* Input group styling */
                .input-group-text {
                    border: 1px solid #ced4da;
                }

                .input-group .form-control:focus {
                    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
                    border-color: #86b7fe;
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

                .dynamic-item .card-header {
                    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
                    border-bottom: 1px solid #dee2e6;
                }

                .item-number {
                    font-weight: bold;
                    color: #007bff;
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

                /* Custom scrollbar */
                .dynamic-container::-webkit-scrollbar {
                    width: 6px;
                }

                .dynamic-container::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 3px;
                }

                .dynamic-container::-webkit-scrollbar-thumb {
                    background: #c1c1c1;
                    border-radius: 3px;
                }

                .dynamic-container::-webkit-scrollbar-thumb:hover {
                    background: #a1a1a1;
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
                                <form action="{{ route('admin.beasiswa.update', $beasiswa) }}" method="POST" id="beasiswaForm">
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

                                        <div id="dateRangeInfo" class="alert alert-info-soft d-none">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <span id="dateRangeText"></span>
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

                                            <small class="form-text text-muted">
                                                <i class="fas fa-lightbulb me-1"></i>Tentukan field apa saja yang akan ditampilkan
                                                dalam form pendaftaran. Minimal harus ada 1 field.
                                            </small>
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

                                            <small class="form-text text-muted">
                                                <i class="fas fa-lightbulb me-1"></i>Tentukan dokumen apa saja yang perlu diupload
                                                oleh pendaftar. Minimal harus ada 1 dokumen.
                                            </small>
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
                                <button type="button" class="btn btn-sm btn-outline-secondary move-up-btn" title="Pindah ke atas">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary move-down-btn"
                                    title="Pindah ke bawah">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
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
                                    Nama Field
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control field-name" placeholder="Contoh: Nama Lengkap" required>
                                <small class="form-text text-muted">Label yang akan ditampilkan di form</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Key Field (Unik)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control field-key" placeholder="nama_lengkap" pattern="^[a-z0-9_]+$"
                                    required>
                                <small class="form-text text-muted">Hanya huruf kecil, angka, dan underscore</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Tipe Field
                                    <span class="text-danger">*</span>
                                </label>
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
                                <label class="form-label fw-semibold">
                                    Icon
                                    <span class="text-danger">*</span>
                                </label>
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
                                <label class="form-label fw-semibold">
                                    Placeholder
                                </label>
                                <input type="text" class="form-control field-placeholder" placeholder="Masukkan placeholder...">
                                <small class="form-text text-muted">Teks bantuan yang muncul di field</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">
                                    Posisi
                                    <span class="text-danger">*</span>
                                </label>
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
                                <label class="form-label fw-semibold">
                                    Opsi Pilihan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="field-options-container">
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control option-value" placeholder="Nilai opsi">
                                        <input type="text" class="form-control option-label" placeholder="Label opsi">
                                        <button type="button" class="btn btn-outline-danger remove-option-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary add-option-btn">
                                    <i class="fas fa-plus me-1"></i>Tambah Opsi
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold">
                                    Validasi
                                </label>
                                <input type="text" class="form-control field-validation" placeholder="min:3|max:50">
                                <small class="form-text text-muted">Aturan validasi (contoh: required|min:3|max:50)</small>
                            </div>
                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input field-required" type="checkbox" checked>
                                    <label class="form-check-label fw-semibold">
                                        Field Wajib
                                    </label>
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
                                <button type="button" class="btn btn-sm btn-outline-secondary move-up-btn" title="Pindah ke atas">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary move-down-btn"
                                    title="Pindah ke bawah">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
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
                                <label class="form-label fw-semibold">
                                    Nama Dokumen
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control document-name" placeholder="Contoh: Transkrip Nilai"
                                    required>
                                <small class="form-text text-muted">Nama yang akan ditampilkan kepada pendaftar</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Key (Unik)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control document-key" placeholder="file_transkrip"
                                    pattern="^[a-z0-9_]+$" required>
                                <small class="form-text text-muted">Hanya huruf kecil, angka, dan underscore</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Icon
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select document-icon" required>
                                    <option value="">-- Pilih Icon --</option>
                                    <optgroup label="Documents">
                                        <option value="fas fa-file-pdf">PDF Document</option>
                                        <option value="fas fa-file-image">Image File</option>
                                        <option value="fas fa-file-alt">Text Document</option>
                                        <option value="fas fa-file-contract">Contract</option>
                                        <option value="fas fa-file-signature">Signature</option>
                                        <option value="fas fa-file-word">Word Document</option>
                                        <option value="fas fa-file-excel">Excel Document</option>
                                        <option value="fas fa-file-powerpoint">PowerPoint</option>
                                        <option value="fas fa-file-archive">Archive File</option>
                                    </optgroup>
                                    <optgroup label="Identity">
                                        <option value="fas fa-id-card">ID Card</option>
                                        <option value="fas fa-passport">Passport</option>
                                        <option value="fas fa-address-card">Address Card</option>
                                        <option value="fas fa-user-circle">User Profile</option>
                                    </optgroup>
                                    <optgroup label="Academic">
                                        <option value="fas fa-graduation-cap">Academic</option>
                                        <option value="fas fa-certificate">Certificate</option>
                                        <option value="fas fa-award">Award</option>
                                        <option value="fas fa-medal">Medal</option>
                                        <option value="fas fa-trophy">Trophy</option>
                                        <option value="fas fa-scroll">Scroll</option>
                                    </optgroup>
                                    <optgroup label="Financial">
                                        <option value="fas fa-money-check">Bank Statement</option>
                                        <option value="fas fa-receipt">Receipt</option>
                                        <option value="fas fa-credit-card">Payment</option>
                                        <option value="fas fa-coins">Financial</option>
                                    </optgroup>
                                    <optgroup label="Medical">
                                        <option value="fas fa-heartbeat">Medical Record</option>
                                        <option value="fas fa-user-md">Doctor</option>
                                        <option value="fas fa-hospital">Hospital</option>
                                        <option value="fas fa-stethoscope">Health</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Warna Icon
                                    <span class="text-danger">*</span>
                                </label>
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
                                    <option value="pink">Pink</option>
                                    <option value="indigo">Indigo</option>
                                    <option value="cyan">Cyan</option>
                                    <option value="emerald">Emerald</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold">
                                    Format File
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="format-checkboxes border p-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="pdf">
                                                <label class="form-check-label">PDF</label>
                                            </div>
                                        </div>
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
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="doc">
                                                <label class="form-check-label">DOC</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="docx">
                                                <label class="form-check-label">DOCX</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="xls">
                                                <label class="form-check-label">XLS</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="xlsx">
                                                <label class="form-check-label">XLSX</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="ppt">
                                                <label class="form-check-label">PPT</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="pptx">
                                                <label class="form-check-label">PPTX</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="txt">
                                                <label class="form-check-label">TXT</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="rtf">
                                                <label class="form-check-label">RTF</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="zip">
                                                <label class="form-check-label">ZIP</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="rar">
                                                <label class="form-check-label">RAR</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="gif">
                                                <label class="form-check-label">GIF</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input document-format" type="checkbox" value="bmp">
                                                <label class="form-check-label">BMP</label>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted mt-2">Pilih minimal 1 format file yang diperbolehkan</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">
                                    Ukuran Max (MB)
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control document-max-size" min="1" max="50" value="5" required>
                                    <span class="input-group-text">MB</span>
                                </div>
                                <small class="form-text text-muted">Maksimal 50MB</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold">
                                    Deskripsi
                                </label>
                                <input type="text" class="form-control document-description"
                                    placeholder="Deskripsi singkat dokumen (opsional)">
                                <small class="form-text text-muted">Keterangan tambahan untuk membantu pendaftar</small>
                            </div>
                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input document-required" type="checkbox" checked>
                                    <label class="form-check-label fw-semibold">
                                        Dokumen Wajib
                                    </label>
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

            // Date validation elements
            const tanggalBukaInput = document.getElementById('tanggal_buka');
            const tanggalTutupInput = document.getElementById('tanggal_tutup');
            const dateRangeInfo = document.getElementById('dateRangeInfo');
            const dateRangeText = document.getElementById('dateRangeText');

            // Store original values for change detection
            const originalValues = {};

            // Initialize with existing data
            const existingFormFields = @json($beasiswa->form_fields ?? []);
            const existingDocuments = @json($beasiswa->required_documents ?? []);

            // FIXED: Better function to handle duplicate keys
            function findAndFixDuplicateKeys(array, keyProperty = 'key') {
                const keyMap = new Map();
                const duplicates = [];

                // First pass: identify duplicates
                array.forEach((item, index) => {
                    const key = item[keyProperty];
                    if (key && key.trim()) {
                        if (keyMap.has(key)) {
                            keyMap.get(key).push(index);
                        } else {
                            keyMap.set(key, [index]);
                        }
                    }
                });

                // Second pass: fix duplicates
                keyMap.forEach((indices, key) => {
                    if (indices.length > 1) {
                        duplicates.push(key);
                        // Keep the first one as is, rename others
                        for (let i = 1; i < indices.length; i++) {
                            const index = indices[i];
                            let counter = 1;
                            let newKey = `${key}_${counter}`;

                            // Make sure the new key doesn't exist either
                            while (array.some((item, idx) => item[keyProperty] === newKey && idx !== index)) {
                                counter++;
                                newKey = `${key}_${counter}`;
                            }

                            array[index][keyProperty] = newKey;
                        }
                    }
                });

                return duplicates;
            }

            // FIXED: Generate unique key without checking duplicates incorrectly
            function generateUniqueKey(baseName, existingKeys, prefix = '') {
                if (!baseName) return '';

                let baseKey = prefix + baseName
                    .toLowerCase()
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, '_')
                    .substring(0, 50);

                // If key doesn't exist, return it
                if (!existingKeys.includes(baseKey)) {
                    return baseKey;
                }

                // If it exists, add counter
                let counter = 1;
                let uniqueKey = `${baseKey}_${counter}`;

                while (existingKeys.includes(uniqueKey)) {
                    counter++;
                    uniqueKey = `${baseKey}_${counter}`;
                }

                return uniqueKey;
            }

            // Load existing form fields
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
            }

            // Load existing documents
            if (existingDocuments) {
                let processedDocuments = [];

                if (typeof existingDocuments === 'string') {
                    try {
                        processedDocuments = JSON.parse(existingDocuments);
                    } catch (e) {
                        processedDocuments = [];
                    }
                } else if (Array.isArray(existingDocuments)) {
                    processedDocuments = existingDocuments;
                } else if (typeof existingDocuments === 'object') {
                    processedDocuments = Object.values(existingDocuments);
                }

                documentsData = processedDocuments.map(doc => ({
                    name: doc.name || '',
                    key: doc.key || '',
                    icon: doc.icon || 'fas fa-file',
                    color: doc.color || 'blue',
                    formats: Array.isArray(doc.formats) ? doc.formats :
                        (typeof doc.formats === 'string' ? doc.formats.split(',') : ['pdf']),
                    max_size: parseInt(doc.max_size || 5),
                    description: doc.description || '',
                    required: doc.required !== false
                }));
            }

            // FIXED: Generate keys for items without keys, then fix duplicates
            function validateAndFixAllKeys() {
                // Generate keys for form fields that don't have keys
                const existingFormKeys = formFieldsData.map(f => f.key).filter(k => k);
                formFieldsData.forEach((field, index) => {
                    if (!field.key || field.key.trim() === '') {
                        field.key = generateUniqueKey(field.name, existingFormKeys);
                        existingFormKeys.push(field.key);
                    }
                });

                // Generate keys for documents that don't have keys
                const existingDocKeys = documentsData.map(d => d.key).filter(k => k);
                documentsData.forEach((doc, index) => {
                    if (!doc.key || doc.key.trim() === '') {
                        doc.key = generateUniqueKey(doc.name, existingDocKeys, 'file_');
                        existingDocKeys.push(doc.key);
                    }
                });

                // Fix any remaining duplicates
                const formDuplicates = findAndFixDuplicateKeys(formFieldsData, 'key');
                const docDuplicates = findAndFixDuplicateKeys(documentsData, 'key');

                if (formDuplicates.length > 0 || docDuplicates.length > 0) {
                    console.log('Fixed duplicates:', { formDuplicates, docDuplicates });
                }
            }

            // Fix keys on initial load
            validateAndFixAllKeys();

            // Initialize display
            updateFormFieldsDisplay();
            updateDocumentsDisplay();
            updateFormFieldsVisibility();
            updateDocumentsVisibility();
            setupDateValidation();
            setupChangeDetection();

            // Event Listeners
            if (addFieldBtn) addFieldBtn.addEventListener('click', () => addFormField());
            if (loadDefaultFieldsBtn) loadDefaultFieldsBtn.addEventListener('click', loadDefaultFormFields);
            if (addDocumentBtn) addDocumentBtn.addEventListener('click', () => addDocumentItem());
            if (loadDefaultDocsBtn) loadDefaultDocsBtn.addEventListener('click', loadDefaultDocuments);
            if (resetFormBtn) resetFormBtn.addEventListener('click', resetForm);

            // Date validation
            function setupDateValidation() {
                if (!tanggalBukaInput || !tanggalTutupInput) return;

                function updateDateRange() {
                    const tanggalBuka = tanggalBukaInput.value;
                    const tanggalTutup = tanggalTutupInput.value;

                    if (tanggalBuka && tanggalTutup && dateRangeInfo && dateRangeText) {
                        const buka = new Date(tanggalBuka);
                        const tutup = new Date(tanggalTutup);
                        const diffTime = Math.abs(tutup - buka);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                        dateRangeInfo.classList.remove('d-none');

                        if (tutup > buka) {
                            if (diffDays < 7) {
                                dateRangeInfo.className = 'alert alert-warning';
                                dateRangeText.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>Periode pendaftaran: ${diffDays} hari - Periode terlalu singkat!`;
                            } else {
                                dateRangeInfo.className = 'alert alert-info-soft';
                                dateRangeText.innerHTML = `<i class="fas fa-info-circle me-2"></i>Periode pendaftaran: ${diffDays} hari (${buka.toLocaleDateString('id-ID')} - ${tutup.toLocaleDateString('id-ID')})`;
                            }
                        } else {
                            dateRangeInfo.className = 'alert alert-danger';
                            dateRangeText.innerHTML = '<i class="fas fa-times-circle me-2"></i>Tanggal tutup harus setelah tanggal buka!';
                        }
                    } else if (dateRangeInfo) {
                        dateRangeInfo.classList.add('d-none');
                    }
                }

                tanggalBukaInput.addEventListener('change', updateDateRange);
                tanggalTutupInput.addEventListener('change', updateDateRange);
                updateDateRange();
            }

            // Change detection
            function setupChangeDetection() {
                document.querySelectorAll('input, select, textarea').forEach(field => {
                    if (field.name && !field.name.startsWith('documents') && !field.name.startsWith('form_fields')) {
                        originalValues[field.name] = field.value;
                        field.addEventListener('input', function () {
                            if (this.value !== originalValues[this.name]) {
                                this.classList.add('changed-field');
                            } else {
                                this.classList.remove('changed-field');
                            }
                        });
                    }
                });
            }

            // Form Fields Management
            function addFormField(data = null) {
                const template = document.getElementById('formFieldTemplate');
                if (!template || !formFieldsContainer) return;

                fieldCounter++;
                const clone = template.content.cloneNode(true);

                const itemNumber = clone.querySelector('.item-number');
                if (itemNumber) itemNumber.textContent = fieldCounter;

                const fieldElement = clone.querySelector('.dynamic-item');
                if (fieldElement) fieldElement.setAttribute('data-index', formFieldsData.length);

                setupFormFieldEvents(clone, formFieldsData.length);

                if (data) {
                    fillFormFieldData(clone, data);
                }

                formFieldsContainer.appendChild(clone);

                const newFieldData = data || {
                    name: '',
                    key: '',
                    type: '',
                    icon: '',
                    placeholder: '',
                    position: '',
                    options: [],
                    validation: '',
                    required: true
                };

                formFieldsData.push(newFieldData);
                updateFormFieldsVisibility();

                const newItem = formFieldsContainer.lastElementChild;
                if (newItem) {
                    newItem.classList.add('new-item');
                    setTimeout(() => newItem.classList.remove('new-item'), 500);
                    newItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            function setupFormFieldEvents(fieldElement, index) {
                const nameInput = fieldElement.querySelector('.field-name');
                const keyInput = fieldElement.querySelector('.field-key');
                const typeSelect = fieldElement.querySelector('.field-type');
                const iconSelect = fieldElement.querySelector('.field-icon');
                const placeholderInput = fieldElement.querySelector('.field-placeholder');
                const positionSelect = fieldElement.querySelector('.field-position');
                const validationInput = fieldElement.querySelector('.field-validation');
                const requiredCheckbox = fieldElement.querySelector('.field-required');
                const removeBtn = fieldElement.querySelector('.remove-field-btn');
                const optionsSection = fieldElement.querySelector('.field-options-section');
                const addOptionBtn = fieldElement.querySelector('.add-option-btn');

                function updateFormFieldData() {
                    if (formFieldsData[index]) {
                        formFieldsData[index].name = nameInput ? nameInput.value : '';
                        formFieldsData[index].key = keyInput ? keyInput.value : '';
                        formFieldsData[index].type = typeSelect ? typeSelect.value : '';
                        formFieldsData[index].icon = iconSelect ? iconSelect.value : '';
                        formFieldsData[index].placeholder = placeholderInput ? placeholderInput.value : '';
                        formFieldsData[index].position = positionSelect ? positionSelect.value : '';
                        formFieldsData[index].validation = validationInput ? validationInput.value : '';
                        formFieldsData[index].required = requiredCheckbox ? requiredCheckbox.checked : true;

                        if (typeSelect && ['select', 'radio', 'checkbox'].includes(typeSelect.value)) {
                            const optionInputs = fieldElement.querySelectorAll('.option-value');
                            const optionLabels = fieldElement.querySelectorAll('.option-label');
                            formFieldsData[index].options = [];

                            optionInputs.forEach((input, idx) => {
                                if (input.value && optionLabels[idx] && optionLabels[idx].value) {
                                    formFieldsData[index].options.push({
                                        value: input.value,
                                        label: optionLabels[idx].value
                                    });
                                }
                            });
                        }
                    }
                }

                // FIXED: Better key generation
                function generateKeyFromName() {
                    if (nameInput && keyInput && !keyInput.value && nameInput.value) {
                        const existingKeys = formFieldsData
                            .map((f, i) => i !== index ? f.key : null)
                            .filter(k => k);

                        keyInput.value = generateUniqueKey(nameInput.value, existingKeys);
                        updateFormFieldData();
                    }
                }

                function toggleOptionsSection() {
                    if (!typeSelect || !optionsSection) return;
                    const needsOptions = ['select', 'radio', 'checkbox'].includes(typeSelect.value);
                    optionsSection.style.display = needsOptions ? 'block' : 'none';

                    if (needsOptions && !fieldElement.querySelector('.option-value')) {
                        addOption();
                    }
                }

                function addOption() {
                    const container = fieldElement.querySelector('.field-options-container');
                    if (!container) return;

                    const optionDiv = document.createElement('div');
                    optionDiv.className = 'input-group mb-2';
                    optionDiv.innerHTML = `
                        <input type="text" class="form-control option-value" placeholder="Nilai opsi">
                        <input type="text" class="form-control option-label" placeholder="Label opsi">
                        <button type="button" class="btn btn-outline-danger remove-option-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;

                    container.appendChild(optionDiv);

                    const valueInput = optionDiv.querySelector('.option-value');
                    const labelInput = optionDiv.querySelector('.option-label');
                    const removeBtn = optionDiv.querySelector('.remove-option-btn');

                    if (valueInput) valueInput.addEventListener('input', updateFormFieldData);
                    if (labelInput) labelInput.addEventListener('input', updateFormFieldData);
                    if (removeBtn) {
                        removeBtn.addEventListener('click', () => {
                            optionDiv.remove();
                            updateFormFieldData();
                        });
                    }
                }

                // Event listeners
                if (nameInput) {
                    nameInput.addEventListener('input', () => {
                        updateFormFieldData();
                        generateKeyFromName();
                    });
                }

                if (keyInput) keyInput.addEventListener('input', updateFormFieldData);
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
                if (addOptionBtn) addOptionBtn.addEventListener('click', addOption);

                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        if (confirm('Yakin ingin hapus field ini?')) {
                            removeFormField(index);
                        }
                    });
                }

                toggleOptionsSection();
            }

            function fillFormFieldData(fieldElement, data) {
                const elements = {
                    name: fieldElement.querySelector('.field-name'),
                    key: fieldElement.querySelector('.field-key'),
                    type: fieldElement.querySelector('.field-type'),
                    icon: fieldElement.querySelector('.field-icon'),
                    placeholder: fieldElement.querySelector('.field-placeholder'),
                    position: fieldElement.querySelector('.field-position'),
                    validation: fieldElement.querySelector('.field-validation'),
                    required: fieldElement.querySelector('.field-required')
                };

                if (elements.name) elements.name.value = data.name || '';
                if (elements.key) elements.key.value = data.key || '';
                if (elements.type) elements.type.value = data.type || '';
                if (elements.icon) elements.icon.value = data.icon || '';
                if (elements.placeholder) elements.placeholder.value = data.placeholder || '';
                if (elements.position) elements.position.value = data.position || '';
                if (elements.validation) elements.validation.value = data.validation || '';
                if (elements.required) elements.required.checked = data.required !== false;

                if (data.options && Array.isArray(data.options) && data.options.length > 0) {
                    const container = fieldElement.querySelector('.field-options-container');
                    if (container) {
                        container.innerHTML = '';
                        data.options.forEach(option => {
                            const optionDiv = document.createElement('div');
                            optionDiv.className = 'input-group mb-2';
                            optionDiv.innerHTML = `
                                <input type="text" class="form-control option-value" placeholder="Nilai opsi" value="${option.value || ''}">
                                <input type="text" class="form-control option-label" placeholder="Label opsi" value="${option.label || ''}">
                                <button type="button" class="btn btn-outline-danger remove-option-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            `;
                            container.appendChild(optionDiv);
                        });
                    }
                }
            }

            function removeFormField(index) {
                formFieldsData.splice(index, 1);
                updateFormFieldsDisplay();
                updateFormFieldsVisibility();
            }

            function updateFormFieldsDisplay() {
                if (!formFieldsContainer) return;
                formFieldsContainer.innerHTML = '';
                fieldCounter = 0;
                formFieldsData.forEach((data, index) => {
                    addFormField(data);
                });
            }

            function updateFormFieldsVisibility() {
                if (formFieldsEmpty) {
                    formFieldsEmpty.style.display = formFieldsData.length === 0 ? 'block' : 'none';
                }
            }

            function loadDefaultFormFields() {
                if (confirm('Ini akan mengganti semua field yang sudah ada. Lanjutkan?')) {
                    formFieldsData = [
                        { name: 'Nama Lengkap', key: 'nama_lengkap', type: 'text', icon: 'fas fa-user', placeholder: 'Masukkan nama lengkap', position: 'personal', validation: 'required|min:3|max:100', required: true },
                        { name: 'NIM', key: 'nim', type: 'number', icon: 'fas fa-id-card', placeholder: 'Masukkan NIM', position: 'personal', validation: 'required|digits_between:8,20', required: true },
                        { name: 'Email', key: 'email', type: 'email', icon: 'fas fa-envelope', placeholder: 'contoh@email.com', position: 'personal', validation: 'required|email', required: true },
                        { name: 'No. HP', key: 'no_hp', type: 'tel', icon: 'fas fa-phone', placeholder: '08xxxxxxxxxx', position: 'personal', validation: 'required|min:10|max:15', required: true },
                        { name: 'Fakultas', key: 'fakultas', type: 'text', icon: 'fas fa-university', placeholder: 'Contoh: Teknik', position: 'academic', validation: 'required|min:3|max:50', required: true },
                        { name: 'Jurusan', key: 'jurusan', type: 'text', icon: 'fas fa-graduation-cap', placeholder: 'Contoh: Teknik Informatika', position: 'academic', validation: 'required|min:3|max:100', required: true },
                        { name: 'Semester', key: 'semester', type: 'select', icon: 'fas fa-calendar-check', placeholder: '-- Pilih Semester --', position: 'academic', validation: 'required|between:1,14', required: true, options: [
                            { value: '1', label: 'Semester 1' }, { value: '2', label: 'Semester 2' }, { value: '3', label: 'Semester 3' }, { value: '4', label: 'Semester 4' },
                            { value: '5', label: 'Semester 5' }, { value: '6', label: 'Semester 6' }, { value: '7', label: 'Semester 7' }, { value: '8', label: 'Semester 8' }
                        ]},
                        { name: 'IPK', key: 'ipk', type: 'number', icon: 'fas fa-chart-line', placeholder: '3.50', position: 'academic', validation: 'required|numeric|between:0,4', required: true },
                        { name: 'Alasan Mendaftar', key: 'alasan_mendaftar', type: 'textarea', icon: 'fas fa-comment-alt', placeholder: 'Jelaskan alasan dan motivasi Anda mendaftar beasiswa ini...', position: 'additional', validation: 'required|min:50|max:1000', required: true }
                    ];

                    validateAndFixAllKeys();
                    updateFormFieldsDisplay();
                    updateFormFieldsVisibility();
                }
            }

            // Documents Management
            function addDocumentItem(data = null) {
                const template = document.getElementById('documentTemplate');
                if (!template || !documentsContainer) return;

                documentCounter++;
                const clone = template.content.cloneNode(true);

                const itemNumber = clone.querySelector('.item-number');
                if (itemNumber) itemNumber.textContent = documentCounter;

                const documentElement = clone.querySelector('.dynamic-item');
                if (documentElement) documentElement.setAttribute('data-index', documentsData.length);

                setupDocumentEvents(clone, documentsData.length);

                if (data) {
                    fillDocumentData(clone, data);
                }

                documentsContainer.appendChild(clone);

                const newDocData = data || {
                    name: '',
                    key: '',
                    icon: 'fas fa-file',
                    color: 'blue',
                    formats: ['pdf'],
                    max_size: 5,
                    description: '',
                    required: true
                };

                documentsData.push(newDocData);
                updateDocumentsVisibility();

                const newItem = documentsContainer.lastElementChild;
                if (newItem) {
                    newItem.classList.add('new-item');
                    setTimeout(() => newItem.classList.remove('new-item'), 500);
                    newItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            function setupDocumentEvents(documentElement, index) {
                const nameInput = documentElement.querySelector('.document-name');
                const keyInput = documentElement.querySelector('.document-key');
                const iconSelect = documentElement.querySelector('.document-icon');
                const colorSelect = documentElement.querySelector('.document-color');
                const formatCheckboxes = documentElement.querySelectorAll('.document-format');
                const maxSizeInput = documentElement.querySelector('.document-max-size');
                const descriptionInput = documentElement.querySelector('.document-description');
                const requiredCheckbox = documentElement.querySelector('.document-required');
                const removeBtn = documentElement.querySelector('.remove-document-btn');

                function updateDocumentData() {
                    if (documentsData[index]) {
                        documentsData[index].name = nameInput ? nameInput.value : '';
                        documentsData[index].key = keyInput ? keyInput.value : '';
                        documentsData[index].icon = iconSelect ? iconSelect.value : '';
                        documentsData[index].color = colorSelect ? colorSelect.value : '';
                        documentsData[index].formats = formatCheckboxes ? Array.from(formatCheckboxes)
                            .filter(cb => cb.checked)
                            .map(cb => cb.value) : [];
                        documentsData[index].max_size = maxSizeInput ? (parseInt(maxSizeInput.value) || 5) : 5;
                        documentsData[index].description = descriptionInput ? descriptionInput.value : '';
                        documentsData[index].required = requiredCheckbox ? requiredCheckbox.checked : true;
                    }
                }

                // FIXED: Better key generation for documents
                function generateKeyFromName() {
                    if (nameInput && keyInput && !keyInput.value && nameInput.value) {
                        const existingKeys = documentsData
                            .map((d, i) => i !== index ? d.key : null)
                            .filter(k => k);

                        keyInput.value = generateUniqueKey(nameInput.value, existingKeys, 'file_');
                        updateDocumentData();
                    }
                }

                // Event listeners
                if (nameInput) {
                    nameInput.addEventListener('input', () => {
                        updateDocumentData();
                        generateKeyFromName();
                    });
                }

                if (keyInput) keyInput.addEventListener('input', updateDocumentData);
                if (iconSelect) iconSelect.addEventListener('change', updateDocumentData);
                if (colorSelect) colorSelect.addEventListener('change', updateDocumentData);
                if (maxSizeInput) maxSizeInput.addEventListener('input', updateDocumentData);
                if (descriptionInput) descriptionInput.addEventListener('input', updateDocumentData);
                if (requiredCheckbox) requiredCheckbox.addEventListener('change', updateDocumentData);

                if (formatCheckboxes && formatCheckboxes.length > 0) {
                    formatCheckboxes.forEach(checkbox => {
                        if (checkbox) {
                            checkbox.addEventListener('change', updateDocumentData);
                        }
                    });
                }

                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        if (confirm('Yakin ingin hapus dokumen ini?')) {
                            removeDocumentItem(index);
                        }
                    });
                }
            }

            function fillDocumentData(documentElement, data) {
                const elements = {
                    name: documentElement.querySelector('.document-name'),
                    key: documentElement.querySelector('.document-key'),
                    icon: documentElement.querySelector('.document-icon'),
                    color: documentElement.querySelector('.document-color'),
                    maxSize: documentElement.querySelector('.document-max-size'),
                    description: documentElement.querySelector('.document-description'),
                    required: documentElement.querySelector('.document-required')
                };

                if (elements.name) elements.name.value = data.name || '';
                if (elements.key) elements.key.value = data.key || '';
                if (elements.icon) elements.icon.value = data.icon || '';
                if (elements.color) elements.color.value = data.color || '';
                if (elements.maxSize) elements.maxSize.value = data.max_size || 5;
                if (elements.description) elements.description.value = data.description || '';
                if (elements.required) elements.required.checked = data.required !== false;

                if (data.formats && Array.isArray(data.formats)) {
                    const formatCheckboxes = documentElement.querySelectorAll('.document-format');
                    formatCheckboxes.forEach(checkbox => {
                        checkbox.checked = data.formats.includes(checkbox.value);
                    });
                }
            }

            function removeDocumentItem(index) {
                documentsData.splice(index, 1);
                updateDocumentsDisplay();
                updateDocumentsVisibility();
            }

            function updateDocumentsDisplay() {
                if (!documentsContainer) return;
                documentsContainer.innerHTML = '';
                documentCounter = 0;
                documentsData.forEach((data, index) => {
                    addDocumentItem(data);
                });
            }

            function updateDocumentsVisibility() {
                if (documentsEmpty) {
                    documentsEmpty.style.display = documentsData.length === 0 ? 'block' : 'none';
                }
            }

            function loadDefaultDocuments() {
                if (confirm('Ini akan mengganti semua dokumen yang sudah ada. Lanjutkan?')) {
                    documentsData = [
                        { name: 'Transkrip Nilai', key: 'file_transkrip', icon: 'fas fa-file-pdf', color: 'red', formats: ['pdf'], max_size: 5, description: 'Transkrip nilai terbaru', required: true },
                        { name: 'KTP', key: 'file_ktp', icon: 'fas fa-id-card', color: 'blue', formats: ['pdf', 'jpg', 'jpeg', 'png'], max_size: 5, description: 'Kartu Tanda Penduduk', required: true },
                        { name: 'Kartu Keluarga', key: 'file_kk', icon: 'fas fa-address-card', color: 'green', formats: ['pdf', 'jpg', 'jpeg', 'png'], max_size: 5, description: 'Kartu Keluarga', required: true },
                        { name: 'Surat Keterangan Tidak Mampu', key: 'file_sktm', icon: 'fas fa-file-contract', color: 'orange', formats: ['pdf', 'doc', 'docx'], max_size: 5, description: 'SKTM dari kelurahan/desa', required: false }
                    ];

                    validateAndFixAllKeys();
                    updateDocumentsDisplay();
                    updateDocumentsVisibility();
                }
            }

            // Reset Form
            function resetForm() {
                if (confirm('Yakin ingin reset ke data asli? Semua perubahan akan hilang.')) {
                    location.reload();
                }
            }

            // FIXED: Simple duplicate detection (for final validation only)
            function getKeyDuplicates(array, keyProperty) {
                const keyCount = {};
                const duplicates = [];

                array.forEach(item => {
                    const key = item[keyProperty];
                    if (key && key.trim()) {
                        keyCount[key] = (keyCount[key] || 0) + 1;
                    }
                });

                Object.keys(keyCount).forEach(key => {
                    if (keyCount[key] > 1) {
                        duplicates.push(key);
                    }
                });

                return duplicates;
            }

            // Form Submission
            if (beasiswaForm) {
                beasiswaForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Date validation
                    if (tanggalBukaInput && tanggalTutupInput) {
                        const startDate = new Date(tanggalBukaInput.value);
                        const endDate = new Date(tanggalTutupInput.value);

                        if (endDate <= startDate) {
                            alert('Tanggal tutup harus setelah tanggal buka!');
                            return false;
                        }
                    }

                    // Validate form fields and documents
                    if (formFieldsData.length === 0) {
                        alert('Minimal harus ada 1 field form yang dikonfigurasi.');
                        return;
                    }

                    if (documentsData.length === 0) {
                        alert('Minimal harus ada 1 dokumen yang diperlukan.');
                        return;
                    }

                    // FIXED: Final validation before submission
                    validateAndFixAllKeys();

                    let hasError = false;
                    let errorMessage = '';

                    // Validate documents data
                    documentsData.forEach((doc, index) => {
                        if (!doc.name || !doc.name.trim()) {
                            hasError = true;
                            errorMessage += `Dokumen ${index + 1}: Nama wajib diisi.\n`;
                        }
                        if (!doc.key || !doc.key.trim()) {
                            hasError = true;
                            errorMessage += `Dokumen ${index + 1}: Key wajib diisi.\n`;
                        }
                        if (!doc.icon) {
                            hasError = true;
                            errorMessage += `Dokumen ${index + 1}: Icon wajib dipilih.\n`;
                        }
                        if (!doc.color) {
                            hasError = true;
                            errorMessage += `Dokumen ${index + 1}: Warna wajib dipilih.\n`;
                        }
                        if (!doc.formats || doc.formats.length === 0) {
                            hasError = true;
                            errorMessage += `Dokumen ${index + 1}: Format file wajib dipilih.\n`;
                        }
                    });

                    // Validate form fields data
                    formFieldsData.forEach((field, index) => {
                        if (!field.name || !field.name.trim()) {
                            hasError = true;
                            errorMessage += `Field ${index + 1}: Nama wajib diisi.\n`;
                        }
                        if (!field.key || !field.key.trim()) {
                            hasError = true;
                            errorMessage += `Field ${index + 1}: Key wajib diisi.\n`;
                        }
                        if (!field.type) {
                            hasError = true;
                            errorMessage += `Field ${index + 1}: Tipe wajib dipilih.\n`;
                        }
                    });

                    // Check for remaining duplicates (should not happen with the new logic)
                    const duplicateDocumentKeys = getKeyDuplicates(documentsData, 'key');
                    const duplicateFormFieldKeys = getKeyDuplicates(formFieldsData, 'key');

                    if (duplicateDocumentKeys.length > 0) {
                        hasError = true;
                        errorMessage += 'Key dokumen yang duplikat: ' + duplicateDocumentKeys.join(', ') + '\n';
                    }

                    if (duplicateFormFieldKeys.length > 0) {
                        hasError = true;
                        errorMessage += 'Key form field yang duplikat: ' + duplicateFormFieldKeys.join(', ') + '\n';
                    }

                    if (hasError) {
                        alert('Terdapat kesalahan:\n\n' + errorMessage);
                        return;
                    }

                    // Remove existing hidden inputs
                    const existingInputs = beasiswaForm.querySelectorAll('input[name^="form_fields"], input[name^="documents"]');
                    existingInputs.forEach(input => input.remove());

                    // Add form fields data
                    formFieldsData.forEach((field, index) => {
                        const fields = ['name', 'key', 'type', 'icon', 'placeholder', 'position', 'validation', 'required'];

                        fields.forEach(fieldName => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `form_fields[${index}][${fieldName}]`;
                            input.value = fieldName === 'required' ? (field[fieldName] ? '1' : '0') : (field[fieldName] || '');
                            beasiswaForm.appendChild(input);
                        });

                        // Add options for select/radio/checkbox fields
                        if (field.options && Array.isArray(field.options)) {
                            field.options.forEach((option, optionIndex) => {
                                ['value', 'label'].forEach(prop => {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = `form_fields[${index}][options][${optionIndex}][${prop}]`;
                                    input.value = option[prop] || '';
                                    beasiswaForm.appendChild(input);
                                });
                            });
                        }
                    });

                    // Add documents data
                    documentsData.forEach((doc, index) => {
                        const fields = ['name', 'key', 'icon', 'color', 'max_size', 'description', 'required'];

                        fields.forEach(field => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `documents[${index}][${field}]`;
                            input.value = field === 'required' ? (doc[field] ? '1' : '0') : (doc[field] || '');
                            beasiswaForm.appendChild(input);
                        });

                        // Add formats array
                        if (doc.formats && Array.isArray(doc.formats)) {
                            doc.formats.forEach((format, formatIndex) => {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = `documents[${index}][formats][${formatIndex}]`;
                                input.value = format;
                                beasiswaForm.appendChild(input);
                            });
                        }
                    });

                    // Show loading state
                    const submitBtn = beasiswaForm.querySelector('button[type="submit"]');
                    const originalContent = submitBtn ? submitBtn.innerHTML : '';
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memperbarui...';
                    }

                    // Submit form
                    const formData = new FormData(beasiswaForm);

                    fetch(beasiswaForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.text();
                        }
                        throw new Error('Network response was not ok');
                    })
                    .then(data => {
                        if (data.includes('Beasiswa berhasil diperbarui') || data.includes('redirect')) {
                            window.location.href = '/admin/beasiswa';
                        } else {
                            beasiswaForm.submit();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        beasiswaForm.submit();
                    })
                    .finally(() => {
                        if (submitBtn) {
                            submitBtn.classList.remove('loading');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalContent;
                        }
                    });
                });
            }
        });

        
    </script>

@endsection