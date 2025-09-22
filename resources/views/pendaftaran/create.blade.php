@extends('layouts.app')

@section('title', 'Daftar Beasiswa - ' . $beasiswa->nama_beasiswa)

@section('content')
<style>
    /* Enhanced Input Styles */
    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #fed7aa;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        font-size: 14px;
        line-height: 1.5;
    }
    
    .form-input:hover {
        border-color: #fdba74;
        box-shadow: 0 2px 8px rgba(251, 146, 60, 0.08);
    }
    
    .form-input:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1), 0 4px 12px rgba(251, 146, 60, 0.15);
        transform: translateY(-1px);
    }
    
    .form-input.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .form-input.success {
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }
    
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #fed7aa;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
        font-size: 14px;
    }
    
    .form-textarea:hover {
        border-color: #fdba74;
        box-shadow: 0 2px 8px rgba(251, 146, 60, 0.08);
    }
    
    .form-textarea:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1), 0 4px 12px rgba(251, 146, 60, 0.15);
        transform: translateY(-1px);
    }
    
    .form-select {
        width: 100%;
        padding: 12px 40px 12px 16px;
        border: 2px solid #fed7aa;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        cursor: pointer;
        font-size: 14px;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23f97316' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
    }
    
    .form-select:hover {
        border-color: #fdba74;
        box-shadow: 0 2px 8px rgba(251, 146, 60, 0.08);
    }
    
    .form-select:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1), 0 4px 12px rgba(251, 146, 60, 0.15);
        transform: translateY(-1px);
    }
    
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #92400e;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
    
    .form-label i {
        margin-right: 8px;
        color: #f97316;
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-group.full-width {
        grid-column: span 2;
    }
    
    /* Radio and Checkbox Improvements */
    .radio-option, .checkbox-option {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border: 2px solid #fed7aa;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
        margin-bottom: 8px;
    }
    
    .radio-option:hover, .checkbox-option:hover {
        border-color: #fdba74;
        background-color: #fff7ed;
        transform: translateX(4px);
        box-shadow: 0 2px 8px rgba(251, 146, 60, 0.1);
    }
    
    .radio-option input, .checkbox-option input {
        margin-right: 12px;
        width: 18px;
        height: 18px;
        accent-color: #f97316;
    }
    
    .radio-option input:focus, .checkbox-option input:focus {
        outline: 2px solid #f97316;
        outline-offset: 2px;
    }
    
    .radio-option span, .checkbox-option span {
        color: #92400e;
        font-weight: 500;
        flex-grow: 1;
    }
    
    .radio-option.selected, .checkbox-option.selected {
        border-color: #f97316;
        background-color: #fff7ed;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }
    
    /* File Upload Enhancements */
    .file-upload-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .file-upload-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .file-upload-card.uploaded {
        border-color: #22c55e;
        background-color: #f0fdf4;
    }
    
    /* Error message styles */
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
    }
    
    .success-message {
        color: #22c55e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
    }
</style>

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
                        <span class="bg-gradient-to-r from-yellow-500 to-orange-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
                            <i class="fas fa-money-bill-wave mr-1"></i>Rp {{ number_format($beasiswa->jumlah_dana, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-10">
                <!-- Scholarship Info Section -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-100 rounded-xl p-6 mb-8 border-l-4 border-orange-500">
                    <h5 class="text-lg font-semibold text-amber-800 mb-4 border-b-2 border-orange-500 pb-2 inline-block">
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
                                <label class="block text-sm font-medium text-amber-700 mb-1">Batas Waktu Pendaftaran</label>
                                <p class="text-orange-600 font-bold text-lg">
                                    {{ \Carbon\Carbon::parse($beasiswa->tanggal_tutup)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-amber-700 mb-2">Persyaratan</label>
                            <div class="bg-amber-50 border border-orange-200 rounded-lg p-4 text-amber-800 text-sm md:text-base whitespace-pre-line">
                                {{ $beasiswa->persyaratan }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Form -->
                <div class="form-section">
                    <h5 class="text-lg font-semibold text-amber-800 mb-6 border-b-2 border-orange-500 pb-2 inline-block">
                        <i class="fas fa-edit text-orange-500 mr-2"></i>Formulir Pendaftaran
                    </h5>

                    <form method="POST" action="{{ route('pendaftar.store', $beasiswa) }}" enctype="multipart/form-data" id="registrationForm">
                        @csrf

                        {{-- Render Dynamic Form Fields by Position --}}
                        @if($beasiswa->form_fields && count($beasiswa->form_fields) > 0)
                            @foreach(['personal', 'academic', 'additional'] as $position)
                                @php
                                    $positionFields = collect($beasiswa->form_fields)->where('position', $position);
                                    $positionTitles = [
                                        'personal' => 'Data Personal',
                                        'academic' => 'Data Akademik', 
                                        'additional' => 'Data Tambahan'
                                    ];
                                    $positionIcons = [
                                        'personal' => 'fas fa-user',
                                        'academic' => 'fas fa-graduation-cap',
                                        'additional' => 'fas fa-info-circle'
                                    ];
                                @endphp
                                
                                @if($positionFields->count() > 0)
                                    <div class="bg-amber-50 rounded-xl p-6 mb-6 border-l-4 border-orange-500 transform hover:translate-x-1 transition-all duration-300">
                                        <h6 class="text-lg font-bold text-orange-800 mb-6 flex items-center">
                                            <div class="bg-gradient-to-r from-orange-500 to-amber-500 text-white w-10 h-10 rounded-full flex items-center justify-center mr-3 shadow-lg">
                                                <i class="{{ $positionIcons[$position] }} text-sm"></i>
                                            </div>
                                            {{ $positionTitles[$position] }}
                                        </h6>
                                        <div class="bg-white rounded-xl p-8 shadow-sm border border-orange-100">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                                @foreach($positionFields as $field)
                                                    <div class="form-group {{ $field['type'] === 'textarea' ? 'full-width' : '' }}">
                                                        <label for="{{ $field['key'] }}" class="form-label">
                                                            <i class="{{ $field['icon'] }} text-orange-500 mr-2"></i>{{ $field['name'] }}
                                                            @if($field['required'])<span class="text-red-500 ml-1">*</span>@endif
                                                        </label>
                                                        
                                                        @switch($field['type'])
                                                            @case('textarea')
                                                                <textarea 
                                                                    id="{{ $field['key'] }}" 
                                                                    name="{{ $field['key'] }}"
                                                                    class="form-textarea @error($field['key']) error @enderror"
                                                                    placeholder="{{ $field['placeholder'] }}"
                                                                    rows="4"
                                                                    {{ $field['required'] ? 'required' : '' }}>{{ old($field['key']) }}</textarea>
                                                                @break
                                                            
                                                            @case('select')
                                                                <select 
                                                                    id="{{ $field['key'] }}" 
                                                                    name="{{ $field['key'] }}"
                                                                    class="form-select @error($field['key']) error @enderror"
                                                                    {{ $field['required'] ? 'required' : '' }}>
                                                                    <option value="">{{ $field['placeholder'] ?: '-- Pilih --' }}</option>
                                                                    @foreach($field['options'] ?? [] as $option)
                                                                        <option value="{{ $option['value'] }}" {{ old($field['key']) == $option['value'] ? 'selected' : '' }}>
                                                                            {{ $option['label'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @break

                                                            @case('radio')
                                                                <div class="space-y-2">
                                                                    @foreach($field['options'] ?? [] as $option)
                                                                        <label class="radio-option" data-value="{{ $option['value'] }}">
                                                                            <input 
                                                                                type="radio" 
                                                                                name="{{ $field['key'] }}" 
                                                                                value="{{ $option['value'] }}"
                                                                                {{ old($field['key']) == $option['value'] ? 'checked' : '' }}
                                                                                {{ $field['required'] ? 'required' : '' }}
                                                                                onchange="updateRadioSelection('{{ $field['key'] }}', this)">
                                                                            <span>{{ $option['label'] }}</span>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                                @break

                                                            @case('checkbox')
                                                                <div class="space-y-2">
                                                                    @foreach($field['options'] ?? [] as $option)
                                                                        <label class="checkbox-option" data-value="{{ $option['value'] }}">
                                                                            <input 
                                                                                type="checkbox" 
                                                                                name="{{ $field['key'] }}[]" 
                                                                                value="{{ $option['value'] }}"
                                                                                {{ in_array($option['value'], old($field['key'], [])) ? 'checked' : '' }}
                                                                                onchange="updateCheckboxSelection(this)">
                                                                            <span>{{ $option['label'] }}</span>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                                @break

                                                            @default
                                                                <input 
                                                                    type="{{ $field['type'] }}" 
                                                                    id="{{ $field['key'] }}" 
                                                                    name="{{ $field['key'] }}"
                                                                    class="form-input @error($field['key']) error @enderror"
                                                                    placeholder="{{ $field['placeholder'] }}"
                                                                    value="{{ old($field['key']) }}"
                                                                    {{ $field['required'] ? 'required' : '' }}
                                                                    @if($field['type'] === 'number' && $field['key'] === 'ipk')
                                                                        step="0.01" min="0" max="4"
                                                                    @elseif($field['type'] === 'number' && $field['key'] === 'semester')
                                                                        min="1" max="14"
                                                                    @endif>
                                                        @endswitch
                                                        
                                                        @error($field['key'])
                                                            <div class="error-message">
                                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            {{-- Fallback jika tidak ada form fields dinamis --}}
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <p class="text-red-700">Form pendaftaran belum dikonfigurasi oleh admin. Silakan hubungi administrator.</p>
                                </div>
                            </div>
                        @endif

                        <!-- Dynamic Document Upload Section -->
                        @if ($beasiswa->required_documents && count($beasiswa->required_documents) > 0)
                            @php
                                $documents = is_array($beasiswa->required_documents) 
                                    ? collect($beasiswa->required_documents) 
                                    : $beasiswa->required_documents;
                                $uniqueDocuments = $documents->unique('key')->take(6);
                            @endphp
                            
                            @if($uniqueDocuments->count() > 0)
                                <div class="bg-orange-50 rounded-xl p-6 mb-6 border-l-4 border-orange-600">
                                    <h6 class="text-md font-semibold text-orange-800 mb-4">
                                        <i class="fas fa-folder-upload text-orange-600 mr-2"></i>Dokumen Pendukung
                                    </h6>
                                    <div class="bg-white rounded-lg p-6 shadow-sm">
                                        <div class="grid grid-cols-1 md:grid-cols-{{ $uniqueDocuments->count() >= 3 ? '3' : ($uniqueDocuments->count() == 2 ? '2' : '1') }} gap-6">
                                            @foreach ($uniqueDocuments as $document)
                                                @php
                                                    $docKey = $document['key'] ?? 'doc_' . $loop->index;
                                                    $docName = $document['name'] ?? 'Dokumen';
                                                    $docIcon = $document['icon'] ?? 'fas fa-file-upload';
                                                    $docColor = $document['color'] ?? 'orange';
                                                    $docFormats = $document['formats'] ?? ['pdf'];
                                                    $docMaxSize = $document['max_size'] ?? 5;
                                                    $docRequired = $document['required'] ?? true;
                                                    $docDescription = $document['description'] ?? '';
                                                @endphp
                                                
                                                <div class="file-upload-card border-2 border-dashed border-orange-300 rounded-xl p-6 text-center cursor-pointer"
                                                    id="card-{{ $docKey }}" data-doc-key="{{ $docKey }}">
                                                    <label for="{{ $docKey }}" class="file-label cursor-pointer block">
                                                        <div class="file-icon text-{{ $docColor }}-500 text-4xl mb-4">
                                                            <i class="{{ $docIcon }}" id="icon-{{ $docKey }}"></i>
                                                        </div>
                                                        <div class="file-info">
                                                            <h6 class="file-title font-semibold text-amber-800 mb-1">
                                                                {{ $docName }}
                                                                @if($docRequired) 
                                                                    <span class="text-red-500">*</span>
                                                                @endif
                                                            </h6>
                                                            <small class="file-desc text-orange-600 text-sm block mb-2" id="desc-{{ $docKey }}">
                                                                Format: {{ strtoupper(implode(', ', $docFormats)) }}<br>
                                                                Max: {{ $docMaxSize }}MB
                                                            </small>
                                                            @if (!empty($docDescription))
                                                                <div class="text-xs text-orange-500 mb-3">{{ $docDescription }}</div>
                                                            @endif

                                                            <!-- Status Upload -->
                                                            <div class="upload-status mt-3 hidden" id="status-{{ $docKey }}">
                                                                <div class="flex items-center justify-center mb-2">
                                                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                                    <span class="text-green-700 font-medium text-sm" id="filename-{{ $docKey }}">
                                                                        File berhasil dipilih
                                                                    </span>
                                                                </div>
                                                                <div class="flex justify-center space-x-2 mt-3">
                                                                    <button type="button" onclick="previewFile('{{ $docKey }}')"
                                                                        class="px-3 py-1 text-xs bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                                                        <i class="fas fa-eye mr-1"></i>Lihat
                                                                    </button>
                                                                    <button type="button" onclick="removeFile('{{ $docKey }}')"
                                                                        class="px-3 py-1 text-xs bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                                                        <i class="fas fa-trash mr-1"></i>Hapus
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                    <input type="file" class="hidden file-input" 
                                                        id="{{ $docKey }}" 
                                                        name="{{ $docKey }}"
                                                        accept="{{ collect($docFormats)->map(fn($ext) => '.' . $ext)->implode(',') }}"
                                                        {{ $docRequired ? 'required' : '' }}
                                                        onchange="handleFileUpload('{{ $docKey }}')">
                                                    @error($docKey)
                                                        <div class="error-message mt-2">
                                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Terms and Submit -->
                        <div class="bg-amber-50 rounded-xl p-6">
                            <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-4 mb-6">
                                <div class="flex items-start">
                                    <input class="mt-1 mr-3 w-4 h-4 text-orange-600 border-2 border-orange-300 rounded focus:ring-orange-500 focus:ring-2" 
                                           type="checkbox" id="terms" required>
                                    <label class="text-amber-800 text-sm cursor-pointer" for="terms">
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
                                    <a href="{{ route('home') }}"
                                        class="bg-white border-2 border-orange-400 text-orange-700 px-6 py-3 rounded-full font-semibold hover:bg-orange-50 transition-all duration-300 text-center">
                                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                                    </a>
                                    <button type="submit"
                                        class="bg-gradient-to-r from-orange-500 to-amber-600 text-white px-6 py-3 rounded-full font-semibold hover:from-orange-600 hover:to-amber-700 transition-all duration-300 shadow-md hover:shadow-lg text-center">
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
                        <span class="text-amber-700 text-sm">Tulis alasan mendaftar dengan jujur dan meyakinkan</span>
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
                                <i class="{{ $document['icon'] ?? 'fas fa-file' }} text-{{ $document['color'] ?? 'orange' }}-500 mt-1 mr-2"></i>
                                <span class="text-amber-700 text-sm">
                                    <strong>{{ $document['name'] }}:</strong>
                                    {{ strtoupper(implode(', ', $document['formats'] ?? ['PDF'])) }}
                                    (Max {{ $document['max_size'] ?? 5 }}MB)
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
    // Global variables untuk menghindari duplikasi
    let processedFiles = new Set();
    
    // Input validation dan enhancement
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced input focus effects
        const inputs = document.querySelectorAll('.form-input, .form-textarea, .form-select');
        inputs.forEach(input => {
            // Focus animation
            input.addEventListener('focus', function() {
                this.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.classList.remove('focused');
                
                // Validation feedback
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
            });
            
            // Real-time validation
            input.addEventListener('input', function() {
                this.classList.remove('error');
                
                // Remove any existing error messages
                const existingError = this.parentElement.querySelector('.error-message');
                if (existingError && !existingError.textContent.includes('{{')) {
                    existingError.remove();
                }
            });
        });
        
        // Phone number formatting
        const phoneInput = document.getElementById('no_hp');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 13) {
                    value = value.substring(0, 13);
                }
                this.value = value;
                
                // Visual feedback
                if (value.length >= 10 && value.length <= 13) {
                    this.classList.remove('error');
                    this.classList.add('success');
                } else {
                    this.classList.remove('success');
                }
            });
        }
        
        // NIM validation
        const nimInput = document.getElementById('nim');
        if (nimInput) {
            nimInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
                if (this.value.length > 15) {
                    this.value = this.value.substring(0, 15);
                }
            });
        }
        
        // IPK validation
        const ipkInput = document.getElementById('ipk');
        if (ipkInput) {
            ipkInput.addEventListener('input', function() {
                let value = parseFloat(this.value);
                if (value > 4) {
                    this.value = '4.00';
                } else if (value < 0) {
                    this.value = '0.00';
                }
                
                // Visual feedback for IPK
                if (value >= 3.5) {
                    this.classList.add('success');
                    this.classList.remove('error');
                } else if (value >= 3.0) {
                    this.classList.remove('success', 'error');
                } else {
                    this.classList.add('error');
                    this.classList.remove('success');
                }
            });
        }
        
        // Character counter for textarea
        const textareaElement = document.getElementById('alasan_mendaftar');
        if (textareaElement) {
            const maxLength = 1000;
            let counterElement = textareaElement.parentNode.querySelector('.char-counter');
            
            if (!counterElement) {
                counterElement = document.createElement('div');
                counterElement.className = 'char-counter text-right text-sm mt-2 text-orange-600';
                textareaElement.parentNode.appendChild(counterElement);
            }

            function updateCounter() {
                const currentLength = textareaElement.value.length;
                const remaining = maxLength - currentLength;
                counterElement.textContent = currentLength + '/' + maxLength + ' karakter';

                if (remaining < 100) {
                    counterElement.className = 'char-counter text-right text-sm mt-2 text-yellow-600';
                } else if (remaining < 0) {
                    counterElement.className = 'char-counter text-right text-sm mt-2 text-red-600';
                    textareaElement.value = textareaElement.value.substring(0, maxLength);
                } else {
                    counterElement.className = 'char-counter text-right text-sm mt-2 text-orange-600';
                }
            }

            textareaElement.addEventListener('input', updateCounter);
            textareaElement.addEventListener('paste', function() {
                setTimeout(updateCounter, 10);
            });
            updateCounter();
        }
        
        // Initialize radio and checkbox selections
        initializeRadioCheckboxes();
    });

    // Radio button selection handler
    function updateRadioSelection(fieldName, selectedInput) {
        const radioOptions = document.querySelectorAll(`input[name="${fieldName}"]`);
        radioOptions.forEach(radio => {
            const label = radio.closest('.radio-option');
            if (label) {
                label.classList.remove('selected');
            }
        });
        
        if (selectedInput.checked) {
            const selectedLabel = selectedInput.closest('.radio-option');
            if (selectedLabel) {
                selectedLabel.classList.add('selected');
            }
        }
    }

    // Checkbox selection handler
    function updateCheckboxSelection(checkbox) {
        const label = checkbox.closest('.checkbox-option');
        if (label) {
            if (checkbox.checked) {
                label.classList.add('selected');
            } else {
                label.classList.remove('selected');
            }
        }
    }

    // Initialize existing selections
    function initializeRadioCheckboxes() {
        // Initialize radio selections
        const checkedRadios = document.querySelectorAll('input[type="radio"]:checked');
        checkedRadios.forEach(radio => {
            const label = radio.closest('.radio-option');
            if (label) {
                label.classList.add('selected');
            }
        });
        
        // Initialize checkbox selections
        const checkedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        checkedCheckboxes.forEach(checkbox => {
            const label = checkbox.closest('.checkbox-option');
            if (label) {
                label.classList.add('selected');
            }
        });
    }

    function handleFileUpload(documentKey) {
        const fileInput = document.getElementById(documentKey);
        const card = document.getElementById('card-' + documentKey);
        const icon = document.getElementById('icon-' + documentKey);
        const desc = document.getElementById('desc-' + documentKey);
        const status = document.getElementById('status-' + documentKey);
        const filename = document.getElementById('filename-' + documentKey);

        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
            return;
        }

        const file = fileInput.files[0];
        const fileKey = documentKey + '_' + file.name + '_' + file.size;
        
        // Cek jika file sudah diproses untuk menghindari duplikasi
        if (processedFiles.has(fileKey)) {
            return;
        }
        
        processedFiles.add(fileKey);

        // Validasi ukuran file
        const maxSizeText = desc ? desc.textContent : '';
        const maxSizeMatch = maxSizeText.match(/Max:\s*(\d+)MB/i);
        const maxSize = (maxSizeMatch ? parseInt(maxSizeMatch[1]) : 5) * 1024 * 1024;

        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar. Maksimal ' + Math.round(maxSize / 1024 / 1024) + 'MB.');
            fileInput.value = '';
            processedFiles.delete(fileKey);
            return;
        }

        // Validasi format file
        const allowedFormats = fileInput.accept.split(',').map(format => format.trim().toLowerCase());
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!allowedFormats.includes(fileExtension)) {
            alert('Format file tidak didukung. Silakan pilih file dengan format yang sesuai.');
            fileInput.value = '';
            processedFiles.delete(fileKey);
            return;
        }

        // Update tampilan card
        if (card) {
            card.classList.remove('border-orange-300');
            card.classList.add('border-green-500', 'bg-green-50', 'uploaded');
        }

        // Update icon
        if (icon) {
            icon.className = 'fas fa-check-circle';
            icon.parentElement.className = 'file-icon text-green-600 text-4xl mb-4';
        }

        // Hide description dan show status
        if (desc) desc.classList.add('hidden');
        if (status) status.classList.remove('hidden');

        // Update filename
        if (filename) filename.textContent = file.name;

        // Animasi sukses
        if (card) {
            card.style.animation = 'pulse 0.5s ease-in-out';
            setTimeout(() => {
                card.style.animation = '';
            }, 500);
        }

        console.log('File uploaded successfully:', file.name, 'for', documentKey);
    }

    function previewFile(documentKey) {
        const fileInput = document.getElementById(documentKey);
        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
            alert('Tidak ada file yang dipilih.');
            return;
        }

        const file = fileInput.files[0];
        const fileType = file.type.toLowerCase();

        try {
            if (fileType.startsWith('image/')) {
                // Preview gambar
                const reader = new FileReader();
                reader.onload = function(e) {
                    const newWindow = window.open('', '_blank');
                    if (newWindow) {
                        newWindow.document.write(`
                            <html>
                                <head>
                                    <title>${file.name}</title>
                                    <style>
                                        body { margin: 0; display: flex; justify-content: center; align-items: center; background: #f0f0f0; min-height: 100vh; }
                                        img { max-width: 100%; max-height: 100vh; object-fit: contain; }
                                    </style>
                                </head>
                                <body>
                                    <img src="${e.target.result}" alt="${file.name}">
                                </body>
                            </html>
                        `);
                    }
                };
                reader.readAsDataURL(file);
            } else if (fileType === 'application/pdf') {
                // Preview PDF
                const fileURL = URL.createObjectURL(file);
                const newWindow = window.open(fileURL, '_blank');
                if (!newWindow) {
                    alert('Pop-up diblokir. Silakan aktifkan pop-up untuk melihat preview file.');
                }
            } else {
                alert('Preview tidak tersedia untuk tipe file ini.');
            }
        } catch (error) {
            console.error('Error previewing file:', error);
            alert('Terjadi kesalahan saat membuka preview file.');
        }
    }

    function removeFile(documentKey) {
        if (!confirm('Apakah Anda yakin ingin menghapus file ini?')) {
            return;
        }

        const fileInput = document.getElementById(documentKey);
        if (fileInput) {
            fileInput.value = '';
            
            // Remove from processed files set
            const files = Array.from(processedFiles);
            processedFiles.clear();
            files.forEach(fileKey => {
                if (!fileKey.startsWith(documentKey + '_')) {
                    processedFiles.add(fileKey);
                }
            });
        }
        
        resetFileStatus(documentKey);
    }

    function resetFileStatus(documentKey) {
        const card = document.getElementById('card-' + documentKey);
        const icon = document.getElementById('icon-' + documentKey);
        const desc = document.getElementById('desc-' + documentKey);
        const status = document.getElementById('status-' + documentKey);

        if (card) {
            card.classList.remove('border-green-500', 'bg-green-50', 'uploaded');
            card.classList.add('border-orange-300');
        }

        if (icon) {
            icon.className = 'fas fa-file-upload';
            icon.parentElement.className = 'file-icon text-orange-500 text-4xl mb-4';
        }

        if (desc) desc.classList.remove('hidden');
        if (status) status.classList.add('hidden');
    }

    // Form submission handling
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registrationForm');
        const submitBtn = document.querySelector('button[type="submit"]');

        if (form) {
            form.addEventListener('submit', function(e) {
                const termsCheckbox = document.getElementById('terms');

                if (!termsCheckbox || !termsCheckbox.checked) {
                    e.preventDefault();
                    alert('Anda harus menyetujui syarat dan ketentuan terlebih dahulu.');
                    if (termsCheckbox) termsCheckbox.focus();
                    return;
                }

                // Validasi file yang required
                const requiredFiles = document.querySelectorAll('input[type="file"][required]');
                let missingFiles = [];
                
                requiredFiles.forEach(fileInput => {
                    if (!fileInput.files || !fileInput.files[0]) {
                        const label = document.querySelector(`label[for="${fileInput.id}"] .file-title`);
                        const fileName = label ? label.textContent.trim() : fileInput.name;
                        missingFiles.push(fileName);
                    }
                });

                if (missingFiles.length > 0) {
                    e.preventDefault();
                    alert('Harap upload dokumen yang diperlukan: ' + missingFiles.join(', '));
                    return;
                }

                // Loading state
                if (submitBtn) {
                    submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';

                    // Re-enable setelah 30 detik sebagai fallback
                    setTimeout(() => {
                        submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Daftar Sekarang';
                    }, 30000);
                }
            });
        }
    });
</script>

@endsection