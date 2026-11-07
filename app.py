#!/usr/bin/env python3
"""
================================================================================
PharmaCare Sasah — Web Dashboard Penambangan Data Logistik & Peramalan Farmasi
UPTD Puskesmas Teluk Sasah, Kabupaten Bintan, Kepulauan Riau
================================================================================
Pengembang : Kelompok 23 - Penambangan Data (INF11121)
Ketua Tim  : Nabil (NIM: 2401020073)
Dosen      : Tekad Matulatan, S.T., M.Kom. & Muhamad Fadli, S.T., M.Kom.
Program Studi S-1 Teknik Informatika, Fakultas Teknik & Teknologi Kemaritiman
Universitas Maritim Raja Ali Haji (UMRAH) - 2026
================================================================================
"""

import os
import pandas as pd
import numpy as np
import streamlit as st

try:
    import plotly.graph_objects as go
    import plotly.express as px
    HAS_PLOTLY = True
except ImportError:
    HAS_PLOTLY = False
    import matplotlib.pyplot as plt

# ==============================================================================
# 1. KONFIGURASI HALAMAN & TEMA MEDIS
# ==============================================================================
st.set_page_config(
    page_title="PharmaCare Sasah — Logistik Obat & Peramalan Penyakit",
    page_icon="💊",
    layout="wide",
    initial_sidebar_state="expanded"
)

st.markdown("""
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700&display=swap');
    
    html, body, [class*="css"] {
        font-family: 'Inter', sans-serif;
        color: #0F172A;
    }
    
    .hero-container {
        background: linear-gradient(135deg, #0369A1 0%, #0284C7 50%, #0EA5E9 100%);
        border-radius: 20px;
        padding: 30px 36px;
        color: white;
        margin-bottom: 22px;
        box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.25);
    }
    
    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        padding: 6px 16px;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .hero-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2.1rem;
        font-weight: 700;
        margin: 0 0 8px 0;
        line-height: 1.25;
    }
    
    .hero-subtitle {
        font-size: 1.02rem;
        opacity: 0.95;
        max-width: 900px;
        line-height: 1.55;
        margin-bottom: 0;
    }
    
    .med-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -2px rgba(0, 0, 0, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .med-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
    }
    
    .med-card-val {
        font-family: 'Poppins', sans-serif;
        font-size: 1.7rem;
        font-weight: 700;
        color: #0369A1;
        margin: 4px 0;
    }
    
    .med-card-lbl {
        font-size: 0.85rem;
        color: #64748B;
        font-weight: 500;
    }
    
    .alert-card-green {
        background-color: #ECFDF5;
        border-left: 5px solid #10B981;
        padding: 14px 18px;
        border-radius: 10px;
        margin: 10px 0;
        color: #065F46;
    }
    
    .alert-card-blue {
        background-color: #EFF6FF;
        border-left: 5px solid #3B82F6;
        padding: 14px 18px;
        border-radius: 10px;
        margin: 10px 0;
        color: #1E40AF;
    }
    
    .alert-card-amber {
        background-color: #FFFBEB;
        border-left: 5px solid #F59E0B;
        padding: 14px 18px;
        border-radius: 10px;
        margin: 10px 0;
        color: #92400E;
    }
    
    .alert-card-red {
        background-color: #FEF2F2;
        border-left: 5px solid #EF4444;
        padding: 14px 18px;
        border-radius: 10px;
        margin: 10px 0;
        color: #991B1B;
    }
    
    .footer-bar {
        text-align: center;
        padding: 24px 0;
        margin-top: 40px;
        border-top: 1px solid #E2E8F0;
        color: #64748B;
        font-size: 0.85rem;
    }
</style>
""", unsafe_allow_html=True)

# ==============================================================================
# 2. PEMUATAN DATASET HISTORIS
# ==============================================================================
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
DATA_DIR = os.path.join(BASE_DIR, '03_Data_Terproses')

@st.cache_data
def load_all_datasets():
    try:
        df_ts = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_bimonthly_timeseries.csv'))
        df_pivot = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_obat_pivot_pemakaian.csv'))
        df_eval = pd.read_csv(os.path.join(DATA_DIR, 'model_evaluation_metrics.csv'))
        df_exp = pd.read_csv(os.path.join(DATA_DIR, 'obat_kadaluarsa_clean.csv'))
        df_dis = pd.read_csv(os.path.join(DATA_DIR, 'penyakit_clean_records.csv'))
        df_dis_fc = pd.read_csv(os.path.join(DATA_DIR, 'prediksi_penyakit_top4.csv'))
        df_corr = pd.read_csv(os.path.join(DATA_DIR, 'korelasi_penyakit_obat_detail.csv'))
        df_monthly24 = pd.read_csv(os.path.join(DATA_DIR, 'tren_bulanan_2024_obat.csv'))
        df_macd = pd.read_csv(os.path.join(DATA_DIR, 'macd_obat_analysis.csv'))
        raw29_path = os.path.join(DATA_DIR, 'lplpo_29_periode_mentah_as_is.csv')
        df_raw29 = pd.read_csv(raw29_path) if os.path.exists(raw29_path) else None
        return df_ts, df_pivot, df_eval, df_exp, df_dis, df_dis_fc, df_corr, df_monthly24, df_macd, df_raw29
    except Exception as e:
        st.error(f"Gagal memuat dataset: {e}")
        return None, None, None, None, None, None, None, None, None, None

df_ts, df_pivot, df_eval, df_exp, df_dis, df_dis_fc, df_corr, df_monthly24, df_macd, df_raw29 = load_all_datasets()

# ==============================================================================
# 3. SIDEBAR NAVIGASI
# ==============================================================================
with st.sidebar:
    st.markdown("### 🏥 **PharmaCare Sasah**")
    st.caption("Sistem Intelijen Logistik Obat & Peramalan Farmasi")
    
    st.markdown("---")
    menu = st.radio(
        "Pilih Modul Navigasi:",
        [
            "🏠 Beranda & Profil Puskesmas",
            "📅 Tren 1-Bulanan (2024) vs 2-Bulanan",
            "🩺 Peramalan 4 Penyakit Terbanyak",
            "🔗 Korelasi & Prediksi Obat-Penyakit",
            "📈 Simulasi Peramalan & Indikator MACD",
            "📦 Rekomendasi Pengadaan 2026-P5",
            "👥 Tim Peneliti (Kelompok 23)"
        ],
        index=0
    )
    
    st.markdown("---")
    st.markdown("#### 🎯 **Fokus Rekomendasi Dosen**")
    st.markdown("""
    * **Saran 1**: Analisis tren bulanan vs dwibulanan.
    * **Saran 2**: Pengujian MA dan indikator MACD.
    * **Saran 3**: Prediksi 4 penyakit dan korelasi obat-penyakit.
    """)
    
    st.markdown("---")
    st.markdown("""
    * **Mitra**: UPTD Puskesmas Teluk Sasah
    * **PIC Farmasi**: Ibu Rahmawati, S.Farm., Apt.
    * **NIM Ketua**: 2401020073 (Nabil)
    """)

