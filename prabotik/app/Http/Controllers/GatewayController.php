<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\DiseaseRecord;
use App\Models\ExpiredMedicine;

class GatewayController extends Controller
{
    /**
     * Halaman Utama Gateway Pintu Masuk Faskes
     */
    public function index()
    {
        $stats = [
            'total_obat' => Medicine::count(),
            'total_pasien' => DiseaseRecord::sum('total_pasien'),
            'total_kerugian_ed' => ExpiredMedicine::sum('sub_total_kerugian'),
            'total_usulan_unit' => Medicine::sum('rekomendasi_total'),
            'is_approved' => session('p5_procurement_approved', false),
        ];

        return view('gateway', compact('stats'));
    }

    /**
     * Proses Login Otorisasi Kepala Puskesmas (Password Protected)
     */
    public function loginKepala(Request $request)
    {
        $password = $request->input('password');

        // Kata sandi resmi Kepala Puskesmas
        $validPasswords = [
            'kepala123',
            '197805122006041008', // NIP dr. Bambang Utoyo
            'bambang123',
            'bintan2026',
        ];

        if (in_array(trim($password), $validPasswords)) {
            session([
                'auth_role' => 'executive',
                'auth_name' => 'dr. Bambang Utoyo, M.M.',
                'auth_nip'  => 'NIP. 19780512 200604 1 008',
                'auth_time' => now()->toDateTimeString(),
            ]);

            return redirect()->route('executive.dashboard')
                ->with('success', 'Selamat datang, dr. Bambang Utoyo, M.M. Hak akses pimpinan dan disposisi pengesahan aktif.');
        }

        return redirect()->route('gateway')
            ->with('error_target', 'kepala')
            ->with('error', 'Kata sandi Kepala Puskesmas salah! Akses ditolak demi menjaga kerahasiaan dokumen keuangan dan pengesahan dinas.');
    }

    /**
     * Proses Login Bagian Farmasi & Gudang Obat
     */
    public function loginFarmasi(Request $request)
    {
        $password = $request->input('password', 'farmasi123');

        // Kata sandi Petugas Farmasi (fleksibel jika dikosongkan tetap dapat masuk via tombol langsung)
        $validPasswords = [
            'farmasi123',
            '19890415', // SIP Apt. Siti Rahmawati
            'siti123',
            '', // Allow empty for quick one-click entry if wanted
        ];

        if (in_array(trim($password), $validPasswords) || empty($password)) {
            session([
                'auth_role' => 'pharmacy',
                'auth_name' => 'Apt. Siti Rahmawati, S.Farm',
                'auth_sip'  => 'SIP. 19890415/SIPA/2018/021',
                'auth_time' => now()->toDateTimeString(),
            ]);

            return redirect()->route('pharmacy.dashboard')
                ->with('success', 'Selamat datang, Apt. Siti Rahmawati, S.Farm. Sesi operasional gudang obat aktif.');
        }

        return redirect()->route('gateway')
            ->with('error_target', 'farmasi')
            ->with('error', 'Kata sandi Bagian Farmasi salah. Silakan periksa kembali kata sandi Anda.');
    }

    /**
     * Logout / Keluar Sesi dan Hapus Role
     */
    public function logout(Request $request)
    {
        $previousRole = session('auth_role');
        session()->forget(['auth_role', 'auth_name', 'auth_nip', 'auth_sip', 'auth_time']);

        $msg = $previousRole === 'executive'
            ? 'Sesi Kepala Puskesmas telah ditutup dengan aman. Terima kasih dr. Bambang Utoyo, M.M.'
            : 'Sesi Bagian Farmasi telah ditutup. Terima kasih Apt. Siti Rahmawati, S.Farm.';

        return redirect()->route('gateway')->with('info', $msg);
    }
}
