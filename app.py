#!/usr/bin/env python3
"""
================================================================================
PRABOTIK (Sistem Prediksi dan Analisis Kebutuhan Obat)
Aplikasi Web Sistem Pendukung Keputusan Pengadaan Obat Puskesmas
UPTD Puskesmas Teluk Sasah, Kabupaten Bintan, Kepulauan Riau
================================================================================
Bagian dari Penelitian Penambangan Data (Data Mining):
"Prediksi Kebutuhan Stok Obat dan Analisis Tren Penyakit Musiman Berdasarkan 
Pola Pemakaian Obat pada Puskesmas Teluk Sasah di Kabupaten Bintan"
================================================================================
"""

import os
import sys
from flask import Flask, render_template, request, session, redirect, url_for, jsonify, Response

# Pastikan path modul terdeteksi
CURRENT_DIR = os.path.dirname(os.path.abspath(__file__))
if CURRENT_DIR not in sys.path:
    sys.path.insert(0, CURRENT_DIR)

from data_loader import data_engine

app = Flask(
    __name__,
    template_folder=os.path.join(CURRENT_DIR, "templates"),
    static_folder=os.path.join(CURRENT_DIR, "static")
)
app.secret_key = "prabotik-puskesmas-teluk-sasah-bintan-2026"

def get_role():
    """Mengelola peran pengguna: 'admin' (Admin Farmasi) atau 'kepala' (Kepala Puskesmas)"""
    role_arg = request.args.get("role")
    if role_arg in ["admin", "kepala"]:
        session["role"] = role_arg
        return role_arg
    return session.get("role", "admin")

@app.context_processor
def inject_global_vars():
    role = get_role()
    return {
        "current_role": role,
        "kpi": data_engine.get_kpi_summary(),
        "budget_summary": data_engine.get_procurement_budget_summary(),
        "disposition_status": session.get("disposition_status", "Menunggu Persetujuan"),
        "disposition_notes": session.get("disposition_notes", "Alokasikan sesuai prioritas obat vital dan buffer 95%.")
    }

# Route untuk disposisi pimpinan (Kepala Puskesmas)
@app.route("/disposisi", methods=["POST"])
def disposisi():
    role = get_role()
    if role == "kepala":
        action = request.form.get("action", "approve")
        notes = request.form.get("notes", "")
        if action == "approve":
            session["disposition_status"] = "Disetujui & Diteruskan ke Dinkes"
        elif action == "revisi":
            session["disposition_status"] = "Perlu Penyesuaian Kuota"
        session["disposition_notes"] = notes
    return redirect(request.referrer or url_for("rekomendasi_page", role=role))

# Route untuk input data LPLPO cepat (Admin Farmasi)
@app.route("/data/tambah", methods=["POST"])
def data_tambah():
    role = get_role()
    if role == "admin":
        nama_obat = request.form.get("nama_obat", "").strip()
        satuan = request.form.get("satuan", "tablet").strip()
        kategori = request.form.get("kategori", "OBAT").strip()
        pemakaian = float(request.form.get("pemakaian", 0))
        stok_akhir = float(request.form.get("stok_akhir", 0))
        
        if nama_obat:
            session["last_added_drug"] = f"Berhasil menambahkan/memperbarui data sediaan: {nama_obat} ({pemakaian:,.0f} {satuan})"
    return redirect(url_for("data_page", role=role))

# ==============================================================================
# 1. MENU UTAMA: DASHBOARD
# ==============================================================================
@app.route("/")
@app.route("/dashboard")
def dashboard():
    role = get_role()
    kpi = data_engine.get_kpi_summary()
    top_drugs = data_engine.get_top_medicines_usage(5)
    top_diseases = data_engine.get_top_diseases_summary(5)
    disease_trends = data_engine.get_disease_monthly_trends()
    quick_recs = data_engine.get_procurement_recommendation_table()

    return render_template(
        "dashboard.html",
        active_menu="dashboard",
        kpi=kpi,
        top_drugs=top_drugs,
        top_diseases=top_diseases,
        disease_trends=disease_trends,
        quick_recs=quick_recs[:5]
    )

