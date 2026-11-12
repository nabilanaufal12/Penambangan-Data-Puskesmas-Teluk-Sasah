"""
PRABOTIK - Data Loader & Analytics Engine
Modul pengolah data dan pemodelan ilmiah berbasis dataset hasil pembersihan (cleaning).
UPTD Puskesmas Teluk Sasah, Kabupaten Bintan.
"""

import os
import pandas as pd
import numpy as np

# Tentukan path dasar repositori
CURRENT_DIR = os.path.dirname(os.path.abspath(__file__))

PATH_STAGE_2 = os.path.join(CURRENT_DIR, "07_Alur_Data_Mentah_ke_Final", "Tahap_02_Pembersihan_dan_Standardisasi")
PATH_STAGE_3 = os.path.join(CURRENT_DIR, "07_Alur_Data_Mentah_ke_Final", "Tahap_03_Tren_1Bulanan_dan_2Bulanan")
PATH_STAGE_4 = os.path.join(CURRENT_DIR, "07_Alur_Data_Mentah_ke_Final", "Tahap_04_Pemodelan_MA_dan_Indikator_MACD")
PATH_STAGE_5 = os.path.join(CURRENT_DIR, "07_Alur_Data_Mentah_ke_Final", "Tahap_05_Korelasi_Obat_dan_Penyakit")
PATH_STAGE_6 = os.path.join(CURRENT_DIR, "07_Alur_Data_Mentah_ke_Final", "Tahap_06_Prediksi_Penyakit_dan_Stok_Obat")
PATH_STAGE_7 = os.path.join(CURRENT_DIR, "07_Alur_Data_Mentah_ke_Final", "Tahap_07_Output_Final_dan_Rekomendasi_Pengadaan")
PATH_STAGE_PROC = os.path.join(CURRENT_DIR, "03_Data_Terproses")

