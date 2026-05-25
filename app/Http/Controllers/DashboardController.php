<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Redirect ke dashboard sesuai role pengguna yang login.
     */
    public function redirect(): RedirectResponse
    {
        return redirect()->route(Auth::user()->dashboardRouteName());
    }

    /**
     * Dashboard statistik admin/owner.
     */
    public function admin(): View
    {
        return view('dashboard.admin', [
            'stats' => [
                'total_booking' => 0,
                'total_transaksi' => 0,
                'total_pendapatan' => 0,
                'laundry_proses' => 0,
                'laundry_selesai' => 0,
                'belum_dibayar' => 0,
            ],
        ]);
    }

    /**
     * Dashboard operasional kasir.
     */
    public function kasir(): View
    {
        return view('dashboard.kasir', [
            'stats' => [
                'booking_hari_ini' => 0,
                'laundry_proses' => 0,
                'transaksi_hari_ini' => 0,
                'siap_diambil' => 0,
            ],
        ]);
    }

    /**
     * Dashboard pelanggan.
     */
    public function user(): View
    {
        return view('dashboard.user', [
            'stats' => [
                'booking_aktif' => 0,
                'status_cucian' => 'Belum ada data',
                'riwayat_booking' => 0,
                'estimasi_selesai' => '-',
            ],
        ]);
    }
}
