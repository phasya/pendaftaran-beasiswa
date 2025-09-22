<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beasiswa;

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
            'form_fields' => 'required|array|min:1',
            'form_fields.*.name' => 'required|string|max:255',
            'form_fields.*.key' => 'nullable|string|max:255',
            'form_fields.*.type' => 'required|string',
            'form_fields.*.icon' => 'required|string',
            'form_fields.*.position' => 'required|string',
            'form_fields.*.validation' => 'nullable|string',
            'form_fields.*.placeholder' => 'nullable|string',
            'form_fields.*.required' => 'required|boolean',
            'documents' => 'required|array|min:1',
            'documents.*.name' => 'required|string|max:255',
            'documents.*.key' => 'nullable|string|max:255',
            'documents.*.icon' => 'required|string',
            'documents.*.color' => 'required|string',
            'documents.*.formats' => 'required|array|min:1',
            'documents.*.max_size' => 'required|numeric|min:1|max:10',
            'documents.*.description' => 'nullable|string',
            'documents.*.required' => 'required|boolean'
        ]);

        try {
            // Process form fields with auto key generation
            $processedFormFields = [];
            $usedFieldKeys = [];

            foreach ($validated['form_fields'] as $field) {
                if (empty($field['name'])) {
                    continue;
                }

                // Generate unique key if not provided
                if (empty($field['key'])) {
                    $field['key'] = Beasiswa::generateUniqueKey($field['name'], $usedFieldKeys);
                }

                $usedFieldKeys[] = $field['key'];

                $processedFormFields[] = [
                    'name' => $field['name'],
                    'key' => $field['key'],
                    'type' => $field['type'] ?? 'text',
                    'icon' => $field['icon'] ?? 'fas fa-user',
                    'placeholder' => $field['placeholder'] ?? '',
                    'position' => $field['position'] ?? 'personal',
                    'validation' => $field['validation'] ?? '',
                    'required' => (bool) ($field['required'] ?? true),
                    'options' => $field['options'] ?? []
                ];
            }

            // Process documents with auto key generation
            $processedDocuments = [];
            $usedDocKeys = [];

            foreach ($validated['documents'] as $doc) {
                if (empty($doc['name'])) {
                    continue;
                }

                // Generate unique key if not provided
                if (empty($doc['key'])) {
                    $doc['key'] = Beasiswa::generateUniqueKey($doc['name'], $usedDocKeys, 'file');
                }

                $usedDocKeys[] = $doc['key'];

                $processedDocuments[] = [
                    'name' => $doc['name'],
                    'key' => $doc['key'],
                    'icon' => $doc['icon'] ?? 'fas fa-file',
                    'color' => $doc['color'] ?? 'gray',
                    'formats' => $doc['formats'] ?? [],
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
                'form_fields' => json_encode($processedFormFields),
                'required_documents' => json_encode($processedDocuments)
            ]);

            return redirect()->route('admin.beasiswa.index')
                ->with('success', 'Beasiswa berhasil ditambahkan!');

        } catch (\Exception $e) {
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
        // Debug log untuk melihat data yang masuk
        \Log::info('Update Request Data:', $request->all());

        // Validation rules yang disesuaikan dengan JavaScript
        $validated = $request->validate([
            'nama_beasiswa' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jumlah_dana' => 'required|numeric|min:0',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
            'status' => 'required|in:aktif,nonaktif',
            'persyaratan' => 'required|string',

            // Form fields validation - disesuaikan dengan data dari JavaScript
            'form_fields' => 'required|array|min:1',
            'form_fields.*.name' => 'required|string|max:255',
            'form_fields.*.key' => 'nullable|string|max:255',
            'form_fields.*.type' => 'required|string',
            'form_fields.*.icon' => 'required|string',
            'form_fields.*.placeholder' => 'nullable|string',
            'form_fields.*.position' => 'required|string',
            'form_fields.*.validation' => 'nullable|string',
            'form_fields.*.required' => 'required|in:0,1', // Accept string 0/1
            'form_fields.*.options' => 'nullable|array',
            'form_fields.*.options.*.value' => 'nullable|string',
            'form_fields.*.options.*.label' => 'nullable|string',

            // Documents validation - disesuaikan dengan data dari JavaScript
            'documents' => 'required|array|min:1',
            'documents.*.name' => 'required|string|max:255',
            'documents.*.key' => 'nullable|string|max:255',
            'documents.*.icon' => 'required|string',
            'documents.*.color' => 'required|string',
            'documents.*.formats' => 'required|array|min:1',
            'documents.*.max_size' => 'required|numeric|min:1|max:10',
            'documents.*.description' => 'nullable|string',
            'documents.*.required' => 'required|in:0,1' // Accept string 0/1
        ]);

        try {
            // Process form fields with better error handling
            $processedFormFields = [];
            $usedFieldKeys = [];

            if (!empty($validated['form_fields'])) {
                foreach ($validated['form_fields'] as $index => $field) {
                    // Skip empty fields
                    if (empty($field['name']) || empty($field['type'])) {
                        \Log::warning("Skipping form field at index {$index}: missing name or type", $field);
                        continue;
                    }

                    // Generate unique key if not provided
                    if (empty($field['key'])) {
                        $field['key'] = Beasiswa::generateUniqueKey($field['name'], $usedFieldKeys);
                    }

                    // Ensure key uniqueness
                    $originalKey = $field['key'];
                    $counter = 1;
                    while (in_array($field['key'], $usedFieldKeys)) {
                        $field['key'] = $originalKey . '_' . $counter;
                        $counter++;
                    }
                    $usedFieldKeys[] = $field['key'];

                    // Process options if they exist
                    $processedOptions = [];
                    if (!empty($field['options']) && is_array($field['options'])) {
                        foreach ($field['options'] as $option) {
                            if (!empty($option['value']) && !empty($option['label'])) {
                                $processedOptions[] = [
                                    'value' => $option['value'],
                                    'label' => $option['label']
                                ];
                            }
                        }
                    }

                    $processedFormFields[] = [
                        'name' => $field['name'],
                        'key' => $field['key'],
                        'type' => $field['type'],
                        'icon' => $field['icon'] ?? 'fas fa-user',
                        'placeholder' => $field['placeholder'] ?? '',
                        'position' => $field['position'] ?? 'personal',
                        'validation' => $field['validation'] ?? '',
                        'required' => ($field['required'] === '1' || $field['required'] === 1 || $field['required'] === true),
                        'options' => $processedOptions
                    ];
                }
            }

            // Process documents with better error handling
            $processedDocuments = [];
            $usedDocKeys = [];

            if (!empty($validated['documents'])) {
                foreach ($validated['documents'] as $index => $doc) {
                    // Skip empty documents
                    if (empty($doc['name']) || empty($doc['formats'])) {
                        \Log::warning("Skipping document at index {$index}: missing name or formats", $doc);
                        continue;
                    }

                    // Generate unique key if not provided
                    if (empty($doc['key'])) {
                        $doc['key'] = Beasiswa::generateUniqueKey($doc['name'], $usedDocKeys, 'file');
                    }

                    // Ensure key uniqueness
                    $originalKey = $doc['key'];
                    $counter = 1;
                    while (in_array($doc['key'], $usedDocKeys)) {
                        $doc['key'] = $originalKey . '_' . $counter;
                        $counter++;
                    }
                    $usedDocKeys[] = $doc['key'];

                    // Process formats array
                    $processedFormats = [];
                    if (is_array($doc['formats'])) {
                        $processedFormats = array_filter($doc['formats'], function ($format) {
                            return in_array(strtolower($format), ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx']);
                        });
                    }

                    if (empty($processedFormats)) {
                        $processedFormats = ['pdf']; // Default format
                    }

                    $processedDocuments[] = [
                        'name' => $doc['name'],
                        'key' => $doc['key'],
                        'icon' => $doc['icon'] ?? 'fas fa-file',
                        'color' => $doc['color'] ?? 'gray',
                        'formats' => array_values($processedFormats),
                        'max_size' => max(1, min(10, (int) ($doc['max_size'] ?? 5))),
                        'description' => $doc['description'] ?? '',
                        'required' => ($doc['required'] === '1' || $doc['required'] === 1 || $doc['required'] === true)
                    ];
                }
            }

            // Validate that we have processed data
            if (empty($processedFormFields)) {
                throw new \Exception('No valid form fields were processed');
            }

            if (empty($processedDocuments)) {
                throw new \Exception('No valid documents were processed');
            }

            // Log processed data for debugging
            \Log::info('Processed Form Fields:', $processedFormFields);
            \Log::info('Processed Documents:', $processedDocuments);

            // Update the beasiswa record
            $updateData = [
                'nama_beasiswa' => $validated['nama_beasiswa'],
                'deskripsi' => $validated['deskripsi'],
                'jumlah_dana' => $validated['jumlah_dana'],
                'tanggal_buka' => $validated['tanggal_buka'],
                'tanggal_tutup' => $validated['tanggal_tutup'],
                'status' => $validated['status'],
                'persyaratan' => $validated['persyaratan'],
                'form_fields' => $processedFormFields, // Let model handle JSON encoding
                'required_documents' => $processedDocuments // Let model handle JSON encoding
            ];

            $beasiswa->update($updateData);

            \Log::info('Beasiswa updated successfully', ['id' => $beasiswa->id]);

            // Handle AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Beasiswa berhasil diperbarui!',
                    'redirect' => route('admin.beasiswa.index')
                ]);
            }

            return redirect()->route('admin.beasiswa.index')
                ->with('success', 'Beasiswa berhasil diperbarui!');

        } catch (\Exception $e) {
            \Log::error('Error updating beasiswa:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Handle AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
                ], 422);
            }

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

    // Rest of methods remain the same...
    public function clean(Request $request, Beasiswa $beasiswa)
    {
        try {
            $originalFormFields = count($beasiswa->getRawRequiredDocumentsAttribute() ?? []);
            $originalDocuments = count($beasiswa->getRawRequiredDocumentsAttribute() ?? []);

            $beasiswa->cleanDuplicateData();

            $cleanedFormFields = count($beasiswa->form_fields);
            $cleanedDocuments = count($beasiswa->required_documents);

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

            $formFields = $beasiswa->getRawRequiredDocumentsAttribute() ?? [];
            $fieldNames = [];
            $fieldKeys = [];
            $duplicateFormFields = [];

            foreach ($formFields as $index => $field) {
                if (!is_array($field))
                    continue;

                $name = strtolower(trim($field['name'] ?? ''));
                $key = trim($field['key'] ?? '');

                if (in_array($name, $fieldNames) || in_array($key, $fieldKeys)) {
                    $duplicateFormFields[] = [
                        'index' => $index,
                        'name' => $field['name'] ?? '',
                        'key' => $field['key'] ?? '',
                        'duplicate_type' => in_array($name, $fieldNames) ? 'name' : 'key'
                    ];
                }

                $fieldNames[] = $name;
                $fieldKeys[] = $key;
            }

            $documents = $beasiswa->getRawRequiredDocumentsAttribute() ?? [];
            $docNames = [];
            $docKeys = [];
            $duplicateDocuments = [];

            foreach ($documents as $index => $doc) {
                if (!is_array($doc))
                    continue;

                $name = strtolower(trim($doc['name'] ?? ''));
                $key = trim($doc['key'] ?? '');

                if (in_array($name, $docNames) || in_array($key, $docKeys)) {
                    $duplicateDocuments[] = [
                        'index' => $index,
                        'name' => $doc['name'] ?? '',
                        'key' => $doc['key'] ?? '',
                        'duplicate_type' => in_array($name, $docNames) ? 'name' : 'key'
                    ];
                }

                $docNames[] = $name;
                $docKeys[] = $key;
            }

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
                    $originalFields = count($beasiswa->getRawRequiredDocumentsAttribute() ?? []);
                    $originalDocs = count($beasiswa->getRawRequiredDocumentsAttribute() ?? []);

                    $beasiswa->cleanDuplicateData();

                    $cleanedFields = count($beasiswa->form_fields);
                    $cleanedDocs = count($beasiswa->required_documents);

                    $removedFields = $originalFields - $cleanedFields;
                    $removedDocs = $originalDocs - $cleanedDocs;

                    if ($removedFields > 0 || $removedDocs > 0) {
                        $totalCleaned++;
                        $totalRemovedFields += $removedFields;
                        $totalRemovedDocs += $removedDocs;
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
}