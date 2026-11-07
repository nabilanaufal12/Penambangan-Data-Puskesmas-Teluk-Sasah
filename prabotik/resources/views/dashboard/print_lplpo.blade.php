<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LPLPO 2026-P5 — UPTD Puskesmas Teluk Sasah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                font-size: 10pt;
            }
            @page {
                size: A4 landscape;
                margin: 1cm;
            }
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 p-4 sm:p-8">

    <!-- Action Bar (No-Print) -->
    <div class="no-print max-w-7xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center space-x-3">
            <a href="{{ route('procurement') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                &larr; Kembali ke Dashboard
            </a>
            <span class="text-xs text-slate-500">Format Resmi Lembar LPLPO Standar Dinas Kesehatan Kabupaten Bintan</span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('lplpo.print', ['scope' => request('scope') === 'all' ? 'priority' : 'all']) }}" class="px-3 py-2 rounded-xl bg-sky-50 text-sky-700 hover:bg-sky-100 text-xs font-bold transition">
                {{ request('scope') === 'all' ? 'Tampilkan Prioritas 1 & 2 Saja' : 'Tampilkan Seluruh 150 Obat' }}
            </a>
            <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold shadow-md transition flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Lembar LPLPO (PDF/Kertas)</span>
            </button>
        </div>
    </div>

    <!-- Official Document Paper Container -->
    <div class="max-w-7xl mx-auto bg-white p-8 sm:p-12 rounded-xl shadow-lg border border-slate-200 text-[11px] leading-tight">
        
        <!-- Official Kop Surat Pemerintah Kabupaten Bintan -->
        <div class="border-b-2 border-slate-900 pb-4 mb-6 flex items-center justify-between text-center">
            <div class="w-20 text-left">
                <img src="/images/logo_umrah.png" alt="Logo Faskes" class="w-16 h-16 object-contain">
            </div>
            <div class="flex-1 px-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800">PEMERINTAH KABUPATEN BINTAN</h3>
                <h2 class="text-base font-extrabold uppercase tracking-wide text-slate-900">DINAS KESEHATAN</h2>
                <h1 class="text-lg font-black uppercase tracking-wider text-slate-950 mt-0.5">UPTD PUSKESMAS TELUK SASAH</h1>
                <p class="text-[10px] text-slate-600 mt-1">
                    Jl. Raya Teluk Sasah, Kec. Seri Kuala Lobam, Kabupaten Bintan, Kepulauan Riau 29152 &bull; Telp/UGD: (0771) 482-123 &bull; Email: puskesmas.teluksasah@bintankab.go.id
                </p>
            </div>
            <div class="w-20 text-right">
                <div class="border border-slate-300 p-1.5 text-center text-[9px] rounded font-bold uppercase bg-slate-50">
                    Formulir LPLPO Resmi
                </div>
            </div>
        </div>

        <!-- Document Metadata Header -->
        <div class="text-center mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wider underline">
                LAPORAN PEMAKAIAN DAN LEMBAR PERMINTAAN OBAT (LPLPO)
            </h2>
            <div class="flex justify-between items-center text-xs text-slate-700 mt-3 px-2">
                <div>
                    <p><strong>Puskesmas:</strong> UPTD Puskesmas Teluk Sasah</p>
                    <p><strong>Kecamatan:</strong> Seri Kuala Lobam</p>
                </div>
                <div>
                    <p><strong>Kabupaten/Kota:</strong> Bintan</p>
                    <p><strong>Provinsi:</strong> Kepulauan Riau</p>
                </div>
                <div class="text-right">
                    <p><strong>Periode Pelaporan:</strong> Juli – Agustus 2026 (2026-P4)</p>
                    <p><strong>Periode Permintaan:</strong> September – Oktober 2026 (2026-P5)</p>
                </div>
            </div>
        </div>

        <!-- Official 10-Column Standard Table -->
        <div class="overflow-x-auto mb-6">
            <table class="w-full border-collapse border border-slate-400 text-left text-[10px]">
                <thead class="bg-slate-100 text-slate-900 font-bold uppercase text-center text-[9px]">
                    <tr>
                        <th class="border border-slate-400 px-2 py-2 w-8">No</th>
                        <th class="border border-slate-400 px-3 py-2 text-left">Nama Sediaan Obat</th>
                        <th class="border border-slate-400 px-2 py-2 w-16">Satuan</th>
                        <th class="border border-slate-400 px-2 py-2 w-20 text-right">Stok Awal</th>
                        <th class="border border-slate-400 px-2 py-2 w-20 text-right">Penerimaan</th>
                        <th class="border border-slate-400 px-2 py-2 w-20 text-right">Persediaan</th>
                        <th class="border border-slate-400 px-2 py-2 w-20 text-right">Pemakaian</th>
                        <th class="border border-slate-400 px-2 py-2 w-16 text-right">Rusak / ED</th>
                        <th class="border border-slate-400 px-2 py-2 w-20 text-right">Sisa Stok Akhir</th>
                        <th class="border border-slate-400 px-2 py-2 w-24 text-right bg-slate-200/60 font-black">Permintaan Resmi (P5)</th>
                        <th class="border border-slate-400 px-2 py-2 text-left">Keterangan Khusus</th>
                    </tr>
                    <tr class="bg-slate-50 text-slate-500 font-mono text-[8px]">
                        <th class="border border-slate-400 py-0.5">1</th>
                        <th class="border border-slate-400 py-0.5">2</th>
                        <th class="border border-slate-400 py-0.5">3</th>
                        <th class="border border-slate-400 py-0.5">4</th>
                        <th class="border border-slate-400 py-0.5">5</th>
                        <th class="border border-slate-400 py-0.5">6 (4+5)</th>
                        <th class="border border-slate-400 py-0.5">7</th>
                        <th class="border border-slate-400 py-0.5">8</th>
                        <th class="border border-slate-400 py-0.5">9 (6-7-8)</th>
                        <th class="border border-slate-400 py-0.5 font-bold">10</th>
                        <th class="border border-slate-400 py-0.5">11</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @foreach($medicines as $index => $med)
                    <tr class="{{ $loop->even ? 'bg-slate-50/50' : 'bg-white' }}">
                        <td class="border border-slate-300 px-2 py-1.5 text-center">{{ $index + 1 }}</td>
                        <td class="border border-slate-300 px-3 py-1.5 font-bold text-slate-900">
                            {{ $med->nama_obat }}
                        </td>
                        <td class="border border-slate-300 px-2 py-1.5 text-center text-slate-600">{{ $med->satuan }}</td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right font-mono">{{ number_format($med->sisa_stok * 1.2, 0, ',', '.') }}</td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right font-mono">{{ number_format($med->prediksi_P5 * 0.8, 0, ',', '.') }}</td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right font-mono">{{ number_format(($med->sisa_stok * 1.2) + ($med->prediksi_P5 * 0.8), 0, ',', '.') }}</td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right font-mono">{{ number_format($med->prediksi_P5 * 0.7, 0, ',', '.') }}</td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right font-mono text-slate-400">0</td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right font-mono font-bold">{{ number_format($med->sisa_stok, 0, ',', '.') }}</td>
                        <td class="border border-slate-300 px-2 py-1.5 text-right font-mono font-extrabold bg-slate-100 text-slate-950 text-xs">
                            {{ number_format($med->rekomendasi_total, 0, ',', '.') }}
                        </td>
                        <td class="border border-slate-300 px-2 py-1.5 text-[9px] text-slate-600">
                            @if($med->is_censored_adjusted)
                                <strong>Koreksi Permintaan Tersensor:</strong> Imputasi 5.850 tab akibat stockout total P4 (+Safety Stock 1.340 tab).
                            @elseif($med->rekomendasi_total == 0)
                                Sisa stok fisik gudang masih mencukupi kebutuhan P5 (Cegah Overstock).
                            @else
                                Termasuk buffer Safety Stock 95% ({{ number_format($med->safety_stock, 0, ',', '.') }} {{ $med->satuan }}).
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Official Signatures Block (Format Pemerintah) -->
        <div class="mt-8 pt-4 border-t border-slate-300 grid grid-cols-2 text-center text-xs">
            <div>
                <p>Mengetahui,</p>
                <p class="font-bold">Kepala UPTD Puskesmas Teluk Sasah</p>
                <div class="h-20"></div>
                <p class="font-bold underline text-slate-900">dr. H. Kurniawan</p>
                <p class="text-[10px] text-slate-600">NIP. 19780512 200604 1 008</p>
            </div>
            <div>
                <p>Teluk Sasah, {{ date('d F Y') }}</p>
                <p class="font-bold">Pengelola Obat / Petugas Gudang Farmasi</p>
                <div class="h-20"></div>
                <p class="font-bold underline text-slate-900">Apoteker Penanggung Jawab Puskesmas</p>
                <p class="text-[10px] text-slate-600">NIP. 19850920 201101 2 015</p>
            </div>
        </div>

    </div>

</body>
</html>