# ==============================================================================
# MODUL 1: BERANDA & PROFIL PUSKESMAS
# ==============================================================================
if menu == "🏠 Beranda & Profil Puskesmas":
    st.markdown("""
    <div class="hero-container">
        <span class="hero-badge">UPTD PUSKESMAS TELUK SASAH • KABUPATEN BINTAN</span>
        <h1 class="hero-title">Sistem Intelijen Logistik Obat & Peramalan Penyakit</h1>
        <p class="hero-subtitle">
            Platform komputasi penambangan data untuk peramalan kebutuhan obat dan penyakit musiman, 
            dilengkapi analisis korelasi klinis, indikator momentum MACD, serta formula Safety Stock 95% 
            guna menjamin ketersediaan obat bagi masyarakat pesisir Teluk Sasah.
        </p>
    </div>
    """, unsafe_allow_html=True)
    
    c1, c2, c3, c4 = st.columns(4)
    with c1:
        st.markdown("""
        <div class="med-card">
            <div style="font-size:1.8rem;">📂</div>
            <div class="med-card-lbl">Basis Data Terintegrasi</div>
            <div class="med-card-val">29 Berkas</div>
            <div style="font-size: 0.8rem; color: #059669;">✔ LPLPO Resmi 2023–2026</div>
        </div>
        """, unsafe_allow_html=True)
    with c2:
        st.markdown("""
        <div class="med-card">
            <div style="font-size:1.8rem;">⏱️</div>
            <div class="med-card-lbl">Deret Waktu Kontinu</div>
            <div class="med-card-val">22 Periode</div>
            <div style="font-size: 0.8rem; color: #059669;">✔ 2023-P1 s/d 2026-P4</div>
        </div>
        """, unsafe_allow_html=True)
    with c3:
        st.markdown("""
        <div class="med-card">
            <div style="font-size:1.8rem;">🩺</div>
            <div class="med-card-lbl">Prediksi Penyakit Top 4</div>
            <div class="med-card-val">MAPE 8.4%</div>
            <div style="font-size: 0.8rem; color: #059669;">✔ ISPA, Hipertensi, Maag, DM</div>
        </div>
        """, unsafe_allow_html=True)
    with c4:
        st.markdown("""
        <div class="med-card">
            <div style="font-size:1.8rem;">💰</div>
            <div class="med-card-lbl">Kerugian ED Tercegah</div>
            <div class="med-card-val">Rp 43,3 Juta</div>
            <div style="font-size: 0.8rem; color: #EF4444;">138 Item Obat Kadaluarsa</div>
        </div>
        """, unsafe_allow_html=True)

    st.markdown("### 🌟 **Tiga Fitur Kunci Tindak Lanjut Konsultasi Dosen**")
    f1, f2, f3 = st.columns(3)
    with f1:
        st.markdown("""
        <div class="med-card">
            <h4>1. Visualisasi Tren Bulanan vs 2-Bulanan</h4>
            <p style="color: #475569; font-size: 0.9rem;">
                Menyajikan perbandingan resolusi temporal antara data granular 12 bulan (tahun 2024) 
                dengan deret agregat 22 periode dwibulanan, mengungkap dinamika bulanan yang tersembunyi.
            </p>
        </div>
        """, unsafe_allow_html=True)
    with f2:
        st.markdown("""
        <div class="med-card">
            <h4>2. Indikator MACD (Momentum Tren Obat)</h4>
            <p style="color: #475569; font-size: 0.9rem;">
                Menerapkan <i>Moving Average Convergence Divergence</i> untuk mendeteksi sinyal dini 
                kenaikan tajam permintaan obat (bullish) atau penurunan konsumsi (bearish) guna mencegah overstock.
            </p>
        </div>
        """, unsafe_allow_html=True)
    with f3:
        st.markdown("""
        <div class="med-card">
            <h4>3. Prediksi Terpadu 4 Penyakit & Obat</h4>
            <p style="color: #475569; font-size: 0.9rem;">
                Meramalkan tren 4 penyakit terbanyak (ISPA, Hipertensi, Dispepsia, Diabetes) dan menghubungkannya 
                secara kuantitatif dengan estimasi kebutuhan obat peresepan pasien.
            </p>
        </div>
        """, unsafe_allow_html=True)

    st.markdown("---")
    st.markdown("### 🧹 **Hasil Tahap 2: Pembersihan & Normalisasi Data (Data Cleaning & Normalization)**")
    st.caption("Ringkasan audit reduksi baris noise, standardisasi satuan farmasi, pemisahan anomali program gizi, dan pemetaan ICD-10 WHO.")
    
    clean_img_path = os.path.join(BASE_DIR, '05_Visualisasi_Grafik', '20_hasil_tahap2_pembersihan_dan_standardisasi.png')
    if os.path.exists(clean_img_path):
        st.image(
            clean_img_path, 
            caption="Infografis Akademik: Hasil Audit Data Cleaning, Normalisasi Satuan, Pemisahan Anomali Gizi, dan Standarisasi ICD-10", 
            use_container_width=True
        )
        
    with st.expander("🔍 Lihat Rincian Angka & Metrik Kunci Hasil Tahap 2", expanded=False):
        c_res1, c_res2, c_res3, c_res4 = st.columns(4)
        with c_res1:
            st.metric("Transaksi LPLPO Bersih", "15.781 Baris", "99.8% Valid (32 noise dibuang)")
        with c_res2:
            st.metric("Diagnosis Standar ICD-10", "450 Rekaman", "100% Terpetakan WHO")
        with c_res3:
            st.metric("Obat Kadaluarsa Murni", "138 Item", "Terverifikasi Audit Fisik")
        with c_res4:
            st.metric("Total Kerugian ED", "Rp 43,35 Juta", "Lookup Harga Presisi")

