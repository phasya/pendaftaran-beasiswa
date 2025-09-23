@extends('layouts.admin')

@section('title', 'Detail Pendaftar')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $pendaftar->nama_lengkap }}</h1>
                        <p class="text-gray-600 text-sm">Detail Informasi Pendaftar</p>
                    </div>
                </div>
            </div>
            
            <!-- Status Badge -->
            <div class="flex items-center gap-3">
                @if($pendaftar->status == 'pending')
                    <div class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-800 rounded-full font-medium">
                        <i class="fas fa-clock mr-2"></i>
                        Menunggu Review
                    </div>
                @elseif($pendaftar->status == 'diterima')
                    <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full font-medium">
                        <i class="fas fa-check-circle mr-2"></i>
                        Diterima
                    </div>
                @else
                    <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full font-medium">
                        <i class="fas fa-times-circle mr-2"></i>
                        Ditolak
                    </div>
                @endif
                
                <div class="text-sm text-gray-500">
                    <i class="fas fa-calendar mr-1"></i>
                    {{ $pendaftar->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Rejection Notice -->
            @if($pendaftar->isRejected() && $pendaftar->rejection_reason)
                <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-red-800 mb-2">Aplikasi Ditolak</h3>
                            <div class="bg-white rounded-lg p-4 mb-4">
                                <p class="text-red-700 italic">{{ $pendaftar->rejection_reason }}</p>
                            </div>
                            
                            <div class="flex flex-wrap gap-4 text-sm">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-2 text-red-600"></i>
                                    <span class="text-red-700">{{ $pendaftar->rejection_date ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center">
                                    @if($pendaftar->can_resubmit)
                                        <i class="fas fa-redo mr-2 text-blue-600"></i>
                                        <span class="text-blue-700 font-medium">Dapat Submit Ulang</span>
                                    @else
                                        <i class="fas fa-ban mr-2 text-gray-600"></i>
                                        <span class="text-gray-700 font-medium">Ditolak Permanen</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Personal Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Personal</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    @php
                        // Process form fields (simplified from original code)
                        $beasiswa = $pendaftar->beasiswa;
                        $rawFormFields = [];
                        
                        if (method_exists($beasiswa, 'getRawFormFieldsAttribute')) {
                            $rawFormFields = $beasiswa->getRawFormFieldsAttribute() ?: [];
                        } else {
                            $formFieldsJson = $beasiswa->attributes['form_fields'] ?? ($beasiswa->form_fields ?? '[]');
                            $rawFormFields = is_string($formFieldsJson) ? json_decode($formFieldsJson, true) ?: [] : (is_array($formFieldsJson) ? $formFieldsJson : []);
                        }

                        // Get submitted data
                        $submittedRaw = $pendaftar->form_data ?? $pendaftar->form_values ?? $pendaftar->data ?? [];
                        $submitted = is_string($submittedRaw) ? json_decode($submittedRaw, true) ?: [] : (is_array($submittedRaw) ? $submittedRaw : []);
                        
                        // Group fields by position
                        $personalFields = collect($rawFormFields)->where('position', 'personal')->take(6);
                        $academicFields = collect($rawFormFields)->where('position', 'academic')->take(6);
                        $otherFields = collect($rawFormFields)->whereNotIn('position', ['personal', 'academic', 'attachments'])->take(6);
                    @endphp

                    <!-- Personal Data Grid -->
                    @if($personalFields->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @foreach($personalFields as $field)
                                @php
                                    $key = $field['key'] ?? strtolower(str_replace(' ', '_', $field['name'] ?? ''));
                                    $value = $submitted[$key] ?? $submitted[$field['name'] ?? ''] ?? null;
                                    $displayValue = is_array($value) ? implode(', ', $value) : $value;
                                @endphp
                                
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="{{ $field['icon'] ?? 'fas fa-info-circle' }} text-gray-500 text-sm"></i>
                                        <label class="text-sm font-medium text-gray-700">{{ $field['name'] ?? 'Unknown Field' }}</label>
                                        @if($field['required'] ?? false)
                                            <span class="text-red-500 text-xs">*</span>
                                        @endif
                                    </div>
                                    <div class="text-gray-900 font-medium">
                                        @php
                                            // Check if field has options and current value matches an option
                                            $finalDisplayValue = $displayValue;
                                            if (!empty($field['options']) && $displayValue) {
                                                foreach ($field['options'] as $option) {
                                                    if (isset($option['value']) && $option['value'] == $displayValue) {
                                                        $finalDisplayValue = $option['label'] ?? $displayValue;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        {{ $finalDisplayValue ?: 'Tidak diisi' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Academic Information -->
                    @if($academicFields->count() > 0)
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-graduation-cap text-green-600"></i>
                                <h3 class="text-md font-semibold text-gray-900">Informasi Akademik</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($academicFields as $field)
                                    @php
                                        $key = $field['key'] ?? strtolower(str_replace(' ', '_', $field['name'] ?? ''));
                                        $value = $submitted[$key] ?? $submitted[$field['name'] ?? ''] ?? null;
                                        $displayValue = is_array($value) ? implode(', ', $value) : $value;
                                    @endphp
                                    
                                    <div class="bg-green-50 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="{{ $field['icon'] ?? 'fas fa-book' }} text-green-600 text-sm"></i>
                                            <label class="text-sm font-medium text-gray-700">{{ $field['name'] ?? 'Unknown Field' }}</label>
                                        </div>
                                        <div class="text-gray-900 font-medium">
                                            @php
                                                // Check if field has options and current value matches an option
                                                $finalDisplayValue = $displayValue;
                                                if (!empty($field['options']) && $displayValue) {
                                                    foreach ($field['options'] as $option) {
                                                        if (isset($option['value']) && $option['value'] == $displayValue) {
                                                            $finalDisplayValue = $option['label'] ?? $displayValue;
                                                            break;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            {{ $finalDisplayValue ?: 'Tidak diisi' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Other Information -->
                    @if($otherFields->count() > 0)
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-list text-purple-600"></i>
                                <h3 class="text-md font-semibold text-gray-900">Informasi Tambahan</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($otherFields as $field)
                                    @php
                                        $key = $field['key'] ?? strtolower(str_replace(' ', '_', $field['name'] ?? ''));
                                        $value = $submitted[$key] ?? $submitted[$field['name'] ?? ''] ?? null;
                                        $displayValue = is_array($value) ? implode(', ', $value) : $value;
                                    @endphp
                                    
                                    <div class="bg-purple-50 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="{{ $field['icon'] ?? 'fas fa-tag' }} text-purple-600 text-sm"></i>
                                            <label class="text-sm font-medium text-gray-700">{{ $field['name'] ?? 'Unknown Field' }}</label>
                                        </div>
                                        <div class="text-gray-900 font-medium">
                                            @php
                                                // Check if field has options and current value matches an option
                                                $finalDisplayValue = $displayValue;
                                                if (!empty($field['options']) && $displayValue) {
                                                    // Handle multiple values (for checkboxes)
                                                    if (is_array($value)) {
                                                        $labels = [];
                                                        foreach ($value as $val) {
                                                            foreach ($field['options'] as $option) {
                                                                if (isset($option['value']) && $option['value'] == $val) {
                                                                    $labels[] = $option['label'] ?? $val;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        $finalDisplayValue = !empty($labels) ? implode(', ', $labels) : $displayValue;
                                                    } else {
                                                        // Handle single value (for radio/select)
                                                        foreach ($field['options'] as $option) {
                                                            if (isset($option['value']) && $option['value'] == $displayValue) {
                                                                $finalDisplayValue = $option['label'] ?? $displayValue;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp
                                            {{ $finalDisplayValue ?: 'Tidak diisi' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents Section -->
            @if($pendaftar->beasiswa->required_documents && count($pendaftar->beasiswa->required_documents) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-folder-open text-amber-600"></i>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900">Dokumen Pendukung</h2>
                            </div>
                            
                            @php
                                $totalDocs = count($pendaftar->beasiswa->required_documents);
                                $uploadedDocs = 0;
                                foreach ($pendaftar->beasiswa->required_documents as $doc) {
                                    if ($pendaftar->getDocument($doc['key'])) {
                                        $uploadedDocs++;
                                    }
                                }
                                $completionPercentage = $totalDocs > 0 ? round(($uploadedDocs / $totalDocs) * 100) : 100;
                            @endphp
                            
                            <div class="text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $completionPercentage >= 100 ? 'bg-green-100 text-green-800' : 
                                       ($completionPercentage >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $uploadedDocs }}/{{ $totalDocs }} dokumen
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($pendaftar->beasiswa->required_documents as $document)
                                @php
                                    $uploadedFile = $pendaftar->getDocument($document['key']);
                                @endphp
                                
                                <div class="border rounded-lg p-4 {{ $uploadedFile ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                                    <div class="text-center">
                                        <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                                            {{ $uploadedFile ? 'bg-green-100' : 'bg-red-100' }}">
                                            <i class="{{ $document['icon'] }} text-{{ $document['color'] }}-600"></i>
                                        </div>
                                        
                                        <h4 class="font-medium text-gray-900 mb-2">{{ $document['name'] }}</h4>
                                        
                                        @if($uploadedFile)
                                            <a href="{{ asset('storage/documents/' . $uploadedFile) }}" target="_blank"
                                               class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                                <i class="fas fa-eye mr-1"></i>
                                                Lihat File
                                            </a>
                                            <div class="mt-2 text-xs text-green-700 font-mono truncate">
                                                {{ $uploadedFile }}
                                            </div>
                                        @else
                                            <div class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg text-sm">
                                                <i class="fas fa-times mr-1"></i>
                                                Belum Upload
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Cepat</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-users text-blue-600"></i>
                            <span class="text-sm font-medium text-gray-700">Total Pendaftar</span>
                        </div>
                        <span class="text-lg font-bold text-blue-600">{{ $pendaftar->beasiswa->pendaftars->count() }}</span>
                    </div>
                    
                    @php
                        $beasiswaStats = $pendaftar->beasiswa->pendaftars;
                        $pending = $beasiswaStats->where('status', 'pending')->count();
                        $diterima = $beasiswaStats->where('status', 'diterima')->count();
                        $ditolak = $beasiswaStats->where('status', 'ditolak')->count();
                    @endphp
                    
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="p-2 bg-yellow-50 rounded-lg">
                            <div class="text-lg font-bold text-yellow-600">{{ $pending }}</div>
                            <div class="text-xs text-gray-600">Pending</div>
                        </div>
                        <div class="p-2 bg-green-50 rounded-lg">
                            <div class="text-lg font-bold text-green-600">{{ $diterima }}</div>
                            <div class="text-xs text-gray-600">Diterima</div>
                        </div>
                        <div class="p-2 bg-red-50 rounded-lg">
                            <div class="text-lg font-bold text-red-600">{{ $ditolak }}</div>
                            <div class="text-xs text-gray-600">Ditolak</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Panel -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi</h3>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('admin.pendaftar.update-status', $pendaftar) }}" method="POST" id="statusForm">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                            <select name="status" id="statusSelect" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="pending" {{ $pendaftar->status == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="diterima" {{ $pendaftar->status == 'diterima' ? 'selected' : '' }}>
                                    Diterima
                                </option>
                                <option value="ditolak" {{ $pendaftar->status == 'ditolak' ? 'selected' : '' }}>
                                    Ditolak
                                </option>
                            </select>
                        </div>

                        <!-- Rejection Fields -->
                        <div id="rejectionFields" class="mb-4" style="display: {{ $pendaftar->status == 'ditolak' ? 'block' : 'none' }};">
                            <div class="space-y-4 p-4 bg-red-50 rounded-lg border border-red-200">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Alasan Penolakan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="rejection_reason" id="rejectionReason" rows="3"
                                        class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                                        placeholder="Masukkan alasan penolakan" 
                                        minlength="10" maxlength="1000">{{ old('rejection_reason', $pendaftar->rejection_reason) }}</textarea>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <span id="charCount">0</span>/1000 karakter (minimal 10)
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Status Submit Ulang <span class="text-red-500">*</span>
                                    </label>
                                    <div class="space-y-2">
                                        <label class="flex items-center p-3 border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50">
                                            <input type="radio" name="can_resubmit" value="1" class="text-blue-600 mr-3"
                                                {{ old('can_resubmit', $pendaftar->can_resubmit) == '1' ? 'checked' : '' }}>
                                            <div>
                                                <div class="font-medium text-gray-900">Dapat Submit Ulang</div>
                                                <div class="text-xs text-gray-600">User dapat mengedit dan mengajukan kembali</div>
                                            </div>
                                        </label>
                                        <label class="flex items-center p-3 border border-red-200 rounded-lg cursor-pointer hover:bg-red-50">
                                            <input type="radio" name="can_resubmit" value="0" class="text-red-600 mr-3"
                                                {{ old('can_resubmit', $pendaftar->can_resubmit) == '0' ? 'checked' : '' }}>
                                            <div>
                                                <div class="font-medium text-gray-900">Ditolak Permanen</div>
                                                <div class="text-xs text-gray-600">User tidak dapat mengajukan kembali</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Update Status
                        </button>
                    </form>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="space-y-2">
                            <a href="{{ route('admin.pendaftar.index') }}"
                               class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                            </a>
                            
                            <form action="{{ route('admin.pendaftar.destroy', $pendaftar) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data pendaftar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-trash mr-2"></i>Hapus Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Summary -->
            @if($pendaftar->beasiswa->required_documents && count($pendaftar->beasiswa->required_documents) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Dokumen</h3>
                    
                    @php
                        $totalRequired = collect($pendaftar->beasiswa->required_documents)->where('required', true)->count();
                        $uploadedRequired = 0;
                        foreach ($pendaftar->beasiswa->required_documents as $doc) {
                            if ($doc['required'] && $pendaftar->getDocument($doc['key'])) {
                                $uploadedRequired++;
                            }
                        }
                        $completionPercentage = $totalRequired > 0 ? round(($uploadedRequired / $totalRequired) * 100) : 100;
                    @endphp

                    <div class="mb-4">
                        <div class="flex justify-between text-sm font-medium text-gray-700 mb-1">
                            <span>Kelengkapan</span>
                            <span>{{ $uploadedRequired }}/{{ $totalRequired }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $completionPercentage }}%"></div>
                        </div>
                        <div class="text-center mt-2">
                            <span class="text-sm font-medium {{ $completionPercentage >= 100 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $completionPercentage }}% Lengkap
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        @foreach($pendaftar->beasiswa->required_documents as $document)
                            @php $isUploaded = $pendaftar->getDocument($document['key']); @endphp
                            <div class="flex items-center justify-between p-2 rounded-lg {{ $isUploaded ? 'bg-green-50' : 'bg-red-50' }}">
                                <div class="flex items-center gap-2">
                                    <i class="{{ $document['icon'] }} text-{{ $document['color'] }}-600 text-xs"></i>
                                    <span class="text-xs font-medium text-gray-900">{{ $document['name'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    @if($isUploaded)
                                        <i class="fas fa-check-circle text-green-600 text-xs"></i>
                                    @else
                                        <i class="fas fa-times-circle text-red-600 text-xs"></i>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusSelect = document.getElementById('statusSelect');
            const rejectionFields = document.getElementById('rejectionFields');
            const rejectionReason = document.getElementById('rejectionReason');
            const charCount = document.getElementById('charCount');
            const statusForm = document.getElementById('statusForm');

            function toggleRejectionFields() {
                if (statusSelect.value === 'ditolak') {
                    rejectionFields.style.display = 'block';
                    if (rejectionReason) rejectionReason.required = true;
                } else {
                    rejectionFields.style.display = 'none';
                    if (rejectionReason) rejectionReason.required = false;
                    document.querySelectorAll('input[name="can_resubmit"]').forEach(radio => {
                        radio.checked = false;
                    });
                }
            }

            function updateCharCount() {
                if (!rejectionReason) return;
                const count = rejectionReason.value.length;
                charCount.textContent = count;
            }

            statusSelect.addEventListener('change', toggleRejectionFields);
            if (rejectionReason) {
                rejectionReason.addEventListener('input', updateCharCount);
            }

            toggleRejectionFields();
            if (rejectionReason) {
                updateCharCount();
            }

            statusForm.addEventListener('submit', function (e) {
                if (statusSelect.value === 'ditolak') {
                    const reason = rejectionReason ? rejectionReason.value.trim() : '';
                    const canResubmit = document.querySelector('input[name="can_resubmit"]:checked');

                    if (!reason || reason.length < 10) {
                        e.preventDefault();
                        alert('Alasan penolakan minimal 10 karakter!');
                        if (rejectionReason) rejectionReason.focus();
                        return false;
                    }

                    if (!canResubmit) {
                        e.preventDefault();
                        alert('Pilih status submit ulang!');
                        return false;
                    }

                    if (!confirm('Konfirmasi penolakan dengan alasan: ' + reason.substring(0, 100) + (reason.length > 100 ? '...' : ''))) {
                        e.preventDefault();
                        return false;
                    }
                }
            });
        });
    </script>
@endsection