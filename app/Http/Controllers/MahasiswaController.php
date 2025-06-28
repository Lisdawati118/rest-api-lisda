<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Http\Resources\MahasiswaResource;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::all();
        return MahasiswaResource::collection($mahasiswas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nim' => 'required|string|max:10|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'jk' => 'required|string|max:1',
            'tgl_lahir' => 'required|date',
            'jurusan' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
        ]);

        $mahasiswa = Mahasiswa::create($validatedData);
        return (new MahasiswaResource($mahasiswa))
        ->additional([
            'success' => true,
            'message' => 'Mahasiswa created successfully'

        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nim)
    {
        $mahasiswa = Mahasiswa::findOrFail($nim);
        return response()->json($mahasiswa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $nim)
    {
      
        $request->validate([
            'nim' => 'required|string|max:10|unique:mahasiswas,nim,' . $nim . ',nim',
            'nama' => 'required|string|max:255',
            'jk' => 'required|string|max:1',
            'tgl_lahir' => 'required|date',
            'jurusan' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
        ]);
        $mahasiswa = Mahasiswa::findOrFail($nim);
        $mahasiswa->update($request->all());
        return (new MahasiswaResource($mahasiswa))
        ->additional([
            'success' => true,
            'message' => 'Mahasiswa updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $nim)
    {
        $mahasiswa = Mahasiswa::findOrFail($nim);
        $mahasiswa->delete();
        return (new MahasiswaResource($mahasiswa))
        ->additional([
            'success' => true,
            'message' => 'Mahasiswa deleted successfully'
        ]);
    }
}