# ==============================================================================
# MODUL 2: TREN 1-BULANAN (2024) VS 2-BULANAN (SARAN DOSEN #1)
# ==============================================================================
elif menu == "📅 Tren 1-Bulanan (2024) vs 2-Bulanan":
    st.markdown("## 📅 **Analisis Tren Sesuai Data Mentah: 1-Bulanan & 2-Bulanan**")
    st.caption("Implementasi Arahan Pak Tekad: Menampilkan tren alami apa adanya sesuai berkas LPLPO asli (1-bulanan & 2-bulanan berdampingan tanpa penggabungan paksa).")
    
    st.markdown("""
    <div class="alert-card-blue">
        <b>💡 Konsep Arahan Dosen</b>: Di data mentah UPTD Puskesmas Teluk Sasah terdapat <b>29 berkas laporan LPLPO</b>:
        <ul style="margin-bottom:0; padding-left:20px;">
            <li><b>Laporan 1-Bulanan (14 Berkas)</b>: Januari & Februari 2023, serta 12 bulan penuh tahun 2024.</li>
            <li><b>Laporan 2-Bulanan (15 Berkas)</b>: Maret–Desember 2023 (5 periode), tahun 2025 (6 periode), dan Januari–Agustus 2026 (4 periode).</li>
        </ul>
        Dengan menampilkan data mentah secara berurutan, kita dapat melihat tren alami yang sebenarnya tanpa manipulasi asumsi.
    </div>
    """, unsafe_allow_html=True)
    
    tab_raw, tab_compare, tab_table = st.tabs([
        "📈 Tren Kronologis 29 Laporan Asli (1-Bulan & 2-Bulan As-Is)",
        "📊 Komparasi Sampingan Bulanan vs Dwibulanan (2024)",
        "📋 Matriks Lengkap Data Mentah 29 Titik"
    ])
    
    # --------------------------------------------------------------------------
    # TAB 1: TREN KRONOLOGIS 29 LAPORAN ASLI
    # --------------------------------------------------------------------------
    with tab_raw:
        if df_raw29 is not None:
            # Pilihan Obat & Opsi Tampilan
            med_options = df_raw29['nama_obat'].tolist()
            c_sel1, c_sel2, c_sel3 = st.columns([2, 1.2, 1.5])
            with c_sel1:
                sel_med_raw = st.selectbox("Pilih Nama Obat untuk Dilihat Kurva Tren Data Mentahnya:", med_options, index=1 if len(med_options) > 1 else 0)
            with c_sel2:
                mode_view = st.radio(
                    "Filter Rentang Titik:",
                    ["Semua 29 Laporan As-Is", "Hanya 1-Bulanan (14 Titik)", "Hanya 2-Bulanan (15 Titik)"],
                    horizontal=False
                )
            with c_sel3:
                chart_style = st.radio(
                    "Model Grafik:",
                    ["📈 Line Chart Curve (Kurva Halus)", "🌊 Area Curve (Kurva Gradien)", "📊 Kombinasi Bar & Curve"],
                    horizontal=False
                )
                
            row_raw = df_raw29[df_raw29['nama_obat'] == sel_med_raw].iloc[0]
            raw_cols = [c for c in df_raw29.columns if c not in ['nama_obat', 'satuan', 'total_pemakaian_mentah']]
            
            # Filter berdasarkan pilihan mode_view
            if mode_view == "Hanya 1-Bulanan (14 Titik)":
                filtered_cols = [c for c in raw_cols if '(1B)' in c]
            elif mode_view == "Hanya 2-Bulanan (15 Titik)":
                filtered_cols = [c for c in raw_cols if '(2B)' in c]
            else:
                filtered_cols = raw_cols
                
            x_labels = filtered_cols
            y_values = [float(row_raw[c]) for c in filtered_cols]
            bar_colors = ['#0284C7' if '(1B)' in c else '#10B981' for c in filtered_cols]
            marker_colors = ['#0284C7' if '(1B)' in c else '#10B981' for c in filtered_cols]
            types_label = ['Laporan 1-Bulan' if '(1B)' in c else 'Laporan 2-Bulan' for c in filtered_cols]
            
            if HAS_PLOTLY:
                fig_raw = go.Figure()
                
                # Jika memilih kombinasi bar
                if chart_style == "📊 Kombinasi Bar & Curve":
                    fig_raw.add_trace(go.Bar(
                        x=x_labels,
                        y=y_values,
                        marker=dict(color=bar_colors, line=dict(color='#0F172A', width=1)),
                        name='Kuantitas Laporan Asli',
                        text=[f"{int(v):,}" if v > 0 else "" for v in y_values],
                        textposition='outside',
                        customdata=types_label,
                        hovertemplate="<b>Periode</b>: %{x}<br><b>Format</b>: %{customdata}<br><b>Pemakaian</b>: %{y:,} unit<extra></extra>"
                    ))
                
                # Tentukan fill area jika memilih Area Curve
                fill_mode = 'tozeroy' if chart_style in ["🌊 Area Curve (Kurva Gradien)", "📈 Line Chart Curve (Kurva Halus)"] else None
                fill_color = 'rgba(2, 132, 199, 0.15)' if chart_style == "🌊 Area Curve (Kurva Gradien)" else 'rgba(2, 132, 199, 0.08)'
                
                # UTAMA: LINE CHART CURVE (SPLINE SMOOTHING TINGGI)
                fig_raw.add_trace(go.Scatter(
                    x=x_labels,
                    y=y_values,
                    mode='lines+markers+text',
                    name='Line Chart Curve (Kurva Tren Alami)',
                    line=dict(color='#0284C7', width=3.8, shape='spline', smoothing=1.3),
                    marker=dict(
                        size=10,
                        color=marker_colors,
                        line=dict(color='#FFFFFF', width=2),
                        symbol='circle'
                    ),
                    text=[f"{int(v):,}" if (v > 0 and (idx % 2 == 0 or v == max(y_values))) else "" for idx, v in enumerate(y_values)],
                    textposition='top center',
                    textfont=dict(size=9, color='#1E293B'),
                    fill=fill_mode,
                    fillcolor=fill_color,
                    customdata=types_label,
                    hovertemplate="<b>Periode</b>: %{x}<br><b>Format</b>: %{customdata}<br><b>Kuantitas Pemakaian</b>: <b>%{y:,}</b> unit<extra></extra>"
                ))
                
                # Tambahkan legenda dummy untuk pembeda 1-Bulan vs 2-Bulan
                fig_raw.add_trace(go.Scatter(
                    x=[None], y=[None],
                    mode='markers',
                    name='Titik Laporan 1-Bulanan (14 Berkas)',
                    marker=dict(size=10, color='#0284C7', line=dict(color='#FFFFFF', width=2))
                ))
                fig_raw.add_trace(go.Scatter(
                    x=[None], y=[None],
                    mode='markers',
                    name='Titik Laporan 2-Bulanan (15 Berkas)',
                    marker=dict(size=10, color='#10B981', line=dict(color='#FFFFFF', width=2))
                ))
                
                fig_raw.update_layout(
                    title=dict(
                        text=f"<b>Line Chart Curve Tren Kronologis: {sel_med_raw} ({row_raw['satuan']})</b><br><span style='font-size:12px; color:#64748B;'>Berdasarkan 29 Berkas LPLPO Asli Puskesmas Teluk Sasah (Tanpa Garis Siku / Menggunakan Smooth Spline)</span>",
                        x=0.01, y=0.96
                    ),
                    height=560,
                    xaxis=dict(
                        title="<b>Kronologi 29 Berkas LPLPO Asli</b> (1B = Laporan 1-Bulan, 2B = Laporan 2-Bulan)",
                        tickangle=-45,
                        gridcolor='#E2E8F0',
                        showgrid=True
                    ),
                    yaxis=dict(
                        title="<b>Kuantitas Pemakaian (Butir / Tablet)</b>",
                        gridcolor='#E2E8F0',
                        showgrid=True
                    ),
                    legend=dict(orientation="h", yanchor="bottom", y=1.02, xanchor="right", x=1),
                    margin=dict(l=20, r=20, t=85, b=110),
                    plot_bgcolor='#FFFFFF',
                    paper_bgcolor='#FFFFFF'
                )
                st.plotly_chart(fig_raw, use_container_width=True)
                
            # Penjelasan Interaktif
            c_inf1, c_inf2, c_inf3 = st.columns(3)
            with c_inf1:
                st.info(f"🟦 **Laporan 1-Bulanan (Biru)**: Rata-rata pemakaian bulanan mencerminkan konsumsi murni jangka pendek.")
            with c_inf2:
                st.success(f"🟩 **Laporan 2-Bulanan (Hijau)**: Nilai kuantitas adalah akumulasi dua bulan berjalan.")
            with c_inf3:
                tot_val = sum(y_values)
                st.metric("Total Kuantitas Terpilih", f"{int(tot_val):,} {row_raw['satuan']}")
        else:
            st.warning("Dataset 29 periode mentah belum dimuat.")
            
    # --------------------------------------------------------------------------
    # TAB 2: KOMPARASI BULANAN VS DWIBULANAN (2024)
    # --------------------------------------------------------------------------
    with tab_compare:
        if df_monthly24 is not None and df_pivot is not None:
            med_list = df_monthly24['nama_obat'].head(25).tolist()
            sel_med = st.selectbox("Pilih Obat untuk Membandingkan Format Bulanan vs Dwibulanan:", med_list, index=0)
            
            c_m1, c_m2 = st.columns(2)
            
            # 1. Tren 1 Bulanan 2024
            with c_m1:
                st.markdown("#### **Perspektif 1: Kurva Tren Bulanan 2024 (12 Bulan)**")
                row_24 = df_monthly24[df_monthly24['nama_obat'] == sel_med].iloc[0]
                m_cols = [c for c in df_monthly24.columns if c.startswith('2024-')]
                m_vals = [float(row_24[c]) for c in m_cols]
                m_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']
                
                if HAS_PLOTLY:
                    fig_m = go.Figure()
                    fig_m.add_trace(go.Scatter(
                        x=m_labels, 
                        y=m_vals, 
                        mode='lines+markers+text', 
                        name='Line Chart Curve (Bulanan)', 
                        line=dict(color='#0284C7', width=3.5, shape='spline', smoothing=1.3),
                        marker=dict(size=9, color='#0369A1', line=dict(color='#FFFFFF', width=1.5)),
                        fill='tozeroy',
                        fillcolor='rgba(2, 132, 199, 0.12)',
                        text=[f"{int(v):,}" if v > 0 else "" for v in m_vals],
                        textposition='top center',
                        textfont=dict(size=8.5)
                    ))
                    fig_m.update_layout(
                        title="<b>Kurva Tren 12 Bulan Penuh (2024)</b>",
                        height=420, 
                        xaxis_title="Bulan (Tahun 2024)", 
                        yaxis_title="Kuantitas (Butir)", 
                        margin=dict(l=10, r=10, t=50, b=30),
                        plot_bgcolor='#FFFFFF',
                        paper_bgcolor='#FFFFFF'
                    )
                    st.plotly_chart(fig_m, use_container_width=True)
                    
            # 2. Tren Dwibulanan 2024 (6 Periode)
            with c_m2:
                st.markdown("#### **Perspektif 2: Kurva Tren Dwibulanan 2024 (6 Periode)**")
                row_bi = df_pivot[df_pivot['nama_obat'] == sel_med]
                if not row_bi.empty:
                    bi_cols = [f'2024-P{i}' for i in range(1, 7)]
                    bi_vals = [float(row_bi.iloc[0][c]) for c in bi_cols]
                    bi_labels = ['P1 (Jan-Feb)', 'P2 (Mar-Apr)', 'P3 (Mei-Jun)', 'P4 (Jul-Ags)', 'P5 (Sep-Okt)', 'P6 (Nov-Des)']
                    
                    if HAS_PLOTLY:
                        fig_bi = go.Figure()
                        fig_bi.add_trace(go.Scatter(
                            x=bi_labels, 
                            y=bi_vals, 
                            mode='lines+markers+text', 
                            name='Line Chart Curve (Dwibulanan)', 
                            line=dict(color='#10B981', width=3.5, shape='spline', smoothing=1.3),
                            marker=dict(size=10, color='#047857', line=dict(color='#FFFFFF', width=2)),
                            fill='tozeroy',
                            fillcolor='rgba(16, 185, 129, 0.12)',
                            text=[f"{int(v):,}" if v > 0 else "" for v in bi_vals],
                            textposition='top center',
                            textfont=dict(size=9, color='#064E3B')
                        ))
                        fig_bi.update_layout(
                            title="<b>Kurva Tren Dwibulanan 2024 (6 Periode)</b>",
                            height=420, 
                            xaxis_title="Periode Dwibulanan 2024", 
                            yaxis_title="Kuantitas (Butir)", 
                            margin=dict(l=10, r=10, t=50, b=30),
                            plot_bgcolor='#FFFFFF',
                            paper_bgcolor='#FFFFFF'
                        )
                        st.plotly_chart(fig_bi, use_container_width=True)
                        
            st.markdown(f"""
            > **Analisis Dosen**: Pada data bulanan terlihat dinamika mikro internal di dalam satu periode (misal: konsumsi Januari vs Februari tidak selalu 50:50). 
            > Namun penggabungan dwibulanan membantu meredam lonjakan semu yang disebabkan oleh penundaan pencatatan faktur farmasi.
            """)

    # --------------------------------------------------------------------------
    # TAB 3: TABEL MATRIKS LENGKAP 29 TITIK DATA MENTAH
    # --------------------------------------------------------------------------
    with tab_table:
        if df_raw29 is not None:
            st.markdown("#### **Tabel Matriks Konsumsi 29 Laporan Asli LPLPO (Tahun 2023–2026)**")
            search_med = st.text_input("Cari Nama Obat pada Data Mentah:", "")
            view_tbl = df_raw29.copy()
            if search_med:
                view_tbl = view_tbl[view_tbl['nama_obat'].str.contains(search_med, case=False, na=False)]
            st.dataframe(view_tbl, use_container_width=True, height=450)
            st.caption(f"Menampilkan {len(view_tbl)} obat dari total 29 berkas LPLPO asli Puskesmas Teluk Sasah.")

