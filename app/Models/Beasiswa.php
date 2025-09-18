<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Validate form fields for duplicate keys
     */
    public function validateFormFieldsUniqueness($formFields)
    {
        if (!is_array($formFields)) {
            return true;
        }

        $keys = collect($formFields)->pluck('key')->filter()->toArray();
        $duplicates = array_diff_assoc($keys, array_unique($keys));

        if (!empty($duplicates)) {
            throw new \InvalidArgumentException('Key form field yang benar-benar duplikat: ' . implode(', ', array_unique($duplicates)));
        }

        return true;
    }

    /**
     * Validate required documents for duplicate keys
     */
    public function validateDocumentsUniqueness($documents)
    {
        if (!is_array($documents)) {
            return true;
        }

        $keys = collect($documents)->pluck('key')->filter()->toArray();
        $duplicates = array_diff_assoc($keys, array_unique($keys));

        if (!empty($duplicates)) {
            throw new \InvalidArgumentException('Key dokumen yang benar-benar duplikat: ' . implode(', ', array_unique($duplicates)));
        }

        return true;
    }

    /**
     * Get form fields with validation
     */
    public function getFormFieldsAttribute($value)
    {
        if (is_array($value)) {
            return empty($value) ? $this->getDefaultFormFields() : $value;
        }

        $decoded = json_decode($value, true);

        if (!is_array($decoded) || empty($decoded)) {
            return $this->getDefaultFormFields();
        }

        return $decoded;
    }

    /**
     * Set form fields attribute with duplicate validation
     */
    public function setFormFieldsAttribute($value)
    {
        if (is_array($value)) {
            // Validasi duplikasi sebelum menyimpan
            $this->validateFormFieldsUniqueness($value);
            $this->attributes['form_fields'] = json_encode($value);
        } else {
            $this->attributes['form_fields'] = $value;
        }
    }

    /**
     * Get required documents with validation
     */
    public function getRequiredDocumentsAttribute($value)
    {
        if (is_array($value)) {
            return empty($value) ? $this->getDefaultDocuments() : $value;
        }

        $decoded = json_decode($value, true);

        if (!is_array($decoded) || empty($decoded)) {
            return $this->getDefaultDocuments();
        }

        return $decoded;
    }

    /**
     * Set required documents attribute with duplicate validation
     */
    public function setRequiredDocumentsAttribute($value)
    {
        if (is_array($value)) {
            // Validasi duplikasi sebelum menyimpan
            $this->validateDocumentsUniqueness($value);
            $this->attributes['required_documents'] = json_encode($value);
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
                'required' => true
            ],
            [
                'name' => 'NIM',
                'key' => 'nim',
                'type' => 'number',
                'icon' => 'fas fa-id-card',
                'placeholder' => 'Masukkan NIM',
                'position' => 'personal',
                'validation' => 'required|digits_between:8,20',
                'required' => true
            ],
            [
                'name' => 'Email',
                'key' => 'email',
                'type' => 'email',
                'icon' => 'fas fa-envelope',
                'placeholder' => 'contoh@email.com',
                'position' => 'personal',
                'validation' => 'required|email',
                'required' => true
            ],
            [
                'name' => 'No. HP',
                'key' => 'no_hp',
                'type' => 'tel',
                'icon' => 'fas fa-phone',
                'placeholder' => '08xxxxxxxxxx',
                'position' => 'personal',
                'validation' => 'required|min:10|max:15',
                'required' => true
            ],
            [
                'name' => 'Fakultas',
                'key' => 'fakultas',
                'type' => 'text',
                'icon' => 'fas fa-university',
                'placeholder' => 'Contoh: Teknik',
                'position' => 'academic',
                'validation' => 'required|min:3|max:50',
                'required' => true
            ],
            [
                'name' => 'Jurusan',
                'key' => 'jurusan',
                'type' => 'text',
                'icon' => 'fas fa-graduation-cap',
                'placeholder' => 'Contoh: Teknik Informatika',
                'position' => 'academic',
                'validation' => 'required|min:3|max:100',
                'required' => true
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
                'required' => true
            ],
            [
                'name' => 'Alasan Mendaftar',
                'key' => 'alasan_mendaftar',
                'type' => 'textarea',
                'icon' => 'fas fa-comment-alt',
                'placeholder' => 'Jelaskan alasan dan motivasi Anda mendaftar beasiswa ini...',
                'position' => 'additional',
                'validation' => 'required|min:50|max:1000',
                'required' => true
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
                'key' => 'file_transkrip',
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
                'key' => 'file_kk',
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
            }

            // Add custom validation from field config
            if (!empty($field['validation'])) {
                $customRules = explode('|', $field['validation']);
                foreach ($customRules as $customRule) {
                    if (!in_array($customRule, $rule)) {
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
}