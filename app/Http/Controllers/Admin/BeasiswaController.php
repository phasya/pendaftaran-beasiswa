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
            'form_fields.*.required' => 'required|boolean',
            'documents' => 'required|array|min:1',
            'documents.*.name' => 'required|string|max:255',
            'documents.*.key' => 'nullable|string|max:255',
            'documents.*.icon' => 'required|string',
            'documents.*.color' => 'required|string',
            'documents.*.formats' => 'required|array|min:1',
            'documents.*.max_size' => 'required|numeric|min:1|max:10',
            'documents.*.required' => 'required|boolean'
        ]);

        try {
            // Process form fields
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

            // Process documents
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

            // Update the beasiswa record
            $beasiswa->update([
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
                ->with('success', 'Beasiswa berhasil diperbarui!');

        } catch (\Exception $e) {
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

    /**
     * NEW: Clean duplicate data in beasiswa
     */
    public function clean(Request $request, Beasiswa $beasiswa)
    {
        try {
            // Get original data for comparison
            $originalFormFields = count($beasiswa->getRawFormFieldsAttribute());
            $originalDocuments = count($beasiswa->getRawRequiredDocumentsAttribute());

            // Clean duplicate data using model method
            $beasiswa->cleanDuplicateData();

            // Get cleaned data count
            $cleanedFormFields = count($beasiswa->form_fields);
            $cleanedDocuments = count($beasiswa->required_documents);

            // Calculate removed items
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

    /**
     * NEW: Get duplicate data analysis for debugging
     */
    public function analyze(Beasiswa $beasiswa)
    {
        try {
            $analysis = [
                'form_fields' => [],
                'documents' => [],
                'summary' => []
            ];

            // Analyze form fields
            $formFields = $beasiswa->getRawFormFieldsAttribute();
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

            // Analyze documents
            $documents = $beasiswa->getRawRequiredDocumentsAttribute();
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

    /**
     * NEW: Bulk clean all beasiswa data
     */
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
                    $originalFields = count($beasiswa->getRawFormFieldsAttribute());
                    $originalDocs = count($beasiswa->getRawRequiredDocumentsAttribute());

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