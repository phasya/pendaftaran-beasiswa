@extends('layouts.app')

@section('title', 'Status Pendaftaran')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">

                @if($userApplication)
                    <!-- Header Section -->
                    <div class="mb-8">
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-trophy text-orange-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h1 class="text-3xl font-bold text-gray-900">{{ $userApplication->beasiswa->nama_beasiswa }}</h1>
                                        <p class="text-gray-600">Status Pendaftaran Beasiswa Anda</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="flex flex-col items-start lg:items-end gap-3">
                                @if($userApplication->status == 'pending')
                                    <div class="bg-gradient-to-r from-yellow-400 to-amber-500 px-6 py-3 rounded-2xl shadow-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="animate-pulse">
                                                <i class="fas fa-clock text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="text-white font-bold text-lg">Pending Review</div>
                                                <div class="text-yellow-100 text-sm">Sedang diproses admin</div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($userApplication->status == 'diterima')
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-3 rounded-2xl shadow-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="animate-bounce">
                                                <i class="fas fa-check-circle text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="text-white font-bold text-lg">Diterima</div>
                                                <div class="text-green-100 text-sm">Selamat! Beasiswa Anda diterima</div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-gradient-to-r from-red-500 to-rose-600 px-6 py-3 rounded-2xl shadow-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="animate-pulse">
                                                <i class="fas fa-times-circle text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="text-white font-bold text-lg">Ditolak</div>
                                                <div class="text-red-100 text-sm">Pendaftaran tidak diterima</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Diajukan: {{ $userApplication->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                        <!-- Main Content -->
                        <div class="lg:col-span-3 space-y-6">

                            {{-- Enhanced Rejection Information --}}
                            @if($userApplication->isRejected() && $userApplication->rejection_reason)
                                <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-6">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-red-800 mb-2">Informasi Penolakan</h3>
                                            <div class="bg-white rounded-lg p-4 mb-4">
                                                <p class="text-red-700 italic leading-relaxed">{{ $userApplication->rejection_reason }}</p>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                <div class="flex items-center">
                                                    <i class="fas fa-calendar mr-2 text-red-600"></i>
                                                    <span class="text-red-700">{{ $userApplication->rejection_date ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    @if($userApplication->can_resubmit)
                                                        <i class="fas fa-redo mr-2 text-blue-600"></i>
                                                        <span class="text-blue-700 font-medium">Dapat Submit Ulang</span>
                                                    @else
                                                        <i class="fas fa-ban mr-2 text-gray-600"></i>
                                                        <span class="text-gray-700 font-medium">Ditolak Permanen</span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($userApplication->can_resubmit && $userApplication->beasiswa->isActive())
                                                <div class="mt-6">
                                                    <a href="{{ route('pendaftar.resubmit', $userApplication) }}"
                                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-bold rounded-xl hover:from-orange-600 hover:to-amber-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                                                        <i class="fas fa-edit mr-2"></i>Edit dan Submit Ulang
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Data Pendaftar -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <h2 class="text-lg font-semibold text-gray-900">Data Pendaftaran Anda</h2>
                                    </div>
                                </div>

                                <div class="p-6">
                                    @php
                                        // Process form fields dari beasiswa
                                        $beasiswa = $userApplication->beasiswa;
                                        $rawFormFields = [];

                                        if (method_exists($beasiswa, 'getRawFormFieldsAttribute')) {
                                            $rawFormFields = $beasiswa->getRawFormFieldsAttribute() ?: [];
                                        } else {
                                            $formFieldsJson = $beasiswa->attributes['form_fields'] ?? ($beasiswa->form_fields ?? '[]');
                                            $rawFormFields = is_string($formFieldsJson) ? json_decode($formFieldsJson, true) ?: [] : (is_array($formFieldsJson) ? $formFieldsJson : []);
                                        }

                                        // Get submitted data dari user
                                        $submittedRaw = $userApplication->form_data ?? $userApplication->form_values ?? $userApplication->data ?? [];
                                        $submitted = is_string($submittedRaw) ? json_decode($submittedRaw, true) ?: [] : (is_array($submittedRaw) ? $submittedRaw : []);

                                        // Group fields by position
                                        $personalFields = collect($rawFormFields)->where('position', 'personal');
                                        $academicFields = collect($rawFormFields)->where('position', 'academic');
                                        $otherFields = collect($rawFormFields)->whereNotIn('position', ['personal', 'academic', 'attachments']);
                                    @endphp

                                    <!-- Personal Information -->
                                    @if($personalFields->count() > 0)
                                        <div class="mb-6">
                                            <div class="flex items-center gap-2 mb-4">
                                                <i class="fas fa-user-circle text-blue-600"></i>
                                                <h3 class="text-md font-semibold text-gray-900">Informasi Personal</h3>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach($personalFields as $field)
                                                    @php
                                                        $key = $field['key'] ?? strtolower(str_replace(' ', '_', $field['name'] ?? ''));
                                                        $value = $submitted[$key] ?? $submitted[$field['name'] ?? ''] ?? null;
                                                        $displayValue = is_array($value) ? implode(', ', $value) : $value;
                                                    @endphp

                                                    <div class="bg-blue-50 rounded-lg p-4">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <i class="{{ $field['icon'] ?? 'fas fa-info-circle' }} text-blue-600 text-sm"></i>
                                                            <label class="text-sm font-medium text-gray-700">{{ $field['name'] ?? 'Unknown Field' }}</label>
                                                            @if($field['required'] ?? false)
                                                                <span class="text-red-500 text-xs">*</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-gray-900 font-medium">
                                                            @php
                                                                // Handle options untuk dropdown/radio/checkbox
                                                                $finalDisplayValue = $displayValue;
                                                                if (!empty($field['options']) && $displayValue) {
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

                                    <!-- Academic Information -->
                                    @if($academicFields->count() > 0)
                                        <div class="mb-6">
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
                                                                $finalDisplayValue = $displayValue;
                                                                if (!empty($field['options']) && $displayValue) {
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

                                    <!-- Other Information -->
                                    @if($otherFields->count() > 0)
                                        <div class="mb-6">
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
                                                                $finalDisplayValue = $displayValue;
                                                                if (!empty($field['options']) && $displayValue) {
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
                            @if($userApplication->beasiswa->required_documents && count($userApplication->beasiswa->required_documents) > 0)
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
                                                                $totalDocs = count($userApplication->beasiswa->required_documents);
                                                                $uploadedDocs = 0;
                                                                foreach ($userApplication->beasiswa->required_documents as $doc) {
                                                                    if ($userApplication->getDocument($doc['key'])) {
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
                                                            @foreach($userApplication->beasiswa->required_documents as $document)
                                                                @php
                                                                    $uploadedFile = $userApplication->getDocument($document['key']);
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

                            <!-- Timeline Status -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-timeline text-amber-600"></i>
                                        </div>
                                        <h2 class="text-lg font-semibold text-gray-900">Timeline Status</h2>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="space-y-6">
                                        <!-- Submitted -->
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center shadow-lg">
                                                <i class="fas fa-paper-plane text-white text-lg"></i>
                                            </div>
                                            <div class="ml-6 flex-1">
                                                <div class="bg-orange-50 rounded-xl p-4 border-l-4 border-orange-500">
                                                    <div class="text-lg font-bold text-orange-900">Beasiswa Diajukan</div>
                                                    <div class="text-amber-700">
                                                        {{ $userApplication->created_at->format('d F Y \p\u\k\u\l H:i') }}</div>
                                                    <div class="text-sm text-orange-600 mt-1">Pendaftaran berhasil dikirim ke sistem</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Current Status -->
                                        @if($userApplication->status == 'pending')
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center animate-pulse shadow-lg">
                                                    <i class="fas fa-clock text-white text-lg"></i>
                                                </div>
                                                <div class="ml-6 flex-1">
                                                    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-500 rounded-xl p-4">
                                                        <div class="text-lg font-bold text-yellow-900">Sedang Dalam Review</div>
                                                        <div class="text-amber-700">Menunggu keputusan admin</div>
                                                        <div class="text-sm text-yellow-600 mt-1">Dokumen dan data Anda sedang diperiksa</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($userApplication->status == 'diterima')
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-12 h-12 bg-green-500 rounded-full flex items-center justify-center animate-bounce shadow-lg">
                                                    <i class="fas fa-check text-white text-lg"></i>
                                                </div>
                                                <div class="ml-6 flex-1">
                                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-xl p-4">
                                                        <div class="text-lg font-bold text-green-900">🎉 Beasiswa Diterima!</div>
                                                        <div class="text-green-700">Selamat! Pendaftaran Anda telah disetujui</div>
                                                        <div class="text-sm text-green-600 mt-1">Anda berhak mendapatkan dana beasiswa sesuai ketentuan</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-12 h-12 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                                                    <i class="fas fa-times text-white text-lg"></i>
                                                </div>
                                                <div class="ml-6 flex-1">
                                                    <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-xl p-4">
                                                        <div class="text-lg font-bold text-red-900">Beasiswa Ditolak</div>
                                                        <div class="text-red-700">
                                                            {{ $userApplication->rejection_date ?? 'Tanggal tidak tersedia' }}
                                                        </div>
                                                        <div class="text-sm text-red-600 mt-1">Mohon periksa informasi penolakan di atas</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="lg:col-span-1 space-y-6">

                            <!-- Quick Info -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Info Beasiswa</h3>

                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-money-bill-wave text-orange-600"></i>
                                            <span class="text-sm font-medium text-gray-700">Dana Beasiswa</span>
                                        </div>
                                        <span class="text-lg font-bold text-orange-600">
                                            Rp {{ number_format($userApplication->beasiswa->jumlah_dana, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-calendar text-blue-600"></i>
                                            <span class="text-sm font-medium text-gray-700">Batas Tutup</span>
                                        </div>
                                        <span class="text-sm font-bold text-blue-600">
                                            {{ \Carbon\Carbon::parse($userApplication->beasiswa->tanggal_tutup)->format('d M Y') }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-users text-gray-600"></i>
                                            <span class="text-sm font-medium text-gray-700">Total Pendaftar</span>
                                        </div>
                                        <span class="text-lg font-bold text-gray-600">
                                            {{ $userApplication->beasiswa->pendaftars->count() }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Summary -->
                            @if($userApplication->beasiswa->required_documents && count($userApplication->beasiswa->required_documents) > 0)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Dokumen</h3>

                                    @php
                                        $totalRequired = collect($userApplication->beasiswa->required_documents)->where('required', true)->count();
                                        $uploadedRequired = 0;
                                        foreach ($userApplication->beasiswa->required_documents as $doc) {
                                            if ($doc['required'] && $userApplication->getDocument($doc['key'])) {
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
                                        @foreach($userApplication->beasiswa->required_documents as $document)
                                            @php $isUploaded = $userApplication->getDocument($document['key']); @endphp
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

                            <!-- Action Panel -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tindakan</h3>

                                <div class="space-y-3">
                                    @if($userApplication->status == 'diterima')
                                        <button onclick="window.print()"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                            <i class="fas fa-print mr-2"></i>Cetak Bukti Penerimaan
                                        </button>
                                    @endif

                                    @if($userApplication->canResubmit() && $userApplication->beasiswa->isActive())
                                        <a href="{{ route('pendaftar.resubmit', $userApplication) }}"
                                           class="block text-center bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                            <i class="fas fa-edit mr-2"></i>Edit Pendaftaran
                                        </a>
                                    @endif

                                    <a href="{{ route('home') }}"
                                       class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
                                    </a>
                                </div>
                            </div>

                            <!-- Status Notification -->
                            @if($userApplication->status == 'diterima')
                                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                                    <div class="flex items-center justify-center mb-4">
                                        <div class="bg-green-500 p-3 rounded-full mr-4">
                                            <i class="fas fa-trophy text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-green-800">Selamat!</h4>
                                            <p class="text-green-600 text-sm">Beasiswa Anda telah diterima</p>
                                        </div>
                                    </div>
                                    <p class="text-green-700 text-sm text-center">
                                        Informasi selanjutnya akan dikirim melalui email atau kontak yang terdaftar
                                    </p>
                                </div>
                            @elseif($userApplication->status == 'pending')
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                                    <div class="flex items-center justify-center mb-4">
                                        <div class="bg-yellow-500 p-3 rounded-full mr-4 animate-pulse">
                                            <i class="fas fa-clock text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-yellow-800">Sedang Diproses</h4>
                                            <p class="text-yellow-600 text-sm">Harap bersabar menunggu</p>
                                        </div>
                                    </div>
                                    <p class="text-yellow-700 text-sm text-center">
                                        Admin sedang meninjau aplikasi Anda
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                @else
                    <!-- No Application State -->
                    <div class="bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 rounded-3xl shadow-xl border border-orange-200 p-12 text-center relative overflow-hidden">
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute inset-0"
                                style="background-image: radial-gradient(circle at 2px 2px, rgba(249,115,22,0.4) 1px, transparent 0); background-size: 30px 30px;">
                            </div>
                        </div>

                        <div class="relative z-10">
                            <div class="text-8xl text-orange-300 mb-6 animate-pulse">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h3 class="text-3xl font-bold text-orange-800 mb-4">Belum Ada Pendaftaran Beasiswa</h3>
                            <p class="text-amber-700 mb-8 text-lg">Anda belum mendaftar untuk beasiswa apapun. Jangan lewatkan
                                kesempatan emas ini!</p>

                            <div class="bg-white rounded-2xl p-6 mb-8 shadow-sm">
                                <h4 class="font-semibold text-orange-800 mb-3">Mengapa harus mendaftar beasiswa?</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-money-bill-wave text-green-500"></i>
                                        <span class="text-gray-700">Bantuan biaya kuliah</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-graduation-cap text-blue-500"></i>
                                        <span class="text-gray-700">Prestasi akademik</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-star text-yellow-500"></i>
                                        <span class="text-gray-700">Pengembangan diri</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('home') }}"
                                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-bold text-lg rounded-2xl hover:from-orange-600 hover:to-amber-700 transform hover:scale-105 transition-all duration-300 shadow-xl hover:shadow-2xl">
                                <i class="fas fa-search mr-3"></i>Jelajahi Beasiswa Tersedia
                                <i class="fas fa-arrow-right ml-3"></i>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- All Applications History -->
                @if($allApplications && $allApplications->count() > 1)
                    <div class="bg-white rounded-3xl shadow-xl border border-orange-200 overflow-hidden mt-8">
                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-8 py-6">
                            <h3 class="text-2xl font-bold text-white flex items-center">
                                <i class="fas fa-history text-orange-200 mr-3"></i>Riwayat Pendaftaran Lainnya
                            </h3>
                            <p class="text-orange-100 mt-1">Daftar aplikasi beasiswa Anda yang lain</p>
                        </div>
                        <div class="p-8">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b-2 border-orange-200">
                                            <th class="text-left py-4 px-6 font-bold text-orange-800 text-lg">Beasiswa</th>
                                            <th class="text-left py-4 px-6 font-bold text-orange-800 text-lg">Tanggal</th>
                                            <th class="text-left py-4 px-6 font-bold text-orange-800 text-lg">Status</th>
                                            <th class="text-left py-4 px-6 font-bold text-orange-800 text-lg">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allApplications as $application)
                                            <tr class="border-b border-orange-100 hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 transition-all duration-300">
                                                <td class="py-4 px-6">
                                                    <div class="font-bold text-orange-900 text-lg">
                                                        {{ $application->beasiswa->nama_beasiswa }}</div>
                                                    <div class="text-amber-700 flex items-center mt-1">
                                                        <i class="fas fa-coins mr-2"></i>
                                                        Rp {{ number_format($application->beasiswa->jumlah_dana, 0, ',', '.') }}
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6">
                                                    <div class="text-amber-800 font-semibold">
                                                        {{ $application->created_at->format('d M Y') }}</div>
                                                    <div class="text-sm text-orange-600">{{ $application->created_at->format('H:i') }}
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6">
                                                    @if($application->status == 'pending')
                                                        <div class="bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 px-4 py-2 rounded-full font-bold inline-flex items-center">
                                                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></div>
                                                            Pending
                                                        </div>
                                                    @elseif($application->status == 'diterima')
                                                        <div class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 px-4 py-2 rounded-full font-bold inline-flex items-center">
                                                            <i class="fas fa-check-circle mr-2 text-green-600"></i>
                                                            Diterima
                                                        </div>
                                                    @else
                                                        <div class="bg-gradient-to-r from-red-100 to-rose-100 text-red-800 px-4 py-2 rounded-full font-bold inline-flex items-center">
                                                            <i class="fas fa-times-circle mr-2 text-red-600"></i>
                                                            Ditolak
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6">
                                                    @if($application->canResubmit() && $application->beasiswa->isActive())
                                                        <a href="{{ route('pendaftar.resubmit', $application) }}"
                                                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold inline-flex items-center transition-colors duration-300">
                                                            <i class="fas fa-edit mr-2"></i>Edit
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400 italic">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <style>
            @keyframes pulse {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.5;
                }
            }

            @keyframes bounce {
                0%, 20%, 53%, 80%, 100% {
                    transform: translateY(0);
                }
                40%, 43% {
                    transform: translateY(-8px);
                }
                70% {
                    transform: translateY(-4px);
                }
                90% {
                    transform: translateY(-2px);
                }
            }

            .animate-pulse {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            .animate-bounce {
                animation: bounce 1s infinite;
            }

            @media print {
                .no-print {
                    display: none !important;
                }
                body {
                    print-color-adjust: exact;
                    -webkit-print-color-adjust: exact;
                }
                .bg-gradient-to-r,
                .bg-gradient-to-br {
                    background: white !important;
                    color: black !important;
                    border: 2px solid #333 !important;
                }
                .text-white {
                    color: black !important;
                }
                .shadow-xl,
                .shadow-lg,
                .shadow-sm {
                    box-shadow: none !important;
                }
            }
        </style>
        
@endsection