# ==============================================================================
# 2. MENU UTAMA: DATA
# ==============================================================================
@app.route("/data")
def data_page():
    role = get_role()
    kpi = data_engine.get_kpi_summary()
    filter_tahun = request.args.get("tahun", "")

    # Data LPLPO
    df_lplpo = data_engine.df_lplpo
    if filter_tahun and filter_tahun.isdigit():
        df_lplpo_sub = df_lplpo[df_lplpo["tahun"] == int(filter_tahun)]
    else:
        df_lplpo_sub = df_lplpo

    # Tampilkan seluruh rekaman transaksi LPLPO
    lplpo_records = df_lplpo_sub.to_dict(orient="records")
    
    # Data Penyakit & Kadaluarsa
    penyakit_records = data_engine.df_penyakit.to_dict(orient="records")
    ed_records = data_engine.df_kadaluarsa.to_dict(orient="records")

    return render_template(
        "data.html",
        active_menu="data",
        filter_tahun=filter_tahun,
        total_lplpo=len(df_lplpo),
        lplpo_records=lplpo_records,
        total_penyakit=len(penyakit_records),
        penyakit_records=penyakit_records,
        total_ed=len(ed_records),
        ed_records=ed_records
    )

# ==============================================================================
# 3. MENU UTAMA: ANALISIS
# ==============================================================================
@app.route("/analisis")
def analisis_page():
    role = get_role()
    kpi = data_engine.get_kpi_summary()
    
    # Daftar pilihan obat yang aktif di pivot
    available_drugs = sorted(data_engine.df_pivot["nama_obat"].unique().tolist())
    
    # Default obat: Parasetamol tablet 500 mg jika ada, atau obat pertama
    default_med = "Parasetamol tablet 500 mg"
    if default_med not in available_drugs and available_drugs:
        default_med = available_drugs[0]
        
    selected_drug = request.args.get("med", default_med)
    ts_data = data_engine.get_medicine_historical_timeseries(selected_drug)
    
    if ts_data is None:
        selected_drug = default_med
        ts_data = data_engine.get_medicine_historical_timeseries(selected_drug)
        
    is_parasetamol = "parasetamol" in selected_drug.lower()
    ma_res = data_engine.compute_moving_average_models(ts_data["values"], is_paracetamol=is_parasetamol)
    
    # Data musiman dan korelasi
    seasonal_table = data_engine.get_seasonal_index_data()
    seasonal_chart = data_engine.get_seasonal_chart_data()
    correlation_table = data_engine.get_correlations_list()
    benchmark_table = data_engine.get_benchmarks_list()
    stockout_sample = data_engine.get_stockout_analysis_data()

    return render_template(
        "analisis.html",
        active_menu="analisis",
        available_drugs=available_drugs[:60],
        selected_drug=selected_drug,
        ts_data=ts_data,
        ma_res=ma_res,
        seasonal_table=seasonal_table,
        seasonal_chart=seasonal_chart,
        correlation_table=correlation_table,
        benchmark_table=benchmark_table,
        stockout_sample=stockout_sample
    )

# ==============================================================================
# 4. MENU UTAMA: REKOMENDASI PENGADAAN
# ==============================================================================
@app.route("/rekomendasi")
def rekomendasi_page():
    role = get_role()
    rec_list = data_engine.get_procurement_recommendation_table()

    return render_template(
        "rekomendasi.html",
        active_menu="rekomendasi",
        rec_list=rec_list
    )

# ==============================================================================
# 5. MENU UTAMA: LAPORAN
# ==============================================================================
@app.route("/laporan")
def laporan_page():
    role = get_role()
    rec_list = data_engine.get_procurement_recommendation_table()

    return render_template(
        "laporan.html",
        active_menu="laporan",
        rec_list=rec_list
    )

# ==============================================================================
# API ENDPOINT: FORECAST DINAMIS
# ==============================================================================
@app.route("/api/forecast")
def api_forecast():
    med_name = request.args.get("med", "")
    ts_data = data_engine.get_medicine_historical_timeseries(med_name)
    if not ts_data:
        return jsonify({"error": "Obat tidak ditemukan"}), 404
    
    is_parasetamol = "parasetamol" in med_name.lower()
    ma_res = data_engine.compute_moving_average_models(ts_data["values"], is_paracetamol=is_parasetamol)
    return jsonify({
        "nama_obat": ts_data["nama_obat"],
        "satuan": ts_data["satuan"],
        "periods": ts_data["periods"],
        "actual_values": ts_data["values"],
        "forecast": ma_res
    })

# ==============================================================================
# RUNNER
# ==============================================================================
if __name__ == "__main__":
    port = int(os.environ.get("PORT", 5000))
    print(f"[*] Menjalankan PRABOTIK di http://127.0.0.1:{port}")
    app.run(host="0.0.0.0", port=port, debug=False)