class PrabotikDataEngine:
    _instance = None

    def __new__(cls):
        if cls._instance is None:
            cls._instance = super(PrabotikDataEngine, cls).__new__(cls)
            cls._instance._load_datasets()
        return cls._instance

    def _load_datasets(self):
        # 1. Dataset LPLPO Granular Bersih (15.781 baris)
        f_lplpo = os.path.join(PATH_STAGE_2, "01_lplpo_granular_bersih.csv")
        self.df_lplpo = pd.read_csv(f_lplpo)

        # 2. Dataset Penyakit Terstandarisasi ICD-10 (450 baris)
        f_penyakit = os.path.join(PATH_STAGE_2, "02_penyakit_terstandarisasi_icd10.csv")
        self.df_penyakit = pd.read_csv(f_penyakit)

        # 3. Dataset Obat Kadaluarsa Bersih (138 baris)
        f_kadaluarsa = os.path.join(PATH_STAGE_2, "03_audit_obat_kadaluarsa_bersih.csv")
        self.df_kadaluarsa = pd.read_csv(f_kadaluarsa)

        # 4. Dataset Stockout Nyata (292 obat)
        f_stockout = os.path.join(PATH_STAGE_2, "04_analisis_stockout_nyata_lplpo.csv")
        self.df_stockout = pd.read_csv(f_stockout)

        # 5. Dataset Pivot Pemakaian Obat 22 Periode Kontinu
        f_pivot = os.path.join(PATH_STAGE_3, "03_lplpo_obat_pivot_matriks.csv")
        if os.path.exists(f_pivot):
            self.df_pivot = pd.read_csv(f_pivot)
        else:
            self.df_pivot = pd.read_csv(os.path.join(PATH_STAGE_PROC, "lplpo_obat_pivot_pemakaian.csv"))

        # 6. Dataset Evaluasi Model Moving Average (150 obat aktif)
        f_metrics = os.path.join(PATH_STAGE_4, "01_evaluasi_model_moving_average.csv")
        if os.path.exists(f_metrics):
            self.df_metrics = pd.read_csv(f_metrics)
        else:
            self.df_metrics = pd.read_csv(os.path.join(PATH_STAGE_PROC, "model_evaluation_metrics.csv"))

        # 7. Dataset Korelasi Penyakit dan Obat
        f_korelasi = os.path.join(PATH_STAGE_5, "01_korelasi_penyakit_dan_obat_detail.csv")
        if os.path.exists(f_korelasi):
            self.df_korelasi = pd.read_csv(f_korelasi)
        else:
            self.df_korelasi = pd.read_csv(os.path.join(PATH_STAGE_PROC, "korelasi_penyakit_obat_detail.csv"))

        # 8. Dataset Indeks Musiman 4 Penyakit Utama
        f_musiman = os.path.join(PATH_STAGE_6, "05_indeks_musiman_4_penyakit_utama.csv")
        if os.path.exists(f_musiman):
            self.df_musiman = pd.read_csv(f_musiman)
        else:
            self.df_musiman = pd.DataFrame()

        # 9. Dataset Rekomendasi Prioritas Pengadaan 2026-P5
        f_rekomendasi = os.path.join(PATH_STAGE_7, "01_rekomendasi_prioritas_pengadaan_2026_P5.csv")
        if os.path.exists(f_rekomendasi):
            self.df_rekomendasi = pd.read_csv(f_rekomendasi)
        else:
            self.df_rekomendasi = pd.DataFrame()

        # 10. Dataset Benchmarking ML vs Baseline
        f_benchmark = os.path.join(PATH_STAGE_PROC, "benchmarking_ml_vs_baseline.csv")
        if os.path.exists(f_benchmark):
            self.df_benchmark = pd.read_csv(f_benchmark)
        else:
            self.df_benchmark = pd.DataFrame()

    def get_kpi_summary(self):
        """Menghasilkan KPI utama untuk Dashboard Eksekutif & Admin"""
        total_transaksi = len(self.df_lplpo)
        total_obat_unik = self.df_lplpo["nama_obat"].nunique()
        total_pasien = int(self.df_penyakit["total_pasien"].sum())
        total_pasien_laki = int(self.df_penyakit["pasien_laki"].sum())
        total_pasien_perempuan = int(self.df_penyakit["pasien_perempuan"].sum())
        total_kerugian_ed = float(self.df_kadaluarsa["sub_total_kerugian"].sum())
        total_item_ed = len(self.df_kadaluarsa)
        total_kejadian_stockout = len(self.df_stockout)
        
        # Total pemakaian non-gizi
        df_reguler = self.df_lplpo[~self.df_lplpo["flag_program_gizi"]]
        total_pemakaian_reguler = int(df_reguler["pemakaian"].sum())

        return {
            "total_transaksi": total_transaksi,
            "total_obat_unik": total_obat_unik,
            "total_pasien": total_pasien,
            "total_pasien_laki": total_pasien_laki,
            "total_pasien_perempuan": total_pasien_perempuan,
            "total_kerugian_ed": total_kerugian_ed,
            "total_item_ed": total_item_ed,
            "total_kejadian_stockout": total_kejadian_stockout,
            "total_pemakaian_reguler": total_pemakaian_reguler,
            "periode_awal": "2023-P1",
            "periode_akhir": "2026-P4",
            "periode_prediksi": "2026-P5"
        }

    def get_top_medicines_usage(self, n=10):
        """Daftar n obat teratas berdasarkan akumulasi pemakaian (memisahkan flag program gizi)"""
        # Exclude program gizi untuk urutan obat rawat jalan murni
        df_non_gizi = self.df_lplpo[~self.df_lplpo["flag_program_gizi"]]
        top_drugs = (
            df_non_gizi.groupby(["nama_obat", "satuan", "kategori"])["pemakaian"]
            .sum()
            .reset_index()
            .sort_values(by="pemakaian", ascending=False)
            .head(n)
        )
        return top_drugs.to_dict(orient="records")

    def get_top_diseases_summary(self, n=10):
        """Top diagnosis penyakit kumulatif 2023-2026"""
        top_d = (
            self.df_penyakit.groupby(["kode_icd", "nama_penyakit", "klaster_icd"])[
                ["total_pasien", "pasien_laki", "pasien_perempuan"]
            ]
            .sum()
            .reset_index()
            .sort_values(by="total_pasien", ascending=False)
            .head(n)
        )
        # Hitung persentase
        total_all = self.df_penyakit["total_pasien"].sum()
        top_d["persentase"] = ((top_d["total_pasien"] / total_all) * 100).round(2)
        return top_d.to_dict(orient="records")

    def get_disease_monthly_trends(self):
        """Tren bulanan penyakit 2023 - 2026 untuk 4 penyakit utama"""
        top_codes = ["J00", "I10", "K30", "E11"]
        df_sub = self.df_penyakit[self.df_penyakit["kode_icd"].isin(top_codes)].copy()
        
        # Bentuk label periode YYYY-MM
        df_sub["periode_label"] = df_sub.apply(lambda r: f"{int(r['tahun'])}-{int(r['bulan_angka']):02d}", axis=1)
        
        # Pivot untuk chart
        piv = df_sub.pivot_table(index="periode_label", columns="kode_icd", values="total_pasien", fill_value=0)
        piv = piv.sort_index()

        labels = piv.index.tolist()
        series = {
            "J00": piv["J00"].tolist() if "J00" in piv.columns else [],
            "I10": piv["I10"].tolist() if "I10" in piv.columns else [],
            "K30": piv["K30"].tolist() if "K30" in piv.columns else [],
            "E11": piv["E11"].tolist() if "E11" in piv.columns else []
        }
        return {"labels": labels, "series": series}

    def get_seasonal_index_data(self):
        """Data indeks musiman 12 bulan untuk 4 penyakit utama"""
        if self.df_musiman.empty:
            return []
        return self.df_musiman.to_dict(orient="records")

    def get_seasonal_chart_data(self):
        """Format chart indeks musiman per bulan"""
        if self.df_musiman.empty:
            return {"labels": [], "series": {}}
        
        months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"]
        piv = self.df_musiman.pivot_table(index="bulan_no", columns="kode_icd", values="indeks_musiman")
        piv = piv.sort_index()

        return {
            "labels": months,
            "series": {
                "J00": [float(x) for x in piv["J00"].tolist()] if "J00" in piv.columns else [],
                "I10": [float(x) for x in piv["I10"].tolist()] if "I10" in piv.columns else [],
                "K30": [float(x) for x in piv["K30"].tolist()] if "K30" in piv.columns else [],
                "E11": [float(x) for x in piv["E11"].tolist()] if "E11" in piv.columns else []
            }
        }

    def get_medicine_historical_timeseries(self, nama_obat):
        """Mengambil data deret waktu pemakaian 22 periode dwibulanan untuk 1 obat"""
        periods = [
            "2023-P1", "2023-P2", "2023-P3", "2023-P4", "2023-P5", "2023-P6",
            "2024-P1", "2024-P2", "2024-P3", "2024-P4", "2024-P5", "2024-P6",
            "2025-P1", "2025-P2", "2025-P3", "2025-P4", "2025-P5", "2025-P6",
            "2026-P1", "2026-P2", "2026-P3", "2026-P4"
        ]
        
        row = self.df_pivot[self.df_pivot["nama_obat"].str.strip().str.lower() == nama_obat.strip().lower()]
        if row.empty:
            return None
        
        vals = []
        for p in periods:
            val = float(row[p].values[0]) if p in row.columns else 0.0
            vals.append(val)
        
        return {
            "nama_obat": row["nama_obat"].values[0],
            "satuan": row["satuan"].values[0] if "satuan" in row.columns else "unit",
            "periods": periods,
            "values": vals
        }

    def compute_moving_average_models(self, timeseries_vals, is_paracetamol=False, apply_censored_correction=True):
        """
        Menghitung SMA-2, SMA-3, WMA-3 secara dinamis untuk deret waktu pemakaian.
        Memberikan evaluasi MAPE, MAE, dan prediksi periode berikutnya (t+1).
        Untuk Parasetamol, mendukung koreksi data tersensor (censored demand) akibat total stockout pada 2026-P4.
        """
        y = np.array(timeseries_vals, dtype=float).copy()
        
        uncorrected_pred_sma2 = None
        has_correction = False
        
        if is_paracetamol and apply_censored_correction and len(y) >= 4 and y[-1] == 0.0:
            # Imputasi rata-rata normal sebelum stockout: (5256 + 6444)/2 = 5850
            uncorrected_pred_sma2 = (y[-1] + y[-2]) / 2.0  # 3222
            imputed_val = (y[-2] + y[-3]) / 2.0            # 5850
            y[-1] = imputed_val
            has_correction = True

        n = len(y)
        
        # SMA-2
        pred_sma2 = np.full(n, np.nan)
        for t in range(2, n):
            pred_sma2[t] = (y[t-1] + y[t-2]) / 2.0
        next_sma2 = (y[-1] + y[-2]) / 2.0 if n >= 2 else y[-1]

        # SMA-3
        pred_sma3 = np.full(n, np.nan)
        for t in range(3, n):
            pred_sma3[t] = (y[t-1] + y[t-2] + y[t-3]) / 3.0
        next_sma3 = (y[-1] + y[-2] + y[-3]) / 3.0 if n >= 3 else next_sma2

        # WMA-3 (bobot: w1=1, w2=2, w3=3 -> pembagi 6)
        pred_wma3 = np.full(n, np.nan)
        for t in range(3, n):
            pred_wma3[t] = (1.0 * y[t-3] + 2.0 * y[t-2] + 3.0 * y[t-1]) / 6.0
        next_wma3 = (1.0 * y[-3] + 2.0 * y[-2] + 3.0 * y[-1]) / 6.0 if n >= 3 else next_sma3

        # Evaluasi MAPE pada titik yang valid (aktual > 0)
        def calc_metrics(actual, pred, start_idx):
            act = actual[start_idx:]
            pr = pred[start_idx:]
            mask = act > 0
            if np.sum(mask) == 0:
                return {"mape": 0.0, "mae": 0.0, "rmse": 0.0}
            
            mae = float(np.mean(np.abs(act - pr)))
            rmse = float(np.sqrt(np.mean((act - pr) ** 2)))
            mape = float(np.mean(np.abs((act[mask] - pr[mask]) / act[mask])) * 100.0)
            return {"mape": round(mape, 2), "mae": round(mae, 2), "rmse": round(rmse, 2)}

        m_sma2 = calc_metrics(y, pred_sma2, 2)
        m_sma3 = calc_metrics(y, pred_sma3, 3)
        m_wma3 = calc_metrics(y, pred_wma3, 3)

        # Cari model terbaik berdasarkan MAPE terkecil
        candidates = [
            ("SMA (k=2)", m_sma2["mape"], next_sma2),
            ("SMA (k=3)", m_sma3["mape"], next_sma3),
            ("WMA (k=3)", m_wma3["mape"], next_wma3)
        ]
        candidates.sort(key=lambda x: x[1])
        best_name, best_mape, best_pred = candidates[0]

        return {
            "sma2": {"pred_hist": [None if np.isnan(v) else round(v, 1) for v in pred_sma2], "next": round(next_sma2, 1), "metrics": m_sma2},
            "sma3": {"pred_hist": [None if np.isnan(v) else round(v, 1) for v in pred_sma3], "next": round(next_sma3, 1), "metrics": m_sma3},
            "wma3": {"pred_hist": [None if np.isnan(v) else round(v, 1) for v in pred_wma3], "next": round(next_wma3, 1), "metrics": m_wma3},
            "best_model": best_name,
            "best_mape": best_mape,
            "next_recommendation": round(best_pred, 1),
            "has_correction": has_correction,
            "uncorrected_pred": round(uncorrected_pred_sma2, 1) if uncorrected_pred_sma2 is not None else None
        }

    def get_procurement_recommendation_table(self):
        """Tabel lengkap rekomendasi pengadaan 2026-P5 dengan estimasi anggaran"""
        if self.df_rekomendasi.empty:
            return []
        
        # Ambil median harga satuan dari LPLPO untuk estimasi anggaran
        df_price = self.df_lplpo[self.df_lplpo["harga_satuan"] > 0].groupby("nama_obat")["harga_satuan"].median().to_dict()

        records = self.df_rekomendasi.to_dict(orient="records")
        for r in records:
            # Format visual
            name = r["nama_obat"]
            unit_price = float(df_price.get(name, 150.0))
            qty = float(r["rekomendasi_total_pengadaan"])
            est_cost = qty * unit_price
            
            r["harga_satuan"] = unit_price
            r["estimasi_anggaran"] = est_cost
            r["harga_satuan_fmt"] = f"Rp {unit_price:,.0f}"
            r["estimasi_anggaran_fmt"] = f"Rp {est_cost:,.0f}"
            r["prediksi_fmt"] = f"{r['prediksi_2026_P5']:,.1f}"
            r["safety_stock_fmt"] = f"{r['safety_stock_95']:,.0f}"
            r["total_pengadaan_fmt"] = f"{r['rekomendasi_total_pengadaan']:,.0f}"
            r["mape_fmt"] = f"{r['mape_terbaik_persen']:.1f}%"
        return records

    def get_procurement_budget_summary(self):
        """Total estimasi anggaran pengadaan P5 dan alokasi per prioritas"""
        rec = self.get_procurement_recommendation_table()
        total_anggaran = sum(r["estimasi_anggaran"] for r in rec)
        total_item = len(rec)
        
        # Alokasi per kategori
        p1_anggaran = sum(r["estimasi_anggaran"] for r in rec if "Prioritas 1" in r["prioritas_kategori"])
        p2_anggaran = sum(r["estimasi_anggaran"] for r in rec if "Prioritas 2" in r["prioritas_kategori"])
        p3_anggaran = total_anggaran - (p1_anggaran + p2_anggaran)
        
        return {
            "total_anggaran": total_anggaran,
            "total_anggaran_fmt": f"Rp {total_anggaran:,.0f}",
            "total_item": total_item,
            "p1_anggaran": p1_anggaran,
            "p1_anggaran_fmt": f"Rp {p1_anggaran:,.0f}",
            "p2_anggaran": p2_anggaran,
            "p2_anggaran_fmt": f"Rp {p2_anggaran:,.0f}",
            "p3_anggaran": p3_anggaran,
            "p3_anggaran_fmt": f"Rp {p3_anggaran:,.0f}"
        }

    def get_correlations_list(self):
        """Daftar korelasi penyakit vs obat farmakoterapi preskripsi"""
        if self.df_korelasi.empty:
            return []
        records = self.df_korelasi.to_dict(orient="records")
        for r in records:
            r["pearson_fmt"] = f"{r['pearson_r']:.3f}" if "pearson_r" in r else "-"
            r["spearman_fmt"] = f"{r['spearman_rho']:.3f}" if "spearman_rho" in r else "-"
            r["rasio_fmt"] = f"{r['rasio_dosis_per_pasien']:.2f}" if "rasio_dosis_per_pasien" in r else "-"
        return records

    def get_expired_summary_data(self):
        """Ringkasan audit obat kedaluwarsa"""
        total_nominal = float(self.df_kadaluarsa["sub_total_kerugian"].sum())
        by_year = (
            self.df_kadaluarsa.groupby("tahun_lapor")["sub_total_kerugian"]
            .agg(["count", "sum"])
            .reset_index()
            .to_dict(orient="records")
        )
        top_loss = (
            self.df_kadaluarsa.sort_values(by="sub_total_kerugian", ascending=False)
            .head(10)
            .to_dict(orient="records")
        )
        return {
            "total_nominal": total_nominal,
            "by_year": by_year,
            "top_loss": top_loss
        }

    def get_stockout_analysis_data(self):
        """Data kejadian kekosongan obat fisik (stockout nyata)"""
        top_so = self.df_stockout.sort_values(by="jumlah_periode_stockout", ascending=False).head(15)
        return top_so.to_dict(orient="records")

    def get_benchmarks_list(self):
        """Daftar hasil benchmarking machine learning vs baseline statistik"""
        if self.df_benchmark.empty:
            return []
        return self.df_benchmark.to_dict(orient="records")

# Inisialisasi singleton
data_engine = PrabotikDataEngine()
