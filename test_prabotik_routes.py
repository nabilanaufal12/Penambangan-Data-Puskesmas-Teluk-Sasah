import sys
import os

base_dir = r"Penambangan-Data-Puskesmas-Teluk-Sasah-main"
sys.path.insert(0, base_dir)
os.chdir(base_dir)

from app import app

client = app.test_client()

routes = [
    ("/", "Dashboard"),
    ("/dashboard?role=admin", "Dashboard Admin"),
    ("/dashboard?role=kepala", "Dashboard Kepala"),
    ("/data", "Data Page"),
    ("/data?tahun=2024", "Data Page 2024"),
    ("/analisis", "Analisis Page"),
    ("/analisis?med=Amlodipin+10+mg+tablet", "Analisis Amlodipin"),
    ("/rekomendasi", "Rekomendasi Page"),
    ("/laporan", "Laporan Page"),
    ("/api/forecast?med=Parasetamol+tablet+500+mg", "API Forecast")
]

print("=== TESTING ALL PRABOTIK ROUTES ===")
all_pass = True
for path, desc in routes:
    res = client.get(path)
    status = res.status_code
    print(f"[{status}] {desc} -> {path}")
    if status != 200:
        all_pass = False
        print(f"ERROR: {res.data[:200]}")

if all_pass:
    print("\n>>> ALL PRABOTIK ROUTES PASSED 100% SUCCESFULLY! <<<")
else:
    print("\n>>> THERE WERE ERRORS IN TESTING! <<<")

