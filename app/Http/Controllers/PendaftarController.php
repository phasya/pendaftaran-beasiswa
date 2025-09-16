<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PendaftarController extends Controller
{
    public function create(Beasiswa $beasiswa)
    {
        // Check if beasiswa is active
        if (!$beasiswa->isActive()) {
            return redirect()->route('home')->with('error', 'Beasiswa tidak tersedia atau sudah ditutup.');
        }

        return view('pendaftaran.create', compact('beasiswa'));
    }

    public function store(Request $request, Beasiswa $beasiswa)
    {
        // Check if beasiswa is still active
        if (!$beasiswa->isActive()) {
            return redirect()->route('home')->with('error', 'Beasiswa tidak tersedia atau sudah ditutup.');
        }

        // Build dynamic validation rules
        $rules = [];
        $messages = [];

        // Get form field validation rules from beasiswa configuration
        $formFieldRules = $beasiswa->getFormFieldValidationRules();
        $formFieldMessages = $beasiswa->getFormFieldValidationMessages();

        // Get document validation rules from beasiswa configuration
        $documentRules = $beasiswa->getDocumentValidationRules();
        $documentMessages = $beasiswa->getDocumentValidationMessages();

        // Merge all validation rules and messages
        $rules = array_merge($formFieldRules, $documentRules);
        $messages = array_merge($formFieldMessages, $documentMessages);

        // Additional validation for specific cases
        $rules['terms'] = 'required|accepted';
        $messages['terms.required'] = 'Anda harus menyetujui syarat dan ketentuan.';
        $messages['terms.accepted'] = 'Anda harus menyetujui syarat dan ketentuan.';

        // Validate the request
        $validated = $request->validate($rules, $messages);

        // Remove terms from validated data
        unset($validated['terms']);

        // Prepare form data
        $formData = [];
        foreach ($beasiswa->form_fields as $field) {
            if (isset($validated[$field['key']])) {
                $formData[$field['key']] = $validated[$field['key']];
            }
        }

        // Handle file uploads
        $uploadedFiles = [];
        foreach ($beasiswa->required_documents as $document) {
            if ($request->hasFile($document['key'])) {
                $file = $request->file($document['key']);

                // Generate unique filename
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . $document['key'] . '_' . uniqid() . '.' . $extension;

                // Store file in documents directory
                $path = $file->storeAs('documents/beasiswa_' . $beasiswa->id, $filename, 'public');

                $uploadedFiles[$document['key']] = [
                    'original_name' => $file->getClientOriginalName(),
                    'stored_name' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ];
            }
        }

        // Create pendaftar record
        $pendaftar = Pendaftar::create([
            'user_id' => Auth::id(),
            'beasiswa_id' => $beasiswa->id,
            'form_data' => $formData,
            'uploaded_documents' => $uploadedFiles,
            'status' => 'pending',
            'submitted_at' => now()
        ]);

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil dikirim! Status pendaftaran dapat dilihat di halaman status.');
    }

    public function show(Pendaftar $pendaftar)
    {
        // Authorization check
        if (Auth::id() !== $pendaftar->user_id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $pendaftar->load('beasiswa', 'user');
        return view('pendaftaran.show', compact('pendaftar'));
    }

    public function downloadDocument(Pendaftar $pendaftar, $documentKey)
    {
        // Authorization check
        if (Auth::id() !== $pendaftar->user_id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        // Check if document exists
        if (!isset($pendaftar->uploaded_documents[$documentKey])) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $document = $pendaftar->uploaded_documents[$documentKey];
        $filePath = $document['path'];

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di server.');
        }

        // Return file download response
        return Storage::disk('public')->download($filePath, $document['original_name']);
    }
}