# ==============================================================================
# MODUL 3: PERAMALAN 4 PENYAKIT TERBANYAK (SARAN DOSEN #3)
# ==============================================================================
elif menu == "🩺 Peramalan 4 Penyakit Terbanyak":
    st.markdown("## 🩺 **Peramalan Kasus 4 Penyakit Terbanyak di Puskesmas**")
    st.caption("Implementasi Saran Dosen 3: Memprediksi tren minimal 4 diagnosis penyakit paling sering di UPTD Puskesmas Teluk Sasah.")
    
    if df_dis_fc is not None and df_dis is not None:
        # Metrik 4 Penyakit
        c1, c2, c3, c4 = st.columns(4)
        cards = [
            (c1, "J00: ISPA", df_dis_fc.iloc[0]),
            (c2, "I10: Hipertensi", df_dis_fc.iloc[1]),
            (c3, "K30: Dispepsia", df_dis_fc.iloc[2]),
            (c4, "E11: Diabetes DM", df_dis_fc.iloc[3]),
        ]
        for col, title, r in cards:
            with col:
                st.markdown(f"""
                <div class="med-card">
                    <div style="font-weight:700; color:#0369A1;">{title}</div>
                    <div class="med-card-val">{r['prediksi_kasus_2026_P5']:.0f} Kasus</div>
                    <div class="med-card-lbl">Prediksi 2026-P5 ({r['best_model']})</div>
                    <div style="font-size:0.8rem; color:#059669; margin-top:4px;">MAPE: <b>{r['mape_wma3']:.1f}%</b></div>
                </div>
                """, unsafe_allow_html=True)
                
        st.markdown("---")
        st.markdown("#### **Tabel Evaluasi Model Peramalan Penyakit (SMA vs WMA)**")
        view_dis = df_dis_fc[['kode_icd', 'nama_penyakit_alias', 'total_kasus_historis', 'mean_kasus_bimonthly', 'mape_sma2', 'mape_sma3', 'mape_wma3', 'best_model', 'prediksi_kasus_2026_P5']]
        st.dataframe(view_dis.rename(columns={
            'kode_icd': 'Kode ICD-10',
            'nama_penyakit_alias': 'Diagnosis Penyakit',
            'total_kasus_historis': 'Total Kasus (2023-2026)',
            'mean_kasus_bimonthly': 'Rata-rata/Periode',
            'mape_sma2': 'MAPE SMA-2 (%)',
            'mape_sma3': 'MAPE SMA-3 (%)',
            'mape_wma3': 'MAPE WMA-3 (%)',
            'best_model': 'Model Terbaik',
            'prediksi_kasus_2026_P5': 'Prediksi 2026-P5 (Pasien)'
        }), use_container_width=True)
        
        # Pilihan Grafik Interaktif
        st.markdown("#### **Visualisasi Kurva Deret Waktu Kasus Penyakit (2023–2026-P5)**")
        sel_icd = st.selectbox("Pilih Penyakit untuk Dilihat Kurva Trennya:", df_dis_fc['nama_penyakit_alias'].tolist(), index=0)
        icd_code = df_dis_fc[df_dis_fc['nama_penyakit_alias'] == sel_icd]['kode_icd'].values[0]
        
        period_cols = [c for c in df_pivot.columns if c.startswith(('2023-', '2024-', '2025-', '2026-')) and c.endswith(tuple(f'P{i}' for i in range(1, 7)))]
        s_data = df_dis[df_dis['kode_icd'] == icd_code].groupby('periode_bimonthly_id')['total_pasien'].sum()
        s_vals = [s_data.get(p, 0.0) for p in period_cols]
        target_pred = df_dis_fc[df_dis_fc['kode_icd'] == icd_code]['prediksi_kasus_2026_P5'].values[0]
        
        if HAS_PLOTLY:
            fig_p = go.Figure()
            fig_p.add_trace(go.Scatter(
                x=period_cols, 
                y=s_vals, 
                mode='lines+markers', 
                name='Kurva Kasus Riil Pasien', 
                line=dict(color='#0284C7', width=3, shape='spline', smoothing=1.3),
                fill='tozeroy',
                fillcolor='rgba(2, 132, 199, 0.07)',
                marker=dict(size=6, color='#0369A1')
            ))
            fig_p.add_trace(go.Scatter(x=['2026-P5'], y=[target_pred], mode='markers+text', name='Proyeksi 2026-P5', marker=dict(color='#EF4444', size=14, symbol='star'), text=[f" {target_pred:.0f} Pasien"], textposition="top right"))
            fig_p.update_layout(height=450, xaxis_title="Periode Dwibulanan", yaxis_title="Jumlah Pasien (Jiwa)", hovermode='x unified')
            st.plotly_chart(fig_p, use_container_width=True)

