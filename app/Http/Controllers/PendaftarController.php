<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beasiswa;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Exception;

class PendaftarController extends Controller
{
    /**
     * Show resubmit form for rejected application
     */
    public function create(Beasiswa $beasiswa)
    {
        if (!$beasiswa->isActive()) {
            return redirect()->route('home')
                ->with('error', 'Pendaftaran beasiswa sudah ditutup atau tidak aktif.');
        }

        // Cek berdasarkan email user yang login - hanya cek yang statusnya pending atau diterima
        $existingApplication = Pendaftar::where('email', Auth::user()->email)
            ->whereIn('status', ['pending', 'diterima'])
            ->first();

        if ($existingApplication) {
            $beasiswaTerdaftar = Beasiswa::find($existingApplication->beasiswa_id);
            $statusText = $existingApplication->status == 'pending' ? 'sedang menunggu verifikasi' : 'telah diterima';

            return redirect()->route('home')
                ->with('error', 'Anda sudah terdaftar di beasiswa "' . $beasiswaTerdaftar->nama_beasiswa . '" dan ' . $statusText . '.');
        }

        return view('pendaftaran.create', compact('beasiswa'));
    }

    public function store(Request $request, Beasiswa $beasiswa)
    {
        try {
            Log::info('Starting dynamic form submission', [
                'beasiswa_id' => $beasiswa->id,
                'user_email' => Auth::user()->email,
                'form_fields_available' => is_array($beasiswa->form_fields) ? count($beasiswa->form_fields) : 0,
                'documents_required' => is_array($beasiswa->required_documents) ? count($beasiswa->required_documents) : 0
            ]);

            if (!$beasiswa->isActive()) {
                return redirect()->route('home')
                    ->with('error', 'Pendaftaran beasiswa sudah ditutup atau tidak aktif.');
            }

            // Cek apakah user sudah memiliki aplikasi aktif
            $existingApplicationByEmail = Pendaftar::where('email', Auth::user()->email)
                ->whereIn('status', ['pending', 'diterima'])
                ->first();

            if ($existingApplicationByEmail) {
                return redirect()->route('home')
                    ->with('error', 'Anda masih memiliki beasiswa yang aktif.');
            }

            // Build validation rules
            $rules = [];
            $messages = [];

            // Terms agreement validation
            if ($request->has('terms_agreement')) {
                $rules['terms_agreement'] = 'required|accepted';
                $messages['terms_agreement.required'] = 'Anda harus menyetujui syarat dan ketentuan.';
                $messages['terms_agreement.accepted'] = 'Anda harus menyetujui syarat dan ketentuan.';
            }

            // Dynamic form field validation
            if ($beasiswa->form_fields && is_array($beasiswa->form_fields)) {
                foreach ($beasiswa->form_fields as $index => $field) {
                    if (!is_array($field)) {
                        Log::warning('Invalid field format at index ' . $index, ['field' => $field]);
                        continue;
                    }

                    $fieldKey = $field['key'] ?? '';
                    $fieldName = $field['name'] ?? $fieldKey;
                    $fieldType = $field['type'] ?? 'text';
                    $isRequired = $field['required'] ?? false;

                    if (empty($fieldKey)) {
                        Log::warning('Empty field key at index ' . $index);
                        continue;
                    }

                    $fieldRules = [];

                    // Required validation
                    if ($isRequired) {
                        if ($fieldType === 'checkbox') {
                            $fieldRules[] = 'required';
                            $fieldRules[] = 'array';
                            $fieldRules[] = 'min:1';
                            $messages[$fieldKey . '.min'] = $fieldName . ' minimal pilih satu.';
                        } else {
                            $fieldRules[] = 'required';
                        }
                        $messages[$fieldKey . '.required'] = $fieldName . ' wajib diisi.';
                    } else {
                        $fieldRules[] = 'nullable';
                    }

                    // Type-specific validation
                    switch ($fieldType) {
                        case 'email':
                            $fieldRules[] = 'email';
                            $fieldRules[] = 'max:255';
                            $messages[$fieldKey . '.email'] = 'Format email tidak valid.';
                            break;

                        case 'number':
                            $fieldRules[] = 'numeric';
                            if ($fieldKey === 'ipk') {
                                $fieldRules[] = 'between:0,4';
                                $messages[$fieldKey . '.between'] = 'IPK harus antara 0 dan 4.';
                            } elseif ($fieldKey === 'semester') {
                                $fieldRules[] = 'integer';
                                $fieldRules[] = 'between:1,14';
                                $messages[$fieldKey . '.between'] = 'Semester harus antara 1 dan 14.';
                            } elseif ($fieldKey === 'angkatan') {
                                $fieldRules[] = 'integer';
                                $fieldRules[] = 'between:1980,2030';
                                $messages[$fieldKey . '.between'] = 'Tahun angkatan tidak valid.';
                            }
                            break;

                        case 'text':
                            $fieldRules[] = 'string';
                            $fieldRules[] = 'max:255';

                            // Special validation untuk NIM
                            if ($fieldKey === 'nim') {
                                if ($request->filled($fieldKey)) {
                                    // Cek apakah NIM sudah dipakai di aplikasi yang masih aktif
                                    if (Pendaftar::isNimTaken($request->input($fieldKey), Auth::user()->email)) {
                                        $messages[$fieldKey . '.unique'] = 'NIM ini sudah digunakan pendaftar lain yang masih aktif.';
                                        $fieldRules[] = Rule::unique('non_existent_table', 'field'); // Force validation to fail
                                    }
                                }
                            }

                            // Special validation untuk no HP
                            if ($fieldKey === 'no_hp') {
                                $fieldRules[] = 'regex:/^[0-9]{10,13}$/';
                                $messages[$fieldKey . '.regex'] = 'Format nomor HP tidak valid (10-13 digit angka).';
                            }
                            break;

                        case 'textarea':
                            $fieldRules[] = 'string';
                            $fieldRules[] = 'max:5000';
                            break;

                        case 'select':
                        case 'radio':
                            $fieldRules[] = 'string';
                            $fieldRules[] = 'max:255';
                            break;

                        case 'checkbox':
                            if (!in_array('array', $fieldRules)) {
                                $fieldRules[] = 'array';
                            }
                            break;

                        case 'date':
                            $fieldRules[] = 'date';
                            break;

                        default:
                            $fieldRules[] = 'string';
                            $fieldRules[] = 'max:255';
                    }

                    if (!empty($fieldRules)) {
                        $rules[$fieldKey] = $fieldRules;
                    }

                    Log::info('Added validation rule for dynamic field', [
                        'field' => $fieldKey,
                        'rules' => $fieldRules,
                        'type' => $fieldType,
                        'required' => $isRequired
                    ]);
                }
            }

            // Document validation
            if ($beasiswa->required_documents && is_array($beasiswa->required_documents)) {
                foreach ($beasiswa->required_documents as $document) {
                    if (!is_array($document)) {
                        continue;
                    }

                    $docKey = $document['key'] ?? '';
                    $docName = $document['name'] ?? 'Dokumen';
                    $isRequired = $document['required'] ?? true;

                    if (empty($docKey)) {
                        continue;
                    }

                    $docRules = [];

                    if ($isRequired) {
                        $docRules[] = 'required';
                        $messages[$docKey . '.required'] = 'Dokumen ' . $docName . ' wajib diupload.';
                    } else {
                        $docRules[] = 'nullable';
                    }

                    $docRules[] = 'file';

                    // File size validation
                    $maxSizeKB = ($document['max_size'] ?? 5) * 1024;
                    $docRules[] = 'max:' . $maxSizeKB;
                    $messages[$docKey . '.max'] = 'Ukuran file ' . $docName . ' maksimal ' . ($document['max_size'] ?? 5) . 'MB.';

                    // File type validation
                    if (!empty($document['formats']) && is_array($document['formats'])) {
                        $allowedMimes = [];
                        foreach ($document['formats'] as $format) {
                            $format = strtolower(trim($format));
                            switch ($format) {
                                case 'pdf':
                                    $allowedMimes[] = 'pdf';
                                    break;
                                case 'jpg':
                                case 'jpeg':
                                    $allowedMimes[] = 'jpg';
                                    $allowedMimes[] = 'jpeg';
                                    break;
                                case 'png':
                                    $allowedMimes[] = 'png';
                                    break;
                                case 'doc':
                                    $allowedMimes[] = 'doc';
                                    break;
                                case 'docx':
                                    $allowedMimes[] = 'docx';
                                    break;
                            }
                        }
                        if (!empty($allowedMimes)) {
                            $docRules[] = 'mimes:' . implode(',', array_unique($allowedMimes));
                            $messages[$docKey . '.mimes'] = 'Format file ' . $docName . ' harus: ' . implode(', ', $document['formats']);
                        }
                    }

                    $rules[$docKey] = $docRules;
                }
            }

            Log::info('Final validation rules for dynamic form', [
                'rules_count' => count($rules),
                'rules' => array_keys($rules)
            ]);

            // Perform validation
            $validated = $request->validate($rules, $messages);

            Log::info('Validation passed for dynamic form', [
                'validated_fields' => array_keys($validated)
            ]);

            // Remove terms_agreement from data to be stored
            unset($validated['terms_agreement']);

            // Separate form data from file uploads
            $formData = [];
            $fileKeys = [];

            // Collect file upload keys
            if ($beasiswa->required_documents && is_array($beasiswa->required_documents)) {
                foreach ($beasiswa->required_documents as $document) {
                    $docKey = $document['key'] ?? '';
                    if (!empty($docKey)) {
                        $fileKeys[] = $docKey;
                    }
                }
            }

            // Prepare form data (exclude file uploads)
            if ($beasiswa->form_fields && is_array($beasiswa->form_fields)) {
                foreach ($beasiswa->form_fields as $field) {
                    $fieldKey = $field['key'] ?? '';
                    if (!empty($fieldKey) && isset($validated[$fieldKey]) && !in_array($fieldKey, $fileKeys)) {
                        $formData[$fieldKey] = $validated[$fieldKey];
                    }
                }
            }

            Log::info('Form data prepared', [
                'form_data_keys' => array_keys($formData),
                'file_keys' => $fileKeys
            ]);

            // Handle file uploads
            $uploadedDocuments = [];
            if ($beasiswa->required_documents && is_array($beasiswa->required_documents)) {
                foreach ($beasiswa->required_documents as $document) {
                    $docKey = $document['key'] ?? '';

                    if (empty($docKey)) {
                        continue;
                    }

                    if ($request->hasFile($docKey)) {
                        $file = $request->file($docKey);

                        if ($file->isValid()) {
                            try {
                                $extension = $file->getClientOriginalExtension();
                                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                                $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
                                $fileName = time() . '_' . $docKey . '_' . $safeName . '.' . $extension;

                                // Ensure directory exists
                                if (!Storage::disk('public')->exists('documents')) {
                                    Storage::disk('public')->makeDirectory('documents');
                                }

                                $path = $file->storeAs('documents', $fileName, 'public');
                                $uploadedDocuments[$docKey] = $fileName;

                                Log::info('File uploaded successfully for dynamic form', [
                                    'key' => $docKey,
                                    'original_name' => $file->getClientOriginalName(),
                                    'stored_name' => $fileName,
                                    'path' => $path,
                                    'size' => $file->getSize()
                                ]);
                            } catch (Exception $e) {
                                Log::error('File upload failed for dynamic form', [
                                    'key' => $docKey,
                                    'error' => $e->getMessage(),
                                    'trace' => $e->getTraceAsString()
                                ]);

                                return redirect()->back()
                                    ->withInput()
                                    ->with('error', 'Gagal mengupload file ' . ($document['name'] ?? $docKey) . '. Error: ' . $e->getMessage());
                            }
                        } else {
                            Log::error('Invalid file upload for dynamic form', [
                                'key' => $docKey,
                                'error' => $file->getErrorMessage()
                            ]);

                            return redirect()->back()
                                ->withInput()
                                ->with('error', 'File ' . ($document['name'] ?? $docKey) . ' tidak valid: ' . $file->getErrorMessage());
                        }
                    }
                }
            }

            // Create pendaftar record with JSON data
            $pendaftarData = [
                'beasiswa_id' => $beasiswa->id,
                'email' => Auth::user()->email,
                'form_data' => $formData, // Store all form data as JSON
                'uploaded_documents' => $uploadedDocuments,
                'status' => 'pending',
            ];

            Log::info('Creating pendaftar with dynamic form data', [
                'beasiswa_id' => $beasiswa->id,
                'email' => Auth::user()->email,
                'form_data_keys' => array_keys($formData),
                'documents_count' => count($uploadedDocuments)
            ]);

            // Create the application
            $pendaftar = Pendaftar::create($pendaftarData);

            Log::info('Pendaftar created successfully with dynamic form', [
                'id' => $pendaftar->id,
                'beasiswa_id' => $pendaftar->beasiswa_id,
                'email' => $pendaftar->email,
                'form_data_stored' => !empty($pendaftar->form_data),
                'documents_stored' => !empty($pendaftar->uploaded_documents)
            ]);

            return redirect()->route('home')
                ->with('success', 'Pendaftaran beasiswa berhasil! Data Anda sedang dalam proses verifikasi.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for dynamic form', [
                'errors' => $e->errors(),
                'validator_messages' => $e->validator->messages()->toArray(),
                'failed_rules' => $e->validator->failed(),
                'input_data' => $request->except(['_token'])
            ]);

            $firstError = collect($e->errors())->flatten()->first();

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validasi gagal: ' . $firstError);

        } catch (Exception $e) {
            Log::error('Unexpected error during dynamic form submission', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token'])
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage() . '. Silakan coba lagi atau hubungi administrator.');
        }
    }


