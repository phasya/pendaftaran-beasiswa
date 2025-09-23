<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beasiswa;
use Carbon\Carbon;

class BeasiswaController extends Controller
{
    public function index()
    {
        $beasiswas = Beasiswa::latest()->paginate(10);
        return view('admin.beasiswa.index', compact('beasiswas'));
    }

    public function create()
    {
        return view('admin.beasiswa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_beasiswa' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jumlah_dana' => 'required|numeric|min:0',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
            'status' => 'required|in:aktif,nonaktif',
            'persyaratan' => 'required|string',
            'form_fields_json' => 'required|string',
            'required_documents_json' => 'required|string',
        ]);

        try {
            // Decode and validate JSON data
            $formFields = json_decode($validated['form_fields_json'], true);
            $requiredDocuments = json_decode($validated['required_documents_json'], true);

            // Check for JSON decode errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Format data JSON tidak valid: ' . json_last_error_msg());
            }

            // Validate decoded data structure
            if (!is_array($formFields) || empty($formFields)) {
                throw new \Exception('Data form fields tidak valid atau kosong.');
            }

            if (!is_array($requiredDocuments) || empty($requiredDocuments)) {
                throw new \Exception('Data dokumen tidak valid atau kosong.');
            }

            // Process form fields with auto key generation
            $processedFormFields = [];
            $usedFieldKeys = [];

            foreach ($formFields as $index => $field) {
                if (!is_array($field) || empty($field['name'])) {
                    continue;
                }

                // Generate unique key if not provided
                if (empty($field['key'])) {
                    $field['key'] = $this->generateUniqueKey($field['name'], $usedFieldKeys);
                }

                // Ensure key uniqueness
                $key = $field['key'];
                $originalKey = $key;
                $counter = 1;
                while (in_array($key, $usedFieldKeys)) {
                    $key = $originalKey . '_' . $counter;
                    $counter++;
                }
                $usedFieldKeys[] = $key;

                // Process options
                $options = [];
                if (isset($field['options']) && is_array($field['options'])) {
                    foreach ($field['options'] as $option) {
                        if (
                            isset($option['value'], $option['label']) &&
                            !empty($option['value']) && !empty($option['label'])
                        ) {
                            $options[] = [
                                'value' => $option['value'],
                                'label' => $option['label']
                            ];
                        }
                    }
                }

                $processedFormFields[] = [
                    'name' => $field['name'],
                    'key' => $key,
                    'type' => $field['type'] ?? 'text',
                    'icon' => $field['icon'] ?? 'fas fa-user',
                    'placeholder' => $field['placeholder'] ?? '',
                    'position' => $field['position'] ?? 'personal',
                    'validation' => $field['validation'] ?? '',
                    'required' => (bool) ($field['required'] ?? true),
                    'options' => $options
                ];
            }

            // Process documents with auto key generation
            $processedDocuments = [];
            $usedDocKeys = [];

            foreach ($requiredDocuments as $index => $doc) {
                if (!is_array($doc) || empty($doc['name'])) {
                    continue;
                }

                // Generate unique key if not provided
                if (empty($doc['key'])) {
                    $doc['key'] = $this->generateUniqueKey($doc['name'], $usedDocKeys, 'file');
                }

                // Ensure key uniqueness
                $key = $doc['key'];
                $originalKey = $key;
                $counter = 1;
                while (in_array($key, $usedDocKeys)) {
                    $key = $originalKey . '_' . $counter;
                    $counter++;
                }
                $usedDocKeys[] = $key;

                $processedDocuments[] = [
                    'name' => $doc['name'],
                    'key' => $key,
                    'icon' => $doc['icon'] ?? 'fas fa-file',
                    'color' => $doc['color'] ?? 'gray',
                    'formats' => $doc['formats'] ?? ['pdf'],
                    'max_size' => (int) ($doc['max_size'] ?? 5),
                    'description' => $doc['description'] ?? '',
                    'required' => (bool) ($doc['required'] ?? true)
                ];
            }

            // Create the beasiswa record
            $beasiswa = Beasiswa::create([
                'nama_beasiswa' => $validated['nama_beasiswa'],
                'deskripsi' => $validated['deskripsi'],
                'jumlah_dana' => $validated['jumlah_dana'],
                'tanggal_buka' => $validated['tanggal_buka'],
                'tanggal_tutup' => $validated['tanggal_tutup'],
                'status' => $validated['status'],
                'persyaratan' => $validated['persyaratan'],
                'form_fields' => $processedFormFields,
                'required_documents' => $processedDocuments
            ]);

            \Log::info('Beasiswa created successfully', [
                'id' => $beasiswa->id,
                'form_fields_count' => count($processedFormFields),
                'documents_count' => count($processedDocuments)
            ]);