# ==============================================================================
# MODUL 4: KORELASI & PREDIKSI OBAT-PENYAKIT (SARAN DOSEN #3)
# ==============================================================================
elif menu == "🔗 Korelasi & Prediksi Obat-Penyakit":
    st.markdown("## 🔗 **Korelasi Klinis & Prediksi Terpadu Obat-Penyakit**")
    st.caption("Implementasi Saran Dosen 3: Mengaitkan proyeksi lonjakan pasien penyakit dengan kebutuhan obat peresepan.")
    
    if df_corr is not None:
        st.markdown("""
        <div class="alert-card-green">
            <b>🔬 Pendekatan Rasio Dosis Klinis (Prescription/Dose Ratio)</b>:<br>
            Selain meramal deret waktu obat murni, kebutuhan obat dapat diproyeksikan dari perkiraan jumlah pasien:
            <code>Proyeksi Obat = Prediksi Jumlah Pasien (2026-P5) × Rata-rata Butir per Pasien</code>
        </div>
        """, unsafe_allow_html=True)
        
        st.markdown("#### **Tabel Matriks Korelasi Klinis & Rasio Konsumsi per Kasus**")
        view_corr = df_corr[['nama_penyakit', 'nama_obat_standar', 'indikasi_klinis', 'pearson_r', 'spearman_rho', 'rasio_dosis_per_pasien', 'prediksi_kasus_P5', 'prediksi_kebutuhan_obat_P5']]
        st.dataframe(view_corr.rename(columns={
            'nama_penyakit': 'Penyakit Terkait',
            'nama_obat_standar': 'Obat Terapi',
            'indikasi_klinis': 'Indikasi Klinis',
            'pearson_r': 'Korelasi Pearson (r)',
            'spearman_rho': 'Spearman (rho)',
            'rasio_dosis_per_pasien': 'Dosis (Butir/Pasien)',
            'prediksi_kasus_P5': 'Target Kasus P5',
            'prediksi_kebutuhan_obat_P5': 'Proyeksi Kebutuhan Obat (Butir)'
        }), use_container_width=True)
        
        # Heatmap Korelasi
        if HAS_PLOTLY:
            st.markdown("#### **Peta Panas (Heatmap) Korelasi Penyakit vs Obat Terkait**")
            fig_hm = px.imshow(
                df_corr[['pearson_r']].T,
                labels=dict(x="Pasangan Penyakit - Obat", color="Korelasi Pearson"),
                x=df_corr['nama_penyakit'] + " vs " + df_corr['nama_obat_standar'],
                y=['Korelasi (r)'],
                color_continuous_scale='Blues',
                aspect='auto'
            )
            fig_hm.update_layout(height=240, margin=dict(l=0, r=0, t=20, b=0))
            st.plotly_chart(fig_hm, use_container_width=True)
            
        # Kalkulator Simulasi Interaktif Bebas
        st.markdown("---")
        st.markdown("### 🧮 **Kalkulator Simulasi: Proyeksi Kebutuhan Obat Berdasarkan Skenario Kasus**")
        c_sim1, c_sim2 = st.columns([1, 1.2])
        with c_sim1:
            sel_pair = st.selectbox("Pilih Pasangan Penyakit - Obat:", df_corr['nama_penyakit'] + " ➔ " + df_corr['nama_obat_standar'])
            r_sel = df_corr[(df_corr['nama_penyakit'] + " ➔ " + df_corr['nama_obat_standar']) == sel_pair].iloc[0]
            
            sim_kasus = st.slider(f"Simulasi Jumlah Pasien {r_sel['nama_penyakit']} (Jiwa):", min_value=10, max_value=1500, value=int(r_sel['prediksi_kasus_P5']), step=10)
            
        with c_sim2:
            hasil_simulasi = int(sim_kasus * r_sel['rasio_dosis_per_pasien'])
            st.markdown(f"""
            <div class="med-card" style="border-left: 5px solid #0284C7;">
                <h4>Hasil Proyeksi Logistik Cerdas</h4>
                <p>Jika fasilitas kesehatan melayani <b>{sim_kasus} pasien {r_sel['nama_penyakit']}</b>:</p>
                <div class="med-card-val">{hasil_simulasi:,} Butir</div>
                <div class="med-card-lbl">Kebutuhan Estimasi {r_sel['nama_obat_standar']} (Rasio {r_sel['rasio_dosis_per_pasien']} butir/kasus)</div>
            </div>
            """, unsafe_allow_html=True)

