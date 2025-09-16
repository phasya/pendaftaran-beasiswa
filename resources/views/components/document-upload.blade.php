{{-- resources/views/components/document-upload.blade.php --}}

<div class="file-upload-card border-2 border-dashed border-orange-300 rounded-xl p-6 text-center transition-all duration-300 cursor-pointer hover:border-orange-500 hover:bg-orange-50"
    id="card-{{ $document['key'] }}" data-original-icon="{{ $document['icon'] }}"
    data-original-color="{{ $document['color'] }}">

    <label for="{{ $document['key'] }}" class="file-label cursor-pointer">
        <div class="file-icon text-{{ $document['color'] }}-500 text-4xl mb-4">
            <i class="{{ $document['icon'] }}" id="icon-{{ $document['key'] }}"></i>
        </div>

        <div class="file-info">
            <h6 class="file-title font-semibold text-amber-800 mb-1">
                {{ $document['name'] }}
                @if($document['required'])
                    <span class="text-red-500">*</span>
                @endif
            </h6>

            <small class="file-desc text-orange-600 text-sm" id="desc-{{ $document['key'] }}">
                Format: {{ strtoupper(implode(', ', $document['formats'])) }}, Max: {{ $document['max_size'] }}MB
            </small>

            @if(!empty($document['description']))
                <div class="text-xs text-orange-500 mt-1">
                    {{ $document['description'] }}
                </div>
            @endif

            <!-- Status Upload -->
            <div class="upload-status mt-3 hidden" id="status-{{ $document['key'] }}">
                <div class="flex items-center justify-center mb-2">
                    <i class="fas fa-check-circle text-orange-600 mr-2"></i>
                    <span class="text-orange-700 font-medium text-sm" id="filename-{{ $document['key'] }}">
                        File berhasil dipilih
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-center space-x-2 mt-3">
                    <button type="button" onclick="previewFile('{{ $document['key'] }}')"
                        class="px-3 py-1 text-xs bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">
                        <i class="fas fa-eye mr-1"></i>Lihat File
                    </button>
                    <button type="button" onclick="removeFile('{{ $document['key'] }}')"
                        class="px-3 py-1 text-xs bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
    </label>

    <input type="file" class="hidden file-input" id="{{ $document['key'] }}" name="{{ $document['key'] }}"
        accept="{{ collect($document['formats'])->map(fn($ext) => '.' . $ext)->implode(',') }}" {{ $document['required'] ? 'required' : '' }} onchange="handleFileUpload('{{ $document['key'] }}')">

    @if($error)
        <div class="text-red-500 text-sm mt-2">{{ $error }}</div>
    @endif
</div>