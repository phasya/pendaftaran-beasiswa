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