# ==============================================================================
# MODUL 5: SIMULASI PERAMALAN OBAT & INDIKATOR MACD (SARAN DOSEN #2)
# ==============================================================================
elif menu == "📈 Simulasi Peramalan & Indikator MACD":
    st.markdown("## 📈 **Simulasi Peramalan Obat & Indikator Momentum MACD**")
    st.caption("Implementasi Saran Dosen 2: Pengujian Moving Average (SMA/WMA) dan indikator MACD untuk mendeteksi arah akselerasi permintaan obat.")
    
    tab_ma, tab_macd = st.tabs(["📊 Peramalan Moving Average (SMA & WMA)", "📉 Analisis Indikator MACD (Momentum)"])
    
    with tab_ma:
        if df_pivot is not None:
            c_ctrl1, c_ctrl2, c_ctrl3 = st.columns([2, 1.5, 1.5])
            with c_ctrl1:
                med_options = ['Parasetamol tablet 500 mg', 'Amoksisilin kaplet 500 mg', 'Ambroksol tablet 30 mg', 'Amlodipin 10 mg tablet', 'Metformin tablet 500 mg', 'Vitamin B-Kompleks tablet']
                avail = [m for m in med_options if m in df_pivot['nama_obat'].values]
                if not avail:
                    avail = df_pivot['nama_obat'].head(10).tolist()
                p_med = st.selectbox("Pilih Obat:", avail, index=0)
            with c_ctrl2:
                p_model = st.radio("Model Peramalan:", ["WMA (k=3, Bobot 1,2,3) ⭐", "SMA (k=3)", "SMA (k=2)"])
            with c_ctrl3:
                p_z = st.select_slider("Tingkat Kepercayaan Safety Stock:", ["90% (Z=1.28)", "95% (Z=1.645) ⭐", "99% (Z=2.33)"], value="95% (Z=1.645) ⭐")
                z_val = 1.645 if "95%" in p_z else (1.28 if "90%" in p_z else 2.33)
                
            period_cols = [c for c in df_pivot.columns if c.startswith(('2023-', '2024-', '2025-', '2026-')) and c.endswith(tuple(f'P{i}' for i in range(1, 7)))]
            row_med = df_pivot[df_pivot['nama_obat'] == p_med].iloc[0]
            actual = [float(row_med[c]) for c in period_cols]
            
            # Hitung prediksi
            pred = [None] * len(actual)
            if "WMA" in p_model:
                k = 3
                w = np.array([1, 2, 3])
                for t in range(k, len(actual)):
                    pred[t] = np.dot(actual[t-k:t], w) / w.sum()
                next_val = np.dot(actual[-k:], w) / w.sum()
            elif "SMA (k=3)" in p_model:
                k = 3
                for t in range(k, len(actual)):
                    pred[t] = np.mean(actual[t-k:t])
                next_val = np.mean(actual[-k:])
            else:
                k = 2
                for t in range(k, len(actual)):
                    pred[t] = np.mean(actual[t-k:t])
                next_val = np.mean(actual[-k:])
                
            # Evaluasi
            e_act = np.array(actual[k:])
            e_prd = np.array(pred[k:])
            v_idx = e_act > 0
            mape_val = np.mean(np.abs((e_act[v_idx] - e_prd[v_idx]) / e_act[v_idx])) * 100 if np.sum(v_idx) > 0 else 0
            mae_val = np.mean(np.abs(e_act - e_prd))
            rmse_val = np.sqrt(np.mean((e_act - e_prd)**2))
            ss_val = z_val * float(row_med['std_pemakaian'])
            
            m1, m2, m3, m4 = st.columns(4)
            m1.metric("Akurasi Galat (MAPE)", f"{mape_val:.2f}%", delta="Sangat Akurat" if mape_val < 20 else "Cukup Akurat")
            m2.metric("Mean Absolute Error (MAE)", f"{mae_val:.1f} butir")
            m3.metric("Root Mean Squared Error", f"{rmse_val:.1f} butir")
            m4.metric("Prediksi 2026-P5", f"{next_val:.0f} butir", delta=f"+ SS: {ss_val:.0f}")
            
            if HAS_PLOTLY:
                fig_fc = go.Figure()
                fig_fc.add_trace(go.Scatter(
                    x=period_cols, 
                    y=actual, 
                    mode='lines+markers',
                    name='Konsumsi Aktual (Kurva Mulus)', 
                    line=dict(color='#0284C7', width=3, shape='spline', smoothing=1.3),
                    fill='tozeroy',
                    fillcolor='rgba(2, 132, 199, 0.06)',
                    marker=dict(size=6, color='#0369A1')
                ))
                fig_fc.add_trace(go.Scatter(
                    x=period_cols[k:], 
                    y=pred[k:], 
                    mode='lines+markers',
                    name=f'Prediksi {p_model.split()[0]} (Kurva Mulus)', 
                    line=dict(color='#10B981', dash='dash', width=2.5, shape='spline', smoothing=1.3),
                    marker=dict(size=5, color='#047857')
                ))
                fig_fc.add_trace(go.Scatter(
                    x=['2026-P5'], 
                    y=[next_val], 
                    mode='markers+text', 
                    name='Proyeksi 2026-P5', 
                    marker=dict(color='#F59E0B', size=14, symbol='star'), 
                    text=[f" {next_val:.0f}"], 
                    textposition="top right"
                ))
                fig_fc.update_layout(height=430, xaxis_title="Periode Dwibulanan", yaxis_title="Kuantitas (Butir)", hovermode='x unified')
                st.plotly_chart(fig_fc, use_container_width=True)

    with tab_macd:
        st.markdown("#### **Indikator MACD (Moving Average Convergence Divergence) pada Logistik Farmasi**")
        st.write("MACD mengukur selisih antara Exponential Moving Average jangka pendek (Fast EMA) dan jangka panjang (Slow EMA) untuk mendeteksi percepatan momentum konsumsi.")
        
        if df_macd is not None and HAS_PLOTLY:
            # Dropdown pilihan obat untuk visualisasi MACD
            sel_med_macd = st.selectbox("Pilih Obat untuk Melihat Kurva Interaktif MACD & Histogram:", df_macd['nama_obat'].tolist(), index=0)
            row_macd_data = df_macd[df_macd['nama_obat'] == sel_med_macd].iloc[0]
            
            # Hitung series EMA Fast, Slow, MACD, Signal
            row_pivot_data = df_pivot[df_pivot['nama_obat'] == sel_med_macd]
            if not row_pivot_data.empty:
                s_med = pd.Series([float(row_pivot_data.iloc[0][p]) for p in period_cols])
                fast_ema = s_med.ewm(span=2, adjust=False).mean()
                slow_ema = s_med.ewm(span=4, adjust=False).mean()
                m_line = fast_ema - slow_ema
                sig_line = m_line.ewm(span=2, adjust=False).mean()
                hist = m_line - sig_line
                
                fig_m_ind = go.Figure()
                fig_m_ind.add_trace(go.Scatter(
                    x=period_cols, y=m_line, mode='lines', name='MACD Line', 
                    line=dict(color='#2563EB', width=2.5, shape='spline', smoothing=1.3)
                ))
                fig_m_ind.add_trace(go.Scatter(
                    x=period_cols, y=sig_line, mode='lines', name='Signal Line', 
                    line=dict(color='#DC2626', width=2, dash='dash', shape='spline', smoothing=1.3)
                ))
                fig_m_ind.add_trace(go.Bar(
                    x=period_cols, y=hist, name='Histogram Momentum',
                    marker_color=['#10B981' if v >= 0 else '#EF4444' for v in hist],
                    opacity=0.6
                ))
                fig_m_ind.update_layout(
                    title=f"Kurva Mulus Indikator MACD & Momentum Histogram: {sel_med_macd}",
                    height=380, xaxis_title="Periode Dwibulanan", yaxis_title="Nilai Indikator",
                    hovermode='x unified'
                )
                st.plotly_chart(fig_m_ind, use_container_width=True)
                
            st.markdown("---")
            st.markdown("##### **Tabel Status Indikator Momentum MACD 96 Obat Esensial**")
            st.dataframe(df_macd[['nama_obat', 'satuan', 'total_pemakaian', 'last_pemakaian_P4', 'macd_value', 'signal_value', 'histogram', 'momentum_status']].rename(columns={
                'nama_obat': 'Nama Obat',
                'satuan': 'Satuan',
                'total_pemakaian': 'Total Pemakaian',
                'last_pemakaian_P4': 'Pemakaian Terakhir (2026-P4)',
                'macd_value': 'Nilai MACD',
                'signal_value': 'Garis Sinyal',
                'histogram': 'Histogram MACD',
                'momentum_status': 'Status Momentum Tren'
            }), use_container_width=True)
            
            st.markdown("""
            > **Kaidah Manajerial Berbasis MACD**:
            > * **Bullish Momentum (Histogram Positif)**: Kebutuhan konsumsi sedang mengakselerasi naik. Apoteker disarankan menambah kuantitas order atau memperkuat buffer Safety Stock.
            > * **Bearish Momentum (Histogram Negatif)**: Kebutuhan konsumsi sedang melambat. Apoteker disarankan memangkas kuantitas order agar tidak terjadi penumpukan obat yang berisiko kadaluarsa.
            """)

