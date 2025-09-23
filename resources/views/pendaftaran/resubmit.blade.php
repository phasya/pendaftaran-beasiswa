@extends('layouts.app')

@section('title', 'Edit dan Submit Ulang - ' . $pendaftar->beasiswa->nama_beasiswa)

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
    
    .radio-group, .checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
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
</style>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-orange-800 mb-2">
                <i class="fas fa-edit text-orange-600 mr-2"></i>Edit dan Submit Ulang
            </h1>
            <p class="text-amber-700">Perbaiki data Anda dan ajukan kembali</p>
        </div>

        <!-- Previous Rejection Info -->
        <div class="bg-red-50 border-l-4 border-red-500 p-6 mb-8 rounded-r-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-red-800 mb-2">Alasan Penolakan Sebelumnya</h3>
                    <div class="bg-white border border-red-200 rounded-lg p-4">
                        <p class="text-red-800">{{ $pendaftar->rejection_reason }}</p>
                    </div>
                    <div class="mt-3">
                        <span class="text-sm text-red-700">
                            <i class="fas fa-calendar mr-1"></i>Ditolak pada:
                            {{ $pendaftar->rejected_at ? $pendaftar->rejected_at->format('d M Y H:i') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
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

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg border border-orange-200 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-amber-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">
                    <i class="fas fa-graduation-cap mr-2"></i>{{ $pendaftar->beasiswa->nama_beasiswa }}
                </h2>
                <p class="text-orange-100">Dana: Rp {{ number_format($pendaftar->beasiswa->jumlah_dana, 0, ',', '.') }}
                </p>
            </div>

            <div class="p-6 md:p-10">
                <form action="{{ route('pendaftar.resubmit.store', $pendaftar) }}" method="POST"
                    enctype="multipart/form-data" id="resubmitForm" novalidate>
                    @csrf
                    @method('PUT')

                    @php
                        // Get current form data from the pendaftar model
                        $currentFormData = [];
                        if (isset($pendaftar->form_data)) {
                            $currentFormData = is_string($pendaftar->form_data) ? json_decode($pendaftar->form_data, true) ?: [] : (is_array($pendaftar->form_data) ? $pendaftar->form_data : []);
                        }

                        // Also check for individual properties for backward compatibility
                        $fallbackData = [
                            'nama_lengkap' => $pendaftar->nama_lengkap ?? '',
                            'nim' => $pendaftar->nim ?? '',
                            'email' => $pendaftar->email ?? '',
                            'no_hp' => $pendaftar->no_hp ?? '',
                            'fakultas' => $pendaftar->fakultas ?? '',
                            'jurusan' => $pendaftar->jurusan ?? '',
                            'semester' => $pendaftar->semester ?? '',
                            'ipk' => $pendaftar->ipk ?? '',
                            'alasan_mendaftar' => $pendaftar->alasan_mendaftar ?? '',
                        ];

                        // Merge current form data with fallback data
                        $existingData = array_merge($fallbackData, $currentFormData);
                    @endphp

                    {{-- Render Dynamic Form Fields by Position --}}
                    @if($pendaftar->beasiswa->form_fields && is_array($pendaftar->beasiswa->form_fields) && count($pendaftar->beasiswa->form_fields) > 0)
                        @foreach(['personal', 'academic', 'additional'] as $position)
                            @php
                                $positionFields = collect($pendaftar->beasiswa->form_fields)->where('position', $position);
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
                                                        
                                                        // Get the current value for this field
                                                        $currentValue = old($fieldName, $existingData[$fieldName] ?? '');
                                                        
                                                        // Special handling for email field
                                                        $isReadonly = $fieldName === 'email';
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
                                                                        {{ ($field['required'] ?? false) ? 'required' : '' }}
                                                                        {{ $isReadonly ? 'readonly' : '' }}>{{ $currentValue }}</textarea>
                                                                    @if($isReadonly)
                                                                        <p class="text-xs text-orange-600 mt-1">Field ini tidak dapat diubah</p>
                                                                    @endif
                                                                    @break
                                                                
                                                                @case('select')
                                                                    <select 
                                                                        id="{{ $fieldId }}" 
                                                                        name="{{ $fieldName }}"
                                                                        class="form-select @error($fieldName) error @enderror"
                                                                        {{ ($field['required'] ?? false) ? 'required' : '' }}
                                                                        {{ $isReadonly ? 'disabled' : '' }}>
                                                                        <option value="">{{ $field['placeholder'] ?? '-- Pilih --' }}</option>
                                                                        @if(isset($field['options']) && is_array($field['options']))
                                                                            @foreach($field['options'] as $option)
                                                                                @if(is_array($option))
                                                                                    <option value="{{ $option['value'] ?? '' }}" {{ $currentValue == ($option['value'] ?? '') ? 'selected' : '' }}>
                                                                                        {{ $option['label'] ?? '' }}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    @if($isReadonly)
                                                                        <input type="hidden" name="{{ $fieldName }}" value="{{ $currentValue }}">
                                                                        <p class="text-xs text-orange-600 mt-1">Field ini tidak dapat diubah</p>
                                                                    @endif
                                                                    @break

                                                                @case('radio')
                                                                    <div class="radio-group">
                                                                        @if(isset($field['options']) && is_array($field['options']))
                                                                            @foreach($field['options'] as $optionIndex => $option)
                                                                                @if(is_array($option))
                                                                                    @php
                                                                                        $radioId = $fieldId . '_option_' . $optionIndex;
                                                                                        $isChecked = $currentValue == ($option['value'] ?? '');
                                                                                    @endphp
                                                                                    <div class="radio-option {{ $isChecked ? 'selected' : '' }}" data-value="{{ $option['value'] ?? '' }}">
                                                                                        <input 
                                                                                            type="radio" 
                                                                                            id="{{ $radioId }}"
                                                                                            name="{{ $fieldName }}" 
                                                                                            value="{{ $option['value'] ?? '' }}"
                                                                                            {{ $isChecked ? 'checked' : '' }}
                                                                                            {{ ($field['required'] ?? false) ? 'required' : '' }}
                                                                                            {{ $isReadonly ? 'disabled' : '' }}
                                                                                            onchange="updateRadioSelection('{{ $fieldName }}', this)">
                                                                                        <label for="{{ $radioId }}" class="cursor-pointer flex-grow">
                                                                                            <span>{{ $option['label'] ?? '' }}</span>
                                                                                        </label>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                    @if($isReadonly)
                                                                        <input type="hidden" name="{{ $fieldName }}" value="{{ $currentValue }}">
                                                                        <p class="text-xs text-orange-600 mt-1">Field ini tidak dapat diubah</p>
                                                                    @endif
                                                                    @break

                                                                @case('checkbox')
                                                                    <div class="checkbox-group">
                                                                        @php
                                                                            $currentCheckboxValues = is_array($currentValue) ? $currentValue : (is_string($currentValue) ? explode(',', $currentValue) : []);
                                                                        @endphp
                                                                        @if(isset($field['options']) && is_array($field['options']))
                                                                            @foreach($field['options'] as $optionIndex => $option)
                                                                                @if(is_array($option))
                                                                                    @php
                                                                                        $checkboxId = $fieldId . '_option_' . $optionIndex;
                                                                                        $isChecked = in_array($option['value'] ?? '', $currentCheckboxValues);
                                                                                    @endphp
                                                                                    <div class="checkbox-option {{ $isChecked ? 'selected' : '' }}" data-value="{{ $option['value'] ?? '' }}">
                                                                                        <input 
                                                                                            type="checkbox" 
                                                                                            id="{{ $checkboxId }}"
                                                                                            name="{{ $fieldName }}[]" 
                                                                                            value="{{ $option['value'] ?? '' }}"
                                                                                            {{ $isChecked ? 'checked' : '' }}
                                                                                            {{ $isReadonly ? 'disabled' : '' }}
                                                                                            onchange="updateCheckboxSelection(this)">
                                                                                        <label for="{{ $checkboxId }}" class="cursor-pointer flex-grow">
                                                                                            <span>{{ $option['label'] ?? '' }}</span>
                                                                                        </label>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                    @if($isReadonly)
                                                                        @foreach($currentCheckboxValues as $val)
                                                                            <input type="hidden" name="{{ $fieldName }}[]" value="{{ $val }}">
                                                                        @endforeach
                                                                        <p class="text-xs text-orange-600 mt-1">Field ini tidak dapat diubah</p>
                                                                    @endif
                                                                    @break

                                                                @default
                                                                    <input 
                                                                        type="{{ $fieldType }}" 
                                                                        id="{{ $fieldId }}" 
                                                                        name="{{ $fieldName }}"
                                                                        class="form-input @error($fieldName) error @enderror {{ $isReadonly ? 'bg-amber-100 cursor-not-allowed' : '' }}"
                                                                        placeholder="{{ $field['placeholder'] ?? '' }}"
                                                                        value="{{ $currentValue }}"
                                                                        {{ ($field['required'] ?? false) ? 'required' : '' }}
                                                                        {{ $isReadonly ? 'readonly' : '' }}
                                                                        @if($fieldType === 'number' && $fieldName === 'ipk')
                                                                            step="0.01" min="0" max="4"
                                                                        @elseif($fieldType === 'number' && $fieldName === 'semester')
                                                                            min="1" max="14"
                                                                        @elseif($fieldName === 'no_hp')
                                                                            pattern="[0-9]{10,13}"
                                                                        @endif>
                                                                    @if($isReadonly)
                                                                        <p class="text-xs text-orange-600 mt-1">Field ini tidak dapat diubah</p>
                                                                    @endif
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
                        {{-- Fallback form for older beasiswa without dynamic fields --}}
                        <div class="bg-amber-50 rounded-xl p-6 mb-6 border-l-4 border-orange-500">
                            <h6 class="text-lg font-bold text-orange-800 mb-6">
                                <i class="fas fa-user text-orange-600 mr-2"></i>Data Personal
                            </h6>
                            <div class="bg-white rounded-xl p-8 shadow-sm">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="form-label" for="nama_lengkap">
                                            <i class="fas fa-user text-orange-500 mr-2"></i>Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="nama_lengkap" name="nama_lengkap"
                                            value="{{ old('nama_lengkap', $pendaftar->nama_lengkap) }}"
                                            class="form-input @error('nama_lengkap') error @enderror"
                                            required>
                                        @error('nama_lengkap')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="form-label" for="nim">
                                            <i class="fas fa-id-card text-orange-500 mr-2"></i>NIM <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" id="nim" name="nim"
                                            value="{{ old('nim', $pendaftar->nim) }}"
                                            class="form-input @error('nim') error @enderror"
                                            required>
                                        @error('nim')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="form-label" for="email">
                                            <i class="fas fa-envelope text-orange-500 mr-2"></i>Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email', $pendaftar->email) }}" readonly
                                            class="form-input bg-amber-100 cursor-not-allowed">
                                        <p class="text-xs text-orange-600 mt-1">Email tidak dapat diubah</p>
                                    </div>

                                    <div>
                                        <label class="form-label" for="no_hp">
                                            <i class="fas fa-phone text-orange-500 mr-2"></i>No. HP <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" id="no_hp" name="no_hp"
                                            value="{{ old('no_hp', $pendaftar->no_hp) }}"
                                            class="form-input @error('no_hp') error @enderror"
                                            pattern="[0-9]{10,13}" required>
                                        @error('no_hp')
                                            <div class="error-message">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Dynamic Document Upload Section -->
                    @if (isset($pendaftar->beasiswa->required_documents) && $pendaftar->beasiswa->required_documents && count($pendaftar->beasiswa->required_documents) > 0)
                        @php
                            $documents = is_array($pendaftar->beasiswa->required_documents) 
                                ? collect($pendaftar->beasiswa->required_documents) 
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
                                <p class="text-sm text-amber-700 mb-6">
                                    <i class="fas fa-info-circle text-orange-500 mr-1"></i>
                                    Kosongkan jika tidak ingin mengubah file yang sudah ada
                                </p>
                                
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
                                                
                                                // Get current file for this document
                                                $currentFile = $pendaftar->getDocument($docKey);
                                            @endphp
                                            
                                            <div class="file-upload-card border-2 border-dashed border-orange-300 rounded-xl p-6 text-center"
                                                id="card_{{ $docKey }}" onclick="triggerFileInput('{{ $docId }}')">
                                                
                                                <!-- File input yang tersembunyi -->
                                                <input 
                                                    type="file" 
                                                    id="{{ $docId }}" 
                                                    name="{{ $docKey }}"
                                                    class="file-input-hidden"
                                                    accept="{{ collect($docFormats)->map(fn($ext) => '.' . strtolower($ext))->implode(',') }}">
                                                
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
                                                            
                                                            @if($currentFile)
                                                                <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                                                                    <div class="text-xs text-green-700 font-medium">File saat ini:</div>
                                                                    <a href="{{ asset('storage/documents/' . $currentFile) }}" 
                                                                       target="_blank" 
                                                                       class="text-xs text-blue-600 hover:text-blue-800 underline"
                                                                       onclick="event.stopPropagation();">
                                                                        Lihat file lama
                                                                    </a>
                                                                </div>
                                                            @endif
                                                            <div class="text-xs text-gray-500 mt-2">Klik untuk mengganti file</div>
                                                        </div>

                                                        <!-- Status Upload -->
                                                        <div class="upload-status mt-3 hidden" id="status_{{ $docKey }}">
                                                            <div class="flex items-center justify-center mb-2">
                                                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                                <span class="text-green-700 font-medium text-sm" id="filename_{{ $docKey }}">
                                                                    File baru berhasil dipilih
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

                    <!-- Action Buttons -->
                    <div class="bg-amber-50 rounded-xl p-6">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="form-info">
                                <small class="text-orange-600 text-sm">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    Data Anda akan dijaga kerahasiaannya
                                </small>
                            </div>
                            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                                <a href="{{ route('status') }}"
                                    class="bg-white border-2 border-orange-400 text-orange-700 px-6 py-3 rounded-full font-semibold hover:bg-orange-50 transition-all duration-300 text-center">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Status
                                </a>
                                <button type="submit" id="submitBtn"
                                    class="bg-gradient-to-r from-orange-500 to-amber-600 text-white px-6 py-3 rounded-full font-semibold hover:from-orange-600 hover:to-amber-700 transition-all duration-300 shadow-md hover:shadow-lg text-center">
                                    <i class="fas fa-paper-plane mr-2"></i>Submit Ulang Beasiswa
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Guidelines -->
        <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-6 mt-8">
            <h3 class="text-lg font-semibold text-yellow-800 mb-3">
                <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>Tips untuk Resubmit
            </h3>
            <ul class="text-sm text-yellow-800 space-y-2">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-600 mr-2 mt-0.5"></i>
                    Baca dengan teliti alasan penolakan di atas
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-600 mr-2 mt-0.5"></i>
                    Perbaiki bagian yang menjadi alasan penolakan
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-600 mr-2 mt-0.5"></i>
                    Pastikan semua dokumen sudah sesuai persyaratan
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-yellow-600 mr-2 mt-0.5"></i>
                    Upload ulang dokumen hanya jika diperlukan
                </li>
            </ul>
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
                console.log('[Resubmit Form Debug] ' + message, data);
            } else {
                console.log('[Resubmit Form Debug] ' + message);
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
            const container = checkbox.closest('.checkbox-option');
            if (container) {
                container.classList.add('selected');
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
        
        // Add event listeners to file inputs
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(fileInput => {
            const docKey = fileInput.name;
            fileInput.addEventListener('change', function() {
                handleFileChange(docKey, this);
            });
        });
        
        // Form submission handling
        const form = document.getElementById('resubmitForm');
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
                
                // Confirmation
                if (!confirm('Apakah Anda yakin ingin mengajukan ulang beasiswa ini?')) {
                    e.preventDefault();
                    debugLog('User cancelled submission');
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
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit Ulang Beasiswa';
                        
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
        
        debugLog('Form initialization completed successfully');
    });
</script>

@endsection