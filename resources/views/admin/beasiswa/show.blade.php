@extends('layouts.admin')

@section('title', 'Detail Beasiswa')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="fas fa-graduation-cap"></i> Detail Beasiswa</h1>
        <p class="text-muted mb-0">
            <i class="fas fa-info-circle me-2"></i>Informasi lengkap tentang program beasiswa
        </p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="mb-1">{{ $beasiswa->judul ?? '—' }}</h3>
                <p class="text-muted mb-3">
                    <small>
                        <i class="fas fa-calendar-plus me-1"></i>
                        Dibuka: {{ optional($beasiswa->tanggal_buka)->format('d M Y') ?? '-' }}
                        &nbsp; | &nbsp;
                        Ditutup: {{ optional($beasiswa->tanggal_tutup)->format('d M Y') ?? '-' }}
                    </small>
                </p>

                <div class="mb-4">
                    <h6 class="section-title mb-2">
                        <i class="fas fa-align-left text-info me-2"></i>Deskripsi Beasiswa
                    </h6>
                    <div class="content-box">
                        <p class="mb-0 lh-lg">{!! nl2br(e($beasiswa->deskripsi ?? '')) !!}</p>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="info-card h-100 border-success">
                            <div class="info-icon text-success">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="info-content">
                                <h6 class="info-title">Jumlah Dana</h6>
                                <p class="info-value text-success fw-bold fs-4 mb-0">
                                    Rp {{ isset($beasiswa->jumlah_dana) ? number_format($beasiswa->jumlah_dana, 0, ',', '.') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card h-100 border-primary">
                            <div class="info-icon text-primary">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="info-content">
                                <h6 class="info-title">Kuota</h6>
                                <p class="info-value fw-bold fs-4 mb-0">{{ $beasiswa->kuota ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card h-100 border-warning">
                            <div class="info-icon text-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h6 class="info-title">Tanggal Update</h6>
                                <p class="info-value fw-bold fs-6 mb-0">{{ optional($beasiswa->updated_at)->format('d M Y H:i') ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================
                     Parsing & rendering dynamic form fields
                     ============================================ --}}
                @php
                    // Ambil data raw dari attribute / accessor
                    if (method_exists($beasiswa, 'getRawFormFieldsAttribute')) {
                        $rawFormFields = $beasiswa->getRawFormFieldsAttribute() ?: [];
                    } else {
                        $formFieldsJson = $beasiswa->attributes['form_fields'] ?? ($beasiswa->form_fields ?? '[]');
                        $rawFormFields = is_string($formFieldsJson) ? json_decode($formFieldsJson, true) ?: [] : (is_array($formFieldsJson) ? $formFieldsJson : []);
                    }

                    if (!is_array($rawFormFields)) {
                        $rawFormFields = [];
                    }

                    $cleanFormFields = [];
                    $usedFieldKeys = [];

                    $slugFn = function($text) {
                        if (class_exists(\Illuminate\Support\Str::class)) {
                            return \Illuminate\Support\Str::slug($text, '_');
                        }
                        $s = preg_replace('/[^A-Za-z0-9]+/', '_', $text);
                        $s = trim($s, '_');
                        return strtolower($s ?: 'field');
                    };

                    foreach ($rawFormFields as $idx => $f) {
                        if (!is_array($f)) continue;
                        $name = isset($f['name']) ? trim((string)$f['name']) : '';
                        if ($name === '') continue;

                        $type = isset($f['type']) ? trim((string)$f['type']) : 'text';
                        $key = isset($f['key']) ? trim((string)$f['key']) : '';
                        if ($key === '') {
                            $key = $slugFn($name);
                        } else {
                            $key = $slugFn($key);
                        }

                        // ensure unique key
                        $baseKey = $key;
                        $kSuffix = 1;
                        while (in_array($key, $usedFieldKeys)) {
                            $key = $baseKey . '_' . $kSuffix;
                            $kSuffix++;
                        }

                        // skip exact duplicates by name+type (case-insensitive)
                        $isDuplicate = false;
                        foreach ($cleanFormFields as $existing) {
                            if (strtolower($existing['name']) === strtolower($name) && $existing['type'] === $type) {
                                $isDuplicate = true;
                                break;
                            }
                        }
                        if ($isDuplicate) continue;

                        $icon = isset($f['icon']) ? trim((string)$f['icon']) : ($f['icon'] ?? 'fas fa-user');
                        $placeholder = isset($f['placeholder']) ? trim((string)$f['placeholder']) : '';
                        $position = isset($f['position']) ? trim((string)$f['position']) : 'personal';
                        $validation = isset($f['validation']) ? trim((string)$f['validation']) : '';
                        $required = isset($f['required']) ? (bool)$f['required'] : false;

                        $options = [];
                        if (isset($f['options']) && is_array($f['options'])) {
                            foreach ($f['options'] as $opt) {
                                if (!is_array($opt)) continue;
                                $val = isset($opt['value']) ? (string)$opt['value'] : (isset($opt['label']) ? (string)$opt['label'] : null);
                                $lab = isset($opt['label']) ? (string)$opt['label'] : $val;
                                if ($val === null) continue;
                                $options[] = ['value' => $val, 'label' => $lab];
                            }
                        }

                        $entry = [
                            'index' => $idx,
                            'name' => $name,
                            'key' => $key,
                            'type' => $type,
                            'icon' => $icon,
                            'placeholder' => $placeholder,
                            'position' => $position,
                            'validation' => $validation,
                            'required' => $required,
                            'options' => $options,
                        ];

                        $cleanFormFields[] = $entry;
                        $usedFieldKeys[] = $key;
                    }

                    // Group by position
                    $formFieldsByPosition = [];
                    foreach ($cleanFormFields as $field) {
                        $pos = $field['position'] ?: 'other';
                        if (!isset($formFieldsByPosition[$pos])) {
                            $formFieldsByPosition[$pos] = [];
                        }
                        $formFieldsByPosition[$pos][] = $field;
                    }

                    $positionsOrder = ['personal', 'academic', 'other', 'attachments'];
                    $positionLabels = [
                        'personal' => 'Data Personal',
                        'academic' => 'Data Akademik',
                        'attachments' => 'Lampiran / Dokumen',
                        'other' => 'Lainnya',
                    ];
                    $positionColors = [
                        'personal' => 'primary',
                        'academic' => 'success',
                        'attachments' => 'warning',
                        'other' => 'secondary',
                    ];
                @endphp

                @php
                    $totalFields = count($cleanFormFields);
                    $requiredFields = collect($cleanFormFields)->where('required', true)->count();
                    $optionalFields = max(0, $totalFields - $requiredFields);
                @endphp

                <div class="detail-section mb-4">
                    <h6 class="section-title mb-3">
                        <i class="fas fa-wpforms text-primary me-2"></i>Konfigurasi Form Pendaftaran
                    </h6>

                    <div class="mb-3">
                        <div class="alert alert-info border-0">
                            <i class="fas fa-chart-pie me-2"></i>
                            <strong>Ringkasan Form:</strong>
                            Total {{ $totalFields }} field ({{ $requiredFields }} wajib, {{ $optionalFields }} opsional)
                        </div>
                    </div>

                    @if($totalFields === 0)
                        <div class="alert alert-secondary">
                            <i class="fas fa-exclamation-circle me-2"></i>Tidak ada field form yang dikonfigurasi untuk beasiswa ini.
                        </div>
                    @else
                        @foreach($formFieldsByPosition as $position => $fields)
                            <div class="detail-section mb-4">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-circle text-{{ $positionColors[$position] ?? 'secondary' }} me-2"></i>
                                    {{ $positionLabels[$position] ?? ucfirst($position) }}
                                    <small class="text-muted ms-2">({{ count($fields) }} field)</small>
                                </h6>

                                <div class="row g-3">
                                    @foreach($fields as $field)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="field-preview-card h-100 p-3 border rounded">
                                                <div class="d-flex align-items-start">
                                                    <div class="me-3">
                                                        <i class="{{ $field['icon'] }} fa-2x text-{{ $positionColors[$position] ?? 'secondary' }}"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $field['name'] }}
                                                            @if($field['required'])
                                                                <span class="badge bg-danger ms-2">Wajib</span>
                                                            @endif
                                                        </h6>
                                                        <div class="text-muted small">
                                                            <div><i class="fas fa-tag me-1"></i>Type: {{ ucfirst($field['type']) }}</div>
                                                            <div><i class="fas fa-key me-1"></i>Key: <code>{{ $field['key'] }}</code></div>
                                                            @if(!empty($field['placeholder']))
                                                                <div><i class="fas fa-info-circle me-1"></i>{{ $field['placeholder'] }}</div>
                                                            @endif
                                                            @if(!empty($field['validation']))
                                                                <div><i class="fas fa-check-circle me-1"></i>{{ $field['validation'] }}</div>
                                                            @endif

                                                            @if(in_array($field['type'], ['select','radio','checkbox']) && !empty($field['options']))
                                                                <div class="mt-2">
                                                                    <small class="text-muted">Opsi:</small>
                                                                    <ul class="mb-0">
                                                                        @foreach($field['options'] as $opt)
                                                                            <li><code>{{ $opt['value'] }}</code> — {{ $opt['label'] }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- ========================================
                     Required Documents (parsing + stronger dedup)
                     ======================================== --}}
                @php
                    if (method_exists($beasiswa, 'getRawRequiredDocumentsAttribute')) {
                        $rawDocuments = $beasiswa->getRawRequiredDocumentsAttribute() ?: [];
                    } else {
                        $docsJson = $beasiswa->attributes['required_documents'] ?? ($beasiswa->required_documents ?? '[]');
                        $rawDocuments = is_string($docsJson) ? json_decode($docsJson, true) ?: [] : (is_array($docsJson) ? $docsJson : []);
                    }
                    if (!is_array($rawDocuments)) $rawDocuments = [];

                    $cleanDocuments = [];
                    $seenDocSignatures = [];

                    foreach ($rawDocuments as $dIdx => $d) {
                        if (!is_array($d)) continue;
                        $dName = isset($d['name']) ? trim((string)$d['name']) : '';
                        if ($dName === '') continue;

                        $dKey = isset($d['key']) ? trim((string)$d['key']) : '';
                        // fallback key from name
                        if ($dKey === '') {
                            $dKey = $slugFn($dName);
                        } else {
                            $dKey = $slugFn($dKey);
                        }

                        $dRequired = isset($d['required']) ? (bool)$d['required'] : false;
                        $dMaxSize = isset($d['max_size']) ? (int)$d['max_size'] : 0;
                        $dFormats = isset($d['formats']) && is_array($d['formats']) ? $d['formats'] : [];

                        // normalize formats ordering to create stable signature
                        $normFormats = array_values(array_filter(array_map('strtolower', $dFormats)));
                        sort($normFormats);

                        // signature: name lowercase + required flag + maxsize + joined formats
                        $signature = strtolower($dName) . '|' . ($dRequired ? '1' : '0') . '|' . $dMaxSize . '|' . implode(',', $normFormats);

                        // If we've seen same signature already, skip (strong dedupe)
                        if (in_array($signature, $seenDocSignatures)) continue;

                        // ensure unique key (avoid collisions)
                        $base = $dKey;
                        $sfx = 1;
                        while (collect($cleanDocuments)->pluck('key')->contains($dKey)) {
                            $dKey = $base . '_' . $sfx;
                            $sfx++;
                        }

                        $color = isset($d['color']) && $d['color'] ? trim((string)$d['color']) : 'primary';
                        $icon = isset($d['icon']) && $d['icon'] ? trim((string)$d['icon']) : 'fas fa-file';
                        $desc = isset($d['description']) ? trim((string)$d['description']) : '';

                        $cleanDocuments[] = [
                            'name' => $dName,
                            'key' => $dKey,
                            'color' => $color,
                            'icon' => $icon,
                            'description' => $desc,
                            'required' => $dRequired,
                            'max_size' => $dMaxSize,
                            'formats' => $dFormats,
                        ];

                        $seenDocSignatures[] = $signature;
                    }
                @endphp

                @if(count($cleanDocuments) > 0)
                    <div class="detail-section mb-4">
                        <h6 class="section-title mb-3">
                            <i class="fas fa-folder-open text-warning me-2"></i>Dokumen yang Diperlukan
                            <small class="text-muted">({{ count($cleanDocuments) }} dokumen)</small>
                        </h6>

                        <div class="row g-4">
                            @foreach($cleanDocuments as $document)
                                <div class="col-md-{{ count($cleanDocuments) == 3 ? '4' : (count($cleanDocuments) == 2 ? '6' : '12') }}">
                                    <div class="document-card h-100 p-3 border rounded">
                                        <div class="d-flex">
                                            <div class="me-3 align-self-start">
                                                <i class="{{ $document['icon'] }} fa-2x text-{{ $document['color'] }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $document['name'] }}
                                                    @if($document['required'])
                                                        <span class="badge bg-danger ms-2">Wajib</span>
                                                    @endif
                                                </h6>
                                                @if(!empty($document['description']))
                                                    <p class="mb-1 text-muted small">{{ $document['description'] }}</p>
                                                @endif
                                                <small class="text-muted">
                                                    Key: <code>{{ $document['key'] }}</code>
                                                    @if(!empty($document['max_size']))
                                                        &nbsp; | &nbsp;Max: {{ $document['max_size'] }} KB
                                                    @endif
                                                    @if(!empty($document['formats']))
                                                        &nbsp; | &nbsp;Formats: {{ implode(', ', $document['formats']) }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="alert alert-secondary">
                        <i class="fas fa-info-circle me-2"></i>Tidak ada dokumen wajib yang didefinisikan.
                    </div>
                @endif

                {{-- Jika admin masih menyimpan persyaratan teks biasa, tampilkan juga --}}
                @if(!empty($beasiswa->persyaratan))
                    <div class="detail-section mt-4">
                        <h6 class="section-title mb-2">
                            <i class="fas fa-list-check text-warning me-2"></i>Persyaratan Pendaftaran (catatan admin)
                        </h6>
                        <div class="content-box">
                            {!! nl2br(e($beasiswa->persyaratan)) !!}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<style>
    /* small helpers to keep preview tidy */
    .info-card { display:flex; gap:1rem; padding:1rem; align-items:center; border-radius:.5rem; }
    .info-icon { font-size:1.6rem; width:48px; text-align:center; }
    .field-preview-card { background:#fff; }
    .document-card { background:#fff; }
    .detail-section { border-left:4px solid #6dd3d2; padding-left:1rem; margin-left:5px; }
    .section-title { font-weight:600; color:#495057; }
</style>
@endsection
