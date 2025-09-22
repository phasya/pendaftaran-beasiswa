@extends('layouts.admin')

@section('title', 'Tambah Beasiswa')

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
    </style>

    <div class="container-fluid px-4 py-3">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2"><i class="fas fa-plus"></i> Tambah Beasiswa</h1>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle me-2"></i>Lengkapi form di bawah untuk menambahkan program beasiswa baru
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
                                    <i class="fas fa-graduation-cap text-primary me-2"></i>Form Tambah Beasiswa
                                </h6>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-info-soft text-info">
                                    <i class="fas fa-asterisk me-1" style="font-size: 8px;"></i>Required Fields
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.beasiswa.store') }}" method="POST" id="beasiswaForm">
                            @csrf

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
                                    <input type="text" class="form-control" id="nama_beasiswa" name="nama_beasiswa"
                                        placeholder="Contoh: Beasiswa Prestasi Akademik 2025" required>
                                </div>

                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label fw-semibold">
                                        <i class="fas fa-align-left text-info me-2"></i>Deskripsi Beasiswa
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"
                                        placeholder="Jelaskan tujuan, target penerima, dan manfaat beasiswa ini..." required></textarea>
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
                                        <input type="number" class="form-control" id="jumlah_dana" name="jumlah_dana"
                                            min="0" step="100000" placeholder="5000000" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_buka" class="form-label fw-semibold">
                                            <i class="fas fa-calendar-plus text-success me-2"></i>Tanggal Buka
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control" id="tanggal_buka" name="tanggal_buka" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_tutup" class="form-label fw-semibold">
                                            <i class="fas fa-calendar-times text-danger me-2"></i>Tanggal Tutup
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control" id="tanggal_tutup" name="tanggal_tutup" required>
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
                                            <button type="button" class="btn btn-sm btn-outline-success me-2" id="loadDefaultFieldsBtn">
                                                <i class="fas fa-magic me-2"></i>Muat Default
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="addFieldBtn">
                                                <i class="fas fa-plus me-2"></i>Tambah Field
                                            </button>
                                        </div>
                                    </div>

                                    <div id="formFieldsContainer" class="dynamic-container" style="max-height: 600px; overflow-y: auto;">
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
                                            <button type="button" class="btn btn-sm btn-outline-success me-2" id="loadDefaultDocsBtn">
                                                <i class="fas fa-magic me-2"></i>Muat Default
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="addDocumentBtn">
                                                <i class="fas fa-plus me-2"></i>Tambah Dokumen
                                            </button>
                                        </div>
                                    </div>

                                    <div id="documentsContainer" class="dynamic-container" style="max-height: 600px; overflow-y: auto;">
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
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="aktif">Aktif (Bisa dilamar)</option>
                                        <option value="nonaktif">Nonaktif (Tidak bisa dilamar)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="persyaratan" class="form-label fw-semibold">
                                        <i class="fas fa-list-check text-info me-2"></i>Persyaratan Pendaftaran
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="persyaratan" name="persyaratan" rows="8"
                                        placeholder="Contoh:&#10;1. Mahasiswa aktif semester 3 ke atas&#10;2. IPK minimal 3.0&#10;3. Tidak sedang menerima beasiswa lain"
                                        required></textarea>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="form-actions bg-light p-4 rounded-3 mt-4">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <small>Pastikan semua data sudah benar sebelum menyimpan</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Kembali
                                        </button>
                                        <button type="button" class="btn btn-outline-warning" id="resetFormBtn">
                                            <i class="fas fa-undo me-2"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Simpan Beasiswa
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
                        <button type="button" class="btn btn-sm btn-outline-danger remove-document-btn" title="Hapus dokumen">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control document-name" placeholder="Contoh: Transkrip Nilai" required>
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
                        <input type="text" class="form-control document-description" placeholder="Deskripsi singkat dokumen (opsional)">
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

            // Initialize
            updateFormFieldsVisibility();
            updateDocumentsVisibility();

            // Event Listeners
            addFieldBtn.addEventListener('click', () => addFormField());
            loadDefaultFieldsBtn.addEventListener('click', loadDefaultFormFields);
            addDocumentBtn.addEventListener('click', () => addDocumentItem());
            loadDefaultDocsBtn.addEventListener('click', loadDefaultDocuments);
            resetFormBtn.addEventListener('click', resetForm);

            // Auto Key Generation Functions
            function generateUniqueFieldKey(name) {
                let baseKey = name
                    .toLowerCase()
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, '_')
                    .substring(0, 30);

                if (!baseKey) {
                    baseKey = 'field';
                }

                let key = baseKey;
                let counter = 1;

                while (usedFieldKeys.has(key)) {
                    key = baseKey + '_' + counter;
                    counter++;
                }

                usedFieldKeys.add(key);
                return key;
            }

            function generateUniqueDocumentKey(name) {
                let baseKey = name
                    .toLowerCase()
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, '_')
                    .substring(0, 25);

                if (!baseKey) {
                    baseKey = 'dokumen';
                }

                let key = 'file_' + baseKey;
                let counter = 1;

                while (usedDocumentKeys.has(key)) {
                    key = 'file_' + baseKey + '_' + counter;
                    counter++;
                }

                usedDocumentKeys.add(key);
                return key;
            }

            function removeFromUsedKeys(key, isDocument = false) {
                if (isDocument) {
                    usedDocumentKeys.delete(key);
                } else {
                    usedFieldKeys.delete(key);
                }
            }

            // Form Fields Management
            function addFormField(data = null) {
                fieldCounter++;
                const template = document.getElementById('formFieldTemplate');
                const clone = template.content.cloneNode(true);

                clone.querySelector('.item-number').textContent = fieldCounter;
                const fieldElement = clone.querySelector('.dynamic-item');
                fieldElement.setAttribute('data-index', formFieldsData.length);

                setupFormFieldEvents(clone, formFieldsData.length);

                if (data) {
                    fillFormFieldData(clone, data);
                    if (data.key) {
                        usedFieldKeys.add(data.key);
                    }
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
                newItem.classList.add('new-item');
                setTimeout(() => newItem.classList.remove('new-item'), 500);
                newItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
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

                const previewLabel = fieldElement.querySelector('.field-preview label');
                const previewInput = fieldElement.querySelector('.field-preview input');
                const previewSmall = fieldElement.querySelector('.field-preview small');

                function updateFormFieldData() {
                    if (formFieldsData[index]) {
                        // Generate key if name changed
                        if (nameInput.value && nameInput.value !== formFieldsData[index].name) {
                            if (formFieldsData[index].key) {
                                removeFromUsedKeys(formFieldsData[index].key, false);
                            }
                            formFieldsData[index].key = generateUniqueFieldKey(nameInput.value);
                            keyDisplay.textContent = formFieldsData[index].key;
                        }

                        formFieldsData[index].name = nameInput.value;
                        formFieldsData[index].type = typeSelect.value;
                        formFieldsData[index].icon = iconSelect.value;
                        formFieldsData[index].placeholder = placeholderInput.value;
                        formFieldsData[index].position = positionSelect.value;
                        formFieldsData[index].validation = validationInput.value;
                        formFieldsData[index].required = requiredCheckbox.checked;

                        // Update options for select/radio/checkbox
                        if (['select', 'radio', 'checkbox'].includes(typeSelect.value)) {
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

                        updatePreview();
                    }
                }

                function updatePreview() {
                    const data = formFieldsData[index];
                    if (!data) return;

                    const iconClass = data.icon || 'fas fa-user';
                    const fieldName = data.name || 'Nama Field';
                    const requiredMark = data.required ? ' <span class="text-danger">*</span>' : '';
                    const positionText = data.position ? getPositionText(data.position) : 'Tidak ditentukan';

                    previewLabel.innerHTML = `<i class="${iconClass} text-primary me-2"></i>${fieldName}${requiredMark}`;
                    previewInput.placeholder = data.placeholder || 'Placeholder...';
                    previewSmall.textContent = `Posisi: ${positionText}`;

                    // Update input type for preview
                    if (data.type === 'textarea') {
                        if (previewInput.tagName.toLowerCase() !== 'textarea') {
                            const textarea = document.createElement('textarea');
                            textarea.className = previewInput.className;
                            textarea.placeholder = previewInput.placeholder;
                            textarea.disabled = true;
                            textarea.rows = 3;
                            previewInput.parentNode.replaceChild(textarea, previewInput);
                        }
                    } else if (data.type === 'select') {
                        if (previewInput.tagName.toLowerCase() !== 'select') {
                            const select = document.createElement('select');
                            select.className = previewInput.className.replace('form-control', 'form-select');
                            select.disabled = true;

                            const defaultOption = document.createElement('option');
                            defaultOption.textContent = data.placeholder || '-- Pilih --';
                            select.appendChild(defaultOption);

                            data.options.forEach(option => {
                                const opt = document.createElement('option');
                                opt.value = option.value;
                                opt.textContent = option.label;
                                select.appendChild(opt);
                            });

                            previewInput.parentNode.replaceChild(select, previewInput);
                        }
                    } else {
                        if (previewInput.tagName.toLowerCase() !== 'input') {
                            const input = document.createElement('input');
                            input.className = 'form-control';
                            input.type = data.type || 'text';
                            input.placeholder = data.placeholder || 'Placeholder...';
                            input.disabled = true;
                            previewInput.parentNode.replaceChild(input, previewInput);
                        } else {
                            previewInput.type = data.type || 'text';
                        }
                    }
                }

                function getPositionText(position) {
                    const positions = {
                        'personal': 'Data Personal',
                        'academic': 'Data Akademik',
                        'additional': 'Data Tambahan'
                    };
                    return positions[position] || position;
                }

                function toggleOptionsSection() {
                    const needsOptions = ['select', 'radio', 'checkbox'].includes(typeSelect.value);
                    optionsSection.style.display = needsOptions ? 'block' : 'none';

                    if (needsOptions && !fieldElement.querySelector('.option-value')) {
                        addOption();
                    }
                }

                function addOption() {
                    const container = fieldElement.querySelector('.field-options-container');
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

                    valueInput.addEventListener('input', updateFormFieldData);
                    labelInput.addEventListener('input', updateFormFieldData);
                    removeBtn.addEventListener('click', () => {
                        optionDiv.remove();
                        updateFormFieldData();
                    });
                }

                // Event listeners
                nameInput.addEventListener('input', updateFormFieldData);
                typeSelect.addEventListener('change', () => {
                    toggleOptionsSection();
                    updateFormFieldData();
                });
                iconSelect.addEventListener('change', updateFormFieldData);
                placeholderInput.addEventListener('input', updateFormFieldData);
                positionSelect.addEventListener('change', updateFormFieldData);
                validationInput.addEventListener('input', updateFormFieldData);
                requiredCheckbox.addEventListener('change', updateFormFieldData);
                addOptionBtn.addEventListener('click', addOption);

                removeBtn.addEventListener('click', () => {
                    if (confirm('Yakin ingin hapus field ini?')) {
                        removeFormField(index);
                    }
                });

                // Initial setup
                toggleOptionsSection();
                updatePreview();
            }

            function fillFormFieldData(fieldElement, data) {
                fieldElement.querySelector('.field-name').value = data.name || '';
                fieldElement.querySelector('.field-key-display').textContent = data.key || 'akan_dibuat_otomatis';
                fieldElement.querySelector('.field-type').value = data.type || '';
                fieldElement.querySelector('.field-icon').value = data.icon || '';
                fieldElement.querySelector('.field-placeholder').value = data.placeholder || '';
                fieldElement.querySelector('.field-position').value = data.position || '';
                fieldElement.querySelector('.field-validation').value = data.validation || '';
                fieldElement.querySelector('.field-required').checked = data.required !== false;

                // Fill options if available
                if (data.options && Array.isArray(data.options) && data.options.length > 0) {
                    const container = fieldElement.querySelector('.field-options-container');
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

                        // Add event listeners for each option
                        const valueInput = optionDiv.querySelector('.option-value');
                        const labelInput = optionDiv.querySelector('.option-label');
                        const removeBtn = optionDiv.querySelector('.remove-option-btn');

                        valueInput.addEventListener('input', () => {
                            updateFormFieldDataForIndex(index);
                        });
                        labelInput.addEventListener('input', () => {
                            updateFormFieldDataForIndex(index);
                        });
                        removeBtn.addEventListener('click', () => {
                            optionDiv.remove();
                            updateFormFieldDataForIndex(index);
                        });
                    });
                }
            }

            function updateFormFieldDataForIndex(index) {
                const fieldElement = formFieldsContainer.querySelector(`[data-index="${index}"]`);
                if (!fieldElement) return;

                const nameInput = fieldElement.querySelector('.field-name');
                const typeSelect = fieldElement.querySelector('.field-type');
                const optionInputs = fieldElement.querySelectorAll('.option-value');
                const optionLabels = fieldElement.querySelectorAll('.option-label');

                if (formFieldsData[index]) {
                    // Update options for select/radio/checkbox
                    if (['select', 'radio', 'checkbox'].includes(typeSelect.value)) {
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

            function removeFormField(index) {
                if (formFieldsData[index] && formFieldsData[index].key) {
                    removeFromUsedKeys(formFieldsData[index].key, false);
                }

                formFieldsData.splice(index, 1);
                updateFormFieldsDisplay();
                updateFormFieldsVisibility();
            }

            function updateFormFieldsDisplay() {
                formFieldsContainer.innerHTML = '';
                fieldCounter = 0;
                // Rebuild used keys set
                usedFieldKeys.clear();
                formFieldsData.forEach((data, index) => {
                    if (data.key) {
                        usedFieldKeys.add(data.key);
                    }
                    addFormField(data);
                });
            }

            function updateFormFieldsVisibility() {
                formFieldsEmpty.style.display = formFieldsData.length === 0 ? 'block' : 'none';
            }

            function loadDefaultFormFields() {
                // Clear existing data and keys
                formFieldsData = [];
                usedFieldKeys.clear();

                const defaultFields = [
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
                    },
                    {
                        name: 'Fakultas',
                        key: 'fakultas',
                        type: 'text',
                        icon: 'fas fa-university',
                        placeholder: 'Contoh: Teknik',
                        position: 'academic',
                        validation: 'required|min:3|max:50',
                        required: true,
                        options: []
                    },
                    {
                        name: 'Jurusan',
                        key: 'jurusan',
                        type: 'text',
                        icon: 'fas fa-graduation-cap',
                        placeholder: 'Contoh: Teknik Informatika',
                        position: 'academic',
                        validation: 'required|min:3|max:100',
                        required: true,
                        options: []
                    },
                    {
                        name: 'Semester',
                        key: 'semester',
                        type: 'select',
                        icon: 'fas fa-calendar-check',
                        placeholder: '-- Pilih Semester --',
                        position: 'academic',
                        validation: 'required|between:1,14',
                        required: true,
                        options: [
                            { value: '1', label: 'Semester 1' },
                            { value: '2', label: 'Semester 2' },
                            { value: '3', label: 'Semester 3' },
                            { value: '4', label: 'Semester 4' },
                            { value: '5', label: 'Semester 5' },
                            { value: '6', label: 'Semester 6' },
                            { value: '7', label: 'Semester 7' },
                            { value: '8', label: 'Semester 8' }
                        ]
                    },
                    {
                        name: 'IPK',
                        key: 'ipk',
                        type: 'number',
                        icon: 'fas fa-chart-line',
                        placeholder: '3.50',
                        position: 'academic',
                        validation: 'required|numeric|between:0,4',
                        required: true,
                        options: []
                    },
                    {
                        name: 'Alasan Mendaftar',
                        key: 'alasan_mendaftar',
                        type: 'textarea',
                        icon: 'fas fa-comment-alt',
                        placeholder: 'Jelaskan alasan dan motivasi Anda mendaftar beasiswa ini...',
                        position: 'additional',
                        validation: 'required|min:50|max:1000',
                        required: true,
                        options: []
                    }
                ];

                defaultFields.forEach(field => {
                    formFieldsData.push(field);
                });

                updateFormFieldsDisplay();
                updateFormFieldsVisibility();
            }

            // Documents Management - Fixed
            function addDocumentItem(data = null) {
                documentCounter++;
                const template = document.getElementById('documentTemplate');
                const clone = template.content.cloneNode(true);

                clone.querySelector('.item-number').textContent = documentCounter;
                const documentElement = clone.querySelector('.dynamic-item');
                documentElement.setAttribute('data-index', documentsData.length);

                setupDocumentEvents(clone, documentsData.length);

                if (data) {
                    fillDocumentData(clone, data);
                    if (data.key) {
                        usedDocumentKeys.add(data.key);
                    }
                }

                documentsContainer.appendChild(clone);

                const newDocData = data || {
                    name: '',
                    key: '',
                    icon: '',
                    color: '',
                    formats: [],
                    max_size: 5,
                    description: '',
                    required: true
                };

                documentsData.push(newDocData);
                updateDocumentsVisibility();

                const newItem = documentsContainer.lastElementChild;
                newItem.classList.add('new-item');
                setTimeout(() => newItem.classList.remove('new-item'), 500);
                newItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
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

                const previewIcon = documentElement.querySelector('.preview-icon i');
                const previewName = documentElement.querySelector('.preview-name');
                const previewDetails = documentElement.querySelector('.preview-details');
                const previewRequired = documentElement.querySelector('.preview-required');

                function updateDocumentData() {
                    if (documentsData[index]) {
                        // Generate key if name changed
                        if (nameInput.value && nameInput.value !== documentsData[index].name) {
                            if (documentsData[index].key) {
                                removeFromUsedKeys(documentsData[index].key, true);
                            }
                            documentsData[index].key = generateUniqueDocumentKey(nameInput.value);
                            keyDisplay.textContent = documentsData[index].key;
                        }

                        documentsData[index].name = nameInput.value;
                        documentsData[index].icon = iconSelect.value;
                        documentsData[index].color = colorSelect.value;
                        documentsData[index].formats = Array.from(formatCheckboxes)
                            .filter(cb => cb.checked)
                            .map(cb => cb.value);
                        documentsData[index].max_size = parseInt(maxSizeInput.value) || 5;
                        documentsData[index].description = descriptionInput.value;
                        documentsData[index].required = requiredCheckbox.checked;

                        updatePreview();
                    }
                }

                function updatePreview() {
                    const data = documentsData[index];
                    if (!data) return;

                    // Reset icon classes
                    previewIcon.className = '';
                    previewIcon.classList.add(data.icon || 'fas', 'fa-file');
                    previewIcon.classList.add(`text-${data.color || 'secondary'}`);
                    previewIcon.style.fontSize = '2rem';

                    previewName.textContent = data.name || 'Nama Dokumen';

                    const formats = data.formats.length > 0
                        ? data.formats.map(f => f.toUpperCase()).join(', ')
                        : '-';
                    previewDetails.textContent = `Format: ${formats} | Max: ${data.max_size}MB`;

                    if (data.required) {
                        previewRequired.innerHTML = '<span class="badge bg-warning text-dark">Wajib</span>';
                    } else {
                        previewRequired.innerHTML = '<span class="badge bg-secondary">Opsional</span>';
                    }
                }

                // Event listeners
                nameInput.addEventListener('input', updateDocumentData);
                iconSelect.addEventListener('change', updateDocumentData);
                colorSelect.addEventListener('change', updateDocumentData);
                maxSizeInput.addEventListener('input', updateDocumentData);
                descriptionInput.addEventListener('input', updateDocumentData);
                requiredCheckbox.addEventListener('change', updateDocumentData);

                formatCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateDocumentData);
                });

                removeBtn.addEventListener('click', () => {
                    if (confirm('Yakin ingin hapus dokumen ini?')) {
                        removeDocumentItem(index);
                    }
                });

                // Initial update
                updatePreview();
            }

            function fillDocumentData(documentElement, data) {
                documentElement.querySelector('.document-name').value = data.name || '';
                documentElement.querySelector('.document-key-display').textContent = data.key || 'file_akan_dibuat_otomatis';
                documentElement.querySelector('.document-icon').value = data.icon || '';
                documentElement.querySelector('.document-color').value = data.color || '';
                documentElement.querySelector('.document-max-size').value = data.max_size || 5;
                documentElement.querySelector('.document-description').value = data.description || '';
                documentElement.querySelector('.document-required').checked = data.required !== false;

                if (data.formats && Array.isArray(data.formats)) {
                    const formatCheckboxes = documentElement.querySelectorAll('.document-format');
                    formatCheckboxes.forEach(checkbox => {
                        checkbox.checked = data.formats.includes(checkbox.value);
                    });
                }
            }

            function removeDocumentItem(index) {
                if (documentsData[index] && documentsData[index].key) {
                    removeFromUsedKeys(documentsData[index].key, true);
                }

                documentsData.splice(index, 1);
                updateDocumentsDisplay();
                updateDocumentsVisibility();
            }

            function updateDocumentsDisplay() {
                documentsContainer.innerHTML = '';
                documentCounter = 0;
                // Rebuild used keys set
                usedDocumentKeys.clear();
                documentsData.forEach((data, index) => {
                    if (data.key) {
                        usedDocumentKeys.add(data.key);
                    }
                    addDocumentItem(data);
                });
            }

            function updateDocumentsVisibility() {
                documentsEmpty.style.display = documentsData.length === 0 ? 'block' : 'none';
            }

            function loadDefaultDocuments() {
                // Clear existing data and keys
                documentsData = [];
                usedDocumentKeys.clear();

                const defaultDocs = [
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
                    },
                    {
                        name: 'Kartu Keluarga',
                        key: 'file_kartu_keluarga',
                        icon: 'fas fa-users',
                        color: 'green',
                        formats: ['pdf', 'jpg', 'jpeg', 'png'],
                        max_size: 5,
                        description: 'Kartu Keluarga',
                        required: true
                    }
                ];

                defaultDocs.forEach(doc => {
                    documentsData.push(doc);
                });

                updateDocumentsDisplay();
                updateDocumentsVisibility();
            }

            // Reset Form
            function resetForm() {
                if (confirm('Yakin ingin reset semua data? Semua yang sudah diisi akan hilang.')) {
                    beasiswaForm.reset();
                    formFieldsData = [];
                    documentsData = [];
                    usedFieldKeys.clear();
                    usedDocumentKeys.clear();
                    updateFormFieldsDisplay();
                    updateDocumentsDisplay();
                    updateFormFieldsVisibility();
                    updateDocumentsVisibility();
                }
            }

            // Form Submission
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

                // Remove existing hidden inputs
                const existingInputs = beasiswaForm.querySelectorAll('input[name^="form_fields"], input[name^="documents"]');
                existingInputs.forEach(input => input.remove());

                // Add form fields data as hidden inputs
                formFieldsData.forEach((field, index) => {
                    const fields = ['name', 'key', 'type', 'icon', 'placeholder', 'position', 'validation', 'required'];

                    fields.forEach(fieldName => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `form_fields[${index}][${fieldName}]`;

                        if (fieldName === 'required') {
                            input.value = field[fieldName] ? '1' : '0';
                        } else {
                            input.value = field[fieldName] || '';
                        }

                        beasiswaForm.appendChild(input);
                    });

                    // Add options for select/radio/checkbox fields
                    if (field.options && Array.isArray(field.options)) {
                        field.options.forEach((option, optionIndex) => {
                            const valueInput = document.createElement('input');
                            valueInput.type = 'hidden';
                            valueInput.name = `form_fields[${index}][options][${optionIndex}][value]`;
                            valueInput.value = option.value || '';
                            beasiswaForm.appendChild(valueInput);

                            const labelInput = document.createElement('input');
                            labelInput.type = 'hidden';
                            labelInput.name = `form_fields[${index}][options][${optionIndex}][label]`;
                            labelInput.value = option.label || '';
                            beasiswaForm.appendChild(labelInput);
                        });
                    }
                });

                // Add documents data as hidden inputs
                documentsData.forEach((doc, index) => {
                    const fields = ['name', 'key', 'icon', 'color', 'max_size', 'description', 'required'];

                    fields.forEach(field => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `documents[${index}][${field}]`;

                        if (field === 'required') {
                            input.value = doc[field] ? '1' : '0';
                        } else {
                            input.value = doc[field] || '';
                        }

                        beasiswaForm.appendChild(input);
                    });

                    // Add formats array as hidden inputs
                    if (doc.formats && Array.isArray(doc.formats)) {
                        doc.formats.forEach((format, formatIndex) => {
                            const formatInput = document.createElement('input');
                            formatInput.type = 'hidden';
                            formatInput.name = `documents[${index}][formats][${formatIndex}]`;
                            formatInput.value = format;
                            beasiswaForm.appendChild(formatInput);
                        });
                    }
                });

                // Show loading state
                const submitBtn = beasiswaForm.querySelector('button[type="submit"]');
                const originalContent = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';

                // Submit form via fetch or normal submission
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
                        // Check if response indicates success
                        if (data.includes('Beasiswa berhasil ditambahkan') || data.includes('redirect')) {
                            // Redirect to index page
                            window.location.href = beasiswaForm.action.replace('/store', '');
                        } else {
                            // Handle form errors by submitting normally
                            beasiswaForm.submit();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Fallback to normal form submission
                        beasiswaForm.submit();
                    })
                    .finally(() => {
                        // Reset loading state
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalContent;
                    });
            });

            // Helper function to validate form data
            function validateFormData() {
                let isValid = true;
                let errors = [];

                // Validate form fields
                formFieldsData.forEach((field, index) => {
                    if (!field.name || field.name.trim() === '') {
                        errors.push(`Field ${index + 1}: Nama field wajib diisi`);
                        isValid = false;
                    }
                    if (!field.type || field.type === '') {
                        errors.push(`Field ${index + 1}: Tipe field wajib dipilih`);
                        isValid = false;
                    }
                    if (!field.icon || field.icon === '') {
                        errors.push(`Field ${index + 1}: Icon wajib dipilih`);
                        isValid = false;
                    }
                    if (!field.position || field.position === '') {
                        errors.push(`Field ${index + 1}: Posisi wajib dipilih`);
                        isValid = false;
                    }

                    // Validate options for select/radio/checkbox
                    if (['select', 'radio', 'checkbox'].includes(field.type)) {
                        if (!field.options || field.options.length === 0) {
                            errors.push(`Field ${index + 1}: Minimal harus ada 1 opsi untuk tipe ${field.type}`);
                            isValid = false;
                        } else {
                            field.options.forEach((option, optIndex) => {
                                if (!option.value || !option.label) {
                                    errors.push(`Field ${index + 1}, Opsi ${optIndex + 1}: Value dan label wajib diisi`);
                                    isValid = false;
                                }
                            });
                        }
                    }
                });

                // Validate documents
                documentsData.forEach((doc, index) => {
                    if (!doc.name || doc.name.trim() === '') {
                        errors.push(`Dokumen ${index + 1}: Nama dokumen wajib diisi`);
                        isValid = false;
                    }
                    if (!doc.icon || doc.icon === '') {
                        errors.push(`Dokumen ${index + 1}: Icon wajib dipilih`);
                        isValid = false;
                    }
                    if (!doc.color || doc.color === '') {
                        errors.push(`Dokumen ${index + 1}: Warna icon wajib dipilih`);
                        isValid = false;
                    }
                    if (!doc.formats || doc.formats.length === 0) {
                        errors.push(`Dokumen ${index + 1}: Minimal harus ada 1 format file`);
                        isValid = false;
                    }
                    if (!doc.max_size || doc.max_size < 1 || doc.max_size > 10) {
                        errors.push(`Dokumen ${index + 1}: Ukuran maksimal harus antara 1-10 MB`);
                        isValid = false;
                    }
                });

                if (!isValid) {
                    alert('Error validasi:\n' + errors.join('\n'));
                }

                return isValid;
            }

            // Export data functions for debugging
            window.getFormFieldsData = function () {
                return formFieldsData;
            };

            window.getDocumentsData = function () {
                return documentsData;
            };

            window.getUsedKeys = function () {
                return {
                    fieldKeys: Array.from(usedFieldKeys),
                    documentKeys: Array.from(usedDocumentKeys)
                };
            };
        });
    </script>
@endsection