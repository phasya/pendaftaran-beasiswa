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
        cursor: pointer;
    }
    
    .file-upload-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .file-upload-card.uploaded {
        border-color: #22c55e !important;
        background-color: #f0fdf4 !important;
    }
    
    .file-upload-area {
        cursor: pointer;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
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

    /* Radio button custom styling */
    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* File input override */
    .file-input-hidden {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }

    /* Debug helper */
    .debug-info {
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 12px;
        margin: 16px 0;
        font-size: 12px;
        color: #6b7280;
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

        <!-- Show validation errors if any -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <h4 class="text-red-800 font-semibold mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Terdapat kesalahan pada form:
                </h4>
                <ul class="list-disc list-inside text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Show success message -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center text-green-800">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Show error message -->
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center text-red-800">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

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
                                <span class="block text-sm font-medium text-amber-700 mb-1">Dana Beasiswa</span>
                                <p class="text-yellow-600 font-bold text-lg">
                                    Rp {{ number_format($beasiswa->jumlah_dana, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <span class="block text-sm font-medium text-amber-700 mb-1">Batas Waktu Pendaftaran</span>
                                <p class="text-orange-600 font-bold text-lg">
                                    {{ \Carbon\Carbon::parse($beasiswa->tanggal_tutup)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-amber-700 mb-2">Persyaratan</span>
                            <div class="bg-amber-50 border border-orange-200 rounded-lg p-4 text-amber-800 text-sm md:text-base whitespace-pre-line">
                                {{ $beasiswa->persyaratan ?? 'Tidak ada persyaratan khusus.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Form -->
                <div class="form-section">
                    <h5 class="text-lg font-semibold text-amber-800 mb-6 border-b-2 border-orange-500 pb-2 inline-block">
                        <i class="fas fa-edit text-orange-500 mr-2"></i>Formulir Pendaftaran
                    </h5>

                    <form method="POST" action="{{ route('pendaftar.store', $beasiswa) }}" enctype="multipart/form-data" id="registrationForm" novalidate>
                        @csrf

                        {{-- Debug Information --}}
                        @if(config('app.debug') && $beasiswa->form_fields)
                            <div class="debug-info">
                                <strong>Debug - Form Fields Available:</strong><br>
                                Total fields: {{ is_array($beasiswa->form_fields) ? count($beasiswa->form_fields) : 'Not an array' }}<br>
                                @if(is_array($beasiswa->form_fields))
                                    @foreach($beasiswa->form_fields as $index => $field)
                                        Field {{ $index }}: {{ is_array($field) ? ($field['key'] ?? 'No key') . ' (' . ($field['type'] ?? 'text') . ')' : 'Invalid field format' }}<br>
                                    @endforeach
                                @endif
                            </div>
                        @endif

                        {{-- Render Dynamic Form Fields by Position --}}
                        @if($beasiswa->form_fields && is_array($beasiswa->form_fields) && count($beasiswa->form_fields) > 0)
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
                                                @foreach($positionFields as $fieldIndex => $field)
                                                    @if(is_array($field))
                                                        @php
                                                            $fieldId = ($field['key'] ?? 'field') . '_' . $position . '_' . $fieldIndex;
                                                            $fieldName = $field['key'] ?? '';
                                                            $fieldType = $field['type'] ?? 'text';
                                                        @endphp
                                                        
                                                        @if(!empty($fieldName))
                                                            <div class="form-group {{ $fieldType === 'textarea' ? 'full-width' : '' }}">
                                                                <label for="{{ $fieldId }}" class="form-label">
                                                                    <i class="{{ $field['icon'] ?? 'fas fa-edit' }} text-orange-500 mr-2"></i>{{ $field['name'] ?? $fieldName }}
                                                                    @if($field['required'] ?? false)<span class="text-red-500 ml-1">*</span>@endif
                                                                </label>
                                                                
                                                                @switch($fieldType)
                                                                    @case('textarea')
                                                                        <textarea 
                                                                            id="{{ $fieldId }}" 
                                                                            name="{{ $fieldName }}"
                                                                            class="form-textarea @error($fieldName) error @enderror"
                                                                            placeholder="{{ $field['placeholder'] ?? '' }}"
                                                                            rows="4"
                                                                            {{ ($field['required'] ?? false) ? 'required' : '' }}>{{ old($fieldName) }}</textarea>
                                                                        @break
                                                                    
                                                                    @case('select')
                                                                        <select 
                                                                            id="{{ $fieldId }}" 
                                                                            name="{{ $fieldName }}"
                                                                            class="form-select @error($fieldName) error @enderror"
                                                                            {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                                                            <option value="">{{ $field['placeholder'] ?? '-- Pilih --' }}</option>
                                                                            @if(isset($field['options']) && is_array($field['options']))
                                                                                @foreach($field['options'] as $option)
                                                                                    @if(is_array($option))
                                                                                        <option value="{{ $option['value'] ?? '' }}" {{ old($fieldName) == ($option['value'] ?? '') ? 'selected' : '' }}>
                                                                                            {{ $option['label'] ?? '' }}
                                                                                        </option>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                        @break

                                                                    @case('radio')
                                                                        <div class="radio-group">
                                                                            @if(isset($field['options']) && is_array($field['options']))
                                                                                @foreach($field['options'] as $optionIndex => $option)
                                                                                    @if(is_array($option))
                                                                                        @php
                                                                                            $radioId = $fieldId . '_option_' . $optionIndex;
                                                                                        @endphp
                                                                                        <div class="radio-option" data-value="{{ $option['value'] ?? '' }}">
                                                                                            <input 
                                                                                                type="radio" 
                                                                                                id="{{ $radioId }}"
                                                                                                name="{{ $fieldName }}" 
                                                                                                value="{{ $option['value'] ?? '' }}"
                                                                                                {{ old($fieldName) == ($option['value'] ?? '') ? 'checked' : '' }}
                                                                                                {{ ($field['required'] ?? false) ? 'required' : '' }}
                                                                                                onchange="updateRadioSelection('{{ $fieldName }}', this)">
                                                                                            <label for="{{ $radioId }}" class="cursor-pointer flex-grow">
                                                                                                <span>{{ $option['label'] ?? '' }}</span>
                                                                                            </label>
                                                                                        </div>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </div>
                                                                        @break

                                                                    @case('checkbox')
                                                                        <div class="checkbox-group">
                                                                            @if(isset($field['options']) && is_array($field['options']))
                                                                                @foreach($field['options'] as $optionIndex => $option)
                                                                                    @if(is_array($option))
                                                                                        @php
                                                                                            $checkboxId = $fieldId . '_option_' . $optionIndex;
                                                                                        @endphp
                                                                                        <div class="checkbox-option" data-value="{{ $option['value'] ?? '' }}">
                                                                                            <input 
                                                                                                type="checkbox" 
                                                                                                id="{{ $checkboxId }}"
                                                                                                name="{{ $fieldName }}[]" 
                                                                                                value="{{ $option['value'] ?? '' }}"
                                                                                                {{ in_array($option['value'] ?? '', old($fieldName, [])) ? 'checked' : '' }}
                                                                                                onchange="updateCheckboxSelection(this)">
                                                                                            <label for="{{ $checkboxId }}" class="cursor-pointer flex-grow">
                                                                                                <span>{{ $option['label'] ?? '' }}</span>
                                                                                            </label>
                                                                                        </div>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </div>
                                                                        @break

                                                                    @default
                                                                        <input 
                                                                            type="{{ $fieldType }}" 
                                                                            id="{{ $fieldId }}" 
                                                                            name="{{ $fieldName }}"
                                                                            class="form-input @error($fieldName) error @enderror"
                                                                            placeholder="{{ $field['placeholder'] ?? '' }}"
                                                                            value="{{ old($fieldName) }}"
                                                                            {{ ($field['required'] ?? false) ? 'required' : '' }}
                                                                            @if($fieldType === 'number' && $fieldName === 'ipk')
                                                                                step="0.01" min="0" max="4"
                                                                            @elseif($fieldType === 'number' && $fieldName === 'semester')
                                                                                min="1" max="14"
                                                                            @elseif($fieldName === 'no_hp')
                                                                                pattern="[0-9]{10,13}"
                                                                            @endif>
                                                                @endswitch
                                                                
                                                                @error($fieldName)
                                                                    <div class="error-message">
                                                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        @endif
                                                    @endif
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
                                @if(config('app.debug'))
                                    <div class="mt-2 text-xs text-red-600">
                                        Debug: form_fields = {{ json_encode($beasiswa->form_fields) }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Dynamic Document Upload Section -->
                        @if (isset($beasiswa->required_documents) && $beasiswa->required_documents && count($beasiswa->required_documents) > 0)
                            @php
                                $documents = is_array($beasiswa->required_documents) 
                                    ? collect($beasiswa->required_documents) 
                                    : collect([]);
                                $validDocuments = $documents->filter(function($doc) {
                                    return is_array($doc) && !empty($doc['key']);
                                });
                                $uniqueDocuments = $validDocuments->unique('key')->take(6);
                            @endphp
                            
                            @if($uniqueDocuments->count() > 0)
                                <div class="bg-orange-50 rounded-xl p-6 mb-6 border-l-4 border-orange-600">
                                    <h6 class="text-md font-semibold text-orange-800 mb-4">
                                        <i class="fas fa-folder-upload text-orange-600 mr-2"></i>Dokumen Pendukung
                                    </h6>
                                    <div class="bg-white rounded-lg p-6 shadow-sm">
                                        <div class="grid grid-cols-1 md:grid-cols-{{ $uniqueDocuments->count() >= 3 ? '3' : ($uniqueDocuments->count() == 2 ? '2' : '1') }} gap-6">
                                            @foreach ($uniqueDocuments as $docIndex => $document)
                                                @php
                                                    $docKey = $document['key'] ?? 'doc_' . $docIndex;
                                                    $docName = $document['name'] ?? 'Dokumen';
                                                    $docIcon = $document['icon'] ?? 'fas fa-file-upload';
                                                    $docColor = $document['color'] ?? 'orange';
                                                    $docFormats = $document['formats'] ?? ['pdf'];
                                                    $docMaxSize = $document['max_size'] ?? 5;
                                                    $docRequired = $document['required'] ?? true;
                                                    $docDescription = $document['description'] ?? '';
                                                    $docId = 'upload_' . $docKey;
                                                @endphp
                                                
                                                <div class="file-upload-card border-2 border-dashed border-orange-300 rounded-xl p-6 text-center"
                                                    id="card_{{ $docKey }}" onclick="triggerFileInput('{{ $docId }}')">
                                                    
                                                    <!-- File input yang tersembunyi -->
                                                    <input 
                                                        type="file" 
                                                        id="{{ $docId }}" 
                                                        name="{{ $docKey }}"
                                                        class="file-input-hidden"
                                                        accept="{{ collect($docFormats)->map(fn($ext) => '.' . strtolower($ext))->implode(',') }}"
                                                        {{ $docRequired ? 'required' : '' }}
                                                        onchange="handleFileChange('{{ $docKey }}', this)">
                                                    
                                                    <div class="file-upload-area">
                                                        <div class="file-icon text-{{ $docColor }}-500 text-4xl mb-4" id="icon_{{ $docKey }}">
                                                            <i class="{{ $docIcon }}"></i>
                                                        </div>
                                                        
                                                        <div class="file-info">
                                                            <h6 class="file-title font-semibold text-amber-800 mb-1">
                                                                {{ $docName }}
                                                                @if($docRequired) 
                                                                    <span class="text-red-500">*</span>
                                                                @endif
                                                            </h6>
                                                            
                                                            <div class="file-desc text-orange-600 text-sm block mb-2" id="desc_{{ $docKey }}">
                                                                <div>Format: {{ strtoupper(implode(', ', $docFormats)) }}</div>
                                                                <div>Max: {{ $docMaxSize }}MB</div>
                                                                @if (!empty($docDescription))
                                                                    <div class="text-xs text-orange-500 mt-1">{{ $docDescription }}</div>
                                                                @endif
                                                                <div class="text-xs text-gray-500 mt-2">Klik untuk memilih file</div>
                                                            </div>

                                                            <!-- Status Upload -->
                                                            <div class="upload-status mt-3 hidden" id="status_{{ $docKey }}">
                                                                <div class="flex items-center justify-center mb-2">
                                                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                                    <span class="text-green-700 font-medium text-sm" id="filename_{{ $docKey }}">
                                                                        File berhasil dipilih
                                                                    </span>
                                                                </div>
                                                                <div class="flex justify-center space-x-2 mt-3">
                                                                    <button type="button" onclick="event.stopPropagation(); previewFile('{{ $docKey }}')"
                                                                        class="px-3 py-1 text-xs bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                                                        <i class="fas fa-eye mr-1"></i>Lihat
                                                                    </button>
                                                                    <button type="button" onclick="event.stopPropagation(); removeFile('{{ $docKey }}')"
                                                                        class="px-3 py-1 text-xs bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                                                        <i class="fas fa-trash mr-1"></i>Hapus
                                                                    </button>
                                                                    <button type="button" onclick="event.stopPropagation(); triggerFileInput('{{ $docId }}')"
                                                                        class="px-3 py-1 text-xs bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">
                                                                        <i class="fas fa-edit mr-1"></i>Ganti
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
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
                                           type="checkbox" id="terms_agreement" name="terms_agreement" value="1" required>
                                    <label class="text-amber-800 text-sm cursor-pointer" for="terms_agreement">
                                        Saya menyatakan bahwa data yang saya berikan adalah <strong>benar dan valid</strong>.
                                        Saya bersedia menerima konsekuensi jika terbukti memberikan data palsu.
                                    </label>
                                </div>
                                @error('terms_agreement')
                                    <div class="error-message mt-2 ml-7">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
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
                                    <button type="submit" id="submitBtn"
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
                    @if (isset($beasiswa->required_documents) && $beasiswa->required_documents && count($beasiswa->required_documents) > 0)
                        @foreach ($beasiswa->required_documents as $document)
                            @if(is_array($document) && !empty($document['key']))
                                <li class="flex items-start">
                                    <i class="{{ $document['icon'] ?? 'fas fa-file' }} text-{{ $document['color'] ?? 'orange' }}-500 mt-1 mr-2"></i>
                                    <span class="text-amber-700 text-sm">
                                        <strong>{{ $document['name'] ?? 'Dokumen' }}:</strong>
                                        {{ strtoupper(implode(', ', $document['formats'] ?? ['PDF'])) }}
                                        (Max {{ $document['max_size'] ?? 5 }}MB)
                                    </span>
                                </li>
                            @endif
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
    // Global variables
    let uploadedFiles = {};
    
    // Enhanced console logging for debugging
    function debugLog(message, data = null) {
        if (typeof console !== 'undefined') {
            if (data) {
                console.log('[Form Debug] ' + message, data);
            } else {
                console.log('[Form Debug] ' + message);
            }
        }
    }
    
    // Trigger file input when card is clicked
    function triggerFileInput(inputId) {
        debugLog('Triggering file input: ' + inputId);
        const fileInput = document.getElementById(inputId);
        if (fileInput) {
            fileInput.click();
        } else {
            debugLog('File input not found: ' + inputId);
        }
    }
    
    // Handle file selection with improved validation
    function handleFileChange(docKey, input) {
        debugLog('File change detected for: ' + docKey);
        
        const file = input.files[0];
        
        if (!file) {
            debugLog('No file selected');
            resetFileStatus(docKey);
            return;
        }
        
        debugLog('File selected', {
            name: file.name,
            size: file.size,
            type: file.type
        });
        
        // Get validation parameters
        const cardElement = document.getElementById(`card_${docKey}`);
        const descElement = document.getElementById(`desc_${docKey}`);
        
        let maxSize = 5; // default 5MB
        if (descElement) {
            const maxSizeMatch = descElement.textContent.match(/Max:\s*(\d+)MB/i);
            if (maxSizeMatch) {
                maxSize = parseInt(maxSizeMatch[1]);
            }
        }
        
        // Validate file size
        const maxSizeBytes = maxSize * 1024 * 1024;
        if (file.size > maxSizeBytes) {
            alert(`Ukuran file terlalu besar. Maksimal ${maxSize}MB.\nFile Anda: ${(file.size/1024/1024).toFixed(2)}MB`);
            input.value = '';
            resetFileStatus(docKey);
            return;
        }
        
        // Validate file format
        const allowedFormats = input.accept.split(',').map(format => format.trim().toLowerCase());
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        debugLog('Format validation', {
            allowedFormats: allowedFormats,
            fileExtension: fileExtension,
            fileName: file.name
        });
        
        if (allowedFormats.length > 0 && !allowedFormats.includes(fileExtension)) {
            alert(`Format file tidak didukung.\nFormat yang diizinkan: ${allowedFormats.join(', ')}\nFile Anda: ${fileExtension}`);
            input.value = '';
            resetFileStatus(docKey);
            return;
        }
        
        // Update tampilan jika validasi berhasil
        updateFileStatus(docKey, file);
        
        // Store file reference
        uploadedFiles[docKey] = file;
        
        debugLog('File validation passed and stored', {
            docKey: docKey,
            fileName: file.name,
            fileSize: file.size
        });
    }
    
    // Update file status display
    function updateFileStatus(docKey, file) {
        const card = document.getElementById(`card_${docKey}`);
        const icon = document.getElementById(`icon_${docKey}`);
        const desc = document.getElementById(`desc_${docKey}`);
        const status = document.getElementById(`status_${docKey}`);
        const filename = document.getElementById(`filename_${docKey}`);
        
        // Update card appearance
        if (card) {
            card.classList.remove('border-orange-300');
            card.classList.add('border-green-500', 'bg-green-50', 'uploaded');
        }
        
        // Update icon
        if (icon) {
            icon.innerHTML = '<i class="fas fa-check-circle"></i>';
            icon.className = 'file-icon text-green-600 text-4xl mb-4';
        }
        
        // Hide description and show status
        if (desc) desc.classList.add('hidden');
        if (status) status.classList.remove('hidden');
        
        // Update filename
        if (filename) {
            filename.textContent = file.name;
        }
        
        // Add animation
        if (card) {
            card.style.animation = 'pulse 0.5s ease-in-out';
            setTimeout(() => {
                if (card) card.style.animation = '';
            }, 500);
        }
        
        debugLog('File status updated successfully', {
            docKey: docKey,
            fileName: file.name
        });
    }
    
    // Reset file status display
    function resetFileStatus(docKey) {
        const card = document.getElementById(`card_${docKey}`);
        const icon = document.getElementById(`icon_${docKey}`);
        const desc = document.getElementById(`desc_${docKey}`);
        const status = document.getElementById(`status_${docKey}`);
        const input = document.getElementById(`upload_${docKey}`);
        
        // Reset card appearance
        if (card) {
            card.classList.remove('border-green-500', 'bg-green-50', 'uploaded');
            card.classList.add('border-orange-300');
        }
        
        // Reset icon
        if (icon) {
            icon.innerHTML = '<i class="fas fa-file-upload"></i>';
            icon.className = 'file-icon text-orange-500 text-4xl mb-4';
        }
        
        // Show description and hide status
        if (desc) desc.classList.remove('hidden');
        if (status) status.classList.add('hidden');
        
        // Clear file input
        if (input) {
            input.value = '';
        }
        
        // Remove from uploaded files
        delete uploadedFiles[docKey];
        
        debugLog('File status reset', { docKey: docKey });
    }
    
    // Preview file with better error handling
    function previewFile(docKey) {
        const input = document.getElementById(`upload_${docKey}`);
        
        if (!input || !input.files || !input.files[0]) {
            alert('Tidak ada file yang dipilih.');
            return;
        }
        
        const file = input.files[0];
        const fileType = file.type.toLowerCase();
        
        debugLog('Attempting to preview file', {
            docKey: docKey,
            fileName: file.name,
            fileType: fileType,
            fileSize: file.size
        });
        
        try {
            if (fileType.startsWith('image/')) {
                // Preview gambar
                const reader = new FileReader();
                reader.onload = function(e) {
                    const newWindow = window.open('', '_blank', 'width=800,height=600');
                    if (newWindow) {
                        newWindow.document.write(`
                            <html>
                                <head>
                                    <title>Preview: ${file.name}</title>
                                    <style>
                                        body { 
                                            margin: 0; 
                                            display: flex; 
                                            justify-content: center; 
                                            align-items: center; 
                                            background: #f0f0f0; 
                                            min-height: 100vh; 
                                            font-family: Arial, sans-serif; 
                                        }
                                        img { 
                                            max-width: 90%; 
                                            max-height: 90vh; 
                                            object-fit: contain; 
                                            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
                                        }
                                        .info { 
                                            position: absolute; 
                                            top: 10px; 
                                            left: 10px; 
                                            background: rgba(0,0,0,0.7); 
                                            color: white; 
                                            padding: 8px 12px; 
                                            border-radius: 4px; 
                                            font-size: 12px; 
                                        }
                                    </style>
                                </head>
                                <body>
                                    <div class="info">${file.name} (${(file.size/1024/1024).toFixed(2)} MB)</div>
                                    <img src="${e.target.result}" alt="${file.name}">
                                </body>
                            </html>
                        `);
                        debugLog('Image preview opened successfully');
                    } else {
                        alert('Pop-up diblokir. Silakan aktifkan pop-up untuk melihat preview file.');
                    }
                };
                reader.onerror = function(error) {
                    debugLog('FileReader error', error);
                    alert('Gagal membaca file gambar.');
                };
                reader.readAsDataURL(file);
            } else if (fileType === 'application/pdf') {
                // Preview PDF
                const fileURL = URL.createObjectURL(file);
                const newWindow = window.open(fileURL, '_blank', 'width=900,height=700');
                if (!newWindow) {
                    alert('Pop-up diblokir. Silakan aktifkan pop-up untuk melihat preview file.');
                } else {
                    debugLog('PDF preview opened successfully');
                }
            } else {
                alert(`Preview tidak tersedia untuk tipe file ${fileType}. File: ${file.name}`);
                debugLog('Preview not supported for file type', {
                    fileType: fileType,
                    fileName: file.name
                });
            }
        } catch (error) {
            debugLog('Error previewing file', error);
            alert('Terjadi kesalahan saat membuka preview file: ' + error.message);
        }
    }
    
    // Remove file with confirmation
    function removeFile(docKey) {
        if (!confirm('Apakah Anda yakin ingin menghapus file ini?')) {
            return;
        }
        
        resetFileStatus(docKey);
        debugLog('File removed by user', { docKey: docKey });
    }
    
    // Radio button selection handler
    function updateRadioSelection(fieldName, selectedInput) {
        debugLog('Updating radio selection', { fieldName: fieldName, value: selectedInput.value });
        
        const radioOptions = document.querySelectorAll(`input[name="${fieldName}"]`);
        radioOptions.forEach(radio => {
            const container = radio.closest('.radio-option');
            if (container) {
                container.classList.remove('selected');
            }
        });
        
        if (selectedInput && selectedInput.checked) {
            const selectedContainer = selectedInput.closest('.radio-option');
            if (selectedContainer) {
                selectedContainer.classList.add('selected');
            }
        }
    }
    
    // Checkbox selection handler
    function updateCheckboxSelection(checkbox) {
        debugLog('Updating checkbox selection', { 
            name: checkbox.name, 
            value: checkbox.value, 
            checked: checkbox.checked 
        });
        
        const container = checkbox.closest('.checkbox-option');
        if (container) {
            if (checkbox.checked) {
                container.classList.add('selected');
            } else {
                container.classList.remove('selected');
            }
        }
    }
    
    // Enhanced form validation
    function validateForm() {
        debugLog('Starting form validation...');
        
        let isValid = true;
        const errors = [];
        
        // Check terms agreement
        const termsCheckbox = document.getElementById('terms_agreement');
        if (!termsCheckbox || !termsCheckbox.checked) {
            isValid = false;
            errors.push('Anda harus menyetujui syarat dan ketentuan.');
            if (termsCheckbox) {
                termsCheckbox.focus();
                termsCheckbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        
        // Check required form fields
        const requiredInputs = document.querySelectorAll('input[required], select[required], textarea[required]');
        requiredInputs.forEach(input => {
            if (input.type === 'checkbox' && input.name !== 'terms_agreement') {
                // Handle checkbox groups
                const checkboxGroup = document.querySelectorAll(`input[name="${input.name}"]`);
                const hasChecked = Array.from(checkboxGroup).some(cb => cb.checked);
                if (!hasChecked) {
                    isValid = false;
                    const label = input.closest('.form-group')?.querySelector('.form-label')?.textContent?.replace('*', '').trim();
                    errors.push(`${label || input.name} minimal pilih satu.`);
                }
            } else if (input.type !== 'checkbox' && input.type !== 'file') {
                if (!input.value.trim()) {
                    isValid = false;
                    const label = input.closest('.form-group')?.querySelector('.form-label')?.textContent?.replace('*', '').trim();
                    errors.push(`${label || input.name} wajib diisi.`);
                    input.classList.add('error');
                }
            }
        });
        
        // Check required files
        const requiredFileInputs = document.querySelectorAll('input[type="file"][required]');
        requiredFileInputs.forEach(fileInput => {
            if (!fileInput.files || fileInput.files.length === 0) {
                isValid = false;
                const docKey = fileInput.name;
                const card = document.getElementById(`card_${docKey}`);
                const titleElement = card?.querySelector('.file-title');
                const fileName = titleElement ? titleElement.textContent.replace('*', '').trim() : docKey;
                errors.push(`Dokumen ${fileName} wajib diupload.`);
                
                // Highlight the file upload card
                if (card) {
                    card.classList.add('border-red-500');
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        debugLog('Form validation completed', {
            isValid: isValid,
            errorCount: errors.length,
            errors: errors
        });
        
        if (!isValid) {
            alert('Terdapat kesalahan pada form:\n\n• ' + errors.join('\n• '));
        }
        
        return isValid;
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        debugLog('Document loaded, initializing form...');
        
        // Initialize existing radio/checkbox selections
        const checkedRadios = document.querySelectorAll('input[type="radio"]:checked');
        checkedRadios.forEach(radio => {
            const container = radio.closest('.radio-option');
            if (container) {
                container.classList.add('selected');
            }
        });
        
        const checkedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        checkedCheckboxes.forEach(checkbox => {
            if (checkbox.id !== 'terms_agreement') {
                const container = checkbox.closest('.checkbox-option');
                if (container) {
                    container.classList.add('selected');
                }
            }
        });
        
        // Enhanced input validation and formatting
        const inputs = document.querySelectorAll('.form-input, .form-textarea, .form-select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.classList.add('focused');
                this.classList.remove('error');
            });
            
            input.addEventListener('blur', function() {
                this.classList.remove('focused');
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
            });
            
            input.addEventListener('input', function() {
                this.classList.remove('error');
            });
        });
        
        // Phone number formatting
        const phoneInput = document.querySelector('input[name="no_hp"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                // Remove non-digits
                this.value = this.value.replace(/\D/g, '');
                // Limit to 13 characters
                if (this.value.length > 13) {
                    this.value = this.value.substring(0, 13);
                }
            });
            
            phoneInput.addEventListener('blur', function() {
                // Add validation feedback
                if (this.value.length > 0 && (this.value.length < 10 || this.value.length > 13)) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
            });
        }
        
        // NIM formatting
        const nimInput = document.querySelector('input[name="nim"]');
        if (nimInput) {
            nimInput.addEventListener('input', function() {
                // Remove non-digits
                this.value = this.value.replace(/\D/g, '');
                // Limit to 15 characters
                if (this.value.length > 15) {
                    this.value = this.value.substring(0, 15);
                }
            });
        }
        
        // IPK formatting
        const ipkInput = document.querySelector('input[name="ipk"]');
        if (ipkInput) {
            ipkInput.addEventListener('input', function() {
                let value = parseFloat(this.value);
                if (value > 4) {
                    this.value = '4.00';
                } else if (value < 0) {
                    this.value = '0.00';
                }
            });
            
            ipkInput.addEventListener('blur', function() {
                if (this.value && !isNaN(this.value)) {
                    this.value = parseFloat(this.value).toFixed(2);
                }
            });
        }
        
        // Form submission handling
        const form = document.getElementById('registrationForm');
        const submitBtn = document.getElementById('submitBtn');
        
        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                debugLog('Form submission initiated...');
                
                // Prevent double submission
                if (submitBtn.disabled) {
                    e.preventDefault();
                    debugLog('Form already being submitted, preventing double submission');
                    return false;
                }
                
                // Validate form
                if (!validateForm()) {
                    e.preventDefault();
                    debugLog('Form validation failed, preventing submission');
                    return false;
                }
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim Data...';
                
                // Create progress indicator
                const progressDiv = document.createElement('div');
                progressDiv.className = 'fixed top-0 left-0 right-0 z-50 bg-orange-500 text-white text-center py-2';
                progressDiv.innerHTML = '<i class="fas fa-upload mr-2"></i>Sedang mengirim data, mohon tunggu...';
                document.body.appendChild(progressDiv);
                
                debugLog('Form submission proceeding with loading state active...');
                
                // Fallback timeout (30 seconds)
                setTimeout(() => {
                    if (submitBtn && submitBtn.disabled) {
                        debugLog('Submission timeout reached, re-enabling form');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Daftar Sekarang';
                        
                        if (progressDiv && progressDiv.parentNode) {
                            progressDiv.remove();
                        }
                        
                        alert('Proses upload memakan waktu lama. Jika masalah berlanjut, coba refresh halaman dan submit ulang.');
                    }
                }, 30000);
                
                return true;
            });
            
            debugLog('Form event listeners attached successfully');
        } else {
            debugLog('Form or submit button not found', {
                form: !!form,
                submitBtn: !!submitBtn
            });
        }
        
        // File input error recovery
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(fileInput => {
            fileInput.addEventListener('error', function(e) {
                debugLog('File input error', {
                    inputName: this.name,
                    error: e
                });
            });
        });
        
        debugLog('Form initialization completed successfully');
    });
    
    // Global error handler
    window.addEventListener('error', function(e) {
        debugLog('Global JavaScript error caught', {
            message: e.message,
            filename: e.filename,
            lineno: e.lineno,
            colno: e.colno,
            error: e.error
        });
    });
    
    // Global unhandled promise rejection handler
    window.addEventListener('unhandledrejection', function(e) {
        debugLog('Unhandled promise rejection', {
            reason: e.reason,
            promise: e.promise
        });
    });
</script>

@endsection