# ==============================================================================
# MODUL 6: REKOMENDASI PENGADAAN 2026-P5 (SAFETY STOCK)
# ==============================================================================
elif menu == "📦 Rekomendasi Pengadaan 2026-P5":
    st.markdown("## 📦 **Rekomendasi Alokasi Pengadaan Logistik Obat (2026-P5)**")
    st.caption("Penerapan formula pengadaan presisi bebas overstock dan understock untuk diserahkan ke Dinas Kesehatan.")
    
    st.markdown("""
    <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px;">
        <b>📐 Rumus Alokasi Rekomendasi Pengadaan Presisi</b>:<br>
        <code>Rekomendasi Order = MAX( 0, (Peramalan F_t+1 + Safety Stock 95%) - Sisa Stok Akhir Gudang )</code>
    </div>
    """, unsafe_allow_html=True)
    
    if df_eval is not None and df_pivot is not None and df_ts is not None:
        last_stock = df_ts[df_ts['periode_bimonthly_id'] == '2026-P4'].groupby('nama_obat')['stok_akhir'].last().reset_index()
        rec_df = pd.merge(df_eval[['nama_obat', 'satuan', 'mean_pemakaian', 'cv_pemakaian', 'mape_wma3', 'next_pred_wma3', 'best_model']], last_stock, on='nama_obat', how='inner')
        stds = df_pivot.set_index('nama_obat')['std_pemakaian']
        rec_df['std_pemakaian'] = rec_df['nama_obat'].map(stds).fillna(0)
        rec_df['safety_stock_95'] = (1.645 * rec_df['std_pemakaian']).round().astype(int)
        rec_df['total_kebutuhan'] = (rec_df['next_pred_wma3'] + rec_df['safety_stock_95']).round().astype(int)
        rec_df['rekomendasi_order'] = np.maximum(0, rec_df['total_kebutuhan'] - rec_df['stok_akhir']).round().astype(int)
        
        def assign_status(row):
            if row['rekomendasi_order'] == 0:
                return "🟢 Stok Aman / Berlebih (Order 0)"
            elif row['stok_akhir'] == 0:
                return "🔴 Darurat Stockout (Wajib Order)"
            else:
                return "🟡 Pengadaan Rutin Presisi"
                
        rec_df['status_logistik'] = rec_df.apply(assign_status, axis=1)
        
        col_f1, col_f2 = st.columns([1.5, 1])
        with col_f1:
            filter_status = st.selectbox(
                "Filter Kategori Status Stok:",
                ["Semua Obat", "Hanya yang Perlu Order (> 0)", "Hanya Stok Aman / Berlebih (Order = 0)"]
            )
        with col_f2:
            st.metric("Total Obat Terdaftar", f"{len(rec_df)} Item", delta=f"{len(rec_df[rec_df['rekomendasi_order']>0])} Perlu Order")
            
        if filter_status == "Hanya yang Perlu Order (> 0)":
            display_df = rec_df[rec_df['rekomendasi_order'] > 0]
        elif filter_status == "Hanya Stok Aman / Berlebih (Order = 0)":
            display_df = rec_df[rec_df['rekomendasi_order'] == 0]
        else:
            display_df = rec_df
            
        view_cols = ['nama_obat', 'satuan', 'stok_akhir', 'next_pred_wma3', 'safety_stock_95', 'total_kebutuhan', 'rekomendasi_order', 'status_logistik']
        col_rename = {
            'nama_obat': 'Nama Obat', 'satuan': 'Satuan', 'stok_akhir': 'Sisa Stok Gudang',
            'next_pred_wma3': 'Prediksi WMA-3', 'safety_stock_95': 'Safety Stock (95%)',
            'total_kebutuhan': 'Total Kebutuhan', 'rekomendasi_order': 'Rekomendasi Order',
            'status_logistik': 'Status Logistik'
        }
        
        st.dataframe(display_df[view_cols].rename(columns=col_rename).reset_index(drop=True), use_container_width=True)
        
        csv_data = display_df[view_cols].rename(columns=col_rename).to_csv(index=False).encode('utf-8')
        st.download_button(
            label="📥 Unduh Lembar Rekomendasi Pengadaan Resmi (.CSV)",
            data=csv_data,
            file_name="Rekomendasi_Pengadaan_Obat_Puskesmas_Teluk_Sasah_2026_P5.csv",
            mime="text/csv"
        )

