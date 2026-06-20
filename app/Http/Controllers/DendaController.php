<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Denda;

class DendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dendas = Denda::with(['peminjaman.user', 'peminjaman.buku'])
            ->latest()
            ->paginate(15);

        $totalBelumLunas = Denda::where('status_bayar', 'belum')->sum('total_denda');

        return view('denda.index', compact('dendas', 'totalBelumLunas'));
    }

    /**
     * Mark a fine as paid.
     */
    public function lunasi(Denda $denda)
    {
        if ($denda->status_bayar === 'lunas') {
            return redirect()->back()->with('error', 'Denda ini sudah lunas sebelumnya.');
        }

        $denda->update(['status_bayar' => 'lunas']);

        return redirect()->route('denda.index')
            ->with('success', 'Denda berhasil ditandai sebagai lunas!');
    }
}