    public function resubmit(Pendaftar $pendaftar)
    {
        // Pastikan user hanya bisa resubmit aplikasi mereka sendiri
        if ($pendaftar->email !== Auth::user()->email) {
            abort(403, 'Akses ditolak: Anda hanya dapat mengedit aplikasi Anda sendiri.');
        }

        // Pastikan aplikasi bisa di-resubmit
        if ($pendaftar->status !== 'ditolak' || !$pendaftar->can_resubmit) {
            return redirect()->route('status')
                ->with('error', 'Aplikasi ini tidak dapat diajukan ulang.');
        }

        // Load beasiswa relationship
        $pendaftar->load('beasiswa');

        Log::info('Resubmit form accessed', [
            'pendaftar_id' => $pendaftar->id,
            'beasiswa_id' => $pendaftar->beasiswa_id,
            'user_email' => Auth::user()->email,
            'form_data_available' => !empty($pendaftar->form_data),
            'beasiswa_form_fields_available' => !empty($pendaftar->beasiswa->form_fields)
        ]);

        return view('pendaftaran.resubmit', compact('pendaftar'));
    }

    /**
     * Process resubmit form submission
     */
    public function resubmitStore(Request $request, Pendaftar $pendaftar)
    {
        try {
            Log::info('Starting resubmit form submission', [
                'pendaftar_id' => $pendaftar->id,
                'beasiswa_id' => $pendaftar->beasiswa_id,
                'user_email' => Auth::user()->email
            ]);

            // Pastikan user hanya bisa resubmit aplikasi mereka sendiri
            if ($pendaftar->email !== Auth::user()->email) {
                abort(403, 'Akses ditolak: Anda hanya dapat mengedit aplikasi Anda sendiri.');
            }

            // Pastikan aplikasi bisa di-resubmit
            if ($pendaftar->status !== 'ditolak' || !$pendaftar->can_resubmit) {
                return redirect()->route('status')
                    ->with('error', 'Aplikasi ini tidak dapat diajukan ulang.');
            }

            $beasiswa = $pendaftar->beasiswa;

            if (!$beasiswa) {
                return redirect()->route('status')
                    ->with('error', 'Beasiswa tidak ditemukan.');
            }

            // Build validation rules (sama seperti store method)
            $rules = [];
            $messages = [];

            // Dynamic form field validation
            if ($beasiswa->form_fields && is_array($beasiswa->form_fields)) {
                foreach ($beasiswa->form_fields as $index => $field) {
                    if (!is_array($field)) {
                        Log::warning('Invalid field format at index ' . $index, ['field' => $field]);
                        continue;
                    }

                    $fieldKey = $field['key'] ?? '';
                    $fieldName = $field['name'] ?? $fieldKey;
                    $fieldType = $field['type'] ?? 'text';
                    $isRequired = $field['required'] ?? false;

                    if (empty($fieldKey)) {
                        Log::warning('Empty field key at index ' . $index);
                        continue;
                    }

                    $fieldRules = [];

                    // Required validation
                    if ($isRequired) {
                        if ($fieldType === 'checkbox') {
                            $fieldRules[] = 'required';
                            $fieldRules[] = 'array';
                            $fieldRules[] = 'min:1';
                            $messages[$fieldKey . '.min'] = $fieldName . ' minimal pilih satu.';
                        } else {
                            $fieldRules[] = 'required';
                        }
                        $messages[$fieldKey . '.required'] = $fieldName . ' wajib diisi.';
                    } else {
                        $fieldRules[] = 'nullable';
                    }

                    // Type-specific validation (sama seperti store method)
                    switch ($fieldType) {
                        case 'email':
                            $fieldRules[] = 'email';
                            $fieldRules[] = 'max:255';
                            $messages[$fieldKey . '.email'] = 'Format email tidak valid.';
                            break;

                        case 'number':
                            $fieldRules[] = 'numeric';
                            if ($fieldKey === 'ipk') {
                                $fieldRules[] = 'between:0,4';
                                $messages[$fieldKey . '.between'] = 'IPK harus antara 0 dan 4.';
                            } elseif ($fieldKey === 'semester') {
                                $fieldRules[] = 'integer';
                                $fieldRules[] = 'between:1,14';
                                $messages[$fieldKey . '.between'] = 'Semester harus antara 1 dan 14.';
                            } elseif ($fieldKey === 'angkatan') {
                                $fieldRules[] = 'integer';
                                $fieldRules[] = 'between:1980,2030';
                                $messages[$fieldKey . '.between'] = 'Tahun angkatan tidak valid.';
                            }
                            break;

                        case 'text':
                            $fieldRules[] = 'string';
                            $fieldRules[] = 'max:255';

                            // Special validation untuk NIM (skip jika sama dengan yang sudah ada)
                            if ($fieldKey === 'nim') {
                                if ($request->filled($fieldKey)) {
                                    $currentNim = $pendaftar->form_data['nim'] ?? '';
                                    $newNim = $request->input($fieldKey);

                                    // Hanya cek jika NIM berubah
                                    if ($newNim !== $currentNim) {
                                        if (Pendaftar::isNimTaken($newNim, Auth::user()->email)) {
                                            $messages[$fieldKey . '.unique'] = 'NIM ini sudah digunakan pendaftar lain yang masih aktif.';
                                            $fieldRules[] = Rule::unique('non_existent_table', 'field'); // Force validation to fail
                                        }
                                    }
                                }
                            }

                            // Special validation untuk no HP
                            if ($fieldKey === 'no_hp') {
                                $fieldRules[] = 'regex:/^[0-9]{10,13}$/';
                                $messages[$fieldKey . '.regex'] = 'Format nomor HP tidak valid (10-13 digit angka).';
                            }
                            break;

                        case 'textarea':
                            $fieldRules[] = 'string';
                            $fieldRules[] = 'max:5000';
                            break;

                        case 'select':
                        case 'radio':
                            $fieldRules[] = 'string';
                            $fieldRules[] = 'max:255';
                            break;

                        case 'checkbox':
                            if (!in_array('array', $fieldRules)) {
                                $fieldRules[] = 'array';
                            }
                            break;

                        case 'date':
                            $fieldRules[] = 'date';
                            break;

                        default:
                            $fieldRules[] = 'string';
                            $fieldRules[] = 'max:255';
                    }

                    if (!empty($fieldRules)) {
                        $rules[$fieldKey] = $fieldRules;
                    }
                }
            }

            // Document validation (optional untuk resubmit)
            if ($beasiswa->required_documents && is_array($beasiswa->required_documents)) {
                foreach ($beasiswa->required_documents as $document) {
                    if (!is_array($document)) {
                        continue;
                    }

                    $docKey = $document['key'] ?? '';
                    $docName = $document['name'] ?? 'Dokumen';

                    if (empty($docKey)) {
                        continue;
                    }

                    // Untuk resubmit, file upload optional (hanya jika ada file baru)
                    if ($request->hasFile($docKey)) {
                        $docRules = ['file'];

                        // File size validation
                        $maxSizeKB = ($document['max_size'] ?? 5) * 1024;
                        $docRules[] = 'max:' . $maxSizeKB;
                        $messages[$docKey . '.max'] = 'Ukuran file ' . $docName . ' maksimal ' . ($document['max_size'] ?? 5) . 'MB.';

                        // File type validation
                        if (!empty($document['formats']) && is_array($document['formats'])) {
                            $allowedMimes = [];
                            foreach ($document['formats'] as $format) {
                                $format = strtolower(trim($format));
                                switch ($format) {
                                    case 'pdf':
                                        $allowedMimes[] = 'pdf';
                                        break;
                                    case 'jpg':
                                    case 'jpeg':
                                        $allowedMimes[] = 'jpg';
                                        $allowedMimes[] = 'jpeg';
                                        break;
                                    case 'png':
                                        $allowedMimes[] = 'png';
                                        break;
                                    case 'doc':
                                        $allowedMimes[] = 'doc';
                                        break;
                                    case 'docx':
                                        $allowedMimes[] = 'docx';
                                        break;
                                }
                            }
                            if (!empty($allowedMimes)) {
                                $docRules[] = 'mimes:' . implode(',', array_unique($allowedMimes));
                                $messages[$docKey . '.mimes'] = 'Format file ' . $docName . ' harus: ' . implode(', ', $document['formats']);
                            }
                        }

                        $rules[$docKey] = $docRules;
                    }
                }
            }

            Log::info('Validation rules for resubmit', [
                'rules_count' => count($rules),
                'rules' => array_keys($rules)
            ]);

            // Perform validation
            $validated = $request->validate($rules, $messages);

            Log::info('Resubmit validation passed', [
                'validated_fields' => array_keys($validated)
            ]);

            // Prepare updated form data
            $currentFormData = $pendaftar->form_data ?? [];
            $updatedFormData = $currentFormData;

            // Update form data dengan input baru
            if ($beasiswa->form_fields && is_array($beasiswa->form_fields)) {
                foreach ($beasiswa->form_fields as $field) {
                    $fieldKey = $field['key'] ?? '';
                    if (!empty($fieldKey) && array_key_exists($fieldKey, $validated)) {
                        $updatedFormData[$fieldKey] = $validated[$fieldKey];
                    }
                }
            }

            // Handle file uploads (update dokumen jika ada yang baru)
            $currentDocuments = $pendaftar->uploaded_documents ?? [];
            $updatedDocuments = $currentDocuments;

            if ($beasiswa->required_documents && is_array($beasiswa->required_documents)) {
                foreach ($beasiswa->required_documents as $document) {
                    $docKey = $document['key'] ?? '';

                    if (empty($docKey) || !$request->hasFile($docKey)) {
                        continue;
                    }

                    $file = $request->file($docKey);

                    if ($file->isValid()) {
                        try {
                            // Hapus file lama jika ada
                            if (isset($currentDocuments[$docKey])) {
                                $oldFilePath = 'documents/' . $currentDocuments[$docKey];
                                if (Storage::disk('public')->exists($oldFilePath)) {
                                    Storage::disk('public')->delete($oldFilePath);
                                    Log::info('Old file deleted', ['file' => $oldFilePath]);
                                }
                            }

                            $extension = $file->getClientOriginalExtension();
                            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
                            $fileName = time() . '_' . $docKey . '_' . $safeName . '.' . $extension;

                            // Ensure directory exists
                            if (!Storage::disk('public')->exists('documents')) {
                                Storage::disk('public')->makeDirectory('documents');
                            }

                            $path = $file->storeAs('documents', $fileName, 'public');
                            $updatedDocuments[$docKey] = $fileName;

                            Log::info('File uploaded successfully for resubmit', [
                                'key' => $docKey,
                                'original_name' => $file->getClientOriginalName(),
                                'stored_name' => $fileName,
                                'path' => $path,
                                'size' => $file->getSize()
                            ]);
                        } catch (Exception $e) {
                            Log::error('File upload failed for resubmit', [
                                'key' => $docKey,
                                'error' => $e->getMessage()
                            ]);

                            return redirect()->back()
                                ->withInput()
                                ->with('error', 'Gagal mengupload file ' . ($document['name'] ?? $docKey) . '. Error: ' . $e->getMessage());
                        }
                    }
                }
            }

            // Update pendaftar record
            $pendaftar->update([
                'form_data' => $updatedFormData,
                'uploaded_documents' => $updatedDocuments,
                'status' => 'pending',
                'rejection_reason' => null,
                'rejection_date' => null,
                'can_resubmit' => false,
                'updated_at' => now()
            ]);

            Log::info('Pendaftar resubmitted successfully', [
                'id' => $pendaftar->id,
                'beasiswa_id' => $pendaftar->beasiswa_id,
                'email' => $pendaftar->email
            ]);

            return redirect()->route('status')
                ->with('success', 'Aplikasi berhasil diajukan ulang! Data Anda akan diverifikasi kembali.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Resubmit validation failed', [
                'pendaftar_id' => $pendaftar->id,
                'errors' => $e->errors(),
                'validator_messages' => $e->validator->messages()->toArray()
            ]);

            $firstError = collect($e->errors())->flatten()->first();

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validasi gagal: ' . $firstError);

        } catch (Exception $e) {
            Log::error('Unexpected error during resubmit', [
                'pendaftar_id' => $pendaftar->id,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage() . '. Silakan coba lagi atau hubungi administrator.');
        }
    }
}