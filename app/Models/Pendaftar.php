<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pendaftar extends Model
{
    use HasFactory;

    protected $fillable = [
        'beasiswa_id',
        'email',
        'form_data',
        'uploaded_documents',
        'status',
        'rejection_reason',
        'catatan_admin',
        'can_resubmit',
        'rejected_at'
    ];

    protected $casts = [
        'form_data' => 'array',
        'uploaded_documents' => 'array',
        'can_resubmit' => 'boolean',
        'rejected_at' => 'datetime'
    ];

    /**
     * Relationship dengan Beasiswa
     */
    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    /**
     * Helper method untuk mengakses nilai dari form_data
     */
    public function getFormValue($key, $default = null)
    {
        return $this->form_data[$key] ?? $default;
    }

    /**
     * Helper method untuk mengecek apakah field ada di form_data
     */
    public function hasFormField($key)
    {
        return isset($this->form_data[$key]);
    }

    /**
     * Helper method untuk set nilai form_data
     */
    public function setFormValue($key, $value)
    {
        $formData = $this->form_data ?? [];
        $formData[$key] = $value;
        $this->form_data = $formData;
    }

    /**
     * Legacy accessors untuk backward compatibility
     * Ini memungkinkan akses seperti $pendaftar->nama_lengkap
     */
    public function getNamaLengkapAttribute()
    {
        return $this->getFormValue('nama_lengkap');
    }

    public function getNimAttribute()
    {
        return $this->getFormValue('nim');
    }

    public function getNoHpAttribute()
    {
        return $this->getFormValue('no_hp');
    }

    public function getFakultasAttribute()
    {
        return $this->getFormValue('fakultas');
    }

    public function getJurusanAttribute()
    {
        return $this->getFormValue('jurusan');
    }

    public function getSemesterAttribute()
    {
        return $this->getFormValue('semester');
    }

    public function getIpkAttribute()
    {
        return $this->getFormValue('ipk');
    }

    public function getAlasanMendaftarAttribute()
    {
        return $this->getFormValue('alasan_mendaftar');
    }

    public function getTempatLahirAttribute()
    {
        return $this->getFormValue('tempat_lahir');
    }

    public function getTanggalLahirAttribute()
    {
        return $this->getFormValue('tanggal_lahir');
    }

    public function getJenisKelaminAttribute()
    {
        return $this->getFormValue('jenis_kelamin');
    }

    public function getAngkatanAttribute()
    {
        return $this->getFormValue('angkatan');
    }

    public function getAlamatAttribute()
    {
        return $this->getFormValue('alamat');
    }

    /**
     * Check if this application was rejected
     */
    public function isRejected()
    {
        return $this->status === 'ditolak';
    }

    /**
     * Check if user can resubmit after rejection
     */
    public function canResubmit()
    {
        return $this->isRejected() && $this->can_resubmit;
    }

    /**
     * Check if application is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if application is accepted
     */
    public function isAccepted()
    {
        return $this->status === 'diterima';
    }

    /**
     * Get formatted rejection date
     */
    public function getRejectionDateAttribute()
    {
        return $this->rejected_at ? $this->rejected_at->format('d M Y H:i') : null;
    }

    /**
     * Get document by key
     */
    public function getDocument($key)
    {
        return $this->uploaded_documents[$key] ?? null;
    }

    /**
     * Set document
     */
    public function setDocument($key, $filename)
    {
        $documents = $this->uploaded_documents ?? [];
        $documents[$key] = $filename;
        $this->uploaded_documents = $documents;
    }

    /**
     * Remove document
     */
    public function removeDocument($key)
    {
        $documents = $this->uploaded_documents ?? [];
        unset($documents[$key]);
        $this->uploaded_documents = $documents;
    }

    /**
     * Check if has all required documents
     */
    public function hasAllRequiredDocuments()
    {
        if (!$this->beasiswa || !$this->beasiswa->required_documents) {
            return false;
        }

        foreach ($this->beasiswa->required_documents as $document) {
            if (($document['required'] ?? true) && empty($this->getDocument($document['key'] ?? ''))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get missing required documents
     */
    public function getMissingRequiredDocuments()
    {
        $missing = [];

        if (!$this->beasiswa || !$this->beasiswa->required_documents) {
            return $missing;
        }

        foreach ($this->beasiswa->required_documents as $document) {
            $docKey = $document['key'] ?? '';
            $isRequired = $document['required'] ?? true;

            if ($isRequired && empty($this->getDocument($docKey))) {
                $missing[] = $document;
            }
        }

        return $missing;
    }

    /**
     * Get all form fields dengan values untuk tampilan
     */
    public function getFormFieldsWithValues()
    {
        $result = [];

        if (!$this->beasiswa || !$this->beasiswa->form_fields) {
            return $result;
        }

        foreach ($this->beasiswa->form_fields as $field) {
            $key = $field['key'] ?? '';
            if (!empty($key)) {
                $result[$key] = [
                    'field' => $field,
                    'value' => $this->getFormValue($key)
                ];
            }
        }

        return $result;
    }

    /**
     * Scope untuk get aplikasi yang bisa resubmit
     */
    public function scopeCanResubmit($query)
    {
        return $query->where('status', 'ditolak')
            ->where('can_resubmit', true);
    }

    /**
     * Scope untuk get aplikasi yang ditolak permanen
     */
    public function scopePermanentlyRejected($query)
    {
        return $query->where('status', 'ditolak')
            ->where('can_resubmit', false);
    }

    /**
     * Scope untuk get aplikasi aktif (pending atau diterima)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'diterima']);
    }

    /**
     * Check unique NIM across active applications
     */
    public static function isNimTaken($nim, $excludeEmail = null)
    {
        $query = self::whereJsonContains('form_data->nim', $nim)
            ->whereIn('status', ['pending', 'diterima']);

        if ($excludeEmail) {
            $query->where('email', '!=', $excludeEmail);
        }

        return $query->exists();
    }

    /**
     * Get pendaftar by NIM
     */
    public static function findByNim($nim)
    {
        return self::whereJsonContains('form_data->nim', $nim)->first();
    }
}