            return redirect()->route('admin.beasiswa.index')
                ->with('success', 'Beasiswa berhasil ditambahkan!');

        } catch (\Exception $e) {
            \Log::error('Error creating beasiswa: ' . $e->getMessage(), [
                'request_data' => $request->only(['nama_beasiswa', 'status']),
                'form_fields_json_length' => strlen($request->form_fields_json ?? ''),
                'required_documents_json_length' => strlen($request->required_documents_json ?? '')
            ]);

            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
    }

    

    public function show(Beasiswa $beasiswa)
    {
        return view('admin.beasiswa.show', compact('beasiswa'));
    }

    

    public function edit(Beasiswa $beasiswa)
    {
        return view('admin.beasiswa.edit', compact('beasiswa'));
    }

    public function update(Request $request, Beasiswa $beasiswa)
    {
        // First validate the basic fields and JSON strings
        $validated = $request->validate([
            'nama_beasiswa' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jumlah_dana' => 'required|numeric|min:0',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
            'status' => 'required|in:aktif,nonaktif',
            'persyaratan' => 'required|string',
            'form_fields_json' => 'required|string',
            'required_documents_json' => 'required|string',
        ]);

        try {
            // Decode and validate JSON data
            $formFields = json_decode($validated['form_fields_json'], true);
            $requiredDocuments = json_decode($validated['required_documents_json'], true);

            // Check for JSON decode errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Format data JSON tidak valid: ' . json_last_error_msg());
            }

            // Validate decoded data structure
            if (!is_array($formFields) || empty($formFields)) {
                throw new \Exception('Data form fields tidak valid atau kosong.');
            }

            if (!is_array($requiredDocuments) || empty($requiredDocuments)) {
                throw new \Exception('Data dokumen tidak valid atau kosong.');
            }

            // Validate each form field
            foreach ($formFields as $index => $field) {
                if (!is_array($field)) {
                    throw new \Exception("Form field ke-" . ($index + 1) . " bukan array yang valid.");
                }

                // Check required fields
                $requiredFieldKeys = ['name', 'key', 'type', 'icon', 'position'];
                foreach ($requiredFieldKeys as $key) {
                    if (!isset($field[$key]) || empty($field[$key])) {
                        throw new \Exception("Form field ke-" . ($index + 1) . ": {$key} tidak boleh kosong.");
                    }
                }

                // Validate field type
                $allowedTypes = ['text', 'email', 'number', 'date', 'textarea', 'select', 'radio', 'checkbox', 'tel'];
                if (!in_array($field['type'], $allowedTypes)) {
                    throw new \Exception("Form field ke-" . ($index + 1) . ": tipe '{$field['type']}' tidak valid.");
                }

                // Validate options for select/radio/checkbox
                if (in_array($field['type'], ['select', 'radio', 'checkbox'])) {
                    if (!isset($field['options']) || !is_array($field['options']) || empty($field['options'])) {
                        throw new \Exception("Form field ke-" . ($index + 1) . ": opsi pilihan harus diisi untuk tipe {$field['type']}.");
                    }
                }
            }

            // Validate each document
            foreach ($requiredDocuments as $index => $doc) {
                if (!is_array($doc)) {
                    throw new \Exception("Dokumen ke-" . ($index + 1) . " bukan array yang valid.");
                }

                // Check required fields
                $requiredDocKeys = ['name', 'key', 'icon', 'color', 'formats', 'max_size'];
                foreach ($requiredDocKeys as $key) {
                    if (
                        !isset($doc[$key]) ||
                        (is_array($doc[$key]) ? empty($doc[$key]) : empty($doc[$key]))
                    ) {
                        throw new \Exception("Dokumen ke-" . ($index + 1) . ": {$key} tidak boleh kosong.");
                    }
                }

                // Validate formats
                if (!is_array($doc['formats']) || empty($doc['formats'])) {
                    throw new \Exception("Dokumen ke-" . ($index + 1) . ": format file harus dipilih minimal 1.");
                }

                $allowedFormats = ['jpg', 'jpeg', 'png', 'pdf'];
                foreach ($doc['formats'] as $format) {
                    if (!in_array(strtolower($format), $allowedFormats)) {
                        throw new \Exception("Dokumen ke-" . ($index + 1) . ": format '{$format}' tidak diperbolehkan.");
                    }
                }

                // Validate max size
                if (!is_numeric($doc['max_size']) || $doc['max_size'] < 1 || $doc['max_size'] > 10) {
                    throw new \Exception("Dokumen ke-" . ($index + 1) . ": ukuran maksimal harus antara 1-10 MB.");
                }
            }

            // Process and clean the data
            $processedFormFields = [];
            $usedFieldKeys = [];

            foreach ($formFields as $field) {
                // Generate unique key if needed
                $key = $field['key'];
                $originalKey = $key;
                $counter = 1;
                while (in_array($key, $usedFieldKeys)) {
                    $key = $originalKey . '_' . $counter;
                    $counter++;
                }
                $usedFieldKeys[] = $key;

                // Process options
                $options = [];
                if (isset($field['options']) && is_array($field['options'])) {
                    foreach ($field['options'] as $option) {
                        if (
                            isset($option['value'], $option['label']) &&
                            !empty($option['value']) && !empty($option['label'])
                        ) {
                            $options[] = [
                                'value' => $option['value'],
                                'label' => $option['label']
                            ];
                        }
                    }
                }

                $processedFormFields[] = [
                    'name' => $field['name'],
                    'key' => $key,
                    'type' => $field['type'],
                    'icon' => $field['icon'],
                    'placeholder' => $field['placeholder'] ?? '',
                    'position' => $field['position'],
                    'validation' => $field['validation'] ?? '',
                    'required' => (bool) ($field['required'] ?? true),
                    'options' => $options
                ];
            }

            // Process documents
            $processedDocuments = [];
            $usedDocKeys = [];

            foreach ($requiredDocuments as $doc) {
                // Generate unique key if needed
                $key = $doc['key'];
                $originalKey = $key;
                $counter = 1;
                while (in_array($key, $usedDocKeys)) {
                    $key = $originalKey . '_' . $counter;
                    $counter++;
                }
                $usedDocKeys[] = $key;

                $processedDocuments[] = [
                    'name' => $doc['name'],
                    'key' => $key,
                    'icon' => $doc['icon'],
                    'color' => $doc['color'],
                    'formats' => array_map('strtolower', $doc['formats']),
                    'max_size' => (int) $doc['max_size'],
                    'description' => $doc['description'] ?? '',
                    'required' => (bool) ($doc['required'] ?? true)
                ];
            }

            // Update the beasiswa record
            $beasiswa->update([
                'nama_beasiswa' => $validated['nama_beasiswa'],
                'deskripsi' => $validated['deskripsi'],
                'jumlah_dana' => $validated['jumlah_dana'],
                'tanggal_buka' => $validated['tanggal_buka'],
                'tanggal_tutup' => $validated['tanggal_tutup'],
                'status' => $validated['status'],
                'persyaratan' => $validated['persyaratan'],
                'form_fields' => $processedFormFields,  // Model will cast to JSON
                'required_documents' => $processedDocuments,  // Model will cast to JSON
            ]);

            // Log success for debugging
            \Log::info('Beasiswa updated successfully', [
                'id' => $beasiswa->id,
                'form_fields_count' => count($processedFormFields),
                'documents_count' => count($processedDocuments)
            ]);

            return redirect()->route('admin.beasiswa.index')
                ->with('success', 'Beasiswa berhasil diperbarui!');

        } catch (\Exception $e) {
            // Log the error with full context
            \Log::error('Error updating beasiswa', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => [
                    'form_fields_json_length' => strlen($request->form_fields_json ?? ''),
                    'required_documents_json_length' => strlen($request->required_documents_json ?? ''),
                    'basic_fields' => $request->only(['nama_beasiswa', 'status'])
                ]
            ]);

            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Beasiswa $beasiswa)
    {
        try {
            $beasiswa->delete();
            return redirect()->route('admin.beasiswa.index')
                ->with('success', 'Beasiswa berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.beasiswa.index')
                ->with('error', 'Terjadi kesalahan saat menghapus beasiswa: ' . $e->getMessage());
        }
    }

    public function clean(Request $request, Beasiswa $beasiswa)
    {
        try {
            $originalFormFields = count($beasiswa->form_fields ?? []);
            $originalDocuments = count($beasiswa->required_documents ?? []);

            // Clean duplicate form fields
            $cleanFormFields = $this->removeDuplicates($beasiswa->form_fields ?? [], 'key');
            $cleanDocuments = $this->removeDuplicates($beasiswa->required_documents ?? [], 'key');

            // Update the beasiswa
            $beasiswa->update([
                'form_fields' => $cleanFormFields,
                'required_documents' => $cleanDocuments
            ]);

            $cleanedFormFields = count($cleanFormFields);
            $cleanedDocuments = count($cleanDocuments);

            $removedFormFields = $originalFormFields - $cleanedFormFields;
            $removedDocuments = $originalDocuments - $cleanedDocuments;

            $message = "Data berhasil dibersihkan! ";
            if ($removedFormFields > 0 || $removedDocuments > 0) {
                $message .= "Dihapus: {$removedFormFields} form field duplikat, {$removedDocuments} dokumen duplikat.";
            } else {
                $message .= "Tidak ada data duplikat yang ditemukan.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'original_form_fields' => $originalFormFields,
                    'cleaned_form_fields' => $cleanedFormFields,
                    'removed_form_fields' => $removedFormFields,
                    'original_documents' => $originalDocuments,
                    'cleaned_documents' => $cleanedDocuments,
                    'removed_documents' => $removedDocuments
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membersihkan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function analyze(Beasiswa $beasiswa)
    {
        try {
            $analysis = [
                'form_fields' => [],
                'documents' => [],
                'summary' => []
            ];

            // Analyze form fields
            $formFields = $beasiswa->form_fields ?? [];
            $duplicateFormFields = $this->findDuplicates($formFields, 'key');

            // Analyze documents
            $documents = $beasiswa->required_documents ?? [];
            $duplicateDocuments = $this->findDuplicates($documents, 'key');

            $analysis['form_fields'] = $duplicateFormFields;
            $analysis['documents'] = $duplicateDocuments;
            $analysis['summary'] = [
                'total_form_fields' => count($formFields),
                'duplicate_form_fields' => count($duplicateFormFields),
                'total_documents' => count($documents),
                'duplicate_documents' => count($duplicateDocuments),
                'has_duplicates' => count($duplicateFormFields) > 0 || count($duplicateDocuments) > 0
            ];

            return response()->json([
                'success' => true,
                'data' => $analysis
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menganalisis data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkClean(Request $request)
    {
        try {
            $beasiswas = Beasiswa::all();
            $totalCleaned = 0;
            $totalRemovedFields = 0;
            $totalRemovedDocs = 0;
            $errors = [];

            foreach ($beasiswas as $beasiswa) {
                try {
                    $originalFields = count($beasiswa->form_fields ?? []);
                    $originalDocs = count($beasiswa->required_documents ?? []);

                    // Clean duplicates
                    $cleanFormFields = $this->removeDuplicates($beasiswa->form_fields ?? [], 'key');
                    $cleanDocuments = $this->removeDuplicates($beasiswa->required_documents ?? [], 'key');

                    // Update if there were changes
                    if (count($cleanFormFields) !== $originalFields || count($cleanDocuments) !== $originalDocs) {
                        $beasiswa->update([
                            'form_fields' => $cleanFormFields,
                            'required_documents' => $cleanDocuments
                        ]);

                        $totalCleaned++;
                        $totalRemovedFields += ($originalFields - count($cleanFormFields));
                        $totalRemovedDocs += ($originalDocs - count($cleanDocuments));
                    }

                } catch (\Exception $e) {
                    $errors[] = "Error cleaning beasiswa ID {$beasiswa->id}: " . $e->getMessage();
                }
            }

            $message = "Bulk cleaning selesai! ";
            $message .= "{$totalCleaned} beasiswa dibersihkan. ";
            $message .= "Total dihapus: {$totalRemovedFields} form fields, {$totalRemovedDocs} dokumen.";

            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " dan " . (count($errors) - 3) . " error lainnya.";
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'total_processed' => count($beasiswas),
                    'total_cleaned' => $totalCleaned,
                    'total_removed_fields' => $totalRemovedFields,
                    'total_removed_documents' => $totalRemovedDocs,
                    'errors' => $errors
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat bulk cleaning: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper method for generating unique keys
    private function generateUniqueKey($name, $usedKeys, $prefix = '')
    {
        $baseKey = strtolower(trim($name));
        $baseKey = preg_replace('/[^a-z0-9\s]/', '', $baseKey);
        $baseKey = preg_replace('/\s+/', '_', $baseKey);
        $baseKey = substr($baseKey, 0, 30);

        if (empty($baseKey)) {
            $baseKey = $prefix ? 'dokumen' : 'field';
        }

        if ($prefix) {
            $baseKey = $prefix . '_' . $baseKey;
        }

        $key = $baseKey;
        $counter = 1;

        while (in_array($key, $usedKeys)) {
            $key = $baseKey . '_' . $counter;
            $counter++;
        }

        return $key;
    }

    // Helper method to find duplicates
    private function findDuplicates($items, $keyField)
    {
        $seen = [];
        $duplicates = [];

        foreach ($items as $index => $item) {
            if (!is_array($item) || !isset($item[$keyField])) {
                continue;
            }

            $key = $item[$keyField];
            if (in_array($key, $seen)) {
                $duplicates[] = [
                    'index' => $index,
                    'name' => $item['name'] ?? '',
                    'key' => $key,
                    'duplicate_type' => 'key'
                ];
            } else {
                $seen[] = $key;
            }
        }

        return $duplicates;
    }

    // Helper method to remove duplicates
    private function removeDuplicates($items, $keyField)
    {
        $seen = [];
        $cleaned = [];

        foreach ($items as $item) {
            if (!is_array($item) || !isset($item[$keyField])) {
                continue;
            }

            $key = $item[$keyField];
            if (!in_array($key, $seen)) {
                $seen[] = $key;
                $cleaned[] = $item;
            }
        }

        return $cleaned;
    }
}