<?php

namespace App\Http\Controllers;

use App\Models\SuratPerjalananDinas;
use App\Models\SuratTugas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    /**
     * Download Surat Tugas PDF
     */
    public function downloadSuratTugas($id)
    {
        $suratTugas = SuratTugas::with(['peserta' => function($query) {
            $query->orderBy('surat_tugas_peserta.urutan');
        }, 'penandatangan'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.surat-tugas', [
            'surat' => $suratTugas
        ]);

        $pdf->setPaper('a4', 'portrait');

        $filename = 'Surat_Tugas_' . str_replace(['/', '.'], '_', $suratTugas->nomor_surat) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download Surat Perjalanan Dinas (SPD) PDF
     */
    public function downloadSpd($id)
    {
        $spd = SuratPerjalananDinas::with([
            'suratTugas',
            'pegawai',
            'ppk',
            'pptk',
            'perjalanan' => function($query) {
                $query->orderBy('urutan');
            }
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.surat-spd', [
            'spd' => $spd
        ]);

        $pdf->setPaper('a4', 'portrait');

        $filename = 'SPD_' . str_replace(['/', '.'], '_', $spd->nomor_spd) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Preview Surat Tugas in browser
     */
    public function previewSuratTugas($id)
    {
        $suratTugas = SuratTugas::with(['peserta' => function($query) {
            $query->orderBy('surat_tugas_peserta.urutan');
        }, 'penandatangan'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.surat-tugas', [
            'surat' => $suratTugas
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Preview SPD in browser
     */
    public function previewSpd($id)
    {
        $spd = SuratPerjalananDinas::with([
            'suratTugas',
            'pegawai',
            'ppk',
            'pptk',
            'perjalanan' => function($query) {
                $query->orderBy('urutan');
            }
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.surat-spd', [
            'spd' => $spd
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream();
    }
}
