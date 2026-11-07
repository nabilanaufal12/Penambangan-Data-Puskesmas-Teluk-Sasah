<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\DiseaseRecord;
use App\Models\ExpiredMedicine;
use App\Models\ClinicalCorrelation;
use App\Models\MlBenchmark;
use Illuminate\Support\Facades\DB;

class ExecutiveController extends Controller
{
    public function index()
    {
        $totalObat = Medicine::count();
        $obatTersedia = Medicine::where('sisa_stok', '>', 0)->count();
        $ketersediaanPct = round(($obatTersedia / max(1, $totalObat)) * 100, 1);

        $totalPasien = DiseaseRecord::sum('total_pasien');
        $totalEdLoss = ExpiredMedicine::sum('sub_total_kerugian');
        $totalOrderUnit = Medicine::sum('rekomendasi_total');
        $estimasiAnggaran = $totalOrderUnit * 1350;
        $isApproved = session('p5_procurement_approved', false);

        $kpis = [
            'ketersediaan_pct' => $ketersediaanPct,
            'obat_tersedia' => $obatTersedia,
            'total_obat' => $totalObat,
            'total_pasien' => $totalPasien,
            'total_ed_loss' => $totalEdLoss,
            'total_order_unit' => $totalOrderUnit,
            'estimasi_anggaran' => $estimasiAnggaran,
            'rasio_peresepan' => '94.6%',
            'periode_aktif' => '2026-P5 (Sep-Okt)',
            'is_approved' => $isApproved,
            'status_disposisi' => $isApproved ? 'Telah Disahkan Kepala Puskesmas' : 'Menunggu Pengesahan Akhir',
            'kapus_nama' => 'dr. Bambang Utoyo, M.M.',
            'kapus_nip' => 'NIP. 19780512 200604 1 008',
            'pengelola_nama' => 'Apt. Siti Rahmawati, S.Farm',
            'pengelola_sip' => 'SIP. 19890415/SIPA/2018/021',
        ];

        $topDiseases = DiseaseRecord::select('kode_icd', 'nama_penyakit', DB::raw('sum(total_pasien) as total_pasien'))
            ->groupBy('kode_icd', 'nama_penyakit')
            ->orderByDesc('total_pasien')
            ->take(5)
            ->get()
            ->map(function ($item) use ($totalPasien) {
                $item->persentase = round(($item->total_pasien / max(1, $totalPasien)) * 100, 1);
                return $item;
            });

        $edLossByYear = ExpiredMedicine::select('tahun', DB::raw('sum(sub_total_kerugian) as total_loss'), DB::raw('count(*) as count'))
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        $topProcurement = Medicine::orderByDesc('rekomendasi_total')->take(6)->get();
        $correlations = ClinicalCorrelation::take(5)->get();

        return view('executive.index', compact(
            'kpis', 'topDiseases', 'edLossByYear', 'topProcurement', 'correlations'
        ));
    }

    public function disposition()
    {
        $topProcurement = Medicine::orderByDesc('rekomendasi_total')->take(10)->get();
        $allProcurement = Medicine::where('rekomendasi_total', '>', 0)->orderByDesc('rekomendasi_total')->paginate(20);
        $totalOrderUnit = Medicine::sum('rekomendasi_total');
        $estimasiAnggaran = $totalOrderUnit * 1350;
        $isApproved = session('p5_procurement_approved', false);

        return view('executive.disposition', compact(
            'topProcurement', 'allProcurement', 'totalOrderUnit', 'estimasiAnggaran', 'isApproved'
        ));
    }

    public function approveProcurement(Request $request)
    {
        session(['p5_procurement_approved' => true]);
        return redirect()->back()->with('success', 'Usulan Rencana Kebutuhan Obat P5 (2026) BERHASIL DISAHKAN oleh Kepala UPTD Puskesmas Teluk Sasah (dr. Bambang Utoyo, M.M. - NIP. 19780512 200604 1 008). Dokumen resmi siap dikirim ke Dinas Kesehatan Kabupaten Bintan.');
    }

    public function diseases()
    {
        $totalPasien = DiseaseRecord::sum('total_pasien');
        $topDiseases = DiseaseRecord::select('kode_icd', 'nama_penyakit', 'klaster_icd', DB::raw('sum(total_pasien) as total_pasien'), DB::raw('sum(pasien_laki) as laki'), DB::raw('sum(pasien_perempuan) as perempuan'))
            ->groupBy('kode_icd', 'nama_penyakit', 'klaster_icd')
            ->orderByDesc('total_pasien')
            ->take(10)
            ->get()
            ->map(function ($item) use ($totalPasien) {
                $item->persentase = round(($item->total_pasien / max(1, $totalPasien)) * 100, 1);
                return $item;
            });

        $recentRecords = DiseaseRecord::orderByDesc('tahun')->orderByDesc('bulan_angka')->paginate(15);

        return view('executive.diseases', compact('topDiseases', 'totalPasien', 'recentRecords'));
    }

    public function financialLoss()
    {
        $totalLoss = ExpiredMedicine::sum('sub_total_kerugian');
        $totalItems = ExpiredMedicine::count();
        $yearlyLoss = ExpiredMedicine::select('tahun', DB::raw('sum(sub_total_kerugian) as total'), DB::raw('count(*) as count'))
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        $budgetSources = ExpiredMedicine::select('sumber_anggaran', DB::raw('sum(sub_total_kerugian) as total'), DB::raw('count(*) as count'))
            ->groupBy('sumber_anggaran')
            ->get();

        $expiredList = ExpiredMedicine::orderByDesc('tahun')->orderByDesc('sub_total_kerugian')->paginate(15);

        return view('executive.financial', compact('totalLoss', 'totalItems', 'yearlyLoss', 'budgetSources', 'expiredList'));
    }

    public function correlations()
    {
        $correlations = ClinicalCorrelation::all();
        $benchmarks = MlBenchmark::all();

        return view('executive.correlations', compact('correlations', 'benchmarks'));
    }

    public function printApprovedLplpo()
    {
        $medicines = Medicine::orderBy('nama_obat')->get();
        return view('dashboard.print_lplpo', compact('medicines'));
    }

    public function profile()
    {
        return view('executive.profile');
    }
}
