<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

class PDFController extends Controller
{
    public function showPDF($filename)
    {
        // Path ke file PDF
        $path = storage_path('app/public/pdfs/' . $filename);

        // Cek apakah file ada
        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        // Tampilkan PDF di browser
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    public function downloadPDF($filename)
    {
        $path = storage_path('app/public/pdfs/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        // Download PDF
        return response()->download($path, $filename, [
            'Content-Type' => 'application/pdf'
        ]);
    }
}
