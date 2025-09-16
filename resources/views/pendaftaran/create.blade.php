@extends('layouts.app')

@section('title', 'Daftar Beasiswa - ' . $beasiswa->nama_beasiswa)

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <h2 class="text-3xl md:text-4xl font-bold text-orange-600 mb-2">
                    <i class="fas fa-graduation-cap mr-2"></i>Pendaftaran Beasiswa
                </h2>
                <p class="text-amber-700 text-lg">Lengkapi formulir di bawah untuk mendaftar beasiswa</p>
            </div>

            <!-- Main Registration Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-0">
                <div class="bg-gradient-to-r from-orange-500 to-amber-600 py-6 px-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-4 md:mb-0">
                            <h4 class="text-xl font-semibold text-white mb-1">
                                <i class="fas fa-trophy text-yellow-300 mr-2"></i>{{ $beasiswa->nama_beasiswa }}
                            </h4>
                            <small class="text-white opacity-90">
                                <i class="fas fa-calendar-alt mr-1"></i>Batas pendaftaran:
                                {{ \Carbon\Carbon::parse($beasiswa->tanggal_tutup)->format('d M Y') }}
                            </small>
                        </div>
                        <div class="scholarship-amount">
                            <span
                                class="bg-gradient-to-r from-yellow-500 to-orange-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
                                <i class="fas fa-money-bill-wave mr-1"></i>Rp
                                {{ number_format($beasiswa->jumlah_dana, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-10">
                    <!-- Scholarship Info Section -->
                    <div
                        class="bg-gradient-to-r from-amber-50 to-orange-100 rounded-xl p-6 mb-8 border-l-4 border-orange-500">
                        <h5
                            class="text-lg font-semibold text-amber-800 mb-4 border-b-2 border-orange-500 pb-2 inline-block">
                            <i class="fas fa-info-circle text-orange-400 mr-2"></i>Informasi Beasiswa
                        </h5>
                        <div class="bg-white rounded-lg p-6 shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-amber-700 mb-1">Dana Beasiswa</label>
                                    <p class="text-yellow-600 font-bold text-lg">
                                        Rp {{ number_format($beasiswa->jumlah_dana, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-amber-700 mb-1">Batas Waktu
                                        Pendaftaran</label>
                                    <p class="text-orange-600 font-bold text-lg">
                                        {{ \Carbon\Carbon::parse($beasiswa->tanggal_tutup)->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-amber-700 mb-2">Persyaratan</label>
                                <div
                                    class="bg-amber-50 border border-orange-200 rounded-lg p-4 text-amber-800 text-sm md:text-base whitespace-pre-line">
                                    {{ $beasiswa->persyaratan }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Form -->
                    <div class="form-section">
                        <h5
                            class="text-lg font-semibold text-amber-800 mb-6 border-b-2 border-orange-500 pb-2 inline-block">
                            <i class="fas fa-edit text-orange-500 mr-2"></i>Formulir Pendaftaran
                        </h5>

                        <form method="POST" action="{{ route('pendaftar.store', $beasiswa) }}" enctype="multipart/form-data"
                            id="registrationForm">
                            @csrf

                            @php
                                $formFields = $beasiswa->getFormFieldsByPosition();
                                $positionTitles = [
                                    'personal' => ['title' => 'Data Personal', 'icon' => 'fas fa-user-circle', 'color' => 'amber'],
                                    'academic' => ['title' => 'Data Akademik', 'icon' => 'fas fa-graduation-cap', 'color' => 'orange'],
                                    'additional' => ['title' => 'Data Tambahan', 'icon' => 'fas fa-plus-circle', 'color' => 'yellow']
                                ];
                            @endphp

                                @foreach($formFields as $position => $fields)
                                    @if(count($fields) > 0)
                                        @php
                                            $positionConfig = $positionTitles[$position];
                                            $bgColor = $positionConfig['color'] . '-50';
                                            $borderColor = $positionConfig['color'] . '-500';
                                            $textColor = $positionConfig['color'] . '-800';
                                            $iconColor = $positionConfig['color'] === 'amber' ? 'orange-400' : $positionConfig['color'] . '-400';
                                        @endphp

                                        <!-- Dynamic Form Section -->
                                        <div class="bg-{{ $bgColor }} rounded-xl p-6 mb-6 border-l-4 border-{{ $borderColor }}">
                                            <h6 class="text-md font-semibold text-{{ $textColor }} mb-4">
                                                <i class="{{ $positionConfig['icon'] }} text-{{ $iconColor }} mr-2"></i>{{ $positionConfig['title'] }}
                                            </h6>
                                            <div class="bg-white rounded-lg p-6 shadow-sm">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    @foreach($fields as $field)
                                                        <div class="{{ in_array($field['type'], ['textarea']) ? 'md:col-span-2' : '' }}">
                                                            @include('components.form-field', [
                                                                'field' => $field,
                                                                'old_value' => old($field['key']),
                                                                'error' => $errors->first($field['key'])
                                                            ])
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Dynamic Document Upload Section -->
                                @if ($beasiswa->required_documents && count($beasiswa->required_documents) > 0)
                                    <div class="bg-orange-50 rounded-xl p-6 mb-6 border-l-4 border-orange-600">
                                        <h6 class="text-md font-semibold text-orange-800 mb-4">
                                            <i class="fas fa-folder-upload text-orange-600 mr-2"></i>Dokumen Pendukung
                                        </h6>
                                        <div class="bg-white rounded-lg p-6 shadow-sm">
                                            <div class="grid grid-cols-1 md:grid-cols-{{ count($beasiswa->required_documents) >= 3 ? '3' : (count($beasiswa->required_documents) == 2 ? '2' : '1') }} gap-4">
                                                @foreach ($beasiswa->required_documents as $document)
                                                    @include('components.document-upload', [
                                                        'document' => $document,
                                                        'error' => $errors->first($document['key'])
                                                    ])
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Terms and Submit -->
                                <div class="bg-amber-50 rounded-xl p-6">
                                    <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-4 mb-6">
                                        <div class="flex items-start">
                                            <input class="mt-1 mr-3" type="checkbox" id="terms" required>
                                            <label class="text-amber-800 text-sm" for="terms">
                                                Saya menyatakan bahwa data yang saya berikan adalah <strong>benar dan valid</strong>.
                                                Saya bersedia menerima konsekuensi jika terbukti memberikan data palsu.
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                                        <div class="form-info">
                                            <small class="text-orange-600 text-sm">
                                                <i class="fas fa-shield-alt mr-1"></i>
                                                Data Anda akan dijaga kerahasiaannya
                                            </small>
                                        </div>
                                        <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                                            <a href="{{ route('home') }}" class="bg-white border-2 border-orange-400 text-orange-700 px-6 py-3 rounded-full font-semibold hover:bg-orange-50 transition-all duration-300 text-center">
                                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                                            </a>
                                            <button type="submit" class="bg-gradient-to-r from-orange-500 to-amber-600 text-white px-6 py-3 rounded-full font-semibold hover:from-orange-600 hover:to-amber-700 transition-all duration-300 shadow-md hover:shadow-lg text-center">
                                                <i class="fas fa-paper-plane mr-2"></i>Daftar Sekarang
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tips Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <div class="bg-white border-2 border-orange-500 rounded-xl p-6 shadow-md transition-all duration-300 hover:shadow-lg">
                        <h6 class="text-lg font-semibold text-orange-600 mb-4">
                            <i class="fas fa-lightbulb mr-2"></i>Tips Sukses
                        </h6>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-500 mt-1 mr-2"></i>
                                <span class="text-amber-700 text-sm">Pastikan dokumen berkualitas baik dan jelas</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-500 mt-1 mr-2"></i>
                                <span class="text-amber-700 text-sm">Isi semua field dengan data yang benar dan lengkap</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-yellow-500 mt-1 mr-2"></i>
                                <span class="text-amber-700 text-sm">Periksa kembali semua data sebelum mengirim</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white border-2 border-yellow-400 rounded-xl p-6 shadow-md transition-all duration-300 hover:shadow-lg">
                        <h6 class="text-lg font-semibold text-yellow-600 mb-4">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Persyaratan Dokumen
                        </h6>
                        <ul class="space-y-2">
                            @if ($beasiswa->required_documents && count($beasiswa->required_documents) > 0)
                                @foreach ($beasiswa->required_documents as $document)
                                    <li class="flex items-start">
                                        <i class="{{ $document['icon'] }} text-{{ $document['color'] }}-500 mt-1 mr-2"></i>
                                        <span class="text-amber-700 text-sm">
                                            <strong>{{ $document['name'] }}:</strong>
                                            {{ strtoupper(implode(', ', $document['formats'])) }}
                                            (Max {{ $document['max_size'] }}MB)
                                        </span>
                                    </li>
                                @endforeach
                            @else
                                <li class="flex items-start">
                                    <i class="fas fa-info text-orange-500 mt-1 mr-2"></i>
                                    <span class="text-amber-700 text-sm">Tidak ada dokumen khusus yang diperlukan</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    <script>
    // File upload handling
    function handleFileUpload(documentKey) {
        const fileInput = document.getElementById(documentKey);
        const card = document.getElementById('card-' + documentKey);
        const icon = document.getElementById('icon-' + documentKey);
        const desc = document.getElementById('desc-' + documentKey);
        const status = document.getElementById('status-' + documentKey);
        const filename = document.getElementById('filename-' + documentKey);

        if (fileInput.files && fileInput.files[0]) {
            const file = fileInput.files[0];

            // Validasi ukuran file
            const maxSizeText = desc.textContent;
            const maxSizeMatch = maxSizeText.match(/Max: (\d+)MB/);
            const maxSize = (maxSizeMatch ? parseInt(maxSizeMatch[1]) : 5) * 1024 * 1024;

            if (file.size > maxSize) {
                alert('Ukuran file terlalu besar. Maksimal ' + Math.round(maxSize / 1024 / 1024) + 'MB.');
                fileInput.value = '';
                return;
            }

            // Update card appearance
            card.classList.remove('border-orange-300', 'hover:border-orange-500', 'hover:bg-orange-50');
            card.classList.add('border-orange-600', 'bg-orange-100');

            // Update icon
            icon.className = 'fas fa-check-circle';
            icon.parentElement.className = 'file-icon text-orange-600 text-4xl mb-4';

            // Show status, hide description
            desc.classList.add('hidden');
            status.classList.remove('hidden');
            filename.textContent = file.name;

            // Animation
            card.style.animation = 'pulse 0.5s ease-in-out';
            setTimeout(() => card.style.animation = '', 500);
        }
    }

    function previewFile(documentKey) {
        const fileInput = document.getElementById(documentKey);
        if (fileInput.files && fileInput.files[0]) {
            const file = fileInput.files[0];
            const fileType = file.type;

            if (fileType.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const newWindow = window.open();
                    newWindow.document.write(
                        '<html><head><title>' + file.name + '</title></head>' +
                        '<body style="margin:0; display:flex; justify-content:center; align-items:center; background:#f0f0f0;">' +
                        '<img src="' + e.target.result + '" style="max-width:100%; max-height:100%; object-fit:contain;">' +
                        '</body></html>'
                    );
                };
                reader.readAsDataURL(file);
            } else if (fileType === 'application/pdf') {
                const fileURL = URL.createObjectURL(file);
                window.open(fileURL, '_blank');
            } else {
                alert('Preview tidak tersedia untuk tipe file ini.');
            }
        }
    }

    function removeFile(documentKey) {
        if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
            const fileInput = document.getElementById(documentKey);
            fileInput.value = '';
            resetFileStatus(documentKey);
        }
    }

    function resetFileStatus(documentKey) {
        const card = document.getElementById('card-' + documentKey);
        const icon = document.getElementById('icon-' + documentKey);
        const desc = document.getElementById('desc-' + documentKey);
        const status = document.getElementById('status-' + documentKey);

        // Reset appearance
        card.classList.remove('border-orange-600', 'bg-orange-100');
        card.classList.add('border-orange-300', 'hover:border-orange-500', 'hover:bg-orange-50');

        // Reset icon to original from document data
        const originalIconClass = card.dataset.originalIcon || 'fas fa-file-upload';
        const originalColor = card.dataset.originalColor || 'orange';
        icon.className = originalIconClass;
        icon.parentElement.className = `file-icon text-${originalColor}-500 text-4xl mb-4`;

        // Show description, hide status
        desc.classList.remove('hidden');
        status.classList.add('hidden');
    }

    // DOM Content Loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            .upload-status {
                animation: fadeIn 0.3s ease-in-out;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);

        // Form submission handling
        const form = document.getElementById('registrationForm');
        const submitBtn = document.querySelector('button[type="submit"]');

        if (form) {
            form.addEventListener('submit', function(e) {
                const termsCheckbox = document.getElementById('terms');
                if (!termsCheckbox.checked) {
                    e.preventDefault();
                    alert('Anda harus menyetujui syarat dan ketentuan terlebih dahulu.');
                    termsCheckbox.focus();
                    return;
                }

                // Loading state
                if (submitBtn) {
                    submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';

                    setTimeout(() => {
                        submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Daftar Sekarang';
                    }, 30000);
                }
            });
        }

        // Store original icon data for reset functionality
        const cards = document.querySelectorAll('.file-upload-card');
        cards.forEach(card => {
            const icon = card.querySelector('.file-icon i');
            if (icon) {
                card.dataset.originalIcon = icon.className;
                const colorClass = Array.from(icon.classList).find(cls => cls.includes('text-'));
                if (colorClass) {
                    card.dataset.originalColor = colorClass.replace('text-', '').replace('-500', '');
                }
            }
        });

        // Scroll to first error field
        const errorFields = document.querySelectorAll('.border-red-500');
        if (errorFields.length > 0) {
            errorFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
    </script>
@endsection