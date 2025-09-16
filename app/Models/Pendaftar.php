<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'beasiswa_id',
        'form_data',
        'uploaded_documents',
        'status',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'rejection_reason',
        'notes'
    ];

    protected $casts = [
        'form_data' => 'array',
        'uploaded_documents' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_REVIEW = 'review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_REVIEW => 'Sedang Direview',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak'
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Accessors & Mutators
    public function getStatusLabelAttribute()
    {
        $statuses = self::getStatusOptions();
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_REVIEW => 'info',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    // Helper Methods
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isUnderReview()
    {
        return $this->status === self::STATUS_REVIEW;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function canBeEdited()
    {
        return $this->isPending() || $this->isRejected();
    }

    /**
     * Get form data value by key
     */
    public function getFormDataValue($key, $default = null)
    {
        return $this->form_data[$key] ?? $default;
    }

    /**
     * Get uploaded document info
     */
    public function getDocumentInfo($documentKey)
    {
        return $this->uploaded_documents[$documentKey] ?? null;
    }

    /**
     * Check if document exists
     */
    public function hasDocument($documentKey)
    {
        return isset($this->uploaded_documents[$documentKey]);
    }

    /**
     * Get document URL for viewing/downloading
     */
    public function getDocumentUrl($documentKey)
    {
        if (!$this->hasDocument($documentKey)) {
            return null;
        }

        $document = $this->uploaded_documents[$documentKey];
        return asset('storage/' . $document['path']);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSize($documentKey)
    {
        if (!$this->hasDocument($documentKey)) {
            return null;
        }

        $bytes = $this->uploaded_documents[$documentKey]['size'] ?? 0;
        
        if ($bytes === 0) return '0 Bytes';
        
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    /**
     * Get all form data formatted for display
     */
    public function getFormattedFormData()
    {
        if (!$this->beasiswa) {
            return $this->form_data;
        }

        $formatted = [];
        
        foreach ($this->beasiswa->form_fields as $field) {
            $key = $field['key'];
            $value = $this->getFormDataValue($key);
            
            if ($value !== null && $value !== '') {
                $formatted[] = [
                    'label' => $field['name'],
                    'value' => $this->formatFieldValue($field, $value),
                    'icon' => $field['icon'] ?? 'fas fa-info',
                    'type' => $field['type']
                ];
            }
        }

        return $formatted;
    }

    /**
     * Format field value for display
     */
    private function formatFieldValue($field, $value)
    {
        switch ($field['type']) {
            case 'select':
            case 'radio':
                if (isset($field['options'])) {
                    foreach ($field['options'] as $option) {
                        if ($option['value'] == $value) {
                            return $option['label'];
                        }
                    }
                }
                return $value;
                
            case 'checkbox':
                if (is_array($value) && isset($field['options'])) {
                    $labels = [];
                    foreach ($field['options'] as $option) {
                        if (in_array($option['value'], $value)) {
                            $labels[] = $option['label'];
                        }
                    }
                    return implode(', ', $labels);
                }
                return is_array($value) ? implode(', ', $value) : $value;
                
            case 'date':
                try {
                    return \Carbon\Carbon::parse($value)->format('d M Y');
                } catch (\Exception $e) {
                    return $value;
                }
                
            case 'number':
                if ($field['key'] === 'ipk') {
                    return number_format(floatval($value), 2);
                }
                return number_format(intval($value));
                
            case 'textarea':
                return nl2br(e($value));
                
            default:
                return $value;
        }
    }

    /**
     * Get document summary for display
     */
    public function getDocumentSummary()
    {
        if (!$this->beasiswa) {
            return [];
        }

        $summary = [];
        
        foreach ($this->beasiswa->required_documents as $docConfig) {
            $key = $docConfig['key'];
            $hasDoc = $this->hasDocument($key);
            
            $summary[] = [
                'name' => $docConfig['name'],
                'key' => $key,
                'icon' => $docConfig['icon'],
                'color' => $docConfig['color'],
                'required' => $docConfig['required'],
                'uploaded' => $hasDoc,
                'file_info' => $hasDoc ? $this->uploaded_documents[$key] : null,
                'formatted_size' => $hasDoc ? $this->getFormattedFileSize($key) : null,
                'url' => $hasDoc ? $this->getDocumentUrl($key) : null
            ];
        }

        return $summary;
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByBeasiswa($query, $beasiswaId)
    {
        return $query->where('beasiswa_id', $beasiswaId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('submitted_at', '>=', now()->subDays($days));
    }
}