# ==============================================================================
# MODUL 7: TIM PENELITI & BERKAS AKADEMIK
# ==============================================================================
elif menu == "👥 Tim Peneliti (Kelompok 23)":
    st.markdown("## 👥 **Profil Proyek & Tim Peneliti (Kelompok 23)**")
    
    col_t1, col_t2 = st.columns([1.2, 1])
    with col_t1:
        st.markdown("""
        <div class="med-card">
            <h3>🎓 Identitas Penanggung Jawab Proyek</h3>
            <p>
                <b>Nama Mahasiswa</b>: NABIL<br>
                <b>NIM</b>: 2401020073<br>
                <b>Peran</b>: Ketua Tim / Penanggung Jawab Proyek Penambangan Data<br>
                <b>Program Studi</b>: S-1 Teknik Informatika (Semester 5)<br>
                <b>Fakultas</b>: Fakultas Teknik dan Teknologi Kemaritiman<br>
                <b>Perguruan Tinggi</b>: Universitas Maritim Raja Ali Haji (UMRAH), Tanjungpinang
            </p>
            <hr style="border: none; border-top: 1px solid #E2E8F0; margin: 15px 0;">
            <p>
                <b>Dosen Pengampu Mata Kuliah</b>:<br>
                1. Tekad Matulatan, S.T., M.Kom.<br>
                2. Muhamad Fadli, S.T., M.Kom.
            </p>
        </div>
        """, unsafe_allow_html=True)
        
    with col_t2:
        st.markdown("""
        <div class="med-card">
            <h3>📑 Berkas Akademik Resmi</h3>
            <ul style="color: #475569; font-size: 0.95rem; line-height: 1.8;">
                <li><b>Laporan Akhir Lengkap (FinPD)</b>: <code>FinPD_2401020073.docx</code></li>
                <li><b>Proposal Proyek (PropPD)</b>: <code>PropPD_2401020069.pdf</code></li>
                <li><b>Tugas Mandiri Minggu 10 (Klasifikasi)</b>: <code>PD10_2401020073.docx</code></li>
                <li><b>Tugas Mandiri Minggu 11 (Kluster)</b>: <code>PD11_2401020073.docx</code></li>
                <li><b>Tugas Mandiri Minggu 12 (Asosiasi)</b>: <code>PD12_2401020073.docx</code></li>
                <li><b>Tugas Mandiri Minggu 13 (Pengukuran)</b>: <code>PD13_2401020073.docx</code></li>
                <li><b>Aplikasi Web Streamlit</b>: <code>app.py</code></li>
            </ul>
        </div>
        """, unsafe_allow_html=True)

st.markdown("""
<div class="footer-bar">
    <b>PharmaCare Sasah</b> &copy; 2026 Kelompok 23 • Program Studi S-1 Teknik Informatika, Universitas Maritim Raja Ali Haji (UMRAH).<br>
    Dikembangkan untuk Tata Kelola Logistik Farmasi UPTD Puskesmas Teluk Sasah, Kabupaten Bintan.
</div>
""", unsafe_allow_html=True)
