<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PDFController extends Controller
{
    public function showPDFFromStorage($filename)
    {
        // Menggunakan Laravel Storage
        if (!Storage::disk('public')->exists('pdfs/' . $filename)) {
            abort(404, 'File tidak ditemukan');
        }

        $file = Storage::disk('public')->get('pdfs/' . $filename);

        return response($file, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    public function streamPDF($filename)
    {
        $path = 'pdfs/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->response($path, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline'
        ]);
    }
}