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
        // Debug: Uncomment ini untuk melihat data yang diterima
        // dd($request->all());

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
            'form_fields.*.key' => 'required|string|max:255',
            'form_fields.*.type' => 'required|string',
            'form_fields.*.icon' => 'required|string',
            'form_fields.*.position' => 'required|string',
            'form_fields.*.validation' => 'nullable|string',
            'form_fields.*.placeholder' => 'nullable|string',
            'form_fields.*.required' => 'required|boolean',
            'documents' => 'required|array|min:1',
            'documents.*.name' => 'required|string|max:255',
            'documents.*.key' => 'required|string|max:255',
            'documents.*.icon' => 'required|string',
            'documents.*.color' => 'required|string',
            'documents.*.formats' => 'required|array|min:1',
            'documents.*.max_size' => 'required|numeric|min:1|max:10',
            'documents.*.description' => 'nullable|string',
            'documents.*.required' => 'required|boolean'
        ]);

        // Siapkan data untuk disimpan
        $processedFormFields = [];
        foreach ($validated['form_fields'] as $field) {
            $processedFormFields[] = [
                'name' => $field['name'] ?? '',
                'key' => $field['key'] ?? '',
                'type' => $field['type'] ?? 'text',
                'icon' => $field['icon'] ?? 'fas fa-user',
                'placeholder' => $field['placeholder'] ?? '',
                'position' => $field['position'] ?? 'personal',
                'validation' => $field['validation'] ?? '',
                'required' => (bool) ($field['required'] ?? true),
                'options' => $field['options'] ?? []
            ];
        }

        $processedDocuments = [];
        foreach ($validated['documents'] as $doc) {
            $processedDocuments[] = [
                'name' => $doc['name'] ?? '',
                'key' => $doc['key'] ?? '',
                'icon' => $doc['icon'] ?? 'fas fa-file',
                'color' => $doc['color'] ?? 'gray',
                'formats' => $doc['formats'] ?? [],
                'max_size' => (int) ($doc['max_size'] ?? 5),
                'description' => $doc['description'] ?? '',
                'required' => (bool) ($doc['required'] ?? true)
            ];
        }

        $beasiswaData = [
            'nama_beasiswa' => $validated['nama_beasiswa'],
            'deskripsi' => $validated['deskripsi'],
            'jumlah_dana' => $validated['jumlah_dana'],
            'tanggal_buka' => $validated['tanggal_buka'],
            'tanggal_tutup' => $validated['tanggal_tutup'],
            'status' => $validated['status'],
            'persyaratan' => $validated['persyaratan'],
            'form_fields' => json_encode($processedFormFields),
            'documents' => json_encode($processedDocuments)
        ];

        Beasiswa::create($beasiswaData);

        return redirect()->route('admin.beasiswa.index')
            ->with('success', 'Beasiswa berhasil ditambahkan!');
    }

    public function show(Beasiswa $beasiswa)
    {
        // Decode JSON data untuk ditampilkan (hanya jika data ada dan berupa string)
        if (!empty($beasiswa->form_fields) && is_string($beasiswa->form_fields)) {
            $beasiswa->form_fields = json_decode($beasiswa->form_fields, true);
        } else {
            $beasiswa->form_fields = [];
        }

        if (!empty($beasiswa->documents) && is_string($beasiswa->documents)) {
            $beasiswa->documents = json_decode($beasiswa->documents, true);
        } else {
            $beasiswa->documents = [];
        }

        return view('admin.beasiswa.show', compact('beasiswa'));
    }

    public function edit(Beasiswa $beasiswa)
    {
        // Decode JSON data untuk form edit (hanya jika data ada dan berupa string)
        if (!empty($beasiswa->form_fields) && is_string($beasiswa->form_fields)) {
            $beasiswa->form_fields = json_decode($beasiswa->form_fields, true);
        } else {
            $beasiswa->form_fields = [];
        }

        if (!empty($beasiswa->documents) && is_string($beasiswa->documents)) {
            $beasiswa->documents = json_decode($beasiswa->documents, true);
        } else {
            $beasiswa->documents = [];
        }

        return view('admin.beasiswa.edit', compact('beasiswa'));
    }

    public function update(Request $request, Beasiswa $beasiswa)
    {
        $validated = $request->validate
        ([
            'nama_beasiswa' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jumlah_dana' => 'required|numeric|min:0',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
            'status' => 'required|in:aktif,nonaktif',
            'persyaratan' => 'required|string',
            'form_fields' => 'required|array|min:1',
            'form_fields.*.name' => 'required|string|max:255',
            'form_fields.*.key' => 'required|string|max:255',
            'form_fields.*.type' => 'required|string',
            'form_fields.*.icon' => 'required|string',
            'form_fields.*.position' => 'required|string',
            'form_fields.*.required' => 'required|boolean',
            'documents' => 'required|array|min:1',
            'documents.*.name' => 'required|string|max:255',
            'documents.*.key' => 'required|string|max:255',
            'documents.*.icon' => 'required|string',
            'documents.*.color' => 'required|string',
            'documents.*.formats' => 'required|array|min:1',
            'documents.*.max_size' => 'required|numeric|min:1|max:10',
            'documents.*.required' => 'required|boolean'
        ]);

        // Siapkan data untuk diupdate
        $updateData = [
            'nama_beasiswa' => $validated['nama_beasiswa'],
            'deskripsi' => $validated['deskripsi'],
            'jumlah_dana' => $validated['jumlah_dana'],
            'tanggal_buka' => $validated['tanggal_buka'],
            'tanggal_tutup' => $validated['tanggal_tutup'],
            'status' => $validated['status'],
            'persyaratan' => $validated['persyaratan'],
            'form_fields' => json_encode($validated['form_fields']),
            'documents' => json_encode($validated['documents'])
        ];

        $beasiswa->update($updateData);

        return redirect()->route('admin.beasiswa.index')
            ->with('success', 'Beasiswa berhasil diupdate!');
    }

    public function destroy(Beasiswa $beasiswa)
    {
        $beasiswa->delete();
        return redirect()->route('admin.beasiswa.index')
            ->with('success', 'Beasiswa berhasil dihapus!');
    }
}