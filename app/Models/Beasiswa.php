<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class Beasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_beasiswa',
        'deskripsi',
        'jumlah_dana',
        'tanggal_buka',
        'tanggal_tutup',
        'status',
        'persyaratan',
        'form_fields',
        'required_documents',
        'dynamic_fields'
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
        'form_fields' => 'array',
        'required_documents' => 'array',
        'dynamic_fields' => 'array'
    ];

    public function pendaftars()
    {
        return $this->hasMany(Pendaftar::class);
    }

    public function isActive()
    {
        return $this->status === 'aktif' &&
            now()->between($this->tanggal_buka, $this->tanggal_tutup);
    }

    public function getFormattedDanaAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_dana, 0, ',', '.');
    }

    /**
     * Generate unique key from name
     */
    public static function generateUniqueKey($name, $usedKeys = [], $prefix = '')
    {
        // Clean and convert name to key format
        $baseKey = Str::slug($name, '_');
        $baseKey = preg_replace('/[^a-z0-9_]/', '', strtolower($baseKey));
        $baseKey = substr($baseKey, 0, 30);

        // Fallback if empty
        if (empty($baseKey)) {
            $baseKey = $prefix ? 'default' : 'field';
        }

        if ($prefix) {
            $baseKey = $prefix . '_' . $baseKey;
        }

        $key = $baseKey;
        $counter = 1;

        // Ensure uniqueness
        while (in_array($key, $usedKeys)) {
            $key = $baseKey . '_' . $counter;
            $counter++;
        }

        return $key;
    }

    /**
     * Process and auto-generate keys for form fields
     */
    public function processFormFields($formFields)
    {
        if (!is_array($formFields)) {
            return $this->getDefaultFormFields();
        }

        $processedFields = [];
        $usedKeys = [];

        foreach ($formFields as $field) {
            if (empty($field['name'])) {
                continue; // Skip fields without names
            }

            // Generate unique key if not provided or empty
            if (empty($field['key'])) {
                $field['key'] = self::generateUniqueKey($field['name'], $usedKeys);
            } else {
                // Sanitize existing key
                $field['key'] = $this->sanitizeKey($field['key']);

                // Ensure uniqueness
                if (in_array($field['key'], $usedKeys)) {
                    $field['key'] = self::generateUniqueKey($field['name'], $usedKeys);
                }
            }

            $usedKeys[] = $field['key'];

            // Process options for select/radio/checkbox fields
            $processedOptions = [];
            if (!empty($field['options']) && is_array($field['options'])) {
                foreach ($field['options'] as $option) {
                    if (is_array($option)) {
                        $processedOptions[] = [
                            'value' => $option['value'] ?? '',
                            'label' => $option['label'] ?? ($option['value'] ?? '')
                        ];
                    } else {
                        // Handle string options
                        $processedOptions[] = [
                            'value' => $option,
                            'label' => $option
                        ];
                    }
                }
            }

            $processedFields[] = [
                'name' => $field['name'] ?? '',
                'key' => $field['key'],
                'type' => $field['type'] ?? 'text',
                'icon' => $field['icon'] ?? 'fas fa-user',
                'placeholder' => $field['placeholder'] ?? '',
                'position' => $field['position'] ?? 'personal',
                'validation' => $field['validation'] ?? '',
                'required' => (bool) ($field['required'] ?? true),
                'options' => $processedOptions
            ];
        }

        return empty($processedFields) ? $this->getDefaultFormFields() : $processedFields;
    }

    /**
     * Process and auto-generate keys for required documents
     */
    public function processRequiredDocuments($documents)
    {
        if (!is_array($documents)) {
            return $this->getDefaultDocuments();
        }

        $processedDocs = [];
        $usedKeys = [];

        foreach ($documents as $doc) {
            if (empty($doc['name'])) {
                continue; // Skip documents without names
            }

            // Generate unique key if not provided or empty
            if (empty($doc['key'])) {
                $doc['key'] = self::generateUniqueKey($doc['name'], $usedKeys, 'file');
            } else {
                // Sanitize existing key
                $doc['key'] = $this->sanitizeKey($doc['key'], 'file');

                // Ensure uniqueness
                if (in_array($doc['key'], $usedKeys)) {
                    $doc['key'] = self::generateUniqueKey($doc['name'], $usedKeys, 'file');
                }
            }

            $usedKeys[] = $doc['key'];

            // Process formats array
            $formats = [];
            if (!empty($doc['formats']) && is_array($doc['formats'])) {
                $formats = array_filter($doc['formats'], function ($format) {
                    return in_array(strtolower($format), ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx']);
                });
            }

            $processedDocs[] = [
                'name' => $doc['name'] ?? '',
                'key' => $doc['key'],
                'icon' => $doc['icon'] ?? 'fas fa-file',
                'color' => $doc['color'] ?? 'gray',
                'formats' => array_values($formats),
                'max_size' => max(1, min(10, (int) ($doc['max_size'] ?? 5))),
                'description' => $doc['description'] ?? '',
                'required' => (bool) ($doc['required'] ?? true)
            ];
        }

        return empty($processedDocs) ? $this->getDefaultDocuments() : $processedDocs;
    }

    /**
     * Sanitize key to ensure it's database/form safe
     */
    private function sanitizeKey($key, $prefix = '')
    {
        // Remove any non-alphanumeric characters except underscores
        $sanitized = preg_replace('/[^a-zA-Z0-9_]/', '_', $key);
        $sanitized = strtolower($sanitized);

        // Remove multiple consecutive underscores
        $sanitized = preg_replace('/_+/', '_', $sanitized);

        // Trim underscores from start and end
        $sanitized = trim($sanitized, '_');

        // Ensure it starts with the prefix if provided
        if ($prefix && !str_starts_with($sanitized, $prefix . '_')) {
            $sanitized = $prefix . '_' . $sanitized;
        }

        // Limit length
        return substr($sanitized, 0, 50);
    }

    /**
     * Get form fields with validation and auto-processing
     */
    public function getFormFieldsAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return $this->getDefaultFormFields();
        }

        if (is_array($value)) {
            return empty($value) ? $this->getDefaultFormFields() : $this->processFormFields($value);
        }

        $decoded = json_decode($value, true);

        if (!is_array($decoded) || empty($decoded)) {
            return $this->getDefaultFormFields();
        }

        return $this->processFormFields($decoded);
    }

    /**
     * Set form fields attribute with processing
     */
    public function setFormFieldsAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['form_fields'] = json_encode($this->getDefaultFormFields());
        } elseif (is_array($value)) {
            $processed = $this->processFormFields($value);
            $this->attributes['form_fields'] = json_encode($processed);
        } else {
            $this->attributes['form_fields'] = $value;
        }
    }

    /**
     * Get required documents with validation and auto-processing
     */
    public function getRequiredDocumentsAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return $this->getDefaultDocuments();
        }

        if (is_array($value)) {
            return empty($value) ? $this->getDefaultDocuments() : $this->processRequiredDocuments($value);
        }

        // If string (JSON), decode it
        $decoded = json_decode($value, true);

        // Return default documents if none set or invalid
        if (!is_array($decoded) || empty($decoded)) {
            return $this->getDefaultDocuments();
        }

        return $this->processRequiredDocuments($decoded);
    }

    /**
     * Set required documents attribute with processing
     */
    public function setRequiredDocumentsAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['required_documents'] = json_encode($this->getDefaultDocuments());
        } elseif (is_array($value)) {
            $processed = $this->processRequiredDocuments($value);
            $this->attributes['required_documents'] = json_encode($processed);
        } else {
            $this->attributes['required_documents'] = $value;
        }
    }

    /**
     * Get default form fields structure
     */
    private function getDefaultFormFields()
    {
        return [
            [
                'name' => 'Nama Lengkap',
                'key' => 'nama_lengkap',
                'type' => 'text',
                'icon' => 'fas fa-user',
                'placeholder' => 'Masukkan nama lengkap',
                'position' => 'personal',
                'validation' => 'required|min:3|max:100',
                'required' => true,
                'options' => []
            ],
            [
                'name' => 'NIM',
                'key' => 'nim',
                'type' => 'number',
                'icon' => 'fas fa-id-card',
                'placeholder' => 'Masukkan NIM',
                'position' => 'personal',
                'validation' => 'required|digits_between:8,20',
                'required' => true,
                'options' => []
            ],
            [
                'name' => 'Email',
                'key' => 'email',
                'type' => 'email',
                'icon' => 'fas fa-envelope',
                'placeholder' => 'contoh@email.com',
                'position' => 'personal',
                'validation' => 'required|email',
                'required' => true,
                'options' => []
            ],
            [
                'name' => 'No. HP',
                'key' => 'no_hp',
                'type' => 'tel',
                'icon' => 'fas fa-phone',
                'placeholder' => '08xxxxxxxxxx',
                'position' => 'personal',
                'validation' => 'required|min:10|max:15',
                'required' => true,
                'options' => []
            ],
            [
                'name' => 'Fakultas',
                'key' => 'fakultas',
                'type' => 'text',
                'icon' => 'fas fa-university',
                'placeholder' => 'Contoh: Teknik',
                'position' => 'academic',
                'validation' => 'required|min:3|max:50',
                'required' => true,
                'options' => []
            ],
            [
                'name' => 'Jurusan',
                'key' => 'jurusan',
                'type' => 'text',
                'icon' => 'fas fa-graduation-cap',
                'placeholder' => 'Contoh: Teknik Informatika',
                'position' => 'academic',
                'validation' => 'required|min:3|max:100',
                'required' => true,
                'options' => []
            ],
            [
                'name' => 'Semester',
                'key' => 'semester',
                'type' => 'select',
                'icon' => 'fas fa-calendar-check',
                'placeholder' => '-- Pilih Semester --',
                'position' => 'academic',
                'validation' => 'required|between:1,14',
                'required' => true,
                'options' => [
                    ['value' => '1', 'label' => 'Semester 1'],
                    ['value' => '2', 'label' => 'Semester 2'],
                    ['value' => '3', 'label' => 'Semester 3'],
                    ['value' => '4', 'label' => 'Semester 4'],
                    ['value' => '5', 'label' => 'Semester 5'],
                    ['value' => '6', 'label' => 'Semester 6'],
                    ['value' => '7', 'label' => 'Semester 7'],
                    ['value' => '8', 'label' => 'Semester 8']
                ]
            ],
            [
                'name' => 'IPK',
                'key' => 'ipk',
                'type' => 'number',
                'icon' => 'fas fa-chart-line',
                'placeholder' => '3.50',
                'position' => 'academic',
                'validation' => 'required|numeric|between:0,4',
                'required' => true,
                'options' => []
            ],
            [
                'name' => 'Alasan Mendaftar',
                'key' => 'alasan_mendaftar',
                'type' => 'textarea',
                'icon' => 'fas fa-comment-alt',
                'placeholder' => 'Jelaskan alasan dan motivasi Anda mendaftar beasiswa ini...',
                'position' => 'additional',
                'validation' => 'required|min:50|max:1000',
                'required' => true,
                'options' => []
            ]
        ];
    }

    /**
     * Get default documents structure
     */
    private function getDefaultDocuments()
    {
        return [
            [
                'name' => 'Transkrip Nilai',
                'key' => 'file_transkrip_nilai',
                'icon' => 'fas fa-file-pdf',
                'color' => 'red',
                'formats' => ['pdf'],
                'max_size' => 5,
                'required' => true,
                'description' => 'Transkrip nilai terbaru'
            ],
            [
                'name' => 'KTP',
                'key' => 'file_ktp',
                'icon' => 'fas fa-id-card',
                'color' => 'blue',
                'formats' => ['pdf', 'jpg', 'jpeg', 'png'],
                'max_size' => 5,
                'required' => true,
                'description' => 'Kartu Tanda Penduduk'
            ],
            [
                'name' => 'Kartu Keluarga',
                'key' => 'file_kartu_keluarga',
                'icon' => 'fas fa-users',
                'color' => 'green',
                'formats' => ['pdf', 'jpg', 'jpeg', 'png'],
                'max_size' => 5,
                'required' => true,
                'description' => 'Kartu Keluarga'
            ]
        ];
    }

    /**
     * Get form fields grouped by position
     */
    public function getFormFieldsByPosition()
    {
        $fields = $this->form_fields;
        $grouped = [
            'personal' => [],
            'academic' => [],
            'additional' => []
        ];

        foreach ($fields as $field) {
            $position = $field['position'] ?? 'additional';
            if (isset($grouped[$position])) {
                $grouped[$position][] = $field;
            } else {
                $grouped['additional'][] = $field;
            }
        }

        return $grouped;
    }

    /**
     * Get validation rules for form fields
     */
    public function getFormFieldValidationRules()
    {
        $rules = [];

        foreach ($this->form_fields as $field) {
            $rule = [];

            if ($field['required']) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            // Add type-specific rules
            switch ($field['type']) {
                case 'email':
                    $rule[] = 'email';
                    break;
                case 'number':
                    $rule[] = 'numeric';
                    break;
                case 'date':
                    $rule[] = 'date';
                    break;
                case 'tel':
                    $rule[] = 'string';
                    break;
                case 'select':
                case 'radio':
                    // Validate against available options
                    if (!empty($field['options'])) {
                        $validValues = collect($field['options'])->pluck('value')->toArray();
                        $rule[] = 'in:' . implode(',', $validValues);
                    }
                    break;
                case 'checkbox':
                    $rule[] = 'array';
                    if (!empty($field['options'])) {
                        $validValues = collect($field['options'])->pluck('value')->toArray();
                        $rule[] = 'in:' . implode(',', $validValues);
                    }
                    break;
            }

            // Add custom validation from field config
            if (!empty($field['validation'])) {
                $customRules = explode('|', $field['validation']);
                foreach ($customRules as $customRule) {
                    if (!in_array($customRule, $rule) && $customRule !== 'required') {
                        $rule[] = $customRule;
                    }
                }
            }

            $rules[$field['key']] = implode('|', $rule);
        }

        return $rules;
    }

    /**
     * Get validation messages for form fields
     */
    public function getFormFieldValidationMessages()
    {
        $messages = [];

        foreach ($this->form_fields as $field) {
            $key = $field['key'];
            $name = $field['name'];

            $messages["{$key}.required"] = "Field {$name} wajib diisi.";
            $messages["{$key}.email"] = "Format {$name} tidak valid.";
            $messages["{$key}.numeric"] = "{$name} harus berupa angka.";
            $messages["{$key}.date"] = "Format {$name} harus berupa tanggal yang valid.";
            $messages["{$key}.min"] = "{$name} minimal harus berisi :min karakter.";
            $messages["{$key}.max"] = "{$name} maksimal berisi :max karakter.";
            $messages["{$key}.between"] = "{$name} harus berada di antara :min dan :max.";
            $messages["{$key}.in"] = "{$name} harus memilih salah satu opsi yang tersedia.";
            $messages["{$key}.array"] = "{$name} harus berupa pilihan yang valid.";
        }

        return $messages;
    }

    /**
     * Get required document keys for validation
     */
    public function getRequiredDocumentKeys()
    {
        return collect($this->required_documents)->pluck('key')->toArray();
    }

    /**
     * Get validation rules for documents
     */
    public function getDocumentValidationRules()
    {
        $rules = [];

        foreach ($this->required_documents as $document) {
            $rule = [];

            if ($document['required']) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            $rule[] = 'file';

            if (!empty($document['formats'])) {
                $rule[] = 'mimes:' . implode(',', $document['formats']);
            }

            if (!empty($document['max_size'])) {
                $rule[] = 'max:' . ($document['max_size'] * 1024); // Convert MB to KB
            }

            $rules[$document['key']] = implode('|', $rule);
        }

        return $rules;
    }

    /**
     * Get document validation messages
     */
    public function getDocumentValidationMessages()
    {
        $messages = [];

        foreach ($this->required_documents as $document) {
            $key = $document['key'];
            $name = $document['name'];

            $messages["{$key}.required"] = "Dokumen {$name} wajib diupload.";
            $messages["{$key}.file"] = "File {$name} harus berupa file yang valid.";

            if (!empty($document['formats'])) {
                $formats = implode(', ', array_map('strtoupper', $document['formats']));
                $messages["{$key}.mimes"] = "Format {$name} harus: {$formats}.";
            }

            if (!empty($document['max_size'])) {
                $messages["{$key}.max"] = "Ukuran {$name} maksimal {$document['max_size']}MB.";
            }
        }

        return $messages;
    }

    /**
     * Validate form fields data integrity
     */
    public function validateFormFieldsIntegrity()
    {
        $errors = [];
        $usedKeys = [];

        foreach ($this->form_fields as $index => $field) {
            $fieldName = "Field " . ($index + 1);

            // Check required properties
            if (empty($field['name'])) {
                $errors[] = "{$fieldName}: Name is required";
            }

            if (empty($field['key'])) {
                $errors[] = "{$fieldName}: Key is required";
            } else {
                // Check for duplicate keys
                if (in_array($field['key'], $usedKeys)) {
                    $errors[] = "{$fieldName}: Duplicate key '{$field['key']}'";
                }
                $usedKeys[] = $field['key'];

                // Check key format
                if (!preg_match('/^[a-z0-9_]+$/', $field['key'])) {
                    $errors[] = "{$fieldName}: Key '{$field['key']}' contains invalid characters";
                }
            }

            // Check type validity
            $validTypes = ['text', 'email', 'number', 'date', 'textarea', 'select', 'radio', 'checkbox', 'tel'];
            if (!in_array($field['type'] ?? '', $validTypes)) {
                $errors[] = "{$fieldName}: Invalid field type";
            }

            // Check options for select/radio/checkbox
            if (in_array($field['type'] ?? '', ['select', 'radio', 'checkbox'])) {
                if (empty($field['options']) || !is_array($field['options'])) {
                    $errors[] = "{$fieldName}: Options are required for {$field['type']} fields";
                } else {
                    foreach ($field['options'] as $optIndex => $option) {
                        if (!is_array($option) || empty($option['value']) || empty($option['label'])) {
                            $errors[] = "{$fieldName}, Option " . ($optIndex + 1) . ": Value and label are required";
                        }
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Validate documents data integrity
     */
    public function validateDocumentsIntegrity()
    {
        $errors = [];
        $usedKeys = [];

        foreach ($this->required_documents as $index => $document) {
            $docName = "Document " . ($index + 1);

            // Check required properties
            if (empty($document['name'])) {
                $errors[] = "{$docName}: Name is required";
            }

            if (empty($document['key'])) {
                $errors[] = "{$docName}: Key is required";
            } else {
                // Check for duplicate keys
                if (in_array($document['key'], $usedKeys)) {
                    $errors[] = "{$docName}: Duplicate key '{$document['key']}'";
                }
                $usedKeys[] = $document['key'];

                // Check key format
                if (!preg_match('/^[a-z0-9_]+$/', $document['key'])) {
                    $errors[] = "{$docName}: Key '{$document['key']}' contains invalid characters";
                }
            }

            // Check formats
            if (empty($document['formats']) || !is_array($document['formats'])) {
                $errors[] = "{$docName}: At least one format is required";
            } else {
                $validFormats = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
                foreach ($document['formats'] as $format) {
                    if (!in_array(strtolower($format), $validFormats)) {
                        $errors[] = "{$docName}: Invalid format '{$format}'";
                    }
                }
            }

            // Check max_size
            $maxSize = $document['max_size'] ?? 0;
            if ($maxSize < 1 || $maxSize > 10) {
                $errors[] = "{$docName}: Max size must be between 1 and 10 MB";
            }
        }

        return $errors;
    }

    /**
     * Get comprehensive validation report
     */
    public function getValidationReport()
    {
        return [
            'form_fields_errors' => $this->validateFormFieldsIntegrity(),
            'documents_errors' => $this->validateDocumentsIntegrity(),
            'is_valid' => empty($this->validateFormFieldsIntegrity()) && empty($this->validateDocumentsIntegrity())
        ];
    }

    /**
     * Auto-fix common data issues
     */
    public function autoFixData()
    {
        // Fix form fields
        $fixedFormFields = $this->processFormFields($this->attributes['form_fields'] ?? []);
        $this->attributes['form_fields'] = json_encode($fixedFormFields);

        // Fix documents
        $fixedDocuments = $this->processRequiredDocuments($this->attributes['required_documents'] ?? []);
        $this->attributes['required_documents'] = json_encode($fixedDocuments);

        return $this;
    }

    /**
     * Get field by key
     */
    public function getFormFieldByKey($key)
    {
        return collect($this->form_fields)->firstWhere('key', $key);
    }

    /**
     * Get document by key
     */
    public function getDocumentByKey($key)
    {
        return collect($this->required_documents)->firstWhere('key', $key);
    }

    /**
     * Check if field exists
     */
    public function hasFormField($key)
    {
        return !is_null($this->getFormFieldByKey($key));
    }

    /**
     * Check if document exists
     */
    public function hasDocument($key)
    {
        return !is_null($this->getDocumentByKey($key));
    }
}