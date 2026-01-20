<?php

namespace App\Http\Controllers;

use App\Models\SuratPerjalananDinas;
use Illuminate\Http\Request;

class SpdController extends Controller
{
    /**
     * Display a listing of all SPD
     */
    public function index()
    {
        $spds = SuratPerjalananDinas::with(['suratTugas', 'pegawai', 'ppk', 'pptk'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('spd.index', compact('spds'));
    }

    /**
     * Show SPD details
     */
    public function show($id)
    {
        $spd = SuratPerjalananDinas::with([
            'suratTugas',
            'pegawai',
            'ppk',
            'pptk',
            'perjalanan' => function($query) {
                $query->orderBy('urutan');
            },
            'pengikut'
        ])->findOrFail($id);

        return view('spd.show', compact('spd'));
    }
}
